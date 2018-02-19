<?php include_once "usersinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($deudas_grid)) $deudas_grid = new cdeudas_grid();

// Page init
$deudas_grid->Page_Init();

// Page main
$deudas_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$deudas_grid->Page_Render();
?>
<?php if ($deudas->Export == "") { ?>
<script type="text/javascript">

// Form object
var fdeudasgrid = new ew_Form("fdeudasgrid", "grid");
fdeudasgrid.FormKeyCountName = '<?php echo $deudas_grid->FormKeyCountName ?>';

// Validate form
fdeudasgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_codigo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $deudas->codigo->FldCaption(), $deudas->codigo->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fdeudasgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_cliente", false)) return false;
	if (ew_ValueChanged(fobj, infix, "codigo", false)) return false;
	return true;
}

// Form_CustomValidate event
fdeudasgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdeudasgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdeudasgrid.Lists["x_id_cliente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"cuentas"};
fdeudasgrid.Lists["x_id_cliente"].Data = "<?php echo $deudas_grid->id_cliente->LookupFilterQuery(FALSE, "grid") ?>";

// Form object for search
</script>
<?php } ?>
<?php
if ($deudas->CurrentAction == "gridadd") {
	if ($deudas->CurrentMode == "copy") {
		$bSelectLimit = $deudas_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$deudas_grid->TotalRecs = $deudas->ListRecordCount();
			$deudas_grid->Recordset = $deudas_grid->LoadRecordset($deudas_grid->StartRec-1, $deudas_grid->DisplayRecs);
		} else {
			if ($deudas_grid->Recordset = $deudas_grid->LoadRecordset())
				$deudas_grid->TotalRecs = $deudas_grid->Recordset->RecordCount();
		}
		$deudas_grid->StartRec = 1;
		$deudas_grid->DisplayRecs = $deudas_grid->TotalRecs;
	} else {
		$deudas->CurrentFilter = "0=1";
		$deudas_grid->StartRec = 1;
		$deudas_grid->DisplayRecs = $deudas->GridAddRowCount;
	}
	$deudas_grid->TotalRecs = $deudas_grid->DisplayRecs;
	$deudas_grid->StopRec = $deudas_grid->DisplayRecs;
} else {
	$bSelectLimit = $deudas_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($deudas_grid->TotalRecs <= 0)
			$deudas_grid->TotalRecs = $deudas->ListRecordCount();
	} else {
		if (!$deudas_grid->Recordset && ($deudas_grid->Recordset = $deudas_grid->LoadRecordset()))
			$deudas_grid->TotalRecs = $deudas_grid->Recordset->RecordCount();
	}
	$deudas_grid->StartRec = 1;
	$deudas_grid->DisplayRecs = $deudas_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$deudas_grid->Recordset = $deudas_grid->LoadRecordset($deudas_grid->StartRec-1, $deudas_grid->DisplayRecs);

	// Set no record found message
	if ($deudas->CurrentAction == "" && $deudas_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$deudas_grid->setWarningMessage(ew_DeniedMsg());
		if ($deudas_grid->SearchWhere == "0=101")
			$deudas_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$deudas_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$deudas_grid->RenderOtherOptions();
?>
<?php $deudas_grid->ShowPageHeader(); ?>
<?php
$deudas_grid->ShowMessage();
?>
<?php if ($deudas_grid->TotalRecs > 0 || $deudas->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($deudas_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> deudas">
<div id="fdeudasgrid" class="ewForm ewListForm form-inline">
<?php if ($deudas_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($deudas_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_deudas" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_deudasgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$deudas_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$deudas_grid->RenderListOptions();

// Render list options (header, left)
$deudas_grid->ListOptions->Render("header", "left");
?>
<?php if ($deudas->Id->Visible) { // Id ?>
	<?php if ($deudas->SortUrl($deudas->Id) == "") { ?>
		<th data-name="Id" class="<?php echo $deudas->Id->HeaderCellClass() ?>"><div id="elh_deudas_Id" class="deudas_Id"><div class="ewTableHeaderCaption"><?php echo $deudas->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id" class="<?php echo $deudas->Id->HeaderCellClass() ?>"><div><div id="elh_deudas_Id" class="deudas_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->id_cliente->Visible) { // id_cliente ?>
	<?php if ($deudas->SortUrl($deudas->id_cliente) == "") { ?>
		<th data-name="id_cliente" class="<?php echo $deudas->id_cliente->HeaderCellClass() ?>"><div id="elh_deudas_id_cliente" class="deudas_id_cliente"><div class="ewTableHeaderCaption"><?php echo $deudas->id_cliente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_cliente" class="<?php echo $deudas->id_cliente->HeaderCellClass() ?>"><div><div id="elh_deudas_id_cliente" class="deudas_id_cliente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->id_cliente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->id_cliente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->id_cliente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->codigo->Visible) { // codigo ?>
	<?php if ($deudas->SortUrl($deudas->codigo) == "") { ?>
		<th data-name="codigo" class="<?php echo $deudas->codigo->HeaderCellClass() ?>"><div id="elh_deudas_codigo" class="deudas_codigo"><div class="ewTableHeaderCaption"><?php echo $deudas->codigo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo" class="<?php echo $deudas->codigo->HeaderCellClass() ?>"><div><div id="elh_deudas_codigo" class="deudas_codigo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->codigo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->codigo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->codigo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$deudas_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$deudas_grid->StartRec = 1;
$deudas_grid->StopRec = $deudas_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($deudas_grid->FormKeyCountName) && ($deudas->CurrentAction == "gridadd" || $deudas->CurrentAction == "gridedit" || $deudas->CurrentAction == "F")) {
		$deudas_grid->KeyCount = $objForm->GetValue($deudas_grid->FormKeyCountName);
		$deudas_grid->StopRec = $deudas_grid->StartRec + $deudas_grid->KeyCount - 1;
	}
}
$deudas_grid->RecCnt = $deudas_grid->StartRec - 1;
if ($deudas_grid->Recordset && !$deudas_grid->Recordset->EOF) {
	$deudas_grid->Recordset->MoveFirst();
	$bSelectLimit = $deudas_grid->UseSelectLimit;
	if (!$bSelectLimit && $deudas_grid->StartRec > 1)
		$deudas_grid->Recordset->Move($deudas_grid->StartRec - 1);
} elseif (!$deudas->AllowAddDeleteRow && $deudas_grid->StopRec == 0) {
	$deudas_grid->StopRec = $deudas->GridAddRowCount;
}

// Initialize aggregate
$deudas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$deudas->ResetAttrs();
$deudas_grid->RenderRow();
if ($deudas->CurrentAction == "gridadd")
	$deudas_grid->RowIndex = 0;
if ($deudas->CurrentAction == "gridedit")
	$deudas_grid->RowIndex = 0;
while ($deudas_grid->RecCnt < $deudas_grid->StopRec) {
	$deudas_grid->RecCnt++;
	if (intval($deudas_grid->RecCnt) >= intval($deudas_grid->StartRec)) {
		$deudas_grid->RowCnt++;
		if ($deudas->CurrentAction == "gridadd" || $deudas->CurrentAction == "gridedit" || $deudas->CurrentAction == "F") {
			$deudas_grid->RowIndex++;
			$objForm->Index = $deudas_grid->RowIndex;
			if ($objForm->HasValue($deudas_grid->FormActionName))
				$deudas_grid->RowAction = strval($objForm->GetValue($deudas_grid->FormActionName));
			elseif ($deudas->CurrentAction == "gridadd")
				$deudas_grid->RowAction = "insert";
			else
				$deudas_grid->RowAction = "";
		}

		// Set up key count
		$deudas_grid->KeyCount = $deudas_grid->RowIndex;

		// Init row class and style
		$deudas->ResetAttrs();
		$deudas->CssClass = "";
		if ($deudas->CurrentAction == "gridadd") {
			if ($deudas->CurrentMode == "copy") {
				$deudas_grid->LoadRowValues($deudas_grid->Recordset); // Load row values
				$deudas_grid->SetRecordKey($deudas_grid->RowOldKey, $deudas_grid->Recordset); // Set old record key
			} else {
				$deudas_grid->LoadRowValues(); // Load default values
				$deudas_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$deudas_grid->LoadRowValues($deudas_grid->Recordset); // Load row values
		}
		$deudas->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($deudas->CurrentAction == "gridadd") // Grid add
			$deudas->RowType = EW_ROWTYPE_ADD; // Render add
		if ($deudas->CurrentAction == "gridadd" && $deudas->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$deudas_grid->RestoreCurrentRowFormValues($deudas_grid->RowIndex); // Restore form values
		if ($deudas->CurrentAction == "gridedit") { // Grid edit
			if ($deudas->EventCancelled) {
				$deudas_grid->RestoreCurrentRowFormValues($deudas_grid->RowIndex); // Restore form values
			}
			if ($deudas_grid->RowAction == "insert")
				$deudas->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$deudas->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($deudas->CurrentAction == "gridedit" && ($deudas->RowType == EW_ROWTYPE_EDIT || $deudas->RowType == EW_ROWTYPE_ADD) && $deudas->EventCancelled) // Update failed
			$deudas_grid->RestoreCurrentRowFormValues($deudas_grid->RowIndex); // Restore form values
		if ($deudas->RowType == EW_ROWTYPE_EDIT) // Edit row
			$deudas_grid->EditRowCnt++;
		if ($deudas->CurrentAction == "F") // Confirm row
			$deudas_grid->RestoreCurrentRowFormValues($deudas_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$deudas->RowAttrs = array_merge($deudas->RowAttrs, array('data-rowindex'=>$deudas_grid->RowCnt, 'id'=>'r' . $deudas_grid->RowCnt . '_deudas', 'data-rowtype'=>$deudas->RowType));

		// Render row
		$deudas_grid->RenderRow();

		// Render list options
		$deudas_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($deudas_grid->RowAction <> "delete" && $deudas_grid->RowAction <> "insertdelete" && !($deudas_grid->RowAction == "insert" && $deudas->CurrentAction == "F" && $deudas_grid->EmptyRow())) {
?>
	<tr<?php echo $deudas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$deudas_grid->ListOptions->Render("body", "left", $deudas_grid->RowCnt);
?>
	<?php if ($deudas->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $deudas->Id->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="deudas" data-field="x_Id" name="o<?php echo $deudas_grid->RowIndex ?>_Id" id="o<?php echo $deudas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($deudas->Id->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_Id" class="form-group deudas_Id">
<span<?php echo $deudas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_Id" name="x<?php echo $deudas_grid->RowIndex ?>_Id" id="x<?php echo $deudas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($deudas->Id->CurrentValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_Id" class="deudas_Id">
<span<?php echo $deudas->Id->ViewAttributes() ?>>
<?php echo $deudas->Id->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_Id" name="x<?php echo $deudas_grid->RowIndex ?>_Id" id="x<?php echo $deudas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($deudas->Id->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_Id" name="o<?php echo $deudas_grid->RowIndex ?>_Id" id="o<?php echo $deudas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($deudas->Id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_Id" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_Id" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($deudas->Id->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_Id" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_Id" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($deudas->Id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->id_cliente->Visible) { // id_cliente ?>
		<td data-name="id_cliente"<?php echo $deudas->id_cliente->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($deudas->id_cliente->getSessionValue() <> "") { ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_cliente" class="form-group deudas_id_cliente">
<span<?php echo $deudas->id_cliente->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deudas->id_cliente->ViewValue)) && $deudas->id_cliente->LinkAttributes() <> "") { ?>
<a<?php echo $deudas->id_cliente->LinkAttributes() ?>><p class="form-control-static"><?php echo $deudas->id_cliente->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deudas->id_cliente->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $deudas_grid->RowIndex ?>_id_cliente" name="x<?php echo $deudas_grid->RowIndex ?>_id_cliente" value="<?php echo ew_HtmlEncode($deudas->id_cliente->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_cliente" class="form-group deudas_id_cliente">
<select data-table="deudas" data-field="x_id_cliente" data-value-separator="<?php echo $deudas->id_cliente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_id_cliente" name="x<?php echo $deudas_grid->RowIndex ?>_id_cliente"<?php echo $deudas->id_cliente->EditAttributes() ?>>
<?php echo $deudas->id_cliente->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_id_cliente") ?>
</select>
</span>
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_id_cliente" name="o<?php echo $deudas_grid->RowIndex ?>_id_cliente" id="o<?php echo $deudas_grid->RowIndex ?>_id_cliente" value="<?php echo ew_HtmlEncode($deudas->id_cliente->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($deudas->id_cliente->getSessionValue() <> "") { ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_cliente" class="form-group deudas_id_cliente">
<span<?php echo $deudas->id_cliente->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deudas->id_cliente->ViewValue)) && $deudas->id_cliente->LinkAttributes() <> "") { ?>
<a<?php echo $deudas->id_cliente->LinkAttributes() ?>><p class="form-control-static"><?php echo $deudas->id_cliente->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deudas->id_cliente->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $deudas_grid->RowIndex ?>_id_cliente" name="x<?php echo $deudas_grid->RowIndex ?>_id_cliente" value="<?php echo ew_HtmlEncode($deudas->id_cliente->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_cliente" class="form-group deudas_id_cliente">
<select data-table="deudas" data-field="x_id_cliente" data-value-separator="<?php echo $deudas->id_cliente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_id_cliente" name="x<?php echo $deudas_grid->RowIndex ?>_id_cliente"<?php echo $deudas->id_cliente->EditAttributes() ?>>
<?php echo $deudas->id_cliente->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_id_cliente") ?>
</select>
</span>
<?php } ?>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_cliente" class="deudas_id_cliente">
<span<?php echo $deudas->id_cliente->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deudas->id_cliente->ListViewValue())) && $deudas->id_cliente->LinkAttributes() <> "") { ?>
<a<?php echo $deudas->id_cliente->LinkAttributes() ?>><?php echo $deudas->id_cliente->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $deudas->id_cliente->ListViewValue() ?>
<?php } ?>
</span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_id_cliente" name="x<?php echo $deudas_grid->RowIndex ?>_id_cliente" id="x<?php echo $deudas_grid->RowIndex ?>_id_cliente" value="<?php echo ew_HtmlEncode($deudas->id_cliente->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_id_cliente" name="o<?php echo $deudas_grid->RowIndex ?>_id_cliente" id="o<?php echo $deudas_grid->RowIndex ?>_id_cliente" value="<?php echo ew_HtmlEncode($deudas->id_cliente->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_id_cliente" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_id_cliente" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_id_cliente" value="<?php echo ew_HtmlEncode($deudas->id_cliente->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_id_cliente" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_id_cliente" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_id_cliente" value="<?php echo ew_HtmlEncode($deudas->id_cliente->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->codigo->Visible) { // codigo ?>
		<td data-name="codigo"<?php echo $deudas->codigo->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_codigo" class="form-group deudas_codigo">
<input type="text" data-table="deudas" data-field="x_codigo" name="x<?php echo $deudas_grid->RowIndex ?>_codigo" id="x<?php echo $deudas_grid->RowIndex ?>_codigo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->codigo->getPlaceHolder()) ?>" value="<?php echo $deudas->codigo->EditValue ?>"<?php echo $deudas->codigo->EditAttributes() ?>>
</span>
<input type="hidden" data-table="deudas" data-field="x_codigo" name="o<?php echo $deudas_grid->RowIndex ?>_codigo" id="o<?php echo $deudas_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($deudas->codigo->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_codigo" class="form-group deudas_codigo">
<input type="text" data-table="deudas" data-field="x_codigo" name="x<?php echo $deudas_grid->RowIndex ?>_codigo" id="x<?php echo $deudas_grid->RowIndex ?>_codigo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->codigo->getPlaceHolder()) ?>" value="<?php echo $deudas->codigo->EditValue ?>"<?php echo $deudas->codigo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_codigo" class="deudas_codigo">
<span<?php echo $deudas->codigo->ViewAttributes() ?>>
<?php echo $deudas->codigo->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_codigo" name="x<?php echo $deudas_grid->RowIndex ?>_codigo" id="x<?php echo $deudas_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($deudas->codigo->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_codigo" name="o<?php echo $deudas_grid->RowIndex ?>_codigo" id="o<?php echo $deudas_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($deudas->codigo->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_codigo" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_codigo" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($deudas->codigo->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_codigo" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_codigo" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($deudas->codigo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$deudas_grid->ListOptions->Render("body", "right", $deudas_grid->RowCnt);
?>
	</tr>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD || $deudas->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdeudasgrid.UpdateOpts(<?php echo $deudas_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($deudas->CurrentAction <> "gridadd" || $deudas->CurrentMode == "copy")
		if (!$deudas_grid->Recordset->EOF) $deudas_grid->Recordset->MoveNext();
}
?>
<?php
	if ($deudas->CurrentMode == "add" || $deudas->CurrentMode == "copy" || $deudas->CurrentMode == "edit") {
		$deudas_grid->RowIndex = '$rowindex$';
		$deudas_grid->LoadRowValues();

		// Set row properties
		$deudas->ResetAttrs();
		$deudas->RowAttrs = array_merge($deudas->RowAttrs, array('data-rowindex'=>$deudas_grid->RowIndex, 'id'=>'r0_deudas', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($deudas->RowAttrs["class"], "ewTemplate");
		$deudas->RowType = EW_ROWTYPE_ADD;

		// Render row
		$deudas_grid->RenderRow();

		// Render list options
		$deudas_grid->RenderListOptions();
		$deudas_grid->StartRowCnt = 0;
?>
	<tr<?php echo $deudas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$deudas_grid->ListOptions->Render("body", "left", $deudas_grid->RowIndex);
?>
	<?php if ($deudas->Id->Visible) { // Id ?>
		<td data-name="Id">
<?php if ($deudas->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_deudas_Id" class="form-group deudas_Id">
<span<?php echo $deudas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->Id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_Id" name="x<?php echo $deudas_grid->RowIndex ?>_Id" id="x<?php echo $deudas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($deudas->Id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_Id" name="o<?php echo $deudas_grid->RowIndex ?>_Id" id="o<?php echo $deudas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($deudas->Id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->id_cliente->Visible) { // id_cliente ?>
		<td data-name="id_cliente">
<?php if ($deudas->CurrentAction <> "F") { ?>
<?php if ($deudas->id_cliente->getSessionValue() <> "") { ?>
<span id="el$rowindex$_deudas_id_cliente" class="form-group deudas_id_cliente">
<span<?php echo $deudas->id_cliente->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deudas->id_cliente->ViewValue)) && $deudas->id_cliente->LinkAttributes() <> "") { ?>
<a<?php echo $deudas->id_cliente->LinkAttributes() ?>><p class="form-control-static"><?php echo $deudas->id_cliente->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deudas->id_cliente->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $deudas_grid->RowIndex ?>_id_cliente" name="x<?php echo $deudas_grid->RowIndex ?>_id_cliente" value="<?php echo ew_HtmlEncode($deudas->id_cliente->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_deudas_id_cliente" class="form-group deudas_id_cliente">
<select data-table="deudas" data-field="x_id_cliente" data-value-separator="<?php echo $deudas->id_cliente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_id_cliente" name="x<?php echo $deudas_grid->RowIndex ?>_id_cliente"<?php echo $deudas->id_cliente->EditAttributes() ?>>
<?php echo $deudas->id_cliente->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_id_cliente") ?>
</select>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_deudas_id_cliente" class="form-group deudas_id_cliente">
<span<?php echo $deudas->id_cliente->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deudas->id_cliente->ViewValue)) && $deudas->id_cliente->LinkAttributes() <> "") { ?>
<a<?php echo $deudas->id_cliente->LinkAttributes() ?>><p class="form-control-static"><?php echo $deudas->id_cliente->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deudas->id_cliente->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" data-table="deudas" data-field="x_id_cliente" name="x<?php echo $deudas_grid->RowIndex ?>_id_cliente" id="x<?php echo $deudas_grid->RowIndex ?>_id_cliente" value="<?php echo ew_HtmlEncode($deudas->id_cliente->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_id_cliente" name="o<?php echo $deudas_grid->RowIndex ?>_id_cliente" id="o<?php echo $deudas_grid->RowIndex ?>_id_cliente" value="<?php echo ew_HtmlEncode($deudas->id_cliente->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->codigo->Visible) { // codigo ?>
		<td data-name="codigo">
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_codigo" class="form-group deudas_codigo">
<input type="text" data-table="deudas" data-field="x_codigo" name="x<?php echo $deudas_grid->RowIndex ?>_codigo" id="x<?php echo $deudas_grid->RowIndex ?>_codigo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->codigo->getPlaceHolder()) ?>" value="<?php echo $deudas->codigo->EditValue ?>"<?php echo $deudas->codigo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_codigo" class="form-group deudas_codigo">
<span<?php echo $deudas->codigo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->codigo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_codigo" name="x<?php echo $deudas_grid->RowIndex ?>_codigo" id="x<?php echo $deudas_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($deudas->codigo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_codigo" name="o<?php echo $deudas_grid->RowIndex ?>_codigo" id="o<?php echo $deudas_grid->RowIndex ?>_codigo" value="<?php echo ew_HtmlEncode($deudas->codigo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$deudas_grid->ListOptions->Render("body", "right", $deudas_grid->RowCnt);
?>
<script type="text/javascript">
fdeudasgrid.UpdateOpts(<?php echo $deudas_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($deudas->CurrentMode == "add" || $deudas->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $deudas_grid->FormKeyCountName ?>" id="<?php echo $deudas_grid->FormKeyCountName ?>" value="<?php echo $deudas_grid->KeyCount ?>">
<?php echo $deudas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($deudas->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $deudas_grid->FormKeyCountName ?>" id="<?php echo $deudas_grid->FormKeyCountName ?>" value="<?php echo $deudas_grid->KeyCount ?>">
<?php echo $deudas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($deudas->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdeudasgrid">
</div>
<?php

// Close recordset
if ($deudas_grid->Recordset)
	$deudas_grid->Recordset->Close();
?>
<?php if ($deudas_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($deudas_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($deudas_grid->TotalRecs == 0 && $deudas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($deudas_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($deudas->Export == "") { ?>
<script type="text/javascript">
fdeudasgrid.Init();
</script>
<?php } ?>
<?php
$deudas_grid->Page_Terminate();
?>
