@push('js')
    <script type="text/javascript">

        (function ($) {

            /* ---------------------------------------------------
             Send Mail Popup
            -----------------------------------------------------*/
            $(document).on('click', '#show-send-mail', function(e){
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

            /* ---------------------------------------------------
            Customer Popup Detail
            -----------------------------------------------------*/
            $('.detail-link ').on('click', function(e){
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
                        $('.ajax-data-popup .modal-body-wrapper').empty();
                        $('.ajax-data-popup .modal-body-wrapper').append($(response.data));
                        // hide if name identification content is not included
                        if ($('#name-identification').length == 0 ) {
                            $('#name-identification-tab').hide();
                        } else {
                            $('#name-identification-tab').show();
                        }
                        $('.ajax-data-popup a[href="#basic-information"]').tab('show')
                        $('.ajax-data-popup').modal('show');
                    }
                });
            });

        })(jQuery);


    </script>

@endpush