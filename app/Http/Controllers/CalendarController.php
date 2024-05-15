<?php

namespace App\Http\Controllers;

class CalendarController extends Controller
{
    public function index(){

      $user_id = auth()->user()->id;

      return view('calendar.index',['user_id' => $user_id]);
    }
}