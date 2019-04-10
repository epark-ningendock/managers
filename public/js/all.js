(function($){

    /* ---------------------------------------------------
    Prepare popup box to delete form
    -----------------------------------------------------*/
    function delete_popup_btn_action() {
        $('.delete-popup-btn').on('click', function(){

            let id = $(this).data('id');
            let target_form = $(this).data('target-form');
            let target_form_action = $(target_form).attr('action').replace(':id', id);
            let popup_id = $(this).data('target').replace('#', '');

            $('#' + popup_id).modal('show').attr('data-target-form', target_form);
            $(target_form).attr('action', target_form_action);

            return false;

        });
    }

    /* ---------------------------------------------------
    Submit form after popup done!
    -----------------------------------------------------*/
    function delete_record() {
        $('.delete-btn').on('click', function(){
            let target_form = $(this).closest('.delete-record-popup').data('target-form');
            $(target_form).submit();
        });
    }

    // delete_popup_btn_action();
    // delete_record()

})(jQuery);