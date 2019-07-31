@push('js')
    <script>

        (function ($) {


            function dateLoader(ajaxRoute) {

                $.ajax({
                    url: ajaxRoute,
                    method: "GET",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('.calendar-box').html(response.data);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Reservation days showing error");
                    }
                });

            }


            $(document).ready(function(){

                let thisValue = $('#course_id').val();

                if ( thisValue ) {
                    let ajaxRoute = "{{  route('course.reservation.days', ['course_id' => ':1', 'page' => old('page_number', 1) ]) }}".replace(":1", thisValue);
                    dateLoader(ajaxRoute);

                    $reserveDate = '{{ old('reservation_date') }}';

                    if ( ! $reserveDate ) {
                        $reserveDate = '{{ (isset($reservation->reservation_date) ) ? $reservation->reservation_date->format('Y-m-d') : '' }}';
                    }

                    if ( $reserveDate ) {
                        setTimeout(function(){
                            $('td[data-date="' + $reserveDate + '"]').addClass('it-would-reserve');
                        }, 500);
                    }

                }


            });

            /* ---------------------------------------------------
            Show datepicker depending on course id
            -----------------------------------------------------*/
            $(document).on('change', '#course_id', function(){

                let thisValue = $(this).val();
                let ajaxRoute = "{{  route('course.reservation.days', ['course_id' => ':1']) }}".replace(":1", thisValue);

                dateLoader(ajaxRoute);
                
            });

            /* ---------------------------------------------------
            Load datepicker through ajax
            -----------------------------------------------------*/
            
            $(document).on('click', '.prev-next-link', function(event){
                event.preventDefault();

                let ajaxRoute = $(this).attr('href');

                $.ajax({
                    url: ajaxRoute,
                    method: "GET",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('.calendar-box').html(response.data);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                         alert("Reservation days showing error");
                    }                    
                });
                
            });


            $(document).on('click', '.it-can-reserve', function(){
                $(this)
                .siblings('td').removeClass('it-would-reserve').end()
                .addClass('it-would-reserve');
                $('#reservation_date').val($(this).attr('data-date'));
            });          

          

        })(jQuery);

    </script>
@endpush