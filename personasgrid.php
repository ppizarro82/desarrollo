<?php include_once "usersinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($personas_grid)) $personas_grid = new cpersonas_grid();

// Page init
$personas_grid->Page_Init();

// Page main
$personas_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$personas_grid->Page_Render();
?>
<?php if ($personas->Export == "") { ?>
<script type="text/javascript">

// Form object
var fpersonasgrid = new ew_Form("fpersonasgrid", "grid");
fpersonasgrid.FormKeyCountName = '<?php echo $personas_grid->FormKeyCountName ?>';

// Validate form
fpersonasgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_tipopersona");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $personas->id_tipopersona->FldCaption(), $personas->id_tipopersona->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombres");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $personas->nombres->FldCaption(), $personas->nombres->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_paterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $personas->paterno->FldCaption(), $personas->paterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_materno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $personas->materno->FldCaption(), $personas->materno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_nacimiento");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($personas->fecha_nacimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_registro");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $personas->fecha_registro->FldCaption(), $personas->fecha_registro->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_registro");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($personas->fecha_registro->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $personas->estado->FldCaption(), $personas->estado->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fpersonasgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_tipopersona", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tipo_documento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "no_documento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nombres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "paterno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "materno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha_nacimiento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fecha_registro", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estado", false)) return false;
	return true;
}

