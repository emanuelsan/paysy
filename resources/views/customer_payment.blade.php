@extends('layouts.app')
@section('content')
    <h2>Customer Payment Page</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach($customer->billables as $billable)
            <tr>
                <td>{{$billable->name}}</td>
                <td>{{$billable->quantity}}</td>
                <td>{{$billable->price}}&nbsp;{{env('CURRENCY')}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <form action="{{route('customerPayment',['customer'=>$customer->hash_id])}}" method="post">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="creditcardtype">Credit Card Type</label>
                    <select class="form-control" name="creditcardtype" id="creditcardtype">
                        <option value="visa">Visa</option>
                        <option value="mastercard">Mastercard</option>
                        <option value="discover">Discover</option>
                        <option value="amex">American Express</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="creditcardnumber">Credit Card Number</label>
                    <input type="text" class="form-control" name="creditcardnumber" id="creditcardnumber" placeholder="Ex: 4000000000000000">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="creditcardholder">Credit Card Holder</label>
                    <input type="text" class="form-control" name="creditcardholder" id="creditcardholder" placeholder="Ex: John Doe">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="expiremonth">Expire Month</label>
                    <select class="form-control" name="expiremonth" id="expiremonth">
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="expireyear">Expire Year</label>
                    <select class="form-control" name="expireyear" id="expireyear">
                        @for($i=0;$i<11;$i++)
                            <option value="{{$i+date("Y")}}">{{$i+date("Y")}}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="cvv2">CVV</label>
                    <input type="text" class="form-control" name="cvv2" id="cvv2" placeholder="Ex: 000">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </div>
    </form>
@stop