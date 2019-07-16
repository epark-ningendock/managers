$(document).ready(function() {
    $('.staff_add').click(function() {[]
        $(this).hide();
        $(this).prev().show();
    });

    $('#interview_add').click(function() {
        let i = $('.interview_detail_new').length;
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
    })

});