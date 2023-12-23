<?php
include 'groups_config_lte.php';
include 'tables_config_lte.php';

$groups = get_table_groups();
$allGroups=get_table_groups(true);
$memberInfo = getMemberInfo();
$get_custom_menus = json_decode(file_get_contents('appginilte/jsondb/custom_table_menus.json'),true);
function getgroupindex($group)
{
  global $allGroups;
  return array_search($group, array_keys($allGroups));
}
foreach ($groups as $grp => $tables) {
  # code...
  $group_index = getgroupindex($grp);
  $tlink = '';
  if ($grp !== "None") {
    // code...
    $gn = str_replace(" ", "_", $grp);
    $grpicon = $cjson[$gn . '_fa'] ? $cjson[$gn . '_fa'] : 'fa fa-table';
    $grpcollapsed=$cjson[$gn . '_collapsed'];
    $navitemclass=''; $liststyle=''; if($grpcollapsed=="0"){ $navitemclass="menu-is-opening menu-open"; $liststyle='style="display: block;"'; }
    $grptop = '<li class="nav-item '.$navitemclass.'">
    <a href="#" class="nav-link">
      <i class="nav-icon ' . $grpicon . '"></i>
      <p>
        ' . $grp . '
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview" '.$liststyle.'>';
    foreach ($tables as $tn) {
      $json = json_encode(get_tables_info(true));
      $decode = json_decode($json);
      $table_title = $decode->$tn->Caption;
      $tableIcon = $decode->$tn->tableIcon;
      $menu_hidden = $djson[$tn . '_hidden_nm'] ? $djson[$tn . '_hidden_nm'] : '';
      $menu_icon = $djson[$tn . '_icon'] ? $djson[$tn . '_icon'] : 'default';
      $menu_fa = $djson[$tn . '_fa'] ? $djson[$tn . '_fa'] : 'fa fa-trophy';
      $show_icon = ($menu_icon == 'default') ? '<img src="' . $tableIcon . '">' : '<i class="' . $menu_fa . ' nav-icon"></i>';
      /* hide current menu in nav menu? */
      if (strpos($menu_hidden, $group) === false && strpos($menu_hidden, '*') === false) {
        $tlink .= '<li class="nav-item">
      <a href="appginilte_view.php?page=' . $tn . '_view.php?Embedded=true" class="nav-link">
       ' . $show_icon . '
        <p>' . $table_title . '</p>
      </a>
    </li>';
      }
    }
    //custom links for each group
    if (is_array($navLinks)) {
      foreach ($navLinks as $link) {
        if (getLoggedAdmin() !== false || @in_array($memberInfo['group'], $link['groups']) || @in_array('*', $link['groups'])) {
          $menu_index =  $link['table_group'];
          /* add prepend_path to custom links if they aren't absolute links */
          if (!preg_match('/^(http|\/\/)/i', $link['url'])) $link['url'] = $prepend_path . $link['url'];
          if (!preg_match('/^(http|\/\/)/i', $link['icon']) && $link['icon']) $link['icon'] = $prepend_path . $link['icon'];
          if (is_numeric($menu_index) && $menu_index == $group_index) {
            $icon_type = $link['icon_type'];
            $custom_link = $link['CustomLink'];
            $hidenavmenu= $link['hidenavmenu'];
            $hiddenstyle=($hidenavmenu=='Yes')?'style="display:none !important;"':'';
            if (!empty($custom_link)) {
              $link['url'] = $custom_link;
            }
            if ($icon_type == 'fa') {
              $tlink .= '<li class="nav-item" '.$hiddenstyle.'>
              <a href="' . $link['url'] . '" class="nav-link">
                <i class="' . $link['icon'] . ' nav-icon"></i>
                <p>' . $link['title'] . '</p>
              </a>
            </li>';
            } else {
              $tlink .= '<li class="nav-item" '.$hiddenstyle.'>
            <a href="' . $link['url'] . '" class="nav-link">
              <img src="' . $link['icon'] . '">
              <p>' . $link['title'] . '</p>
            </a>
          </li>';
            }
          }
        }
      }
    }
    $grpbtm = '</ul></li>';
    echo empty($tlink) ? '' : $grptop . $tlink . $grpbtm;
  } else {
    foreach ($tables as $tn) {
      $json = json_encode(get_tables_info(true));
      $decode = json_decode($json);
      $table_title = $decode->$tn->Caption;
      $tableIcon = $decode->$tn->tableIcon;
      $menu_hidden = $djson[$tn . '_hidden_nm'] ? $djson[$tn . '_hidden_nm'] : '';
      $menu_icon = $djson[$tn . '_icon'] ? $djson[$tn . '_icon'] : 'default';
      $menu_fa = $djson[$tn . '_fa'] ? $djson[$tn . '_fa'] : 'fa fa-trophy';
      $show_icon = ($menu_icon == 'default') ? '<img src="' . $tableIcon . '">' : '<i class="' . $menu_fa . ' nav-icon"></i>';
      /* hide current menu in nav menu? */
      if (strpos($menu_hidden, $group) === false && strpos($menu_hidden, '*') === false) {
        $tlink .= ' <li class="nav-item">
      <a href="appginilte_view.php?page=' . $tn . '_view.php?Embedded=true" class="nav-link">
      ' . $show_icon . '
        <p>
        ' . $table_title . '
        </p>
      </a>
    </li>';
      }
    }
    echo $tlink;
  }
}

