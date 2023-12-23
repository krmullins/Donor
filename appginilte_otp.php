<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
include 'appginilte/appginilte.php';
$userName=makeSafe(strtolower($_GET['u']));
if(isset($userName)){
    $email=sqlValue("SELECT email FROM membership_users WHERE lcase(memberID)='{$userName}'");
}
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
            color: red;
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
            background-color: red;
            border: 1px solid red;
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
				<h6><?php echo $translate['enter_otp']; ?></h6>
				<div> <span><?php echo $translate['otp_sent']; ?> </span> <small><?php echo obfuscate_email($email); ?></small> </div>
				<form method="POST" action="">
					<div id="otp" class="inputs d-flex flex-row justify-content-center mt-2"> 
						<input class="m-2 text-center form-control rounded" type="number" id="first" name="first" maxlength="1" required="yes" autofocus /> 
						<input class="m-2 text-center form-control rounded" type="number" id="second" name="second" maxlength="1" required="yes" /> 
						<input class="m-2 text-center form-control rounded" type="number" id="third" name="third" maxlength="1" required="yes" /> 
						<input class="m-2 text-center form-control rounded" type="number" id="fourth" name="fourth" maxlength="1" required="yes" /> 
						<input class="m-2 text-center form-control rounded" type="number" id="fifth" name="fifth" maxlength="1" required="yes" /> 
						<input class="m-2 text-center form-control rounded" type="number" id="sixth" name="sixth" maxlength="1" required="yes" /> 
					</div>
					<div class="mt-4"> <button class="btn btn-danger px-4 validate" type="submit" id="submit" name="submit"><?php echo $translate['Validate']; ?></button> </div>
				</form>
			</div>
			<div class="card-2">
				<div class="content d-flex justify-content-center align-items-center"> <span><?php echo $translate['dint_receive_otp']; ?> &nbsp;</span> <a href="?user=<?php echo $username ?>&resend=1" class="text-decoration-none ms-3"><?php echo $translate['resend_otp']; ?></a> &nbsp;| &nbsp;<a href="index.php?signIn=1"  class="text-decoration-none ms-3"><?php echo $translate['go to login']; ?></a></div>
			</div>
		</div>
	</div>
    </div>
    <!-- /.center -->
    <?php 
if (isset($_POST['submit'])) {
	// code...
	$one=$_POST['first'];
	$two=$_POST['second'];
	$three=$_POST['third'];
	$four=$_POST['fourth'];
	$five=$_POST['fifth'];
	$six=$_POST['sixth'];
	$otp=(int)($one.$two.$three.$four.$five.$six);
	$otp_expiry=strtotime($_SESSION['otp_expiry']);
	$currTime=strtotime(date('Y-m-d H:i:s'));
    $gendotp=(int)$_SESSION['otp'];
    $arrayotp=[$otp,$gendotp];
	if ($arrayotp[0]!==$arrayotp[1]) {
		// code...
		 echo '<script type="text/javascript">swal("'.$translate['Ooops!'].'", "'.$translate['invalid totp'].'", "error");</script>';
	}
	elseif ($currTime>$otp_expiry) {
		// code...
		echo '<script type="text/javascript">swal("'.$translate['Ooops!'].'", "'.$translate['otp_expired'].'", "error");</script>';
	}
	else{
		$_SESSION['otp_expiry']=0;
        $_SESSION['otp']=0;
        $_SESSION['otp_required']=0;
		echo '<script type="text/javascript">swal("'.$translate['Congrats!'].'", "'.$translate['totp_success'].'", "success");</script>';
		echo "<meta http-equiv='refresh' content='2;url=index.php' />";
	}

}

if (isset($_GET['resend']) && !isset($_POST['submit'])) {
	// code...
	$otp=mt_rand(123456,789101);
	$minutes_to_add = 10;
	$time = new DateTime(date('Y-m-d H:i:s'));
	$time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
	$stamp = $time->format('Y-m-d H:i:s');
	$email=sqlValue("SELECT email FROM membership_users WHERE memberID='$username'");
    $_SESSION['otp']=$otp;
    $_SESSION['otp_expiry']=$stamp;
    $_SESSION['otp_required']=1;
	sendemail($email,"Please Verify It Is You","Dear $username, If you are attempting to sign-in, please use the following code to confirm your identity: <b>$otp</b> <br> This code expires in 10 minutes. If you are not the one attempting to login to your account please ignore this.");
	echo '<script type="text/javascript">swal("Congrats!", "An OTP has been sent to your registered email '.obfuscate_email($email).' ", "success");</script>';
}

 ?>
    <!-- jQuery -->
    <script src="appginilte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="appginilte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>