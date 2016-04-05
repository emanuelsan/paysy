@extends('layouts.app')
@section('content')
    <h2>Withdraw from</h2>
    <h3>{{$customer->name}}</h3>
    @if ( count($errors) > 0)
        <div class="errors alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <form action="{{route('confirm')}}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="card_id" value="{{$customer->card_id}}">
            <div class="col-md-10">
                <div class="input-group">
                    <div class="input-group-addon">$</div>
                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount">
                </div>
            </div>
            <div class="col-md-2">
                <input type="submit" value="Pay" class="btn btn-success">
            </div>
        </form>
    </div>


@stop