<meta charset="utf-8">
<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">

<title>easyVarsity- University Management System</title>

<?php include_once(__DIR__ . '/../helpers/app_settings.php') ?>

<link rel="stylesheet" href="<?php echo STATIC_ROOT . 'libs/css/compiled.min.css' ?>">

<style>
  .add-link {
    padding-left: 12px;
    background: url("<?php echo STATIC_ROOT . 'img/icon_addlink.gif' ?>") 0 .2em no-repeat;
    cursor: pointer;
  }
  .delete-icon {
    padding-left: 12px;
    background: url("<?php echo STATIC_ROOT . 'img/icon_delete.jpg' ?>") 0 .2em no-repeat;
    cursor: pointer;
  }
  .form-control-error-display {
    color: #DA3E16
  }
</style>

<!--[if lt IE 9]>
<script
  src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script
  src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<script src="<?php echo STATIC_ROOT . 'libs/modernizr.js' ?>"></script>