// Form_CustomValidate event
fpersonasgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpersonasgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fpersonasgrid.Lists["x_id_tipopersona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipo_persona"};
fpersonasgrid.Lists["x_id_tipopersona"].Data = "<?php echo $personas_grid->id_tipopersona->LookupFilterQuery(FALSE, "grid") ?>";
fpersonasgrid.Lists["x_tipo_documento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpersonasgrid.Lists["x_tipo_documento"].Options = <?php echo json_encode($personas_grid->tipo_documento->Options()) ?>;
fpersonasgrid.Lists["x_estado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpersonasgrid.Lists["x_estado"].Options = <?php echo json_encode($personas_grid->estado->Options()) ?>;

// Form object for search
</script>
<?php } ?>
<?php
if ($personas->CurrentAction == "gridadd") {
	if ($personas->CurrentMode == "copy") {
		$bSelectLimit = $personas_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$personas_grid->TotalRecs = $personas->ListRecordCount();
			$personas_grid->Recordset = $personas_grid->LoadRecordset($personas_grid->StartRec-1, $personas_grid->DisplayRecs);
		} else {
			if ($personas_grid->Recordset = $personas_grid->LoadRecordset())
				$personas_grid->TotalRecs = $personas_grid->Recordset->RecordCount();
		}
		$personas_grid->StartRec = 1;
		$personas_grid->DisplayRecs = $personas_grid->TotalRecs;
	} else {
		$personas->CurrentFilter = "0=1";
		$personas_grid->StartRec = 1;
		$personas_grid->DisplayRecs = $personas->GridAddRowCount;
	}
	$personas_grid->TotalRecs = $personas_grid->DisplayRecs;
	$personas_grid->StopRec = $personas_grid->DisplayRecs;
} else {
	$bSelectLimit = $personas_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($personas_grid->TotalRecs <= 0)
			$personas_grid->TotalRecs = $personas->ListRecordCount();
	} else {
		if (!$personas_grid->Recordset && ($personas_grid->Recordset = $personas_grid->LoadRecordset()))
			$personas_grid->TotalRecs = $personas_grid->Recordset->RecordCount();
	}
	$personas_grid->StartRec = 1;
	$personas_grid->DisplayRecs = $personas_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$personas_grid->Recordset = $personas_grid->LoadRecordset($personas_grid->StartRec-1, $personas_grid->DisplayRecs);

	// Set no record found message
	if ($personas->CurrentAction == "" && $personas_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$personas_grid->setWarningMessage(ew_DeniedMsg());
		if ($personas_grid->SearchWhere == "0=101")
			$personas_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$personas_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$personas_grid->RenderOtherOptions();
?>
<?php $personas_grid->ShowPageHeader(); ?>
<?php
$personas_grid->ShowMessage();
?>
<?php if ($personas_grid->TotalRecs > 0 || $personas->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($personas_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> personas">
<div id="fpersonasgrid" class="ewForm ewListForm form-inline">
<?php if ($personas_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($personas_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_personas" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_personasgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$personas_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$personas_grid->RenderListOptions();

// Render list options (header, left)
$personas_grid->ListOptions->Render("header", "left");
?>
<?php if ($personas->Id->Visible) { // Id ?>
	<?php if ($personas->SortUrl($personas->Id) == "") { ?>
		<th data-name="Id" class="<?php echo $personas->Id->HeaderCellClass() ?>"><div id="elh_personas_Id" class="personas_Id"><div class="ewTableHeaderCaption"><?php echo $personas->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id" class="<?php echo $personas->Id->HeaderCellClass() ?>"><div><div id="elh_personas_Id" class="personas_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->id_tipopersona->Visible) { // id_tipopersona ?>
	<?php if ($personas->SortUrl($personas->id_tipopersona) == "") { ?>
		<th data-name="id_tipopersona" class="<?php echo $personas->id_tipopersona->HeaderCellClass() ?>"><div id="elh_personas_id_tipopersona" class="personas_id_tipopersona"><div class="ewTableHeaderCaption"><?php echo $personas->id_tipopersona->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipopersona" class="<?php echo $personas->id_tipopersona->HeaderCellClass() ?>"><div><div id="elh_personas_id_tipopersona" class="personas_id_tipopersona">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->id_tipopersona->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->id_tipopersona->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->id_tipopersona->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->tipo_documento->Visible) { // tipo_documento ?>
	<?php if ($personas->SortUrl($personas->tipo_documento) == "") { ?>
		<th data-name="tipo_documento" class="<?php echo $personas->tipo_documento->HeaderCellClass() ?>"><div id="elh_personas_tipo_documento" class="personas_tipo_documento"><div class="ewTableHeaderCaption"><?php echo $personas->tipo_documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo_documento" class="<?php echo $personas->tipo_documento->HeaderCellClass() ?>"><div><div id="elh_personas_tipo_documento" class="personas_tipo_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->tipo_documento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->tipo_documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->tipo_documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->no_documento->Visible) { // no_documento ?>
	<?php if ($personas->SortUrl($personas->no_documento) == "") { ?>
		<th data-name="no_documento" class="<?php echo $personas->no_documento->HeaderCellClass() ?>"><div id="elh_personas_no_documento" class="personas_no_documento"><div class="ewTableHeaderCaption"><?php echo $personas->no_documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="no_documento" class="<?php echo $personas->no_documento->HeaderCellClass() ?>"><div><div id="elh_personas_no_documento" class="personas_no_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->no_documento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->no_documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->no_documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->nombres->Visible) { // nombres ?>
	<?php if ($personas->SortUrl($personas->nombres) == "") { ?>
		<th data-name="nombres" class="<?php echo $personas->nombres->HeaderCellClass() ?>"><div id="elh_personas_nombres" class="personas_nombres"><div class="ewTableHeaderCaption"><?php echo $personas->nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombres" class="<?php echo $personas->nombres->HeaderCellClass() ?>"><div><div id="elh_personas_nombres" class="personas_nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->nombres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->paterno->Visible) { // paterno ?>
	<?php if ($personas->SortUrl($personas->paterno) == "") { ?>
		<th data-name="paterno" class="<?php echo $personas->paterno->HeaderCellClass() ?>"><div id="elh_personas_paterno" class="personas_paterno"><div class="ewTableHeaderCaption"><?php echo $personas->paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="paterno" class="<?php echo $personas->paterno->HeaderCellClass() ?>"><div><div id="elh_personas_paterno" class="personas_paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->paterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->materno->Visible) { // materno ?>
	<?php if ($personas->SortUrl($personas->materno) == "") { ?>
		<th data-name="materno" class="<?php echo $personas->materno->HeaderCellClass() ?>"><div id="elh_personas_materno" class="personas_materno"><div class="ewTableHeaderCaption"><?php echo $personas->materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="materno" class="<?php echo $personas->materno->HeaderCellClass() ?>"><div><div id="elh_personas_materno" class="personas_materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->materno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<?php if ($personas->SortUrl($personas->fecha_nacimiento) == "") { ?>
		<th data-name="fecha_nacimiento" class="<?php echo $personas->fecha_nacimiento->HeaderCellClass() ?>"><div id="elh_personas_fecha_nacimiento" class="personas_fecha_nacimiento"><div class="ewTableHeaderCaption"><?php echo $personas->fecha_nacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_nacimiento" class="<?php echo $personas->fecha_nacimiento->HeaderCellClass() ?>"><div><div id="elh_personas_fecha_nacimiento" class="personas_fecha_nacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->fecha_nacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->fecha_nacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->fecha_nacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->fecha_registro->Visible) { // fecha_registro ?>
	<?php if ($personas->SortUrl($personas->fecha_registro) == "") { ?>
		<th data-name="fecha_registro" class="<?php echo $personas->fecha_registro->HeaderCellClass() ?>"><div id="elh_personas_fecha_registro" class="personas_fecha_registro"><div class="ewTableHeaderCaption"><?php echo $personas->fecha_registro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_registro" class="<?php echo $personas->fecha_registro->HeaderCellClass() ?>"><div><div id="elh_personas_fecha_registro" class="personas_fecha_registro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->fecha_registro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->fecha_registro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->fecha_registro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->estado->Visible) { // estado ?>
	<?php if ($personas->SortUrl($personas->estado) == "") { ?>
		<th data-name="estado" class="<?php echo $personas->estado->HeaderCellClass() ?>"><div id="elh_personas_estado" class="personas_estado"><div class="ewTableHeaderCaption"><?php echo $personas->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado" class="<?php echo $personas->estado->HeaderCellClass() ?>"><div><div id="elh_personas_estado" class="personas_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$personas_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$personas_grid->StartRec = 1;
$personas_grid->StopRec = $personas_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($personas_grid->FormKeyCountName) && ($personas->CurrentAction == "gridadd" || $personas->CurrentAction == "gridedit" || $personas->CurrentAction == "F")) {
		$personas_grid->KeyCount = $objForm->GetValue($personas_grid->FormKeyCountName);
		$personas_grid->StopRec = $personas_grid->StartRec + $personas_grid->KeyCount - 1;
	}
}
$personas_grid->RecCnt = $personas_grid->StartRec - 1;
if ($personas_grid->Recordset && !$personas_grid->Recordset->EOF) {
	$personas_grid->Recordset->MoveFirst();
	$bSelectLimit = $personas_grid->UseSelectLimit;
	if (!$bSelectLimit && $personas_grid->StartRec > 1)
		$personas_grid->Recordset->Move($personas_grid->StartRec - 1);
} elseif (!$personas->AllowAddDeleteRow && $personas_grid->StopRec == 0) {
	$personas_grid->StopRec = $personas->GridAddRowCount;
}

// Initialize aggregate
$personas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$personas->ResetAttrs();
$personas_grid->RenderRow();
if ($personas->CurrentAction == "gridadd")
	$personas_grid->RowIndex = 0;
if ($personas->CurrentAction == "gridedit")
	$personas_grid->RowIndex = 0;
while ($personas_grid->RecCnt < $personas_grid->StopRec) {
	$personas_grid->RecCnt++;
	if (intval($personas_grid->RecCnt) >= intval($personas_grid->StartRec)) {
		$personas_grid->RowCnt++;
		if ($personas->CurrentAction == "gridadd" || $personas->CurrentAction == "gridedit" || $personas->CurrentAction == "F") {
			$personas_grid->RowIndex++;
			$objForm->Index = $personas_grid->RowIndex;
			if ($objForm->HasValue($personas_grid->FormActionName))
				$personas_grid->RowAction = strval($objForm->GetValue($personas_grid->FormActionName));
			elseif ($personas->CurrentAction == "gridadd")
				$personas_grid->RowAction = "insert";
			else
				$personas_grid->RowAction = "";
		}

		// Set up key count
		$personas_grid->KeyCount = $personas_grid->RowIndex;

		// Init row class and style
		$personas->ResetAttrs();
		$personas->CssClass = "";
		if ($personas->CurrentAction == "gridadd") {
			if ($personas->CurrentMode == "copy") {
				$personas_grid->LoadRowValues($personas_grid->Recordset); // Load row values
				$personas_grid->SetRecordKey($personas_grid->RowOldKey, $personas_grid->Recordset); // Set old record key
			} else {
				$personas_grid->LoadRowValues(); // Load default values
				$personas_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$personas_grid->LoadRowValues($personas_grid->Recordset); // Load row values
		}
		$personas->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($personas->CurrentAction == "gridadd") // Grid add
			$personas->RowType = EW_ROWTYPE_ADD; // Render add
		if ($personas->CurrentAction == "gridadd" && $personas->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$personas_grid->RestoreCurrentRowFormValues($personas_grid->RowIndex); // Restore form values
		if ($personas->CurrentAction == "gridedit") { // Grid edit
			if ($personas->EventCancelled) {
				$personas_grid->RestoreCurrentRowFormValues($personas_grid->RowIndex); // Restore form values
			}
			if ($personas_grid->RowAction == "insert")
				$personas->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$personas->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($personas->CurrentAction == "gridedit" && ($personas->RowType == EW_ROWTYPE_EDIT || $personas->RowType == EW_ROWTYPE_ADD) && $personas->EventCancelled) // Update failed
			$personas_grid->RestoreCurrentRowFormValues($personas_grid->RowIndex); // Restore form values
		if ($personas->RowType == EW_ROWTYPE_EDIT) // Edit row
			$personas_grid->EditRowCnt++;
		if ($personas->CurrentAction == "F") // Confirm row
			$personas_grid->RestoreCurrentRowFormValues($personas_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$personas->RowAttrs = array_merge($personas->RowAttrs, array('data-rowindex'=>$personas_grid->RowCnt, 'id'=>'r' . $personas_grid->RowCnt . '_personas', 'data-rowtype'=>$personas->RowType));

		// Render row
		$personas_grid->RenderRow();

		// Render list options
		$personas_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($personas_grid->RowAction <> "delete" && $personas_grid->RowAction <> "insertdelete" && !($personas_grid->RowAction == "insert" && $personas->CurrentAction == "F" && $personas_grid->EmptyRow())) {
?>
	<tr<?php echo $personas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$personas_grid->ListOptions->Render("body", "left", $personas_grid->RowCnt);
?>
	<?php if ($personas->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $personas->Id->CellAttributes() ?>>
<?php if ($personas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="personas" data-field="x_Id" name="o<?php echo $personas_grid->RowIndex ?>_Id" id="o<?php echo $personas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($personas->Id->OldValue) ?>">
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_Id" class="form-group personas_Id">
<span<?php echo $personas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="personas" data-field="x_Id" name="x<?php echo $personas_grid->RowIndex ?>_Id" id="x<?php echo $personas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($personas->Id->CurrentValue) ?>">
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_Id" class="personas_Id">
<span<?php echo $personas->Id->ViewAttributes() ?>>
<?php echo $personas->Id->ListViewValue() ?></span>
</span>
<?php if ($personas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="personas" data-field="x_Id" name="x<?php echo $personas_grid->RowIndex ?>_Id" id="x<?php echo $personas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($personas->Id->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_Id" name="o<?php echo $personas_grid->RowIndex ?>_Id" id="o<?php echo $personas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($personas->Id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="personas" data-field="x_Id" name="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_Id" id="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($personas->Id->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_Id" name="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_Id" id="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($personas->Id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($personas->id_tipopersona->Visible) { // id_tipopersona ?>
		<td data-name="id_tipopersona"<?php echo $personas->id_tipopersona->CellAttributes() ?>>
<?php if ($personas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($personas->id_tipopersona->getSessionValue() <> "") { ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_id_tipopersona" class="form-group personas_id_tipopersona">
<span<?php echo $personas->id_tipopersona->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->id_tipopersona->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" name="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($personas->id_tipopersona->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_id_tipopersona" class="form-group personas_id_tipopersona">
<select data-table="personas" data-field="x_id_tipopersona" data-value-separator="<?php echo $personas->id_tipopersona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" name="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona"<?php echo $personas->id_tipopersona->EditAttributes() ?>>
<?php echo $personas->id_tipopersona->SelectOptionListHtml("x<?php echo $personas_grid->RowIndex ?>_id_tipopersona") ?>
</select>
</span>
<?php } ?>
<input type="hidden" data-table="personas" data-field="x_id_tipopersona" name="o<?php echo $personas_grid->RowIndex ?>_id_tipopersona" id="o<?php echo $personas_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($personas->id_tipopersona->OldValue) ?>">
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($personas->id_tipopersona->getSessionValue() <> "") { ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_id_tipopersona" class="form-group personas_id_tipopersona">
<span<?php echo $personas->id_tipopersona->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->id_tipopersona->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" name="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($personas->id_tipopersona->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_id_tipopersona" class="form-group personas_id_tipopersona">
<select data-table="personas" data-field="x_id_tipopersona" data-value-separator="<?php echo $personas->id_tipopersona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" name="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona"<?php echo $personas->id_tipopersona->EditAttributes() ?>>
<?php echo $personas->id_tipopersona->SelectOptionListHtml("x<?php echo $personas_grid->RowIndex ?>_id_tipopersona") ?>
</select>
</span>
<?php } ?>
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_id_tipopersona" class="personas_id_tipopersona">
<span<?php echo $personas->id_tipopersona->ViewAttributes() ?>>
<?php echo $personas->id_tipopersona->ListViewValue() ?></span>
</span>
<?php if ($personas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="personas" data-field="x_id_tipopersona" name="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" id="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($personas->id_tipopersona->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_id_tipopersona" name="o<?php echo $personas_grid->RowIndex ?>_id_tipopersona" id="o<?php echo $personas_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($personas->id_tipopersona->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="personas" data-field="x_id_tipopersona" name="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" id="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($personas->id_tipopersona->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_id_tipopersona" name="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_id_tipopersona" id="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($personas->id_tipopersona->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($personas->tipo_documento->Visible) { // tipo_documento ?>
		<td data-name="tipo_documento"<?php echo $personas->tipo_documento->CellAttributes() ?>>
<?php if ($personas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_tipo_documento" class="form-group personas_tipo_documento">
<select data-table="personas" data-field="x_tipo_documento" data-value-separator="<?php echo $personas->tipo_documento->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $personas_grid->RowIndex ?>_tipo_documento" name="x<?php echo $personas_grid->RowIndex ?>_tipo_documento"<?php echo $personas->tipo_documento->EditAttributes() ?>>
<?php echo $personas->tipo_documento->SelectOptionListHtml("x<?php echo $personas_grid->RowIndex ?>_tipo_documento") ?>
</select>
</span>
<input type="hidden" data-table="personas" data-field="x_tipo_documento" name="o<?php echo $personas_grid->RowIndex ?>_tipo_documento" id="o<?php echo $personas_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($personas->tipo_documento->OldValue) ?>">
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_tipo_documento" class="form-group personas_tipo_documento">
<select data-table="personas" data-field="x_tipo_documento" data-value-separator="<?php echo $personas->tipo_documento->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $personas_grid->RowIndex ?>_tipo_documento" name="x<?php echo $personas_grid->RowIndex ?>_tipo_documento"<?php echo $personas->tipo_documento->EditAttributes() ?>>
<?php echo $personas->tipo_documento->SelectOptionListHtml("x<?php echo $personas_grid->RowIndex ?>_tipo_documento") ?>
</select>
</span>
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_tipo_documento" class="personas_tipo_documento">
<span<?php echo $personas->tipo_documento->ViewAttributes() ?>>
<?php echo $personas->tipo_documento->ListViewValue() ?></span>
</span>
<?php if ($personas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="personas" data-field="x_tipo_documento" name="x<?php echo $personas_grid->RowIndex ?>_tipo_documento" id="x<?php echo $personas_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($personas->tipo_documento->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_tipo_documento" name="o<?php echo $personas_grid->RowIndex ?>_tipo_documento" id="o<?php echo $personas_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($personas->tipo_documento->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="personas" data-field="x_tipo_documento" name="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_tipo_documento" id="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($personas->tipo_documento->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_tipo_documento" name="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_tipo_documento" id="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($personas->tipo_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($personas->no_documento->Visible) { // no_documento ?>
		<td data-name="no_documento"<?php echo $personas->no_documento->CellAttributes() ?>>
<?php if ($personas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_no_documento" class="form-group personas_no_documento">
<input type="text" data-table="personas" data-field="x_no_documento" name="x<?php echo $personas_grid->RowIndex ?>_no_documento" id="x<?php echo $personas_grid->RowIndex ?>_no_documento" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->no_documento->getPlaceHolder()) ?>" value="<?php echo $personas->no_documento->EditValue ?>"<?php echo $personas->no_documento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="personas" data-field="x_no_documento" name="o<?php echo $personas_grid->RowIndex ?>_no_documento" id="o<?php echo $personas_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($personas->no_documento->OldValue) ?>">
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_no_documento" class="form-group personas_no_documento">
<input type="text" data-table="personas" data-field="x_no_documento" name="x<?php echo $personas_grid->RowIndex ?>_no_documento" id="x<?php echo $personas_grid->RowIndex ?>_no_documento" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->no_documento->getPlaceHolder()) ?>" value="<?php echo $personas->no_documento->EditValue ?>"<?php echo $personas->no_documento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_no_documento" class="personas_no_documento">
<span<?php echo $personas->no_documento->ViewAttributes() ?>>
<?php echo $personas->no_documento->ListViewValue() ?></span>
</span>
<?php if ($personas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="personas" data-field="x_no_documento" name="x<?php echo $personas_grid->RowIndex ?>_no_documento" id="x<?php echo $personas_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($personas->no_documento->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_no_documento" name="o<?php echo $personas_grid->RowIndex ?>_no_documento" id="o<?php echo $personas_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($personas->no_documento->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="personas" data-field="x_no_documento" name="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_no_documento" id="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($personas->no_documento->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_no_documento" name="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_no_documento" id="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($personas->no_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($personas->nombres->Visible) { // nombres ?>
		<td data-name="nombres"<?php echo $personas->nombres->CellAttributes() ?>>
<?php if ($personas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_nombres" class="form-group personas_nombres">
<input type="text" data-table="personas" data-field="x_nombres" name="x<?php echo $personas_grid->RowIndex ?>_nombres" id="x<?php echo $personas_grid->RowIndex ?>_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->nombres->getPlaceHolder()) ?>" value="<?php echo $personas->nombres->EditValue ?>"<?php echo $personas->nombres->EditAttributes() ?>>
</span>
<input type="hidden" data-table="personas" data-field="x_nombres" name="o<?php echo $personas_grid->RowIndex ?>_nombres" id="o<?php echo $personas_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($personas->nombres->OldValue) ?>">
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_nombres" class="form-group personas_nombres">
<input type="text" data-table="personas" data-field="x_nombres" name="x<?php echo $personas_grid->RowIndex ?>_nombres" id="x<?php echo $personas_grid->RowIndex ?>_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->nombres->getPlaceHolder()) ?>" value="<?php echo $personas->nombres->EditValue ?>"<?php echo $personas->nombres->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_nombres" class="personas_nombres">
<span<?php echo $personas->nombres->ViewAttributes() ?>>
<?php echo $personas->nombres->ListViewValue() ?></span>
</span>
<?php if ($personas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="personas" data-field="x_nombres" name="x<?php echo $personas_grid->RowIndex ?>_nombres" id="x<?php echo $personas_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($personas->nombres->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_nombres" name="o<?php echo $personas_grid->RowIndex ?>_nombres" id="o<?php echo $personas_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($personas->nombres->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="personas" data-field="x_nombres" name="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_nombres" id="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($personas->nombres->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_nombres" name="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_nombres" id="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($personas->nombres->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($personas->paterno->Visible) { // paterno ?>
		<td data-name="paterno"<?php echo $personas->paterno->CellAttributes() ?>>
<?php if ($personas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_paterno" class="form-group personas_paterno">
<input type="text" data-table="personas" data-field="x_paterno" name="x<?php echo $personas_grid->RowIndex ?>_paterno" id="x<?php echo $personas_grid->RowIndex ?>_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->paterno->getPlaceHolder()) ?>" value="<?php echo $personas->paterno->EditValue ?>"<?php echo $personas->paterno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="personas" data-field="x_paterno" name="o<?php echo $personas_grid->RowIndex ?>_paterno" id="o<?php echo $personas_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($personas->paterno->OldValue) ?>">
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_paterno" class="form-group personas_paterno">
<input type="text" data-table="personas" data-field="x_paterno" name="x<?php echo $personas_grid->RowIndex ?>_paterno" id="x<?php echo $personas_grid->RowIndex ?>_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->paterno->getPlaceHolder()) ?>" value="<?php echo $personas->paterno->EditValue ?>"<?php echo $personas->paterno->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_paterno" class="personas_paterno">
<span<?php echo $personas->paterno->ViewAttributes() ?>>
<?php echo $personas->paterno->ListViewValue() ?></span>
</span>
<?php if ($personas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="personas" data-field="x_paterno" name="x<?php echo $personas_grid->RowIndex ?>_paterno" id="x<?php echo $personas_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($personas->paterno->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_paterno" name="o<?php echo $personas_grid->RowIndex ?>_paterno" id="o<?php echo $personas_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($personas->paterno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="personas" data-field="x_paterno" name="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_paterno" id="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($personas->paterno->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_paterno" name="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_paterno" id="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($personas->paterno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($personas->materno->Visible) { // materno ?>
		<td data-name="materno"<?php echo $personas->materno->CellAttributes() ?>>
<?php if ($personas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_materno" class="form-group personas_materno">
<input type="text" data-table="personas" data-field="x_materno" name="x<?php echo $personas_grid->RowIndex ?>_materno" id="x<?php echo $personas_grid->RowIndex ?>_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->materno->getPlaceHolder()) ?>" value="<?php echo $personas->materno->EditValue ?>"<?php echo $personas->materno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="personas" data-field="x_materno" name="o<?php echo $personas_grid->RowIndex ?>_materno" id="o<?php echo $personas_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($personas->materno->OldValue) ?>">
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_materno" class="form-group personas_materno">
<input type="text" data-table="personas" data-field="x_materno" name="x<?php echo $personas_grid->RowIndex ?>_materno" id="x<?php echo $personas_grid->RowIndex ?>_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->materno->getPlaceHolder()) ?>" value="<?php echo $personas->materno->EditValue ?>"<?php echo $personas->materno->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_materno" class="personas_materno">
<span<?php echo $personas->materno->ViewAttributes() ?>>
<?php echo $personas->materno->ListViewValue() ?></span>
</span>
<?php if ($personas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="personas" data-field="x_materno" name="x<?php echo $personas_grid->RowIndex ?>_materno" id="x<?php echo $personas_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($personas->materno->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_materno" name="o<?php echo $personas_grid->RowIndex ?>_materno" id="o<?php echo $personas_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($personas->materno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="personas" data-field="x_materno" name="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_materno" id="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($personas->materno->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_materno" name="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_materno" id="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($personas->materno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($personas->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<td data-name="fecha_nacimiento"<?php echo $personas->fecha_nacimiento->CellAttributes() ?>>
<?php if ($personas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_fecha_nacimiento" class="form-group personas_fecha_nacimiento">
<input type="text" data-table="personas" data-field="x_fecha_nacimiento" data-format="7" name="x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" id="x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" size="20" placeholder="<?php echo ew_HtmlEncode($personas->fecha_nacimiento->getPlaceHolder()) ?>" value="<?php echo $personas->fecha_nacimiento->EditValue ?>"<?php echo $personas->fecha_nacimiento->EditAttributes() ?>>
<?php if (!$personas->fecha_nacimiento->ReadOnly && !$personas->fecha_nacimiento->Disabled && !isset($personas->fecha_nacimiento->EditAttrs["readonly"]) && !isset($personas->fecha_nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpersonasgrid", "x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="personas" data-field="x_fecha_nacimiento" name="o<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" id="o<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" value="<?php echo ew_HtmlEncode($personas->fecha_nacimiento->OldValue) ?>">
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_fecha_nacimiento" class="form-group personas_fecha_nacimiento">
<input type="text" data-table="personas" data-field="x_fecha_nacimiento" data-format="7" name="x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" id="x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" size="20" placeholder="<?php echo ew_HtmlEncode($personas->fecha_nacimiento->getPlaceHolder()) ?>" value="<?php echo $personas->fecha_nacimiento->EditValue ?>"<?php echo $personas->fecha_nacimiento->EditAttributes() ?>>
<?php if (!$personas->fecha_nacimiento->ReadOnly && !$personas->fecha_nacimiento->Disabled && !isset($personas->fecha_nacimiento->EditAttrs["readonly"]) && !isset($personas->fecha_nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpersonasgrid", "x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_fecha_nacimiento" class="personas_fecha_nacimiento">
<span<?php echo $personas->fecha_nacimiento->ViewAttributes() ?>>
<?php echo $personas->fecha_nacimiento->ListViewValue() ?></span>
</span>
<?php if ($personas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="personas" data-field="x_fecha_nacimiento" name="x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" id="x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" value="<?php echo ew_HtmlEncode($personas->fecha_nacimiento->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_fecha_nacimiento" name="o<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" id="o<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" value="<?php echo ew_HtmlEncode($personas->fecha_nacimiento->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="personas" data-field="x_fecha_nacimiento" name="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" id="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" value="<?php echo ew_HtmlEncode($personas->fecha_nacimiento->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_fecha_nacimiento" name="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" id="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" value="<?php echo ew_HtmlEncode($personas->fecha_nacimiento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($personas->fecha_registro->Visible) { // fecha_registro ?>
		<td data-name="fecha_registro"<?php echo $personas->fecha_registro->CellAttributes() ?>>
<?php if ($personas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_fecha_registro" class="form-group personas_fecha_registro">
<input type="text" data-table="personas" data-field="x_fecha_registro" data-format="11" name="x<?php echo $personas_grid->RowIndex ?>_fecha_registro" id="x<?php echo $personas_grid->RowIndex ?>_fecha_registro" size="20" placeholder="<?php echo ew_HtmlEncode($personas->fecha_registro->getPlaceHolder()) ?>" value="<?php echo $personas->fecha_registro->EditValue ?>"<?php echo $personas->fecha_registro->EditAttributes() ?>>
<?php if (!$personas->fecha_registro->ReadOnly && !$personas->fecha_registro->Disabled && !isset($personas->fecha_registro->EditAttrs["readonly"]) && !isset($personas->fecha_registro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpersonasgrid", "x<?php echo $personas_grid->RowIndex ?>_fecha_registro", {"ignoreReadonly":true,"useCurrent":false,"format":11});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="personas" data-field="x_fecha_registro" name="o<?php echo $personas_grid->RowIndex ?>_fecha_registro" id="o<?php echo $personas_grid->RowIndex ?>_fecha_registro" value="<?php echo ew_HtmlEncode($personas->fecha_registro->OldValue) ?>">
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_fecha_registro" class="form-group personas_fecha_registro">
<input type="text" data-table="personas" data-field="x_fecha_registro" data-format="11" name="x<?php echo $personas_grid->RowIndex ?>_fecha_registro" id="x<?php echo $personas_grid->RowIndex ?>_fecha_registro" size="20" placeholder="<?php echo ew_HtmlEncode($personas->fecha_registro->getPlaceHolder()) ?>" value="<?php echo $personas->fecha_registro->EditValue ?>"<?php echo $personas->fecha_registro->EditAttributes() ?>>
<?php if (!$personas->fecha_registro->ReadOnly && !$personas->fecha_registro->Disabled && !isset($personas->fecha_registro->EditAttrs["readonly"]) && !isset($personas->fecha_registro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpersonasgrid", "x<?php echo $personas_grid->RowIndex ?>_fecha_registro", {"ignoreReadonly":true,"useCurrent":false,"format":11});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_fecha_registro" class="personas_fecha_registro">
<span<?php echo $personas->fecha_registro->ViewAttributes() ?>>
<?php echo $personas->fecha_registro->ListViewValue() ?></span>
</span>
<?php if ($personas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="personas" data-field="x_fecha_registro" name="x<?php echo $personas_grid->RowIndex ?>_fecha_registro" id="x<?php echo $personas_grid->RowIndex ?>_fecha_registro" value="<?php echo ew_HtmlEncode($personas->fecha_registro->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_fecha_registro" name="o<?php echo $personas_grid->RowIndex ?>_fecha_registro" id="o<?php echo $personas_grid->RowIndex ?>_fecha_registro" value="<?php echo ew_HtmlEncode($personas->fecha_registro->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="personas" data-field="x_fecha_registro" name="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_fecha_registro" id="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_fecha_registro" value="<?php echo ew_HtmlEncode($personas->fecha_registro->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_fecha_registro" name="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_fecha_registro" id="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_fecha_registro" value="<?php echo ew_HtmlEncode($personas->fecha_registro->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($personas->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $personas->estado->CellAttributes() ?>>
<?php if ($personas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_estado" class="form-group personas_estado">
<div id="tp_x<?php echo $personas_grid->RowIndex ?>_estado" class="ewTemplate"><input type="radio" data-table="personas" data-field="x_estado" data-value-separator="<?php echo $personas->estado->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $personas_grid->RowIndex ?>_estado" id="x<?php echo $personas_grid->RowIndex ?>_estado" value="{value}"<?php echo $personas->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $personas_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $personas->estado->RadioButtonListHtml(FALSE, "x{$personas_grid->RowIndex}_estado") ?>
</div></div>
</span>
<input type="hidden" data-table="personas" data-field="x_estado" name="o<?php echo $personas_grid->RowIndex ?>_estado" id="o<?php echo $personas_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($personas->estado->OldValue) ?>">
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_estado" class="form-group personas_estado">
<div id="tp_x<?php echo $personas_grid->RowIndex ?>_estado" class="ewTemplate"><input type="radio" data-table="personas" data-field="x_estado" data-value-separator="<?php echo $personas->estado->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $personas_grid->RowIndex ?>_estado" id="x<?php echo $personas_grid->RowIndex ?>_estado" value="{value}"<?php echo $personas->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $personas_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $personas->estado->RadioButtonListHtml(FALSE, "x{$personas_grid->RowIndex}_estado") ?>
</div></div>
</span>
<?php } ?>
<?php if ($personas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $personas_grid->RowCnt ?>_personas_estado" class="personas_estado">
<span<?php echo $personas->estado->ViewAttributes() ?>>
<?php echo $personas->estado->ListViewValue() ?></span>
</span>
<?php if ($personas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="personas" data-field="x_estado" name="x<?php echo $personas_grid->RowIndex ?>_estado" id="x<?php echo $personas_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($personas->estado->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_estado" name="o<?php echo $personas_grid->RowIndex ?>_estado" id="o<?php echo $personas_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($personas->estado->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="personas" data-field="x_estado" name="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_estado" id="fpersonasgrid$x<?php echo $personas_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($personas->estado->FormValue) ?>">
<input type="hidden" data-table="personas" data-field="x_estado" name="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_estado" id="fpersonasgrid$o<?php echo $personas_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($personas->estado->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$personas_grid->ListOptions->Render("body", "right", $personas_grid->RowCnt);
?>
	</tr>
<?php if ($personas->RowType == EW_ROWTYPE_ADD || $personas->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fpersonasgrid.UpdateOpts(<?php echo $personas_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($personas->CurrentAction <> "gridadd" || $personas->CurrentMode == "copy")
		if (!$personas_grid->Recordset->EOF) $personas_grid->Recordset->MoveNext();
}
?>
<?php
	if ($personas->CurrentMode == "add" || $personas->CurrentMode == "copy" || $personas->CurrentMode == "edit") {
		$personas_grid->RowIndex = '$rowindex$';
		$personas_grid->LoadRowValues();

		// Set row properties
		$personas->ResetAttrs();
		$personas->RowAttrs = array_merge($personas->RowAttrs, array('data-rowindex'=>$personas_grid->RowIndex, 'id'=>'r0_personas', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($personas->RowAttrs["class"], "ewTemplate");
		$personas->RowType = EW_ROWTYPE_ADD;

		// Render row
		$personas_grid->RenderRow();

		// Render list options
		$personas_grid->RenderListOptions();
		$personas_grid->StartRowCnt = 0;
?>
	<tr<?php echo $personas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$personas_grid->ListOptions->Render("body", "left", $personas_grid->RowIndex);
?>
	<?php if ($personas->Id->Visible) { // Id ?>
		<td data-name="Id">
<?php if ($personas->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_personas_Id" class="form-group personas_Id">
<span<?php echo $personas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->Id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="personas" data-field="x_Id" name="x<?php echo $personas_grid->RowIndex ?>_Id" id="x<?php echo $personas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($personas->Id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="personas" data-field="x_Id" name="o<?php echo $personas_grid->RowIndex ?>_Id" id="o<?php echo $personas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($personas->Id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($personas->id_tipopersona->Visible) { // id_tipopersona ?>
		<td data-name="id_tipopersona">
<?php if ($personas->CurrentAction <> "F") { ?>
<?php if ($personas->id_tipopersona->getSessionValue() <> "") { ?>
<span id="el$rowindex$_personas_id_tipopersona" class="form-group personas_id_tipopersona">
<span<?php echo $personas->id_tipopersona->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->id_tipopersona->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" name="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($personas->id_tipopersona->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_personas_id_tipopersona" class="form-group personas_id_tipopersona">
<select data-table="personas" data-field="x_id_tipopersona" data-value-separator="<?php echo $personas->id_tipopersona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" name="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona"<?php echo $personas->id_tipopersona->EditAttributes() ?>>
<?php echo $personas->id_tipopersona->SelectOptionListHtml("x<?php echo $personas_grid->RowIndex ?>_id_tipopersona") ?>
</select>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_personas_id_tipopersona" class="form-group personas_id_tipopersona">
<span<?php echo $personas->id_tipopersona->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->id_tipopersona->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="personas" data-field="x_id_tipopersona" name="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" id="x<?php echo $personas_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($personas->id_tipopersona->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="personas" data-field="x_id_tipopersona" name="o<?php echo $personas_grid->RowIndex ?>_id_tipopersona" id="o<?php echo $personas_grid->RowIndex ?>_id_tipopersona" value="<?php echo ew_HtmlEncode($personas->id_tipopersona->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($personas->tipo_documento->Visible) { // tipo_documento ?>
		<td data-name="tipo_documento">
<?php if ($personas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_personas_tipo_documento" class="form-group personas_tipo_documento">
<select data-table="personas" data-field="x_tipo_documento" data-value-separator="<?php echo $personas->tipo_documento->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $personas_grid->RowIndex ?>_tipo_documento" name="x<?php echo $personas_grid->RowIndex ?>_tipo_documento"<?php echo $personas->tipo_documento->EditAttributes() ?>>
<?php echo $personas->tipo_documento->SelectOptionListHtml("x<?php echo $personas_grid->RowIndex ?>_tipo_documento") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_personas_tipo_documento" class="form-group personas_tipo_documento">
<span<?php echo $personas->tipo_documento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->tipo_documento->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="personas" data-field="x_tipo_documento" name="x<?php echo $personas_grid->RowIndex ?>_tipo_documento" id="x<?php echo $personas_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($personas->tipo_documento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="personas" data-field="x_tipo_documento" name="o<?php echo $personas_grid->RowIndex ?>_tipo_documento" id="o<?php echo $personas_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($personas->tipo_documento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($personas->no_documento->Visible) { // no_documento ?>
		<td data-name="no_documento">
<?php if ($personas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_personas_no_documento" class="form-group personas_no_documento">
<input type="text" data-table="personas" data-field="x_no_documento" name="x<?php echo $personas_grid->RowIndex ?>_no_documento" id="x<?php echo $personas_grid->RowIndex ?>_no_documento" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->no_documento->getPlaceHolder()) ?>" value="<?php echo $personas->no_documento->EditValue ?>"<?php echo $personas->no_documento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_personas_no_documento" class="form-group personas_no_documento">
<span<?php echo $personas->no_documento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->no_documento->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="personas" data-field="x_no_documento" name="x<?php echo $personas_grid->RowIndex ?>_no_documento" id="x<?php echo $personas_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($personas->no_documento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="personas" data-field="x_no_documento" name="o<?php echo $personas_grid->RowIndex ?>_no_documento" id="o<?php echo $personas_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($personas->no_documento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($personas->nombres->Visible) { // nombres ?>
		<td data-name="nombres">
<?php if ($personas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_personas_nombres" class="form-group personas_nombres">
<input type="text" data-table="personas" data-field="x_nombres" name="x<?php echo $personas_grid->RowIndex ?>_nombres" id="x<?php echo $personas_grid->RowIndex ?>_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->nombres->getPlaceHolder()) ?>" value="<?php echo $personas->nombres->EditValue ?>"<?php echo $personas->nombres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_personas_nombres" class="form-group personas_nombres">
<span<?php echo $personas->nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->nombres->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="personas" data-field="x_nombres" name="x<?php echo $personas_grid->RowIndex ?>_nombres" id="x<?php echo $personas_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($personas->nombres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="personas" data-field="x_nombres" name="o<?php echo $personas_grid->RowIndex ?>_nombres" id="o<?php echo $personas_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($personas->nombres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($personas->paterno->Visible) { // paterno ?>
		<td data-name="paterno">
<?php if ($personas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_personas_paterno" class="form-group personas_paterno">
<input type="text" data-table="personas" data-field="x_paterno" name="x<?php echo $personas_grid->RowIndex ?>_paterno" id="x<?php echo $personas_grid->RowIndex ?>_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->paterno->getPlaceHolder()) ?>" value="<?php echo $personas->paterno->EditValue ?>"<?php echo $personas->paterno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_personas_paterno" class="form-group personas_paterno">
<span<?php echo $personas->paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->paterno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="personas" data-field="x_paterno" name="x<?php echo $personas_grid->RowIndex ?>_paterno" id="x<?php echo $personas_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($personas->paterno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="personas" data-field="x_paterno" name="o<?php echo $personas_grid->RowIndex ?>_paterno" id="o<?php echo $personas_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($personas->paterno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($personas->materno->Visible) { // materno ?>
		<td data-name="materno">
<?php if ($personas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_personas_materno" class="form-group personas_materno">
<input type="text" data-table="personas" data-field="x_materno" name="x<?php echo $personas_grid->RowIndex ?>_materno" id="x<?php echo $personas_grid->RowIndex ?>_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->materno->getPlaceHolder()) ?>" value="<?php echo $personas->materno->EditValue ?>"<?php echo $personas->materno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_personas_materno" class="form-group personas_materno">
<span<?php echo $personas->materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->materno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="personas" data-field="x_materno" name="x<?php echo $personas_grid->RowIndex ?>_materno" id="x<?php echo $personas_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($personas->materno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="personas" data-field="x_materno" name="o<?php echo $personas_grid->RowIndex ?>_materno" id="o<?php echo $personas_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($personas->materno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($personas->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<td data-name="fecha_nacimiento">
<?php if ($personas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_personas_fecha_nacimiento" class="form-group personas_fecha_nacimiento">
<input type="text" data-table="personas" data-field="x_fecha_nacimiento" data-format="7" name="x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" id="x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" size="20" placeholder="<?php echo ew_HtmlEncode($personas->fecha_nacimiento->getPlaceHolder()) ?>" value="<?php echo $personas->fecha_nacimiento->EditValue ?>"<?php echo $personas->fecha_nacimiento->EditAttributes() ?>>
<?php if (!$personas->fecha_nacimiento->ReadOnly && !$personas->fecha_nacimiento->Disabled && !isset($personas->fecha_nacimiento->EditAttrs["readonly"]) && !isset($personas->fecha_nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpersonasgrid", "x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_personas_fecha_nacimiento" class="form-group personas_fecha_nacimiento">
<span<?php echo $personas->fecha_nacimiento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->fecha_nacimiento->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="personas" data-field="x_fecha_nacimiento" name="x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" id="x<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" value="<?php echo ew_HtmlEncode($personas->fecha_nacimiento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="personas" data-field="x_fecha_nacimiento" name="o<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" id="o<?php echo $personas_grid->RowIndex ?>_fecha_nacimiento" value="<?php echo ew_HtmlEncode($personas->fecha_nacimiento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($personas->fecha_registro->Visible) { // fecha_registro ?>
		<td data-name="fecha_registro">
<?php if ($personas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_personas_fecha_registro" class="form-group personas_fecha_registro">
<input type="text" data-table="personas" data-field="x_fecha_registro" data-format="11" name="x<?php echo $personas_grid->RowIndex ?>_fecha_registro" id="x<?php echo $personas_grid->RowIndex ?>_fecha_registro" size="20" placeholder="<?php echo ew_HtmlEncode($personas->fecha_registro->getPlaceHolder()) ?>" value="<?php echo $personas->fecha_registro->EditValue ?>"<?php echo $personas->fecha_registro->EditAttributes() ?>>
<?php if (!$personas->fecha_registro->ReadOnly && !$personas->fecha_registro->Disabled && !isset($personas->fecha_registro->EditAttrs["readonly"]) && !isset($personas->fecha_registro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpersonasgrid", "x<?php echo $personas_grid->RowIndex ?>_fecha_registro", {"ignoreReadonly":true,"useCurrent":false,"format":11});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_personas_fecha_registro" class="form-group personas_fecha_registro">
<span<?php echo $personas->fecha_registro->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->fecha_registro->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="personas" data-field="x_fecha_registro" name="x<?php echo $personas_grid->RowIndex ?>_fecha_registro" id="x<?php echo $personas_grid->RowIndex ?>_fecha_registro" value="<?php echo ew_HtmlEncode($personas->fecha_registro->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="personas" data-field="x_fecha_registro" name="o<?php echo $personas_grid->RowIndex ?>_fecha_registro" id="o<?php echo $personas_grid->RowIndex ?>_fecha_registro" value="<?php echo ew_HtmlEncode($personas->fecha_registro->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($personas->estado->Visible) { // estado ?>
		<td data-name="estado">
<?php if ($personas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_personas_estado" class="form-group personas_estado">
<div id="tp_x<?php echo $personas_grid->RowIndex ?>_estado" class="ewTemplate"><input type="radio" data-table="personas" data-field="x_estado" data-value-separator="<?php echo $personas->estado->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $personas_grid->RowIndex ?>_estado" id="x<?php echo $personas_grid->RowIndex ?>_estado" value="{value}"<?php echo $personas->estado->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $personas_grid->RowIndex ?>_estado" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $personas->estado->RadioButtonListHtml(FALSE, "x{$personas_grid->RowIndex}_estado") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_personas_estado" class="form-group personas_estado">
<span<?php echo $personas->estado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->estado->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="personas" data-field="x_estado" name="x<?php echo $personas_grid->RowIndex ?>_estado" id="x<?php echo $personas_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($personas->estado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="personas" data-field="x_estado" name="o<?php echo $personas_grid->RowIndex ?>_estado" id="o<?php echo $personas_grid->RowIndex ?>_estado" value="<?php echo ew_HtmlEncode($personas->estado->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$personas_grid->ListOptions->Render("body", "right", $personas_grid->RowCnt);
?>
<script type="text/javascript">
fpersonasgrid.UpdateOpts(<?php echo $personas_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($personas->CurrentMode == "add" || $personas->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $personas_grid->FormKeyCountName ?>" id="<?php echo $personas_grid->FormKeyCountName ?>" value="<?php echo $personas_grid->KeyCount ?>">
<?php echo $personas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($personas->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $personas_grid->FormKeyCountName ?>" id="<?php echo $personas_grid->FormKeyCountName ?>" value="<?php echo $personas_grid->KeyCount ?>">
<?php echo $personas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($personas->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fpersonasgrid">
</div>
<?php

// Close recordset
if ($personas_grid->Recordset)
	$personas_grid->Recordset->Close();
?>
<?php if ($personas_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($personas_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($personas_grid->TotalRecs == 0 && $personas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($personas_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($personas->Export == "") { ?>
<script type="text/javascript">
fpersonasgrid.Init();
</script>
<?php } ?>
<?php
$personas_grid->Page_Terminate();
?>
