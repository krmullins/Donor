<!-- DetailView Code Inserted By AdminLTE Plugin For Appgini -->
	<?php $tableName = $x->TableName; $group = getMemberInfo()['group']; $formatgroup = str_replace(' ', '_', $group); $perms = file_get_contents('appginilte/group_perms/' . $formatgroup . '_' . $tableName . '-dv.json'); if ($perms) { $extract = json_decode($perms, true); $readonly_fields = explode(",", $extract['readonlyfields_' . $tableName]); $readonly_fields_count = count($readonly_fields); $readonly_fields_string = ""; for ($i = 0; $i < $readonly_fields_count; $i++) { $readonly_fields_string .= "'" . $readonly_fields[$i] . "',"; } $readonly_fields_string = rtrim($readonly_fields_string, ","); $readonly_fields_string = "[" . $readonly_fields_string . "]"; $hidden_fields = explode(",", $extract['hiddenfields_' . $tableName]); $hidden_fields_count = count($hidden_fields); $hidden_fields_string = ""; for ($i = 0; $i < $hidden_fields_count; $i++) { $hidden_fields_string .= "'" . $hidden_fields[$i] . "',"; } $hidden_fields_string = rtrim($hidden_fields_string, ","); $hidden_fields_string = "[" . $hidden_fields_string . "]"; if(!empty($extract['readonlyfields_' . $tableName])){ echo "<script>var readonly_fields = " . $readonly_fields_string . ";</script>"; } else{ echo "<script>var readonly_fields = [];</script>"; } if(!empty( $extract['hiddenfields_' . $tableName])){ echo "<script>var hidden_fields = " . $hidden_fields_string . "; var tableName = '" . $tableName . "';</script>"; } else{ echo "<script>var hidden_fields = []; var tableName = '" . $tableName . "';</script>"; } } else { echo "<script>var readonly_fields=[]; var hidden_fields=[]; var tableName = '" . $tableName . "';</script>"; } ?>
	<!-- DetailView Code Inserted By AdminLTE Plugin For Appgini -->
<!-- TableActions Code inserted by AdminLTE Plugin For Appgini_v_05.20 -->
	<?php $tableName = $x->TableName; $groupName = getMemberInfo()['group']; $getTableData = sqlValue("SELECT option_value FROM alte_options WHERE option_name='table_actions_{$tableName}'"); if ($getTableData) { $decode = json_decode($getTableData, true); $tableactions = $decode['tableactions_' . $tableName] ? $decode['tableactions_' . $tableName] : 0; $access_to_action_view = explode(",", $decode['access_to_action_view_' . $tableName]); $access_to_action_view_count = count($access_to_action_view); $access_to_action_view_string = ""; for ($i = 0; $i < $access_to_action_view_count; $i++) { $access_to_action_view_string .= "'" . $access_to_action_view[$i] . "',"; } $access_to_action_view_string = rtrim($access_to_action_view_string, ","); $access_to_action_view_string = "[" . $access_to_action_view_string . "]"; $access_to_action_edit = explode(",", $decode['access_to_action_edit_'. $tableName]); $access_to_action_edit_count = count($access_to_action_edit); $access_to_action_edit_string = ""; for ($i = 0; $i < $access_to_action_edit_count; $i++) { $access_to_action_edit_string.= "'". $access_to_action_edit[$i]. "',"; } $access_to_action_edit_string = rtrim($access_to_action_edit_string, ","); $access_to_action_edit_string = "[". $access_to_action_edit_string. "]"; $access_to_action_delete = explode(",", $decode['access_to_action_delete_'. $tableName]); $access_to_action_delete_count = count($access_to_action_delete); $access_to_action_delete_string = ""; for ($i = 0; $i < $access_to_action_delete_count; $i++) { $access_to_action_delete_string.= "'". $access_to_action_delete[$i]. "',"; } $access_to_action_delete_string = rtrim($access_to_action_delete_string, ","); $access_to_action_delete_string = "[". $access_to_action_delete_string. "]"; echo "<script> var access_to_action_view=" . $access_to_action_view_string . "; var access_to_action_edit=". $access_to_action_edit_string. "; var access_to_action_delete=". $access_to_action_delete_string. "; var TAenabled=" . $tableactions . "; var tableName = '" . $tableName . "'; var groupName= '". $groupName. "'; </script>"; } else { echo "<script> var access_to_action_view=[]; var access_to_action_edit=[]; var access_to_action_delete=[]; var TAenabled=0; var tableName = '" . $tableName . "'; var groupName= '". $groupName. "'; </script>"; } ?>
	<!-- TableActions Code inserted by AdminLTE Plugin For Appgini_v_05.20-->
<?php include PREPEND_PATH."hooks/appginilte_otp.php"; check_otp(); ?>
<?php

?>
<script src="hooks/AppGiniHelper.min.js"></script>
<script>
    var common = AppGiniHelper.getCommon();
    common.setTitle("<b>DonorSoft</b>");
    common.setIcon("gift");

</script>

<script>
    var lv = AppGiniHelper.LV;
    if (lv != null) {
        lv.setBackgroundGradient("whitesmoke", "silver")
            .setVariation(Variation.primary) // .success .danger .warning .info
            .setIcons("user", "lock")
            .removeLostPassword()
            .removeRememberMe()
            .removeFooter()
            .center();
    }
</script>