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
require_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';

$langs->load('dyntable@dyntable');

// Security check
if (! $user->rights->dyntable->admin)
	accessforbidden();

$title = $langs->trans("createlisttitle");


$form = new Form($db);
llxHeader('', $title);
dol_fiche_head();
print_fiche_titre($title, '', dol_buildpath('/dyntable/img/object_list.png', 1), 1);
print '<form name="addlead" action="' . $_SERVER["PHP_SELF"] . '" method="POST">';
print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
print '<input type="hidden" name="action" value="add">';

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
print '<td class="fieldrequired"  width="25%">';
print $langs->trans('Filter_button') .': ';
print $form->selectyesno("filter_button",'1',1);
print '</td>';

print '<td class="fieldrequired"  width="25%">';
print $langs->trans('export_button') .': ';
print $form->selectyesno("export_button",'1',1);
print ' ' . $langs->trans('export_name') .': ';
print '<input type="text" name="export_name" id="export_name" size="15" value=""/>';
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

function createlead() {
	$div = $('<div id="createlead"><iframe width="100%" height="100%" frameborder="0" src="<?php echo dol_buildpath('/volvo/lead/leadexpress.php?action=create&userid='.$search_commercial, 1); ?>" style="display: block;"></iframe></div>');
	$div.dialog({
		modal:true,
		width:"90%",
		height:$(window).height() - 25,
		close:function() {
 			document.location.reload(true);
 		}
	});
}

function wievlead(idlead) {
	$div = $('<div id="wievlead"><iframe width="100%" height="100%" frameborder="0" src="<?php echo dol_buildpath('/volvo/lead/leadexpress.php', 1) . '?id='; ?>' + idlead + '" style="display: block;"></iframe></div>');
	$div.dialog({
 		modal:true,
		width:"90%",
		height:$(window).height() - 25,
		close:function() {
			if($('#ordercreatedid').val()>0){
				document.location.href='<?php echo dol_buildpath('/commande/card.php',2).'?id=';?>'+$('#ordercreatedid').val();
			}else{
				document.location.reload(true);
			}
		}
 	})
}


function allowDrop(ev) {
 	ev.preventDefault();
}

function drag(ev) {
	ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
	ev.preventDefault();
	var element = ev.dataTransfer.getData("text");
	var dest = ev.target.className;
	if (ev.target.className.indexOf('cal_event cal_event_busy')!=-1){
		dest = ev.target.parentNode.id;
		ev.target.parentNode.appendChild(document.getElementById(element));
	}
 	if (ev.target.className.indexOf('dropper')!=-1){
		dest = ev.target.id;
		ev.target.appendChild(document.getElementById(element));
 	}
 	$.ajax({
 		method: "POST",
 		url: "dragdrop.php",
 		data: {
 			id_lead: element,
 			new_statut: dest
 		},
 		success: function(msg){
 			if (msg != ""){
 				$('div.fiche ').first().prepend(msg);
 			}
 		},
 		error: function(msg){
 			alert( "erreur: " + msg );
 		}
 	})
}

document.getElementById("export_button").onchange = function(){
	var exp = document.getElementById("export_button").value;

	if (exp=1){
		alert( "erreur: " + exp );
		document.getElementById("export_name").style.display = ""
	} else {
		document.getElementById("export_name").style.display = "none"
	}
}

function visibilite(thingId) {
	var targetElement;
	targetElement = document.getElementById(thingId) ;
	if (targetElement.style.display == "none") {
		targetElement.style.display = "" ;
	} else {
		targetElement.style.display = "none" ;
	}
}


</script>
<?php

dol_fiche_end();
llxFooter();
$db->close();
