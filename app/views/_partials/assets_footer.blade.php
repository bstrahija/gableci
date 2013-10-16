<script>
var APP_URL = '<?php echo url(); ?>/';
var APP_ROUTE = '<?php echo Route::currentRouteName(); ?>';
</script>

<script src="//code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/fancybox/jquery.fancybox.pack.js') }}"></script>
<script src="{{ URL::asset('assets/js/hook/hook.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins.js') }}"></script>
<script src="{{ URL::asset('assets/js/script.js?c=' . date('ymdHis')) }}"></script>
