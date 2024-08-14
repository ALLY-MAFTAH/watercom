<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogHelper;
use App\Models\Batch;
use App\Models\BatchItem;
use App\Models\Stock;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{


    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function productsIndex()
    {
        $stocks = Stock::orderBy('type','DESC')->get();

        return view('old_stocks.index', compact('stocks'));
    }
    public function index(Request $request)
    {
        // Get the current date and time if not provided
        $date = $request->input('date', now()->format('Y-m-d'));
        $time = $request->input('time', now()->format('H:i'));
        $dateTime = $date . ' ' . $time;

        // Fetch batches and stocks
        $batches = Batch::latest()->get();
        $stocks = Stock::orderBy("name")->get();

        // Calculate current stock
        $currentStock = $this->calculateCurrentStock();

        // Calculate stock for the specific date and time
        $stockForDateTime = $this->calculateStockForDateTime($dateTime);

        return view('stocks.index', compact('batches', 'stocks', 'currentStock', 'stockForDateTime'));
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

    private function calculateStockForDateTime($dateTime)
    {
        // Fetch all batch items up to the specified date and time
        $batchItems = BatchItem::whereHas('batch', function ($query) use ($dateTime) {
            $query->where('date', '<=', $dateTime);
        })->with('batch', 'product')->get();

        // Initialize an empty array to store the stock for the specified date and time
        $stock = [];

        foreach ($batchItems as $item) {
            $productId = $item->product_id;
            $quantity = $item->quantity;

            // Determine if the batch is an addition or reduction in stock
            if ($item->batch->type == 'IN') {
                if (!isset($stock[$productId])) {
                    $stock[$productId] = 0;
                }
                $stock[$productId] += $quantity;
            } elseif ($item->batch->type == 'OUT') {
                if (!isset($stock[$productId])) {
                    $stock[$productId] = 0;
                }
                $stock[$productId] -= $quantity;
            }
        }

        return $stock;
    }

    public function showStock(Request $request, Stock $stock)
    {

        return view('stocks.show', compact('stock'));
    }
    public function postStock(Request $request)
    {
        // dd($request->all());
        try {
            $attributes = $request->validate([
                'name' => 'required',
                'volume' => 'required',
                'unit' => 'required',
                'type' => 'required',
                'measure' => 'required',
                'cost' => 'required',
                'quantity' => 'required',
            ]);

            $attributes['status'] = true;
            $stock = Stock::create($attributes);
            ActivityLogHelper::addToLog(Auth::user()->name . ' Added ' . $stock->name . ' to stock ');
        } catch (QueryException $th) {
            dd($th);
            notify()->error('Failed to add "' . $request->volume . ' ' . $request->measure . '-' . $request->name . '" to stock. It already exists.');
            return back();
        }
        $request->request->add(['price' => $request->price,'special_price' => $request->special_price, 'stock_id' => $stock->id]); //add request

        $product = new ProductController();
        $product->postProduct($request);

        notify()->success('You have successful added ' . $request->quantity . ' ' . $request->unit . ' of ' . $request->name . ' to stock');

        return Redirect::back();
    }
    public function putStock(Request $request, Stock $stock)
    {
        try {
            $attributes = $request->validate([
                'name' => 'required',
                'volume' => 'required',
                'unit' => 'required',
                'measure' => 'required',
                'type' => 'required',
                'cost' => 'required',
                'quantity' => 'required',
            ]);

            $stock->update($attributes);
            ActivityLogHelper::addToLog(Auth::user()->name . ' Updated ' . $stock->name . ' in stock ');
            $request->request->add([ 'has_discount' => $request->has_discount, 'stock_id' => $stock->id]); //add request

            $productController = new ProductController();
           $productController->putProduct($request, $stock->product);
            // dd($result);
            // dd($stock->product->price);
            // $stock->product->save();
            notify()->success('You have successful edited stock');
            return redirect()->back();
        } catch (QueryException $th) {
            dd($th->getMessage());
            notify()->error('Failed to edit stock. "' . $request->name . '" already exists.');
            return back();
        }
    }
    public function toggleStatus(Request $request, Stock $stock)
    {

        $attributes = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $stock->update($attributes);
        ActivityLogHelper::addToLog('Switched stock ' . $stock->name . ' status ');

        notify()->success('You have successfully updated stock status');
        return back();
    }
    public function deleteStock(Stock $stock)
    {

        $itsName = $stock->name;
        $stock->delete();
        ActivityLogHelper::addToLog('Deleted stock ' . $itsName);

        $product = new ProductController();
        $product->deleteProduct($stock->product);

        notify()->success('You have successful deleted ' . $itsName . '.');
        return back();
    }
}
