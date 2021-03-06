@php
    use App\Enums\FileLocationNo;
    use App\Enums\ImageGroupNumber;
    use App\Enums\SelectPhotoFlag;
@endphp

<div class="box box-primary form-box">
    <div class="box-body staff-form">
    <input type="hidden" name="lock_version" value="{{ $hospital->lock->lock_version or ''}}" />
    @include('layouts.partials.error_pan')
    @include('layouts.partials.message_lock')
<!-- フラッシュメッセージ -->
    @if (session('success'))
        @include('layouts.partials.message')
    @endif
    @includeIf('hospital.partials.nav-bar')
        <!--
    <div class="form-entry">

    <h2>施設メイン登録</h2>
    <div class="form-group ">
        {{Form::label('main', '施設メイン',['class' => 'form_label'])}}
        <?php $main_image_category = $hospital->hospital_categories->firstWhere('image_order', ImageGroupNumber::IMAGE_GROUP_FACILITY_MAIN); ?>
        @if(!is_null($main_image_category) && !is_null($main_image_category->hospital_image) && !is_null($main_image_category->hospital_image->path))
            <div class="main_image_area">
                <img class="object-fit" src="{{$main_image_category->hospital_image->path}}" height="200">
                <p class="file_delete_text">
                    <a onclick="return confirm('この施設画像を削除します、よろしいですか？')" class="btn btn-mini btn-danger" href="{{ route('hospital.delete_main_image', ['hospital' => $hospital->id, 'hospital_image_id' => $main_image_category->hospital_image_id, 'is_sp' => true]) }}">
                        ファイル削除
                    </a>
                </p>
            </div>
        @else
            <div class="main_image_area">
                <img src="{{ asset('img/no_image.png') }}">
            </div>
        @endif
            <label class="file-upload btn btn-primary">
                ファイル選択 {{Form::file("main", ['class' => 'field', 'accept' => 'image/*'])}}
            </label>
        @if ($errors->has('main'))
            {{ $errors->first('main') }}
        @endif
    </div>
    </div>
    </div>
</div>
-->


<div class="box box-primary form-box">
    <div class="form-entry box-body">
        <input type="hidden" name="lock_version" value="{{ $hospital->lock->lock_version or ''}}" />
   <h2>サブメイン</h2>
    <div class="row">
        @for ($i = 1; $i <= 4; $i++)
        <div class="col-sm-6">
            {{Form::label('sub_'.$i, 'サブメイン'.$i,['class' => 'form_label'])}}
            <?php $sub_image_category = $hospital->hospital_categories->where('image_order', ImageGroupNumber::IMAGE_GROUP_FACILITY_SUB)->where('file_location_no', $i)->first(); ?>
            {{Form::hidden('sub_'.$i.'_category_id', $sub_image_category['id'] )}}
            @if (!is_null($sub_image_category) && !is_null($sub_image_category->hospital_image))
                <div class="sub_image_area">
                    <img class="object-fit" src="{{$sub_image_category->hospital_image->path}}">
                    <p class="file_delete_text">
                        <a onclick="return confirm('この施設画像を削除します、よろしいですか？')" class="btn btn-mini btn-danger" href="{{ route('hospital.image.delete', ['hospital' => $hospital->id, 'hospital_category_id' => $sub_image_category->id, 'hospital_image_id' => $sub_image_category->hospital_image_id]) }}">
                            ファイル削除
                        </a>
                    </p>
                </div>
            @else
                <div class="sub_image_area">
                    <img src="/img/no_image.png">
                </div>
            @endif
            <label class="file-upload btn btn-primary">
                ファイル選択 {{Form::file("sub_".$i, ['class' => 'field', 'accept' => 'image/*'])}}
            </label>
            @if ($errors->has('sub_'.$i))
                <div class="error_message">
                    {{ $errors->first('sub_'.$i) }}
                </div>
            @endif
        </div>
        @endfor
    </div>
    </div>
</div>
<div class="box box-primary form-box">
    <div class="form-entry box-body">
        <h2>TOP</h2>
        <div class="form-group @if($errors->has('title')) has-error @endif">
            {{Form::label('interview_1_caption', 'タイトル',['class' => 'form_label'])}}
            <?php $title = $hospital->hospital_categories->firstWhere('image_order', ImageGroupNumber::IMAGE_GROUP_TOP); ?>
            {{Form::text('title', is_null($title) ? '' : $title->title, ['class' => 'form-control'])}}
            @if ($errors->has('title'))
                <p class="help-block">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('title') }}
                </p>
            @endif
        </div>

        <div class="form-group @if($errors->has('caption')) has-error @endif">
            {{Form::label('caption', '本文 1000字以内',['class' => 'form_label'])}}
            {{Form::textarea('caption', is_null($title) ? '' : $title->caption, ['class' => 'form-control', 'rows' => 4])}}
            @if ($errors->has('caption'))
                <div class="error_message">
                    <p class="help-block">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('caption') }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="box box-primary form-box">
    <div class="form-entry">
        <div class="box-body" style="padding-bottom: 0px">
            <h2>こだわり</h2>
            @for ($i = 1; $i <= 4; $i++)
                @if($i === 1 || $i === 3)<div class="row" style="position: relative;padding: 0 0 44px 0">@endif

                <?php $image_speciality = $hospital->hospital_categories->where('image_order', ImageGroupNumber::IMAGE_GROUP_SPECIALITY)->where('file_location_no', $i)->first(); ?>
                <div class="col-sm-6" @if(is_null($image_speciality) && $i != 1) style="display: none" @endif>
                    {{Form::label('speciality_1_caption', 'こだわり'.$i,['class' => 'form_label'])}}
                    @if (!is_null($image_speciality) && !is_null($image_speciality->hospital_image) && !is_null($image_speciality->hospital_image->path))
                        <div class="speciality_image_area">
                            <img class="object-fit" src="{{$image_speciality->hospital_image->path}}" width="150">
                            <p class="file_delete_text">
                                <a class="btn btn-mini btn-danger" onclick="return confirm('{{ trans('messages.delete_image_popup_content') }}')" href="{{ route('hospital.delete_image', ['hospital' => $hospital->id, 'hospital_image_id' => $image_speciality->hospital_image_id]) }}">
                                    <i class="icon-trash icon-white"></i>
                                    ファイル削除
                                </a>
                            </p>
                        </div>
                    @else
                        <div class="speciality_image_area">
                            <img src="/img/no_image.png">
                        </div>
                    @endif

                    <label class="file-upload btn btn-primary">
                        ファイル選択 {{Form::file("speciality_".$i, ['class' => 'field', 'accept' => 'image/*'])}}
                    </label>

                    @if ($errors->has('speciality_'.$i))
                        <div class="error_message">
                            {{ $errors->first('speciality_'.$i) }}
                        </div>
                    @endif

                    <div class="form-group @if($errors->has('speciality_'.$i.'_title')) has-error @endif">
                        {{Form::label('interview_1_caption', 'タイトル',['class' => 'form_label'])}}
                        {{Form::text('speciality_'.$i.'_title', is_null($image_speciality) ? '' : $image_speciality->title, ['class' => 'form-control'])}}
                        @if ($errors->has('speciality_'.$i.'_title'))
                            <p class="help-block">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('speciality_'.$i.'_title') }}
                            </p>
                        @endif
                    </div>

                    <div class="form-group @if($errors->has('speciality_'.$i.'_caption')) has-error @endif">
                        {{Form::label('caption', '本文',['class' => 'form_label'])}}
                        {{Form::textarea('speciality_'.$i.'_caption', is_null($image_speciality) ? '' : $image_speciality->caption, ['class' => 'form-control', 'rows' => 4])}}
                        @if ($errors->has('speciality_'.$i.'_caption'))
                            <p class="help-block">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('speciality_'.$i.'_caption') }}
                            </p>
                        @endif
                    </div>

                    @if (!is_null($image_speciality))
                        <p style="text-align: center; margin-top: 10px">
                            <a onclick="return confirm('こだわり{{ $i }}を削除します、よろしいですか？')" class="btn btn-mini btn-danger" href="{{ route('hospital.image.delete', ['hospital' => $hospital->id, 'hospital_category_id' => $image_speciality->id, 'hospital_image_id' => $image_speciality->hospital_image_id]) }}">
                                削除
                            </a>
                        </p>
                    @endif

                </div>

                <a href="javascript:void(0)" class="speciality_add btn btn-info" data-i="{{ $i }}" data-sp="{{ (is_null($image_speciality)) }}" style="@if(!is_null($image_speciality) OR $i < 2)display: none; @endif z-index: {{ 100 - $i}}">
                    <i class="icon-trash icon-white"></i>
                    追加
                </a>

                @if($i === 2 || $i === 4)</div>@endif
            @endfor
    </div>
    </div>
