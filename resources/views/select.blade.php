@extends('layouts.app')
@section('content')
    <h2>Cards</h2>
    @if ( count($errors) > 0 || (Session::get('errors') && $errors = Session::get('errors')))
        <div class="errors alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (Session::get('success'))
        <div class="alert alert-success">
            Transaction completed successfully with id {{Session::get('success')}}
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <th>Card ID</th>
            <th>Actions</th>
        </tr>
        @foreach($customers as $customer)
            <tr>
                <td>{{$customer->name}}</td>
                <td>{{$customer->card_id}}</td>
                <td>
                    <form action="{{route('selectcard')}}" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="card_id" value="{{$customer->card_id}}">
                        <input type="submit" value="Payment" class="btn btn-success">
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    <h2>Errors</h2>
    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <th>Card ID</th>
            <th>Actions</th>
        </tr>
        @foreach($errors as $error)
        <tr>
            <td>{{$customer->name}}</td>
            <td>{{$customer->card_id}}</td>
            <td><a href="{{route('/')}}">Remove</a></td>
        </tr>
        @endforeach
    </table>
@stop