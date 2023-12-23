<!-- DataTables  & Plugins -->
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
 <!-- Font Awesome -->
 <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
<?php
define('PREPEND_PATH', '../');
$hooks_dir = dirname(__FILE__);
include("{$hooks_dir}/../defaultLang.php");
include("{$hooks_dir}/../language.php");
include("{$hooks_dir}/../lib.php");
$translate = array();
include ('../appginilte_language.php');
require ('../appginilte_defaultLang.php');
//array merge translateEN and translate
$translate=array_merge($translateEN, $translate);
$acesspermsData = json_decode(@file_get_contents('accessperms_config.json'), true);
$globalsearchgroups = $acesspermsData['globalsearchgroups'] ? $acesspermsData['globalsearchgroups'] : '';
$globalsearch = $acesspermsData['globalsearch'] ? $acesspermsData['globalsearch'] : 0;

/* check access: modify this part according to who you want to allow to access this page */
$mi = getMemberInfo();
if ($globalsearch =="0" || !in_array($mi['group'], $globalsearchgroups)) {
	echo '<br><div class="alert alert-danger">'.$translate['You are not allowed to access this page, please contact your administartor for help.'].'</div>';
		exit;
}

$search = $_REQUEST['search'];
echo show_search_form($search);
$results = process_search($search);

/*
	  Before rendering search results, you can optionally modify the $results array here to manipulate the way
	  results are displayed ...
	  The structure of the results array is $results[$tablename][$i]['record'][$fieldname]
	  You could explore it by using:
	  echo '<pre>';
	  print_r($results);
	  echo '</pre>';
	  
	  Example: to trim the Notes field of the employees table to the first 20 characters only:
	  trim_results($results, 'employees', 'Notes', 20);
	  
	  You could repeat the above line many times for trimming other fields.
	*/

echo (empty(render_search_results($results, $search)) ? '<br><div class="alert alert-danger">No results found</div>' : render_search_results($results, $search));

include_once("{$hooks_dir}/../footer.php");

/* use this function to trim the displayed length of a specific field in a specific table to given length */
function trim_results(&$results, $table, $field, $length)
{
	for ($i = 0; $i < count($results[$table]); $i++) {
		$ellipsis = '';
		$str = &$results[$table][$i]['record'][$field];
		if (strlen($str) > $length) $ellipsis = ' ...';

		$str = substr($str, 0, $length) . $ellipsis;
	}
}

/* function to display search form */
function show_search_form($search = '')
{
	ob_start();
?>
	<div class="page-header">
		<h1>Global Search</h1>
		<p>Perform global search on all tables of your application.</p>
	</div>
	<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<div class="input-group">
	<input type="search" class="form-control form-control-lg" placeholder="Search.." id="search" name="search" value="<?php echo html_attr($search); ?>" autofocus>
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
	if (!$search) return false;

	/* get tables accessible by current user */
	$tables = getTableList();
	if (!count($tables)) return false;

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

	$html = '<h2>Showing matches from ' . count($results) . ' tables</h2>';
	foreach ($results as $tn => $tres) {
		if (!count($tres)) continue;
		$tableID = uniqid();
		ob_start();
	?>
		<hr>
		<button type="button" class="btn btn-info btn-sm vspacer-sm">
			<?php echo $tables[$tn][0]; ?>
			<span class="badge"><?php echo count($tres); ?></span>
		</button>
		<a href="<?php echo "../" . $tn . "_view.php?SearchString=$search"; ?>" class="btn btn-success btn-sm vspacer-sm">View All <span class="glyphicon glyphicon-zoom-in"></span></a><br><br>
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
						<?php $link = "../{$tn}_view.php?SelectedID=" . urlencode($rec['id']); ?>
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
	if (!count($fields) || $fields === false) return false;

	array_pop($fields); // remove last element as it's an empty string
	return $fields;
}

/* function to prepare search query */
function get_search_query($tn, $search)
{
	if (!$search) return false;
	$fields = list_of_fields($tn);
	if (!$fields) return false;

	$safe_search = makeSafe($search);
	$where = " AND CONCAT_WS('||', " . implode(', ', $fields) . ") LIKE '%{$safe_search}%'";
	$pk = "`{$tn}`.`" . getPKFieldName($tn) . "` as 'PRIMARY_KEY_VALUE'";
	$query = "SELECT {$pk}, " . get_sql_fields($tn) . " FROM " . get_sql_from($tn) . $where;

	return $query;
}
?>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>