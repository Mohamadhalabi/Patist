@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3>Orders Information</h3>
            <br><br>
            <div class="col-lg-12">
                <table class="table table-responsive table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">application number</th>
                        <th scope="col">publication date</th>
                        <th scope="col">inventor </th>
                        <th scope="col">invention title</th>
                        <th scope="col">service</th>
                        <th scope="col">service fee</th>
                        <th scope="col">official fee</th>
                        <th scope="col">translation quantity</th>
                        <th scope="col">translation fee</th>
                        <th scope="col">total</th>
                        <th scope="col">order status</th>
                        <th scope="col">Action</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                        <td>{{$order->link}}</td>
                        <td>{{$order->application_number}}</td>
                        <td>{{$order->publication_date}}</td>
                        <td>{{$order->inventor}}</td>
                        <td>{{$order->invention_title}}</td>
                        <td>{{$order->service}}</td>
                        <td>{{$order->service_fee}}</td>
                        <td>{{$order->official_fee}}</td>
                        <td>{{$order->translation_quantity}}</td>
                        <td>{{$order->translation_fee}}</td>
                        <td>{{$order->total}}</td>
                        <th>{{$order->order_status}}</th>
                            <th>
                                <a class="btn btn-primary" href="{{ route('edit',$order->id) }}">Edit</a>
                            </th>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
