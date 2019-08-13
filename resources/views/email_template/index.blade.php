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
        <span> テンプレート管理</span>
    </h1>
@stop

@section('table')
  @include('layouts.partials.pagination-label', ['paginator' => $email_templates])
    <table id="email-template" class="table no-border table-hover table-striped mb-5">
      <thead>
      <tr>
          <th>ID</th>
          <th>テンプレート名</th>
          <th></th>
      </tr>
      </thead>
      <tbody>
      @foreach ($email_templates as $email_template)
          <tr>
              <td class="email-template-id">{{ $email_template->id }}</td>
              <td class="email-template-title">{{ $email_template->title }}</td>
              <td class="text-right">
                  <a href="{{ route('email-template.edit', $email_template->id) }}"
                    class="btn btn-primary">
                    <i class="fa fa-edit"> 編集</i>
                    </a>
                  <button class="btn btn-primary delete-btn delete-popup-btn" data-id="{{ $email_template->id }}">
                      <i class="fa fa-trash"></i>
                  </button>
          </tr>
      @endforeach
      </tbody>
    </table>
    {{ $email_templates->links() }}
@stop