@extends('layouts.form')

@section('content_header')
  <h1>基本情報</h1>
@stop

@section('form')
  <form method="POST" action="{{ route('hospital.store') }}">

    <div class="box-body">

      <div class="form-group">

        <div class="row">

          <div class="col-md-4">
            {{ __('State') }}
          </div>

          <div class="col-md-8">
            <div class="custom-control custom-radio custom-control-inline" style="display: inline-block;margin-right: 30px;">
              <input type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input">
              <label class="custom-control-label" for="customRadioInline1">Private</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline" style="display: inline-block;">
              <input type="radio" id="customRadioInline2" name="customRadioInline1" class="custom-control-input">
              <label class="custom-control-label" for="customRadioInline2">Public</label>
            </div>
          </div>

        </div>

      </div>

    </div>



{{--    <div class="form-group @if ($errors->has('name')) has-error @endif">--}}
{{--      <label for="name">スタッフ名</label>--}}
{{--      <input type="text" class="form-control" id="name" name="name"--}}
{{--             value="{{ old('name', (isset($staff) ? $staff->name : null)) }}"--}}
{{--             placeholder="スタッフ名">--}}
{{--      @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif--}}
{{--    </div>--}}



{{--    @include('staff.partials.form')--}}
  </form>
@stop