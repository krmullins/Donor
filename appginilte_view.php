<?php
define("PREPEND_PATH", "");
$hooks_dir = dirname(__FILE__);
include("defaultLang.php");
include("language.php");
include("lib.php");
include 'appginilte_header.php';
$page = $_REQUEST['page'];
$page_title = substr($page, 0, strpos($page, ".php")) ?: $page;
if (strpos($page_title, "_view")) {
    # code...
    $rtrim_page_title = preg_replace('/_view$/', '', $page_title);
    $json = json_encode(get_tables_info(true));
    $decode = json_decode($json, true);
    $page_title = $decode[$rtrim_page_title]['Caption'];
}
//remove unwanted characters
$page_title = str_replace(array('-', '/', '_', 'appginilte', 'plugins'), ' ', $page_title);
//capitalize first letter of each word
$page_title = ucwords($page_title);
?>
<script>
    document.title = '<?php echo $appName . '|' . ucwords(str_replace("_", " ", $page_title)); ?>';
</script>
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
                            <?php echo ucwords(str_replace("_", " ", $page_title)); ?>
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
                <div class="card card-outline card-<?= strtolower($brand_logo_variant); ?>">
                    <div class="card-header d-print-none">
                        <h3 class="card-title">
                            <i class="fas fa-tag"></i>
                            <?php echo ucwords(str_replace("_", " ", $page_title)); ?>
                        </h3>
                        <div class="text-right">
                            <div class="btn-group">
                                <?php if (userCanImport()) { ?>
                                    <a type="button" class="btn btn-default" href="appginilte_view.php?page=import-csv.php?Embedded=true">
                                        <i class="fas fa-file-import"></i>
                                    </a>
                                <?php } ?>
                                <button type="button" class="btn btn-default" onclick="document.getElementById('child-iframe').contentWindow.location.reload();">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <a type="button" class="btn btn-default" onclick="window.history.back();">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="theframeparent">
                        <iframe class="responsive-iframe" src="<?php echo $page; ?>" id="child-iframe" scrolling="no"></iframe>
                    </div>
                    <script>
                        let iframe = document.getElementById('child-iframe');
                        iframe.addEventListener('load', function() {
                            setInterval(function() {
                                var height = iframe.contentDocument.body.scrollHeight;
                                var width = iframe.contentDocument.body.scrollWidth;
                                if (height < 900) {
                                    iframe.style.height = '900px';
                                } else {
                                    iframe.style.height = height + 'px';
                                }
                                iframe.style.width = '100%';
                            }, 100);
                        });
                        $(window).resize(function() {
                            //make iframe full width and responsive
                            setInterval(function() {
                                var height = iframe.contentDocument.body.scrollHeight;
                                var width = $(this).width();
                                if (height < 900) {
                                    iframe.style.height = '900px';
                                } else {
                                    iframe.style.height = height + 'px';
                                }
                                iframe.style.width = '100%';
                            }, 100);
                        });
                        // Listen for changes to the side menu
                        const toggleBtn = document.getElementById("toggleBtnId");
                        toggleBtn.addEventListener("click", function() {
                            // Set the width of the iframe to match the parent div
                            iframe.style.width = '100%';
                        });
                    </script>
                </div>
            </div>
    </section>
    <!-- /.content -->
    <!-- Modal For rendering Edit Views -->
    <script>
        function launchEditModal(tn, recID) {
            // Get the modal element
            const Theemodal = jQuery('#modal-xl');

            // Create a new Bootstrap modal object
            const modal = new bootstrap.Modal(Theemodal[0]);

            // Set the URL for the iframe
            const url = `${tn}_view.php?SelectedID=${recID}`;

            // Create a new iframe element
            const iframe = document.createElement('iframe');

            // Set the attributes for the iframe
            iframe.src = url;
            iframe.style.border = 'none';
            iframe.style.width = '100%';

            // Add an event listener to the iframe to adjust its height
            iframe.addEventListener('load', function() {
                iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
            });

            // Get the modal body element
            const modalBody = Theemodal.find('.modal-body')[0];

            // Clear the modal body and append the iframe
            modalBody.innerHTML = '';
            modalBody.appendChild(iframe);

            // Display the modal
            modal.show();
        }
    </script>
    <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <?php echo $translate['Edit Table Record']; ?>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">
                        <?php echo $translate['Close']; ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal For rendering Edit Views -->
</div>
<!-- /.content-wrapper -->
<?php include 'appginilte_footer.php'; ?>