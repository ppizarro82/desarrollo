<?php include_once "usersinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($emails_grid)) $emails_grid = new cemails_grid();

// Page init
$emails_grid->Page_Init();

// Page main
$emails_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$emails_grid->Page_Render();
?>
<?php if ($emails->Export == "") { ?>
<script type="text/javascript">

// Form object
var femailsgrid = new ew_Form("femailsgrid", "grid");
femailsgrid.FormKeyCountName = '<?php echo $emails_grid->FormKeyCountName ?>';

// Validate form
femailsgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $emails->id_persona->FldCaption(), $emails->id_persona->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $emails->_email->FldCaption(), $emails->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($emails->_email->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
femailsgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_persona", false)) return false;
	if (ew_ValueChanged(fobj, infix, "_email", false)) return false;
	return true;
}

// Form_CustomValidate event
femailsgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
femailsgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
femailsgrid.Lists["x_id_persona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_paterno","x_materno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
femailsgrid.Lists["x_id_persona"].Data = "<?php echo $emails_grid->id_persona->LookupFilterQuery(FALSE, "grid") ?>";

// Form object for search
</script>
<?php } ?>
<?php
if ($emails->CurrentAction == "gridadd") {
	if ($emails->CurrentMode == "copy") {
		$bSelectLimit = $emails_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$emails_grid->TotalRecs = $emails->ListRecordCount();
			$emails_grid->Recordset = $emails_grid->LoadRecordset($emails_grid->StartRec-1, $emails_grid->DisplayRecs);
		} else {
			if ($emails_grid->Recordset = $emails_grid->LoadRecordset())
				$emails_grid->TotalRecs = $emails_grid->Recordset->RecordCount();
		}
		$emails_grid->StartRec = 1;
		$emails_grid->DisplayRecs = $emails_grid->TotalRecs;
	} else {
		$emails->CurrentFilter = "0=1";
		$emails_grid->StartRec = 1;
		$emails_grid->DisplayRecs = $emails->GridAddRowCount;
	}
	$emails_grid->TotalRecs = $emails_grid->DisplayRecs;
	$emails_grid->StopRec = $emails_grid->DisplayRecs;
} else {
	$bSelectLimit = $emails_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($emails_grid->TotalRecs <= 0)
			$emails_grid->TotalRecs = $emails->ListRecordCount();
	} else {
		if (!$emails_grid->Recordset && ($emails_grid->Recordset = $emails_grid->LoadRecordset()))
			$emails_grid->TotalRecs = $emails_grid->Recordset->RecordCount();
	}
	$emails_grid->StartRec = 1;
	$emails_grid->DisplayRecs = $emails_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$emails_grid->Recordset = $emails_grid->LoadRecordset($emails_grid->StartRec-1, $emails_grid->DisplayRecs);

	// Set no record found message
	if ($emails->CurrentAction == "" && $emails_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$emails_grid->setWarningMessage(ew_DeniedMsg());
		if ($emails_grid->SearchWhere == "0=101")
			$emails_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$emails_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$emails_grid->RenderOtherOptions();
?>
<?php $emails_grid->ShowPageHeader(); ?>
<?php
$emails_grid->ShowMessage();
?>
<?php if ($emails_grid->TotalRecs > 0 || $emails->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($emails_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> emails">
<div id="femailsgrid" class="ewForm ewListForm form-inline">
<?php if ($emails_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($emails_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_emails" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_emailsgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$emails_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$emails_grid->RenderListOptions();

// Render list options (header, left)
$emails_grid->ListOptions->Render("header", "left");
?>
<?php if ($emails->Id->Visible) { // Id ?>
	<?php if ($emails->SortUrl($emails->Id) == "") { ?>
		<th data-name="Id" class="<?php echo $emails->Id->HeaderCellClass() ?>"><div id="elh_emails_Id" class="emails_Id"><div class="ewTableHeaderCaption"><?php echo $emails->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id" class="<?php echo $emails->Id->HeaderCellClass() ?>"><div><div id="elh_emails_Id" class="emails_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($emails->id_persona->Visible) { // id_persona ?>
	<?php if ($emails->SortUrl($emails->id_persona) == "") { ?>
		<th data-name="id_persona" class="<?php echo $emails->id_persona->HeaderCellClass() ?>"><div id="elh_emails_id_persona" class="emails_id_persona"><div class="ewTableHeaderCaption"><?php echo $emails->id_persona->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_persona" class="<?php echo $emails->id_persona->HeaderCellClass() ?>"><div><div id="elh_emails_id_persona" class="emails_id_persona">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->id_persona->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->id_persona->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->id_persona->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($emails->_email->Visible) { // email ?>
	<?php if ($emails->SortUrl($emails->_email) == "") { ?>
		<th data-name="_email" class="<?php echo $emails->_email->HeaderCellClass() ?>"><div id="elh_emails__email" class="emails__email"><div class="ewTableHeaderCaption"><?php echo $emails->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email" class="<?php echo $emails->_email->HeaderCellClass() ?>"><div><div id="elh_emails__email" class="emails__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->_email->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$emails_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$emails_grid->StartRec = 1;
$emails_grid->StopRec = $emails_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($emails_grid->FormKeyCountName) && ($emails->CurrentAction == "gridadd" || $emails->CurrentAction == "gridedit" || $emails->CurrentAction == "F")) {
		$emails_grid->KeyCount = $objForm->GetValue($emails_grid->FormKeyCountName);
		$emails_grid->StopRec = $emails_grid->StartRec + $emails_grid->KeyCount - 1;
	}
}
$emails_grid->RecCnt = $emails_grid->StartRec - 1;
if ($emails_grid->Recordset && !$emails_grid->Recordset->EOF) {
	$emails_grid->Recordset->MoveFirst();
	$bSelectLimit = $emails_grid->UseSelectLimit;
	if (!$bSelectLimit && $emails_grid->StartRec > 1)
		$emails_grid->Recordset->Move($emails_grid->StartRec - 1);
} elseif (!$emails->AllowAddDeleteRow && $emails_grid->StopRec == 0) {
	$emails_grid->StopRec = $emails->GridAddRowCount;
}

// Initialize aggregate
$emails->RowType = EW_ROWTYPE_AGGREGATEINIT;
$emails->ResetAttrs();
$emails_grid->RenderRow();
if ($emails->CurrentAction == "gridadd")
	$emails_grid->RowIndex = 0;
if ($emails->CurrentAction == "gridedit")
	$emails_grid->RowIndex = 0;
while ($emails_grid->RecCnt < $emails_grid->StopRec) {
	$emails_grid->RecCnt++;
	if (intval($emails_grid->RecCnt) >= intval($emails_grid->StartRec)) {
		$emails_grid->RowCnt++;
		if ($emails->CurrentAction == "gridadd" || $emails->CurrentAction == "gridedit" || $emails->CurrentAction == "F") {
			$emails_grid->RowIndex++;
			$objForm->Index = $emails_grid->RowIndex;
			if ($objForm->HasValue($emails_grid->FormActionName))
				$emails_grid->RowAction = strval($objForm->GetValue($emails_grid->FormActionName));
			elseif ($emails->CurrentAction == "gridadd")
				$emails_grid->RowAction = "insert";
			else
				$emails_grid->RowAction = "";
		}

		// Set up key count
		$emails_grid->KeyCount = $emails_grid->RowIndex;

		// Init row class and style
		$emails->ResetAttrs();
		$emails->CssClass = "";
		if ($emails->CurrentAction == "gridadd") {
			if ($emails->CurrentMode == "copy") {
				$emails_grid->LoadRowValues($emails_grid->Recordset); // Load row values
				$emails_grid->SetRecordKey($emails_grid->RowOldKey, $emails_grid->Recordset); // Set old record key
			} else {
				$emails_grid->LoadRowValues(); // Load default values
				$emails_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$emails_grid->LoadRowValues($emails_grid->Recordset); // Load row values
		}
		$emails->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($emails->CurrentAction == "gridadd") // Grid add
			$emails->RowType = EW_ROWTYPE_ADD; // Render add
		if ($emails->CurrentAction == "gridadd" && $emails->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$emails_grid->RestoreCurrentRowFormValues($emails_grid->RowIndex); // Restore form values
		if ($emails->CurrentAction == "gridedit") { // Grid edit
			if ($emails->EventCancelled) {
				$emails_grid->RestoreCurrentRowFormValues($emails_grid->RowIndex); // Restore form values
			}
			if ($emails_grid->RowAction == "insert")
				$emails->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$emails->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($emails->CurrentAction == "gridedit" && ($emails->RowType == EW_ROWTYPE_EDIT || $emails->RowType == EW_ROWTYPE_ADD) && $emails->EventCancelled) // Update failed
			$emails_grid->RestoreCurrentRowFormValues($emails_grid->RowIndex); // Restore form values
		if ($emails->RowType == EW_ROWTYPE_EDIT) // Edit row
			$emails_grid->EditRowCnt++;
		if ($emails->CurrentAction == "F") // Confirm row
			$emails_grid->RestoreCurrentRowFormValues($emails_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$emails->RowAttrs = array_merge($emails->RowAttrs, array('data-rowindex'=>$emails_grid->RowCnt, 'id'=>'r' . $emails_grid->RowCnt . '_emails', 'data-rowtype'=>$emails->RowType));

		// Render row
		$emails_grid->RenderRow();

		// Render list options
		$emails_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($emails_grid->RowAction <> "delete" && $emails_grid->RowAction <> "insertdelete" && !($emails_grid->RowAction == "insert" && $emails->CurrentAction == "F" && $emails_grid->EmptyRow())) {
?>
	<tr<?php echo $emails->RowAttributes() ?>>
<?php

// Render list options (body, left)
$emails_grid->ListOptions->Render("body", "left", $emails_grid->RowCnt);
?>
	<?php if ($emails->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $emails->Id->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="emails" data-field="x_Id" name="o<?php echo $emails_grid->RowIndex ?>_Id" id="o<?php echo $emails_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($emails->Id->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_Id" class="form-group emails_Id">
<span<?php echo $emails->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x_Id" name="x<?php echo $emails_grid->RowIndex ?>_Id" id="x<?php echo $emails_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($emails->Id->CurrentValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_Id" class="emails_Id">
<span<?php echo $emails->Id->ViewAttributes() ?>>
<?php echo $emails->Id->ListViewValue() ?></span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x_Id" name="x<?php echo $emails_grid->RowIndex ?>_Id" id="x<?php echo $emails_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($emails->Id->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_Id" name="o<?php echo $emails_grid->RowIndex ?>_Id" id="o<?php echo $emails_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($emails->Id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x_Id" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_Id" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($emails->Id->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_Id" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_Id" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($emails->Id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($emails->id_persona->Visible) { // id_persona ?>
		<td data-name="id_persona"<?php echo $emails->id_persona->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($emails->id_persona->getSessionValue() <> "") { ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_id_persona" class="form-group emails_id_persona">
<span<?php echo $emails->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($emails->id_persona->ViewValue)) && $emails->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $emails->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $emails->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $emails->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $emails_grid->RowIndex ?>_id_persona" name="x<?php echo $emails_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($emails->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_id_persona" class="form-group emails_id_persona">
<select data-table="emails" data-field="x_id_persona" data-value-separator="<?php echo $emails->id_persona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $emails_grid->RowIndex ?>_id_persona" name="x<?php echo $emails_grid->RowIndex ?>_id_persona"<?php echo $emails->id_persona->EditAttributes() ?>>
<?php echo $emails->id_persona->SelectOptionListHtml("x<?php echo $emails_grid->RowIndex ?>_id_persona") ?>
</select>
</span>
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_id_persona" name="o<?php echo $emails_grid->RowIndex ?>_id_persona" id="o<?php echo $emails_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($emails->id_persona->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($emails->id_persona->getSessionValue() <> "") { ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_id_persona" class="form-group emails_id_persona">
<span<?php echo $emails->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($emails->id_persona->ViewValue)) && $emails->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $emails->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $emails->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $emails->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $emails_grid->RowIndex ?>_id_persona" name="x<?php echo $emails_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($emails->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_id_persona" class="form-group emails_id_persona">
<select data-table="emails" data-field="x_id_persona" data-value-separator="<?php echo $emails->id_persona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $emails_grid->RowIndex ?>_id_persona" name="x<?php echo $emails_grid->RowIndex ?>_id_persona"<?php echo $emails->id_persona->EditAttributes() ?>>
<?php echo $emails->id_persona->SelectOptionListHtml("x<?php echo $emails_grid->RowIndex ?>_id_persona") ?>
</select>
</span>
<?php } ?>
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_id_persona" class="emails_id_persona">
<span<?php echo $emails->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($emails->id_persona->ListViewValue())) && $emails->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $emails->id_persona->LinkAttributes() ?>><?php echo $emails->id_persona->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $emails->id_persona->ListViewValue() ?>
<?php } ?>
</span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x_id_persona" name="x<?php echo $emails_grid->RowIndex ?>_id_persona" id="x<?php echo $emails_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($emails->id_persona->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_id_persona" name="o<?php echo $emails_grid->RowIndex ?>_id_persona" id="o<?php echo $emails_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($emails->id_persona->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x_id_persona" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_id_persona" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($emails->id_persona->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_id_persona" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_id_persona" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($emails->id_persona->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($emails->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $emails->_email->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails__email" class="form-group emails__email">
<input type="text" data-table="emails" data-field="x__email" name="x<?php echo $emails_grid->RowIndex ?>__email" id="x<?php echo $emails_grid->RowIndex ?>__email" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->_email->getPlaceHolder()) ?>" value="<?php echo $emails->_email->EditValue ?>"<?php echo $emails->_email->EditAttributes() ?>>
</span>
<input type="hidden" data-table="emails" data-field="x__email" name="o<?php echo $emails_grid->RowIndex ?>__email" id="o<?php echo $emails_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($emails->_email->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails__email" class="form-group emails__email">
<input type="text" data-table="emails" data-field="x__email" name="x<?php echo $emails_grid->RowIndex ?>__email" id="x<?php echo $emails_grid->RowIndex ?>__email" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->_email->getPlaceHolder()) ?>" value="<?php echo $emails->_email->EditValue ?>"<?php echo $emails->_email->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails__email" class="emails__email">
<span<?php echo $emails->_email->ViewAttributes() ?>>
<?php echo $emails->_email->ListViewValue() ?></span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x__email" name="x<?php echo $emails_grid->RowIndex ?>__email" id="x<?php echo $emails_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($emails->_email->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x__email" name="o<?php echo $emails_grid->RowIndex ?>__email" id="o<?php echo $emails_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($emails->_email->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x__email" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>__email" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($emails->_email->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x__email" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>__email" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($emails->_email->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$emails_grid->ListOptions->Render("body", "right", $emails_grid->RowCnt);
?>
	</tr>
<?php if ($emails->RowType == EW_ROWTYPE_ADD || $emails->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
femailsgrid.UpdateOpts(<?php echo $emails_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($emails->CurrentAction <> "gridadd" || $emails->CurrentMode == "copy")
		if (!$emails_grid->Recordset->EOF) $emails_grid->Recordset->MoveNext();
}
?>
<?php
	if ($emails->CurrentMode == "add" || $emails->CurrentMode == "copy" || $emails->CurrentMode == "edit") {
		$emails_grid->RowIndex = '$rowindex$';
		$emails_grid->LoadRowValues();

		// Set row properties
		$emails->ResetAttrs();
		$emails->RowAttrs = array_merge($emails->RowAttrs, array('data-rowindex'=>$emails_grid->RowIndex, 'id'=>'r0_emails', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($emails->RowAttrs["class"], "ewTemplate");
		$emails->RowType = EW_ROWTYPE_ADD;

		// Render row
		$emails_grid->RenderRow();

		// Render list options
		$emails_grid->RenderListOptions();
		$emails_grid->StartRowCnt = 0;
?>
	<tr<?php echo $emails->RowAttributes() ?>>
<?php

// Render list options (body, left)
$emails_grid->ListOptions->Render("body", "left", $emails_grid->RowIndex);
?>
	<?php if ($emails->Id->Visible) { // Id ?>
		<td data-name="Id">
<?php if ($emails->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_emails_Id" class="form-group emails_Id">
<span<?php echo $emails->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->Id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x_Id" name="x<?php echo $emails_grid->RowIndex ?>_Id" id="x<?php echo $emails_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($emails->Id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_Id" name="o<?php echo $emails_grid->RowIndex ?>_Id" id="o<?php echo $emails_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($emails->Id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($emails->id_persona->Visible) { // id_persona ?>
		<td data-name="id_persona">
<?php if ($emails->CurrentAction <> "F") { ?>
<?php if ($emails->id_persona->getSessionValue() <> "") { ?>
<span id="el$rowindex$_emails_id_persona" class="form-group emails_id_persona">
<span<?php echo $emails->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($emails->id_persona->ViewValue)) && $emails->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $emails->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $emails->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $emails->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x<?php echo $emails_grid->RowIndex ?>_id_persona" name="x<?php echo $emails_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($emails->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_emails_id_persona" class="form-group emails_id_persona">
<select data-table="emails" data-field="x_id_persona" data-value-separator="<?php echo $emails->id_persona->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $emails_grid->RowIndex ?>_id_persona" name="x<?php echo $emails_grid->RowIndex ?>_id_persona"<?php echo $emails->id_persona->EditAttributes() ?>>
<?php echo $emails->id_persona->SelectOptionListHtml("x<?php echo $emails_grid->RowIndex ?>_id_persona") ?>
</select>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_emails_id_persona" class="form-group emails_id_persona">
<span<?php echo $emails->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($emails->id_persona->ViewValue)) && $emails->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $emails->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $emails->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $emails->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" data-table="emails" data-field="x_id_persona" name="x<?php echo $emails_grid->RowIndex ?>_id_persona" id="x<?php echo $emails_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($emails->id_persona->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_id_persona" name="o<?php echo $emails_grid->RowIndex ?>_id_persona" id="o<?php echo $emails_grid->RowIndex ?>_id_persona" value="<?php echo ew_HtmlEncode($emails->id_persona->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($emails->_email->Visible) { // email ?>
		<td data-name="_email">
<?php if ($emails->CurrentAction <> "F") { ?>
<span id="el$rowindex$_emails__email" class="form-group emails__email">
<input type="text" data-table="emails" data-field="x__email" name="x<?php echo $emails_grid->RowIndex ?>__email" id="x<?php echo $emails_grid->RowIndex ?>__email" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->_email->getPlaceHolder()) ?>" value="<?php echo $emails->_email->EditValue ?>"<?php echo $emails->_email->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_emails__email" class="form-group emails__email">
<span<?php echo $emails->_email->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->_email->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x__email" name="x<?php echo $emails_grid->RowIndex ?>__email" id="x<?php echo $emails_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($emails->_email->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x__email" name="o<?php echo $emails_grid->RowIndex ?>__email" id="o<?php echo $emails_grid->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($emails->_email->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$emails_grid->ListOptions->Render("body", "right", $emails_grid->RowCnt);
?>
<script type="text/javascript">
femailsgrid.UpdateOpts(<?php echo $emails_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($emails->CurrentMode == "add" || $emails->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $emails_grid->FormKeyCountName ?>" id="<?php echo $emails_grid->FormKeyCountName ?>" value="<?php echo $emails_grid->KeyCount ?>">
<?php echo $emails_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($emails->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $emails_grid->FormKeyCountName ?>" id="<?php echo $emails_grid->FormKeyCountName ?>" value="<?php echo $emails_grid->KeyCount ?>">
<?php echo $emails_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($emails->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="femailsgrid">
</div>
<?php

// Close recordset
if ($emails_grid->Recordset)
	$emails_grid->Recordset->Close();
?>
<?php if ($emails_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($emails_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($emails_grid->TotalRecs == 0 && $emails->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($emails_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($emails->Export == "") { ?>
<script type="text/javascript">
femailsgrid.Init();
</script>
<?php } ?>
<?php
$emails_grid->Page_Terminate();
?>
