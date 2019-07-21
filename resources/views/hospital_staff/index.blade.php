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
    <h1>
        <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
        - 
        <i class="fa fa-users"> 医療機関スタッフ管理</i>
    </h1>
@stop



@section('table')
    <div class="count-paginate-bar">
        <div class="row">
            <div class="col-sm-6">
                <div class="display-total text-left mr-5 ">
                    全{{ $hospital_staffs->total() }} 件中
                    {{ ( $hospital_staffs->currentPage() * $hospital_staffs->perPage() ) - $hospital_staffs->perPage() + 1 }}件
                    @if ($hospital_staffs->currentPage() === $hospital_staffs->lastPage())
                      ~ {{ $hospital_staffs->total() }} 件を表示
                    @else
                      ~ {{ $hospital_staffs->currentPage() * $hospital_staffs->perPage() }} 件を表示
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
            </div>
        </div>
    </div>
    <table id="example2" class="table table-bordered table-hover table-striped mb-5 mt-5">
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
                       class="btn btn-primary">
                       <i class="fa fa-edit text-bold"> 編集</i>
                    </a>
                <td>
                    <button class="btn btn-primary delete-btn delete-popup-btn" data-id="{{ $hospital_staff->id }}">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $hospital_staffs->links() }}
@stop