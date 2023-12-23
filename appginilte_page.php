<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
include 'appginilte_header.php';
$page = $_REQUEST['page'];
//get page data from json filein appginilte/custompages/pagesdata
$file_contents = file_get_contents('appginilte/custompages/pagesdata/' . $page.'.json');
$file_contents = json_decode($file_contents, true);
$pageName=$file_contents['pageName'];
$pageAccess = $file_contents['pageAccess'] ? $file_contents['pageAccess'] : 'public';
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
                        <li class="breadcrumb-item active"><?php echo $pageName; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="container-fluid">
            <?php
            if(strpos($pageAccess, $group) === false && strpos($pageAccess, '*') === false){
                echo '<div class="alert alert-danger">'.$translate['you_do_not_have_access_to_this_page'].'</div>';
            }
            //ceckif file exists page: in appginilte/custompages/page.phpthen include it else show error
            elseif (file_exists('appginilte/custompages/' . $page . '.php')) {
                include 'appginilte/custompages/' . $page . '.php';
            } else {
                echo '<div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">'.$translate['error'].'</h4>
                <p>'.$translate['page_not_found'].'</p>
                <hr>
                <p class="mb-0">'.$translate['go_to_dashboard'].'</p>
                </div>';
            }
            ?>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    document.title = '<?php echo $appName.'|'.ucwords(str_replace("_", " ", $pageName)); ?>';
</script>
<?php include 'appginilte_footer.php'; ?>