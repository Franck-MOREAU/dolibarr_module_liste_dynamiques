<?php
$res = @include '../../main.inc.php'; // For root directory
if (! $res)
	$res = @include '../../../main.inc.php'; // For "custom" directory
if (! $res)
	die("Include of main fails");

global $db, $user;

$table = GETPOST('table_ajax');
$alias = GETPOST('alias_ajax');

$form = new Form($db);

$champs =$db->DDLInfoTable($table);

foreach ($champs as $champ){
	$fields.= '<option value="' . $alias. '.' . $champ[0] . '">' . $alias. '.' . $champ[0] .'</option>';
}

echo $fields;