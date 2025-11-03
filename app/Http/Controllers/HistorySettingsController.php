<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistorySettingsController extends Controller
{
    public function index()
    {
        return view('history-settings');
    }
}
