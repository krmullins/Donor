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
                        <li class="breadcrumb-item active">
                            <?php echo $translate['dashboard builder']; ?>
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
                            <?php echo $translate['dashboard builder']; ?>
                        </h3>
                    </div>
                    <div class="card-body">
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
                        <div class="row">
                            <!-- col-8 -->
                            <div class="col-md-10">
                                <div class="btn-group float-right">
                                    <a href="admin/pageSQL.php" target="_blank" type="button"
                                        class="btn btn-secondary"><span class="fas fa-database"></span>
                                        <?php echo $translate['interactive_sql_queries_tool']; ?>
                                    </a>
                                    <a href=" https://portals.docsie.io/ronthedev/ronthedev-docs/ronthedev-docs-platinum/appginilte-pro/deployment_9gnlh6Jl5RYLgSnjV/?doc=/"
                                        target="_blank" type="button" class="btn btn-primary"><span
                                            class="fas fa-book"></span>
                                        <?php echo $translate['appginilte_documentation']; ?>
                                    </a>
                                    <a href="https://www.youtube.com/playlist?list=PLLOittZfPCp8nlGoHfulFS8yfnWwmHYfp"
                                        target="_blank" type="button" class="btn btn-danger"><span
                                            class="fab fa-youtube"></span>
                                        <?php echo $translate['Youtube Tutorials']; ?>
                                    </a>
                                </div>
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <button class="btn btn-warning" type="submit" name="submit"><span
                                                class="fa fa-save"></span>
                                            <?php echo $translate['save_code_changes']; ?>
                                        </button>
                                    </div>
                                    <div>
                                        <?php
                                        $getpage = (empty($_GET['page'])) ? 'Admins_Top.php' : $_GET['page'];
                                        if (strpos($getpage, 'Bottom')) {
                                            $position = $translate['Bottom'];
                                        } else {
                                            $position = $translate['Top'];
                                        }
                                        echo showAlert('success', '' . $getpage . ' ' . $translate['Page'], str_replace('<position>', $position, $translate['page_pos_desc'])); ?>
                                    </div>
                                    <div class="form-group">
                                        <textarea id="code" name="code" class="code">
                                        <?php echo file_get_contents('appginilte/views/' . $getpage); ?>
                                    </textarea>
                                    </div>
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
                            <!-- col-8 -->
                            <!-- col-2 -->
                            <div class="col-md-2">
                                <!-- NEW CARD -->
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title"
                                            style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                                            <?php echo $translate['Groups Dashboard Pages']; ?>
                                        </h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body" style="min-height: 5rem;max-height: 70vh;overflow-y:auto">
                                        <?php
                                        if ($handle = opendir('./appginilte/views')) {

                                            while (false !== ($entry = readdir($handle))) {

                                                if ($entry != "." && $entry != ".." && $entry != "index.html") {
                                                    #code...
                                                    //random card colors
                                                    $colors = array('primary', 'secondary', 'success', 'danger', 'warning', 'info', 'maroon', 'dark');
                                                    $rand_color = $colors[array_rand($colors)];
                                                    echo '<div class="card card-' . $rand_color . ' card-outline">
                                                        <div class="card-header">
                                                            <p class="card-title" style=" white-space: nowrap; 
                                                            width: 100%; 
                                                            overflow: hidden;
                                                            text-overflow: ellipsis;font-size: 14px;"><b>' . $entry . '</b></p>
                                                            <div class="card-tools">
                                                                <a href="?page=' . $entry . '" class="btn btn-tool" data-toggle="tooltip" title="' . $entry . '">
                                                                    <i class="far fa-edit"></i>
                                                                </a>
                                                                <a href="?delete=' . $entry . '" class="btn btn-tool" style="text-decoration:none;color:red" onclick="return confirmDelete();"><span class="far fa-trash-alt"></span></a>
                                                            </div>
                                                        </div>
                                                    </div>';
                                                }
                                            }
                                            closedir($handle);
                                        }
                                        ?>
                                    </div>
                                </div>
                                <!-- NEW CARD -->
                            </div>
                            <!-- col-2 -->
                        </div>
                        <?php
                        if (isset($_POST['submit'])) {
                            # code...
                            $new = $_POST['code'];
                            $myfile = fopen("appginilte/views/$getpage", "w") or die("Unable to open file!");
                            fwrite($myfile, $new);
                            fclose($myfile);
                            echo showToast('success', $translate['Code Changes Saved Successfully'], str_replace('<getpage>', $getpage, $translate['code_changes_saved_desc'])) . '<META HTTP-EQUIV="refresh" CONTENT="3">';
                        }
                        ?>
                        <!-- CODE MIRROR ABOVE -->
                    </div>
                </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
if (isset($_GET['delete'])) {
    $filename = $_GET['delete'];
    unlink('appginilte/views/' . $filename);
    echo showToast('success', $translate['page_deleted'], $translate['page_deleted_successfully']) . '<META HTTP-EQUIV="refresh" CONTENT="3;url=appginilte_dash-builder.php">';
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