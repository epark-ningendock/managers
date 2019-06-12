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
            Customer Popup Detail
            -----------------------------------------------------*/
            $(document).on('click','.ajax-paginator a', function(e){
                e.preventDefault();
                var $link = $(this);
                var page_id = getUrlParameter('page', $link.attr('href'));

                $.ajax({
                    url: '{{ route('customer.detail') }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        page_id: page_id,
                        id: '{{ $customer_detail->id }}'
                    },
                    cache: false,
                    success: function (response) {
                        $('.ajax-data-popup .modal-body-wrapper').html(response.data);
                        $('.ajax-data-popup a[href="#accepted-guidance-history"]').tab('show') // Select tab by name
                    }
                });
            });

        })(jQuery);


    </script>

@endpush