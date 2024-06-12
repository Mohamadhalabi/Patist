@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3>Payment Information</h3>
            <br><br>
            <div class="col-lg-12">
                <table class="table table-responsive table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">service</th>
                        <th scope="col">service fee</th>
                        <th scope="col">official fee </th>
                        <th scope="col">translation quantity</th>
                        <th scope="col">translation fee</th>
                        <th scope="col">total</th>
                        <th scope="col">order status</th>
                        <th scope="col">action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$edit->link}}</td>
                        <td>{{$edit->service}}</td>
                        <td>{{$edit->service_fee}}</td>
                        <td>{{$edit->official_fee}}</td>
                        <td>{{$edit->translation_quantity}}</td>
                        <td>{{$edit->translation_fee}}</td>
                        <td>{{$edit->total}}</td>
                        <td>{{$edit->order_status}}</td>
                        @if($edit->order_status !='success')
                        <td>
                            <a class="btn btn-info" style="color:white" href="{{ route('payment-status',$edit->id) }}">Change to paid</a>
                        </td>
                        @else
                            <td>
                                <a class="btn btn-success" href="{{ route('payment-status',$edit->id) }}">Change to unpaid</a>
                            </td>
                        @endif
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br><br><br>
        <div class="row justify-content-center">
            <h3>User Information</h3>
            <br><br>
            <div class="col-lg-12">
                <table class="table table-responsive table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">name</th>
                        <th scope="col">phone number</th>
                        <th scope="col">email</th>
                        <th scope="col">company</th>
                        <th scope="col">relationship</th>
                        <th scope="col">tax number</th>
                        <th scope="col">address</th>
                        <th scope="col">post code</th>
                        <th scope="col">country</th>
                        <th scope="col">state</th>
                        <th scope="col">city</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{$edit->user_name}}</td>
                        <td>{{$edit->user_phone}}</td>
                        <td>{{$edit->user_email}}</td>
                        <td>{{$edit->company}}</td>
                        <td>{{$edit->relationship}}</td>
                        <td>{{$edit->tax_number}}</td>
                        <td>{{$edit->address}}</td>
                        <td>{{$edit->post_code}}</td>
                        <td>{{$edit->country}}</td>
                        <td>{{$edit->state}}</td>
                        <td>{{$edit->city}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
