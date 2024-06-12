@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3>Add New Price</h3>
            <br><br>
            <div class="col-lg-12">
                <form action="{{ route('price.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="inputQuestion" class="form-label">Item</label>
                        <input type="text" name="item" class="form-control" id="item">
                    </div>
                    <div class="mb-3">
                        <label for="inputQuestion" class="form-label">Amount</label>
                        <input type="number" name="amount" class="form-control" id="amount">
                    </div>
                    <div class="mb-3">
                        <label for="inputAnswer" class="form-label">Currency</label>
                        <input type="text" name="currency" class="form-control" id="currency">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
