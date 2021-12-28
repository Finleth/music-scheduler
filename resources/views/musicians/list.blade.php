@extends('layout.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Musicians</h4>
        </div>

        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{route('musician-create')}}" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="edit"></i>
                Add Musician
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
                                    <th>Name</th>
                                    <th>Schedule</th>
                                    <th>Main Instrument</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($musicians as $musician)
                                    <tr>
                                        <td>
                                            <a href="<?= route('musician-edit', $musician->id) ?>"><?= $musician->last_name . ', ' . $musician->first_name ?></a>
                                        </td>
                                        <td>
                                            @if ($musician->schedule_event_types->isNotEmpty())
                                                @foreach ($musician->schedule_event_types as $event)
                                                    <div><?= $event->title ?></div>
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($musician->instruments->isNotEmpty())
                                                @foreach ($musician->instruments as $instrument)
                                                    @if ($instrument->primary === config('enums.YES'))
                                                        <div><?= $instrument->name ?></div>
                                                    @endif
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td><?= ucfirst($musician->status) ?></td>
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
