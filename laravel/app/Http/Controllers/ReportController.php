<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Event;

class ReportController extends Controller
{
    // Main page for reports
    function reportList()
    {
        $events = Event::orderBy('start_date', 'desc')->take(10)->get();
        return view('pages/admin/report-list', compact('events'));
    }
}
