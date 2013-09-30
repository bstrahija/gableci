<script>
var APP_URL = '<?php echo url(); ?>/';
var APP_ROUTE = '<?php echo Route::currentRouteName(); ?>';
</script>

<link href="{{ URL::asset('assets/css/bootstrap.min.css?c=' . date('ymdHis')) }}" rel="stylesheet">
<link href="{{ URL::asset('assets/js/fancybox/jquery.fancybox.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/css/main.css?c=' . date('ymdHis')) }}" rel="stylesheet">

<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="{{ URL::asset('assets/js/bootstrap.min.js?c=' . date('ymdHis')) }}"></script>
<script src="{{ URL::asset('assets/js/fancybox/jquery.fancybox.pack.js') }}"></script>
<script src="{{ URL::asset('assets/js/script.js?c=' . date('ymdHis')) }}"></script>
