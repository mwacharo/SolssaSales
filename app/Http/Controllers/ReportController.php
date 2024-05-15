<?php

namespace App\Http\Controllers;

class ReportController extends Controller
{
    public function index()
    {

        $user_id = auth()->user()->id;

        return view('reports.index',['user_id' => $user_id]);

    }

}