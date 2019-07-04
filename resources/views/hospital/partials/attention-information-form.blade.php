@php
if(isset($hospital)) {
  $hospital_details = $hospital->hospital_details;
}

$o_minor_ids = collect(old('minor_ids'));
$o_minor_values = collect(old('minor_values'));
@endphp
  <div class="box-body">
    <label for="name">PV・予約</label>
    <div class="row">
        <div class='col-sm-4 text-bold'>
          <p>PV件数 
            <span>{{ $hospital->pv_count }}</span> 件
          </p>
        </div>
        <div class="form-group col-sm-4 form-inline @if ($errors->has('pvad')) has-error @endif">
          <label>PR</label>
          <input type="text" class="form-control pr-input" id="pvad" name="pvad" value="{{ isset($hospital->pvad) ? $hospital->pvad : 0 }}" placeholder="">
        </div>
        @if ($errors->has('pvad')) <p class="help-block">{{ $errors->first('pvad') }}</p> @endif
        <div class='col-sm-4 checkbox'>
          <label class="ml-3">
              {{ Form::hidden('is_pickup') }}
              {{ Form::checkbox('is_pickup', 1, $hospital->is_pickup) }}
              <p class='text-bold'>ピックアップ</p>
          </label>
        </div>
      </div>
    <table class="table table-bordered">
      @foreach($middles as $middle)
        <tr>
          @if(!isset($last) || $middle != $last)
            <td class='text-bold' colspan="{{ collect($middle->minor_classifications)->count() }}">{{ $middle->name }}</td>
            @php
              $last = $middle
            @endphp
          @endif
          <td>
            @foreach($middle->minors_with_fregist_order as $index => $minor)
              @php
                $minor_value = '';
                if($o_minor_ids->isNotEmpty()) {
                  $search_index = $o_minor_ids->search(function($m_id) use ($minor) {
                    return $m_id == $minor->id;
                  });
                  if ($search_index >= 0) {
                    $minor_value = $o_minor_values[$search_index];
                  }
                } else if (isset($hospital_details)) {
                  $temp = $hospital_details->where('minor_classification_id', $minor->id)->flatten(1);
                  if ($temp->isNotEmpty()) {
                    $minor_value = $minor->is_fregist == '1' ? $temp[0]->select_status : $temp[0]->inputstring;
                  }
                }
              @endphp
              <input type="hidden" name="minor_ids[]" value="{{ $minor->id }}" />
              @if($minor->is_fregist == '1')
                <input type="checkbox" class="minor-checkbox" name="minor_values[]"
                        id="{{ 'minor_id_'.$minor->id }}"
                        {{ $minor_value == 1 ? 'checked' : '' }} value="{{ $minor->id }}" />
                <label class="mr-2" for="{{ 'minor_id_'.$minor->id }}">{{ $minor->name }}</label>
              @else
              <div class="form-group mt-3">
                  <label>{{ $minor->name }}</label>
                  <textarea class="form-control minor-text @if ($index > 0) mt-2 @endif" name="minor_values[]" cols="30" rows="5">{{ $minor_value }}</textarea>
              </div>
              @endif
            @endforeach
          </td>
        </tr>
      @endforeach
    </table>
  </div>

  <div class="box-primary">
    <div class="box-footer">
      <a href="{{ url()->previous() }}" class="btn btn-default">戻る</a>
      <button type="submit" class="btn btn-primary">作成</button>
    </div>
  </div>

  @section('script')
  <script>
      (function ($) {

          /* ---------------------------------------------------
          // minor checkbox values
          -----------------------------------------------------*/
          (function () {
              const change = function(ele) {
                  if (ele.prop('checked')) {
                      ele.next('input:hidden').remove();
                  } else {
                      $('<input type="hidden" name="minor_values[]" value="0"/>').insertAfter(ele);
                  }
              };

              $('.minor-checkbox').each(function(index, ele) {
                  ele = $(ele);
                  ele.change(function() {
                      change(ele);
                  });
                  change(ele);
              });
          })();

      })(jQuery);
  </script>
@stop

<style>
.pr-input {
  width: 50%
}
</style>