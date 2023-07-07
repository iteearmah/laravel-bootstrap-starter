@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between align-content-center">
                            <span>{{ __('Edit Role') }}</span>
                            <div>
                                <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-eye"></i> {{ __('View') }}
                                </a>
                                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-list"></i> {{ __('Back to list') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('roles.update', $role) }}">
                            @csrf
                        @method('PUT')
                        @include('roles.partials.form')
                       <button class="btn btn-primary mt-3">{{ __('Update') }}</button>
                          <a href="{{ route('roles.index') }}" class="btn btn-secondary mt-3">{{ __('Cancel') }}</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
