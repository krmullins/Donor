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
                            <?php echo $translate['theme switcher']; ?>
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
                            <?php echo $translate['theme switcher']; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <!-- Create a form with theme options to Customize AppginiLTE -->
                                <form method="post" action="appginilte_switcher.php">
                                    <div class="form-group">
                                        <label for="theme">
                                            <?php echo $translate['select_theme']; ?>
                                        </label>
                                        <select class="form-control" id="app_theme" name="app_theme">
                                            <option value="light-mode" <?php echo $app_theme == "light-mode" ? "selected" : ""; ?>><?php echo $translate['light_theme']; ?></option>
                                            <option value="dark-mode" <?php echo $app_theme == "dark-mode" ? "selected" : ""; ?>><?php echo $translate['dark_theme']; ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="theme">
                                            <?php echo $translate['header_options']; ?>
                                        </label>
                                        <!-- check boxes for header options -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="fixed_header"
                                                name="fixed_header" <?php echo $fixed_header == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="fixed_header">Fixed Header</label><br>
                                            <input type="checkbox" class="form-check-input" id="dropdown_legacy_offset"
                                                name="dropdown_legacy_offset" <?php echo $dropdown_legacy_offset == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="dropdown_legacy_offset">Dropdown Legacy
                                                Offset</label><br>
                                            <input type="checkbox" class="form-check-input" id="no_border"
                                                name="no_border" <?php echo $no_border == "on" ? "selected" : ""; ?>>
                                            <label class="form-check-label" for="no_border">No border</label><br>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="theme">
                                            <?php echo $translate['sidebar_options']; ?>
                                        </label>
                                        <!-- check boxes for sidebar options -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="sidebar_collapsed"
                                                name="sidebar_collapsed" <?php echo $sidebar_collapsed == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label"
                                                for="sidebar_collapsed">Collapsed</label><br>
                                            <input type="checkbox" class="form-check-input" id="sidebar_fixed"
                                                name="sidebar_fixed" <?php echo $sidebar_fixed == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="sidebar_fixed">Fixed</label><br>
                                            <input type="checkbox" class="form-check-input" id="sidebar_mini"
                                                name="sidebar_mini" <?php echo $sidebar_mini == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="sidebar_mini">Sidebar Mini</label><br>
                                            <input type="checkbox" class="form-check-input" id="sidebar_mini_md"
                                                name="sidebar_mini_md" <?php echo $sidebar_mini_md == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="sidebar_mini_md">Sidebar Mini
                                                MD</label><br>
                                            <input type="checkbox" class="form-check-input" id="sidebar_mini_xs"
                                                name="sidebar_mini_xs" <?php echo $sidebar_mini_xs == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="sidebar_mini_xs">Sidebar Mini
                                                XS</label><br>
                                            <input type="checkbox" class="form-check-input" id="sidebar_flat_style"
                                                name="sidebar_flat_style" <?php echo $sidebar_flat_style == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="sidebar_flat_style">Nav Flat
                                                Style</label><br>
                                            <input type="checkbox" class="form-check-input" id="sidebar_legacy_style"
                                                name="sidebar_legacy_style" <?php echo $sidebar_legacy_style == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="sidebar_legacy_style">Nav Legacy
                                                Style</label><br>
                                            <input type="checkbox" class="form-check-input" id="sidebar_compact"
                                                name="sidebar_compact" <?php echo $sidebar_compact == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="sidebar_compact">Nav
                                                Compact</label><br>
                                            <input type="checkbox" class="form-check-input" id="sidebar_child_indent"
                                                name="sidebar_child_indent" <?php echo $sidebar_child_indent == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="sidebar_child_indent">Nav Child
                                                Indent</label><br>
                                            <input type="checkbox" class="form-check-input"
                                                id="sidebar_child_hide_on_collapse"
                                                name="sidebar_child_hide_on_collapse" <?php echo $sidebar_child_hide_on_colapse == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="sidebar_child_hide_on_collapse">Nav
                                                Child Hide on Collapse</label><br>
                                            <input type="checkbox" class="form-check-input" id="sidebar_disable_hover"
                                                name="sidebar_disable_hover" <?php echo $sidebar_disable_hover == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="sidebar_disable_hover">Disable
                                                Hover/Focus Auto-Expand</label><br>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="theme">
                                            <?php echo $translate['footer_options']; ?>
                                        </label>
                                        <!-- check boxes for footer options -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="footer_fixed"
                                                name="footer_fixed" <?php echo $footer_fixed == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="footer_fixed">Fixed Footer</label><br>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="theme">
                                            <?php echo $translate['small_text_options']; ?>
                                        </label>
                                        <!-- check boxes for small text options -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="text_sm_body"
                                                name="text_sm_body" <?php echo $text_sm_body == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="text_sm_body">Body</label><br>
                                            <input class="form-check-input" type="checkbox" id="text_sm_navbar"
                                                name="text_sm_navbar" <?php echo $text_sm_navbar == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="text_sm_navbar">Navbar</label><br>
                                            <input class="form-check-input" type="checkbox" id="text_sm_brand"
                                                name="text_sm_brand" <?php echo $text_sm_brand == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="text_sm_brand">Brand</label><br>
                                            <input class="form-check-input" type="checkbox" id="text_sm_sidebarnav"
                                                name="text_sm_sidebarnav" <?php echo $text_sm_sidebarnav == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="text_sm_sidebarnav">Sidebar
                                                Nav</label><br>
                                            <input class="form-check-input" type="checkbox" id="text_sm_footer"
                                                name="text_sm_footer" <?php echo $text_sm_footer == "on" ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="text_sm_footer">Footer</label><br>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="theme">
                                            <?php echo $translate['navbar_variant']; ?>
                                        </label>
                                        <!-- dropdown list of navbar colors -->
                                        <select class="custom-select mb-3 text-light border-1 bg-white"
                                            id="navbar_variant" name="navbar_variant">
                                            <option value="Primary" class="bg-primary" <?php echo $navbar_variant == "Primary" ? "selected" : ""; ?>>Primary</option>
                                            <option value="Secondary" class="bg-secondary" <?php echo $navbar_variant == "Secondary" ? "selected" : ""; ?>>Secondary</option>
                                            <option value="Info" class="bg-info" <?php echo $navbar_variant == "Info" ? "selected" : ""; ?>>Info</option>
                                            <option value="Success" class="bg-success" <?php echo $navbar_variant == "Success" ? "selected" : ""; ?>>Success</option>
                                            <option value="Danger" class="bg-danger" <?php echo $navbar_variant == "Danger" ? "selected" : ""; ?>>Danger</option>
                                            <option value="Indigo" class="bg-indigo" <?php echo $navbar_variant == "Indigo" ? "selected" : ""; ?>>Indigo</option>
                                            <option value="Purple" class="bg-purple" <?php echo $navbar_variant == "Purple" ? "selected" : ""; ?>>Purple</option>
                                            <option value="Pink" class="bg-pink" <?php echo $navbar_variant == "Pink" ? "selected" : ""; ?>>Pink</option>
                                            <option value="Navy" class="bg-navy" <?php echo $navbar_variant == "Navy" ? "selected" : ""; ?>>Navy</option>
                                            <option value="Lightblue" class="bg-lightblue" <?php echo $navbar_variant == "Lightblue" ? "selected" : ""; ?>>Lightblue</option>
                                            <option value="Teal" class="bg-teal" <?php echo $navbar_variant == "Teal" ? "selected" : ""; ?>>Teal</option>
                                            <option value="Cyan" class="bg-cyan" <?php echo $navbar_variant == "Cyan" ? "selected" : ""; ?>>Cyan</option>
                                            <option value="Dark" class="bg-dark" <?php echo $navbar_variant == "Dark" ? "selected" : ""; ?>>Dark</option>
                                            <option value="Gray dark" class="bg-gray-dark" <?php echo $navbar_variant == "Gray dark" ? "selected" : ""; ?>>Gray dark</option>
                                            <option value="Gray" class="bg-gray" <?php echo $navbar_variant == "Gray" ? "selected" : ""; ?>>Gray</option>
                                            <option value="Light" class="bg-light" <?php echo $navbar_variant == "Light" ? "selected" : ""; ?>>Light</option>
                                            <option value="Warning" class="bg-warning" <?php echo $navbar_variant == "Warning" ? "selected" : ""; ?>>Warning</option>
                                            <option value="White" class="bg-white" <?php echo $navbar_variant == "White" ? "selected" : ""; ?>>White</option>
                                            <option value="Orange" class="bg-orange" <?php echo $navbar_variant == "Orange" ? "selected" : ""; ?>>Orange</option>
                                            <option value="NONE" <?php echo $navbar_variant == "NONE" ? "selected" : ""; ?>>
                                                NONE</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="theme">
                                            <?php echo $translate['accent_color_variant']; ?>
                                        </label>
                                        <!-- dropdown list of accent colors -->
                                        <select class="custom-select mb-3 border-1" id="accent_color_variant"
                                            name="accent_color_variant">
                                            <option value="Primary" class="bg-primary" <?php echo $accent_color_variant == "Primary" ? "selected" : ""; ?>>Primary</option>
                                            <option value="Warning" class="bg-warning" <?php echo $accent_color_variant == "Warning" ? "selected" : ""; ?>>Warning</option>
                                            <option value="Info" class="bg-info" <?php echo $accent_color_variant == "Info" ? "selected" : ""; ?>>Info</option>
                                            <option value="Danger" class="bg-danger" <?php echo $accent_color_variant == "Danger" ? "selected" : ""; ?>>Danger</option>
                                            <option value="Success" class="bg-success" <?php echo $accent_color_variant == "Success" ? "selected" : ""; ?>>Success</option>
                                            <option value="Indigo" class="bg-indigo" <?php echo $accent_color_variant == "Indigo" ? "selected" : ""; ?>>Indigo</option>
                                            <option value="Lightblue" class="bg-lightblue" <?php echo $accent_color_variant == "Lightblue" ? "selected" : ""; ?>>Lightblue</option>
                                            <option value="Navy" class="bg-navy" <?php echo $accent_color_variant == "Navy" ? "selected" : ""; ?>>Navy</option>
                                            <option value="Purple" class="bg-purple" <?php echo $accent_color_variant == "Purple" ? "selected" : ""; ?>>Purple</option>
                                            <option value="Fuchsia" class="bg-fuchsia" <?php echo $accent_color_variant == "Fuchsia" ? "selected" : ""; ?>>Fuchsia</option>
                                            <option value="Pink" class="bg-pink" <?php echo $accent_color_variant == "Pink" ? "selected" : ""; ?>>Pink</option>
                                            <option value="Maroon" class="bg-maroon" <?php echo $accent_color_variant == "Maroon" ? "selected" : ""; ?>>Maroon</option>
                                            <option value="Orange" class="bg-orange" <?php echo $accent_color_variant == "Orange" ? "selected" : ""; ?>>Orange</option>
                                            <option value="Lime" class="bg-lime" <?php echo $accent_color_variant == "Lime" ? "selected" : ""; ?>>Lime</option>
                                            <option value="Teal" class="bg-teal" <?php echo $accent_color_variant == "Teal" ? "selected" : ""; ?>>Teal</option>
                                            <option value="Olive" class="bg-olive" <?php echo $accent_color_variant == "Olive" ? "selected" : ""; ?>>Olive</option>
                                            <option value="NONE" <?php echo $accent_color_variant == "NONE" ? "selected" : ""; ?>>NONE</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="theme">
                                            <?php echo $translate['dark_side_bar_variant']; ?>
                                        </label>
                                        <!-- dropdown list of dark sidebar colors -->
                                        <select class="custom-select mb-3 border-1" id="dark_sidebar_variant"
                                            name="dark_sidebar_variant">
                                            <option value="Primary" class="bg-primary" <?php echo $dark_sidebar_variant == "Primary" ? "selected" : ""; ?>>Primary</option>
                                            <option value="Warning" class="bg-warning" <?php echo $dark_sidebar_variant == "Warning" ? "selected" : ""; ?>>Warning</option>
                                            <option value="Info" class="bg-info" <?php echo $dark_sidebar_variant == "Info" ? "selected" : ""; ?>>Info</option>
                                            <option value="Danger" class="bg-danger" <?php echo $dark_sidebar_variant == "Danger" ? "selected" : ""; ?>>Danger</option>
                                            <option value="Success" class="bg-success" <?php echo $dark_sidebar_variant == "Success" ? "selected" : ""; ?>>Success</option>
                                            <option value="Indigo" class="bg-indigo" <?php echo $dark_sidebar_variant == "Indigo" ? "selected" : ""; ?>>Indigo</option>
                                            <option value="Lightblue" class="bg-lightblue" <?php echo $dark_sidebar_variant == "Lightblue" ? "selected" : ""; ?>>Lightblue</option>
                                            <option value="Navy" class="bg-navy" <?php echo $dark_sidebar_variant == "Navy" ? "selected" : ""; ?>>Navy</option>
                                            <option value="Purple" class="bg-purple" <?php echo $dark_sidebar_variant == "Purple" ? "selected" : ""; ?>>Purple</option>
                                            <option value="Fuchsia" class="bg-fuchsia" <?php echo $dark_sidebar_variant == "Fuchsia" ? "selected" : ""; ?>>Fuchsia</option>
                                            <option value="Pink" class="bg-pink" <?php echo $dark_sidebar_variant == "Pink" ? "selected" : ""; ?>>Pink</option>
                                            <option value="Maroon" class="bg-maroon" <?php echo $dark_sidebar_variant == "Maroon" ? "selected" : ""; ?>>Maroon</option>
                                            <option value="Orange" class="bg-orange" <?php echo $dark_sidebar_variant == "Orange" ? "selected" : ""; ?>>Orange</option>
                                            <option value="Lime" class="bg-lime" <?php echo $dark_sidebar_variant == "Lime" ? "selected" : ""; ?>>Lime</option>
                                            <option value="Teal" class="bg-teal" <?php echo $dark_sidebar_variant == "Teal" ? "selected" : ""; ?>>Teal</option>
                                            <option value="Olive" class="bg-olive" <?php echo $dark_sidebar_variant == "Olive" ? "selected" : ""; ?>>Olive</option>
                                            <option value="NONE" <?php echo $dark_sidebar_variant == "NONE" ? "selected" : ""; ?>>NONE</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="theme">
                                            <?php echo $translate['light_side_bar_variant']; ?>
                                        </label>
                                        <!-- dropdown list of light sidebar colors -->
                                        <select class="custom-select mb-3 text-light border-1 bg-white"
                                            id="light_sidebar_variant" name="light_sidebar_variant">
                                            <option value="Primary" class="bg-primary" <?php echo $light_sidebar_variant == "Primary" ? "selected" : ""; ?>>Primary</option>
                                            <option value="Warning" class="bg-warning" <?php echo $light_sidebar_variant == "Warning" ? "selected" : ""; ?>>Warning</option>
                                            <option value="Info" class="bg-info" <?php echo $light_sidebar_variant == "Info" ? "selected" : ""; ?>>Info</option>
                                            <option value="Danger" class="bg-danger" <?php echo $light_sidebar_variant == "Danger" ? "selected" : ""; ?>>Danger</option>
                                            <option value="Success" class="bg-success" <?php echo $light_sidebar_variant == "Success" ? "selected" : ""; ?>>Success</option>
                                            <option value="Indigo" class="bg-indigo" <?php echo $light_sidebar_variant == "Indigo" ? "selected" : ""; ?>>Indigo</option>
                                            <option value="Lightblue" class="bg-lightblue" <?php echo $light_sidebar_variant == "Lightblue" ? "selected" : ""; ?>>Lightblue</option>
                                            <option value="Navy" class="bg-navy" <?php echo $light_sidebar_variant == "Navy" ? "selected" : ""; ?>>Navy</option>
                                            <option value="Purple" class="bg-purple" <?php echo $light_sidebar_variant == "Purple" ? "selected" : ""; ?>>Purple</option>
                                            <option value="Fuchsia" class="bg-fuchsia" <?php echo $light_sidebar_variant == "Fuchsia" ? "selected" : ""; ?>>Fuchsia</option>
                                            <option value="Pink" class="bg-pink" <?php echo $light_sidebar_variant == "Pink" ? "selected" : ""; ?>>Pink</option>
                                            <option value="Maroon" class="bg-maroon" <?php echo $light_sidebar_variant == "Maroon" ? "selected" : ""; ?>>Maroon</option>
                                            <option value="Orange" class="bg-orange" <?php echo $light_sidebar_variant == "Orange" ? "selected" : ""; ?>>Orange</option>
                                            <option value="Lime" class="bg-lime" <?php echo $light_sidebar_variant == "Lime" ? "selected" : ""; ?>>Lime</option>
                                            <option value="Teal" class="bg-teal" <?php echo $light_sidebar_variant == "Teal" ? "selected" : ""; ?>>Teal</option>
                                            <option value="Olive" class="bg-olive" <?php echo $light_sidebar_variant == "Olive" ? "selected" : ""; ?>>Olive</option>
                                            <option value="NONE" <?php echo $light_sidebar_variant == "NONE" ? "selected" : ""; ?>>NONE</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="theme">
                                            <?php echo $translate['brand_logo_variant']; ?>
                                        </label>
                                        <!-- dropdown list of brand logo colors -->
                                        <select class="custom-select mb-3 border-1" id="brand_logo_variant"
                                            name="brand_logo_variant">
                                            <option value="Primary" class="bg-primary" <?php echo $brand_logo_variant == "Primary" ? "selected" : ""; ?>>Primary</option>
                                            <option value="Secondary" class="bg-secondary" <?php echo $brand_logo_variant == "Secondary" ? "selected" : ""; ?>>Secondary</option>
                                            <option value="Info" class="bg-info" <?php echo $brand_logo_variant == "Info" ? "selected" : ""; ?>>Info</option>
                                            <option value="Success" class="bg-success" <?php echo $brand_logo_variant == "Success" ? "selected" : ""; ?>>Success</option>
                                            <option value="Danger" class="bg-danger" <?php echo $brand_logo_variant == "Danger" ? "selected" : ""; ?>>Danger</option>
                                            <option value="Indigo" class="bg-indigo" <?php echo $brand_logo_variant == "Indigo" ? "selected" : ""; ?>>Indigo</option>
                                            <option value="Purple" class="bg-purple" <?php echo $brand_logo_variant == "Purple" ? "selected" : ""; ?>>Purple</option>
                                            <option value="Pink" class="bg-pink" <?php echo $brand_logo_variant == "Pink" ? "selected" : ""; ?>>Pink</option>
                                            <option value="Navy" class="bg-navy" <?php echo $brand_logo_variant == "Navy" ? "selected" : ""; ?>>Navy</option>
                                            <option value="Lightblue" class="bg-lightblue" <?php echo $brand_logo_variant == "Lightblue" ? "selected" : ""; ?>>Lightblue</option>
                                            <option value="Teal" class="bg-teal" <?php echo $brand_logo_variant == "Teal" ? "selected" : ""; ?>>Teal</option>
                                            <option value="Cyan" class="bg-cyan" <?php echo $brand_logo_variant == "Cyan" ? "selected" : ""; ?>>Cyan</option>
                                            <option value="Dark" class="bg-dark" <?php echo $brand_logo_variant == "Dark" ? "selected" : ""; ?>>Dark</option>
                                            <option value="Gray dark" class="bg-gray-dark" <?php echo $brand_logo_variant == "Gray dark" ? "selected" : ""; ?>>Gray dark</option>
                                            <option value="Gray" class="bg-gray" <?php echo $brand_logo_variant == "Gray" ? "selected" : ""; ?>>Gray</option>
                                            <option value="Light" class="bg-light" <?php echo $brand_logo_variant == "Light" ? "selected" : ""; ?>>Light</option>
                                            <option value="Warning" class="bg-warning" <?php echo $brand_logo_variant == "Warning" ? "selected" : ""; ?>>Warning</option>
                                            <option value="White" class="bg-white" <?php echo $brand_logo_variant == "White" ? "selected" : ""; ?>>White</option>
                                            <option value="Orange" class="bg-orange" <?php echo $brand_logo_variant == "Orange" ? "selected" : ""; ?>>Orange</option>
                                            <option value="NONE" <?php echo $brand_logo_variant == "NONE" ? "selected" : ""; ?>>NONE</option>
                                            <a href="#">clear</a>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="savetheme"><span
                                            class="fas fa-save"></span>
                                        <?php echo $translate['save_theme_changes']; ?>
                                    </button>
                                    <a href="?reset=1" class="btn btn-danger"><span class="far fa-trash-alt"></span>
                                        <?php echo $translate['reset_all_theme_changes']; ?>
                                    </a>
                                </form>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
