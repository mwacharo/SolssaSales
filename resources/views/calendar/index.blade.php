@extends('layouts/contentNavbarLayout')

@section('content')
    <calendar-component  :user_id="{{ json_encode($user_id) }}"></calendar-component>
@endsection
