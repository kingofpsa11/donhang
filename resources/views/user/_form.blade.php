<!-- Name Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="">Tên</label>
    <input type="text" class="form-control" placeholder="Name" name="name" value="{{ $user->name ?? null }}">
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<!-- email Form Input -->
<div class="form-group @if ($errors->has('email')) has-error @endif">
    <label for="">Email</label>
    <input type="email" class="form-control" placeholder="Email" name="email" value="{{ $user->email ?? null }}">
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
    <label for="">Vai trò</label>
    <select name="roles[]" id="" class="form-control" multiple>
        @foreach ($roles as $key => $singleRole)
            @if (isset($user))
                @if (in_array($key, $user->roles->pluck('id')->toArray()))
                    <option value="{{ $key }}" selected>{{ $singleRole }}</option>
                @else
                    <option value="{{ $key }}">{{ $singleRole }}</option>
                @endif
            @else
                <option value="{{ $key }}">{{ $singleRole }}</option>
            @endif
        @endforeach
    </select>
    @if ($errors->has('roles')) <p class="help-block">{{ $errors->first('roles') }}</p> @endif
</div>

<!-- Permissions -->
@if(isset($user))
    @include('shared._permissions', ['closed' => 'false', 'model' => $user ])
@endif