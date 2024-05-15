<?php

namespace App\Http\Controllers;

class BranchController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;

        return view('branches.index',['user_id' => $user_id]);
    }
}