<?php
@include("{$currDir}/hooks/links-home.php");
if (!defined('PREPEND_PATH'))
	define('PREPEND_PATH', '');
if (!defined('datalist_db_encoding'))
	define('datalist_db_encoding', 'UTF-8');
$translate = array();
include('appginilte_language.php');
require('appginilte_defaultLang.php');
//array merge translateEN and translate
$translate = array_merge($translateEN, $translate);
//data handler
include 'appginilte/datahandler.php';
$jdata = new jsondb();
#Variables that will be handy
$info = getMemberInfo();
$username = $info['username'];
$email = $info['email'];
$group = $info['group'];
$groupID = $info['groupID'];
$custom1 = $info['custom']['0'];
$custom2 = $info['custom']['1'];
$custom3 = $info['custom']['2'];
$custom4 = $info['custom']['3'];
// Get Site Address Dynamically
$site_addr = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["SCRIPT_NAME"]);
//Must end with /
$site_addr = rtrim($site_addr, "/\\") . "/";
// Can Be Set Manually Like "http://localhost/mysite/".
define("SITE_ADDR", $site_addr);
//check if we need to run updates
if (file_exists('appginilte/aru.bin')) {
	if ($group == "Admins") {
		echo '<script>var confirmed = confirm("Install Important Updates Now..."); /*Check if the user clicked "ok"*/ if (confirmed) { /*Redirect to another page*/ window.location.href = "appginilte_install.php?aru=1"; }</script>';
	}
}
include 'config.php';
$super_admin = $adminConfig['adminUsername'];
$isSuperAdmin = ($username == $super_admin) ? 'Yes' : 'No';
$env = $jdata->rDataBase('env');
$acesspermsData = get_option('accessperms_config');
$generalsettingsData = get_option('generalsettings_config');
$appName = $generalsettingsData['appName'] ? $generalsettingsData['appName'] : 'App';
$footerText = $generalsettingsData['footerText'] ? $generalsettingsData['footerText'] : '&copy; ' . date('Y') . ' AppGiniLTE';
$appIcon = $generalsettingsData['appIcon'] ? $generalsettingsData['appIcon'] : '../../appginilte/dist/img/AdminLTELogo.png';
$enablesweetalert = $generalsettingsData['enablesweetalert'] ? $generalsettingsData['enablesweetalert'] : 0;
$sessionExpiry = $generalsettingsData['sessionExpiry'] ? $generalsettingsData['sessionExpiry'] : 60;
$enablecustompages = $generalsettingsData['enablecustompages'] ? $generalsettingsData['enablecustompages'] : 0;
$dashboardWelcomeMessage = $generalsettingsData['dashboardWelcomeMessage'] ? $generalsettingsData['dashboardWelcomeMessage'] : '';
$siteLoader = $generalsettingsData['siteLoader'] ? $generalsettingsData['siteLoader'] : 'https://hackernoon.com/images/0*4Gzjgh9Y7Gu8KEtZ.gif';
$enablethemeswitcherAppgini = $generalsettingsData['enablethemeswitcherAppgini'] ? $generalsettingsData['enablethemeswitcherAppgini'] : 0;
$enableRTL = $generalsettingsData['enableRTL'] ? $generalsettingsData['enableRTL'] : 0;

if ($group == "Admins") {
	$adminltesettings = is_numeric($acesspermsData['adminltesettings']) ? $acesspermsData['adminltesettings'] : 0;
	$userareapems = is_numeric($acesspermsData['userareapems']) ? $acesspermsData['userareapems'] : 0;
	$dashbuilder = is_numeric($acesspermsData['dashbuilder']) ? $acesspermsData['dashbuilder'] : 0;
}
$globalsearchgroups = $acesspermsData['globalsearchgroups'] ? $acesspermsData['globalsearchgroups'] : ['Admins'];
$globalsearch = $acesspermsData['globalsearch'] ? $acesspermsData['globalsearch'] : 0;
//get app customizations settings
$customizations = get_option('appcustomizations');
$app_theme = $customizations['app_theme'] ? $customizations['app_theme'] : 'light-mode';
$fixed_header = $customizations['fixed_header'] ? $customizations['fixed_header'] : '';
$dropdown_legacy_offset = $customizations['dropdown_legacy_offset'] ? $customizations['dropdown_legacy_offset'] : '';
$no_border = $customizations['no_border'] ? $customizations['no_border'] : '';
$sidebar_collapsed = $customizations['sidebar_collapsed'] ? $customizations['sidebar_collapsed'] : '';
$sidebar_fixed = $customizations['sidebar_fixed'] ? $customizations['sidebar_fixed'] : '';
$sidebar_mini = $customizations['sidebar_mini'] ? $customizations['sidebar_mini'] : '';
$sidebar_mini_md = $customizations['sidebar_mini_md'] ? $customizations['sidebar_mini_md'] : '';
$sidebar_mini_xs = $customizations['sidebar_mini_xs'] ? $customizations['sidebar_mini_xs'] : '';
$sidebar_flat_style = $customizations['sidebar_flat_style'] ? $customizations['sidebar_flat_style'] : '';
$sidebar_legacy_style = $customizations['sidebar_legacy_style'] ? $customizations['sidebar_legacy_style'] : '';
$sidebar_compact_style = $customizations['sidebar_compact'] ? $customizations['sidebar_compact'] : '';
$sidebar_child_indent = $customizations['sidebar_child_indent'] ? $customizations['sidebar_child_indent'] : '';
$sidebar_child_hide_on_collapse = $customizations['sidebar_child_hide_on_collapse'] ? $customizations['sidebar_child_hide_on_collapse'] : '';
$sidebar_disable_hover = $customizations['sidebar_disable_hover'] ? $customizations['sidebar_disable_hover'] : '';
$footer_fixed = $customizations['footer_fixed'] ? $customizations['footer_fixed'] : '';
$text_sm_body = $customizations['text_sm_body'] ? $customizations['text_sm_body'] : '';
$text_sm_navbar = $customizations['text_sm_navbar'] ? $customizations['text_sm_navbar'] : '';
$text_sm_brand = $customizations['text_sm_brand'] ? $customizations['text_sm_brand'] : '';
$text_sm_sidebarnav = $customizations['text_sm_sidebarnav'] ? $customizations['text_sm_sidebarnav'] : '';
$text_sm_footer = $customizations['text_sm_footer'] ? $customizations['text_sm_footer'] : '';
$navbar_variant = $customizations['navbar_variant'] ? $customizations['navbar_variant'] : '';
$accent_color_variant = $customizations['accent_color_variant'] ? $customizations['accent_color_variant'] : '';
$dark_sidebar_variant = $customizations['dark_sidebar_variant'] ? $customizations['dark_sidebar_variant'] : '';
$light_sidebar_variant = $customizations['light_sidebar_variant'] ? $customizations['light_sidebar_variant'] : '';
$brand_logo_variant = $customizations['brand_logo_variant'] ? $customizations['brand_logo_variant'] : 'dark';
//check login otp
/**
 * Summary of check_otp
 * @return void
 */
function check_otp()
{
	global $username, $group, $email;
	$jdata = new jsondb();
	$concatGroupName = str_replace(' ', '_', $group);
	$lotpconfig = get_option('lotp_config');
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
		header("Location: appginilte_otp.php?u=$username");
	}
	if ($_SESSION['otp_required'] == 1) {
		header("Location: appginilte_otp.php?u=$username");
	}
	//do checks for totp
	$totpconfig = get_option('totp_config');
	$totpEnabled = $totpconfig[$concatGroupName . '_enabletotp'] ? $totpconfig[$concatGroupName . '_enabletotp'] : '0';
	if ($totpEnabled == 1 && !isset($_SESSION['totp_required'])) {
		$_SESSION['totp_required'] = 1;
		header("Location: appginilte_totp.php");
	}
	if ($_SESSION['totp_required'] == 1) {
		header("Location: appginilte_totp.php");
	}
}


/**
 * Summary of homeLinkInfoBox_1
 * @param mixed $title
 * @param mixed $count
 * @param mixed $link
 * @param mixed $icon
 * @param mixed $color
 * @param mixed $sizeclass
 * @param mixed $icontype
 * @param mixed $appginiIcon
 * @param mixed $dp
 * @return string
 */
function homeLinkInfoBox_1($title, $count, $link, $icon = "far fa-envelope", $color = "info", $sizeclass = "col-md-3 col-sm-6 col-12", $icontype = "default", $appginiIcon = '', $dp = 0)
{
	$show_icon = ($icontype == 'default') ? '<i> <img src="' . $appginiIcon . '"></i>' : '<i class="' . $icon . '"></i>';
	$html = '<div class="' . $sizeclass . '" onclick="window.location.href = \'' . $link . '\'">
	<div class="info-box">
	  <span class="info-box-icon bg-' . $color . '">' . $show_icon . '</span>
	  <div class="info-box-content">
		<span class="info-box-text"><a href="' . $link . '" style="text-decoration:none;color:black"><b>' . $title . '</b></a></span>
		<span class="info-box-number">' . number_format($count, $dp) . '</span>
	  </div>
	  <!-- /.info-box-content -->
	</div>
	</div>';
	return $html;
}
/**
 * Summary of homeLinkInfoBox_2
 * @param mixed $title
 * @param mixed $count
 * @param mixed $link
 * @param mixed $icon
 * @param mixed $color
 * @param mixed $sizeclass
 * @param mixed $icontype
 * @param mixed $appginiIcon
 * @param mixed $dp
 * @return string
 */
function homeLinkInfoBox_2($title, $count, $link, $icon = "far fa-envelope", $color = "info", $sizeclass = "col-md-3 col-sm-6 col-12", $icontype = "default", $appginiIcon = '', $dp = 0)
{
	$show_icon = ($icontype == 'default') ? '<i> <img src="' . $appginiIcon . '"></i>' : '<i class="' . $icon . '"></i>';
	$html = '<div class="' . $sizeclass . '" onclick="window.location.href = \'' . $link . '\'">
	<div class="info-box  mb-12 bg-' . $color . '">
	<span class="info-box-icon">' . $show_icon . '</span>
	<div class="info-box-content">
	  <span class="info-box-text"><a href="' . $link . '" style="text-decoration:none;color:white"><b>' . $title . '</b></a></span>
	  <span class="info-box-number">' . number_format($count, $dp) . '</span>
	</div>
	<!-- /.info-box-content -->
  </div>
  </div>';
	return $html;
}
/**
 * Summary of homeLinkInfoBox_3
 * @param mixed $title
 * @param mixed $count
 * @param mixed $link
 * @param mixed $icon
 * @param mixed $color
 * @param mixed $sizeclass
 * @param mixed $icontype
 * @param mixed $appginiIcon
 * @param mixed $dp
 * @return string
 */
function homeLinkInfoBox_3($title, $count, $link, $icon = "far fa-envelope", $color = "info", $sizeclass = "col-md-3 col-sm-6 col-12", $icontype = "default", $appginiIcon = '', $dp = 0)
{
	global $translate;
	$show_icon = ($icontype == 'default') ? '<i> <img src="' . $appginiIcon . '"></i>' : '<i class="' . $icon . '"></i>';
	$html = '<div class="' . $sizeclass . '">
	<!-- small card -->
	<div class="small-box bg-' . $color . '">
	  <div class="inner">
		<h3>' . number_format($count, $dp) . '</h3>
		<p>' . $title . '</p>
	  </div>
	  <div class="icon">
	  ' . $show_icon . '
	  </div>
	  <a href="' . $link . '" class="small-box-footer">
		' . $translate['More info'] . ' <i class="fas fa-arrow-circle-right"></i>
	  </a>
	</div>
  </div>';
	return $html;
}
/**
 * Summary of responsiveTable
 * @param mixed $title
 * @param mixed $dataquery
 * @param mixed $sizeclass
 * @return string
 */
