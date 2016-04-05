@extends('layouts.app')
@section('content')
    <h2>Confirm</h2>
    <h3>{{$customer->name}} - ${{$amount}}</h3>

    <div class="row">
        <form action="{{route('withdraw')}}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="card_id" value="{{$customer->card_id}}">
            <input type="hidden" name="amount" value="{{$amount}}">
            <div class="col-md-12">
                <input type="submit" value="Confirm" class="btn btn-success">
            </div>
        </form>
    </div>


@stop