<!-- Name Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="">Name</label>
    <input type="text" class="form-control" placeholder="Name" name="name">
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<!-- email Form Input -->
<div class="form-group @if ($errors->has('email')) has-error @endif">
    <label for="">Email</label>
    <input type="email" class="form-control" placeholder="Email" name="email">
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
</div>

<!-- password Form Input -->
<div class="form-group @if ($errors->has('password')) has-error @endif">
    <label for="">Password</label>
    <input type="password" class="form-control" placeholder="Password" name="password">
    @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
</div>

<!-- Roles Form Input -->
<div class="form-group @if ($errors->has('roles')) has-error @endif">
    <label for="">Roles</label>
    <select name="roles[]" id="" class="form-control" multiple>
        @foreach ($roles as $role)
            @if (isset($user))
                @foreach ($user->roles as $userRole)
                    @if ($userRole->id === $role->id)
                        <option value="{{ $userRole->id }}">{{ $userRole->name }}</option>
                    @else
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endif
                @endforeach
            @else
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endif
        @endforeach
    </select>
    @if ($errors->has('roles')) <p class="help-block">{{ $errors->first('roles') }}</p> @endif
</div>

<!-- Permissions -->
@if(isset($user))
    @include('shared._permissions', ['closed' => 'true', 'model' => $user ])
@endif