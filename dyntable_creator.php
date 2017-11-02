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
	$field1 = GETPOST('field1');
	$field2 = GETPOST('field2');
	if($field1==-1){
		$field1 = '';
	}
	if($field2==-1){
		$field2= '';
	}

	$from->fk_dyntable = $id;
	$from->order = GETPOST('order');
	$from->from = GETPOST('jonction');
	$from->table = GETPOST('table');
	$from->as = GETPOST('alias');
	$from->field1 = $field1;
	$from->field2 = $field2;
	$res = $from->create($user);
}

if($action == 'del_from'){
	$from_id = GETPOST('element');
	$from = new dyntable_from($db);
	$from->fetch($from_id);
	$from->delete($user);
}

if($action == 'edit_from'){
	$edit_id = GETPOST('element');
	$from = new dyntable_from($db);
	$from->fetch($edit_id);
	$edit_order = $from->order;
	$edit_from = $from->from;
	$edit_table = $from->table;
	$edit_alias = $from->as;
	$edit_field1 = $from->field1;
	$edit_field2 = $from->field2;
}

if($action == 'edit-from'){
	$from = new dyntable_from($db);
	$edit_id = GETPOST('element');
	$from->fetch($edit_id);
	$field1 = GETPOST('field1');
	$field2 = GETPOST('field2');
	if($field1==-1){
		$field1 = '';
	}
	if($field2==-1){
		$field2= '';
	}

	$from->fk_dyntable = $id;
	$from->order = GETPOST('order');
	$from->from = GETPOST('jonction');
	$from->table = GETPOST('table');
	$from->as = GETPOST('alias');
	$from->field1 = $field1;
	$from->field2 = $field2;
	$from->update($user);
}

if($action == 'add-where'){
	$where = new dyntable_where($db);
	$where->fk_dyntable = $id;
	$where->ordre = GETPOST('order');
	$where->where = GETPOST('jonction');
	$where->field = GETPOST('field');
	$where->operateur = GETPOST('operateur');
	$where->val1 = GETPOST('val1');
	$where->val2 = GETPOST('val2');
	$where->create($user);
}

if($action == 'del_where'){
	$where_id = GETPOST('element');
	$where = new dyntable_where($db);
	$where->fetch($where_id);
	$where->delete($user);
}

if($action == 'edit_where'){
	$where_id = GETPOST('element');
	$where = new dyntable_where($db);
	$where->fetch($where_id);
	$edit_ordre = $where->ordre;
	$edit_where = $where->where;
	$edit_field = $where->field;
	$edit_operateur = $where->operateur;
	$edit_val1 = $where->val1;
	$edit_val2 = $where->val2;
}

if($action == 'edit-where'){
	$where = new dyntable_where($db);
	$edit_id = GETPOST('element');
	$where->fetch($edit_id);

	$where->fk_dyntable = $id;
	$where->ordre = GETPOST('order');
	$where->where = GETPOST('jonction');
	$where->field = GETPOST('field');
	$where->operateur = GETPOST('operateur');
	$where->val1 = GETPOST('val1');
	$where->val2 = GETPOST('val2');
	$where->update($user);
}


if($step>1){
	$object = new Dyntable($db);
	$object->fetch($id);
}

$title = $langs->trans("createlisttitle");


$form = new Form($db);
llxHeader('', $title);

