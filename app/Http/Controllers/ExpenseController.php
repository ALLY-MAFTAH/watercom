<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

use App\Helpers\ActivityLogHelper;
use App\Models\Item;
use App\Models\Expense;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class ExpenseController extends Controller
{
    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $expenses = Expense::latest()->get();
        $stocks = Stock::latest()->get();

        return view('expenses.index', compact('expenses', 'stocks'));
    }

    public function showExpense(Request $request, Expense $expense)
    {

        return view('expenses.show', compact('expense'));
    }

    public function sendExpense(Request $request)
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

            $expense = Expense::create($attributes);
            $user = User::find(Auth::user()->id);
            $user->expenses()->save($expense);

            foreach ($selectedStocks as $stock) {
                $itemAttributes = [
                    'stock_id' => $stock['stock_id'],
                    'name' => $stock['name'],
                    'quantity' => $stock['quantity'],
                    'volume' => $stock['volume'],
                    'measure' => $stock['measure'],
                    'unit' => $stock['unit'],
                    'expense_id' => $expense->id,
                ];

                $item = Item::create($itemAttributes);
                $expense->items()->save($item);
            }

            $receivers = [
                setting('Admin Email'),
                setting('Afya Email'),

            ];
            foreach ($receivers as $receiver) {

                Mail::send('mails.expense', ['expense' => $selectedStocks], function ($m) use ($receiver) {
                    $m->from('amelipaapp@gmail.com', setting('App Name', "Tanga Watercom"));

                    $m->to($receiver)->subject('Please, receive new expense.');
                });
            }

            ActivityLogHelper::addToLog('Created expense. Number: ' . $expense->number);

            notify()->success('You have successful sent an expense');
            return redirect()->back();
        } catch (QueryException $th) {
            dd($th->getMessage());
            notify()->error($th->getMessage());
            return back()->with('error', 'Failed to create expense');
        }
    }
    public function postExpense(Request $request)
    {
        try {
            $attributes=$request->validate([
                'title'=> 'required',
                'amount'=> 'required',
            ]);
            $attributes['user_id']=Auth::user()->id;
            $attributes['description']=$request->input('description')??"";
            $attributes['status']=false;
            // dd($attributes);
            $expense = Expense::create($attributes);
            ActivityLogHelper::addToLog('Added expense ' . $expense->title);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            notify()->error('Errr: ' . $th->getMessage());
            return back();
        }
        notify()->success('You have successful added an expense');
        return redirect()->back();
    }

    public function putExpense(Request $request, Expense $expense)
    {

        try {
            $attributes = $request->validate([
                'title' => 'required',
                'amount' => 'required',
            ]);
            $attributes['description']= $request->input('description')??'';

            $expense->update($attributes);
            ActivityLogHelper::addToLog('Updated expense ' . $expense->name);
        } catch (QueryException $th) {
            dd($th->getMessage());
            notify()->error('Errors: ' . $th->getMessage());
            return back();
        }
        notify()->success('You have successful edited an expense');
        return redirect()->back();
    }

    public function toggleStatus(Request $request, Expense $expense)
    {
        try {

            $attributes = $request->validate([
                'status' => ['required', 'boolean'],
            ]);
            $attributes['served_date'] =  Carbon::now();

            $expense->update($attributes);

            $items = Item::where(['expense_id' => $expense->id])->get();
            foreach ($items as $item) {
                $stock = Stock::findOrFail($item->stock_id);

                $newQuantity = $stock->quantity + $item->quantity;
                $attributes = [
                    'quantity' => $newQuantity
                ];
                $stock->update($attributes);
            }
            ActivityLogHelper::addToLog('Switched expense ' . $expense->name . ' status ');
        } catch (QueryException $th) {
            notify()->error($th->getMessage());
            return back();
        }
        notify()->success('You have successfully updated expense status');
        return back();
    }

    public function deleteExpense(Expense $expense)
    {

        $itsName = $expense->name;
        $expense->delete();
        ActivityLogHelper::addToLog('Deleted expense ' . $itsName);

        notify()->success('You have successful deleted ' . $itsName . '.');
        return back();
    }
}
