<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
include 'appginilte/appginilte.php';
include_once 'appginilte/totp.php';
//if guest user, redirect to login page
if($_SESSION['memberID']=='guest'){
    header("Location: index.php");
    exit;
}
$usercrypt=$str = crc32($username);
//check if the file $usercrypt.json exists in appginilte/2fa folder
//if it does, then the user has already setup 2fa
//if it doesn't, then the user has not setup 2fa
//if the user has not setup 2fa, then the user is shwon the 2fa setup modal
//if the user has setup 2fa, then the user is redirected to the 2fa login page
if (file_exists('appginilte/2fa/' . $usercrypt . '.json')) {
    //get the init key from the json file
    $json = file_get_contents('appginilte/2fa/' . $usercrypt . '.json');
    $json_data = json_decode($json, true);
    $init_key = $json_data['init_key'];
    $tfa=1;
} else {
    //redirect to appginilte_setup_totp.php
    header("Location: appginilte_setup_totp.php");
}
$TimeStamp	  = Google2FA::get_timestamp();
$secretkey 	  = Google2FA::base32_decode($init_key);	// Decode it into binary
$current_totp       	  = Google2FA::oath_hotp($secretkey, $TimeStamp);	// Get current token
$qrlink="otpauth://totp/$appName($username)?secret=".$init_key;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $translate['Verify Account']; ?> </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="appginilte/images/<?php echo $appIcon; ?>">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="appginilte/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="appginilte/dist/css/adminlte.min.css">
    <style type="text/css">
        .height-100 {
            height: 100vh
        }

        .card {
            width: 400px;
            border: none;
            height: 400px;
            box-shadow: 0px 5px 20px 0px #d2dae3;
            z-index: 1;
            display: flex;
            justify-content: center;
            align-items: center
        }

        .card h6 {
            color: #049cdc;
            font-size: 20px
        }

        .inputs input {
            width: 40px;
            height: 40px
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0
        }

        .card-2 {
            background-color: #fff;
            padding: 10px;
            width: 350px;
            height: 100px;
            bottom: -50px;
            left: 20px;
            position: absolute;
            border-radius: 5px
        }

        .card-2 .content {
            margin-top: 50px
        }

        .card-2 .content a {
            color: red
        }

        .form-control:focus {
            box-shadow: none;
            border: 2px solid red
        }

        .validate {
            border-radius: 20px;
            height: 40px;
            background-color: #14b955;
            border: 1px solid #14b955;
            width: 140px
        }
    </style>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function(event) {

            function OTPInput() {
                const inputs = document.querySelectorAll('#otp > *[id]');
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].addEventListener('keydown', function(event) {
                        if (event.key === "Backspace") {
                            inputs[i].value = '';
                            if (i !== 0) inputs[i - 1].focus();
                        } else {
                            if (i === inputs.length - 1 && inputs[i].value !== '') {
                                return true;
                            } else if (event.keyCode > 47 && event.keyCode < 58) {
                                inputs[i].value = event.key;
                                if (i !== inputs.length - 1) inputs[i + 1].focus();
                                event.preventDefault();
                            } else if (event.keyCode > 64 && event.keyCode < 91) {
                                inputs[i].value = String.fromCharCode(event.keyCode);
                                if (i !== inputs.length - 1) inputs[i + 1].focus();
                                event.preventDefault();
                            }
                        }
                    });
                }
            }
            OTPInput();
        });
    </script>
    <!--CODE INSERTED FOR SWEET ALERT-->
    <script type="text/javascript" src="https://www.jquery-az.com/javascript/alert/dist/sweetalert-dev.js"></script>
    <link rel="stylesheet" type="text/css" href="https://www.jquery-az.com/javascript/alert/dist/sweetalert.css">
    <!--CODE INSERTED FOR SWEET ALERT-->
</head>

<body class="hold-transition lockscreen">
    <!-- Automatic element centering -->
    <div>
        <div class="container height-100 d-flex justify-content-center align-items-center">
            <div class="position-relative">
                <div class="card p-2 text-center">
                    <h6><?php echo $translate['totp_verification']; ?> </h6>
                    <div><small><b><?php echo $translate['totp_instructions']; ?></b></small> </div>
                    <form method="POST" action="">
                        <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2">
                            <input class="m-2 text-center form-control rounded" type="number" id="first" name="first" maxlength="1" required="yes" autofocus />
                            <input class="m-2 text-center form-control rounded" type="number" id="second" name="second" maxlength="1" required="yes" />
                            <input class="m-2 text-center form-control rounded" type="number" id="third" name="third" maxlength="1" required="yes" />
                            <input class="m-2 text-center form-control rounded" type="number" id="fourth" name="fourth" maxlength="1" required="yes" />
                            <input class="m-2 text-center form-control rounded" type="number" id="fifth" name="fifth" maxlength="1" required="yes" />
                            <input class="m-2 text-center form-control rounded" type="number" id="sixth" name="sixth" maxlength="1" required="yes" />
                        </div>
                        <div class="mt-4"> 
                            <button class="btn btn-success px-4 validate" type="submit" id="submit" name="submit"><?php echo $translate['Validate']; ?></button>
                            <p><small><?php echo $translate['totp_assistance']; ?></small> </p>
                         </div>
                    </form>
                </div>
                <div class="card-2">
                    <div class="content d-flex justify-content-center align-items-center"> 
                        <a href="index.php?signIn=1" class="text-decoration-none ms-3"><b><?php echo $translate['go to login']; ?></b></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.center -->
    <?php
    if (isset($_POST['submit'])) {
        // code...
        $one = $_POST['first'];
        $two = $_POST['second'];
        $three = $_POST['third'];
        $four = $_POST['fourth'];
        $five = $_POST['fifth'];
        $six = $_POST['sixth'];
        $totp = (int)($one . $two . $three . $four . $five . $six);
        $result = Google2FA::verify_key($init_key,$totp);
        if ($result==FALSE) {
            // code...
            echo '<script type="text/javascript">swal("'.$translate['Ooops!'].'", "'.$translate['invalid totp'].'", "error");</script>';
        } else {
            $_SESSION['totp_required'] = 0;
            echo '<script type="text/javascript">swal("'.$translate['Congrats!'].'", "'.$translate['totp_success'].'", "success");</script>';
            echo "<meta http-equiv='refresh' content='2;url=index.php' />";
        }
    }

    ?>
    <!-- jQuery -->
    <script src="appginilte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="appginilte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>