@extends('layouts/contentNavbarLayout')

@section('content')
    <user-component  :user_id="{{ json_encode($user_id) }}"></user-component>
@endsection
