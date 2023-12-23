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
                        <li class="breadcrumb-item active"><?php echo $translate['Data Encryption Tool']; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="container-fluid">
            <div class="row">
                <?php echo showCallout('info', $translate['Table Data Encryption Tool'], $translate['enc_tool_desc']); ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title w-100">
                                <a class="d-block w-100 text-white" data-toggle="collapse" href="#collapseOne">
                                    <?php echo $translate['table fields encryption']; ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="collapse show" data-parent="#accordion">
                            <div class="card-body">
                                <!-- Table Encryption Start -->
                                <h3><?php echo $translate['My Tables']; ?></h3>
                                <hr>
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
                                        $getTableData = file_get_contents("hooks/table_encryption/" . $table_name . "-enc.json");
                                        $decode = json_decode($getTableData, true);
                                        $encryptedfields = $decode['encryptedfields_' . $table_name];
                                        echo '
                                                                        <form action="" method="POST">
                                                                        <input type="hidden" id="table_name" name="table_name" value="' . $table_name . '">
                                                                        <label class="text-primary">' . $table_title . ' <span class="fa fa-table"></span></label>
                                                                        <div class="row">
                                                                        <div class="col-lg-12 col-sm-12">
                                                                            <label><i>' . $translate['Select Encrypted Fields (You can select multiple fields)'] . '</i></label>
                                                                            <select multiple id="encryptedfields_' . $table_name . '" name="encryptedfields_' . $table_name . '[]" class="form-control"">';
                                        $tblfls = get_table_fields($table_name);
                                        foreach ($tblfls as $key => $value) {
                                            # code...
                                            $colvalue = $key;
                                            $label = $value['info']['caption'];
                                            echo '<option value="' . $colvalue . '" ' . (strpos($encryptedfields, $colvalue) !== false ? 'selected' : '') . '>' . $label . '</option>';
                                        }
                                        echo ' </select></div>';
                                        echo '<br><br><div class="container text-center">
                                              <div class="col-lg-6 col-sm-12">
                                             <button type="submit" class="btn btn-success" name="encsave_' . $table_name . '">' . $translate['save_changes'] . '</button>&nbsp;
                                                ' . showModal($translate['How To Setup Encryption For']. $table_title . $translate['Table'], '<iframe src="appginilte/setupencr.php?table=' . $table_name . '" frameborder="0" style="border: 0; width: 100%;height: 900px;"></iframe>', '', $translate['How To Setup'], 'dark', 'fas fa-cog', 'btn-md') . '</div></div>
                                             </div>
                                             </form>
                                             <hr>';
                                        if (isset($_POST['encsave_' . $table_name])) {
                                            $table_name = $_POST['table_name'];
                                            $encryptedfields = $_POST['encryptedfields_' . $table_name];
                                            $encoptions = '';
                                            foreach ($encryptedfields as $selectedOption) {
                                                $encoptions .= $selectedOption . ',';
                                            }
                                            $_POST['encryptedfields_' . $table_name] = rtrim($encoptions, ",");
                                            $jenc = json_encode($_POST);
                                            //write to appginilte/table_encryption/$table_name-enc.json
                                            $folder = "hooks/table_encryption";
                                            if (!file_exists($folder)) {
                                                mkdir($folder, 0777, true);
                                            }
                                            file_put_contents("hooks/table_encryption/index.html", "//Silence is golden");
                                            file_put_contents("hooks/table_encryption/$table_name-enc.json", $jenc);
                                            echo showToast('success', $translate['changes_saved_successfully'], $translate['changes_saved_successfully']);
                                        }
                                    }
                                    echo '</div></div>';
                                }
                                ?>
                                <!-- Detail View Code For Admins Group -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    document.title = '<?php echo $appName . '|' . $translate['Data Encryption Tool']; ?>';
</script>
<?php include 'appginilte_footer.php'; ?>