@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3>Knowledge Base Page</h3>
            <br><br>
            <div class="col-lg-12">
                <a class="btn btn-primary float-end" href="{{route ('faq.create')}}">+ Add New Content</a></br></br>
                <table class="table table-responsive table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Type</th>
                        <th scope="col">Question</th>
                        <th scope="col">Answer</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($blogs as $blog)
                        <tr>
                            <td>{{$blog->id}}</td>
                            <td>{{$blog->title}}</td>
                            <td>{{$blog->answer}}</td>
                            <td>{{$blog->question}}</td>
                            <td>
                                <form action="{{ route('faq.destroy',$blog->id) }}" method="Post">
                                    <a class="btn btn-warning" href="{{ route('faq.edit',$blog->id) }}">Edit</a>
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