function responsiveTable($title = "Table title", $dataquery, $sizeclass = "col-12")
{
	$th = '';
	$colnames = [];
	$coldata = [];
	$tr = '';
	foreach ($dataquery as $key => $value) {
		# code...get all column names in our query
		$colnames = array_keys($value);
		$coldata[] = array_values($value);
	}
	foreach ($colnames as $name) {
		# code...create our table headers
		$th .= '<th>' . $name . '</th>';
	}
	foreach ($coldata as $key => $data) {
		# code...create our table rows
		$td = '';
		foreach ($data as $key => $value) {
			# code...combine data for our rows
			$td .= '<td>' . $value . '</td>';
		}
		$tr .= '<tr>' . $td . '</tr>';
	}
	$colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
	$color_id = array_rand($colors);
	$color = $colors[$color_id];
	$html = '
	<div class="' . $sizeclass . '">
	  <div class="card">
		<div class="card-header card-outline card-' . $color . '" data-card-widget="collapse">
		  <h3 class="card-title">' . $title . '</h3>
		  <div class="card-tools">
		  <button type="button" class="btn btn-tool" data-card-widget="maximize">
			<i class="fas fa-expand"></i>
		  </button>
		  <button type="button" class="btn btn-tool" data-card-widget="collapse">
			<i class="fas fa-minus"></i>
		  </button>
		  <button type="button" class="btn btn-tool" data-card-widget="remove">
			<i class="fas fa-times"></i>
		  </button>
		</div>
		</div>
		<!-- /.card-header -->
		<div class="card-body table-responsive p-0">
		  <table class="table table-hover text-nowrap table-bordered">
			<thead>
			  <tr>
				' . $th . '
			  </tr>
			</thead>
			<tbody>
			' . $tr . '
			</tbody>
		  </table>
		</div>
		<!-- /.card-body -->
	  </div>
	  <!-- /.card -->
	</div>';

	return $html;
}

/**
 * Summary of dataTable
 * @param mixed $title
 * @param mixed $dataquery
 * @param mixed $sizeclass
 * @param mixed $allow_copy
 * @param mixed $allow_csv
 * @param mixed $allow_excel
 * @param mixed $allow_pdf
 * @param mixed $allow_print
 * @param mixed $sortColumn
 * @param mixed $SortOrder
 * @param mixed $recordsPerPage
 * @param mixed $customstyling
 * @param mixed $collapsed
 * @return string
 */
function dataTable($title = "Table title", $dataquery, $sizeclass = "col-md-12", $allow_copy = 'Yes', $allow_csv = 'Yes', $allow_excel = 'Yes', $allow_pdf = 'Yes', $allow_print = 'Yes', $sortColumn = 0, $SortOrder = 'desc', $recordsPerPage = 50, $customstyling = [], $collapsed = "no")
{
	$tableID = uniqid();
	$th = '';
	$colnames = [];
	$coldata = [];
	$tr = '';
	foreach ($dataquery as $key => $value) {
		# code...get all column names in our query
		$colnames = array_keys($value);
		$coldata[] = array_values($value);
	}
	//if empty coldata then return show alert box
	if (empty($coldata)) {
		$html = showAlert('danger', 'Oops!', 'No data found for this table');
		return $html;
		exit;
	}
	foreach ($colnames as $name) {
		# code...create our table headers
		$th .= '<th>' . ucwords(str_replace('_', ' ', $name)) . '</th>';
	}
	foreach ($coldata as $key => $data) {
		# code...create our table rows
		$td = '';
		foreach ($data as $key => $value) {
			# code...combine data for our rows
			$td .= '<td>' . customdatastyling($value, $customstyling) . '</td>';
		}
		$tr .= '<tr>' . $td . '</tr>';
	}
	$colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
	$color_id = array_rand($colors);
	$color = $colors[$color_id];
	$collapsiple = ($collapsed == "no") ? '' : 'collapsed-card';
	$html = '<div class="' . $sizeclass . '">
    <div class="card card-outline card-' . $color . ' ' . $collapsiple . '">
              <div class="card-header" data-card-widget="collapse">
                <h3 class="card-title">' . $title . '</h3>
				<div class="card-tools">
		  <button type="button" class="btn btn-tool" data-card-widget="maximize">
			<i class="fas fa-expand"></i>
		  </button>
		  <button type="button" class="btn btn-tool" data-card-widget="collapse">
			<i class="fas fa-minus"></i>
		  </button>
		  <button type="button" class="btn btn-tool" data-card-widget="remove">
			<i class="fas fa-times"></i>
		  </button>
		</div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="' . $tableID . '" class="table table-bordered table-striped">
                  <thead>
                  <tr>
				  ' . $th . '
                  </tr>
                  </thead>
                  <tbody>
                  ' . $tr . '
                  </tbody>
                  <tfoot>
                  <tr>
				  ' . $th . '
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
    </div>';
	$colvis = ['colvis'];
	$buttons = [];
	if ($allow_csv == 'Yes') {
		$buttons[] = 'csv';
	}
	if ($allow_excel == 'Yes') {
		$buttons[] = 'excel';
	}
	if ($allow_pdf == 'Yes') {
		$buttons[] = 'pdf';
	}
	if ($allow_print == 'Yes') {
		$buttons[] = 'print';
	}
	if ($allow_copy == 'Yes') {
		$buttons[] = 'copy';
	}
	$buttons = array_merge($buttons, $colvis);
	$js = '
	<script>
	$(function () {
	  $("#' . $tableID . '").DataTable({
		order: [[' . $sortColumn . ', "' . $SortOrder . '"]],
		"pageLength": ' . $recordsPerPage . ',
		"responsive": true, "lengthChange": false, "autoWidth": false,
		"buttons": ' . json_encode($buttons) . '
	  }).buttons().container().appendTo("#' . $tableID . '_wrapper .col-md-6:eq(0)");
	});
  </script>';
	return $html . $js;
}

/**
 * Summary of donutChart
 * @param mixed $chartTitle
 * @param mixed $chartData
 * @param mixed $color
 * @param mixed $chartSize
 * @return string
 */
function donutChart($chartTitle = "Title of the donut chart", $chartData, $color = "primary", $chartSize = "col-md-12")
{
	$labels = '';
	$datasets = '';
	$backgroundColor = '';
	foreach ($chartData as $key => $data) {
		# code...
		$backgroundColor .= '"#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6) . '",';
		$labels .= "\"" . $data['label'] . "\",";
		$datasets .= '' . $data['value'] . ',';
	}
	//if empty datasets then return show alert box
	if (empty($datasets)) {
		$html = showAlert('danger', 'Oops!', 'No data found for this chart');
		return $html;
		exit;
	}
	$chartID = uniqid();
	$html = '
	<div class="' . $chartSize . '">
	<div class="card card-' . $color . '">
		<div class="card-header" data-card-widget="collapse">
			<h3 class="card-title">' . $chartTitle . '</h3>

			<div class="card-tools">
				<button type="button" class="btn btn-tool" data-card-widget="collapse">
					<i class="fas fa-minus"></i>
				</button>
				<button type="button" class="btn btn-tool" data-card-widget="remove">
					<i class="fas fa-times"></i>
				</button>
			</div>
		</div>
		<div class="card-body">
			<canvas id="' . $chartID . '" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
		</div>
		<!-- /.card-body -->
	</div>
</div>
	';
	$js = '
	<script>
		$(function() {
			var donutChartCanvas = $("#' . $chartID . '").get(0).getContext("2d");
			var donutData = {
	
				labels: [
					' . $labels . '
				],
				datasets: [{
					data: [' . $datasets . '],
					backgroundColor: [' . $backgroundColor . '],
				}]
			}
			var donutOptions = {
				maintainAspectRatio: false,
				responsive: true,
			}
			//Create douhnut chart
			new Chart(donutChartCanvas, {
				type: "doughnut",
				data: donutData,
				options: donutOptions
			})
		});
			</script>';
	return $html . $js;
}
/**
 * Summary of pieChart
 * @param mixed $chartTitle
 * @param mixed $chartData
 * @param mixed $color
 * @param mixed $chartSize
 * @return string
 */
function pieChart($chartTitle = "Title of the pie chart", $chartData, $color = "primary", $chartSize = "col-md-12")
{
	$labels = '';
	$datasets = '';
	$backgroundColor = '';
	foreach ($chartData as $key => $data) {
		# code...
		$backgroundColor .= '"#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6) . '",';
		$labels .= "\"" . $data['label'] . "\",";
		$datasets .= '' . $data['value'] . ',';
	}
	//if empty datasets then return show alert box
	if (empty($datasets)) {
		$html = showAlert('danger', 'Oops!', 'No data found for this chart');
		return $html;
		exit;
	}
	$chartID = uniqid();
	$html = '
	<div class="' . $chartSize . '">
	<div class="card card-' . $color . '">
		<div class="card-header" data-card-widget="collapse">
			<h3 class="card-title">' . $chartTitle . '</h3>

			<div class="card-tools">
				<button type="button" class="btn btn-tool" data-card-widget="collapse">
					<i class="fas fa-minus"></i>
				</button>
				<button type="button" class="btn btn-tool" data-card-widget="remove">
					<i class="fas fa-times"></i>
				</button>
			</div>
		</div>
		<div class="card-body">
			<canvas id="' . $chartID . '" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
		</div>
		<!-- /.card-body -->
	</div>
</div>
	';
	$js = '
	<script>
		$(function() {
			var pieChartCanvas = $("#' . $chartID . '").get(0).getContext("2d");
			var pieData = {
	
				labels: [
					' . $labels . '
				],
				datasets: [{
					data: [' . $datasets . '],
					backgroundColor: [' . $backgroundColor . '],
				}]
			}
			var pieOptions = {
				maintainAspectRatio: false,
				responsive: true,
			}
			//Create pie chart
			new Chart(pieChartCanvas, {
				type: "pie",
				data: pieData,
				options: pieOptions
			})
		});
			</script>';
	return $html . $js;
}

/**
 * Summary of barChart
 * @param mixed $chartTitle
 * @param mixed $chartData
 * @param mixed $datasetLabel
 * @param mixed $color
 * @param mixed $chartSize
 * @return string
 */
function barChart($chartTitle = "Title Of The Bar Chart", $chartData, $datasetLabel = "Label Of data set", $color = "primary", $chartSize = "col-md-12")
{
	$labels = '';
	$datasets = '';
	$backgroundColor = '';
	foreach ($chartData as $key => $data) {
		# code...
		$backgroundColor .= '"#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6) . '",';
		$labels .= "\"" . $data['label'] . "\",";
		$datasets .= '' . $data['value'] . ',';
	}
	//if empty datasets then return show alert box
	if (empty($datasets)) {
		$html = showAlert('danger', 'Oops!', 'No data found for this chart');
		return $html;
		exit;
	}
	$chartID = uniqid();
	$html = '
	<div class="' . $chartSize . '">
	<div class="card card-' . $color . '">
		<div class="card-header" data-card-widget="collapse">
			<h3 class="card-title">' . $chartTitle . '</h3>

			<div class="card-tools">
				<button type="button" class="btn btn-tool" data-card-widget="collapse">
					<i class="fas fa-minus"></i>
				</button>
				<button type="button" class="btn btn-tool" data-card-widget="remove">
					<i class="fas fa-times"></i>
				</button>
			</div>
		</div>
		<div class="card-body">
			<canvas id="' . $chartID . '" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
		</div>
		<!-- /.card-body -->
	</div>
	</div>';
	$js = '
	<script>
		$(function() {
			// bar chart data
			var areaChartData = {
				labels: [' . $labels . '],
				datasets: [{
					label: "' . $datasetLabel . '",
					backgroundColor: [' . $backgroundColor . '],
					borderColor: "rgba(60,141,188,0.8)",
					pointRadius: false,
					pointColor: "#3b8bba",
					pointStrokeColor: "rgba(60,141,188,1)",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(60,141,188,1)",
					data: [ ' . $datasets . ']
				}, ]
			}
			//bar chart init
			var barChartCanvas = $("#' . $chartID . '").get(0).getContext("2d")
			var barChartData = $.extend(true, {}, areaChartData)
			var temp0 = areaChartData.datasets[0]
			barChartData.datasets[0] = temp0
	
			var barChartOptions = {
				responsive: true,
				legend: {
					display: true
				},
				maintainAspectRatio: false,
				datasetFill: false
			}
	
			new Chart(barChartCanvas, {
				type: "bar",
				data: barChartData,
				options: barChartOptions
			})
		})
	</script>';
	return $html . $js;
}

/**
 * Summary of lineChart
 * @param mixed $chartTitle
 * @param mixed $chartData
 * @param mixed $datasetLabel
 * @param mixed $color
 * @param mixed $chartSize
 * @return string
 */
