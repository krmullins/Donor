<?php
	/*
	* You can add custom links to the navigation menu by appending them here ...
	* The format for each link is:
		$navLinks[] = [
			'url' => 'path/to/link', 
			'title' => 'Link title', 
			'groups' => ['group1', 'group2'], // groups allowed to see this link, use '*' if you want to show the link to all groups
			'icon' => 'path/to/icon',
			'table_group' => 0, // optional index of table group, default is 0
		];
	*/


	/* summary_reports links */
	$navLinks[] = [
		'url' => 'hooks/summary-reports-list.php',
		'title' => 'Summary Reports',
		'groups' => ['*'],
		'icon' => 'hooks/summary_reports-logo-md.png',
	];


	/*AdminLTE_For_Appgini_reports_links*/
	if(file_exists("appginilte/appginilte_reports/access_to_reports_menu.json")){
		$reportsgetAccess = file_get_contents("appginilte/appginilte_reports/access_to_reports_menu.json");
		$reportsaccess = json_decode( $reportsgetAccess, true );
		$reportsgroupAccess = !empty($reportsaccess['access_to_reports_menu'])?$reportsaccess['access_to_reports_menu']:[];
		if(!empty($reportsgroupAccess)){
			$navLinks[] = [
				'url'=>'appginilte_reportslist.php',
				'title'=>'Reports',
				'groups'=>str_replace("'", "", explode(",", $reportsgroupAccess)),
				'icon'=>'fas fa-chart-line',
				'icon_type'=>'fa',
				'table_group'=>'None',
			];
		}
	}
	/*End_of_AdminLTE_For_Appgini_reports_links*/

	//AppginiLTECustomPagesNavLinks_23.05.26
	$generalsettingsData=json_decode(sqlValue("SELECT option_value FROM alte_options WHERE option_name='generalsettings_config'"), true);
	$enablecustompages=$generalsettingsData['enablecustompages']?$generalsettingsData['enablecustompages']:0;
	$files = scandir('appginilte/custompages/pagesdata');
	foreach ($files as $file) {
		if ($file != '.' && $file != '..' && $file != "index.html" && $file != " ") {
			$file_contents = file_get_contents('appginilte/custompages/pagesdata/' . $file);
			$file_contents = json_decode($file_contents, true);
			//get comma separated values of pageAccess and convert to array
			$pageAccess = explode(',', $file_contents['pageAccess']);
			if($enablecustompages==1){
				$navLinks[] = [
					'url'=>'appginilte_page.php?page='.$file_contents['FileName'],
					'title'=>$file_contents['pageName'],
					'groups'=>$pageAccess,
					'icon'=>$file_contents['pageIcon'],
					'icon_type'=>'fa',
					'table_group'=>$file_contents['pageGroup'],
					'CustomLink'=>$file_contents['CustomLink'],
					'hidenavmenu'=>$file_contents['hidenavmenu'],
				];
			}
		}
	}
	//EndAppginiLTECustomPagesNavLinks_23.05.26