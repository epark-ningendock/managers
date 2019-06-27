@php

    $params = [
                'delete_route' => 'email-template.destroy',
                'create_route' => 'email-template.create'
              ];
@endphp

@extends('layouts.list', $params)

@section('title', 'Epark')

@section('content_header')
  <h1>テンプレート管理 &gt; &GT;{{ request()->session()->get('hospital_name') }}</h1>
@stop

@section('table')
    <table id="example2" class="table table-bordered table-hover mb-5 mt-5">
      <thead>
      <tr>
          <th>ID</th>
          <th>テンプレート名</th>
          <th>編集</th>
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
                    class="btn btn-primary">編集</a>
              <td>
                  <button class="btn btn-danger delete-btn delete-popup-btn" data-id="{{ $email_template->id }}">
                      削除
                  </button>
              </td>
          </tr>
      @endforeach
      </tbody>
    </table>
    {{ $email_templates->links() }}
@stop