</div>
<div class="box box-primary form-box">
    <div class="form-entry box-body">
        <h2>地図・アクセス</h2>
    <div class="form-group @if($errors->has('map_url')) has-error @endif">
        {{Form::label('map_url', '地図・アクセス',['class' => 'form_label'])}}
        <?php $map = $hospital->hospital_categories->where('image_order', ImageGroupNumber::IMAGE_GROUP_MAP)->first(); ?>
        {{Form::text('map_url', is_null($map) ? '' : $map->hospital_image()->first()->memo1, ['class' => 'form-control w20em','placeholder'=>'https://example.com/access'])}}
        @if ($errors->has('map_url'))
            <p class="help-block">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('map_url') }}
            </p>
        @endif
    </div>
    </div>
</div>

<div class="box box-primary form-box" id="interview_section">
    <div class="form-entry box-body">
        <h2>インタビュー</h2>
    <div class="row" style="position: relative">
        <div class="col-sm-6">
            {{Form::label('interview_1', 'インタビューメイン画像',['class' => 'form_label'])}}
            @if(!is_null($interview_top) && !is_null($interview_top->hospital_image) && !is_null($interview_top->hospital_image->path))
                <div class="interview_image_area">
                    <img src="{{$interview_top->hospital_image->path}}" width="150">
                    <p class="file_delete_text">
                        <a class="btn btn-mini btn-danger" onclick="return confirm('{{ trans('messages.delete_image_popup_content') }}')" href="{{ route('hospital.delete_image', ['hospital' => $hospital->id, 'hospital_image_id' => $interview_top->hospital_image_id]) }}">
                            <i class="icon-trash icon-white"></i>
                            ファイル削除
                        </a>
                    </p>
                </div>
            @else
                <div class="interview_image_area">
                    <img src="/img/no_image.png">
                </div>
            @endif
            <label class="file-upload btn btn-primary">
                ファイル選択 {{Form::file("interview_1", ['class' => 'field', 'accept' => 'image/*'])}}
            </label>
            @if ($errors->has('interview_1'))
                <p class="help-block">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('interview_1') }}
                </p>
            @endif
        </div>
        <div class="col-sm-6">
            <div class="form-group @if($errors->has('interview_1_title')) has-error @endif">
            {{Form::label('interview_1_title', 'タイトル',['class' => 'form_label'])}}
            {{Form::text('interview_1_title', is_null($interview_top) ? '' : $interview_top->title,['class' => 'form-control'])}}
            @if ($errors->has('interview_1_title'))
                <p class="help-block">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('interview_1_title') }}
                </p>
            @endif
            </div>
            <div class="form-group @if($errors->has('interview_1_caption')) has-error @endif">
            {{Form::label('interview_1_caption', '本文',['class' => 'form_label'])}}
            {{Form::textarea('interview_1_caption', is_null($interview_top) ? '' : $interview_top->caption,['class' => 'form-control'])}}
            @if ($errors->has('interview_1_caption'))
                <p class="help-block">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('interview_1_caption') }}
                </p>
            @endif
            </div>
        </div>
    </div>
    <div id="interview_detail_box">
        @foreach($interviews as $key => $interview)
            <div class="interview_detail_{{$loop->iteration}} interview_list">

                <div class="form-group @if ($errors->has('interview.'.$interview->id.'.question')) has-error @endif">

                    {{Form::label('interview['. $interview->id .']', '質問',['class' => 'form_label'])}}
                    {{Form::text('interview['. $interview->id .'][question]', old('interview.'.$interview->id.'.question', $interview->question), ['class' => 'form-control'])}}
                    @if ($errors->has('interview.'.$interview->id.'.question'))
                        <div class="error_message">
                            {{ $errors->first('interview.'.$interview->id.'.question') }}
                        </div>
                    @endif
                </div>
                <div class="form-group @if ($errors->has('interview.'.$interview->id.'.answer')) has-error @endif">
                    {{Form::label('interview['. $interview->id .'][answer]', '回答',['class' => 'form_label'])}}
                    {{Form::text('interview['. $interview->id .'][answer]', old('interview.'.$interview->id.'.answer', $interview->answer) , ['class' => 'form-control'])
                     }}
                    @if ('interview.'.$interview->id.'.answer')
                        <div class="error_message">
                            {{ $errors->first('interview.'.$interview->id.'.answer') }}
                        </div>
                    @endif
                </div>
                <p class="mt-3">
                    <a class="btn btn-mini btn-danger" onclick="return confirm('このインタビューを削除しますか？')" href="{{ route('hospital.delete_interview', ['hospital' => $hospital->id, 'interview_id' => $interview->id]) }}">
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
                    @if ($errors->has('interview[0][answer]'))
                        <div class="error_message">
                            {{ $errors->first('interview[0][answer]') }}
                        </div>
                    @endif
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
</div>
<div class="box box-primary form-box">
    <div class="form-entry box-body">
    <!--スタッフタブ-->
        <h2>写真タブ</h2>
        <p class="tab_name">
            スタッフ
            <button type="button" class="btn btn-light btn select-tab tab-normal-bt">選択</button>
        </p>
    <?php $staff_tab_box = $hospital->hospital_categories->where('image_order', ImageGroupNumber::IMAGE_GROUP_TAB)->where('file_location_no', FileLocationNo::TAB_CATEGORY_STAFF);?>
    <?php $staff_tab_box = $staff_tab_box->sortBy('order2');?>
    <?php $staff_show_order2 = $staff_tab_box->pluck('order')->toArray();?>
    <?php $staff_tab_count = empty($staff_tab_box) ? 1 : count($staff_tab_box) + 1;?>
    <!--登録済のタブ画像フォーム-->
    <div class="open_close_tab">
    @foreach($staff_tab_box as $staff_tab)
        <?php $i = $loop->iteration ;?>
        <div class="row photo-tab" data-order="{{$i}}" @if(is_null($staff_tab) && $i != 1) style="display: none" @endif>
            <div class="col-sm-6">
                @if(!is_null($staff_tab) && !is_null($staff_tab->hospital_image) && !is_null($staff_tab->hospital_image->path))
                    <div class="tab_image_area">
                        <img class="object-fit" src="{{$staff_tab->hospital_image->path}}">
                        <p class="file_delete_text">
                            <a class="btn btn-mini btn-danger" onclick="return confirm('{{ trans('messages.delete_image_popup_content') }}')" href="{{ route('hospital.delete_image', ['hospital' => $hospital->id, 'hospital_image_id' => $staff_tab->hospital_image_id]) }}">
                                <i class="icon-trash icon-white"></i>
                                ファイル削除
                            </a>
                        </p>
                    </div>
                @else
                    <div class="tab_image_area">
                        <img src="/img/no_image.png">
                    </div>
                @endif
                <label class="file-upload btn btn-primary">
                    ファイル選択 {{Form::file("staff_tab_".$i, ['class' => 'field', 'accept' => 'image/*'])}}
                </label>
                @if ($errors->has('staff_tab_'.$i))
                    <div class="error_message">
                        {{ $errors->first('staff_tab_').$i }}
                    </div>
                @endif
                    {{Form::hidden('staff_tab_'.$i.'_location', FileLocationNo::TAB_CATEGORY_STAFF )}}
                    {{Form::hidden('staff_tab_'.$i.'_category_id', $staff_tab['id'] )}}
            </div>
            <div class="col-sm-6">
                <div class="form-group @if ($errors->has('staff_tab_'.$i.'_order2')) has-error @endif">
                    <label for="staff_tab_{{$i}}_order2">表示順
                        <span class="form_required">必須</span>
                    </label>
                {{Form::text('staff_tab_'.$i.'_order2', old('staff_tab_'.$i.'_order2',$staff_tab['order2']), ['class' => 'form-control'])}}
                @if ($errors->has('staff_tab_'.$i.'_order2'))
                    <div class="error_message">{{ $errors->first('staff_tab_'.$i.'_order2') }}</div>
                @endif
                </div>
                <div class="form-group @if ($errors->has('staff_tab_'.$i.'_memo2')) has-error @endif">
                {{Form::label('staff_tab_'.$i.'_memo2', '説明',['class' => 'form_label'])}}
                {{Form::textarea('staff_tab_'.$i.'_memo2', old('staff_tab_'.$i.'_memo2',$staff_tab->hospital_image->memo2), ['class' => 'form-control','rows' => "2"])}}
                @if ($errors->has('staff_tab_'.$i.'_memo2'))
                    <div class="error_message">{{ $errors->first('staff_tab_'.$i.'_memo2') }}</div>
                @endif
                </div>
            </div>
            @if(!is_null($staff_tab))
                <p style="text-align: center; margin-top: 10px">
                    <a onclick="return confirm('スタッフタブを削除します、よろしいですか？')" class="btn btn-mini btn-danger" href="{{ route('hospital.image.delete', ['hospital' => $hospital->id, 'hospital_category_id' => $staff_tab->id, 'hospital_image_id' => $staff_tab->hospital_image_id]) }}">
                        削除
                    </a>
                </p>
            @endif
        </div>
        <a href="javascript:void(0)" class="staff_add btn btn-info" style="@if(!is_null($staff_tab) OR $i == 1)display: none; @endif z-index: {{ 100 - $i}}">
            <i class="icon-trash icon-white"></i>
            追加
        </a>
    @endforeach
    <!--//登録済のタブ画像フォーム-->
    <!--未登録のタブ画像フォーム-->
    @for ($i = $staff_tab_count; $i <= 30; $i++)
        {{--@if(!in_array($i, $staff_show_order2))--}}
        <div class="row photo-tab" data-order="{{$i}}"
             @if($i != 1 && (!old('staff_tab_'.$i.'_order2') && !old('staff_tab_'.$i.'_memo2')) )
             style="display: none"
            @endif
        >
            <div class="col-sm-6">
                <div class="tab_image_area">
                    <img src="/img/no_image.png">
                </div>
                <label class="file-upload btn btn-primary">
                    ファイル選択 {{Form::file("staff_tab_".$i, ['class' => 'field', 'accept' => 'image/*'])}}
                </label>
                @if ($errors->has('staff_tab_'.$i))
                <p class="help-block"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>{{ $errors->first('staff_tab_').$i }}</p>
                @endif
                {{Form::hidden('staff_tab_'.$i.'_location', FileLocationNo::TAB_CATEGORY_STAFF )}}
            </div>
            <div class="col-sm-6">
                <div class="form-group @if ($errors->has('staff_tab_'.$i.'_order2')) has-error @endif">
                <label for="staff_tab_{{$i}}_order2">表示順
                    <span class="form_required">必須</span>
                </label>
                {{Form::text('staff_tab_'.$i.'_order2', null, ['class' => 'form-control'])}}
                @if ($errors->has('staff_tab_'.$i.'_order2'))
                    <div class="error_message">{{ $errors->first('staff_tab_'.$i.'_order2') }}</div>
                @endif
                </div>
                <div class="form-group @if ($errors->has('staff_tab_'.$i.'_memo2')) has-error @endif">
                {{Form::label('staff_tab_'.$i.'_memo2', '説明',['class' => 'form_label'])}}
                {{Form::textarea('staff_tab_'.$i.'_memo2', null, ['class' => 'form-control','rows' => "2"])}}
                @if ($errors->has('staff_tab_'.$i.'_memo2'))
                    <div class="error_message">{{ $errors->first('staff_tab_'.$i.'_memo2') }}</div>
                @endif
                </div>
            </div>
        </div>
        <a href="javascript:void(0)" class="staff_add btn btn-info"
           style="@if($i == 1 or (old('staff_tab_'.$i.'_order2') or old('staff_tab_'.$i.'_memo2')) )display: none; @endif z-index: {{ 100 - $i}}"
        >
            <i class="icon-trash icon-white"></i>
            追加
        </a>
        {{--@endif--}}
    @endfor
    <!--//未登録のタブ画像フォーム-->
    </div>
    <!--・//スタッフタブ-->

    <!--設備タブ-->
    <p class="tab_name">
        設備
        <button type="button" class="btn btn-light btn select-tab tab-normal-bt">選択</button>
    </p>
    <?php $facility_tab_box = $hospital->hospital_categories->where('image_order', ImageGroupNumber::IMAGE_GROUP_TAB)->where('file_location_no', FileLocationNo::TAB_CATEGORY_FACILITY);?>
    <?php $facility_tab_box = $facility_tab_box->sortBy('order2');?>
    <?php $facility_show_order2 = $facility_tab_box->pluck('order')->toArray();?>
    <?php $facility_tab_count = empty($facility_tab_box) ? 1 : count($facility_tab_box) + 1;?>

    <!--未登録のタブ画像フォーム-->
    <div class="open_close_tab">
        @foreach($facility_tab_box as $facility_tab)
            <?php $i = $loop->iteration ;?>
            <div class="row photo-tab" data-order="{{$i}}" @if(is_null($facility_tab) && $i != 1) style="display: none" @endif>
                <div class="col-sm-6">
                    @if(!is_null($facility_tab) && !is_null($facility_tab->hospital_image) && !is_null($facility_tab->hospital_image->path))
                        <div class="tab_image_area">
                            <img class="object-fit" src="{{$facility_tab->hospital_image->path}}">
                            <p class="file_delete_text">
                                <a class="btn btn-mini btn-danger" onclick="return confirm('{{ trans('messages.delete_image_popup_content') }}')" href="{{ route('hospital.delete_image', ['hospital' => $hospital->id, 'hospital_image_id' => $facility_tab->hospital_image_id]) }}">
                                    <i class="icon-trash icon-white"></i>
                                    ファイル削除
                                </a>
                            </p>
                        </div>
                    @else
                        <div class="tab_image_area">
                            <img src="/img/no_image.png">
                        </div>
                    @endif
                    <label class="file-upload btn btn-primary">
                        ファイル選択 {{Form::file("facility_tab_".$i, ['class' => 'field', 'accept' => 'image/*'])}}
                    </label>
                    @if ($errors->has('facility_tab_'.$i))
                        <div class="error_message">
                            {{ $errors->first('facility_tab_').$i }}
                        </div>
                    @endif
                        {{Form::hidden('facility_tab_'.$i.'_location', FileLocationNo::TAB_CATEGORY_FACILITY )}}
                        {{Form::hidden('facility_tab_'.$i.'_category_id', $facility_tab['id'] )}}
                </div>
                <div class="col-sm-6">
                    <div class="form-group @if ($errors->has('facility_tab_'.$i.'_order2')) has-error @endif">
                    <label for="facility_tab_{{$i}}_order2">表示順
                        <span class="form_required">必須</span>
                    </label>
                    {{Form::text('facility_tab_'.$i.'_order2', old('facility_tab_'.$i.'_order2',$facility_tab['order2']), ['class' => 'form-control'])}}
                    @if ($errors->has('facility_tab_'.$i.'_order2'))
                        <div class="error_message">{{ $errors->first('facility_tab_'.$i.'_order2') }}</div>
                    @endif
                    </div>
                    <div class="form-group @if ($errors->has('facility_tab_'.$i.'_memo2')) has-error @endif">
                    {{Form::label('facility_tab_'.$i.'_memo2', '説明',['class' => 'form_label'])}}
                    {{Form::textarea('facility_tab_'.$i.'_memo2', is_null($facility_tab) ? null : $facility_tab->hospital_image->memo2, ['class' => 'form-control','rows' => "2"])}}
                    @if ($errors->has('facility_tab_'.$i.'_memo2'))
                        <div class="error_message">{{ $errors->first('facility_tab_'.$i.'_memo2') }}</div>
                    @endif
                    </div>
                </div>
                @if(!is_null($facility_tab))
                    <p style="text-align: center; margin-top: 10px">
                        <a onclick="return confirm('設備タブを削除します、よろしいですか？')" class="btn btn-mini btn-danger" href="{{ route('hospital.image.delete', ['hospital' => $hospital->id, 'hospital_category_id' => $facility_tab->id, 'hospital_image_id' => $facility_tab->hospital_image_id]) }}">
                            削除
                        </a>
                    </p>
                @endif
            </div>
            <a href="javascript:void(0)" class="facility_add btn btn-info" style="@if(!is_null($facility_tab) OR $i == 1)display: none; @endif z-index: {{ 100 - $i}}">
                <i class="icon-trash icon-white"></i>
                追加
            </a>
        @endforeach
    <!--//登録済のタブ画像フォーム-->
        <!--未登録のタブ画像フォーム-->
        @for ($i = $facility_tab_count; $i <= 30; $i++)
            {{--@if(!in_array($i, $facility_show_order2))--}}
            <div class="row photo-tab" data-order="{{$i}}" @if($i != 1 && (!old('facility_tab_'.$i.'_order2') && !old('facility_tab_'.$i.'_memo2')) ) style="display: none" @endif>
                <div class="col-sm-6">
                    <div class="tab_image_area">
                        <img src="/img/no_image.png">
                    </div>
                    <label class="file-upload btn btn-primary">
                        ファイル選択 {{Form::file("facility_tab_".$i, ['class' => 'field', 'accept' => 'image/*'])}}
                    </label>
                    @if ($errors->has('facility_tab_'.$i))
                        <div class="error_message">
                            {{ $errors->first('facility_tab_').$i }}
                        </div>
                    @endif
                    {{Form::hidden('facility_tab_'.$i.'_location', FileLocationNo::TAB_CATEGORY_FACILITY )}}
                </div>
                <div class="col-sm-6">
                    <div class="form-group @if ($errors->has('facility_tab_'.$i.'_order2')) has-error @endif">
                        <label for="facility_tab_{{$i}}_order2">表示順
                            <span class="form_required">必須</span>
                        </label>
                    {{Form::text('facility_tab_'.$i.'_order2', null, ['class' => 'form-control'])}}
                    @if ($errors->has('facility_tab_'.$i.'_order2'))
                        <div class="error_message"> {{ $errors->first('facility_tab_'.$i.'_order2') }} </div>
                    @endif
                    </div>
                    <div class="form-group @if ($errors->has('facility_tab_'.$i.'_memo2')) has-error @endif">
                    {{Form::label('facility_tab_'.$i.'_memo2', '説明',['class' => 'form_label'])}}
                    {{Form::textarea('facility_tab_'.$i.'_memo2', null, ['class' => 'form-control','rows' => "2"])}}
                    @if ($errors->has('facility_tab_'.$i.'_memo2'))
                        <div class="error_message"> {{ $errors->first('facility_tab_'.$i.'_memo2') }} </div>
                    @endif
                    </div>
                </div>
            </div>
            <a href="javascript:void(0)" class="facility_add btn btn-info" style="@if($i == 1 or (old('facility_tab_'.$i.'_order2') or old('facility_tab_'.$i.'_memo2')) )display: none; @endif z-index: {{ 100 - $i}}">
                <i class="icon-trash icon-white"></i>
                追加
            </a>
            {{--@endif--}}
    @endfor
    <!--//未登録のタブ画像フォーム-->
    </div>
    <!--・//設備タブ-->

    <!--院内タブ-->
    <p class="tab_name">
        院内
        <button type="button" class="btn btn-light btn select-tab tab-normal-bt">選択</button>
    </p>
    <?php $internal_tab_box = $hospital->hospital_categories->where('image_order', ImageGroupNumber::IMAGE_GROUP_TAB)->where('file_location_no', FileLocationNo::TAB_CATEGORY_INTERNAL);?>
    <?php $internal_tab_box = $internal_tab_box->sortBy('order2');?>
    <?php $internal_show_order2 = $internal_tab_box->pluck('order')->toArray();?>
    <?php $internal_tab_count = empty($internal_tab_box) ? 1 : count($internal_tab_box) + 1;?>

    <!--登録済院内タブ画像フォーム-->
    <div class="open_close_tab">
        @foreach($internal_tab_box as $internal_tab)
            <?php $i = $loop->iteration ;?>
            <div class="row photo-tab" data-order="{{$i}}" @if(is_null($internal_tab) && $i != 1) style="display: none" @endif>
                <div class="col-sm-6">
                    @if(!is_null($internal_tab) && !is_null($internal_tab->hospital_image) && !is_null($internal_tab->hospital_image->path))
                        <div class="tab_image_area">
                            <img class="object-fit" src="{{$internal_tab->hospital_image->path}}">
                            <p class="file_delete_text">
                                <a class="btn btn-mini btn-danger" onclick="return confirm('{{ trans('messages.delete_image_popup_content') }}')" href="{{ route('hospital.delete_image', ['hospital' => $hospital->id, 'hospital_image_id' => $internal_tab->hospital_image_id]) }}">
                                    <i class="icon-trash icon-white"></i>
                                    ファイル削除
                                </a>
                            </p>
                        </div>
                    @else
                        <div class="tab_image_area">
                            <img src="/img/no_image.png">
                        </div>
                    @endif
                    <label class="file-upload btn btn-primary">
                        ファイル選択 {{Form::file("internal_tab_".$i, ['class' => 'field', 'accept' => 'image/*'])}}
                    </label>
                    @if ($errors->has('internal_tab_'.$i))
                        <div class="error_message">
                            {{ $errors->first('internal_tab_').$i }}
                        </div>
                    @endif
                    {{Form::hidden('internal_tab_'.$i.'_location', FileLocationNo::TAB_CATEGORY_INTERNAL )}}
                    {{Form::hidden('internal_tab_'.$i.'_category_id', $internal_tab['id'] )}}
                </div>
                <div class="col-sm-6">
                    <div class="form-group @if ($errors->has('internal_tab_'.$i.'_order2')) has-error @endif">
                        <label for="internal_tab_{{$i}}_order2">表示順
                            <span class="form_required">必須</span>
                        </label>
                    {{Form::text('internal_tab_'.$i.'_order2', old('internal_tab_'.$i.'_order2',$internal_tab['order2']), ['class' => 'form-control'])}}
                    @if ($errors->has('internal_tab_'.$i.'_order2'))
                        <div class="error_message"> {{ $errors->first('internal_tab_'.$i.'_order2') }} </div>
                    @endif
                    </div>
                    <div class="form-group @if ($errors->has('internal_tab_'.$i.'_memo2')) has-error @endif">
                    {{Form::label('internal_tab_'.$i.'_memo2', '説明',['class' => 'form_label'])}}
                    {{Form::textarea('internal_tab_'.$i.'_memo2', is_null($internal_tab) ? null : $internal_tab->hospital_image->memo2, ['class' => 'form-control','rows' => "2"])}}
                    @if ($errors->has('internal_tab_'.$i.'_memo2'))
                        <div class="error_message">{{ $errors->first('internal_tab_'.$i.'_memo2') }}</div>
                    @endif
                    </div>
                </div>
                @if(!is_null($internal_tab))
                    <p style="text-align: center; margin-top: 10px">
                        <a onclick="return confirm('院内タブを削除します、よろしいですか？')" class="btn btn-mini btn-danger" href="{{ route('hospital.image.delete', ['hospital' => $hospital->id, 'hospital_category_id' => $internal_tab->id, 'hospital_image_id' => $internal_tab->hospital_image_id]) }}">
                            削除
                        </a>
                    </p>
                @endif
            </div>
            <a href="javascript:void(0)" class="internal_add btn btn-info" style="@if(!is_null($internal_tab) OR $i == 1)display: none; @endif z-index: {{ 100 - $i}}">
                <i class="icon-trash icon-white"></i>
                追加
            </a>
        @endforeach
    <!--//登録済のタブ画像フォーム-->
        <!--未登録のタブ画像フォーム-->
        @for ($i = $internal_tab_count; $i <= 30; $i++)
            {{--@if(!in_array($i, $internal_show_order2))--}}
                <div class="row photo-tab" data-order="{{$i}}" @if($i != 1 && (!old('internal_tab_'.$i.'_order2') && !old('internal_tab_'.$i.'_memo2')) ) style="display: none" @endif>
                    <div class="col-sm-6">
                        <div class="tab_image_area">
                            <img src="/img/no_image.png">
                        </div>
                        <label class="file-upload btn btn-primary">
                            ファイル選択 {{Form::file("internal_tab_".$i, ['class' => 'field', 'accept' => 'image/*'])}}
                        </label>
                        @if ($errors->has('internal_tab_'.$i))
                            <div class="error_message">
                                {{ $errors->first('internal_tab_').$i }}
                            </div>
                        @endif
                        {{Form::hidden('internal_tab_'.$i.'_location', FileLocationNo::TAB_CATEGORY_INTERNAL )}}
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group @if ($errors->has('internal_tab_'.$i.'_order2')) has-error @endif">
                            <label for="internal_tab_{{$i}}_order2">表示順
                                <span class="form_required">必須</span>
                            </label>
                        {{Form::text('internal_tab_'.$i.'_order2', null, ['class' => 'form-control'])}}
                        @if ($errors->has('internal_tab_'.$i.'_order2'))
                            <div class="error_message">{{ $errors->first('internal_tab_'.$i.'_order2') }}</div>
                        @endif
                        </div>
                        <div class="form-group @if ($errors->has('internal_tab_'.$i.'_memo2')) has-error @endif">
                        {{Form::label('internal_tab_'.$i.'_memo2', '説明',['class' => 'form_label'])}}
                        {{Form::textarea('internal_tab_'.$i.'_memo2', null, ['class' => 'form-control','rows'=>'2'])}}
                        @if ($errors->has('internal_tab_'.$i.'_memo2'))
                            <div class="error_message">{{ $errors->first('internal_tab_'.$i.'_memo2') }}</div>
                        @endif
                        </div>
                    </div>
                </div>
                <a href="javascript:void(0)" class="internal_add btn btn-info" style="@if($i == 1 or (old('internal_tab_'.$i.'_order2') or old('internal_tab_'.$i.'_memo2')) )display: none; @endif z-index: {{ 100 - $i}}">
                    <i class="icon-trash icon-white"></i>
                    追加
                </a>
        {{--@endif--}}
         @endfor
    <!--//未登録のタブ画像フォーム-->
    </div>
    <!--・//院内タブ-->
    <!--外観タブ-->
    <p class="tab_name">
        外観
        <button type="button" class="btn btn-light btn select-tab tab-normal-bt">選択</button>
    </p>
