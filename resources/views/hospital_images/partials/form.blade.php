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
                {{Form::text('title', $title->title)}}
                @if ($errors->has('title'))
                    {{ $errors->first('title') }}
                @endif
            </td>
        </tr>
        <tr>
            <th>キャプション</th>
            <td>
                {{Form::text('caption', $title->caption)}}
                @if ($errors->has('caption'))
                    {{ $errors->first('caption') }}
                @endif
            </td>
        </tr>
    </table>
</div>
