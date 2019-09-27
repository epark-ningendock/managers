(function($){

    /* ---------------------------------------------------
    Prepare popup box to delete form
    -----------------------------------------------------*/
    (function() {
        $('.delete-popup-btn').on('click', function(){
            let id = $(this).data('id');
            let targetForm = $(this).data('target-form') || '#delete-record-form';
            let message = $(this).data('message');
            let btnText = $(this).data('button-text') || '削除';
            let targetFormAction = $(targetForm).attr('action').replace(':id', id);
            let modal = $(this).data('modal') || '#confirm-modal';
            $(targetForm).attr('action', targetFormAction);

            Modal.showConfirm(message, btnText, modal, function() {
                $(modal).find('input, select, textarea').each(function(i, e){
                    e = $(e);
                    $(`<input type="hidden" name="${e.prop('name')}" />`)
                        .val(e.val())
                        .appendTo($(targetForm));
                });
                $(targetForm).submit();
            });
            return false;
        });
    })();


    /* ---------------------------------------------------
    Tab
    -----------------------------------------------------*/
    $('.tab-list').on('click', '.btn', function(e){

          e.preventDefault()
          $(this).tab('show')

        $(this).siblings('.btn').removeClass('active');
        $(this).addClass('active');

    });
    

})(jQuery);