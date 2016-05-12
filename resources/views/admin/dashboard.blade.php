@extends('layouts.app')
@section('content')
    <h2>Welcome back!</h2>
    <ul>
        <li>Rooms</li>
        <li><a href="{{route('admin.customers.index')}}">Customers</a></li>
        <li>Payments</li>
    </ul>
@stop