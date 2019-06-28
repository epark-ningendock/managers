@extends('layouts.form')

@section('content_header')
    <h1>オプション管理 &gt; &GT;{{ request()->session()->get('hospital_name') }}</h1>
@stop

@section('form')

    <h3 class="std-title">オプション登録</h3>
    <p class="sub-title text-bold">
        </b><span class="text-danger">(*)</span>以下の項目を入力してください。
    </p>
    <form method="post" action="{{ route('hospital.image.store', $hospital_id) }}" enctype="multipart/form-data">
        {{ csrf_field() }}

        @includeIf('hospital_images.partials.form')

        <div class="action-btn-wrapper text-center mb-5 pb-5">
            <button class="btn btn-primary" type="submit">登録</button>
        </div>

    </form>

@stop