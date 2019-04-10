@extends('adminlte::page')

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>医療機関スタッフ管理</h1>
@stop

<!-- ページの内容を入力 -->
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <a class="btn btn-success" href="{{ url('/hospital-staff/create') }}">新規作成</a>
                </div>

                @include('commons.message')


                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover mb-5 mt-5">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>スタッフ施設名</th>
                            <th>ログインID</th>
                            <th>編集</th>
                            <th>削除</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($hospital_staffs as $hospital_staff)
                            <tr>
                                <td>{{ $hospital_staff->id }}</td>
                                <td>{{ $hospital_staff->name }}</td>
                                <td>{{ $hospital_staff->login_id }}</td>
                                <td>
                                    <a href="{{ route('hospital-staff.edit', $hospital_staff->id) }}"
                                       class="btn btn-primary">編集</a>
                                <td>
                                    <a href="#" class="btn btn-danger delete-popup-btn"
                                       data-target-form="#delete-record-form" data-target="#delete-confirmation"
                                       data-id="{{ $hospital_staff->id }}">削除</a>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                    </table>

                    {{ $hospital_staffs->links() }}


                </div>
            </div>
        </div>
    </div>

    <form id="delete-record-form" class="hide" method="POST"
          action="{{ route('hospital-staff.destroy', ':id') }}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
    </form>

    @include('commons.delete-modal-box', [
        'id' => 'delete-confirmation',
        'title' =>trans('commons.delete_popup_title'),
        'content' => trans('commons.delete_popup_content', ['name' => trans('messages.names.hospital_staff')])
    ])


@stop