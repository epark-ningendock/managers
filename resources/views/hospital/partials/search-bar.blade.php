<form id="hospital-contract-search" class="form-horizontal" action="{{ route('hospital.search.contractInfo') }}">

    {{ csrf_field() }}

    <h5 class="sm-title">既存の登録情報を使用する</h5>

    <div class="form-group @if( $errors->has('contract_info_search_word'))  has-error @endif">
        <label for="contract_info_search_word" class="col-md-4">医療機関ID・医療機関名・契約者名</label>
        <div class="col-md-7">
            <input type="text" class="form-control" id="contract_info_search_word" name="contract_info_search_word" value="{{ old('name', (isset($contractor_name_kana->name) ?? null)) }}" />
            @if ($errors->has('contract_info_search_word')) <p class="help-block">{{ $errors->first('contract_info_search_word') }}</p> @endif
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary">検索</button>
        </div>
    </div>

    <hr/>
</form>