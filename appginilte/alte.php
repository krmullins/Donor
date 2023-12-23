<?php
define("PREPEND_PATH", ""); $hooks_dir = dirname(__FILE__); include("defaultLang.php"); include("language.php"); include("lib.php");
$generalsettingsData=get_option('generalsettings_config');
$appName=$generalsettingsData['appName']?$generalsettingsData['appName']:'APP NAME';
$footerText=$generalsettingsData['footerText']?$generalsettingsData['footerText']:'&copy; '.date('Y').' AppGiniLTE';
$appIcon=$generalsettingsData['appIcon']?$generalsettingsData['appIcon']:'../../appginilte/dist/img/AdminLTELogo.png';
$sessionExpiry=$generalsettingsData['sessionExpiry']?$generalsettingsData['sessionExpiry']:60;
$enableRTL=$generalsettingsData['enableRTL']?$generalsettingsData['enableRTL']:0;
require ('appginilte_language.php');
require ('appginilte_defaultLang.php');
//array merge translateEN and translate
$translate=array_merge($translateEN, $translate);

/**
 * Summary of strong_password
 * @param mixed $pass
 * @return bool
 */
function strong_password($pass) { if (strlen($pass) < 8) return false; $no_shows = 0; $upper_case = preg_match_all('/[A-Z]{1}/', $pass); if (!$upper_case) $no_shows++; $lower_case = preg_match_all('/[a-z]{1}/', $pass); if (!$lower_case) $no_shows++; $numbers = preg_match_all('/[0-9]{1}/', $pass); if (!$numbers) $no_shows++; $special = preg_match_all('/[^0-9a-zA-Z]{1}/', $pass); if (!$special) $no_shows++; if ($no_shows > 1) return false; return true; }

/**
 * Summary of get_option
 * @param mixed $name
 * @return mixed
 */
function get_option($name) { $sql = "SELECT option_value FROM alte_options WHERE option_name='$name'"; $result = json_decode(sqlValue($sql), true); return (json_last_error() === JSON_ERROR_NONE) ? $result : sqlValue($sql); }
?>