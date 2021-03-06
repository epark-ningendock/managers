<form class="mb-5" method="get" action="{{ route('customer.index') }}">
    <input type="hidden" name="pagination" value="{{ request('pagination') }}">
    <div class="row">

        <div class="col-sm-6">
            <div class="form-group">
                <label for="customer_id">顧客ID</label>
                <input value="{{ request('customer_id') }}" id="customer_id" type="text" class="form-control" name="customer_id" />
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group date-picker">
                <label for="name">{{ trans('messages.name') }}</label>
                <input value="{{ request('name') }}" id="name" type="text" class="form-control date-picker" name="name" />
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="registration_card_number">{{ trans('messages.registration_card_number') }}</label>
                <input value="{{ request('registration_card_number') }}" id="registration_card_number" type="text" class="form-control" name="registration_card_number" />
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="tel">{{ trans('messages.tel') }}</label>
                <input value="{{ request('tel') }}" id="tel" type="text" class="form-control" name="tel" />
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="birthday">{{ trans('messages.birthday') }}</label>
                <div class="input-group date datepicker"  data-date-format="yyyy-mm-dd" data-provide="datepicker">
                    <input  autocomplete="off"  class="form-control" name="birthday" id="birthday"
                           value="{{ request('birthday') }}"/>
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-6">
            <label for=""></label>
            <div class="action-btn-wrapper text-right">

                <button type="submit" class="btn btn-primary">{{ trans('messages.search') }}</button>
                <a href="{{ route('customer.index') }}" class="btn btn-default">{{ trans('messages.clear_search') }}</a>
            </div>
        </div>

    </div>

</form>
<div class="action-btn-bar text-right mt-4 mb-4">
    <a href="{{ route('customer.create') }}" class="btn btn-primary">{{ trans('messages.create_new') }}</a>
</div>

<div class="paginate-select-box text-right">
    <label for="record_per_page">表示件数</label>
    <select class="form-control ml-2" name="pagination" id="paginate-selection" style="display: inline-block; width: 50px;">
        <option value="{{ route('customer.index', ['pagination' => 10]) }}" {{ inputSelectBoxSelected('pagination', 10) }}>10</option>
        <option value="{{ route('customer.index', ['pagination' => 20]) }}" {{ inputSelectBoxSelected('pagination', 20) }}>20</option>
        <option value="{{ route('customer.index', ['pagination' => 50]) }}" {{ inputSelectBoxSelected('pagination', 50) }}>50</option>
        <option value="{{ route('customer.index', ['pagination' => 100]) }}" {{ inputSelectBoxSelected('pagination', 100) }}>100</option>
    </select>
</div>