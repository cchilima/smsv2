{{-- <link rel="icon" href="{{ asset('global_assets/images/favicon.png') }}"> --}}

{{-- <!-- Global stylesheets --> --}}
<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
<link href="{{ asset('global_assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
<link href=" {{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}
<link href=" {{ asset('assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
<link href=" {{ asset('assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
<link href=" {{ asset('assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
<link href=" {{ asset('assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
<!-- /global stylesheets -->

{{-- DatePickers --}}
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}" type="text/css">

{{-- Custom App CSS --}}
<link href=" {{ asset('assets/css/qs.css') }}" rel="stylesheet" type="text/css">

{{--   Core JS files --}}
<script src="{{ asset('global_assets/js/main/jquery.min.js') }} "></script>
<script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }} "></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="{{ asset('global_assets/js/plugins/loaders/blockui.min.js') }} "></script>
<script src="{{ 'https://cdn.jsdelivr.net/npm/chart.js' }}"></script>

{{-- Editable table rows --}}
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/css/jquery-editable.css"
    rel="stylesheet" /> --}}

@livewireStyles()
@stack('css')
