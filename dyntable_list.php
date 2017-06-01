<?php
/* Copyright (C) 2007-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2016      Jean-François Ferry	<jfefe@aternatik.fr>
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
 *   	\file       dyntable/dyntable_list.php
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
require_once(DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php');
require_once DOL_DOCUMENT_ROOT.'/core/lib/date.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';
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


$search_myfield=GETPOST('search_myfield');
$optioncss = GETPOST('optioncss','alpha');

// Load variable for pagination
$limit = GETPOST("limit")?GETPOST("limit","int"):$conf->liste_limit;
$sortfield = GETPOST('sortfield','alpha');
$sortorder = GETPOST('sortorder','alpha');
$page = GETPOST('page','int');
if ($page == -1) { $page = 0; }
$offset = $limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
if (! $sortfield) $sortfield="t.rowid"; // Set here default search field
if (! $sortorder) $sortorder="ASC";

// Protection if external user
$socid=0;
if ($user->societe_id > 0)
{
    $socid = $user->societe_id;
	//accessforbidden();
}

// Initialize technical object to manage hooks. Note that conf->hooks_modules contains array
$hookmanager->initHooks(array('dyntablelist'));
$extrafields = new ExtraFields($db);

// fetch optionals attributes and labels
$extralabels = $extrafields->fetch_name_optionals_label('dyntable');
$search_array_options=$extrafields->getOptionalsFromPost($extralabels,'','search_');

// Load object if id or ref is provided as parameter
$object=new Dyntable($db);
if (($id > 0 || ! empty($ref)) && $action != 'add')
{
	$result=$object->fetch($id,$ref);
	if ($result < 0) dol_print_error($db);
}

// Definition of fields for list
$arrayfields=array(
    
't.title'=>array('label'=>$langs->trans("Fieldtitle"), 'checked'=>1),
't.default_sortfield'=>array('label'=>$langs->trans("Fielddefault_sortfield"), 'checked'=>1),
't.export_name'=>array('label'=>$langs->trans("Fieldexport_name"), 'checked'=>1),
't.context'=>array('label'=>$langs->trans("Fieldcontext"), 'checked'=>1),
't.search_button'=>array('label'=>$langs->trans("Fieldsearch_button"), 'checked'=>1),
't.remove_filter_button'=>array('label'=>$langs->trans("Fieldremove_filter_button"), 'checked'=>1),
't.export_button'=>array('label'=>$langs->trans("Fieldexport_button"), 'checked'=>1),
't.select_fields_button'=>array('label'=>$langs->trans("Fieldselect_fields_button"), 'checked'=>1),
't.mode'=>array('label'=>$langs->trans("Fieldmode"), 'checked'=>1),
't.limite'=>array('label'=>$langs->trans("Fieldlimite"), 'checked'=>1),
't.filter_clause'=>array('label'=>$langs->trans("Fieldfilter_clause"), 'checked'=>1),
't.filter_mode'=>array('label'=>$langs->trans("Fieldfilter_mode"), 'checked'=>1),
't.filter_line'=>array('label'=>$langs->trans("Fieldfilter_line"), 'checked'=>1),
't.sql_from'=>array('label'=>$langs->trans("Fieldsql_from"), 'checked'=>1),
't.sql_where'=>array('label'=>$langs->trans("Fieldsql_where"), 'checked'=>1),
't.sql_having'=>array('label'=>$langs->trans("Fieldsql_having"), 'checked'=>1),
't.sql_group'=>array('label'=>$langs->trans("Fieldsql_group"), 'checked'=>1),
't.sql_filter_action'=>array('label'=>$langs->trans("Fieldsql_filter_action"), 'checked'=>1),
't.sql_select'=>array('label'=>$langs->trans("Fieldsql_select"), 'checked'=>1),
't.subtitle'=>array('label'=>$langs->trans("Fieldsubtitle"), 'checked'=>1),
't.active'=>array('label'=>$langs->trans("Fieldactive"), 'checked'=>1),

    
    //'t.entity'=>array('label'=>$langs->trans("Entity"), 'checked'=>1, 'enabled'=>(! empty($conf->multicompany->enabled) && empty($conf->multicompany->transverse_mode))),
    't.datec'=>array('label'=>$langs->trans("DateCreationShort"), 'checked'=>0, 'position'=>500),
    't.tms'=>array('label'=>$langs->trans("DateModificationShort"), 'checked'=>0, 'position'=>500),
    //'t.statut'=>array('label'=>$langs->trans("Status"), 'checked'=>1, 'position'=>1000),
);
// Extra fields
if (is_array($extrafields->attribute_label) && count($extrafields->attribute_label))
{
   foreach($extrafields->attribute_label as $key => $val) 
   {
       $arrayfields["ef.".$key]=array('label'=>$extrafields->attribute_label[$key], 'checked'=>$extrafields->attribute_list[$key], 'position'=>$extrafields->attribute_pos[$key], 'enabled'=>$extrafields->attribute_perms[$key]);
   }
}




/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/

if (GETPOST('cancel')) { $action='list'; $massaction=''; }
if (! GETPOST('confirmmassaction')) { $massaction=''; }

$parameters=array();
$reshook=$hookmanager->executeHooks('doActions',$parameters,$object,$action);    // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

include DOL_DOCUMENT_ROOT.'/core/actions_changeselectedfields.inc.php';

if (GETPOST("button_removefilter_x") || GETPOST("button_removefilter.x") ||GETPOST("button_removefilter")) // All test are required to be compatible with all browsers
{
	
$search_title='';
$search_default_sortfield='';
$search_export_name='';
$search_context='';
$search_search_button='';
$search_remove_filter_button='';
$search_export_button='';
$search_select_fields_button='';
$search_mode='';
$search_limite='';
$search_filter_clause='';
$search_filter_mode='';
$search_filter_line='';
$search_sql_from='';
$search_sql_where='';
$search_sql_having='';
$search_sql_group='';
$search_sql_filter_action='';
$search_sql_select='';
$search_subtitle='';
$search_active='';

	
	$search_date_creation='';
	$search_date_update='';
	$search_array_options=array();
}


if (empty($reshook))
{
    // Mass actions. Controls on number of lines checked
    $maxformassaction=1000;
    if (! empty($massaction) && count($toselect) < 1)
    {
        $error++;
        setEventMessages($langs->trans("NoLineChecked"), null, "warnings");
    }
    if (! $error && count($toselect) > $maxformassaction)
    {
        setEventMessages($langs->trans('TooManyRecordForMassAction',$maxformassaction), null, 'errors');
        $error++;
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
			if (! empty($object->errors)) setEventMessages(null,$object->errors,'errors');
			else setEventMessages($object->error,null,'errors');
		}
	}
}




/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/

$now=dol_now();

$form=new Form($db);

//$help_url="EN:Module_Customers_Orders|FR:Module_Commandes_Clients|ES:Módulo_Pedidos_de_clientes";
$help_url='';
$title = $langs->trans('MyModuleListTitle');
llxHeader('', $title, $help_url);

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


$sql = "SELECT";
$sql.= " t.rowid,";

		$sql .= " t.title,";
		$sql .= " t.default_sortfield,";
		$sql .= " t.export_name,";
		$sql .= " t.context,";
		$sql .= " t.search_button,";
		$sql .= " t.remove_filter_button,";
		$sql .= " t.export_button,";
		$sql .= " t.select_fields_button,";
		$sql .= " t.mode,";
		$sql .= " t.limite,";
		$sql .= " t.filter_clause,";
		$sql .= " t.filter_mode,";
		$sql .= " t.filter_line,";
		$sql .= " t.sql_from,";
		$sql .= " t.sql_where,";
		$sql .= " t.sql_having,";
		$sql .= " t.sql_group,";
		$sql .= " t.sql_filter_action,";
		$sql .= " t.sql_select,";
		$sql .= " t.subtitle,";
		$sql .= " t.active";


// Add fields for extrafields
foreach ($extrafields->attribute_label as $key => $val) $sql.=($extrafields->attribute_type[$key] != 'separate' ? ",ef.".$key.' as options_'.$key : '');
// Add fields from hooks
$parameters=array();
$reshook=$hookmanager->executeHooks('printFieldListSelect',$parameters);    // Note that $action and $object may have been modified by hook
$sql.=$hookmanager->resPrint;
$sql.= " FROM ".MAIN_DB_PREFIX."dyntable as t";
if (is_array($extrafields->attribute_label) && count($extrafields->attribute_label)) $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."dyntable_extrafields as ef on (u.rowid = ef.fk_object)";
$sql.= " WHERE 1 = 1";
//$sql.= " WHERE u.entity IN (".getEntity('mytable',1).")";

if ($search_title) $sql.= natural_search("title",$search_title);
if ($search_default_sortfield) $sql.= natural_search("default_sortfield",$search_default_sortfield);
if ($search_export_name) $sql.= natural_search("export_name",$search_export_name);
if ($search_context) $sql.= natural_search("context",$search_context);
if ($search_search_button) $sql.= natural_search("search_button",$search_search_button);
if ($search_remove_filter_button) $sql.= natural_search("remove_filter_button",$search_remove_filter_button);
if ($search_export_button) $sql.= natural_search("export_button",$search_export_button);
if ($search_select_fields_button) $sql.= natural_search("select_fields_button",$search_select_fields_button);
if ($search_mode) $sql.= natural_search("mode",$search_mode);
if ($search_limite) $sql.= natural_search("limite",$search_limite);
if ($search_filter_clause) $sql.= natural_search("filter_clause",$search_filter_clause);
if ($search_filter_mode) $sql.= natural_search("filter_mode",$search_filter_mode);
if ($search_filter_line) $sql.= natural_search("filter_line",$search_filter_line);
if ($search_sql_from) $sql.= natural_search("sql_from",$search_sql_from);
if ($search_sql_where) $sql.= natural_search("sql_where",$search_sql_where);
if ($search_sql_having) $sql.= natural_search("sql_having",$search_sql_having);
if ($search_sql_group) $sql.= natural_search("sql_group",$search_sql_group);
if ($search_sql_filter_action) $sql.= natural_search("sql_filter_action",$search_sql_filter_action);
if ($search_sql_select) $sql.= natural_search("sql_select",$search_sql_select);
if ($search_subtitle) $sql.= natural_search("subtitle",$search_subtitle);
if ($search_active) $sql.= natural_search("active",$search_active);


if ($sall)          $sql.= natural_search(array_keys($fieldstosearchall), $sall);
// Add where from extra fields
foreach ($search_array_options as $key => $val)
{
    $crit=$val;
    $tmpkey=preg_replace('/search_options_/','',$key);
    $typ=$extrafields->attribute_type[$tmpkey];
    $mode=0;
    if (in_array($typ, array('int','double'))) $mode=1;    // Search on a numeric
    if ($val && ( ($crit != '' && ! in_array($typ, array('select'))) || ! empty($crit))) 
    {
        $sql .= natural_search('ef.'.$tmpkey, $crit, $mode);
    }
}
// Add where from hooks
$parameters=array();
$reshook=$hookmanager->executeHooks('printFieldListWhere',$parameters);    // Note that $action and $object may have been modified by hook
$sql.=$hookmanager->resPrint;
$sql.=$db->order($sortfield,$sortorder);
//$sql.= $db->plimit($conf->liste_limit+1, $offset);

// Count total nb of records
$nbtotalofrecords = 0;
if (empty($conf->global->MAIN_DISABLE_FULL_SCANLIST))
{
	$result = $db->query($sql);
	$nbtotalofrecords = $db->num_rows($result);
}	

$sql.= $db->plimit($limit+1, $offset);


dol_syslog($script_file, LOG_DEBUG);
$resql=$db->query($sql);
if ($resql)
{
    $num = $db->num_rows($resql);
    
    $params='';
    if ($limit > 0 && $limit != $conf->liste_limit) $param.='&limit='.$limit;
    
if ($search_title != '') $params.= '&amp;search_title='.urlencode($search_title);
if ($search_default_sortfield != '') $params.= '&amp;search_default_sortfield='.urlencode($search_default_sortfield);
if ($search_export_name != '') $params.= '&amp;search_export_name='.urlencode($search_export_name);
if ($search_context != '') $params.= '&amp;search_context='.urlencode($search_context);
if ($search_search_button != '') $params.= '&amp;search_search_button='.urlencode($search_search_button);
if ($search_remove_filter_button != '') $params.= '&amp;search_remove_filter_button='.urlencode($search_remove_filter_button);
if ($search_export_button != '') $params.= '&amp;search_export_button='.urlencode($search_export_button);
if ($search_select_fields_button != '') $params.= '&amp;search_select_fields_button='.urlencode($search_select_fields_button);
if ($search_mode != '') $params.= '&amp;search_mode='.urlencode($search_mode);
if ($search_limite != '') $params.= '&amp;search_limite='.urlencode($search_limite);
if ($search_filter_clause != '') $params.= '&amp;search_filter_clause='.urlencode($search_filter_clause);
if ($search_filter_mode != '') $params.= '&amp;search_filter_mode='.urlencode($search_filter_mode);
if ($search_filter_line != '') $params.= '&amp;search_filter_line='.urlencode($search_filter_line);
if ($search_sql_from != '') $params.= '&amp;search_sql_from='.urlencode($search_sql_from);
if ($search_sql_where != '') $params.= '&amp;search_sql_where='.urlencode($search_sql_where);
if ($search_sql_having != '') $params.= '&amp;search_sql_having='.urlencode($search_sql_having);
if ($search_sql_group != '') $params.= '&amp;search_sql_group='.urlencode($search_sql_group);
if ($search_sql_filter_action != '') $params.= '&amp;search_sql_filter_action='.urlencode($search_sql_filter_action);
if ($search_sql_select != '') $params.= '&amp;search_sql_select='.urlencode($search_sql_select);
if ($search_subtitle != '') $params.= '&amp;search_subtitle='.urlencode($search_subtitle);
if ($search_active != '') $params.= '&amp;search_active='.urlencode($search_active);

	
    if ($optioncss != '') $param.='&optioncss='.$optioncss;
    // Add $param from extra fields
    foreach ($search_array_options as $key => $val)
    {
        $crit=$val;
        $tmpkey=preg_replace('/search_options_/','',$key);
        if ($val != '') $param.='&search_options_'.$tmpkey.'='.urlencode($val);
    } 

    print_barre_liste($title, $page, $_SERVER["PHP_SELF"], $params, $sortfield, $sortorder, '', $num, $nbtotalofrecords, 'title_companies', 0, '', '', $limit);


	print '<form method="POST" id="searchFormList" action="'.$_SERVER["PHP_SELF"].'">';
    if ($optioncss != '') print '<input type="hidden" name="optioncss" value="'.$optioncss.'">';
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
	print '<input type="hidden" name="formfilteraction" id="formfilteraction" value="list">';
    print '<input type="hidden" name="action" value="list">';
	print '<input type="hidden" name="sortfield" value="'.$sortfield.'">';
	print '<input type="hidden" name="sortorder" value="'.$sortorder.'">';
	
    if ($sall)
    {
        foreach($fieldstosearchall as $key => $val) $fieldstosearchall[$key]=$langs->trans($val);
        print $langs->trans("FilterOnInto", $all) . join(', ',$fieldstosearchall);
    }
    
    $moreforfilter = '';
    $moreforfilter.='<div class="divsearchfield">';
    $moreforfilter.= $langs->trans('MyFilter') . ': <input type="text" name="search_myfield" value="'.dol_escape_htmltag($search_myfield).'">';
    $moreforfilter.= '</div>';
    
	if (! empty($moreforfilter))
	{
		print '<div class="liste_titre liste_titre_bydiv centpercent">';
		print $moreforfilter;
    	$parameters=array();
    	$reshook=$hookmanager->executeHooks('printFieldPreListTitle',$parameters);    // Note that $action and $object may have been modified by hook
	    print $hookmanager->resPrint;
	    print '</div>';
	}

    $varpage=empty($contextpage)?$_SERVER["PHP_SELF"]:$contextpage;
    $selectedfields=$form->multiSelectArrayWithCheckbox('selectedfields', $arrayfields, $varpage);	// This also change content of $arrayfields
	
	print '<table class="liste '.($moreforfilter?"listwithfilterbefore":"").'">';

    // Fields title
    print '<tr class="liste_titre">';
    // 
if (! empty($arrayfields['t.title']['checked'])) print_liste_field_titre($arrayfields['t.title']['label'],$_SERVER['PHP_SELF'],'t.title','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.default_sortfield']['checked'])) print_liste_field_titre($arrayfields['t.default_sortfield']['label'],$_SERVER['PHP_SELF'],'t.default_sortfield','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.export_name']['checked'])) print_liste_field_titre($arrayfields['t.export_name']['label'],$_SERVER['PHP_SELF'],'t.export_name','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.context']['checked'])) print_liste_field_titre($arrayfields['t.context']['label'],$_SERVER['PHP_SELF'],'t.context','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.search_button']['checked'])) print_liste_field_titre($arrayfields['t.search_button']['label'],$_SERVER['PHP_SELF'],'t.search_button','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.remove_filter_button']['checked'])) print_liste_field_titre($arrayfields['t.remove_filter_button']['label'],$_SERVER['PHP_SELF'],'t.remove_filter_button','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.export_button']['checked'])) print_liste_field_titre($arrayfields['t.export_button']['label'],$_SERVER['PHP_SELF'],'t.export_button','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.select_fields_button']['checked'])) print_liste_field_titre($arrayfields['t.select_fields_button']['label'],$_SERVER['PHP_SELF'],'t.select_fields_button','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.mode']['checked'])) print_liste_field_titre($arrayfields['t.mode']['label'],$_SERVER['PHP_SELF'],'t.mode','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.limite']['checked'])) print_liste_field_titre($arrayfields['t.limite']['label'],$_SERVER['PHP_SELF'],'t.limite','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.filter_clause']['checked'])) print_liste_field_titre($arrayfields['t.filter_clause']['label'],$_SERVER['PHP_SELF'],'t.filter_clause','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.filter_mode']['checked'])) print_liste_field_titre($arrayfields['t.filter_mode']['label'],$_SERVER['PHP_SELF'],'t.filter_mode','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.filter_line']['checked'])) print_liste_field_titre($arrayfields['t.filter_line']['label'],$_SERVER['PHP_SELF'],'t.filter_line','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.sql_from']['checked'])) print_liste_field_titre($arrayfields['t.sql_from']['label'],$_SERVER['PHP_SELF'],'t.sql_from','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.sql_where']['checked'])) print_liste_field_titre($arrayfields['t.sql_where']['label'],$_SERVER['PHP_SELF'],'t.sql_where','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.sql_having']['checked'])) print_liste_field_titre($arrayfields['t.sql_having']['label'],$_SERVER['PHP_SELF'],'t.sql_having','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.sql_group']['checked'])) print_liste_field_titre($arrayfields['t.sql_group']['label'],$_SERVER['PHP_SELF'],'t.sql_group','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.sql_filter_action']['checked'])) print_liste_field_titre($arrayfields['t.sql_filter_action']['label'],$_SERVER['PHP_SELF'],'t.sql_filter_action','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.sql_select']['checked'])) print_liste_field_titre($arrayfields['t.sql_select']['label'],$_SERVER['PHP_SELF'],'t.sql_select','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.subtitle']['checked'])) print_liste_field_titre($arrayfields['t.subtitle']['label'],$_SERVER['PHP_SELF'],'t.subtitle','',$params,'',$sortfield,$sortorder);
if (! empty($arrayfields['t.active']['checked'])) print_liste_field_titre($arrayfields['t.active']['label'],$_SERVER['PHP_SELF'],'t.active','',$params,'',$sortfield,$sortorder);

    //if (! empty($arrayfields['t.field1']['checked'])) print_liste_field_titre($arrayfields['t.field1']['label'],$_SERVER['PHP_SELF'],'t.field1','',$params,'',$sortfield,$sortorder);
    //if (! empty($arrayfields['t.field2']['checked'])) print_liste_field_titre($arrayfields['t.field2']['label'],$_SERVER['PHP_SELF'],'t.field2','',$params,'',$sortfield,$sortorder);
	// Extra fields
	if (is_array($extrafields->attribute_label) && count($extrafields->attribute_label))
	{
	   foreach($extrafields->attribute_label as $key => $val) 
	   {
           if (! empty($arrayfields["ef.".$key]['checked'])) 
           {
				$align=$extrafields->getAlignFlag($key);
				print_liste_field_titre($extralabels[$key],$_SERVER["PHP_SELF"],"ef.".$key,"",$param,($align?'align="'.$align.'"':''),$sortfield,$sortorder);
           }
	   }
	}
    // Hook fields
	$parameters=array('arrayfields'=>$arrayfields);
    $reshook=$hookmanager->executeHooks('printFieldListTitle',$parameters);    // Note that $action and $object may have been modified by hook
    print $hookmanager->resPrint;
	if (! empty($arrayfields['t.datec']['checked']))  print_liste_field_titre($arrayfields['t.datec']['label'],$_SERVER["PHP_SELF"],"t.datec","",$param,'align="center" class="nowrap"',$sortfield,$sortorder);
	if (! empty($arrayfields['t.tms']['checked']))    print_liste_field_titre($arrayfields['t.tms']['label'],$_SERVER["PHP_SELF"],"t.tms","",$param,'align="center" class="nowrap"',$sortfield,$sortorder);
	//if (! empty($arrayfields['t.status']['checked'])) print_liste_field_titre($langs->trans("Status"),$_SERVER["PHP_SELF"],"t.status","",$param,'align="center"',$sortfield,$sortorder);
	print_liste_field_titre($selectedfields, $_SERVER["PHP_SELF"],"",'','','align="right"',$sortfield,$sortorder,'maxwidthsearch ');
    print '</tr>'."\n";

    // Fields title search
	print '<tr class="liste_titre">';
	// 
if (! empty($arrayfields['t.title']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_title" value="'.$search_title.'" size="10"></td>';
if (! empty($arrayfields['t.default_sortfield']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_default_sortfield" value="'.$search_default_sortfield.'" size="10"></td>';
if (! empty($arrayfields['t.export_name']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_export_name" value="'.$search_export_name.'" size="10"></td>';
if (! empty($arrayfields['t.context']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_context" value="'.$search_context.'" size="10"></td>';
if (! empty($arrayfields['t.search_button']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_search_button" value="'.$search_search_button.'" size="10"></td>';
if (! empty($arrayfields['t.remove_filter_button']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_remove_filter_button" value="'.$search_remove_filter_button.'" size="10"></td>';
if (! empty($arrayfields['t.export_button']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_export_button" value="'.$search_export_button.'" size="10"></td>';
if (! empty($arrayfields['t.select_fields_button']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_select_fields_button" value="'.$search_select_fields_button.'" size="10"></td>';
if (! empty($arrayfields['t.mode']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_mode" value="'.$search_mode.'" size="10"></td>';
if (! empty($arrayfields['t.limite']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_limite" value="'.$search_limite.'" size="10"></td>';
if (! empty($arrayfields['t.filter_clause']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_filter_clause" value="'.$search_filter_clause.'" size="10"></td>';
if (! empty($arrayfields['t.filter_mode']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_filter_mode" value="'.$search_filter_mode.'" size="10"></td>';
if (! empty($arrayfields['t.filter_line']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_filter_line" value="'.$search_filter_line.'" size="10"></td>';
if (! empty($arrayfields['t.sql_from']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_sql_from" value="'.$search_sql_from.'" size="10"></td>';
if (! empty($arrayfields['t.sql_where']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_sql_where" value="'.$search_sql_where.'" size="10"></td>';
if (! empty($arrayfields['t.sql_having']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_sql_having" value="'.$search_sql_having.'" size="10"></td>';
if (! empty($arrayfields['t.sql_group']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_sql_group" value="'.$search_sql_group.'" size="10"></td>';
if (! empty($arrayfields['t.sql_filter_action']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_sql_filter_action" value="'.$search_sql_filter_action.'" size="10"></td>';
if (! empty($arrayfields['t.sql_select']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_sql_select" value="'.$search_sql_select.'" size="10"></td>';
if (! empty($arrayfields['t.subtitle']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_subtitle" value="'.$search_subtitle.'" size="10"></td>';
if (! empty($arrayfields['t.active']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_active" value="'.$search_active.'" size="10"></td>';

	//if (! empty($arrayfields['t.field1']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_field1" value="'.$search_field1.'" size="10"></td>';
	//if (! empty($arrayfields['t.field2']['checked'])) print '<td class="liste_titre"><input type="text" class="flat" name="search_field2" value="'.$search_field2.'" size="10"></td>';
	// Extra fields
	if (is_array($extrafields->attribute_label) && count($extrafields->attribute_label))
	{
        foreach($extrafields->attribute_label as $key => $val) 
        {
            if (! empty($arrayfields["ef.".$key]['checked']))
            {
                $align=$extrafields->getAlignFlag($key);
                $typeofextrafield=$extrafields->attribute_type[$key];
                print '<td class="liste_titre'.($align?' '.$align:'').'">';
            	if (in_array($typeofextrafield, array('varchar', 'int', 'double', 'select')))
				{
				    $crit=$val;
    				$tmpkey=preg_replace('/search_options_/','',$key);
    				$searchclass='';
    				if (in_array($typeofextrafield, array('varchar', 'select'))) $searchclass='searchstring';
    				if (in_array($typeofextrafield, array('int', 'double'))) $searchclass='searchnum';
    				print '<input class="flat'.($searchclass?' '.$searchclass:'').'" size="4" type="text" name="search_options_'.$tmpkey.'" value="'.dol_escape_htmltag($search_array_options['search_options_'.$tmpkey]).'">';
				}
                print '</td>';
            }
        }
	}
    // Fields from hook
	$parameters=array('arrayfields'=>$arrayfields);
    $reshook=$hookmanager->executeHooks('printFieldListOption',$parameters);    // Note that $action and $object may have been modified by hook
    print $hookmanager->resPrint;
    if (! empty($arrayfields['t.datec']['checked']))
    {
        // Date creation
        print '<td class="liste_titre">';
        print '</td>';
    }
    if (! empty($arrayfields['t.tms']['checked']))
    {
        // Date modification
        print '<td class="liste_titre">';
        print '</td>';
    }
    /*if (! empty($arrayfields['u.statut']['checked']))
    {
        // Status
        print '<td class="liste_titre" align="center">';
        print $form->selectarray('search_statut', array('-1'=>'','0'=>$langs->trans('Disabled'),'1'=>$langs->trans('Enabled')),$search_statut);
        print '</td>';
    }*/
    // Action column
	print '<td class="liste_titre" align="right">';
    $searchpitco=$form->showFilterAndCheckAddButtons(0);
    print $searchpitco;
    print '</td>';
	print '</tr>'."\n";
        
    
	$i=0;
	$var=true;
	$totalarray=array();
    while ($i < min($num, $limit))
    {
        $obj = $db->fetch_object($resql);
        if ($obj)
        {
            $var = !$var;
            
            // Show here line of result
            print '<tr '.$bc[$var].'>';
            // LIST_OF_TD_FIELDS_LIST
            /*
            if (! empty($arrayfields['t.field1']['checked'])) 
            {
                print '<td>'.$obj->field1.'</td>';
    		    if (! $i) $totalarray['nbfield']++;
            }
            if (! empty($arrayfields['t.field2']['checked'])) 
            {
                print '<td>'.$obj->field2.'</td>';
    		    if (! $i) $totalarray['nbfield']++;
            }*/
        	// Extra fields
    		if (is_array($extrafields->attribute_label) && count($extrafields->attribute_label))
    		{
    		   foreach($extrafields->attribute_label as $key => $val) 
    		   {
    				if (! empty($arrayfields["ef.".$key]['checked'])) 
    				{
    					print '<td';
    					$align=$extrafields->getAlignFlag($key);
    					if ($align) print ' align="'.$align.'"';
    					print '>';
    					$tmpkey='options_'.$key;
    					print $extrafields->showOutputField($key, $obj->$tmpkey, '', 1);
    					print '</td>';
    		            if (! $i) $totalarray['nbfield']++;
    				}
    		   }
    		}
            // Fields from hook
    	    $parameters=array('arrayfields'=>$arrayfields, 'obj'=>$obj);
    		$reshook=$hookmanager->executeHooks('printFieldListValue',$parameters);    // Note that $action and $object may have been modified by hook
            print $hookmanager->resPrint;
        	// Date creation
            if (! empty($arrayfields['t.datec']['checked']))
            {
                print '<td align="center">';
                print dol_print_date($db->jdate($obj->date_creation), 'dayhour');
                print '</td>';
    		    if (! $i) $totalarray['nbfield']++;
            }
            // Date modification
            if (! empty($arrayfields['t.tms']['checked']))
            {
                print '<td align="center">';
                print dol_print_date($db->jdate($obj->date_update), 'dayhour');
                print '</td>';
    		    if (! $i) $totalarray['nbfield']++;
            }
            // Status
            /*
            if (! empty($arrayfields['u.statut']['checked']))
            {
    		  $userstatic->statut=$obj->statut;
              print '<td align="center">'.$userstatic->getLibStatut(3).'</td>';
            }*/

            // Action column
            print '<td></td>';
            if (! $i) $totalarray['nbfield']++;

            print '</tr>';
        }
        $i++;
    }
    
    $db->free($resql);

	$parameters=array('sql' => $sql);
	$reshook=$hookmanager->executeHooks('printFieldListFooter',$parameters);    // Note that $action and $object may have been modified by hook
	print $hookmanager->resPrint;

	print "</table>\n";
	print "</form>\n";
	
	$db->free($result);
}
else
{
    $error++;
    dol_print_error($db);
}


// End of page
llxFooter();
$db->close();
