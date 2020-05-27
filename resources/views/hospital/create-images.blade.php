@extends('layouts.hospital_image_form')

@section('content_header')
    <h1>画像登録 - {{ $hospital->name }} </h1>
@stop
@section('form')
    {!! Form::open(['url' => route('hospital.image.store', $hospital_id), 'files' => true]) !!}
        {{ csrf_field() }}

        @includeIf('hospital.partials.images-form')

        <div class="action-btn-wrapper text-center mb-5 pb-5">
            <button class="btn btn-primary" type="submit">登録</button>
        </div>
    {!! Form::close() !!}

@stop

