<?php

namespace App\Http\Controllers;

class DealController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;
        return view('deals.index',['user_id' => $user_id]);
    }

}