<?php
include 'tables_config_lte.php';
include 'groups_config_lte.php';

$groups = get_table_groups();
$i=0;
$ngtlink = '';
foreach ($groups as $grp => $tables) {
    # code...
    $tlink = '';
    $gn = str_replace(" ", "_", $grp);
    $group_hpd=$cjson[$gn . '_hpd'] ? $cjson[$gn . '_hpd'] : 'default';
    if ($grp !== "None" && $group_hpd !== "notgrouped") {
        // code...
        $i++;
        $collapse=($i==1)?'':'collapsed-card';
        $group_cc=$cjson[$gn . '_cc'] ? $cjson[$gn . '_cc'] : 'primary';
        $linkstop = ($group_hpd=="default") ? '<h5 class="mb-2 mt-4">' . $grp . '</h5>
            <div class="row">' : '<div class="col-md-12">
        <div class="card card-'.$group_cc.' '.$collapse.'">
          <div class="card-header" data-card-widget="collapse">
            <h3 class="card-title">'.$grp.'</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="maximize">
                <i class="fas fa-expand"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body"><div class="row">';
        foreach ($tables as $tn) {
            $json = json_encode(get_tables_info(true));
            $decode = json_decode($json);
            $table_title = $decode->$tn->Caption;
            $tableIcon = $decode->$tn->tableIcon;
            $sql_from = get_sql_from($tn, false, true);
            $count_records = ($sql_from ? sqlValue("select count(1) from " . $sql_from) : 0);
            $card_color = $djson[$tn . '_color'] ? $djson[$tn . '_color'] : 'primary';
            $card_icon = $djson[$tn . '_icon'] ? $djson[$tn . '_icon'] : 'default';
            $card_fa = $djson[$tn . '_fa'] ? $djson[$tn . '_fa'] : 'fa fa-trophy';
            $card_hidden = $djson[$tn . '_hidden_hp'] ? $djson[$tn . '_hidden_hp'] : '';
            $card_style=$djson[$tn . '_card_style'] ? $djson[$tn . '_card_style'] : 'homeLinkInfoBox_1';
            $card_size=$djson[$tn.'_card_size']?$djson[$tn . '_card_size'] : 'col-md-3 col-sm-6 col-12';
            /* hide current card in homepage? */
            if (strpos($card_hidden, $group) === false && strpos($card_hidden, '*') === false) {
                $tlink .= $card_style($table_title,$count_records,'appginilte_view.php?page=' . $tn . '_view.php?Embedded=true',$card_fa,$card_color,$card_size,$card_icon,$tableIcon);
            }
        }
        if($group_hpd=="default"){
            $linksbottom = '</div>';
        }
        else{
        $linksbottom = '</div></div></div></div>';
        }
        echo empty($tlink)?'': $linkstop . $tlink . $linksbottom;
    } else {
        foreach ($tables as $tn) {
            $json = json_encode(get_tables_info(true));
            $decode = json_decode($json);
            $table_title = $decode->$tn->Caption;
            $tableIcon = $decode->$tn->tableIcon;
            $sql_from = get_sql_from($tn, false, true);
            $count_records = ($sql_from ? sqlValue("select count(1) from " . $sql_from) : 0);
            $card_color = $djson[$tn . '_color'] ? $djson[$tn . '_color'] : 'primary';
            $card_icon = $djson[$tn . '_icon'] ? $djson[$tn . '_icon'] : 'default';
            $card_fa = $djson[$tn . '_fa'] ? $djson[$tn . '_fa'] : 'fa fa-trophy';
            $card_hidden = $djson[$tn . '_hidden_hp'] ? $djson[$tn . '_hidden_hp'] : '';
            $card_style=$djson[$tn . '_card_style'] ? $djson[$tn . '_card_style'] : 'homeLinkInfoBox_1';
            $show_icon = ($card_icon == 'default') ? '<i> <img src="' . $tableIcon . '"></i>' : '<i class="' . $card_fa . '"></i>';
            $card_size=$djson[$tn.'_card_size']?$djson[$tn . '_card_size'] : 'col-md-3 col-sm-6 col-12';
            /* hide current card in homepage? */
            if (strpos($card_hidden, $group) === false && strpos($card_hidden, '*') === false) {
                $ngtlink .= $card_style($table_title,$count_records,'appginilte_view.php?page=' . $tn . '_view.php?Embedded=true',$card_fa,$card_color, $card_size,$card_icon,$tableIcon);
            }
        }
    }
}
//Dispaly non grouped table cards
if (!empty($ngtlink)) {
    echo '<div class="container-fluid"><div class="row">' . $ngtlink . '</div></div>';
  }