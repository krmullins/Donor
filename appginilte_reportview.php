<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
include 'appginilte_header.php';
$getdata = $_GET['getdata'];
$filedata = json_decode(file_get_contents('appginilte/appginilte_reports/' . $getdata . ''), true);
$report_name = $filedata['report_name'];
$labelfield = $filedata['labelfield'];
$label_field_alias=$filedata['label_field_alias'];
$label_field_summary_title=$filedata['label_field_summary_title'];
$table = $filedata['table'];
$summary = $filedata['summary'];
$summaryfield = $filedata['summaryfield'];
$report_groups = $filedata['report_groups'];
$barchart = $filedata['barchart'] ? $filedata['barchart'] : '';
$donutchart = $filedata['donutchart'] ? $filedata['donutchart'] : '';
$piechart = $filedata['piechart'] ? $filedata['piechart'] : '';
$pagination = $filedata['pagination'];
$FilterOperator = $filedata['FilterOperator'] ? $filedata['FilterOperator'] : 'like';
$labelfieldislookup = $filedata['labelfieldislookup'] ? $filedata['labelfieldislookup'] : '';
$labelfieldisdate= $filedata['labelfieldisdate'] ? $filedata['labelfieldisdate'] : '';
$labelfielddisplay = $filedata['labelfielddisplay'] ? $filedata['labelfielddisplay'] : '';
$labelfieldparent = $filedata['labelfieldparent'] ? $filedata['labelfieldparent'] : '';
$labelfieldparentfield = $filedata['labelfieldparentfield'] ? $filedata['labelfieldparentfield'] : '';
$enforceuserpermissions = $filedata['enforceuserpermissions'] ? $filedata['enforceuserpermissions'] : '';
$customwhere = $filedata['customwhere'] ? $filedata['customwhere'] : '';
$labelfieldposition = $filedata['labelfieldposition'] ? $filedata['labelfieldposition'] : '';
$DateFilter = $filedata['DateFilter'] ? $filedata['DateFilter'] : 'No';
$DateFilterField = $filedata['DateFilterField'] ? $filedata['DateFilterField'] : '';
$DateFilterCollapse = $filedata['DateFilterCollapse'] ? $filedata['DateFilterCollapse'] : 'Yes';
$reportDateFilterField='';
//format DateFilterField: remove underscores, capitalize first letter
$FDateFilterField = str_replace('_', ' ', $DateFilterField);
$FDateFilterField = ucwords($FDateFilterField);
//show and or blank depending on custom where isset
$showand = $customwhere ? ' AND ' : '';
$fdatefrom = '';
$fdateto = '';
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
                        <li class="breadcrumb-item active"><?php echo $report_name; ?></li>
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
                            <?php echo $report_name; ?>
                        </h3>
                        <div class="float-right"><a href="<?php echo $_SERVER['HTTP_REFERER'] ?>" class="btn btn-default"><span class="fas fa-chevron-left"></span> <?php echo $translate['back']; ?></a></div>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($DateFilter == "Yes" && !empty($DateFilterField)) {
                            echo showDataCard('<span class="fas fa-filter"></span> '.$translate['filter_by'].' ' . $FDateFilterField . '', '
                            <div class="btn-group">
                                <button type="button" class="btn btn-success"><i class="far fa-calendar-check"></i> '.$translate['select_report_period'].'</button>
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu" style="">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="getDateRange(\'Month-To-Date\');">'.$translate['month_to_date'].'</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="getDateRange(\'Current Month\');">'.$translate['current_month'].'</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="getDateRange(\'Last Month\');">'.$translate['last_month'].'</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="getDateRange(\'Quater-To-Date\');">'.$translate['quater_to_date'].'</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="getDateRange(\'Current Quater\');">'.$translate['current_quater'].'</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="getDateRange(\'Last Quater\');">'.$translate['last_quater'].'</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="getDateRange(\'Year-To-Date\');">'.$translate['year_to_date'].'</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="getDateRange(\'Current Year\');">'.$translate['current_year'].'</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="getDateRange(\'Last Year\');">'.$translate['last_year'].'</a>
                                </div>
                            </div><hr>
                            <form method="POST" action=""><div class="row"><div class="col-md-6"><div class="form-group"><label>' . $FDateFilterField . ' '.$translate['from:'].'</label><input type="date" class="form-control" id="from" name="datefrom" required></div></div><div class="col-md-6"><div class="form-group"><label>' . $FDateFilterField . ' '.$translate['to:'].'</label><input type="date" class="form-control" id="to"  name="dateto" required></div></div><div class="col-md-12"><div class="form-group text-center"><button type="submit" class="btn btn-primary" name="applyfilter">'.$translate['apply_filters'].'</button></div></div></form>', 'danger', 'col-md-12', 'No', $DateFilterCollapse);
                            if (isset($_POST['applyfilter'])) {
                                $reportDateFilterField = $filedata['DateFilterField'] ? $filedata['DateFilterField'] : '';
                                $datefrom = $_POST['datefrom'];
                                $dateto = $_POST['dateto'];
                                $customwhere .= "" . $showand . "".$table."." . $DateFilterField . " BETWEEN '" . $datefrom . "' AND '" . $dateto . "'";
                                $report_name .= " - From " . date("F jS, Y", strtotime($datefrom)) . " To " . date("F jS, Y", strtotime($dateto));
                                $fdateto = date("m/d/Y", strtotime($dateto));
                                $fdatefrom = date("m/d/Y", strtotime($datefrom));
                            }
                        }
                        ?>
                        <script>
                            document.title = '<?php echo $report_name; ?>';
                        </script>
                        <?php echo renderReport($report_name, $labelfield, $table, $summary, $report_groups, $barchart, $donutchart, $piechart, $pagination, $FilterOperator, $labelfieldislookup, $labelfieldparent, $labelfieldparentfield, $enforceuserpermissions, $customwhere, $labelfieldposition, $summaryfield, $reportDateFilterField, $fdatefrom, $fdateto,$labelfieldisdate,$labelfielddisplay,$label_field_alias,$label_field_summary_title); ?>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    //function to get date ranges based on the periodspecified
    function getDateRange(period) {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        var datefrom = '';
        var dateto = '';
        switch (period) {
            case 'Month-To-Date':
                datefrom = yyyy + '-' + mm + '-01';
                dateto = today;
                break;
            case 'Current Month':
                datefrom = yyyy + '-' + mm + '-01';
                dateto = yyyy + '-' + mm + '-' + new Date(yyyy, mm, 0).getDate();
                break;
            case 'Last Month':
                datefrom = yyyy + '-' + ('0' +(mm - 1)).slice(-2) + '-01';
                dateto = yyyy + '-' + ('0' +(mm - 1)).slice(-2) + '-' + new Date(yyyy, (mm - 1), 0).getDate();
                break;
            case 'Quater-To-Date':
                var q = Math.floor((mm - 1) / 3) + 1;
                var startMonth = (q - 1) * 3 + 1;
                var endMonth = q * 3;
                datefrom = yyyy + '-' + ('0'+startMonth).slice(-2) + '-01';
                dateto = today;
                break;
            case 'Current Quater':
                var q = Math.floor((mm - 1) / 3) + 1;
                var startMonth = (q - 1) * 3 + 1;
                var endMonth = q * 3;
                datefrom = yyyy + '-' + ('0'+startMonth).slice(-2) + '-01';
                dateto = yyyy + '-' + ('0'+endMonth).slice(-2) + '-' + new Date(yyyy, endMonth, 0).getDate();
                break;
            case 'Last Quater':
                var q = Math.floor((mm - 1) / 3);
                var startMonth = (q - 1) * 3 + 1;
                var endMonth = q * 3;
                datefrom = yyyy + '-' + ('0'+startMonth).slice(-2) + '-01';
                dateto = yyyy + '-' + ('0'+endMonth).slice(-2) + '-' + new Date(yyyy, endMonth, 0).getDate();
                break;
            case 'Year-To-Date':
                datefrom = yyyy + '-01-01';
                dateto = today;
                break;
            case 'Current Year':
                datefrom = yyyy + '-01-01';
                dateto = yyyy + '-12-31';
                break;
            case 'Last Year':
                datefrom = (yyyy - 1) + '-01-01';
                dateto = (yyyy - 1) + '-12-31';
                break;
        }
        document.getElementById('from').value = datefrom;
        document.getElementById('to').value = dateto;
    }
</script>
<?php include 'appginilte_footer.php'; ?>