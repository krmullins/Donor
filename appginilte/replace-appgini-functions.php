<?php 
$appginilte_dir = dirname(__FILE__);
include("{$appginilte_dir}/../defaultLang.php");
include("{$appginilte_dir}/../language.php");
include("{$appginilte_dir}/../lib.php");

// Step 1: Specify the file containing the function we want to overwrite
$appgini_file = "{$appginilte_dir}/../incCommon.php";

// Step 2: Specify the file containing our version of the function
$mod_file = "{$appginilte_dir}/mod.htmlUserBar.php";
$mod2_file= "{$appginilte_dir}/mod.showNotifications.php";

// Step 3: Specify the name of the function we want to overwrite
$func_name = 'htmlUserBar';
$func2_name='showNotifications';

//check if sweetalert is allowed
$installSWAL=($enablesweetalert==1) ? replace_function($appgini_file, $func2_name, $mod2_file) : '';
echo replace_function($appgini_file, $func_name, $mod_file) . '<br>' . replaceIndex() . createDashboardViews() . '<br>' . adminOTPcreate().'<br>'.installReportsMenu().'<br>'.$installSWAL.'<br>'.installInHeaderExtras().'<br>'.installInFooterExtras().'<br>'.installCustomPagesMenu().'<br>'.createFiles().'<br>'.createOptionsTable().'<br>'.modifyAdminIncHeader();

#######################################

function replace_function($appgini_file, $function_name, $mod_file)
{
	// read the new code from the mod file
	$new_code = @file($mod_file);
	if (empty($new_code)) return 'No custom code found.';

	// remove the first line containing PHP opening tag and keep the rest as $new_snippet
	array_shift($new_code);
	$new_snippet = implode('', $new_code);

	$pattern1 = '/\s*function\s+' . $function_name . '\s*\(.*\).*(\R.*){200}/';
	$pattern2 = '/\t#+(.*\R)*/';

	$entire_code = file_get_contents($appgini_file);
	if (!$entire_code) return 'Invalid AppGini file.';

	$m = [];
	if (!preg_match_all($pattern1, $entire_code, $m)) return 'Function to replace not found.';
	$snippet = $m[0][0] . "\n";

	if (!preg_match_all($pattern2, $snippet, $m)) return 'Could not find the end of the function: '.$function_name;
	$snippet = str_replace($m[0][0], '', $snippet);

	$snippet_nocrlf = str_replace("\r\n", "\n", $snippet);
	$new_snippet_nocrlf = str_replace("\r\n", "\n", $new_snippet);
	if (trim($snippet_nocrlf) == trim($new_snippet_nocrlf)) return $function_name.' Function already replaced.';

	//Backup: if you wish to get a backup regenerate your appgini file

	$new_code = str_replace(trim($snippet), trim($new_snippet), $entire_code);
	if (!@file_put_contents($appgini_file, $new_code)) return "Couldn't overwrite file.";

	return $function_name.' Function overwritten successfully.';
}

function replaceIndex()
{
	global $appginilte_dir;
	$index_file = "{$appginilte_dir}/../index.php";
	//read the entire file
	$getfile = file_get_contents($index_file);

	//replace home.php in the file with appginilte_dashboard.php
	$putfile = (checkIfCodeIsInserted($index_file, 'appginilte_dashboard.php')) ? $getfile : str_replace('home.php', 'appginilte_dashboard.php', $getfile);
	//replace login.php in the file with appginilte_login.php
	$putfile = (checkIfCodeIsInserted($index_file, 'appginilte_login.php')) ? $putfile : str_replace('login.php', 'appginilte_login.php', $putfile);

	//write the entire file
	if (!@file_put_contents($index_file, $putfile)) return "Couldn't overwrite Index file.";

	return 'Index file overwritten successfully.';
}

