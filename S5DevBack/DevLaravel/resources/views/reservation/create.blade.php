@extends('base')

@section('title', 'Réserver ...')
@section('add_res_active', 'active')

@section('type_request', 'POST')

@section('content')
    @include('reservation.form')
@endsection
