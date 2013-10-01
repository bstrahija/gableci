<!doctype html>
<html lang="en">
<head>
	<?php partial('_partial.meta'); ?>

	<?php partial('_partial.assets_head'); ?>
</head>
<body>

<?php partial('_partial.header'); ?>

<script type="text/x-handlebars">
  <h2>Welcome to Ember.js</h2>

  {{outlet}}
</script>

<script type="text/x-handlebars" data-template-name="index">
  <ul>
  {{#each item in model}}
    <li>{{item}}</li>
  {{/each}}
  </ul>
</script>

<?php partial('_partial.footer'); ?>

</body>
</html>
