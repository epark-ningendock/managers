<div class="box box-primary form-box">
    <div class="form_title"><div class="number_circle">1</div> <span class="input_title">施設画像登録</span></div>
    <div class="form-group ">
        {{Form::label('main', '施設メイン画像',['class' => 'form_label'])}}
        <?php $main_image_category = $hospital->hospital_categories->firstWhere('image_order', $image_order::IMAGE_GROUP_FACILITY_MAIN); ?>

        @if(!is_null($main_image_category) && !is_null($main_image_category->hospital_image->path))
            <div class="image_area">
                <img src="/img/uploads/500-auto-{{$main_image_category->hospital_image->path}}" height="200">
                <p class="file_delete_text">
                    <a onclick="return confirm('{{ trans('messages.delete_image_popup_content') }}')" href="{{ route('hospital.delete_image', ['hospital' => $hospital->id, 'hospital_image_id' => $main_image_category->hospital_image_id]) }}">
                        <i class="icon-trash icon-white"></i>
                        ファイル削除
                    </a>
                </p>
            </div>
        @else
            <div class="no_image_area">
                <img src="/img/icon_noimage.png" width="50">
            </div>
        @endif
        <label class="file-upload btn btn-primary">
            ファイル選択 {{Form::file("main", ['class' => 'field'])}}
        </label>
        @if ($errors->has('main'))
            {{ $errors->first('main') }}
        @endif
    </div>

    <div class="form-group">
        {{Form::label('interview_1_caption', 'TOP',['class' => 'form_label'])}}
        <?php $title = $hospital->hospital_categories->firstWhere('image_order', $image_order::IMAGE_GROUP_TOP); ?>
        {{Form::text('title', is_null($title) ? '' : $title->title, ['class' => 'form-control'])}}
        @if ($errors->has('title'))
            <div class="error_message">
                {{ $errors->first('title') }}
            </div>
        @endif
    </div>

    <div class="form-group">
        {{Form::label('caption', '本文',['class' => 'form_label'])}}
        {{Form::textarea('caption', is_null($title) ? '' : $title->caption, ['class' => 'form-control', 'rows' => 4])}}
        @if ($errors->has('caption'))
            <div class="error_message">
                {{ $errors->first('caption') }}
            </div>
        @endif
    </div>
</div>

<div class="box box-primary form-box">
    <div class="form_title"><div class="number_circle">2</div> <span class="input_title">施設サブ画像</span></div>
    <div class="row">
        @for ($i = 1; $i <= 4; $i++)
        <div class="col-sm-6">
            {{Form::label('sub_'.$i, '施設サブ画像'.$i,['class' => 'form_label'])}}
            <?php $sub_image_category = $hospital->hospital_categories->where('image_order', $image_order::IMAGE_GROUP_FACILITY_SUB)->where('order2', $i)->first(); ?>
            @if (!is_null($sub_image_category))
                <div class="image_area">
                    <img src="/img/uploads/300-auto-{{$sub_image_category->hospital_image->path}}" width="150">
                </div>
                <p style="text-align: center; margin-top: 10px">
                    <a onclick="return confirm('この施設画像を削除します、よろしいですか？')" class="btn btn-mini btn-danger" href="{{ route('hospital.image.delete', ['hospital' => $hospital->id, 'hospital_category_id' => $sub_image_category->id, 'hospital_image_id' => $sub_image_category->hospital_image_id]) }}">
                        削除
                    </a>
                </p>
            @else
                <div class="no_image_area">
                    <img src="/img/icon_noimage.png" width="50">
                </div>
            @endif
            <label class="file-upload btn btn-primary">
                ファイル選択 {{Form::file("sub_".$i, ['class' => 'field'])}}
            </label>
            @if ($errors->has('image'))
                {{ $errors->first('image') }}
            @endif
        </div>
        @endfor
    </div>
</div>


