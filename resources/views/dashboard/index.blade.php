@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('content')
    <dashboard-component :user="{{ json_encode($user) }}"></dashboard-component>
@endsection
