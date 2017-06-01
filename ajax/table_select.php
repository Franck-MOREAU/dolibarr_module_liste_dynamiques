<?php
$res = @include '../../main.inc.php'; // For root directory
if (! $res)
	$res = @include '../../../main.inc.php'; // For "custom" directory
if (! $res)
	die("Include of main fails");

global $db, $user;

$table = GETPOST('table_add');
$color = '#aad4ff';
$color2= '#56aaff';

$champs =$db->DDLInfoTable($table);
$return = '<div class="cal_event cal_event_busy" align="left" draggable="false" style="background:' . $color .'; background: -webkit-gradient(linear, left top, left bottom, from('.$color.'), to('.$color2.'));';
$return.=  'border-radius:6px; margin-bottom: 3px; width:200px;">';
$return.= '<a href="" onclick="javascript:visibilite(\'liste_champs_' . $table . '\'); return false;" >'. img_edit_add('+','') . '<h ><b>' . $table . ' </b></h></a>';
$return.= '<div id= "liste_champs_'. $table . '"  style="display:none;">';
foreach ($champs as $champ){
	$return.= $champ[0] . '</br>';
}
$return.= '</div>';
$return.= '</div>';

echo $return;