function lineChart($chartTitle = "Title Of The Line Chart", $chartData, $datasetLabel = "Label Of data set", $color = "primary", $chartSize = "col-md-12")
{
	$labels = '';
	$datasets = '';
	foreach ($chartData as $key => $data) {
		# code...
		$backgroundColor = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6) . '';
		$labels .= "\"" . $data['label'] . "\",";
		$datasets .= '' . $data['value'] . ',';
	}
	//if empty datasets then return show alert box
	if (empty($datasets)) {
		$html = showAlert('danger', 'Oops!', 'No data found for this chart');
		return $html;
		exit;
	}
	$chartID = uniqid();
	$html = '<div class="' . $chartSize . '">
	<!-- LINE CHART -->
	<div class="card card-' . $color . '">
	  <div class="card-header" data-card-widget="collapse">
		<h3 class="card-title">' . $chartTitle . '</h3>

		<div class="card-tools">
		  <button type="button" class="btn btn-tool" data-card-widget="collapse">
			<i class="fas fa-minus"></i>
		  </button>
		  <button type="button" class="btn btn-tool" data-card-widget="remove">
			<i class="fas fa-times"></i>
		  </button>
		</div>
	  </div>
	  <div class="card-body">
		<div class="chart">
		  <canvas id="' . $chartID . '" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
		</div>
	  </div>
	  <!-- /.card-body -->
	</div>
	</div>';
	$js = '
	<script>
	$(function() {
		var ThelineChartData = {
			labels  : [' . $labels . '],
			datasets: [
			  {
				label               : "' . $datasetLabel . '",
				backgroundColor     : "' . $backgroundColor . '",
				borderColor         : "rgba(60,141,188,0.8)",
				pointRadius          : 4,
				pointColor          : "#3b8bba",
				pointStrokeColor    : "rgba(60,141,188,1)",
				pointHighlightFill  : "#fff",
				pointHighlightStroke: "rgba(60,141,188,1)",
				data                : [' . $datasets . ']
			  },
			]
		  }
		  var ThelineChartOptions = {
			maintainAspectRatio : false,
			responsive : true,
			legend: {
			  display: true
			},
			scales: {
			  xAxes: [{
				gridLines : {
				  display : false,
				}
			  }],
			  yAxes: [{
				gridLines : {
				  display : false,
				}
			  }]
			}
		  }
		var lineChartCanvas = $("#' . $chartID . '").get(0).getContext("2d")
    var lineChartOptions = $.extend(true, {}, ThelineChartOptions)
    var lineChartData = $.extend(true, {}, ThelineChartData)
    lineChartData.datasets[0].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, {
      type: "line",
      data: lineChartData,
      options: lineChartOptions
    })
	})
	</script>';
	return $html . $js;
}

/**
 * Summary of areaChart
 * @param mixed $chartTitle
 * @param mixed $chartData
 * @param mixed $datasetLabel
 * @param mixed $color
 * @param mixed $chartSize
 * @return string
 */
function areaChart($chartTitle = "Title Of The Line Chart", $chartData, $datasetLabel = "Label Of data set", $color = "primary", $chartSize = "col-md-12")
{
	$labels = '';
	$datasets = '';
	foreach ($chartData as $key => $data) {
		# code...
		$backgroundColor = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6) . '';
		$labels .= "\"" . $data['label'] . "\",";
		$datasets .= '' . $data['value'] . ',';
	}
	//if empty datasets then return show alert box
	if (empty($datasets)) {
		$html = showAlert('danger', 'Oops!', 'No data found for this chart');
		return $html;
		exit;
	}
	$chartID = uniqid();
	$html = '<div class="' . $chartSize . '">
	<!-- AREA CHART -->
	<div class="card card-' . $color . '">
	  <div class="card-header" data-card-widget="collapse">
		<h3 class="card-title">' . $chartTitle . '</h3>
		<div class="card-tools">
		  <button type="button" class="btn btn-tool" data-card-widget="collapse">
			<i class="fas fa-minus"></i>
		  </button>
		  <button type="button" class="btn btn-tool" data-card-widget="remove">
			<i class="fas fa-times"></i>
		  </button>
		</div>
	  </div>
	  <div class="card-body">
		<div class="chart">
		  <canvas id="' . $chartID . '" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
		</div>
	  </div>
	  <!-- /.card-body -->
	</div>
	<!-- /.card -->
	</div>';
	$js = '
	<script>
	$(function() {
	 var areaChartCanvas = $("#' . $chartID . '").get(0).getContext("2d")
    var areaChartData = {
      labels  : [' . $labels . '],
      datasets: [
        {
          label               : "' . $datasetLabel . '",
          backgroundColor     : "' . $backgroundColor . '",
          borderColor         : "rgba(210, 214, 222, 1)",
          pointRadius         : 4,
          pointColor          : "rgba(210, 214, 222, 1)",
          pointStrokeColor    : "#c1c7d1",
          pointHighlightFill  : "#fff",
          pointHighlightStroke: "rgba(220,220,220,1)",
          data                : [' . $datasets . ']
        },
      ]
    }
    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: true
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
    new Chart(areaChartCanvas, {
      type: "line",
      data: areaChartData,
      options: areaChartOptions
    })
})
	</script>';
	return $html . $js;
}

/**
 * Summary of showAlert
 * @param mixed $type
 * @param mixed $title
 * @param mixed $body
 * @return string
 */
function showAlert($type, $title, $body)
{
	if ($type == "success") {
		$icon = "fa-check-circle";
	} elseif ($type == "info") {
		$icon = "fa-info-circle";
	} elseif ($type == "warning") {
		$icon = "fa-exclamation-triangle";
	} elseif ($type == "danger") {
		$icon = "fa-times-circle";
	}
	$html = '
	<div class="alert alert-' . $type . ' alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<h5><i class="icon fas ' . $icon . '"></i> ' . $title . '</h5>
		' . $body . '
	</div>
	';
	return $html;
}

/**
 * Summary of showCallout
 * @param mixed $type
 * @param mixed $title
 * @param mixed $body
 * @return string
 */
function showCallout($type, $title, $body)
{
	if ($type == "success") {
		$icon = "fa-check-circle";
	} elseif ($type == "info") {
		$icon = "fa-info-circle";
	} elseif ($type == "warning") {
		$icon = "fa-exclamation-triangle";
	} elseif ($type == "danger") {
		$icon = "fa-times-circle";
	}
	$html = '
	<div class="callout callout-' . $type . '">
		<h5><i class="icon fas ' . $icon . '"></i> ' . $title . '</h5>
		' . $body . '
	</div>
	';
	return $html;
}

/**
 * Summary of showApplicationButton
 * @param mixed $has_bg
 * @param mixed $bg_color
 * @param mixed $has_count
 * @param mixed $count_bg
 * @param mixed $count
 * @param mixed $title
 * @param mixed $link
 * @param mixed $icon
 * @return string
 */
function showApplicationButton($has_bg = "No", $bg_color = "success", $has_count = "No", $count_bg = "danger", $count = '0', $title = "Sales", $link = "#", $icon = "fa-trophy")
{
	$if_has_bg = ($has_bg == "Yes") ? 'bg-' . $bg_color : '';
	$if_has_count = ($has_count == "Yes") ? '<span class="badge bg-' . $count_bg . '">' . $count . '</span>' : '';
	$html = '
	<a class="btn btn-app ' . $if_has_bg . '" href="' . $link . '">
	' . $if_has_count . '
	<i class="fas ' . $icon . '"></i> ' . $title . '
  </a>
	';
	return $html;
}

/**
 * Summary of showModal
 * @param mixed $title
 * @param mixed $body
 * @param mixed $footer
 * @param mixed $btn_text
 * @param mixed $btn_color
 * @param mixed $btn_icon
 * @param mixed $btn_size
 * @return string
 */
function showModal($title, $body, $footer, $btn_text, $btn_color, $btn_icon, $btn_size = '')
{
	$modalID = uniqid();
	$html = '
	<div class="modal fade" id="mid' . $modalID . '">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">' . $title . '</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					' . $body . '
				</div>
				<div class="modal-footer justify-content-between">
					' . $footer . '
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	';
	$btn = '<button type="button" class="btn btn-' . $btn_color . ' ' . $btn_size . '" data-toggle="modal" data-target="#mid' . $modalID . '">
	<i class="fas ' . $btn_icon . '"></i> ' . $btn_text . '
  </button>';
	return $btn . $html;
}

/**
 * Summary of showToast
 * @param mixed $type
 * @param mixed $title
 * @param mixed $body
 * @return string
 */
function showToast($type, $title, $body)
{
	// Show a toast notification whn function is called.
	// $type = success, info, warning, danger
	if ($type == "success") {
		$type = "success";
	} elseif ($type == "info") {
		$type = "info";
	} elseif ($type == "warning") {
		$type = "warning";
	} elseif ($type == "danger") {
		$type = "error";
	}
	$script = '<script src="appginilte/plugins/sweetalert2/sweetalert2.all.min.js"></script>
	<script>
	Swal.fire({
		title: "' . $title . '",
		text: "' . $body . '",
		icon: "' . $type . '",
		showCancelButton: false,
		confirmButtonText: "Ok",
	});
</script>';
	return $script;
}

/**
 * Summary of showDataCard
 * @param mixed $cardTitle
 * @param mixed $cardBody
 * @param mixed $cardColor
 * @param mixed $cardSize
 * @param mixed $cardOutline
 * @param mixed $collapsed
 * @return string
 */
function showDataCard($cardTitle = 'Title of card', $cardBody = 'Html content can go in here', $cardColor = 'primary', $cardSize = 'col-md-3', $cardOutline = 'No', $collapsed = 'No')
{
	$if_collapsed = ($collapsed == "Yes") ? 'collapsed-card' : '';
	$if_outline = ($cardOutline == "Yes") ? 'card-outline' : '';
	$html = ' <div class="' . $cardSize . '">
	<div class="card ' . $if_outline . ' card-' . $cardColor . ' ' . $if_collapsed . '">
	  <div class="card-header" data-card-widget="collapse">
		<h3 class="card-title">' . $cardTitle . '</h3>
		<div class="card-tools">
		  <button type="button" class="btn btn-tool" data-card-widget="maximize">
			<i class="fas fa-expand"></i>
		  </button>
		  <button type="button" class="btn btn-tool" data-card-widget="collapse">
			<i class="fas fa-minus"></i>
		  </button>
		  <button type="button" class="btn btn-tool" data-card-widget="remove">
			<i class="fas fa-times"></i>
		  </button>
		</div>
		<!-- /.card-tools -->
	  </div>
	  <!-- /.card-header -->
	  <div class="card-body">
		' . $cardBody . '
	  </div>
	  <!-- /.card-body -->
	</div>
	<!-- /.card -->
  </div>';
	return $html;
}

/**
 * Summary of sendemail
 * @param mixed $recipient
 * @param mixed $subject
 * @param mixed $body
 * @return bool
 */
