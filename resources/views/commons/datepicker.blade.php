
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ url('css/bootstrap-datepicker.min.css') }}">
@endpush

@push('js')
    <script src="{{ url('js/handlebars.js') }}"></script>
    <script src="{{ url('js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('js/bootstrap-datepicker.ja.min.js') }}"></script>
    <script src="{{ url('js/bootstrap3-typeahead.min.js') }}"></script>
    <script type="text/javascript">
        (function ($) {
            $('.datepicker').datepicker({
                language: 'ja',
                autoclose: true,
            });
        })(jQuery);
    </script>
@endpush
