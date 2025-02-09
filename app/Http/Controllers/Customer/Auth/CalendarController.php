<?php

namespace App\Http\Controllers\Customer\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        // dd('reham');
        return view('calendar');
    }
}