function sendemail($recipient, $subject, $body, $attachment_path = '', $attachment_name = '')
{
	$recipients = explode(",", $recipient);
	$sent = 0;
	foreach ($recipients as $recipient) {
		$mail = [
			"to" => $recipient,
			"message" => $body,
			"subject" => $subject,
			"tag" => ["attachment_path" => $attachment_path, "attachment_name" => $attachment_name]
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

/**
 * Summary of obfuscate_email
 * @param mixed $email
 * @return string
 */
function obfuscate_email($email)
{
	$em = explode("@", $email);
	$name = implode('@', array_slice($em, 0, count($em) - 1));
	$len = floor(strlen($name) / 2);

	return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
}

/**
 * Summary of areaChartMultiDataSets
 * @param mixed $chartTitle
 * @param mixed $chartData
 * @param mixed $datasetLabel
 * @param mixed $color
 * @param mixed $chartSize
 * @return string
 */
function areaChartMultiDataSets($chartTitle = "Title Of The Line Chart", $chartData, $datasetLabel, $color = "primary", $chartSize = "col-md-12")
{
	if (count($chartData) !== count($datasetLabel)) {
		return showAlert("danger", "Area Chart Error", "Error: chartData and datasetLabel array values must be the same length");
		exit;
	}
	$grandlabelset = []; //array to store all the labels
	foreach ($chartData as $qkey => $eachquery) {
		$labelqkeys = []; //reset the labelqkeys array
		foreach ($eachquery as $key => $value) {
			# code...
			$labelqkeys[$key] = $value['label']; //add the label to the labelqkeys array
		}
		$grandlabelset[] = $labelqkeys; //add the labelqkeys array to the grandlabelset array
	}
	//operation to get the unique values from the grandlabelset array
	$totallabelkeys = count($grandlabelset) - 1; //get the total number of keys in the merged array
	$uniquelabels = []; //reset the uniquelabels array
	for ($i = 0; $i <= $totallabelkeys; $i++) {
		# code...
		$uniquelabels = array_merge($uniquelabels, $grandlabelset[$i]); //merge the values of the uniquelabels and grandlabelset arrays
	}
	$uniquelabels = array_values(array_unique($uniquelabels)); //remove the duplicate values from the uniquelabels array
	$grandvalueset = []; //reset the grandvalueset array
	foreach ($chartData as $qkey => $eachquery) {
		$valueqkeys = []; //reset the valueqkeys array
		foreach ($eachquery as $key => $value) {
			# code...
			$label = $value['label']; //get the label
			$labelkey = array_search($label, $uniquelabels); //get the key of the label in the uniquelabels array
			$valueqkeys[$labelkey] = $value['value']; //add the value to the valueqkeys array
		}
		$grandvalueset[] = $valueqkeys; //add the valueqkeys array to the grandvalueset array
	}
	$thedatasets = ''; //reset the thedatasets variable
	foreach ($grandvalueset as $key => $value) {
		# code...
		$backgroundColor = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6) . '';
		$thedatasets .= '{
			label               : "' . $datasetLabel[$key] . '",
			backgroundColor     : "' . $backgroundColor . '",
			borderColor         : "rgba(210, 214, 222, 1)",
			pointRadius         : 4,
			pointColor          : "rgba(210, 214, 222, 1)",
			pointStrokeColor    : "#c1c7d1",
			pointHighlightFill  : "#fff",
			pointHighlightStroke: "rgba(220,220,220,1)",
			data                : ' . produceJsonFromIndexedArray($value) . '
		  },';
	}
	//if empty datasets then return show alert box
	if (empty($thedatasets)) {
		$html = showAlert('danger', 'Oops!', 'No data found for this chart');
		return $html;
		exit;
	}
	$chartID = uniqid();
	$html = '<div class="' . $chartSize . '">
	<!-- AREA CHART -->
	<div class="card card-' . $color . '">
	  <div class="card-header" data-card-widget="collapse">
		<h3 class="card-title">' . $chartTitle . '</h3>
		<div class="card-tools">
		  <button type="button" class="btn btn-tool" data-card-widget="collapse">
			<i class="fas fa-minus"></i>
		  </button>
		  <button type="button" class="btn btn-tool" data-card-widget="remove">
			<i class="fas fa-times"></i>
		  </button>
		</div>
	  </div>
	  <div class="card-body">
		<div class="chart">
		  <canvas id="' . $chartID . '" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
		</div>
	  </div>
	  <!-- /.card-body -->
	</div>
	<!-- /.card -->
	</div>';
	$js = '
	<script>
	$(function() {
	 var areaChartCanvas = $("#' . $chartID . '").get(0).getContext("2d")
    var areaChartData = {
      labels  : ' . json_encode($uniquelabels) . ',
      datasets: [
       ' . $thedatasets . '
      ]
    }
    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: true
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
    new Chart(areaChartCanvas, {
      type: "line",
      data: areaChartData,
      options: areaChartOptions
    })
})
	</script>';
	return $html . $js;
}

/**
 * Summary of lineChartMultiDataSets
 * @param mixed $chartTitle
 * @param mixed $chartData
 * @param mixed $datasetLabel
 * @param mixed $color
 * @param mixed $chartSize
 * @return string
 */
function lineChartMultiDataSets($chartTitle = "Title Of The Line Chart", $chartData, $datasetLabel, $color = "primary", $chartSize = "col-md-12")
{
	if (count($chartData) !== count($datasetLabel)) {
		return showAlert("danger", "Area Chart Error", "Error: chartData and datasetLabel array values must be the same length");
		exit;
	}
	$grandlabelset = []; //array to store all the labels
	foreach ($chartData as $qkey => $eachquery) {
		$labelqkeys = []; //reset the labelqkeys array
		foreach ($eachquery as $key => $value) {
			# code...
			$labelqkeys[$key] = $value['label']; //add the label to the labelqkeys array
		}
		$grandlabelset[] = $labelqkeys; //add the labelqkeys array to the grandlabelset array
	}
	//operation to get the unique values from the grandlabelset array
	$totallabelkeys = count($grandlabelset) - 1; //get the total number of keys in the merged array
	$uniquelabels = []; //reset the uniquelabels array
	for ($i = 0; $i <= $totallabelkeys; $i++) {
		# code...
		$uniquelabels = array_merge($uniquelabels, $grandlabelset[$i]); //merge the values of the uniquelabels and grandlabelset arrays
	}
	$uniquelabels = array_values(array_unique($uniquelabels)); //remove the duplicate values from the uniquelabels array
	$grandvalueset = []; //reset the grandvalueset array
	$fillstyle = ''; //reset the fillstyle variable
	foreach ($chartData as $qkey => $eachquery) {
		$valueqkeys = []; //reset the valueqkeys array
		$fillstyle .= 'lineChartData.datasets[' . $qkey . '].fill = false;'; //set the fill style to false for each dataset
		foreach ($eachquery as $key => $value) {
			# code...
			$label = $value['label']; //get the label
			$labelkey = array_search($label, $uniquelabels); //get the key of the label in the uniquelabels array
			$valueqkeys[$labelkey] = $value['value']; //add the value to the valueqkeys array
		}
		$grandvalueset[] = $valueqkeys; //add the valueqkeys array to the grandvalueset array
	}
	$thedatasets = ''; //reset the thedatasets variable
	foreach ($grandvalueset as $key => $value) {
		# code...
		$backgroundColor = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6) . '';
		$thedatasets .= '{
			label               : "' . $datasetLabel[$key] . '",
			backgroundColor     : "' . $backgroundColor . '",
			borderColor         : "rgba(210, 214, 222, 1)",
			pointRadius         : 4,
			pointColor          : "rgba(210, 214, 222, 1)",
			pointStrokeColor    : "#c1c7d1",
			pointHighlightFill  : "#fff",
			pointHighlightStroke: "rgba(220,220,220,1)",
			data                : ' . produceJsonFromIndexedArray($value) . '
		  },';
	}
	//if empty datasets then return show alert box
	if (empty($thedatasets)) {
		$html = showAlert('danger', 'Oops!', 'No data found for this chart');
		return $html;
		exit;
	}
	$chartID = uniqid();
	$html = '<div class="' . $chartSize . '">
	<!-- LINE CHART -->
	<div class="card card-' . $color . '">
	  <div class="card-header" data-card-widget="collapse">
		<h3 class="card-title">' . $chartTitle . '</h3>

		<div class="card-tools">
		  <button type="button" class="btn btn-tool" data-card-widget="collapse">
			<i class="fas fa-minus"></i>
		  </button>
		  <button type="button" class="btn btn-tool" data-card-widget="remove">
			<i class="fas fa-times"></i>
		  </button>
		</div>
	  </div>
	  <div class="card-body">
		<div class="chart">
		  <canvas id="' . $chartID . '" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
		</div>
	  </div>
	  <!-- /.card-body -->
	</div>
	</div>';
	$js = '
	<script>
	$(function() {
		var ThelineChartData = {
			labels  : ' . json_encode($uniquelabels) . ',
			datasets: [
			  ' . $thedatasets . '
			]
		  }
		  var ThelineChartOptions = {
			maintainAspectRatio : false,
			responsive : true,
			legend: {
			  display: true
			},
			scales: {
			  xAxes: [{
				gridLines : {
				  display : false,
				}
			  }],
			  yAxes: [{
				gridLines : {
				  display : false,
				}
			  }]
			}
		  }
		var lineChartCanvas = $("#' . $chartID . '").get(0).getContext("2d")
    var lineChartOptions = $.extend(true, {}, ThelineChartOptions)
    var lineChartData = $.extend(true, {}, ThelineChartData)
    ' . $fillstyle . '
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, {
      type: "line",
      data: lineChartData,
      options: lineChartOptions
    })
	})
	</script>';
	return $html . $js;
}

/**
 * Summary of barChartMultiDataSets
 * @param mixed $chartTitle
 * @param mixed $chartData
 * @param mixed $datasetLabel
 * @param mixed $color
 * @param mixed $chartSize
 * @return string
 */
function barChartMultiDataSets($chartTitle = "Title Of The Bar Chart", $chartData, $datasetLabel, $color = "primary", $chartSize = "col-md-12")
{
	if (count($chartData) !== count($datasetLabel)) {
		return showAlert("danger", "Area Chart Error", "Error: chartData and datasetLabel array values must be the same length");
		exit;
	}
	$grandlabelset = []; //array to store all the labels
	foreach ($chartData as $qkey => $eachquery) {
		$labelqkeys = []; //reset the labelqkeys array
		foreach ($eachquery as $key => $value) {
			# code...
			$labelqkeys[$key] = $value['label']; //add the label to the labelqkeys array
		}
		$grandlabelset[] = $labelqkeys; //add the labelqkeys array to the grandlabelset array
	}
	//operation to get the unique values from the grandlabelset array
	$totallabelkeys = count($grandlabelset) - 1; //get the total number of keys in the merged array
	$uniquelabels = []; //reset the uniquelabels array
	for ($i = 0; $i <= $totallabelkeys; $i++) {
		# code...
		$uniquelabels = array_merge($uniquelabels, $grandlabelset[$i]); //merge the values of the uniquelabels and grandlabelset arrays
	}
	$uniquelabels = array_values(array_unique($uniquelabels)); //remove the duplicate values from the uniquelabels array
	$grandvalueset = []; //reset the grandvalueset array
	$fillstyle = ''; //reset the fillstyle variable
	foreach ($chartData as $qkey => $eachquery) {
		$valueqkeys = []; //reset the valueqkeys array
		$fillstyle .= 'var temp' . $qkey . ' = areaChartData.datasets[' . $qkey . ']; barChartData.datasets[' . $qkey . '] = temp' . $qkey . '; '; //set whatever there
		foreach ($eachquery as $key => $value) {
			# code...
			$label = $value['label']; //get the label
			$labelkey = array_search($label, $uniquelabels); //get the key of the label in the uniquelabels array
			$valueqkeys[$labelkey] = $value['value']; //add the value to the valueqkeys array
		}
		$grandvalueset[] = $valueqkeys; //add the valueqkeys array to the grandvalueset array
	}
	$thedatasets = ''; //reset the thedatasets variable
	foreach ($grandvalueset as $key => $value) {
		# code...
		$backgroundColor = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6) . '';
		$thedatasets .= '{
			label               : "' . $datasetLabel[$key] . '",
			backgroundColor     : "' . $backgroundColor . '",
			borderColor         : "rgba(210, 214, 222, 1)",
			pointRadius         : 4,
			pointColor          : "rgba(210, 214, 222, 1)",
			pointStrokeColor    : "#c1c7d1",
			pointHighlightFill  : "#fff",
			pointHighlightStroke: "rgba(220,220,220,1)",
			data                : ' . produceJsonFromIndexedArray($value) . '
		  },';
	}
	//if empty datasets then return show alert box
	if (empty($thedatasets)) {
		$html = showAlert('danger', 'Oops!', 'No data found for this chart');
		return $html;
		exit;
	}
	$chartID = uniqid();
	$html = '
	<div class="' . $chartSize . '">
	<div class="card card-' . $color . '">
		<div class="card-header" data-card-widget="collapse">
			<h3 class="card-title">' . $chartTitle . '</h3>

			<div class="card-tools">
				<button type="button" class="btn btn-tool" data-card-widget="collapse">
					<i class="fas fa-minus"></i>
				</button>
				<button type="button" class="btn btn-tool" data-card-widget="remove">
					<i class="fas fa-times"></i>
				</button>
			</div>
		</div>
		<div class="card-body">
			<canvas id="' . $chartID . '" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
		</div>
		<!-- /.card-body -->
	</div>
	</div>';
	$js = '
	<script>
		$(function() {
			// bar chart data
			var areaChartData = {
				labels: ' . json_encode($uniquelabels) . ',
				datasets: [' . $thedatasets . ']
			}
			//bar chart init
			var barChartCanvas = $("#' . $chartID . '").get(0).getContext("2d")
			var barChartData = $.extend(true, {}, areaChartData)
			' . $fillstyle . '
	
			var barChartOptions = {
				responsive: true,
				legend: {
					display: true
				},
				maintainAspectRatio: false,
				datasetFill: false
			}
	
			new Chart(barChartCanvas, {
				type: "bar",
				data: barChartData,
				options: barChartOptions
			})
		})
	</script>';
	return $html . $js;
}