if (isset($_POST['savetheme'])) {
    //json encode post
    $sitecustomizations = json_encode($_POST);
    //create option appcustomizations
   set_option('appcustomizations', $sitecustomizations);
    //show success alert
    echo showToast('success', $translate['theme_changes_saved_successfully'], $translate['theme_changes_success_description']);
    //refresh automatically after 2 seconds
    echo '<meta http-equiv="refresh" content="2;url=appginilte_switcher.php" />';
}
if (isset($_GET['reset'])) {
    //delete the appcustomizations.json file
    delete_option('appcustomizations');
    //show success alert
    echo showToast('success', $translate['theme_changes_reset_successfully'], $translate['theme_changes_reset_description']);
    //refresh automatically after 2 seconds
    echo '<meta http-equiv="refresh" content="2;url=appginilte_switcher.php" />';
}
?>
<script>
    document.title = '<?php echo $appName . '|' . $translate['theme switcher']; ?>';
    const darkSidebarVariant = document.getElementById('dark_sidebar_variant');
    const lightSidebarVariant = document.getElementById('light_sidebar_variant');

    // add event listener for changes to the darkSidebarVariant select list
    darkSidebarVariant.addEventListener('change', function () {
        // check if the selected value is not NONE
        if (this.value !== 'NONE') {
            // set the value of the lightSidebarVariant select list to NONE
            lightSidebarVariant.value = 'NONE';
        }
    });

    // add event listener for changes to the lightSidebarVariant select list
    lightSidebarVariant.addEventListener('change', function () {
        // check if the selected value is not NONE
        if (this.value !== 'NONE') {
            // set the value of the darkSidebarVariant select list to NONE
            darkSidebarVariant.value = 'NONE';
        }
    });

</script>
<?php include 'appginilte_footer.php'; ?>