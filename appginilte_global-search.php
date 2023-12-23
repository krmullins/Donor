<?php
/* Assuming this custom file is placed inside 'hooks' */
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
                        <?php echo $translate['global_search']; ?>
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
                            <i class="fas fa-search"></i>
                            <?php echo $translate['global_search']; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($globalsearch =="0" || !in_array($group, $globalsearchgroups)) {
                            echo '<br><div class="alert alert-danger">'.$translate['You are not allowed to access this page, please contact your administartor for help.'].'</div>';
                            exit;
                        }

                        $search = $_REQUEST['search'];
                        echo show_search_form($search);
                        $results = process_search($search);
                        echo (empty(render_search_results($results, $search)) ? '<br><div class="alert alert-danger">'.$translate['No results found'].'</div>' : render_search_results($results, $search));
                        ?>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include 'appginilte_footer.php'; ?>
<?php
/* use this function to trim the displayed length of a specific field in a specific table to given length */
function trim_results(&$results, $table, $field, $length)
{
    for ($i = 0; $i < count($results[$table]); $i++) {
        $ellipsis = '';
        $str =& $results[$table][$i]['record'][$field];
        if (strlen($str) > $length)
            $ellipsis = ' ...';

        $str = substr($str, 0, $length) . $ellipsis;
    }
}

/* function to display search form */
function show_search_form($search = '')
{
    ob_start();
    global $translate;
    ?>
   <div class="page-header">
		<p><?php echo $translate['Perform global search on all tables of your application.']; ?></p>
	</div>
	<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<div class="input-group">
	<input type="search" class="form-control form-control-lg" placeholder="<?php echo $translate['Search']; ?>.." id="search" name="search" value="<?php echo html_attr($search); ?>" autofocus>
	<div class="input-group-append">
	<button type="submit" class="btn btn-lg btn-default">
	<i class="fa fa-search"></i>
	</button>
	</div>
	</div>
	</form>
    <?php
    return ob_get_clean();
}

/* function to process search */
function process_search($search = '')
{
    if (!$search)
        return false;

    /* get tables accessible by current user */
    $tables = getTableList();
    if (!count($tables))
        return false;

    /* perform search */
    $results = array();
    foreach ($tables as $tn => $tdata) {
        $res = sql(get_search_query($tn, $search), $eo);
        while ($row = db_fetch_assoc($res)) {
            $results[$tn][] = array(
                'id' => $row['PRIMARY_KEY_VALUE'],
                'record' => array_slice($row, 1, NULL, true)
            );
        }
    }

    return $results;
}

/* function to render search results */
function render_search_results($results, $search = '')
{
    if (!is_array($results)) return '';
	if (!count($results) || !$results) return '';

	$tables = getTableList();
    global $translate;

	$html = '<h4>'.$translate['Showing matches from'].' '. count($results) .' '. $translate['tables'].'</h4>';
	foreach ($results as $tn => $tres) {
		if (!count($tres)) continue;
		$tableID = uniqid();
		ob_start();
	?>
		<hr>
		<button type="button" class="btn btn-primary btn-sm vspacer-sm">
			<?php echo $tables[$tn][0]; ?>
			<span class="badge"><?php echo count($tres); ?></span>
		</button>
		<a href="<?php echo "appginilte_view.php?page=" . $tn . "_view.php?SearchString=$search"; ?>" class="btn btn-success btn-sm vspacer-sm"><?php echo $translate['View All']; ?> <span class="glyphicon glyphicon-zoom-in"></span></a><br><br>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered" id="<?php echo $tableID; ?>">
				<thead>
					<tr>
						<th></th>
						<?php foreach ($tres[0]['record'] as $label => $v) { ?>
							<th><?php echo $label; ?></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($tres as $rec) { ?>
						<?php $link = "appginilte_view.php?page={$tn}_view.php?SelectedID=" . urlencode($rec['id']); ?>
						<tr>
							<td><a href="<?php echo $link; ?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a></td>
							<?php foreach ($rec['record'] as $v) { ?>
								<td><?php echo $v; ?></td>
							<?php } ?>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
			<script>
				$(function() {
					$("#<?php echo $tableID; ?>").DataTable({
						"pageLength": 50,
						"responsive": true,
						"lengthChange": false,
						"autoWidth": false,
						"buttons": ["csv", "excel", "pdf", "print", "copy"]
					}).buttons().container().appendTo("#<?php echo $tableID; ?>_wrapper .col-md-6:eq(0)");
				});
			</script>
		</div>
        <?php
        $html .= ob_get_clean();
    }

    return $html;
}

/* function to get a list of query fields of a given table */
function list_of_fields($tn)
{
    $fields = preg_split('/ as \'.*?\',? ?/', get_sql_fields($tn));
    if (!count($fields) || $fields === false)
        return false;

    array_pop($fields); // remove last element as it's an empty string
    return $fields;
}

/* function to prepare search query */
function get_search_query($tn, $search)
{
    if (!$search)
        return false;
    $fields = list_of_fields($tn);
    if (!$fields)
        return false;

    $safe_search = makeSafe($search);
    $where = " AND CONCAT_WS('||', " . implode(', ', $fields) . ") LIKE '%{$safe_search}%'";
    $pk = "`{$tn}`.`" . getPKFieldName($tn) . "` as 'PRIMARY_KEY_VALUE'";
    $query = "SELECT {$pk}, " . get_sql_fields($tn) . " FROM " . get_sql_from($tn) . $where;

    return $query;
}
?>