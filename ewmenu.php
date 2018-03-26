<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(31, "mci_Carpeta_de_Cobro", $Language->MenuPhrase("31", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "fa-male");
$RootMenu->AddMenuItem(38, "mi_deudas", $Language->MenuPhrase("38", "MenuText"), "deudaslist.php?cmd=resetall", 31, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}deudas'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(77, "mci_28229_Base_de_Datos", $Language->MenuPhrase("77", "MenuText"), "", 31, "", TRUE, FALSE, TRUE, "");
$RootMenu->AddMenuItem(18, "mi_personas", $Language->MenuPhrase("18", "MenuText"), "personaslist.php?cmd=resetall", 77, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}personas'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(32, "mi_direcciones", $Language->MenuPhrase("32", "MenuText"), "direccioneslist.php", 77, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}direcciones'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(19, "mi_telefonos", $Language->MenuPhrase("19", "MenuText"), "telefonoslist.php", 77, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}telefonos'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(34, "mi_bienes", $Language->MenuPhrase("34", "MenuText"), "bieneslist.php", 77, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}bienes'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(33, "mi_emails", $Language->MenuPhrase("33", "MenuText"), "emailslist.php", 77, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}emails'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(78, "mi_analisis_php", $Language->MenuPhrase("78", "MenuText"), "analisis.php", -1, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}analisis.php'), FALSE, TRUE, "fa-area-chart");
$RootMenu->AddMenuItem(56, "mci_Seguimientos", $Language->MenuPhrase("56", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "");
$RootMenu->AddMenuItem(14, "mci_Pare1metros", $Language->MenuPhrase("14", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "fa-check-square-o");
$RootMenu->AddMenuItem(16, "mi_cuentas", $Language->MenuPhrase("16", "MenuText"), "cuentaslist.php", 14, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}cuentas'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(15, "mi_estado_deuda", $Language->MenuPhrase("15", "MenuText"), "estado_deudalist.php", 14, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}estado_deuda'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(57, "mi_fuentes", $Language->MenuPhrase("57", "MenuText"), "fuenteslist.php", 14, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}fuentes'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(58, "mi_gestiones", $Language->MenuPhrase("58", "MenuText"), "gestioneslist.php", 14, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}gestiones'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(62, "mi_tipo_bien", $Language->MenuPhrase("62", "MenuText"), "tipo_bienlist.php", 14, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}tipo_bien'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(63, "mi_tipo_direccion", $Language->MenuPhrase("63", "MenuText"), "tipo_direccionlist.php", 14, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}tipo_direccion'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(9, "mi_tipo_gestion", $Language->MenuPhrase("9", "MenuText"), "tipo_gestionlist.php", 14, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}tipo_gestion'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(10, "mi_tipo_persona", $Language->MenuPhrase("10", "MenuText"), "tipo_personalist.php", 14, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}tipo_persona'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(8, "mci_Seguridad", $Language->MenuPhrase("8", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "fa-lock");
$RootMenu->AddMenuItem(3, "mi_users", $Language->MenuPhrase("3", "MenuText"), "userslist.php", 8, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}users'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(2, "mi_userlevels", $Language->MenuPhrase("2", "MenuText"), "userlevelslist.php", 8, "", IsAdmin(), FALSE, FALSE, "");
$RootMenu->AddMenuItem(4, "mi_audittrail", $Language->MenuPhrase("4", "MenuText"), "audittraillist.php", -1, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}audittrail'), FALSE, FALSE, "fa-pencil-square-o");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
