<?php include_once 'appginilte/alte.php'; ?>
<?php
include_once(__DIR__ . '/lib.php');
if (MULTI_TENANTS)
    redirect(SaaS::signupUrl(), true);

$adminConfig = config('adminConfig');
$app_name = $appName;

if (!$cg = sqlValue("select count(1) from membership_groups where allowSignup=1")) {
    $noSignup = true;
    echo error_message($Translation['sign up disabled']);
    exit;
}

if (Request::val('signUp')) {
    // receive data
    $memberID = is_allowed_username(Request::val('newUsername'));
    $email = isEmail(Request::val('email'));
    $password = Request::val('password');
    $confirmPassword = Request::val('confirmPassword');
    $groupID = intval(Request::val('groupID'));
    $custom1 = makeSafe(Request::val('custom1'));
    $custom2 = makeSafe(Request::val('custom2'));
    $custom3 = makeSafe(Request::val('custom3'));
    $custom4 = makeSafe(Request::val('custom4'));

    // validate data
    if (!$memberID) {
        echo error_message($Translation['username invalid']);
        exit;
    }
    if (!strong_password($password) || trim($password) != $password) {
        echo error_message($translate['weak_password']);
    }
    if ($password != $confirmPassword) {
        echo error_message($Translation['password no match']);
        exit;
    }
    if (!$email) {
        echo error_message($Translation['email invalid']);
        exit;
    }
    if (!sqlValue("select count(1) from membership_groups where groupID='$groupID' and allowSignup=1")) {
        echo error_message($Translation['group invalid']);
        exit;
    }

    // save member data
    $needsApproval = sqlValue("select needsApproval from membership_groups where groupID='$groupID'");
    sql("INSERT INTO `membership_users` set memberID='{$memberID}', passMD5='" . password_hash($password, PASSWORD_DEFAULT) . "', email='{$email}', signupDate='" . @date('Y-m-d') . "', groupID='{$groupID}', isBanned='0', isApproved='" . ($needsApproval == 1 ? '0' : '1') . "', custom1='{$custom1}', custom2='{$custom2}', custom3='{$custom3}', custom4='{$custom4}', comments='member signed up through the registration form.'", $eo);

    // admin mail notification
    /* ---- application name as provided in AppGini is used here ---- */
    $message = nl2br(
        "A new member has signed up for {$app_name}.\n\n" .
        "Member name: {$memberID}\n" .
        "Member group: " . sqlValue("select name from membership_groups where groupID='{$groupID}'") . "\n" .
        "Member email: {$email}\n" .
        "IP address: {$_SERVER['REMOTE_ADDR']}\n" .
        "Custom fields:\n" .
        ($adminConfig['custom1'] ? "{$adminConfig['custom1']}: {$custom1}\n" : '') .
        ($adminConfig['custom2'] ? "{$adminConfig['custom2']}: {$custom2}\n" : '') .
        ($adminConfig['custom3'] ? "{$adminConfig['custom3']}: {$custom3}\n" : '') .
        ($adminConfig['custom4'] ? "{$adminConfig['custom4']}: {$custom4}\n" : '')
    );

    if ($adminConfig['notifyAdminNewMembers'] == 2 && !$needsApproval)
        sendmail([
            'to' => $adminConfig['senderEmail'],
            'subject' => "[{$app_name}] New member signup",
            'message' => $message,
        ]);
    elseif ($adminConfig['notifyAdminNewMembers'] >= 1 && $needsApproval)
        sendmail([
            'to' => $adminConfig['senderEmail'],
            'subject' => "[{$app_name}] New member awaiting approval",
            'message' => $message,
        ]);

    // hook: member_activity
    if (function_exists('member_activity')) {
        $args = [];
        member_activity(getMemberInfo($memberID), ($needsApproval ? 'pending' : 'automatic'), $args);
    }

    // redirect to thanks page
    $redirect = ($needsApproval ? '' : '?redir=1');
    redirect("appginilte_thankyou.php$redirect");

    exit;
}

