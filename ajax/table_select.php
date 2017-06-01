<?php
$res = @include '../../main.inc.php'; // For root directory
if (! $res)
	$res = @include '../../../main.inc.php'; // For "custom" directory
if (! $res)
	die("Include of main fails");

global $db, $user;

$table = GETPOST('table_add');
$color = '#56ff56';
$color2= '#00ff00';


$return = '<div class="cal_event cal_event_busy" align="left" draggable="false" style="background:' . $color .'; background: -webkit-gradient(linear, left top, left bottom, from('.$color.'), to('.$color2.'));';
$return.=  'border-radius:6px; margin-bottom: 3px; width:200px;">';
$return.= $table;
$return.= '</div>';

echo $return;