@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-center">
                        <div>{{ __('Confirm Invitation') }}</div>
                    </div>
                    <div class="card-body d-flex gap-3">
                        <div class="input-group d-grid gap-2 justify-content-end">
                            <form action="{{ route('teams.accept-invitation', [$team->id, $user->id, $token]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i>
                                    Accept
                                </button>
                            </form>
                        </div>
                        <div class="input-group d-grid gap-2 justify-content-start">
                            <form action="{{ route('teams.reject-invitation', [$team->id, $user->id, $token]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-circle"></i>
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
