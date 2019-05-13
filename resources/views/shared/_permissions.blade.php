<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="{{ isset($title) ? Str::slug($title) :  'permissionHeading' }}">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#dd-{{ isset($title) ? Str::slug($title) :  'permissionHeading' }}" aria-expanded="{{ $closed ?? 'true' }}" aria-controls="dd-{{ isset($title) ? Str::slug($title) :  'permissionHeading' }}">
                {{ $title ?? 'Phân quyền chi tiết' }} {!! isset($user) ? '<span class="text-danger">(' . $user->getAllPermissions()->count() . ')</span>' : '' !!}
            </a>
        </h4>
    </div>
    <div id="dd-{{ isset($title) ? Str::slug($title) :  'permissionHeading' }}" class="panel-collapse collapse {{ $closed ?? 'in' }}" role="tabpanel" aria-labelledby="dd-{{ isset($title) ? Str::slug($title) :  'permissionHeading' }}">
        <div class="panel-body">
            <div class="row">
                @foreach($permissions as $perm)
                    <?php
                    $per_found = null;
                    if( isset($role) ) {
                        $per_found = $role->hasPermissionTo($perm->name);
                    }

                    if( isset($user) ) {
                        $per_found = $user->hasPermissionTo($perm->name);
                    }
                    ?>

                    <div class="col-md-3">
                        <div class="checkbox">
                            <label class="{{ Str::contains($perm->name, 'delete') ? 'text-danger' : '' }}">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" {{ ($per_found===true) ? 'checked' : '' }} {{ $options ?? ''}}> {{ $perm->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>