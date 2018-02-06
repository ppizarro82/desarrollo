<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(4, "mi_audittrail", $Language->MenuPhrase("4", "MenuText"), "audittraillist.php", -1, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}audittrail'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(8, "mci_Seguridad", $Language->MenuPhrase("8", "MenuText"), "", -1, "", TRUE, FALSE, TRUE, "");
$RootMenu->AddMenuItem(3, "mi_users", $Language->MenuPhrase("3", "MenuText"), "userslist.php", 8, "", AllowListMenu('{A36EA07C-DB7F-422A-9088-B007545008C2}users'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(2, "mi_userlevels", $Language->MenuPhrase("2", "MenuText"), "userlevelslist.php", 8, "", IsAdmin(), FALSE, FALSE, "");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
