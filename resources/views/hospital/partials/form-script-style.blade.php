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
            var route = "{{ route('hospital.search.text') }}";
            $('#s_text').typeahead({
                source: function (term, process) {
                    return $.get(route, {term: term}, function (data) {
                        return process(data);
                    });
                },
                displayText: function (item) {
                    return item.name + ' - ' + item.address1;
                },
                afterSelect: function (item) {
                    $('#s_text').val(item.name);
                }
            });
            $('.datetimepicker').datepicker({
                language:'ja',
                format: 'yyyy-mm-dd',
            });

        })(jQuery);

    </script>

@endpush