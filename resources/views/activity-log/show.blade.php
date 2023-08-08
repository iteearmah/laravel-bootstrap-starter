@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>{{ __('View Activity Log') }}</div>
                        <div class="float-right">
                            <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-list"></i> {{ __('Back to list') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">

                        <!-- spatie activity log  properties $activity -->

                        <dl class="dl-vertical show-view">
                            <dt>{{ __('Log Name') }}</dt>
                            <dd>{{ $activity->log_name }}</dd>
                            <dt>{{ __('Description') }}</dt>
                            <dd>{{ $activity->description }}</dd>
                            @if($activity->subject_id && $activity->subject_type)
                                <dt>{{ __('Subject') }}</dt>
                                <dd>{{ $activity->subject->name ?? __('N/A') }}</dd>
                            @endif
                            @if($activity->causer_id && $activity->causer_type)
                                <dt>{{ __('Causer') }}</dt>
                                <dd>{{ $activity->causer->name ?? __('N/A') }}</dd>
                            @endif
                            <dt>{{ __('Created At') }}</dt>
                            <dd>{{ $activity->created_at }}</dd>
                        </dl>

                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between align-content-center">
                             <span class="d-flex align-items-center">{{ __('Properties') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            @foreach($activity->changes['attributes'] as $key => $val)
                                @if(empty($activity->changes['old']))
                                    <tr>
                                        <th class="fw-bold"><strong>{{ $key }}</strong></th>
                                        <td>{{ $val }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <th class="fw-bold"><strong>{{ $key }}</strong></th>
                                        <td>{{ $activity->changes['old'][$key] ?? __('N/A') }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


