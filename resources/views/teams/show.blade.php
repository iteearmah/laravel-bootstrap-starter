@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            {{ __('View :team',['team' => config('team.team_label')] ) }}
                            </div>
                        <div class="float-right">
                            <a href="{{ route('teams.edit', $team) }}" class="btn btn-sm btn-success">
                                <i class="bi bi-pencil-square"></i> {{ __('Edit') }}
                            </a>
                            <a href="{{ route('teams.index') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-list"></i> {{ __('Back to list') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <dl class="dl-vertical show-view">
                                    <dt>
                                        {{ __('Name') }}
                                    </dt>
                                    <dd>
                                        {{$team->name}}
                                    </dd>
                                    <dt>
                                        {{ __('Description') }}
                                    </dt>
                                    <dd>
                                        {{$team->description}}
                                    </dd>
                                    @if($team->logo)
                                    <dt>
                                        {{ __('Logo') }}
                                    </dt>
                                    <dd class="col-md-3">
                                        <img src="{{ asset(\App\Models\Team::LOGOS_DIRECTORY .'/'.$team->logo) }}" alt="{{ $team->name }}" class="img-thumbnail">
                                    </dd>
                                    @endif
                                </dl>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-content-center flex-wrap">
                        <div>
                            {{ __('Member') }}
                            <div class="text-muted">
                                {{ __('Manage the members of your team.') }}
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('users.index', ['team' => $team]) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-list"></i> {{ __('Users') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                {{ $dataTable->table() }}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            {{ __('Invite') }}
                            <div class="text-muted">
                                {{ __('Add new members to your team.') }}
                            </div>
                        </div>

                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                               <!-- invite member form-->
                                <form action="{{ route('teams.invite-member', $team) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <label for="name" class="col-form-label">{{ __('Name') }}</label>
                                                <div class="col-md-12">
                                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                                           value="{{ old('name') }}" autocomplete="name" autofocus>
                                                    @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <label for="email" class="col-form-label">{{ __('Email') }}</label>
                                                <div class="col-md-12">
                                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                                           name="email" value="{{ old('email') }}" autocomplete="email">
                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <label for="username" class="col-form-label">{{ __('Username') }}</label>
                                                <div class="col-md-12">
                                                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                                                           name="username" value="{{ old('username') }}" autocomplete="username-">
                                                    @error('username')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row mb-3">
                                                <label for="roles" class="col-form-label">{{ __('Roles') }}</label>
                                                <div class="col-md-12">
                                                    @forelse($roles as $id => $role)
                                                        <input type="checkbox" name="roles[]" value="{{ $id }}" class="form-check-input"
                                                               id="{{ $role }}" >
                                                        <label for="{{ $role }}" class="form-check-label">{{ $role }}</label>
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
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-envelope"></i> {{ __('Invite') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script type="module">
        // get username from email

        $('#email').on('keyup', function () {
            // if username is not changed
            let  username = $('#username')
            if(username.val()!== $('#email').val().split('@')[0]  && username === '') {
                let email = $(this).val();
                let username = email.split('@')[0];
                $('#username').val(username);
            }
        });
    </script>
@endpush


