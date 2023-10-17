<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

use App\Helpers\ActivityLogHelper;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::latest()->get();
        $stocks = Stock::latest()->get();

        return view('products.index', compact('products', 'stocks'));
    }

    // SHOW PRODUCT
    public function showProduct(Request $request, Product $product)
    {

        $stocks = Stock::where(['status' => 1])->get();
        $ingredients = $product->ingredients;

        return view('products.show', compact('product', 'ingredients', 'stocks'));
    }

    // POST PRODUCT
    public function postProduct(Request $request)
    {
        $stock = Stock::findOrFail($request->stock_id);

        try {
            $attributes = $request->validate([
                'stock_id' => 'required',
                'name' => 'required',
                'unit' => 'required',
                'volume' => 'required',
                'measure' => 'required',
                'price' => 'required',
                'type' => 'required',
            ]);

            $attributes['status'] = true;

            $product = Product::create($attributes);
            ActivityLogHelper::addToLog('Added product ' . $product->name);

            $stock->product()->save($product);
        } catch (QueryException $th) {
            notify()->error('Product "' . $request->name . '" with quantity of "' . $request->quantity . '" already exists.');
            return back();
        }
        notify()->success('You have successful added a product');

        return Redirect::back();
    }

    // EDIT PRODUCT
    public function putProduct(Request $request, Product $product)
    {
        $stock = Stock::findOrFail($request->stock_id);

        try {
            $attributes = $request->validate([
                'volume' => 'required',
                'unit' => 'required',
                'measure' => 'required',
                'price' => 'required',
                'type' => 'required',
            ]);

            $attributes['stock_id'] = $stock->id;
            $attributes['name'] = $stock->name;

            DB::listen(function ($query) {
                Log::info($query->sql);
                Log::info($query->bindings);
            });

            $product->update($attributes);

            ActivityLogHelper::addToLog('Updated product ' . $product->name);

        } catch (QueryException $th) {

            notify()->error('Product "' . $request->name . '" with volume of "' . $request->quantity . '" already exists.');
            return back();
        }
        notify()->success('You have successful edited an product');

        return redirect()->back();
    }

    // TOGGLE PRODUCT STATUS
    public function toggleStatus(Request $request, Product $product)
    {

        $attributes = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $product->update($attributes);
        ActivityLogHelper::addToLog('Switched product ' . $product->name . ' status ');

        notify()->success('You have successfully updated product status');
        return back();
    }
    public function toggleDiscount(Request $request, Product $product)
    {
// dd($product);
        $attributes = $request->validate([
            'has_discount' => ['required', 'boolean'],
        ]);

        $product->update($attributes);
        ActivityLogHelper::addToLog('Switched product ' . $product->name . ' discount status ');

        notify()->success('You have successfully updated product discount status');
        return back();
    }

    // DELETE PRODUCT
    public function deleteProduct(Product $product)
    {

        $itsName = $product->name;
        $product->delete();
        ActivityLogHelper::addToLog('Deleted product ' . $itsName);

        notify()->success('You have successful deleted ' . $itsName . '.');
        return back();
    }
}
