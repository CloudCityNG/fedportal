<?php
require_once(__DIR__ . '/../helpers/app_settings.php');
?>

<script src="<?php echo STATIC_ROOT . 'libs/compiled.min.js' ?>"></script>

<script>
  var STATIC_ROOT = "<?php echo STATIC_ROOT?>";
  var SESSION_TIME_OUT = parseInt("<?php echo SESSION_TIME_OUT?>");
  var SESSION_TIME_OUT_ALERT = parseInt("<?php echo SESSION_TIME_OUT_ALERT?>");

  function resetValidator($element) {
    $element.find('.has-error').removeClass('has-error');
    $element.find('.has-success').removeClass('has-success');

    $element.find('.help-block.with-errors').each(function() {
      $(this).html('');
    });
  }
</script>
