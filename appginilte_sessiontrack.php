<?php
session_start();
if($_SESSION['session_count'] == 0) {
$_SESSION['session_count'] = 1;
$_SESSION['session_start_time']=time();
} else {
$_SESSION['session_count'] = $_SESSION['session_count'] + 1;
}
include_once 'appginilte/alte.php';
$session_timeout = ($sessionExpiry*60); // enter number of seconds here for session to live (in sec) - 60 = 1 minute - 1800/60 = 30 min

$session_duration = time() - $_SESSION['session_start_time'];
if ($session_duration > $session_timeout) {
session_unset();
session_destroy();
session_start();
session_regenerate_id(true);
$_SESSION["expired"] = "yes";
header("Location: appginilte_lockscreen.php"); // Redirect to Login Page - index.php - or whatever you wish
} else {
$_SESSION['session_start_time']=time();
}
?>