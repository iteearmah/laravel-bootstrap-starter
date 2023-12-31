@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between align-content-center">
                             <span class="d-flex align-items-center">{{ __('Edit :team',['team' => config('team.team_label')] ) }}</span>
                            <div>
                                <a href="{{ route('teams.show', $team) }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-eye"></i> {{ __('View') }}
                                </a>
                                <a href="{{ route('teams.index') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-list"></i> {{ __('Back to list') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('teams.update', $team) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('teams.partials.form')
                            <div class="float-end">
                            <a href="{{ route('teams.index') }}" class="btn btn-secondary mt-3">{{__('Cancel')}}</a>
                                <button class="btn btn-primary mt-3">{{__('Update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
