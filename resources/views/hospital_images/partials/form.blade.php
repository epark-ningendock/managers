<div class="box-body">
    <div class="form-group">
        <label>施設メイン画像</label>
        {{Form::file("main", ['class' => 'field'])}}
        @if ($errors->has('image'))
            {{ $errors->first('image') }}
        @endif
    </div>
    <div class="form-group">
        @for ($i = 1; $i <= 4; $i++)
            <label>施設サブ画像 {{ $i }}</label>
            {{Form::file("sub".'['.$i.']', ['class' => 'field'])}}
            @if ($errors->has('image'.$i))
                {{ $errors->first('image'.$i) }}
            @endif
        @endfor
    </div>
</div>
