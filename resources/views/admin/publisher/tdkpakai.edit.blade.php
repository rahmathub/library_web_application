@extends('layouts.admin')
@section('header', 'Publisher')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Create New Publisher</h3>
            </div>

            {{-- Form --}}
            <form action="{{ url('publishers/'.$publisher->id) }}" method="post" >
                @csrf
                {{ method_field('PUT') }}

                <div class="card-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control"  placeholder="Enter name" required=""
                        value="{{ $publisher->name }}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control"  placeholder="Enter email" required=""
                        value="{{ $publisher->email }}">
                    </div>
                    <div class="form-group">
                        <label>Number Phone</label>
                        <input type="text" name="phone_number" class="form-control"  placeholder="Enter number phone" required=""
                        value="{{ $publisher->phone_number }}">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control"  placeholder="Enter address" required=""
                        value="{{ $publisher->address }}">
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection