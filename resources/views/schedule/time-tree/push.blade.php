@extends('layout.master')

@push('plugin-styles')
    {!! Html::style('/assets/plugins/select2/css/select2.min.css') !!}
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Push Schedule Events: {{$calendar->name}}</h4>
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
                        <label for="batch_id" class="col-sm-2 col-form-label">Batch</label>
                        <div class="form-group col-sm-4">
                            <select id="batch-selector" name="batch_id" class="form-control" required>
                                <option value=""></option>
                                @foreach ($scheduleGenerations as $batch)
                                    <option value="{{$batch->id}}" data-event-count="{{$batch->events_created}}">
                                        {{sprintf('Batch %s (%s)', $batch->batch, $batch->created_at->format(config('app.DISPLAY_DATE_FORMAT')))}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6"></div>

                        <label class="col-sm-2 col-form-label">Event Count</label>
                        <div id="batch-event-count" class="form-group col-sm-4">
                            -
                        </div>
                        <div class="col-sm-6"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap grid-margin">
            <a href="{{ route('schedule-list', $calendar->id) }}" class="btn btn-outline-secondary btn-icon-text mr-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="x"></i>
                Cancel
            </a>
            <button type="submit" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0" data-cy="submit">
                <i class="btn-icon-prepend" data-feather="save"></i>
                Push
            </button>
        </div>
    </form>
@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/select2/js/select2.min.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/select2.js') !!}
    {!! Html::script('/assets/js/schedule-time-tree.js') !!}
@endpush
