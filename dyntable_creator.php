<?php
/*
 * Copyright (C) 2014-2016 Florian HENRY <florian.henry@atm-consulting.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file lead/lead/list.php
 * \ingroup lead
 * \brief list of lead
 */
$res = @include '../../main.inc.php'; // For root directory
if (! $res)
	$res = @include '../../../main.inc.php'; // For "custom" directory
if (! $res)
	die("Include of main fails");

dol_include_once('/core/class/html.formother.class.php');
dol_include_once('/dyntable/class/dyntable.class.php');
require_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';

$langs->load('dyntable@dyntable');

// Security check
if (! $user->rights->dyntable->admin)
	accessforbidden();
$action = GETPOST('action');
$step = GETPOST('step');
$id = GETPOST('id');
if($step==0){
	$step = 1;
}

if($action == 'new-step1'){
	$dyntable = new Dyntable($db);
	$dyntable->title = GETPOST('title');
	$dyntable->context = GETPOST('context');
	$dyntable->search_button = GETPOST('filter_button');
	$dyntable->remove_filter_button = $dyntable->search_button;
	$dyntable->export_button = GETPOST('export_button');
	$dyntable->export_name = GETPOST('export_name');
	$dyntable->select_fields_button = GETPOST('select_field_button');
	$dyntable->limite = GETPOST('limit');
	$dyntable->mode = 'sql_methode';
	$dyntable->filter_mode = 'AND ';
	$dyntable->active = 0;
	$id = $dyntable->create($user);
	if($id>0){
		$step=2;
	}else{
		setEventMessages(null, $dyntable->errors, 'errors');
		$step = 1;
	}
}

if($action == 'add-from'){
	$from = new dyntable_from($db);
	$from->fk_dyntable = $id;
	$from->order = GETPOST('order');
	$from->from = GETPOST('jonction');
	$from->table = GETPOST('table');
	$from->as = GETPOST('alias');
	$from->field1 = GETPOST('field1');
	$from->field2 = GETPOST('field2');
	$res = $from->create($user);
	echo $res;
}

if($step>1){
	$object = new Dyntable($db);
	$object->fetch($id);
}

$title = $langs->trans("createlisttitle");


$form = new Form($db);
llxHeader('', $title);
dol_fiche_head();
print_fiche_titre($title.' - Step 1', '', dol_buildpath('/dyntable/img/object_list.png', 1), 1);

