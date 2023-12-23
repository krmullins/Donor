<?php include_once 'appginilte/alte.php'; ?>
<!DOCTYPE html>
<html lang="en" <?php if ($enableRTL == 1) {
  echo 'dir="rtl"';
} ?>>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    <?php echo $appName ? $appName : APP_TITLE; ?> |
    <?php echo $translate['Login']; ?>
  </title>
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="appginilte/images/<?php echo $appIcon; ?>">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="appginilte/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="appginilte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <?php if ($enableRTL == 1) {
    echo '<link rel="stylesheet" href="appginilte/dist/css/adminlte.min.rtl.css">';
  } else {
    echo '<link rel="stylesheet" href="appginilte/dist/css/adminlte.min.css">';
  } ?>
  <?php
  //check if file exists: appginilte-custom-header.php and include it
  if (file_exists('appginilte/appginilte-custom-header.php')) {
    include 'appginilte/appginilte-custom-header.php';
  }
  //get login options
  $login_card_color = (empty(get_option('login_card_color')))? 'primary': get_option('login_card_color');
  $login_card_outline = (get_option('login_card_outline') == 'yes') ? 'card-outline' : '';
  $login_background_image = get_option('login_background_image');
  ?>
</head>

<body class="hold-transition login-page" <?php if (!empty($login_background_image)) {
  echo 'style="background-image: url(' . $login_background_image . ');background-size: cover"';
} ?>>
  <?php if (!isset($Translation)) {
    @header('Location: index.php?signIn=1');
    exit;
  } ?>
  <?php if (MULTI_TENANTS)
    redirect(SaaS::loginUrl(), true); ?>
  <?php if (Request::val('loginFailed')) { ?>
    <div class="alert alert-danger">
      <?php echo $Translation['login failed']; ?>
    </div>
  <?php } ?>
  <?php
  // reCaptcha config
  $reCaptchaData = get_option('reCaptcha_config');

  // invalidate login if reCAPTCHA is invalid
  if (
    Request::val('signIn')
    && Request::val('username')
    && Request::val('password')
    && !validRecaptcha()
  ) {
    unset($_REQUEST['username']);
    unset($_REQUEST['password']);
    unset($_REQUEST['signIn']);
    redirect('index.php?loginFailed=1');
  }

  /**
   * Summary of validRecaptcha
   * @return bool
   */
  function validRecaptcha()
  {
    global $reCaptchaData;
    // get reCAPTCHA response from login form
    $reCaptchaResponse = Request::val('g-recaptcha-response');
    if (!$reCaptchaResponse)
      return false;

    if (!function_exists('curl_init'))
      return false;

    // send a POST request to Google's reCAPTCHA validation API endpoint
    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => http_build_query([
        'secret' => $reCaptchaData['RECAPTCHA_SECRET_KEY'],
        'response' => $reCaptchaResponse,
      ]),
      CURLOPT_RETURNTRANSFER => true,
    ]);

    $googleRespJson = curl_exec($ch);
    curl_close($ch);

    // if error response, abort
    if ($googleRespJson === false)
      return false;
    $googleResp = @json_decode($googleRespJson, true);
    if ($googleResp === null)
      return false;

    return !empty($googleResp['success']);
  }
  ?>
  <?php
  if (!empty($_GET['error'])) {
    # code...
    $error = $_GET['error'];
    if ($error == 'nouser') {
      echo '<div class="alert alert-danger">' . $translate['No user found on our database with the email provided.'] . '</div>';
    } elseif ($error == 'dupusers') {
      echo '<div class="alert alert-danger">' . $translate['More than one user found with the provided email. Please use the ordinary login method.'] . '</div>';
    } elseif ($error == 'notapproved') {
      echo '<div class="alert alert-danger">' . $translate['The user is not approved or banned from the system.Kindly contact admin for asistance.'] . '</div>';
    }
  }
  ?>
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-<?= $login_card_color; ?> <?= $login_card_outline; ?>">
      <div class="card-header text-center">
        <a href="" class="h4"><b>
            <?php echo $appName ? $appName : APP_TITLE; ?>
          </b>
          <?php echo $translate['Login']; ?>
        </a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">
          <?php echo $translate['Sign in to start your session']; ?>
        </p>

        <form method="post" action="">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="<?php echo $translate['Username']; ?>" name="username"
              id="username" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="<?php echo $translate['Password']; ?>"
              name="password" id="password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="far fa-eye-slash" id="togglePassword"></span>
              </div>
            </div>
          </div>
          <div class="row reCaptcha">
            <?php
            $enablereCaptcha = $reCaptchaData['enablereCaptcha'] ? $reCaptchaData['enablereCaptcha'] : '';
            if ($enablereCaptcha == 1) {
              include 'appginilte_reCaptcha.php';
            } ?>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-<?= $login_card_color; ?>">
                <input type="checkbox" id="remember" name="rememberMe" value="1">
                <label for="remember">
                  <?php echo $translate['Remember Me']; ?>
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-<?= $login_card_color; ?> btn-block" name="signIn" id="submit"
                value="signIn"><span class="fas fa-sign-in-alt"></span>
                <?php echo $translate['Sign In']; ?>
              </button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <!--social-auth-links -->
        <?php
        $GoogleOauthData = get_option('GoogleOAuth');
        $MicrosoftOauthData = get_option('MicrosoftOAuth');
        $TwitterOauthData = get_option('TwitterOAuth');
        $enablemauth = $MicrosoftOauthData['enablemauth'] ? $MicrosoftOauthData['enablemauth'] : 0;
        $enablegauth = $GoogleOauthData['enablegauth'] ? $GoogleOauthData['enablegauth'] : 0;
        $enabletauth = $TwitterOauthData['enabletauth'] ? $TwitterOauthData['enabletauth'] : 0;
        if ($enablemauth == 1 || $enablegauth == 1 || $enabletauth == 1 || !empty(get_option('CustomLoginMethods'))) { ?>
          <p class="text-center">-
            <?php echo $translate['OR']; ?>-
          </p>
          <div class="social-auth-links text-center mt-2 mb-3">
            <?php if ($enablegauth == 1) { ?>
              <a href="appginilte_google.php" class="btn btn-block btn-outline-danger btn-sm">
                <i class="fab fa-google mr-2"></i>
                <?php echo $translate['Sign in using Google']; ?>
              </a>
            <?php }
            if ($enablemauth == 1) { ?>
              <a href="appginilte_microsoft.php?action=login" class="btn btn-block btn-outline-primary btn-sm">
                <i class="fab fa-microsoft mr-2"></i>
                <?php echo $translate['Sign in using Microsoft']; ?>
              </a>
            <?php }
            if ($enabletauth == 1) { ?>
              <a href="appginilte_twitter.php?action=login" class="btn btn-block btn-outline-info btn-sm">
                <i class="fab fa-twitter mr-2"></i>
                <?php echo $translate['Sign in using Twitter']; ?>
              </a>
            <?php }
            if (!empty(get_option('CustomLoginMethods'))) {
              $decode_contents = get_option('CustomLoginMethods');
              foreach ($decode_contents as $loginmethod) {
                $enablemethod = $loginmethod['enablemethod'];
                $btntext = $loginmethod['btntext'];
                $btnvariation = $loginmethod['btnvariation'];
                $btnicon = $loginmethod['btnicon'];
                $btnlink = $loginmethod['btnlink'];
                if ($enablemethod == "Yes") {
                  echo ' <a href="' . $btnlink . '" class="' . $btnvariation . '">
                    <i class="' . $btnicon . ' mr-2"></i>
                    ' . $btntext . '
                  </a>';
                }
              }
            }
            ?>
          </div>
        <?php } ?>
        <!-- /.social-auth-links -->
        <p class="mb-1">
          <a href="appginilte_passwordreset.php">
            <?php echo $translate['Forgot Password?']; ?>
          </a>
        </p>
        <?php if (sqlValue("SELECT COUNT(1) from `membership_groups` WHERE `allowSignup`=1")) { ?>
          <p class="mb-0">
            <a href="appginilte_register.php" class="text-center"><strong>
                <?php echo $translate['Register a new membership']; ?>
              </strong></a>
          </p>
        <?php } ?>
      </div>
      <!-- /.card-body -->
      <?php if (is_array(getTableList()) && count(getTableList())) { /* if anon. users can see any tables ... */?>
        <div class="card-footer">
          <a href="index.php"><i class="glyphicon glyphicon-user text-muted"></i>
            <?php echo $Translation['continue browsing as guest']; ?>
          </a>
        </div>
      <?php } ?>
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->
  <script>
    document.getElementById('username').focus();
  </script>
  <script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    togglePassword.addEventListener("click", function () {
      // toggle the type attribute
      const type = password.getAttribute("type") === "password" ? "text" : "password";
      password.setAttribute("type", type);
      // toggle the icon
      togglePassword.classList.toggle("fa-eye");
      togglePassword.classList.toggle("fa-eye-slash");
    });
  </script>
  <!-- jQuery -->
  <script src="appginilte/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="appginilte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="appginilte/dist/js/adminlte.min.js"></script>
  <?php
  //check if file exists: appginilte-custom-footer.php and include it
  if (file_exists('appginilte/appginilte-custom-footer.php')) {
    include 'appginilte/appginilte-custom-footer.php';
  }
  ?>
</body>

</html>