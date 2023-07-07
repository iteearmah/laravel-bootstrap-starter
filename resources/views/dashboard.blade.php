@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Bootstrap Dashboard -->
                        <div class="row">
                            <div class="col-12 col-sm-6 col-xxl-3 d-flex">
                                <div class="card flex-fill">
                                    <div class="card-body py-4">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h3 class="mb-2">{{ __($totalUsers) }}</h3>
                                                <p class="mb-2">Total Users</p>
                                            </div>
                                            <div class="d-inline-block ms-3">
                                                <div class="stat">
                                                    <i class="bi bi-people-fill text-primary"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @role(Helper::superAdminRoleName())
                            <div class="col-12 col-sm-6 col-xxl-3 d-flex">
                                <div class="card flex-fill">
                                    <div class="card-body py-4">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h3 class="mb-2">{{ $totalTeams}}</h3>
                                                <p class="mb-2">{{ __('Teams') }}</p>
                                            </div>
                                            <div class="d-inline-block ms-3">
                                                <div class="stat">
                                                    <i class="bi bi-people-fill text-primary"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endrole
                            <div class="col-12 col-sm-6 col-xxl-3 d-flex">
                                <div class="card flex-fill">
                                    <div class="card-body py-4">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h3 class="mb-2">{{ auth()->user()?->last_login_at?->diffForHumans(null, false, true) }}</h3>
                                                <p class="mb-2">{{ __('Last Logged in') }}</p>
                                            </div>
                                            <div class="d-inline-block ms-3">
                                                <div class="stat">
                                                    <i class="bi bi-clock-history text-primary"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
