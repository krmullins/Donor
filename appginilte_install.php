<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
include 'appginilte_header.php';
if ($group !== "Admins") {
    header('location: index.php');
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
                            <?php echo $translate['install']; ?>
                            <?php echo $env['plugin'] . ' v ' . $env['version']; ?>
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
                            <?php echo $translate['install']; ?>
                            <?php echo $env['plugin'] . ' v ' . $env['version']; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-check"></i>
                                <?php echo $translate['install_success']; ?>
                            </h5>
                            <?php
                            include 'appginilte/replace-appgini-functions.php';
                            if(isset($_GET['aru'])){ $maindirfiles= getjsonFiles('appginilte'); $TAfiles= getjsonFiles('appginilte/table_actions'); foreach ($maindirfiles as $jsonFile) { $string = str_replace("appginilte/", "", $jsonFile); $string = str_replace(".json", "", $string); if(empty(get_option($string))){ $contents=file_get_contents($jsonFile); set_option($string,$contents); } } foreach ($TAfiles as $jsonFile) { $string = str_replace("appginilte/table_actions/", "", $jsonFile); $string = str_replace(".json", "", $string); if(empty(get_option("table_actions_".$string))){ $contents=file_get_contents($jsonFile); set_option("table_actions_".$string,$contents); } } unlink('appginilte/aru.bin'); echo "<br>"."Options Created"; }
                            ?>
                        </div>
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-exclamation-triangle"></i>
                                <?php echo $translate['issues_that_need_attention']; ?>
                            </h5>
                            <?php
                            include 'appginilte/issues_that_need_attention.php';
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
<?php include 'appginilte_footer.php'; ?>