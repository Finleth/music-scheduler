@extends('layout.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Schedule</h4>
        </div>

        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{route('musician-create')}}" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0">
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
                                                    <a href="#">{{$event->schedule_event_type->title}}: {{$event->musician->first_name}}</a>
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
                @if(!empty($musicians))
                    {{$musicians->appends(\Request::except('page'))->render()}}
                @endif
            </div>
        </div>
    </div>
@endsection
