@extends('layouts/contentNavbarLayout')

@section('content')
    <industry-component  :user_id="{{ json_encode($user_id) }}"></industry-component>
@endsection