if($step == 1){
	dol_fiche_head();
	print_fiche_titre($title.' - Step 1', '', dol_buildpath('/dyntable/img/object_list.png', 1), 1);

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

	dol_fiche_end();
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
}elseif($step>1){
	dol_fiche_head();
	print_fiche_titre($title.' - Step 1', '', dol_buildpath('/dyntable/img/object_list.png', 1), 1);

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
}
if($step == 2){
	dol_fiche_head();
	print_fiche_titre($title.' - Step 2', '', dol_buildpath('/dyntable/img/object_list.png', 1), 1);

	$from = new dyntable_from($db);
	$from->fetchAll('ASC','ordre',0,0,array('fk_dyntable'=>$id),'AND');

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
		print '<td>';
		print '<a href="' . $_SERVER['PHP_SELF'] . '?id='.$id.'&action=del_from&element='. $line->id .'&step=2">' . img_delete() . '</a>';
		print '<a href="' . $_SERVER['PHP_SELF'] . '?id='.$id.'&action=edit_from&element='. $line->id .'&step=2">' . img_edit(). '</a>';
		print '</td>';
		print '</tr>';
	}
	print '<tr>';
	print '<form name="addlead" action="' . $_SERVER["PHP_SELF"] . '" method="POST">';
	print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
	if($action == 'edit_from'){
		print '<input type="hidden" name="action" value="edit-from">';
		print '<input type="hidden" name="element" value="' . GETPOST('element') . '">';
	}else{
		print '<input type="hidden" name="action" value="add-from">';
	}
	print '<input type="hidden" name="step" value="2">';
	print '<input type="hidden" name="id" value="'. $id .'">';

	print '<td class="fieldrequired">';
	print '<input type="text" name="order" size="3" value="' . $edit_order . '"/>';
	print '</td>';

	print '<td class="fieldrequired">';
	$join = array('FROM','INNER JOIN', 'LEFT JOIN', 'JOIN', 'UNION');
	print $form->selectarray('jonction', $join,$edit_from,1,0,1,'',0,0,0,'','',0);
	print '</td>';

	print '<td class="fieldrequired">';
	$tables = $db->DDLListTables($db->database_name,MAIN_DB_PREFIX.'%');
	print $form->selectarray('table', $tables,$edit_table,1,0,1,'',0,0,0,'','',1);
	print '</td>';

	print '<td class="fieldrequired">';
	print '<input type="text" name="alias" id="alias" size="15" value="' . $edit_alias . '"/>';
	print '</td>';

	print '<td class="fieldrequired">';
	$fields = array();
	foreach ($from->lines as $line){
		$tableinfo=$db->DDLInfoTable($line->table);
		foreach ($tableinfo as $field){
			$fields[] = $line->as . '.' . $field[0];
		}
	}
	print $form->selectarray('field1', $fields,$edit_field1,1,0,1,'',0,0,0,'','',1);
	print '</td>';

	print '<td class="fieldrequired">';
	print '<select id="field2" class="flat field2" name="field2">';
	print '<option class="optiongrey" value="-1">&nbsp;</option>';
	if($action == 'edit_from'){
		$champs =$db->DDLInfoTable($edit_table);
		foreach ($champs as $champ){
			$name = $edit_alias. '.' . $champ[0];
			print '<option value="' . $name . '"' . ($name==$edit_field2?' SELECTED':'') . '>' . $name .'</option>';
		}

	}

	print '</select>';
	print '</td>';


	print '<td class="fieldrequired">';
	print '<input type="submit" class="button" value="' . $langs->trans("Create") . '">';
	print '</td>';

	print '</form>';
	print '</tr>';
	print '</table>';

	print '</br>';

	print '<div class="inline-block divButAction"><a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $id . '&step=3">' . $langs->trans('nextstep') . '</a></div>';
	print '<div class="inline-block divButAction"><a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $id . '&step=1">' . $langs->trans('prevstep') . '</a></div>';

	dol_fiche_end();
	?>
	<script type="text/javascript" language="javascript">
	document.getElementById("alias").onchange = function(){
		$.ajax({
	 		method: "POST",
	 		url: "ajax/table_select.php",
	 		data: {
	 			table_ajax: document.getElementById("table").value,
	 			alias_ajax: document.getElementById("alias").value,
	 		},
	 		success: function(msg){
	 			if (msg != ""){
	 				document.getElementById('field2').innerHTML += msg;
	 			}
	 		},
	 		error: function(msg){
	 			alert( "erreur: " + msg );
	 		}
		})
	}
	</script>
	<?php
}elseif($step>2){
	dol_fiche_head();
	print_fiche_titre($title.' - Step 2', '', dol_buildpath('/dyntable/img/object_list.png', 1), 1);

	$from = new dyntable_from($db);
	$from->fetchAll('ASC','ordre',0,0,array('fk_dyntable'=>$id),'AND');

	print '<table class="border" width="100%">';
	print '<tr>';
	print '<td> ordre </td>';
	print '<td> type de jonction </td>';
	print '<td> nom de la table </td>';
	print '<td> alias </td>';
	print '<td> champs de jonction 1 </td>';
	print '<td> champs de jonction 2 </td>';
	print '</tr>';

	foreach ($from->lines as $line){
		print '<tr>';
		print '<td>'. $line->order . '</td>';
		print '<td>' . $line->from . '</td>';
		print '<td>' . $line->table . '</td>';
		print '<td>' . $line->as . '</td>';
		print '<td>' . $line->field1 . '</td>';
		print '<td>' . $line->field2 . '</td>';
		print '</tr>';
	}
	print '</table>';
	dol_fiche_end();
}
if($step==3){
	dol_fiche_head();
	print_fiche_titre($title.' - Step 3', '', dol_buildpath('/dyntable/img/object_list.png', 1), 1);

	$where = new dyntable_where($db);
	$where->fetchAll('ASC','ordre',0,0,array('fk_dyntable'=> $id),'AND');


	print '<table class="border" width="100%">';
	print '<tr>';
	print '<td> ordre </td>';
	print '<td> Jonction </td>';
	print '<td> champ </td>';
	print '<td> opérateur </td>';
	print '<td> valeur 1 </td>';
	print '<td> valeur 2 </td>';
	print '<td></td>';
	print '</tr>';

	foreach ($where->lines as $line){
		print '<tr>';
		print '<td>'. $line->ordre . '</td>';
		print '<td>' . $line->where . '</td>';
		print '<td>' . $line->field . '</td>';
		print '<td>' . $line->operateur . '</td>';
		print '<td>' . $line->val1 . '</td>';
		print '<td>' . $line->val2 . '</td>';
		print '<td>';
		print '<a href="' . $_SERVER['PHP_SELF'] . '?id='.$id.'&action=del_where&element='. $line->id .'&step=3">' . img_delete() . '</a>';
		print '<a href="' . $_SERVER['PHP_SELF'] . '?id='.$id.'&action=edit_where&element='. $line->id .'&step=3">' . img_edit(). '</a>';
		print '</td>';
		print '</tr>';
	}

	print '<tr>';
	print '<form name="addlead" action="' . $_SERVER["PHP_SELF"] . '" method="POST">';
	print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
	if($action == 'edit_where'){
		print '<input type="hidden" name="action" value="edit-where">';
		print '<input type="hidden" name="element" value="' . GETPOST('element') . '">';
	}else{
		print '<input type="hidden" name="action" value="add-where">';
	}
	print '<input type="hidden" name="step" value="3">';
	print '<input type="hidden" name="id" value="'. $id .'">';

	print '<td class="fieldrequired">';
	print '<input type="text" name="order" size="3" value="' . $edit_ordre . '"/>';
	print '</td>';

	print '<td class="fieldrequired">';
	$join = array('WHERE','AND', 'OR');
	print $form->selectarray('jonction', $join,$edit_where,1,0,1,'',0,0,0,'','',0);
	print '</td>';

	print '<td class="fieldrequired">';
	$fields = array();
	$from = new dyntable_from($db);
	$from->fetchAll('ASC','ordre',0,0,array('fk_dyntable'=>$id),'AND');
	foreach ($from->lines as $line){
		$tableinfo=$db->DDLInfoTable($line->table);
		foreach ($tableinfo as $field){
			$fields[] = $line->as . '.' . $field[0];
		}
	}
	print $form->selectarray('field', $fields,$edit_field,1,0,1,'',0,0,0,'','',1);
	print '</td>';

	print '<td class="fieldrequired">';
	$operateur = array('=','<', '>','<>','>=','<=','IS NULL','IS NOT NULL','IN', 'NOT IN','BETWEEN', 'NOT BETWEEN');
	print $form->selectarray('operateur', $operateur,$edit_operateur,1,0,1,'',0,0,0,'','',0);
	print '</td>';

	print '<td class="fieldrequired">';
	print '<input type="text" name="val1" id="val1" size="15" value="' . $edit_val1 . '"/>';
	print '</td>';

	print '<td class="fieldrequired">';
	print '<input type="text" name="val2" id="val2" size="15" value="' . $edit_val2 . '"/>';
	print '</td>';

	print '<td class="fieldrequired">';
	print '<input type="submit" class="button" value="' . $langs->trans("Create") . '">';
	print '</td>';

	print '</form>';
	print '</tr>';

	print '</table>';
	dol_fiche_end();

}elseif ($step>3){
	dol_fiche_head();
	print_fiche_titre($title.' - Step 3', '', dol_buildpath('/dyntable/img/object_list.png', 1), 1);

	$where = new dyntable_where($db);
	$where->fetchAll('ASC','ordre',0,0,array('fk_dyntable'=> $id),'AND');

	print '<tr>';
	print '<td> ordre </td>';
	print '<td> Jonction </td>';
	print '<td> champ </td>';
	print '<td> opérateur </td>';
	print '<td> valeur 1 </td>';
	print '<td> valeur 2 </td>';
	print '</tr>';

	foreach ($where->lines as $line){
		print '<tr>';
		print '<td>'. $line->ordre . '</td>';
		print '<td>' . $line->where . '</td>';
		print '<td>' . $line->field . '</td>';
		print '<td>' . $line->operateur . '</td>';
		print '<td>' . $line->val1 . '</td>';
		print '<td>' . $line->val2 . '</td>';
		print '</tr>';
	}

	dol_fiche_end();
}

?>
<script type="text/javascript" language="javascript">


</script>
<?php


llxFooter();
$db->close();
