<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(31, "mci_Cartera", $Language->MenuPhrase("31", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "fa-male");
$RootMenu->AddMenuItem(18, "mi_personas", $Language->MenuPhrase("18", "MenuText"), "personaslist.php?cmd=resetall", 31, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}personas'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(19, "mi_telefonos", $Language->MenuPhrase("19", "MenuText"), "telefonoslist.php?cmd=resetall", 31, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}telefonos'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(32, "mi_direcciones", $Language->MenuPhrase("32", "MenuText"), "direccioneslist.php?cmd=resetall", 31, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}direcciones'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(33, "mi_emails", $Language->MenuPhrase("33", "MenuText"), "emailslist.php?cmd=resetall", 31, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}emails'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(16, "mi_cuentas", $Language->MenuPhrase("16", "MenuText"), "cuentaslist.php", -1, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}cuentas'), FALSE, FALSE, "fa-address-card-o");
$RootMenu->AddMenuItem(14, "mci_Pare1metros", $Language->MenuPhrase("14", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "fa-check-square-o");
$RootMenu->AddMenuItem(17, "mi_ciudades", $Language->MenuPhrase("17", "MenuText"), "ciudadeslist.php", 14, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}ciudades'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(15, "mi_estado_deuda", $Language->MenuPhrase("15", "MenuText"), "estado_deudalist.php", 14, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}estado_deuda'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(9, "mi_tipo_gestion", $Language->MenuPhrase("9", "MenuText"), "tipo_gestionlist.php", 14, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}tipo_gestion'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(10, "mi_tipo_persona", $Language->MenuPhrase("10", "MenuText"), "tipo_personalist.php", 14, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}tipo_persona'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(4, "mi_audittrail", $Language->MenuPhrase("4", "MenuText"), "audittraillist.php", -1, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}audittrail'), FALSE, FALSE, "fa-pencil-square-o");
$RootMenu->AddMenuItem(8, "mci_Seguridad", $Language->MenuPhrase("8", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "fa-lock");
$RootMenu->AddMenuItem(3, "mi_users", $Language->MenuPhrase("3", "MenuText"), "userslist.php", 8, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}users'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(2, "mi_userlevels", $Language->MenuPhrase("2", "MenuText"), "userlevelslist.php", 8, "", IsAdmin(), FALSE, FALSE, "");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
