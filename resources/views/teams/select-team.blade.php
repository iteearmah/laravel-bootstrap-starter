<!-- bootstrap view to be used to allow user to select a team
q: how to write laravel php artisan for pivot table for teams and users table
a: php artisan make:migration create_team_user_table --create=team_user
-->

@extends('layouts.app')

@section('content')
    <div class="container col-4">
        <div class="card">
            <div class="card-header">{{ __('Select :team',['team' => config('team.team_label')] ) }}</div>

            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    @foreach($teams as $team)
                       <!-- bootstrap list-group to be used to display teams -->
                        <ul class="list-group px-2">
                            <li class="list-group-item mb-2">
                                <a href="{{ route('teams.switch', $team) }}" class="d-flex justify-content-between link-dark text-decoration-none">
                                    <span>{{ $team->name }}</span>
                                    <i class="bi bi-arrow-right-circle"></i>
                                </a>
                            </li>
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
@endsection
