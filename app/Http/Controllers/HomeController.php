<?php

namespace App\Http\Controllers;

use App\Models\BatchItem;
use App\Models\Expense;
use App\Models\Good;
use App\Models\Sale;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $todaysTotalAmount = 0;
        $todaysSodaAmount = 0;
        $todaysWaterAmount = 0;
        $todaysTotalCustomers = 0;
        $todaysLeadingSoda = null;
        $todaysLeadingWater = null;
        $todaysLeadingProduct = null;
        $todaysTopSales = null;
        $stockAmount = 0;
        $todaySales = 0;
        $thisWeekSalesAmount = 0;
        $thisWeekProfit = 0;
        $thisMonthSalesAmount = 0;
        $thisMonthProfit = 0;
        $todayDate = Carbon::now();


        try {
            // TOTAL
            $todaysTotalAmount = Sale::where('date', date('Y-m-d'))->sum('price');
            $todaysTotalSales = Sale::where(['date' => date('Y-m-d')])->get();
            $productIdFrequencies = array_count_values($todaysTotalSales->pluck('stock_id')->toArray());
            if ($productIdFrequencies != []) {
                $mostFrequentProductId = array_search(max($productIdFrequencies), $productIdFrequencies);
                $todaysLeadingProduct = Stock::find($mostFrequentProductId);
            }
            $stocks = Stock::all();
            $currentStock = $this->calculateCurrentStock();

            $stockAmount = $stocks->sum(function ($stock) use ($currentStock) {
                // Use the null coalescing operator to default to zero if the key doesn't exist
                $quantity = $currentStock[$stock->id] ?? 0;
                return $stock->product->price * $quantity;
            });

            $salesRevenue = Good::whereYear('date', date('Y'))->sum('amount_paid');

            $todayExpensesAmount=Expense::where("date","=", $todayDate->toDateString())->sum('amount');
            // dd($todayExpensesAmount);

            // WATER BASED
            $todaysSodaAmount = Sale::where('type', "Soda")->where(['date' => date('Y-m-d')])->sum('price');
            $todaysSodaSales = Sale::where('type', 'Soda')->where(['date' => date('Y-m-d')])->get();
            $sodaIdFrequencies = array_count_values($todaysSodaSales->pluck('stock_id')->toArray());
            if ($sodaIdFrequencies != []) {
                $mostFrequentSodaId = array_search(max($sodaIdFrequencies), $sodaIdFrequencies);
                $todaysLeadingSoda = Stock::find($mostFrequentSodaId);
            }
            // SODA BASED
            $todaysWaterAmount = Sale::where('type', "Water")->where(['date' => date('Y-m-d')])->sum('price');
            $todaysWaterSales = Sale::where('type', 'Water')->where(['date' => date('Y-m-d')])->get();
            $waterIdFrequencies = array_count_values($todaysWaterSales->pluck('stock_id')->toArray());
            if ($waterIdFrequencies != []) {
                $mostFrequentWaterId = array_search(max($waterIdFrequencies), $waterIdFrequencies);
                $todaysLeadingWater = Stock::find($mostFrequentWaterId);
            }
            // WEEK BASED
            $now = Carbon::now();
            $startOfWeek = $now->startOfWeek()->format('Y-m-d');
            $endOfWeek = $now->endOfWeek()->format('Y-m-d');
            $thisWeekSales = Sale::with('stock')->whereBetween('date', [$startOfWeek, $endOfWeek])->get();
            $thisWeekSalesAmount = $thisWeekSales->sum('price');
            foreach ($thisWeekSales as $sale) {
                if ($sale->stock) {
                    $thisWeekProfit = $thisWeekProfit + ($sale->price - ($sale->quantity * $sale->stock->cost));
                }
            }

            // MONTH BASED
            $thisMonthSales = Sale::whereMonth('date', date('m'))->get();
            $thisMonthSalesAmount = $thisMonthSales->sum('price');
            foreach ($thisMonthSales as $sale) {
                if ($sale->stock) {
                    $thisMonthProfit = $thisMonthProfit + ($sale->price - ($sale->quantity * $sale->stock->cost));
                }
            }

            // CUSTOMER BASED
            $todaysTotalCustomers = Good::where('date', date('Y-m-d'))->count();
            $todaysTopSales = Good::where('date', date('Y-m-d'))->orderBy('amount_paid', 'desc')->paginate(5);

        return view('home.dashboard', compact(
            'todaysTotalAmount',
            'todaysSodaAmount',
            'todaysWaterAmount',
            'stockAmount',
            'thisWeekSalesAmount',
            'thisWeekProfit',
            'thisMonthSalesAmount',
            'thisMonthProfit',
            'todaysTotalCustomers',
            'todaysLeadingSoda',
            'todaysLeadingWater',
            'todaysLeadingProduct',
            'todaysTopSales',
            'salesRevenue',
            'todayExpensesAmount',
        ));
    }  catch (\Throwable $th) {
        dd($th);
        notify()->error($th->getMessage());
        return back();
    }
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
}
