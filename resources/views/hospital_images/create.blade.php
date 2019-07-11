@extends('layouts.form')

@section('content_header')
    <h1>画像登録 {{ request()->session()->get('hospital_name') }}</h1>
@stop
@section('form')
    <!-- フラッシュメッセージ -->
    @if (session('success'))
        @include('layouts.partials.message')
    @endif
    <h3 class="std-title">施設画像登録</h3>


    <p class="sub-title text-bold">
        </b><span class="text-danger">(*)</span>以下の項目を入力してください。
    </p>
    {!! Form::open(['url' => route('hospital.image.store', $hospital_id), 'files' => true]) !!}
        {{ csrf_field() }}

        @includeIf('hospital_images.partials.form')

        <div class="action-btn-wrapper text-center mb-5 pb-5">
            <button class="btn btn-primary" type="submit">登録</button>
        </div>
    {!! Form::close() !!}

@stop

