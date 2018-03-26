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
			elm = this.GetElements("x" + infix + "_email1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $emails->email1->FldCaption(), $emails->email1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_email1");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($emails->email1->FldErrMsg()) ?>");

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
	if (ew_ValueChanged(fobj, infix, "id_fuente", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_gestion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "email1", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tipo_documento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "no_documento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nombres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "paterno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "materno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "email2", false)) return false;
	if (ew_ValueChanged(fobj, infix, "email3", false)) return false;
	if (ew_ValueChanged(fobj, infix, "email4", false)) return false;
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
femailsgrid.Lists["x_id_persona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_paterno","x_materno","x_nombres",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
femailsgrid.Lists["x_id_persona"].Data = "<?php echo $emails_grid->id_persona->LookupFilterQuery(FALSE, "grid") ?>";
femailsgrid.Lists["x_id_fuente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"fuentes"};
femailsgrid.Lists["x_id_fuente"].Data = "<?php echo $emails_grid->id_fuente->LookupFilterQuery(FALSE, "grid") ?>";
femailsgrid.Lists["x_id_gestion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gestiones"};
femailsgrid.Lists["x_id_gestion"].Data = "<?php echo $emails_grid->id_gestion->LookupFilterQuery(FALSE, "grid") ?>";

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
<?php if ($emails->id_fuente->Visible) { // id_fuente ?>
	<?php if ($emails->SortUrl($emails->id_fuente) == "") { ?>
		<th data-name="id_fuente" class="<?php echo $emails->id_fuente->HeaderCellClass() ?>"><div id="elh_emails_id_fuente" class="emails_id_fuente"><div class="ewTableHeaderCaption"><?php echo $emails->id_fuente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_fuente" class="<?php echo $emails->id_fuente->HeaderCellClass() ?>"><div><div id="elh_emails_id_fuente" class="emails_id_fuente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->id_fuente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->id_fuente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->id_fuente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($emails->id_gestion->Visible) { // id_gestion ?>
	<?php if ($emails->SortUrl($emails->id_gestion) == "") { ?>
		<th data-name="id_gestion" class="<?php echo $emails->id_gestion->HeaderCellClass() ?>"><div id="elh_emails_id_gestion" class="emails_id_gestion"><div class="ewTableHeaderCaption"><?php echo $emails->id_gestion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_gestion" class="<?php echo $emails->id_gestion->HeaderCellClass() ?>"><div><div id="elh_emails_id_gestion" class="emails_id_gestion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->id_gestion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->id_gestion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->id_gestion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($emails->email1->Visible) { // email1 ?>
	<?php if ($emails->SortUrl($emails->email1) == "") { ?>
		<th data-name="email1" class="<?php echo $emails->email1->HeaderCellClass() ?>"><div id="elh_emails_email1" class="emails_email1"><div class="ewTableHeaderCaption"><?php echo $emails->email1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="email1" class="<?php echo $emails->email1->HeaderCellClass() ?>"><div><div id="elh_emails_email1" class="emails_email1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->email1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->email1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->email1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($emails->tipo_documento->Visible) { // tipo_documento ?>
	<?php if ($emails->SortUrl($emails->tipo_documento) == "") { ?>
		<th data-name="tipo_documento" class="<?php echo $emails->tipo_documento->HeaderCellClass() ?>"><div id="elh_emails_tipo_documento" class="emails_tipo_documento"><div class="ewTableHeaderCaption"><?php echo $emails->tipo_documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo_documento" class="<?php echo $emails->tipo_documento->HeaderCellClass() ?>"><div><div id="elh_emails_tipo_documento" class="emails_tipo_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->tipo_documento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->tipo_documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->tipo_documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($emails->no_documento->Visible) { // no_documento ?>
	<?php if ($emails->SortUrl($emails->no_documento) == "") { ?>
		<th data-name="no_documento" class="<?php echo $emails->no_documento->HeaderCellClass() ?>"><div id="elh_emails_no_documento" class="emails_no_documento"><div class="ewTableHeaderCaption"><?php echo $emails->no_documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="no_documento" class="<?php echo $emails->no_documento->HeaderCellClass() ?>"><div><div id="elh_emails_no_documento" class="emails_no_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->no_documento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->no_documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->no_documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($emails->nombres->Visible) { // nombres ?>
	<?php if ($emails->SortUrl($emails->nombres) == "") { ?>
		<th data-name="nombres" class="<?php echo $emails->nombres->HeaderCellClass() ?>"><div id="elh_emails_nombres" class="emails_nombres"><div class="ewTableHeaderCaption"><?php echo $emails->nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombres" class="<?php echo $emails->nombres->HeaderCellClass() ?>"><div><div id="elh_emails_nombres" class="emails_nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->nombres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($emails->paterno->Visible) { // paterno ?>
	<?php if ($emails->SortUrl($emails->paterno) == "") { ?>
		<th data-name="paterno" class="<?php echo $emails->paterno->HeaderCellClass() ?>"><div id="elh_emails_paterno" class="emails_paterno"><div class="ewTableHeaderCaption"><?php echo $emails->paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="paterno" class="<?php echo $emails->paterno->HeaderCellClass() ?>"><div><div id="elh_emails_paterno" class="emails_paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->paterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($emails->materno->Visible) { // materno ?>
	<?php if ($emails->SortUrl($emails->materno) == "") { ?>
		<th data-name="materno" class="<?php echo $emails->materno->HeaderCellClass() ?>"><div id="elh_emails_materno" class="emails_materno"><div class="ewTableHeaderCaption"><?php echo $emails->materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="materno" class="<?php echo $emails->materno->HeaderCellClass() ?>"><div><div id="elh_emails_materno" class="emails_materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->materno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($emails->email2->Visible) { // email2 ?>
	<?php if ($emails->SortUrl($emails->email2) == "") { ?>
		<th data-name="email2" class="<?php echo $emails->email2->HeaderCellClass() ?>"><div id="elh_emails_email2" class="emails_email2"><div class="ewTableHeaderCaption"><?php echo $emails->email2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="email2" class="<?php echo $emails->email2->HeaderCellClass() ?>"><div><div id="elh_emails_email2" class="emails_email2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->email2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->email2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->email2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($emails->email3->Visible) { // email3 ?>
	<?php if ($emails->SortUrl($emails->email3) == "") { ?>
		<th data-name="email3" class="<?php echo $emails->email3->HeaderCellClass() ?>"><div id="elh_emails_email3" class="emails_email3"><div class="ewTableHeaderCaption"><?php echo $emails->email3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="email3" class="<?php echo $emails->email3->HeaderCellClass() ?>"><div><div id="elh_emails_email3" class="emails_email3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->email3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->email3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->email3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($emails->email4->Visible) { // email4 ?>
	<?php if ($emails->SortUrl($emails->email4) == "") { ?>
		<th data-name="email4" class="<?php echo $emails->email4->HeaderCellClass() ?>"><div id="elh_emails_email4" class="emails_email4"><div class="ewTableHeaderCaption"><?php echo $emails->email4->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="email4" class="<?php echo $emails->email4->HeaderCellClass() ?>"><div><div id="elh_emails_email4" class="emails_email4">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $emails->email4->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($emails->email4->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($emails->email4->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
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
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $emails_grid->RowIndex ?>_id_persona"><?php echo (strval($emails->id_persona->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $emails->id_persona->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($emails->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $emails_grid->RowIndex ?>_id_persona',m:0,n:30});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="emails" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $emails->id_persona->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $emails_grid->RowIndex ?>_id_persona" id="x<?php echo $emails_grid->RowIndex ?>_id_persona" value="<?php echo $emails->id_persona->CurrentValue ?>"<?php echo $emails->id_persona->EditAttributes() ?>>
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
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $emails_grid->RowIndex ?>_id_persona"><?php echo (strval($emails->id_persona->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $emails->id_persona->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($emails->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $emails_grid->RowIndex ?>_id_persona',m:0,n:30});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="emails" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $emails->id_persona->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $emails_grid->RowIndex ?>_id_persona" id="x<?php echo $emails_grid->RowIndex ?>_id_persona" value="<?php echo $emails->id_persona->CurrentValue ?>"<?php echo $emails->id_persona->EditAttributes() ?>>
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
	<?php if ($emails->id_fuente->Visible) { // id_fuente ?>
		<td data-name="id_fuente"<?php echo $emails->id_fuente->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_id_fuente" class="form-group emails_id_fuente">
<select data-table="emails" data-field="x_id_fuente" data-value-separator="<?php echo $emails->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $emails_grid->RowIndex ?>_id_fuente" name="x<?php echo $emails_grid->RowIndex ?>_id_fuente"<?php echo $emails->id_fuente->EditAttributes() ?>>
<?php echo $emails->id_fuente->SelectOptionListHtml("x<?php echo $emails_grid->RowIndex ?>_id_fuente") ?>
</select>
</span>
<input type="hidden" data-table="emails" data-field="x_id_fuente" name="o<?php echo $emails_grid->RowIndex ?>_id_fuente" id="o<?php echo $emails_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($emails->id_fuente->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_id_fuente" class="form-group emails_id_fuente">
<select data-table="emails" data-field="x_id_fuente" data-value-separator="<?php echo $emails->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $emails_grid->RowIndex ?>_id_fuente" name="x<?php echo $emails_grid->RowIndex ?>_id_fuente"<?php echo $emails->id_fuente->EditAttributes() ?>>
<?php echo $emails->id_fuente->SelectOptionListHtml("x<?php echo $emails_grid->RowIndex ?>_id_fuente") ?>
</select>
</span>
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_id_fuente" class="emails_id_fuente">
<span<?php echo $emails->id_fuente->ViewAttributes() ?>>
<?php echo $emails->id_fuente->ListViewValue() ?></span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x_id_fuente" name="x<?php echo $emails_grid->RowIndex ?>_id_fuente" id="x<?php echo $emails_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($emails->id_fuente->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_id_fuente" name="o<?php echo $emails_grid->RowIndex ?>_id_fuente" id="o<?php echo $emails_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($emails->id_fuente->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x_id_fuente" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_id_fuente" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($emails->id_fuente->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_id_fuente" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_id_fuente" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($emails->id_fuente->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($emails->id_gestion->Visible) { // id_gestion ?>
		<td data-name="id_gestion"<?php echo $emails->id_gestion->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_id_gestion" class="form-group emails_id_gestion">
<select data-table="emails" data-field="x_id_gestion" data-value-separator="<?php echo $emails->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $emails_grid->RowIndex ?>_id_gestion" name="x<?php echo $emails_grid->RowIndex ?>_id_gestion"<?php echo $emails->id_gestion->EditAttributes() ?>>
<?php echo $emails->id_gestion->SelectOptionListHtml("x<?php echo $emails_grid->RowIndex ?>_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$emails->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $emails->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $emails_grid->RowIndex ?>_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $emails_grid->RowIndex ?>_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $emails->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<input type="hidden" data-table="emails" data-field="x_id_gestion" name="o<?php echo $emails_grid->RowIndex ?>_id_gestion" id="o<?php echo $emails_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($emails->id_gestion->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_id_gestion" class="form-group emails_id_gestion">
<select data-table="emails" data-field="x_id_gestion" data-value-separator="<?php echo $emails->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $emails_grid->RowIndex ?>_id_gestion" name="x<?php echo $emails_grid->RowIndex ?>_id_gestion"<?php echo $emails->id_gestion->EditAttributes() ?>>
<?php echo $emails->id_gestion->SelectOptionListHtml("x<?php echo $emails_grid->RowIndex ?>_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$emails->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $emails->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $emails_grid->RowIndex ?>_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $emails_grid->RowIndex ?>_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $emails->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_id_gestion" class="emails_id_gestion">
<span<?php echo $emails->id_gestion->ViewAttributes() ?>>
<?php echo $emails->id_gestion->ListViewValue() ?></span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x_id_gestion" name="x<?php echo $emails_grid->RowIndex ?>_id_gestion" id="x<?php echo $emails_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($emails->id_gestion->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_id_gestion" name="o<?php echo $emails_grid->RowIndex ?>_id_gestion" id="o<?php echo $emails_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($emails->id_gestion->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x_id_gestion" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_id_gestion" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($emails->id_gestion->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_id_gestion" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_id_gestion" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($emails->id_gestion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($emails->email1->Visible) { // email1 ?>
		<td data-name="email1"<?php echo $emails->email1->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_email1" class="form-group emails_email1">
<input type="text" data-table="emails" data-field="x_email1" name="x<?php echo $emails_grid->RowIndex ?>_email1" id="x<?php echo $emails_grid->RowIndex ?>_email1" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->email1->getPlaceHolder()) ?>" value="<?php echo $emails->email1->EditValue ?>"<?php echo $emails->email1->EditAttributes() ?>>
</span>
<input type="hidden" data-table="emails" data-field="x_email1" name="o<?php echo $emails_grid->RowIndex ?>_email1" id="o<?php echo $emails_grid->RowIndex ?>_email1" value="<?php echo ew_HtmlEncode($emails->email1->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_email1" class="form-group emails_email1">
<input type="text" data-table="emails" data-field="x_email1" name="x<?php echo $emails_grid->RowIndex ?>_email1" id="x<?php echo $emails_grid->RowIndex ?>_email1" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->email1->getPlaceHolder()) ?>" value="<?php echo $emails->email1->EditValue ?>"<?php echo $emails->email1->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_email1" class="emails_email1">
<span<?php echo $emails->email1->ViewAttributes() ?>>
<?php echo $emails->email1->ListViewValue() ?></span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x_email1" name="x<?php echo $emails_grid->RowIndex ?>_email1" id="x<?php echo $emails_grid->RowIndex ?>_email1" value="<?php echo ew_HtmlEncode($emails->email1->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_email1" name="o<?php echo $emails_grid->RowIndex ?>_email1" id="o<?php echo $emails_grid->RowIndex ?>_email1" value="<?php echo ew_HtmlEncode($emails->email1->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x_email1" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_email1" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_email1" value="<?php echo ew_HtmlEncode($emails->email1->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_email1" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_email1" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_email1" value="<?php echo ew_HtmlEncode($emails->email1->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($emails->tipo_documento->Visible) { // tipo_documento ?>
		<td data-name="tipo_documento"<?php echo $emails->tipo_documento->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_tipo_documento" class="form-group emails_tipo_documento">
<input type="text" data-table="emails" data-field="x_tipo_documento" name="x<?php echo $emails_grid->RowIndex ?>_tipo_documento" id="x<?php echo $emails_grid->RowIndex ?>_tipo_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($emails->tipo_documento->getPlaceHolder()) ?>" value="<?php echo $emails->tipo_documento->EditValue ?>"<?php echo $emails->tipo_documento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="emails" data-field="x_tipo_documento" name="o<?php echo $emails_grid->RowIndex ?>_tipo_documento" id="o<?php echo $emails_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($emails->tipo_documento->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_tipo_documento" class="form-group emails_tipo_documento">
<input type="text" data-table="emails" data-field="x_tipo_documento" name="x<?php echo $emails_grid->RowIndex ?>_tipo_documento" id="x<?php echo $emails_grid->RowIndex ?>_tipo_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($emails->tipo_documento->getPlaceHolder()) ?>" value="<?php echo $emails->tipo_documento->EditValue ?>"<?php echo $emails->tipo_documento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_tipo_documento" class="emails_tipo_documento">
<span<?php echo $emails->tipo_documento->ViewAttributes() ?>>
<?php echo $emails->tipo_documento->ListViewValue() ?></span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x_tipo_documento" name="x<?php echo $emails_grid->RowIndex ?>_tipo_documento" id="x<?php echo $emails_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($emails->tipo_documento->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_tipo_documento" name="o<?php echo $emails_grid->RowIndex ?>_tipo_documento" id="o<?php echo $emails_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($emails->tipo_documento->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x_tipo_documento" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_tipo_documento" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($emails->tipo_documento->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_tipo_documento" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_tipo_documento" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($emails->tipo_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($emails->no_documento->Visible) { // no_documento ?>
		<td data-name="no_documento"<?php echo $emails->no_documento->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_no_documento" class="form-group emails_no_documento">
<input type="text" data-table="emails" data-field="x_no_documento" name="x<?php echo $emails_grid->RowIndex ?>_no_documento" id="x<?php echo $emails_grid->RowIndex ?>_no_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($emails->no_documento->getPlaceHolder()) ?>" value="<?php echo $emails->no_documento->EditValue ?>"<?php echo $emails->no_documento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="emails" data-field="x_no_documento" name="o<?php echo $emails_grid->RowIndex ?>_no_documento" id="o<?php echo $emails_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($emails->no_documento->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_no_documento" class="form-group emails_no_documento">
<input type="text" data-table="emails" data-field="x_no_documento" name="x<?php echo $emails_grid->RowIndex ?>_no_documento" id="x<?php echo $emails_grid->RowIndex ?>_no_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($emails->no_documento->getPlaceHolder()) ?>" value="<?php echo $emails->no_documento->EditValue ?>"<?php echo $emails->no_documento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_no_documento" class="emails_no_documento">
<span<?php echo $emails->no_documento->ViewAttributes() ?>>
<?php echo $emails->no_documento->ListViewValue() ?></span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x_no_documento" name="x<?php echo $emails_grid->RowIndex ?>_no_documento" id="x<?php echo $emails_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($emails->no_documento->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_no_documento" name="o<?php echo $emails_grid->RowIndex ?>_no_documento" id="o<?php echo $emails_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($emails->no_documento->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x_no_documento" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_no_documento" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($emails->no_documento->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_no_documento" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_no_documento" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($emails->no_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($emails->nombres->Visible) { // nombres ?>
		<td data-name="nombres"<?php echo $emails->nombres->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_nombres" class="form-group emails_nombres">
<input type="text" data-table="emails" data-field="x_nombres" name="x<?php echo $emails_grid->RowIndex ?>_nombres" id="x<?php echo $emails_grid->RowIndex ?>_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->nombres->getPlaceHolder()) ?>" value="<?php echo $emails->nombres->EditValue ?>"<?php echo $emails->nombres->EditAttributes() ?>>
</span>
<input type="hidden" data-table="emails" data-field="x_nombres" name="o<?php echo $emails_grid->RowIndex ?>_nombres" id="o<?php echo $emails_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($emails->nombres->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_nombres" class="form-group emails_nombres">
<input type="text" data-table="emails" data-field="x_nombres" name="x<?php echo $emails_grid->RowIndex ?>_nombres" id="x<?php echo $emails_grid->RowIndex ?>_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->nombres->getPlaceHolder()) ?>" value="<?php echo $emails->nombres->EditValue ?>"<?php echo $emails->nombres->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_nombres" class="emails_nombres">
<span<?php echo $emails->nombres->ViewAttributes() ?>>
<?php echo $emails->nombres->ListViewValue() ?></span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x_nombres" name="x<?php echo $emails_grid->RowIndex ?>_nombres" id="x<?php echo $emails_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($emails->nombres->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_nombres" name="o<?php echo $emails_grid->RowIndex ?>_nombres" id="o<?php echo $emails_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($emails->nombres->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x_nombres" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_nombres" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($emails->nombres->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_nombres" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_nombres" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($emails->nombres->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($emails->paterno->Visible) { // paterno ?>
		<td data-name="paterno"<?php echo $emails->paterno->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_paterno" class="form-group emails_paterno">
<input type="text" data-table="emails" data-field="x_paterno" name="x<?php echo $emails_grid->RowIndex ?>_paterno" id="x<?php echo $emails_grid->RowIndex ?>_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->paterno->getPlaceHolder()) ?>" value="<?php echo $emails->paterno->EditValue ?>"<?php echo $emails->paterno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="emails" data-field="x_paterno" name="o<?php echo $emails_grid->RowIndex ?>_paterno" id="o<?php echo $emails_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($emails->paterno->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_paterno" class="form-group emails_paterno">
<input type="text" data-table="emails" data-field="x_paterno" name="x<?php echo $emails_grid->RowIndex ?>_paterno" id="x<?php echo $emails_grid->RowIndex ?>_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->paterno->getPlaceHolder()) ?>" value="<?php echo $emails->paterno->EditValue ?>"<?php echo $emails->paterno->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_paterno" class="emails_paterno">
<span<?php echo $emails->paterno->ViewAttributes() ?>>
<?php echo $emails->paterno->ListViewValue() ?></span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x_paterno" name="x<?php echo $emails_grid->RowIndex ?>_paterno" id="x<?php echo $emails_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($emails->paterno->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_paterno" name="o<?php echo $emails_grid->RowIndex ?>_paterno" id="o<?php echo $emails_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($emails->paterno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x_paterno" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_paterno" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($emails->paterno->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_paterno" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_paterno" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($emails->paterno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($emails->materno->Visible) { // materno ?>
		<td data-name="materno"<?php echo $emails->materno->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_materno" class="form-group emails_materno">
<input type="text" data-table="emails" data-field="x_materno" name="x<?php echo $emails_grid->RowIndex ?>_materno" id="x<?php echo $emails_grid->RowIndex ?>_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->materno->getPlaceHolder()) ?>" value="<?php echo $emails->materno->EditValue ?>"<?php echo $emails->materno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="emails" data-field="x_materno" name="o<?php echo $emails_grid->RowIndex ?>_materno" id="o<?php echo $emails_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($emails->materno->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_materno" class="form-group emails_materno">
<input type="text" data-table="emails" data-field="x_materno" name="x<?php echo $emails_grid->RowIndex ?>_materno" id="x<?php echo $emails_grid->RowIndex ?>_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->materno->getPlaceHolder()) ?>" value="<?php echo $emails->materno->EditValue ?>"<?php echo $emails->materno->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_materno" class="emails_materno">
<span<?php echo $emails->materno->ViewAttributes() ?>>
<?php echo $emails->materno->ListViewValue() ?></span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x_materno" name="x<?php echo $emails_grid->RowIndex ?>_materno" id="x<?php echo $emails_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($emails->materno->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_materno" name="o<?php echo $emails_grid->RowIndex ?>_materno" id="o<?php echo $emails_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($emails->materno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x_materno" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_materno" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($emails->materno->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_materno" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_materno" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($emails->materno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($emails->email2->Visible) { // email2 ?>
		<td data-name="email2"<?php echo $emails->email2->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_email2" class="form-group emails_email2">
<input type="text" data-table="emails" data-field="x_email2" name="x<?php echo $emails_grid->RowIndex ?>_email2" id="x<?php echo $emails_grid->RowIndex ?>_email2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->email2->getPlaceHolder()) ?>" value="<?php echo $emails->email2->EditValue ?>"<?php echo $emails->email2->EditAttributes() ?>>
</span>
<input type="hidden" data-table="emails" data-field="x_email2" name="o<?php echo $emails_grid->RowIndex ?>_email2" id="o<?php echo $emails_grid->RowIndex ?>_email2" value="<?php echo ew_HtmlEncode($emails->email2->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_email2" class="form-group emails_email2">
<input type="text" data-table="emails" data-field="x_email2" name="x<?php echo $emails_grid->RowIndex ?>_email2" id="x<?php echo $emails_grid->RowIndex ?>_email2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->email2->getPlaceHolder()) ?>" value="<?php echo $emails->email2->EditValue ?>"<?php echo $emails->email2->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_email2" class="emails_email2">
<span<?php echo $emails->email2->ViewAttributes() ?>>
<?php echo $emails->email2->ListViewValue() ?></span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x_email2" name="x<?php echo $emails_grid->RowIndex ?>_email2" id="x<?php echo $emails_grid->RowIndex ?>_email2" value="<?php echo ew_HtmlEncode($emails->email2->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_email2" name="o<?php echo $emails_grid->RowIndex ?>_email2" id="o<?php echo $emails_grid->RowIndex ?>_email2" value="<?php echo ew_HtmlEncode($emails->email2->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x_email2" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_email2" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_email2" value="<?php echo ew_HtmlEncode($emails->email2->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_email2" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_email2" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_email2" value="<?php echo ew_HtmlEncode($emails->email2->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($emails->email3->Visible) { // email3 ?>
		<td data-name="email3"<?php echo $emails->email3->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_email3" class="form-group emails_email3">
<input type="text" data-table="emails" data-field="x_email3" name="x<?php echo $emails_grid->RowIndex ?>_email3" id="x<?php echo $emails_grid->RowIndex ?>_email3" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->email3->getPlaceHolder()) ?>" value="<?php echo $emails->email3->EditValue ?>"<?php echo $emails->email3->EditAttributes() ?>>
</span>
<input type="hidden" data-table="emails" data-field="x_email3" name="o<?php echo $emails_grid->RowIndex ?>_email3" id="o<?php echo $emails_grid->RowIndex ?>_email3" value="<?php echo ew_HtmlEncode($emails->email3->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_email3" class="form-group emails_email3">
<input type="text" data-table="emails" data-field="x_email3" name="x<?php echo $emails_grid->RowIndex ?>_email3" id="x<?php echo $emails_grid->RowIndex ?>_email3" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->email3->getPlaceHolder()) ?>" value="<?php echo $emails->email3->EditValue ?>"<?php echo $emails->email3->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_email3" class="emails_email3">
<span<?php echo $emails->email3->ViewAttributes() ?>>
<?php echo $emails->email3->ListViewValue() ?></span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x_email3" name="x<?php echo $emails_grid->RowIndex ?>_email3" id="x<?php echo $emails_grid->RowIndex ?>_email3" value="<?php echo ew_HtmlEncode($emails->email3->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_email3" name="o<?php echo $emails_grid->RowIndex ?>_email3" id="o<?php echo $emails_grid->RowIndex ?>_email3" value="<?php echo ew_HtmlEncode($emails->email3->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x_email3" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_email3" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_email3" value="<?php echo ew_HtmlEncode($emails->email3->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_email3" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_email3" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_email3" value="<?php echo ew_HtmlEncode($emails->email3->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($emails->email4->Visible) { // email4 ?>
		<td data-name="email4"<?php echo $emails->email4->CellAttributes() ?>>
<?php if ($emails->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_email4" class="form-group emails_email4">
<input type="text" data-table="emails" data-field="x_email4" name="x<?php echo $emails_grid->RowIndex ?>_email4" id="x<?php echo $emails_grid->RowIndex ?>_email4" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->email4->getPlaceHolder()) ?>" value="<?php echo $emails->email4->EditValue ?>"<?php echo $emails->email4->EditAttributes() ?>>
</span>
<input type="hidden" data-table="emails" data-field="x_email4" name="o<?php echo $emails_grid->RowIndex ?>_email4" id="o<?php echo $emails_grid->RowIndex ?>_email4" value="<?php echo ew_HtmlEncode($emails->email4->OldValue) ?>">
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_email4" class="form-group emails_email4">
<input type="text" data-table="emails" data-field="x_email4" name="x<?php echo $emails_grid->RowIndex ?>_email4" id="x<?php echo $emails_grid->RowIndex ?>_email4" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->email4->getPlaceHolder()) ?>" value="<?php echo $emails->email4->EditValue ?>"<?php echo $emails->email4->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($emails->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $emails_grid->RowCnt ?>_emails_email4" class="emails_email4">
<span<?php echo $emails->email4->ViewAttributes() ?>>
<?php echo $emails->email4->ListViewValue() ?></span>
</span>
<?php if ($emails->CurrentAction <> "F") { ?>
<input type="hidden" data-table="emails" data-field="x_email4" name="x<?php echo $emails_grid->RowIndex ?>_email4" id="x<?php echo $emails_grid->RowIndex ?>_email4" value="<?php echo ew_HtmlEncode($emails->email4->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_email4" name="o<?php echo $emails_grid->RowIndex ?>_email4" id="o<?php echo $emails_grid->RowIndex ?>_email4" value="<?php echo ew_HtmlEncode($emails->email4->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="emails" data-field="x_email4" name="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_email4" id="femailsgrid$x<?php echo $emails_grid->RowIndex ?>_email4" value="<?php echo ew_HtmlEncode($emails->email4->FormValue) ?>">
<input type="hidden" data-table="emails" data-field="x_email4" name="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_email4" id="femailsgrid$o<?php echo $emails_grid->RowIndex ?>_email4" value="<?php echo ew_HtmlEncode($emails->email4->OldValue) ?>">
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
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $emails_grid->RowIndex ?>_id_persona"><?php echo (strval($emails->id_persona->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $emails->id_persona->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($emails->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $emails_grid->RowIndex ?>_id_persona',m:0,n:30});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="emails" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $emails->id_persona->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $emails_grid->RowIndex ?>_id_persona" id="x<?php echo $emails_grid->RowIndex ?>_id_persona" value="<?php echo $emails->id_persona->CurrentValue ?>"<?php echo $emails->id_persona->EditAttributes() ?>>
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
	<?php if ($emails->id_fuente->Visible) { // id_fuente ?>
		<td data-name="id_fuente">
<?php if ($emails->CurrentAction <> "F") { ?>
<span id="el$rowindex$_emails_id_fuente" class="form-group emails_id_fuente">
<select data-table="emails" data-field="x_id_fuente" data-value-separator="<?php echo $emails->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $emails_grid->RowIndex ?>_id_fuente" name="x<?php echo $emails_grid->RowIndex ?>_id_fuente"<?php echo $emails->id_fuente->EditAttributes() ?>>
<?php echo $emails->id_fuente->SelectOptionListHtml("x<?php echo $emails_grid->RowIndex ?>_id_fuente") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_emails_id_fuente" class="form-group emails_id_fuente">
<span<?php echo $emails->id_fuente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->id_fuente->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x_id_fuente" name="x<?php echo $emails_grid->RowIndex ?>_id_fuente" id="x<?php echo $emails_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($emails->id_fuente->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_id_fuente" name="o<?php echo $emails_grid->RowIndex ?>_id_fuente" id="o<?php echo $emails_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($emails->id_fuente->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($emails->id_gestion->Visible) { // id_gestion ?>
		<td data-name="id_gestion">
<?php if ($emails->CurrentAction <> "F") { ?>
<span id="el$rowindex$_emails_id_gestion" class="form-group emails_id_gestion">
<select data-table="emails" data-field="x_id_gestion" data-value-separator="<?php echo $emails->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $emails_grid->RowIndex ?>_id_gestion" name="x<?php echo $emails_grid->RowIndex ?>_id_gestion"<?php echo $emails->id_gestion->EditAttributes() ?>>
<?php echo $emails->id_gestion->SelectOptionListHtml("x<?php echo $emails_grid->RowIndex ?>_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$emails->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $emails->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $emails_grid->RowIndex ?>_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $emails_grid->RowIndex ?>_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $emails->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_emails_id_gestion" class="form-group emails_id_gestion">
<span<?php echo $emails->id_gestion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->id_gestion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x_id_gestion" name="x<?php echo $emails_grid->RowIndex ?>_id_gestion" id="x<?php echo $emails_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($emails->id_gestion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_id_gestion" name="o<?php echo $emails_grid->RowIndex ?>_id_gestion" id="o<?php echo $emails_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($emails->id_gestion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($emails->email1->Visible) { // email1 ?>
		<td data-name="email1">
<?php if ($emails->CurrentAction <> "F") { ?>
<span id="el$rowindex$_emails_email1" class="form-group emails_email1">
<input type="text" data-table="emails" data-field="x_email1" name="x<?php echo $emails_grid->RowIndex ?>_email1" id="x<?php echo $emails_grid->RowIndex ?>_email1" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->email1->getPlaceHolder()) ?>" value="<?php echo $emails->email1->EditValue ?>"<?php echo $emails->email1->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_emails_email1" class="form-group emails_email1">
<span<?php echo $emails->email1->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->email1->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x_email1" name="x<?php echo $emails_grid->RowIndex ?>_email1" id="x<?php echo $emails_grid->RowIndex ?>_email1" value="<?php echo ew_HtmlEncode($emails->email1->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_email1" name="o<?php echo $emails_grid->RowIndex ?>_email1" id="o<?php echo $emails_grid->RowIndex ?>_email1" value="<?php echo ew_HtmlEncode($emails->email1->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($emails->tipo_documento->Visible) { // tipo_documento ?>
		<td data-name="tipo_documento">
<?php if ($emails->CurrentAction <> "F") { ?>
<span id="el$rowindex$_emails_tipo_documento" class="form-group emails_tipo_documento">
<input type="text" data-table="emails" data-field="x_tipo_documento" name="x<?php echo $emails_grid->RowIndex ?>_tipo_documento" id="x<?php echo $emails_grid->RowIndex ?>_tipo_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($emails->tipo_documento->getPlaceHolder()) ?>" value="<?php echo $emails->tipo_documento->EditValue ?>"<?php echo $emails->tipo_documento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_emails_tipo_documento" class="form-group emails_tipo_documento">
<span<?php echo $emails->tipo_documento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->tipo_documento->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x_tipo_documento" name="x<?php echo $emails_grid->RowIndex ?>_tipo_documento" id="x<?php echo $emails_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($emails->tipo_documento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_tipo_documento" name="o<?php echo $emails_grid->RowIndex ?>_tipo_documento" id="o<?php echo $emails_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($emails->tipo_documento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($emails->no_documento->Visible) { // no_documento ?>
		<td data-name="no_documento">
<?php if ($emails->CurrentAction <> "F") { ?>
<span id="el$rowindex$_emails_no_documento" class="form-group emails_no_documento">
<input type="text" data-table="emails" data-field="x_no_documento" name="x<?php echo $emails_grid->RowIndex ?>_no_documento" id="x<?php echo $emails_grid->RowIndex ?>_no_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($emails->no_documento->getPlaceHolder()) ?>" value="<?php echo $emails->no_documento->EditValue ?>"<?php echo $emails->no_documento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_emails_no_documento" class="form-group emails_no_documento">
<span<?php echo $emails->no_documento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->no_documento->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x_no_documento" name="x<?php echo $emails_grid->RowIndex ?>_no_documento" id="x<?php echo $emails_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($emails->no_documento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_no_documento" name="o<?php echo $emails_grid->RowIndex ?>_no_documento" id="o<?php echo $emails_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($emails->no_documento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($emails->nombres->Visible) { // nombres ?>
		<td data-name="nombres">
<?php if ($emails->CurrentAction <> "F") { ?>
<span id="el$rowindex$_emails_nombres" class="form-group emails_nombres">
<input type="text" data-table="emails" data-field="x_nombres" name="x<?php echo $emails_grid->RowIndex ?>_nombres" id="x<?php echo $emails_grid->RowIndex ?>_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->nombres->getPlaceHolder()) ?>" value="<?php echo $emails->nombres->EditValue ?>"<?php echo $emails->nombres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_emails_nombres" class="form-group emails_nombres">
<span<?php echo $emails->nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->nombres->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x_nombres" name="x<?php echo $emails_grid->RowIndex ?>_nombres" id="x<?php echo $emails_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($emails->nombres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_nombres" name="o<?php echo $emails_grid->RowIndex ?>_nombres" id="o<?php echo $emails_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($emails->nombres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($emails->paterno->Visible) { // paterno ?>
		<td data-name="paterno">
<?php if ($emails->CurrentAction <> "F") { ?>
<span id="el$rowindex$_emails_paterno" class="form-group emails_paterno">
<input type="text" data-table="emails" data-field="x_paterno" name="x<?php echo $emails_grid->RowIndex ?>_paterno" id="x<?php echo $emails_grid->RowIndex ?>_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->paterno->getPlaceHolder()) ?>" value="<?php echo $emails->paterno->EditValue ?>"<?php echo $emails->paterno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_emails_paterno" class="form-group emails_paterno">
<span<?php echo $emails->paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->paterno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x_paterno" name="x<?php echo $emails_grid->RowIndex ?>_paterno" id="x<?php echo $emails_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($emails->paterno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_paterno" name="o<?php echo $emails_grid->RowIndex ?>_paterno" id="o<?php echo $emails_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($emails->paterno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($emails->materno->Visible) { // materno ?>
		<td data-name="materno">
<?php if ($emails->CurrentAction <> "F") { ?>
<span id="el$rowindex$_emails_materno" class="form-group emails_materno">
<input type="text" data-table="emails" data-field="x_materno" name="x<?php echo $emails_grid->RowIndex ?>_materno" id="x<?php echo $emails_grid->RowIndex ?>_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->materno->getPlaceHolder()) ?>" value="<?php echo $emails->materno->EditValue ?>"<?php echo $emails->materno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_emails_materno" class="form-group emails_materno">
<span<?php echo $emails->materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->materno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x_materno" name="x<?php echo $emails_grid->RowIndex ?>_materno" id="x<?php echo $emails_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($emails->materno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_materno" name="o<?php echo $emails_grid->RowIndex ?>_materno" id="o<?php echo $emails_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($emails->materno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($emails->email2->Visible) { // email2 ?>
		<td data-name="email2">
<?php if ($emails->CurrentAction <> "F") { ?>
<span id="el$rowindex$_emails_email2" class="form-group emails_email2">
<input type="text" data-table="emails" data-field="x_email2" name="x<?php echo $emails_grid->RowIndex ?>_email2" id="x<?php echo $emails_grid->RowIndex ?>_email2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->email2->getPlaceHolder()) ?>" value="<?php echo $emails->email2->EditValue ?>"<?php echo $emails->email2->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_emails_email2" class="form-group emails_email2">
<span<?php echo $emails->email2->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->email2->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x_email2" name="x<?php echo $emails_grid->RowIndex ?>_email2" id="x<?php echo $emails_grid->RowIndex ?>_email2" value="<?php echo ew_HtmlEncode($emails->email2->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_email2" name="o<?php echo $emails_grid->RowIndex ?>_email2" id="o<?php echo $emails_grid->RowIndex ?>_email2" value="<?php echo ew_HtmlEncode($emails->email2->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($emails->email3->Visible) { // email3 ?>
		<td data-name="email3">
<?php if ($emails->CurrentAction <> "F") { ?>
<span id="el$rowindex$_emails_email3" class="form-group emails_email3">
<input type="text" data-table="emails" data-field="x_email3" name="x<?php echo $emails_grid->RowIndex ?>_email3" id="x<?php echo $emails_grid->RowIndex ?>_email3" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->email3->getPlaceHolder()) ?>" value="<?php echo $emails->email3->EditValue ?>"<?php echo $emails->email3->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_emails_email3" class="form-group emails_email3">
<span<?php echo $emails->email3->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->email3->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x_email3" name="x<?php echo $emails_grid->RowIndex ?>_email3" id="x<?php echo $emails_grid->RowIndex ?>_email3" value="<?php echo ew_HtmlEncode($emails->email3->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_email3" name="o<?php echo $emails_grid->RowIndex ?>_email3" id="o<?php echo $emails_grid->RowIndex ?>_email3" value="<?php echo ew_HtmlEncode($emails->email3->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($emails->email4->Visible) { // email4 ?>
		<td data-name="email4">
<?php if ($emails->CurrentAction <> "F") { ?>
<span id="el$rowindex$_emails_email4" class="form-group emails_email4">
<input type="text" data-table="emails" data-field="x_email4" name="x<?php echo $emails_grid->RowIndex ?>_email4" id="x<?php echo $emails_grid->RowIndex ?>_email4" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($emails->email4->getPlaceHolder()) ?>" value="<?php echo $emails->email4->EditValue ?>"<?php echo $emails->email4->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_emails_email4" class="form-group emails_email4">
<span<?php echo $emails->email4->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $emails->email4->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="emails" data-field="x_email4" name="x<?php echo $emails_grid->RowIndex ?>_email4" id="x<?php echo $emails_grid->RowIndex ?>_email4" value="<?php echo ew_HtmlEncode($emails->email4->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="emails" data-field="x_email4" name="o<?php echo $emails_grid->RowIndex ?>_email4" id="o<?php echo $emails_grid->RowIndex ?>_email4" value="<?php echo ew_HtmlEncode($emails->email4->OldValue) ?>">
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
