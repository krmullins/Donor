<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
include 'appginilte_header.php';
if ($group !== "Admins") {
    header('location: index.php');
} else {
    if ($isSuperAdmin == 'No' && $userareapems == 0) {
        header('location: index.php');
    }
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo $appName; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php"><?php echo $translate['dashboard']; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo $translate['user_area_permissions']; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-sliders-h"></i>
                            <?php echo $translate['groups_user_area_permissions']; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                    <?php echo $translate['user_area_permissions_description']; ?>
                        <!-- Card Tabs -->
                        <div class="row">
                            <div class="col-12">
                                <!-- Custom Tabs -->
                                <div class="card">
                                    <div class="card-header d-flex p-0">
                                        <h3 class="card-title p-3">  <?php echo $translate['groups_user_area_permissions']; ?></h3>
                                        <ul class="nav nav-pills ml-auto p-2">
                                            <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab"><span class="fas fa-table"></span> <?php echo $translate['table_view']; ?></a></li>
                                            <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab"><span class="far fa-list-alt"></span> <?php echo $translate['detail_view']; ?></a></li>
                                        </ul>
                                    </div><!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_1">
                                                <h3><?php echo $translate['table_view_permission']; ?></h3>
                                                <div id="accordion">
                                                    <div class="card card-primary">
                                                        <div class="card-header" data-card-widget="collapse">
                                                            <h4 class="card-title w-100">
                                                                <a class="d-block w-100 text-white" data-toggle="collapse" href="#collapseOne">
                                                                <?php echo $translate['admins_group']; ?>
                                                                <span class="fas fa-chevron-down float-right"></span>
                                                                </a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                                            <div class="card-body">
                                                                <form action="" method="POST">
                                                                    <?php
                                                                    $groups = get_table_groups();
                                                                    foreach ($groups as $grp => $tables) {
                                                                        echo '<div class="card card-dark collapsed-card">
                                                                        <div class="card-header" data-card-widget="collapse">
                                                                        <h3 class="card-title">'.$grp.'</h3>
                                                                        <div class="card-tools">
                                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                                        </button>
                                                                        </div>
                                                                        </div>
                                                                        <div class="card-body" style="display: none;">
                                                                        ';
                                                                        foreach ($tables as $tn) {
                                                                            $json = json_encode(get_tables_info(true));
                                                                            $decode = json_decode($json);
                                                                            $table_info = $tn;
                                                                            $table_name = $decode->$tn->Caption;
                                                                            $perms = @file_get_contents('appginilte/group_perms/Admins.json');
                                                                            $extract = json_decode($perms, true);
                                                                            $AllowPrinting = is_numeric($extract[$tn . "_AllowPrinting"]) ? $extract[$tn . "_AllowPrinting"] : 1;
                                                                            $AllowPrintingDV = is_numeric($extract[$tn . "_AllowPrintingDV"]) ? $extract[$tn . "_AllowPrintingDV"] : 1;
                                                                            $AllowCSV = is_numeric($extract[$tn . "_AllowCSV"]) ? $extract[$tn . "_AllowCSV"] : 1;
                                                                            $AllowFilters =  is_numeric($extract[$tn . "_AllowFilters"]) ? $extract[$tn . "_AllowFilters"] : 1;
                                                                            echo '
                                                                            <label class="text-primary">' . $table_name . ' <span class="fa fa-table"></span></label>
                                                                            <div class="row">
                                                                            <div class="col-lg-3 col-sm-12">
                                                                            <label>'.$translate['allow_printing'].'</label>
                                                                            <select id="' . $table_info . '_AllowPrinting" name="' . $table_info . '_AllowPrinting" class="form-control">
                                                                            <option value="1" ' . ($AllowPrinting == 1 ? 'selected' : '') . '>'.$translate['yes'].'</option>
                                                                            <option value="0" ' . ($AllowPrinting == 0 ? 'selected' : '') . '>'.$translate['no'].'</option>
                                                                            </select>
                                                                            </div>
                                                                            <div class="col-lg-3 col-sm-12">
                                                                            <label>'.$translate['allow_printing_dv'].'</label>
                                                                            <select id="' . $table_info . '_AllowPrintingDV" name="' . $table_info . '_AllowPrintingDV" class="form-control">
                                                                            <option value="1" ' . ($AllowPrintingDV == 1 ? 'selected' : '') . '>'.$translate['yes'].'</option>
                                                                            <option value="0" ' . ($AllowPrintingDV == 0 ? 'selected' : '') . '>'.$translate['no'].'</option>
                                                                            </select>
                                                                            </div>
                                                                            <div class="col-lg-3 col-sm-12">
                                                                            <label>'.$translate['allow_csv'].'</label>
                                                                            <select id="' . $table_info . '_AllowCSV" name="' . $table_info . '_AllowCSV" class="form-control">
                                                                            <option value="1" ' . ($AllowCSV == 1 ? 'selected' : '') . '>'.$translate['yes'].'</option>
                                                                            <option value="0" ' . ($AllowCSV == 0 ? 'selected' : '') . '>'.$translate['no'].'</option>
                                                                            </select>
                                                                            </div>
                                                                            <div class="col-lg-3 col-sm-12">
                                                                            <label>'.$translate['allow_filters'].'</label>
                                                                            <select id="' . $table_info . '_AllowFilters" name="' . $table_info . '_AllowFilters" class="form-control">
                                                                            <option value="1" ' . ($AllowFilters == 1 ? 'selected' : '') . '>'.$translate['yes'].'</option>
                                                                            <option value="0" ' . ($AllowFilters == 0 ? 'selected' : '') . '>'.$translate['no'].'</option>
                                                                            </select>
                                                                            </div>
                                                                            </div>
                                                                            ';
                                                                        }
                                                                        echo '</div></div>';
                                                                    }
                                                                    ?>
                                                                    <br><button type="submit" class="btn btn-success" name="Admins"><?php echo $translate['save_changes']; ?></button>
                                                                </form>
                                                                <?php
                                                                if (isset($_POST['Admins'])) {
                                                                    $json_encode = json_encode($_POST);

                                                                    foreach ($groups as $grp => $tables) {
                                                                        foreach ($tables as $tn) {
                                                                            $grp_name = "Admins";
                                                                            $hook_file = "hooks/{$tn}.php";
                                                                            //create code to be inserted in hook file
                                                                            $hook_code = "if(\$memberInfo['group'] == '{$grp_name}'){\n\t\t \$perms=file_get_contents('appginilte/group_perms/{$grp_name}.json');\n\t\t \$extract=json_decode(\$perms,true);\n\t\t \$options->AllowPrinting = \$extract['{$tn}_AllowPrinting'];\n\t\t \$options->AllowPrintingDV = \$extract['{$tn}_AllowPrintingDV'];\n\t\t \$options->AllowCSV = \$extract['{$tn}_AllowCSV'];\n\t\t \$options->AllowFilters = \$extract['{$tn}_AllowFilters'];\n\t\t }";
                                                                            //add code to each table's hook file
                                                                            if (!file_exists("appginilte/group_perms/Admins.json")) {
                                                                                //only add CODE to hook file if json file does not exist
                                                                                add_code_to_hook(
                                                                                    $hook_file,
                                                                                    "{$tn}_init",
                                                                                    $hook_code
                                                                                );
                                                                            }
                                                                        }
                                                                    }
                                                                    //create json file for group permissions
                                                                    file_put_contents("appginilte/group_perms/Admins.json", $json_encode);
                                                                    echo showToast('success', $translate['changes_saved_successfully'], $translate['Admins Group Permissions Saved Successfully']);
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    $getgroups = sql("SELECT name FROM `membership_groups` WHERE name !='Admins' AND name !='anonymous'", $eo);  // get all groups
                                                    foreach ($getgroups as $key => $gdata) {
                                                        $colid = rand(0, 5); // get random color id
                                                        $colors = array("primary", "success", "info", "warning", "danger", "secondary"); // get all colors
                                                        $color = $colors[$colid]; // get color
                                                        $concatGroupName = str_replace(' ', '_', $gdata['name']);
                                                    ?>
                                                        <div class="card card-<?php echo $color; ?>">
                                                            <div class="card-header" data-card-widget="collapse">
                                                                <h4 class="card-title w-100">
                                                                    <a class="d-block w-100  text-white" data-toggle="collapse" href="#<?php echo  str_replace(' ', '', $gdata['name']); ?>">
                                                                        <?php echo $groupname = $gdata['name']; ?> <?php echo $translate['group']; ?>
                                                                        <span class="fas fa-chevron-down float-right"></span>   
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="<?php echo  str_replace(' ', '', $groupname); ?>" class="collapse" data-parent="#accordion">
                                                                <div class="card-body">
                                                                    <form action="" method="POST">
                                                                        <?php
                                                                        foreach ($groups as $grp => $tables) {
                                                                            echo '<div class="card card-dark collapsed-card">
                                                                            <div class="card-header" data-card-widget="collapse">
                                                                            <h3 class="card-title">'.$grp.'</h3>
                                                                            <div class="card-tools">
                                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                                            </button>
                                                                            </div>
                                                                            </div>
                                                                            <div class="card-body" style="display: none;">
                                                                            ';
                                                                            foreach ($tables as $tn) {
                                                                                $json = json_encode(get_tables_info(true));
                                                                                $decode = json_decode($json);
                                                                                $table_info = $tn;
                                                                                $table_name = $decode->$tn->Caption;
                                                                                $perms = @file_get_contents('appginilte/group_perms/' . $concatGroupName . '.json');
                                                                                $extract = json_decode($perms, true);
                                                                                $AllowPrinting = is_numeric($extract[$tn . "_AllowPrinting"]) ? $extract[$tn . "_AllowPrinting"] : 1;
                                                                                $AllowPrintingDV = is_numeric($extract[$tn . "_AllowPrintingDV"]) ? $extract[$tn . "_AllowPrintingDV"] : 1;
                                                                                $AllowCSV = is_numeric($extract[$tn . "_AllowCSV"]) ? $extract[$tn . "_AllowCSV"] : 1;
                                                                                $AllowFilters =  is_numeric($extract[$tn . "_AllowFilters"]) ? $extract[$tn . "_AllowFilters"] : 1;
                                                                                echo '
                                                                                <label class="text-primary">' . $table_name . ' <span class="fa fa-table"></span></label>
                                                                                <div class="row">
                                                                                <div class="col-lg-3 col-sm-12">
                                                                                <label>'.$translate['allow_printing'].'</label>
                                                                                <select id="' . $table_info . '_AllowPrinting" name="' . $table_info . '_AllowPrinting" class="form-control">
                                                                                <option value="1" ' . ($AllowPrinting == 1 ? 'selected' : '') . '>'.$translate['yes'].'</option>
                                                                                <option value="0" ' . ($AllowPrinting == 0 ? 'selected' : '') . '>'.$translate['no'].'</option>
                                                                                </select>
                                                                                </div>
                                                                                <div class="col-lg-3 col-sm-12">
                                                                                <label>'.$translate['allow_printing_dv'].'</label>
                                                                                <select id="' . $table_info . '_AllowPrintingDV" name="' . $table_info . '_AllowPrintingDV" class="form-control">
                                                                                <option value="1" ' . ($AllowPrintingDV == 1 ? 'selected' : '') . '>'.$translate['yes'].'</option>
                                                                                <option value="0" ' . ($AllowPrintingDV == 0 ? 'selected' : '') . '>'.$translate['no'].'</option>
                                                                                </select>
                                                                                </div>
                                                                                <div class="col-lg-3 col-sm-12">
                                                                                <label>'.$translate['allow_csv'].'</label>
                                                                                <select id="' . $table_info . '_AllowCSV" name="' . $table_info . '_AllowCSV" class="form-control">
                                                                                <option value="1" ' . ($AllowCSV == 1 ? 'selected' : '') . '>'.$translate['yes'].'</option>
                                                                                <option value="0" ' . ($AllowCSV == 0 ? 'selected' : '') . '>'.$translate['no'].'</option>
                                                                                </select>
                                                                                </div>
                                                                                <div class="col-lg-3 col-sm-12">
                                                                                <label>'.$translate['allow_filters'].'</label>
                                                                                <select id="' . $table_info . '_AllowFilters" name="' . $table_info . '_AllowFilters" class="form-control">
                                                                                <option value="1" ' . ($AllowFilters == 1 ? 'selected' : '') . '>'.$translate['yes'].'</option>
                                                                                <option value="0" ' . ($AllowFilters == 0 ? 'selected' : '') . '>'.$translate['no'].'</option>
                                                                                </select>
                                                                                </div>
                                                                                </div>
                                                                                ';
                                                                            }
                                                                            echo '</div></div>';
                                                                        }
                                                                        ?>
                                                                        <br><button type="submit" class="btn btn-<?php echo $color; ?>" name="<?php echo  str_replace(' ', '_', $groupname); ?>"><?php echo $translate['save_changes']; ?></button>
                                                                    </form>
                                                                    <?php
                                                                    if (isset($_POST[str_replace(' ', '_', $groupname)])) {
                                                                        $json_encode = json_encode($_POST);

                                                                        foreach ($groups as $grp => $tables) {
                                                                            foreach ($tables as $tn) {
                                                                                $grp_name = $concatGroupName;
                                                                                $hook_file = "hooks/{$tn}.php";
                                                                                //create code to be inserted in hook file
                                                                                $hook_code = "if(\$memberInfo['group'] == '{$gdata['name']}'){\n\t\t \$perms=file_get_contents('appginilte/group_perms/{$grp_name}.json');\n\t\t \$extract=json_decode(\$perms,true);\n\t\t \$options->AllowPrinting = \$extract['{$tn}_AllowPrinting'];\n\t\t \$options->AllowPrintingDV = \$extract['{$tn}_AllowPrintingDV'];\n\t\t \$options->AllowCSV = \$extract['{$tn}_AllowCSV'];\n\t\t \$options->AllowFilters = \$extract['{$tn}_AllowFilters'];\n\t\t }";
                                                                                //add code to each table's hook file
                                                                                if (!file_exists("appginilte/group_perms/" . str_replace(' ', '_', $groupname) . ".json")) {
                                                                                    //only add CODE to hook file if json file does not exist
                                                                                    add_code_to_hook(
                                                                                        $hook_file,
                                                                                        "{$tn}_init",
                                                                                        $hook_code
                                                                                    );
                                                                                }
                                                                            }
                                                                        }
                                                                        //create json file for group permissions
                                                                        file_put_contents("appginilte/group_perms/" . str_replace(' ', '_', $groupname) . ".json", $json_encode);
                                                                        echo showToast('success', $translate['changes_saved_successfully'], $translate['Group Permissions Saved Successfully']);
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                </div>
                                            </div>
                                            <!-- /.tab-pane -->
                                            <div class="tab-pane" id="tab_2">
                                                <h3><?php echo $translate['detail_view_permission']; ?></h3>
                                                <div id="accordion">
                                                    <div class="card card-primary">
                                                        <div class="card-header" data-card-widget="collapse">
                                                            <h4 class="card-title w-100">
                                                                <a class="d-block w-100  text-white" data-toggle="collapse" href="#collapseOne">
                                                                <?php echo $translate['admins_group']; ?>
                                                                <span class="fas fa-chevron-down float-right"></span>
                                                                </a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                                            <div class="card-body">
                                                                <!-- Detail View Code For Admins Group -->
                                                                <?php
                                                                foreach ($groups as $grp => $tables) {
                                                                    echo '<div class="card card-dark collapsed-card">
                                                                    <div class="card-header" data-card-widget="collapse">
                                                                    <h3 class="card-title">'.$grp.'</h3>
                                                                    <div class="card-tools">
                                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                                    </button>
                                                                    </div>
                                                                    </div>
                                                                    <div class="card-body" style="display: none;">
                                                                    ';
                                                                    foreach ($tables as $tn) {
                                                                        $json = json_encode(get_tables_info(true));
                                                                        $decode = json_decode($json);
                                                                        $table_title = $decode->$tn->Caption;
                                                                        $table_name = $tn;
                                                                        $getTableData = file_get_contents("appginilte/group_perms/Admins_" . $table_name . "-dv.json");
                                                                        $decode = json_decode($getTableData, true);
                                                                        $readonlyfields = $decode['readonlyfields_' . $table_name];
                                                                        $hiddenfields = $decode['hiddenfields_' . $table_name];
                                                                        echo '
                                                                        <form action="" method="POST">
                                                                        <input type="hidden" id="table_name" name="table_name" value="' . $table_name . '">
                                                                        <label class="text-primary">' . $table_title . ' <span class="fa fa-table"></span></label>
                                                                        <div class="row">
                                                                        <div class="col-lg-5 col-sm-12">
                                                                            <label><i>'.$translate['Read Only Fields ? (You can select multiple fields)'].'</i></label>
                                                                            <select multiple id="readonlyfields_' . $table_name . '" name="readonlyfields_' . $table_name . '[]" class="form-control"">';
                                                                        $tblfls = get_table_fields($table_name);
                                                                        foreach ($tblfls as $key => $value) {
                                                                            # code...
                                                                            $colvalue = $key;
                                                                            $label = $value['info']['caption'];
                                                                            echo '<option value="' . $colvalue . '" ' . (strpos($readonlyfields, $colvalue) !== false ? 'selected' : '') . '>' . $label . '</option>';
                                                                        }
                                                                        echo ' </select>
                                                                        </div>';
                                                                        echo '<div class="col-lg-5 col-sm-12">
                                                                            <label><i>'.$translate['Hidden Fields ? (You can select multiple fields)'].'</i></label>
                                                                            <select multiple id="hiddenfields_' . $table_name . '" name="hiddenfields_' . $table_name . '[]" class="form-control"">';
                                                                        $tblfls = get_table_fields($table_name);
                                                                        foreach ($tblfls as $key => $value) {
                                                                            # code...
                                                                            $colvalue = $key;
                                                                            $label = $value['info']['caption'];
                                                                            echo '<option value="' . $colvalue . '" ' . (strpos($hiddenfields, $colvalue) !== false ? 'selected' : '') . '>' . $label . '</option>';
                                                                        }
                                                                        echo ' </select>
                                                                        </div>';
                                                                        echo '
                                                                        <br>
                                                                        <div class="col-lg-2 col-sm-12 text-center"><button type="submit" class="btn btn-success" name="detailviewsave_' . $table_name . '">'.$translate['save_changes'].'</button></div>
                                                                        </div>
                                                                        </form>
                                                                        <hr>
                                                                        ';
                                                                        if (isset($_POST['detailviewsave_' . $table_name])) {
                                                                            $table_name = $_POST['table_name'];
                                                                            $readonlyfields = $_POST['readonlyfields_' . $table_name];
                                                                            $hiddenfields = $_POST['hiddenfields_' . $table_name];
                                                                            $readonlyoptions = '';
                                                                            $hiddenoptions = '';
                                                                            foreach ($readonlyfields as $selectedOption) {
                                                                                $readonlyoptions .= $selectedOption . ',';
                                                                            }
                                                                            foreach ($hiddenfields as $selectedOption) {
                                                                                $hiddenoptions .= $selectedOption . ',';
                                                                            }
                                                                            $_POST['readonlyfields_' . $table_name] = rtrim($readonlyoptions, ",");
                                                                            $_POST['hiddenfields_' . $table_name] = rtrim($hiddenoptions, ",");
                                                                            $jenc = json_encode($_POST);
                                                                            //write to appginilte/group_perms/$table_name-dv.json
                                                                            file_put_contents("appginilte/group_perms/Admins_$table_name-dv.json", $jenc);
                                                                            //create table_name-dv.js file
                                                                            $insertcode = '/** DetailView Code Inserted By AppginiLTE */
                                                                            //loop through readonly_fields array and disable those fields
                                                                            for (var i = 0; i < readonly_fields.length; i++) {
                                                                                $j(\'#\' + readonly_fields[i]).prop(\'readOnly\', true);
                                                                                $j(\'#\' + readonly_fields[i]).prop(\'disabled \', true);
                                                                            }
                                                                            //loop through hidden_fields array and hide those fields
                                                                            for (var i = 0; i < hidden_fields.length; i++) {
                                                                                var className=tableName + "-" + hidden_fields[i];
                                                                                document.getElementsByClassName(className)[0].style.display ="none";
                                                                            }
                                                                            /** End of DetailView Code Inserted By AppginiLTE */';
                                                                            //check if fileexists: $table_name-dv.js in hooks folder
                                                                            if (file_exists("hooks/$table_name-dv.js")) {
                                                                                //read file: $table_name-dv.js in hooks folder
                                                                                $filecontents = file_get_contents("hooks/$table_name-dv.js");
                                                                                //check if code is already inserted
                                                                                if (!strpos($filecontents, 'DetailView Code Inserted By AppginiLTE')) {
                                                                                    //code is not inserted
                                                                                    //write file: $table_name-dv.js in hooks folder
                                                                                    file_put_contents("hooks/$table_name-dv.js", $insertcode . "\n" . $filecontents, LOCK_EX);
                                                                                }
                                                                            } else {
                                                                                //write to hooks/$table_name-dv.js
                                                                                file_put_contents("hooks/$table_name-dv.js", $insertcode);
                                                                            }
                                                                            echo showToast('success', $translate['changes_saved_successfully'], $translate['Detail View Changes Saved Successfully']);
                                                                        }
                                                                    }
                                                                    echo '</div></div>';
                                                                }
                                                                ?>
                                                                <!-- Detail View Code For Admins Group -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    $getgroups = sql("SELECT name FROM `membership_groups` WHERE name !='Admins' AND name !='anonymous'", $eo);  // get all groups
                                                    foreach ($getgroups as $key => $gdata) {
                                                        $colid = rand(0, 5); // get random color id
                                                        $colors = array("primary", "success", "info", "warning", "danger", "secondary"); // get all colors
                                                        $color = $colors[$colid]; // get color
                                                        $concatGroupName = str_replace(' ', '_', $gdata['name']);
                                                    ?>
                                                        <div class="card card-<?php echo $color; ?>">
                                                            <div class="card-header" data-card-widget="collapse">
                                                                <h4 class="card-title w-100">
                                                                    <a class="d-block w-100  text-white" data-toggle="collapse" href="#<?php echo  str_replace(' ', '', $gdata['name']); ?>">
                                                                        <?php echo $groupname = $gdata['name']; ?> <?php echo $translate['group']; ?>
                                                                        <span class="fas fa-chevron-down float-right"></span>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="<?php echo  str_replace(' ', '', $groupname); ?>" class="collapse" data-parent="#accordion">
                                                                <div class="card-body">
                                                                    <!-- Detail View Code For Other Groups -->
                                                                    <?php
                                                                    foreach ($groups as $grp => $tables) {
                                                                        echo '<div class="card card-dark collapsed-card">
                                                                        <div class="card-header" data-card-widget="collapse">
                                                                        <h3 class="card-title">'.$grp.'</h3>
                                                                        <div class="card-tools">
                                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                                        </button>
                                                                        </div>
                                                                        </div>
                                                                        <div class="card-body" style="display: none;">
                                                                        ';
                                                                        foreach ($tables as $tn) {
                                                                            $json = json_encode(get_tables_info(true));
                                                                            $decode = json_decode($json);
                                                                            $table_title = $decode->$tn->Caption;
                                                                            $table_name = $tn;
                                                                            $getTableData = file_get_contents("appginilte/group_perms/" . $concatGroupName . "_" . $table_name . "-dv.json");
                                                                            $decode = json_decode($getTableData, true);
                                                                            $readonlyfields = $decode['readonlyfields_' . $table_name];
                                                                            $hiddenfields = $decode['hiddenfields_' . $table_name];
                                                                            echo '
                                                                        <form action="" method="POST">
                                                                        <input type="hidden" id="table_name" name="table_name" value="' . $table_name . '">
                                                                        <label class="text-primary">' . $table_title . ' <span class="fa fa-table"></span></label>
                                                                        <div class="row">
                                                                        <div class="col-lg-5 col-sm-12">
                                                                            <label><i>'.$translate['Read Only Fields ? (You can select multiple fields)'].'</i></label>
                                                                            <select multiple id="readonlyfields_' . $table_name . '" name="readonlyfields_' . $table_name . '[]" class="form-control"">';
                                                                            $tblfls = get_table_fields($table_name);
                                                                            foreach ($tblfls as $key => $value) {
                                                                                # code...
                                                                                $colvalue = $key;
                                                                                $label = $value['info']['caption'];
                                                                                echo '<option value="' . $colvalue . '" ' . (strpos($readonlyfields, $colvalue) !== false ? 'selected' : '') . '>' . $label . '</option>';
                                                                            }
                                                                            echo ' </select>
                                                                        </div>';
                                                                            echo '<div class="col-lg-5 col-sm-12">
                                                                            <label><i>'.$translate['Hidden Fields ? (You can select multiple fields)'].'</i></label>
                                                                            <select multiple id="hiddenfields_' . $table_name . '" name="hiddenfields_' . $table_name . '[]" class="form-control"">';
                                                                            $tblfls = get_table_fields($table_name);
                                                                            foreach ($tblfls as $key => $value) {
                                                                                # code...
                                                                                $colvalue = $key;
                                                                                $label = $value['info']['caption'];
                                                                                echo '<option value="' . $colvalue . '" ' . (strpos($hiddenfields, $colvalue) !== false ? 'selected' : '') . '>' . $label . '</option>';
                                                                            }
                                                                            echo ' </select>
                                                                        </div>';
                                                                            echo '
                                                                        <br>
                                                                        <div class="col-lg-2 col-sm-12 text-center"><button type="submit" class="btn btn-success" name="' . $concatGroupName . 'detailviewsave_' . $table_name . '">'.$translate['save_changes'].'</button></div>
                                                                        </div>
                                                                        </form>
                                                                        <hr>
                                                                        ';
                                                                            if (isset($_POST[$concatGroupName . 'detailviewsave_' . $table_name])) {
                                                                                $table_name = $_POST['table_name'];
                                                                                $readonlyfields = $_POST['readonlyfields_' . $table_name];
                                                                                $hiddenfields = $_POST['hiddenfields_' . $table_name];
                                                                                $readonlyoptions = '';
                                                                                $hiddenoptions = '';
                                                                                foreach ($readonlyfields as $selectedOption) {
                                                                                    $readonlyoptions .= $selectedOption . ',';
                                                                                }
                                                                                foreach ($hiddenfields as $selectedOption) {
                                                                                    $hiddenoptions .= $selectedOption . ',';
                                                                                }
                                                                                $_POST['readonlyfields_' . $table_name] = rtrim($readonlyoptions, ",");
                                                                                $_POST['hiddenfields_' . $table_name] = rtrim($hiddenoptions, ",");
                                                                                $jenc = json_encode($_POST);
                                                                                //write to appginilte/group_perms/$table_name-dv.json
                                                                                file_put_contents("appginilte/group_perms/" . $concatGroupName . "_" . $table_name . "-dv.json", $jenc);
                                                                                //create table_name-dv.js file
                                                                                $insertcode = '/** DetailView Code Inserted By AppginiLTE */
                                                                            //loop through readonly_fields array and disable those fields
                                                                            for (var i = 0; i < readonly_fields.length; i++) {
                                                                                $j(\'#\' + readonly_fields[i]).prop(\'readOnly\', true);
                                                                                $j(\'#\' + readonly_fields[i]).prop(\'disabled \', true);
                                                                            }
                                                                            //loop through hidden_fields array and hide those fields
                                                                            for (var i = 0; i < hidden_fields.length; i++) {
                                                                                var className=tableName + "-" + hidden_fields[i];
                                                                                document.getElementsByClassName(className)[0].style.display ="none";
                                                                            }
                                                                            /** End of DetailView Code Inserted By AppginiLTE */';
                                                                                //check if fileexists: $table_name-dv.js in hooks folder
                                                                                if (file_exists("hooks/$table_name-dv.js")) {
                                                                                    //read file: $table_name-dv.js in hooks folder
                                                                                    $filecontents = file_get_contents("hooks/$table_name-dv.js");
                                                                                    //check if code is already inserted
                                                                                    if (!strpos($filecontents, 'DetailView Code Inserted By AppginiLTE')) {
                                                                                        //code is not inserted
                                                                                        //write file: $table_name-dv.js in hooks folder
                                                                                        file_put_contents("hooks/$table_name-dv.js", $insertcode . "\n" . $filecontents, LOCK_EX);
                                                                                    }
                                                                                } else {
                                                                                    //write to hooks/$table_name-dv.js
                                                                                    file_put_contents("hooks/$table_name-dv.js", $insertcode);
                                                                                }
                                                                                echo showToast('success', $translate['changes_saved_successfully'], $translate['Detail View Changes Saved Successfully']);
                                                                            }
                                                                        }
                                                                        echo '</div></div>';
                                                                    }
                                                                    ?>
                                                                    <!-- Detail View Code For Other Groups -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.tab-content -->
                                    </div><!-- /.card-body -->
                                </div>
                                <!-- ./card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                        <!-- Card Tabs -->
                    </div>

                </div>
                <!-- /.card -->
            </div>
        </div>
</div>
</section>
<!-- /.content -->
</div>
<?php
function add_code_to_hook($hook_file_path, $hook_function, $code, $location = 'top')
{
    /* Check if hook file exists and is writable */
    $hook_code = @file_get_contents($hook_file_path);
    if (!$hook_code) return 'Unable to access hook file';

    /* Find hook function */
    preg_match('/function\s+' . $hook_function . '\s*\(/i', $hook_code, $matches, PREG_OFFSET_CAPTURE);
    if (count($matches) != 1) return 'Could not determine correct function location';

    /* start position of hook function */
    $hf_position = $matches[0][1];

    /* position of next function, or EOF position if this is the last function in the file */
    $nf_position = strlen($hook_code);
    preg_match('/function\s+[a-z0-9_]+\s*\(/i', $hook_code, $matches, PREG_OFFSET_CAPTURE, $hf_position + 10);
    if (count($matches)) $nf_position = $matches[0][1];

    /* hook function code */
    $old_function_code = substr($hook_code, $hf_position, $nf_position - $hf_position);

    /* Checks $code is not already in there */
    if (strpos($old_function_code, $code) !== false) 'Code already exists';

    /* determine insertion point based on $location */
    /*********** location support not yet implemented ************/

    /* insert $code and save */
    $code_comment = "/* Inserted by AppginiLTE on " . date('Y-m-d h:i:s') . " */";
    $new_function_code = preg_replace(
        '/(function\s+' . $hook_function . '\s*\(.*\)\s*\\' . chr(123) . ')/i',
        "\$1\n\t\t{$code_comment}\n\t\t{$code}\n\t\t/* End of AppginiLTE code */\n",
        $old_function_code,
        1
    );
    if (!$new_function_code) return 'Error while injecting code';
    if ($new_function_code == $old_function_code) return 'Nothing changed';

    $hook_code = str_replace($old_function_code, $new_function_code, $hook_code);
    if (!@file_put_contents($hook_file_path, $hook_code)) return 'Could not save changes';

    return 'Success';
}

?>
<!-- /.content-wrapper -->
<?php include 'appginilte_footer.php'; ?>