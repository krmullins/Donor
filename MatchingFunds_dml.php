<?php

// Data functions (insert, update, delete, form) for table MatchingFunds

// This script and data application were generated by AppGini 23.17
// Download AppGini for free from https://bigprof.com/appgini/download/

function MatchingFunds_insert(&$error_message = '') {
	global $Translation;

	// mm: can member insert record?
	$arrPerm = getTablePermissions('MatchingFunds');
	if(!$arrPerm['insert']) return false;

	$data = [
		'OrganizationName' => Request::val('OrganizationName', ''),
		'Amount' => Request::val('Amount', ''),
		'SupporterID' => Request::lookup('SupporterID', ''),
		'DateSubmitted' => mysql_datetime(Request::val('DateSubmitted', '')),
		'DateReceived' => mysql_datetime(Request::val('DateReceived', '')),
		'Notes' => Request::val('Notes', ''),
		'DonationID' => Request::lookup('DonationID', ''),
		'Attachment' => Request::fileUpload('Attachment', [
			'maxSize' => 8192000,
			'types' => 'txt|doc|docx|docm|odt|pdf|rtf',
			'noRename' => false,
			'dir' => '',
			'success' => function($name, $selected_id) {
			},
			'failure' => function($selected_id, $fileRemoved) {
				if(!strlen(Request::val('SelectedID'))) return '';

				/* for empty upload fields, when saving a copy of an existing record, copy the original upload field */
				return existing_value('MatchingFunds', 'Attachment', Request::val('SelectedID'));
			},
		]),
	];


	// hook: MatchingFunds_before_insert
	if(function_exists('MatchingFunds_before_insert')) {
		$args = [];
		if(!MatchingFunds_before_insert($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$error = '';
	// set empty fields to NULL
	$data = array_map(function($v) { return ($v === '' ? NULL : $v); }, $data);
	insert('MatchingFunds', backtick_keys_once($data), $error);
	if($error) {
		$error_message = $error;
		return false;
	}

	$recID = db_insert_id(db_link());

	update_calc_fields('MatchingFunds', $recID, calculated_fields()['MatchingFunds']);

	// hook: MatchingFunds_after_insert
	if(function_exists('MatchingFunds_after_insert')) {
		$res = sql("SELECT * FROM `MatchingFunds` WHERE `ID`='" . makeSafe($recID, false) . "' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) {
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args = [];
		if(!MatchingFunds_after_insert($data, getMemberInfo(), $args)) { return $recID; }
	}

	// mm: save ownership data
	set_record_owner('MatchingFunds', $recID, getLoggedMemberID());

	// if this record is a copy of another record, copy children if applicable
	if(strlen(Request::val('SelectedID'))) MatchingFunds_copy_children($recID, Request::val('SelectedID'));

	return $recID;
}

function MatchingFunds_copy_children($destination_id, $source_id) {
	global $Translation;
	$requests = []; // array of curl handlers for launching insert requests
	$eo = ['silentErrors' => true];
	$safe_sid = makeSafe($source_id);

	// launch requests, asynchronously
	curl_batch($requests);
}

function MatchingFunds_delete($selected_id, $AllowDeleteOfParents = false, $skipChecks = false) {
	// insure referential integrity ...
	global $Translation;
	$selected_id = makeSafe($selected_id);

	// mm: can member delete record?
	if(!check_record_permission('MatchingFunds', $selected_id, 'delete')) {
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: MatchingFunds_before_delete
	if(function_exists('MatchingFunds_before_delete')) {
		$args = [];
		if(!MatchingFunds_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'] . (
				!empty($args['error_message']) ?
					'<div class="text-bold">' . strip_tags($args['error_message']) . '</div>'
					: '' 
			);
	}

	// delete file stored in the 'Attachment' field
	$res = sql("SELECT `Attachment` FROM `MatchingFunds` WHERE `ID`='{$selected_id}'", $eo);
	if($row = @db_fetch_row($res)) {
		if($row[0] != '') {
			@unlink(getUploadDir('') . $row[0]);
		}
	}

	sql("DELETE FROM `MatchingFunds` WHERE `ID`='{$selected_id}'", $eo);

	// hook: MatchingFunds_after_delete
	if(function_exists('MatchingFunds_after_delete')) {
		$args = [];
		MatchingFunds_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("DELETE FROM `membership_userrecords` WHERE `tableName`='MatchingFunds' AND `pkValue`='{$selected_id}'", $eo);
}

function MatchingFunds_update(&$selected_id, &$error_message = '') {
	global $Translation;

	// mm: can member edit record?
	if(!check_record_permission('MatchingFunds', $selected_id, 'edit')) return false;

	$data = [
		'OrganizationName' => Request::val('OrganizationName', ''),
		'Amount' => Request::val('Amount', ''),
		'SupporterID' => Request::lookup('SupporterID', ''),
		'DateSubmitted' => mysql_datetime(Request::val('DateSubmitted', '')),
		'DateReceived' => mysql_datetime(Request::val('DateReceived', '')),
		'Notes' => Request::val('Notes', ''),
		'DonationID' => Request::lookup('DonationID', ''),
		'Attachment' => Request::fileUpload('Attachment', [
			'maxSize' => 8192000,
			'types' => 'txt|doc|docx|docm|odt|pdf|rtf',
			'noRename' => false,
			'dir' => '',
			'id' => $selected_id,
			'success' => function($name, $selected_id) {
			},
			'removeOnSuccess' => true,
			'removeOnRequest' => true,
			'remove' => function($selected_id) {
				// delete old file from server
				$oldFile = existing_value('MatchingFunds', 'Attachment', $selected_id);
				if(!$oldFile) return;

				@unlink(getUploadDir('') . $oldFile);
			},
			'failure' => function($selected_id, $fileRemoved) {
				if($fileRemoved) return '';
				return existing_value('MatchingFunds', 'Attachment', $selected_id);
			},
		]),
	];

	// get existing values
	$old_data = getRecord('MatchingFunds', $selected_id);
	if(is_array($old_data)) {
		$old_data = array_map('makeSafe', $old_data);
		$old_data['selectedID'] = makeSafe($selected_id);
	}

	$data['selectedID'] = makeSafe($selected_id);

	// hook: MatchingFunds_before_update
	if(function_exists('MatchingFunds_before_update')) {
		$args = ['old_data' => $old_data];
		if(!MatchingFunds_before_update($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$set = $data; unset($set['selectedID']);
	foreach ($set as $field => $value) {
		$set[$field] = ($value !== '' && $value !== NULL) ? $value : NULL;
	}

	if(!update(
		'MatchingFunds', 
		backtick_keys_once($set), 
		['`ID`' => $selected_id], 
		$error_message
	)) {
		echo $error_message;
		echo '<a href="MatchingFunds_view.php?SelectedID=' . urlencode($selected_id) . "\">{$Translation['< back']}</a>";
		exit;
	}


	$eo = ['silentErrors' => true];

	update_calc_fields('MatchingFunds', $data['selectedID'], calculated_fields()['MatchingFunds']);

	// hook: MatchingFunds_after_update
	if(function_exists('MatchingFunds_after_update')) {
		$res = sql("SELECT * FROM `MatchingFunds` WHERE `ID`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) $data = array_map('makeSafe', $row);

		$data['selectedID'] = $data['ID'];
		$args = ['old_data' => $old_data];
		if(!MatchingFunds_after_update($data, getMemberInfo(), $args)) return;
	}

	// mm: update ownership data
	sql("UPDATE `membership_userrecords` SET `dateUpdated`='" . time() . "' WHERE `tableName`='MatchingFunds' AND `pkValue`='" . makeSafe($selected_id) . "'", $eo);
}

function MatchingFunds_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $separateDV = 0, $TemplateDV = '', $TemplateDVP = '') {
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;
	$eo = ['silentErrors' => true];
	$noUploads = null;
	$row = $urow = $jsReadOnly = $jsEditable = $lookups = null;

	$noSaveAsCopy = false;

	// mm: get table permissions
	$arrPerm = getTablePermissions('MatchingFunds');
	if(!$arrPerm['insert'] && $selected_id == '')
		// no insert permission and no record selected
		// so show access denied error unless TVDV
		return $separateDV ? $Translation['tableAccessDenied'] : '';
	$AllowInsert = ($arrPerm['insert'] ? true : false);
	// print preview?
	$dvprint = false;
	if(strlen($selected_id) && Request::val('dvprint_x') != '') {
		$dvprint = true;
	}

	$filterer_SupporterID = Request::val('filterer_SupporterID');
	$filterer_DonationID = Request::val('filterer_DonationID');

	// populate filterers, starting from children to grand-parents
	if($filterer_DonationID && !$filterer_SupporterID) $filterer_SupporterID = sqlValue("select SupporterID from Donations where ID='" . makeSafe($filterer_DonationID) . "'");

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: SupporterID
	$combo_SupporterID = new DataCombo;
	// combobox: DonationID, filterable by: SupporterID
	$combo_DonationID = new DataCombo;

	if($selected_id) {
		if(!check_record_permission('MatchingFunds', $selected_id, 'view'))
			return $Translation['tableAccessDenied'];

		// can edit?
		$AllowUpdate = check_record_permission('MatchingFunds', $selected_id, 'edit');

		// can delete?
		$AllowDelete = check_record_permission('MatchingFunds', $selected_id, 'delete');

		$res = sql("SELECT * FROM `MatchingFunds` WHERE `ID`='" . makeSafe($selected_id) . "'", $eo);
		if(!($row = db_fetch_array($res))) {
			return error_message($Translation['No records found'], 'MatchingFunds_view.php', false);
		}
		$combo_SupporterID->SelectedData = $row['SupporterID'];
		$combo_DonationID->SelectedData = $row['DonationID'];
		$urow = $row; /* unsanitized data */
		$row = array_map('safe_html', $row);
	} else {
		$filterField = Request::val('FilterField');
		$filterOperator = Request::val('FilterOperator');
		$filterValue = Request::val('FilterValue');
		$combo_SupporterID->SelectedData = $filterer_SupporterID;
		$combo_DonationID->SelectedData = $filterer_DonationID;
	}
	$combo_SupporterID->HTML = '<span id="SupporterID-container' . $rnd1 . '"></span><input type="hidden" name="SupporterID" id="SupporterID' . $rnd1 . '" value="' . html_attr($combo_SupporterID->SelectedData) . '">';
	$combo_SupporterID->MatchText = '<span id="SupporterID-container-readonly' . $rnd1 . '"></span><input type="hidden" name="SupporterID" id="SupporterID' . $rnd1 . '" value="' . html_attr($combo_SupporterID->SelectedData) . '">';
	$combo_DonationID->HTML = '<span id="DonationID-container' . $rnd1 . '"></span><input type="hidden" name="DonationID" id="DonationID' . $rnd1 . '" value="' . html_attr($combo_DonationID->SelectedData) . '">';
	$combo_DonationID->MatchText = '<span id="DonationID-container-readonly' . $rnd1 . '"></span><input type="hidden" name="DonationID" id="DonationID' . $rnd1 . '" value="' . html_attr($combo_DonationID->SelectedData) . '">';

	ob_start();
	?>

	<script>
		// initial lookup values
		AppGini.current_SupporterID__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['SupporterID'] : htmlspecialchars($filterer_SupporterID, ENT_QUOTES)); ?>"};
		AppGini.current_DonationID__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['DonationID'] : htmlspecialchars($filterer_DonationID, ENT_QUOTES)); ?>"};

		jQuery(function() {
			setTimeout(function() {
				if(typeof(SupporterID_reload__RAND__) == 'function') SupporterID_reload__RAND__();
				<?php echo (!$AllowUpdate || $dvprint ? 'if(typeof(DonationID_reload__RAND__) == \'function\') DonationID_reload__RAND__(AppGini.current_SupporterID__RAND__.value);' : ''); ?>
			}, 50); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
		function SupporterID_reload__RAND__() {
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint) { ?>

			$j("#SupporterID-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c) {
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_SupporterID__RAND__.value, t: 'MatchingFunds', f: 'SupporterID' },
						success: function(resp) {
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="SupporterID"]').val(resp.results[0].id);
							$j('[id=SupporterID-container-readonly__RAND__]').html('<span class="match-text" id="SupporterID-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=Supporters_view_parent]').hide(); } else { $j('.btn[id=Supporters_view_parent]').show(); }

						if(typeof(DonationID_reload__RAND__) == 'function') DonationID_reload__RAND__(AppGini.current_SupporterID__RAND__.value);

							if(typeof(SupporterID_update_autofills__RAND__) == 'function') SupporterID_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term) { return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) { return { s: term, p: page, t: 'MatchingFunds', f: 'SupporterID' }; },
					results: function(resp, page) { return resp; }
				},
				escapeMarkup: function(str) { return str; }
			}).on('change', function(e) {
				AppGini.current_SupporterID__RAND__.value = e.added.id;
				AppGini.current_SupporterID__RAND__.text = e.added.text;
				$j('[name="SupporterID"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=Supporters_view_parent]').hide(); } else { $j('.btn[id=Supporters_view_parent]').show(); }

						if(typeof(DonationID_reload__RAND__) == 'function') DonationID_reload__RAND__(AppGini.current_SupporterID__RAND__.value);

				if(typeof(SupporterID_update_autofills__RAND__) == 'function') SupporterID_update_autofills__RAND__();
			});

			if(!$j("#SupporterID-container__RAND__").length) {
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_SupporterID__RAND__.value, t: 'MatchingFunds', f: 'SupporterID' },
					success: function(resp) {
						$j('[name="SupporterID"]').val(resp.results[0].id);
						$j('[id=SupporterID-container-readonly__RAND__]').html('<span class="match-text" id="SupporterID-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=Supporters_view_parent]').hide(); } else { $j('.btn[id=Supporters_view_parent]').show(); }

						if(typeof(SupporterID_update_autofills__RAND__) == 'function') SupporterID_update_autofills__RAND__();
					}
				});
			}

		<?php } else { ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_SupporterID__RAND__.value, t: 'MatchingFunds', f: 'SupporterID' },
				success: function(resp) {
					$j('[id=SupporterID-container__RAND__], [id=SupporterID-container-readonly__RAND__]').html('<span class="match-text" id="SupporterID-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=Supporters_view_parent]').hide(); } else { $j('.btn[id=Supporters_view_parent]').show(); }

					if(typeof(SupporterID_update_autofills__RAND__) == 'function') SupporterID_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function DonationID_reload__RAND__(filterer_SupporterID) {
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint) { ?>

			$j("#DonationID-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c) {
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { filterer_SupporterID: filterer_SupporterID, id: AppGini.current_DonationID__RAND__.value, t: 'MatchingFunds', f: 'DonationID' },
						success: function(resp) {
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="DonationID"]').val(resp.results[0].id);
							$j('[id=DonationID-container-readonly__RAND__]').html('<span class="match-text" id="DonationID-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=Donations_view_parent]').hide(); } else { $j('.btn[id=Donations_view_parent]').show(); }


							if(typeof(DonationID_update_autofills__RAND__) == 'function') DonationID_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term) { return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) { return { filterer_SupporterID: filterer_SupporterID, s: term, p: page, t: 'MatchingFunds', f: 'DonationID' }; },
					results: function(resp, page) { return resp; }
				},
				escapeMarkup: function(str) { return str; }
			}).on('change', function(e) {
				AppGini.current_DonationID__RAND__.value = e.added.id;
				AppGini.current_DonationID__RAND__.text = e.added.text;
				$j('[name="DonationID"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=Donations_view_parent]').hide(); } else { $j('.btn[id=Donations_view_parent]').show(); }


				if(typeof(DonationID_update_autofills__RAND__) == 'function') DonationID_update_autofills__RAND__();
			});

			if(!$j("#DonationID-container__RAND__").length) {
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_DonationID__RAND__.value, t: 'MatchingFunds', f: 'DonationID' },
					success: function(resp) {
						$j('[name="DonationID"]').val(resp.results[0].id);
						$j('[id=DonationID-container-readonly__RAND__]').html('<span class="match-text" id="DonationID-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=Donations_view_parent]').hide(); } else { $j('.btn[id=Donations_view_parent]').show(); }

						if(typeof(DonationID_update_autofills__RAND__) == 'function') DonationID_update_autofills__RAND__();
					}
				});
			}

		<?php } else { ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_DonationID__RAND__.value, t: 'MatchingFunds', f: 'DonationID' },
				success: function(resp) {
					$j('[id=DonationID-container__RAND__], [id=DonationID-container-readonly__RAND__]').html('<span class="match-text" id="DonationID-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=Donations_view_parent]').hide(); } else { $j('.btn[id=Donations_view_parent]').show(); }

					if(typeof(DonationID_update_autofills__RAND__) == 'function') DonationID_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_clean());


	// code for template based detail view forms

	// open the detail view template
	if($dvprint) {
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/MatchingFunds_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	} else {
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/MatchingFunds_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Matching Funds details', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', (Request::val('Embedded') ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert) {
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return MatchingFunds_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return MatchingFunds_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	} else {
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if(Request::val('Embedded')) {
		$backAction = 'AppGini.closeParentModal(); return false;';
	} else {
		$backAction = '$j(\'form\').eq(0).attr(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;';
	}

	if($selected_id) {
		if(!Request::val('Embedded')) $templateCode = str_replace('<%%DVPRINT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="dvprint" name="dvprint_x" value="1" onclick="$j(\'form\').eq(0).prop(\'novalidate\', true); document.myform.reset(); return true;" title="' . html_attr($Translation['Print Preview']) . '"><i class="glyphicon glyphicon-print"></i> ' . $Translation['Print Preview'] . '</button>', $templateCode);
		if($AllowUpdate)
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return MatchingFunds_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		else
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);

		if($AllowDelete)
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		else
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);

		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	} else {
		$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);

		// if not in embedded mode and user has insert only but no view/update/delete,
		// remove 'back' button
		if(
			$arrPerm['insert']
			&& !$arrPerm['update'] && !$arrPerm['delete'] && !$arrPerm['view']
			&& !Request::val('Embedded')
		)
			$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '', $templateCode);
		elseif($separateDV)
			$templateCode = str_replace(
				'<%%DESELECT_BUTTON%%>', 
				'<button
					type="submit" 
					class="btn btn-default" 
					id="deselect" 
					name="deselect_x" 
					value="1" 
					onclick="' . $backAction . '" 
					title="' . html_attr($Translation['Back']) . '">
						<i class="glyphicon glyphicon-chevron-left"></i> ' .
						$Translation['Back'] .
				'</button>',
				$templateCode
			);
		else
			$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '', $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(($selected_id && !$AllowUpdate && !$AllowInsert) || (!$selected_id && !$AllowInsert)) {
		$jsReadOnly = '';
		$jsReadOnly .= "\tjQuery('#OrganizationName').replaceWith('<div class=\"form-control-static\" id=\"OrganizationName\">' + (jQuery('#OrganizationName').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#Amount').replaceWith('<div class=\"form-control-static\" id=\"Amount\">' + (jQuery('#Amount').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#SupporterID').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#SupporterID_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#DateSubmitted').parents('.input-group').replaceWith('<div class=\"form-control-static\" id=\"DateSubmitted\">' + (jQuery('#DateSubmitted').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#DateReceived').parents('.input-group').replaceWith('<div class=\"form-control-static\" id=\"DateReceived\">' + (jQuery('#DateReceived').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#DonationID').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#DonationID_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#Attachment').replaceWith('<div class=\"form-control-static\" id=\"Attachment\">' + (jQuery('#Attachment').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#Attachment, #Attachment-edit-link').hide();\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	} elseif($AllowInsert) {
		$jsEditable = "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
		$locale = isset($Translation['datetimepicker locale']) ? ", locale: '{$Translation['datetimepicker locale']}'" : '';
		$jsEditable .= "\tjQuery('#DateSubmitted').addClass('always_shown').parents('.input-group').datetimepicker({ toolbarPlacement: 'top', sideBySide: true, showClear: true, showTodayButton: true, showClose: true, icons: { close: 'glyphicon glyphicon-ok' }, format: AppGini.datetimeFormat('dt') {$locale} });";
		$locale = isset($Translation['datetimepicker locale']) ? ", locale: '{$Translation['datetimepicker locale']}'" : '';
		$jsEditable .= "\tjQuery('#DateReceived').addClass('always_shown').parents('.input-group').datetimepicker({ toolbarPlacement: 'top', sideBySide: true, showClear: true, showTodayButton: true, showClose: true, icons: { close: 'glyphicon glyphicon-ok' }, format: AppGini.datetimeFormat('dt') {$locale} });";
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode = str_replace('<%%COMBO(SupporterID)%%>', $combo_SupporterID->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(SupporterID)%%>', $combo_SupporterID->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(SupporterID)%%>', urlencode($combo_SupporterID->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(DonationID)%%>', $combo_DonationID->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(DonationID)%%>', $combo_DonationID->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(DonationID)%%>', urlencode($combo_DonationID->MatchText), $templateCode);

	/* lookup fields array: 'lookup field name' => ['parent table name', 'lookup field caption'] */
	$lookup_fields = ['SupporterID' => ['Supporters', 'SupporterID'], 'DonationID' => ['Donations', 'DonationID'], ];
	foreach($lookup_fields as $luf => $ptfc) {
		$pt_perm = getTablePermissions($ptfc[0]);

		// process foreign key links
		if($pt_perm['view'] || $pt_perm['edit']) {
			$templateCode = str_replace("<%%PLINK({$luf})%%>", '<button type="button" class="btn btn-default view_parent" id="' . $ptfc[0] . '_view_parent" title="' . html_attr($Translation['View'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-eye-open"></i></button>', $templateCode);
		}

		// if user has insert permission to parent table of a lookup field, put an add new button
		if($pt_perm['insert'] /* && !Request::val('Embedded')*/) {
			$templateCode = str_replace("<%%ADDNEW({$ptfc[0]})%%>", '<button type="button" class="btn btn-default add_new_parent" id="' . $ptfc[0] . '_add_new" title="' . html_attr($Translation['Add New'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-plus text-success"></i></button>', $templateCode);
		}
	}

	// process images
	$templateCode = str_replace('<%%UPLOADFILE(ID)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(OrganizationName)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(Amount)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(SupporterID)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(DateSubmitted)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(DateReceived)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(Notes)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(DonationID)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(Attachment)%%>', ($noUploads ? '' : "<div>{$Translation['upload image']}</div>" . '<input type="file" name="Attachment" id="Attachment" data-filetypes="txt|doc|docx|docm|odt|pdf|rtf" data-maxsize="8192000" style="max-width: calc(100% - 1.5rem);" accept=".txt,.doc,.docx,.docm,.odt,.pdf,.rtf">' . '<i class="text-danger clear-upload hidden pull-right" style="margin-top: -.1em; font-size: large;">&times;</i>'), $templateCode);

	// process values
	if($selected_id) {
		if( $dvprint) $templateCode = str_replace('<%%VALUE(ID)%%>', safe_html($urow['ID']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(ID)%%>', html_attr($row['ID']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ID)%%>', urlencode($urow['ID']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(OrganizationName)%%>', safe_html($urow['OrganizationName']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(OrganizationName)%%>', html_attr($row['OrganizationName']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(OrganizationName)%%>', urlencode($urow['OrganizationName']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(Amount)%%>', safe_html($urow['Amount']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(Amount)%%>', html_attr($row['Amount']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Amount)%%>', urlencode($urow['Amount']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(SupporterID)%%>', safe_html($urow['SupporterID']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(SupporterID)%%>', html_attr($row['SupporterID']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(SupporterID)%%>', urlencode($urow['SupporterID']), $templateCode);
		$templateCode = str_replace('<%%VALUE(DateSubmitted)%%>', app_datetime($row['DateSubmitted'], 'dt'), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(DateSubmitted)%%>', urlencode(app_datetime($urow['DateSubmitted'], 'dt')), $templateCode);
		$templateCode = str_replace('<%%VALUE(DateReceived)%%>', app_datetime($row['DateReceived'], 'dt'), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(DateReceived)%%>', urlencode(app_datetime($urow['DateReceived'], 'dt')), $templateCode);
		if($AllowUpdate || $AllowInsert) {
			$templateCode = str_replace('<%%HTMLAREA(Notes)%%>', '<textarea name="Notes" id="Notes" rows="5">' . safe_html(htmlspecialchars_decode($row['Notes'])) . '</textarea>', $templateCode);
		} else {
			$templateCode = str_replace('<%%HTMLAREA(Notes)%%>', '<div id="Notes" class="form-control-static">' . $row['Notes'] . '</div>', $templateCode);
		}
		$templateCode = str_replace('<%%VALUE(Notes)%%>', nl2br($row['Notes']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Notes)%%>', urlencode($urow['Notes']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(DonationID)%%>', safe_html($urow['DonationID']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(DonationID)%%>', html_attr($row['DonationID']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(DonationID)%%>', urlencode($urow['DonationID']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(Attachment)%%>', safe_html($urow['Attachment']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(Attachment)%%>', html_attr($row['Attachment']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Attachment)%%>', urlencode($urow['Attachment']), $templateCode);
	} else {
		$templateCode = str_replace('<%%VALUE(ID)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(ID)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(OrganizationName)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(OrganizationName)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(Amount)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Amount)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(SupporterID)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(SupporterID)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(DateSubmitted)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(DateSubmitted)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(DateReceived)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(DateReceived)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%HTMLAREA(Notes)%%>', '<textarea name="Notes" id="Notes" rows="5"></textarea>', $templateCode);
		$templateCode = str_replace('<%%VALUE(DonationID)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(DonationID)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(Attachment)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(Attachment)%%>', urlencode(''), $templateCode);
	}

	// process translations
	$templateCode = parseTemplate($templateCode);

	// clear scrap
	$templateCode = str_replace('<%%', '<!-- ', $templateCode);
	$templateCode = str_replace('%%>', ' -->', $templateCode);

	// hide links to inaccessible tables
	if(Request::val('dvprint_x') == '') {
		$templateCode .= "\n\n<script>\$j(function() {\n";
		$arrTables = getTableList();
		foreach($arrTables as $name => $caption) {
			$templateCode .= "\t\$j('#{$name}_link').removeClass('hidden');\n";
			$templateCode .= "\t\$j('#xs_{$name}_link').removeClass('hidden');\n";
		}

		$templateCode .= $jsReadOnly;
		$templateCode .= $jsEditable;

		if(!$selected_id) {
			$templateCode.="\n\tif(document.getElementById('AttachmentEdit')) { document.getElementById('AttachmentEdit').style.display='inline'; }";
			$templateCode.="\n\tif(document.getElementById('AttachmentEditLink')) { document.getElementById('AttachmentEditLink').style.display='none'; }";
		}

		$templateCode.="\n});</script>\n";
	}

	// ajaxed auto-fill fields
	$templateCode .= '<script>';
	$templateCode .= '$j(function() {';


	$templateCode.="});";
	$templateCode.="</script>";
	$templateCode .= $lookups;

	// handle enforced parent values for read-only lookup fields
	$filterField = Request::val('FilterField');
	$filterOperator = Request::val('FilterOperator');
	$filterValue = Request::val('FilterValue');

	// don't include blank images in lightbox gallery
	$templateCode = preg_replace('/blank.gif" data-lightbox=".*?"/', 'blank.gif"', $templateCode);

	// don't display empty email links
	$templateCode=preg_replace('/<a .*?href="mailto:".*?<\/a>/', '', $templateCode);

	/* default field values */
	$rdata = $jdata = get_defaults('MatchingFunds');
	if($selected_id) {
		$jdata = get_joined_record('MatchingFunds', $selected_id);
		if($jdata === false) $jdata = get_defaults('MatchingFunds');
		$rdata = $row;
	}
	$templateCode .= loadView('MatchingFunds-ajax-cache', ['rdata' => $rdata, 'jdata' => $jdata]);

	// hook: MatchingFunds_dv
	if(function_exists('MatchingFunds_dv')) {
		$args = [];
		MatchingFunds_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}