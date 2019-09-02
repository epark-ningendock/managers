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

            $(document).on('click', '#perform-integration', function(e) {
                e.preventDefault();
                if ($('.identical_ids:checked').length == 0) {
                    return;
                }
                const data = $(this).parents('form').serialize();
                const customerId = $('#customer-id').val();
                const url = '{{ route('customer.integration', ':id') }}';

                $.ajax({
                    url: url.replace(':id', customerId),
                    method: "POST",
                    data: data,
                    cache: false,
                    success: function (data) {
                        if(data.success) {
                            $('.ajax-data-popup').modal('hide');

                            // showing success message
                            const message = $(`<div class="alert alert-success alert-block">
                                                 <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                                 <strong class="white-space">${data.success}</strong>
                                               </div>`).prependTo('section.content>div.row>div.col-xs-12>div.box');

                            //scroll up for message box
                            $('html, body').animate({
                                scrollTop: 0
                            }, 300);

                            $('.alert-success').fadeOut(4000, function() {
                                $('.alert-success').remove();
                            });
                        } else {
                            let detailMessage = '';
                            if (data.error) {
                                detailMessage = `<strong class="white-space">${data.error}</strong>`;
                            } else if(data.errors) {
                                detailMessage = '<url>';
                                for(const key in data.errors) {
                                    detailMessage += `<li><strong class="white-space">${ data.errors[key] }</strong></li>`;
                                }
                                detailMessage += '</url>';
                            }

                            // showing error message
                            const message = $(`<div class="alert alert-danger alert-block">
                                                 <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                                 ${detailMessage}
                                               </div>`).prependTo('#name-identification .modal-body');

                            $('.alert-danger').fadeOut(4000, function() {
                                $('.alert-danger').remove();
                            });
                        }
                    }
                });

            });
        })(jQuery);


    </script>

@endpush