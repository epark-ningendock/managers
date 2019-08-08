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

                        if ( $('.hor-date-table').length > 0 ) {

                            $('.hor-date-table tbody').append($(response.data).find('tbody').children());

                        } else {

                            $('.calendar-box').html(response.data);
                            $('.hor-date-table tbody tr').addClass('hide-tr').first('tr').addClass('show-tr');

                        }

                        $('.date-row-bar').show();
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
                            $('#reservation_date').val($reserveDate);
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

                week_row = $('.hor-date-table tbody tr');
                show_tr = $('.show-tr');
                active_week = week_row.siblings('.show-tr');

                week_row.removeClass('show-tr');

                if ( show_tr.nextAll('tr').length == 3 ) {
                    let ajaxRoute = $(this).attr('href');
                    dateLoader(ajaxRoute);
                }

                if ( $(this).hasClass('prev-link')  ) {

                    week_row.eq(active_week.index() - 1).addClass('show-tr');

                    if ( show_tr.prev('tr').index() === 0 ) {
                        $('.prev-link').remove();
                    }

                } else {

                    week_row.eq(active_week.index() + 1).addClass('show-tr');

                    if ( $(this).siblings('.prev-link').length === 0 ) {
                        $('<a href="#" class="prev-next-link prev-link fl"><<</a>').prependTo($(".paginate-box"));
                    }

                }

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
