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
                        <li class="breadcrumb-item active"><?php echo $translate['group_admins']; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <?php echo showCallout('info', $translate['group_admins_management'],$translate['group_admins_description']); ?>
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-eye"></i>
                            <?php echo $translate['group_admins_management']; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="card card-widget widget-user" style="min-height: 5rem;max-height: 70vh;overflow-y: auto;">
                                    <!-- Add the bg color to the header using any of the bg-* classes -->
                                    <div class="widget-user-header bg-success">
                                        <h3 class="widget-user-username"><?php echo $appName; ?></h3>
                                        <h5 class="widget-user-desc"> <?php echo $translate['membership_groups']; ?></h5>
                                    </div>
                                    <br>
                                    <div class="p-0">
                                        <ul class="nav flex-column">
                                            <?php
                                            //get current available groups
                                            $groups = sql("SELECT * FROM membership_groups", $eo);
                                            foreach ($groups as $group) {
                                                $group_id = $group['groupID'];
                                                $group_name = $group['name'];
                                                $group_members = sqlValue("SELECT COUNT(1) FROM membership_users WHERE groupID = '$group_id'", $eo);
                                                echo '<li class="nav-item">
                                                    <a href="?group=' . $group_id . '" class="nav-link">
                                                     <span class="fas fa-users"></span> ' . $group_name . ' <span class="float-right badge ' . ($group_members > 0 ? "badge-success" : "badge-info") . ' right">Members: ' . $group_members . '</span>
                                                    </a>
                                                </li>';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php
                                        if (isset($_GET['group'])) {
                                            $group = makeSafe($_GET['group']);
                                            $group_name = sqlValue("SELECT name FROM membership_groups WHERE groupID = '$group'", $eo);
                                            //get names of groupadmins from folder appginilte/group_admins/$group_name.json
                                            $group_admins=[];
                                            if(file_exists("appginilte/group_admins/$group.json")){
                                                $group_admins = json_decode(@file_get_contents("appginilte/group_admins/$group.json"), true);
                                            }
                                        ?>
                                            <div class="card card-widget widget-user" style="min-height: 5rem;max-height: 70vh;overflow-y: auto;">
                                                <!-- Add the bg color to the header using any of the bg-* classes -->
                                                <div class="widget-user-header bg-warning">
                                                    <h4 class="widget-user-username"><?php echo $appName; ?></h4>
                                                    <h5 class="widget-user-desc">Group Admins for <?php echo $group_name; ?> Group</h5>
                                                </div>
                                                <br>
                                                <div class="card-footer p-0">
                                                    <ul class="nav flex-column">
                                                        <?php
                                                        foreach ($group_admins as $key => $group_admin) {
                                                            $group_admin_name = $group_admin;
                                                            echo '<li class="nav-item">
                                                        <a href="admin/pageEditMemberPermissions.php?memberID='.$group_admin_name.'" class="nav-link">
                                                         <span class="fas fa-user"></span> ' . $group_admin_name . '
                                                        </a>
                                                    </li>';
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        //create a form that will be used to select group admins for current group: get mebers of that group for selection
                                        $members = sql("SELECT * FROM membership_users WHERE groupID = '$group'", $eo);
                                        //create a multi-select form with all members of the group
                                        ?>
                                    </div>
                                    <?php
                                    if (isset($_GET['group'])) {
                                    ?>
                                    <div class="col-md-6">
                                        <form method="post" action="appginilte_group_admins.php?group=<?php echo $group; ?>">
                                            <div class="form-group">
                                                <label for="group_admins"> <?php echo $translate['select_group_admins']; ?></label>
                                                <select class="form-control" id="group_admins" name="group_admins[]" multiple>
                                                    <?php
                                                    $group_admins = json_decode(file_get_contents("appginilte/group_admins/$group.json"), true);
                                                    foreach ($members as $member) {
                                                        $member_name = $member['memberID'];
                                                        //if member is already a group admin, select it
                                                        if (in_array($member_name, $group_admins)) {
                                                            echo '<option value="' . $member_name . '" selected>' . $member_name . '</option>';
                                                        } else {
                                                            echo '<option value="' . $member_name . '">' . $member_name . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary"> <?php echo $translate['submit']; ?></button>
                                        </form>
                                    </div>
                                    <?php
                                    }
                                    ?>
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
<script>
    document.title = '<?php echo $appName . '| '.$translate['group_admins'].''; ?>';
</script>
<?php
//if isset POST group_admins, save the group admins to the appginilte/group_admins/$group_name.json
if (isset($_POST['group_admins'])) {
    $group_admins = $_POST['group_admins'];
    $group_name = sqlValue("SELECT name FROM membership_groups WHERE groupID = '$group'", $eo);
    $group_admins_json = json_encode($group_admins);
    file_put_contents("appginilte/group_admins/$group.json", $group_admins_json);
    //for each group_admins, check if exists as memberID in membership_userpermissions table,if not add for each available tableName
    //get all tables
    $groups = get_table_groups();
    foreach ($groups as $grp => $tables) {
        foreach ($tables as $tn) {
            $table_name=$tn;
            foreach ($group_admins as $group_admin) {
                $group_admin_name = $group_admin;
                $group_admin_exists = sqlValue("SELECT COUNT(1) FROM membership_userpermissions WHERE tableName = '$table_name' AND memberID = '$group_admin_name'", $eo);
                if (!$group_admin_exists) {
                    sql("INSERT INTO membership_userpermissions SET tableName = '$table_name', memberID = '$group_admin_name', allowInsert = 1, allowView = 2, allowEdit = 2, allowDelete = 2", $eo);
                }
                else{
                    sql("UPDATE membership_userpermissions SET allowInsert = 1, allowView = 2, allowEdit = 2, allowDelete = 2 WHERE tableName = '$table_name' AND memberID = '$group_admin_name'", $eo);
                }
            }
        }
    }
    //show success message in a toast
    echo showToast('success', $translate['group_admins_updated'], $translate['group_admins_updated_description']);
    //refresh page after 3 seconds
    echo '<meta http-equiv="refresh" content="3;url=appginilte_group_admins.php?group=' . $group . '">';
}
?>
<?php include 'appginilte_footer.php'; ?>