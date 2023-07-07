<div class="row mb-3">
    <label for="name" class="col-form-label">{{ __('Name') }}</label>
    <div class="col-md-12">
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
               value="{{ old('name', $team->name) }}" autocomplete="name" autofocus>
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <label for="description" class="col-form-label">{{ __('Description') }}</label>
    <div class="col-md-12">
        <input id="description" type="text" class="form-control @error('description') is-invalid @enderror"
               name="description" autocomplete="description " value="{{ old('description', $team->description) }}">
        @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>


<div class="row mb-3">
    <label for="logo" class="col-form-label">{{ __('Logo') }}</label>
    <div class="col-md-12">
        <input id="logo" type="file" class="form-control @error('logo') is-invalid @enderror" name="logo"
               autocomplete="logo">
        @if($team->logo)
            <div class="mt-2 col-md-2">
                <img src="{{ asset(\App\Models\Team::LOGOS_DIRECTORY .'/'.$team->logo) }}" alt="{{ $team->name }}"
                     class="img-thumbnail">
                <!-- delete existing logo -->
                <div class="mt-2">
                    <label>
                        {!! Form::checkbox('delete_existing_logo') !!}
                        {!! Form::hidden('logo_name', $team->logo) !!}
                        Delete logo
                    </label>
                </div>
            </div>
        @endif
        @error('logo')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

@if(!request()->routeIs('teams.create'))
    <div class="row mb-3">
        <label for="roles" class="col-form-label">{{ __('Roles') }}</label>
        <div class="col-md-12">
            @forelse($roles as $role)
                <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="form-check-input"
                       id="{{ $role->name }}" {{ $team->roles->pluck('id')->contains($role->id) ? 'checked' : '' }}>
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
@endif
