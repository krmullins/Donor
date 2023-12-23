<?php define("PREPEND_PATH", ""); $hooks_dir = dirname(__FILE__); include("defaultLang.php"); include("language.php"); include("lib.php"); logOutUser(); ?>
<?php include_once 'appginilte/alte.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $appName ? $appName : APP_TITLE; ?> | <?php echo $translate['session_expired']; ?></title>
    <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="appginilte/images/<?php echo $appIcon; ?>">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="appginilte/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="appginilte/dist/css/adminlte.min.css">
</head>
<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    <a href="index.php"><b><?php echo $appName ? $appName : APP_TITLE; ?></b></a>
  </div>
  <!-- User name -->
  <div class="lockscreen-name"><h3><?php echo $translate['your_session_expired']; ?></h3></div>
    <div class="text-center"><img src="appginilte/images/expired.gif" alt="expired" style="width: 100%;"></div>
  <!-- START LOCK SCREEN ITEM -->
  <div class="help-block text-center">
    <h4><?php echo $translate['click_below_to_login']; ?></h4>
  </div>
  <div class="text-center">
    <a href="index.php?signOut=1"><b><?php echo $translate['retrieve_session']; ?></b></a>
  </div>
  <div class="lockscreen-footer text-center">
  <?php echo $translate['Copyright']; ?> &copy; <?php echo date('Y'); ?> <b><a href="#0" class="text-black"><?php echo $appName ? $appName : APP_TITLE; ?></a></b><br>
  <?php echo $translate['All rights reserved.']; ?>
  </div>
</div>
<!-- /.center -->

<!-- jQuery -->
<script src="appginilte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="appginilte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
