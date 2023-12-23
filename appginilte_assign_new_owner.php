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
                        <li class="breadcrumb-item"><a href="index.php"><?php echo $translate['dashboard']; ?> </a></li>
                        <li class="breadcrumb-item active"> <?php echo $translate['Assign New Owner']; ?></li>
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
                            <?php echo $translate['Assign New Owner']; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php
                        //check if file exits $groupID in appginilte/group_admins/$groupID.json
                        if (!file_exists('appginilte/group_admins/' . $groupID . '.json')) {
                            echo showAlert('danger', $translate['Group Admins file not found'], $translate['Please contact the system administrator']);
                        } else {
                            $perms = file_get_contents('appginilte/group_admins/' . $groupID . '.json');
                            $extract = json_decode($perms, true);
                            //if usernamenotin array exit
                            if (!in_array($username, $extract)) {
                                echo showAlert('danger', $translate['You are not allowed to access this page'], $translate['Please contact the system administrator']);
                            } else {
                                //get all members of the group
                                $res = sql("SELECT `memberID` FROM membership_users WHERE `groupID`='{$groupID}' ORDER BY `memberID` ASC", $eo);
                                $optionlist = '';
                                foreach ($res as $row) {
                                    //show drop down list with all members of the group
                                    $optionlist .= "<option value='{$row['memberID']}'>{$row['memberID']}</option>";
                                }
                                //show form
                                echo "<form method='post' action=''>
                                    <div class='form-group'>
                                        <label for='exampleInputEmail1'>{$translate['Select New Owner']}</label>
                                        <select class='form-control' name='new_owner'>
                                            {$optionlist}
                                        </select>
                                    </div>
                                    <button type='submit' class='btn btn-primary'>{$translate['submit']}</button>
                                </form>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    document.title = <?php echo $translate['Assign New Owner']; ?>;
</script>
<?php
if (isset($_POST['new_owner'])) {
    $new_owner = $_POST['new_owner'];
    /* receive calling parameters */
    $table = $_REQUEST['table'];
    $ids = $_REQUEST['ids']; /* this is an array of IDs */
    foreach ($ids as $id) {
        $cs_ids = makeSafe($id);
        //set new owner
        $primary_key = $cs_ids;
        set_record_owner($table, $primary_key, $new_owner);
        //Set/update the owner of given record
    }

    echo showToast('success', $tranlsate['Owner Changed'], $translation['The owner of the selected records has been changed']);
}
?>
<?php include 'appginilte_footer.php'; ?>