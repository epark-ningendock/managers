@push('js')
    <script type="text/javascript">

        (function ($) {

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
                        $('.ajax-data-popup .modal-body-wrapper').html(response.data);
                        $('.ajax-data-popup').modal('show');
                    }
                });
            });

        })(jQuery);


    </script>

@endpush