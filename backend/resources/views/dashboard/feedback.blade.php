@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3>Feedback Page</h3>
            <br><br>
            <div class="col-lg-12">
                <table class="table table-responsive table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Email</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Message</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($feedback as $feedbacks)
                        <tr>
                            <td>{{$feedbacks->id}}</td>
                            <td>{{$feedbacks->email}}</td>
                            <td>{{$feedbacks->subject}}</td>
                            <td>{{$feedbacks->message}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
