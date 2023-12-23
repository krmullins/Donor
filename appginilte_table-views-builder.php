<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
include 'appginilte_header.php';
$get_custom_menus = file_get_contents('appginilte/jsondb/custom_table_menus.json');
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
                        <li class="breadcrumb-item"><a href="index.php"><?php echo $translate['dashboard']; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo $translate['table views builder']; ?></li>
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
                            <?php echo $translate['table views builder']; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php
                        echo showCallout('info', $translate['table views builder'], $translate['table_views_desc']);
                        ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card card-row card-primary" style="min-height: 5rem;max-height: 70vh;overflow-y: auto;">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                        <?php echo $translate['my_table_views']; ?>
                                        </h3>
                                        <div class="text-right"><a class="btn btn-success btn-sm" href="appginilte_table-views-builder.php"><span class="fas fa-plus-circle"></span></a></div>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                            $files = scandir('appginilte/custompages/pagesdata');
                                            $count = 0;
                                            foreach ($files as $file) {
                                                if ($file != '.' && $file != '..' && $file != "index.html" && $file != " " && strpos($file, '_tview') == true) {
                                                    $count++;
                                                    $file_contents = file_get_contents('appginilte/custompages/pagesdata/' . $file);
                                                    $file_contents = json_decode($file_contents, true);
                                            ?>
                                                    <div class="card card-warning card-outline">
                                                        <div class="card-header">
                                                            <h5 class="card-title"><?php echo $file_contents['pageName']; ?></h5><br>
                                                            <p><small><b><?php echo $translate['DB View Name:'];?> <?php echo $file_contents['FileName']; ?></b></small></p>
                                                            <div class="card-tools">
                                                                <a href="?edit=<?php echo $file_contents['FileName']; ?>" class="btn btn-tool text-success" data-toggle="tooltip" title="<?php echo $translate['edit'];?>">
                                                                    <i class="fas fa-pen"></i>
                                                                </a>
                                                                <a href="?delete=<?php echo $file_contents['FileName']; ?>" class="btn btn-tool btn-link text-danger" onclick="return confirmDelete();" data-toggle="tooltip" title="<?php echo $translate['delete'];?>"><i
                                                                        class="far fa-trash-alt"></i></a>
                                                                <a href="appginilte_page-builder.php?edit=<?php echo $file_contents['FileName']; ?>" class="btn btn-tool text-primary" data-toggle="tooltip" title="<?php echo $translate['Edit Page Code'];?>">
                                                                    <i class="fas fa-terminal"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            if ($count == 0) {
                                                echo showAlert('danger', $translate['no_table_views_found'], $translate['no_table_views_found_description']);
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="card card-row card-success">
                                    <?php
                                    $edit = $_GET['edit'];
                                    if(!empty($edit)){
                                        $pagedata = file_get_contents('appginilte/custompages/pagesdata/'.$edit.'.json');
                                        $decodepagedata = json_decode($pagedata, true);
                                        $pageName = $decodepagedata['pageName'];
                                        $pageIcon=$decodepagedata['pageIcon'];
                                        $pageGroup=$decodepagedata['pageGroup'];
                                        $pageAccess=$decodepagedata['pageAccess'];
                                        $hidenavmenu=$decodepagedata['hidenavmenu'];
                                        $sql=$decodepagedata['sql'];
                                    }
                                    ?>
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <?php if (empty($edit)) {
                                                echo $translate['Create Table View'];
                                            } else
                                                echo $translate['Edit Table View']; ?>
                                        </h3>
                                        <div class="text-right"><a href="admin/pageSQL.php" class="btn btn-info btn-sm" target="_blank"><span class="fas fa-terminal"></span> <?php echo $translate['Interactive SQL queries tool']; ?></a></div>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        $groups = get_table_groups();
                                        $array_keys = array_keys($groups);
                                        //loop through the groups
                                        $tablegroups = "";
                                        foreach ($array_keys as $key => $value) {
                                            # code...
                                            $selected = ($pageGroup == $key) ? 'selected' : '';
                                            $tablegroups .= '<option value="' . $key . '" '.$selected.'>' . $value . '</option>';
                                        }
                                        foreach (json_decode($get_custom_menus, true) as $customMenu) {
                                            $selected = ($pageGroup ==  $customMenu['menuID']) ? 'selected' : '';
                                            $tablegroups .= '<option value="' . $customMenu['menuID'] . '" '.$selected.'>' . $customMenu['customGroupName'] . '</option>';
                                        }
                                        $getusergroups = sql("SELECT name FROM `membership_groups`", $eo); // get all groups
                                        $usergroups = "";
                                        foreach ($getusergroups as $key => $value) {
                                            # code...
                                            if(!empty($edit)){
                                                $selected = strpos($pageAccess, $value['name']) !== false ? 'selected' : '';
                                            }
                                           
                                            $usergroups .= '<option value="' . $value['name'] . '" '.$selected.'>' . $value['name'] . '</option>';
                                        }
                                        ?>
                                        <form action="" method="POST">
                                            <div class="form-group">
                                                <label for="pageName"><?php echo $translate['View Name']; ?></label>
                                                <input type="text" class="form-control" id="pageName" name="pageName"
                                                    placeholder="<?php echo $translate['Enter Table View Name']; ?>" value="<?php echo $pageName; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="pageIcon"><?php echo $translate['Menu Icon']; ?></label>
                                                <input type="text" class="form-control" id="pageIcon" name="pageIcon"
                                                    placeholder="<?php echo $translate['Enter Font Awesome Icon']; ?>" value="<?php echo $pageIcon; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="pageGroup"><?php echo $translate['Select Table Group']; ?></label>
                                                <select class="form-control" id="pageGroup" name="pageGroup" required>
                                                    <option value="None"><?php echo $translate['None']; ?></option>
                                                   <?php echo $tablegroups; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="pageAccess"><?php echo $translate['View Group Access']; ?></label>
                                                <select class="form-control" id="pageAccess" name="pageAccess[]"
                                                    multiple required>
                                                    <option value="*"><?php echo $translate['All']; ?></option>
                                                    <?php echo $usergroups; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="hidenavmenu"><?php echo $translate['hide_in_nav_menu']; ?></label>
                                                <select class="form-control" id="hidenavmenu" name="hidenavmenu"
                                                    required>
                                                    <option value="Yes" <?php echo ($hidenavmenu == 'Yes') ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                    <option value="No" <?php echo ($hidenavmenu == 'No') ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="querycode"><?php echo $translate['Database Query (Must begin with: SELECT)']; ?></label>
                                               <br> <small><?php echo $translate['db_view_query_description']; ?></small>
                                                <textarea class="form-control" id="sql" name="sql"
                                                    placeholder="<?php echo $translate['Enter sql query']; ?>"><?php echo $sql; ?></textarea>
                                            </div>
                                            <?php if(empty($edit)){ ?><button type="submit" class="btn btn-primary" name="createtableview"><?php echo $translate['Create Table View']; ?></button>
                                            <?php } else{ ?>
                                                <button type="submit" class="btn btn-primary" name="edittableview"><?php echo $translate['Edit Table View']; ?></button>
                                                <?php } ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
if (isset($_POST['createtableview'])) {
    $pageAccess = $_POST['pageAccess'];
    $selectedgroups = '';
    foreach ($pageAccess as $selectedvalue) {
        # code...
        $selectedgroups .= $selectedvalue . ',';
    }
    $_POST['pageAccess'] = rtrim($selectedgroups, ',');
    $_POST['FileName'] = time().'_tview';
    $jenc = json_encode($_POST);
    $query = $_POST['sql'];
    //run sql to create the table view 
    $runsql = sql("CREATE VIEW {$_POST['FileName']} AS {$query}",$eo);
    if(!$runsql){
        echo showAlert('danger', $translate['Error Creating Table View'], $translate['create_view_error_description']);
        exit;
    }
    //write to appginlte/custompages/pagesdata/time.json
    file_put_contents('appginilte/custompages/pagesdata/' . $_POST['FileName'] . '.json', $jenc);
    //page dummy content
    $pagedummycontent = '<!-- Write your custom page content in here, it could be anything and everything, you can also use AppginiLTE widget functions here-->
    <?php
    $page = $_GET[\'page\'];
    $pagedata = file_get_contents(\'appginilte/custompages/pagesdata/\'.$page.\'.json\');
    $decodepagedata = json_decode($pagedata, true);
    $page_name = $decodepagedata[\'pageName\'];
    ?> 
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $page_name; ?></h3>
                </div>
                <div class="card-body">
                    <?php echo dataTable($page_name,sql("SELECT * FROM {$page}",$eo),\'col-md-12\',\'Yes\',\'Yes\',\'Yes\',\'Yes\',\'Yes\',0,\'desc\',50); ?>
                </div>
            </div>
        </div>
    </div>';
    //write to appginlte/custompages/time.php
    file_put_contents('appginilte/custompages/' . $_POST['FileName'] . '.php', $pagedummycontent);
    echo showToast('success', $translate['view_created'], $translate['view_created_details']) . '<meta http-equiv="refresh" content="2;url=appginilte_table-views-builder.php">';
}
if (isset($_POST['edittableview'])) {
    $pageAccess = $_POST['pageAccess'];
    $selectedgroups = '';
    foreach ($pageAccess as $selectedvalue) {
        # code...
        $selectedgroups .= $selectedvalue . ',';
    }
    $_POST['pageAccess'] = rtrim($selectedgroups, ',');
    $_POST['FileName'] = $_GET['edit'];
    $jenc = json_encode($_POST);
    $query = $_POST['sql'];
    //run sql to create the table view 
    $runsql = sql("CREATE OR REPLACE VIEW {$_POST['FileName']} AS {$query}",$eo);
    if(!$runsql){
        echo showAlert('danger', $translate['Error Updating Table View'], $translate['Error Updating Table View Description']);
        exit;
    }
    //write to appginlte/custompages/pagesdata/$_POST['FileName'].json
    file_put_contents('appginilte/custompages/pagesdata/' . $_POST['FileName'] . '.json', $jenc);
    echo showToast('success', $translate['view_edited'], $translate['view_edited_successfully']) . '<meta http-equiv="refresh" content="2;url=appginilte_table-views-builder.php">';
}
if (isset($_GET['delete'])) {
    $filename = $_GET['delete'] . '.php';
    $filenamejson = $_GET['delete'] . '.json';
    unlink('appginilte/custompages/' . $filename);
    unlink('appginilte/custompages/pagesdata/' . $filenamejson);
    sql("DROP VIEW {$_GET['delete']}", $eo);
    echo showToast('success', $translate['view_deleted'], $translate['view_deleted_successfully']) . '<META HTTP-EQUIV="refresh" CONTENT="3;url=appginilte_table-views-builder.php">';
}
?>
<script>
    document.title = '<?php echo $appName . '|' . $translate['table views builder']; ?>';
    function confirmDelete() {
  // show a confirm dialog
  var choice = confirm("<?php echo $translate['Do you really want to delete this table view?']; ?>");

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