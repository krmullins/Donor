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
$page = $_REQUEST['edit'];
?>
<!-- CODE MIRROR BELOW -->
<link rel=stylesheet href="appginilte/codemirror/lib/codemirror.css">
<link rel=stylesheet href="appginilte/codemirror/doc/docs.css">
<script src="appginilte/codemirror/lib/codemirror.js"></script>
<script src="appginilte/codemirror/mode/xml/xml.js"></script>
<script src="appginilte/codemirror/mode/javascript/javascript.js"></script>
<script src="appginilte/codemirror/mode/php/php.js"></script>
<script src="appginilte/codemirror/mode/css/css.js"></script>
<script src="appginilte/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="appginilte/codemirror/addon/edit/matchbrackets.js"></script>
<link rel="stylesheet" href="appginilte/codemirror/theme/monokai.css">
<style>
    .CodeMirror {
        height: auto;
        border: 1px solid #ddd;
    }

    .CodeMirror-scroll {
        max-height: 100%;
    }

    .CodeMirror pre {
        padding-left: 7px;
        line-height: 1.25;
    }

    .banner {
        background: #ffc;
        padding: 6px;
        border-bottom: 2px solid silver;
    }

    .banner div {
        margin: 0 auto;
        max-width: 700px;
        text-align: center;
    }

    .code {
        border-radius: 6px;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
        font-family: 'Source Code Pro', monospace;
        font-size: 14px;
        font-weight: 400;
        height: 340px;
        letter-spacing: normal;
        line-height: 20px;
        padding: 10px;
        tab-size: 4;
    }

    a,
    a:visited,
    a:link,
    .quasilink {
        color: black;
        text-decoration: none;
    }

    section {
        border-top: 1px solid #f8f8f8;
        background-color: #f8f8f8;
    }