// drop-down of groups allowing self-signup
$groupsDropDown = preg_replace('/<option.*?value="".*?><\/option>/i', '', htmlSQLSelect('groupID', "select groupID, concat(name, if(needsApproval=1, ' *', ' ')) from membership_groups where allowSignup=1 order by name", ($cg == 1 ? sqlValue("select groupID from membership_groups where allowSignup=1 order by name limit 1") : 0)));
$groupsDropDown = str_replace('<select ', '<select class="form-control" ', $groupsDropDown);
?>
<!DOCTYPE html>
<html lang="en" <?php if ($enableRTL == 1) {
    echo 'dir="rtl"';
} ?>>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php echo $appName ? $appName : APP_TITLE; ?> |
        <?php echo $translate['Registration']; ?>
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
    //get register options
    $register_card_color = (empty(get_option('register_card_color')))? 'primary': get_option('register_card_color');
    $register_card_outline = (get_option('register_card_outline') == 'yes') ? 'card-outline' : '';
    $register_background_image = get_option('register_background_image') ?? '';
    ?>
</head>

<body class="hold-transition register-page" <?php if (!empty($register_background_image)) {
    echo 'style="background-image: url(' . $register_background_image . ');background-size: cover"';
} ?>>
    <?php if (!$noSignup) { ?>
        <div class="register-box">
            <div class="card <?= $register_card_outline; ?> card-<?= $register_card_color; ?>">
                <div class="card-header text-center">
                    <a href="index.php" class="h4"><b>
                            <?php echo $appName ? $appName : APP_TITLE; ?>
                        </b>
                        <?php echo $translate['Sign Up']; ?>
                    </a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">
                        <?php echo $translate['Register a new membership']; ?>
                    </p>

                    <form action="" method="post">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="<?php echo $translate['Username']; ?>"
                                id="username" name="newUsername" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="<?php echo $translate['Email']; ?>"
                                id="email" name="email" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="<?php echo $translate['Password']; ?>"
                                id="password" name="password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control"
                                placeholder="<?php echo $translate['Retype password']; ?>" id="confirmPassword"
                                name="confirmPassword">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="form-group">
                                <label for="group" class="control-label">
                                    <?php echo $Translation['group']; ?>
                                </label>
                                <?php echo $groupsDropDown; ?>
                                <span class="help-block">
                                    <?php echo $Translation['groups *']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <?php
                            if (!$adminConfig['hide_custom_user_fields_during_signup']) {
                                for ($cf = 1; $cf <= 4; $cf++) {
                                    if ($adminConfig['custom' . $cf] != '') {
                                        ?>

                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control"
                                                placeholder="<?php echo html_attr($adminConfig['custom' . $cf]); ?>"
                                                id="custom<?php echo $cf; ?>" name="custom<?php echo $cf; ?>">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span class="fas fa-edit"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-<?= $register_card_color; ?>">
                                    <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                    <label for="agreeTerms">
                                        <?php echo $translate['I agree to the terms']; ?>
                                    </label>
                                </div>
                            </div>
                            <!-- /.col -->

                            <div class="col-4">
                                <button value="signUp" id="submit" type="submit"
                                    class="btn btn-<?= $register_card_color; ?> btn-block" name="signUp"><span
                                        class="fas fa-user-plus"></span>
                                    <?php echo $translate['Register']; ?>
                                </button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>


                    <a href="index.php" class="text-center">
                        <?php echo $translate['I already have a membership']; ?>
                    </a>
                </div>
                <!-- /.form-box -->
            </div><!-- /.card -->
        </div>
        <script src="appginilte/plugins/ajax/jquery.min.js"></script>
        <script>
            $(function () {
                $('#username').focus();

                $('#usernameAvailable, #usernameNotAvailable').click(function () {
                    $('#username').focus();
                });
                $('#username').on('keyup blur', checkUser);

                /* password strength feedback */
                $('#password').on('keyup blur', function () {
                    var ps = passwordStrength($('#password').val(), $('#username').val());

                    if (ps == 'strong') {
                        $('#password').parents('.form-group').removeClass('has-error has-warning').addClass('has-success');
                        $('#password').attr('title', '<?php echo html_attr($Translation['Password strength: strong']); ?>');
                    } else if (ps == 'good') {
                        $('#password').parents('.form-group').removeClass('has-success has-error').addClass('has-warning');
                        $('#password').attr('title', '<?php echo html_attr($Translation['Password strength: good']); ?>');
                    } else {
                        $('#password').parents('.form-group').removeClass('has-success has-warning').addClass('has-error');
                        $('#password').attr('title', '<?php echo html_attr($Translation['Password strength: weak']); ?>');
                    }
                });

                /* inline feedback of confirm password */
                $('#confirmPassword').on('keyup blur', function () {
                    if ($('#confirmPassword').val() != $('#password').val() || !$('#confirmPassword').val().length) {
                        $('#confirmPassword').parents('.form-group').removeClass('has-success').addClass('has-error');
                    } else {
                        $('#confirmPassword').parents('.form-group').removeClass('has-error').addClass('has-success');
                    }
                });

                /* inline feedback of email */
                $('#email').on('change', function () {
                    if (validateEmail($('#email').val())) {
                        $('#email').parents('.form-group').removeClass('has-error').addClass('has-success');
                    } else {
                        $('#email').parents('.form-group').removeClass('has-success').addClass('has-error');
                    }
                });

                /* validate form before submitting */
                $('#submit').click(function (e) {
                    if (!jsValidateSignup()) e.preventDefault();
                })
            });

            var uaro; // user availability request object
            function checkUser() {
                // abort previous request, if any
                if (uaro != undefined) uaro.abort();

                reset_username_status();

                uaro = $.ajax({
                    url: 'checkMemberID.php',
                    type: 'GET',
                    data: {
                        'memberID': $('#username').val()
                    },
                    success: function (resp) {
                        if (resp.indexOf('username-available') > -1) {
                            reset_username_status('success');
                        } else {
                            reset_username_status('error');
                        }
                    }
                });
            }

            function reset_username_status(status) {
                $('#usernameNotAvailable, #usernameAvailable')
                    .addClass('hidden')
                    .parents('.form-group')
                    .removeClass('has-error has-success');

                if (status == undefined) return;
                if (status == 'success') {
                    $('#usernameAvailable')
                        .removeClass('hidden')
                        .parents('.form-group')
                        .addClass('has-success');
                }
                if (status == 'error') {
                    $('#usernameNotAvailable')
                        .removeClass('hidden')
                        .parents('.form-group')
                        .addClass('has-error');
                }
            }

            /* validate data before submitting */
            function jsValidateSignup() {
                var p1 = $('#password').val();
                var p2 = $('#confirmPassword').val();
                var email = $('#email').val();

                /* user exists? */
                if (!$('#username').parents('.form-group').hasClass('has-success')) {
                    modal_window({
                        message: '<div class="alert alert-danger"><?php echo html_attr($Translation['username invalid']); ?></div>',
                        title: "<?php echo html_attr($Translation['error:']); ?>",
                        close: function () {
                            $('#username').focus();
                        }
                    });
                    return false;
                }

                /* passwords not matching? */
                if (p1 != p2) {
                    modal_window({
                        message: '<div class="alert alert-danger"><?php echo html_attr($Translation['password no match']); ?></div>',
                        title: "<?php echo html_attr($Translation['error:']); ?>",
                        close: function () {
                            $('#confirmPassword').focus();
                        }
                    });
                    return false;
                }

                if (!validateEmail(email)) {
                    modal_window({
                        message: '<div class="alert alert-danger"><?php echo html_attr($Translation['email invalid']); ?></div>',
                        title: "<?php echo html_attr($Translation['error:']); ?>",
                        close: function () {
                            $('#email').focus();
                        }
                    });
                    return false;
                }

                return true;
            }
        </script>

        <style>
            #usernameAvailable,
            #usernameNotAvailable {
                cursor: pointer;
            }
        </style>
    <?php } ?>
    <!-- /.register-box -->
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