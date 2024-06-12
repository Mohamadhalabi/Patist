@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3>Contact Page</h3>
            <br><br>
            <div class="col-lg-12">
                <table class="table table-responsive table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Message</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($contact as $contacts)
                        <tr>
                            <td>{{$contacts->id}}</td>
                            <td>{{$contacts->name}}</td>
                            <td>{{$contacts->email}}</td>
                            <td>{{$contacts->phone}}</td>
                            <td>{{$contacts->subject}}</td>
                            <td>{{$contacts->message}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
