$(document).ready(function() {
    $('.file-upload').file_upload();
    $('.staff_add').click(function() {[]
        $(this).hide();
        $(this).prev().show();
    });
    $('.facility_add').click(function() {[]
        $(this).hide();
        $(this).prev().show();
    });

    $('.internal_add').click(function() {[]
        $(this).hide();
        $(this).prev().show();
    });
    $('.external_add').click(function() {[]
        $(this).hide();
        $(this).prev().show();
    });

    $('.another_add').click(function() {[]
        $(this).hide();
        $(this).prev().show();
    });

    $('.speciality_add').click(function() {[]
        $(this).hide();
        $(this).prev().show();
    });

    $('#sampleModal').on('show.bs.modal', function (event) {
        var clickBotton = $(event.relatedTarget).data('clickbotton');
        $('#showBsModal').text(clickBotton+"をクリックしてshowメソッドを呼び出しましたね。");
    });

    $('#interview_add').click(function() {
        let i = $('.interview_detail_new').length;
        if(i >= 20) {
            alert('インタビューは20個までです');
            return false;
        }
        $('.interview_detail_new:last').after(
            '<div class="interview_list interview_detail_new">\n' +
            '                <div>\n' +
            '                    <label for="interview_new[' + i + '][question]" class="form_label">質問</label>\n' +
            '                    <input class="form-control" name="interview_new[' + i + '][question]" type="text" value="" id="interview_new[][question]">\n' +
            '                </div>\n' +
            '                <div>\n' +
            '                    <label for="interview_new[' + i + '][answer]" class="form_label">回答</label>\n' +
            '                    <input class="form-control" name="interview_new[' + i + '][answer]" type="text" value="" id="interview_new[][answer]">\n' +
            '                </div>\n' +
            '            </div>'
        );
    });
    $('.select-tab').on("click", function() {
        $(this).next().slideToggle();
        $(this).parent().next().slideToggle();
    });

    $(function() {
        $('input[type=file]').after('<span></span>');

        // アップロードするファイルを選択
        $('input[type=file]').change(function() {
            var file = $(this).prop('files')[0];
            var tmp_area = $(this).parent().prev();

            // 画像以外は処理を停止
            if (! file.type.match('image.*')) {
                // クリア
                $(this).val('');
                $('span').html('');
                return;
            }

            // 画像表示
            var reader = new FileReader();
            reader.onload = function() {
                var img_src = $('<img>').attr('src', reader.result);
                tmp_area.html(img_src);
            }
            reader.readAsDataURL(file);
        });
    });

    'use strict';
    // JavaScript で表示
    $('#staticModalButton').on('click', function() {
        $('#staticModal').modal();
    });

    $('.select-tab-button').on('click', function() {
        const sub_image = $(this).parents('.modal').prev().find('.sub_image_area img');
        const sub_image_hidden = $(this).parents('.modal').prev().find('.select-photo');
        const selected_hospital_image_id = $(this).data('imageid');
        console.log($(this).data('selectnum'));
        let modal_close = [];
        const modal = $(this).parents('.modal');
        //let modal_close = true;

        modal.parent().find('.select-photo').each(function(i, elem) {
            if(sub_image_hidden.val() != elem.value && selected_hospital_image_id == elem.value ) {
                modal_close.push(elem.value);
            }
        });
        console.log(modal_close.length)

        if(modal_close.length >= 1 ) {
            alert('他の写真選択で設定済です');
        } else {
            sub_image_hidden.attr({
                'value': $(this).data('imageid'),
            });
            sub_image.attr({
                'src': $(this).data('path'),
            });

            $('.close').click();//選択時に閉じるようにする。
        }

    });

    //削除ボタン（選択した画像の削除）
    $('.unselect').on('click', function() {
        const image = $(this).parent().find('img');
        const select_photo_val = $(this).parent().find('.select-photo');
        select_photo_val.val('');
        image.attr('src',$(this).data('path'));

        console.log(select_photo_val);

    });

    $('.select-photo-delete').on('click', function() {
        const category_id = $(this).data('hospital_category');
        const image = $(this).parent().find('img');
        const hospital_id = $(this).data('hospital_id');
        const src = $(this).data('path');
        const btn = $(this);

        $.ajax({
            url: "/hospital/" + hospital_id + "/images/" + category_id + "/delete_hospital_category/",
            type: 'GET',
            contentType: 'application/json; charset=UTF-8',
            headers: {
                'Content-Type': 'application/json',
            },
        }).done(function(data, textStatus){
            btn.addClass('unselect');
            image.attr('src',src);
        }).fail (function(xhr, textStatus, errorThrown){
        });

    });

});