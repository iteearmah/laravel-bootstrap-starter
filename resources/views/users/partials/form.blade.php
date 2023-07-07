<div class="row mb-3">
    <label for="name" class="col-form-label">{{ __('Name') }}</label>
    <div class="col-md-12">
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
               value="{{ old('name', $user->name) }}" autocomplete="name" autofocus>
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <label for="email" class="col-form-label">{{ __('Email') }}</label>
    <div class="col-md-12">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
               autocomplete="email" value="{{ old('email', $user->email) }}">
        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <label for="username" class="col-form-label">{{ __('Username') }}</label>
    <div class="col-md-12">
        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username"
               autocomplete="username" value="{{ old('username', $user->username) }}">
        @error('username')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <label for="password" class="col-form-label">{{ __('Password') }}</label>
    <div class="col-md-12">
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
               name="password" autocomplete="new-password">
        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<!-- roles and permissions -->

<div class="row mb-3">
    <label for="roles" class="col-form-label">{{ __('Roles') }}</label>
    <div class="col-md-12">
        @forelse($roles as $role)
            <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="form-check-input"
                   id="{{ $role->name }}" {{ $user->roles->pluck('id')->contains($role->id) ? 'checked' : '' }}>
            <label for="{{ $role->name }}" class="form-check-label">{{ $role->name }}</label>
        @empty
            <p>{{ __('No Roles') }}</p>
        @endforelse
        @error('roles')
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
                       id="{{ $permission->name }}" {{ $user->permissions->pluck('id')->contains($permission->id) ? 'checked' : '' }}>
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

@push('scripts')
    <script type="module">
        // load permissions for multiple selected roles

        $(document).ready(function () {
            //$user->roles->pluck('permissions')->toArray() to javascript array

            let selectedPermissions = [];
            @foreach($user->roles->pluck('permissions')->toArray() as $permissions)
            @foreach($permissions as $permission)
            selectedPermissions.push({{ $permission['id'] }});
            @endforeach
            @endforeach

            $('input[name="roles[]"]').click(function () {
                var roles = [];
                $('input[name="roles[]"]:checked').each(function () {
                    roles.push($(this).val());
                });
                if (roles.length > 0) {
                    $.ajax({
                        url: "{{ route('users.get-permissions') }}",
                        method: 'post',
                        data: {
                            roles: roles,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            let permissions = data;
                            let html = '';
                            for (let i = 0; i < permissions.length; i++) {
                                html += '<div class="form-check">';
                                //check if permission is selected
                                if (selectedPermissions.includes(permissions[i].id)) {
                                    html += '<input class="form-check-input" type="checkbox" name="permissions[]" id="' + permissions[i].name + '" value="' + permissions[i].id + '" checked>';
                                } else {
                                    html += '<input class="form-check-input" type="checkbox" name="permissions[]" id="' + permissions[i].name + '" value="' + permissions[i].id + '" checked>';
                                }
                                html += '<label class="form-check-label" for="' + permissions[i].name + '">' + permissions[i].name + '</label>';
                                html += '</div>';
                            }

                            $('#permissions').html(html);
                        }
                    });
                } else {
                    $('#permissions').html("{{ __('No roles selected') }}");
                }
            });
        });

    </script>
@endpush
