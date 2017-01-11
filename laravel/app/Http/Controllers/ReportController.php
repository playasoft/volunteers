<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Event;

class ReportController extends Controller
{
    // Require admin authentication
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // Main page for reports
    function reportList()
    {
        $events = Event::orderBy('start_date', 'desc')->take(10)->get();
        return view('pages/admin/report-list', compact('events'));
    }
}
