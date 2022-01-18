@extends('layout.master')

@push('plugin-styles')
    {!! Html::style('/assets/plugins/select2/css/select2.min.css') !!}
    {!! Html::style('/assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css') !!}
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Calendar: {{$calendar->name}}</h4>
        </div>

        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{route('schedule-generate', $calendar->id)}}" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="edit"></i>
                Generate Schedule
            </a>
        </div>
    </div>
    @if(Session::has('message'))
        <div class="alert alert-icon-info">
            {{Session::get('message')}}
        </div>
    @endif

    @if(Session::has('error'))
        <div class="alert alert-icon-danger">
            {{Session::get('error')}}
        </div>
    @endif

    <form class="cmxform" method="get">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body row">
                        <label for="start" class="col-sm-2 col-form-label text-right">Start</label>
                        <div class="form-group col-sm-4">
                            <div class="input-group date timepicker-date-only" id="startdate" data-target-input="nearest">
                                <input type="text" name="start" class="form-control datetimepicker-input" data-target="#startdate"
                                       value="{{old('start') ?? request()->start}}">
                                <div class="input-group-append" data-target="#startdate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6"></div>

                        <label for="end" class="col-sm-2 col-form-label text-right">End</label>
                        <div class="form-group col-sm-4">
                            <div class="input-group date timepicker-date-only" id="enddate" data-target-input="nearest">
                                <input type="text" name="end" class="form-control datetimepicker-input" data-target="#enddate"
                                       value="{{old('end') ?? request()->end}}">
                                <div class="input-group-append" data-target="#enddate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6"></div>

                        <label for="batch" class="col-sm-2 col-form-label text-right">Batch</label>
                        <div class="form-group col-sm-4">
                            <select name="batch" class="form-control auto-select2">
                                <option value="">All</option>
                                @foreach ($scheduleGenerations as $batch)
                                    <option value="{{$batch->batch}}" {{(old('batch') ?? request()->batch) == $batch->batch ? 'selected' : ''}}>
                                        {{sprintf('Batch %s (%s)', $batch->batch, $batch->created_at->format(config('app.DISPLAY_DATE_FORMAT')))}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap grid-margin">
            <button type="submit" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0" data-cy="submit">
                <i class="btn-icon-prepend" data-feather="save"></i>
                Filter
            </button>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="col-6">Date</th>
                                    <th class="col-6">Events</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedule as $day)
                                    <tr>
                                        <td class="col-6">{{$day->event_date->format(config('app.DISPLAY_DATE_FORMAT_DAY_OF_WEEK'))}}</td>
                                        <td class="col-6">
                                            @foreach ($day->events as $event)
                                                <div class="mb-1">
                                                    <a href="{{route('schedule-event-edit', $event->id)}}">{{$event->schedule_event_type->title}}: {{$event->musician->first_name ? $event->musician->first_name : $event->musician->last_name}}</a>
                                                </div>
                                            @endforeach
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
                {{$schedule->links()}}
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/select2/js/select2.min.js') !!}
    {!! Html::script('/assets/plugins/moment/moment.min.js') !!}
    {!! Html::script('/assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/select2.js') !!}
    {!! Html::script('/assets/js/timepicker.js') !!}
@endpush
