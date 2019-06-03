@php

    $params = [
                'delete_route' => 'hospital-staff.destroy',
                'create_route' => 'hospital-staff.create'
              ];
@endphp

@extends('layouts.list', $params)

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>医療機関スタッフ</h1>
@stop



@section('table')
    <table id="example2" class="table table-bordered table-hover mb-5 mt-5">
        <thead>
        <tr>
            <th>ログインID</th>
            <th>医療機関スタッフ名</th>
            <th>メールアドレス</th>
            <th>編集</th>
            <th>削除</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($hospital_staffs as $hospital_staff)
            <tr>
                <td>{{ $hospital_staff->login_id }}</td>
                <td>{{ $hospital_staff->name }}</td>
                <td>{{ $hospital_staff->email }}</td>
                <td>
                    <a href="{{ route('hospital-staff.edit', $hospital_staff->id) }}"
                       class="btn btn-primary">編集</a>
                <td>
                    <button class="btn btn-danger delete-btn delete-popup-btn" data-id="{{ $hospital_staff->id }}">
                        削除
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $hospital_staffs->links() }}
@stop