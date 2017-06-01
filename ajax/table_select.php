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

$db->DDLInfoTable($table);

$return = '<div class="cal_event cal_event_busy" align="left" draggable="false" style="background:' . $color .'; background: -webkit-gradient(linear, left top, left bottom, from('.$color.'), to('.$color2.'));';
$return.=  'border-radius:6px; margin-bottom: 3px; width:200px;">';
$return.= '<a href="" onclick="javascript:visibilite(\'' . $table . '\'); return false;" >'. img_edit_add('+','') . '<h style="font-size: large; color: white;"><b>' . $table . ' </b></h></a>';
$return.= '  '. $table;
$return.= '</div>';

echo $return;