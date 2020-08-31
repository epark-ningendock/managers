
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ url('css/bootstrap-datetimepicker.min.css') }}">
@endpush

@push('js')
    <script src="{{ url('js/handlebars.js') }}"></script>
    <script src="{{ url('js/moment.min.js') }}"></script>
    <script src="{{ url('js/moment-ja.js') }}"></script>
    <script src="{{ url('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ url('js/bootstrap3-typeahead.min.js') }}"></script>
    <script type="text/javascript">
        (function ($) {
            $('.datetimepicker').datetimepicker();
        })(jQuery);
    </script>
@endpush
