<div class="table-responsive">
    <table class="table table-bordered mb-5 mt-5">

        <tr>

            <td class="gray-column">
                <label for="name">
                    {{ trans('messages.option_name') }}
                    <spna class="text-danger">(*)</spna>
                </label>
            </td>

            <td>

                <fieldset>
                    <div>
                        <input id="file" type="file" name="image">

                        @if ($errors->has('image'))
                            {{ $errors->first('image') }}
                        @endif
                    </div>
                </fieldset>
            </td>

        </tr>

    </table>

</div>