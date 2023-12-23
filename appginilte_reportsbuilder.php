<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
include 'appginilte_header.php';
include 'config.php';
if ($group !== "Admins") {
    header('location: index.php');
} else {
    if ($isSuperAdmin == 'No' && $dashbuilder == 0) {
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
                    <h1>
                        <?php echo $appName; ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">
                                <?php echo $translate['dashboard']; ?>
                            </a></li>
                        <li class="breadcrumb-item active">
                            <?php echo $translate['reports builder']; ?>
                        </li>
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
                            <i class="fas fa-eye"></i>
                            <?php echo $translate['reports builder']; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <?php echo $appName; ?>
                                        </h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>

                                    </div>
                                    <div class="card-body" style="min-height: 5rem;max-height: 70vh;overflow-y: auto;">
                                        <ul class="nav flex-column">
                                            <?php
                                            $json = json_encode(get_tables_info(true));
                                            $decodetables = json_decode($json, true);
                                            foreach ($decodetables as $key => $value) {
                                                # code...
                                                $table_title = $decodetables[$key]['Caption'];
                                                $tableIcon = $decodetables[$key]['tableIcon'];
                                                $table_name = $key;
                                                $report_count = reportcount($table_name);
                                                echo '<li class="nav-item">
                                                <a href="?table=' . $table_name . '" class="nav-link">
                                                <img src="' . $tableIcon . '"> ' . $table_title . ' <span class="float-right badge ' . ($report_count > 0 ? "badge-success" : "badge-info") . ' right">' . $report_count . '</span>
                                                </a>
                                            </li>';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <?php
                                $get_table = $_GET['table'];
                                $get_table_title = $decodetables[$get_table]['Caption'];
                                $tblgrps = $groups = sql("SELECT name FROM membership_groups", $eo);
                                $getAccess = file_get_contents("appginilte/appginilte_reports/access_to_reports_menu.json");
                                $decode = json_decode($getAccess, true);
                                $access_to_reports_menu = $decode['access_to_reports_menu'];
                                if (empty($get_table)) {
                                    echo showAlert('danger', $translate['No project table selected'], $translate['Please select a project table on the left to view or build reports.']);

                                    ?>
                                    <!-- Add content for groups that can acess reports menu. -->
                                    <form action="" method="POST">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><i>
                                                            <?php echo $translate['access_to_reports_menu']; ?>
                                                        </i></label>
                                                    <select multiple name="access_to_reports_menu[]"
                                                        id="access_to_reports_menu" class="form-control">
                                                        <?php
                                                        foreach ($tblgrps as $grp) {
                                                            # code...
                                                            echo '<option value="' . $grp['name'] . '" ' . (strpos($access_to_reports_menu, $grp['name']) !== false ? 'selected' : '') . '>' . $grp['name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-success" type="submit"
                                                        name="submit_reports_access">
                                                        <?php echo $translate['save_changes']; ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                } else {

                                    $tcols = '';
                                    $tfils = '';
                                    $tblfls = get_table_fields($get_table);
                                    foreach ($tblfls as $key => $value) {
                                        # code...
                                        $colvalue = $key;
                                        $label = $value['info']['caption'];
                                        $tcols .= '<option value="' . $colvalue . '">' . $label . '</option>';
                                        $tfils .= '<span class="badge badge-secondary">`' . $get_table . '`.`' . $colvalue . '`</span> ';
                                    }
                                    //get numeric data type appgini table fields
                                    $numtcols = '';
                                    foreach ($tblfls as $key => $value) {
                                        # code...
                                        $colvalue = $key;
                                        $label = $value['info']['caption'];
                                        $property = $value['appgini'];
                                        //if property contains Numeric Data Types
                                        if (strpos($property, 'INT') !== false || strpos($property, 'TINYINT') !== false || strpos($property, 'SMALLINT') !== false || strpos($property, 'MEDIUMINT') !== false || strpos($property, 'BIGINT') !== false || strpos($property, 'FLOAT') !== false || strpos($property, 'DOUBLE') !== false || strpos($property, 'DECIMAL') !== false) {
                                            $numtcols .= '<option value="' . $colvalue . '">' . $label . '</option>';
                                        }
                                    }
                                    //get only DATE appgini fields properties
                                    $dtcols = '';
                                    foreach ($tblfls as $key => $value) {
                                        # code...
                                        $colvalue = $key;
                                        $label = $value['info']['caption'];
                                        $property = $value['appgini'];
                                        //if property contains string DATE
                                        if (strpos($property, 'DATE') !== false) {
                                            $dtcols .= '<option value="' . $colvalue . '">' . $label . '</option>';
                                        }
                                    }
                                    $ptables = '';
                                    foreach ($decodetables as $key => $value) {
                                        # code...
                                        $table_title = $decodetables[$key]['Caption'];
                                        $table_name = $key;
                                        $ptables .= '<option value="' . $table_name . '">' . $table_title . '</option>';
                                    }
                                    $report_form = '
                                    <form action="" method="POST">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="far fa-plus-square"></i>
                                                ' . $get_table_title . '
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="report_name">' . $translate['Report Name'] . '</label>
                                                        <input type="text" class="form-control" id="report_name" name="report_name" placeholder="' . $translate['Enter report name'] . '" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="report_labelfield">' . $translate['Label Field'] . '</label>
                                                        <select class="form-control" name="labelfield" id="labelfield" required>
                                                        ' . $tcols . '
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="report_name">' . $translate['Label Field Alias'] . '</label>
                                                        <input type="text" class="form-control" id="label_field_alias" name="label_field_alias" placeholder="' . $translate['Enter Label Field Alias'] . '">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="report_name">' . $translate['Label Field Summary Title'] . '</label>
                                                    <input type="text" class="form-control" id="label_field_summary_title" name="label_field_summary_title" placeholder="' . $translate['Enter Label Field Summary Title'] . '">
                                                </div>
                                                </div>
                                                <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="report_labelfieldislookup">' . $translate['Label Field Is A Lookup Field?'] . '</label>
                                                        <select class="form-control" name="labelfieldislookup" id="labelfieldislookup" required>
                                                        <option value="No">' . $translate['no'] . '</option>
                                                        <option value="Yes">' . $translate['yes'] . '</option>
                                                        </select>
                                                    </div>
                                                </div>
                                               <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="report_labelfieldisdate">' . $translate['Label Field Is A Date?'] . '</label>
                                                        <select class="form-control" name="labelfieldisdate" id="labelfieldisdate" required>
                                                        <option value="No">' . $translate['no'] . '</option>
                                                        <option value="Yes">' . $translate['yes'] . '</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="labelfielddisplay_report">
                                                    <div class="form-group">
                                                        <label for="report_labelfielddisplay">' . $translate['Label Field Group By'] . '</label>
                                                        <select class="form-control" name="labelfielddisplay" id="labelfielddisplay">
                                                        <option value="month">' . $translate['Month Name'] . '</option>
                                                        <option value="year">' . $translate['Year'] . '</option>
                                                        <option value="date">' . $translate['Full Date'] . '</option>
                                                        </select>
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2" id="report_labelfieldposition">
                                                <div class="form-group">
                                                        <label for="report_labelfieldposition">' . $translate['Label Field Position'] . '</label>
                                                        <input type="number" class="form-control" id="labelfieldposition" name="labelfieldposition" placeholder="' . $translate['Enter Position Of Label Field'] . '" min="1" max="1000">
                                                    </div>
                                                </div>
                                                <div class="col-md-4" id="report_labelfieldparent">
                                                    <div class="form-group">
                                                        <label for="report_labelfieldparent">' . $translate['Select Parent Table'] . '</label>
                                                        <select class="form-control" name="labelfieldparent" id="labelfieldparent">
                                                        <option value="">' . $translate['Select Parent Table'] . '</option>
                                                        ' . $ptables . '
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-6" id="report_labelfieldparentfield">
                                                    <div class="form-group">
                                                        <label for="report_labelfieldparentfield">' . $translate['Select Parent Field'] . '</label>
                                                        <select class="form-control" name="labelfieldparentfield" id="labelfieldparentfield">
                                                        <option value="">' . $translate['Select Parent Field'] . '</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="report_summary">' . $translate['Summary'] . '</label>
                                                        <select class="form-control" name="summary" id="summary" required>
                                                        <option value="">' . $translate['Select Option'] . '</option>
                                                        <option value="Average">' . $translate['Average'] . '</option>
                                                        <option value="Count">' . $translate['Count'] . '</option>
                                                        <option value="Sum">' . $translate['Sum'] . '</option>
                                                        <option value="Maximum">' . $translate['Maximum'] . '</option>
                                                        <option value="Minimum">' . $translate['Minimum'] . '</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-12" id="report_summaryfield">
                                                    <div class="form-group">
                                                        <label for="report_summaryfield">' . $translate['Summary Field'] . '</label>
                                                        <select class="form-control" name="summaryfield" id="summaryfield">
                                                        ' . $numtcols . '
                                                        </select>
                                                    </div>
                                                </div>
                                                </div>
                                            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="report_FilterOperator">' . $translate['Label Field Filter Operator'] . '</label>
                                                        <select class="form-control" name="FilterOperator" id="FilterOperator" required>
                                                        <option value="equal-to">' . $translate['Equal To'] . '</option>
                                                        <option value="not-equal-to">' . $translate['Not Equal To'] . '</option>
                                                        <option value="greater-than">' . $translate['Greater Than'] . '</option>
                                                        <option value="greater-than-or-equal-to">' . $translate['Greater Than Or Equal To'] . '</option>
                                                        <option value="less-than">' . $translate['Less Than'] . '</option>
                                                        <option value="less-than-or-equal-to">' . $translate['Less Than Or Equal To'] . '</option>
                                                        <option value="like">' . $translate['Like'] . '</option>
                                                        <option value="not-like">' . $translate['Not Like'] . '</option>
                                                        <option value="is-empty">' . $translate['Is Empty'] . '</option>
                                                        <option value="is-not-empty">' . $translate['Is Not Empty'] . '</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="report_report_groups">' . $translate['Groups That Can Access This Report'] . '</label>
                                                    <textarea class="form-control" id="report_groups" name="report_groups" rows="3" placeholder="' . $translate['Enter groups that can access this report separated by a comma, or use * for all groups'] . '" required></textarea>
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <div class="form-group">
                                                        <label for="report_DateFilter">' . $translate['Include Date Filter ?'] . '</label>
                                                        <select class="form-control" name="DateFilter" id="DateFilter" required>
                                                        <option value="No">' . $translate['no'] . '</option>
                                                        <option value="Yes">' . $translate['yes'] . '</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="report_DateFilterField">
                                                <div class="form-group">
                                                        <label for="report_DateFilterField">' . $translate['Date Field'] . '</label>
                                                        <select class="form-control" name="DateFilterField" id="DateFilterField">
                                                        ' . $dtcols . '
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="report_DateFilterFieldCollapse">
                                                <div class="form-group">
                                                        <label for="report_DateFilterCollapse">' . $translate['Collapased Date Filter Card ?'] . '</label>
                                                        <select class="form-control" name="DateFilterCollapse" id="DateFilterCollapse">
                                                        <option value="No">' . $translate['no'] . '</option>
                                                        <option value="Yes">' . $translate['yes'] . '</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="barchart" id="barchart">
                                                <label class="form-check-label">' . $translate['Include Bar Chart?'] . '</label>
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="donutchart" id="donutchart">
                                                <label class="form-check-label">' . $translate['Include Donut Chart?'] . '</label>
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="piechart" id="piechart">
                                                <label class="form-check-label">' . $translate['Include Pie Chart?'] . '</label>
                                                </div>
                                                </div>
                                                <div class="col-md-12"><hr></div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="report_ReportCardSize">' . $translate['Report Card Size'] . '</label>
                                                        <select class="form-control" name="ReportCardSize" id="ReportCardSize" required>
                                                        <option value="col-md-1">col-md-1</option>
                                                        <option value="col-md-2">col-md-2</option>
                                                        <option value="col-md-3">col-md-3</option>
                                                        <option value="col-md-4">col-md-4</option>
                                                        <option value="col-md-5">col-md-5</option>
                                                        <option value="col-md-6">col-md-6</option>
                                                        <option value="col-md-7">col-md-7</option>
                                                        <option value="col-md-8">col-md-8</option>
                                                        <option value="col-md-9">col-md-9</option>
                                                        <option value="col-md-10">col-md-10</option>
                                                        <option value="col-md-11">col-md-11</option>
                                                        <option value="col-md-12">col-md-12</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="report_ReportCardColor">' . $translate['Report Card Color'] . '</label>
                                                        <select class="form-control" name="ReportCardColor" id="ReportCardColor" required>
                                                        <option value="primary">Primary</option>
                                                        <option value="success">Success</option>
                                                        <option value="warning">Warning</option>
                                                        <option value="danger">Danger</option>
                                                        <option value="info">Info</option>
                                                        <option value="secondary">Secondary</option>
                                                        <option value="dark">Dark</option>
                                                        <option value="maroon">Maroon</option>
                                                        <option value="purple">Purple</option>
                                                        <option value="indigo">Indigo</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12"><hr></div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="report_name">' . $translate['Data Table Records Per Page'] . '</label>
                                                        <input type="number" class="form-control" id="pagination" name="pagination" placeholder="' . $translate['Enter total records per page'] . '" min="1" max="1000" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="report_customwhere">' . $translate['Custom WHERE'] . ' </label>  ' . showModal($translate['Fields you can use in the WHERE clause for this report'], $tfils, $translate['Table fields helper'], $translate['Fields Helper'], 'btn btn-info', 'fas fa-arrow-right', 'btn-xs') . '
                                                    <textarea class="form-control" id="customwhere" name="customwhere" rows="3" placeholder="' . $translate['Enter your custom WHERE condition here. Dont start with WHERE'] . '"></textarea>
                                                </div>
                                                </div>
                                                <div class="col-md-3">
                                                <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="enforceuserpermissions" id="enforceuserpermissions">
                                                <label class="form-check-label">' . $translate['Show records based on user permissions'] . '</label>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><br>
                                    <button class="btn btn-primary" type="submit" name="savereport" id="savereport">' . $translate['Save Report'] . '</button>
                                    </form>
                                    ';
                                    echo showModal($translate['Create New Report For'] . $get_table_title . '', $report_form, $translate['Reports Buider Pro'], $translate['Create'] . " " . $get_table_title . " " . $translate['Report'], 'btn btn-success', 'fas fa-plus') . '<a href="appginilte_reportsbuilder.php" class="btn btn-warning"><span class="fas fa-home"></span> ' . $translate['Reports Home'] . '</a>' . '<br><br>' . '<h3>' . $get_table_title . $translate['Reports'] . '</h3>';
                                    if (reportcount($get_table) == 0) {
                                        echo showAlert('danger', $translate['No reports found'], $translate['No reports found for this table.']);
                                    }
                                    if ($handle = opendir('./appginilte/appginilte_reports')) {
                                        $navlinktop = '<div class="p-0"><ul class="nav flex-column">';
                                        $navlinkbottom = '</ul></div>';
                                        $navitem = '';
                                        while (false !== ($entry = readdir($handle))) {
                                            if ($entry != "." && $entry != ".." && $entry != "index.html") {
                                                #code...
                                                $unique_id = uniqid();
                                                $entry_table_name = explode('-', $entry)[0];
                                                if ($entry_table_name == $get_table) {
                                                    $filedata = json_decode(file_get_contents('appginilte/appginilte_reports/' . $entry . ''), true);
                                                    $report_name = $filedata['report_name'];
                                                    $label_field_alias = $filedata['label_field_alias'];
                                                    $label_field_summary_title = $filedata['label_field_summary_title'];
                                                    $FilterOperator = $filedata['FilterOperator'] ? $filedata['FilterOperator'] : 'equal-to';
                                                    $labelfield = $filedata['labelfield'];
                                                    $table = $filedata['table'];
                                                    $summary = $filedata['summary'];
                                                    $summaryfield = $filedata['summaryfield'];
                                                    $report_groups = $filedata['report_groups'];
                                                    $barchart = $filedata['barchart'] ? $filedata['barchart'] : '';
                                                    $donutchart = $filedata['donutchart'] ? $filedata['donutchart'] : '';
                                                    $piechart = $filedata['piechart'] ? $filedata['piechart'] : '';
                                                    $pagination = $filedata['pagination'];
                                                    $labelfieldislookup = $filedata['labelfieldislookup'];
                                                    $labelfieldisdate = $filedata['labelfieldisdate'];
                                                    $labelfielddisplay = $filedata['labelfielddisplay'];
                                                    $labelfieldparent = $filedata['labelfieldparent'];
                                                    $labelfieldparentfield = $filedata['labelfieldparentfield'] ? $filedata['labelfieldparentfield'] : 'select option';
                                                    $enforceuserpermissions = $filedata['enforceuserpermissions'];
                                                    $customwhere = $filedata['customwhere'] ? $filedata['customwhere'] : '';
                                                    $labelfieldposition = $filedata['labelfieldposition'] ? $filedata['labelfieldposition'] : '1';
                                                    $ReportCardSize = $filedata['ReportCardSize'] ? $filedata['ReportCardSize'] : 'col-md-3';
                                                    $ReportCardColor = $filedata['ReportCardColor'] ? $filedata['ReportCardColor'] : 'success';
                                                    $DateFilter = $filedata['DateFilter'] ? $filedata['DateFilter'] : 'No';
                                                    $DateFilterField = $filedata['DateFilterField'] ? $filedata['DateFilterField'] : '';
                                                    $DateFilterCollapse = $filedata['DateFilterCollapse'] ? $filedata['DateFilterCollapse'] : 'Yes';
                                                    $etcols = '';
                                                    foreach ($tblfls as $key => $value) {
                                                        # code...
                                                        $colvalue = $key;
                                                        $label = $value['info']['caption'];
                                                        $etcols .= '<option value="' . $colvalue . '" ' . ($labelfield == $colvalue ? "selected" : "") . '>' . $label . '</option>';
                                                    }

                                                    $eptables = '';
                                                    foreach ($decodetables as $key => $value) {
                                                        # code...
                                                        $table_title = $decodetables[$key]['Caption'];
                                                        $table_name = $key;
                                                        $eptables .= '<option value="' . $table_name . '" ' . ($labelfieldparent == $table_name ? "selected" : "") . '>' . $table_title . '</option>';
                                                    }
                                                    //get numeric data type appgini table fields
                                                    $enumtcols = '';
                                                    foreach ($tblfls as $key => $value) {
                                                        # code...
                                                        $colvalue = $key;
                                                        $label = $value['info']['caption'];
                                                        $property = $value['appgini'];
                                                        //if property contains Numeric Data Types
                                                        if (strpos($property, 'INT') !== false || strpos($property, 'TINYINT') !== false || strpos($property, 'SMALLINT') !== false || strpos($property, 'MEDIUMINT') !== false || strpos($property, 'BIGINT') !== false || strpos($property, 'FLOAT') !== false || strpos($property, 'DOUBLE') !== false || strpos($property, 'DECIMAL') !== false) {
                                                            $enumtcols .= '<option value="' . $colvalue . '" ' . ($summaryfield == $colvalue ? "selected" : "") . '>' . $label . '</option>';
                                                        }
                                                    }
                                                    //get only DATE appgini fields properties
                                                    $edtcols = '';
                                                    foreach ($tblfls as $key => $value) {
                                                        # code...
                                                        $colvalue = $key;
                                                        $label = $value['info']['caption'];
                                                        $property = $value['appgini'];
                                                        //if property contains string DATE
                                                        if (strpos($property, 'DATE') !== false) {
                                                            $edtcols .= '<option value="' . $colvalue . '" ' . ($DateFilterField == $colvalue ? "selected" : "") . '>' . $label . '</option>';
                                                        }
                                                    }
                                                    // edit report form
                                                    $edit_report_form = '
                                                    <form action="" method="POST">
                                                    <input type="hidden" id="filename" name="filename" value="' . $entry . '">
                                                        <div class="card card-primary">
                                                            <div class="card-header">
                                                                <h3 class="card-title">
                                                                    <i class="far fa-edit"></i>
                                                                    ' . $report_name . '
                                                                </h3>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="report_name">' . $translate['Report Name'] . '</label>
                                                                            <input type="text" class="form-control" id="report_name" name="report_name" value="' . $report_name . '" placeholder="' . $translate['Enter report name'] . '" required>
                                                                        </div>
                                                                    </div>
                                                                
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="report_labelfield">' . $translate['Label Field'] . '</label>
                                                                            <select class="form-control" name="labelfield" id="labelfield" required>
                                                                                ' . $etcols . '
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="report_name">' . $translate['Label Field Alias'] . '</label>
                                                                        <input type="text" class="form-control" id="elabel_field_alias" name="label_field_alias" value="' . $label_field_alias . '" placeholder="' . $translate['Enter Label Field Alias'] . '">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="report_name">' . $translate['Label Field Summary Title'] . '</label>
                                                                    <input type="text" class="form-control" id="elabel_field_summary_title" name="label_field_summary_title" value="' . $label_field_summary_title . '" placeholder="' . $translate['Enter Label Field Summary Title'] . '">
                                                                </div>
                                                                </div>
                                                                    <div class="row">
                                                                    <div class="col-md-12">
                                                                    <div class="form-group">
                                                                    <label for="report_labelfieldislookup">' . $translate['Label Field Is A Lookup Field?'] . '</label>
                                                                    <select class="form-control" name="labelfieldislookup" id="' . $unique_id . 'labelfieldislookup" required onchange="elabelfieldislookup(\'' . $unique_id . '\')">
                                                                    <option value="No" ' . ($labelfieldislookup == "No" ? "selected" : "") . '>' . $translate['no'] . '</option>
                                                                    <option value="Yes" ' . ($labelfieldislookup == "Yes" ? "selected" : "") . '>' . $translate['yes'] . '</option>
                                                                    </select>
                                                                </div>
                                                                    </div>
                                                                <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="report_labelfieldisdate">' . $translate['Label Field Is A Date?'] . '</label>
                                                                            <select class="form-control" name="labelfieldisdate" id="' . $unique_id . 'labelfieldisdate" required onchange="elabelfieldisdate(\'' . $unique_id . '\')">
                                                                            <option value="No" ' . ($labelfieldisdate == "No" ? "selected" : "") . '>' . $translate['no'] . '</option>
                                                                            <option value="Yes" ' . ($labelfieldisdate == "Yes" ? "selected" : "") . '>' . $translate['yes'] . '</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6" id="' . $unique_id . 'elabelfielddisplay_report">
                                                                        <div class="form-group">
                                                                            <label for="report_labelfielddisplay">' . $translate['Label Field Group By'] . '</label>
                                                                            <select class="form-control" name="labelfielddisplay" id="' . $unique_id . 'labelfielddisplay">
                                                                            <option value="month" ' . ($labelfielddisplay == "month" ? "selected" : "") . '>' . $translate['Month Name'] . '</option>
                                                                            <option value="year" ' . ($labelfielddisplay == "year" ? "selected" : "") . '>' . $translate['Year'] . '</option>
                                                                            <option value="date" ' . ($labelfielddisplay == "date" ? "selected" : "") . '>' . $translate['Full Date'] . '</option>
                                                                            </select>
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-2" id="' . $unique_id . 'ereport_labelfieldposition">
                                                                    <div class="form-group">
                                                                            <label for="report_labelfieldposition">' . $translate['Label Field Position'] . '</label>
                                                                            <input type="number" class="form-control" id="labelfieldposition" name="labelfieldposition" value="' . $labelfieldposition . '" placeholder="' . $translate['Enter Position Of Label Field'] . '" min="1" max="1000">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4" id="' . $unique_id . 'ereport_labelfieldparent">
                                                                        <div class="form-group">
                                                                            <label for="report_labelfieldparent">' . $translate['Select Parent Table'] . '</label>
                                                                            <select class="form-control" name="labelfieldparent" id="' . $unique_id . 'labelfieldparent" onchange="elabelfieldparent(\'' . $unique_id . '\')">
                                                                            <option value="">' . $translate['Select Parent Table'] . '</option>
                                                                            ' . $eptables . '
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                
                                                                    <div class="col-md-6" id="' . $unique_id . 'ereport_labelfieldparentfield">
                                                                        <div class="form-group">
                                                                            <label for="report_labelfieldparentfield">' . $translate['Select Parent Field'] . '</label>
                                                                            <select class="form-control" name="labelfieldparentfield" id="' . $unique_id . 'labelfieldparentfield">
                                                                            <option value="' . $labelfieldparentfield . '">' . $labelfieldparentfield . '</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="report_summary">' . $translate['Summary'] . '</label>
                                                                            <select class="form-control" name="summary" id="' . $unique_id . 'summary" required onchange="summaryfieldchange(\'' . $unique_id . '\')">
                                                                                <option value="Average" ' . ($summary == "Average" ? "selected" : "") . '>' . $translate['Average'] . '</option>
                                                                                <option value="Count" ' . ($summary == "Count" ? "selected" : "") . '>' . $translate['Count'] . '</option>
                                                                                <option value="Sum" ' . ($summary == "Sum" ? "selected" : "") . '>' . $translate['Sum'] . '</option>
                                                                                <option value="Maximum" ' . ($summary == "Maximum" ? "selected" : "") . '>' . $translate['Maximum'] . '</option>
                                                                                <option value="Minimum" ' . ($summary == "Minimum" ? "selected" : "") . '>' . $translate['Minimum'] . '</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-12" id="' . $unique_id . 'ereport_summaryfield">
                                                                            <div class="form-group">
                                                                                <label for="report_summaryfield">' . $translate['Summary Field'] . '</label>
                                                                                <select class="form-control" name="summaryfield" id="summaryfield">
                                                                                ' . $enumtcols . '
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="report_FilterOperator">' . $translate['Label Field Filter Operator'] . '</label>
                                                                            <select class="form-control" name="FilterOperator" id="FilterOperator" required>
                                                                                <option value="equal-to" ' . ($FilterOperator == "equal-to" ? "selected" : "") . '>' . $translate['Equal To'] . '</option>
                                                                                <option value="not-equal-to" ' . ($FilterOperator == "not-equal-to" ? "selected" : "") . '>' . $translate['Not Equal To'] . '</option>
                                                                                <option value="greater-than" ' . ($FilterOperator == "greater-than" ? "selected" : "") . '>' . $translate['Greater Than'] . '</option>
                                                                                <option value="greater-than-or-equal-to" ' . ($FilterOperator == "greater-than-or-equal-to" ? "selected" : "") . '>' . $translate['Greater Than Or Equal To'] . '</option>
                                                                                <option value="less-than" ' . ($FilterOperator == "less-than" ? "selected" : "") . '>' . $translate['Less Than'] . '</option>
                                                                                <option value="less-than-or-equal-to" ' . ($FilterOperator == "less-than-or-equal-to" ? "selected" : "") . '>' . $translate['Less Than Or Equal To'] . '</option>
                                                                                <option value="like" ' . ($FilterOperator == "like" ? "selected" : "") . '>' . $translate['Like'] . '</option>
                                                                                <option value="not-like" ' . ($FilterOperator == "not-like" ? "selected" : "") . '>' . $translate['Not Like'] . '</option>
                                                                                <option value="is-empty" ' . ($FilterOperator == "is-empty" ? "selected" : "") . '>' . $translate['Is Empty'] . '</option>
                                                                                <option value="is-not-empty" ' . ($FilterOperator == "is-not-empty" ? "selected" : "") . '>' . $translate['Is Not Empty'] . '</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="report_report_groups">' . $translate['Groups That Can Access This Report'] . '</label>
                                                                            <textarea class="form-control" id="report_groups" name="report_groups" rows="3" placeholder="' . $translate['Enter groups that can access this report separated by a comma, or use * for all groups'] . '" required>' . $report_groups . '</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                    <div class="form-group">
                                                                            <label for="report_DateFilter">' . $translate['Include Date Filter ?'] . '</label>
                                                                            <select class="form-control" name="DateFilter" id="' . $unique_id . 'DateFilter" required onchange="datefilterfieldchange(\'' . $unique_id . '\')">
                                                                            <option value="No" ' . ($DateFilter == "No" ? "selected" : "") . '>' . $translate['no'] . '</option>
                                                                            <option value="Yes" ' . ($DateFilter == "Yes" ? "selected" : "") . '>' . $translate['yes'] . '</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4" id="' . $unique_id . 'ereport_DateFilterField">
                                                                    <div class="form-group">
                                                                            <label for="report_DateFilterField">' . $translate['Date Field'] . '</label>
                                                                            <select class="form-control" name="DateFilterField" id="DateFilterField">
                                                                            ' . $edtcols . '
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4" id="' . $unique_id . 'ereport_DateFilterFieldCollapse">
                                                                    <div class="form-group">
                                                                            <label for="report_DateFilterCollapse">' . $translate['Collapased Date Filter Card ?'] . '</label>
                                                                            <select class="form-control" name="DateFilterCollapse" id="DateFilterCollapse">
                                                                            <option value="No" ' . ($DateFilterCollapse == "No" ? "selected" : "") . '>' . $translate['no'] . '</option>
                                                                            <option value="Yes" ' . ($DateFilterCollapse == "Yes" ? "selected" : "") . '>' . $translate['yes'] . '</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" name="barchart" id="barchart" ' . ($barchart == "on" ? ' checked' : '') . '>
                                                                            <label class="form-check-label">' . $translate['Include Bar Chart?'] . '</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" name="donutchart" id="donutchart" ' . ($donutchart == "on" ? ' checked' : '') . '>
                                                                            <label class="form-check-label">' . $translate['Include Donut Chart?'] . '</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" name="piechart" id="piechart" ' . ($piechart == "on" ? ' checked' : '') . '>
                                                                            <label class="form-check-label">' . $translate['Include Pie Chart?'] . '</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12"><hr></div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="report_ReportCardSize">' . $translate['Report Card Size'] . '</label>
                                                                            <select class="form-control" name="ReportCardSize" id="ReportCardSize" required>
                                                                                <option value="col-md-1" ' . ($ReportCardSize == "col-md-1" ? "selected" : "") . '>col-md-1</option>
                                                                                <option value="col-md-2" ' . ($ReportCardSize == "col-md-2" ? "selected" : "") . '>col-md-2</option>
                                                                                <option value="col-md-3" ' . ($ReportCardSize == "col-md-3" ? "selected" : "") . '>col-md-3</option>
                                                                                <option value="col-md-4" ' . ($ReportCardSize == "col-md-4" ? "selected" : "") . '>col-md-4</option>
                                                                                <option value="col-md-5" ' . ($ReportCardSize == "col-md-5" ? "selected" : "") . '>col-md-5</option>
                                                                                <option value="col-md-6" ' . ($ReportCardSize == "col-md-6" ? "selected" : "") . '>col-md-6</option>
                                                                                <option value="col-md-7" ' . ($ReportCardSize == "col-md-7" ? "selected" : "") . '>col-md-7</option>
                                                                                <option value="col-md-8" ' . ($ReportCardSize == "col-md-8" ? "selected" : "") . '>col-md-8</option>
                                                                                <option value="col-md-9" ' . ($ReportCardSize == "col-md-9" ? "selected" : "") . '>col-md-9</option>
                                                                                <option value="col-md-10" ' . ($ReportCardSize == "col-md-10" ? "selected" : "") . '>col-md-10</option>
                                                                                <option value="col-md-11" ' . ($ReportCardSize == "col-md-11" ? "selected" : "") . '>col-md-11</option>
                                                                                <option value="col-md-12" ' . ($ReportCardSize == "col-md-12" ? "selected" : "") . '>col-md-12</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="report_ReportCardColor">' . $translate['Report Card Color'] . '</label>
                                                                            <select class="form-control" name="ReportCardColor" id="ReportCardColor" required>
                                                                                <option value="primary" ' . ($ReportCardColor == "primary" ? "selected" : "") . '>Primary</option>
                                                                                <option value="secondary" ' . ($ReportCardColor == "secondary" ? "selected" : "") . '>Secondary</option>
                                                                                <option value="success" ' . ($ReportCardColor == "success" ? "selected" : "") . '>Success</option>
                                                                                <option value="danger" ' . ($ReportCardColor == "danger" ? "selected" : "") . '>Danger</option>
                                                                                <option value="warning" ' . ($ReportCardColor == "warning" ? "selected" : "") . '>Warning</option>
                                                                                <option value="info" ' . ($ReportCardColor == "info" ? "selected" : "") . '>Info</option>
                                                                                <option value="dark" ' . ($ReportCardColor == "dark" ? "selected" : "") . '>Dark</option>
                                                                                <option value="maroon" ' . ($ReportCardColor == "maroon" ? "selected" : "") . '>Maroon</option>
                                                                                <option value="purple" ' . ($ReportCardColor == "purple" ? "selected" : "") . '>Purple</option>
                                                                                <option value="indigo" ' . ($ReportCardColor == "indigo" ? "selected" : "") . '>Indigo</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12"><hr></div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="report_name">' . $translate['Data Table Records Per Page'] . '</label>
                                                                            <input type="number" class="form-control" id="pagination" name="pagination" value="' . $pagination . '" placeholder="' . $translate['Enter total records per page'] . '" min="1" max="1000" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="report_customwhere">' . $translate['Custom WHERE'] . ' </label>  ' . showModal($translate['Fields you can use in the WHERE clause for this report'], $tfils, $translate['Table fields helper'], $translate['Fields Helper'], 'btn btn-info', 'fas fa-arrow-right', 'btn-xs') . '
                                                                        <textarea class="form-control" id="customwhere" name="customwhere" rows="3" placeholder="' . $translate['Enter your custom WHERE condition here. Dont start with WHERE'] . '">' . $customwhere . '</textarea>
                                                                    </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                    <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="enforceuserpermissions" id="enforceuserpermissions" ' . ($enforceuserpermissions == "on" ? 'checked' : '') . '>
                                                                    <label class="form-check-label">' . $translate['Show records based on user permissions'] . '</label>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><br>
                                                        <button class="btn btn-primary" type="submit" name="savereportchanges" id="savereportchanges">' . $translate['Save Report'] . '</button>
                                                    </form>
                                                    ';
                                                    //edit report form
                                                    $navitem .= '<li class="nav-item">
                                                        <strong>' . $report_name . '</strong>
                                                    <div class="btn-group float-right" style="margin-bottom: 4px;">' . showModal($translate['edit'] . $report_name, $edit_report_form, $translate['Reports Buider Pro'], '', 'btn btn-success', 'fas fa-edit') . '<a href="appginilte_reportsbuilder.php?deletereport=' . $entry . '&target=' . $get_table . '" onclick="return confirmDelete();" class="btn btn-sm btn-danger" type="button"><i class="far fa-trash-alt"></i></a></div>
                                                    </li>
                                                    <script>
                                                    function autohideFields(elementID) {
                                                        var e = document.getElementById(elementID + "labelfieldislookup");
                                                        var Theselected = e.value;
                                                        if (Theselected == "Yes") {
                                                            document.getElementById(elementID + "ereport_labelfieldparent").style.display = "block";
                                                            document.getElementById(elementID + "ereport_labelfieldparentfield").style.display = "block";
                                                            document.getElementById(elementID + "ereport_labelfieldposition").style.display = "block";
                                                        } else {
                                                            document.getElementById(elementID + "ereport_labelfieldparent").style.display = "none";
                                                            document.getElementById(elementID + "ereport_labelfieldparentfield").style.display = "none";
                                                            document.getElementById(elementID + "ereport_labelfieldposition").style.display = "none";
                                                        }

                                                        var d = document.getElementById(elementID + "labelfieldisdate");
                                                        var TheselectedIsDate = d.value;
                                                        if (TheselectedIsDate == "Yes") {
                                                            document.getElementById(elementID + "elabelfielddisplay_report").style.display = "block";
                                                        } else {
                                                            document.getElementById(elementID + "elabelfielddisplay_report").style.display = "none";
                                                        }

                                                        const grabSummary = document.getElementById(elementID +"summary");
                                                            var selectedSummary = grabSummary.value;
                                                            if (selectedSummary == "Average"||selectedSummary == "Sum"||selectedSummary == "Maximum"||selectedSummary == "Minimum") {
                                                                document.getElementById(elementID +"ereport_summaryfield").style.display = "block";
                                                            } else {
                                                                document.getElementById(elementID +"ereport_summaryfield").style.display = "none";
                                                            }
                                                        var DateFilter = document.getElementById(elementID +"DateFilter");
                                                        var selectedDateFilter = DateFilter.value;
                                                        if (selectedDateFilter == "Yes") {
                                                            document.getElementById(elementID +"ereport_DateFilterField").style.display = "block";
                                                            document.getElementById(elementID +"ereport_DateFilterFieldCollapse").style.display = "block";
                                                        } else {
                                                            document.getElementById(elementID +"ereport_DateFilterField").style.display = "none";
                                                            document.getElementById(elementID +"ereport_DateFilterFieldCollapse").style.display = "none";
                                                        }
                                                    }
                                                    autohideFields(\'' . $unique_id . '\')</script>';
                                                }
                                            }
                                        }
                                        echo $navlinktop . $navitem . $navlinkbottom;
                                        closedir($handle);
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
if (isset($_POST['savereport'])) {
    $_POST['table'] = $get_table;
    $jenc = json_encode($_POST);
    $file_name = $get_table . "-" . time() . ".json";
    //write to appginilte/ppginilte_reports
    file_put_contents("appginilte/appginilte_reports/" . $file_name . "", $jenc);
    echo showToast('success', $translate['Report Saved Successfully'], $translate['Report Created And Saved Successfully']) . '<META HTTP-EQUIV="refresh" CONTENT="2">';
}
if (isset($_POST['savereportchanges'])) {
    $_POST['table'] = $get_table;
    $file_name = $_POST['filename'];
    $jenc = json_encode($_POST);
    //write to appginilte/ppginilte_reports
    file_put_contents("appginilte/appginilte_reports/" . $file_name . "", $jenc);
    echo showToast('success', $translate['Report Changes Saved Successfully'], $translate['Report Changes Made Have Been Saved Successfully']) . '<META HTTP-EQUIV="refresh" CONTENT="2">';
}
if (isset($_GET['deletereport'])) {
    # code...
    $file_name = $_GET['deletereport'];
    $target = $_GET['target'];
    unlink("appginilte/appginilte_reports/" . $file_name . "");
    echo showToast('success', $translate['All good'], $translate['Report deleted successfully']) . '<META HTTP-EQUIV="refresh" CONTENT="3; URL=appginilte_reportsbuilder.php?table=' . $target . '">';
}
if (isset($_GET['del'])) {
    # code...
    echo showToast('success', $translate['Report Deleted Successfully'], $translate['Report Deleted Successfully']);
}


if (isset($_POST['submit_reports_access'])) {
    $menuaccess = '';
    foreach ($_POST['access_to_reports_menu'] as $selectedOption) {
        $menuaccess .= "'" . $selectedOption . "'" . ',';
    }
    $_POST['access_to_reports_menu'] = rtrim($menuaccess, ",");
    $jenc = json_encode($_POST);
    //write to appginilte/appginilte_reports/access_to_reports_menu.json
    file_put_contents("appginilte/appginilte_reports/access_to_reports_menu.json", $jenc);
    echo showToast('success', $translate['changes_saved_successfully'], $translate['access_to_reports_menu_changes_saved_successfully']);
}

?>
<script>
    document.getElementById("report_labelfieldparent").style.display = "none";
    document.getElementById("report_labelfieldparentfield").style.display = "none";
    document.getElementById("report_labelfieldposition").style.display = "none";
    document.getElementById("report_summaryfield").style.display = "none";
    document.getElementById("report_DateFilterField").style.display = "none";
    document.getElementById("report_DateFilterFieldCollapse").style.display = "none";
    document.getElementById("labelfielddisplay_report").style.display = "none";
    const grabIsLookup = document.querySelector("#labelfieldislookup");
    grabIsLookup.onchange = (e) => {
        var selectedIsLookup = document.getElementById("labelfieldislookup").value;
        if (selectedIsLookup == "Yes") {
            document.getElementById("report_labelfieldparent").style.display = "block";
            document.getElementById("report_labelfieldparentfield").style.display = "block";
            document.getElementById("report_labelfieldposition").style.display = "block";
        } else {
            document.getElementById("report_labelfieldparent").style.display = "none";
            document.getElementById("report_labelfieldparentfield").style.display = "none";
            document.getElementById("report_labelfieldposition").style.display = "none";
        }
    }
    const grabIsDate = document.querySelector("#labelfieldisdate");
    grabIsDate.onchange = (e) => {
        var selectedIsDate = document.getElementById("labelfieldisdate").value;
        if (selectedIsDate == "Yes") {
            document.getElementById("labelfielddisplay_report").style.display = "block";
        } else {
            document.getElementById("labelfielddisplay_report").style.display = "none";
        }
    }
    const grabSummary = document.querySelector("#summary");
    grabSummary.onchange = (e) => {
        var selectedSummary = document.getElementById("summary").value;
        if (selectedSummary == "Average" || selectedSummary == "Sum" || selectedSummary == "Maximum" || selectedSummary == "Minimum") {
            document.getElementById("report_summaryfield").style.display = "block";
        } else {
            document.getElementById("report_summaryfield").style.display = "none";
        }
    }
    const grabDateFilter = document.querySelector("#DateFilter");
    grabDateFilter.onchange = (e) => {
        var selectedDateFilter = document.getElementById("DateFilter").value;
        if (selectedDateFilter == "Yes") {
            document.getElementById("report_DateFilterField").style.display = "block";
            document.getElementById("report_DateFilterFieldCollapse").style.display = "block";
        } else {
            document.getElementById("report_DateFilterField").style.display = "none";
            document.getElementById("report_DateFilterFieldCollapse").style.display = "none";
        }
    }
    $(document).ready(function () {
        $('#labelfieldparent').change(function () {
            loadlabelfieldparentField($(this).find(':selected').val())
        })
    })
    //logic for edit form
    function elabelfieldislookup(elementID) {
        var selectedIsLookup = document.getElementById(elementID + "labelfieldislookup").value;
        if (selectedIsLookup == "Yes") {
            document.getElementById(elementID + "ereport_labelfieldparent").style.display = "block";
            document.getElementById(elementID + "ereport_labelfieldparentfield").style.display = "block";
            document.getElementById(elementID + "ereport_labelfieldposition").style.display = "block";
        } else {
            document.getElementById(elementID + "ereport_labelfieldparent").style.display = "none";
            document.getElementById(elementID + "ereport_labelfieldparentfield").style.display = "none";
            document.getElementById(elementID + "ereport_labelfieldposition").style.display = "none";
        }
    }

    function elabelfieldisdate(elementID) {
        var selectedIsDate = document.getElementById(elementID + "labelfieldisdate").value;
        if (selectedIsDate == "Yes") {
            document.getElementById(elementID + "elabelfielddisplay_report").style.display = "block";
        } else {
            document.getElementById(elementID + "elabelfielddisplay_report").style.display = "none";
        }
    }

    function summaryfieldchange(elementID) {
        var selectedSummary = document.getElementById(elementID + "summary").value;
        if (selectedSummary == "Average" || selectedSummary == "Sum" || selectedSummary == "Maximum" || selectedSummary == "Minimum") {
            document.getElementById(elementID + "ereport_summaryfield").style.display = "block";
        } else {
            document.getElementById(elementID + "ereport_summaryfield").style.display = "none";
        }
    }

    function datefilterfieldchange(elementID) {
        var selectedDateFilter = document.getElementById(elementID + "DateFilter").value;
        if (selectedDateFilter == "Yes") {
            document.getElementById(elementID + "ereport_DateFilterField").style.display = "block";
        } else {
            document.getElementById(elementID + "ereport_DateFilterField").style.display = "none";
        }
    }

    function elabelfieldparent(elementID) {
        var selectedparent = document.getElementById(elementID + "labelfieldparent").value;
        loadelabelfieldparentField(selectedparent, elementID);
    }


    function loadlabelfieldparentField(parentTable) {
        theParentTable = parentTable;
        $("#labelfieldparentfield").children().remove()
        $.ajax({
            type: "POST",
            url: "appginilte_reportbuilder_ajax.php",
            data: {
                theParentTable: theParentTable
            },
        }).done(function (result) {
            var data = JSON.parse(result);
            length = data.length;
            if (length == 1) {
                var changer = "<option value='null'>--------</option>";
            } else {
                var changer = "";
            }
            if (length == 0) {
                $("#labelfieldparentfield").children().remove()
                $("#labelfieldparentfield").append("<option value='null'>No Parent Fields Available</option>");
            }

            $.each(data, function (index, value) {
                $("#labelfieldparentfield").append("<option value='" + value.id + "'>" + value.name + "</option>" + changer);
            });

        });
    }
    //logic for edit form
    function loadelabelfieldparentField(parentTable, elementID) {
        theParentTable = parentTable;
        $("#" + elementID + "labelfieldparentfield").children().remove()
        $.ajax({
            type: "POST",
            url: "appginilte_reportbuilder_ajax.php",
            data: {
                theParentTable: theParentTable
            },
        }).done(function (result) {
            var data = JSON.parse(result);
            length = data.length;
            if (length == 1) {
                var changer = "<option value='null'>--------</option>";
            } else {
                var changer = "";
            }
            if (length == 0) {
                $("#" + elementID + "labelfieldparentfield").children().remove()
                $("#" + elementID + "labelfieldparentfield").append("<option value='null'>No Parent Fields Available</option>");
            }

            $.each(data, function (index, value) {
                $("#" + elementID + "labelfieldparentfield").append("<option value='" + value.id + "'>" + value.name + "</option>" + changer);
            });

        });
    }
    function confirmDelete() {
        // show a confirm dialog
        var choice = confirm("<?php echo $translate['Do you really want to delete this item']; ?>");

        // if the user clicked OK
        if (choice) {
            // continue with the link navigation
            return true;
        } else {
            // cancel the link navigation
            return false;
        }
    }
</script>
<?php include 'appginilte_footer.php'; ?>