<div class="row mb-3">
    <label for="name" class="col-form-label">{{ __('Name') }}</label>
    <div class="col-md-12">
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
               value="{{ old('name', $role->name) }}" autocomplete="name" autofocus>
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <label for="permissions" class="col-form-label">{{ __('Permissions') }}</label>
    <div class="col-md-12">
        <!-- checkbox for permissions -->
        @forelse($permissions as $permission)
            <div class="form-check">
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-check-input"
                       id="{{ $permission->name }}" {{ in_array($permission->id, $rolePermissions,true) ? 'checked' : '' }}>
                <label for="{{ $permission->name }}" class="form-check-label">{{ $permission->name }}</label>
            </div>
        @empty
            <div id="permissions">
                <p>{{ __('No Permissions') }}</p>
            </div>

        @endforelse
        @error('permissions')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
