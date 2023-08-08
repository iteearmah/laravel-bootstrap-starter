@extends('layouts.app')

<!-- user create with roles and permissions boostrap view and laravelcollective/html  -->
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>{{ __('Create User') }}</div>
                        <div>
                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-list"></i> {{ __('Back to list') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- create user form -->
                        <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @include('users.partials.form')
                            <div class="float-end">
                                <a href="{{ route('users.index') }}"
                                   class="btn btn-secondary mt-3">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-primary mt-3">{{ __('Create') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


