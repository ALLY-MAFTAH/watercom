<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BatchItem;
use App\Models\Good;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Routing\Controller;

use App\Helpers\ActivityLogHelper;
use App\Models\Customer;
use App\Models\UnpaidGood;
use App\Models\Product;
use App\Models\UnpaidSale;
use App\Models\Stock;
use App\Services\MessagingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UnpaidSaleController extends Controller
{

    // public function inadex(Request $request)
    // {

    //     $filteredStockName = "";
    //     $filteredDate = Carbon::now('GMT+3')->toDateString();
    //     $selectedStockName = "";
    //     $selectedDate = "";

    //     $filteredStockName = $request->get('filteredStockName', "All Products");
    //     $filteredDate = $request->get('filteredDate', "All Days");

    //     if ($filteredDate == null) {
    //         $filteredDate = "All Days";
    //     }
    //     $filteredStock = Stock::where(['name' => $filteredStockName])->first();

    //     if ($filteredDate != "All Days" && $filteredStockName != "All Products") {
    //         $sales = UnpaidSale::where(['stock_id' => $filteredStock->id, 'date' => $filteredDate])->latest()->paginate(10);
    //     } elseif ($filteredDate == "All Days" && $filteredStockName != "All Products") {
    //         $sales = UnpaidSale::where(['stock_id' => $filteredStock->id])->latest()->paginate(10);
    //     } elseif ($filteredStockName == "All Products" && $filteredDate != "All Days") {
    //         $sales = UnpaidSale::where('date', $filteredDate)->latest()->paginate(10);
    //     } else {
    //         $sales = UnpaidSale::latest()->paginate(10);
    //     }
    //     $selectedStockName = $filteredStockName;
    //     $selectedDate = $filteredDate;

    //     $stocks = Stock::where('status', 1)->where('quantity', '>', 0)->get();
    //     $allStocks = Stock::all();
    //     $products = Product::where(['status' => 1])->get();

    //     return view('cart.index', compact('sales', 'products', 'stocks', 'allStocks', 'filteredDate', 'filteredStockName', 'selectedStockName', 'selectedDate'));
    // }

    public function saveUnpaidProduct(Request $request)
    {
        $dateTime = $request->input('dateTime');

        if (empty($dateTime)) {
            notify()->error('Date and time are required.');
            return back();
        }
        $parsedDateTime = Carbon::parse($dateTime);
        $carts = session()->get('cart');
        $purchases = [];

        if ($carts==[]||$carts==null) {

        notify()->success('The cart is empty, please add product');
        return Redirect::back();
        }

        foreach ($carts as $cart) {
            $product = Product::findOrFail($cart['id']);
            $quantity = $cart['quantity'];

            $stock = Stock::findOrFail($product->stock_id);
            $currentStock = $this->calculateCurrentStock();

            if ($quantity > $currentStock[$stock->id]) {
                notify()->error("Sorry! You can't sell " . $quantity . " " . $product->unit . " of " . $product->name . ' - ' . $product->volume . ' ' . $product->measure . ". Quantity remained is " . $currentStock[$stock->id] . " " . $product->unit);
                return back();
            }
        }

        try {
            $totalAmount = 0;
            foreach ($carts as $cart) {
                $totalAmount = $totalAmount + $cart['price'] * $quantity;
            }
            $attribute = [
                'user_id' => Auth::user()->id,
                'seller' => Auth::user()->name,
                'customer_id' => 0,
                'receipt_number' => $request->receipt_number ?? "",
                'amount_paid' => $totalAmount,
                'date' => $parsedDateTime,
                'status' => 0,
            ];
            $good = UnpaidGood::create($attribute);
            foreach ($carts as $cart) {
                $product = Product::findOrFail($cart['id']);
                $quantity = $cart['quantity'];

                $stock = Stock::findOrFail($product->stock_id);

                $attributes = [
                    'name' => $product->name,
                    'type' => $product->type,
                    'volume' => $product->volume,
                    'measure' => $product->measure,
                    'unit_price' => $cart['price'],
                    'price' => $cart['price'] * $quantity,
                    'quantity' => $quantity,
                    'unit' => $product->unit,
                    'category' => $cart['category'],
                    'seller' => Auth::user()->name,
                    'product_id' => $product->id,
                    'user_id' => Auth::user()->id,
                    'date' => $parsedDateTime,
                    'stock_id' => $product->stock_id,
                    'unpaid_good_id' => $good->id,
                    'status' => 0,
                ];
                $sale = UnpaidSale::create($attributes);
                $good->unpaidPurchases()->save($sale);

                $purchases[] = $sale;

                $newQuantity = $stock->quantity - $quantity;
                $stock->update([
                    'quantity' => $newQuantity,
                ]);
                $stock->save();
            }
             // BATCH OUT

             $attributes = [
                'date' => $parsedDateTime,
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
                $customer->unpaidGoods()->save($good);

                // MESSAGE CONTENT
                $heading  =  "Ndugu ".$customer->name.",\nUmechukua bidhaa zifuatazo kutoka kwetu\n";
                $boughtUnpaidGoods = [];
                foreach ($purchases as $key => $purchasedUnpaidGood) {
                    $boughtUnpaidGoods[] = ++$key . ". " . $purchasedUnpaidGood->name . " " . $purchasedUnpaidGood->volume . " " . $purchasedUnpaidGood->measure . " - " . $purchasedUnpaidGood->quantity . " " . $purchasedUnpaidGood->unit . "\n";
                }
                $totalCost = "Zinazogharimu Jumla ya Tsh " . number_format($totalAmount, 0, '.', ',') . ". Ambazo hazijalipwa.\n";
                $closing  = "Ahsante na karibu tena.";
                $messageBody = $heading . implode('', $boughtUnpaidGoods) . $totalCost . $closing;

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
            session()->forget('cart');
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            notify()->error($th->getMessage());
            return back();
        }

        notify()->success('UnpaidSales recorded successfully');
        return Redirect::back();
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
    public function verifyPayment(Request $request, UnpaidGood $unpaidGood)
    {
        // dd($unpaidGood->status);
        $carts = $unpaidGood->unpaidPurchases;

        $purchases = [];

        $newQuantities = $request->input('new_quantity');

        try {
            $totalAmount = 0;
            foreach ($carts as $cart) {
                $newQuantity = $newQuantities[$cart['id']];
                $totalAmount = $totalAmount +  $cart['unit_price'] * $newQuantity;
            }

            $attribute = [
                'user_id' => Auth::user()->id,
                'seller' => Auth::user()->name,
                'customer_id' => $request->customer_id??0,
                'receipt_number' => $request->receipt_number ?? "",
                'amount_paid' => $totalAmount,
                'date' => $unpaidGood->date,
            ];
            $good = Good::create($attribute);
            foreach ($carts as $cart) {
                $product = Product::findOrFail($cart['product_id']);

                $newQuantity = $newQuantities[$cart['id']];

                $stock = Stock::findOrFail($product->stock_id);

                $attributes = [
                    'name' => $product->name,
                    'type' => $product->type,
                    'volume' => $product->volume,
                    'measure' => $product->measure,
                    'price' => $cart['unit_price'] * $newQuantity,
                    'quantity' => $newQuantity,
                    'unit' => $product->unit,
                    'category' =>  $cart['category']??"",
                    'seller' => Auth::user()->name,
                    'product_id' => $product->id,
                    'user_id' => Auth::user()->id,
                    'date' => $unpaidGood->date,
                    'stock_id' => $product->stock_id,
                    'good_id' => $good->id,
                    'status' => 1,
                ];
                $sale = Sale::create($attributes);
                $stock->sales()->save($sale);
                $good->purchases()->save($sale);
                $purchases[] = $sale;

                if ($newQuantity < $cart['quantity']) {
                    $returnedQuantity = $cart['quantity'] - $newQuantity;
                    // BATCH IN
            $attributes = [
                'date' => $unpaidGood->date,
                'status' => true,
                'type' => "IN",
                'user_id' => Auth::user()->id,
            ];

            $batch = Batch::create($attributes);
            $user = User::find(Auth::user()->id);
            $user->batches()->save($batch);

                $batchItemAttributes = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $returnedQuantity,
                    'volume' => $product->volume,
                    'measure' => $product->measure,
                    'unit' => $product->unit,
                    'type' => $product->type,
                    'status' => true,
                    'batch_id' => $batch->id,
                ];
                $batchItem = BatchItem::create($batchItemAttributes);
                $batch->batchItems()->save($batchItem);


            ActivityLogHelper::addToLog('Created batch. Date: ' . $batch->date);
                }
            }



                       if ($request->customer_id != null) {
                $customer = Customer::findOrFail($request->customer_id);
                // $atr = ['customer_id' => $customer->id];
                // $good->update($atr);
                // $customer->goods()->save($good);

                // MESSAGE CONTENT
                $heading  =  "Ndugu ".$customer->name.",\nUmelipia bidhaa zifuatazo kutoka kwetu\n";
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
            $unpaidGood->update([
                'status' => 1,
            ]);
            $unpaidGood->save();

            ActivityLogHelper::addToLog('Successful sold products.');
            session()->forget('cart');
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            notify()->error($th->getMessage());
            return back();
        }

        notify()->success('Sales recorded successfully');
        return Redirect::back();
    }




    public function allUnpaidSales(Request $request)
    {
        $filteredStockId = $request->get('filteredStockId', "All Products");
        $filteredDate = $request->get('filteredDate', "All Days");

        if ($filteredDate == null) {
            $filteredDate = "All Days";
        }

        $sales = UnpaidSale::latest()->get();
        if ($filteredDate != "All Days" && $filteredStockId != "All Products") {
            $sales = UnpaidSale::where(['stock_id' => $filteredStockId, 'date' => $filteredDate])->latest()->get();
        } elseif ($filteredStockId != "All Products") {
            $sales = UnpaidSale::where(['stock_id' => $filteredStockId])->latest()->get();
        } elseif ($filteredDate != "All Days") {
            $sales = UnpaidSale::where('date', $filteredDate)->latest()->get();
        }

        $boughtUnpaidGoods = UnpaidGood::latest()->get();
        if ($filteredDate != "All Days") {
            $boughtUnpaidGoods = UnpaidGood::where('date', $filteredDate)->latest()->get();
        }

        $stocks = Stock::where('status', 1)->where('quantity', '>', 0)->get();
        $allStocks = Stock::all();
        $products = Product::where(['status' => 1])->get();

        // $selectedStockName = $filteredStockName;
        $selectedDate = $filteredDate;
        return view('unpaid_sales.index', compact(
            'sales',
            'stocks',
            'allStocks',
            'products',
            'filteredDate',
            'filteredStockId',
            // 'selectedStockName',
            'selectedDate',
            'boughtUnpaidGoods'
        ));
    }



    public function discardUnpaidGood(UnpaidGood $unpaidGood)
    {
        // dd($unpaidGood->id);
        $carts = $unpaidGood->unpaidPurchases;

        try {
            foreach ($carts as $cart) {
                $product = Product::findOrFail($cart['product_id']);
                $stock = Stock::findOrFail($product->stock_id);
            $returnedQuantity = $cart['quantity'];
                    $newStockQty = $stock->quantity + $returnedQuantity;
                    $stock->update([
                        'quantity' => $newStockQty,
                    ]);
                    $stock->save();
                    $itsName = $unpaidGood->id;
                    $unpaidGood->delete();
                }
            ActivityLogHelper::addToLog('Discarded unpaid transaction ' . $itsName);

            notify()->success('You have successful deleted unpaid transaction.');
            return back();
        } catch (\Throwable $th) {
            dd($th->getMessage());
            notify()->success('You have successful deleted unpaid transaction.');
            return back();
        }
    }
    public function deleteUnpaidGood(UnpaidGood $unpaidGood)
    {
        // dd($unpaidGood->id);
        try {

            $itsName = $unpaidGood->id;
            $unpaidGood->delete();
            ActivityLogHelper::addToLog('Deleted unpaid transaction ' . $itsName);

            notify()->success('You have successful deleted unpaid transaction.');
            return back();
        } catch (\Throwable $th) {
            dd($th->getMessage());
            notify()->success('You have successful deleted unpaid transaction.');
            return back();
        }
    }
}
