<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    // Main page for reports
    function reportList()
    {
        return view('pages/admin/report-list');
    }
}