</style>
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
                            <?php echo $translate['custom_page_builder']; ?>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
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
                            <!-- tables -->
                            <div>
                                <div>
                                    <ul class="nav flex-column">
                                        <!-- Read and get files from appginilte/custompages/pagesdata get json files and extract info for each -->
                                        <?php
                                        $files = scandir('appginilte/custompages/pagesdata');
                                        $count = 0;
                                        foreach ($files as $file) {
                                            if ($file != '.' && $file != '..' && $file != "index.html" && $file != " " && strpos($file, '_tview') == false) {
                                                $count++;
                                                $file_contents = file_get_contents('appginilte/custompages/pagesdata/' . $file);
                                                $file_contents = json_decode($file_contents, true);
                                                ?>
                                                <li class="nav-item"><span class="fas fa-file-code"></span>
                                                    <?php echo $file_contents['pageName']; ?>
                                                    <div class="btn-group float-right">
                                                        <a href="?edit=<?php echo $file_contents['FileName']; ?>" type="button"
                                                            class="btn btn-success btn-xs"><span class="far fa-edit"></span></a>
                                                        <a href="?delete=<?php echo $file_contents['FileName']; ?>"
                                                            onclick="return confirmDelete();" type="button"
                                                            class="btn btn-danger btn-xs"><span
                                                                class="far fa-trash-alt"></span></a>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                        }
                                        if ($count == 0) {
                                            echo showAlert('danger', $translate['no_custom_pages_found'], $translate['no_custom_pages_found_description']);
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <!-- tables -->
                        </div>

                    </div>
                </div>
                <div class="col-md-8">
                    <!-- create html form with the following fields: page name, page icon, table group:drop down, groups that can access page -->
                    <?php
                    $groups = get_table_groups();
                    $array_keys = array_keys($groups);
                    $get_custom_menus = file_get_contents('appginilte/jsondb/custom_table_menus.json');
                    //loop through the groups
                    $tablegroups = "";
                    foreach ($array_keys as $key => $value) {
                        # code...
                        $tablegroups .= '<option value="' . $key . '">' . $value . '</option>';
                    }
                    foreach (json_decode($get_custom_menus, true) as $customMenu) {
                        $tablegroups .= '<option value="' . $customMenu['menuID'] . '">' . $customMenu['customGroupName'] . '</option>';
                    }
                    $getusergroups = sql("SELECT name FROM `membership_groups`", $eo); // get all groups
                    $usergroups = "";
                    foreach ($getusergroups as $key => $value) {
                        # code...
                        $usergroups .= '<option value="' . $value['name'] . '">' . $value['name'] . '</option>';
                    }
                    $createpageform = '
                    <form action="appginilte_page-builder.php" method="post">
                        <div class="form-group">
                            <label for="pageName">' . $translate['page_name'] . '</label>
                            <input type="text" class="form-control" id="pageName" name="pageName" placeholder="' . $translate['page_name'] . '" required>
                        </div>
                        <div class="form-group">
                            <label for="pageIcon">' . $translate['page_icon'] . '</label>
                            <input type="text" class="form-control" id="pageIcon" name="pageIcon" placeholder="' . $translate['Page Font Awosome Icon'] . '" required>
                        </div>
                        <div class="form-group">
                            <label for="pageGroup">' . $translate['page_table_group'] . '</label>
                            <select class="form-control" id="pageGroup" name="pageGroup" required>
                            <option value="None">' . $translate['None'] . '</option>
                                ' . $tablegroups . '
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pageAccess">' . $translate['page_group_access'] . '</label>
                            <select class="form-control" id="pageAccess" name="pageAccess[]" multiple required>
                                <option value="*">' . $translate['All'] . '</option>
                                ' . $usergroups . '
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="hidenavmenu">' . $translate['hide_in_nav_menu'] . '</label>
                            <select class="form-control" id="hidenavmenu" name="hidenavmenu" required>
                                <option value="No">' . $translate['no'] . '</option>
                                <option value="Yes">' . $translate['yes'] . '</option>
                            </select>
                        </div> 
                        <div class="form-group">
                            <label for="CustomLink">' . $translate['page_custom_link'] . '</label>
                            <div class="alert alert-info">
                                <p>' . $translate['custom_link_instructions'] . '</p>
                            </div>
                            <input type="text" class="form-control" id="CustomLink" name="CustomLink" placeholder="' . $translate['Enter custom link for this page.'] . '">
                        </div>
                        <button type="submit" class="btn btn-primary" name="createpage">' . $translate['create_page'] . '</button>
                    </form>';
                    $filename = $page . '.json';
                    //get file conents from appginilte/custompages
                    $file_contents = file_get_contents('appginilte/custompages/pagesdata/' . $filename);
                    $efile_contents = json_decode($file_contents, true);
                    $pageGroup = $efile_contents['pageGroup'];
                    $pageAccess = $efile_contents['pageAccess'];
                    $FileName = $efile_contents['FileName'];
                    $hidenavmenu = $efile_contents['hidenavmenu'];
                    $etablegroups = "";
                    foreach ($array_keys as $key => $value) {
                        # code...
                        $etablegroups .= '<option value="' . $key . '" ' . (is_numeric($pageGroup) && $pageGroup == $key ? 'selected' : '') . '>' . $value . '</option>';
                    }
                    foreach (json_decode($get_custom_menus, true) as $customMenu) {
                        $etablegroups .= '<option value="' . $customMenu['menuID'] . '" ' . ($pageGroup == $customMenu['menuID'] ? 'selected' : '') . '>' . $customMenu['customGroupName'] . '</option>';
                    }
                    $eusergroups = "";
                    foreach ($getusergroups as $key => $value) {
                        # code...
                        $eusergroups .= '<option value="' . $value['name'] . '" ' . (strpos($pageAccess, $value['name']) !== false ? 'selected' : '') . '>' . $value['name'] . '</option>';
                    }

                    //if edit is set then show the edit page form
                    $editpageform = '
                    <form action="appginilte_page-builder.php" method="post">
                    <input type="hidden" name="FileName" value="' . $FileName . '">
                        <div class="form-group">
                            <label for="pageName">' . $translate['page_name'] . '</label>
                            <input type="text" class="form-control" id="pageName" name="pageName" placeholder="' . $translate['page_name'] . '" value="' . $efile_contents['pageName'] . '" required>
                        </div>
                        <div class="form-group">
                            <label for="pageIcon">' . $translate['page_icon'] . '</label>
                            <input type="text" class="form-control" id="pageIcon" name="pageIcon" placeholder="' . $translate['Page Font Awosome Icon'] . '" value="' . $efile_contents['pageIcon'] . '" required>
                        </div>
                        <div class="form-group">
                            <label for="pageGroup">' . $translate['page_table_group'] . '</label>
                            <select class="form-control" id="pageGroup" name="pageGroup" required>
                            <option value="None" ' . ($pageGroup == 'None' ? 'selected' : '') . '>None</option>
                                ' . $etablegroups . '
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pageAccess">' . $translate['page_group_access'] . '</label>
                            <select class="form-control" id="pageAccess" name="pageAccess[]" multiple required>
                                <option value="*" ' . (strpos($pageAccess, '*') !== false ? 'selected' : '') . '>All</option>
                                ' . $eusergroups . '
                            </select>
                        </div>
                        <div class="form-group">
                        <label for="hidenavmenu">' . $translate['hide_in_nav_menu'] . '</label>
                        <select class="form-control" id="hidenavmenu" name="hidenavmenu" required>
                            <option value="No" ' . ($hidenavmenu == 'No' ? 'selected' : '') . '>' . $translate['no'] . '</option>
                            <option value="Yes" ' . ($hidenavmenu == 'Yes' ? 'selected' : '') . '>' . $translate['yes'] . '</option>
                        </select>
                    </div> 
                        <div class="form-group">
                            <label for="CustomLink">' . $translate['page_custom_link'] . '</label>
                            <div class="alert alert-info">
                                <p>' . $translate['custom_link_instructions'] . '</p>
                            </div>
                            <input type="text" class="form-control" id="CustomLink" name="CustomLink" placeholder="' . $translate['Enter custom link for this page.'] . '" value="' . $efile_contents['CustomLink'] . '">
                        </div>
                        <button type="submit" class="btn btn-primary" name="editpage">' . $translate['save_page_changes'] . '</button>
                    </form>';

                    if (empty($page)) {
                        echo showModal($translate['create_custom_page'], $createpageform, $translate['custom_page_builder_by_appgini'], $translate['create_custom_page'], 'btn btn-success', 'fas fa-plus-circle', 'btn-md') .'<a href="https://portals.docsie.io/ronthedev/ronthedev-docs/ronthedev-docs-platinum/appginilte-pro/deployment_9gnlh6Jl5RYLgSnjV/?doc=/advanced-code-snippets/" target="_blank" class="btn btn-dark"><span class="fas fa-info-circle"></span> ' . $translate['advanced_code_snippets'] . '</a>' .'<hr>';
                        echo showAlert('danger', $translate['no_page_selected'], $translate['please_select_a_page_to_edit']);
                        ?>
                        <!-- Logic for custom table group menus -->
                        <div class="container">
                            <?php
                            $creategroupform = '<form action="appginilte_page-builder.php" method="POST">
                                <div class="form-group">
                                    <label for="pageName">' . $translate['group_name'] . '</label>
                                    <input type="text" class="form-control" id="customGroupName" name="customGroupName" placeholder="' . $translate['group_name'] . '" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="pageIcon">' . $translate['group_icon'] . '</label>
                                    <input type="text" class="form-control" id="customGroupIcon" name="customGroupIcon" placeholder="' . $translate['group_icon'] . '" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="menu_always_collapsed">' . $translate['menu_always_collapsed'] . '</label>
                                    <select class="form-control" id="customGroupCollapsed" name="customGroupCollapsed" required>
                                        <option value="No">' . $translate['no'] . '</option>
                                        <option value="Yes">' . $translate['yes'] . '</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="hidenavmenu">' . $translate['hide_in_nav_menu'] . '</label>
                                    <select class="form-control" id="CustomGroupHidenavmenu" name="CustomGroupHidenavmenu" required>
                                        <option value="No">' . $translate['no'] . '</option>
                                        <option value="Yes">' . $translate['yes'] . '</option>
                                    </select>
                                </div> 
                            <button type="submit" class="btn btn-primary" name="createCustomMenu">' . $translate['save_custom_page_group'] . '</button>
                            </form>';
                            echo showModal($translate['create_custom_table_group_menu'], $creategroupform, $translate['create_custom_table_group_menu'], $translate['create_custom_table_group_menu'], 'btn btn-warning', 'fas fa-plus-circle', 'btn-md') . '<hr>';
                            if (empty(json_decode($get_custom_menus, true))) {
                                echo showAlert('danger', $translate['no_custom_table_menus'], $translate['you_do_not_have_custom_table_menus_you_can_create']);
                            } else {
                                $custom_menus = json_decode($get_custom_menus, true);
                                //loop therough the custom menus
                                $menuItem = '';
                                foreach ($custom_menus as $cmenu) {
                                    $custom_menu_id = $cmenu['menuID'];
                                    $custom_menu_name = $cmenu['customGroupName'];
                                    $custom_menu_icon = $cmenu['customGroupIcon'];
                                    $custom_menu_collapsed = $cmenu['customGroupCollapsed'];
                                    $custom_menu_hidenav = $cmenu['CustomGroupHidenavmenu'];
                                    $editgroupform = '<form action="appginilte_page-builder.php" method="POST">
                                    <div class="form-group">
                                        <label for="pageName">' . $translate['group_name'] . '</label>
                                        <input type="text" class="form-control" id="customGroupName" name="customGroupName" placeholder="' . $translate['group_name'] . '" value="' . $custom_menu_name . '" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="pageIcon">' . $translate['group_icon'] . '</label>
                                        <input type="text" class="form-control" id="customGroupIcon" name="customGroupIcon" placeholder="' . $translate['group_icon'] . '" value="' . $custom_menu_icon . '" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="menu_always_collapsed">' . $translate['menu_always_collapsed'] . '</label>
                                        <select class="form-control" id="customGroupCollapsed" name="customGroupCollapsed" required>
                                            <option value="No" ' . ($custom_menu_collapsed == 'No' ? 'selected' : '') . '>' . $translate['no'] . '</option>
                                            <option value="Yes" ' . ($custom_menu_collapsed == 'Yes' ? 'selected' : '') . '>' . $translate['yes'] . '</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="hidenavmenu">' . $translate['hide_in_nav_menu'] . '</label>
                                        <select class="form-control" id="CustomGroupHidenavmenu" name="CustomGroupHidenavmenu" required>
                                            <option value="No" ' . ($custom_menu_hidenav == 'No' ? 'selected' : '') . '>' . $translate['no'] . '</option>
                                            <option value="Yes" ' . ($custom_menu_hidenav == 'Yes' ? 'selected' : '') . '>' . $translate['yes'] . '</option>
                                        </select>
                                    </div> 
                                    <input type="hidden" class="form-control" id="menuID" name="menuID" value="' . $custom_menu_id . '">
                                <button type="submit" class="btn btn-primary" name="editCustomMenu">' . $translate['save_custom_page_group'] . '</button>
                                </form>';
                                    $menuItem .= '<div class="card card-dark card-outline">
                                    <div class="card-header">
                                        <h5 class="card-title">' . $custom_menu_name . '</h5>
                                        <div class="card-tools">
                                            ' . showModal($translate['edit_custom_table_group'], $editgroupform, '', '', 'btn btn-tool', 'fas fa-pen', '') . '
                                            <a href="?deletemenu=' . $custom_menu_id . '" class="btn btn-tool" onclick="return confirmDelete();">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>';
                                }
                                ?>
                                <div class="card card-row card-primary"
                                    style="min-height: 5rem;max-height: 70vh;overflow-y: auto;">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <?php echo $translate['my_custom_menus']; ?>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <?php echo $menuItem; ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <!-- Logic for custom table gorup menus -->
                        <?php
                    } else {
                        $filename = $page . '.php';
                        //get file conents from appginilte/custompages
                        $file_contents = file_get_contents('appginilte/custompages/' . $filename);
                        //display conents in a text area
                        echo '<h6><b>' . $efile_contents['pageName'] . '</b></h6>';
                        if (strpos($_GET['edit'], '_tview') == false) {
                            echo showModal($translate['edit_custom_page'], $editpageform, $translate['custom_page_builder_by_appgini'], $translate['edit_custom_page_settings'], 'btn btn-success btn-bock', 'far fa-edit', 'btn-md') . '<hr>';
                        }

                        $editareaform = '
                        <div class="form-group">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-success" name="savecodechanges"><span class="fa fa-save"></span> ' . $translate['save_code_changes'] . '</button>
                                <a href="appginilte_page-builder.php" type="button" class="btn btn-warning"><span class="fa fa-home"></span> ' . $translate['page_builder_home'] . '</a>
                                <a href="admin/pageSQL.php" target="_blank" type="button" class="btn btn-info"><span class="fas fa-database"></span> ' . $translate['interactive_sql_queries_tool'] . '</a>
                                <a href="https://portals.docsie.io/ronthedev/ronthedev-docs/ronthedev-docs-platinum/appginilte-pro/deployment_9gnlh6Jl5RYLgSnjV/?doc=/" target="_blank" type="button" class="btn btn-danger"><span class="fas fa-book"></span> ' . $translate['appginilte_documentation'] . '</a>
                            </div>
                        </div>
                            <div class="form-group"><textarea name="code" id="code" class="code">' . $file_contents . '</textarea></div>';
                    }
                    ?>
                    <form action="" method="POST">
                        <?php echo $editareaform; ?>
                    </form>
                    <script>
                        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
                            lineNumbers: true,
                            mode: "text/html",
                            matchBrackets: true,
                            indentUnit: 4,
                            indentWithTabs: true
                        });
                        editor.setOption("theme", "monokai");
                    </script>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
if (isset($_POST['createpage'])) {
    $pageAccess = $_POST['pageAccess'];
    $selectedgroups = '';
    foreach ($pageAccess as $selectedvalue) {
        # code...
        $selectedgroups .= $selectedvalue . ',';
    }
    $_POST['pageAccess'] = rtrim($selectedgroups, ',');
    $_POST['FileName'] = time();
    $jenc = json_encode($_POST);
    //write to appginlte/custompages/pagesdata/time.json
    file_put_contents('appginilte/custompages/pagesdata/' . $_POST['FileName'] . '.json', $jenc);
    //page dummy content
    $pagedummycontent = '<!-- Write your custom page content in here, it could be anything and everything, you can also use AppginiLTE widget functions here-->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Page Title</h3>
                </div>
                <div class="card-body">
                    <p>Page Content</p>
                </div>
            </div>
        </div>
    </div>';
    //write to appginlte/custompages/time.php
    file_put_contents('appginilte/custompages/' . $_POST['FileName'] . '.php', $pagedummycontent);
    echo showToast('success', $translate['page_created'], $translate['page_created_successfully']) . '<meta http-equiv="refresh" content="2;url=appginilte_page-builder.php">';
}
if (isset($_POST['editpage'])) {
    $pageAccess = $_POST['pageAccess'];
    $FileName = $_POST['FileName'];
    $selectedgroups = '';
    foreach ($pageAccess as $selectedvalue) {
        # code...
        $selectedgroups .= $selectedvalue . ',';
    }
    $_POST['pageAccess'] = rtrim($selectedgroups, ',');
    $jenc = json_encode($_POST);
    //write to appginlte/custompages/pagesdata/time.json
    file_put_contents('appginilte/custompages/pagesdata/' . $FileName . '.json', $jenc);
    echo showToast('success', $translate['page_edited'], $translate['page_edited_successfully']) . '<META HTTP-EQUIV="refresh" CONTENT="3">';
}
if (isset($_GET['delete'])) {
    $filename = $_GET['delete'] . '.php';
    $filenamejson = $_GET['delete'] . '.json';
    unlink('appginilte/custompages/' . $filename);
    unlink('appginilte/custompages/pagesdata/' . $filenamejson);
    echo showToast('success', $translate['page_deleted'], $translate['page_deleted_successfully']) . '<META HTTP-EQUIV="refresh" CONTENT="3;url=appginilte_page-builder.php">';
}
if (isset($_POST['savecodechanges'])) {
    $filename = $page . '.php';
    $code_contents = $_POST['code'];
    $myfile = fopen('appginilte/custompages/' . $filename, "w") or die("Unable to open file!");
    fwrite($myfile, $code_contents);
    fclose($myfile);
    echo showToast('success', $translate['page_saved'], $translate['page_saved_successfully']) . '<META HTTP-EQUIV="refresh" CONTENT="3">';
}
if (isset($_POST['createCustomMenu'])) {
    $customGroupName = $_POST['customGroupName'];
    $customGroupIcon = $_POST['customGroupIcon'];
    $customGroupCollapsed = $_POST['customGroupCollapsed'];
    $CustomGroupHidenavmenu = $_POST['CustomGroupHidenavmenu'];
    $menuID = "cpm_" . time();
    $_POST['menuID'] = $menuID;
    //get file contents
    $custom_menus = file_get_contents('appginilte/jsondb/custom_table_menus.json');
    $allMenus = [];
    if (empty($custom_menus)) {
        $allMenus[] = $_POST;
        $encodeAllMenus = json_encode($allMenus, true);
        file_put_contents('appginilte/jsondb/custom_table_menus.json', $encodeAllMenus);
        echo showToast('success', $translate['custom_menu_saved'], $translate['custom_menu_saved_success']) . '<META HTTP-EQUIV="refresh" CONTENT="3">';
    } else {
        $allMenus = json_decode($custom_menus);
        $allMenus[] = $_POST;
        $encodeAllMenus = json_encode($allMenus, true);
        file_put_contents('appginilte/jsondb/custom_table_menus.json', $encodeAllMenus);
        echo showToast('success', $translate['custom_menu_saved'], $translate['custom_menu_saved_success']) . '<META HTTP-EQUIV="refresh" CONTENT="3">';
    }
}
if (isset($_POST['editCustomMenu'])) {
    $customGroupName = $_POST['customGroupName'];
    $customGroupIcon = $_POST['customGroupIcon'];
    $customGroupCollapsed = $_POST['customGroupCollapsed'];
    $CustomGroupHidenavmenu = $_POST['CustomGroupHidenavmenu'];
    $menuID = $_POST['menuID'];
    $custom_menus = file_get_contents('appginilte/jsondb/custom_table_menus.json');
    // Convert the JSON object to an array
    $array = json_decode($custom_menus, true);

    // The menuID of the item to update
    $menuID_to_update = $menuID;

    // The new values to update for the item
    $new_values = array(
        'customGroupName' => $customGroupName,
        'customGroupIcon' => $customGroupIcon,
        'customGroupCollapsed' => $customGroupCollapsed,
        'CustomGroupHidenavmenu' => $CustomGroupHidenavmenu
    );

    // Loop through the array and update the item with the specified menuID
    foreach ($array as $key => $value) {
        if ($value['menuID'] == $menuID_to_update) {
            // Update the values of the item with the specified menuID
            $array[$key] = array_merge($value, $new_values);
        }
    }

    // Convert the updated array back to JSON format
    $json = json_encode($array);
    file_put_contents('appginilte/jsondb/custom_table_menus.json', $json);
    echo showToast('success', $translate['custom_menu_saved'], $translate['custom_menu_saved_success']) . '<META HTTP-EQUIV="refresh" CONTENT="3">';
}
if (isset($_REQUEST['deletemenu'])) {
    $deletemenu = $_GET['deletemenu'];
    $custom_menus = file_get_contents('appginilte/jsondb/custom_table_menus.json');
    // Convert the JSON object to an array
    $array = json_decode($custom_menus, true);

    // Loop through the array and find the element with the specified menuID
    foreach ($array as $key => $value) {
        if ($value['menuID'] == $deletemenu) {
            // Remove the element with the specified menuID
            unset($array[$key]);
        }
    }
    // Convert the updated array back to JSON format
    $json = json_encode(array_values($array));
    file_put_contents('appginilte/jsondb/custom_table_menus.json', $json);
    echo showToast('success', $translate['custom_menu_deleted'], $translate['custom_menu_deleted_success']) . '<META HTTP-EQUIV="refresh" CONTENT="3;URL=appginilte_page-builder.php">';
}
?>
<script>
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