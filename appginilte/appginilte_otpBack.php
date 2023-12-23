<?php
include("../defaultLang.php");
include("../language.php");
include("../lib.php");
$username=$_SESSION['memberID'];
$memberGroupID=$_SESSION['memberGroupID'];
function check_otp()
{
    global $username,$memberGroupID;
    $group=sqlValue("SELECT name FROM membership_groups WHERE groupID='$memberGroupID'");
    $email=sqlValue("SELECT email FROM membership_users WHERE memberID='$username'");
	$concatGroupName = str_replace(' ', '_', $group);
	$lotpconfig = json_decode(sqlValue("SELECT option_value FROM alte_options WHERE option_name='lotp_config'"),true);
	$otpEnabled = $lotpconfig[$concatGroupName . '_enablelotp'] ? $lotpconfig[$concatGroupName . '_enablelotp'] : '0';
	if ($otpEnabled == 1 && !isset($_SESSION['otp'])) {
		$otp = mt_rand(123456, 789101);
		$minutes_to_add = 10;
		$time = new DateTime(date('Y-m-d H:i:s'));
		$time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
		$stamp = $time->format('Y-m-d H:i:s');
		$_SESSION['otp'] = $otp;
		$_SESSION['otp_expiry'] = $stamp;
		$_SESSION['otp_required'] = 1;
		sendemail($email, "Please Verify It Is You", "Dear $username, If you are attempting to sign-in, please use the following code to confirm your identity: <b>$otp</b> <br> This code expires in 10 minutes. If you are not the one attempting to login to your account please ignore this.");
		header("Location: ../appginilte_otp.php");
	}
	if ($_SESSION['otp_required'] == 1) {
		header("Location: ../appginilte_otp.php");
	}
	//do checks for totp
	$totpconfig = json_decode(sqlValue("SELECT option_value FROM alte_options WHERE option_name='totp_config'"),true);
	$totpEnabled = $totpconfig[$concatGroupName . '_enabletotp'] ? $totpconfig[$concatGroupName . '_enabletotp'] : '0';
	if ($totpEnabled == 1 && !isset($_SESSION['totp_required'])) {
		$_SESSION['totp_required'] = 1;
		header("Location: ../appginilte_totp.php");
	}
	if ($_SESSION['totp_required'] == 1) {
		header("Location: ../appginilte_totp.php");
	}
}
function sendemail($recipient, $subject, $body)
{
	$recipients = explode(",", $recipient);
	$sent = 0;
	foreach ($recipients as $recipient) {
		$mail = [
			"to" => $recipient,
			"message" => $body,
			"subject" => $subject,
			"tag" => []
		];
		$send = sendmail($mail);
		$sent = ($send == true) ? $sent + 1 : $sent;
	}
	if ($sent > 0) {
		return true;
	} else {
		return false;
	}
}