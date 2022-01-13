@extends('layout.master')

@push('plugin-styles')
    {!! Html::style('/assets/plugins/select2/css/select2.min.css') !!}
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Musician</h4>
        </div>

        @if(Route::current()->getName() != 'musician-new')
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{route('musician-new')}}" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="edit"></i>
                Add Musician
            </a>
        </div>
        @endif
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
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="first_name" class="col-sm-2 col-form-label">First Name</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="first_name" value="{{old('first_name') ?? $musician->first_name}}"
                                    name="first_name" placeholder="First name" {{$readonly ?? ''}}>
                            </div>

                            <div class="col-sm-6"></div>

                            <label for="last_name" class="col-sm-2 col-form-label">Last Name</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="last_name" value="{{old('last_name') ?? $musician->last_name}}"
                                    name="last_name" placeholder="Last name" {{$readonly ?? ''}}>
                            </div>

                            <div class="col-sm-6"></div>

                            <label for="status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-4">
                                <select class="auto-select2" name="status" id="status" {{$readonly ?? ''}}>
                                    <option value="{{ config('enums.status.ACTIVE') }}">Active</option>
                                    <option value="{{ config('enums.status.INACTIVE') }}" {{ $musician->status === config('enums.status.INACTIVE') ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap grid-margin">
            <a href="{{ route('musicians-list') }}" class="btn btn-outline-secondary btn-icon-text mr-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="x"></i>
                Cancel
            </a>
            <button type="submit" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0" data-cy="submit">
                <i class="btn-icon-prepend" data-feather="save"></i>
                Save
            </button>
        </div>
    </form>

    @if(Route::current()->getName() != 'musician-new')
    <div class="row">
        <div class="col-md-7 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <h5>Assignable Events</h5>
                        </div>

                        @if($availableEvents->isNotEmpty())
                        <div class="col-md-3 d-flex justify-content-end">
                            <a href="{{route('musician-event-new', $musician->id)}}"><i class="btn-icon-prepend text-primary" data-feather="plus-circle"></i></a>
                        </div>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="col-3">Title</th>
                                    <th class="col-3">Frequency</th>
                                    <th class="col-3">Auto Schedule</th>
                                    <th class="col-3">Force Assign</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($musician->schedule_event_types as $event)
                                    <tr>
                                        <td class="col-3">
                                            <a href="{{route('musician-event-edit', ['musician' => $musician->id, 'event' => $event->pivot->id])}}">{{$event->title}}</a>
                                        </td>
                                        <td class="col-3">{{$event->pivot->auto_schedule === config('enums.YES') ? $event->pivot->frequency . '%' : '-'}}</td>
                                        <td class="col-3">{{ucfirst($event->pivot->auto_schedule)}}</td>
                                        <td class="col-3">{{config('enums.schedulable_weeks')[$event->pivot->schedule_week] ?? 'None'}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <h5>Instruments</h5>
                            </div>

                            <div class="col-md-3 d-flex justify-content-end">
                                <a href="{{route('instrument-new', $musician->id)}}"><i class="btn-icon-prepend text-primary" data-feather="plus-circle"></i></a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Primary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($musician->instruments as $instrument)
                                        <tr>
                                            <td>
                                                <a href="{{route('instrument-edit', ['musician' => $musician->id, 'instrument' => $instrument->id])}}">{{$instrument->name}}</a>
                                            </td>
                                            <td>{{ ucfirst($instrument->primary) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <h5>Blackout Dates</h5>
                            </div>

                            <div class="col-md-3 d-flex justify-content-end">
                                <a href="{{route('blackout-new', $musician->id)}}"><i class="btn-icon-prepend text-primary" data-feather="plus-circle"></i></a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Period</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($musician->blackouts()->where('end', '>', date(config('app.DATE_FORMAT')))->get() as $blackout)
                                        <tr>
                                            <td>
                                                <a href="{{route('blackout-edit', ['musician' => $musician->id, 'blackout' => $blackout->id])}}">
                                                    {{$blackout->start->format(config('app.DISPLAY_DATE_FORMAT'))}} - {{$blackout->end->format(config('app.DISPLAY_DATE_FORMAT'))}}
                                                </a>
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
    </div>
    @endif
@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/select2/js/select2.min.js') !!}
    {!! Html::script('/assets/js/util.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/select2.js') !!}
@endpush
