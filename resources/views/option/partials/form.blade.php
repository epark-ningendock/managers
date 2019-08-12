<div class="table-responsive">
    <input type="hidden" name="lock_version" value="{{ $option->lock_version or '' }}" />
    <table class="table table-bordered mb-5 mt-5">

        <tr>

            <td class="gray-column">
                <label for="name">
                    {{ trans('messages.option_name') }}
                    <spna class="text-danger">(*)</spna>
                </label>
            </td>

            <td>

                <div class="form-group @if ($errors->has('name')) has-error @endif">
                    <input type="text" class="form-control" name="name" id="name"
                           placeholder="{{ trans('messages.option_name') }}"
                           value="{{ old('name', (isset($option) ? $option->name : '')) }}"/>
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                </div>
            </td>

        </tr>

        <tr>

            <td class="gray-column">
                <label for="confirm">{{ trans('messages.option_description') }}</label>
            </td>

            <td>
                <div class="form-group @if ($errors->has('confirm')) has-error @endif">
                                <textarea name="confirm" id="confirm" rows="6"
                                          placeholder="{{ trans('messages.option_description') }}"
                                          class="form-control">{{ old('confirm', (isset($option) ? $option->confirm : '')) }}</textarea>
                    <span class="text-muted d-block text-right" style="text-align: right; display: block;">128文字</span>
                    @if ($errors->has('confirm')) <p
                            class="help-block">{{ $errors->first('confirm') }}</p> @endif
                </div>
            </td>

        </tr>

        <tr>

            <td class="gray-column">
                <label for="price">
                    {{ trans('messages.price') }}
                    <spna class="text-danger">(*)</spna>
                </label>
            </td>

            <td>

                <div class="form-group d-inline-block @if ($errors->has('price')) has-error @endif"
                     style="display: inline-block; min-width: 150px;">
                    <input type="text" class="form-control" name="price" id="price"
                           placeholder="{{ trans('messages.price') }}"
                           value="{{ old('price', (isset($option) ? $option->price : '')) }}"/>
                    @if ($errors->has('price')) <p class="help-block">{{ $errors->first('price') }}</p> @endif
                </div>
                <span class="input-side-text">円（税込）</span>
            </td>

        </tr>

        <tr>

            <td class="gray-column">
                <label for="tax_class_id">
                    {{ trans('messages.tax_classification') }}
                    <spna class="text-danger">(*)</spna>
                </label>
            </td>

            <td>

                <div class="form-group @if ($errors->has('tax_class_id')) has-error @endif">
                    @if(isset($tax_classes))
                        <select name="tax_class_id" id="tax_class_id" class="form-control">
                            @foreach ($tax_classes as $tax_class)

                                <option
                                        @if ( (old('tax_class_id') == $tax_class->id) ||  isset($option->tax_class_id) && ($option->tax_class_id ==$tax_class->id) )
                                        selected="selected"
                                        @endif
                                        value="{{ $tax_class->id }}"> {{ $tax_class->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('tax_class_id')) <p
                                class="help-block">{{ $errors->first('tax_class_id') }}</p> @endif
                    @else
                        <span class="text-danger">税区分</span>
                    @endif
                </div>

            </td>

        </tr>

    </table>

</div>