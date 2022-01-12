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

@push('plugin-styles')
    {!! Html::style('/assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') !!}
    {!! Html::style('/assets/plugins/select2/css/select2.min.css') !!}
    {!! Html::style('/assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css') !!}
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Schedule Event Type</h4>
        </div>
    </div>
    @if(Session::has('message'))
        <div class="alert alert-icon-info">
        {{Session::get('message')}}
        </div>
    @endif
    @if(!empty($message))
        <div class="alert alert-icon-info">
            <i class="link-icon" data-feather="check"></i>{{$message}}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-icon-danger">
            <i class="link-icon" data-feather="alert-triangle"></i>
            <span>Please resolve the following errors, and try again.</span>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form class="cmxform" method="post">
        @csrf
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body row">
                        <label for="title" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-4 form-group">
                            <input type="text" name="title" class="form-control" value="{{$eventType->title}}">
                        </div>

                        <div class="col-sm-6"></div>

                        <label for="time" class="col-sm-2 col-form-label">Time</label>
                        <div class="form-group col-sm-4">
                            <div class="input-group date timepicker-time-only" id="time" data-target-input="nearest">
                                <input type="text" name="time" class="form-control datetimepicker-input" data-target="#time"
                                       value="{{$eventType->time ? $eventType->time->format(config('app.INPUT_TIME_FORMAT')) : ''}}">
                                <div class="input-group-append" data-target="#time" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6"></div>

                        <label for="day_of_week" class="col-sm-2 col-form-label">Day of Week</label>
                        <div class="col-sm-4 form-group">
                            <select name="day_of_week" class="form-control auto-select2">
                                @foreach ($weekdays as $index => $day)
                                    <option value="{{$index}}" {{$eventType->day_of_week === (string) $index ? 'selected' : ''}}>{{$day}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6"></div>

                        <label for="first_of_month" class="col-sm-2 col-form-label">First of Month</label>
                        <div class="col-sm-4 form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="first_of_month" class="form-check-input"
                                           value="{{config('enums.YES')}}" {{$eventType->first_of_month === config('enums.YES') ? 'checked' : ''}}>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap grid-margin">
            <a href="{{ route('schedule-event-types-list') }}" class="btn btn-outline-secondary btn-icon-text mr-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="x"></i>
                Cancel
            </a>
            <button type="submit" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0" data-cy="submit">
                <i class="btn-icon-prepend" data-feather="save"></i>
                Save
            </button>
            @if(Route::current()->getName() != 'schedule-event-type-new')
            <a href="{{ route('schedule-event-type-delete', ['id' => $eventType->id]) }}" class="btn btn-outline-danger btn-icon-text mr-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="delete"></i>
                Delete
            </a>
            @endif
        </div>
    </form>
@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/select2/js/select2.min.js') !!}
    {!! Html::script('/assets/plugins/moment/moment.min.js') !!}
    {!! Html::script('/assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js') !!}
    {!! Html::script('/assets/js/util.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/select2.js') !!}
    {!! Html::script('/assets/js/timepicker.js') !!}
@endpush
