@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div>{{ __('View User') }}</div>
                <div class="float-right">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-success">
                        <i class="bi bi-pencil-square"></i> {{ __('Edit') }}
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">
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
                                {{$user->name}}
                            </dd>
                            <dt>
                                {{ __('Email') }}
                            </dt>
                            <dd>
                                {{$user->email}}
                            </dd>
                            <dt>
                                {{ __('Roles') }}
                            </dt>
                            <dd>
                                @foreach($user->roles as $role)
                                    <span class="badge badge-pill text-bg-primary">{{ ucwords($role->name) }}</span>
                                @endforeach
                            </dd>
                            <dt>
                                {{ __('Permissions') }}
                            </dt>
                            <dd>
                                @forelse($user->permissions as $permission)
                                    <span class="badge badge-pill text-bg-primary">{{ $permission->name }}</span>
                                @empty
                                    <span class="badge badge-pill text-bg-danger">No Permissions</span>
                                @endforelse
                            </dd>
                        </dl>
                    </div>
                </div>

            </div>
        </div>
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between">
                        <div>{{ __('Teams') }}</div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Logo') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($user->teams as $team)
                                <tr>
                                    <td><a href="{{ route('teams.show', $team) }}" >{{ $team->name }}</a></td>
                                    <td>{{ $team->description }}</td>
                                    <td>
                                        @if($team->logo)
                                            <img src="{{ asset(\App\Models\Team::LOGOS_DIRECTORY .'/'.$team->logo) }}" alt="{{ $team->name }}" class="img-thumbnail">
                                        @else
                                            {{ __('N/A') }}
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"><span class="badge badge-pill text-bg-danger">No Teams</span></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


