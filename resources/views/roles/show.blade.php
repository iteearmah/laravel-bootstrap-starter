@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>{{ __('View Role') }}</div>
                        <div class="float-right">
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-success">
                                <i class="bi bi-pencil-square"></i> {{ __('Edit') }}
                            </a>
                            <a href="{{ route('roles.index') }}" class="btn btn-sm btn-primary">
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
                                        {{$role->name}}
                                    </dd>
                                    <dt>
                                        {{ __('Permissions') }}
                                    </dt>
                                    <dd>
                                        @foreach($role->permissions as $permission)
                                            <span class="badge badge-pill text-bg-primary">{{ $permission->name }}</span>
                                        @endforeach
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
