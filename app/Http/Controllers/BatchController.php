<?php

namespace App\Http\Controllers;

use App\Models\BatchItem;
use App\Services\MessagingService;
use Illuminate\Routing\Controller;

use App\Helpers\ActivityLogHelper;
use App\Models\Item;
use App\Models\Batch;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class BatchController extends Controller
{
    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $batches = Batch::latest()->get();
        $stocks = Stock::orderBy("name")->get();

        return view('batches.index', compact('batches', 'stocks'));
    }

    public function showBatch(Request $request, Batch $batch)
    {

        return view('batches.show', compact('batch'));
    }

    public function saveBatch(Request $request)
    {
        $selectedStocks = json_decode($request->selectedStocks, true);

        try {

            $attributes = [
                'date' => Carbon::now(),
                'status' => true,
                'type' => "IN",
                'user_id' => Auth::user()->id,
            ];

            $batch = Batch::create($attributes);
            $user = User::find(Auth::user()->id);
            $user->batches()->save($batch);

            foreach ($selectedStocks as $stock) {
                $batchItemAttributes = [
                    'product_id' => $stock['product_id'],
                    'name' => $stock['name'],
                    'quantity' => $stock['quantity'],
                    'volume' => $stock['volume'],
                    'measure' => $stock['measure'],
                    'unit' => $stock['unit'],
                    'type' => $stock['type'],
                    'status' => true,
                    'batch_id' => $batch->id,
                ];
                $batchItem = BatchItem::create($batchItemAttributes);
                $batch->batchItems()->save($batchItem);
            }

            ActivityLogHelper::addToLog('Created batch. Date: ' . $batch->date);

            notify()->success('You have successful save a batch');
            return redirect()->back();
        } catch (QueryException $th) {
            // dd($th->getMessage());
            notify()->error($th->getMessage());
            return back()->with('error', 'Failed to create batch');
        }
    }
    public function postBatch(Request $request)
    {
        $itemsIds = $request->input('ids');
        $itemsQuantities = $request->input('quantities');

        $selectedStocks = [];
        for ($i = 0; $i < count($itemsIds); $i++) {
            $stock = Stock::findOrFail($itemsIds[$i]);
            $selectedStock = [
                'product_id' => $stock->product->id,
                'name' => $stock->name,
                'volume' => $stock->volume,
                'measure' => $stock->measure,
                'unit' => $stock->unit,
                'type' => $stock->type,
                'quantity' => $itemsQuantities[$i],
            ];

            array_push($selectedStocks, $selectedStock);
        }
        return response()->json($selectedStocks);
    }

    public function putBatch(Request $request, Batch $batch)
    {

        try {
            $attributes = $request->validate([
                'volume' => 'required',
                'unit' => 'required',
                'measure' => 'required',
                'price' => 'required',
            ]);

            $batch->update($attributes);
            ActivityLogHelper::addToLog('Updated batch ' . $batch->name);
        } catch (QueryException $th) {
            notify()->error('Batch "' . $request->name . '" with volume of "' . $request->quantity . '" already exists.');
            return back();
        }
        notify()->success('You have successful edited an batch');
        return redirect()->back();
    }

    public function toggleStatus(Request $request, Batch $batch)
    {
        try {

            $attributes = $request->validate([
                'status' => ['required', 'boolean'],
            ]);
            $attributes['served_date'] =  Carbon::now();

            $batch->update($attributes);

            $items = Item::where(['order_id' => $batch->id])->get();
            foreach ($items as $item) {
                $stock = Stock::findOrFail($item->stock_id);

                $newQuantity = $stock->quantity + $item->quantity;
                $attributes = [
                    'quantity' => $newQuantity
                ];
                $stock->update($attributes);
            }
            ActivityLogHelper::addToLog('Switched batch ' . $batch->name . ' status ');
        } catch (QueryException $th) {
            notify()->error($th->getMessage());
            return back();
        }
        notify()->success('You have successfully updated batch status');
        return back();
    }

    public function deleteBatch(Batch $batch)
    {

        $itsName = $batch->name;
        $batch->delete();
        ActivityLogHelper::addToLog('Deleted batch ' . $itsName);

        notify()->success('You have successful deleted ' . $itsName . '.');
        return back();
    }
}
