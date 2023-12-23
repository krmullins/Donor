<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");

$theParentTable = isset($_POST['theParentTable'])  ? $_POST['theParentTable'] : "";
$tcols = array();
$tblfls = get_table_fields($theParentTable);
foreach ($tblfls as $key => $value) {
    # code...
    $colvalue = $key;
    $label = $value['info']['caption'];
    $tcols []= array('id'=>$colvalue,'name'=>$label);
}
echo json_encode($tcols);
