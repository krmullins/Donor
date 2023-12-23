<?php include 'appginilte_header.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?php echo $dashboardWelcomeMessage; ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"><?php echo $translate['Home']; ?></a></li>
              <li class="breadcrumb-item active"><?php echo $appName; ?></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <?php 
      // get member info
	    $mi = getMemberInfo();
      // get configured name of guest user
      $admin_config = config('adminConfig');
      $guest_username = $admin_config['anonymousMember'];
      /* accessible tables */
	    $arrTables = get_tables_info();
	    if(is_array($arrTables) && count($arrTables)) {/*Do nothing*/ }
      elseif($mi['username'] && $mi['username'] != $guest_username) {
        // non-guest user but no tables to access
        echo "<div class='alert alert-danger'>".$translate['no tables found']."</div>";
      }
      else {
        // guest user or no tables to access
        header("Location: index.php?signIn=1");
      }
      ?>
      <!--Content Above Home Links  -->
      <!-- universal Top_homelinks.php -->
      <?php include 'appginilte/views/universal_Top_homelinks.php'; ?>
        <?php include 'appginilte/above_homelinks.php'; ?>
      <!--Content Above Home Links  -->
      <!-- Home Links -->
      <?php include 'appginilte/home_links.php'; ?>
      <!-- Home Links -->
      <!--Content Below Home Links  -->
       <!-- universal Bottom_homelinks.php -->
      <?php include 'appginilte/views/universal_Bottom_homelinks.php'; ?>
      <?php include 'appginilte/below_homelinks.php'; ?>
      <!--Content Below Home Links  -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include 'appginilte_footer.php'; ?>