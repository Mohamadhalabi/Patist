@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3>FAQs Page</h3>
            <br><br>
            <div class="col-lg-12">
                <form action="{{ route('faq.update',$faq->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="inputQuestion" class="form-label">Question</label>
                        <input type="text" name="title" class="form-control" value="{{$faq->title}}" id="title">
                    </div>
                    <div class="mb-3">
                        <label for="inputAnswer" class="form-label">Answer</label>
                        <textarea class="form-control" name="answer" id="answer" rows="3">
                            {{$faq->answer}}
                        </textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
