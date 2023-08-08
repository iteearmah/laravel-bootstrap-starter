@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-content-center">
                     <span class="d-flex align-items-center">{{ __('Manage  :team',['team' => \Illuminate\Support\Str::plural(config('team.team_label'))] ) }}</span>
                    <div>
                        <a href="{{ route('teams.create') }}" class="btn btn-primary"><i
                                class="bi bi-plus-circle"></i> {{ __('Add :team',['team' => config('team.team_label')]) }}</a>
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
