<!doctype html>
<html lang="en">
<head>
	<?php partial('_partial.meta'); ?>

	<?php partial('_partial.assets_head'); ?>
</head>
<body>

<script type="text/x-handlebars">
	<?php hbs_template('_partial.header'); ?>

	<div class="container">
		{{outlet}}
	</div>
</script>

<script type="text/x-handlebars" data-template-name="index">
	<?php hbs_template('reservations'); ?>
</script>

<script type="text/x-handlebars" data-template-name="reservations">
	<?php hbs_template('reservations'); ?>
</script>

<script type="text/x-handlebars" data-template-name="dishes">
	<?php hbs_template('dishes'); ?>
</script>

<?php partial('_partial.footer'); ?>

</body>
</html>
