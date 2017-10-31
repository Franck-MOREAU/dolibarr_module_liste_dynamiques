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
print '</td>';

print '<td class="fieldrequired"  width="25%">';
print $langs->trans('select_field_button') .': ';
print $form->selectyesno("select_field_button",'1',1);
print '</td>';

print '<td class="fieldrequired"  width="25%">';
print $langs->trans('limite') .': ';
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
llxFooter();
$db->close();
