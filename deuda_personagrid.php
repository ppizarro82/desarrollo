<?php include_once "usersinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($deuda_persona_grid)) $deuda_persona_grid = new cdeuda_persona_grid();

// Page init
$deuda_persona_grid->Page_Init();

// Page main
$deuda_persona_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$deuda_persona_grid->Page_Render();
?>
<?php if ($deuda_persona->Export == "") { ?>
<script type="text/javascript">

// Form object
var fdeuda_personagrid = new ew_Form("fdeuda_personagrid", "grid");
fdeuda_personagrid.FormKeyCountName = '<?php echo $deuda_persona_grid->FormKeyCountName ?>';

// Validate form
fdeuda_personagrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $deuda_persona->id_persona->FldCaption(), $deuda_persona->id_persona->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_deuda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $deuda_persona->id_deuda->FldCaption(), $deuda_persona->id_deuda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_tipopersona");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $deuda_persona->id_tipopersona->FldCaption(), $deuda_persona->id_tipopersona->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fdeuda_personagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_persona", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_deuda", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_tipopersona", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mig_codigo_cliente", false)) return false;
	return true;
}

// Form_CustomValidate event
fdeuda_personagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdeuda_personagrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdeuda_personagrid.Lists["x_id_persona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_paterno","x_materno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
fdeuda_personagrid.Lists["x_id_persona"].Data = "<?php echo $deuda_persona_grid->id_persona->LookupFilterQuery(FALSE, "grid") ?>";
fdeuda_personagrid.Lists["x_id_deuda"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_cuenta","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"deudas"};
fdeuda_personagrid.Lists["x_id_deuda"].Data = "<?php echo $deuda_persona_grid->id_deuda->LookupFilterQuery(FALSE, "grid") ?>";
fdeuda_personagrid.Lists["x_id_tipopersona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipo_persona"};
fdeuda_personagrid.Lists["x_id_tipopersona"].Data = "<?php echo $deuda_persona_grid->id_tipopersona->LookupFilterQuery(FALSE, "grid") ?>";

// Form object for search
</script>
<?php } ?>
<?php
if ($deuda_persona->CurrentAction == "gridadd") {
	if ($deuda_persona->CurrentMode == "copy") {
		$bSelectLimit = $deuda_persona_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$deuda_persona_grid->TotalRecs = $deuda_persona->ListRecordCount();
			$deuda_persona_grid->Recordset = $deuda_persona_grid->LoadRecordset($deuda_persona_grid->StartRec-1, $deuda_persona_grid->DisplayRecs);
		} else {
			if ($deuda_persona_grid->Recordset = $deuda_persona_grid->LoadRecordset())
				$deuda_persona_grid->TotalRecs = $deuda_persona_grid->Recordset->RecordCount();
		}
		$deuda_persona_grid->StartRec = 1;
		$deuda_persona_grid->DisplayRecs = $deuda_persona_grid->TotalRecs;
	} else {
		$deuda_persona->CurrentFilter = "0=1";
		$deuda_persona_grid->StartRec = 1;
		$deuda_persona_grid->DisplayRecs = $deuda_persona->GridAddRowCount;
	}
	$deuda_persona_grid->TotalRecs = $deuda_persona_grid->DisplayRecs;
	$deuda_persona_grid->StopRec = $deuda_persona_grid->DisplayRecs;
} else {
	$bSelectLimit = $deuda_persona_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($deuda_persona_grid->TotalRecs <= 0)
			$deuda_persona_grid->TotalRecs = $deuda_persona->ListRecordCount();
	} else {
		if (!$deuda_persona_grid->Recordset && ($deuda_persona_grid->Recordset = $deuda_persona_grid->LoadRecordset()))
			$deuda_persona_grid->TotalRecs = $deuda_persona_grid->Recordset->RecordCount();
	}
	$deuda_persona_grid->StartRec = 1;
	$deuda_persona_grid->DisplayRecs = $deuda_persona_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$deuda_persona_grid->Recordset = $deuda_persona_grid->LoadRecordset($deuda_persona_grid->StartRec-1, $deuda_persona_grid->DisplayRecs);

	// Set no record found message
	if ($deuda_persona->CurrentAction == "" && $deuda_persona_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$deuda_persona_grid->setWarningMessage(ew_DeniedMsg());
		if ($deuda_persona_grid->SearchWhere == "0=101")
			$deuda_persona_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$deuda_persona_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$deuda_persona_grid->RenderOtherOptions();
?>
<?php $deuda_persona_grid->ShowPageHeader(); ?>
<?php
$deuda_persona_grid->ShowMessage();
?>
<?php if ($deuda_persona_grid->TotalRecs > 0 || $deuda_persona->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($deuda_persona_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> deuda_persona">
<div id="fdeuda_personagrid" class="ewForm ewListForm form-inline">
<?php if ($deuda_persona_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($deuda_persona_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_deuda_persona" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_deuda_personagrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$deuda_persona_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$deuda_persona_grid->RenderListOptions();

// Render list options (header, left)
$deuda_persona_grid->ListOptions->Render("header", "left");
?>
<?php if ($deuda_persona->id_persona->Visible) { // id_persona ?>
	<?php if ($deuda_persona->SortUrl($deuda_persona->id_persona) == "") { ?>
		<th data-name="id_persona" class="<?php echo $deuda_persona->id_persona->HeaderCellClass() ?>"><div id="elh_deuda_persona_id_persona" class="deuda_persona_id_persona"><div class="ewTableHeaderCaption"><?php echo $deuda_persona->id_persona->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_persona" class="<?php echo $deuda_persona->id_persona->HeaderCellClass() ?>"><div><div id="elh_deuda_persona_id_persona" class="deuda_persona_id_persona">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deuda_persona->id_persona->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deuda_persona->id_persona->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deuda_persona->id_persona->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deuda_persona->id_deuda->Visible) { // id_deuda ?>
	<?php if ($deuda_persona->SortUrl($deuda_persona->id_deuda) == "") { ?>
		<th data-name="id_deuda" class="<?php echo $deuda_persona->id_deuda->HeaderCellClass() ?>"><div id="elh_deuda_persona_id_deuda" class="deuda_persona_id_deuda"><div class="ewTableHeaderCaption"><?php echo $deuda_persona->id_deuda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_deuda" class="<?php echo $deuda_persona->id_deuda->HeaderCellClass() ?>"><div><div id="elh_deuda_persona_id_deuda" class="deuda_persona_id_deuda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deuda_persona->id_deuda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deuda_persona->id_deuda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deuda_persona->id_deuda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deuda_persona->id_tipopersona->Visible) { // id_tipopersona ?>
	<?php if ($deuda_persona->SortUrl($deuda_persona->id_tipopersona) == "") { ?>
		<th data-name="id_tipopersona" class="<?php echo $deuda_persona->id_tipopersona->HeaderCellClass() ?>"><div id="elh_deuda_persona_id_tipopersona" class="deuda_persona_id_tipopersona"><div class="ewTableHeaderCaption"><?php echo $deuda_persona->id_tipopersona->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipopersona" class="<?php echo $deuda_persona->id_tipopersona->HeaderCellClass() ?>"><div><div id="elh_deuda_persona_id_tipopersona" class="deuda_persona_id_tipopersona">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deuda_persona->id_tipopersona->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deuda_persona->id_tipopersona->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deuda_persona->id_tipopersona->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deuda_persona->mig_codigo_cliente->Visible) { // mig_codigo_cliente ?>
	<?php if ($deuda_persona->SortUrl($deuda_persona->mig_codigo_cliente) == "") { ?>
		<th data-name="mig_codigo_cliente" class="<?php echo $deuda_persona->mig_codigo_cliente->HeaderCellClass() ?>"><div id="elh_deuda_persona_mig_codigo_cliente" class="deuda_persona_mig_codigo_cliente"><div class="ewTableHeaderCaption"><?php echo $deuda_persona->mig_codigo_cliente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_codigo_cliente" class="<?php echo $deuda_persona->mig_codigo_cliente->HeaderCellClass() ?>"><div><div id="elh_deuda_persona_mig_codigo_cliente" class="deuda_persona_mig_codigo_cliente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deuda_persona->mig_codigo_cliente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deuda_persona->mig_codigo_cliente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deuda_persona->mig_codigo_cliente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$deuda_persona_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$deuda_persona_grid->StartRec = 1;
$deuda_persona_grid->StopRec = $deuda_persona_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($deuda_persona_grid->FormKeyCountName) && ($deuda_persona->CurrentAction == "gridadd" || $deuda_persona->CurrentAction == "gridedit" || $deuda_persona->CurrentAction == "F")) {
		$deuda_persona_grid->KeyCount = $objForm->GetValue($deuda_persona_grid->FormKeyCountName);
		$deuda_persona_grid->StopRec = $deuda_persona_grid->StartRec + $deuda_persona_grid->KeyCount - 1;
	}
}
$deuda_persona_grid->RecCnt = $deuda_persona_grid->StartRec - 1;
if ($deuda_persona_grid->Recordset && !$deuda_persona_grid->Recordset->EOF) {
	$deuda_persona_grid->Recordset->MoveFirst();
	$bSelectLimit = $deuda_persona_grid->UseSelectLimit;
	if (!$bSelectLimit && $deuda_persona_grid->StartRec > 1)
		$deuda_persona_grid->Recordset->Move($deuda_persona_grid->StartRec - 1);
} elseif (!$deuda_persona->AllowAddDeleteRow && $deuda_persona_grid->StopRec == 0) {
	$deuda_persona_grid->StopRec = $deuda_persona->GridAddRowCount;
}

// Initialize aggregate
$deuda_persona->RowType = EW_ROWTYPE_AGGREGATEINIT;
$deuda_persona->ResetAttrs();
$deuda_persona_grid->RenderRow();
if ($deuda_persona->CurrentAction == "gridadd")
	$deuda_persona_grid->RowIndex = 0;
if ($deuda_persona->CurrentAction == "gridedit")
	$deuda_persona_grid->RowIndex = 0;
while ($deuda_persona_grid->RecCnt < $deuda_persona_grid->StopRec) {
	$deuda_persona_grid->RecCnt++;
	if (intval($deuda_persona_grid->RecCnt) >= intval($deuda_persona_grid->StartRec)) {
		$deuda_persona_grid->RowCnt++;
		if ($deuda_persona->CurrentAction == "gridadd" || $deuda_persona->CurrentAction == "gridedit" || $deuda_persona->CurrentAction == "F") {
			$deuda_persona_grid->RowIndex++;
			$objForm->Index = $deuda_persona_grid->RowIndex;
			if ($objForm->HasValue($deuda_persona_grid->FormActionName))
				$deuda_persona_grid->RowAction = strval($objForm->GetValue($deuda_persona_grid->FormActionName));
			elseif ($deuda_persona->CurrentAction == "gridadd")
				$deuda_persona_grid->RowAction = "insert";
			else
				$deuda_persona_grid->RowAction = "";
		}

		// Set up key count
		$deuda_persona_grid->KeyCount = $deuda_persona_grid->RowIndex;

		// Init row class and style
		$deuda_persona->ResetAttrs();
		$deuda_persona->CssClass = "";
		if ($deuda_persona->CurrentAction == "gridadd") {
			if ($deuda_persona->CurrentMode == "copy") {
				$deuda_persona_grid->LoadRowValues($deuda_persona_grid->Recordset); // Load row values
				$deuda_persona_grid->SetRecordKey($deuda_persona_grid->RowOldKey, $deuda_persona_grid->Recordset); // Set old record key
			} else {
				$deuda_persona_grid->LoadRowValues(); // Load default values
				$deuda_persona_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$deuda_persona_grid->LoadRowValues($deuda_persona_grid->Recordset); // Load row values
		}
		$deuda_persona->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($deuda_persona->CurrentAction == "gridadd") // Grid add
			$deuda_persona->RowType = EW_ROWTYPE_ADD; // Render add
		if ($deuda_persona->CurrentAction == "gridadd" && $deuda_persona->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$deuda_persona_grid->RestoreCurrentRowFormValues($deuda_persona_grid->RowIndex); // Restore form values
		if ($deuda_persona->CurrentAction == "gridedit") { // Grid edit
			if ($deuda_persona->EventCancelled) {
				$deuda_persona_grid->RestoreCurrentRowFormValues($deuda_persona_grid->RowIndex); // Restore form values
			}
			if ($deuda_persona_grid->RowAction == "insert")
				$deuda_persona->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$deuda_persona->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($deuda_persona->CurrentAction == "gridedit" && ($deuda_persona->RowType == EW_ROWTYPE_EDIT || $deuda_persona->RowType == EW_ROWTYPE_ADD) && $deuda_persona->EventCancelled) // Update failed
			$deuda_persona_grid->RestoreCurrentRowFormValues($deuda_persona_grid->RowIndex); // Restore form values
		if ($deuda_persona->RowType == EW_ROWTYPE_EDIT) // Edit row
			$deuda_persona_grid->EditRowCnt++;
		if ($deuda_persona->CurrentAction == "F") // Confirm row
			$deuda_persona_grid->RestoreCurrentRowFormValues($deuda_persona_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$deuda_persona->RowAttrs = array_merge($deuda_persona->RowAttrs, array('data-rowindex'=>$deuda_persona_grid->RowCnt, 'id'=>'r' . $deuda_persona_grid->RowCnt . '_deuda_persona', 'data-rowtype'=>$deuda_persona->RowType));

		// Render row
		$deuda_persona_grid->RenderRow();

		// Render list options
		$deuda_persona_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($deuda_persona_grid->RowAction <> "delete" && $deuda_persona_grid->RowAction <> "insertdelete" && !($deuda_persona_grid->RowAction == "insert" && $deuda_persona->CurrentAction == "F" && $deuda_persona_grid->EmptyRow())) {
?>
	<tr<?php echo $deuda_persona->RowAttributes() ?>>
<?php

// Render list options (body, left)
$deuda_persona_grid->ListOptions->Render("body", "left", $deuda_persona_grid->RowCnt);
?>
	<?php if ($deuda_persona->id_persona->Visible) { // id_persona ?>
		<td data-name="id_persona"<?php echo $deuda_persona->id_persona->CellAttributes() ?>>
<?php if ($deuda_persona->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($deuda_persona->id_persona->getSessionValue() <> "") { ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_id_persona" class="form-group deuda_persona_id_persona">
<span<?php echo $deuda_persona->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_persona->ViewValue)) && $deuda_persona->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $deuda_persona->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deuda_persona->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($deuda_persona->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_id_persona" class="form-group deuda_persona_id_persona">
<select data-table="deuda_persona" data-field="x_id_persona" data-value-separator="<?php echo $deuda_persona->id_persona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona"<?php echo $deuda_persona->id_persona->EditAttributes() ?>>
<?php echo $deuda_persona->id_persona->SelectOptionListHtml("x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona") ?>
</select>
</span>
<?php } ?>
<input type="hidden" data-table="deuda_persona" data-field="x_id_persona" name="o<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" id="o<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($deuda_persona->id_persona->OldValue) ?>">
<?php } ?>
<?php if ($deuda_persona->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_id_persona" class="form-group deuda_persona_id_persona">
<span<?php echo $deuda_persona->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_persona->EditValue)) && $deuda_persona->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $deuda_persona->id_persona->EditValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deuda_persona->id_persona->EditValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" data-table="deuda_persona" data-field="x_id_persona" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($deuda_persona->id_persona->CurrentValue) ?>">
<?php } ?>
<?php if ($deuda_persona->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_id_persona" class="deuda_persona_id_persona">
<span<?php echo $deuda_persona->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_persona->ListViewValue())) && $deuda_persona->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_persona->LinkAttributes() ?>><?php echo $deuda_persona->id_persona->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $deuda_persona->id_persona->ListViewValue() ?>
<?php } ?>
</span>
</span>
<?php if ($deuda_persona->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deuda_persona" data-field="x_id_persona" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($deuda_persona->id_persona->FormValue) ?>">
<input type="hidden" data-table="deuda_persona" data-field="x_id_persona" name="o<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" id="o<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($deuda_persona->id_persona->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deuda_persona" data-field="x_id_persona" name="fdeuda_personagrid$x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" id="fdeuda_personagrid$x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($deuda_persona->id_persona->FormValue) ?>">
<input type="hidden" data-table="deuda_persona" data-field="x_id_persona" name="fdeuda_personagrid$o<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" id="fdeuda_personagrid$o<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($deuda_persona->id_persona->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deuda_persona->id_deuda->Visible) { // id_deuda ?>
		<td data-name="id_deuda"<?php echo $deuda_persona->id_deuda->CellAttributes() ?>>
<?php if ($deuda_persona->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($deuda_persona->id_deuda->getSessionValue() <> "") { ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_id_deuda" class="form-group deuda_persona_id_deuda">
<span<?php echo $deuda_persona->id_deuda->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_deuda->ViewValue)) && $deuda_persona->id_deuda->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_deuda->LinkAttributes() ?>><p class="form-control-static"><?php echo $deuda_persona->id_deuda->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deuda_persona->id_deuda->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($deuda_persona->id_deuda->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_id_deuda" class="form-group deuda_persona_id_deuda">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda"><?php echo (strval($deuda_persona->id_deuda->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $deuda_persona->id_deuda->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($deuda_persona->id_deuda->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="deuda_persona" data-field="x_id_deuda" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $deuda_persona->id_deuda->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" value="<?php echo $deuda_persona->id_deuda->CurrentValue ?>"<?php echo $deuda_persona->id_deuda->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="deuda_persona" data-field="x_id_deuda" name="o<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" id="o<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($deuda_persona->id_deuda->OldValue) ?>">
<?php } ?>
<?php if ($deuda_persona->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_id_deuda" class="form-group deuda_persona_id_deuda">
<span<?php echo $deuda_persona->id_deuda->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_deuda->EditValue)) && $deuda_persona->id_deuda->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_deuda->LinkAttributes() ?>><p class="form-control-static"><?php echo $deuda_persona->id_deuda->EditValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deuda_persona->id_deuda->EditValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" data-table="deuda_persona" data-field="x_id_deuda" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($deuda_persona->id_deuda->CurrentValue) ?>">
<?php } ?>
<?php if ($deuda_persona->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_id_deuda" class="deuda_persona_id_deuda">
<span<?php echo $deuda_persona->id_deuda->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_deuda->ListViewValue())) && $deuda_persona->id_deuda->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_deuda->LinkAttributes() ?>><?php echo $deuda_persona->id_deuda->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $deuda_persona->id_deuda->ListViewValue() ?>
<?php } ?>
</span>
</span>
<?php if ($deuda_persona->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deuda_persona" data-field="x_id_deuda" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($deuda_persona->id_deuda->FormValue) ?>">
<input type="hidden" data-table="deuda_persona" data-field="x_id_deuda" name="o<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" id="o<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($deuda_persona->id_deuda->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deuda_persona" data-field="x_id_deuda" name="fdeuda_personagrid$x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" id="fdeuda_personagrid$x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($deuda_persona->id_deuda->FormValue) ?>">
<input type="hidden" data-table="deuda_persona" data-field="x_id_deuda" name="fdeuda_personagrid$o<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" id="fdeuda_personagrid$o<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($deuda_persona->id_deuda->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deuda_persona->id_tipopersona->Visible) { // id_tipopersona ?>
		<td data-name="id_tipopersona"<?php echo $deuda_persona->id_tipopersona->CellAttributes() ?>>
<?php if ($deuda_persona->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_id_tipopersona" class="form-group deuda_persona_id_tipopersona">
<select data-table="deuda_persona" data-field="x_id_tipopersona" data-value-separator="<?php echo $deuda_persona->id_tipopersona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona"<?php echo $deuda_persona->id_tipopersona->EditAttributes() ?>>
<?php echo $deuda_persona->id_tipopersona->SelectOptionListHtml("x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona") ?>
</select>
</span>
<input type="hidden" data-table="deuda_persona" data-field="x_id_tipopersona" name="o<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" id="o<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($deuda_persona->id_tipopersona->OldValue) ?>">
<?php } ?>
<?php if ($deuda_persona->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_id_tipopersona" class="form-group deuda_persona_id_tipopersona">
<select data-table="deuda_persona" data-field="x_id_tipopersona" data-value-separator="<?php echo $deuda_persona->id_tipopersona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona"<?php echo $deuda_persona->id_tipopersona->EditAttributes() ?>>
<?php echo $deuda_persona->id_tipopersona->SelectOptionListHtml("x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona") ?>
</select>
</span>
<?php } ?>
<?php if ($deuda_persona->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_id_tipopersona" class="deuda_persona_id_tipopersona">
<span<?php echo $deuda_persona->id_tipopersona->ViewAttributes() ?>>
<?php echo $deuda_persona->id_tipopersona->ListViewValue() ?></span>
</span>
<?php if ($deuda_persona->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deuda_persona" data-field="x_id_tipopersona" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($deuda_persona->id_tipopersona->FormValue) ?>">
<input type="hidden" data-table="deuda_persona" data-field="x_id_tipopersona" name="o<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" id="o<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($deuda_persona->id_tipopersona->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deuda_persona" data-field="x_id_tipopersona" name="fdeuda_personagrid$x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" id="fdeuda_personagrid$x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($deuda_persona->id_tipopersona->FormValue) ?>">
<input type="hidden" data-table="deuda_persona" data-field="x_id_tipopersona" name="fdeuda_personagrid$o<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" id="fdeuda_personagrid$o<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($deuda_persona->id_tipopersona->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deuda_persona->mig_codigo_cliente->Visible) { // mig_codigo_cliente ?>
		<td data-name="mig_codigo_cliente"<?php echo $deuda_persona->mig_codigo_cliente->CellAttributes() ?>>
<?php if ($deuda_persona->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_mig_codigo_cliente" class="form-group deuda_persona_mig_codigo_cliente">
<input type="text" data-table="deuda_persona" data-field="x_mig_codigo_cliente" name="x<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" id="x<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deuda_persona->mig_codigo_cliente->getPlaceHolder()) ?>" value="<?php echo $deuda_persona->mig_codigo_cliente->EditValue ?>"<?php echo $deuda_persona->mig_codigo_cliente->EditAttributes() ?>>
</span>
<input type="hidden" data-table="deuda_persona" data-field="x_mig_codigo_cliente" name="o<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" id="o<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" value="<?php echo ew_HtmlEncode($deuda_persona->mig_codigo_cliente->OldValue) ?>">
<?php } ?>
<?php if ($deuda_persona->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_mig_codigo_cliente" class="form-group deuda_persona_mig_codigo_cliente">
<input type="text" data-table="deuda_persona" data-field="x_mig_codigo_cliente" name="x<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" id="x<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deuda_persona->mig_codigo_cliente->getPlaceHolder()) ?>" value="<?php echo $deuda_persona->mig_codigo_cliente->EditValue ?>"<?php echo $deuda_persona->mig_codigo_cliente->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($deuda_persona->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deuda_persona_grid->RowCnt ?>_deuda_persona_mig_codigo_cliente" class="deuda_persona_mig_codigo_cliente">
<span<?php echo $deuda_persona->mig_codigo_cliente->ViewAttributes() ?>>
<?php echo $deuda_persona->mig_codigo_cliente->ListViewValue() ?></span>
</span>
<?php if ($deuda_persona->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deuda_persona" data-field="x_mig_codigo_cliente" name="x<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" id="x<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" value="<?php echo ew_HtmlEncode($deuda_persona->mig_codigo_cliente->FormValue) ?>">
<input type="hidden" data-table="deuda_persona" data-field="x_mig_codigo_cliente" name="o<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" id="o<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" value="<?php echo ew_HtmlEncode($deuda_persona->mig_codigo_cliente->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deuda_persona" data-field="x_mig_codigo_cliente" name="fdeuda_personagrid$x<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" id="fdeuda_personagrid$x<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" value="<?php echo ew_HtmlEncode($deuda_persona->mig_codigo_cliente->FormValue) ?>">
<input type="hidden" data-table="deuda_persona" data-field="x_mig_codigo_cliente" name="fdeuda_personagrid$o<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" id="fdeuda_personagrid$o<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" value="<?php echo ew_HtmlEncode($deuda_persona->mig_codigo_cliente->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$deuda_persona_grid->ListOptions->Render("body", "right", $deuda_persona_grid->RowCnt);
?>
	</tr>
<?php if ($deuda_persona->RowType == EW_ROWTYPE_ADD || $deuda_persona->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdeuda_personagrid.UpdateOpts(<?php echo $deuda_persona_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($deuda_persona->CurrentAction <> "gridadd" || $deuda_persona->CurrentMode == "copy")
		if (!$deuda_persona_grid->Recordset->EOF) $deuda_persona_grid->Recordset->MoveNext();
}
?>
<?php
	if ($deuda_persona->CurrentMode == "add" || $deuda_persona->CurrentMode == "copy" || $deuda_persona->CurrentMode == "edit") {
		$deuda_persona_grid->RowIndex = '$rowindex$';
		$deuda_persona_grid->LoadRowValues();

		// Set row properties
		$deuda_persona->ResetAttrs();
		$deuda_persona->RowAttrs = array_merge($deuda_persona->RowAttrs, array('data-rowindex'=>$deuda_persona_grid->RowIndex, 'id'=>'r0_deuda_persona', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($deuda_persona->RowAttrs["class"], "ewTemplate");
		$deuda_persona->RowType = EW_ROWTYPE_ADD;

		// Render row
		$deuda_persona_grid->RenderRow();

		// Render list options
		$deuda_persona_grid->RenderListOptions();
		$deuda_persona_grid->StartRowCnt = 0;
?>
	<tr<?php echo $deuda_persona->RowAttributes() ?>>
<?php

// Render list options (body, left)
$deuda_persona_grid->ListOptions->Render("body", "left", $deuda_persona_grid->RowIndex);
?>
	<?php if ($deuda_persona->id_persona->Visible) { // id_persona ?>
		<td data-name="id_persona">
<?php if ($deuda_persona->CurrentAction <> "F") { ?>
<?php if ($deuda_persona->id_persona->getSessionValue() <> "") { ?>
<span id="el$rowindex$_deuda_persona_id_persona" class="form-group deuda_persona_id_persona">
<span<?php echo $deuda_persona->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_persona->ViewValue)) && $deuda_persona->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $deuda_persona->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deuda_persona->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($deuda_persona->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_deuda_persona_id_persona" class="form-group deuda_persona_id_persona">
<select data-table="deuda_persona" data-field="x_id_persona" data-value-separator="<?php echo $deuda_persona->id_persona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona"<?php echo $deuda_persona->id_persona->EditAttributes() ?>>
<?php echo $deuda_persona->id_persona->SelectOptionListHtml("x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona") ?>
</select>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_deuda_persona_id_persona" class="form-group deuda_persona_id_persona">
<span<?php echo $deuda_persona->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_persona->ViewValue)) && $deuda_persona->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $deuda_persona->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deuda_persona->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" data-table="deuda_persona" data-field="x_id_persona" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($deuda_persona->id_persona->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deuda_persona" data-field="x_id_persona" name="o<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" id="o<?php echo $deuda_persona_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($deuda_persona->id_persona->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deuda_persona->id_deuda->Visible) { // id_deuda ?>
		<td data-name="id_deuda">
<?php if ($deuda_persona->CurrentAction <> "F") { ?>
<?php if ($deuda_persona->id_deuda->getSessionValue() <> "") { ?>
<span id="el$rowindex$_deuda_persona_id_deuda" class="form-group deuda_persona_id_deuda">
<span<?php echo $deuda_persona->id_deuda->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_deuda->ViewValue)) && $deuda_persona->id_deuda->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_deuda->LinkAttributes() ?>><p class="form-control-static"><?php echo $deuda_persona->id_deuda->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deuda_persona->id_deuda->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($deuda_persona->id_deuda->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_deuda_persona_id_deuda" class="form-group deuda_persona_id_deuda">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda"><?php echo (strval($deuda_persona->id_deuda->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $deuda_persona->id_deuda->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($deuda_persona->id_deuda->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="deuda_persona" data-field="x_id_deuda" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $deuda_persona->id_deuda->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" value="<?php echo $deuda_persona->id_deuda->CurrentValue ?>"<?php echo $deuda_persona->id_deuda->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_deuda_persona_id_deuda" class="form-group deuda_persona_id_deuda">
<span<?php echo $deuda_persona->id_deuda->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_deuda->ViewValue)) && $deuda_persona->id_deuda->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_deuda->LinkAttributes() ?>><p class="form-control-static"><?php echo $deuda_persona->id_deuda->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deuda_persona->id_deuda->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" data-table="deuda_persona" data-field="x_id_deuda" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($deuda_persona->id_deuda->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deuda_persona" data-field="x_id_deuda" name="o<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" id="o<?php echo $deuda_persona_grid->RowIndex ?>_id_deuda" value="<?php echo ew_HtmlEncode($deuda_persona->id_deuda->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deuda_persona->id_tipopersona->Visible) { // id_tipopersona ?>
		<td data-name="id_tipopersona">
<?php if ($deuda_persona->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deuda_persona_id_tipopersona" class="form-group deuda_persona_id_tipopersona">
<select data-table="deuda_persona" data-field="x_id_tipopersona" data-value-separator="<?php echo $deuda_persona->id_tipopersona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona"<?php echo $deuda_persona->id_tipopersona->EditAttributes() ?>>
<?php echo $deuda_persona->id_tipopersona->SelectOptionListHtml("x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_deuda_persona_id_tipopersona" class="form-group deuda_persona_id_tipopersona">
<span<?php echo $deuda_persona->id_tipopersona->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deuda_persona->id_tipopersona->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deuda_persona" data-field="x_id_tipopersona" name="x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" id="x<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($deuda_persona->id_tipopersona->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deuda_persona" data-field="x_id_tipopersona" name="o<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" id="o<?php echo $deuda_persona_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($deuda_persona->id_tipopersona->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deuda_persona->mig_codigo_cliente->Visible) { // mig_codigo_cliente ?>
		<td data-name="mig_codigo_cliente">
<?php if ($deuda_persona->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deuda_persona_mig_codigo_cliente" class="form-group deuda_persona_mig_codigo_cliente">
<input type="text" data-table="deuda_persona" data-field="x_mig_codigo_cliente" name="x<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" id="x<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deuda_persona->mig_codigo_cliente->getPlaceHolder()) ?>" value="<?php echo $deuda_persona->mig_codigo_cliente->EditValue ?>"<?php echo $deuda_persona->mig_codigo_cliente->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_deuda_persona_mig_codigo_cliente" class="form-group deuda_persona_mig_codigo_cliente">
<span<?php echo $deuda_persona->mig_codigo_cliente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deuda_persona->mig_codigo_cliente->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deuda_persona" data-field="x_mig_codigo_cliente" name="x<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" id="x<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" value="<?php echo ew_HtmlEncode($deuda_persona->mig_codigo_cliente->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deuda_persona" data-field="x_mig_codigo_cliente" name="o<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" id="o<?php echo $deuda_persona_grid->RowIndex ?>_mig_codigo_cliente" value="<?php echo ew_HtmlEncode($deuda_persona->mig_codigo_cliente->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$deuda_persona_grid->ListOptions->Render("body", "right", $deuda_persona_grid->RowCnt);
?>
<script type="text/javascript">
fdeuda_personagrid.UpdateOpts(<?php echo $deuda_persona_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($deuda_persona->CurrentMode == "add" || $deuda_persona->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $deuda_persona_grid->FormKeyCountName ?>" id="<?php echo $deuda_persona_grid->FormKeyCountName ?>" value="<?php echo $deuda_persona_grid->KeyCount ?>">
<?php echo $deuda_persona_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($deuda_persona->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $deuda_persona_grid->FormKeyCountName ?>" id="<?php echo $deuda_persona_grid->FormKeyCountName ?>" value="<?php echo $deuda_persona_grid->KeyCount ?>">
<?php echo $deuda_persona_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($deuda_persona->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdeuda_personagrid">
</div>
<?php

// Close recordset
if ($deuda_persona_grid->Recordset)
	$deuda_persona_grid->Recordset->Close();
?>
<?php if ($deuda_persona_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($deuda_persona_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($deuda_persona_grid->TotalRecs == 0 && $deuda_persona->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($deuda_persona_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($deuda_persona->Export == "") { ?>
<script type="text/javascript">
fdeuda_personagrid.Init();
</script>
<?php } ?>
<?php
$deuda_persona_grid->Page_Terminate();
?>
