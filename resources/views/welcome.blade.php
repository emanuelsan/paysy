@extends('layouts.app')
@section('content')
<div class="title">Paysy</div>
<div class="subtitle">a WebSy idea</div>

<form action="{{route('welcome')}}" method="get" id="customerSearch">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="customerIdentifier">Customer Identifier</label>
                <input type="text" class="form-control" name="customer" id="customerIdentifier" placeholder="Ex: 2B7Q8">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-default">Search</button>
        </div>
    </div>
</form>
@stop
