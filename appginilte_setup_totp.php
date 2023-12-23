<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
include 'appginilte/appginilte.php';
include_once 'appginilte/totp.php';
//if guest user, redirect to login page
if ($_SESSION['memberID'] == 'guest') {
    header("Location: index.php");
    exit;
}
$usercrypt = $str = crc32($username);
//check if the file $usercrypt.json exists in appginilte/2fa folder
//if it does, then the user has already setup 2fa
//if it doesn't, then the user has not setup 2fa
//if the user has not setup 2fa, then the user is shwon the 2fa setup modal
//if the user has setup 2fa, then the user is redirected to the 2fa login page
if (file_exists('appginilte/2fa/' . $usercrypt . '.json')) {
   //redirect to appginilte_totp.php
    header("Location: appginilte_totp.php");
} else {
    $init_key = Google2FA::generateInitializationKey(); // Set the inital key
    //save the init key to the json file
    $json_data = array(
        'init_key' => $init_key
    );
    $json = json_encode($json_data);
    file_put_contents('appginilte/2fa/' . $usercrypt . '.json', $json);
    $tfa = 0;
}
$TimeStamp      = Google2FA::get_timestamp();
$secretkey       = Google2FA::base32_decode($init_key);    // Decode it into binary
$current_totp             = Google2FA::oath_hotp($secretkey, $TimeStamp);    // Get current token
$qrlink = "otpauth://totp/$appName($username)?secret=" . $init_key;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $translate['Verify Account']; ?></title>
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
            height: 300px;
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

        /* THE MODAL */
        /* The Modal (background) */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            border: 1px solid #888;
            width: 80%;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            -webkit-animation-name: animatetop;
            -webkit-animation-duration: 0.4s;
            animation-name: animatetop;
            animation-duration: 0.4s
        }

        /* Add Animation */
        @-webkit-keyframes animatetop {
            from {
                top: -300px;
                opacity: 0
            }

            to {
                top: 0;
                opacity: 1
            }
        }

        @keyframes animatetop {
            from {
                top: -300px;
                opacity: 0
            }

            to {
                top: 0;
                opacity: 1
            }
        }

        /* The Close Button */
        .close {
            color: black;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-header {
            padding: 2px 16px;
            background-color: white;
            color: black;
        }

        .modal-body {
            padding: 2px 16px;
        }

        .modal-footer {
            padding: 2px 16px;
            background-color: white;
            color: black;
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
    <!-- /.center -->
    <!-- THE MODAL -->
    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <p>
                <h5><?php echo $translate['setup_totp']; ?></h5>
                </p>
                <p><strong> <?php echo $translate['How to setup using QR code']; ?></strong></p>
                <ul>
                    <li><?php echo $translate['download_authenticator']; ?></li>
                    <li><?php echo $translate['open_authenticator']; ?></li>
                    <li><?php echo $translate['scan_qr']; ?></li>
                    <?php echo '<br> <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $qrlink . '&choe=UTF-8">'; ?>
                    <li><?php echo $translate['enter_totp']; ?></li>
                </ul>
                <p><strong> <?php echo $translate['How to setup using setup key']; ?> </strong></p>
                <ul>
                    <li><?php echo $translate['download_authenticator']; ?></li>
                    <li><?php echo $translate['open_authenticator']; ?></li>
                    <li><?php echo $translate['Enter the setup key below:']; ?></li>
                    <strong><?php echo $init_key; ?></strong>
                    <li><?php echo $translate['Enter account name:']; ?> <b><?php echo $appName . "(" . $username . ")"; ?></b></li>
                    <li><?php echo $translate['enter_totp']; ?></li>
                </ul>
                <!-- Show button: verify code -->
                <a href="appginilte_totp.php" class="btn btn-success px-4 validate"><?php echo $translate['verify_code']; ?></a>
            </div>
            <div class="modal-footer"></div>
        </div>

    </div>
    <!-- THE MODAL -->
    <?php
    if ($tfa == 0) {
        echo '<script type="text/javascript"> var tfamodal = document.getElementById("myModal");  tfamodal.style.display = "block";</script>';
    }
    ?>
    <!-- jQuery -->
    <script src="appginilte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="appginilte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>