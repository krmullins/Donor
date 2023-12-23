<?php
include_once 'appginilte/alte.php';
	include_once(__DIR__ . '/lib.php');
	if(Request::val('redir') == 1) {
		echo '<META HTTP-EQUIV="Refresh" CONTENT="5;url=index.php?signIn=1">';
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $translate['Sign Up Success']; ?></title>
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="appginilte/images/<?php echo $appIcon; ?>">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="appginilte/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="appginilte/dist/css/adminlte.min.css">
</head>
<body class="hold-transition lockscreen" style="background-color:white">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    <a href="index.php"><?php echo $translate['thank_you_for_registering']; ?></a>
    <h1><img src="appginilte/images/thankyou.gif" alt="thankyou"></h1>
  </div>
  <!-- START LOCK SCREEN ITEM -->
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
 <?php echo $translate['sign_up_instructions']; ?>
  </div>
</div>
<!-- /.center -->

<!-- jQuery -->
<script src="appginilte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="appginilte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
