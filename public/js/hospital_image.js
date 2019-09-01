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


    $('#interview_add').click(function() {
        let i = $('.interview_detail_new').length;
        console.log(i);
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
                // /console.log(file.parent());
            }
            reader.readAsDataURL(file);
        });
    });
});