<?php $external_tab_box = $hospital->hospital_categories->where('image_order', ImageGroupNumber::IMAGE_GROUP_TAB)->where('file_location_no', FileLocationNo::TAB_CATEGORY_EXTERNAL);?>
<?php $external_tab_box = $external_tab_box->sortBy('order2');?>
<?php $external_show_order2 = $external_tab_box->pluck('order')->toArray();?>
<?php $external_tab_count = empty($external_tab_box) ? 1 : count($external_tab_box) + 1;?>

    <!--登録済外観タブ画像フォーム-->
    <div class="open_close_tab">
        @foreach($external_tab_box as $external_tab)
            <?php $i = $loop->iteration ;?>
            <div class="row photo-tab" data-order="{{$i}}" @if(is_null($external_tab) && $i != 1) style="display: none" @endif>
                <div class="col-sm-6">
                    @if(!is_null($external_tab) && !is_null($external_tab->hospital_image) && !is_null($external_tab->hospital_image->path))
                        <div class="tab_image_area">
                            <img class="object-fit" src="{{$external_tab->hospital_image->path}}">
                            <p class="file_delete_text">
                                <a class="btn btn-mini btn-danger" onclick="return confirm('{{ trans('messages.delete_image_popup_content') }}')" href="{{ route('hospital.delete_image', ['hospital' => $hospital->id, 'hospital_image_id' => $external_tab->hospital_image_id]) }}">
                                    <i class="icon-trash icon-white"></i>
                                    ファイル削除
                                </a>
                            </p>
                        </div>
                    @else
                        <div class="tab_image_area">
                            <img src="/img/no_image.png">
                        </div>
                    @endif
                    <label class="file-upload btn btn-primary">
                        ファイル選択 {{Form::file("external_tab_".$i, ['class' => 'field', 'accept' => 'image/*'])}}
                    </label>
                    @if ($errors->has('external_tab_'.$i))
                        <div class="error_message">
                            {{ $errors->first('external_tab_').$i }}
                        </div>
                    @endif
                    {{Form::hidden('external_tab_'.$i.'_location', FileLocationNo::TAB_CATEGORY_EXTERNAL )}}
                    {{Form::hidden('external_tab_'.$i.'_category_id', $external_tab['id'] )}}
                </div>
                <div class="col-sm-6">
                    <div class="form-group @if ($errors->has('external_tab_'.$i.'_order2')) has-error @endif">
                        <label for="external_tab_{{$i}}_order2">表示順
                            <span class="form_required">必須</span>
                        </label>
                    {{Form::text('external_tab_'.$i.'_order2', old('external_tab_'.$i.'_order2',$external_tab['order2']), ['class' => 'form-control'])}}
                    @if ($errors->has('external_tab_'.$i.'_order2'))
                        <div class="error_message">{{ $errors->first('external_tab_'.$i.'_order2') }}</div>
                    @endif
                    </div>
                    <div class="form-group @if ($errors->has('external_tab_'.$i.'_memo2')) has-error @endif">
                    {{Form::label('external_tab_'.$i.'_memo2', '説明',['class' => 'form_label'])}}
                    {{Form::textarea('external_tab_'.$i.'_memo2', is_null($external_tab) ? null : $external_tab->hospital_image->memo2, ['class' => 'form-control','rows' => "2"])}}
                    @if ($errors->has('external_tab_'.$i.'_memo2'))
                        <div class="error_message">{{ $errors->first('external_tab_'.$i.'_memo2') }}</div>
                    @endif
                    </div>
                </div>
                @if(!is_null($external_tab))
                    <p style="text-align: center; margin-top: 10px">
                        <a onclick="return confirm('外観タブを削除します、よろしいですか？')" class="btn btn-mini btn-danger" href="{{ route('hospital.image.delete', ['hospital' => $hospital->id, 'hospital_category_id' => $external_tab->id, 'hospital_image_id' => $external_tab->hospital_image_id]) }}">
                            削除
                        </a>
                    </p>
                @endif
            </div>
            <a href="javascript:void(0)" class="external_add btn btn-info" style="@if(!is_null($external_tab) OR $i == 1)display: none; @endif z-index: {{ 100 - $i}}">
                <i class="icon-trash icon-white"></i>
                追加
            </a>
        @endforeach
    <!--//登録済のタブ画像フォーム-->
        <!--未登録のタブ画像フォーム-->
        @for ($i = $external_tab_count; $i <= 30; $i++)
            {{--@if(!in_array($i, $external_show_order2))--}}
                <div class="row photo-tab" data-order="{{$i}}" @if($i != 1 && (!old('external_tab_'.$i.'_order2') && !old('external_tab_'.$i.'_memo2')) ) style="display: none" @endif>
                    <div class="col-sm-6">
                        <div class="tab_image_area">
                            <img src="/img/no_image.png">
                        </div>
                        <label class="file-upload btn btn-primary">
                            ファイル選択 {{Form::file("external_tab_".$i, ['class' => 'field', 'accept' => 'image/*'])}}
                        </label>
                        @if ($errors->has('external_tab_'.$i))
                            <div class="error_message">
                                {{ $errors->first('external_tab_').$i }}
                            </div>
                        @endif
                        {{Form::hidden('external_tab_'.$i.'_location', FileLocationNo::TAB_CATEGORY_EXTERNAL )}}
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group @if ($errors->has('external_tab_'.$i.'_order2')) has-error @endif">
                            <label for="external_tab_{{$i}}_order2">表示順
                                <span class="form_required">必須</span>
                            </label>
                        {{Form::text('external_tab_'.$i.'_order2', null, ['class' => 'form-control'])}}
                        @if ($errors->has('external_tab_'.$i.'_order2'))
                            <div class="error_message">{{ $errors->first('external_tab_'.$i.'_order2') }}</div>
                        @endif
                        </div>
                        <div class="form-group">
                        {{Form::label('external_tab_'.$i.'_memo2', '説明',['class' => 'form_label'])}}
                        {{Form::textarea('external_tab_'.$i.'_memo2', null, ['class' => 'form-control','rows' => "2"])}}
                        @if ($errors->has('external_tab_'.$i.'_memo2'))
                            <div class="error_message"> {{ $errors->first('external_tab_'.$i.'_memo2') }} </div>
                        @endif
                        </div>
                    </div>
                </div>
                <a href="javascript:void(0)" class="external_add btn btn-info" style="@if($i == 1 or (old('external_tab_'.$i.'_order2') or old('external_tab_'.$i.'_memo2')) )display: none; @endif z-index: {{ 100 - $i}}">
                    <i class="icon-trash icon-white"></i>
                    追加
                </a>
        {{--@endif--}}
    @endfor
    <!--//未登録のタブ画像フォーム-->
    </div>
    <!--・//外観タブ-->
    <!--その他タブ-->
    <p class="tab_name">
        その他
        <button type="button" class="btn btn-light btn select-tab tab-normal-bt">選択</button>
    </p>
