@php
  use \App\Enums\StaffStatus;
  use \App\Enums\Authority;
  use \App\Enums\Permission;

  $params = [
              'delete_route' => 'course.destroy'
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
      <i class="fa fa-book"> 検査コース管理</i>
  </h1>
@stop

@section('button')
  <div class="pull-right">
    <a class="btn btn-primary mr-2" href="{{ route('course.sort') }}">並び替え</a>
    <a class="btn btn-primary btn-create" href="{{ route('course.create') }}">新規作成</a>
  </div>
@stop

<!-- search section -->
@section('table')
  <div class="table-responsive">
    @include('layouts.partials.pagination-label', ['paginator' => $courses])
    <table id="example2" class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
        <th>検査コースID</th>
        <th>検査コース名</th>
        <th>WEB受付</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      @foreach ($courses as $course)
      <tr>
          <td>{{ $course->id }}</td>
          <td>{{ $course->name }}</td>
          <td>{{ $course->web_reception == '1' ? '受け付ける' : '受け付けない' }}</td>
          <td>
            <a class="btn btn-primary"
               href="{{ route('course.edit', $course->id) }}">
               <i class="fa fa-edit text-bold"> 編集</i>
            </a>
            <button class="btn btn-danger delete-btn delete-popup-btn ml-3" data-id="{{ $course->id }}">
              <i class="fa fa-trash"></i>
            </button>
            <a class="btn btn-success ml-3" href="{{ route('course.copy', $course->id) }}">
                <i class="fa fa-copy text-bold"></i>
            </a>
            <button class="btn btn-default ml-3">
              プレビュー
            </button>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  {{ $courses->links() }}
  <style>
   td, th {
     text-align: center;
   }
  </style>
@stop