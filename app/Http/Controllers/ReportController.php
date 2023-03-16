<?php

namespace App\Http\Controllers;

use App\Models\Good;
use App\Models\Sale;
use Illuminate\Routing\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jimmyjs\ReportGenerator\ReportMedia\CSVReport;
use Jimmyjs\ReportGenerator\ReportMedia\ExcelReport;
use Jimmyjs\ReportGenerator\ReportMedia\PdfReport;

// use PdfReport;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function salesReport(Request $request)
    {
        // dd($request->all());
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $sortBy = $request->input('sort_by');
        $basedOn = $request->input('based_on');
        $orientation = $request->input('orientation');
        $groupBy = null;

        if ($request->group_by == "seller") {
            $groupBy = "Seller";
        }
        if ($request->group_by == "customer_id") {
            $groupBy = "Customer";
        }
        if ($request->group_by == "date") {
            $groupBy = "Issued Date";
        }
        if ($request->group_by == "name") {
            $groupBy = "Product Name";
        }
        if ($request->group_by == "type") {
            $groupBy = "Type";
        }
        if ($request->group_by == "date") {
            $groupBy = "Issued Date";
        }
        if ($groupBy != null) {
            $sortBy = $request->group_by;
        }

        $title = 'Tanga Watercom Sales Report';
        $meta = [
            'Issued from' => Carbon::parse($fromDate)->format('D, d M Y') . ' to ' . Carbon::parse($toDate)->format('D, d M Y'),
            'Sorted By' => $sortBy
        ];

        try {
            if ($basedOn == "Product") {
                $sales = Sale::whereBetween('date', [$fromDate, $toDate])->orderBy($sortBy);
                if (Auth::user()->role_id == 1) {
                    $columns = [
                        'Issued Date' => function ($result) {
                            return Carbon::parse($result->date)->format('D, d M Y');
                        },
                        'Product Name' => function ($result) {
                            return $result->name . " " . $result->volume . " " . $result->measure;
                        },
                        'Quantity' => function ($result) {
                            return $result->quantity . " " . $result->unit;
                        },
                        'Type' => 'type',
                        'Price' => 'price',
                        'Profit' => function ($sale) {
                            return $sale->price - ($sale->stock->cost * $sale->quantity);
                        },
                        'Seller' => 'seller',
                    ];
                } else {
                    $columns = [
                        'Issued Date' => function ($result) {
                            return Carbon::parse($result->date)->format('D, d M Y');
                        },
                        'Product Name' => function ($result) {
                            return $result->name . " " . $result->volume . " " . $result->measure;
                        },
                        'Quantity' => function ($result) {
                            return $result->quantity . " " . $result->unit;
                        },
                        'Price' => 'price',
                        'Type' => 'type',
                        'Seller' => 'seller',
                    ];
                }
            } else {
                $sales = Good::whereBetween('date', [$fromDate, $toDate])->orderBy($sortBy);
                if (Auth::user()->role_id == 1) {
                    $columns = [
                        'Issued Date' => function ($result) {
                            return Carbon::parse($result->date)->format('D, d M Y');
                        },
                        'Products' => function ($result) {
                            foreach ($result->purchases as $purchase) {
                                $products[] =
                                    $purchase->name . " " . $purchase->volume . " " . $purchase->measure . " - " . $purchase->quantity . " " . $purchase->unit . "\r\n";
                            }
                            return implode("", $products);
                        },
                        'Price' => 'amount_paid',
                        'Profit' =>  function ($sale) {
                            $profit = 0;
                            foreach ($sale->purchases as $purchase) {
                                $profit = $profit + ($purchase->price - ($purchase->stock->cost * $purchase->quantity));
                            }
                            return $profit;
                        },
                        'Customer' => function ($result) {
                            if ($result->customer) {
                                return $result->customer->name . "\r\n" . $result->customer->phone;
                            } else {
                                return "Not Recorder";
                            }
                        },
                        'Seller' => 'seller',
                    ];
                } else {
                    $columns = [
                        'Issued Date' => function ($result) {
                            return Carbon::parse($result->date)->format('D, d M Y');
                        },
                        'Products' => function ($result) {
                            foreach ($result->purchases as $purchase) {
                                $products[] =
                                    $purchase->name . " " . $purchase->volume . " " . $purchase->measure . " - " . $purchase->quantity . " " . $purchase->unit . "\r\n";
                            }
                            return implode("", $products);
                        },
                        'Price' => 'amount_paid',
                        'Customer' => function ($result) {
                            if ($result->customer) {
                                return $result->customer->name . "\r\n" . $result->customer->phone;
                            } else {
                                return "Not Recorder";
                            }
                        },
                        'Seller' => 'seller',
                    ];
                }
            }
            $reportInstance = new PdfReport();
            // $reportInstance = new ExcelReport();
            // $reportInstance = new CSVReport  ();
            return $reportInstance->of($title, $meta, $sales, $columns)->setOrientation($orientation)

                ->editColumn('Issued Date', [
                    'class' => 'center'
                ])->editColumns(['Price', 'Profit'], [
                    'class' => 'right'
                ])->showTotal([
                    'Price' => 'point',
                    'Profit' => 'point',
                ])
                ->groupBy($groupBy)
                ->stream(); // other available method: store('path/to/file.pdf') to save to disk, download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
