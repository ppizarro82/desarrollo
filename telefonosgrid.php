<?php include_once "usersinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($telefonos_grid)) $telefonos_grid = new ctelefonos_grid();

// Page init
$telefonos_grid->Page_Init();

// Page main
$telefonos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$telefonos_grid->Page_Render();
?>
<?php if ($telefonos->Export == "") { ?>
<script type="text/javascript">

// Form object
var ftelefonosgrid = new ew_Form("ftelefonosgrid", "grid");
ftelefonosgrid.FormKeyCountName = '<?php echo $telefonos_grid->FormKeyCountName ?>';

// Validate form
ftelefonosgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_id_persona");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $telefonos->id_persona->FldCaption(), $telefonos->id_persona->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_telefono1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $telefonos->telefono1->FldCaption(), $telefonos->telefono1->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ftelefonosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_persona", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_fuente", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_gestion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tipo_documento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "no_documento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nombres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "paterno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "materno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "telefono1", false)) return false;
	if (ew_ValueChanged(fobj, infix, "telefono2", false)) return false;
	if (ew_ValueChanged(fobj, infix, "telefono3", false)) return false;
	if (ew_ValueChanged(fobj, infix, "telefono4", false)) return false;
	return true;
}

// Form_CustomValidate event
ftelefonosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
ftelefonosgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
ftelefonosgrid.Lists["x_id_persona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_paterno","x_materno","x_nombres",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
ftelefonosgrid.Lists["x_id_persona"].Data = "<?php echo $telefonos_grid->id_persona->LookupFilterQuery(FALSE, "grid") ?>";
ftelefonosgrid.Lists["x_id_fuente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"fuentes"};
ftelefonosgrid.Lists["x_id_fuente"].Data = "<?php echo $telefonos_grid->id_fuente->LookupFilterQuery(FALSE, "grid") ?>";
ftelefonosgrid.Lists["x_id_gestion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gestiones"};
ftelefonosgrid.Lists["x_id_gestion"].Data = "<?php echo $telefonos_grid->id_gestion->LookupFilterQuery(FALSE, "grid") ?>";

// Form object for search
</script>
<?php } ?>
<?php
if ($telefonos->CurrentAction == "gridadd") {
	if ($telefonos->CurrentMode == "copy") {
		$bSelectLimit = $telefonos_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$telefonos_grid->TotalRecs = $telefonos->ListRecordCount();
			$telefonos_grid->Recordset = $telefonos_grid->LoadRecordset($telefonos_grid->StartRec-1, $telefonos_grid->DisplayRecs);
		} else {
			if ($telefonos_grid->Recordset = $telefonos_grid->LoadRecordset())
				$telefonos_grid->TotalRecs = $telefonos_grid->Recordset->RecordCount();
		}
		$telefonos_grid->StartRec = 1;
		$telefonos_grid->DisplayRecs = $telefonos_grid->TotalRecs;
	} else {
		$telefonos->CurrentFilter = "0=1";
		$telefonos_grid->StartRec = 1;
		$telefonos_grid->DisplayRecs = $telefonos->GridAddRowCount;
	}
	$telefonos_grid->TotalRecs = $telefonos_grid->DisplayRecs;
	$telefonos_grid->StopRec = $telefonos_grid->DisplayRecs;
} else {
	$bSelectLimit = $telefonos_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($telefonos_grid->TotalRecs <= 0)
			$telefonos_grid->TotalRecs = $telefonos->ListRecordCount();
	} else {
		if (!$telefonos_grid->Recordset && ($telefonos_grid->Recordset = $telefonos_grid->LoadRecordset()))
			$telefonos_grid->TotalRecs = $telefonos_grid->Recordset->RecordCount();
	}
	$telefonos_grid->StartRec = 1;
	$telefonos_grid->DisplayRecs = $telefonos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$telefonos_grid->Recordset = $telefonos_grid->LoadRecordset($telefonos_grid->StartRec-1, $telefonos_grid->DisplayRecs);

	// Set no record found message
	if ($telefonos->CurrentAction == "" && $telefonos_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$telefonos_grid->setWarningMessage(ew_DeniedMsg());
		if ($telefonos_grid->SearchWhere == "0=101")
			$telefonos_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$telefonos_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$telefonos_grid->RenderOtherOptions();
?>
<?php $telefonos_grid->ShowPageHeader(); ?>
<?php
$telefonos_grid->ShowMessage();
?>
<?php if ($telefonos_grid->TotalRecs > 0 || $telefonos->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($telefonos_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> telefonos">
<div id="ftelefonosgrid" class="ewForm ewListForm form-inline">
<?php if ($telefonos_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($telefonos_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_telefonos" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_telefonosgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$telefonos_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$telefonos_grid->RenderListOptions();

// Render list options (header, left)
$telefonos_grid->ListOptions->Render("header", "left");
?>
<?php if ($telefonos->Id->Visible) { // Id ?>
	<?php if ($telefonos->SortUrl($telefonos->Id) == "") { ?>
		<th data-name="Id" class="<?php echo $telefonos->Id->HeaderCellClass() ?>"><div id="elh_telefonos_Id" class="telefonos_Id"><div class="ewTableHeaderCaption"><?php echo $telefonos->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id" class="<?php echo $telefonos->Id->HeaderCellClass() ?>"><div><div id="elh_telefonos_Id" class="telefonos_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->id_persona->Visible) { // id_persona ?>
	<?php if ($telefonos->SortUrl($telefonos->id_persona) == "") { ?>
		<th data-name="id_persona" class="<?php echo $telefonos->id_persona->HeaderCellClass() ?>"><div id="elh_telefonos_id_persona" class="telefonos_id_persona"><div class="ewTableHeaderCaption"><?php echo $telefonos->id_persona->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_persona" class="<?php echo $telefonos->id_persona->HeaderCellClass() ?>"><div><div id="elh_telefonos_id_persona" class="telefonos_id_persona">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->id_persona->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->id_persona->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->id_persona->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->id_fuente->Visible) { // id_fuente ?>
	<?php if ($telefonos->SortUrl($telefonos->id_fuente) == "") { ?>
		<th data-name="id_fuente" class="<?php echo $telefonos->id_fuente->HeaderCellClass() ?>"><div id="elh_telefonos_id_fuente" class="telefonos_id_fuente"><div class="ewTableHeaderCaption"><?php echo $telefonos->id_fuente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_fuente" class="<?php echo $telefonos->id_fuente->HeaderCellClass() ?>"><div><div id="elh_telefonos_id_fuente" class="telefonos_id_fuente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->id_fuente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->id_fuente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->id_fuente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->id_gestion->Visible) { // id_gestion ?>
	<?php if ($telefonos->SortUrl($telefonos->id_gestion) == "") { ?>
		<th data-name="id_gestion" class="<?php echo $telefonos->id_gestion->HeaderCellClass() ?>"><div id="elh_telefonos_id_gestion" class="telefonos_id_gestion"><div class="ewTableHeaderCaption"><?php echo $telefonos->id_gestion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_gestion" class="<?php echo $telefonos->id_gestion->HeaderCellClass() ?>"><div><div id="elh_telefonos_id_gestion" class="telefonos_id_gestion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->id_gestion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->id_gestion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->id_gestion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->tipo_documento->Visible) { // tipo_documento ?>
	<?php if ($telefonos->SortUrl($telefonos->tipo_documento) == "") { ?>
		<th data-name="tipo_documento" class="<?php echo $telefonos->tipo_documento->HeaderCellClass() ?>"><div id="elh_telefonos_tipo_documento" class="telefonos_tipo_documento"><div class="ewTableHeaderCaption"><?php echo $telefonos->tipo_documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo_documento" class="<?php echo $telefonos->tipo_documento->HeaderCellClass() ?>"><div><div id="elh_telefonos_tipo_documento" class="telefonos_tipo_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->tipo_documento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->tipo_documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->tipo_documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->no_documento->Visible) { // no_documento ?>
	<?php if ($telefonos->SortUrl($telefonos->no_documento) == "") { ?>
		<th data-name="no_documento" class="<?php echo $telefonos->no_documento->HeaderCellClass() ?>"><div id="elh_telefonos_no_documento" class="telefonos_no_documento"><div class="ewTableHeaderCaption"><?php echo $telefonos->no_documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="no_documento" class="<?php echo $telefonos->no_documento->HeaderCellClass() ?>"><div><div id="elh_telefonos_no_documento" class="telefonos_no_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->no_documento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->no_documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->no_documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->nombres->Visible) { // nombres ?>
	<?php if ($telefonos->SortUrl($telefonos->nombres) == "") { ?>
		<th data-name="nombres" class="<?php echo $telefonos->nombres->HeaderCellClass() ?>"><div id="elh_telefonos_nombres" class="telefonos_nombres"><div class="ewTableHeaderCaption"><?php echo $telefonos->nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombres" class="<?php echo $telefonos->nombres->HeaderCellClass() ?>"><div><div id="elh_telefonos_nombres" class="telefonos_nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->nombres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->paterno->Visible) { // paterno ?>
	<?php if ($telefonos->SortUrl($telefonos->paterno) == "") { ?>
		<th data-name="paterno" class="<?php echo $telefonos->paterno->HeaderCellClass() ?>"><div id="elh_telefonos_paterno" class="telefonos_paterno"><div class="ewTableHeaderCaption"><?php echo $telefonos->paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="paterno" class="<?php echo $telefonos->paterno->HeaderCellClass() ?>"><div><div id="elh_telefonos_paterno" class="telefonos_paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->paterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->materno->Visible) { // materno ?>
	<?php if ($telefonos->SortUrl($telefonos->materno) == "") { ?>
		<th data-name="materno" class="<?php echo $telefonos->materno->HeaderCellClass() ?>"><div id="elh_telefonos_materno" class="telefonos_materno"><div class="ewTableHeaderCaption"><?php echo $telefonos->materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="materno" class="<?php echo $telefonos->materno->HeaderCellClass() ?>"><div><div id="elh_telefonos_materno" class="telefonos_materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->materno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->telefono1->Visible) { // telefono1 ?>
	<?php if ($telefonos->SortUrl($telefonos->telefono1) == "") { ?>
		<th data-name="telefono1" class="<?php echo $telefonos->telefono1->HeaderCellClass() ?>"><div id="elh_telefonos_telefono1" class="telefonos_telefono1"><div class="ewTableHeaderCaption"><?php echo $telefonos->telefono1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono1" class="<?php echo $telefonos->telefono1->HeaderCellClass() ?>"><div><div id="elh_telefonos_telefono1" class="telefonos_telefono1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->telefono1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->telefono1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->telefono1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->telefono2->Visible) { // telefono2 ?>
	<?php if ($telefonos->SortUrl($telefonos->telefono2) == "") { ?>
		<th data-name="telefono2" class="<?php echo $telefonos->telefono2->HeaderCellClass() ?>"><div id="elh_telefonos_telefono2" class="telefonos_telefono2"><div class="ewTableHeaderCaption"><?php echo $telefonos->telefono2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono2" class="<?php echo $telefonos->telefono2->HeaderCellClass() ?>"><div><div id="elh_telefonos_telefono2" class="telefonos_telefono2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->telefono2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->telefono2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->telefono2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->telefono3->Visible) { // telefono3 ?>
	<?php if ($telefonos->SortUrl($telefonos->telefono3) == "") { ?>
		<th data-name="telefono3" class="<?php echo $telefonos->telefono3->HeaderCellClass() ?>"><div id="elh_telefonos_telefono3" class="telefonos_telefono3"><div class="ewTableHeaderCaption"><?php echo $telefonos->telefono3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono3" class="<?php echo $telefonos->telefono3->HeaderCellClass() ?>"><div><div id="elh_telefonos_telefono3" class="telefonos_telefono3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->telefono3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->telefono3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->telefono3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->telefono4->Visible) { // telefono4 ?>
	<?php if ($telefonos->SortUrl($telefonos->telefono4) == "") { ?>
		<th data-name="telefono4" class="<?php echo $telefonos->telefono4->HeaderCellClass() ?>"><div id="elh_telefonos_telefono4" class="telefonos_telefono4"><div class="ewTableHeaderCaption"><?php echo $telefonos->telefono4->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telefono4" class="<?php echo $telefonos->telefono4->HeaderCellClass() ?>"><div><div id="elh_telefonos_telefono4" class="telefonos_telefono4">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->telefono4->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->telefono4->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->telefono4->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$telefonos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$telefonos_grid->StartRec = 1;
$telefonos_grid->StopRec = $telefonos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($telefonos_grid->FormKeyCountName) && ($telefonos->CurrentAction == "gridadd" || $telefonos->CurrentAction == "gridedit" || $telefonos->CurrentAction == "F")) {
		$telefonos_grid->KeyCount = $objForm->GetValue($telefonos_grid->FormKeyCountName);
		$telefonos_grid->StopRec = $telefonos_grid->StartRec + $telefonos_grid->KeyCount - 1;
	}
}
$telefonos_grid->RecCnt = $telefonos_grid->StartRec - 1;
if ($telefonos_grid->Recordset && !$telefonos_grid->Recordset->EOF) {
	$telefonos_grid->Recordset->MoveFirst();
	$bSelectLimit = $telefonos_grid->UseSelectLimit;
	if (!$bSelectLimit && $telefonos_grid->StartRec > 1)
		$telefonos_grid->Recordset->Move($telefonos_grid->StartRec - 1);
} elseif (!$telefonos->AllowAddDeleteRow && $telefonos_grid->StopRec == 0) {
	$telefonos_grid->StopRec = $telefonos->GridAddRowCount;
}

// Initialize aggregate
$telefonos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$telefonos->ResetAttrs();
$telefonos_grid->RenderRow();
if ($telefonos->CurrentAction == "gridadd")
	$telefonos_grid->RowIndex = 0;
if ($telefonos->CurrentAction == "gridedit")
	$telefonos_grid->RowIndex = 0;
while ($telefonos_grid->RecCnt < $telefonos_grid->StopRec) {
	$telefonos_grid->RecCnt++;
	if (intval($telefonos_grid->RecCnt) >= intval($telefonos_grid->StartRec)) {
		$telefonos_grid->RowCnt++;
		if ($telefonos->CurrentAction == "gridadd" || $telefonos->CurrentAction == "gridedit" || $telefonos->CurrentAction == "F") {
			$telefonos_grid->RowIndex++;
			$objForm->Index = $telefonos_grid->RowIndex;
			if ($objForm->HasValue($telefonos_grid->FormActionName))
				$telefonos_grid->RowAction = strval($objForm->GetValue($telefonos_grid->FormActionName));
			elseif ($telefonos->CurrentAction == "gridadd")
				$telefonos_grid->RowAction = "insert";
			else
				$telefonos_grid->RowAction = "";
		}

		// Set up key count
		$telefonos_grid->KeyCount = $telefonos_grid->RowIndex;

		// Init row class and style
		$telefonos->ResetAttrs();
		$telefonos->CssClass = "";
		if ($telefonos->CurrentAction == "gridadd") {
			if ($telefonos->CurrentMode == "copy") {
				$telefonos_grid->LoadRowValues($telefonos_grid->Recordset); // Load row values
				$telefonos_grid->SetRecordKey($telefonos_grid->RowOldKey, $telefonos_grid->Recordset); // Set old record key
			} else {
				$telefonos_grid->LoadRowValues(); // Load default values
				$telefonos_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$telefonos_grid->LoadRowValues($telefonos_grid->Recordset); // Load row values
		}
		$telefonos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($telefonos->CurrentAction == "gridadd") // Grid add
			$telefonos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($telefonos->CurrentAction == "gridadd" && $telefonos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$telefonos_grid->RestoreCurrentRowFormValues($telefonos_grid->RowIndex); // Restore form values
		if ($telefonos->CurrentAction == "gridedit") { // Grid edit
			if ($telefonos->EventCancelled) {
				$telefonos_grid->RestoreCurrentRowFormValues($telefonos_grid->RowIndex); // Restore form values
			}
			if ($telefonos_grid->RowAction == "insert")
				$telefonos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$telefonos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($telefonos->CurrentAction == "gridedit" && ($telefonos->RowType == EW_ROWTYPE_EDIT || $telefonos->RowType == EW_ROWTYPE_ADD) && $telefonos->EventCancelled) // Update failed
			$telefonos_grid->RestoreCurrentRowFormValues($telefonos_grid->RowIndex); // Restore form values
		if ($telefonos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$telefonos_grid->EditRowCnt++;
		if ($telefonos->CurrentAction == "F") // Confirm row
			$telefonos_grid->RestoreCurrentRowFormValues($telefonos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$telefonos->RowAttrs = array_merge($telefonos->RowAttrs, array('data-rowindex'=>$telefonos_grid->RowCnt, 'id'=>'r' . $telefonos_grid->RowCnt . '_telefonos', 'data-rowtype'=>$telefonos->RowType));

		// Render row
		$telefonos_grid->RenderRow();

		// Render list options
		$telefonos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($telefonos_grid->RowAction <> "delete" && $telefonos_grid->RowAction <> "insertdelete" && !($telefonos_grid->RowAction == "insert" && $telefonos->CurrentAction == "F" && $telefonos_grid->EmptyRow())) {
?>
	<tr<?php echo $telefonos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$telefonos_grid->ListOptions->Render("body", "left", $telefonos_grid->RowCnt);
?>
	<?php if ($telefonos->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $telefonos->Id->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="telefonos" data-field="x_Id" name="o<?php echo $telefonos_grid->RowIndex ?>_Id" id="o<?php echo $telefonos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($telefonos->Id->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_Id" class="form-group telefonos_Id">
<span<?php echo $telefonos->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_Id" name="x<?php echo $telefonos_grid->RowIndex ?>_Id" id="x<?php echo $telefonos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($telefonos->Id->CurrentValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_Id" class="telefonos_Id">
<span<?php echo $telefonos->Id->ViewAttributes() ?>>
<?php echo $telefonos->Id->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_Id" name="x<?php echo $telefonos_grid->RowIndex ?>_Id" id="x<?php echo $telefonos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($telefonos->Id->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_Id" name="o<?php echo $telefonos_grid->RowIndex ?>_Id" id="o<?php echo $telefonos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($telefonos->Id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_Id" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_Id" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($telefonos->Id->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_Id" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_Id" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($telefonos->Id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->id_persona->Visible) { // id_persona ?>
		<td data-name="id_persona"<?php echo $telefonos->id_persona->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($telefonos->id_persona->getSessionValue() <> "") { ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_id_persona" class="form-group telefonos_id_persona">
<span<?php echo $telefonos->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($telefonos->id_persona->ViewValue)) && $telefonos->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $telefonos->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $telefonos->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $telefonos->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" name="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($telefonos->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_id_persona" class="form-group telefonos_id_persona">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $telefonos_grid->RowIndex ?>_id_persona"><?php echo (strval($telefonos->id_persona->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $telefonos->id_persona->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($telefonos->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $telefonos_grid->RowIndex ?>_id_persona',m:0,n:30});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="telefonos" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $telefonos->id_persona->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" id="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" value="<?php echo $telefonos->id_persona->CurrentValue ?>"<?php echo $telefonos->id_persona->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_id_persona" name="o<?php echo $telefonos_grid->RowIndex ?>_id_persona" id="o<?php echo $telefonos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($telefonos->id_persona->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($telefonos->id_persona->getSessionValue() <> "") { ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_id_persona" class="form-group telefonos_id_persona">
<span<?php echo $telefonos->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($telefonos->id_persona->ViewValue)) && $telefonos->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $telefonos->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $telefonos->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $telefonos->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" name="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($telefonos->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_id_persona" class="form-group telefonos_id_persona">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $telefonos_grid->RowIndex ?>_id_persona"><?php echo (strval($telefonos->id_persona->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $telefonos->id_persona->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($telefonos->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $telefonos_grid->RowIndex ?>_id_persona',m:0,n:30});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="telefonos" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $telefonos->id_persona->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" id="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" value="<?php echo $telefonos->id_persona->CurrentValue ?>"<?php echo $telefonos->id_persona->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_id_persona" class="telefonos_id_persona">
<span<?php echo $telefonos->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($telefonos->id_persona->ListViewValue())) && $telefonos->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $telefonos->id_persona->LinkAttributes() ?>><?php echo $telefonos->id_persona->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $telefonos->id_persona->ListViewValue() ?>
<?php } ?>
</span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_id_persona" name="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" id="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($telefonos->id_persona->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_id_persona" name="o<?php echo $telefonos_grid->RowIndex ?>_id_persona" id="o<?php echo $telefonos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($telefonos->id_persona->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_id_persona" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_id_persona" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($telefonos->id_persona->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_id_persona" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_id_persona" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($telefonos->id_persona->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->id_fuente->Visible) { // id_fuente ?>
		<td data-name="id_fuente"<?php echo $telefonos->id_fuente->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_id_fuente" class="form-group telefonos_id_fuente">
<select data-table="telefonos" data-field="x_id_fuente" data-value-separator="<?php echo $telefonos->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $telefonos_grid->RowIndex ?>_id_fuente" name="x<?php echo $telefonos_grid->RowIndex ?>_id_fuente"<?php echo $telefonos->id_fuente->EditAttributes() ?>>
<?php echo $telefonos->id_fuente->SelectOptionListHtml("x<?php echo $telefonos_grid->RowIndex ?>_id_fuente") ?>
</select>
</span>
<input type="hidden" data-table="telefonos" data-field="x_id_fuente" name="o<?php echo $telefonos_grid->RowIndex ?>_id_fuente" id="o<?php echo $telefonos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($telefonos->id_fuente->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_id_fuente" class="form-group telefonos_id_fuente">
<select data-table="telefonos" data-field="x_id_fuente" data-value-separator="<?php echo $telefonos->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $telefonos_grid->RowIndex ?>_id_fuente" name="x<?php echo $telefonos_grid->RowIndex ?>_id_fuente"<?php echo $telefonos->id_fuente->EditAttributes() ?>>
<?php echo $telefonos->id_fuente->SelectOptionListHtml("x<?php echo $telefonos_grid->RowIndex ?>_id_fuente") ?>
</select>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_id_fuente" class="telefonos_id_fuente">
<span<?php echo $telefonos->id_fuente->ViewAttributes() ?>>
<?php echo $telefonos->id_fuente->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_id_fuente" name="x<?php echo $telefonos_grid->RowIndex ?>_id_fuente" id="x<?php echo $telefonos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($telefonos->id_fuente->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_id_fuente" name="o<?php echo $telefonos_grid->RowIndex ?>_id_fuente" id="o<?php echo $telefonos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($telefonos->id_fuente->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_id_fuente" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_id_fuente" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($telefonos->id_fuente->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_id_fuente" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_id_fuente" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($telefonos->id_fuente->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->id_gestion->Visible) { // id_gestion ?>
		<td data-name="id_gestion"<?php echo $telefonos->id_gestion->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_id_gestion" class="form-group telefonos_id_gestion">
<select data-table="telefonos" data-field="x_id_gestion" data-value-separator="<?php echo $telefonos->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $telefonos_grid->RowIndex ?>_id_gestion" name="x<?php echo $telefonos_grid->RowIndex ?>_id_gestion"<?php echo $telefonos->id_gestion->EditAttributes() ?>>
<?php echo $telefonos->id_gestion->SelectOptionListHtml("x<?php echo $telefonos_grid->RowIndex ?>_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$telefonos->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $telefonos->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $telefonos_grid->RowIndex ?>_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $telefonos_grid->RowIndex ?>_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $telefonos->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<input type="hidden" data-table="telefonos" data-field="x_id_gestion" name="o<?php echo $telefonos_grid->RowIndex ?>_id_gestion" id="o<?php echo $telefonos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($telefonos->id_gestion->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_id_gestion" class="form-group telefonos_id_gestion">
<select data-table="telefonos" data-field="x_id_gestion" data-value-separator="<?php echo $telefonos->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $telefonos_grid->RowIndex ?>_id_gestion" name="x<?php echo $telefonos_grid->RowIndex ?>_id_gestion"<?php echo $telefonos->id_gestion->EditAttributes() ?>>
<?php echo $telefonos->id_gestion->SelectOptionListHtml("x<?php echo $telefonos_grid->RowIndex ?>_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$telefonos->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $telefonos->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $telefonos_grid->RowIndex ?>_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $telefonos_grid->RowIndex ?>_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $telefonos->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_id_gestion" class="telefonos_id_gestion">
<span<?php echo $telefonos->id_gestion->ViewAttributes() ?>>
<?php echo $telefonos->id_gestion->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_id_gestion" name="x<?php echo $telefonos_grid->RowIndex ?>_id_gestion" id="x<?php echo $telefonos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($telefonos->id_gestion->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_id_gestion" name="o<?php echo $telefonos_grid->RowIndex ?>_id_gestion" id="o<?php echo $telefonos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($telefonos->id_gestion->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_id_gestion" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_id_gestion" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($telefonos->id_gestion->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_id_gestion" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_id_gestion" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($telefonos->id_gestion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->tipo_documento->Visible) { // tipo_documento ?>
		<td data-name="tipo_documento"<?php echo $telefonos->tipo_documento->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_tipo_documento" class="form-group telefonos_tipo_documento">
<input type="text" data-table="telefonos" data-field="x_tipo_documento" name="x<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" id="x<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->tipo_documento->getPlaceHolder()) ?>" value="<?php echo $telefonos->tipo_documento->EditValue ?>"<?php echo $telefonos->tipo_documento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="telefonos" data-field="x_tipo_documento" name="o<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" id="o<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($telefonos->tipo_documento->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_tipo_documento" class="form-group telefonos_tipo_documento">
<input type="text" data-table="telefonos" data-field="x_tipo_documento" name="x<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" id="x<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->tipo_documento->getPlaceHolder()) ?>" value="<?php echo $telefonos->tipo_documento->EditValue ?>"<?php echo $telefonos->tipo_documento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_tipo_documento" class="telefonos_tipo_documento">
<span<?php echo $telefonos->tipo_documento->ViewAttributes() ?>>
<?php echo $telefonos->tipo_documento->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_tipo_documento" name="x<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" id="x<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($telefonos->tipo_documento->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_tipo_documento" name="o<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" id="o<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($telefonos->tipo_documento->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_tipo_documento" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($telefonos->tipo_documento->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_tipo_documento" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($telefonos->tipo_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->no_documento->Visible) { // no_documento ?>
		<td data-name="no_documento"<?php echo $telefonos->no_documento->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_no_documento" class="form-group telefonos_no_documento">
<input type="text" data-table="telefonos" data-field="x_no_documento" name="x<?php echo $telefonos_grid->RowIndex ?>_no_documento" id="x<?php echo $telefonos_grid->RowIndex ?>_no_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->no_documento->getPlaceHolder()) ?>" value="<?php echo $telefonos->no_documento->EditValue ?>"<?php echo $telefonos->no_documento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="telefonos" data-field="x_no_documento" name="o<?php echo $telefonos_grid->RowIndex ?>_no_documento" id="o<?php echo $telefonos_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($telefonos->no_documento->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_no_documento" class="form-group telefonos_no_documento">
<input type="text" data-table="telefonos" data-field="x_no_documento" name="x<?php echo $telefonos_grid->RowIndex ?>_no_documento" id="x<?php echo $telefonos_grid->RowIndex ?>_no_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->no_documento->getPlaceHolder()) ?>" value="<?php echo $telefonos->no_documento->EditValue ?>"<?php echo $telefonos->no_documento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_no_documento" class="telefonos_no_documento">
<span<?php echo $telefonos->no_documento->ViewAttributes() ?>>
<?php echo $telefonos->no_documento->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_no_documento" name="x<?php echo $telefonos_grid->RowIndex ?>_no_documento" id="x<?php echo $telefonos_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($telefonos->no_documento->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_no_documento" name="o<?php echo $telefonos_grid->RowIndex ?>_no_documento" id="o<?php echo $telefonos_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($telefonos->no_documento->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_no_documento" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_no_documento" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($telefonos->no_documento->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_no_documento" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_no_documento" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($telefonos->no_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->nombres->Visible) { // nombres ?>
		<td data-name="nombres"<?php echo $telefonos->nombres->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_nombres" class="form-group telefonos_nombres">
<input type="text" data-table="telefonos" data-field="x_nombres" name="x<?php echo $telefonos_grid->RowIndex ?>_nombres" id="x<?php echo $telefonos_grid->RowIndex ?>_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->nombres->getPlaceHolder()) ?>" value="<?php echo $telefonos->nombres->EditValue ?>"<?php echo $telefonos->nombres->EditAttributes() ?>>
</span>
<input type="hidden" data-table="telefonos" data-field="x_nombres" name="o<?php echo $telefonos_grid->RowIndex ?>_nombres" id="o<?php echo $telefonos_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($telefonos->nombres->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_nombres" class="form-group telefonos_nombres">
<input type="text" data-table="telefonos" data-field="x_nombres" name="x<?php echo $telefonos_grid->RowIndex ?>_nombres" id="x<?php echo $telefonos_grid->RowIndex ?>_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->nombres->getPlaceHolder()) ?>" value="<?php echo $telefonos->nombres->EditValue ?>"<?php echo $telefonos->nombres->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_nombres" class="telefonos_nombres">
<span<?php echo $telefonos->nombres->ViewAttributes() ?>>
<?php echo $telefonos->nombres->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_nombres" name="x<?php echo $telefonos_grid->RowIndex ?>_nombres" id="x<?php echo $telefonos_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($telefonos->nombres->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_nombres" name="o<?php echo $telefonos_grid->RowIndex ?>_nombres" id="o<?php echo $telefonos_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($telefonos->nombres->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_nombres" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_nombres" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($telefonos->nombres->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_nombres" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_nombres" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($telefonos->nombres->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->paterno->Visible) { // paterno ?>
		<td data-name="paterno"<?php echo $telefonos->paterno->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_paterno" class="form-group telefonos_paterno">
<input type="text" data-table="telefonos" data-field="x_paterno" name="x<?php echo $telefonos_grid->RowIndex ?>_paterno" id="x<?php echo $telefonos_grid->RowIndex ?>_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->paterno->getPlaceHolder()) ?>" value="<?php echo $telefonos->paterno->EditValue ?>"<?php echo $telefonos->paterno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="telefonos" data-field="x_paterno" name="o<?php echo $telefonos_grid->RowIndex ?>_paterno" id="o<?php echo $telefonos_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($telefonos->paterno->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_paterno" class="form-group telefonos_paterno">
<input type="text" data-table="telefonos" data-field="x_paterno" name="x<?php echo $telefonos_grid->RowIndex ?>_paterno" id="x<?php echo $telefonos_grid->RowIndex ?>_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->paterno->getPlaceHolder()) ?>" value="<?php echo $telefonos->paterno->EditValue ?>"<?php echo $telefonos->paterno->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_paterno" class="telefonos_paterno">
<span<?php echo $telefonos->paterno->ViewAttributes() ?>>
<?php echo $telefonos->paterno->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_paterno" name="x<?php echo $telefonos_grid->RowIndex ?>_paterno" id="x<?php echo $telefonos_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($telefonos->paterno->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_paterno" name="o<?php echo $telefonos_grid->RowIndex ?>_paterno" id="o<?php echo $telefonos_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($telefonos->paterno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_paterno" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_paterno" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($telefonos->paterno->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_paterno" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_paterno" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($telefonos->paterno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->materno->Visible) { // materno ?>
		<td data-name="materno"<?php echo $telefonos->materno->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_materno" class="form-group telefonos_materno">
<input type="text" data-table="telefonos" data-field="x_materno" name="x<?php echo $telefonos_grid->RowIndex ?>_materno" id="x<?php echo $telefonos_grid->RowIndex ?>_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->materno->getPlaceHolder()) ?>" value="<?php echo $telefonos->materno->EditValue ?>"<?php echo $telefonos->materno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="telefonos" data-field="x_materno" name="o<?php echo $telefonos_grid->RowIndex ?>_materno" id="o<?php echo $telefonos_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($telefonos->materno->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_materno" class="form-group telefonos_materno">
<input type="text" data-table="telefonos" data-field="x_materno" name="x<?php echo $telefonos_grid->RowIndex ?>_materno" id="x<?php echo $telefonos_grid->RowIndex ?>_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->materno->getPlaceHolder()) ?>" value="<?php echo $telefonos->materno->EditValue ?>"<?php echo $telefonos->materno->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_materno" class="telefonos_materno">
<span<?php echo $telefonos->materno->ViewAttributes() ?>>
<?php echo $telefonos->materno->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_materno" name="x<?php echo $telefonos_grid->RowIndex ?>_materno" id="x<?php echo $telefonos_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($telefonos->materno->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_materno" name="o<?php echo $telefonos_grid->RowIndex ?>_materno" id="o<?php echo $telefonos_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($telefonos->materno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_materno" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_materno" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($telefonos->materno->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_materno" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_materno" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($telefonos->materno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->telefono1->Visible) { // telefono1 ?>
		<td data-name="telefono1"<?php echo $telefonos->telefono1->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_telefono1" class="form-group telefonos_telefono1">
<input type="text" data-table="telefonos" data-field="x_telefono1" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono1" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono1" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono1->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono1->EditValue ?>"<?php echo $telefonos->telefono1->EditAttributes() ?>>
</span>
<input type="hidden" data-table="telefonos" data-field="x_telefono1" name="o<?php echo $telefonos_grid->RowIndex ?>_telefono1" id="o<?php echo $telefonos_grid->RowIndex ?>_telefono1" value="<?php echo ew_HtmlEncode($telefonos->telefono1->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_telefono1" class="form-group telefonos_telefono1">
<input type="text" data-table="telefonos" data-field="x_telefono1" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono1" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono1" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono1->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono1->EditValue ?>"<?php echo $telefonos->telefono1->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_telefono1" class="telefonos_telefono1">
<span<?php echo $telefonos->telefono1->ViewAttributes() ?>>
<?php echo $telefonos->telefono1->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_telefono1" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono1" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono1" value="<?php echo ew_HtmlEncode($telefonos->telefono1->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_telefono1" name="o<?php echo $telefonos_grid->RowIndex ?>_telefono1" id="o<?php echo $telefonos_grid->RowIndex ?>_telefono1" value="<?php echo ew_HtmlEncode($telefonos->telefono1->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_telefono1" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_telefono1" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_telefono1" value="<?php echo ew_HtmlEncode($telefonos->telefono1->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_telefono1" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_telefono1" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_telefono1" value="<?php echo ew_HtmlEncode($telefonos->telefono1->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->telefono2->Visible) { // telefono2 ?>
		<td data-name="telefono2"<?php echo $telefonos->telefono2->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_telefono2" class="form-group telefonos_telefono2">
<input type="text" data-table="telefonos" data-field="x_telefono2" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono2" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono2" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono2->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono2->EditValue ?>"<?php echo $telefonos->telefono2->EditAttributes() ?>>
</span>
<input type="hidden" data-table="telefonos" data-field="x_telefono2" name="o<?php echo $telefonos_grid->RowIndex ?>_telefono2" id="o<?php echo $telefonos_grid->RowIndex ?>_telefono2" value="<?php echo ew_HtmlEncode($telefonos->telefono2->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_telefono2" class="form-group telefonos_telefono2">
<input type="text" data-table="telefonos" data-field="x_telefono2" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono2" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono2" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono2->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono2->EditValue ?>"<?php echo $telefonos->telefono2->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_telefono2" class="telefonos_telefono2">
<span<?php echo $telefonos->telefono2->ViewAttributes() ?>>
<?php echo $telefonos->telefono2->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_telefono2" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono2" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono2" value="<?php echo ew_HtmlEncode($telefonos->telefono2->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_telefono2" name="o<?php echo $telefonos_grid->RowIndex ?>_telefono2" id="o<?php echo $telefonos_grid->RowIndex ?>_telefono2" value="<?php echo ew_HtmlEncode($telefonos->telefono2->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_telefono2" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_telefono2" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_telefono2" value="<?php echo ew_HtmlEncode($telefonos->telefono2->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_telefono2" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_telefono2" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_telefono2" value="<?php echo ew_HtmlEncode($telefonos->telefono2->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->telefono3->Visible) { // telefono3 ?>
		<td data-name="telefono3"<?php echo $telefonos->telefono3->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_telefono3" class="form-group telefonos_telefono3">
<input type="text" data-table="telefonos" data-field="x_telefono3" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono3" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono3" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono3->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono3->EditValue ?>"<?php echo $telefonos->telefono3->EditAttributes() ?>>
</span>
<input type="hidden" data-table="telefonos" data-field="x_telefono3" name="o<?php echo $telefonos_grid->RowIndex ?>_telefono3" id="o<?php echo $telefonos_grid->RowIndex ?>_telefono3" value="<?php echo ew_HtmlEncode($telefonos->telefono3->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_telefono3" class="form-group telefonos_telefono3">
<input type="text" data-table="telefonos" data-field="x_telefono3" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono3" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono3" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono3->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono3->EditValue ?>"<?php echo $telefonos->telefono3->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_telefono3" class="telefonos_telefono3">
<span<?php echo $telefonos->telefono3->ViewAttributes() ?>>
<?php echo $telefonos->telefono3->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_telefono3" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono3" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono3" value="<?php echo ew_HtmlEncode($telefonos->telefono3->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_telefono3" name="o<?php echo $telefonos_grid->RowIndex ?>_telefono3" id="o<?php echo $telefonos_grid->RowIndex ?>_telefono3" value="<?php echo ew_HtmlEncode($telefonos->telefono3->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_telefono3" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_telefono3" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_telefono3" value="<?php echo ew_HtmlEncode($telefonos->telefono3->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_telefono3" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_telefono3" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_telefono3" value="<?php echo ew_HtmlEncode($telefonos->telefono3->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->telefono4->Visible) { // telefono4 ?>
		<td data-name="telefono4"<?php echo $telefonos->telefono4->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_telefono4" class="form-group telefonos_telefono4">
<input type="text" data-table="telefonos" data-field="x_telefono4" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono4" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono4" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono4->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono4->EditValue ?>"<?php echo $telefonos->telefono4->EditAttributes() ?>>
</span>
<input type="hidden" data-table="telefonos" data-field="x_telefono4" name="o<?php echo $telefonos_grid->RowIndex ?>_telefono4" id="o<?php echo $telefonos_grid->RowIndex ?>_telefono4" value="<?php echo ew_HtmlEncode($telefonos->telefono4->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_telefono4" class="form-group telefonos_telefono4">
<input type="text" data-table="telefonos" data-field="x_telefono4" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono4" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono4" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono4->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono4->EditValue ?>"<?php echo $telefonos->telefono4->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_telefono4" class="telefonos_telefono4">
<span<?php echo $telefonos->telefono4->ViewAttributes() ?>>
<?php echo $telefonos->telefono4->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_telefono4" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono4" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono4" value="<?php echo ew_HtmlEncode($telefonos->telefono4->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_telefono4" name="o<?php echo $telefonos_grid->RowIndex ?>_telefono4" id="o<?php echo $telefonos_grid->RowIndex ?>_telefono4" value="<?php echo ew_HtmlEncode($telefonos->telefono4->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_telefono4" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_telefono4" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_telefono4" value="<?php echo ew_HtmlEncode($telefonos->telefono4->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_telefono4" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_telefono4" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_telefono4" value="<?php echo ew_HtmlEncode($telefonos->telefono4->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$telefonos_grid->ListOptions->Render("body", "right", $telefonos_grid->RowCnt);
?>
	</tr>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD || $telefonos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ftelefonosgrid.UpdateOpts(<?php echo $telefonos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($telefonos->CurrentAction <> "gridadd" || $telefonos->CurrentMode == "copy")
		if (!$telefonos_grid->Recordset->EOF) $telefonos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($telefonos->CurrentMode == "add" || $telefonos->CurrentMode == "copy" || $telefonos->CurrentMode == "edit") {
		$telefonos_grid->RowIndex = '$rowindex$';
		$telefonos_grid->LoadRowValues();

		// Set row properties
		$telefonos->ResetAttrs();
		$telefonos->RowAttrs = array_merge($telefonos->RowAttrs, array('data-rowindex'=>$telefonos_grid->RowIndex, 'id'=>'r0_telefonos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($telefonos->RowAttrs["class"], "ewTemplate");
		$telefonos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$telefonos_grid->RenderRow();

		// Render list options
		$telefonos_grid->RenderListOptions();
		$telefonos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $telefonos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$telefonos_grid->ListOptions->Render("body", "left", $telefonos_grid->RowIndex);
?>
	<?php if ($telefonos->Id->Visible) { // Id ?>
		<td data-name="Id">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_telefonos_Id" class="form-group telefonos_Id">
<span<?php echo $telefonos->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->Id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_Id" name="x<?php echo $telefonos_grid->RowIndex ?>_Id" id="x<?php echo $telefonos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($telefonos->Id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_Id" name="o<?php echo $telefonos_grid->RowIndex ?>_Id" id="o<?php echo $telefonos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($telefonos->Id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->id_persona->Visible) { // id_persona ?>
		<td data-name="id_persona">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<?php if ($telefonos->id_persona->getSessionValue() <> "") { ?>
<span id="el$rowindex$_telefonos_id_persona" class="form-group telefonos_id_persona">
<span<?php echo $telefonos->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($telefonos->id_persona->ViewValue)) && $telefonos->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $telefonos->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $telefonos->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $telefonos->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" name="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($telefonos->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_telefonos_id_persona" class="form-group telefonos_id_persona">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $telefonos_grid->RowIndex ?>_id_persona"><?php echo (strval($telefonos->id_persona->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $telefonos->id_persona->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($telefonos->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $telefonos_grid->RowIndex ?>_id_persona',m:0,n:30});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="telefonos" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $telefonos->id_persona->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" id="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" value="<?php echo $telefonos->id_persona->CurrentValue ?>"<?php echo $telefonos->id_persona->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_telefonos_id_persona" class="form-group telefonos_id_persona">
<span<?php echo $telefonos->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($telefonos->id_persona->ViewValue)) && $telefonos->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $telefonos->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $telefonos->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $telefonos->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_id_persona" name="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" id="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($telefonos->id_persona->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_id_persona" name="o<?php echo $telefonos_grid->RowIndex ?>_id_persona" id="o<?php echo $telefonos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($telefonos->id_persona->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->id_fuente->Visible) { // id_fuente ?>
		<td data-name="id_fuente">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_id_fuente" class="form-group telefonos_id_fuente">
<select data-table="telefonos" data-field="x_id_fuente" data-value-separator="<?php echo $telefonos->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $telefonos_grid->RowIndex ?>_id_fuente" name="x<?php echo $telefonos_grid->RowIndex ?>_id_fuente"<?php echo $telefonos->id_fuente->EditAttributes() ?>>
<?php echo $telefonos->id_fuente->SelectOptionListHtml("x<?php echo $telefonos_grid->RowIndex ?>_id_fuente") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_id_fuente" class="form-group telefonos_id_fuente">
<span<?php echo $telefonos->id_fuente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->id_fuente->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_id_fuente" name="x<?php echo $telefonos_grid->RowIndex ?>_id_fuente" id="x<?php echo $telefonos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($telefonos->id_fuente->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_id_fuente" name="o<?php echo $telefonos_grid->RowIndex ?>_id_fuente" id="o<?php echo $telefonos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($telefonos->id_fuente->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->id_gestion->Visible) { // id_gestion ?>
		<td data-name="id_gestion">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_id_gestion" class="form-group telefonos_id_gestion">
<select data-table="telefonos" data-field="x_id_gestion" data-value-separator="<?php echo $telefonos->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $telefonos_grid->RowIndex ?>_id_gestion" name="x<?php echo $telefonos_grid->RowIndex ?>_id_gestion"<?php echo $telefonos->id_gestion->EditAttributes() ?>>
<?php echo $telefonos->id_gestion->SelectOptionListHtml("x<?php echo $telefonos_grid->RowIndex ?>_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$telefonos->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $telefonos->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $telefonos_grid->RowIndex ?>_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $telefonos_grid->RowIndex ?>_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $telefonos->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_id_gestion" class="form-group telefonos_id_gestion">
<span<?php echo $telefonos->id_gestion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->id_gestion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_id_gestion" name="x<?php echo $telefonos_grid->RowIndex ?>_id_gestion" id="x<?php echo $telefonos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($telefonos->id_gestion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_id_gestion" name="o<?php echo $telefonos_grid->RowIndex ?>_id_gestion" id="o<?php echo $telefonos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($telefonos->id_gestion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->tipo_documento->Visible) { // tipo_documento ?>
		<td data-name="tipo_documento">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_tipo_documento" class="form-group telefonos_tipo_documento">
<input type="text" data-table="telefonos" data-field="x_tipo_documento" name="x<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" id="x<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->tipo_documento->getPlaceHolder()) ?>" value="<?php echo $telefonos->tipo_documento->EditValue ?>"<?php echo $telefonos->tipo_documento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_tipo_documento" class="form-group telefonos_tipo_documento">
<span<?php echo $telefonos->tipo_documento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->tipo_documento->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_tipo_documento" name="x<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" id="x<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($telefonos->tipo_documento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_tipo_documento" name="o<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" id="o<?php echo $telefonos_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($telefonos->tipo_documento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->no_documento->Visible) { // no_documento ?>
		<td data-name="no_documento">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_no_documento" class="form-group telefonos_no_documento">
<input type="text" data-table="telefonos" data-field="x_no_documento" name="x<?php echo $telefonos_grid->RowIndex ?>_no_documento" id="x<?php echo $telefonos_grid->RowIndex ?>_no_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->no_documento->getPlaceHolder()) ?>" value="<?php echo $telefonos->no_documento->EditValue ?>"<?php echo $telefonos->no_documento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_no_documento" class="form-group telefonos_no_documento">
<span<?php echo $telefonos->no_documento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->no_documento->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_no_documento" name="x<?php echo $telefonos_grid->RowIndex ?>_no_documento" id="x<?php echo $telefonos_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($telefonos->no_documento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_no_documento" name="o<?php echo $telefonos_grid->RowIndex ?>_no_documento" id="o<?php echo $telefonos_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($telefonos->no_documento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->nombres->Visible) { // nombres ?>
		<td data-name="nombres">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_nombres" class="form-group telefonos_nombres">
<input type="text" data-table="telefonos" data-field="x_nombres" name="x<?php echo $telefonos_grid->RowIndex ?>_nombres" id="x<?php echo $telefonos_grid->RowIndex ?>_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->nombres->getPlaceHolder()) ?>" value="<?php echo $telefonos->nombres->EditValue ?>"<?php echo $telefonos->nombres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_nombres" class="form-group telefonos_nombres">
<span<?php echo $telefonos->nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->nombres->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_nombres" name="x<?php echo $telefonos_grid->RowIndex ?>_nombres" id="x<?php echo $telefonos_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($telefonos->nombres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_nombres" name="o<?php echo $telefonos_grid->RowIndex ?>_nombres" id="o<?php echo $telefonos_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($telefonos->nombres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->paterno->Visible) { // paterno ?>
		<td data-name="paterno">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_paterno" class="form-group telefonos_paterno">
<input type="text" data-table="telefonos" data-field="x_paterno" name="x<?php echo $telefonos_grid->RowIndex ?>_paterno" id="x<?php echo $telefonos_grid->RowIndex ?>_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->paterno->getPlaceHolder()) ?>" value="<?php echo $telefonos->paterno->EditValue ?>"<?php echo $telefonos->paterno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_paterno" class="form-group telefonos_paterno">
<span<?php echo $telefonos->paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->paterno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_paterno" name="x<?php echo $telefonos_grid->RowIndex ?>_paterno" id="x<?php echo $telefonos_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($telefonos->paterno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_paterno" name="o<?php echo $telefonos_grid->RowIndex ?>_paterno" id="o<?php echo $telefonos_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($telefonos->paterno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->materno->Visible) { // materno ?>
		<td data-name="materno">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_materno" class="form-group telefonos_materno">
<input type="text" data-table="telefonos" data-field="x_materno" name="x<?php echo $telefonos_grid->RowIndex ?>_materno" id="x<?php echo $telefonos_grid->RowIndex ?>_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->materno->getPlaceHolder()) ?>" value="<?php echo $telefonos->materno->EditValue ?>"<?php echo $telefonos->materno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_materno" class="form-group telefonos_materno">
<span<?php echo $telefonos->materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->materno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_materno" name="x<?php echo $telefonos_grid->RowIndex ?>_materno" id="x<?php echo $telefonos_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($telefonos->materno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_materno" name="o<?php echo $telefonos_grid->RowIndex ?>_materno" id="o<?php echo $telefonos_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($telefonos->materno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->telefono1->Visible) { // telefono1 ?>
		<td data-name="telefono1">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_telefono1" class="form-group telefonos_telefono1">
<input type="text" data-table="telefonos" data-field="x_telefono1" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono1" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono1" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono1->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono1->EditValue ?>"<?php echo $telefonos->telefono1->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_telefono1" class="form-group telefonos_telefono1">
<span<?php echo $telefonos->telefono1->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->telefono1->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_telefono1" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono1" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono1" value="<?php echo ew_HtmlEncode($telefonos->telefono1->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_telefono1" name="o<?php echo $telefonos_grid->RowIndex ?>_telefono1" id="o<?php echo $telefonos_grid->RowIndex ?>_telefono1" value="<?php echo ew_HtmlEncode($telefonos->telefono1->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->telefono2->Visible) { // telefono2 ?>
		<td data-name="telefono2">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_telefono2" class="form-group telefonos_telefono2">
<input type="text" data-table="telefonos" data-field="x_telefono2" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono2" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono2" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono2->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono2->EditValue ?>"<?php echo $telefonos->telefono2->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_telefono2" class="form-group telefonos_telefono2">
<span<?php echo $telefonos->telefono2->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->telefono2->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_telefono2" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono2" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono2" value="<?php echo ew_HtmlEncode($telefonos->telefono2->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_telefono2" name="o<?php echo $telefonos_grid->RowIndex ?>_telefono2" id="o<?php echo $telefonos_grid->RowIndex ?>_telefono2" value="<?php echo ew_HtmlEncode($telefonos->telefono2->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->telefono3->Visible) { // telefono3 ?>
		<td data-name="telefono3">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_telefono3" class="form-group telefonos_telefono3">
<input type="text" data-table="telefonos" data-field="x_telefono3" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono3" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono3" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono3->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono3->EditValue ?>"<?php echo $telefonos->telefono3->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_telefono3" class="form-group telefonos_telefono3">
<span<?php echo $telefonos->telefono3->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->telefono3->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_telefono3" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono3" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono3" value="<?php echo ew_HtmlEncode($telefonos->telefono3->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_telefono3" name="o<?php echo $telefonos_grid->RowIndex ?>_telefono3" id="o<?php echo $telefonos_grid->RowIndex ?>_telefono3" value="<?php echo ew_HtmlEncode($telefonos->telefono3->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->telefono4->Visible) { // telefono4 ?>
		<td data-name="telefono4">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_telefono4" class="form-group telefonos_telefono4">
<input type="text" data-table="telefonos" data-field="x_telefono4" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono4" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono4" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono4->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono4->EditValue ?>"<?php echo $telefonos->telefono4->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_telefono4" class="form-group telefonos_telefono4">
<span<?php echo $telefonos->telefono4->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->telefono4->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_telefono4" name="x<?php echo $telefonos_grid->RowIndex ?>_telefono4" id="x<?php echo $telefonos_grid->RowIndex ?>_telefono4" value="<?php echo ew_HtmlEncode($telefonos->telefono4->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_telefono4" name="o<?php echo $telefonos_grid->RowIndex ?>_telefono4" id="o<?php echo $telefonos_grid->RowIndex ?>_telefono4" value="<?php echo ew_HtmlEncode($telefonos->telefono4->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$telefonos_grid->ListOptions->Render("body", "right", $telefonos_grid->RowCnt);
?>
<script type="text/javascript">
ftelefonosgrid.UpdateOpts(<?php echo $telefonos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($telefonos->CurrentMode == "add" || $telefonos->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $telefonos_grid->FormKeyCountName ?>" id="<?php echo $telefonos_grid->FormKeyCountName ?>" value="<?php echo $telefonos_grid->KeyCount ?>">
<?php echo $telefonos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($telefonos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $telefonos_grid->FormKeyCountName ?>" id="<?php echo $telefonos_grid->FormKeyCountName ?>" value="<?php echo $telefonos_grid->KeyCount ?>">
<?php echo $telefonos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($telefonos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ftelefonosgrid">
</div>
<?php

// Close recordset
if ($telefonos_grid->Recordset)
	$telefonos_grid->Recordset->Close();
?>
<?php if ($telefonos_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($telefonos_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($telefonos_grid->TotalRecs == 0 && $telefonos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($telefonos_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($telefonos->Export == "") { ?>
<script type="text/javascript">
ftelefonosgrid.Init();
</script>
<?php } ?>
<?php
$telefonos_grid->Page_Terminate();
?>
