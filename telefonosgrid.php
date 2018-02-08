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
			elm = this.GetElements("x" + infix + "_tipo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $telefonos->tipo->FldCaption(), $telefonos->tipo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_numero");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $telefonos->numero->FldCaption(), $telefonos->numero->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ult_fecha_activo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $telefonos->ult_fecha_activo->FldCaption(), $telefonos->ult_fecha_activo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ult_fecha_activo");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($telefonos->ult_fecha_activo->FldErrMsg()) ?>");

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
	if (ew_ValueChanged(fobj, infix, "tipo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "numero", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ult_fecha_activo", false)) return false;
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
ftelefonosgrid.Lists["x_id_persona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_paterno","x_materno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
ftelefonosgrid.Lists["x_id_persona"].Data = "<?php echo $telefonos_grid->id_persona->LookupFilterQuery(FALSE, "grid") ?>";
ftelefonosgrid.Lists["x_tipo"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftelefonosgrid.Lists["x_tipo"].Options = <?php echo json_encode($telefonos_grid->tipo->Options()) ?>;

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
<?php if ($telefonos->tipo->Visible) { // tipo ?>
	<?php if ($telefonos->SortUrl($telefonos->tipo) == "") { ?>
		<th data-name="tipo" class="<?php echo $telefonos->tipo->HeaderCellClass() ?>"><div id="elh_telefonos_tipo" class="telefonos_tipo"><div class="ewTableHeaderCaption"><?php echo $telefonos->tipo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo" class="<?php echo $telefonos->tipo->HeaderCellClass() ?>"><div><div id="elh_telefonos_tipo" class="telefonos_tipo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->tipo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->tipo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->tipo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->numero->Visible) { // numero ?>
	<?php if ($telefonos->SortUrl($telefonos->numero) == "") { ?>
		<th data-name="numero" class="<?php echo $telefonos->numero->HeaderCellClass() ?>"><div id="elh_telefonos_numero" class="telefonos_numero"><div class="ewTableHeaderCaption"><?php echo $telefonos->numero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="numero" class="<?php echo $telefonos->numero->HeaderCellClass() ?>"><div><div id="elh_telefonos_numero" class="telefonos_numero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->numero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->numero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->numero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($telefonos->ult_fecha_activo->Visible) { // ult_fecha_activo ?>
	<?php if ($telefonos->SortUrl($telefonos->ult_fecha_activo) == "") { ?>
		<th data-name="ult_fecha_activo" class="<?php echo $telefonos->ult_fecha_activo->HeaderCellClass() ?>"><div id="elh_telefonos_ult_fecha_activo" class="telefonos_ult_fecha_activo"><div class="ewTableHeaderCaption"><?php echo $telefonos->ult_fecha_activo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ult_fecha_activo" class="<?php echo $telefonos->ult_fecha_activo->HeaderCellClass() ?>"><div><div id="elh_telefonos_ult_fecha_activo" class="telefonos_ult_fecha_activo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $telefonos->ult_fecha_activo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($telefonos->ult_fecha_activo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($telefonos->ult_fecha_activo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
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
<select data-table="telefonos" data-field="x_id_persona" data-value-separator="<?php echo $telefonos->id_persona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" name="x<?php echo $telefonos_grid->RowIndex ?>_id_persona"<?php echo $telefonos->id_persona->EditAttributes() ?>>
<?php echo $telefonos->id_persona->SelectOptionListHtml("x<?php echo $telefonos_grid->RowIndex ?>_id_persona") ?>
</select>
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
<select data-table="telefonos" data-field="x_id_persona" data-value-separator="<?php echo $telefonos->id_persona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" name="x<?php echo $telefonos_grid->RowIndex ?>_id_persona"<?php echo $telefonos->id_persona->EditAttributes() ?>>
<?php echo $telefonos->id_persona->SelectOptionListHtml("x<?php echo $telefonos_grid->RowIndex ?>_id_persona") ?>
</select>
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
	<?php if ($telefonos->tipo->Visible) { // tipo ?>
		<td data-name="tipo"<?php echo $telefonos->tipo->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_tipo" class="form-group telefonos_tipo">
<select data-table="telefonos" data-field="x_tipo" data-value-separator="<?php echo $telefonos->tipo->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $telefonos_grid->RowIndex ?>_tipo" name="x<?php echo $telefonos_grid->RowIndex ?>_tipo"<?php echo $telefonos->tipo->EditAttributes() ?>>
<?php echo $telefonos->tipo->SelectOptionListHtml("x<?php echo $telefonos_grid->RowIndex ?>_tipo") ?>
</select>
</span>
<input type="hidden" data-table="telefonos" data-field="x_tipo" name="o<?php echo $telefonos_grid->RowIndex ?>_tipo" id="o<?php echo $telefonos_grid->RowIndex ?>_tipo" value="<?php echo ew_HtmlEncode($telefonos->tipo->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_tipo" class="form-group telefonos_tipo">
<select data-table="telefonos" data-field="x_tipo" data-value-separator="<?php echo $telefonos->tipo->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $telefonos_grid->RowIndex ?>_tipo" name="x<?php echo $telefonos_grid->RowIndex ?>_tipo"<?php echo $telefonos->tipo->EditAttributes() ?>>
<?php echo $telefonos->tipo->SelectOptionListHtml("x<?php echo $telefonos_grid->RowIndex ?>_tipo") ?>
</select>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_tipo" class="telefonos_tipo">
<span<?php echo $telefonos->tipo->ViewAttributes() ?>>
<?php echo $telefonos->tipo->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_tipo" name="x<?php echo $telefonos_grid->RowIndex ?>_tipo" id="x<?php echo $telefonos_grid->RowIndex ?>_tipo" value="<?php echo ew_HtmlEncode($telefonos->tipo->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_tipo" name="o<?php echo $telefonos_grid->RowIndex ?>_tipo" id="o<?php echo $telefonos_grid->RowIndex ?>_tipo" value="<?php echo ew_HtmlEncode($telefonos->tipo->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_tipo" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_tipo" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_tipo" value="<?php echo ew_HtmlEncode($telefonos->tipo->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_tipo" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_tipo" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_tipo" value="<?php echo ew_HtmlEncode($telefonos->tipo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->numero->Visible) { // numero ?>
		<td data-name="numero"<?php echo $telefonos->numero->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_numero" class="form-group telefonos_numero">
<input type="text" data-table="telefonos" data-field="x_numero" name="x<?php echo $telefonos_grid->RowIndex ?>_numero" id="x<?php echo $telefonos_grid->RowIndex ?>_numero" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->numero->getPlaceHolder()) ?>" value="<?php echo $telefonos->numero->EditValue ?>"<?php echo $telefonos->numero->EditAttributes() ?>>
</span>
<input type="hidden" data-table="telefonos" data-field="x_numero" name="o<?php echo $telefonos_grid->RowIndex ?>_numero" id="o<?php echo $telefonos_grid->RowIndex ?>_numero" value="<?php echo ew_HtmlEncode($telefonos->numero->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_numero" class="form-group telefonos_numero">
<input type="text" data-table="telefonos" data-field="x_numero" name="x<?php echo $telefonos_grid->RowIndex ?>_numero" id="x<?php echo $telefonos_grid->RowIndex ?>_numero" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->numero->getPlaceHolder()) ?>" value="<?php echo $telefonos->numero->EditValue ?>"<?php echo $telefonos->numero->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_numero" class="telefonos_numero">
<span<?php echo $telefonos->numero->ViewAttributes() ?>>
<?php echo $telefonos->numero->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_numero" name="x<?php echo $telefonos_grid->RowIndex ?>_numero" id="x<?php echo $telefonos_grid->RowIndex ?>_numero" value="<?php echo ew_HtmlEncode($telefonos->numero->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_numero" name="o<?php echo $telefonos_grid->RowIndex ?>_numero" id="o<?php echo $telefonos_grid->RowIndex ?>_numero" value="<?php echo ew_HtmlEncode($telefonos->numero->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_numero" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_numero" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_numero" value="<?php echo ew_HtmlEncode($telefonos->numero->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_numero" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_numero" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_numero" value="<?php echo ew_HtmlEncode($telefonos->numero->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($telefonos->ult_fecha_activo->Visible) { // ult_fecha_activo ?>
		<td data-name="ult_fecha_activo"<?php echo $telefonos->ult_fecha_activo->CellAttributes() ?>>
<?php if ($telefonos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_ult_fecha_activo" class="form-group telefonos_ult_fecha_activo">
<input type="text" data-table="telefonos" data-field="x_ult_fecha_activo" data-format="7" name="x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" id="x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" size="20" placeholder="<?php echo ew_HtmlEncode($telefonos->ult_fecha_activo->getPlaceHolder()) ?>" value="<?php echo $telefonos->ult_fecha_activo->EditValue ?>"<?php echo $telefonos->ult_fecha_activo->EditAttributes() ?>>
<?php if (!$telefonos->ult_fecha_activo->ReadOnly && !$telefonos->ult_fecha_activo->Disabled && !isset($telefonos->ult_fecha_activo->EditAttrs["readonly"]) && !isset($telefonos->ult_fecha_activo->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("ftelefonosgrid", "x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="telefonos" data-field="x_ult_fecha_activo" name="o<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" id="o<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($telefonos->ult_fecha_activo->OldValue) ?>">
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_ult_fecha_activo" class="form-group telefonos_ult_fecha_activo">
<input type="text" data-table="telefonos" data-field="x_ult_fecha_activo" data-format="7" name="x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" id="x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" size="20" placeholder="<?php echo ew_HtmlEncode($telefonos->ult_fecha_activo->getPlaceHolder()) ?>" value="<?php echo $telefonos->ult_fecha_activo->EditValue ?>"<?php echo $telefonos->ult_fecha_activo->EditAttributes() ?>>
<?php if (!$telefonos->ult_fecha_activo->ReadOnly && !$telefonos->ult_fecha_activo->Disabled && !isset($telefonos->ult_fecha_activo->EditAttrs["readonly"]) && !isset($telefonos->ult_fecha_activo->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("ftelefonosgrid", "x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($telefonos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $telefonos_grid->RowCnt ?>_telefonos_ult_fecha_activo" class="telefonos_ult_fecha_activo">
<span<?php echo $telefonos->ult_fecha_activo->ViewAttributes() ?>>
<?php echo $telefonos->ult_fecha_activo->ListViewValue() ?></span>
</span>
<?php if ($telefonos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="telefonos" data-field="x_ult_fecha_activo" name="x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" id="x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($telefonos->ult_fecha_activo->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_ult_fecha_activo" name="o<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" id="o<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($telefonos->ult_fecha_activo->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="telefonos" data-field="x_ult_fecha_activo" name="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" id="ftelefonosgrid$x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($telefonos->ult_fecha_activo->FormValue) ?>">
<input type="hidden" data-table="telefonos" data-field="x_ult_fecha_activo" name="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" id="ftelefonosgrid$o<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($telefonos->ult_fecha_activo->OldValue) ?>">
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
<select data-table="telefonos" data-field="x_id_persona" data-value-separator="<?php echo $telefonos->id_persona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $telefonos_grid->RowIndex ?>_id_persona" name="x<?php echo $telefonos_grid->RowIndex ?>_id_persona"<?php echo $telefonos->id_persona->EditAttributes() ?>>
<?php echo $telefonos->id_persona->SelectOptionListHtml("x<?php echo $telefonos_grid->RowIndex ?>_id_persona") ?>
</select>
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
	<?php if ($telefonos->tipo->Visible) { // tipo ?>
		<td data-name="tipo">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_tipo" class="form-group telefonos_tipo">
<select data-table="telefonos" data-field="x_tipo" data-value-separator="<?php echo $telefonos->tipo->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $telefonos_grid->RowIndex ?>_tipo" name="x<?php echo $telefonos_grid->RowIndex ?>_tipo"<?php echo $telefonos->tipo->EditAttributes() ?>>
<?php echo $telefonos->tipo->SelectOptionListHtml("x<?php echo $telefonos_grid->RowIndex ?>_tipo") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_tipo" class="form-group telefonos_tipo">
<span<?php echo $telefonos->tipo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->tipo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_tipo" name="x<?php echo $telefonos_grid->RowIndex ?>_tipo" id="x<?php echo $telefonos_grid->RowIndex ?>_tipo" value="<?php echo ew_HtmlEncode($telefonos->tipo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_tipo" name="o<?php echo $telefonos_grid->RowIndex ?>_tipo" id="o<?php echo $telefonos_grid->RowIndex ?>_tipo" value="<?php echo ew_HtmlEncode($telefonos->tipo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->numero->Visible) { // numero ?>
		<td data-name="numero">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_numero" class="form-group telefonos_numero">
<input type="text" data-table="telefonos" data-field="x_numero" name="x<?php echo $telefonos_grid->RowIndex ?>_numero" id="x<?php echo $telefonos_grid->RowIndex ?>_numero" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->numero->getPlaceHolder()) ?>" value="<?php echo $telefonos->numero->EditValue ?>"<?php echo $telefonos->numero->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_numero" class="form-group telefonos_numero">
<span<?php echo $telefonos->numero->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->numero->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_numero" name="x<?php echo $telefonos_grid->RowIndex ?>_numero" id="x<?php echo $telefonos_grid->RowIndex ?>_numero" value="<?php echo ew_HtmlEncode($telefonos->numero->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_numero" name="o<?php echo $telefonos_grid->RowIndex ?>_numero" id="o<?php echo $telefonos_grid->RowIndex ?>_numero" value="<?php echo ew_HtmlEncode($telefonos->numero->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($telefonos->ult_fecha_activo->Visible) { // ult_fecha_activo ?>
		<td data-name="ult_fecha_activo">
<?php if ($telefonos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_telefonos_ult_fecha_activo" class="form-group telefonos_ult_fecha_activo">
<input type="text" data-table="telefonos" data-field="x_ult_fecha_activo" data-format="7" name="x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" id="x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" size="20" placeholder="<?php echo ew_HtmlEncode($telefonos->ult_fecha_activo->getPlaceHolder()) ?>" value="<?php echo $telefonos->ult_fecha_activo->EditValue ?>"<?php echo $telefonos->ult_fecha_activo->EditAttributes() ?>>
<?php if (!$telefonos->ult_fecha_activo->ReadOnly && !$telefonos->ult_fecha_activo->Disabled && !isset($telefonos->ult_fecha_activo->EditAttrs["readonly"]) && !isset($telefonos->ult_fecha_activo->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("ftelefonosgrid", "x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_telefonos_ult_fecha_activo" class="form-group telefonos_ult_fecha_activo">
<span<?php echo $telefonos->ult_fecha_activo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $telefonos->ult_fecha_activo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="telefonos" data-field="x_ult_fecha_activo" name="x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" id="x<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($telefonos->ult_fecha_activo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="telefonos" data-field="x_ult_fecha_activo" name="o<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" id="o<?php echo $telefonos_grid->RowIndex ?>_ult_fecha_activo" value="<?php echo ew_HtmlEncode($telefonos->ult_fecha_activo->OldValue) ?>">
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
