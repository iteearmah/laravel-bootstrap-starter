@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-content-center">
                    <span>{{ __('Manage Users') }}</span>
                    <div>
                        <a href="{{ route('users.create') }}" class="btn btn-primary"><i
                            class="bi bi-plus-circle"></i> {{ __('Add User') }}</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
