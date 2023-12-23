<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
include 'appginilte_header.php';
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
                        <li class="breadcrumb-item active"><?php echo $translate['reports']; ?></li>
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
                            <?php echo $translate['reports']; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php
                        $json = json_encode(get_tables_info(true));
                        $decode = json_decode($json, true);
                        $total_report_count = 0;
                        foreach ($decode as $key => $value) {
                            # code...
                            $table_title = $decode[$key]['Caption'];
                            $tableIcon = $decode[$key]['tableIcon'];
                            $table_name = $key;
                            $report_count = reportcount($table_name, $group);
                            $total_report_count = $total_report_count + $report_count;
                            if ($report_count > 0) {
                                //get individual reports from appginilte/appginilte_reports
                                if ($handle = opendir('./appginilte/appginilte_reports')) {
                                    $reportitem = '';
                                    while (false !== ($entry = readdir($handle))) {
                                        if ($entry != "." && $entry != ".." && $entry != "index.html") {
                                            #code...
                                            $entry_table_name=explode('-', $entry)[0];
                                            if ($entry_table_name==$table_name) {
                                                $filedata = json_decode(file_get_contents('appginilte/appginilte_reports/' . $entry . ''), true);
                                                $report_name = $filedata['report_name'];
                                                $report_groups= $filedata['report_groups'];
                                                $ReportCardSize=$filedata['ReportCardSize']?$filedata['ReportCardSize']:'col-md-3';
                                                $ReportCardColor=$filedata['ReportCardColor']?$filedata['ReportCardColor']:'success';
                                                if(strpos($report_groups, $group) !== false||$report_groups=='*'){
                                                $reportitem .= homeLinkInfoBox_2($report_name, '1', 'appginilte_reportview.php?getdata=' . $entry . '', 'far fa-file-excel', $ReportCardColor,  $ReportCardSize, 'fontawosome', '');
                                                }
                                            }
                                        }
                                    }
                                    closedir($handle);
                                }
                                echo showDataCard("<b>" . $table_title . "</b>", "<div class='row'>" . $reportitem . "</div>", "primary", "col-md-12", "No");
                            }
                        }
                        if ($total_report_count == 0) {
                            echo showAlert("danger", $translate['no_reports_found'], $translate['no_reports_found_description']);
                        }
                        ?>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include 'appginilte_footer.php'; ?>