<?php

namespace App\Http\Controllers;

class IndustryController extends Controller
{

    public function index()
    {
        $user_id = auth()->user()->id;
        return view('industries.index',['user_id' => $user_id]);
    }

}