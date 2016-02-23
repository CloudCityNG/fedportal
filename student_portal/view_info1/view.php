<!doctype html>
<html class="no-js" lang="">
<head>
  <?php require(__DIR__ . '/../../includes/header.php'); ?>
  <link rel="stylesheet" href="<?php echo path_to_link(__DIR__ . '/../../libs/css/main.min.css', true) ?>">
  <link rel="stylesheet" href="<?php echo $cssPath; ?>"/>
</head>

<body>
  <div class="app">
    <?php require(__DIR__ . '/../includes/nav.php') ?>

    <section class="layout">
      <section class="main-content">
        <div class="content-wrap">
          <div class="wrapper">
            <section class="panel">
              <div class="panel-body">
                <?php require(__DIR__ . '/view-main.php') ?>
              </div>
            </section>
          </div>
        </div>
      </section>
    </section>
  </div>

  <?php include(__DIR__ . '/../../includes/js-footer.php'); ?>
  <script src="<?php echo $jsPath ?>"></script>
</body>
</html>
