<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogHelper;
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
    public function index()
    {
        $stocks = Stock::orderBy('type','DESC')->get();

        return view('stocks.index', compact('stocks'));
    }
    public function showStock(Request $request, Stock $stock)
    {

        return view('stocks.show', compact('stock'));
    }
    public function postStock(Request $request)
    {
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
            // dd($th);
            notify()->error('Failed to add "' . $request->volume . ' ' . $request->measure . '-' . $request->name . '" to stock. It already exists.');
            return back();
        }
        $request->request->add(['price' => $request->price, 'stock_id' => $stock->id]); //add request

        $product = new ProductController();
        $product->postProduct($request);

        notify()->success('You have successful added ' . $request->quantity . ' ' . $request->unit . ' of ' . $request->name . ' to stock');

        return Redirect::back();
    }
    public function putStock(Request $request, Stock $stock)
    {
        // dd($request->all);
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
        } catch (QueryException $th) {
            notify()->error('Failed to edit stock. "' . $request->name . '" already exists.');
            return back();
        }
        $request->request->add(['price' => $request->price, 'has_discount' => $request->has_discount, 'stock_id' => $stock->id]); //add request

        $product = new ProductController();
        $product->putProduct($request, $stock->product);
        notify()->success('You have successful edited stock');
        return redirect()->back();
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
