<?php

namespace App\Http\Controllers;

class ServiceController extends Controller
{

    public function index()
    {

        $user_id = auth()->user()->id;

        return view('services.index',['user_id' => $user_id]);
    }

}