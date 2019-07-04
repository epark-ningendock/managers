<div class="box-body">
    <div class="form-group">
        <label>施設画像</label>
        <input id="file" type="file" name="main_image">
        @if ($errors->has('image'))
            {{ $errors->first('image') }}
        @endif
    </div>
</div>