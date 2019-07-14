<div class="box-body">
    <table class="table">
        <tr>
            <th>施設メイン画像</th>
            <td>
                <?php $main_image_category = $hospital->hospital_categories->firstWhere('image_order', $image_order::IMAGE_GROUP_FACILITY_MAIN); ?>
                @if (!is_null($main_image_category))
                    <div><img src="/img/uploads/300-300-{{$main_image_category->hospital_image->path}}" width="250"></div>
                @endif
                {{Form::file("main", ['class' => 'field'])}}
                @if ($errors->has('image'))
                    {{ $errors->first('image') }}
                @endif
            </td>
        </tr>
        @for ($i = 1; $i <= 4; $i++)
            <tr>
                <th>施設サブ画像 {{ $i }}
                <td>
                    <?php $sub_image_category = $hospital->hospital_categories->where('image_order', $image_order::IMAGE_GROUP_FACILITY_SUB)->where('order2', $i)->first(); ?>
                    @if(!is_null($sub_image_category))
                        <img src="/img/uploads/300-300-{{$sub_image_category->hospital_image->path}}" width="250">
                    @endif
                    {{Form::file("sub_".$i, ['class' => 'field'])}}
                    @if ($errors->has('image'.$i))
                        {{ $errors->first('image'.$i) }}
                    @endif
                </td>
            </tr>
        @endfor
        <tr>
            <th>TOP</th>
            <?php $title = $hospital->hospital_categories->firstWhere('image_order', $image_order::IMAGE_GROUP_TOP); ?>
            <td>
                {{Form::text('title', is_null($title) ? '' : $title->title)}}
                @if ($errors->has('title'))
                    {{ $errors->first('title') }}
                @endif
            </td>
        </tr>
        <tr>
            <th>キャプション</th>
            <td>
                {{Form::text('caption', is_null($title) ? '' : $title->caption)}}
                @if ($errors->has('caption'))
                    {{ $errors->first('caption') }}
                @endif
            </td>
        </tr>
        @for ($i = 1; $i <= 4; $i++)
        <tr>
            <th>こだわり{{$i}}</th>
            <td>
                <?php $image_speciality = $hospital->hospital_categories->where('image_order', $image_order::IMAGE_GROUP_SPECIALITY)->where('order2', $i)->first(); ?>
                @if(!is_null($image_speciality))
                    <img src="/img/uploads/300-300-{{$image_speciality->hospital_image->path}}" width="150">
                @endif
                {{Form::file("speciality_".$i, ['class' => 'field'])}}
                @if ($errors->has('image'.$i))
                    {{ $errors->first('image'.$i) }}
                @endif
            </td>
        </tr>
        @endfor
        <tr>
            <th>地図・アクセス</th>
            <td>
                <?php $map = $hospital->hospital_categories->where('image_order', $image_order::IMAGE_GROUP_MAP)->first(); ?>
                    {{Form::text('map_url', is_null($map) ? '' : $map->hospital_image()->first()->memo1)}}
                    @if ($errors->has('map_url'))
                        {{ $errors->first('map_url') }}
                    @endif
            </td>
        </tr>
        <tr>
            <th>インタビュー</th>
            <td>
                <?php //$interview = $hospital->hospital_categories->where('image_order', $image_order::IMAGE_GROUP_INTERVIEW)->first(); ?>
                    @if(!is_null($interview_top))
                        <img src="/img/uploads/300-300-{{$interview_top->hospital_image->path}}" width="150">
                    @endif
                    {{Form::file("interview_1", ['class' => 'field'])}}
                    @if ($errors->has('interview_1'))
                        {{ $errors->first('interview_1') }}
                    @endif
                @if ($errors->has('interview_1'))
                    {{ $errors->first('interview_1') }}
                @endif
                    {{Form::label('interview_1_title', 'タイトル')}}
                    {{Form::text('interview_1_title', is_null($interview_top) ? '' : $interview_top->title)}}
                    @if ($errors->has('interview_1_title'))
                        {{ $errors->first('interview_1_title') }}
                    @endif
                    {{Form::label('interview_1_caption', 'キャプション')}}
                    {{Form::text('interview_1_caption', is_null($interview_top) ? '' : $interview_top->caption)}}
                    @if ($errors->has('interview_1_caption'))
                        {{ $errors->first('interview_1_caption') }}
                    @endif
                <h2>インタビュー詳細</h2>
                @foreach($interviews as $interview)
                <div class="interview_detail_{{$loop->iteration}}">
                    <div>
                        {{Form::label('interview['. $interview->id .']', '質問')}}
                        {{Form::textarea('interview['. $interview->id .'][question]', is_null($interview) ? '' : $interview->question)}}
                        @if ($errors->has('interview['. $interview->id .'][question]'))
                            {{ $errors->first('interview['. $interview->id .'][question]') }}
                        @endif
                    </div>
                    <div>
                        {{Form::label('interview['. $interview->id .']', '回答')}}
                        {{Form::textarea('interview['. $interview->id .'][answer]', is_null($interview) ? '' : $interview->answer)}}
                        @if ($errors->has('interview['. $interview->id .'][answer]'))
                            {{ $errors->first('interview['. $interview->id .'][answer]') }}
                        @endif
                    </div>
                </div>
                @endforeach
                <div class="interview_detail_new">
                    <div>
                        {{Form::label('interview_new[1][question]', '質問')}}
                        {{Form::textarea('interview_new[1][question]', '')}}
                    </div>
                    <div>
                        {{Form::label('interview_new[1][answer]', '回答')}}
                        {{Form::textarea('interview_new[1][answer]', '')}}
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <th>写真タブ</th>
            <td>
                @for ($i = 1; $i <= 5; $i++)
                <div>
                    <h2>{{$tab_name_list[$i]}}</h2>
                    <?php $tab = $hospital->hospital_categories->where('image_order', $image_order::IMAGE_GROUP_TAB)->where('order2', $i)->first();

                    ?>
                    @if(!is_null($tab))
                        <img src="/img/uploads/300-300-{{$tab->hospital_image->path}}" width="150">
                    @endif
                    {{Form::file("tab_".$i, ['class' => 'field'])}}
                    @if ($errors->has('tab_'.$i))
                        {{ $errors->first('tab_').$i }}
                    @endif
                    <div>
                        {{Form::label('tab_'.$i.'_order1', '表示順')}}
                        {{Form::text('tab_'.$i.'_order1', is_null($tab) ? '' : $tab->order)}}
                        @if ($errors->has('tab_'.$i.'_order1'))
                            {{ $errors->first('tab_'.$i.'_order1') }}
                        @endif
                    </div>
                    <div>
                        {{Form::label('tab_'.$i.'_memo1', 'alt')}}
                        {{Form::text('tab_'.$i.'_memo1', is_null($tab) ? '' : $tab->hospital_image->memo1)}}
                        @if ($errors->has('tab_'.$i.'_memo1'))
                            {{ $errors->first('tab_'.$i.'_memo1') }}
                        @endif
                    </div>
                    <div>
                        {{Form::label('tab_'.$i.'_memo2', '説明')}}
                        {{Form::textarea('tab_'.$i.'_memo2', is_null($tab) ? '' : $tab->hospital_image->memo2)}}
                        @if ($errors->has('tab_'.$i.'_memo2'))
                            {{ $errors->first('tab_'.$i.'_memo2') }}
                        @endif
                    </div>
                </div>
                @endfor
            </td>
        </tr>
        <tr>
            <th>スタッフ</th>
            <td>
                @for ($i = 1; $i <= 10; $i++)
                    <div>
                        <h2>スタッフ{{$i}}</h2>
                        <?php $staff = $hospital->hospital_categories->where('image_order', $image_order::IMAGE_GROUP_STAFF)->where('order2', $i)->first();

                        ?>
                        @if(!is_null($staff))
                            <img src="/img/uploads/300-300-{{$staff->hospital_image->path}}" width="150">
                        @endif
                        {{Form::file("staff_".$i, ['class' => 'field'])}}
                        @if ($errors->has('staff_'.$i))
                            {{ $errors->first('staff_').$i }}
                        @endif
                        <div>
                            {{Form::label('staff_'.$i.'_memo1', 'alt')}}
                            {{Form::text('staff_'.$i.'_memo1', is_null($staff) ? '' : $staff->hospital_image->memo1)}}
                            @if ($errors->has('staff_'.$i.'_memo1'))
                                {{ $errors->first('staff_'.$i.'_memo1') }}
                            @endif

                        </div>
                        <div>
                            {{Form::label('staff_'.$i.'_name', '名前')}}
                            {{Form::text('staff_'.$i.'_name', is_null($staff) ? '' : $staff->name)}}
                            @if ($errors->has('staff_'.$i.'_name'))
                                {{ $errors->first('staff_'.$i.'_name') }}
                            @endif
                        </div>
                        <div>
                            {{Form::label('staff_'.$i.'_career', '経歴')}}
                            {{Form::text('staff_'.$i.'_career', is_null($staff) ? '' : $staff->career)}}
                            @if ($errors->has('staff_'.$i.'_career'))
                                {{ $errors->first('staff_'.$i.'_career') }}
                            @endif
                        </div>
                        <div>
                            {{Form::label('staff_'.$i.'_memo', 'コメント')}}
                            {{Form::textarea('staff_'.$i.'_memo', is_null($staff) ? '' : $staff->memo)}}
                            @if ($errors->has('staff_'.$i.'_memo'))
                                {{ $errors->first('staff_'.$i.'_memo') }}
                            @endif
                        </div>
                    </div>
                @endfor
            </td>
        </tr>
    </table>
</div>
