@extends('layouts/contentNavbarLayout')

@section('content')
    <task-component :user_id="{{ json_encode($user_id) }}"></task-component>
@endsection
