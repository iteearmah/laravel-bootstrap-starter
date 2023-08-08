@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between align-content-center">
                             <span class="d-flex align-items-center">{{ __('Add :team',['team' => config('team.team_label')] ) }}</span>
                            <div>
                                <a href="{{ route('teams.index') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-list"></i> {{ __('Back to list') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('teams.store') }}" enctype="multipart/form-data">
                            @csrf
                            @include('teams.partials.form')
                            <button class="btn btn-primary mt-3">{{__('Create')}}</button>
                            <a href="{{ route('teams.index') }}" class="btn btn-secondary mt-3">{{__('Cancel')}}</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
