@extends('layouts/contentNavbarLayout')

@section('content')
    <deal-component  :user_id="{{json_encode($user_id) }}"></deal-component>
@endsection
