@push('js')
    <script type="text/javascript">

        (function ($) {

            /* ---------------------------------------------------
            Get request parameter
            -----------------------------------------------------*/
            window.getUrlParameter = function(name, url) {
                if (!url) url = window.location.href;
                name = name.replace(/[\[\]]/g, '\\$&');
                var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, ' '));
            }


            /* ---------------------------------------------------
            Reservation list
            -----------------------------------------------------*/
            $(document).on('click','.reservation.ajax-paginator a', function(e){
                e.preventDefault();
                const $link = $(this);
                const pageId = getUrlParameter('page', $link.attr('href'));
                const customerId = $('#customer-id').val();

                $.ajax({
                    url: '{{ route('customer.detail') }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        page_id: pageId,
                        id: customerId
                    },
                    cache: false,
                    success: function (response) {
                        $('.ajax-data-popup .modal-body-wrapper').empty();
                        $('.ajax-data-popup .modal-body-wrapper').append($(response.data));
                        // reactive for force change
                        $('.ajax-data-popup a[href="#basic-information"]').tab('show');
                        $('.ajax-data-popup a[href="#accepted-guidance-history"]').tab('show'); // Select tab by name
                    }
                });
            });

            $(document).on('click','.name-identification.ajax-paginator a', function(e){
                e.preventDefault();
                const $link = $(this);
                const pageId = getUrlParameter('page', $link.attr('href'));
                const customerId = $('#customer-id').val();
                $.ajax({
                    url: '{{ route('customer.detail') }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        identification_page_id: pageId,
                        id: customerId
                    },
                    cache: false,
                    success: function (response) {
                        $('.ajax-data-popup .modal-body-wrapper').empty();
                        $('.ajax-data-popup .modal-body-wrapper').append($(response.data));
                        // reactive for force change
                        $('.ajax-data-popup a[href="#basic-information"]').tab('show');
                        $('.ajax-data-popup a[href="#name-identification"]').tab('show'); // Select tab by name


                    }
                });
            });
        })(jQuery);


    </script>

@endpush