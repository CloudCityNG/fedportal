<?php

require_once(__DIR__ . '/../helpers/app_settings.php');

?>

<script src="<?php echo STATIC_ROOT . 'libs/compiled.min.js' ?>"></script>

<script>
  var STATIC_ROOT = "<?php echo STATIC_ROOT?>";

  var DATE_PICKER_OPTIONS = {
    yearRange: "-50:+03",
    changeMonth: true,
    changeYear: true,
    inline: true,
    showButtonPanel: true,
    dateFormat: "dd-mm-yy",
    beforeShow: function () {
      $(".ui-datepicker").css('font-size', 15)
    }
  };

  $(function () {
    $(".date-picker").datepicker(DATE_PICKER_OPTIONS);
  });

  function resetValidator($element) {
    $element.find('.has-error').removeClass('has-error');
    $element.find('.has-success').removeClass('has-success');

    $element.find('.help-block.with-errors').each(function () {
      $(this).html('');
    });
  }
</script>
