@extends('layouts.dashboard')

@section('title', 'Phân quyền')

@section('content')

    <!-- Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="roleModalLabel">Phân quyền</h4>
                    </div>
                    <div class="modal-body">
                        <!-- name Form Input -->
                        <div class="form-group @if ($errors->has('name')) has-error @endif">
                            <label for="">Tên</label>
                            <input type="text" class="form-control" placeholder="Tên phân quyền" name="name">
                            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
    
                        <!-- Submit Form Button -->
                        <input type="submit" class="btn btn-primary" value="Lưu">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <h3>Phân quyền</h3>
        </div>
        <div class="col-md-7 page-action text-right">
            @can('add_roles')
                <a href="#" class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#roleModal"><i class="fa fa-plus"></i> New</a>
            @endcan
        </div>
    </div>

    @foreach ($roles as $role)
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            {{--{!! Form::model($role, ['method' => 'PUT', 'route' => ['roles.update',  $role->id ], 'class' => 'm-b']) !!}--}}

            @if($role->name === 'Admin')
                @include('shared._permissions', [
                              'title' => 'Phân quyền cho ' . $role->name,
                              'options' => 'disabled'])
            @else
                @include('shared._permissions', [
                              'title' => 'Phân quyền cho ' . $role->name,
                              'model' => $role ])
                @can('edit_roles')
                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                @endcan
            @endif
        </form>
    @endforeach
@endsection