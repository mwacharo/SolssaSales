<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('dashboard.index', ['user' => $user]);
    }
}