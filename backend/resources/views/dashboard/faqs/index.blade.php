@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3>FAQs Page</h3>
            <br><br>
            <div class="col-lg-12">
                <a class="btn btn-primary float-end" href="{{route ('faq.create')}}">+ Add New Question</a></br></br>
                <table class="table table-responsive table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Question</th>
                        <th scope="col">Answer</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($faqs as $faq)
                        <tr>
                            <td>{{$faq->id}}</td>
                            <td>{{$faq->title}}</td>
                            <td>{{$faq->answer}}</td>
                            <td>
                                <form action="{{ route('faq.destroy',$faq->id) }}" method="Post">
                                    <a class="btn btn-warning" href="{{ route('faq.edit',$faq->id) }}">Edit</a>
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
