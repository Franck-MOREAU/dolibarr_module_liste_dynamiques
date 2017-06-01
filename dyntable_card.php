<?php
/* Copyright (C) 2007-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
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
 *   	\file       dyntable/dyntable_card.php
 *		\ingroup    dyntable
 *		\brief      This file is an example of a php page
 *					Initialy built by build_class_from_table on 2017-06-01 17:08
 */

//if (! defined('NOREQUIREUSER'))  define('NOREQUIREUSER','1');
//if (! defined('NOREQUIREDB'))    define('NOREQUIREDB','1');
//if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');
//if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK','1');			// Do not check anti CSRF attack test
//if (! defined('NOSTYLECHECK'))   define('NOSTYLECHECK','1');			// Do not check style html tag into posted data
//if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1');		// Do not check anti POST attack test
//if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');			// If there is no need to load and show top and left menu
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');			// If we don't need to load the html.form.class.php
//if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
//if (! defined("NOLOGIN"))        define("NOLOGIN",'1');				// If this page is public (can be called outside logged session)

// Change this following line to use the correct relative path (../, ../../, etc)
$res=0;
if (! $res && file_exists("../main.inc.php")) $res=@include '../main.inc.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists("../../main.inc.php")) $res=@include '../../main.inc.php';			// to work if your module directory is into a subdir of root htdocs directory
if (! $res && file_exists("../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../dolibarr/htdocs/main.inc.php';     // Used on dev env only
if (! $res && file_exists("../../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../../dolibarr/htdocs/main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");
// Change this following line to use the correct relative path from htdocs
include_once(DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php');
dol_include_once('/dyntable/class/dyntable.class.php');

// Load traductions files requiredby by page
$langs->load("dyntable");
$langs->load("other");

// Get parameters
$id			= GETPOST('id','int');
$action		= GETPOST('action','alpha');
$backtopage = GETPOST('backtopage');
$myparam	= GETPOST('myparam','alpha');


$search_title=GETPOST('search_title','alpha');
$search_default_sortfield=GETPOST('search_default_sortfield','alpha');
$search_export_name=GETPOST('search_export_name','alpha');
$search_context=GETPOST('search_context','alpha');
$search_search_button=GETPOST('search_search_button','int');
$search_remove_filter_button=GETPOST('search_remove_filter_button','int');
$search_export_button=GETPOST('search_export_button','int');
$search_select_fields_button=GETPOST('search_select_fields_button','int');
$search_mode=GETPOST('search_mode','alpha');
$search_limite=GETPOST('search_limite','int');
$search_filter_clause=GETPOST('search_filter_clause','alpha');
$search_filter_mode=GETPOST('search_filter_mode','alpha');
$search_filter_line=GETPOST('search_filter_line','int');
$search_sql_from=GETPOST('search_sql_from','alpha');
$search_sql_where=GETPOST('search_sql_where','alpha');
$search_sql_having=GETPOST('search_sql_having','alpha');
$search_sql_group=GETPOST('search_sql_group','alpha');
$search_sql_filter_action=GETPOST('search_sql_filter_action','alpha');
$search_sql_select=GETPOST('search_sql_select','alpha');
$search_subtitle=GETPOST('search_subtitle','alpha');
$search_active=GETPOST('search_active','int');



// Protection if external user
if ($user->societe_id > 0)
{
	//accessforbidden();
}

if (empty($action) && empty($id) && empty($ref)) $action='list';

// Load object if id or ref is provided as parameter
$object=new Dyntable($db);
if (($id > 0 || ! empty($ref)) && $action != 'add')
{
	$result=$object->fetch($id,$ref);
	if ($result < 0) dol_print_error($db);
}

// Initialize technical object to manage hooks of modules. Note that conf->hooks_modules contains array array
$hookmanager->initHooks(array('dyntable'));
$extrafields = new ExtraFields($db);



/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/

$parameters=array();
$reshook=$hookmanager->executeHooks('doActions',$parameters,$object,$action);    // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

if (empty($reshook))
{
	// Action to add record
	if ($action == 'add')
	{
		if (GETPOST('cancel'))
		{
			$urltogo=$backtopage?$backtopage:dol_buildpath('/dyntable/list.php',1);
			header("Location: ".$urltogo);
			exit;
		}

		$error=0;

		/* object_prop_getpost_prop */
		
	$object->title=GETPOST('title','alpha');
	$object->default_sortfield=GETPOST('default_sortfield','alpha');
	$object->export_name=GETPOST('export_name','alpha');
	$object->context=GETPOST('context','alpha');
	$object->search_button=GETPOST('search_button','int');
	$object->remove_filter_button=GETPOST('remove_filter_button','int');
	$object->export_button=GETPOST('export_button','int');
	$object->select_fields_button=GETPOST('select_fields_button','int');
	$object->mode=GETPOST('mode','alpha');
	$object->limite=GETPOST('limite','int');
	$object->filter_clause=GETPOST('filter_clause','alpha');
	$object->filter_mode=GETPOST('filter_mode','alpha');
	$object->filter_line=GETPOST('filter_line','int');
	$object->sql_from=GETPOST('sql_from','alpha');
	$object->sql_where=GETPOST('sql_where','alpha');
	$object->sql_having=GETPOST('sql_having','alpha');
	$object->sql_group=GETPOST('sql_group','alpha');
	$object->sql_filter_action=GETPOST('sql_filter_action','alpha');
	$object->sql_select=GETPOST('sql_select','alpha');
	$object->subtitle=GETPOST('subtitle','alpha');
	$object->active=GETPOST('active','int');

		

		if (empty($object->ref))
		{
			$error++;
			setEventMessages($langs->trans("ErrorFieldRequired",$langs->transnoentitiesnoconv("Ref")), null, 'errors');
		}

		if (! $error)
		{
			$result=$object->create($user);
			if ($result > 0)
			{
				// Creation OK
				$urltogo=$backtopage?$backtopage:dol_buildpath('/dyntable/list.php',1);
				header("Location: ".$urltogo);
				exit;
			}
			{
				// Creation KO
				if (! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
				else  setEventMessages($object->error, null, 'errors');
				$action='create';
			}
		}
		else
		{
			$action='create';
		}
	}

	// Cancel
	if ($action == 'update' && GETPOST('cancel')) $action='view';

	// Action to update record
	if ($action == 'update' && ! GETPOST('cancel'))
	{
		$error=0;

		
	$object->title=GETPOST('title','alpha');
	$object->default_sortfield=GETPOST('default_sortfield','alpha');
	$object->export_name=GETPOST('export_name','alpha');
	$object->context=GETPOST('context','alpha');
	$object->search_button=GETPOST('search_button','int');
	$object->remove_filter_button=GETPOST('remove_filter_button','int');
	$object->export_button=GETPOST('export_button','int');
	$object->select_fields_button=GETPOST('select_fields_button','int');
	$object->mode=GETPOST('mode','alpha');
	$object->limite=GETPOST('limite','int');
	$object->filter_clause=GETPOST('filter_clause','alpha');
	$object->filter_mode=GETPOST('filter_mode','alpha');
	$object->filter_line=GETPOST('filter_line','int');
	$object->sql_from=GETPOST('sql_from','alpha');
	$object->sql_where=GETPOST('sql_where','alpha');
	$object->sql_having=GETPOST('sql_having','alpha');
	$object->sql_group=GETPOST('sql_group','alpha');
	$object->sql_filter_action=GETPOST('sql_filter_action','alpha');
	$object->sql_select=GETPOST('sql_select','alpha');
	$object->subtitle=GETPOST('subtitle','alpha');
	$object->active=GETPOST('active','int');

		

		if (empty($object->ref))
		{
			$error++;
			setEventMessages($langs->transnoentitiesnoconv("ErrorFieldRequired",$langs->transnoentitiesnoconv("Ref")), null, 'errors');
		}

		if (! $error)
		{
			$result=$object->update($user);
			if ($result > 0)
			{
				$action='view';
			}
			else
			{
				// Creation KO
				if (! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
				else setEventMessages($object->error, null, 'errors');
				$action='edit';
			}
		}
		else
		{
			$action='edit';
		}
	}

	// Action to delete
	if ($action == 'confirm_delete')
	{
		$result=$object->delete($user);
		if ($result > 0)
		{
			// Delete OK
			setEventMessages("RecordDeleted", null, 'mesgs');
			header("Location: ".dol_buildpath('/dyntable/list.php',1));
			exit;
		}
		else
		{
			if (! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
			else setEventMessages($object->error, null, 'errors');
		}
	}
}




/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/

llxHeader('','MyPageName','');

$form=new Form($db);


// Put here content of your page

// Example : Adding jquery code
print '<script type="text/javascript" language="javascript">
jQuery(document).ready(function() {
	function init_myfunc()
	{
		jQuery("#myid").removeAttr(\'disabled\');
		jQuery("#myid").attr(\'disabled\',\'disabled\');
	}
	init_myfunc();
	jQuery("#mybutton").click(function() {
		init_myfunc();
	});
});
</script>';


// Part to create
if ($action == 'create')
{
	print load_fiche_titre($langs->trans("NewMyModule"));

	print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
	print '<input type="hidden" name="action" value="add">';
	print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';

	dol_fiche_head();

	print '<table class="border centpercent">'."\n";
	// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
	// 
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldtitle").'</td><td><input class="flat" type="text" name="title" value="'.GETPOST('title').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fielddefault_sortfield").'</td><td><input class="flat" type="text" name="default_sortfield" value="'.GETPOST('default_sortfield').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldexport_name").'</td><td><input class="flat" type="text" name="export_name" value="'.GETPOST('export_name').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldcontext").'</td><td><input class="flat" type="text" name="context" value="'.GETPOST('context').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsearch_button").'</td><td><input class="flat" type="text" name="search_button" value="'.GETPOST('search_button').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldremove_filter_button").'</td><td><input class="flat" type="text" name="remove_filter_button" value="'.GETPOST('remove_filter_button').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldexport_button").'</td><td><input class="flat" type="text" name="export_button" value="'.GETPOST('export_button').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldselect_fields_button").'</td><td><input class="flat" type="text" name="select_fields_button" value="'.GETPOST('select_fields_button').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldmode").'</td><td><input class="flat" type="text" name="mode" value="'.GETPOST('mode').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldlimite").'</td><td><input class="flat" type="text" name="limite" value="'.GETPOST('limite').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfilter_clause").'</td><td><input class="flat" type="text" name="filter_clause" value="'.GETPOST('filter_clause').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfilter_mode").'</td><td><input class="flat" type="text" name="filter_mode" value="'.GETPOST('filter_mode').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfilter_line").'</td><td><input class="flat" type="text" name="filter_line" value="'.GETPOST('filter_line').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_from").'</td><td><input class="flat" type="text" name="sql_from" value="'.GETPOST('sql_from').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_where").'</td><td><input class="flat" type="text" name="sql_where" value="'.GETPOST('sql_where').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_having").'</td><td><input class="flat" type="text" name="sql_having" value="'.GETPOST('sql_having').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_group").'</td><td><input class="flat" type="text" name="sql_group" value="'.GETPOST('sql_group').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_filter_action").'</td><td><input class="flat" type="text" name="sql_filter_action" value="'.GETPOST('sql_filter_action').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_select").'</td><td><input class="flat" type="text" name="sql_select" value="'.GETPOST('sql_select').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsubtitle").'</td><td><input class="flat" type="text" name="subtitle" value="'.GETPOST('subtitle').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldactive").'</td><td><input class="flat" type="text" name="active" value="'.GETPOST('active').'"></td></tr>';

	print '</table>'."\n";

	dol_fiche_end();

	print '<div class="center"><input type="submit" class="button" name="add" value="'.$langs->trans("Create").'"> &nbsp; <input type="submit" class="button" name="cancel" value="'.$langs->trans("Cancel").'"></div>';

	print '</form>';
}



// Part to edit record
if (($id || $ref) && $action == 'edit')
{
	print load_fiche_titre($langs->trans("MyModule"));
    
	print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
	print '<input type="hidden" name="action" value="update">';
	print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';
	print '<input type="hidden" name="id" value="'.$object->id.'">';
	
	dol_fiche_head();

	print '<table class="border centpercent">'."\n";
	// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
	// 
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldtitle").'</td><td><input class="flat" type="text" name="title" value="'.$object->title.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fielddefault_sortfield").'</td><td><input class="flat" type="text" name="default_sortfield" value="'.$object->default_sortfield.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldexport_name").'</td><td><input class="flat" type="text" name="export_name" value="'.$object->export_name.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldcontext").'</td><td><input class="flat" type="text" name="context" value="'.$object->context.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsearch_button").'</td><td><input class="flat" type="text" name="search_button" value="'.$object->search_button.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldremove_filter_button").'</td><td><input class="flat" type="text" name="remove_filter_button" value="'.$object->remove_filter_button.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldexport_button").'</td><td><input class="flat" type="text" name="export_button" value="'.$object->export_button.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldselect_fields_button").'</td><td><input class="flat" type="text" name="select_fields_button" value="'.$object->select_fields_button.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldmode").'</td><td><input class="flat" type="text" name="mode" value="'.$object->mode.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldlimite").'</td><td><input class="flat" type="text" name="limite" value="'.$object->limite.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfilter_clause").'</td><td><input class="flat" type="text" name="filter_clause" value="'.$object->filter_clause.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfilter_mode").'</td><td><input class="flat" type="text" name="filter_mode" value="'.$object->filter_mode.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfilter_line").'</td><td><input class="flat" type="text" name="filter_line" value="'.$object->filter_line.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_from").'</td><td><input class="flat" type="text" name="sql_from" value="'.$object->sql_from.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_where").'</td><td><input class="flat" type="text" name="sql_where" value="'.$object->sql_where.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_having").'</td><td><input class="flat" type="text" name="sql_having" value="'.$object->sql_having.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_group").'</td><td><input class="flat" type="text" name="sql_group" value="'.$object->sql_group.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_filter_action").'</td><td><input class="flat" type="text" name="sql_filter_action" value="'.$object->sql_filter_action.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_select").'</td><td><input class="flat" type="text" name="sql_select" value="'.$object->sql_select.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsubtitle").'</td><td><input class="flat" type="text" name="subtitle" value="'.$object->subtitle.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldactive").'</td><td><input class="flat" type="text" name="active" value="'.$object->active.'"></td></tr>';

	print '</table>';
	
	dol_fiche_end();

	print '<div class="center"><input type="submit" class="button" name="save" value="'.$langs->trans("Save").'">';
	print ' &nbsp; <input type="submit" class="button" name="cancel" value="'.$langs->trans("Cancel").'">';
	print '</div>';

	print '</form>';
}



