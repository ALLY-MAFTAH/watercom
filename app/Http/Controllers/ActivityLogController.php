<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller;

use App\Helpers\ActivityLogHelper;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
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
        $logs = ActivityLog::all();

        return view('logs.index', compact('logs'));
    }

    public function postLog()
    {
        ActivityLogHelper::addToLog('My Testing Add To Log.');
        dd('log insert successfully.');
    }
}