<div class="box box-primary form-box">
    <div class="form_title"><div class="number_circle">3</div> <span class="input_title">こだわり</span></div>
    <div class="row">
        @for ($i = 1; $i <= 4; $i++)
            <div class="col-sm-6">
                {{Form::label('interview_1_caption', 'こだわり'.$i,['class' => 'form_label'])}}
                <?php $image_speciality = $hospital->hospital_categories->where('image_order', $image_order::IMAGE_GROUP_SPECIALITY)->where('order2', $i)->first(); ?>
                @if (!is_null($image_speciality) && !is_null($image_speciality->hospital_image->path))
                    <div class="image_area">
                        <img src="/img/uploads/300-auto-{{$image_speciality->hospital_image->path}}" width="150">
                        <p class="file_delete_text">
                            <a onclick="return confirm('{{ trans('messages.delete_image_popup_content') }}')" href="{{ route('hospital.delete_image', ['hospital' => $hospital->id, 'hospital_image_id' => $image_speciality->hospital_image_id]) }}">
                                <i class="icon-trash icon-white"></i>
                                ファイル削除
                            </a>
                        </p>
                    </div>
                @else
                    <div class="no_image_area">
                        <img src="/img/icon_noimage.png" width="50">
                    </div>
                @endif

                <label class="file-upload btn btn-primary">
                    ファイル選択 {{Form::file("speciality_".$i, ['class' => 'field'])}}
                </label>

                @if ($errors->has('speciality_'.$i))
                    <div class="error_message">
                    {{ $errors->first('speciality_'.$i) }}
                    </div>
                @endif

                <div class="form-group">
                    {{Form::label('interview_1_caption', 'タイトル',['class' => 'form_label'])}}
                    {{Form::text('speciality_'.$i.'_title', is_null($image_speciality) ? '' : $image_speciality->title, ['class' => 'form-control'])}}
                    @if ($errors->has('speciality_'.$i.'_title'))
                        <div class="error_message">
                            {{ $errors->first('speciality_'.$i.'_title') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    {{Form::label('caption', '本文',['class' => 'form_label'])}}
                    {{Form::textarea('speciality_'.$i.'_caption', is_null($image_speciality) ? '' : $image_speciality->caption, ['class' => 'form-control', 'rows' => 4])}}
                    @if ($errors->has('speciality_'.$i.'caption'))
                        <div class="error_message">
                            {{ $errors->first('speciality_'.$i.'caption') }}
                        </div>
                    @endif
                </div>
            </div>
        @endfor
    </div>
</div>


<div class="box box-primary form-box">
    <div class="form_title"><div class="number_circle">5</div> <span class="input_title">地図・アクセス</span></div>
    <div class="form-group">
        {{Form::label('map_url', '地図・アクセス',['class' => 'form_label'])}}
        <?php $map = $hospital->hospital_categories->where('image_order', $image_order::IMAGE_GROUP_MAP)->first(); ?>
        {{Form::text('map_url', is_null($map) ? '' : $map->hospital_image()->first()->memo1, ['class' => 'form-control'])}}
        @if ($errors->has('map_url'))
            <div class="error_message">
            {{ $errors->first('map_url') }}
            </div>
        @endif
    </div>
</div>

<div class="box box-primary form-box" id="interview_section">
    <div class="form_title"><div class="number_circle">6</div> <span class="input_title">インタビュー</span></div>
    <div class="row">
        <div class="col-sm-6">
            {{Form::label('interview_1', 'インタビューメイン画像',['class' => 'form_label'])}}

            @if(!is_null($interview_top) && !is_null($interview_top->hospital_image->path))
                <div class="image_area">
                    <img src="/img/uploads/300-auto-{{$interview_top->hospital_image->path}}" width="150">
                    <p class="file_delete_text">
                        <a onclick="return confirm('{{ trans('messages.delete_image_popup_content') }}')" href="{{ route('hospital.delete_image', ['hospital' => $hospital->id, 'hospital_image_id' => $interview_top->hospital_image_id]) }}">
                            <i class="icon-trash icon-white"></i>
                            ファイル削除
                        </a>
                    </p>
                </div>
            @else
                <div class="no_image_area">
                    <img src="/img/icon_noimage.png" width="50">
                </div>
            @endif

            <label class="file-upload btn btn-primary">
                ファイル選択 {{Form::file("interview_1", ['class' => 'field'])}}
            </label>
            @if ($errors->has('interview_1'))
                <div class="error_message">
                {{ $errors->first('interview_1') }}
                </div>
            @endif
        </div>
        <div class="col-sm-6">
            {{Form::label('interview_1_title', 'タイトル',['class' => 'form_label'])}}
            {{Form::text('interview_1_title', is_null($interview_top) ? '' : $interview_top->title,['class' => 'form-control'])}}
            @if ($errors->has('interview_1_title'))
                <div class="error_message">
                {{ $errors->first('interview_1_title') }}
                </div>
            @endif

            {{Form::label('interview_1_caption', 'キャプション',['class' => 'form_label'])}}
            {{Form::text('interview_1_caption', is_null($interview_top) ? '' : $interview_top->caption,['class' => 'form-control'])}}
            @if ($errors->has('interview_1_caption'))
                <div class="error_message">
                {{ $errors->first('interview_1_caption') }}
                </div>
            @endif
        </div>
    </div>
    <div id="interview_detail_box">
        @foreach($interviews as $interview)
            <div class="interview_detail_{{$loop->iteration}} interview_list">
                <div>
                    {{Form::label('interview['. $interview->id .']', '質問',['class' => 'form_label'])}}
                    {{Form::text('interview['. $interview->id .'][question]', is_null($interview) ? '' : $interview->question, ['class' => 'form-control'])}}
                    @if ($errors->has('interview['. $interview->id .'][question]'))
                        <div class="error_message">
                        {{ $errors->first('interview['. $interview->id .'][question]') }}
                        </div>
                    @endif
                </div>
                <div>
                    {{Form::label('interview['. $interview->id .'][answer]', '回答',['class' => 'form_label'])}}
                    {{Form::text('interview['. $interview->id .'][answer]', is_null($interview) ? '' : $interview->answer, ['class' => 'form-control'])}}
                    @if ($errors->has('interview['. $interview->id .'][answer]'))
                        <div class="error_message">
                            {{ $errors->first('interview['. $interview->id .'][answer]') }}
                        </div>
                    @endif
                </div>
                <p class="file_delete_text">
                    <a onclick="return confirm('このインタビューを削除しますか？')" href="{{ route('hospital.delete_interview', ['hospital' => $hospital->id, 'interview_id' => $interview->id]) }}">
                        <i class="icon-trash icon-white"></i>
                        削除
                    </a>
                </p>
            </div>
        @endforeach
            <div class="interview_list interview_detail_new">
                <div>
                    {{Form::label('interview_new[0][question]', '質問',['class' => 'form_label'])}}
                    {{Form::text('interview_new[0][question]', '', ['class' => 'form-control'])}}
                </div>
                <div>
                    {{Form::label('interview_new[0][answer]', '回答',['class' => 'form_label'])}}
                    {{Form::text('interview_new[0][answer]', '', ['class' => 'form-control'])}}
                </div>
            </div>
    </div>
    <a href="javascript:void(0)" id="interview_add" class="btn btn-info">
        <i class="icon-trash icon-white"></i>
        追加
    </a>
</div>

<div class="box box-primary form-box">
    <div class="form_title"><div class="number_circle">7</div> <span class="input_title">写真タブ</span></div>
    @for ($i = 1; $i <= 5; $i++)
    <div class="row">
        <p class="tab_name">{{$tab_name_list[$i]}}</p>
        <div class="col-sm-6">
            <?php $tab = $hospital->hospital_categories->where('image_order', $image_order::IMAGE_GROUP_TAB)->where('order2', $i)->first();?>
            @if(!is_null($tab) && !is_null($tab->hospital_image->path))
                <div class="image_area">
                    <img src="/img/uploads/300-auto-{{$tab->hospital_image->path}}" width="150">
                    <p class="file_delete_text">
                        <a onclick="return confirm('{{ trans('messages.delete_image_popup_content') }}')" href="{{ route('hospital.delete_image', ['hospital' => $hospital->id, 'hospital_image_id' => $tab->hospital_image_id]) }}">
                            <i class="icon-trash icon-white"></i>
                            ファイル削除
                        </a>
                    </p>
                </div>
            @else
                <div class="no_image_area">
                    <img src="/img/icon_noimage.png" width="50">
                </div>
            @endif
            <label class="file-upload btn btn-primary">
                ファイル選択 {{Form::file("tab_".$i, ['class' => 'field'])}}
            </label>
            @if ($errors->has('tab_'.$i))
                <div class="error_message">
                {{ $errors->first('tab_').$i }}
                </div>
            @endif
        </div>
        <div class="col-sm-6">
            {{Form::label('tab_'.$i.'_order1', '表示順',['class' => 'form_label'])}}
            {{Form::text('tab_'.$i.'_order1', is_null($tab) ? '' : $tab->order, ['class' => 'form-control'])}}
            @if ($errors->has('tab_'.$i.'_order1'))
                {{ $errors->first('tab_'.$i.'_order1') }}
            @endif
            {{Form::label('tab_'.$i.'_memo1', 'alt',['class' => 'form_label'])}}
            {{Form::text('tab_'.$i.'_memo1', is_null($tab) ? '' : $tab->hospital_image->memo1, ['class' => 'form-control'])}}
            @if ($errors->has('tab_'.$i.'_memo1'))
                {{ $errors->first('tab_'.$i.'_memo1') }}
            @endif
            {{Form::label('tab_'.$i.'_memo2', '説明',['class' => 'form_label'])}}
            {{Form::textarea('tab_'.$i.'_memo2', is_null($tab) ? '' : $tab->hospital_image->memo2, ['class' => 'form-control','rows' => "2"])}}
            @if ($errors->has('tab_'.$i.'_memo2'))
                {{ $errors->first('tab_'.$i.'_memo2') }}
            @endif
        </div>
    </div>
    @endfor
</div>

<div class="box box-primary form-box" id="staff_section">
    <div class="form_title"><div class="number_circle">8</div> <span class="input_title">スタッフ</span></div>
    <div class="row">
    @for ($i = 1; $i <= 10; $i++)
        <?php $staff = $hospital->hospital_categories->where('image_order', $image_order::IMAGE_GROUP_STAFF)->where('order2', $i)->first();?>
        <div class="col-sm-6 staff_box" data-order="{{$i}}" @if(is_null($staff) && $i != 1) style="display: none" @endif>
            <p class="box_staff_title">スタッフ{{$i}}</p>
            @if(!is_null($staff) && !is_null($staff->hospital_image->path))
                <div class="image_area">
                    <img src="/img/uploads/300-auto-{{$staff->hospital_image->path}}" width="150">
                    <p class="file_delete_text">
                        <a onclick="return confirm('{{ trans('messages.delete_image_popup_content') }}')" href="{{ route('hospital.delete_image', ['hospital' => $hospital->id, 'hospital_image_id' => $staff->hospital_image_id]) }}">
                            <i class="icon-trash icon-white"></i>
                            ファイル削除
                        </a>
                    </p>
                </div>
            @else
                <div class="no_image_area">
                    <img src="/img/icon_noimage.png" width="50">
                </div>
            @endif
            <label class="file-upload btn btn-primary">
                ファイル選択 {{Form::file("staff_".$i, ['class' => 'field'])}}
            </label>
            @if ($errors->has('staff_'.$i))
                {{ $errors->first('staff_').$i }}
            @endif
            {{Form::label('staff_'.$i.'_memo1', 'alt',['class' => 'form_label'])}}
            {{Form::text('staff_'.$i.'_memo1', is_null($staff) ? '' : $staff->hospital_image->memo1, ['class' => 'form-control'])}}
            @if ($errors->has('staff_'.$i.'_memo1'))
                {{ $errors->first('staff_'.$i.'_memo1') }}
            @endif
            {{Form::label('staff_'.$i.'_name', '名前',['class' => 'form_label'])}}
            {{Form::text('staff_'.$i.'_name', is_null($staff) ? '' : $staff->name, ['class' => 'form-control'])}}
            @if ($errors->has('staff_'.$i.'_name'))
                {{ $errors->first('staff_'.$i.'_name') }}
            @endif
            {{Form::label('staff_'.$i.'_career', '経歴',['class' => 'form_label'])}}
            {{Form::textarea('staff_'.$i.'_career', is_null($staff) ? '' : $staff->career, ['class' => 'form-control', 'rows'=> 2])}}
            @if ($errors->has('staff_'.$i.'_career'))
                {{ $errors->first('staff_'.$i.'_career') }}
            @endif
            {{Form::label('staff_'.$i.'_memo', 'コメント',['class' => 'form_label'])}}
            {{Form::textarea('staff_'.$i.'_memo', is_null($staff) ? '' : $staff->memo, ['class' => 'form-control', 'rows'=> 4])}}
            @if ($errors->has('staff_'.$i.'_memo'))
                {{ $errors->first('staff_'.$i.'_memo') }}
            @endif
            @if(!is_null($staff))
            <p style="text-align: center; margin-top: 10px">
                <a onclick="return confirm('このスタッフを削除します、よろしいですか？')" class="btn btn-mini btn-danger" href="{{ route('hospital.image.delete', ['hospital' => $hospital->id, 'hospital_category_id' => $staff->id, 'hospital_image_id' => $staff->hospital_image_id]) }}">
                    削除
                </a>
            </p>
            @endif
        </div>
        <a href="javascript:void(0)" class="staff_add btn btn-info" style="@if(!is_null($staff) OR $i == 1)display: none; @endif z-index: {{ 100 - $i}}">
            <i class="icon-trash icon-white"></i>
            追加
        </a>
    @endfor
    </div>
</div>
