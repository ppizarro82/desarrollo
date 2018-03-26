<?php include_once "usersinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($vehiculos_grid)) $vehiculos_grid = new cvehiculos_grid();

// Page init
$vehiculos_grid->Page_Init();

// Page main
$vehiculos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vehiculos_grid->Page_Render();
?>
<?php if ($vehiculos->Export == "") { ?>
<script type="text/javascript">

// Form object
var fvehiculosgrid = new ew_Form("fvehiculosgrid", "grid");
fvehiculosgrid.FormKeyCountName = '<?php echo $vehiculos_grid->FormKeyCountName ?>';

// Validate form
fvehiculosgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vehiculos->id_persona->FldCaption(), $vehiculos->id_persona->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_marca");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vehiculos->marca->FldCaption(), $vehiculos->marca->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modelo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vehiculos->modelo->FldCaption(), $vehiculos->modelo->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fvehiculosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_persona", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_fuente", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_gestion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "marca", false)) return false;
	if (ew_ValueChanged(fobj, infix, "modelo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "placa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "anio", false)) return false;
	return true;
}

// Form_CustomValidate event
fvehiculosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fvehiculosgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fvehiculosgrid.Lists["x_id_persona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_paterno","x_materno","x_nombres",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
fvehiculosgrid.Lists["x_id_persona"].Data = "<?php echo $vehiculos_grid->id_persona->LookupFilterQuery(FALSE, "grid") ?>";
fvehiculosgrid.Lists["x_id_fuente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"fuentes"};
fvehiculosgrid.Lists["x_id_fuente"].Data = "<?php echo $vehiculos_grid->id_fuente->LookupFilterQuery(FALSE, "grid") ?>";
fvehiculosgrid.Lists["x_id_gestion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gestiones"};
fvehiculosgrid.Lists["x_id_gestion"].Data = "<?php echo $vehiculos_grid->id_gestion->LookupFilterQuery(FALSE, "grid") ?>";

// Form object for search
</script>
<?php } ?>
<?php
if ($vehiculos->CurrentAction == "gridadd") {
	if ($vehiculos->CurrentMode == "copy") {
		$bSelectLimit = $vehiculos_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$vehiculos_grid->TotalRecs = $vehiculos->ListRecordCount();
			$vehiculos_grid->Recordset = $vehiculos_grid->LoadRecordset($vehiculos_grid->StartRec-1, $vehiculos_grid->DisplayRecs);
		} else {
			if ($vehiculos_grid->Recordset = $vehiculos_grid->LoadRecordset())
				$vehiculos_grid->TotalRecs = $vehiculos_grid->Recordset->RecordCount();
		}
		$vehiculos_grid->StartRec = 1;
		$vehiculos_grid->DisplayRecs = $vehiculos_grid->TotalRecs;
	} else {
		$vehiculos->CurrentFilter = "0=1";
		$vehiculos_grid->StartRec = 1;
		$vehiculos_grid->DisplayRecs = $vehiculos->GridAddRowCount;
	}
	$vehiculos_grid->TotalRecs = $vehiculos_grid->DisplayRecs;
	$vehiculos_grid->StopRec = $vehiculos_grid->DisplayRecs;
} else {
	$bSelectLimit = $vehiculos_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($vehiculos_grid->TotalRecs <= 0)
			$vehiculos_grid->TotalRecs = $vehiculos->ListRecordCount();
	} else {
		if (!$vehiculos_grid->Recordset && ($vehiculos_grid->Recordset = $vehiculos_grid->LoadRecordset()))
			$vehiculos_grid->TotalRecs = $vehiculos_grid->Recordset->RecordCount();
	}
	$vehiculos_grid->StartRec = 1;
	$vehiculos_grid->DisplayRecs = $vehiculos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$vehiculos_grid->Recordset = $vehiculos_grid->LoadRecordset($vehiculos_grid->StartRec-1, $vehiculos_grid->DisplayRecs);

	// Set no record found message
	if ($vehiculos->CurrentAction == "" && $vehiculos_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$vehiculos_grid->setWarningMessage(ew_DeniedMsg());
		if ($vehiculos_grid->SearchWhere == "0=101")
			$vehiculos_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$vehiculos_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$vehiculos_grid->RenderOtherOptions();
?>
<?php $vehiculos_grid->ShowPageHeader(); ?>
<?php
$vehiculos_grid->ShowMessage();
?>
<?php if ($vehiculos_grid->TotalRecs > 0 || $vehiculos->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($vehiculos_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> vehiculos">
<div id="fvehiculosgrid" class="ewForm ewListForm form-inline">
<?php if ($vehiculos_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($vehiculos_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_vehiculos" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_vehiculosgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$vehiculos_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$vehiculos_grid->RenderListOptions();

// Render list options (header, left)
$vehiculos_grid->ListOptions->Render("header", "left");
?>
<?php if ($vehiculos->Id->Visible) { // Id ?>
	<?php if ($vehiculos->SortUrl($vehiculos->Id) == "") { ?>
		<th data-name="Id" class="<?php echo $vehiculos->Id->HeaderCellClass() ?>"><div id="elh_vehiculos_Id" class="vehiculos_Id"><div class="ewTableHeaderCaption"><?php echo $vehiculos->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id" class="<?php echo $vehiculos->Id->HeaderCellClass() ?>"><div><div id="elh_vehiculos_Id" class="vehiculos_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vehiculos->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vehiculos->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vehiculos->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($vehiculos->id_persona->Visible) { // id_persona ?>
	<?php if ($vehiculos->SortUrl($vehiculos->id_persona) == "") { ?>
		<th data-name="id_persona" class="<?php echo $vehiculos->id_persona->HeaderCellClass() ?>"><div id="elh_vehiculos_id_persona" class="vehiculos_id_persona"><div class="ewTableHeaderCaption"><?php echo $vehiculos->id_persona->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_persona" class="<?php echo $vehiculos->id_persona->HeaderCellClass() ?>"><div><div id="elh_vehiculos_id_persona" class="vehiculos_id_persona">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vehiculos->id_persona->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vehiculos->id_persona->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vehiculos->id_persona->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($vehiculos->id_fuente->Visible) { // id_fuente ?>
	<?php if ($vehiculos->SortUrl($vehiculos->id_fuente) == "") { ?>
		<th data-name="id_fuente" class="<?php echo $vehiculos->id_fuente->HeaderCellClass() ?>"><div id="elh_vehiculos_id_fuente" class="vehiculos_id_fuente"><div class="ewTableHeaderCaption"><?php echo $vehiculos->id_fuente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_fuente" class="<?php echo $vehiculos->id_fuente->HeaderCellClass() ?>"><div><div id="elh_vehiculos_id_fuente" class="vehiculos_id_fuente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vehiculos->id_fuente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vehiculos->id_fuente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vehiculos->id_fuente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($vehiculos->id_gestion->Visible) { // id_gestion ?>
	<?php if ($vehiculos->SortUrl($vehiculos->id_gestion) == "") { ?>
		<th data-name="id_gestion" class="<?php echo $vehiculos->id_gestion->HeaderCellClass() ?>"><div id="elh_vehiculos_id_gestion" class="vehiculos_id_gestion"><div class="ewTableHeaderCaption"><?php echo $vehiculos->id_gestion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_gestion" class="<?php echo $vehiculos->id_gestion->HeaderCellClass() ?>"><div><div id="elh_vehiculos_id_gestion" class="vehiculos_id_gestion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vehiculos->id_gestion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vehiculos->id_gestion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vehiculos->id_gestion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($vehiculos->marca->Visible) { // marca ?>
	<?php if ($vehiculos->SortUrl($vehiculos->marca) == "") { ?>
		<th data-name="marca" class="<?php echo $vehiculos->marca->HeaderCellClass() ?>"><div id="elh_vehiculos_marca" class="vehiculos_marca"><div class="ewTableHeaderCaption"><?php echo $vehiculos->marca->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="marca" class="<?php echo $vehiculos->marca->HeaderCellClass() ?>"><div><div id="elh_vehiculos_marca" class="vehiculos_marca">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vehiculos->marca->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vehiculos->marca->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vehiculos->marca->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($vehiculos->modelo->Visible) { // modelo ?>
	<?php if ($vehiculos->SortUrl($vehiculos->modelo) == "") { ?>
		<th data-name="modelo" class="<?php echo $vehiculos->modelo->HeaderCellClass() ?>"><div id="elh_vehiculos_modelo" class="vehiculos_modelo"><div class="ewTableHeaderCaption"><?php echo $vehiculos->modelo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modelo" class="<?php echo $vehiculos->modelo->HeaderCellClass() ?>"><div><div id="elh_vehiculos_modelo" class="vehiculos_modelo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vehiculos->modelo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vehiculos->modelo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vehiculos->modelo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($vehiculos->placa->Visible) { // placa ?>
	<?php if ($vehiculos->SortUrl($vehiculos->placa) == "") { ?>
		<th data-name="placa" class="<?php echo $vehiculos->placa->HeaderCellClass() ?>"><div id="elh_vehiculos_placa" class="vehiculos_placa"><div class="ewTableHeaderCaption"><?php echo $vehiculos->placa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="placa" class="<?php echo $vehiculos->placa->HeaderCellClass() ?>"><div><div id="elh_vehiculos_placa" class="vehiculos_placa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vehiculos->placa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vehiculos->placa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vehiculos->placa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($vehiculos->anio->Visible) { // anio ?>
	<?php if ($vehiculos->SortUrl($vehiculos->anio) == "") { ?>
		<th data-name="anio" class="<?php echo $vehiculos->anio->HeaderCellClass() ?>"><div id="elh_vehiculos_anio" class="vehiculos_anio"><div class="ewTableHeaderCaption"><?php echo $vehiculos->anio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="anio" class="<?php echo $vehiculos->anio->HeaderCellClass() ?>"><div><div id="elh_vehiculos_anio" class="vehiculos_anio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vehiculos->anio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vehiculos->anio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vehiculos->anio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$vehiculos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$vehiculos_grid->StartRec = 1;
$vehiculos_grid->StopRec = $vehiculos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($vehiculos_grid->FormKeyCountName) && ($vehiculos->CurrentAction == "gridadd" || $vehiculos->CurrentAction == "gridedit" || $vehiculos->CurrentAction == "F")) {
		$vehiculos_grid->KeyCount = $objForm->GetValue($vehiculos_grid->FormKeyCountName);
		$vehiculos_grid->StopRec = $vehiculos_grid->StartRec + $vehiculos_grid->KeyCount - 1;
	}
}
$vehiculos_grid->RecCnt = $vehiculos_grid->StartRec - 1;
if ($vehiculos_grid->Recordset && !$vehiculos_grid->Recordset->EOF) {
	$vehiculos_grid->Recordset->MoveFirst();
	$bSelectLimit = $vehiculos_grid->UseSelectLimit;
	if (!$bSelectLimit && $vehiculos_grid->StartRec > 1)
		$vehiculos_grid->Recordset->Move($vehiculos_grid->StartRec - 1);
} elseif (!$vehiculos->AllowAddDeleteRow && $vehiculos_grid->StopRec == 0) {
	$vehiculos_grid->StopRec = $vehiculos->GridAddRowCount;
}

// Initialize aggregate
$vehiculos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vehiculos->ResetAttrs();
$vehiculos_grid->RenderRow();
if ($vehiculos->CurrentAction == "gridadd")
	$vehiculos_grid->RowIndex = 0;
if ($vehiculos->CurrentAction == "gridedit")
	$vehiculos_grid->RowIndex = 0;
while ($vehiculos_grid->RecCnt < $vehiculos_grid->StopRec) {
	$vehiculos_grid->RecCnt++;
	if (intval($vehiculos_grid->RecCnt) >= intval($vehiculos_grid->StartRec)) {
		$vehiculos_grid->RowCnt++;
		if ($vehiculos->CurrentAction == "gridadd" || $vehiculos->CurrentAction == "gridedit" || $vehiculos->CurrentAction == "F") {
			$vehiculos_grid->RowIndex++;
			$objForm->Index = $vehiculos_grid->RowIndex;
			if ($objForm->HasValue($vehiculos_grid->FormActionName))
				$vehiculos_grid->RowAction = strval($objForm->GetValue($vehiculos_grid->FormActionName));
			elseif ($vehiculos->CurrentAction == "gridadd")
				$vehiculos_grid->RowAction = "insert";
			else
				$vehiculos_grid->RowAction = "";
		}

		// Set up key count
		$vehiculos_grid->KeyCount = $vehiculos_grid->RowIndex;

		// Init row class and style
		$vehiculos->ResetAttrs();
		$vehiculos->CssClass = "";
		if ($vehiculos->CurrentAction == "gridadd") {
			if ($vehiculos->CurrentMode == "copy") {
				$vehiculos_grid->LoadRowValues($vehiculos_grid->Recordset); // Load row values
				$vehiculos_grid->SetRecordKey($vehiculos_grid->RowOldKey, $vehiculos_grid->Recordset); // Set old record key
			} else {
				$vehiculos_grid->LoadRowValues(); // Load default values
				$vehiculos_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$vehiculos_grid->LoadRowValues($vehiculos_grid->Recordset); // Load row values
		}
		$vehiculos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($vehiculos->CurrentAction == "gridadd") // Grid add
			$vehiculos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($vehiculos->CurrentAction == "gridadd" && $vehiculos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$vehiculos_grid->RestoreCurrentRowFormValues($vehiculos_grid->RowIndex); // Restore form values
		if ($vehiculos->CurrentAction == "gridedit") { // Grid edit
			if ($vehiculos->EventCancelled) {
				$vehiculos_grid->RestoreCurrentRowFormValues($vehiculos_grid->RowIndex); // Restore form values
			}
			if ($vehiculos_grid->RowAction == "insert")
				$vehiculos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$vehiculos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($vehiculos->CurrentAction == "gridedit" && ($vehiculos->RowType == EW_ROWTYPE_EDIT || $vehiculos->RowType == EW_ROWTYPE_ADD) && $vehiculos->EventCancelled) // Update failed
			$vehiculos_grid->RestoreCurrentRowFormValues($vehiculos_grid->RowIndex); // Restore form values
		if ($vehiculos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$vehiculos_grid->EditRowCnt++;
		if ($vehiculos->CurrentAction == "F") // Confirm row
			$vehiculos_grid->RestoreCurrentRowFormValues($vehiculos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$vehiculos->RowAttrs = array_merge($vehiculos->RowAttrs, array('data-rowindex'=>$vehiculos_grid->RowCnt, 'id'=>'r' . $vehiculos_grid->RowCnt . '_vehiculos', 'data-rowtype'=>$vehiculos->RowType));

		// Render row
		$vehiculos_grid->RenderRow();

		// Render list options
		$vehiculos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($vehiculos_grid->RowAction <> "delete" && $vehiculos_grid->RowAction <> "insertdelete" && !($vehiculos_grid->RowAction == "insert" && $vehiculos->CurrentAction == "F" && $vehiculos_grid->EmptyRow())) {
?>
	<tr<?php echo $vehiculos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vehiculos_grid->ListOptions->Render("body", "left", $vehiculos_grid->RowCnt);
?>
	<?php if ($vehiculos->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $vehiculos->Id->CellAttributes() ?>>
<?php if ($vehiculos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="vehiculos" data-field="x_Id" name="o<?php echo $vehiculos_grid->RowIndex ?>_Id" id="o<?php echo $vehiculos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($vehiculos->Id->OldValue) ?>">
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_Id" class="form-group vehiculos_Id">
<span<?php echo $vehiculos->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vehiculos->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_Id" name="x<?php echo $vehiculos_grid->RowIndex ?>_Id" id="x<?php echo $vehiculos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($vehiculos->Id->CurrentValue) ?>">
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_Id" class="vehiculos_Id">
<span<?php echo $vehiculos->Id->ViewAttributes() ?>>
<?php echo $vehiculos->Id->ListViewValue() ?></span>
</span>
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vehiculos" data-field="x_Id" name="x<?php echo $vehiculos_grid->RowIndex ?>_Id" id="x<?php echo $vehiculos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($vehiculos->Id->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_Id" name="o<?php echo $vehiculos_grid->RowIndex ?>_Id" id="o<?php echo $vehiculos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($vehiculos->Id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vehiculos" data-field="x_Id" name="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_Id" id="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($vehiculos->Id->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_Id" name="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_Id" id="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($vehiculos->Id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vehiculos->id_persona->Visible) { // id_persona ?>
		<td data-name="id_persona"<?php echo $vehiculos->id_persona->CellAttributes() ?>>
<?php if ($vehiculos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($vehiculos->id_persona->getSessionValue() <> "") { ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_id_persona" class="form-group vehiculos_id_persona">
<span<?php echo $vehiculos->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($vehiculos->id_persona->ViewValue)) && $vehiculos->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $vehiculos->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $vehiculos->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $vehiculos->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($vehiculos->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_id_persona" class="form-group vehiculos_id_persona">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $vehiculos_grid->RowIndex ?>_id_persona"><?php echo (strval($vehiculos->id_persona->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $vehiculos->id_persona->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($vehiculos->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $vehiculos_grid->RowIndex ?>_id_persona',m:0,n:30});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="vehiculos" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $vehiculos->id_persona->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" value="<?php echo $vehiculos->id_persona->CurrentValue ?>"<?php echo $vehiculos->id_persona->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="vehiculos" data-field="x_id_persona" name="o<?php echo $vehiculos_grid->RowIndex ?>_id_persona" id="o<?php echo $vehiculos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($vehiculos->id_persona->OldValue) ?>">
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($vehiculos->id_persona->getSessionValue() <> "") { ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_id_persona" class="form-group vehiculos_id_persona">
<span<?php echo $vehiculos->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($vehiculos->id_persona->ViewValue)) && $vehiculos->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $vehiculos->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $vehiculos->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $vehiculos->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($vehiculos->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_id_persona" class="form-group vehiculos_id_persona">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $vehiculos_grid->RowIndex ?>_id_persona"><?php echo (strval($vehiculos->id_persona->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $vehiculos->id_persona->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($vehiculos->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $vehiculos_grid->RowIndex ?>_id_persona',m:0,n:30});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="vehiculos" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $vehiculos->id_persona->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" value="<?php echo $vehiculos->id_persona->CurrentValue ?>"<?php echo $vehiculos->id_persona->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_id_persona" class="vehiculos_id_persona">
<span<?php echo $vehiculos->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($vehiculos->id_persona->ListViewValue())) && $vehiculos->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $vehiculos->id_persona->LinkAttributes() ?>><?php echo $vehiculos->id_persona->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $vehiculos->id_persona->ListViewValue() ?>
<?php } ?>
</span>
</span>
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vehiculos" data-field="x_id_persona" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($vehiculos->id_persona->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_id_persona" name="o<?php echo $vehiculos_grid->RowIndex ?>_id_persona" id="o<?php echo $vehiculos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($vehiculos->id_persona->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vehiculos" data-field="x_id_persona" name="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" id="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($vehiculos->id_persona->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_id_persona" name="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_id_persona" id="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($vehiculos->id_persona->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vehiculos->id_fuente->Visible) { // id_fuente ?>
		<td data-name="id_fuente"<?php echo $vehiculos->id_fuente->CellAttributes() ?>>
<?php if ($vehiculos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_id_fuente" class="form-group vehiculos_id_fuente">
<select data-table="vehiculos" data-field="x_id_fuente" data-value-separator="<?php echo $vehiculos->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente"<?php echo $vehiculos->id_fuente->EditAttributes() ?>>
<?php echo $vehiculos->id_fuente->SelectOptionListHtml("x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente") ?>
</select>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_id_fuente" name="o<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" id="o<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($vehiculos->id_fuente->OldValue) ?>">
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_id_fuente" class="form-group vehiculos_id_fuente">
<select data-table="vehiculos" data-field="x_id_fuente" data-value-separator="<?php echo $vehiculos->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente"<?php echo $vehiculos->id_fuente->EditAttributes() ?>>
<?php echo $vehiculos->id_fuente->SelectOptionListHtml("x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente") ?>
</select>
</span>
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_id_fuente" class="vehiculos_id_fuente">
<span<?php echo $vehiculos->id_fuente->ViewAttributes() ?>>
<?php echo $vehiculos->id_fuente->ListViewValue() ?></span>
</span>
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vehiculos" data-field="x_id_fuente" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($vehiculos->id_fuente->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_id_fuente" name="o<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" id="o<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($vehiculos->id_fuente->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vehiculos" data-field="x_id_fuente" name="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" id="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($vehiculos->id_fuente->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_id_fuente" name="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" id="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($vehiculos->id_fuente->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vehiculos->id_gestion->Visible) { // id_gestion ?>
		<td data-name="id_gestion"<?php echo $vehiculos->id_gestion->CellAttributes() ?>>
<?php if ($vehiculos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_id_gestion" class="form-group vehiculos_id_gestion">
<select data-table="vehiculos" data-field="x_id_gestion" data-value-separator="<?php echo $vehiculos->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion"<?php echo $vehiculos->id_gestion->EditAttributes() ?>>
<?php echo $vehiculos->id_gestion->SelectOptionListHtml("x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$vehiculos->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $vehiculos->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $vehiculos->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_id_gestion" name="o<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" id="o<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($vehiculos->id_gestion->OldValue) ?>">
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_id_gestion" class="form-group vehiculos_id_gestion">
<select data-table="vehiculos" data-field="x_id_gestion" data-value-separator="<?php echo $vehiculos->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion"<?php echo $vehiculos->id_gestion->EditAttributes() ?>>
<?php echo $vehiculos->id_gestion->SelectOptionListHtml("x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$vehiculos->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $vehiculos->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $vehiculos->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_id_gestion" class="vehiculos_id_gestion">
<span<?php echo $vehiculos->id_gestion->ViewAttributes() ?>>
<?php echo $vehiculos->id_gestion->ListViewValue() ?></span>
</span>
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vehiculos" data-field="x_id_gestion" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($vehiculos->id_gestion->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_id_gestion" name="o<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" id="o<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($vehiculos->id_gestion->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vehiculos" data-field="x_id_gestion" name="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" id="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($vehiculos->id_gestion->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_id_gestion" name="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" id="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($vehiculos->id_gestion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vehiculos->marca->Visible) { // marca ?>
		<td data-name="marca"<?php echo $vehiculos->marca->CellAttributes() ?>>
<?php if ($vehiculos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_marca" class="form-group vehiculos_marca">
<input type="text" data-table="vehiculos" data-field="x_marca" name="x<?php echo $vehiculos_grid->RowIndex ?>_marca" id="x<?php echo $vehiculos_grid->RowIndex ?>_marca" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vehiculos->marca->getPlaceHolder()) ?>" value="<?php echo $vehiculos->marca->EditValue ?>"<?php echo $vehiculos->marca->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_marca" name="o<?php echo $vehiculos_grid->RowIndex ?>_marca" id="o<?php echo $vehiculos_grid->RowIndex ?>_marca" value="<?php echo ew_HtmlEncode($vehiculos->marca->OldValue) ?>">
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_marca" class="form-group vehiculos_marca">
<input type="text" data-table="vehiculos" data-field="x_marca" name="x<?php echo $vehiculos_grid->RowIndex ?>_marca" id="x<?php echo $vehiculos_grid->RowIndex ?>_marca" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vehiculos->marca->getPlaceHolder()) ?>" value="<?php echo $vehiculos->marca->EditValue ?>"<?php echo $vehiculos->marca->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_marca" class="vehiculos_marca">
<span<?php echo $vehiculos->marca->ViewAttributes() ?>>
<?php echo $vehiculos->marca->ListViewValue() ?></span>
</span>
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vehiculos" data-field="x_marca" name="x<?php echo $vehiculos_grid->RowIndex ?>_marca" id="x<?php echo $vehiculos_grid->RowIndex ?>_marca" value="<?php echo ew_HtmlEncode($vehiculos->marca->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_marca" name="o<?php echo $vehiculos_grid->RowIndex ?>_marca" id="o<?php echo $vehiculos_grid->RowIndex ?>_marca" value="<?php echo ew_HtmlEncode($vehiculos->marca->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vehiculos" data-field="x_marca" name="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_marca" id="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_marca" value="<?php echo ew_HtmlEncode($vehiculos->marca->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_marca" name="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_marca" id="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_marca" value="<?php echo ew_HtmlEncode($vehiculos->marca->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vehiculos->modelo->Visible) { // modelo ?>
		<td data-name="modelo"<?php echo $vehiculos->modelo->CellAttributes() ?>>
<?php if ($vehiculos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_modelo" class="form-group vehiculos_modelo">
<input type="text" data-table="vehiculos" data-field="x_modelo" name="x<?php echo $vehiculos_grid->RowIndex ?>_modelo" id="x<?php echo $vehiculos_grid->RowIndex ?>_modelo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vehiculos->modelo->getPlaceHolder()) ?>" value="<?php echo $vehiculos->modelo->EditValue ?>"<?php echo $vehiculos->modelo->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_modelo" name="o<?php echo $vehiculos_grid->RowIndex ?>_modelo" id="o<?php echo $vehiculos_grid->RowIndex ?>_modelo" value="<?php echo ew_HtmlEncode($vehiculos->modelo->OldValue) ?>">
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_modelo" class="form-group vehiculos_modelo">
<input type="text" data-table="vehiculos" data-field="x_modelo" name="x<?php echo $vehiculos_grid->RowIndex ?>_modelo" id="x<?php echo $vehiculos_grid->RowIndex ?>_modelo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vehiculos->modelo->getPlaceHolder()) ?>" value="<?php echo $vehiculos->modelo->EditValue ?>"<?php echo $vehiculos->modelo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_modelo" class="vehiculos_modelo">
<span<?php echo $vehiculos->modelo->ViewAttributes() ?>>
<?php echo $vehiculos->modelo->ListViewValue() ?></span>
</span>
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vehiculos" data-field="x_modelo" name="x<?php echo $vehiculos_grid->RowIndex ?>_modelo" id="x<?php echo $vehiculos_grid->RowIndex ?>_modelo" value="<?php echo ew_HtmlEncode($vehiculos->modelo->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_modelo" name="o<?php echo $vehiculos_grid->RowIndex ?>_modelo" id="o<?php echo $vehiculos_grid->RowIndex ?>_modelo" value="<?php echo ew_HtmlEncode($vehiculos->modelo->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vehiculos" data-field="x_modelo" name="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_modelo" id="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_modelo" value="<?php echo ew_HtmlEncode($vehiculos->modelo->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_modelo" name="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_modelo" id="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_modelo" value="<?php echo ew_HtmlEncode($vehiculos->modelo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vehiculos->placa->Visible) { // placa ?>
		<td data-name="placa"<?php echo $vehiculos->placa->CellAttributes() ?>>
<?php if ($vehiculos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_placa" class="form-group vehiculos_placa">
<input type="text" data-table="vehiculos" data-field="x_placa" name="x<?php echo $vehiculos_grid->RowIndex ?>_placa" id="x<?php echo $vehiculos_grid->RowIndex ?>_placa" size="10" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vehiculos->placa->getPlaceHolder()) ?>" value="<?php echo $vehiculos->placa->EditValue ?>"<?php echo $vehiculos->placa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_placa" name="o<?php echo $vehiculos_grid->RowIndex ?>_placa" id="o<?php echo $vehiculos_grid->RowIndex ?>_placa" value="<?php echo ew_HtmlEncode($vehiculos->placa->OldValue) ?>">
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_placa" class="form-group vehiculos_placa">
<input type="text" data-table="vehiculos" data-field="x_placa" name="x<?php echo $vehiculos_grid->RowIndex ?>_placa" id="x<?php echo $vehiculos_grid->RowIndex ?>_placa" size="10" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vehiculos->placa->getPlaceHolder()) ?>" value="<?php echo $vehiculos->placa->EditValue ?>"<?php echo $vehiculos->placa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_placa" class="vehiculos_placa">
<span<?php echo $vehiculos->placa->ViewAttributes() ?>>
<?php echo $vehiculos->placa->ListViewValue() ?></span>
</span>
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vehiculos" data-field="x_placa" name="x<?php echo $vehiculos_grid->RowIndex ?>_placa" id="x<?php echo $vehiculos_grid->RowIndex ?>_placa" value="<?php echo ew_HtmlEncode($vehiculos->placa->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_placa" name="o<?php echo $vehiculos_grid->RowIndex ?>_placa" id="o<?php echo $vehiculos_grid->RowIndex ?>_placa" value="<?php echo ew_HtmlEncode($vehiculos->placa->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vehiculos" data-field="x_placa" name="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_placa" id="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_placa" value="<?php echo ew_HtmlEncode($vehiculos->placa->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_placa" name="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_placa" id="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_placa" value="<?php echo ew_HtmlEncode($vehiculos->placa->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vehiculos->anio->Visible) { // anio ?>
		<td data-name="anio"<?php echo $vehiculos->anio->CellAttributes() ?>>
<?php if ($vehiculos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_anio" class="form-group vehiculos_anio">
<input type="text" data-table="vehiculos" data-field="x_anio" name="x<?php echo $vehiculos_grid->RowIndex ?>_anio" id="x<?php echo $vehiculos_grid->RowIndex ?>_anio" size="10" maxlength="50" placeholder="<?php echo ew_HtmlEncode($vehiculos->anio->getPlaceHolder()) ?>" value="<?php echo $vehiculos->anio->EditValue ?>"<?php echo $vehiculos->anio->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_anio" name="o<?php echo $vehiculos_grid->RowIndex ?>_anio" id="o<?php echo $vehiculos_grid->RowIndex ?>_anio" value="<?php echo ew_HtmlEncode($vehiculos->anio->OldValue) ?>">
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_anio" class="form-group vehiculos_anio">
<input type="text" data-table="vehiculos" data-field="x_anio" name="x<?php echo $vehiculos_grid->RowIndex ?>_anio" id="x<?php echo $vehiculos_grid->RowIndex ?>_anio" size="10" maxlength="50" placeholder="<?php echo ew_HtmlEncode($vehiculos->anio->getPlaceHolder()) ?>" value="<?php echo $vehiculos->anio->EditValue ?>"<?php echo $vehiculos->anio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($vehiculos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vehiculos_grid->RowCnt ?>_vehiculos_anio" class="vehiculos_anio">
<span<?php echo $vehiculos->anio->ViewAttributes() ?>>
<?php echo $vehiculos->anio->ListViewValue() ?></span>
</span>
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vehiculos" data-field="x_anio" name="x<?php echo $vehiculos_grid->RowIndex ?>_anio" id="x<?php echo $vehiculos_grid->RowIndex ?>_anio" value="<?php echo ew_HtmlEncode($vehiculos->anio->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_anio" name="o<?php echo $vehiculos_grid->RowIndex ?>_anio" id="o<?php echo $vehiculos_grid->RowIndex ?>_anio" value="<?php echo ew_HtmlEncode($vehiculos->anio->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vehiculos" data-field="x_anio" name="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_anio" id="fvehiculosgrid$x<?php echo $vehiculos_grid->RowIndex ?>_anio" value="<?php echo ew_HtmlEncode($vehiculos->anio->FormValue) ?>">
<input type="hidden" data-table="vehiculos" data-field="x_anio" name="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_anio" id="fvehiculosgrid$o<?php echo $vehiculos_grid->RowIndex ?>_anio" value="<?php echo ew_HtmlEncode($vehiculos->anio->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$vehiculos_grid->ListOptions->Render("body", "right", $vehiculos_grid->RowCnt);
?>
	</tr>
<?php if ($vehiculos->RowType == EW_ROWTYPE_ADD || $vehiculos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fvehiculosgrid.UpdateOpts(<?php echo $vehiculos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($vehiculos->CurrentAction <> "gridadd" || $vehiculos->CurrentMode == "copy")
		if (!$vehiculos_grid->Recordset->EOF) $vehiculos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($vehiculos->CurrentMode == "add" || $vehiculos->CurrentMode == "copy" || $vehiculos->CurrentMode == "edit") {
		$vehiculos_grid->RowIndex = '$rowindex$';
		$vehiculos_grid->LoadRowValues();

		// Set row properties
		$vehiculos->ResetAttrs();
		$vehiculos->RowAttrs = array_merge($vehiculos->RowAttrs, array('data-rowindex'=>$vehiculos_grid->RowIndex, 'id'=>'r0_vehiculos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($vehiculos->RowAttrs["class"], "ewTemplate");
		$vehiculos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$vehiculos_grid->RenderRow();

		// Render list options
		$vehiculos_grid->RenderListOptions();
		$vehiculos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $vehiculos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vehiculos_grid->ListOptions->Render("body", "left", $vehiculos_grid->RowIndex);
?>
	<?php if ($vehiculos->Id->Visible) { // Id ?>
		<td data-name="Id">
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_vehiculos_Id" class="form-group vehiculos_Id">
<span<?php echo $vehiculos->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vehiculos->Id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_Id" name="x<?php echo $vehiculos_grid->RowIndex ?>_Id" id="x<?php echo $vehiculos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($vehiculos->Id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vehiculos" data-field="x_Id" name="o<?php echo $vehiculos_grid->RowIndex ?>_Id" id="o<?php echo $vehiculos_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($vehiculos->Id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vehiculos->id_persona->Visible) { // id_persona ?>
		<td data-name="id_persona">
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<?php if ($vehiculos->id_persona->getSessionValue() <> "") { ?>
<span id="el$rowindex$_vehiculos_id_persona" class="form-group vehiculos_id_persona">
<span<?php echo $vehiculos->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($vehiculos->id_persona->ViewValue)) && $vehiculos->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $vehiculos->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $vehiculos->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $vehiculos->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($vehiculos->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_vehiculos_id_persona" class="form-group vehiculos_id_persona">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $vehiculos_grid->RowIndex ?>_id_persona"><?php echo (strval($vehiculos->id_persona->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $vehiculos->id_persona->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($vehiculos->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $vehiculos_grid->RowIndex ?>_id_persona',m:0,n:30});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="vehiculos" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $vehiculos->id_persona->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" value="<?php echo $vehiculos->id_persona->CurrentValue ?>"<?php echo $vehiculos->id_persona->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_vehiculos_id_persona" class="form-group vehiculos_id_persona">
<span<?php echo $vehiculos->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($vehiculos->id_persona->ViewValue)) && $vehiculos->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $vehiculos->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $vehiculos->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $vehiculos->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_id_persona" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($vehiculos->id_persona->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vehiculos" data-field="x_id_persona" name="o<?php echo $vehiculos_grid->RowIndex ?>_id_persona" id="o<?php echo $vehiculos_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($vehiculos->id_persona->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vehiculos->id_fuente->Visible) { // id_fuente ?>
		<td data-name="id_fuente">
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vehiculos_id_fuente" class="form-group vehiculos_id_fuente">
<select data-table="vehiculos" data-field="x_id_fuente" data-value-separator="<?php echo $vehiculos->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente"<?php echo $vehiculos->id_fuente->EditAttributes() ?>>
<?php echo $vehiculos->id_fuente->SelectOptionListHtml("x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_vehiculos_id_fuente" class="form-group vehiculos_id_fuente">
<span<?php echo $vehiculos->id_fuente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vehiculos->id_fuente->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_id_fuente" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($vehiculos->id_fuente->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vehiculos" data-field="x_id_fuente" name="o<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" id="o<?php echo $vehiculos_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($vehiculos->id_fuente->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vehiculos->id_gestion->Visible) { // id_gestion ?>
		<td data-name="id_gestion">
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vehiculos_id_gestion" class="form-group vehiculos_id_gestion">
<select data-table="vehiculos" data-field="x_id_gestion" data-value-separator="<?php echo $vehiculos->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion"<?php echo $vehiculos->id_gestion->EditAttributes() ?>>
<?php echo $vehiculos->id_gestion->SelectOptionListHtml("x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$vehiculos->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $vehiculos->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $vehiculos->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_vehiculos_id_gestion" class="form-group vehiculos_id_gestion">
<span<?php echo $vehiculos->id_gestion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vehiculos->id_gestion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_id_gestion" name="x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" id="x<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($vehiculos->id_gestion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vehiculos" data-field="x_id_gestion" name="o<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" id="o<?php echo $vehiculos_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($vehiculos->id_gestion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vehiculos->marca->Visible) { // marca ?>
		<td data-name="marca">
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vehiculos_marca" class="form-group vehiculos_marca">
<input type="text" data-table="vehiculos" data-field="x_marca" name="x<?php echo $vehiculos_grid->RowIndex ?>_marca" id="x<?php echo $vehiculos_grid->RowIndex ?>_marca" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vehiculos->marca->getPlaceHolder()) ?>" value="<?php echo $vehiculos->marca->EditValue ?>"<?php echo $vehiculos->marca->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_vehiculos_marca" class="form-group vehiculos_marca">
<span<?php echo $vehiculos->marca->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vehiculos->marca->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_marca" name="x<?php echo $vehiculos_grid->RowIndex ?>_marca" id="x<?php echo $vehiculos_grid->RowIndex ?>_marca" value="<?php echo ew_HtmlEncode($vehiculos->marca->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vehiculos" data-field="x_marca" name="o<?php echo $vehiculos_grid->RowIndex ?>_marca" id="o<?php echo $vehiculos_grid->RowIndex ?>_marca" value="<?php echo ew_HtmlEncode($vehiculos->marca->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vehiculos->modelo->Visible) { // modelo ?>
		<td data-name="modelo">
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vehiculos_modelo" class="form-group vehiculos_modelo">
<input type="text" data-table="vehiculos" data-field="x_modelo" name="x<?php echo $vehiculos_grid->RowIndex ?>_modelo" id="x<?php echo $vehiculos_grid->RowIndex ?>_modelo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vehiculos->modelo->getPlaceHolder()) ?>" value="<?php echo $vehiculos->modelo->EditValue ?>"<?php echo $vehiculos->modelo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_vehiculos_modelo" class="form-group vehiculos_modelo">
<span<?php echo $vehiculos->modelo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vehiculos->modelo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_modelo" name="x<?php echo $vehiculos_grid->RowIndex ?>_modelo" id="x<?php echo $vehiculos_grid->RowIndex ?>_modelo" value="<?php echo ew_HtmlEncode($vehiculos->modelo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vehiculos" data-field="x_modelo" name="o<?php echo $vehiculos_grid->RowIndex ?>_modelo" id="o<?php echo $vehiculos_grid->RowIndex ?>_modelo" value="<?php echo ew_HtmlEncode($vehiculos->modelo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vehiculos->placa->Visible) { // placa ?>
		<td data-name="placa">
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vehiculos_placa" class="form-group vehiculos_placa">
<input type="text" data-table="vehiculos" data-field="x_placa" name="x<?php echo $vehiculos_grid->RowIndex ?>_placa" id="x<?php echo $vehiculos_grid->RowIndex ?>_placa" size="10" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vehiculos->placa->getPlaceHolder()) ?>" value="<?php echo $vehiculos->placa->EditValue ?>"<?php echo $vehiculos->placa->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_vehiculos_placa" class="form-group vehiculos_placa">
<span<?php echo $vehiculos->placa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vehiculos->placa->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_placa" name="x<?php echo $vehiculos_grid->RowIndex ?>_placa" id="x<?php echo $vehiculos_grid->RowIndex ?>_placa" value="<?php echo ew_HtmlEncode($vehiculos->placa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vehiculos" data-field="x_placa" name="o<?php echo $vehiculos_grid->RowIndex ?>_placa" id="o<?php echo $vehiculos_grid->RowIndex ?>_placa" value="<?php echo ew_HtmlEncode($vehiculos->placa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vehiculos->anio->Visible) { // anio ?>
		<td data-name="anio">
<?php if ($vehiculos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vehiculos_anio" class="form-group vehiculos_anio">
<input type="text" data-table="vehiculos" data-field="x_anio" name="x<?php echo $vehiculos_grid->RowIndex ?>_anio" id="x<?php echo $vehiculos_grid->RowIndex ?>_anio" size="10" maxlength="50" placeholder="<?php echo ew_HtmlEncode($vehiculos->anio->getPlaceHolder()) ?>" value="<?php echo $vehiculos->anio->EditValue ?>"<?php echo $vehiculos->anio->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_vehiculos_anio" class="form-group vehiculos_anio">
<span<?php echo $vehiculos->anio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vehiculos->anio->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vehiculos" data-field="x_anio" name="x<?php echo $vehiculos_grid->RowIndex ?>_anio" id="x<?php echo $vehiculos_grid->RowIndex ?>_anio" value="<?php echo ew_HtmlEncode($vehiculos->anio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vehiculos" data-field="x_anio" name="o<?php echo $vehiculos_grid->RowIndex ?>_anio" id="o<?php echo $vehiculos_grid->RowIndex ?>_anio" value="<?php echo ew_HtmlEncode($vehiculos->anio->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$vehiculos_grid->ListOptions->Render("body", "right", $vehiculos_grid->RowCnt);
?>
<script type="text/javascript">
fvehiculosgrid.UpdateOpts(<?php echo $vehiculos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($vehiculos->CurrentMode == "add" || $vehiculos->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $vehiculos_grid->FormKeyCountName ?>" id="<?php echo $vehiculos_grid->FormKeyCountName ?>" value="<?php echo $vehiculos_grid->KeyCount ?>">
<?php echo $vehiculos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($vehiculos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $vehiculos_grid->FormKeyCountName ?>" id="<?php echo $vehiculos_grid->FormKeyCountName ?>" value="<?php echo $vehiculos_grid->KeyCount ?>">
<?php echo $vehiculos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($vehiculos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fvehiculosgrid">
</div>
<?php

// Close recordset
if ($vehiculos_grid->Recordset)
	$vehiculos_grid->Recordset->Close();
?>
<?php if ($vehiculos_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($vehiculos_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($vehiculos_grid->TotalRecs == 0 && $vehiculos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($vehiculos_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($vehiculos->Export == "") { ?>
<script type="text/javascript">
fvehiculosgrid.Init();
</script>
<?php } ?>
<?php
$vehiculos_grid->Page_Terminate();
?>
