@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>{{ __('Edit User') }}</div>
                        <div class="float-right">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-success">
                                <i class="bi bi-eye"></i> {{ __('View') }}
                            </a>
                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-list"></i> {{ __('Back to list') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @include('users.partials.form')
                            <div class="float-end">
                                <a href="{{ route('users.index') }}"
                                   class="btn btn-secondary mt-3">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-primary mt-3">{{ __('Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
