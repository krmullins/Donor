<?php include 'appginilte/appginilte.php'; check_otp(); ?>
<!DOCTYPE html>
<html lang="en" <?php if ($enableRTL == 1) { echo 'dir="rtl"'; } ?>>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    <?php echo $appName . (isset($x->TableTitle) ? ' | ' . $x->TableTitle : ''); ?>
  </title>
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="appginilte/images/<?php echo $appIcon; ?>">
  <!-- Google Font: Roboto -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="appginilte/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <?php if ($enableRTL == 1) { echo '<link rel="stylesheet" href="appginilte/dist/css/adminlte.min.rtl.css">'; } else { echo '<link rel="stylesheet" href="appginilte/dist/css/adminlte.min.css">'; } ?>
  <!-- Toast css -->
  <link rel="stylesheet" href="appginilte/plugins/toastr/toastr.min.css">
  <style> .responsive-iframe { width: 100%; display: block; border: none; max-width: 100%; } #btn-back-to-top { position: fixed; bottom: 40px; right: 20px; display: none; border-radius: 50%; } div#google_translate_element div.goog-te-gadget-simple { font-size: 15px; border-radius: 5px; } div#google_translate_element div.goog-te-gadget-simple { background-color: #f4f6f9; } div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span { color: black } div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span:hover { color: maroon } .no-js #loader { display: none; } .js #loader { display: block; position: absolute; left: 100px; top: 0; } .se-pre-con { position: fixed; top: 0px; bottom: 0px; width: 100%; height: 100%; z-index: 9999; background: url(<?php echo $siteLoader; ?>) center no-repeat #fff; } </style>
  <!-- DataTables  & Plugins -->
  <link rel="stylesheet" href="appginilte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="appginilte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="appginilte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- jQuery -->
  <script src="appginilte/plugins/jquery/jquery.min.js"></script>
  <script src="appginilte/plugins/ajax/jquery.min.js"></script>
  <style> /* Set the width and height of the scrollbar */ ::-webkit-scrollbar { width: 12px; height: 12px; } /* Style the track */ ::-webkit-scrollbar-track { background: #f1f1f1; } /* Style the thumb */ ::-webkit-scrollbar-thumb { background: linear-gradient(to top, #0f2027, #203a43, #2c5364); border-radius: 6px; } /* Style the buttons */ ::-webkit-scrollbar-button { background: #ccc; } /* Style the corner */ ::-webkit-scrollbar-corner { background: #f1f1f1; } </style>
  <?php /*check if file exists: appginilte-custom-header.php and include it*/ if (file_exists('appginilte/appginilte-custom-header.php')) { include 'appginilte/appginilte-custom-header.php'; } ?>
  <!-- evo-calendar.css-->
  <link rel="stylesheet" type="text/css" href="appginilte/plugins/evo-calendar/css/evo-calendar.css"/>
  <link rel="stylesheet" type="text/css" href="appginilte/plugins/evo-calendar/css/evo-calendar.royal-navy.css"/>
  <link rel="stylesheet" type="text/css" href="appginilte/plugins/evo-calendar/css/evo-calendar.midnight-blue.css"/>
  <link rel="stylesheet" type="text/css" href="appginilte/plugins/evo-calendar/css/evo-calendar.orange-coral.css"/>
</head>
<body class="<?php echo $app_theme; echo " "; echo $fixed_header == "on" ? "layout-navbar-fixed" : ""; echo " "; echo $sidebar_collapsed == "on" ? "sidebar-collapse" : ""; echo " "; echo $sidebar_fixed == "on" ? "layout-fixed" : ""; echo " "; echo $sidebar_mini == "on" ? "sidebar-mini" : "sidebar-mini"; echo " "; echo $sidebar_mini_md == "on" ? "sidebar-mini-md" : ""; echo " "; echo $sidebar_mini_xs == "on" ? "sidebar-mini-xs" : ""; echo " "; echo $footer_fixed == "on" ? "layout-footer-fixed" : ""; echo " "; echo $text_sm_body == "on" ? "text-sm" : ""; echo " "; echo "accent-" . strtolower($accent_color_variant); ?>">
  <div class="se-pre-con"></div>
<!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand <?php echo $app_theme=="light-mode"?"navbar-light":"navbar-dark"; echo " "; echo $dropdown_legacy_offset == "on" ? "dropdown-legacy" : ""; echo " "; echo $no_border == "on" ? "border-bottom-0" : ""; echo " "; echo $text_sm_navbar == "on" ? "text-sm" : ""; echo " "; echo "bg-" . strtolower($navbar_variant); ?>">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item"> <a class="nav-link" data-widget="pushmenu" href="#" role="button" id="toggleBtnId"><i class="fas fa-bars"></i></a> </li>
        <li class="nav-item d-none d-sm-inline-block"> <a href="index.php" class="nav-link"> <?php echo $appName; ?> </a> </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- global seach -->
        <?php if ($globalsearch == "1" && in_array($group, $globalsearchgroups)) { ?>
          <li class="nav-item"> <a class="nav-link" data-widget="navbar-search" href="#" role="button"> <i class="fas fa-search"></i> </a> <div class="navbar-search-block" style="display: none;"> <div class="input-group input-group-sm"> <input class="form-control form-control-navbar" type="search" placeholder="<?php echo $translate['Search']; ?>" aria-label="Search" id="globalsearchtop"> <div class="input-group-append"> <button class="btn btn-navbar" type="submit" onclick="window.location.href='appginilte_global-search.php?search='+document.getElementById('globalsearchtop').value"> <i class="fas fa-search"></i> </button> <button class="btn btn-navbar" type="button" data-widget="navbar-search"> <i class="fas fa-times"></i> </button> </div> </div> </div> </li>
        <?php } ?>
        <!-- global seach -->
        <!-- UserMenu Dropdown: Contains- Admin area, profile, csv import and sign out menus -->
        <!-- Messages plugin start -->
        <?php if (file_exists('plugins/messages/index.php')) { $plugin_setup = sqlValue("SHOW TABLES LIKE 'appgini_messages_group_permissions'"); if (!empty($plugin_setup)) { $groupAccess = sqlValue("SELECT  COUNT(*) FROM appgini_messages_group_permissions WHERE groupID='$groupID' AND hasAccess='1'"); if ($groupAccess > 0 || $group == "Admins") { $msg = sqlValue("SELECT COUNT(1) FROM `appgini_messages` WHERE `owner`='{$username}' AND `markedUnread`='1'"); if ($msg > 0) { $msg = '<span class="badge badge-danger navbar-badge">' . $msg . '</span>'; } else { $msg = ''; } echo '<li class="nav-item"> <a href="appginilte_view.php?page=plugins/messages" class="nav-link" aria-expanded="false"> <i class="fa fa-envelope nav-icon"></i> ' . $msg . ' </a> </li>'; } } } ?>
        <!-- Messages plugin end -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-user fa-1x"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">
              <?php echo $appName . ' | ' . $username; ?>
            </span>
            <div class="dropdown-divider"></div>
            <?php
            $userprofileLink = ($group == 'anonymous') ? "#" : "appginilte_view.php?page=membership_profile.php?Embedded=true";
            $logoutlink = ($group == 'anonymous') ? 'index.php?signOut=1' : 'index.php?signOut=1';
            if ($group == "Admins") {
              # code...
              $adminareaLink = PREPEND_PATH . "admin/pageHome.php";
              #display admin area button/link below here
              echo ' <a href="appginilte_view.php?page=' . $adminareaLink . '" class="dropdown-item">
            <i class="fas fa-lock mr-2"></i> ' . $translate['admin area'] . '
            <span class="float-right text-muted text-sm fa fa-arrow-right"></span>
          </a>
          <div class="dropdown-divider"></div>';
            }
            if ($group !== "anonymous") {
              echo ' <a href="' . $userprofileLink . '" class="dropdown-item">
          <i class="fas fa-user mr-2"></i> ' . $translate['profile'] . '
          <span class="float-right text-muted text-sm fa fa-arrow-right"></span>
        </a>
        <div class="dropdown-divider"></div>';
            }
            ?>
            <div class="dropdown-divider"></div>
            <a href="<?php echo $logoutlink; ?>" class="dropdown-item dropdown-footer"> <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-dark" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path> <polyline points="16 17 21 12 16 7"></polyline> <line x1="21" y1="12" x2="9" y2="12"></line> </svg> <?php echo $translate['logout']; ?> </a>
          </div>
        </li>
        <!-- appginilte custom menu -->
        <li class="nav-item dropdown" id="advancedfeatures">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fa fa-cog"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
            <span class="dropdown-item dropdown-header">
              <?php echo $translate['Advanced Features']; ?>
            </span>
            <div class="dropdown-divider"></div>
            <div id="advmenuitems">
              <?php
              if ($isSuperAdmin == "Yes" || $adminltesettings == 1) {
                echo ' <a href="appginilte_settings.php" class="dropdown-item">
            <i class="fas fa-cog mr-2"></i> ' . $translate['appginilte settings'] . '
          </a>
          <div class="dropdown-divider"></div>';
              }
              if ($isSuperAdmin == "Yes" || $userareapems == 1) {
                echo ' <a href="appginilte_user_area_permissions.php" class="dropdown-item">
          <i class="fas fa-sliders-h"></i> ' . $translate['user area permissions'] . '
        </a>
        <div class="dropdown-divider"></div>';
              }
              if ($isSuperAdmin == "Yes" || $dashbuilder == 1) {
                echo ' <a href="appginilte_reportsbuilder.php" class="dropdown-item">
                  <i class="fas fa-file-excel fa-1x"></i> ' . $translate['reports builder'] . '
                </a>
                <div class="dropdown-divider"></div>';
                echo ' <a href="appginilte_dash-builder.php" class="dropdown-item">
                <i class="fas fa-desktop"></i> ' . $translate['dashboard builder'] . '
                </a>
                <div class="dropdown-divider"></div>';
                echo ' <a href="appginilte_page-builder.php" class="dropdown-item">
                <i class="fas fa-file-code"></i> ' . $translate['custom pages builder'] . '
                </a>
                <div class="dropdown-divider"></div>';
                echo ' <a href="appginilte_table-views-builder.php" class="dropdown-item">
                <i class="fas fa-table"></i> ' . $translate['table views builder'] . '
                </a>
                <div class="dropdown-divider"></div>';
                echo ' <a href="https://documenter.getpostman.com/view/8153370/VUxRQ6i2" class="dropdown-item" target="_blank">
                <i class="fas fa-server"></i> ' . $translate['appgini api documentation'] . '
                </a>
                <div class="dropdown-divider"></div>';
                echo ' <a href="appginilte_group_admins.php" class="dropdown-item">
                <i class="fas fa-users-cog"></i> ' . $translate['groups admins'] . '
                </a>
                <div class="dropdown-divider"></div>';
                echo ' <a href="appginilte_switcher.php" class="dropdown-item">
                <i class="fas fa-toggle-on"></i> ' . $translate['theme switcher'] . '
                </a>
                <div class="dropdown-divider"></div>';
                echo ' <a href="appginilte_data_encryption.php" class="dropdown-item">
                <i class="fas fa-lock"></i> ' . $translate['data encryption'] . '
                </a>';
              }
              ?>
            </div>
          </div>
        </li>
        <!-- appginilte custom menu -->
        <li class="nav-item"> <a class="nav-link" data-widget="fullscreen" href="#" role="button"> <i class="fas fa-expand-arrows-alt"></i> </a> </li>
        <li class="nav-item"> <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"> <i class="fas fa-th-large"></i> </a> </li>
      </ul>
    </nav>
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar elevation-4 <?php echo $sidebar_disable_hover == "on" ? "sidebar-no-expand" : ""; echo " "; if ($dark_sidebar_variant !== 'NONE') { echo "sidebar-dark-" . strtolower($dark_sidebar_variant); } echo " "; if ($light_sidebar_variant !== 'NONE') { echo "sidebar-light-" . strtolower($light_sidebar_variant); } ?>">
      <!-- Brand Logo -->
      <a href="index.php" class="brand-link <?php echo $text_sm_brand == "on" ? "text-sm" : ""; echo " "; echo "bg-" . strtolower($brand_logo_variant); ?>">
        <img src="appginilte/images/<?php echo $appIcon; ?>" alt="AdminLTE Logo"
          class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light" style="display: block; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; max-width: 100%; ">
          <?php echo $appName; ?>
        </span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="https://ui-avatars.com/api/?name=<?php echo $username; ?>&background=random"
              class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="<?php echo $userprofileLink; ?>" class="d-block"><?php echo $username; ?></a>
          </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-columm <?php echo $sidebar_flat_style == "on" ? "nav-flat" : ""; echo " "; echo $sidebar_legacy_style == "on" ? "nav-legacy" : ""; echo " "; echo $sidebar_compact_style == "on" ? "nav-compact" : ""; echo " "; echo $sidebar_child_indent == "on" ? "nav-child-indent" : ""; echo " "; echo $sidebar_child_hide_on_collapse == "on" ? "nav-collapse-hide-child" : ""; echo " "; echo $text_sm_sidebarnav == "on" ? "text-sm" : ""; ?>" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <?php include 'appginilte/aside.php'; ?>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>