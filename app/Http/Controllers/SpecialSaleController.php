<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BatchItem;
use App\Models\User;
use Illuminate\Routing\Controller;

use App\Helpers\ActivityLogHelper;
use App\Models\Customer;
use App\Models\Good;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Stock;
use App\Services\MessagingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class SpecialSaleController extends Controller
{

    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function specialIndex(Request $request)
    {

        $filteredStockName = "";
        $filteredDate = Carbon::now('GMT+3')->toDateString();
        $selectedStockName = "";
        $selectedDate = "";

        $filteredStockName = $request->get('filteredStockName', "All Products");
        $filteredDate = $request->get('filteredDate', "All Days");

        if ($filteredDate == null) {
            $filteredDate = "All Days";
        }
        $filteredStock = Stock::where(['name' => $filteredStockName])->first();

        if ($filteredDate != "All Days" && $filteredStockName != "All Products") {
            $sales = Sale::where(['stock_id' => $filteredStock->id, 'date' => $filteredDate])->latest()->paginate(10);
        } elseif ($filteredDate == "All Days" && $filteredStockName != "All Products") {
            $sales = Sale::where(['stock_id' => $filteredStock->id])->latest()->paginate(10);
        } elseif ($filteredStockName == "All Products" && $filteredDate != "All Days") {
            $sales = Sale::where('date', $filteredDate)->latest()->paginate(10);
        } else {
            $sales = Sale::latest()->paginate(10);
        }
        $selectedStockName = $filteredStockName;
        $selectedDate = $filteredDate;

        $activeStocks = Stock::where('status', 1)->orderBy('unit','desc')->orderBy('volume','asc')->get();
        $stocks = [];
        $currentStock = $this->calculateCurrentStock();

        foreach ($activeStocks as $stock) {
            // Check if the stock ID exists in the current stock array
            if (isset($currentStock[$stock->id]) && $currentStock[$stock->id] >0) {
                $stocks[] = $stock;
            }
        }

        $allStocks = Stock::all();
        $products = Product::where(['status' => 1])->get();
        $currentStock = $this->calculateCurrentStock();

        return view('cart.special_index', compact('sales', 'products', 'stocks','currentStock', 'allStocks', 'filteredDate', 'filteredStockName', 'selectedStockName', 'selectedDate'));
    }
    private function calculateCurrentStock()
{
    // Fetch all batch items with their respective product and batch details
    $batchItems = BatchItem::with('batch', 'product')->get();

    // Initialize an empty array to store the current stock
    $currentStock = [];

    foreach ($batchItems as $item) {
        $productId = $item->product_id;
        $quantity = $item->quantity;

        // Determine if the batch is an addition or reduction in stock
        if ($item->batch->type == 'IN') {
            if (!isset($currentStock[$productId])) {
                $currentStock[$productId] = 0;
            }
            $currentStock[$productId] += $quantity;
        } elseif ($item->batch->type == 'OUT') {
            if (!isset($currentStock[$productId])) {
                $currentStock[$productId] = 0;
            }
            $currentStock[$productId] -= $quantity;
        }
    }

    return $currentStock;
}

    // SELL PRODUCT
    public function specialSaleProduct(Request $request)
    {
        // dd(setting('App Name'));
        $carts = session()->get('special_cart');
        $purchases = [];

        foreach ($carts as $cart) {
            $product = Product::findOrFail($cart['id']);
            $quantity = $cart['quantity'];
            $currentStock = $this->calculateCurrentStock();

            $stock = Stock::findOrFail($product->stock_id);
            if ($quantity > $currentStock[$stock->id]) {
                notify()->error("Sorry! You can't sell " . $quantity . " " . $product->unit . " of " . $product->name . ' - ' . $product->volume . ' ' . $product->measure . ". Quantity remained is " . $currentStock[$stock->id] . " " . $product->unit);
                return back();
            }
        }

        try {
            foreach ($carts as $cart) {
                $product = Product::findOrFail($cart['id']);
                $quantity = $cart['quantity'];

                $stock = Stock::findOrFail($product->stock_id);

                $attributes = [
                    'name' => $product->name,
                    'type' => $product->type,
                    'volume' => $product->volume,
                    'measure' => $product->measure,
                    'price' => $cart['special_price'] * $quantity,
                    'quantity' => $quantity,
                    'unit' => $product->unit,
                    'seller' => Auth::user()->name,
                    'product_id' => $product->id,
                    'user_id' => Auth::user()->id,
                    'date' => Carbon::now(),
                    'stock_id' => $product->stock_id,
                    'good_id' => 0,
                    'status' => true,
                ];
                $sale = Sale::create($attributes);
                $stock->sales()->save($sale);
                $purchases[] = $sale;

                $newQuantity = $stock->quantity - $quantity;
                $stock->update([
                    'quantity' => $newQuantity,
                ]);
                $stock->save();
            }
            $totalAmount = 0;
            foreach ($purchases as $purchase) {
                $totalAmount = $totalAmount + $purchase->price;
            }
            $attribute = [
                'user_id' => Auth::user()->id,
                'seller' => Auth::user()->name,
                'customer_id' => 0,
                'receipt_number' => $request->receipt_number ?? "",
                'amount_paid' => $totalAmount,
                'date' => Carbon::now(),
            ];
            $good = Good::create($attribute);
            $at = ['good_id' => $good->id];
            foreach ($purchases as $purchase) {
                $purchase->update($at);
                $good->purchases()->save($purchase);
            }

             // BATCH OUT

             $attributes = [
                'date' => Carbon::now(),
                'status' => true,
                'type' => "OUT",
                'user_id' => Auth::user()->id,
            ];

            $batch = Batch::create($attributes);
            $user = User::find(Auth::user()->id);
            $user->batches()->save($batch);

            foreach ($purchases as $purchase) {
                $batchItemAttributes = [
                    'product_id' => $purchase->product_id,
                    'name' => $purchase->name,
                    'quantity' => $purchase->quantity,
                    'volume' => $purchase->volume,
                    'measure' => $purchase->measure,
                    'unit' => $purchase->unit,
                    'type' => $purchase->type,
                    'status' => true,
                    'batch_id' => $batch->id,
                ];
                $batchItem = BatchItem::create($batchItemAttributes);
                $batch->batchItems()->save($batchItem);
            }

            ActivityLogHelper::addToLog('Created batch. Date: ' . $batch->date);


            if ($request->customer_id != null) {
                $customer = Customer::findOrFail($request->customer_id);
                $atr = ['customer_id' => $customer->id];
                $good->update($atr);
                $customer->goods()->save($good);

                // MESSAGE CONTENT
                $heading  = "Ndugu mteja,\nUmenunua bidhaa zifuatazo kutoka kwetu\n";
                $boughtGoods = [];
                foreach ($purchases as $key => $purchasedGood) {
                    $boughtGoods[] = ++$key . ". " . $purchasedGood->name . " " . $purchasedGood->volume . " " . $purchasedGood->measure . " - " . $purchasedGood->quantity . " " . $purchasedGood->unit . "\n";
                }
                $totalCost = "Zinazogharimu Jumla ya Tsh " . number_format($totalAmount, 0, '.', ',') . ".\n";
                $closing  = "Ahsante na karibu tena.";
                $messageBody = $heading . implode('', $boughtGoods) . $totalCost . $closing;

                $messagingService = new MessagingService();
                $sendMessageResponse = $messagingService->sendMessage($customer->phone, $messageBody);

                if ($sendMessageResponse == "Sent") {
                    ActivityLogHelper::addToLog('Sent sms to customer. Number: ' . $customer->phone);
                    notify()->success('Message successful sent.');
                } else {
                    notify()->error('Message not sent, crosscheck your inputs');
                }
            }

            ActivityLogHelper::addToLog('Successful sold products.');
            session()->forget('special_cart');
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            notify()->error($th->getMessage());
            return back();
        }

        notify()->success('Sales recorded successfully');
        return Redirect::back();
    }

    public function specialCheckCart()
    {
        if (session()->has('special_cart')) {
            $cartData = session()->get('special_cart');
        }
        return response()->json(['cartData' => $cartData]);
    }
    public function specialGetCartData()
    {
        $cart = session()->get('special_cart', []);
        $customers = Customer::where('is_special',1)-> orderBy('name', 'ASC')->get();
        return response()->json(['cart' => $cart, 'customers' => $customers]);
    }

    public function specialAddToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('special_cart', []);

        // print($cart[$id]['quantity']);
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
            if ($cart[$id]['quantity'] >= setting('Discount Limit Quantity', 10) && $product->has_discount) {
                $cart[$id]['special_price'] = $product->special_price - setting('Discount Amount', 200);
            } else {
                $cart[$id]['special_price'] = $product->special_price;
            }
            session()->put('special_cart', $cart);
        } else {
            $cart[$id] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "special_price" => $product->special_price,
                "volume" => $product->volume,
                "measure" => $product->measure,
            ];
            session()->put('special_cart', $cart);
        }
        return response()->json(['count' => count($cart)], 200);
    }


    public function specialUpdate(Request $request)
    {
        $product = Product::findOrFail($request->id);

        if ($request->id && $request->quantity) {
            $cart = session()->get('special_cart');
            $cart[$request->id]["quantity"] = $request->quantity;

            // if ($cart[$request->id]["quantity"] >= setting('Discount Limit Quantity', 10) && $product->has_discount) {
            //     $cart[$request->id]["special_price"] = $product->special_price - setting('Discount Amount', 200);
            // } else {
                $cart[$request->id]["special_price"] =  $product->special_price;
            // }
            session()->put('special_cart', $cart);
        }
        $total = 0;
        foreach ($cart as $item) {

            $total += $item['quantity'] * $item['special_price'];
        }

        return response()->json(['success' => 'Cart quantity updated', 'newPrice' => $cart[$request->id]["special_price"], 'total' => number_format($total, 0, '.', ',')], 200);
    }

    public function specialRemove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('special_cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('special_cart', $cart);
            }
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['quantity'] * $item['special_price'];
            }
            return response()->json(['total' => number_format($total, 0, '.', ','), 'count' => count($cart), 'success' => 'Product successfull removed from cart'], 200);
        }
    }

    public function specialEmpty()
    {
        // Empty the cart
        session()->forget('special_cart');

        session()->flash('success', 'Cart emptied successfully');
        return back();
    }

    public function specialAllSales(Request $request)
    {
        $filteredStockId = $request->get('filteredStockId', "All Products");
        $filteredDate = $request->get('filteredDate', "All Days");

        if ($filteredDate == null) {
            $filteredDate = "All Days";
        }

        $sales = Sale::latest()->get();
        if ($filteredDate != "All Days" && $filteredStockId != "All Products") {
            $sales = Sale::where(['stock_id' => $filteredStockId, 'date' => $filteredDate])->latest()->get();
        } elseif ($filteredStockId != "All Products") {
            $sales = Sale::where(['stock_id' => $filteredStockId])->latest()->get();
        } elseif ($filteredDate != "All Days") {
            $sales = Sale::where('date', $filteredDate)->latest()->get();
        }

        $boughtGoods = Good::latest()->get();
        if ($filteredDate != "All Days") {
            $boughtGoods = Good::where('date', $filteredDate)->latest()->get();
        }

        $stocks = Stock::where('status', 1)->where('quantity', '>', 0)->get();
        $allStocks = Stock::all();
        $products = Product::where(['status' => 1])->get();

        // $selectedStockName = $filteredStockName;
        $selectedDate = $filteredDate;
        return view('sales.special_index', compact(
            'sales',
            'stocks',
            'allStocks',
            'products',
            'filteredDate',
            'filteredStockId',
            // 'selectedStockName',
            'selectedDate',
            'boughtGoods'
        ));
    }
}
