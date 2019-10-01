@push('js')
    <script type="text/javascript">

        (function ($) {

            /* ---------------------------------------------------
            Customer Popup Detail
            -----------------------------------------------------*/
            $('.send-email').on('click', function(e){
                e.preventDefault();
                var $link = $(this);
                $.ajax({
                    url: $link.attr('data-route'),
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: $link.attr('data-id')
                    },
                    cache: false,
                    success: function (response) {
                        // to unbind js events
                        $('.std-modal-box .modal-content').children().remove();
                        $('.std-modal-box .modal-content').append($(response.data));
                        $('.std-modal-box').modal('show');
                    }
                });
            });

        })(jQuery);


    </script>

@endpush