/**
 * Summary of stackedBarChart
 * @param mixed $chartTitle
 * @param mixed $chartData
 * @param mixed $datasetLabel
 * @param mixed $color
 * @param mixed $chartSize
 * @return string
 */
function stackedBarChart($chartTitle = "Title Of The Bar Chart", $chartData, $datasetLabel, $color = "primary", $chartSize = "col-md-12")
{
	if (count($chartData) !== count($datasetLabel)) {
		return showAlert("danger", "Area Chart Error", "Error: chartData and datasetLabel array values must be the same length");
		exit;
	}
	$grandlabelset = []; //array to store all the labels
	foreach ($chartData as $qkey => $eachquery) {
		$labelqkeys = []; //reset the labelqkeys array
		foreach ($eachquery as $key => $value) {
			# code...
			$labelqkeys[$key] = $value['label']; //add the label to the labelqkeys array
		}
		$grandlabelset[] = $labelqkeys; //add the labelqkeys array to the grandlabelset array
	}
	//operation to get the unique values from the grandlabelset array
	$totallabelkeys = count($grandlabelset) - 1; //get the total number of keys in the merged array
	$uniquelabels = []; //reset the uniquelabels array
	for ($i = 0; $i <= $totallabelkeys; $i++) {
		# code...
		$uniquelabels = array_merge($uniquelabels, $grandlabelset[$i]); //merge the values of the uniquelabels and grandlabelset arrays
	}
	$uniquelabels = array_values(array_unique($uniquelabels)); //remove the duplicate values from the uniquelabels array
	$grandvalueset = []; //reset the grandvalueset array
	$fillstyle = ''; //reset the fillstyle variable
	foreach ($chartData as $qkey => $eachquery) {
		$valueqkeys = []; //reset the valueqkeys array
		foreach ($eachquery as $key => $value) {
			# code...
			$label = $value['label']; //get the label
			$labelkey = array_search($label, $uniquelabels); //get the key of the label in the uniquelabels array
			$valueqkeys[$labelkey] = $value['value']; //add the value to the valueqkeys array
		}
		$grandvalueset[] = $valueqkeys; //add the valueqkeys array to the grandvalueset array
	}
	$thedatasets = ''; //reset the thedatasets variable
	foreach ($grandvalueset as $key => $value) {
		# code...
		$backgroundColor = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6) . '';
		$thedatasets .= '{
			label               : "' . $datasetLabel[$key] . '",
			backgroundColor     : "' . $backgroundColor . '",
			borderColor         : "rgba(210, 214, 222, 1)",
			pointRadius         : 4,
			pointColor          : "rgba(210, 214, 222, 1)",
			pointStrokeColor    : "#c1c7d1",
			pointHighlightFill  : "#fff",
			pointHighlightStroke: "rgba(220,220,220,1)",
			data                : ' . produceJsonFromIndexedArray($value) . '
		  },';
	}
	//if empty datasets then return show alert box
	if (empty($thedatasets)) {
		$html = showAlert('danger', 'Oops!', 'No data found for this chart');
		return $html;
		exit;
	}
	$chartID = uniqid();
	$html = '
	<div class="' . $chartSize . '">
	<div class="card card-' . $color . '">
		<div class="card-header" data-card-widget="collapse">
			<h3 class="card-title">' . $chartTitle . '</h3>

			<div class="card-tools">
				<button type="button" class="btn btn-tool" data-card-widget="collapse">
					<i class="fas fa-minus"></i>
				</button>
				<button type="button" class="btn btn-tool" data-card-widget="remove">
					<i class="fas fa-times"></i>
				</button>
			</div>
		</div>
		<div class="card-body">
			<canvas id="' . $chartID . '" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
		</div>
		<!-- /.card-body -->
	</div>
	</div>';
	$js = '
	<script>
	$(function() {
		// bar chart data
		var ThestackedBarChartData = {
			labels: ' . json_encode($uniquelabels) . ',
			datasets: [' . $thedatasets . ']
		}
		//bar chart init
		var barChartCanvas = $("#' . $chartID . '").get(0).getContext("2d")
		var barChartData = $.extend(true, {}, ThestackedBarChartData)
		' . $fillstyle . '

		var barChartOptions = {
			responsive: true,
			legend: {
				display: true
			},
			maintainAspectRatio: false,
			datasetFill: false
		}

		new Chart(barChartCanvas, {
			type: "bar",
			data: barChartData,
			options: barChartOptions
		})
		var stackedBarChartCanvas = $("#' . $chartID . '").get(0).getContext("2d")
    var stackedBarChartData = $.extend(true, {}, ThestackedBarChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }
    new Chart(stackedBarChartCanvas, {
      type: "bar",
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
  })
	</script>';
	return $html . $js;
}

/**
 * Summary of produceJsonFromIndexedArray
 * @param mixed $array
 * @return string
 */
function produceJsonFromIndexedArray($array)
{
	end($array); // move the internal pointer to the end of the array
	$lastkey = key($array); // get the key of the last element
	$container = []; //reset the container array
	for ($i = 0; $i <= $lastkey; $i++) {
		# code...
		$container[$i] = $array[$i] ? $array[$i] : 0; //add the value to the container array
	}
	$json = '[';
	foreach ($container as $key => $value) {
		# code...
		$json .= '"' . $value . '",';
	}
	$json = rtrim($json, ',');
	$json .= ']';
	return $json;
}

/**
 * Summary of reportcount
 * @param mixed $table_name
 * @param mixed $group
 * @return int
 */
function reportcount($table_name, $group = '')
{
	$count = 0;
	if ($handle = opendir('./appginilte/appginilte_reports')) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != ".." && $entry != "index.html") {
				#code...
				$entry_table_name = explode('-', $entry)[0];
				if ($entry_table_name == $table_name) {
					if ($group != '') {
						$file_data = file_get_contents('./appginilte/appginilte_reports/' . $entry);
						$decode_data = json_decode($file_data, true);
						$report_groups = $decode_data['report_groups'];
						if (strpos($report_groups, $group) !== false || $report_groups === '*') {
							# code...
							$count++;
						}
					} else {
						$count++;
					}
				}
			}
		}
		closedir($handle);
	}
	return $count;
}

/**
 * Summary of renderReport
 * @param mixed $report_name
 * @param mixed $labelfield
 * @param mixed $table
 * @param mixed $summary
 * @param mixed $report_groups
 * @param mixed $barchart
 * @param mixed $donutchart
 * @param mixed $piechart
 * @param mixed $pagination
 * @param mixed $FilterOperator
 * @param mixed $labelfieldislookup
 * @param mixed $labelfieldparent
 * @param mixed $labelfieldparentfield
 * @param mixed $enforceuserpermissions
 * @param mixed $customwhere
 * @param mixed $labelfieldposition
 * @param mixed $summaryfield
 * @param mixed $DateFilterField
 * @param mixed $datefrom
 * @param mixed $dateto
 * @param mixed $labelfieldisdate
 * @param mixed $labelfielddisplay
 * @param mixed $label_field_alias
 * @param mixed $label_field_summary_title
 * @return string
 */
