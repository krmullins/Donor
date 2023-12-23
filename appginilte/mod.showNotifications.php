<?php
function showNotifications($msg = '', $class = '', $fadeout = true)
{
	global $Translation;

	if ($error_message = strip_tags(Request::val('error_message'))) {
		$error_message = '<div class="text-bold">' . $error_message . '</div>';
	}

	if (!$msg) { // if no msg, use url to detect message to display
		$msg = $_SESSION['custom_err_msg'];
		$footer = $_SESSION['alert_footer'];
		if ($_REQUEST['record-added-ok'] != '') {
			$msg = ($msg == '') ? $Translation['new record saved'] : $msg;
			unset($_SESSION['custom_err_msg']);
			unset($_SESSION['alert_footer']);
			$class = 'success';
		} elseif ($_REQUEST['record-added-error'] != '') {
			$msg = ($msg == '') ? $Translation['Couldn\'t save the new record'] : $msg;
			unset($_SESSION['custom_err_msg']);
			unset($_SESSION['alert_footer']);
			$class = 'error';
		} elseif ($_REQUEST['record-updated-ok'] != '') {
			$msg = ($msg == '') ? $Translation['record updated'] : $msg;
			unset($_SESSION['custom_err_msg']);
			unset($_SESSION['alert_footer']);
			$class = 'success';
		} elseif ($_REQUEST['record-updated-error'] != '') {
			$msg = ($msg == '') ? $Translation['Couldn\'t save changes to the record'] : $msg;
			unset($_SESSION['custom_err_msg']);
			unset($_SESSION['alert_footer']);
			$class = 'error';
		} elseif ($_REQUEST['record-deleted-ok'] != '') {
			$msg = ($msg == '') ? $Translation['The record has been deleted successfully'] : $msg;
			unset($_SESSION['custom_err_msg']);
			unset($_SESSION['alert_footer']);
			$class = 'success';
		} elseif ($_REQUEST['record-deleted-error'] != '') {
			$msg = ($msg == '') ? $Translation['Couldn\'t delete this record'] : $msg;
			unset($_SESSION['custom_err_msg']);
			unset($_SESSION['alert_footer']);
			$class = 'error';
		} else {
			return '';
		}
	}
	$id = 'notification-' . rand();

	ob_start();
	// notification template
?>
	<script src="appginilte/plugins/sweetalert2/sweetalert2.all.min.js"></script>
	<script type="text/javascript">
		Swal.fire({
			position: 'top',
			icon: '<?php echo $class ?>',
			title: '<?php echo ($class == "success") ? "Yeey!" : "Oops!"; ?>',
			text: '<?php echo $msg ?>',
			footer: '<?php echo $footer ??''; ?>',
			showConfirmButton: true,
			width: '50%',
		});
	</script>
<?php
	$out = ob_get_clean();
	return $out;
}