//custom table group menus
$customtablegroups='';
foreach ($get_custom_menus as $cmenu) {
  $ctlink='';
  $custom_menu_id = $cmenu['menuID'];
  $custom_menu_name = $cmenu['customGroupName'];
  $custom_menu_icon = $cmenu['customGroupIcon'];
  $customGroupCollapsed=$cmenu['customGroupCollapsed'];
  $CustomGroupHidenavmenu=$cmenu['CustomGroupHidenavmenu'];
  $navitemclass=''; $liststyle=''; $menuhidden=''; if($customGroupCollapsed=="No"){ $navitemclass="menu-is-opening menu-open"; $liststyle='style="display: block;"'; } if($CustomGroupHidenavmenu=="Yes"){$menuhidden='style="display: none;"'; }
    $grptop = '<li class="nav-item '.$navitemclass.'" '.$menuhidden.'>
    <a href="#" class="nav-link">
      <i class="nav-icon ' . $custom_menu_icon . '"></i>
      <p>
        ' . $custom_menu_name . '
        <i class="right fas fa-angle-left"></i>
      </p>
    </a>
    <ul class="nav nav-treeview" '.$liststyle.'>';
    $grpbtm = '</ul></li>';
    if (is_array($navLinks)) {
      foreach ($navLinks as $link) {
        if (getLoggedAdmin() !== false || @in_array($memberInfo['group'], $link['groups']) || @in_array('*', $link['groups'])) {
          $menu_index =  $link['table_group'];
          /* add prepend_path to custom links if they aren't absolute links */
          if (!preg_match('/^(http|\/\/)/i', $link['url'])) $link['url'] = $prepend_path . $link['url'];
          if (!preg_match('/^(http|\/\/)/i', $link['icon']) && $link['icon']) $link['icon'] = $prepend_path . $link['icon'];
          if ($menu_index == $custom_menu_id) {
            $icon_type = $link['icon_type'];
            $custom_link = $link['CustomLink'];
            $hidenavmenu= $link['hidenavmenu'];
            $hiddenstyle=($hidenavmenu=='Yes')?'style="display:none !important;"':'';
            if (!empty($custom_link)) {
              $link['url'] = $custom_link;
            }
            if ($icon_type == 'fa') {
              $ctlink .= '<li class="nav-item" '.$hiddenstyle.'>
              <a href="' . $link['url'] . '" class="nav-link">
                <i class="' . $link['icon'] . ' nav-icon"></i>
                <p>' . $link['title'] . '</p>
              </a>
            </li>';
            } else {
              $ctlink .= '<li class="nav-item" '.$hiddenstyle.'>
            <a href="' . $link['url'] . '" class="nav-link">
              <img src="' . $link['icon'] . '">
              <p>' . $link['title'] . '</p>
            </a>
          </li>';
            }
          }
        }
      }
    }
    if(!empty($ctlink)){  
     $customtablegroups.=$grptop.$ctlink.$grpbtm;
    }
}

echo $customtablegroups;

//custom non grouped links
if (is_array($navLinks)) {
  $customtlink = '';
  foreach ($navLinks as $link) {
    if (getLoggedAdmin() !== false || in_array($group, $link['groups']) || in_array('*', $link['groups'])) {
      $menu_index =  $link['table_group'];
      /* add prepend_path to custom links if they aren't absolute links */
      if (!preg_match('/^(http|\/\/)/i', $link['url'])) $link['url'] = $prepend_path . $link['url'];
      if ($icon_type !== 'fa'){
        if (!preg_match('/^(http|\/\/)/i', $link['icon']) && $link['icon']) $link['icon'] = $prepend_path . $link['icon'];
      }
      if ($menu_index == "None") {
        $icon_type = $link['icon_type'];
        $custom_link = $link['CustomLink'];
        $hidenavmenu= $link['hidenavmenu'];
        $hiddenstyle=($hidenavmenu=='Yes')?'style="display:none !important;"':'';
        if (!empty($custom_link)) {
          $link['url'] = $custom_link;
        }
        if ($icon_type == 'fa') {
          $customtlink .= '<li class="nav-item" '.$hiddenstyle.'>
          <a href="' . $link['url'] . '" class="nav-link">
            <i class="' . $link['icon'] . ' nav-icon"></i>
            <p>' . $link['title'] . '</p>
          </a>
        </li>';
        } else {
          $customtlink .= '<li class="nav-item" '.$hiddenstyle.'>
        <a href="' . $link['url'] . '" class="nav-link">
          <img src="' . $link['icon'] . '" height="32">
          <p>' . $link['title'] . '</p>
        </a>
      </li>';
        }
      }
    }
  }
  echo $customtlink;
}

if (file_exists('plugins/messages/index.php')) {
  $plugin_setup=sqlValue("SHOW TABLES LIKE 'appgini_messages_group_permissions'");
  if(!empty($plugin_setup)){
    $groupAccess=sqlValue("SELECT  COUNT(*) FROM appgini_messages_group_permissions WHERE groupID='$groupID' AND hasAccess='1'");
    if($groupAccess>0||$group=="Admins"){
      $msg = sqlValue("SELECT COUNT(1) FROM `appgini_messages` WHERE `owner`='{$memberInfo['username']}' AND `markedUnread`='1'");
      if ($msg > 0) {
        $msg = '<span class="badge badge-danger">' . $msg . '</span>';
      } else {
        $msg = '';
      }
      echo '<li class="nav-item">
      <a href="appginilte_view.php?page=plugins/messages" class="nav-link">
        <i class="fa fa-envelope nav-icon"></i> <p>Messages &nbsp;   ' . $msg . '</p>
      </a> 
      </li>';
    }

  }
 
}