// Part to show record
if ($id && (empty($action) || $action == 'view' || $action == 'delete'))
{
	print load_fiche_titre($langs->trans("MyModule"));
    
	dol_fiche_head();

	if ($action == 'delete') {
		$formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('DeleteMyOjbect'), $langs->trans('ConfirmDeleteMyObject'), 'confirm_delete', '', 0, 1);
		print $formconfirm;
	}
	
	print '<table class="border centpercent">'."\n";
	// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
	// 
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldtitle").'</td><td>$object->title</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fielddefault_sortfield").'</td><td>$object->default_sortfield</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldexport_name").'</td><td>$object->export_name</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldcontext").'</td><td>$object->context</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsearch_button").'</td><td>$object->search_button</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldremove_filter_button").'</td><td>$object->remove_filter_button</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldexport_button").'</td><td>$object->export_button</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldselect_fields_button").'</td><td>$object->select_fields_button</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldmode").'</td><td>$object->mode</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldlimite").'</td><td>$object->limite</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfilter_clause").'</td><td>$object->filter_clause</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfilter_mode").'</td><td>$object->filter_mode</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfilter_line").'</td><td>$object->filter_line</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_from").'</td><td>$object->sql_from</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_where").'</td><td>$object->sql_where</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_having").'</td><td>$object->sql_having</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_group").'</td><td>$object->sql_group</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_filter_action").'</td><td>$object->sql_filter_action</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsql_select").'</td><td>$object->sql_select</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldsubtitle").'</td><td>$object->subtitle</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldactive").'</td><td>$object->active</td></tr>';

	print '</table>';
	
	dol_fiche_end();


	// Buttons
	print '<div class="tabsAction">'."\n";
	$parameters=array();
	$reshook=$hookmanager->executeHooks('addMoreActionsButtons',$parameters,$object,$action);    // Note that $action and $object may have been modified by hook
	if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

	if (empty($reshook))
	{
		if ($user->rights->dyntable->write)
		{
			print '<div class="inline-block divButAction"><a class="butAction" href="'.$_SERVER["PHP_SELF"].'?id='.$object->id.'&amp;action=edit">'.$langs->trans("Modify").'</a></div>'."\n";
		}

		if ($user->rights->dyntable->delete)
		{
			print '<div class="inline-block divButAction"><a class="butActionDelete" href="'.$_SERVER["PHP_SELF"].'?id='.$object->id.'&amp;action=delete">'.$langs->trans('Delete').'</a></div>'."\n";
		}
	}
	print '</div>'."\n";


	// Example 2 : Adding links to objects
	//$somethingshown=$form->showLinkedObjectBlock($object);
	//$linktoelem = $form->showLinkToObjectBlock($object);
	//if ($linktoelem) print '<br>'.$linktoelem;

}


// End of page
llxFooter();
$db->close();
