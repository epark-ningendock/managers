<!-- @php
    use \App\Enums\HospitalEnums;
    $params = [
    ];
@endphp -->

<!-- @extends('layouts.list', $params) -->

<!-- ページタイトルを入力 -->
@section('title', 'Epark')

<!-- ページの見出しを入力 -->
@section('content_header')
    <h1>医療機関</h1>
@stop

@section('search')
<div class="contract-form-alert">
    @include('commons.alert', $alert = [
        'type' => 'success',
        'message' => trans('messages.created', ['name' => trans('messages.names.contractor')])
    ])
</div>
<div>
    <form id="hospital-contract-search" class="form-horizontal" action="{{ route('hospital.search.contractInfo') }}">

        {{ csrf_field() }}

        <h5 class="sm-title">既存の登録情報を使用する</h5>

        <div class="form-group">
            <label for="contract_info_search_word" class="col-md-4">医療機関ID・医療機関名・契約者名</label>
            <div class="col-md-7">
                <input type="text" class="form-control" id="contract_info_search_word" name="contract_info_search_word" />
            </div>
            <div class="col-md-1">
              <button type="submit" class="btn btn-primary">検索</button>
            </div>
        </div>

        <hr/>
    </form>
    <form id="contract-form" class="form-horizontal" method="post" action="{{ route('contract.store') }}">

      {{ csrf_field() }}

      <h5 class="sm-title">契約情報</h5>

      <div class="form-group">
          <label for="contractor_name_kana" class="col-md-4">契約者名（フリガナ）</label>
          <div class="col-md-8">
              <input type="text" class="form-control" id="contractor_name_kana" name="contractor_name_kana" value="{{ (isset($contract_information->contractor_name_kana) ) ? $contract_information->contractor_name_kana : null }}" />
          </div>
      </div>

      <div class="form-group">
          <label for="contractor_name" class="col-md-4">契約者名</label>
          <div class="col-md-8">
              <input type="text" class="form-control" id="contractor_name" name="contractor_name" value="{{ (isset($contract_information->contractor_name) ) ? $contract_information->contractor_name : null }}"  />
          </div>
      </div>

      <div class="form-group">
          <label for="application_date" class="col-md-4">申込日</label>
          <div class="col-md-8 form-inline">
              {{ Form::text('application_date', old('application_date'), ['class' => 'datetimepicker form-control', 'id' => 'application_date-field', 'placeholder' => '2019-04-01']) }}
          </div>
      </div>

      <div class="form-group">
          <label for="billing_start_date" class="col-md-4">課金開始日</label>
          <div class="col-md-8 form-inline">
              {{ Form::text('billing_start_date', old('billing_start_date'), ['class' => 'datetimepicker form-control', 'id' => 'billing_start_date-field', 'placeholder' => '2019-04-01']) }}
          </div>
      </div>

      <div class="form-group">
          <label for="cancellation_date" class="col-md-4">解約日</label>
          <div class="col-md-8 form-inline">
              {{ Form::text('cancellation_date', old('cancellation_date'), ['class' => 'datetimepicker form-control', 'id' => 'cancellation_date-field', 'placeholder' => '2019-04-01']) }}
          </div>
      </div>


      <h5 class="sm-title">契約者情報</h5>


      <div class="form-group">
          <label for="representative_name_kana" class="col-md-4">代表者名（フリガナ）</label>
          <div class="col-md-8">
              <input type="text" class="form-control" id="representative_name_kana" name="representative_name_kana" value="{{ (isset($contract_information->representative_name_kana) ) ? $contract_information->representative_name_kana : null }}"  />
          </div>
      </div>


      <div class="form-group">
          <label for="representative_name" class="col-md-4">代表者名</label>
          <div class="col-md-8">
              <input type="text" class="form-control" id="representative_name" name="representative_name" value="{{ (isset($contract_information->representative_name) ) ? $contract_information->representative_name : null }}"  />
          </div>
      </div>


      <div class="form-group">
          <label for="postcode" class="col-md-4">郵便番号</label>
          <div class="col-md-8">
              <input type="text" class="form-control" id="postcode" name="postcode" value="{{ (isset($contract_information->postcode) ) ? $contract_information->postcode : null }}"  />
          </div>
      </div>

      <div class="form-group">
          <label for="address" class="col-md-4">住所</label>
          <div class="col-md-8">
              <input type="text" class="form-control" id="address" name="address"  value="{{ (isset($contract_information->address) ) ? $contract_information->address : null }}" />
          </div>
      </div>

      <div class="form-group">
          <label for="tel" class="col-md-4">電話番号</label>
          <div class="col-md-8">
              <input type="text" class="form-control" id="tel" name="tel" value="{{ (isset($contract_information->tel) ) ? $contract_information->tel : null }}"  />
          </div>
      </div>


      <div class="form-group">
          <label for="fax" class="col-md-4">FAX番号</label>
          <div class="col-md-8">
              <input type="text" class="form-control" id="fax" name="fax" value="{{ (isset($contract_information->fax) ) ? $contract_information->fax : null }}"  />
          </div>
      </div>

      <div class="form-group">
          <label for="email" class="col-md-4">メールアドレス</label>
          <div class="col-md-8">
              <input type="email" class="form-control" id="email" name="email" value="{{ (isset($contract_information->hospital_staff->email) ) ? $contract_information->hospital_staff->email : null }}"  />
          </div>
      </div>

      <h5 class="sm-title">代表アカウント情報</h5>

      <div class="form-group">
          <label for="login_id" class="col-md-4">ログインID</label>
          <div class="col-md-8">
              <input type="text" class="form-control" id="login_id" name="login_id" />
          </div>
      </div>

      <div class="form-group">
          <label for="password" class="col-md-4">パスワード</label>
          <div class="col-md-8">
              <input type="password" class="form-control" id="password" name="password" />
          </div>
      </div>

      <h5 class="sm-title">ドックネットID</h5>
      <div class="form-group">
          <label for="old_karada_dog_id" class="col-md-4">からだドックID</label>
          <div class="col-md-8">
              <input type="text" class="form-control" id="old_karada_dog_id" name="old_karada_dog_id" value="{{ (isset($contract_information->old_karada_dog_id) ) ? $contract_information->old_karada_dog_id : null }}"  />
          </div>
      </div>

      <div class="form-group">
          <label for="karada_dog_id" class="col-md-4">からだドック医療機関ID</label>
          <div class="col-md-8">
              <input type="text" class="form-control" id="karada_dog_id" name="karada_dog_id"  value="{{ (isset($contract_information->karada_dog_id) ) ? $contract_information->karada_dog_id : null }}" />
          </div>
      </div>
      <div class="form-group">
          <div class="col-md-12">
            <button type="submit" class="btn btn-success pull-right">登録</button>
          </div>
      </div>
    </form>
</div>

@stop

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ url('css/bootstrap-datepicker.min.css') }}">
@endpush

@push('js')
    <script src="{{ url('js/handlebars.js') }}"></script>
    <script src="{{ url('js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('js/bootstrap-datepicker.ja.min.js') }}"></script>
    <script src="{{ url('js/bootstrap3-typeahead.min.js') }}"></script>
    <script type="text/javascript">

        (function ($) {
            var route = "{{ route('hospital.search.text') }}";
            $('#s_text').typeahead({
                source: function (term, process) {
                    return $.get(route, {term: term}, function (data) {
                        return process(data);
                    });
                },
                displayText: function (item) {
                    return item.name + ' - ' + item.address1;
                },
                afterSelect: function (item) {
                    $('#s_text').val(item.name);
                }
            });
            $('.datetimepicker').datepicker({
                language:'ja',
                format: 'yyyy-mm-dd',
            });

        })(jQuery);

    </script>

@endpush
