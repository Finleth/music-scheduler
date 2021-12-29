@extends('layout.master')

@push('plugin-styles')
    {!! Html::style('/assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') !!}
    {!! Html::style('/assets/plugins/select2/css/select2.min.css') !!}
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Schedule Event</h4>
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
                            <input type="text" name="title" class="form-control" value="{{$scheduleEvent->schedule_event_type->title}}" readonly>
                        </div>

                        <div class="col-sm-6"></div>

                        <label for="musician_id" class="col-sm-2 col-form-label">Musician</label>
                        <div class="form-group col-sm-4">
                            <select name="musician_id">
                                @foreach ($scheduleEvent->schedule_event_type->musicians as $musician)
                                    <option value="{{$musician->id}}" {{$musician->id === $scheduleEvent->musician->id ? 'selected' : ''}}>
                                        {{$musician->first_name . ' ' . $musician->last_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap grid-margin">
            <a href="{{ route('schedule-list') }}" class="btn btn-outline-secondary btn-icon-text mr-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="x"></i>
                Cancel
            </a>
            <button type="submit" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0" data-cy="submit">
                <i class="btn-icon-prepend" data-feather="save"></i>
                Save
            </button>
        </div>
    </form>
@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/select2/js/select2.min.js') !!}
    {!! Html::script('/assets/js/util.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/select2.js') !!}
@endpush
