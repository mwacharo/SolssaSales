@extends('layouts/contentNavbarLayout')

@section('content')
    <branch-component  :user_id="{{ json_encode($user_id) }}"></branch-component>
@endsection
