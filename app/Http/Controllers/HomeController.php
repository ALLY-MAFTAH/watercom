<?php

namespace App\Http\Controllers;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $todaysTotalAmount = 0;
        $todaysJuiceAmount = 0;
        $todaysWaterAmount = 0;
        $todaysTotalCustomers = 0;
        $todaysLeadingJuice = null;
        $todaysLeadingWater = null;
        $todaysLeadingProduct = null;
        $todaysTopSales = null;
        $stockAmount = 0;
        $todaySales = 0;
        $thisWeekSalesAmount = 0;
        $thisWeekProfit = 0;
        $thisMonthSalesAmount = 0;
        $thisMonthProfit = 0;

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
            $stockAmount = $stocks->sum(function ($stock) {
                return $stock->product->price * $stock->quantity;
            });
            $salesRevenue = Sale::whereYear('date', date('Y'))->sum('price');

            // WATER BASED
            $todaysJuiceAmount = Sale::where('type', "Juice")->where(['date' => date('Y-m-d')])->sum('price');
            $todaysJuiceSales = Sale::where('type', 'Juice')->where(['date' => date('Y-m-d')])->get();
            $juiceIdFrequencies = array_count_values($todaysJuiceSales->pluck('stock_id')->toArray());
            if ($juiceIdFrequencies != []) {
                $mostFrequentJuiceId = array_search(max($juiceIdFrequencies), $juiceIdFrequencies);
                $todaysLeadingJuice = Stock::find($mostFrequentJuiceId);
            }
            // JUICE BASED
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
        } catch (\Throwable $th) {
            notify()->error($th->getMessage());
            return back();
        }
        return view('home.dashboard', compact(
            'todaysTotalAmount',
            'todaysJuiceAmount',
            'todaysWaterAmount',
            'stockAmount',
            'thisWeekSalesAmount',
            'thisWeekProfit',
            'thisMonthSalesAmount',
            'thisMonthProfit',
            'todaysTotalCustomers',
            'todaysLeadingJuice',
            'todaysLeadingWater',
            'todaysLeadingProduct',
            'todaysTopSales',
            'salesRevenue',
        ));
    }
}