<?php $another_tab_box = $hospital->hospital_categories->where('image_order', ImageGroupNumber::IMAGE_GROUP_TAB)->where('file_location_no', FileLocationNo::TAB_CATEGORY_ANOTHER);?>
<?php $another_tab_box = $another_tab_box->sortBy('order2');?>
<?php $another_show_order2 = $another_tab_box->pluck('order')->toArray();?>
<?php $another_tab_count = empty($another_tab_box) ? 1 : count($another_tab_box) + 1;?>

<!--登録済その他タブ画像フォーム-->
    <div class="open_close_tab">
        @foreach($another_tab_box as $another_tab)
            <?php $i = $loop->iteration ;?>
            <div class="row photo-tab" data-order="{{$i}}" @if(is_null($another_tab) && $i != 1) style="display: none" @endif>
                <div class="col-sm-6">
                    @if(!is_null($another_tab) && !is_null($another_tab->hospital_image) && !is_null($another_tab->hospital_image->path))
                        <div class="tab_image_area">
                            <img class="object-fit" src="{{$another_tab->hospital_image->path}}">
                            <p class="file_delete_text">
                                <a class="btn btn-mini btn-danger" onclick="return confirm('{{ trans('messages.delete_image_popup_content') }}')" href="{{ route('hospital.delete_image', ['hospital' => $hospital->id, 'hospital_image_id' => $another_tab->hospital_image_id]) }}">
                                    <i class="icon-trash icon-white"></i>
                                    ファイル削除
                                </a>
                            </p>
                        </div>
                    @else
                        <div class="tab_image_area">
                            <img src="/img/no_image.png">
                        </div>
                    @endif
                    <label class="file-upload btn btn-primary">
                        ファイル選択 {{Form::file("another_tab_".$i, ['class' => 'field', 'accept' => 'image/*'])}}
                    </label>
                    @if ($errors->has('another_tab_'.$i))
                        <div class="error_message">
                            {{ $errors->first('another_tab_').$i }}
                        </div>
                    @endif
                    {{Form::hidden('another_tab_'.$i.'_location', FileLocationNo::TAB_CATEGORY_ANOTHER )}}
                    {{Form::hidden('another_tab_'.$i.'_category_id', $another_tab['id'] )}}
                </div>
                <div class="col-sm-6">
                    <div class="form-group @if ($errors->has('another_tab_'.$i.'_order2')) has-error @endif">
                        <label for="another_tab_{{$i}}_order2">表示順
                            <span class="form_required">必須</span>
                        </label>
                    {{Form::text('another_tab_'.$i.'_order2', old('another_tab_'.$i.'_order2',$another_tab['order2']), ['class' => 'form-control'])}}
                    @if ($errors->has('another_tab_'.$i.'_order2'))
                        <div class="error_message">{{ $errors->first('another_tab_'.$i.'_order2') }}</div>
                    @endif
                    </div>


                    <div class="form-group @if ($errors->has('another_tab_'.$i.'_memo2')) has-error @endif">
                    {{Form::label('another_tab_'.$i.'_memo2', '説明',['class' => 'form_label'])}}
                    {{Form::textarea('another_tab_'.$i.'_memo2', is_null($another_tab) ? null : $another_tab->hospital_image->memo2, ['class' => 'form-control','rows' => "2"])}}
                    @if ($errors->has('another_tab_'.$i.'_memo2'))
                        <div class="error_message">{{ $errors->first('another_tab_'.$i.'_memo2') }}</div>
                    @endif
                    </div>
                </div>
            </div>
                @if(!is_null($another_tab))
                    <p style="text-align: center; margin-top: 10px">
                        <a onclick="return confirm('その他タブを削除します、よろしいですか？')" class="btn btn-mini btn-danger" href="{{ route('hospital.image.delete', ['hospital' => $hospital->id, 'hospital_category_id' => $another_tab->id, 'hospital_image_id' => $another_tab->hospital_image_id]) }}">
                            削除
                        </a>
                    </p>
                @endif
            <a href="javascript:void(0)" class="another_add btn btn-info" style="@if(!is_null($another_tab) OR $i == 1)display: none; @endif z-index: {{ 100 - $i}}">
                <i class="icon-trash icon-white"></i>
                追加
            </a>
        @endforeach
    <!--//登録済のタブ画像フォーム-->
        <!--未登録のタブ画像フォーム-->
        @for ($i = $another_tab_count; $i <= 30; $i++)
            {{--@if(!in_array($i, $another_show_order2))--}}
                <div class="row photo-tab" data-order="{{$i}}" @if($i != 1 && (!old('another_tab_'.$i.'_order2') && !old('another_tab_'.$i.'_memo2')) ) style="display: none" @endif>
                    <div class="col-sm-6">
                        <div class="tab_image_area">
                            <img src="/img/no_image.png">
                        </div>
                        <label class="file-upload btn btn-primary">
                            ファイル選択 {{Form::file("another_tab_".$i, ['class' => 'field', 'accept' => 'image/*'])}}
                        </label>
                        @if ($errors->has('another_tab_'.$i))
                            <div class="error_message">
                                {{ $errors->first('another_tab_').$i }}
                            </div>
                        @endif
                        {{Form::hidden('another_tab_'.$i.'_location', FileLocationNo::TAB_CATEGORY_ANOTHER )}}
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group @if ($errors->has('another_tab_'.$i.'_order2')) has-error @endif">
                            <label for="another_tab_{{$i}}_order2">表示順
                                <span class="form_required">必須</span>
                            </label>
                        {{Form::text('another_tab_'.$i.'_order2', null, ['class' => 'form-control'])}}
                        @if ($errors->has('another_tab_'.$i.'_order2'))
                            <div class="error_message"> {{ $errors->first('another_tab_'.$i.'_order2') }} </div>
                        @endif
                        </div>
                        <div class="form-group @if ($errors->has('another_tab_'.$i.'_memo2')) has-error @endif">
                        {{Form::label('another_tab_'.$i.'_memo2', '説明',['class' => 'form_label'])}}
                        {{Form::textarea('another_tab_'.$i.'_memo2', null, ['class' => 'form-control','rows' => "2"])}}
                        @if ($errors->has('another_tab_'.$i.'_memo2'))
                            <div class="error_message">{{ $errors->first('another_tab_'.$i.'_memo2') }} </div>
                        @endif
                        </div>
                    </div>
                </div>
                <a href="javascript:void(0)" class="another_add btn btn-info" style="@if($i == 1 or (old('another_tab_'.$i.'_order2') or old('another_tab_'.$i.'_memo2')) )display: none; @endif z-index: {{ 100 - $i}}">
                    <i class="icon-trash icon-white"></i>
                    追加
                </a>
        {{--@endif--}}
    @endfor
    <!--//未登録のタブ画像フォーム-->
    </div>
    <!--・//その他タブ-->
    </div>
    <div class="form-entry box-body">
        <h2>写真</h2>
        <div class="row" id="select-tab-photo">
            @for ($i = 1; $i <= 4; $i++)
                <div class="col-sm-6 col-select-photo">
                    {{Form::label('sub_'.$i, '画像選択'.$i,['class' => 'form_label'])}}
                        <div class="select-photo-area">
                            <?php $select_photo = $select_photos->where('order', $i)->first(); ?>
                            @if (isset($select_photo->hospital_image) && isset($select_photo->hospital_image->id) && !is_null($select_photo->hospital_image->path))
                                <input type="hidden" value="{{$select_photo->hospital_image->id}}" class="select-photo" name="select_photo[{{$i}}]">
                                <p class="photo"><img src="{{$select_photo->hospital_image->path}}"></p>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticModal{{$i}}">
                                    写真選択
                                </button>
                                <button type="button" class="btn btn-default select-photo-delete" data-hospital_id="{{$select_photo->hospital_id}}" data-hospital_category="{{$select_photo->id}}" data-path="/img/no_image.png">
                                        削除
                                 </button>
                            @else
                                <input type="hidden" value="" class="select-photo" name="select_photo[{{$i}}]">
                                <p class="photo"><img src="/img/no_image.png"></p>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticModal{{$i}}">
                                    写真選択
                                </button>
                                <button type="button" class="btn btn-default unselect" data-path="/img/no_image.png">
                                    削除
                                </button>

                            @endif

                        </div>
                </div>
                <div class="modal" id="staticModal{{$i}}" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-show="true" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&#215;</span><span class="sr-only">閉じる</span>
                                </button>
                                <h4 class="modal-title">写真選択</h4>
                            </div><!-- /modal-header -->
                            <div class="modal-body">
                                <?php $select_tab_photos = $hospital->hospital_categories->where('image_order', ImageGroupNumber::IMAGE_GROUP_TAB)->where('is_display', SelectPhotoFlag::UNSELECTED);?>

                                <div class="row">
                                    <?php $select_photo_count = 0; ?>
                                    @foreach($select_tab_photos as $key => $photo)
                                        <div class="col-sm-4 select-photo">
                                            @if($photo->hospital_image->path != "")
                                                <img class="object-fit" src="{{$photo->hospital_image->path}}" width="100%"><button type="button" class="btn btn-default select-tab-button" data-path="{{$photo->hospital_image->path}}" data-imageid="{{$photo->hospital_image->id}}">選択</button>
                                                <?php $select_photo_count ++; ?>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($select_photo_count === 0)
                                        <p>写真タブにまだ画像が設定されていません。</p>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal" data-target="#staticModal">閉じる</button>
                            </div>
                        </div> <!-- /.modal-content -->
                    </div> <!-- /.modal-dialog -->
                </div> <!-- /.modal -->
            @endfor
        </div>
    </div>