function renderReport($report_name, $labelfield, $table, $summary, $report_groups = "", $barchart = "", $donutchart = "", $piechart = "", $pagination, $FilterOperator = 'like', $labelfieldislookup = '', $labelfieldparent = '', $labelfieldparentfield = '', $enforceuserpermissions = '', $customwhere = '', $labelfieldposition = 1, $summaryfield = '', $DateFilterField = '', $datefrom = '', $dateto = '', $labelfieldisdate = '', $labelfielddisplay = '', $label_field_alias = '', $label_field_summary_title = '')
{
	//GET THE APPROPRIATE MYSQL FUNCTIONS
	if ($summary == 'Count') {
		$mysqlfunc = "COUNT";
		$mysqlfunclabelfield = $labelfield;
	} elseif ($summary == 'Average') {
		$mysqlfunc = "AVG";
		$mysqlfunclabelfield = $summaryfield;
	} elseif ($summary == 'Sum') {
		$mysqlfunc = "SUM";
		$mysqlfunclabelfield = $summaryfield;
	} elseif ($summary == 'Maximum') {
		$mysqlfunc = "MAX";
		$mysqlfunclabelfield = $summaryfield;
	} elseif ($summary == 'Minimum') {
		$mysqlfunc = "MIN";
		$mysqlfunclabelfield = $summaryfield;
	} else {
		$mysqlfunc = "DISTINCT";
	}
	//get labelfield position
	$tblfls = get_table_fields($table);
	$fromStatement = ($enforceuserpermissions == "on") ? get_sql_from($table) : get_sql_from($table, true);
	$customwhere = ($customwhere != '') ? 'AND ' . $customwhere : '';
	$tblfls = array_keys($tblfls);
	$labelfieldpos = array_search($labelfield, $tblfls) + 1;
	//get the data from the database
	$valueAlias = empty($label_field_summary_title) ? ucwords(strtolower($mysqlfunc)) . "_Of_" . ucwords(strtolower($mysqlfunclabelfield)) : str_replace(' ', '_', $label_field_summary_title);
	$labelfieldalias = empty($label_field_alias) ? ucwords(strtolower($labelfield)) : str_replace(' ', '_', $label_field_alias);
	$dataurl = urlencode("" . $table . "_view.php?SortField=&SortDirection=&FilterAnd%5B1%5D=and&FilterField%5B1%5D=" . $labelfieldpos . "&FilterOperator%5B1%5D=" . $FilterOperator . "&FilterValue%5B1%5D=");
	$filterdates_dataurl = "&filterdates_dataurl=none";
	//if date filter field is set, add date filter to the query
	if (!empty($DateFilterField)) {
		$datefilterfieldpos = array_search($DateFilterField, $tblfls) + 1;
		$filterdates_dataurl = urlencode("&FilterAnd%5B2%5D=and&FilterField%5B2%5D=" . $datefilterfieldpos . "&FilterOperator%5B2%5D=greater-than-or-equal-to&FilterValue%5B2%5D=" . $datefrom . "&FilterAnd%5B3%5D=and&FilterField%5B3%5D=" . $datefilterfieldpos . "&FilterOperator%5B3%5D=less-than-or-equal-to&FilterValue%5B3%5D=" . $dateto . "");
	}
	if ($labelfieldislookup == "Yes") {
		//if labelfield is date, format the date using the specified labelfielddisplay format
		if ($labelfieldisdate == "Yes") {
			if ($labelfielddisplay == "month") {
				$labelfieldSelect = "MONTHNAME(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ") AS Month";
				$group_by = "GROUP BY MONTH(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ")";
				$lf_filter_format = "DATE_FORMAT(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ",'%d')";
			} elseif ($labelfielddisplay == "year") {
				$labelfieldSelect = "YEAR(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ") AS Year";
				$group_by = "GROUP BY YEAR(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ")";
				$lf_filter_format = "DATE_FORMAT(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ",'%Y')";
			} else {
				//format date
				$labelfieldSelect = "DATE_FORMAT(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ",'%D %M, %Y') AS " . $labelfieldalias;
				$group_by = "GROUP BY DATE_FORMAT(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ",'%D %M, %Y')";
				$lf_filter_format = "DATE_FORMAT(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ",'%Y-%m-%d')";
			}
		} else {
			$labelfieldSelect = $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . " AS " . $labelfieldalias;
			$group_by = "GROUP BY " . $table . "." . $labelfield . "";
			$lf_filter_format = $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield;
		}
		$dataTableQuery = sql("SELECT " . $labelfieldSelect . ",CONCAT('<a href=\"appginilte_view.php?page=" . $dataurl . "'," . $lf_filter_format . ",'" . $filterdates_dataurl . "','\">',FORMAT(" . $mysqlfunc . "(" . $table . "." . $mysqlfunclabelfield . "),0),'</a>') AS " . $valueAlias . " FROM " . $fromStatement . " " . $customwhere . " " . $group_by . "", $eo);
	}
	if ($labelfieldislookup == "No") {
		//if labelfield is date, format the date using the specified labelfielddisplay format
		if ($labelfieldisdate == "Yes") {
			if ($labelfielddisplay == "month") {
				$labelfieldSelect = "MONTHNAME(" . $table . "." . $labelfield . ") AS Month";
				$group_by = "GROUP BY MONTH(" . $table . "." . $labelfield . ")";
				$lf_filter_format = "DATE_FORMAT(" . $table . "." . $labelfield . ",'%m')";
			} elseif ($labelfielddisplay == "year") {
				$labelfieldSelect = "YEAR(" . $table . "." . $labelfield . ") AS Year";
				$group_by = "GROUP BY YEAR(" . $table . "." . $labelfield . ")";
				$lf_filter_format = "DATE_FORMAT(" . $table . "." . $labelfield . ",'%Y')";
			} else {
				//format date
				$labelfieldSelect = "DATE_FORMAT(" . $table . "." . $labelfield . ",'%D %M, %Y') AS " . $labelfieldalias;
				$group_by = "GROUP BY DATE_FORMAT(" . $table . "." . $labelfield . ",'%D %M, %Y')";
				$lf_filter_format = "DATE_FORMAT(" . $table . "." . $labelfield . ",'%Y-%m-%d')";
			}
		} else {
			$labelfieldSelect = $table . "." . $labelfield . " AS " . $labelfieldalias;
			$group_by = "GROUP BY " . $table . "." . $labelfield . "";
			$lf_filter_format = $table . "." . $labelfield;
		}
		$dataTableQuery = sql("SELECT " . $labelfieldSelect . ",CONCAT('<a href=\"appginilte_view.php?page=" . $dataurl . "'," . $lf_filter_format . ",'" . $filterdates_dataurl . "','\">',FORMAT(" . $mysqlfunc . "(" . $table . "." . $mysqlfunclabelfield . "),0),'</a>') AS " . $valueAlias . "  FROM " . $fromStatement . " " . $customwhere . " $group_by", $eo);
	}
	//store data table in variable.
	$htmldata = dataTable($report_name, $dataTableQuery, 'col-md-12', 'No', 'Yes', 'Yes', 'Yes', 'Yes', 1, 'desc', $pagination, []);
	if ($barchart == "on") {
		if ($labelfieldislookup == "Yes") {
			//if labelfield is date, format the date using the specified labelfielddisplay format
			if ($labelfieldisdate == "Yes") {
				if ($labelfielddisplay == "month") {
					$chartlabelfieldSelect = "MONTHNAME(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ") AS label";
					$chartgroup_by = "GROUP BY MONTH(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ")";
				} elseif ($labelfielddisplay == "year") {
					$chartlabelfieldSelect = "YEAR(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ") AS label";
					$chartgroup_by = "GROUP BY YEAR(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ")";
				} else {
					//format date
					$chartlabelfieldSelect = "DATE_FORMAT(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ",'%D %M, %Y') AS label";
					$chartgroup_by = "GROUP BY " . $table . "." . $labelfield . "";
				}
			} else {
				$chartlabelfieldSelect = $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . " AS label";
				$chartgroup_by = "GROUP BY " . $table . "." . $labelfield . "";
			}
			$barchartQuery = sql("SELECT " . $chartlabelfieldSelect . "," . $mysqlfunc . "(" . $table . "." . $mysqlfunclabelfield . ") AS value  FROM " . $fromStatement . " " . $customwhere . " " . $chartgroup_by . "", $eo);
		}
		if ($labelfieldislookup == "No") {
			//if labelfield is date, format the date using the specified labelfielddisplay format
			if ($labelfieldisdate == "Yes") {
				if ($labelfielddisplay == "month") {
					$chartlabelfieldSelect = "MONTHNAME(" . $table . "." . $labelfield . ") AS label";
					$chartgroup_by = "GROUP BY MONTH(" . $table . "." . $labelfield . ")";
				} elseif ($labelfielddisplay == "year") {
					$chartlabelfieldSelect = "YEAR(" . $table . "." . $labelfield . ") AS label";
					$chartgroup_by = "GROUP BY YEAR(" . $table . "." . $labelfield . ")";
				} else {
					//format date
					$chartlabelfieldSelect = "DATE_FORMAT(" . $table . "." . $labelfield . ",'%D %M, %Y') AS label";
					$chartgroup_by = "GROUP BY " . $table . "." . $labelfield . "";
				}
			} else {
				$chartlabelfieldSelect = $table . "." . $labelfield . " AS label";
				$chartgroup_by = "GROUP BY " . $table . "." . $labelfield . "";
			}
			$barchartQuery = sql("SELECT " . $chartlabelfieldSelect . "," . $mysqlfunc . "(" . $table . "." . $mysqlfunclabelfield . ") AS value  FROM " . $fromStatement . " " . $customwhere . " " . $chartgroup_by . "", $eo);
		}
		$htmldata .= barChart($report_name, $barchartQuery, ucwords(str_replace('_', ' ', $valueAlias)), 'primary', 'col-md-12');
	}

	if ($donutchart == "on") {
		if ($labelfieldislookup == "Yes") {
			//if labelfield is date, format the date using the specified labelfielddisplay format
			if ($labelfieldisdate == "Yes") {
				if ($labelfielddisplay == "month") {
					$chartlabelfieldSelect = "MONTHNAME(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ") AS label";
					$chartgroup_by = "GROUP BY MONTH(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ")";
				} elseif ($labelfielddisplay == "year") {
					$chartlabelfieldSelect = "YEAR(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ") AS label";
					$chartgroup_by = "GROUP BY YEAR(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ")";
				} else {
					//format date
					$chartlabelfieldSelect = "DATE_FORMAT(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ",'%D %M, %Y') AS label";
					$chartgroup_by = "GROUP BY " . $table . "." . $labelfield . "";
				}
			} else {
				$chartlabelfieldSelect = $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . " AS label";
				$chartgroup_by = "GROUP BY " . $table . "." . $labelfield . "";
			}
			$donutchartQuery = sql("SELECT " . $chartlabelfieldSelect . "," . $mysqlfunc . "(" . $table . "." . $mysqlfunclabelfield . ") AS value  FROM " . $fromStatement . " " . $customwhere . " " . $chartgroup_by . "", $eo);
		}
		if ($labelfieldislookup == "No") {
			//if labelfield is date, format the date using the specified labelfielddisplay format
			if ($labelfieldisdate == "Yes") {
				if ($labelfielddisplay == "month") {
					$chartlabelfieldSelect = "MONTHNAME(" . $table . "." . $labelfield . ") AS label";
					$chartgroup_by = "GROUP BY MONTH(" . $table . "." . $labelfield . ")";
				} elseif ($labelfielddisplay == "year") {
					$chartlabelfieldSelect = "YEAR(" . $table . "." . $labelfield . ") AS label";
					$chartgroup_by = "GROUP BY YEAR(" . $table . "." . $labelfield . ")";
				} else {
					//format date
					$chartlabelfieldSelect = "DATE_FORMAT(" . $table . "." . $labelfield . ",'%D %M, %Y') AS label";
					$chartgroup_by = "GROUP BY " . $table . "." . $labelfield . "";
				}
			} else {
				$chartlabelfieldSelect = $table . "." . $labelfield . " AS label";
				$chartgroup_by = "GROUP BY " . $table . "." . $labelfield . "";
			}
			$donutchartQuery = sql("SELECT " . $chartlabelfieldSelect . "," . $mysqlfunc . "(" . $table . "." . $mysqlfunclabelfield . ") AS value  FROM " . $fromStatement . " " . $customwhere . " " . $chartgroup_by . "", $eo);
		}
		$htmldata .= donutChart($report_name, $donutchartQuery, 'danger', 'col-md-12');
	}

	if ($piechart == "on") {
		if ($labelfieldislookup == "Yes") {
			//if labelfield is date, format the date using the specified labelfielddisplay format
			if ($labelfieldisdate == "Yes") {
				if ($labelfielddisplay == "month") {
					$chartlabelfieldSelect = "MONTHNAME(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ") AS label";
					$chartgroup_by = "GROUP BY MONTH(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ")";
				} elseif ($labelfielddisplay == "year") {
					$chartlabelfieldSelect = "YEAR(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ") AS label";
					$chartgroup_by = "GROUP BY YEAR(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ")";
				} else {
					//format date
					$chartlabelfieldSelect = "DATE_FORMAT(" . $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . ",'%D %M, %Y') AS label";
					$chartgroup_by = "GROUP BY " . $table . "." . $labelfield . "";
				}
			} else {
				$chartlabelfieldSelect = $labelfieldparent . $labelfieldposition . "." . $labelfieldparentfield . " AS label";
				$chartgroup_by = "GROUP BY " . $table . "." . $labelfield . "";
			}
			$piechartQuery = sql("SELECT " . $chartlabelfieldSelect . "," . $mysqlfunc . "(" . $table . "." . $mysqlfunclabelfield . ") AS value  FROM " . $fromStatement . "  " . $customwhere . " " . $chartgroup_by . "", $eo);
		}
		if ($labelfieldislookup == "No") {
			//if labelfield is date, format the date using the specified labelfielddisplay format
			if ($labelfieldisdate == "Yes") {
				if ($labelfielddisplay == "month") {
					$chartlabelfieldSelect = "MONTHNAME(" . $table . "." . $labelfield . ") AS label";
					$chartgroup_by = "GROUP BY MONTH(" . $table . "." . $labelfield . ")";
				} elseif ($labelfielddisplay == "year") {
					$chartlabelfieldSelect = "YEAR(" . $table . "." . $labelfield . ") AS label";
					$chartgroup_by = "GROUP BY YEAR(" . $table . "." . $labelfield . ")";
				} else {
					//format date
					$chartlabelfieldSelect = "DATE_FORMAT(" . $table . "." . $labelfield . ",'%D %M, %Y') AS label";
					$chartgroup_by = "GROUP BY " . $table . "." . $labelfield . "";
				}
			} else {
				$chartlabelfieldSelect = $table . "." . $labelfield . " AS label";
				$chartgroup_by = "GROUP BY " . $table . "." . $labelfield . "";
			}
			$piechartQuery = sql("SELECT " . $chartlabelfieldSelect . "," . $mysqlfunc . "(" . $table . "." . $mysqlfunclabelfield . ") AS value  FROM " . $fromStatement . " " . $customwhere . " " . $chartgroup_by . "", $eo);
		}
		$htmldata .= pieChart($report_name, $piechartQuery, 'success', 'col-md-12');
	}
	return $htmldata;
}

/**
 * Summary of customdatastyling
 * @param mixed $value
 * @param mixed $customstyling
 * @return mixed
 */
function customdatastyling($value, $customstyling = [])
{
	if (empty($customstyling)) {
		return $value;
	} else {
		$datestyling = $customstyling['datestyling'];
		$numberstyling = $customstyling['numberstyling'];
		//check if value is a date and apply datestyling as date format
		if (strtotime($value)) {
			return date($datestyling, strtotime($value));
		}
		//check if value is a number and apply numberstyling as number format
		elseif (is_numeric($value)) {
			return number_format($value, $numberstyling);
		} else {
			return $value;
		}
	}
}

/**
 * Summary of googlechart
 * @param mixed $type
 * @param mixed $chartData
 * @param mixed $value_label
 * @param mixed $title
 * @param mixed $cardcolor
 * @param mixed $cardsize
 * @param mixed $cardoutline
 * @param mixed $collapsed
 * @param mixed $chartoptions
 * @return string
 */
