@php

    $params = [
                'delete_route' => 'email-template.destroy',
                'create_route' => 'email-template.create'
              ];
@endphp

@extends('layouts.list', $params)

@section('title', 'Epark')

@section('content_header')
    <h1>    
        <i class="fa fa-hospital-o"> {{ request()->session()->get('hospital_name') }}</i>
        -
        <i class="fa fa-gears"> テンプレート管理</i>
    </h1>
@stop

@section('table')
  @include('layouts.partials.pagination-label', ['paginator' => $email_templates])
    <table id="example2" class="table table-bordered table-hover table-striped mb-5">
      <thead>
      <tr>
          <th>ID</th>
          <th>テンプレート名</th>
          <th>編集</th>
          <th>操作</th>
          <th>削除</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($email_templates as $email_template)
          <tr>
              <td>{{ $email_template->id }}</td>
              <td>{{ $email_template->title }}</td>
              <td>
                  <a href="{{ route('email-template.edit', $email_template->id) }}"
                    class="btn btn-primary">
                    <i class="fa fa-edit text-bold"> 編集</i>
                    </a>
              </td>
              <td>
                <a href="{{ route('email-template.copy', $email_template->id) }}" class="btn btn-default">コピー</a>
              </td>
              <td>
                  <button class="btn btn-danger delete-btn delete-popup-btn" data-id="{{ $email_template->id }}">
                        <i class="fa fa-trash"></i>
                  </button>
              </td>
          </tr>
      @endforeach
      </tbody>
    </table>
    {{ $email_templates->links() }}
@stop