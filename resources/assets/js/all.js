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
            $(targetForm).attr('action', targetFormAction);

            Modal.showConfirm(message, btnText, function() {
                $(targetForm).submit();
            });
            return false;
        });
    })();

})(jQuery);