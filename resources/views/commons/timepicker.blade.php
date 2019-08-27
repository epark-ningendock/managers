@push('css')
    <link rel="stylesheet" href="{{ url('css/bootstrap-datetimepicker.min.css') }}"/>
@endpush

@push('js')
    <script src="{{ url('js/moment.min.js') }}"></script>
    <script src="{{ url('js/moment-ja.js') }}"></script>
    <script src="{{ url('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $('.time-picker').each(function() {
                $(this).datetimepicker({
                    locale: 'ja',
                    format: 'LT',
                    stepping: 5
                });
            });
        });
    </script>
@endpush
