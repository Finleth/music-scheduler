@extends('layout.master')

@php
$weekdays = [
    'Sunday',
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday'
];
@endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Schedule Event Types</h4>
        </div>

        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{route('schedule-event-type-new')}}" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="edit"></i>
                Add Event Type
            </a>
        </div>
    </div>
    @if(Session::has('message'))
        <div class="alert alert-icon-info">
            {{Session::get('message')}}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Frequency</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($eventTypes as $eventType)
                                    <tr>
                                        <td>
                                            <a href="{{ route('schedule-event-type-edit', $eventType->id) }}">{{ $eventType->title }}</a>
                                        </td>
                                        <td>
                                            {{$eventType->first_of_month === config('enums.YES') ? 'First' : 'Every'}}
                                            {{$weekdays[(int) $eventType->day_of_week]}},
                                            {{(new DateTime($eventType->hour . ':' . $eventType->minute))->format(config('app.INPUT_TIME_FORMAT'))}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                @if(!empty($musicians))
                    {{$musicians->appends(\Request::except('page'))->render()}}
                @endif
            </div>
        </div>
    </div>
@endsection
