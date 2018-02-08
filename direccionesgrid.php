<?php include_once "usersinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($direcciones_grid)) $direcciones_grid = new cdirecciones_grid();

// Page init
$direcciones_grid->Page_Init();

// Page main
$direcciones_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$direcciones_grid->Page_Render();
?>
<?php if ($direcciones->Export == "") { ?>
<script type="text/javascript">

// Form object
var fdireccionesgrid = new ew_Form("fdireccionesgrid", "grid");
fdireccionesgrid.FormKeyCountName = '<?php echo $direcciones_grid->FormKeyCountName ?>';

// Validate form
fdireccionesgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $direcciones->id_persona->FldCaption(), $direcciones->id_persona->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_direccion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $direcciones->direccion->FldCaption(), $direcciones->direccion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ult_fecha_activo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $direcciones->ult_fecha_activo->FldCaption(), $direcciones->ult_fecha_activo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ult_fecha_activo");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($direcciones->ult_fecha_activo->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fdireccionesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_persona", false)) return false;
	if (ew_ValueChanged(fobj, infix, "direccion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ult_fecha_activo", false)) return false;
	return true;
}

// Form_CustomValidate event
fdireccionesgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdireccionesgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdireccionesgrid.Lists["x_id_persona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_paterno","x_materno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
fdireccionesgrid.Lists["x_id_persona"].Data = "<?php echo $direcciones_grid->id_persona->LookupFilterQuery(FALSE, "grid") ?>";

// Form object for search
</script>
<?php } ?>
<?php
if ($direcciones->CurrentAction == "gridadd") {
	if ($direcciones->CurrentMode == "copy") {
		$bSelectLimit = $direcciones_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$direcciones_grid->TotalRecs = $direcciones->ListRecordCount();
			$direcciones_grid->Recordset = $direcciones_grid->LoadRecordset($direcciones_grid->StartRec-1, $direcciones_grid->DisplayRecs);
		} else {
			if ($direcciones_grid->Recordset = $direcciones_grid->LoadRecordset())
				$direcciones_grid->TotalRecs = $direcciones_grid->Recordset->RecordCount();
		}
		$direcciones_grid->StartRec = 1;
		$direcciones_grid->DisplayRecs = $direcciones_grid->TotalRecs;
	} else {
		$direcciones->CurrentFilter = "0=1";
		$direcciones_grid->StartRec = 1;
		$direcciones_grid->DisplayRecs = $direcciones->GridAddRowCount;
	}
	$direcciones_grid->TotalRecs = $direcciones_grid->DisplayRecs;
	$direcciones_grid->StopRec = $direcciones_grid->DisplayRecs;
} else {
	$bSelectLimit = $direcciones_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($direcciones_grid->TotalRecs <= 0)
			$direcciones_grid->TotalRecs = $direcciones->ListRecordCount();
	} else {
		if (!$direcciones_grid->Recordset && ($direcciones_grid->Recordset = $direcciones_grid->LoadRecordset()))
			$direcciones_grid->TotalRecs = $direcciones_grid->Recordset->RecordCount();
	}
	$direcciones_grid->StartRec = 1;
	$direcciones_grid->DisplayRecs = $direcciones_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$direcciones_grid->Recordset = $direcciones_grid->LoadRecordset($direcciones_grid->StartRec-1, $direcciones_grid->DisplayRecs);

	// Set no record found message
	if ($direcciones->CurrentAction == "" && $direcciones_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$direcciones_grid->setWarningMessage(ew_DeniedMsg());
		if ($direcciones_grid->SearchWhere == "0=101")
			$direcciones_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$direcciones_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$direcciones_grid->RenderOtherOptions();
?>
<?php $direcciones_grid->ShowPageHeader(); ?>
<?php
$direcciones_grid->ShowMessage();
?>
<?php if ($direcciones_grid->TotalRecs > 0 || $direcciones->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($direcciones_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> direcciones">
<div id="fdireccionesgrid" class="ewForm ewListForm form-inline">
<?php if ($direcciones_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($direcciones_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_direcciones" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_direccionesgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$direcciones_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$direcciones_grid->RenderListOptions();

// Render list options (header, left)
$direcciones_grid->ListOptions->Render("header", "left");
?>
<?php if ($direcciones->Id->Visible) { // Id ?>
	<?php if ($direcciones->SortUrl($direcciones->Id) == "") { ?>
		<th data-name="Id" class="<?php echo $direcciones->Id->HeaderCellClass() ?>"><div id="elh_direcciones_Id" class="direcciones_Id"><div class="ewTableHeaderCaption"><?php echo $direcciones->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id" class="<?php echo $direcciones->Id->HeaderCellClass() ?>"><div><div id="elh_direcciones_Id" class="direcciones_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->id_persona->Visible) { // id_persona ?>
	<?php if ($direcciones->SortUrl($direcciones->id_persona) == "") { ?>
		<th data-name="id_persona" class="<?php echo $direcciones->id_persona->HeaderCellClass() ?>"><div id="elh_direcciones_id_persona" class="direcciones_id_persona"><div class="ewTableHeaderCaption"><?php echo $direcciones->id_persona->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_persona" class="<?php echo $direcciones->id_persona->HeaderCellClass() ?>"><div><div id="elh_direcciones_id_persona" class="direcciones_id_persona">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->id_persona->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->id_persona->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->id_persona->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->direccion->Visible) { // direccion ?>
	<?php if ($direcciones->SortUrl($direcciones->direccion) == "") { ?>
		<th data-name="direccion" class="<?php echo $direcciones->direccion->HeaderCellClass() ?>"><div id="elh_direcciones_direccion" class="direcciones_direccion"><div class="ewTableHeaderCaption"><?php echo $direcciones->direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion" class="<?php echo $direcciones->direccion->HeaderCellClass() ?>"><div><div id="elh_direcciones_direccion" class="direcciones_direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->direccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->ult_fecha_activo->Visible) { // ult_fecha_activo ?>
	<?php if ($direcciones->SortUrl($direcciones->ult_fecha_activo) == "") { ?>
		<th data-name="ult_fecha_activo" class="<?php echo $direcciones->ult_fecha_activo->HeaderCellClass() ?>"><div id="elh_direcciones_ult_fecha_activo" class="direcciones_ult_fecha_activo"><div class="ewTableHeaderCaption"><?php echo $direcciones->ult_fecha_activo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ult_fecha_activo" class="<?php echo $direcciones->ult_fecha_activo->HeaderCellClass() ?>"><div><div id="elh_direcciones_ult_fecha_activo" class="direcciones_ult_fecha_activo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->ult_fecha_activo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->ult_fecha_activo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->ult_fecha_activo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$direcciones_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$direcciones_grid->StartRec = 1;
$direcciones_grid->StopRec = $direcciones_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($direcciones_grid->FormKeyCountName) && ($direcciones->CurrentAction == "gridadd" || $direcciones->CurrentAction == "gridedit" || $direcciones->CurrentAction == "F")) {
		$direcciones_grid->KeyCount = $objForm->GetValue($direcciones_grid->FormKeyCountName);
		$direcciones_grid->StopRec = $direcciones_grid->StartRec + $direcciones_grid->KeyCount - 1;
	}
}
$direcciones_grid->RecCnt = $direcciones_grid->StartRec - 1;
if ($direcciones_grid->Recordset && !$direcciones_grid->Recordset->EOF) {
	$direcciones_grid->Recordset->MoveFirst();
	$bSelectLimit = $direcciones_grid->UseSelectLimit;
	if (!$bSelectLimit && $direcciones_grid->StartRec > 1)
		$direcciones_grid->Recordset->Move($direcciones_grid->StartRec - 1);
} elseif (!$direcciones->AllowAddDeleteRow && $direcciones_grid->StopRec == 0) {
	$direcciones_grid->StopRec = $direcciones->GridAddRowCount;
}

// Initialize aggregate
$direcciones->RowType = EW_ROWTYPE_AGGREGATEINIT;
$direcciones->ResetAttrs();
$direcciones_grid->RenderRow();
if ($direcciones->CurrentAction == "gridadd")
	$direcciones_grid->RowIndex = 0;
if ($direcciones->CurrentAction == "gridedit")
	$direcciones_grid->RowIndex = 0;
while ($direcciones_grid->RecCnt < $direcciones_grid->StopRec) {
	$direcciones_grid->RecCnt++;
	if (intval($direcciones_grid->RecCnt) >= intval($direcciones_grid->StartRec)) {
		$direcciones_grid->RowCnt++;
		if ($direcciones->CurrentAction == "gridadd" || $direcciones->CurrentAction == "gridedit" || $direcciones->CurrentAction == "F") {
			$direcciones_grid->RowIndex++;
			$objForm->Index = $direcciones_grid->RowIndex;
			if ($objForm->HasValue($direcciones_grid->FormActionName))
				$direcciones_grid->RowAction = strval($objForm->GetValue($direcciones_grid->FormActionName));
			elseif ($direcciones->CurrentAction == "gridadd")
				$direcciones_grid->RowAction = "insert";
			else
				$direcciones_grid->RowAction = "";
		}

		// Set up key count
		$direcciones_grid->KeyCount = $direcciones_grid->RowIndex;

		// Init row class and style
		$direcciones->ResetAttrs();
		$direcciones->CssClass = "";
		if ($direcciones->CurrentAction == "gridadd") {
			if ($direcciones->CurrentMode == "copy") {
				$direcciones_grid->LoadRowValues($direcciones_grid->Recordset); // Load row values
				$direcciones_grid->SetRecordKey($direcciones_grid->RowOldKey, $direcciones_grid->Recordset); // Set old record key
			} else {
				$direcciones_grid->LoadRowValues(); // Load default values
				$direcciones_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$direcciones_grid->LoadRowValues($direcciones_grid->Recordset); // Load row values
		}
		$direcciones->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($direcciones->CurrentAction == "gridadd") // Grid add
			$direcciones->RowType = EW_ROWTYPE_ADD; // Render add
		if ($direcciones->CurrentAction == "gridadd" && $direcciones->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$direcciones_grid->RestoreCurrentRowFormValues($direcciones_grid->RowIndex); // Restore form values
		if ($direcciones->CurrentAction == "gridedit") { // Grid edit
			if ($direcciones->EventCancelled) {
				$direcciones_grid->RestoreCurrentRowFormValues($direcciones_grid->RowIndex); // Restore form values
			}
			if ($direcciones_grid->RowAction == "insert")
				$direcciones->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$direcciones->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($direcciones->CurrentAction == "gridedit" && ($direcciones->RowType == EW_ROWTYPE_EDIT || $direcciones->RowType == EW_ROWTYPE_ADD) && $direcciones->EventCancelled) // Update failed
			$direcciones_grid->RestoreCurrentRowFormValues($direcciones_grid->RowIndex); // Restore form values
		if ($direcciones->RowType == EW_ROWTYPE_EDIT) // Edit row
			$direcciones_grid->EditRowCnt++;
		if ($direcciones->CurrentAction == "F") // Confirm row
			$direcciones_grid->RestoreCurrentRowFormValues($direcciones_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$direcciones->RowAttrs = array_merge($direcciones->RowAttrs, array('data-rowindex'=>$direcciones_grid->RowCnt, 'id'=>'r' . $direcciones_grid->RowCnt . '_direcciones', 'data-rowtype'=>$direcciones->RowType));

		// Render row
		$direcciones_grid->RenderRow();

		// Render list options
		$direcciones_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($direcciones_grid->RowAction <> "delete" && $direcciones_grid->RowAction <> "insertdelete" && !($direcciones_grid->RowAction == "insert" && $direcciones->CurrentAction == "F" && $direcciones_grid->EmptyRow())) {
?>
	<tr<?php echo $direcciones->RowAttributes() ?>>
<?php

// Render list options (body, left)
$direcciones_grid->ListOptions->Render("body", "left", $direcciones_grid->RowCnt);
?>
	<?php if ($direcciones->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $direcciones->Id->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="direcciones" data-field="x_Id" name="o<?php echo $direcciones_grid->RowIndex ?>_Id" id="o<?php echo $direcciones_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($direcciones->Id->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_Id" class="form-group direcciones_Id">
<span<?php echo $direcciones->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_Id" name="x<?php echo $direcciones_grid->RowIndex ?>_Id" id="x<?php echo $direcciones_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($direcciones->Id->CurrentValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_Id" class="direcciones_Id">
<span<?php echo $direcciones->Id->ViewAttributes() ?>>
<?php echo $direcciones->Id->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_Id" name="x<?php echo $direcciones_grid->RowIndex ?>_Id" id="x<?php echo $direcciones_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($direcciones->Id->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_Id" name="o<?php echo $direcciones_grid->RowIndex ?>_Id" id="o<?php echo $direcciones_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($direcciones->Id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_Id" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_Id" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($direcciones->Id->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_Id" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_Id" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($direcciones->Id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->id_persona->Visible) { // id_persona ?>
		<td data-name="id_persona"<?php echo $direcciones->id_persona->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($direcciones->id_persona->getSessionValue() <> "") { ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_persona" class="form-group direcciones_id_persona">
<span<?php echo $direcciones->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($direcciones->id_persona->ViewValue)) && $direcciones->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $direcciones->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $direcciones->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $direcciones->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" name="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($direcciones->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_persona" class="form-group direcciones_id_persona">
<select data-table="direcciones" data-field="x_id_persona" data-value-separator="<?php echo $direcciones->id_persona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" name="x<?php echo $direcciones_grid->RowIndex ?>_id_persona"<?php echo $direcciones->id_persona->EditAttributes() ?>>
<?php echo $direcciones->id_persona->SelectOptionListHtml("x<?php echo $direcciones_grid->RowIndex ?>_id_persona") ?>
</select>
</span>
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_id_persona" name="o<?php echo $direcciones_grid->RowIndex ?>_id_persona" id="o<?php echo $direcciones_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($direcciones->id_persona->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($direcciones->id_persona->getSessionValue() <> "") { ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_persona" class="form-group direcciones_id_persona">
<span<?php echo $direcciones->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($direcciones->id_persona->ViewValue)) && $direcciones->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $direcciones->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $direcciones->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $direcciones->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" name="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($direcciones->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_persona" class="form-group direcciones_id_persona">
<select data-table="direcciones" data-field="x_id_persona" data-value-separator="<?php echo $direcciones->id_persona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" name="x<?php echo $direcciones_grid->RowIndex ?>_id_persona"<?php echo $direcciones->id_persona->EditAttributes() ?>>
<?php echo $direcciones->id_persona->SelectOptionListHtml("x<?php echo $direcciones_grid->RowIndex ?>_id_persona") ?>
</select>
</span>
<?php } ?>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_persona" class="direcciones_id_persona">
<span<?php echo $direcciones->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($direcciones->id_persona->ListViewValue())) && $direcciones->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $direcciones->id_persona->LinkAttributes() ?>><?php echo $direcciones->id_persona->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $direcciones->id_persona->ListViewValue() ?>
<?php } ?>
</span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_id_persona" name="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" id="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($direcciones->id_persona->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_id_persona" name="o<?php echo $direcciones_grid->RowIndex ?>_id_persona" id="o<?php echo $direcciones_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($direcciones->id_persona->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_id_persona" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_id_persona" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($direcciones->id_persona->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_id_persona" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_id_persona" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($direcciones->id_persona->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->direccion->Visible) { // direccion ?>
		<td data-name="direccion"<?php echo $direcciones->direccion->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion" class="form-group direcciones_direccion">
<input type="text" data-table="direcciones" data-field="x_direccion" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion->EditValue ?>"<?php echo $direcciones->direccion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_direccion" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($direcciones->direccion->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion" class="form-group direcciones_direccion">
<input type="text" data-table="direcciones" data-field="x_direccion" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion->EditValue ?>"<?php echo $direcciones->direccion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion" class="direcciones_direccion">
<span<?php echo $direcciones->direccion->ViewAttributes() ?>>
<?php echo $direcciones->direccion->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($direcciones->direccion->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_direccion" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($direcciones->direccion->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_direccion" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($direcciones->direccion->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_direccion" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_direccion" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($direcciones->direccion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->ult_fecha_activo->Visible) { // ult_fecha_activo ?>
		<td data-name="ult_fecha_activo"<?php echo $direcciones->ult_fecha_activo->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_ult_fecha_activo" class="form-group direcciones_ult_fecha_activo">
<input type="text" data-table="direcciones" data-field="x_ult_fecha_activo" data-format="7" name="x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" id="x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" size="20" placeholder="<?php echo ew_HtmlEncode($direcciones->ult_fecha_activo->getPlaceHolder()) ?>" value="<?php echo $direcciones->ult_fecha_activo->EditValue ?>"<?php echo $direcciones->ult_fecha_activo->EditAttributes() ?>>
<?php if (!$direcciones->ult_fecha_activo->ReadOnly && !$direcciones->ult_fecha_activo->Disabled && !isset($direcciones->ult_fecha_activo->EditAttrs["readonly"]) && !isset($direcciones->ult_fecha_activo->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdireccionesgrid", "x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="direcciones" data-field="x_ult_fecha_activo" name="o<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" id="o<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($direcciones->ult_fecha_activo->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_ult_fecha_activo" class="form-group direcciones_ult_fecha_activo">
<input type="text" data-table="direcciones" data-field="x_ult_fecha_activo" data-format="7" name="x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" id="x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" size="20" placeholder="<?php echo ew_HtmlEncode($direcciones->ult_fecha_activo->getPlaceHolder()) ?>" value="<?php echo $direcciones->ult_fecha_activo->EditValue ?>"<?php echo $direcciones->ult_fecha_activo->EditAttributes() ?>>
<?php if (!$direcciones->ult_fecha_activo->ReadOnly && !$direcciones->ult_fecha_activo->Disabled && !isset($direcciones->ult_fecha_activo->EditAttrs["readonly"]) && !isset($direcciones->ult_fecha_activo->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdireccionesgrid", "x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_ult_fecha_activo" class="direcciones_ult_fecha_activo">
<span<?php echo $direcciones->ult_fecha_activo->ViewAttributes() ?>>
<?php echo $direcciones->ult_fecha_activo->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_ult_fecha_activo" name="x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" id="x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($direcciones->ult_fecha_activo->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_ult_fecha_activo" name="o<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" id="o<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($direcciones->ult_fecha_activo->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_ult_fecha_activo" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($direcciones->ult_fecha_activo->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_ult_fecha_activo" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($direcciones->ult_fecha_activo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$direcciones_grid->ListOptions->Render("body", "right", $direcciones_grid->RowCnt);
?>
	</tr>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD || $direcciones->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdireccionesgrid.UpdateOpts(<?php echo $direcciones_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($direcciones->CurrentAction <> "gridadd" || $direcciones->CurrentMode == "copy")
		if (!$direcciones_grid->Recordset->EOF) $direcciones_grid->Recordset->MoveNext();
}
?>
<?php
	if ($direcciones->CurrentMode == "add" || $direcciones->CurrentMode == "copy" || $direcciones->CurrentMode == "edit") {
		$direcciones_grid->RowIndex = '$rowindex$';
		$direcciones_grid->LoadRowValues();

		// Set row properties
		$direcciones->ResetAttrs();
		$direcciones->RowAttrs = array_merge($direcciones->RowAttrs, array('data-rowindex'=>$direcciones_grid->RowIndex, 'id'=>'r0_direcciones', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($direcciones->RowAttrs["class"], "ewTemplate");
		$direcciones->RowType = EW_ROWTYPE_ADD;

		// Render row
		$direcciones_grid->RenderRow();

		// Render list options
		$direcciones_grid->RenderListOptions();
		$direcciones_grid->StartRowCnt = 0;
?>
	<tr<?php echo $direcciones->RowAttributes() ?>>
<?php

// Render list options (body, left)
$direcciones_grid->ListOptions->Render("body", "left", $direcciones_grid->RowIndex);
?>
	<?php if ($direcciones->Id->Visible) { // Id ?>
		<td data-name="Id">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_direcciones_Id" class="form-group direcciones_Id">
<span<?php echo $direcciones->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->Id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_Id" name="x<?php echo $direcciones_grid->RowIndex ?>_Id" id="x<?php echo $direcciones_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($direcciones->Id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_Id" name="o<?php echo $direcciones_grid->RowIndex ?>_Id" id="o<?php echo $direcciones_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($direcciones->Id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->id_persona->Visible) { // id_persona ?>
		<td data-name="id_persona">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<?php if ($direcciones->id_persona->getSessionValue() <> "") { ?>
<span id="el$rowindex$_direcciones_id_persona" class="form-group direcciones_id_persona">
<span<?php echo $direcciones->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($direcciones->id_persona->ViewValue)) && $direcciones->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $direcciones->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $direcciones->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $direcciones->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" name="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($direcciones->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_direcciones_id_persona" class="form-group direcciones_id_persona">
<select data-table="direcciones" data-field="x_id_persona" data-value-separator="<?php echo $direcciones->id_persona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" name="x<?php echo $direcciones_grid->RowIndex ?>_id_persona"<?php echo $direcciones->id_persona->EditAttributes() ?>>
<?php echo $direcciones->id_persona->SelectOptionListHtml("x<?php echo $direcciones_grid->RowIndex ?>_id_persona") ?>
</select>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_direcciones_id_persona" class="form-group direcciones_id_persona">
<span<?php echo $direcciones->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($direcciones->id_persona->ViewValue)) && $direcciones->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $direcciones->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $direcciones->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $direcciones->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_id_persona" name="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" id="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($direcciones->id_persona->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_id_persona" name="o<?php echo $direcciones_grid->RowIndex ?>_id_persona" id="o<?php echo $direcciones_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($direcciones->id_persona->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->direccion->Visible) { // direccion ?>
		<td data-name="direccion">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_direccion" class="form-group direcciones_direccion">
<input type="text" data-table="direcciones" data-field="x_direccion" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion->EditValue ?>"<?php echo $direcciones->direccion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_direccion" class="form-group direcciones_direccion">
<span<?php echo $direcciones->direccion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->direccion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_direccion" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($direcciones->direccion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion" value="<?php echo ew_HtmlEncode($direcciones->direccion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->ult_fecha_activo->Visible) { // ult_fecha_activo ?>
		<td data-name="ult_fecha_activo">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_ult_fecha_activo" class="form-group direcciones_ult_fecha_activo">
<input type="text" data-table="direcciones" data-field="x_ult_fecha_activo" data-format="7" name="x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" id="x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" size="20" placeholder="<?php echo ew_HtmlEncode($direcciones->ult_fecha_activo->getPlaceHolder()) ?>" value="<?php echo $direcciones->ult_fecha_activo->EditValue ?>"<?php echo $direcciones->ult_fecha_activo->EditAttributes() ?>>
<?php if (!$direcciones->ult_fecha_activo->ReadOnly && !$direcciones->ult_fecha_activo->Disabled && !isset($direcciones->ult_fecha_activo->EditAttrs["readonly"]) && !isset($direcciones->ult_fecha_activo->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdireccionesgrid", "x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_ult_fecha_activo" class="form-group direcciones_ult_fecha_activo">
<span<?php echo $direcciones->ult_fecha_activo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->ult_fecha_activo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_ult_fecha_activo" name="x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" id="x<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($direcciones->ult_fecha_activo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_ult_fecha_activo" name="o<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" id="o<?php echo $direcciones_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($direcciones->ult_fecha_activo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$direcciones_grid->ListOptions->Render("body", "right", $direcciones_grid->RowCnt);
?>
<script type="text/javascript">
fdireccionesgrid.UpdateOpts(<?php echo $direcciones_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($direcciones->CurrentMode == "add" || $direcciones->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $direcciones_grid->FormKeyCountName ?>" id="<?php echo $direcciones_grid->FormKeyCountName ?>" value="<?php echo $direcciones_grid->KeyCount ?>">
<?php echo $direcciones_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($direcciones->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $direcciones_grid->FormKeyCountName ?>" id="<?php echo $direcciones_grid->FormKeyCountName ?>" value="<?php echo $direcciones_grid->KeyCount ?>">
<?php echo $direcciones_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($direcciones->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdireccionesgrid">
</div>
<?php

// Close recordset
if ($direcciones_grid->Recordset)
	$direcciones_grid->Recordset->Close();
?>
<?php if ($direcciones_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($direcciones_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($direcciones_grid->TotalRecs == 0 && $direcciones->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($direcciones_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($direcciones->Export == "") { ?>
<script type="text/javascript">
fdireccionesgrid.Init();
</script>
<?php } ?>
<?php
$direcciones_grid->Page_Terminate();
?>
