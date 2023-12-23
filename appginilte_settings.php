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
    if ($isSuperAdmin == 'No' && $adminltesettings == 0) {
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
                    <h1><?php echo $appName; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php"><?php echo $translate['dashboard']; ?></a></li>
                        <li class="breadcrumb-item active"><?php echo $translate['settings']; ?></li>
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
                            <i class="fas fa-edit"></i>
                            <?php echo $translate['appginilte_settings']; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <h4><?php echo $translate['customize_look']; ?></h4>
                        <p><?php echo $translate['use_font_awesome_icons']; ?> <a href="https://itsjavi.com/fontawesome-iconpicker/" target="_blank"><?php echo $translate['font_awesome_picker']; ?></a></p>
                        <p><?php echo $translate['dashboard_developed_using']; ?> <a href="adminlte.io/themes/v3/" target="_blank"><?php echo $translate['adminlte_bootstrap_template']; ?></a></p>
                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true"><span class="fas fa-layer-group"></span> <?php echo $translate['table_groups']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"><span class="fas fa-table"></span> <?php echo $translate['table_menus']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-gauth" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"><span class="fas fa-sign-in-alt"></span> <?php echo $translate['Social Login']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-lotp" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"><span class="fas fa-lock"></span> <?php echo $translate['login_otp']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-totp" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"><span class="fas fa-mobile"></span> <?php echo $translate['totp']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-grecap" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"><span class="fab fa-google"></span> <?php echo $translate['google_recaptcha']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-inlineedit" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"><span class="fas fa-tasks"></span> <?php echo $translate['table_actions']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-dbbackup" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"><span class="fas fa-database"></span> <?php echo $translate['database_backup']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-gensetts" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"><span class="fas fa-cog"></span> <?php echo $translate['general_settings']; ?></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="custom-content-below-tabContent">
                            <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                <br>
                                <!-- Show brief explanation details -->
                                <div class="callout callout-info">
                                    <p><?php echo $translate['table_groups_customization_description']; ?></p>
                                </div>
                                <!-- explanation -->
                                <form action="" method="POST">
                                    <?php
                                    $groups = get_table_groups();
                                    $cjson = get_option('groups_config');
                                    foreach ($groups as $grp => $tables) {
                                        if ($grp !== "None") {
                                            $gn = str_replace(" ", "_", $grp);
                                            $group_fa = $cjson[$gn . '_fa'] ? $cjson[$gn . '_fa'] : 'fa fa-table';
                                            $group_hpd = $cjson[$gn . '_hpd'] ? $cjson[$gn . '_hpd'] : 'default';
                                            $group_cc = $cjson[$gn . '_cc'] ? $cjson[$gn . '_cc'] : 'primary';
                                            $group_collapsed= $cjson[$gn . '_collapsed'];
                                            echo '<div class="card card-primary collapsed-card">
                                            <div class="card-header" data-card-widget="collapse">
                                            <h3 class="card-title">'.$grp.'</h3>
                                            <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                            </button>
                                            </div>
                                            </div>
                                            <div class="card-body" style="display: none;">
                                            ';
                                            echo ' <div class="row">
                                            <div class="col-lg-4 col-sm-12">
                                        <label class="text-primary">' . $grp . ' ' . $translate['group_icon'] . ' <span class="' . $group_fa . '"></span></label>
                                        <input class="form-control" value="' . $group_fa . '" name="' . $gn . '_fa" type="text" />
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                        <label>' . $translate['home_page_display'] . '</label>
                                        <select id="' . $gn . '_hpd" name="' . $gn . '_hpd" class="form-control">
                                        <option value="default" ' . ($group_hpd == "default" ? 'selected' : '') . '>' . $translate['default'] . '</option>
                                        <option value="collapsible" ' . ($group_hpd == "collapsible" ? 'selected' : '') . '>' . $translate['collapsible_card'] . '</option>
                                        <option value="notgrouped" ' . ($group_hpd == "notgrouped" ? 'selected' : '') . '>' . $translate['not_grouped'] . '</option>
                                        </select>
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                        <label id="' . $gn . '_cclabel">' . $translate['card_color'] . '</label>
                                        <select id="' . $gn . '_cc" name="' . $gn . '_cc" class="form-control"">
                                        <option value="primary" ' . ($group_cc == "primary" ? 'selected' : '') . '>' . $translate['primary'] . '</option>
                                        <option value="success" ' . ($group_cc == "success" ? 'selected' : '') . '>' . $translate['success'] . '</option>
                                        <option value="warning" ' . ($group_cc == "warning" ? 'selected' : '') . '>' . $translate['warning'] . '</option>
                                        <option value="danger" ' . ($group_cc == "danger" ? 'selected' : '') . '>' . $translate['danger'] . '</option>
                                        <option value="info" ' . ($group_cc == "info" ? 'selected' : '') . '>' . $translate['info'] . '</option>
                                        <option value="dark" ' . ($group_cc == "dark" ? 'selected' : '') . '>' . $translate['dark'] . '</option>
                                        <option value="maroon" ' . ($group_cc == "maroon" ? 'selected' : '') . '>' . $translate['maroon'] . '</option>
                                        <option value="purple" ' . ($group_cc == "purple" ? 'selected' : '') . '>' . $translate['purple'] . '</option>
                                        <option value="indigo" ' . ($group_cc == "indigo" ? 'selected' : '') . '>' . $translate['indigo'] . '</option>
                                        <option value="secondary" ' . ($group_cc == "secondary" ? 'selected' : '') . '>' . $translate['secondary'] . '</option>
                                        </select>
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                        <label>' . $translate['menu_always_collapsed'] . '</label>
                                        <select id="menu_collapsed" name="' . $gn . '_collapsed" class="form-control">
                                        <option value="0" ' . ($group_collapsed == "0" ? 'selected' : '') . '>' . $translate['no'] . '</option>
                                        <option value="1" ' . ($group_collapsed == "1" ? 'selected' : '') . '>' . $translate['yes'] . '</option>
                                        </select>
                                        </div>
                                        </div>
                                        <script type="text/javascript">
                                        var ' . $gn . '_hpd = document.getElementById("' . $gn . '_hpd");
                                        var ' . $gn . '_cclabel = document.getElementById("' . $gn . '_cclabel");
                                        var ' . $gn . '_cc = document.getElementById("' . $gn . '_cc");
                                        var selectedDisplay = ' . $gn . '_hpd.options[' . $gn . '_hpd.selectedIndex].value; 
                                        if(selectedDisplay==="default" ||selectedDisplay==="notgrouped"){' . $gn . '_cclabel.style.display = "none";' . $gn . '_cc.style.display = "none";}
                                        if(selectedDisplay==="collapsible"){' . $gn . '_cclabel.style.display = "block";' . $gn . '_cc.style.display = "block";}
                                        $("#' . $gn . '_hpd").change(function(){
                                            var TheselectedDisplay=$("#' . $gn . '_hpd").val();
                                            if(TheselectedDisplay==="default" || TheselectedDisplay==="notgrouped"){' . $gn . '_cclabel.style.display = "none";' . $gn . '_cc.style.display = "none";}
                                        if(TheselectedDisplay==="collapsible"){' . $gn . '_cclabel.style.display = "block";' . $gn . '_cc.style.display = "block";}
                                          });
                                        </script>
                                        ';
                                        echo '</div></div>';
                                        }
                                    }
                                    ?>
                                    <br><button type="submit" class="btn btn-primary" name="tblgrps"><?php echo $translate['save_changes'] ?></button>
                                </form>


                            </div>
                            <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                <br>
                                <!-- Show brief explanation details -->
                                <div class="callout callout-info">
                                    <p><?php echo $translate['table_menus_customization_description'] ?></p>
                                </div>
                                <!-- explanation -->
                                <form action="" method="POST">
                                    <?php
                                    $tjson = get_option('tables_config');
                                    foreach ($groups as $grp => $tables) {
                                        echo '<div class="card card-primary collapsed-card">
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
                                            $card_color = $tjson[$tn . '_color'] ? $tjson[$tn . '_color'] : 'primary';
                                            $card_icon = $tjson[$tn . '_icon'] ? $tjson[$tn . '_icon'] : 'default';
                                            $card_fa = $tjson[$tn . '_fa'] ? $tjson[$tn . '_fa'] : 'fa fa-trophy';
                                            $card_hidden_hp = $tjson[$tn . '_hidden_hp'] ? $tjson[$tn . '_hidden_hp'] : '';
                                            $card_hidden_nm = $tjson[$tn . '_hidden_nm'] ? $tjson[$tn . '_hidden_nm'] : '';
                                            $card_style = $tjson[$tn . '_card_style'] ? $tjson[$tn . '_card_style'] : 'homeLinkInfoBox_1';
                                            $card_size = $tjson[$tn . '_card_size'] ? $tjson[$tn . '_card_size'] : 'col-md-3 col-sm-6 col-12';
                                            echo '
                                        <label class="text-primary">' . $table_title . ' <span class="fa fa-table"></span></label>
                                        <div class="row">
                                        <div class="col-lg-1 col-sm-12">
                                        <label><i>' . $translate['card_color'] . '</i></label>
                                        <select id="card_color" name="' . $tn . '_color" class="form-control"">
                                        <option value="primary" ' . ($card_color == "primary" ? 'selected' : '') . '>' . $translate['primary'] . '</option>
                                        <option value="success" ' . ($card_color == "success" ? 'selected' : '') . '>' . $translate['success'] . '</option>
                                        <option value="warning" ' . ($card_color == "warning" ? 'selected' : '') . '>' . $translate['warning'] . '</option>
                                        <option value="danger" ' . ($card_color == "danger" ? 'selected' : '') . '>' . $translate['danger'] . '</option>
                                        <option value="info" ' . ($card_color == "info" ? 'selected' : '') . '>' . $translate['info'] . '</option>
                                        <option value="secondary" ' . ($card_color == "secondary" ? 'selected' : '') . '>' . $translate['secondary'] . '</option>
                                        <option value="dark" ' . ($card_color == "dark" ? 'selected' : '') . '>' . $translate['dark'] . '</option>
                                        <option value="maroon" ' . ($card_color == "maroon" ? 'selected' : '') . '>' . $translate['maroon'] . '</option>
                                        <option value="purple" ' . ($card_color == "purple" ? 'selected' : '') . '>' . $translate['purple'] . '</option>
                                        <option value="indigo" ' . ($card_color == "indigo" ? 'selected' : '') . '>' . $translate['indigo'] . '</option>
                                      </select>
                                        </div>
                                        <div class="col-lg-2 col-sm-12">
                                        <label><i>' . $translate['card_icon'] . '</i></label>
                                        <select id="' . $tn . '_icon" name="' . $tn . '_icon" class="form-control">
                                        <option value="default" ' . ($card_icon == "default" ? 'selected' : '') . '>' . $translate['default_appgini_icon'] . '</option>
                                        <option value="fa" ' . ($card_icon == "fa" ? 'selected' : '') . '>' . $translate['font_awesome_icon'] . '</option>
                                        </select>
                                        </div>
                                        <div class="col-lg-3 col-sm-12">
                                        <label><i>' . $translate['hide_in_home_page'] . '</i></label>
                                            <input type="text" class="form-control" value="' . $card_hidden_hp . '" name="' . $tn . '_hidden_hp" placeholder="' . $translate['hide_in_home_page_description'] . '">
                                        </div>
                                        <div class="col-lg-3 col-sm-12">
                                        <label><i>' . $translate['hide_in_nav_menu'] . '</i></label>
                                            <input type="text" class="form-control" value="' . $card_hidden_nm . '" name="' . $tn . '_hidden_nm" placeholder="' . $translate['hide_in_nav_menu_description'] . '">
                                        </div>
                                        <div class="col-lg-3 col-sm-12">
                                        <label id="' . $tn . '_fa"><i>' . $translate['font_awesome_icon'] . '</i></label>
                                            <input type="text" class="form-control" value="' . $card_fa . '" name="' . $tn . '_fa" id="' . $tn . '_fafa">
                                        </div>
                                        </div>
                                        <div class="row">
                                        <div class="col-lg-6 col-sm-12">
                                        <label><i>' . $translate['card_style'] . '</i></label>
                                        <select id="card_style" name="' . $tn . '_card_style" class="form-control"">
                                        <option value="homeLinkInfoBox_1" ' . ($card_style == "homeLinkInfoBox_1" ? 'selected' : '') . '>Info Box 1</option>
                                        <option value="homeLinkInfoBox_2" ' . ($card_style == "homeLinkInfoBox_2" ? 'selected' : '') . '>Info Box 2</option>
                                        <option value="homeLinkInfoBox_3" ' . ($card_style == "homeLinkInfoBox_3" ? 'selected' : '') . '>Info Box 3</option>
                                        </select>
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                        <label><i>' . $translate['card_size'] . '</i></label>
                                        <input type="text" class="form-control" value="' . $card_size . '" name="' . $tn . '_card_size" id="' . $tn . '_card_size" placeholder="' . $translate['Enter card size'] . '">
                                        </div>
                                        </div>
                                        <script type="text/javascript">
                                        var ' . $tn . '_icon = document.getElementById("' . $tn . '_icon");
                                        var ' . $tn . '_fa = document.getElementById("' . $tn . '_fa");
                                        var ' . $tn . '_fafa = document.getElementById("' . $tn . '_fafa");
                                        var selectedIcon = ' . $tn . '_icon.options[' . $tn . '_icon.selectedIndex].value; 
                                        if(selectedIcon==="default"){' . $tn . '_fa.style.display = "none";' . $tn . '_fafa.style.display = "none";}
                                        if(selectedIcon==="fa"){' . $tn . '_fa.style.display = "block";' . $tn . '_fafa.style.display = "block";}
                                        $("#' . $tn . '_icon").change(function(){
                                            var TheselectedIcon=$("#' . $tn . '_icon").val();
                                            if(TheselectedIcon==="default"){' . $tn . '_fa.style.display = "none";' . $tn . '_fafa.style.display = "none";}
                                        if(TheselectedIcon==="fa"){' . $tn . '_fa.style.display = "block";' . $tn . '_fafa.style.display = "block";}
                                          });
                                        </script>
                                        ';
                                        }
                                        echo '</div>
                                        </div>';
                                    }
                                    ?>
                                    <br><button type="submit" class="btn btn-success" name="tbls"><?php echo $translate['save_changes']; ?></button>
                                </form>

                            </div>
                            <div class="tab-pane fade" id="custom-content-gauth" role="tabpanel" aria-labelledby="custom-content-gauth">
                                <br>
                                <?php echo showCallout('info', $translate['google_auth'] . ' <i class="fab fa-google mr-2"></i>', $translate['google_auth_description']);
                                $GoogleOauthData = get_option('GoogleOAuth');
                                $clientID = $GoogleOauthData['clientID'] ? $GoogleOauthData['clientID'] : '';
                                $clientSecret = $GoogleOauthData['clientSecret'] ? $GoogleOauthData['clientSecret'] : '';
                                $redirectUri = $GoogleOauthData['redirectUri'] ? $GoogleOauthData['redirectUri'] : '';
                                $enablegauth = $GoogleOauthData['enablegauth'] ? $GoogleOauthData['enablegauth'] : 0;
                                ?>
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12">
                                            <label><i><?php echo $translate['google_auth_enable']; ?></i></label>
                                            <select id="enablegauth" name="enablegauth" class="form-control"">
                                                <option value=" 1" <?php echo $enablegauth == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                <option value="0" <?php echo $enablegauth == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['client_id']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $clientID; ?>" name="clientID" placeholder="<?php echo $translate['enter_client_id']; ?>">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['client_secret']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $clientSecret; ?>" name="clientSecret" placeholder="<?php echo $translate['enter_client_secret']; ?>">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label style="max-width:100%;color:red"><i><?php echo $translate['google_auth_redirect_url']; ?> <?php echo $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . str_replace('appginilte_settings.php', 'appginilte_google.php', $_SERVER['REQUEST_URI']); ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $redirectUri; ?>" name="redirectUri" id="redirectUri" placeholder="<?php echo $translate['google_auth_redirect_url_description']; ?>">
                                        </div>
                                    </div>
                                    <br><button type="submit" class="btn btn-success" name="gauth"><?php echo $translate['save_changes']; ?></button>
                                </form>
                                <hr>
                                <div class="row">
                                <?php echo showCallout('info', $translate['Microsoft Auth'] . ' <i class="fab fa-microsoft mr-2"></i>', $translate['microsoft_auth_description']);
                                $MicrosoftOauthData = get_option('MicrosoftOAuth');
                                $appid = $MicrosoftOauthData['appid'] ? $MicrosoftOauthData['appid'] : '';
                                $tennantid = $MicrosoftOauthData['tennantid'] ? $MicrosoftOauthData['tennantid'] : '';
                                $secret = $MicrosoftOauthData['secret'] ? $MicrosoftOauthData['secret'] : '';
                                $redirectUri = $MicrosoftOauthData['redirectUri'] ? $MicrosoftOauthData['redirectUri'] : '';
                                $enablemauth = $MicrosoftOauthData['enablemauth'] ? $MicrosoftOauthData['enablemauth'] : 0;
                                ?>
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12">
                                            <label><i><?php echo $translate['microsoft_auth_enable']; ?></i></label>
                                            <select id="enablegauth" name="enablemauth" class="form-control"">
                                                <option value=" 1" <?php echo $enablemauth == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                <option value="0" <?php echo $enablemauth == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['app_id']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $appid; ?>" name="appid" placeholder="<?php echo $translate['enter_app_id']; ?>">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['tennantid']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $tennantid; ?>" name="tennantid" placeholder="<?php echo $translate['enter_tennantid']; ?>">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['secret']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $secret; ?>" name="secret" placeholder="<?php echo $translate['enter_secret']; ?>">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label style="max-width:100%;color:blue"><i><?php echo $translate['microsoft_auth_redirect_url']; ?> <?php echo $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . str_replace('appginilte_settings.php', 'appginilte_microsoft.php', $_SERVER['REQUEST_URI']); ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $redirectUri; ?>" name="redirectUri" id="redirectUri" placeholder="<?php echo $translate['microsoft_auth_redirect_url_description']; ?>">
                                        </div>
                                    </div>
                                    <br><button type="submit" class="btn btn-primary" name="mauth"><?php echo $translate['save_changes']; ?></button>
                                </form>
                                </div>
                                <hr>
                                <div class="row">
                                <?php echo showCallout('info', $translate['Twitter Auth'] . ' <i class="fab fa-twitter mr-2"></i>', $translate['twitter_auth_description']);
                                $TwitterOauthData = get_option('TwitterOAuth');
                                $CONSUMER_KEY = $TwitterOauthData['CONSUMER_KEY'] ? $TwitterOauthData['CONSUMER_KEY'] : '';
                                $CONSUMER_SECRET = $TwitterOauthData['CONSUMER_SECRET'] ? $TwitterOauthData['CONSUMER_SECRET'] : '';
                                $oauthToken = $TwitterOauthData['oauthToken'] ? $TwitterOauthData['oauthToken'] : '';
                                $oauthTokenSecret = $TwitterOauthData['oauthTokenSecret'] ? $TwitterOauthData['oauthTokenSecret'] : '';
                                $redirectUri = $TwitterOauthData['oauth_callback'] ? $TwitterOauthData['oauth_callback'] : '';
                                $enabletauth = $TwitterOauthData['enabletauth'] ? $TwitterOauthData['enabletauth'] : 0;
                                ?>
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12">
                                            <label><i><?php echo $translate['twitter_auth_enable']; ?></i></label>
                                            <select id="enabletauth" name="enabletauth" class="form-control"">
                                                <option value=" 1" <?php echo $enabletauth == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                <option value="0" <?php echo $enabletauth == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['CONSUMER_KEY']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $CONSUMER_KEY; ?>" name="CONSUMER_KEY" placeholder="<?php echo $translate['enter_CONSUMER_KEY']; ?>">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['CONSUMER_SECRET']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $CONSUMER_SECRET; ?>" name="CONSUMER_SECRET" placeholder="<?php echo $translate['enter_CONSUMER_SECRET']; ?>">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['oauthToken']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $oauthToken; ?>" name="oauthToken" placeholder="<?php echo $translate['enter_oauthToken']; ?>">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['oauthTokenSecret']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $oauthTokenSecret; ?>" name="oauthTokenSecret" placeholder="<?php echo $translate['enter_oauthTokenSecret']; ?>">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label style="max-width:100%;color:#1d9bf0"><i><?php echo $translate['twitter_auth_redirect_url']; ?> <?php echo $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . str_replace('appginilte_settings.php', 'appginilte_twitter.php', $_SERVER['REQUEST_URI']); ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $redirectUri; ?>" name="oauth_callback" id="oauth_callback" placeholder="<?php echo $translate['twitter_auth_redirect_url_description']; ?>">
                                        </div>
                                    </div>
                                    <br><button type="submit" class="btn btn-primary" name="tauth"><?php echo $translate['save_changes']; ?></button>
                                </form>
                                </div>
                                <hr>
                                <div class="row">
                                    <?php echo showCallout('info',$translate['Cutom Login Methods'].' <span class="fas fa-puzzle-piece"></span>',$translate['custom_login_methods_description']); ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="custom-content-inlineedit" role="tabpanel" aria-labelledby="custom-content-inlineedit">
                                <br>
                                <?php echo showCallout('info', $translate['table_actions'], $translate['table_actions_description']); ?>

                                <?php
                                foreach ($groups as $grp => $tables) {
                                    echo '<div class="card card-primary collapsed-card">
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
                                        $decode = get_option("table_actions_$table_name");
                                        $access_to_action_view = $decode['access_to_action_view_' . $table_name];
                                        $access_to_action_edit = $decode['access_to_action_edit_' . $table_name];
                                        $access_to_action_delete = $decode['access_to_action_delete_' . $table_name];
                                        $tableactions = $decode['tableactions_' . $table_name] ? $decode['tableactions_' . $table_name] : 0;
                                        $tblgrps = $groups = sql("SELECT name FROM membership_groups", $eo);
                                        echo '
                                        <form action="" method="POST">
                                        <input type="hidden" id="table_name" name="table_name" value="' . $table_name . '">
                                        <label class="text-primary">' . $table_title . ' <span class="fa fa-table"></span></label>
                                        <div class="row">
                                        <div class="col-lg-3 col-sm-12">
                                            <label><i>' . $translate['enable_table_view_actions_on'] . ' ' . $table_title . ' ?</i></label>
                                            <select id="tableactions_' . $table_name . '" name="tableactions_' . $table_name . '" class="form-control"">
                                                <option value="1" ' . ($tableactions == 1 ? 'selected' : '') . '>' . $translate['yes'] . '</option>
                                                <option value="0" ' . ($tableactions == 0 ? 'selected' : '') . '>' . $translate['no'] . '</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-sm-12">
                                            <label><i>' . $translate['access_to_action_view'] . '</i></label>
                                            <select multiple id="access_to_action_view_' . $table_name . '" name="access_to_action_view_' . $table_name . '[]" class="form-control"">';
                                        foreach ($tblgrps as $grp) {
                                            # code...
                                            echo '<option value="' . $grp['name'] . '" ' . (strpos($access_to_action_view, $grp['name']) !== false ? 'selected' : '') . '>' . $grp['name'] . '</option>';
                                        }
                                        echo ' </select>
                                        </div>
                                        <div class="col-lg-3 col-sm-12">
                                        <label><i>' . $translate['access_to_action_edit'] . '</i></label>
                                        <select multiple id="access_to_action_edit_' . $table_name . '" name="access_to_action_edit_' . $table_name . '[]" class="form-control"">';
                                        foreach ($tblgrps as $grp) {
                                            # code...
                                            echo '<option value="' . $grp['name'] . '" ' . (strpos($access_to_action_edit, $grp['name']) !== false ? 'selected' : '') . '>' . $grp['name'] . '</option>';
                                        }
                                        echo ' </select>
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                    <label><i>' . $translate['access_to_action_delete'] . '</i></label>
                                    <select multiple id="access_to_action_delete_' . $table_name . '" name="access_to_action_delete_' . $table_name . '[]" class="form-control"">';
                                        foreach ($tblgrps as $grp) {
                                            # code...
                                            echo '<option value="' . $grp['name'] . '" ' . (strpos($access_to_action_delete, $grp['name']) !== false ? 'selected' : '') . '>' . $grp['name'] . '</option>';
                                        }
                                        echo ' </select>
                                    </div>
                                        <br>
                                        <div class="col-lg-2 col-sm-12 text-center"><button type="submit" class="btn btn-success" name="tableactionssave_' . $table_name . '"><span class="fas fa-check-circle"></span> ' . $translate['save_changes'] . '</button></div>
                                        </div>
                                        </form>
                                        ';
                                        if (isset($_POST['tableactionssave_' . $table_name])) {
                                            $table_name = $_POST['table_name'];
                                            $tableactions = $_POST['tableactions_' . $table_name];
                                            $viewoptions = '';
                                            $editoptions = '';
                                            $deleteoptions = '';
                                            foreach ($_POST['access_to_action_view_' . $table_name] as $selectedOption) {
                                                $viewoptions .= $selectedOption . ',';
                                            }
                                            foreach ($_POST['access_to_action_edit_' . $table_name] as $selectedOption) {
                                                $editoptions .= $selectedOption . ',';
                                            }
                                            foreach ($_POST['access_to_action_delete_' . $table_name] as $selectedOption) {
                                                $deleteoptions .= $selectedOption . ',';
                                            }
                                            $_POST['access_to_action_view_' . $table_name] = rtrim($viewoptions, ",");
                                            $_POST['access_to_action_edit_' . $table_name] = rtrim($editoptions, ",");
                                            $_POST['access_to_action_delete_' . $table_name] = rtrim($deleteoptions, ",");
                                            $jenc = json_encode($_POST);
                                            //create option table_actions_$table_name
                                            set_option("table_actions_$table_name", $jenc);
                                            //create table_name-tv.js file
                                            $insertcode = '//TableActions Code inserted by AdminLTE Plugin For Appgini
                                            window.addEventListener("load", () => { setTimeout(() => { if (TAenabled != 1) { return; } add_buttons(tableName); }, 1000); }); function add_buttons(tn) { const $table = $j(`table[data-tablename="${tn}"]`); $table.find("thead tr th:first-child").css("width", "100px"); $table.find("tbody > tr td:first-child").each(function () { let actionView = ""; let actionEdit = ""; let actionDelete = ""; if (access_to_action_view.includes(groupName)) { actionView = `<a style="margin: 5px;" class="text-success" href="${tn}_view.php?SelectedID=${$j( this ) .closest("tr") .attr( "data-id" )}&dvprint_x=1"><i class="glyphicon glyphicon-eye-open"></i></a>`; } if (access_to_action_edit.includes(groupName)) { actionEdit = `<a style="margin: 5px;" class="text-primary" href="#" onclick="openModal(\'${tn}\',\'${$j( this ) .closest("tr") .attr("data-id")}\');"><i class="glyphicon glyphicon-edit"></i></a> <!-- Modal --> <div class="modal fade" id="${tn}_${$j(this) .closest("tr") .attr( "data-id" )}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg" role="document" style="width: 80%;"> <div class="modal-content" style="height: auto;max-height: none;"> <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <h4 class="modal-title" id="myModalLabel">#${$j(this) .closest("tr") .attr("data-id")}</h4> </div> <div class="modal-body"></div> </div> </div> </div>`; } if (access_to_action_delete.includes(groupName)) { actionDelete = `<a style="margin: 5px;" class="text-danger" href="${tn}_view.php?SelectedID=${$j( this ) .closest("tr") .attr("data-id")}&delete_x=1&csrf_token=${$j( "#csrf_token" ).val()}" onclick="return confirm(AppGini.Translate._map[\'are you sure?\'])"><i class="glyphicon glyphicon-trash"></i></a>`; } $j(this).append(actionView).append(actionEdit).append(actionDelete); }); } function openModal(tn, recID) { parent.launchEditModal(tn, recID); }
                                            //TableActions Code inserted by AdminLTE Plugin For Appgini
                                            ';
                                            //check if fileexists: $table_name.json in hooks folder
                                            if (file_exists("hooks/$table_name-tv.js")) {
                                                //read file: $table_name-tv.js in hooks folder
                                                $filecontents = file_get_contents("hooks/$table_name-tv.js");
                                                //check if code is already inserted
                                                if (!strpos($filecontents, 'TableActions Code inserted by AdminLTE Plugin For Appgini')) {
                                                    //write file: $table_name-tv.js in hooks folder
                                                    file_put_contents("hooks/$table_name-tv.js", $insertcode . "\n" . $filecontents, LOCK_EX);
                                                }
                                            } else {
                                                //write to hooks/$table_name-tv.js
                                                file_put_contents("hooks/$table_name-tv.js", $insertcode);
                                            }
                                            echo showToast('success', $translate['changes_saved_successfully'], $translate['inline_edit_changes_saved_successfully']);
                                        }
                                    }
                                    echo '</div></div>';
                                }
                                ?>
                            </div>
                            <div class="tab-pane fade" id="custom-content-grecap" role="tabpanel" aria-labelledby="custom-content-gauth">
                                <br>
                                <?php echo showCallout('info', $translate['google_recaptcha'], $translate['google_recaptcha_description']);
                                $reCaptchaData = get_option('reCaptcha_config');
                                $clientKey = $reCaptchaData['RECAPTCHA_SITE_KEY'] ? $reCaptchaData['RECAPTCHA_SITE_KEY'] : '';
                                $clientSecret = $reCaptchaData['RECAPTCHA_SECRET_KEY'] ? $reCaptchaData['RECAPTCHA_SECRET_KEY'] : '';
                                $enablereCaptcha = $reCaptchaData['enablereCaptcha'] ? $reCaptchaData['enablereCaptcha'] : '';
                                ?>
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12">
                                            <label><i><?php echo $translate['enable_google_recaptcha']; ?></i></label>
                                            <select id="enablereCaptcha" name="enablereCaptcha" class="form-control"">
                                                <option value=" 1" <?php echo $enablereCaptcha == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                <option value="0" <?php echo $enablereCaptcha == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['client_key']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $clientKey; ?>" name="RECAPTCHA_SITE_KEY" placeholder="<?php echo $translate['enter_client_key']; ?>">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['client_secret']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $clientSecret; ?>" name="RECAPTCHA_SECRET_KEY" placeholder="<?php echo $translate['enter_client_secret']; ?>">
                                        </div>
                                    </div>
                                    <br><button type="submit" class="btn btn-success" name="grecap"><?php echo $translate['save_changes']; ?></button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="custom-content-gensetts" role="tabpanel" aria-labelledby="custom-content-gensetts">
                                <?php 
                                $create_allowed = is_writable(__DIR__); if ($create_allowed) { $createfiles= '<span class="badge badge-success">'.$translate['Creating files on your environment is allowed.'].'</span>'."<br>"; } else { $createfiles= "<span class='badge badge-danger'> ".$translate['Creating files  on your environment is not allowed.']."</span>"."<br>"; } $read_allowed = is_readable(__FILE__); if ($read_allowed) { $readfiles= "<span class='badge badge-success'> ".$translate['Reading files  on your environment is allowed.']."</span>"."<br>"; } else { $readfiles= "<span class='badge badge-danger'> ".$translate['Reading files  on your environment is not allowed.']."</span>"."<br>"; }
                                echo showCallout('info', $translate['general_settings'], $translate['general_settings_description'] . ' ' . $env['plugin'] . ' version: ' . $env['version'] . ' <br><a href="https://forums.appgini.com/phpbb/viewtopic.php?f=12&t=4792" target="_blank">' . $translate['forum_topic'] . '</a><br> '.$createfiles.' '.$readfiles.'<div class="text-right"><a class="btn btn-success" href="appginilte_install.php" style="text-decoration:none;color:white">' . $translate['install_updates'] . ' <span class="far fa-check-circle"></span></a> <a class="btn btn-danger" href="?action=removelte" style="text-decoration:none;color:white">' . $translate['uninstall'] . '<span class="far fa-trash-alt"></span></a></div>'); ?>
                                <br>
                                <strong class="text-info"><?php echo $translate['access_permissions']; ?></strong>
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['admin_users_can_access_settings']; ?></i></label>
                                            <select id="adminltesettings" name="adminltesettings" class="form-control"">
                                                <option value=" 1" <?php echo $adminltesettings == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                <option value="0" <?php echo $adminltesettings == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['admin_users_can_access_user_area_permissions']; ?></i></label>
                                            <select id="userareapems" name="userareapems" class="form-control"">
                                                <option value=" 1" <?php echo $userareapems == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                <option value="0" <?php echo $userareapems == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['admin_users_can_access_report_builder']; ?></i></label>
                                            <select id="dashbuilder" name="dashbuilder" class="form-control"">
                                                <option value=" 1" <?php echo $dashbuilder == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                <option value="0" <?php echo $dashbuilder == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <strong class="text-info"><?php echo $translate['global_search']; ?></strong>
                                    <!-- Show drop down: enable global search -->
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['enable_global_search']; ?></i></label>
                                            <select id="globalsearch" name="globalsearch" class="form-control"">
                                                <option value=" 1" <?php echo $globalsearch == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                <option value="0" <?php echo $globalsearch == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                            </select>
                                        </div>
                                        <!-- show list options of groups that can acess global search -->
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['groups_that_can_access_global_search']; ?></i></label>
                                            <select id="globalsearchgroups" name="globalsearchgroups[]" class="form-control" multiple>
                                                <option value="na" <?php if (in_array('na', $globalsearchgroups)) {echo "selected"; } ?>><?php echo $translate['None']; ?></option>
                                                <?php
                                                $groups = sql("SELECT name FROM membership_groups", $eo);
                                                foreach ($groups as $group) {
                                                    $selected = '';
                                                    if (in_array($group['name'], $globalsearchgroups)) {
                                                        $selected = 'selected';
                                                    }

                                                    echo '<option value="' . $group['name'] . '" ' . $selected . '>' . $group['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <br><button type="submit" class="btn btn-success" name="accessperms"><?php echo $translate['save_changes']; ?></button>
                                </form>
                                <!-- acess permissions end -->
                                <hr>
                                <strong class="text-info"><?php echo $translate['general_configurations']; ?> </strong>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="sitename"><i><?php echo $translate['app_name']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $appName; ?>" name="appName" id="appName" placeholder="<?php echo $translate['enter_app_name']; ?>">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="appicon"><i><?php echo $translate['app_icon']; ?></i></label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="appIcon" name="appIcon">
                                                <label class="custom-file-label" for="customFile"><?php echo $translate['choose_app_icon']; ?></label>
                                            </div>

                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="footertext"><i><?php echo $translate['footer_text_bottom_right']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $footerText; ?>" name="footerText" id="footerText" placeholder="<?php echo $translate['enter_footer_text_bottom_right']; ?>">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['enable_sweet_alert']; ?></i></label>
                                            <select id="enablesweetalert" name="enablesweetalert" class="form-control"">
                                                <option value=" 1" <?php echo $enablesweetalert == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                <option value="0" <?php echo $enablesweetalert == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['user_session_expiry']; ?></i></label>
                                            <input type="number" class="form-control" value="<?php echo $sessionExpiry; ?>" name="sessionExpiry" placeholder="<?php echo $translate['enter_user_session_expiry']; ?>" min="1" max="86400">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['enable_custom_pages']; ?></i></label>
                                            <select id="enablecustompages" name="enablecustompages" class="form-control"">
                                                <option value=" 1" <?php echo $enablecustompages == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                <option value="0" <?php echo $enablecustompages == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['dashboard_welcome_message']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $dashboardWelcomeMessage; ?>" name="dashboardWelcomeMessage" placeholder="<?php echo $translate['enter_dashboard_welcome_message']; ?>">
                                        </div>
                                        <!-- site loader animation gif ur -->
                                        <div class="col-lg-4 col-sm-12">
                                            <label for="siteloader"><i><?php echo $translate['site_loader_animation_url']; ?></i></label>
                                            <input type="text" class="form-control" value="<?php echo $siteLoader; ?>" name="siteLoader" id="siteLoader" placeholder="<?php echo $translate['enter_site_loader_animation_url']; ?>">
                                        </div>
                                        <!-- Enable Appgini UI theme switcher -->
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['enable_appgini_ui_theme_switcher']; ?></i></label>
                                            <select id="enablethemeswitcherAppgini" name="enablethemeswitcherAppgini" class="form-control"">
                                                <option value=" 1" <?php echo $enablethemeswitcherAppgini == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                <option value="0" <?php echo $enablethemeswitcherAppgini == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                            </select>
                                        </div>
                                        <!-- Enable RTL -->
                                        <div class="col-lg-4 col-sm-12">
                                            <label><i><?php echo $translate['enableRTL']; ?></i></label>
                                            <select id="enableRTL" name="enableRTL" class="form-control"">
                                                <option value=" 1" <?php echo $enableRTL == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                <option value="0" <?php echo $enableRTL == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <br><button type="submit" class="btn btn-warning" name="generalsetts"><?php echo $translate['save_changes']; ?></button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="custom-content-totp" role="tabpanel" aria-labelledby="custom-content-totp">
                                <?php echo showCallout('info', $translate['totp'], $translate['totp_description']); ?>
                                <strong class="text-info"><?php echo $translate['totp_configurations']; ?> </strong>
                                <form action="appginilte_settings.php" method="post">
                                    <?php
                                    $getgroups = sql("SELECT name FROM `membership_groups` WHERE name !='anonymous'", $eo);
                                    $tjson = get_option('totp_config');
                                    foreach ($getgroups as $key => $value) {
                                        # code...
                                        $groupname = $value['name'];
                                        $concatGroupName = str_replace(' ', '_', $value['name']);
                                        $totpEnabled = $tjson[$concatGroupName . '_enabletotp'] ? $tjson[$concatGroupName . '_enabletotp'] : '0';
                                        ?>
                                                        <label class="text-primary"><i><?php echo $groupname; ?></i> <span class="fas fa-users"></span></label>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-sm-12">
                                                                <label><i><?php echo $translate['enable_totp']; ?></i></label>
                                                                <select id="enabletotp" name="<?php echo $concatGroupName; ?>_enabletotp" class="form-control"">
                                                                <option value=" 1" <?php echo $totpEnabled == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                                    <option value="0" <?php echo $totpEnabled == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                    <?php } ?>
                                    <br><button type="submit" class="btn btn-success" name="totp"><?php echo $translate['save_changes']; ?></button>
                                </form>
                                <hr>
                                <strong class="text-danger"><?php echo $translate['disable_totp_for_member']; ?></strong>
                                <p> <small><?php echo $translate['disable_totp_description']; ?></small></p>
                                <form action="appginilte_settings.php" method="post">
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12">
                                            <label><i><?php echo $translate['Username']; ?>:</i></label>
                                            <input type="text" class="form-control" name="memberID" placeholder="<?php echo $translate['enter_username']; ?>">
                                        </div>
                                    </div>
                                    <br><button type="submit" class="btn btn-danger" name="disabletotp"><?php echo $translate['disable_totp']; ?></button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="custom-content-lotp" role="tabpanel" aria-labelledby="custom-content-gauth">
                                <?php echo showCallout('info', $translate['login_otp'], $translate['login_otp_description']); ?>
                                <?php
                                include 'config.php';
                                $mail_function = $adminConfig['mail_function'];
                                $smtp_enabled = ($mail_function == 'smtp') ? 'Yes' : 'No';
                                $smtp_server = $adminConfig['smtp_server'];
                                $smtp_port = $adminConfig['smtp_port'];
                                $smtp_encryption = $adminConfig['smtp_encryption'];
                                $smtp_username = $adminConfig['smtp_user'];
                                $senderName = $adminConfig['senderName'];
                                ?>
                                <!-- show status -->
                                <div class="card card-widget widget-user-2">
                                    <!-- Add the bg color to the header using any of the bg-* classes -->
                                    <div class="widget-user-header bg-warning">
                                        <!-- /.widget-user-image -->
                                        <h4 class="widget-user-username"><strong><?php echo $appName; ?> <?php echo $translate['smtp_status']; ?></strong></h4>
                                    </div>
                                    <div class="card-footer p-0">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a href="admin/pageSettings.php" class="nav-link">
                                                <?php echo $translate['smtp_enabled']; ?> <h4 class="float-right"><span class="badge bg-primary "><?php echo $smtp_enabled; ?></span></h4>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">
                                                <?php echo $translate['smtp_server']; ?> <h4 class="float-right"><span class="badge bg-success "><?php echo $smtp_server; ?></span></h4>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">
                                                <?php echo $translate['smtp_port']; ?> <h4 class="float-right"><span class="badge bg-info "><?php echo $smtp_port; ?></span></h4>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">
                                                <?php echo $translate['smtp_encryption']; ?> <h4 class="float-right"><span class="badge bg-warning "><?php echo strtoupper($smtp_encryption); ?></span></h4>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">
                                                <?php echo $translate['smtp_username']; ?> <h4 class="float-right"><span class="badge bg-secondary "><?php echo $smtp_username; ?></span></h4>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link">
                                                <?php echo $translate['sender_name']; ?> <h4 class="float-right"><span class="badge bg-danger "><?php echo $senderName; ?></span></h4>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- show status -->
                                <!-- Show groups form -->
                                <form action="appginilte_settings.php" method="post">
                                    <?php
                                    $getgroups = sql("SELECT name FROM `membership_groups` WHERE name !='anonymous'", $eo);
                                    $tjson = get_option('lotp_config');
                                    foreach ($getgroups as $key => $value) {
                                        # code...
                                        $groupname = $value['name'];
                                        $concatGroupName = str_replace(' ', '_', $value['name']);
                                        $otpEnabled = $tjson[$concatGroupName . '_enablelotp'] ? $tjson[$concatGroupName . '_enablelotp'] : '0';
                                        ?>
                                                        <label class="text-primary"><i><?php echo $groupname; ?></i> <span class="fas fa-users"></span></label>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-sm-12">
                                                                <label><i><?php echo $translate['enable_login_otp']; ?></i></label>
                                                                <select id="enablelotp" name="<?php echo $concatGroupName; ?>_enablelotp" class="form-control"">
                                                                <option value=" 1" <?php echo $otpEnabled == 1 ? 'selected' : ''; ?>><?php echo $translate['yes']; ?></option>
                                                                    <option value="0" <?php echo $otpEnabled == 0 ? 'selected' : ''; ?>><?php echo $translate['no']; ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                    <?php } ?>
                                    <br><button type="submit" class="btn btn-success" name="lotp"><?php echo $translate['save_changes']; ?></button>
                                </form>
                                <!-- show groups form -->
                            </div>
                            <div class="tab-pane fade" id="custom-content-dbbackup" role="tabpanel" aria-labelledby="custom-content-dbbackup">
                                <br>
                                <?php echo showCallout('info', $translate['database_backup'], $translate['database_backup_description']); ?>

                                <!-- DB-BACKUP-tabs -->
                                <div class="card card-primary card-outline card-outline-tabs">
                                    <div class="card-header p-0 border-bottom-0">
                                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="custom-tabs-four-dbbackup-tab" data-toggle="pill" href="#custom-tabs-four-dbbackup" role="tab" aria-controls="custom-tabs-four-dbbackup" aria-selected="true"><span class="fas fa-cloud-upload-alt"></span> <?php echo $translate['backup_database']; ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-four-restore-tab" data-toggle="pill" href="#custom-tabs-four-restore" role="tab" aria-controls="custom-tabs-four-restore" aria-selected="false"><span class="fas fa-redo-alt"></span> <?php echo $translate['restore_database']; ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-four-download-tab" data-toggle="pill" href="#custom-tabs-four-download" role="tab" aria-controls="custom-tabs-four-download" aria-selected="false"><span class="fas fa-cloud-download-alt"></span> <?php echo $translate['download_database_backup']; ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-four-upload-tab" data-toggle="pill" href="#custom-tabs-four-upload" role="tab" aria-controls="custom-tabs-four-upload" aria-selected="false"><span class="fas fa-upload"></span> <?php echo $translate['import_external_database_backup']; ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-four-delete-tab" data-toggle="pill" href="#custom-tabs-four-delete" role="tab" aria-controls="custom-tabs-four-delete" aria-selected="false"><span class="far fa-trash-alt"></span> <?php echo $translate['delete_database_backup']; ?></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-four-automate-tab" data-toggle="pill" href="#custom-tabs-four-automate" role="tab" aria-controls="custom-tabs-four-automate" aria-selected="false"><span class="far fa-clock"></span> <?php echo $translate['automated_database_backup']; ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-four-tabContent">
                                            <div class="tab-pane fade active show" id="custom-tabs-four-dbbackup" role="tabpanel" aria-labelledby="custom-tabs-four-dbbackup-tab">
                                                <form action="" method="POST" onSubmit="return confirm('<?php echo $translate['database_backup_confirmation']; ?>');">
                                                    <div class="row">
                                                        <!-- select backyp file type -->
                                                        <div class="form-group col-lg-4 col-sm-12">
                                                            <label><i><?php echo $translate['select_backup_file_type']; ?></i></label>
                                                            <select id="backupfiletype" name="backupfiletype" class="form-control"">
                                                                <option value=" sql"><?php echo $translate['sql']; ?></option>
                                                                <option value="zip"><?php echo $translate['zip']; ?></option>
                                                            </select>
                                                        </div>
                                                        <!-- select database backup mode -->
                                                        <div class="form-group col-lg-4 col-sm-12">
                                                            <label><i><?php echo $translate['database_backup_mode']; ?></i></label>
                                                            <select id="backupmode" name="backupmode" class="form-control"">
                                                                <option value=" full"><?php echo $translate['full_backup']; ?></option>
                                                                <option value="partial"><?php echo $translate['partial_backup']; ?></option>
                                                            </select>
                                                        </div>
                                                        <!-- select database tables for partial backup -->
                                                        <div class="form-group col-lg-4 col-sm-12" id="partialbackupdiv">
                                                            <label><i><?php echo $translate['select_tables_for_partial_backup']; ?></i></label>
                                                            <select id="partialbackup" name="partialbackup[]" class="form-control" multiple>
                                                                <?php
                                                                $tables = sql("SHOW TABLES", $eo);
                                                                while ($row = $tables->fetch_row()) {
                                                                    echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <button type="submit" class="btn btn-success" name="backupdb"><span class="fas fa-upload"></span> <?php echo $translate['backup']; ?></button>
                                                    </div>
                                                </form>
                                                <!-- script on change backupmode show or hide partialbackupdiv -->
                                                <script>
                                                    //by default hide partialbackupdiv
                                                    $("#partialbackupdiv").hide();
                                                    // on change backupmode show or hide partialbackupdiv
                                                    $(document).ready(function() {
                                                        $('#backupmode').on('change', function() {
                                                            if (this.value == 'partial') {
                                                                $("#partialbackupdiv").show();
                                                            } else {
                                                                $("#partialbackupdiv").hide();
                                                            }
                                                        });
                                                    });
                                                </script>
                                                <!-- if isset POST backupdb -->
                                                <?php
                                                if (isset($_POST['backupdb'])) {
                                                    // get backup file type
                                                    $backupfiletype = $_POST['backupfiletype'];
                                                    // get backup mode
                                                    $backupmode = $_POST['backupmode'];
                                                    // get partial backup tables
                                                    $partialbackup = $_POST['partialbackup'];
                                                    //create a list of camma separated values for partial backup
                                                    $partialbackuplist = implode([","], $partialbackup);
                                                    //if backup mode is full then value of backuptables is * else value is partialbackuplist
                                                    if ($backupmode == 'full') {
                                                        $backuptables = '*';
                                                    } else {
                                                        $backuptables = $partialbackuplist;
                                                    }
                                                    //if backupfiletype is sql then GZIP_BACKUP_FILE is false else GZIP_BACKUP_FILE is true
                                                    if ($backupfiletype == 'sql') {
                                                        define('GZIP_BACKUP_FILE', false);
                                                    } else {
                                                        define('GZIP_BACKUP_FILE', true);
                                                    }

                                                    // BACKUP SCRIPT
                                                    include 'config.php';

                                                    define("DB_USER", $dbUsername);
                                                    define("DB_PASSWORD", $dbPassword);
                                                    define("DB_NAME", $dbDatabase);
                                                    define("DB_HOST", $dbServer);
                                                    define("BACKUP_DIR", 'appginilte/db_backup/backup-files'); // Comment this line to use same script's directory ('.')
                                                    define("TABLES", $backuptables); // Full or partial backup
                                                    define("CHARSET", 'utf8');
                                                    define("DISABLE_FOREIGN_KEY_CHECKS", true); // Set to true if you are having foreign key constraint fails
                                                    define("BATCH_SIZE", 10000); // Batch size when selecting rows from database in order to not exhaust system memory
                                                    // Also number of rows per INSERT statement in backup file
                                                    /**
                                                     * The Backup_Database class
                                                     */
                                                    class Backup_Database
                                                    {
                                                        /**
                                                         * Host where the database is located
                                                         */
                                                        var $host;
                                                        /**
                                                         * Username used to connect to database
                                                         */
                                                        var $username;
                                                        /**
                                                         * Password used to connect to database
                                                         */
                                                        var $passwd;
                                                        /**
                                                         * Database to backup
                                                         */
                                                        var $dbName;
                                                        /**
                                                         * Database charset
                                                         */
                                                        var $charset;
                                                        /**
                                                         * Database connection
                                                         */
                                                        var $conn;
                                                        /**
                                                         * Backup directory where backup files are stored 
                                                         */
                                                        var $backupDir;
                                                        /**
                                                         * Output backup file
                                                         */
                                                        var $backupFile;
                                                        /**
                                                         * Use gzip compression on backup file
                                                         */
                                                        var $gzipBackupFile;
                                                        /**
                                                         * Content of standard output
                                                         */
                                                        var $output;
                                                        /**
                                                         * Disable foreign key checks
                                                         */
                                                        var $disableForeignKeyChecks;
                                                        /**
                                                         * Batch size, number of rows to process per iteration
                                                         */
                                                        var $batchSize;
                                                        /**
                                                         * Constructor initializes database
                                                         */
                                                        public function __construct($host, $username, $passwd, $dbName, $charset = 'utf8')
                                                        {
                                                            $this->host = $host;
                                                            $this->username = $username;
                                                            $this->passwd = $passwd;
                                                            $this->dbName = $dbName;
                                                            $this->charset = $charset;
                                                            $this->conn = $this->initializeDatabase();
                                                            $this->backupDir = BACKUP_DIR ? BACKUP_DIR : '.';
                                                            $this->backupFile = 'myphp-backup-' . $this->dbName . '-' . date("Ymd_His", time()) . '.sql';
                                                            $this->gzipBackupFile = defined('GZIP_BACKUP_FILE') ? GZIP_BACKUP_FILE : true;
                                                            $this->disableForeignKeyChecks = defined('DISABLE_FOREIGN_KEY_CHECKS') ? DISABLE_FOREIGN_KEY_CHECKS : true;
                                                            $this->batchSize = defined('BATCH_SIZE') ? BATCH_SIZE : 1000; // default 1000 rows
                                                            $this->output = '';
                                                        }
                                                        protected function initializeDatabase()
                                                        {
                                                            try {
                                                                $conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);
                                                                if (mysqli_connect_errno()) {
                                                                    throw new Exception('ERROR connecting database: ' . mysqli_connect_error());
                                                                    die();
                                                                }
                                                                if (!mysqli_set_charset($conn, $this->charset)) {
                                                                    mysqli_query($conn, 'SET NAMES ' . $this->charset);
                                                                }
                                                            } catch (Exception $e) {
                                                                print_r($e->getMessage());
                                                                die();
                                                            }
                                                            return $conn;
                                                        }
                                                        /**
                                                         * Backup the whole database or just some tables
                                                         * Use '*' for whole database or 'table1 table2 table3...'
                                                         * @param string $tables
                                                         */
                                                        public function backupTables($tables = '*')
                                                        {
                                                            try {
                                                                /**
                                                                 * Tables to export
                                                                 */
                                                                if ($tables == '*') {
                                                                    $tables = array();
                                                                    $result = mysqli_query($this->conn, 'SHOW TABLES');
                                                                    while ($row = mysqli_fetch_row($result)) {
                                                                        $tables[] = $row[0];
                                                                    }
                                                                } else {
                                                                    $tables = is_array($tables) ? $tables : explode(',', str_replace(' ', '', $tables));
                                                                }
                                                                $sql = 'CREATE DATABASE IF NOT EXISTS `' . $this->dbName . "`;\n\n";
                                                                $sql .= 'USE `' . $this->dbName . "`;\n\n";
                                                                /**
                                                                 * Disable foreign key checks 
                                                                 */
                                                                if ($this->disableForeignKeyChecks === true) {
                                                                    $sql .= "SET foreign_key_checks = 0;\n\n";
                                                                }
                                                                /**
                                                                 * Iterate tables
                                                                 */
                                                                foreach ($tables as $table) {
                                                                    $this->obfPrint("Backing up `" . $table . "` table..." . str_repeat('.', 50 - strlen($table)), 0, 0);
                                                                    /**
                                                                     * CREATE TABLE
                                                                     */
                                                                    $sql .= 'DROP TABLE IF EXISTS `' . $table . '`;';
                                                                    $row = mysqli_fetch_row(mysqli_query($this->conn, 'SHOW CREATE TABLE `' . $table . '`'));
                                                                    $sql .= "\n\n" . $row[1] . ";\n\n";
                                                                    /**
                                                                     * INSERT INTO
                                                                     */
                                                                    $row = mysqli_fetch_row(mysqli_query($this->conn, 'SELECT COUNT(*) FROM `' . $table . '`'));
                                                                    $numRows = $row[0];
                                                                    // Split table in batches in order to not exhaust system memory 
                                                                    $numBatches = intval($numRows / $this->batchSize) + 1; // Number of while-loop calls to perform
                                                                    for ($b = 1; $b <= $numBatches; $b++) {

                                                                        $query = 'SELECT * FROM `' . $table . '` LIMIT ' . ($b * $this->batchSize - $this->batchSize) . ',' . $this->batchSize;
                                                                        $result = mysqli_query($this->conn, $query);
                                                                        $realBatchSize = mysqli_num_rows($result); // Last batch size can be different from $this->batchSize
                                                                        $numFields = mysqli_num_fields($result);
                                                                        if ($realBatchSize !== 0) {
                                                                            $sql .= 'INSERT INTO `' . $table . '` VALUES ';
                                                                            for ($i = 0; $i < $numFields; $i++) {
                                                                                $rowCount = 1;
                                                                                while ($row = mysqli_fetch_row($result)) {
                                                                                    $sql .= '(';
                                                                                    for ($j = 0; $j < $numFields; $j++) {
                                                                                        if (isset($row[$j])) {
                                                                                            $row[$j] = addslashes($row[$j]);
                                                                                            $row[$j] = str_replace("\n", "\\n", $row[$j]);
                                                                                            $row[$j] = str_replace("\r", "\\r", $row[$j]);
                                                                                            $row[$j] = str_replace("\f", "\\f", $row[$j]);
                                                                                            $row[$j] = str_replace("\t", "\\t", $row[$j]);
                                                                                            $row[$j] = str_replace("\v", "\\v", $row[$j]);
                                                                                            $row[$j] = str_replace("\a", "\\a", $row[$j]);
                                                                                            $row[$j] = str_replace("\b", "\\b", $row[$j]);
                                                                                            if ($row[$j] == 'true' or $row[$j] == 'false' or preg_match('/^-?[0-9]+$/', $row[$j]) or $row[$j] == 'NULL' or $row[$j] == 'null') {
                                                                                                $sql .= $row[$j];
                                                                                            } else {
                                                                                                $sql .= '"' . $row[$j] . '"';
                                                                                            }
                                                                                        } else {
                                                                                            $sql .= 'NULL';
                                                                                        }

                                                                                        if ($j < ($numFields - 1)) {
                                                                                            $sql .= ',';
                                                                                        }
                                                                                    }

                                                                                    if ($rowCount == $realBatchSize) {
                                                                                        $rowCount = 0;
                                                                                        $sql .= ");\n"; //close the insert statement
                                                                                    } else {
                                                                                        $sql .= "),\n"; //close the row
                                                                                    }

                                                                                    $rowCount++;
                                                                                }
                                                                            }

                                                                            $this->saveFile($sql);
                                                                            $sql = '';
                                                                        }
                                                                    }
                                                                    $sql .= "\n\n";
                                                                    $this->obfPrint('OK');
                                                                }
                                                                /**
                                                                 * Re-enable foreign key checks 
                                                                 */
                                                                if ($this->disableForeignKeyChecks === true) {
                                                                    $sql .= "SET foreign_key_checks = 1;\n";
                                                                }
                                                                $this->saveFile($sql);
                                                                if ($this->gzipBackupFile) {
                                                                    $this->gzipBackupFile();
                                                                } else {
                                                                    $this->obfPrint('Backup file succesfully saved to ' . $this->backupDir . '/' . $this->backupFile, 1, 1);
                                                                }
                                                            } catch (Exception $e) {
                                                                print_r($e->getMessage());
                                                                return false;
                                                            }
                                                            return true;
                                                        }
                                                        /**
                                                         * Save SQL to file
                                                         * @param string $sql
                                                         */
                                                        protected function saveFile(&$sql)
                                                        {
                                                            if (!$sql)
                                                                return false;
                                                            try {
                                                                if (!file_exists($this->backupDir)) {
                                                                    mkdir($this->backupDir, 0777, true);
                                                                }
                                                                file_put_contents($this->backupDir . '/' . $this->backupFile, $sql, FILE_APPEND | LOCK_EX);
                                                            } catch (Exception $e) {
                                                                print_r($e->getMessage());
                                                                return false;
                                                            }
                                                            return true;
                                                        }
                                                        /*
                                                         * Gzip backup file
                                                         *
                                                         * @param integer $level GZIP compression level (default: 9)
                                                         * @return string New filename (with .gz appended) if success, or false if operation fails
                                                         */
                                                        protected function gzipBackupFile($level = 9)
                                                        {
                                                            if (!$this->gzipBackupFile) {
                                                                return true;
                                                            }
                                                            $source = $this->backupDir . '/' . $this->backupFile;
                                                            $dest = $source . '.gz';
                                                            $this->obfPrint('Gzipping backup file to ' . $dest . '... ', 1, 0);
                                                            $mode = 'wb' . $level;
                                                            if ($fpOut = gzopen($dest, $mode)) {
                                                                if ($fpIn = fopen($source, 'rb')) {
                                                                    while (!feof($fpIn)) {
                                                                        gzwrite($fpOut, fread($fpIn, 1024 * 256));
                                                                    }
                                                                    fclose($fpIn);
                                                                } else {
                                                                    return false;
                                                                }
                                                                gzclose($fpOut);
                                                                if (!unlink($source)) {
                                                                    return false;
                                                                }
                                                            } else {
                                                                return false;
                                                            }

                                                            $this->obfPrint('OK');
                                                            return $dest;
                                                        }
                                                        /**
                                                         * Prints message forcing output buffer flush
                                                         *
                                                         */
                                                        public function obfPrint($msg = '', $lineBreaksBefore = 0, $lineBreaksAfter = 1)
                                                        {
                                                            if (!$msg) {
                                                                return false;
                                                            }
                                                            if ($msg != 'OK' and $msg != 'KO') {
                                                                $msg = date("Y-m-d H:i:s") . ' - ' . $msg;
                                                            }
                                                            $output = '';
                                                            if (php_sapi_name() != "cli") {
                                                                $lineBreak = "<br />";
                                                            } else {
                                                                $lineBreak = "\n";
                                                            }
                                                            if ($lineBreaksBefore > 0) {
                                                                for ($i = 1; $i <= $lineBreaksBefore; $i++) {
                                                                    $output .= $lineBreak;
                                                                }
                                                            }
                                                            $output .= $msg;
                                                            if ($lineBreaksAfter > 0) {
                                                                for ($i = 1; $i <= $lineBreaksAfter; $i++) {
                                                                    $output .= $lineBreak;
                                                                }
                                                            }
                                                            // Save output for later use
                                                            $this->output .= str_replace('<br />', '\n', $output);
                                                            echo $output;
                                                            if (php_sapi_name() != "cli") {
                                                                if (ob_get_level() > 0) {
                                                                    ob_flush();
                                                                }
                                                            }
                                                            $this->output .= " ";
                                                            flush();
                                                        }
                                                        /**
                                                         * Returns full execution output
                                                         *
                                                         */
                                                        public function getOutput()
                                                        {
                                                            return $this->output;
                                                        }
                                                    }
                                                    /**
                                                     * Instantiate Backup_Database and perform backup
                                                     */
                                                    // Report all errors
                                                    error_reporting(E_ALL);
                                                    // Set script max execution time
                                                    set_time_limit(900); // 15 minutes
                                                    if (php_sapi_name() != "cli") {
                                                        echo "<br><div class='text-center'><h3>" . $translate['database_backup_results'] . "</h3></div>";
                                                        echo '<div style="font-family: monospace;" class="alert alert-success text-center">';
                                                    }
                                                    $backupDatabase = new Backup_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, CHARSET);
                                                    $result = $backupDatabase->backupTables(TABLES, BACKUP_DIR) ? 'OK' : 'KO';
                                                    $backupDatabase->obfPrint('Backup result: ' . $result, 1);
                                                    // Use $output variable for further processing, for example to send it by email
                                                    $output = $backupDatabase->getOutput();
                                                    if (php_sapi_name() != "cli") {
                                                        echo "<div class='text-center'><a href='' class='btn btn-danger'><b>" . $translate['exit'] . "</b></a></div>";
                                                        echo '</div>';
                                                    }
                                                    //BACKUP SCRIPT
                                                    echo showToast('success', $translate['database_backup'], $translate['database_backup_completed']);
                                                }
                                                ?>
                                            </div>
                                            <div class="tab-pane fade" id="custom-tabs-four-restore" role="tabpanel" aria-labelledby="custom-tabs-four-restore-tab">
                                                <!-- select file db to restore-->
                                                <form action="" method="POST" onSubmit="return confirm('<?php echo $translate['database_restore_confirmation']; ?>');">
                                                    <div class="row">
                                                        <div class="form-group col-lg-12 col-sm-12">
                                                            <label><i><?php echo $translate['select_backup_file_to_restore']; ?></i></label>
                                                            <select class="form-control" id="backup" name="backup">
                                                                <?php
                                                                if ($handle = opendir('appginilte/db_backup/backup-files')) {

                                                                    while (false !== ($entry = readdir($handle))) {

                                                                        if ($entry != "." && $entry != ".." && $entry != "index.html") {

                                                                            echo "<option value='$entry'>$entry</option>";
                                                                        }
                                                                    }

                                                                    closedir($handle);
                                                                } ?>
                                                            </select>
                                                        </div>
                                                        <button type="submit" class="btn btn-success" name="restoredb"><span class="fas fa-redo-alt"></span> <?php echo $translate['restore']; ?></button>
                                                    </div>
                                                </form>
                                                <!-- if isset post restoredb -->
                                                <?php
                                                if (isset($_POST['restoredb'])) {
                                                    // get backup file name
                                                    $backupfile = $_POST['backup'];
                                                    //RESTORE SCRIPT
                                                    include 'config.php';
                                                    define("DB_USER", $dbUsername);
                                                    define("DB_PASSWORD", $dbPassword);
                                                    define("DB_NAME", $dbDatabase);
                                                    define("DB_HOST", $dbServer);
                                                    define("BACKUP_DIR", 'appginilte/db_backup/backup-files'); // Comment this line to use same script's directory ('.')
                                                    define("BACKUP_FILE", $backupfile); // Script will autodetect if backup file is gzipped based on .gz extension
                                                    define("CHARSET", 'utf8');
                                                    define("DISABLE_FOREIGN_KEY_CHECKS", true); // Set to true if you are having foreign key constraint fails
                                                    /**
                                                     * The Restore_Database class
                                                     */
                                                    class Restore_Database
                                                    {
                                                        /**
                                                         * Host where the database is located
                                                         */
                                                        var $host;
                                                        /**
                                                         * Username used to connect to database
                                                         */
                                                        var $username;
                                                        /**
                                                         * Password used to connect to database
                                                         */
                                                        var $passwd;
                                                        /**
                                                         * Database to backup
                                                         */
                                                        var $dbName;
                                                        /**
                                                         * Database charset
                                                         */
                                                        var $charset;
                                                        /**
                                                         * Database connection
                                                         */
                                                        var $conn;
                                                        /**
                                                         * Disable foreign key checks
                                                         */
                                                        var $disableForeignKeyChecks;
                                                        /**
                                                         * Constructor initializes database
                                                         */
                                                        function __construct($host, $username, $passwd, $dbName, $charset = 'utf8')
                                                        {
                                                            $this->host = $host;
                                                            $this->username = $username;
                                                            $this->passwd = $passwd;
                                                            $this->dbName = $dbName;
                                                            $this->charset = $charset;
                                                            $this->disableForeignKeyChecks = defined('DISABLE_FOREIGN_KEY_CHECKS') ? DISABLE_FOREIGN_KEY_CHECKS : true;
                                                            $this->conn = $this->initializeDatabase();
                                                            $this->backupDir = defined('BACKUP_DIR') ? BACKUP_DIR : '.';
                                                            $this->backupFile = defined('BACKUP_FILE') ? BACKUP_FILE : null;
                                                        }
                                                        /**
                                                         * Destructor re-enables foreign key checks
                                                         */
                                                        function __destructor()
                                                        {
                                                            /**
                                                             * Re-enable foreign key checks 
                                                             */
                                                            if ($this->disableForeignKeyChecks === true) {
                                                                mysqli_query($this->conn, 'SET foreign_key_checks = 1');
                                                            }
                                                        }
                                                        protected function initializeDatabase()
                                                        {
                                                            try {
                                                                $conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);
                                                                if (mysqli_connect_errno()) {
                                                                    throw new Exception('ERROR connecting database: ' . mysqli_connect_error());
                                                                    die();
                                                                }
                                                                if (!mysqli_set_charset($conn, $this->charset)) {
                                                                    mysqli_query($conn, 'SET NAMES ' . $this->charset);
                                                                }
                                                                /**
                                                                 * Disable foreign key checks 
                                                                 */
                                                                if ($this->disableForeignKeyChecks === true) {
                                                                    mysqli_query($conn, 'SET foreign_key_checks = 0');
                                                                }
                                                            } catch (Exception $e) {
                                                                print_r($e->getMessage());
                                                                die();
                                                            }
                                                            return $conn;
                                                        }
                                                        /**
                                                         * Backup the whole database or just some tables
                                                         * Use '*' for whole database or 'table1 table2 table3...'
                                                         * @param string $tables
                                                         */
                                                        public function restoreDb()
                                                        {
                                                            try {
                                                                $sql = '';
                                                                $multiLineComment = false;
                                                                $backupDir = $this->backupDir;
                                                                $backupFile = $this->backupFile;
                                                                /**
                                                                 * Gunzip file if gzipped
                                                                 */
                                                                $backupFileIsGzipped = substr($backupFile, -3, 3) == '.gz' ? true : false;
                                                                if ($backupFileIsGzipped) {
                                                                    if (!$backupFile = $this->gunzipBackupFile()) {
                                                                        throw new Exception("ERROR: couldn't gunzip backup file " . $backupDir . '/' . $backupFile);
                                                                    }
                                                                }
                                                                /**
                                                                 * Read backup file line by line
                                                                 */
                                                                $handle = fopen($backupDir . '/' . $backupFile, "r");
                                                                if ($handle) {
                                                                    while (($line = fgets($handle)) !== false) {
                                                                        $line = ltrim(rtrim($line));
                                                                        if (strlen($line) > 1) { // avoid blank lines
                                                                            $lineIsComment = false;
                                                                            if (preg_match('/^\/\*/', $line)) {
                                                                                $multiLineComment = true;
                                                                                $lineIsComment = true;
                                                                            }
                                                                            if ($multiLineComment or preg_match('/^\/\//', $line)) {
                                                                                $lineIsComment = true;
                                                                            }
                                                                            if (!$lineIsComment) {
                                                                                $sql .= $line;
                                                                                if (preg_match('/;$/', $line)) {
                                                                                    // execute query
                                                                                    if (mysqli_query($this->conn, $sql)) {
                                                                                        if (preg_match('/^CREATE TABLE `([^`]+)`/i', $sql, $tableName)) {
                                                                                            $this->obfPrint("Table succesfully created: `" . $tableName[1] . "`");
                                                                                        }
                                                                                        $sql = '';
                                                                                    } else {
                                                                                        throw new Exception("ERROR: SQL execution error: " . mysqli_error($this->conn));
                                                                                    }
                                                                                }
                                                                            } else if (preg_match('/\*\/$/', $line)) {
                                                                                $multiLineComment = false;
                                                                            }
                                                                        }
                                                                    }
                                                                    fclose($handle);
                                                                } else {
                                                                    throw new Exception("ERROR: couldn't open backup file " . $backupDir . '/' . $backupFile);
                                                                }
                                                            } catch (Exception $e) {
                                                                print_r($e->getMessage());
                                                                return false;
                                                            }
                                                            if ($backupFileIsGzipped) {
                                                                unlink($backupDir . '/' . $backupFile);
                                                            }
                                                            return true;
                                                        }
                                                        /*
                                                         * Gunzip backup file
                                                         *
                                                         * @return string New filename (without .gz appended and without backup directory) if success, or false if operation fails
                                                         */
                                                        protected function gunzipBackupFile()
                                                        {
                                                            // Raising this value may increase performance
                                                            $bufferSize = 4096; // read 4kb at a time
                                                            $error = false;
                                                            $source = $this->backupDir . '/' . $this->backupFile;
                                                            $dest = $this->backupDir . '/' . date("Ymd_His", time()) . '_' . substr($this->backupFile, 0, -3);
                                                            $this->obfPrint('Gunzipping backup file ' . $source . ' ... Please Wait... ', 1, 1);
                                                            // Remove $dest file if exists
                                                            if (file_exists($dest)) {
                                                                if (!unlink($dest)) {
                                                                    return false;
                                                                }
                                                            }

                                                            // Open gzipped and destination files in binary mode
                                                            if (!$srcFile = gzopen($this->backupDir . '/' . $this->backupFile, 'rb')) {
                                                                return false;
                                                            }
                                                            if (!$dstFile = fopen($dest, 'wb')) {
                                                                return false;
                                                            }
                                                            while (!gzeof($srcFile)) {
                                                                // Read buffer-size bytes
                                                                // Both fwrite and gzread are binary-safe
                                                                if (!fwrite($dstFile, gzread($srcFile, $bufferSize))) {
                                                                    return false;
                                                                }
                                                            }
                                                            fclose($dstFile);
                                                            gzclose($srcFile);
                                                            // Return backup filename excluding backup directory
                                                            return str_replace($this->backupDir . '/', '', $dest);
                                                        }
                                                        /**
                                                         * Prints message forcing output buffer flush
                                                         *
                                                         */
                                                        public function obfPrint($msg = '', $lineBreaksBefore = 0, $lineBreaksAfter = 1)
                                                        {
                                                            if (!$msg) {
                                                                return false;
                                                            }
                                                            $msg = date("Y-m-d H:i:s") . ' - ' . $msg;
                                                            $output = '';
                                                            if (php_sapi_name() != "cli") {
                                                                $lineBreak = "<br />";
                                                            } else {
                                                                $lineBreak = "\n";
                                                            }
                                                            if ($lineBreaksBefore > 0) {
                                                                for ($i = 1; $i <= $lineBreaksBefore; $i++) {
                                                                    $output .= $lineBreak;
                                                                }
                                                            }
                                                            $output .= $msg;
                                                            if ($lineBreaksAfter > 0) {
                                                                for ($i = 1; $i <= $lineBreaksAfter; $i++) {
                                                                    $output .= $lineBreak;
                                                                }
                                                            }
                                                            if (php_sapi_name() == "cli") {
                                                                $output .= "\n";
                                                            }
                                                            echo $output;
                                                            if (php_sapi_name() != "cli") {
                                                                ob_flush();
                                                            }
                                                            flush();
                                                        }
                                                    }
                                                    /**
                                                     * Instantiate Restore_Database and perform backup
                                                     */
                                                    // Report all errors
                                                    error_reporting(E_ALL);
                                                    // Set script max execution time
                                                    set_time_limit(900); // 15 minutes
                                                    if (php_sapi_name() != "cli") {
                                                        echo "<br><div class='text-center'><h3>" . $translate['database_restore_results'] . "</h3></div>";
                                                        echo '<div style="font-family: monospace;" class="alert alert-success">';
                                                    }
                                                    $restoreDatabase = new Restore_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                                                    $result = $restoreDatabase->restoreDb(BACKUP_DIR, BACKUP_FILE) ? 'OK' : 'KO';
                                                    $restoreDatabase->obfPrint("Restoration result: " . $result, 1);
                                                    if (php_sapi_name() != "cli") {
                                                        echo "<div class='text-center'><a href='' class='btn btn-danger'><b>" . $translate['exit'] . "</b></a></div>";
                                                        echo '</div>';
                                                    }
                                                    //RESTORE SCRIPT
                                                    echo showToast('success', $translate['restore_database'], $translate['database_restore_completed']);
                                                }
                                                ?>
                                            </div>
                                            <div class="tab-pane fade" id="custom-tabs-four-download" role="tabpanel" aria-labelledby="custom-tabs-four-download-tab">
                                                <!-- get list of backupfiles for direct download -->
                                                <form action="" method="POST" onSubmit="return confirm('<?php echo $translate['database_download_confirmation']; ?>');">
                                                    <div class="row">
                                                        <div class="form-group col-lg-12 col-sm-12">
                                                            <label><i><?php echo $translate['select_backup_file_to_download']; ?></i></label>
                                                            <select class="form-control" id="backup" name="backup">
                                                                <?php
                                                                if ($handle = opendir('appginilte/db_backup/backup-files')) {

                                                                    while (false !== ($entry = readdir($handle))) {

                                                                        if ($entry != "." && $entry != ".." && $entry != "index.html") {

                                                                            echo "<option value='$entry'>$entry</option>";
                                                                        }
                                                                    }

                                                                    closedir($handle);
                                                                } ?>
                                                            </select>
                                                        </div>
                                                        <button type="submit" class="btn btn-success" name="download"><span class="fas fa-download"></span> <?php echo $translate['download']; ?></button>
                                                    </div>
                                                </form>
                                                <!-- if isset post download -->
                                                <?php
                                                if (isset($_POST['download'])) {
                                                    // get backup file name
                                                    $backupfile = $_POST['backup'];
                                                    //force download bakcup file from appginilte/db_backup/backup-files
                                                    header('Content-Description: File Transfer');
                                                    header('Content-Type: application/octet-stream');
                                                    header('Content-Disposition: attachment; filename=' . basename($backupfile));
                                                    header('Expires: 0');
                                                    header('Cache-Control: must-revalidate');
                                                    header('Pragma: public');
                                                    header('Content-Length: ' . filesize('appginilte/db_backup/backup-files/' . $backupfile));
                                                    readfile('appginilte/db_backup/backup-files/' . $backupfile);
                                                    exit;
                                                }
                                                ?>
                                            </div>
                                            <div class="tab-pane fade" id="custom-tabs-four-upload" role="tabpanel" aria-labelledby="custom-tabs-four-upload-tab">
                                                <!-- Upload file to restore Database -->
                                                <form action="" method="POST" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="form-group col-lg-12 col-sm-12">
                                                            <label><i><?php echo $translate['select_backup_file_to_upload']; ?></i></label><br>
                                                            <input type="file" name="fileToUpload" id="fileToUpload">
                                                        </div>
                                                        <button type="submit" class="btn btn-success" name="uploaddb"><span class="fas fa-upload"></span> <?php echo $translate['upload']; ?></button>
                                                    </div>
                                                </form>
                                                <!-- if isset post uploaddb -->
                                                <?php
                                                if (isset($_POST['uploaddb'])) {
                                                    //store the uploaded file inside appginilte/db_backup/backup-files
                                                    $target_dir = "appginilte/db_backup/backup-files/";
                                                    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                                                    $uploadOk = 1;
                                                    $FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                                                    // Check if file already exists
                                                    if (file_exists($target_file)) {
                                                        $uploadOk = 0;
                                                        $error_message = $translate['file_already_exists'];
                                                    }
                                                    // Check file size
                                                    if ($_FILES["fileToUpload"]["size"] > 500000) {
                                                        $uploadOk = 0;
                                                        $error_message = $translate['file_too_large'];
                                                    }
                                                    // Allow certain file formats
                                                    if ($FileType != "sql" && $FileType != "gz") {
                                                        $uploadOk = 0;
                                                        $error_message = "" . $translate['sorry_only_zip_files_are_allowed'] . " .$FileType";
                                                    }
                                                    // Check if $uploadOk is set to 0 by an error
                                                    if ($uploadOk == 0) {
                                                        echo showToast('error', $translate['upload_backup_file'], $error_message);
                                                        // if everything is ok, try to upload file
                                                    } else {
                                                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                                                            echo showToast('success', $translate['upload_backup_file'], $translate['file_has_been_uploaded']);
                                                        } else {
                                                            echo showToast('error', $translate['upload_backup_file'], $translate['sorry_there_was_an_error_uploading_your_file']);
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <div class="tab-pane fade" id="custom-tabs-four-delete" role="tabpanel" aria-labelledby="custom-tabs-four-delete-tab">
                                                <!-- Used to delete backup file -->
                                                <form action="" method="POST" onSubmit="return confirm('<?php echo $translate['delete_backup_file_confirmation']; ?>');">
                                                    <div class="row">
                                                        <div class="form-group col-lg-12 col-sm-12">
                                                            <label><i><?php echo $translate['select_backup_file_to_delete']; ?></i></label>
                                                            <select class="form-control" id="backup" name="backup">
                                                                <?php
                                                                if ($handle = opendir('appginilte/db_backup/backup-files')) {

                                                                    while (false !== ($entry = readdir($handle))) {

                                                                        if ($entry != "." && $entry != ".." && $entry != "index.html") {

                                                                            echo "<option value='$entry'>$entry</option>";
                                                                        }
                                                                    }

                                                                    closedir($handle);
                                                                } ?>
                                                            </select>
                                                        </div>
                                                        <button type="submit" class="btn btn-success" name="delete_db_backup"><span class="far fa-trash-alt"></span> <?php echo $translate['delete_backup_file']; ?></button>
                                                    </div>
                                                </form>
                                                <!-- if isset post delete_db_backup -->
                                                <?php
                                                if (isset($_POST['delete_db_backup'])) {
                                                    //get backup file name
                                                    $backupfile = $_POST['backup'];
                                                    // delete bacckup file from appginilte/db_backup/backup-files
                                                    unlink("appginilte/db_backup/backup-files/" . $backupfile);
                                                    echo showToast('success', $translate['database_backup_deleted'], $translate['database_file_deleted_successfully']);
                                                }
                                                ?>
                                            </div>
                                            <div class="tab-pane fade" id="custom-tabs-four-automate" role="tabpanel" aria-labelledby="custom-tabs-four-automate-tab">
                                                <!-- show callout, with automate backup details and instructions -->
                                                <?php echo showCallout('info', $translate['automate_database_backup'], $translate['automate_database_backup_description']); ?>
                                                <!-- Show how to create a cron job -->
                                                <?php echo showAlert('info', $translate['how_to_create_a_cron_job'], $translate['create_a_cron_job_description']); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- DB-BACKUP-tabs -->
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
if (isset($_POST['tblgrps'])) {
    # code...
    $encode = json_encode($_POST,JSON_UNESCAPED_UNICODE);
    //create option groups_config
    set_option('groups_config', $encode);
    echo showToast('success', $translate['changes_saved_successfully'], $translate['table_group_changes_saved_successfully']).'<META HTTP-EQUIV="refresh" CONTENT="2; URL=appginilte_settings.php">';
}
?>
<?php
if (isset($_POST['tbls'])) {
    # code...
    $jenc = json_encode($_POST,JSON_UNESCAPED_UNICODE);
    //create option tables_config
    set_option('tables_config', $jenc);
    echo showToast('success', $translate['changes_saved_successfully'], $translate['table_changes_saved_successfully']).'<META HTTP-EQUIV="refresh" CONTENT="2; URL=appginilte_settings.php">';
}
if (isset($_POST['gauth'])) {
    $_POST['redirectUri'] = urldecode($_POST['redirectUri']);
    $jenc = json_encode($_POST);
    //create option GoogleOAuth
    set_option('GoogleOAuth',$jenc);
    echo showToast('success', $translate['changes_saved_successfully'], $translate['google_oauth_changes_saved_successfully']).'<META HTTP-EQUIV="refresh" CONTENT="2; URL=appginilte_settings.php">';
}
if (isset($_POST['mauth'])) {
    $_POST['redirectUri'] = urldecode($_POST['redirectUri']);
    $jenc = json_encode($_POST);
    //create option MicrosoftOAuth
    set_option('MicrosoftOAuth', $jenc);
    echo showToast('success', $translate['changes_saved_successfully'], $translate['microsoft_oauth_changes_saved_successfully']).'<META HTTP-EQUIV="refresh" CONTENT="2; URL=appginilte_settings.php">';
}
if (isset($_POST['tauth'])) {
    $_POST['redirectUri'] = urldecode($_POST['redirectUri']);
    $jenc = json_encode($_POST);
    //create option TwitterOAuth
    set_option('TwitterOAuth', $jenc);
    echo showToast('success', $translate['changes_saved_successfully'], $translate['twitter_oauth_changes_saved_successfully']).'<META HTTP-EQUIV="refresh" CONTENT="2; URL=appginilte_settings.php">';
}
if (isset($_POST['grecap'])) {
    $jenc = json_encode($_POST);
    //create option reCaptcha_config
    set_option('reCaptcha_config', $jenc);
    echo showToast('success', $translate['changes_saved_successfully'], $translate['recaptcha_changes_saved_successfully']).'<META HTTP-EQUIV="refresh" CONTENT="2; URL=appginilte_settings.php">';
}
if (isset($_POST['lotp'])) {
    # code...
    $getgroups = sql("SELECT name FROM `membership_groups` WHERE name !='anonymous'", $eo);
    //loop throgh the groups and get the names
    $tjson = get_option('totp_config');
    $skipped_groups = array();
    foreach ($getgroups as $key => $value) {
        # code...
        $groupname = $value['name'];
        $concatGroupName = str_replace(' ', '_', $groupname);
        if ($_POST[$concatGroupName . '_enablelotp'] == 1 && $tjson[$concatGroupName . '_enabletotp'] == 1) {
            //unset POST data
            unset($_POST[$concatGroupName . '_enablelotp']);
            $skipped_groups[] = $groupname;
        }
    }

    $jenc = json_encode($_POST);
    //create option lotp_config
    if ($smtp_enabled == 'No') {
        echo showToast('danger', $translate['changes_not_saved'], $translate['please_enable_smtp_first']);
    } else {
        set_option('lotp_config', $jenc);
        //if we have skipped groups, show a warning
        if (count($skipped_groups) > 0) {
            echo showToast('warning', $translate['changes_saved_successfully'], '' . $translate['login_otp_was_not_enabled_for_the_following_groups'] . ' ' . implode(', ', $skipped_groups) . '. ' . $translate['Please disable TOTP  for the groups first.'] . '');
        } else {
            echo showToast('success', $translate['changes_saved_successfully'], $translate['login_otp_changes_saved_successfully']).'<META HTTP-EQUIV="refresh" CONTENT="2; URL=appginilte_settings.php">';
        }
    }
}
if (isset($_POST['totp'])) {
    $getgroups = sql("SELECT name FROM `membership_groups` WHERE name !='anonymous'", $eo);
    //loop throgh the groups and get the names
    $tjson = get_option('lotp_config');
    $skipped_groups = array();
    foreach ($getgroups as $key => $value) {
        # code...
        $groupname = $value['name'];
        $concatGroupName = str_replace(' ', '_', $groupname);
        if ($_POST[$concatGroupName . '_enabletotp'] == 1 && $tjson[$concatGroupName . '_enablelotp'] == 1) {
            //unset POST data
            unset($_POST[$concatGroupName . '_enabletotp']);
            $skipped_groups[] = $groupname;
        }
    }
    $jenc = json_encode($_POST);
    if (count($skipped_groups) > 0) {
        echo showToast('warning', $translate['changes_saved_successfully'], '' . $translate['Time-based OTP was not enabled for the following groups:'] . ' ' . implode(', ', $skipped_groups) . '. ' . $translate['Please disable Login OTP for the groups first.'] . '');
    } else {
        //create option totp_config
        set_option('totp_config', $jenc);
        echo showToast('success', $translate['changes_saved_successfully'], $translate['time_based_otp_changes_saved_successfully']).'<META HTTP-EQUIV="refresh" CONTENT="2; URL=appginilte_settings.php">';
    }
}
if (isset($_POST['accessperms'])) {
    # code...
    $jenc = json_encode($_POST);
    //create accessperms_config 
    set_option('accessperms_config', $jenc);
    echo showToast('success', $translate['changes_saved_successfully'], $translate['access_perms_changes_saved_successfully']).'<META HTTP-EQUIV="refresh" CONTENT="2; URL=appginilte_settings.php">';
}

if (isset($_POST['generalsetts'])) {
    $target_dir = "appginilte/images/";
    $target_file = $target_dir . basename($_FILES["appIcon"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if (!empty($_FILES['appIcon']['name'])) {
        $check = getimagesize($_FILES["appIcon"]["tmp_name"]);
        if ($check !== false) {
            $msg = "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $msg = $translate['file_is_not_an_image'];
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["appIcon"]["size"] > 500000) {
            $msg = $translate['file_too_large'];
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            $msg = $translate['Sorry, only JPG, JPEG, PNG & GIF files are allowed.'];
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $msg = $translate['sorry_there_was_an_error_uploading_your_file'];
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["appIcon"]["tmp_name"], $target_file)) {
                $okay = "The file " . htmlspecialchars(basename($_FILES["appIcon"]["name"])) . " has been uploaded.";
            } else {
                $msg = $translate['sorry_there_was_an_error_uploading_your_file'];
            }
        }
        $_POST['appIcon'] = basename($_FILES["appIcon"]["name"]);
    } else {
        $_POST['appIcon'] = $appIcon;
    }
    //Upload Image
    if ($uploadOk === 0) {
        echo showToast('danger', $translate['changes_not_saved'], $msg);
    } else {
        $enablethemeswitcherAppgini = $_POST['enablethemeswitcherAppgini'];
        //if enablethemeswitcherAppgini equal to 1, then insert this code in hooks/footer-extras.php <?php include_once 'appginilte/appgini-theme-switcher.php'; if not inserted already
        $footer_extras = file_get_contents('hooks/footer-extras.php');
        if ($enablethemeswitcherAppgini == 1) {
            if (strpos($footer_extras, 'appgini-theme-switcher.php') === false) {
                $footer_extras .= "\n" . '<!-- code inserted by Appginilte --> <?php include_once \'appginilte/appgini-theme-switcher.php\'; ?> <!-- End of code inserted by Appginilte -->';
                file_put_contents('hooks/footer-extras.php', $footer_extras);
            }
        }
        $jenc = json_encode($_POST,JSON_UNESCAPED_UNICODE);
        //create option generalsettings_config
        set_option('generalsettings_config', $jenc);
        echo showToast('success', $translate['changes_saved_successfully'], $translate['general_settings_changes_saved_successfully']).'<META HTTP-EQUIV="refresh" CONTENT="2; URL=appginilte_settings.php">';
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'removelte') {
        $index_file = "index.php";
        //read the entire file
        $getfile = file_get_contents($index_file);

        //replace appginilte_dashboard.php in the file with home.php
        $putfile = str_replace('appginilte_dashboard.php', 'home.php', $getfile);
        //replace appginilte_login.php in the file with login.php
        $putfile = str_replace('appginilte_login.php', 'login.php', $putfile);

        //write the entire file
        if (!@file_put_contents($index_file, $putfile)) {
            echo showToast('danger', $translate['error'], $translate['could_not_write_to_file']);
        } else {
            echo showToast('success', $translate['success'], $translate['AppginiLTE successfully removed']) . '<META HTTP-EQUIV="refresh" CONTENT="2; URL=index.php">';
        }
    }
}

if (isset($_POST['disabletotp'])) {
    $memberID = $_POST['memberID'];
    $usercrypt = $str = crc32($memberID);
    //if file exists $usercrypt.json, then delete it
    if (file_exists("appginilte/2fa/$usercrypt.json")) {
        unlink("appginilte/2fa/$usercrypt.json");
        echo showToast('success', $translate['succes'], '' . $translate['Time-based OTP disabled successfully for'] . ' ' . $memberID . '').'<META HTTP-EQUIV="refresh" CONTENT="2; URL=appginilte_settings.php">';
    } else {
        echo showToast('danger', $translate['error'], '' . $translate['Error disabling Time-based OTP for'] . ' ' . $memberID . '');
    }
}
?>
<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
<?php include 'appginilte_footer.php'; ?>