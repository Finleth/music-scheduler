@extends('layout.master')

@push('plugin-styles')
    {!! Html::style('/assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') !!}
    {!! Html::style('/assets/plugins/select2/css/select2.min.css') !!}
    {!! Html::style('/assets/plugins/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css') !!}
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Blackout</h4>
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
                        <label for="start" class="col-sm-2 col-form-label text-right">Start</label>
                        <div class="form-group col-sm-4">
                            <div class="input-group date timepicker-date-only" id="starttime" data-target-input="nearest">
                                <input type="text" name="start" class="form-control datetimepicker-input" data-target="#starttime"
                                       value="{{$blackout->start->format(config('app.INPUT_DATE_FORMAT'))}}">
                                <div class="input-group-append" data-target="#starttime" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6"></div>

                        <label for="end" class="col-sm-2 col-form-label text-right">End</label>
                        <div class="form-group col-sm-4">
                            <div class="input-group date timepicker-date-only" id="endtime" data-target-input="nearest">
                                <input type="text" name="end" class="form-control datetimepicker-input" data-target="#endtime"
                                       value="{{$blackout->end->format(config('app.INPUT_DATE_FORMAT'))}}">
                                <div class="input-group-append" data-target="#endtime" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap grid-margin">
            <a href="{{ route('musician-edit', $blackout->musician->id) }}" class="btn btn-outline-secondary btn-icon-text mr-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="x"></i>
                Cancel
            </a>
            <button type="submit" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0" data-cy="submit">
                <i class="btn-icon-prepend" data-feather="save"></i>
                Save
            </button>
            @if(Route::current()->getName() != 'blackout-new')
            <a href="{{ route('blackout-delete', ['musician' => $blackout->musician->id, 'blackout' => $blackout->id]) }}" class="btn btn-outline-danger btn-icon-text mr-2 mb-2 mb-md-0">
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
