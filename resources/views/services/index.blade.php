@extends('layouts/contentNavbarLayout')

@section('content')
    <service-component  :user_id="{{ json_encode($user_id) }}"></service-component>
@endsection
