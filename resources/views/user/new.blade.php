@extends('layouts.app')

@section('title', 'Tạo mới người dùng')

@section('content')

    <div class="row">
        <div class="col-md-5">
            <h3>Create</h3>
        </div>
        <div class="col-md-7 page-action text-right">
            <a href="{{ route('users.index') }}" class="btn btn-default btn-sm"> <i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('users.store') }}">
                @include('user._form')
                <input type="submit" class="btn btn-primary">
            </form>
        </div>
    </div>
@endsection