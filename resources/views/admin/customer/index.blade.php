@extends('layouts.app')
@section('content')
    <h2>Customers</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Booking Id</th>
            <th>Room</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach (\App\Customer::all() as $customer)
        <tr>
            <td>{{$customer->hash_id}}</td>
            <td>{{$customer->name}}</td>
            <td>{{$customer->booking_id}}</td>
{{--            <td>{{$customer->room->name}}</td>--}}
            <td>DeleteLink</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <form action="{{route('admin.customers.store')}}" method="POST">
        {{ csrf_field() }}
        <table>
            <tr>
                <td></td>
                <td>
                    <div class="form-group">
                        <label for="name">Customer Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Ex: John Doe">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <label for="booking_id">Booking Id</label>
                        <input type="text" class="form-control" name="booking_id" id="booking_id" placeholder="Ex: 1YsdGD42">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <label for="room">Room</label>
                        <select class="form-control" name="room_id" id="room">
                            @foreach (\App\Room::all() as $room)
                                <option value="{{$room->id}}">{{$room->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <label for="submit">Add New Customer</label>
                        <button id="submit" type="submit" class="form-control btn btn-primary">Submit</button>
                    </div>
                </td>
            </tr>
        </table>
    </form>
@stop