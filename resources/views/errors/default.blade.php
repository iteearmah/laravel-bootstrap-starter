@extends('layouts.error')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1 class="text-danger">{{ __($exception->getStatusCode()) }}</h1>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            {{ __($exception->getMessage() ?: 'Sorry, you are forbidden from accessing this page.') }}
                        </div>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-house"></i> {{ __('Back to Dashboard') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