</div>

<div class="box box-primary form-box">
    <div class="form-entry box-body" style="position: relative">
        <h2>医師・スタッフ</h2>
    <div class="row" style="margin-top: 50px">
    @for ($i = 1; $i <= 10; $i++)
        <?php $staff = $hospital->hospital_categories->where('image_order', ImageGroupNumber::IMAGE_GROUP_STAFF)->where('file_location_no', $i)->first();?>
        <div class="col-sm-6 staff_box" data-order="{{$i}}" @if(is_null($staff) && $i != 1) style="display: none" @endif>
            <p class="box_staff_title">医師・スタッフ{{$i}}</p>
            @if(!is_null($staff) && !is_null($staff->hospital_image) && !is_null($staff->hospital_image->path))
                <div class="staff_image_area">
                    <img class="object-fit" src="{{$staff->hospital_image->path}}">
                    <p class="file_delete_text">
                        <a class="btn btn-mini btn-danger" onclick="return confirm('{{ trans('messages.delete_image_popup_content') }}')" href="{{ route('hospital.delete_image', ['hospital' => $hospital->id, 'hospital_image_id' => $staff->hospital_image_id]) }}">
                            <i class="icon-trash icon-white"></i>
                            ファイル削除
                        </a>
                    </p>
                    {{Form::hidden('staff_'.$i.'_category_id', $staff['id'] )}}
                </div>
            @else
                <div class="staff_image_area">
                    <img src="/img/no_image.png">
                </div>
            @endif
            <label class="file-upload btn btn-primary">
                ファイル選択 {{Form::file("staff_".$i, ['class' => 'field', 'accept' => 'image/*'])}}
            </label>
            @if ($errors->has('staff_'.$i))
                <div class="error_message">
                {{ $errors->first('staff_'.$i) }}
                </div>
            @endif
            <div class="form-group @if ($errors->has('staff_'.$i.'_name')) has-error @endif">
            {{Form::label('staff_'.$i.'_name', '名前',['class' => 'form_label'])}}
            {{Form::text('staff_'.$i.'_name', is_null($staff) ? '' : $staff->name, ['class' => 'form-control'])}}
            @if ($errors->has('staff_'.$i.'_name'))
                {{ $errors->first('staff_'.$i.'_name') }}
            @endif
            </div>
            <div class="form-group @if ($errors->has('staff_'.$i.'_career')) has-error @endif">
            {{Form::label('staff_'.$i.'_career', '経歴',['class' => 'form_label'])}}
            {{Form::textarea('staff_'.$i.'_career', is_null($staff) ? '' : $staff->career, ['class' => 'form-control', 'rows'=> 2])}}
            @if ($errors->has('staff_'.$i.'_career'))
                {{ $errors->first('staff_'.$i.'_career') }}
            @endif
            </div>
            <div class="form-group @if ($errors->has('staff_'.$i.'_memo')) has-error @endif">
            {{Form::label('staff_'.$i.'_memo', 'コメント',['class' => 'form_label'])}}
            {{Form::textarea('staff_'.$i.'_memo', is_null($staff) ? '' : $staff->memo, ['class' => 'form-control', 'rows'=> 4])}}
            @if ($errors->has('staff_'.$i.'_memo'))
                {{ $errors->first('staff_'.$i.'_memo') }}
            @endif
            </div>
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
</div>