if($step == 1){
	print '<form name="addlead" action="' . $_SERVER["PHP_SELF"] . '" method="POST">';
	print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
	print '<input type="hidden" name="action" value="new-step1">';
	print '<input type="hidden" name="step" value="1">';

	print '<table class="border" width="100%">';
	print '<tr>';
	print '<td class="fieldrequired"  width="50%" colspan="2">';
	print $langs->trans('title') .': ';
	print '<input type="text" name="title" size="50" value=""/>';
	print '</td>';

	print '<td class="fieldrequired"  width="50%" colspan="2">';
	print $langs->trans('context') .': ';
	print '<input type="text" name="context" size="15" value=""/>';
	print '</td>';
	print '</tr>';

	print '<tr>';
	print '<td class="fieldrequired"  width="20%">';
	print $langs->trans('Filter_button') .': ';
	print $form->selectyesno("filter_button",'1',1);
	print '</td>';

	print '<td class="fieldrequired"  width="30%">';
	print $langs->trans('export_button') .': ';
	print $form->selectyesno("export_button",'1',1);
	print '<div id ="export_name_div" style="display:inline">';
	print ' ' . $langs->trans('export_name') .': ';
	print '<input type="text" name="export_name" id="export_name" size="15" value=""/>';
	print '</div>';
	print '</td>';

	print '<td class="fieldrequired"  width="25%">';
	print $langs->trans('select_field_button') .': ';
	print $form->selectyesno("select_field_button",'1',1);
	print '</td>';

	print '<td class="fieldrequired"  width="25%">';
	print $langs->trans('limit') .': ';
	print '<input type="text" name="limit" size="3" value="' . $conf->liste_limit . '"/>';
	print '</td>';
	print '</tr>';
	print '</table>';

	print '<div class="center">';
	print '<input type="submit" class="button" value="' . $langs->trans("Create") . '">';
	print '&nbsp;<input type="button" class="button" value="' . $langs->trans("Cancel") . '" onClick="javascript:history.go(-1)">';
	print '</div>';

	print '</form>';
	?>
	<script type="text/javascript" language="javascript">
	document.getElementById("export_button").onchange = function(){
		if (document.getElementById("export_button").value ==1){
			document.getElementById("export_name_div").style.display = "inline"
		} else {
			document.getElementById("export_name_div").style.display = "none"
		}
	}
	</script>
	<?php
}elseif($step == 2){
	print '<table class="border" width="100%">';
	print '<tr>';
	print '<td class="fieldrequired"  width="50%" colspan="2">';
	print $langs->trans('title') .': ';
	print $object->title;
	print '</td>';

	print '<td class="fieldrequired"  width="50%" colspan="2">';
	print $langs->trans('context') .': ';
	print $object->context;
	print '</td>';
	print '</tr>';

	print '<tr>';
	print '<td class="fieldrequired"  width="20%">';
	print $langs->trans('Filter_button') .': ';
	print yn($object->search_button);
	print '</td>';

	print '<td class="fieldrequired"  width="30%">';
	print $langs->trans('export_button') .': ';
	print yn($object->export_button);
	if($object->export_button == 1){
		print ' ' . $langs->trans('export_name') .': ';
		print $object->export_name;
	}
	print '</td>';

	print '<td class="fieldrequired"  width="25%">';
	print $langs->trans('select_field_button') .': ';
	print yn($object->select_fields_button);
	print '</td>';

	print '<td class="fieldrequired"  width="25%">';
	print $langs->trans('limit') .': ';
	print $object->limit;
	print '</td>';
	print '</tr>';
	print '</table>';

	dol_fiche_end();

	print_fiche_titre($title.' - Step 2', '', dol_buildpath('/dyntable/img/object_list.png', 1), 1);

	$from = new dyntable_from($db);
	$from->fetchAll('ASC','order',0,0,array('fk_dyntable'=>$id),'AND');

	print '<table class="border" width="100%">';
	print '<tr>';
	print '<td> ordre </td>';
	print '<td> type de jonction </td>';
	print '<td> nom de la table </td>';
	print '<td> alias </td>';
	print '<td> champs de jonction 1 </td>';
	print '<td> champs de jonction 2 </td>';
	print '<td></td>';
	print '</tr>';

	foreach ($from->lines as $line){
		print '<tr>';
		print '<td>'. $line->order . '</td>';
		print '<td>' . $line->from . '</td>';
		print '<td>' . $line->table . '</td>';
		print '<td>' . $line->as . '</td>';
		print '<td>' . $line->field1 . '</td>';
		print '<td>' . $line->field2 . '</td>';
		print '<td></td>';
		print '</tr>';
	}
	print '<tr>';
	print '<form name="addlead" action="' . $_SERVER["PHP_SELF"] . '" method="POST">';
	print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
	print '<input type="hidden" name="action" value="add-from">';
	print '<input type="hidden" name="step" value="2">';
	print '<input type="hidden" name="id" value="'. $id .'">';

	print '<td class="fieldrequired">';
	print '<input type="text" name="order" size="3" value=""/>';
	print '</td>';

	print '<td class="fieldrequired">';
	$join = array('FROM','INNER JOIN', 'LEFT JOIN', 'JOIN', 'UNION');
	print $form->selectarray('jonction', $join,'jonction',1,0,1,'',0,0,0,'','',0);
	print '</td>';

	print '<td class="fieldrequired">';
	$tables = $db->DDLListTables($db->database_name,MAIN_DB_PREFIX.'%');
	print $form->selectarray('table', $tables,'table',1,0,1,'',0,0,0,'','',0);
	print '</td>';

	print '<td class="fieldrequired">';
	print '<input type="text" name="alias" size="15" value=""/>';
	print '</td>';

	print '<td class="fieldrequired">';
	$fields = array();
	foreach ($from->lines as $line){
		$tableinfo=$db->DDLInfoTable($line->table);
		foreach ($tableinfo as $field){
			$fields[] = $line->as . '.' . $field[0];
		}
	}
	print $form->selectarray('field1', $fields,'field1',1,0,1,'',0,0,0,'','',1);
	print '</td>';

	print '<td class="fieldrequired">';
	print '<input type="submit" class="button" value="' . $langs->trans("Create") . '">';
	print '</td>';

	print '</form>';

	?>
	<script type="text/javascript" language="javascript">

 	document.getElementById("table").onchange = function(){
 		alert( "erreur: ");
 		if (document.getElementById("table").value == "-1"){
 			document.getElementById("add_button").style.display = "none"
 		} else {
 			document.getElementById("add_button").style.display = ""
 		}
 	}

	jQuery(document).ready(function () {
		var from_query = document.getElementById("query_from").value
		if (undefined == from_query){
			document.getElementById("jonction_div").style.display = "none"
		} else {
			document.getElementById("jonction_div").style.display = ""
		}
	})

	</script>
	<?php


}


?>
<script type="text/javascript" language="javascript">


</script>
<?php

dol_fiche_end();
llxFooter();
$db->close();
