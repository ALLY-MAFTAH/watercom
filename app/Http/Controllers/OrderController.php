<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

use App\Helpers\ActivityLogHelper;
use App\Models\Item;
use App\Models\Order;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orders = Order::latest()->get();
        $stocks = Stock::latest()->get();

        return view('orders.index', compact('orders', 'stocks'));
    }

    public function showOrder(Request $request, Order $order)
    {

        return view('orders.show', compact('order'));
    }

    public function sendOrder(Request $request)
    {
        $selectedStocks = json_decode($request->selectedStocks, true);

        try {

            $attributes = [
                'number' => Carbon::now(),
                'served_date' => null,
                'status' => false,
                'created_by' => Auth::user()->name,
                'user_id' => Auth::user()->id,
            ];

            $order = Order::create($attributes);
            $user = User::find(Auth::user()->id);
            $user->orders()->save($order);

            foreach ($selectedStocks as $stock) {
                $itemAttributes = [
                    'stock_id' => $stock['stock_id'],
                    'name' => $stock['name'],
                    'quantity' => $stock['quantity'],
                    'volume' => $stock['volume'],
                    'measure' => $stock['measure'],
                    'unit' => $stock['unit'],
                    'order_id' => $order->id,
                ];

                $item = Item::create($itemAttributes);
                $order->items()->save($item);
            }

            $receivers = [
                setting('Admin Email'),
                setting('Afya Email'),

            ];
            foreach ($receivers as $receiver) {

                Mail::send('mails.order', ['order' => $selectedStocks], function ($m) use ($receiver) {
                    $m->from('amelipaapp@gmail.com', setting('App Name', "Tanga Watercom"));

                    $m->to($receiver)->subject('Please, receive new order.');
                });
            }

            ActivityLogHelper::addToLog('Created order. Number: ' . $order->number);

            notify()->success('You have successful sent an order');
            return redirect()->back();
        } catch (QueryException $th) {
            dd($th->getMessage());
            notify()->error($th->getMessage());
            return back()->with('error', 'Failed to create order');
        }
    }
    public function postOrder(Request $request)
    {
        $itemsIds = $request->input('ids');
        $itemsQuantities = $request->input('quantities');

        $selectedStocks = [];
        for ($i = 0; $i < count($itemsIds); $i++) {
            $stock = Stock::findOrFail($itemsIds[$i]);
            $selectedStock = [
                'stock_id' => $stock->id,
                'name' => $stock->name,
                'volume' => $stock->volume,
                'measure' => $stock->measure,
                'unit' => $stock->unit,
                'quantity' => $itemsQuantities[$i],
            ];

            array_push($selectedStocks, $selectedStock);
        }
        return response()->json($selectedStocks);
    }

    public function putOrder(Request $request, Order $order)
    {

        try {
            $attributes = $request->validate([
                'volume' => 'required',
                'unit' => 'required',
                'measure' => 'required',
                'price' => 'required',
            ]);

            $order->update($attributes);
            ActivityLogHelper::addToLog('Updated order ' . $order->name);
        } catch (QueryException $th) {
            notify()->error('Order "' . $request->name . '" with volume of "' . $request->quantity . '" already exists.');
            return back();
        }
        notify()->success('You have successful edited an order');
        return redirect()->back();
    }

    public function toggleStatus(Request $request, Order $order)
    {
        try {

            $attributes = $request->validate([
                'status' => ['required', 'boolean'],
            ]);
            $attributes['served_date'] =  Carbon::now();

            $order->update($attributes);

            $items = Item::where(['order_id' => $order->id])->get();
            foreach ($items as $item) {
                $stock = Stock::findOrFail($item->stock_id);

                $newQuantity = $stock->quantity + $item->quantity;
                $attributes = [
                    'quantity' => $newQuantity
                ];
                $stock->update($attributes);
            }
            ActivityLogHelper::addToLog('Switched order ' . $order->name . ' status ');
        } catch (QueryException $th) {
            notify()->error($th->getMessage());
            return back();
        }
        notify()->success('You have successfully updated order status');
        return back();
    }

    public function deleteOrder(Order $order)
    {

        $itsName = $order->name;
        $order->delete();
        ActivityLogHelper::addToLog('Deleted order ' . $itsName);

        notify()->success('You have successful deleted ' . $itsName . '.');
        return back();
    }
}