function installInHeaderExtras()
{
	global $appginilte_dir;
	$header_extras_file = "{$appginilte_dir}/../hooks/header-extras.php";
	$codeInsert='<!-- TableActions Code inserted by AdminLTE Plugin For Appgini_v_05.20 -->
	<?php $tableName = $x->TableName; $groupName = getMemberInfo()[\'group\']; $getTableData = sqlValue("SELECT option_value FROM alte_options WHERE option_name=\'table_actions_{$tableName}\'"); if ($getTableData) { $decode = json_decode($getTableData, true); $tableactions = $decode[\'tableactions_\' . $tableName] ? $decode[\'tableactions_\' . $tableName] : 0; $access_to_action_view = explode(",", $decode[\'access_to_action_view_\' . $tableName]); $access_to_action_view_count = count($access_to_action_view); $access_to_action_view_string = ""; for ($i = 0; $i < $access_to_action_view_count; $i++) { $access_to_action_view_string .= "\'" . $access_to_action_view[$i] . "\',"; } $access_to_action_view_string = rtrim($access_to_action_view_string, ","); $access_to_action_view_string = "[" . $access_to_action_view_string . "]"; $access_to_action_edit = explode(",", $decode[\'access_to_action_edit_\'. $tableName]); $access_to_action_edit_count = count($access_to_action_edit); $access_to_action_edit_string = ""; for ($i = 0; $i < $access_to_action_edit_count; $i++) { $access_to_action_edit_string.= "\'". $access_to_action_edit[$i]. "\',"; } $access_to_action_edit_string = rtrim($access_to_action_edit_string, ","); $access_to_action_edit_string = "[". $access_to_action_edit_string. "]"; $access_to_action_delete = explode(",", $decode[\'access_to_action_delete_\'. $tableName]); $access_to_action_delete_count = count($access_to_action_delete); $access_to_action_delete_string = ""; for ($i = 0; $i < $access_to_action_delete_count; $i++) { $access_to_action_delete_string.= "\'". $access_to_action_delete[$i]. "\',"; } $access_to_action_delete_string = rtrim($access_to_action_delete_string, ","); $access_to_action_delete_string = "[". $access_to_action_delete_string. "]"; echo "<script> var access_to_action_view=" . $access_to_action_view_string . "; var access_to_action_edit=". $access_to_action_edit_string. "; var access_to_action_delete=". $access_to_action_delete_string. "; var TAenabled=" . $tableactions . "; var tableName = \'" . $tableName . "\'; var groupName= \'". $groupName. "\'; </script>"; } else { echo "<script> var access_to_action_view=[]; var access_to_action_edit=[]; var access_to_action_delete=[]; var TAenabled=0; var tableName = \'" . $tableName . "\'; var groupName= \'". $groupName. "\'; </script>"; } ?>
	<!-- TableActions Code inserted by AdminLTE Plugin For Appgini_v_05.20-->';
	$detailViewCodeInsert='<!-- DetailView Code Inserted By AdminLTE Plugin For Appgini -->
	<?php $tableName = $x->TableName; $group = getMemberInfo()[\'group\']; $formatgroup = str_replace(\' \', \'_\', $group); $perms = file_get_contents(\'appginilte/group_perms/\' . $formatgroup . \'_\' . $tableName . \'-dv.json\'); if ($perms) { $extract = json_decode($perms, true); $readonly_fields = explode(",", $extract[\'readonlyfields_\' . $tableName]); $readonly_fields_count = count($readonly_fields); $readonly_fields_string = ""; for ($i = 0; $i < $readonly_fields_count; $i++) { $readonly_fields_string .= "\'" . $readonly_fields[$i] . "\',"; } $readonly_fields_string = rtrim($readonly_fields_string, ","); $readonly_fields_string = "[" . $readonly_fields_string . "]"; $hidden_fields = explode(",", $extract[\'hiddenfields_\' . $tableName]); $hidden_fields_count = count($hidden_fields); $hidden_fields_string = ""; for ($i = 0; $i < $hidden_fields_count; $i++) { $hidden_fields_string .= "\'" . $hidden_fields[$i] . "\',"; } $hidden_fields_string = rtrim($hidden_fields_string, ","); $hidden_fields_string = "[" . $hidden_fields_string . "]"; if(!empty($extract[\'readonlyfields_\' . $tableName])){ echo "<script>var readonly_fields = " . $readonly_fields_string . ";</script>"; } else{ echo "<script>var readonly_fields = [];</script>"; } if(!empty( $extract[\'hiddenfields_\' . $tableName])){ echo "<script>var hidden_fields = " . $hidden_fields_string . "; var tableName = \'" . $tableName . "\';</script>"; } else{ echo "<script>var hidden_fields = []; var tableName = \'" . $tableName . "\';</script>"; } } else { echo "<script>var readonly_fields=[]; var hidden_fields=[]; var tableName = \'" . $tableName . "\';</script>"; } ?>
	<!-- DetailView Code Inserted By AdminLTE Plugin For Appgini -->';
	$header_extras_file_contents=file_get_contents($header_extras_file);
	if (!strpos($header_extras_file_contents, "TableActions Code inserted by AdminLTE Plugin For Appgini_v_05.20")) {
		//string does not exist
		file_put_contents($header_extras_file, $codeInsert . "\n" . $header_extras_file_contents, LOCK_EX);
	}
	$header_extras_file_contents=file_get_contents($header_extras_file);
	if (!strpos($header_extras_file_contents, "DetailView Code Inserted By AdminLTE Plugin For Appgini")) {
		//string does not exist
		file_put_contents($header_extras_file, $detailViewCodeInsert . "\n" . $header_extras_file_contents, LOCK_EX);
	}
	return 'Inline edit setup success';
	
}

function installInFooterExtras(){
	global $appginilte_dir;
	$footer_extras_file = "{$appginilte_dir}/../hooks/footer-extras.php";
	$codeInsert='<!-- Inserted by AdminLTE Plugin For Appgini FE1 -->
	<script>
			jQuery(document).ready(function() {
				jQuery(\'a.children-count\').removeAttr(\'target\');
			});
	</script>
	<!-- Inserted by AdminLTE Plugin For Appgini FE1 -->';
	$codeInsert2='<!-- Inserted by AdminLTE Plugin For Appgini FE2 -->
	<script>
	document.addEventListener(\'DOMContentLoaded\', function() {
		const container = document.querySelector(\'.container\');
		if(container.classList.contains(\'users-area\')) {
			container.classList.remove(\'container\');
		}
	});
	</script>
	<!-- Inserted by AdminLTE Plugin For Appgini FE2 -->';
	$footer_extras_file_contents=file_get_contents($footer_extras_file);
	if (!strpos($footer_extras_file_contents, "Inserted by AdminLTE Plugin For Appgini FE1")) {
		//string does not exist
		file_put_contents($footer_extras_file, $codeInsert . "\n" . $footer_extras_file_contents, LOCK_EX);
	}
	if(!strpos($footer_extras_file_contents, "Inserted by AdminLTE Plugin For Appgini FE2")) {
		//string does not exist
		file_put_contents($footer_extras_file, $codeInsert2 . "\n" . $footer_extras_file_contents, LOCK_EX);
	}
	return 'Footer extras script installed';
}

function modifyAdminIncHeader(){
	global $appginilte_dir;
	$incHederFile="{$appginilte_dir}/../admin/incHeader.php";
	$codeInsert='<!-- INSERTED BY ADMINLTE FOR APGINI PLUGIN -->
	<style>
	.navbar  {
	  opacity: 0.9;
	  border-radius:8px;
  	}
	.navbar-brand .text-info{
		color: white !important;
	}
	.navbar-right{
		display: none;
	}
	</style>
	<!-- INSERTED BY ADMINLTE FOR APGINI PLUGIN -->';
	$codeInsert2='<!-- Inserted by AdminLTE Plugin For Appgini AIH1 -->
	<script>
	document.addEventListener(\'DOMContentLoaded\', function() {
		const container = document.querySelector(\'.container\');
		if(container.classList.contains(\'admin-area\')) {
			container.classList.remove(\'container\');
		}
	});
	</script>
	<!-- Inserted by AdminLTE Plugin For Appgini AIH1 -->';
	$incHederFileContents=file_get_contents($incHederFile);
	if (!strpos($incHederFileContents, "INSERTED BY ADMINLTE FOR APGINI PLUGIN")) {
		//string does not exist
		file_put_contents($incHederFile, $incHederFileContents."\n".$codeInsert, LOCK_EX);
	}
	if(!strpos($incHederFileContents, "Inserted by AdminLTE Plugin For Appgini AIH1")) {
		//string does not exist
		file_put_contents($incHederFile, $incHederFileContents."\n".$codeInsert2, LOCK_EX);
	}
	return 'Modify admin incHeader.php success';
}

function adminOTPcreate()
{
	global $appginilte_dir;
	//write to admin folder
	$appginilte_otp=fopen("{$appginilte_dir}/../admin/appginilte_otp.php", "w") or die("Unable to open file!");
	fwrite($appginilte_otp, file_get_contents("{$appginilte_dir}/appginilte_otpBack.php"));
	fclose($appginilte_otp);
	//write to hooks folder
	$appginilte_otp=fopen("{$appginilte_dir}/../hooks/appginilte_otp.php", "w") or die("Unable to open file!");
	fwrite($appginilte_otp, file_get_contents("{$appginilte_dir}/appginilte_hooks_otpBack.php"));
	fclose($appginilte_otp);
	//create include line
	$insertLine = '<?php include "appginilte_otp.php"; check_otp(); ?>';
	$insertHooksLine = '<?php include PREPEND_PATH."hooks/appginilte_otp.php"; check_otp(); ?>';
	$file = file_get_contents("{$appginilte_dir}/../admin/pageHome.php");
	if (!strpos($file, "appginilte_otp.php")) {
		//string does not exist
		file_put_contents("{$appginilte_dir}/../admin/pageHome.php", $insertLine . "\n" . file_get_contents("{$appginilte_dir}/../admin/pageHome.php"), LOCK_EX);
	}
	//insert code in hooks header-extras.php
	$file = file_get_contents("{$appginilte_dir}/../hooks/header-extras.php");
	if (!strpos($file, "appginilte_otp.php")) {
		//string does not exist
		file_put_contents("{$appginilte_dir}/../hooks/header-extras.php", $insertHooksLine . "\n" . file_get_contents("{$appginilte_dir}/../hooks/header-extras.php"), LOCK_EX);
	}
	return 'Appgini OTP setup success';
}

function installReportsMenu()
{
	global $appginilte_dir;
	$file=file_get_contents("{$appginilte_dir}/../hooks/links-navmenu.php");
	$navlinkscode='
	/*AdminLTE_For_Appgini_reports_links*/
	if(file_exists("appginilte/appginilte_reports/access_to_reports_menu.json")){
		$reportsgetAccess = file_get_contents("appginilte/appginilte_reports/access_to_reports_menu.json");
		$reportsaccess = json_decode( $reportsgetAccess, true );
		$reportsgroupAccess = !empty($reportsaccess[\'access_to_reports_menu\'])?$reportsaccess[\'access_to_reports_menu\']:[];
		if(!empty($reportsgroupAccess)){
			$navLinks[] = [
				\'url\'=>\'appginilte_reportslist.php\',
				\'title\'=>\'Reports\',
				\'groups\'=>str_replace("\'", "", explode(",", $reportsgroupAccess)),
				\'icon\'=>\'fas fa-chart-line\',
				\'icon_type\'=>\'fa\',
				\'table_group\'=>\'None\',
			];
		}
	}
	/*End_of_AdminLTE_For_Appgini_reports_links*/';
	if(!strpos($file, "AdminLTE_For_Appgini_reports_links")){
		file_put_contents("{$appginilte_dir}/../hooks/links-navmenu.php", $file."\n".$navlinkscode, LOCK_EX);
	}
	
	return 'reports menu setup success';
}

function installCustomPagesMenu(){
	global $appginilte_dir;
	$file=file_get_contents("{$appginilte_dir}/../hooks/links-navmenu.php");
	$navlinkscode='
	//AppginiLTECustomPagesNavLinks_23.05.26
	$generalsettingsData=json_decode(sqlValue("SELECT option_value FROM alte_options WHERE option_name=\'generalsettings_config\'"), true);
	$enablecustompages=$generalsettingsData[\'enablecustompages\']?$generalsettingsData[\'enablecustompages\']:0;
	$files = scandir(\'appginilte/custompages/pagesdata\');
	foreach ($files as $file) {
		if ($file != \'.\' && $file != \'..\' && $file != "index.html" && $file != " ") {
			$file_contents = file_get_contents(\'appginilte/custompages/pagesdata/\' . $file);
			$file_contents = json_decode($file_contents, true);
			//get comma separated values of pageAccess and convert to array
			$pageAccess = explode(\',\', $file_contents[\'pageAccess\']);
			if($enablecustompages==1){
				$navLinks[] = [
					\'url\'=>\'appginilte_page.php?page=\'.$file_contents[\'FileName\'],
					\'title\'=>$file_contents[\'pageName\'],
					\'groups\'=>$pageAccess,
					\'icon\'=>$file_contents[\'pageIcon\'],
					\'icon_type\'=>\'fa\',
					\'table_group\'=>$file_contents[\'pageGroup\'],
					\'CustomLink\'=>$file_contents[\'CustomLink\'],
					\'hidenavmenu\'=>$file_contents[\'hidenavmenu\'],
				];
			}
		}
	}
	//EndAppginiLTECustomPagesNavLinks_23.05.26';
	if(!strpos($file, "AppginiLTECustomPagesNavLinks_23.05.26")){
		file_put_contents("{$appginilte_dir}/../hooks/links-navmenu.php", $file."\n".$navlinkscode, LOCK_EX);
	}
	return 'custom pages menu setup success';
}

function createDashboardViews()
{
	global $appginilte_dir;
	$abovehomelinks_content = '';
	$belowhomelinks_content = '';
	$groups = sql("SELECT `name` FROM membership_groups", $eo);
	foreach ($groups as $grp => $data) {
		$gn = str_replace(" ", "_", $data['name']);
		$viewpage_Top = "{$appginilte_dir}/views/" . $gn . "_Top.php";
		$viewpage_Bottom = "{$appginilte_dir}/views/" . $gn . "_Bottom.php";
		if (!file_exists($viewpage_Top) || !file_exists($viewpage_Bottom)) {
			$contents = "\n" . '
			<div class="row">
			<div class="col-md-12">
			
			</div>
			</div>' . "\n";
			// initialize content for this file
			file_put_contents($viewpage_Top, '<!--Write your ' . $data['name'] . ' group specific dashboard content and logic in here, this could be charts,cards widgets,tables and everything in between. This content will be shown above home links/cards on the dashboard-->' . $contents); // Save our content to the file.
			file_put_contents($viewpage_Bottom, '<!--Write your ' . $data['name'] . ' group specific dashboard content and logic in here, this could be charts,cards widgets,tables and everything in between.This content will be shown below home links/cards on the dashboard-->' . $contents); // Save our content to the file.
			echo "<br> appginilte/" . $viewpage_Top . " Created Successfully <br>";
			echo "<br> appginilte/" . $viewpage_Bottom . " Created Successfully <br>";
		}
		$abovehomelinks_content .= "\n" . 'if ($group=="' . $data['name'] . '") {
			include "views/' . $gn . '_Top.php";
			}' . "\n";
		$belowhomelinks_content .= "\n" . 'if ($group=="' . $data['name'] . '") {
			include "views/' . $gn . '_Bottom.php";
		}' . "\n";
	}
	$abovehomelinks = "{$appginilte_dir}/above_homelinks.php";
	$belowhomelinks = "{$appginilte_dir}/below_homelinks.php";

	if (!@file_put_contents($abovehomelinks, "<?php " . $abovehomelinks_content . " ?>")) return "Couldn't overwrite Index file.";
	if (!@file_put_contents($belowhomelinks, "<?php " . $belowhomelinks_content . " ?>")) return "Couldn't overwrite Index file.";

	return '<br> Above/Below home page files created success';
}

function checkIfCodeIsInserted($file, $code)
{
	if (strpos(file_get_contents($file), $code)) return TRUE;
}

function createFiles(){
	global $appginilte_dir;
	$message='';
	$languagefile="{$appginilte_dir}/../appginilte_language.php";
	$backupFile="{$appginilte_dir}/languageBack.php";
	//universal files
	$unviversal_bottom="{$appginilte_dir}/views/universal_Bottom_homelinks.php";
	$unviversal_top="{$appginilte_dir}/views/universal_Top_homelinks.php";
	//api custom_functions.php
	$custom_functions="{$appginilte_dir}/api/custom_functions.php";
	//appginilte/appginilte-custom-header.php
	$custom_header = "{$appginilte_dir}/appginilte-custom-header.php";
	//appginilte/appginilte-custom-footer.php
	$custom_footer = "{$appginilte_dir}/appginilte-custom-footer.php";
	//if lanugagefile doesnt exist then create it by copying the backup file
	if(!file_exists($languagefile)){
		//create language file in the project root directory
		$dir = "{$appginilte_dir}/..";
		$file = "appginilte_language.php";
		// Create the file path
		$filepath = $dir . '/' . $file;
		// Create the file
		$handle = fopen($filepath, 'w') or die('Cannot open file:  '.$filepath);
		fwrite($handle, file_get_contents($backupFile));
		// Close the file
		fclose($handle);
		$message.= 'language file created'.'<br>';
	}
	//if universal bottom file doesnt exist then create it
	if(!file_exists($unviversal_bottom)){
		//create universal bottom file in the project root directory
		$dir = "{$appginilte_dir}/views";
		$file = "universal_Bottom_homelinks.php";
		// Create the file path
		$filepath = $dir . '/' . $file;
		// Create the file
		$handle = fopen($filepath, 'w') or die('Cannot open file:  '.$filepath);
		fwrite($handle, '<!-- Write code here that will be rendered for all user groups at the bottom of the dashboard -->');
		// Close the file
		fclose($handle);
		$message.= 'universal bottom file created'. '<br>';
	}
	//if universal top file doesnt exist then create it
	if(!file_exists($unviversal_top)){
		//create universal top file in the project root directory
		$dir = "{$appginilte_dir}/views";
		$file = "universal_Top_homelinks.php";
		// Create the file path
		$filepath = $dir . '/' . $file;
		// Create the file
		$handle = fopen($filepath, 'w') or die('Cannot open file:  '.$filepath);
		fwrite($handle, '<!-- Write code here that will be rendered for all user groups at the top of the dashboard -->');
		// Close the file
		fclose($handle);
		$message.= 'universal top file created'.'<br>';
	}
	//if custom_functions file doesnt exist then create it
	if(!file_exists($custom_functions)){
		//create custom_functions file in the project root directory
		$dir = "{$appginilte_dir}/api";
		$file = "custom_functions.php";
		// Create the file path
		$filepath = $dir . '/' . $file;
		// Create the file
		$handle = fopen($filepath, 'w') or die('Cannot open file:  '.$filepath);
		fwrite($handle, '<?php
		// Write you custom functions in here: 
		//You can call this functions using the api_functions API: provide your function_name and the parameters in the request body.
		// Example:
		// {
		//     "username": "admin",
		//     "access_token": "your_access_token",
		//     "function_name": "get_table_data",
		//     "parameters": ["table_name", "select_fields", "custom_where", "page_number", "results_per_page"]
		// }
		// When writing your functions ensure you return a value that can be converted to JSON. For example, an array or an object or string.
		
		function test_function($username="John Doe",$age="20"){
			return "Hello $username, you are $age years old";
		}');
		// Close the file
		fclose($handle);
		$message.= 'custom_functions file created'.'<br>';
	}

	//check for custom footer and header files exist and create them
	if(!file_exists($custom_header)){
		//create the file
		$dir="{$appginilte_dir}";
		$file= 'appginilte-custom-header.php';
		//crete it now
		// Create the file path
		$filepath = $dir . '/' . $file;
		// Create the file
		$handle = fopen($filepath, 'w') or die('Cannot open file:  '.$filepath);
		fwrite($handle, '<?php
		//add any custom code you need included the theme header');
		// Close the file
		fclose($handle);
		$message.= 'appginilte-custom-header.php file created'.'<br>';
	}
	if(!file_exists($custom_footer)){
		//create the file
		$dir="{$appginilte_dir}";
		$file= 'appginilte-custom-footer.php';
		//crete it now
		// Create the file path
		$filepath = $dir . '/' . $file;
		// Create the file
		$handle = fopen($filepath, 'w') or die('Cannot open file:  '.$filepath);
		fwrite($handle, '<?php
		//add any custom code you need included the theme footer');
		// Close the file
		fclose($handle);
		$message.= 'appginilte-custom-footer.php file created'.'<br>';
	}

	return $message;
}

function createOptionsTable(){
	$sql="CREATE TABLE IF NOT EXISTS alte_options (
		option_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		option_name VARCHAR(191) UNIQUE NOT NULL,
		option_value LONGTEXT NOT NULL,
		PRIMARY KEY (option_id)
	  );";
	  if(sql($sql,$eo)==TRUE){
		return 'Options table created';
	  }
}