function googlechart($type, $chartData, $value_label = "", $title = "chart title", $cardcolor = "primary", $cardsize = "col-md-6", $cardoutline = 'No', $collapsed = 'No', $chartoptions = '')
{
	$chartscript = ' <script type="text/javascript" src="appginilte/plugins/google_charts/loader.js"></script>';
	$alldatasets = '';
	if ($type == "piechart") {
		foreach ($chartData as $key => $data) {
			# code...
			$alldatasets .= '["' . $data['label'] . '",     ' . $data['value'] . '],';
		}
		//if empty datasets then return show alert box
		if (empty($alldatasets)) {
			$html = showAlert('danger', 'Oops!', 'No data found for this chart');
			return $html;
			exit;
		}
		$chartID = uniqid();
		$chartscript .= ' <script type="text/javascript">
		google.charts.load(\'current\', {\'packages\':[\'corechart\']});
		google.charts.setOnLoadCallback(drawChart);
	
		function drawChart() {
	
		  var data = google.visualization.arrayToDataTable([
			[\'Labels\', "' . $value_label . '"],
			' . $alldatasets . '
		  ]);
	
		  var options = {
			title: "' . $title . '",
			' . $chartoptions . '
		  };
	
		  var chart = new google.visualization.PieChart(document.getElementById("' . $chartID . '"));
	
		  chart.draw(data, options);
		}
	  </script>';
		$chartscript .= '<div id="' . $chartID . '" style="max-width: 100%; height: 300px;"></div>';
		$html = showDataCard($title, $chartscript, $cardcolor, $cardsize, $cardoutline, $collapsed);
	}
	if ($type == "donutchart") {
		foreach ($chartData as $key => $data) {
			# code...
			$alldatasets .= '["' . $data['label'] . '",     ' . $data['value'] . '],';
		}
		//if empty datasets then return show alert box
		if (empty($alldatasets)) {
			$html = showAlert('danger', 'Oops!', 'No data found for this chart');
			return $html;
			exit;
		}
		$chartID = uniqid();
		$chartscript .= ' <script type="text/javascript">
		google.charts.load(\'current\', {\'packages\':[\'corechart\']});
		google.charts.setOnLoadCallback(drawChart);
	
		function drawChart() {
	
		  var data = google.visualization.arrayToDataTable([
			[\'Labels\', "' . $value_label . '"],
			' . $alldatasets . '
		  ]);
	
		  var options = {
			title: "' . $title . '",
			pieHole: 0.4,
			' . $chartoptions . '
		  };
	
		  var chart = new google.visualization.PieChart(document.getElementById("' . $chartID . '"));
	
		  chart.draw(data, options);
		}
	  </script>';
		$chartscript .= '<div id="' . $chartID . '" style="max-width: 100%; height: 300px;"></div>';
		$html = showDataCard($title, $chartscript, $cardcolor, $cardsize, $cardoutline, $collapsed);
	}
	if ($type == "linechart") {
		foreach ($chartData as $key => $data) {
			# code...
			$alldatasets .= '["' . $data['label'] . '",     ' . $data['value'] . '],';
		}
		//if empty datasets then return show alert box
		if (empty($alldatasets)) {
			$html = showAlert('danger', 'Oops!', 'No data found for this chart');
			return $html;
			exit;
		}
		$chartID = uniqid();
		$chartscript .= ' <script type="text/javascript">
		google.charts.load(\'current\', {\'packages\':[\'corechart\']});
		google.charts.setOnLoadCallback(drawChart);
	
		function drawChart() {
	
		  var data = google.visualization.arrayToDataTable([
			[\'Labels\', "' . $value_label . '"],
			' . $alldatasets . '
		  ]);
	
		  var options = {
			title: "' . $title . '",
			' . $chartoptions . '
		  };
	
		  var chart = new google.visualization.LineChart(document.getElementById("' . $chartID . '"));
	
		  chart.draw(data, options);
		}
	  </script>';
		$chartscript .= '<div id="' . $chartID . '" style="max-width: 100%; height: 300px;"></div>';
		$html = showDataCard($title, $chartscript, $cardcolor, $cardsize, $cardoutline, $collapsed);
	}
	if ($type == "areachart") {
		foreach ($chartData as $key => $data) {
			# code...
			$alldatasets .= '["' . $data['label'] . '",     ' . $data['value'] . '],';
		}
		//if empty datasets then return show alert box
		if (empty($alldatasets)) {
			$html = showAlert('danger', 'Oops!', 'No data found for this chart');
			return $html;
			exit;
		}
		$chartID = uniqid();
		$chartscript .= ' <script type="text/javascript">
		google.charts.load(\'current\', {\'packages\':[\'corechart\']});
		google.charts.setOnLoadCallback(drawChart);
	
		function drawChart() {
	
		  var data = google.visualization.arrayToDataTable([
			[\'Labels\', "' . $value_label . '"],
			' . $alldatasets . '
		  ]);
	
		  var options = {
			title: "' . $title . '",
			' . $chartoptions . '
		  };
	
		  var chart = new google.visualization.AreaChart(document.getElementById("' . $chartID . '"));
	
		  chart.draw(data, options);
		}
	  </script>';
		$chartscript .= '<div id="' . $chartID . '" style="max-width: 100%; height: 300px;"></div>';
		$html = showDataCard($title, $chartscript, $cardcolor, $cardsize, $cardoutline, $collapsed);
	}
	if ($type == "barchart") {
		foreach ($chartData as $key => $data) {
			# code...
			$alldatasets .= '["' . $data['label'] . '",     ' . $data['value'] . '],';
		}
		//if empty datasets then return show alert box
		if (empty($alldatasets)) {
			$html = showAlert('danger', 'Oops!', 'No data found for this chart');
			return $html;
			exit;
		}
		$chartID = uniqid();
		$chartscript .= '<script type="text/javascript">
       google.charts.load(\'current\', {\'packages\':[\'corechart\']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
         	[\'Labels\', "' . $value_label . '"],
			' . $alldatasets . '
        ]);

        var options = {
         title: "' . $title . '",
			' . $chartoptions . '
        };
		var chart = new google.visualization.BarChart(document.getElementById(("' . $chartID . '")));
            chart.draw(data, options);
      }
	  google.charts.setOnLoadCallback(drawChart);
    </script>';
		$chartscript .= '<div id="' . $chartID . '" style="max-width: 100%; height: 300px;"></div>';
		$html = showDataCard($title, $chartscript, $cardcolor, $cardsize, $cardoutline, $collapsed);
	}

	if ($type == "regions") {
		foreach ($chartData as $key => $data) {
			# code...
			$alldatasets .= '["' . $data['label'] . '",     ' . $data['value'] . '],';
		}
		//if empty datasets then return show alert box
		if (empty($alldatasets)) {
			$html = showAlert('danger', 'Oops!', 'No data found for this chart');
			return $html;
			exit;
		}
		$chartID = uniqid();
		$chartscript .= ' <script type="text/javascript">
		google.charts.load(\'current\', {\'packages\':[\'geochart\']});
		google.charts.setOnLoadCallback(drawRegionsMap);
	
		function drawRegionsMap() {
	
		  var data = google.visualization.arrayToDataTable([
			[\'Country\', "' . $value_label . '"],
			' . $alldatasets . '
		  ]);
	
		  var options = {
			title: "' . $title . '",
			' . $chartoptions . '
		  };
	
		 var options = {};

        var chart = new google.visualization.GeoChart(document.getElementById("' . $chartID . '"));

        chart.draw(data, options);
		}
	  </script>';
		$chartscript .= '<div id="' . $chartID . '" style="max-width: 100%; height: 300px;"></div>';
		$html = showDataCard($title, $chartscript, $cardcolor, $cardsize, $cardoutline, $collapsed);
	}
	if ($type == "gauge") {
		foreach ($chartData as $key => $data) {
			# code...
			$alldatasets .= '["' . $data['label'] . '",     ' . $data['value'] . '],';
		}
		//if empty datasets then return show alert box
		if (empty($alldatasets)) {
			$html = showAlert('danger', 'Oops!', 'No data found for this chart');
			return $html;
			exit;
		}
		$chartID = uniqid();
		$chartscript .= ' <script type="text/javascript">
		google.charts.load(\'current\', {\'packages\':[\'gauge\']});
		google.charts.setOnLoadCallback(drawChart);
	
		function drawChart() {
	
		  var data = google.visualization.arrayToDataTable([
			[\'Labels\', "' . $value_label . '"],
			' . $alldatasets . '
		  ]);
	
		  var options = {
			title: "' . $title . '",
			width: 600, height: 240,
          	redFrom: 90, redTo: 100,
          	yellowFrom:75, yellowTo: 90,
          	minorTicks: 5,
			' . $chartoptions . '
		  };
	
		  var chart = new google.visualization.Gauge(document.getElementById("' . $chartID . '"));
	
		  chart.draw(data, options);
		}
	  </script>';
		$chartscript .= '<div id="' . $chartID . '" style="max-width: 100%; height: 300px;"></div>';
		$html = showDataCard($title, $chartscript, $cardcolor, $cardsize, $cardoutline, $collapsed);
	}

	return $html;

}

/**
 * Summary of Highcharts
 * @param mixed $type
 * @param mixed $chartData
 * @param mixed $value_label
 * @param mixed $title
 * @param mixed $cardcolor
 * @param mixed $cardsize
 * @param mixed $cardoutline
 * @param mixed $collapsed
 * @param mixed $chartoptions
 * @param mixed $chartheight
 * @return string
 */
function Highcharts($type, $chartData, $value_label = "", $title = "cahrt title", $cardcolor = "primary", $cardsize = "col-md-6", $cardoutline = 'No', $collapsed = 'No', $chartoptions = '', $chartheight = "240px")
{
	$script = '<script src="appginilte/plugins/high_charts/highcharts.js"></script>
<script src="appginilte/plugins/high_charts/highcharts-3d.js"></script>
<script src="appginilte/plugins/high_charts/exporting.js"></script>
<script src="appginilte/plugins/high_charts/export-data.js"></script>
<script src="appginilte/plugins/high_charts/accessibility.js"></script>
<script src="appginilte/plugins/high_charts/cylinder.js"></script>';
	$chartID = uniqid();
	$alldatasets = '';
	$script .= '<figure class="highcharts-figure">
    	<div id="' . $chartID . '" style="height:' . $chartheight . '"></div>
</figure>';
	if ($type == "donut-3d") {
		foreach ($chartData as $key => $data) {
			# code...
			$alldatasets .= '["' . $data['label'] . '",     ' . $data['value'] . '],';
		}
		//if empty datasets then return show alert box
		if (empty($alldatasets)) {
			$html = showAlert('danger', 'Oops!', 'No data found for this chart ' . $title);
			return $html;
			exit;
		}
		$script .= "<script>Highcharts.chart('$chartID', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45
        }
    },
    title: {
        text: '$title',
        align: 'left'
    },
    plotOptions: {
        pie: {
            innerSize: 100,
            depth: 45
        }
    },
    series: [{
        name: '$value_label',
        data: [
            $alldatasets

        ]
    }]
});
</script>
";
	}
	if ($type == "pie") {
		foreach ($chartData as $key => $data) {
			# code...
			$alldatasets .= '{ name: "' . $data['label'] . '", y: ' . $data['value'] . ' },';
		}
		//if empty datasets then return show alert box
		if (empty($alldatasets)) {
			$html = showAlert('danger', 'Oops!', 'No data found for this chart ' . $title);
			return $html;
			exit;
		}
		$script .= "<script>
// Build the chart
Highcharts.chart('$chartID', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: '$title',
    align: 'left'
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
  },
  accessibility: {
    point: {
      valueSuffix: '%'
    }
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
        connectorColor: 'silver'
      }
    }
  },
  series: [{
    name: '$value_label',
    data: [
      $alldatasets
    ]
  }]
});</script>";
	}
	if ($type == "line-single") {
		$valuesets = '';
		$labelsets = '';
		foreach ($chartData as $key => $data) {
			# code...
			$labelsets .= '"' . $data['label'] . '",';
			$valuesets .= '' . $data['value'] . ',';
		}
		//if empty datasets then return show alert box
		if (empty($valuesets)) {
			$html = showAlert('danger', 'Oops!', 'No data found for this chart ' . $title);
			return $html;
			exit;
		}
		$script .= '<script>
		Highcharts.chart("' . $chartID . '", {
  chart: {
    type: \'line\'
  },
  title: {
    text: "' . $title . '"
  },
  xAxis: {
    categories: [' . $labelsets . ']
  },
  yAxis: {
    title: {
      text: "' . $value_label . '"
    }
  },
  plotOptions: {
    line: {
      dataLabels: {
        enabled: true
      },
      enableMouseTracking: true
    }
  },
  series: [{
    name: "' . $value_label . '",
    data: [' . $valuesets . ']
  }]
});
	</script>';
	}
	if ($type == "cylinder-3d") {
		$valuesets = '';
		$labelsets = '';
		foreach ($chartData as $key => $data) {
			# code...
			$labelsets .= '"' . $data['label'] . '",';
			$valuesets .= '' . $data['value'] . ',';
		}
		//if empty datasets then return show alert box
		if (empty($valuesets)) {
			$html = showAlert('danger', 'Oops!', 'No data found for this chart ' . $title);
			return $html;
			exit;
		}
		$script .= '<script>
	Highcharts.chart("' . $chartID . '", {
		chart: {
		  type: \'cylinder\',
		  options3d: {
			enabled: true,
			alpha: 0,
			beta: 346,
			depth: 100,
			viewDistance: 10
		  }
		},
		title: {
		  text: "' . $title . '"
		},
		xAxis: {
		  categories: [' . $labelsets . '],
		  title: {
			text: \'Employees\'
		  }
		},
		yAxis: {
		  title: {
			margin: 20,
			text: "' . $value_label . '"
		  }
		},
		plotOptions: {
		  series: {
			depth: 25,
			colorByPoint: true
		  }
		},
		series: [{
		  data: [' . $valuesets . '],
		  name: "' . $value_label . '",
		  showInLegend: false
		}]
	  });
	</script>';
	}
	if ($type == "column-3d") {
		$valuesets = '';
		$labelsets = '';
		foreach ($chartData as $key => $data) {
			# code...
			$labelsets .= '"' . $data['label'] . '",';
			$valuesets .= '' . $data['value'] . ',';
		}
		//if empty datasets then return show alert box
		if (empty($valuesets)) {
			$html = showAlert('danger', 'Oops!', 'No data found for this chart ' . $title);
			return $html;
			exit;
		}
		$script .= '<script>
	const chart = new Highcharts.Chart({
		chart: {
		  renderTo: "' . $chartID . '",
		  type: \'column\',
		  options3d: {
			enabled: true,
			alpha: 0,
			beta: 346,
			depth: 100,
			viewDistance: 10
		  }
		},
		xAxis: {
		  categories: [' . $labelsets . ']
		},
		yAxis: {
			title: {
				margin: 20,
				text: "' . $value_label . '"
			  }
		},
		tooltip: {
			pointFormat: \'' . $value_label . ': {point.y}\'
		  },
		title: {
		  text: "' . $title . '",
		  align: \'left\'
		},
		legend: {
		  enabled: false
		},
		plotOptions: {
		  column: {
			depth: 25
		  }
		},
		series: [{
		  data: [' . $valuesets . '],
		  colorByPoint: true
		}]
	  });
	</script>';
	}

	$html = showDataCard($title, $script, $cardcolor, $cardsize, $cardoutline, $collapsed);

	return $html;
}

/**
 * Summary of showCalendar
 * @param mixed $title
 * @param mixed $calendar_query
 * @param mixed $card_color
 * @param mixed $card_size
 * @param mixed $card_outline
 * @param mixed $card_collapsed
 * @param mixed $calendar_settings
 * @param mixed $event_action_code
 * @return string
 */
function showCalendar(
	$title = "My Calendar",
	$calendar_query = '',
	$card_color = "dark",
	$card_size = "col-md-12",
	$card_outline = "Yes",
	$card_collapsed = "No",
	$calendar_settings = [
		'theme' => 'Midnight Blue',
		'todayHighlight' => true,
		'format' => 'MM dd, yyyy',
		'titleFormat' => 'MM yyyy',
		'eventHeaderFormat' => 'MM dd',
		'firstDayOfWeek' => 1,
		'sidebarDisplayDefault' => true,
		'sidebarToggler' => true,
		'eventDisplayDefault' => true,
		'eventListToggler' => true,
	],
	$event_action_code = ''
) {
	$calendarID = uniqid();
	$getcalendarEvents = sql($calendar_query, $eo);
	$allEvents = array();
	foreach ($getcalendarEvents as $calendarEvent) {
		$id = $calendarEvent['id'] ? $calendarEvent['id'] : mt_rand();
		$name = $calendarEvent['name'] ? $calendarEvent['name'] : 'event name';
		$type = $calendarEvent['type'] ? $calendarEvent['type'] : 'event';
		$is_range = $calendarEvent['is_range'] ? $calendarEvent['is_range'] : false;
		$color = $calendarEvent['color'] ? $calendarEvent['color'] : '';
		$badge = $calendarEvent['badge'] ? $calendarEvent['badge'] : '';
		$everyYear = $calendarEvent['everyYear'] ? $calendarEvent['everyYear'] : false;
		$description = $calendarEvent['description'] ? $calendarEvent['description'] : '';
		if ($is_range == true) {
			$start_date = $calendarEvent['start_date'] ? $calendarEvent['start_date'] : '';
			$end_date = $calendarEvent['end_date'] ? $calendarEvent['end_date'] : '';
			$date = [$start_date, $end_date];
		} else {
			$date = $calendarEvent['date'] ? $calendarEvent['date'] : '';
		}
		$allEvents[] = array(
			'id' => $id,
			'name' => $name,
			'type' => $type,
			'date' => $date,
			'color' => $color,
			'badge' => $badge,
			'everyYear' => $everyYear,
			'description' => $description
		);
	}
	$calendar_body = showDataCard($title, '<div id="' . $calendarID . '"></div>', $card_color, $card_size, $card_outline, $card_collapsed);
	$calendar_body .= '<script>
	// initialize your calendar, once the pages DOM is ready
	$(document).ready(function () {
	  $(\'#' . $calendarID . '\').evoCalendar({
		theme: \'' . $calendar_settings['theme'] . '\',
		todayHighlight: ' . $calendar_settings['todayHighlight'] . ',
		format: \'' . $calendar_settings['format'] . '\',
		titleFormat: \'' . $calendar_settings['titleFormat'] . '\',
		eventHeaderFormat: \'' . $calendar_settings['eventHeaderFormat'] . '\',
		firstDayOfWeek: ' . $calendar_settings['firstDayOfWeek'] . ' ,
		sidebarDisplayDefault: ' . $calendar_settings['sidebarDisplayDefault'] . ',
		sidebarToggler: ' . $calendar_settings['sidebarToggler'] . ',
		eventDisplayDefault: ' . $calendar_settings['eventDisplayDefault'] . ',
		eventListToggler: ' . $calendar_settings['eventListToggler'] . ',
		calendarEvents: ' . json_encode($allEvents) . '
	  }).on(\'selectEvent\', function(event, activeEvent) {
		//event variables made available for use after event clicked
		const eventID = activeEvent.id;
		const eventName=  activeEvent.name;
		const eventType=  activeEvent.type;
		const eventDate=  activeEvent.date;
		const eventColor=  activeEvent.color;
		const eventBadge=  activeEvent.badge;
		const eventEveryYear=  activeEvent.everyYear;
		const eventDescription=  activeEvent.description;
		//your custom event logic passed in from the function, should be js code.
		' . $event_action_code . '
	});
	})
  </script>';
	return $calendar_body;
}

/**
 * Summary of set_option
 * @param mixed $name
 * @param mixed $value
 * @return bool
 */
function set_option($name, $value)
{
	$sql = "INSERT INTO alte_options (option_name, option_value) VALUES ('$name', '$value') ON DUPLICATE KEY UPDATE option_value='$value'";

	if (sql($sql, $eo) === TRUE) {
		return true;
	} else {
		return false;
	}
}

/**
 * Summary of get_option
 * @param mixed $name
 * @return mixed
 */
function get_option($name)
{
	$sql = "SELECT option_value FROM alte_options WHERE option_name='$name'";
	$result = json_decode(sqlValue($sql), true);
	return (json_last_error() === JSON_ERROR_NONE) ? $result : sqlValue($sql);
}

/**
 * Summary of delete_option
 * @param mixed $name
 * @return bool
 */
function delete_option($name)
{
	$sql = "DELETE FROM alte_options WHERE option_name='$name'";
	if (sql($sql, $eo) === TRUE) {
		return true;
	} else {
		return false;
	}
}

/**
 * Summary of getjsonFiles
 * @param mixed $dir
 * @return array|bool
 */
function getjsonFiles($dir)
{
	$jsonFiles = glob($dir . '/*.json');
	return $jsonFiles;
}

/**
 * showDropZone function to create drop zone file upload, callable inside a form
 *
 * @param array $field_configs an array of field configurations
 * @return string the generated function comment
 */
function showDropZone($field_configs = [])
{
    $code = '
    <script src="appginilte/plugins/dropzone/dropzone.js"></script>
    <link href="appginilte/plugins/dropzone/dropzone.css" rel="stylesheet" type="text/css" />
    <style> 
        .dropzone { border: 2px dashed #ccc; border-radius: 5px; padding: 20px; text-align: center; cursor: pointer; } 
        .dropzone:hover { border-color: #007bff; } 
        .dropzone .dz-remove { color: red; } 
    </style>
    <div class="row">';

    foreach ($field_configs as $config) {
        $field_name = $config['field_name'];
        $dictDefaultMessage = $config['dictDefaultMessage'];
        $dictRemoveFile = $config['dictRemoveFile'];
        $maxFiles = $config['maxFiles'];
        $maxFilesize = $config['maxFilesize'];
        $acceptedFiles = $config['acceptedFiles'];
		$sizeClass= $config["sizeClass"] ?? "col-md-6";

        $code .= '
        <div class="' . $sizeClass . '">
            <!-- Add a div for Dropzone -->
            <label>' . $config['label_name'] . '</label>
            <div class="dropzone" id="' . $field_name . '"></div>
        </div>
        <input type="hidden" name="' . $field_name . '">';
    }

    $code .= '</div>
    <script>
		const fileLinks = {};
        const uploadURL = "appginilte_file-upload.php";
        // Initialize Dropzones
        Dropzone.autoDiscover = false;';

    foreach ($field_configs as $config) {
        $field_name = $config['field_name'];

        $code .= '
		// Initialize an array to store file_link values
		fileLinks["' . $field_name . '"] = [];
        const myDropzone_' . $field_name . ' = new Dropzone("#' . $field_name . '", {
            url: uploadURL,
            acceptedFiles: "' . $acceptedFiles . '",
            maxFilesize: ' . $maxFilesize . ',
            addRemoveLinks: true,
            dictDefaultMessage: "' . $dictDefaultMessage . '",
            dictRemoveFile: "' . $dictRemoveFile . '",
            clickable: true,
            maxFiles: ' . $maxFiles . ',
        });

        // Attach event handlers for each Dropzone
        setupDropzoneEventHandlers(myDropzone_' . $field_name . ', "' . $field_name . '");';
    }

    $code .= '
        // Common event handling function
        function setupDropzoneEventHandlers(dropzone, fieldName) {
            dropzone.on("success", (file, response) => {
                let responseObject = JSON.parse(response);
				fileLinks[fieldName].push(responseObject.file_link);
                updateHiddenInput(fieldName);
            });

            dropzone.on("removedfile", (file) => {
                let fileName = file.name;
                deleteFileOnServer(fileName, fieldName);
            });
        }

        function updateHiddenInput(fieldName) {
			const updateHiddenInputVariables = {};
            updateHiddenInputVariables[fieldName] = document.querySelector(\'input[name="\' + fieldName + \'"]\');
            if (updateHiddenInputVariables[fieldName]) {
                updateHiddenInputVariables[fieldName].value = fileLinks[fieldName].join(\',\');
            }
        }

        function deleteFileOnServer(fileName, fieldName) {
			const deleteFileOnServerVariables = {};
            $.ajax({
                type: "POST",
                url: uploadURL,
                data: {
                    target_file: fileName,
                    delete_file: 1
                },
                success: function(response) {
					deleteFileOnServerVariables[fieldName] = $("input[name=\'" + fieldName + "\']"),
                        filesArray = deleteFileOnServerVariables[fieldName].val().split(",");
                    filesArray = filesArray.filter(function(item) {
                        return item.indexOf(fileName) === -1;
                    });
					deleteFileOnServerVariables[fieldName].val(filesArray.length > 0 ? filesArray.join(",") : "");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(\'Error:\', errorThrown);
                }
            });
        }
    </script>';

    return $code;
}