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
    </table>
</div>
