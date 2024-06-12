@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3>FAQs Page</h3>
            <br><br>
            <div class="col-lg-12">
                <a class="btn btn-primary float-end" href="{{route ('price.create')}}">+ Add New Price</a></br></br>
                <table class="table table-responsive table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Item</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Currency</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                    @foreach($prices as $price)
                        <tr>
                            <td>{{$price->id}}</td>
                            <td>{{$price->item}}</td>
                            <td>{{$price->amount}}</td>
                            <td>{{$price->currency}}</td>
                            <td>
                                <form action="{{ route('price.destroy',$price->id) }}" method="Post">
                                    <a class="btn btn-warning" href="{{ route('price.edit',$price->id) }}">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
