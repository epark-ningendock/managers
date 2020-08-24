@push('js')
    <script>

        (function ($) {


            function checkPrevNextButton() {
                ($('.show-tr').prev('tr').length === 0) ? $('.prev-link').hide() : $('.prev-link').show();
                ($('.show-tr').next('tr').length === 0) ? $('.next-link').hide() : $('.next-link').show();
            }

            function dateLoader(ajaxRoute, chooseByCalendar) {

                if(typeof chooseByCalendar === 'undefined') chooseByCalendar = false;

                $.ajax({
                    url: ajaxRoute,
                    method: "GET",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        const oldData = $('.calendar-box').data('old');

                        if (chooseByCalendar) {
                            $('.calendar-box').html(response.data);
                            $('.hor-date-table tbody tr').addClass('hide-tr').first('tr').addClass('show-tr');
                        } else if ( $('.hor-date-table').length > 0 ) {
                            const hasShow_tr = $('.hor-date-table tbody tr').hasClass('show-tr');
                            $('.hor-date-table tbody').empty();
                            $('.hor-date-table tbody').append($(response.data).find('tbody').children());
                            if (hasShow_tr){
                                $('.hor-date-table tbody tr').addClass('hide-tr');
                                $('.hor-date-table tbody tr').first().addClass('show-tr');
                            }
                        } else {
                            $('.calendar-box').html(response.data);
                            $('.hor-date-table tbody tr').addClass('hide-tr').first('tr').addClass('show-tr');
                        }

                        if (!chooseByCalendar && oldData) {
                            const selectedDate = $('.hor-date-table tbody td[data-date=' + oldData + ']')
                            $('#reservation_date').val(oldData);
                            selectedDate.addClass('it-would-reserve');
                            $('.hor-date-table tbody tr').removeClass('show-tr').addClass('hide-tr');
                            selectedDate.parent('tr').addClass('show-tr');
                        }

                        $('.date-row-bar').show();
                        checkPrevNextButton();
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Reservation days showing error");
                    }
                });

            }


            $(document).ready(function(){

                const thisValue = $('#course_id').val();
                const reservationDate = $('.calendar-box').data('old');

                if ( thisValue ) {
                    let ajaxRoute = "{{  route('course.reservation.days', ['course_id' => ':1']) }}".replace(":1", thisValue) + '?reservation_date=' + reservationDate;
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

                if (thisValue) {
                    let ajaxRoute = "{{  route('course.reservation.days', ['course_id' => ':1']) }}".replace(":1", thisValue);
                    dateLoader(ajaxRoute);
                } else {
                    $('.calendar-box').children().remove();
                    $('.date-row-bar').hide();

                }

                
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

                if ( show_tr.nextAll('tr').length == 1 ) {
                    const lastDate = new Date(week_row.last().find('td').last().data('date'));
                    lastDate.setDate(lastDate.getDate() + 1);
                    const  startDate = lastDate.getFullYear() + '/' + (lastDate.getMonth() + 1) + '/' + lastDate.getDate();
                    let ajaxRoute = "{{  route('course.reservation.days', ['course_id' => ':1']) }}".replace(":1", $('#course_id').val()) + '?start_date=' + startDate;
                    dateLoader(ajaxRoute, true);
                }

                if ( $(this).hasClass('prev-link')  ) {
                    week_row.eq(active_week.index() - 1).addClass('show-tr');
                } else {
                    week_row.eq(active_week.index() + 1).addClass('show-tr');
                }

                checkPrevNextButton();

                // year label
                const temp = $('.show-tr td:last').data('date').split('-');
                $('#year-label').text(temp[0]);
            });

            /* ---------------------------------------------------
            Load calendarpicker through ajax
            -----------------------------------------------------*/
            $(document).on('change', '#startdate', function(){
                event.preventDefault();

                let startdate = $(this).val();
                let ajaxRoute = "{{  route('course.reservation.days', ['course_id' => ':1']) }}".replace(":1", $('#course_id').val()) + '?start_date=' + startdate;
                dateLoader(ajaxRoute, true);
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
