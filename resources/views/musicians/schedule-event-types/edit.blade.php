@extends('layout.master')

@push('plugin-styles')
    {!! Html::style('/assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') !!}
    {!! Html::style('/assets/plugins/select2/css/select2.min.css') !!}
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Assign Event</h4>
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
                        <label for="schedule_event_type_id" class="col-sm-2 col-form-label">Event Type</label>
                        <div class="col-sm-4 form-group">
                            <select name="schedule_event_type_id" class="form-control auto-select2">
                                @foreach ($scheduleEventTypes as $event)
                                    <option value="{{$event->id}}" {{(old('schedule_event_type_id') ?? $scheduleEventType->schedule_event_type_id) === $event->id ? 'selected' : ''}}>{{$event->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6"></div>

                        @php
                            $autoSchedule = (old('auto_schedule') ?? $scheduleEventType->auto_schedule) === config('enums.YES');
                        @endphp

                        <label for="auto_schedule" class="col-sm-2 col-form-label">Auto Schedule</label>
                        <div class="col-sm-4 form-group">
                            <select id="auto_schedule" name="auto_schedule" class="form-control auto-select2">
                                <option value="{{config('enums.YES')}}">Yes</option>
                                <option value="{{config('enums.NO')}}" {{$autoSchedule ? '' : 'selected'}}>No</option>
                            </select>
                        </div>
                        <div class="col-sm-6"></div>

                        <label for="first_of_month" class="col-sm-2 col-form-label auto_schedule" style="{{$autoSchedule ? '' : 'display: none;'}}">Frequency</label>
                        <div class="col-sm-4 form-group input-group auto_schedule" style="{{$autoSchedule ? '' : 'display: none;'}}">
                            <input type="number" class="form-control" name="frequency" value="{{old('frequency') ?? $scheduleEventType->frequency ?? '100'}}" min="1" max="100">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-sm-6 auto_schedule" style="{{$autoSchedule ? '' : 'display: none;'}}"></div>

                        <label for="schedule_week" class="col-sm-2 col-form-label">Force Assign</label>
                        <div class="col-sm-4 form-group">
                            <select name="schedule_week" class="form-control auto-select2">
                                <option value="">None</option>
                                @foreach (config('enums.schedulable_weeks') as $value => $week)
                                    <option value="{{$value}}" {{(old('schedule_week') ?? $value) === $scheduleEventType->schedule_week ? 'selected' : ''}}>{{$week}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap grid-margin">
            <a href="{{ route('musician-edit', $musicianId) }}" class="btn btn-outline-secondary btn-icon-text mr-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="x"></i>
                Cancel
            </a>
            <button type="submit" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0" data-cy="submit">
                <i class="btn-icon-prepend" data-feather="save"></i>
                Save
            </button>
            @if(Route::current()->getName() != 'musician-event-new')
            <a href="{{ route('musician-event-delete', ['musician' => $musicianId, 'event' => $scheduleEventType->id]) }}" class="btn btn-outline-danger btn-icon-text mr-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="delete"></i>
                Delete
            </a>
            @endif
        </div>
    </form>
@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/select2/js/select2.min.js') !!}
    {!! Html::script('/assets/js/util.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/select2.js') !!}
    {!! Html::script('/assets/js/musician-events.js') !!}
@endpush
