@extends('layouts/contentNavbarLayout')

@section('content')
    <report-component  :user_id="{{ json_encode($user_id) }}"></report-component>
@endsection
