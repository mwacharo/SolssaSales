<?php

namespace App\Http\Controllers;

class TaskController extends Controller
{

    public function index()
    {
        $user_id = auth()->user()->id;

        return view('tasks.index', ['user_id' => $user_id]);

    }

}