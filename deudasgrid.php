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
			elm = this.GetElements("x" + infix + "_mig_codigo_deuda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $deudas->mig_codigo_deuda->FldCaption(), $deudas->mig_codigo_deuda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mig_fecha_desembolso");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_fecha_desembolso->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mig_tasa");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_tasa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mig_plazo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_plazo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mig_dias_mora");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_dias_mora->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mig_monto_desembolso");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_monto_desembolso->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mig_intereses");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_intereses->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mig_cargos_gastos");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_cargos_gastos->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mig_total_deuda");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_total_deuda->FldErrMsg()) ?>");

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
	if (ew_ValueChanged(fobj, infix, "id_ciudad", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_agente", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_estadodeuda", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mig_codigo_deuda", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mig_fecha_desembolso", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mig_moneda", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mig_tasa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mig_plazo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mig_dias_mora", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mig_monto_desembolso", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mig_intereses", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mig_cargos_gastos", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mig_total_deuda", false)) return false;
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
fdeudasgrid.Lists["x_id_ciudad"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"ciudades"};
fdeudasgrid.Lists["x_id_ciudad"].Data = "<?php echo $deudas_grid->id_ciudad->LookupFilterQuery(FALSE, "grid") ?>";
fdeudasgrid.Lists["x_id_agente"] = {"LinkField":"x_id_user","Ajax":true,"AutoFill":false,"DisplayFields":["x_First_Name","x_Last_Name","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdeudasgrid.Lists["x_id_agente"].Data = "<?php echo $deudas_grid->id_agente->LookupFilterQuery(FALSE, "grid") ?>";
fdeudasgrid.Lists["x_id_estadodeuda"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"estado_deuda"};
fdeudasgrid.Lists["x_id_estadodeuda"].Data = "<?php echo $deudas_grid->id_estadodeuda->LookupFilterQuery(FALSE, "grid") ?>";
fdeudasgrid.Lists["x_mig_moneda"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdeudasgrid.Lists["x_mig_moneda"].Options = <?php echo json_encode($deudas_grid->mig_moneda->Options()) ?>;

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
<?php if ($deudas->id_ciudad->Visible) { // id_ciudad ?>
	<?php if ($deudas->SortUrl($deudas->id_ciudad) == "") { ?>
		<th data-name="id_ciudad" class="<?php echo $deudas->id_ciudad->HeaderCellClass() ?>"><div id="elh_deudas_id_ciudad" class="deudas_id_ciudad"><div class="ewTableHeaderCaption"><?php echo $deudas->id_ciudad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_ciudad" class="<?php echo $deudas->id_ciudad->HeaderCellClass() ?>"><div><div id="elh_deudas_id_ciudad" class="deudas_id_ciudad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->id_ciudad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->id_ciudad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->id_ciudad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->id_agente->Visible) { // id_agente ?>
	<?php if ($deudas->SortUrl($deudas->id_agente) == "") { ?>
		<th data-name="id_agente" class="<?php echo $deudas->id_agente->HeaderCellClass() ?>"><div id="elh_deudas_id_agente" class="deudas_id_agente"><div class="ewTableHeaderCaption"><?php echo $deudas->id_agente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_agente" class="<?php echo $deudas->id_agente->HeaderCellClass() ?>"><div><div id="elh_deudas_id_agente" class="deudas_id_agente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->id_agente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->id_agente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->id_agente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->id_estadodeuda->Visible) { // id_estadodeuda ?>
	<?php if ($deudas->SortUrl($deudas->id_estadodeuda) == "") { ?>
		<th data-name="id_estadodeuda" class="<?php echo $deudas->id_estadodeuda->HeaderCellClass() ?>"><div id="elh_deudas_id_estadodeuda" class="deudas_id_estadodeuda"><div class="ewTableHeaderCaption"><?php echo $deudas->id_estadodeuda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_estadodeuda" class="<?php echo $deudas->id_estadodeuda->HeaderCellClass() ?>"><div><div id="elh_deudas_id_estadodeuda" class="deudas_id_estadodeuda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->id_estadodeuda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->id_estadodeuda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->id_estadodeuda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_codigo_deuda->Visible) { // mig_codigo_deuda ?>
	<?php if ($deudas->SortUrl($deudas->mig_codigo_deuda) == "") { ?>
		<th data-name="mig_codigo_deuda" class="<?php echo $deudas->mig_codigo_deuda->HeaderCellClass() ?>"><div id="elh_deudas_mig_codigo_deuda" class="deudas_mig_codigo_deuda"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_codigo_deuda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_codigo_deuda" class="<?php echo $deudas->mig_codigo_deuda->HeaderCellClass() ?>"><div><div id="elh_deudas_mig_codigo_deuda" class="deudas_mig_codigo_deuda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_codigo_deuda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_codigo_deuda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_codigo_deuda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_fecha_desembolso->Visible) { // mig_fecha_desembolso ?>
	<?php if ($deudas->SortUrl($deudas->mig_fecha_desembolso) == "") { ?>
		<th data-name="mig_fecha_desembolso" class="<?php echo $deudas->mig_fecha_desembolso->HeaderCellClass() ?>"><div id="elh_deudas_mig_fecha_desembolso" class="deudas_mig_fecha_desembolso"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_fecha_desembolso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_fecha_desembolso" class="<?php echo $deudas->mig_fecha_desembolso->HeaderCellClass() ?>"><div><div id="elh_deudas_mig_fecha_desembolso" class="deudas_mig_fecha_desembolso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_fecha_desembolso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_fecha_desembolso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_fecha_desembolso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_moneda->Visible) { // mig_moneda ?>
	<?php if ($deudas->SortUrl($deudas->mig_moneda) == "") { ?>
		<th data-name="mig_moneda" class="<?php echo $deudas->mig_moneda->HeaderCellClass() ?>"><div id="elh_deudas_mig_moneda" class="deudas_mig_moneda"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_moneda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_moneda" class="<?php echo $deudas->mig_moneda->HeaderCellClass() ?>"><div><div id="elh_deudas_mig_moneda" class="deudas_mig_moneda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_moneda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_moneda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_moneda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_tasa->Visible) { // mig_tasa ?>
	<?php if ($deudas->SortUrl($deudas->mig_tasa) == "") { ?>
		<th data-name="mig_tasa" class="<?php echo $deudas->mig_tasa->HeaderCellClass() ?>"><div id="elh_deudas_mig_tasa" class="deudas_mig_tasa"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_tasa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_tasa" class="<?php echo $deudas->mig_tasa->HeaderCellClass() ?>"><div><div id="elh_deudas_mig_tasa" class="deudas_mig_tasa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_tasa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_tasa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_tasa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_plazo->Visible) { // mig_plazo ?>
	<?php if ($deudas->SortUrl($deudas->mig_plazo) == "") { ?>
		<th data-name="mig_plazo" class="<?php echo $deudas->mig_plazo->HeaderCellClass() ?>"><div id="elh_deudas_mig_plazo" class="deudas_mig_plazo"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_plazo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_plazo" class="<?php echo $deudas->mig_plazo->HeaderCellClass() ?>"><div><div id="elh_deudas_mig_plazo" class="deudas_mig_plazo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_plazo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_plazo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_plazo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_dias_mora->Visible) { // mig_dias_mora ?>
	<?php if ($deudas->SortUrl($deudas->mig_dias_mora) == "") { ?>
		<th data-name="mig_dias_mora" class="<?php echo $deudas->mig_dias_mora->HeaderCellClass() ?>"><div id="elh_deudas_mig_dias_mora" class="deudas_mig_dias_mora"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_dias_mora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_dias_mora" class="<?php echo $deudas->mig_dias_mora->HeaderCellClass() ?>"><div><div id="elh_deudas_mig_dias_mora" class="deudas_mig_dias_mora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_dias_mora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_dias_mora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_dias_mora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_monto_desembolso->Visible) { // mig_monto_desembolso ?>
	<?php if ($deudas->SortUrl($deudas->mig_monto_desembolso) == "") { ?>
		<th data-name="mig_monto_desembolso" class="<?php echo $deudas->mig_monto_desembolso->HeaderCellClass() ?>"><div id="elh_deudas_mig_monto_desembolso" class="deudas_mig_monto_desembolso"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_monto_desembolso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_monto_desembolso" class="<?php echo $deudas->mig_monto_desembolso->HeaderCellClass() ?>"><div><div id="elh_deudas_mig_monto_desembolso" class="deudas_mig_monto_desembolso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_monto_desembolso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_monto_desembolso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_monto_desembolso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_intereses->Visible) { // mig_intereses ?>
	<?php if ($deudas->SortUrl($deudas->mig_intereses) == "") { ?>
		<th data-name="mig_intereses" class="<?php echo $deudas->mig_intereses->HeaderCellClass() ?>"><div id="elh_deudas_mig_intereses" class="deudas_mig_intereses"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_intereses->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_intereses" class="<?php echo $deudas->mig_intereses->HeaderCellClass() ?>"><div><div id="elh_deudas_mig_intereses" class="deudas_mig_intereses">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_intereses->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_intereses->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_intereses->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_cargos_gastos->Visible) { // mig_cargos_gastos ?>
	<?php if ($deudas->SortUrl($deudas->mig_cargos_gastos) == "") { ?>
		<th data-name="mig_cargos_gastos" class="<?php echo $deudas->mig_cargos_gastos->HeaderCellClass() ?>"><div id="elh_deudas_mig_cargos_gastos" class="deudas_mig_cargos_gastos"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_cargos_gastos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_cargos_gastos" class="<?php echo $deudas->mig_cargos_gastos->HeaderCellClass() ?>"><div><div id="elh_deudas_mig_cargos_gastos" class="deudas_mig_cargos_gastos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_cargos_gastos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_cargos_gastos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_cargos_gastos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_total_deuda->Visible) { // mig_total_deuda ?>
	<?php if ($deudas->SortUrl($deudas->mig_total_deuda) == "") { ?>
		<th data-name="mig_total_deuda" class="<?php echo $deudas->mig_total_deuda->HeaderCellClass() ?>"><div id="elh_deudas_mig_total_deuda" class="deudas_mig_total_deuda"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_total_deuda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_total_deuda" class="<?php echo $deudas->mig_total_deuda->HeaderCellClass() ?>"><div><div id="elh_deudas_mig_total_deuda" class="deudas_mig_total_deuda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_total_deuda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_total_deuda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_total_deuda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
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
	<?php if ($deudas->id_ciudad->Visible) { // id_ciudad ?>
		<td data-name="id_ciudad"<?php echo $deudas->id_ciudad->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_ciudad" class="form-group deudas_id_ciudad">
<select data-table="deudas" data-field="x_id_ciudad" data-value-separator="<?php echo $deudas->id_ciudad->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_id_ciudad" name="x<?php echo $deudas_grid->RowIndex ?>_id_ciudad"<?php echo $deudas->id_ciudad->EditAttributes() ?>>
<?php echo $deudas->id_ciudad->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_id_ciudad") ?>
</select>
</span>
<input type="hidden" data-table="deudas" data-field="x_id_ciudad" name="o<?php echo $deudas_grid->RowIndex ?>_id_ciudad" id="o<?php echo $deudas_grid->RowIndex ?>_id_ciudad" value="<?php echo ew_HtmlEncode($deudas->id_ciudad->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_ciudad" class="form-group deudas_id_ciudad">
<select data-table="deudas" data-field="x_id_ciudad" data-value-separator="<?php echo $deudas->id_ciudad->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_id_ciudad" name="x<?php echo $deudas_grid->RowIndex ?>_id_ciudad"<?php echo $deudas->id_ciudad->EditAttributes() ?>>
<?php echo $deudas->id_ciudad->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_id_ciudad") ?>
</select>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_ciudad" class="deudas_id_ciudad">
<span<?php echo $deudas->id_ciudad->ViewAttributes() ?>>
<?php echo $deudas->id_ciudad->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_id_ciudad" name="x<?php echo $deudas_grid->RowIndex ?>_id_ciudad" id="x<?php echo $deudas_grid->RowIndex ?>_id_ciudad" value="<?php echo ew_HtmlEncode($deudas->id_ciudad->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_id_ciudad" name="o<?php echo $deudas_grid->RowIndex ?>_id_ciudad" id="o<?php echo $deudas_grid->RowIndex ?>_id_ciudad" value="<?php echo ew_HtmlEncode($deudas->id_ciudad->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_id_ciudad" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_id_ciudad" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_id_ciudad" value="<?php echo ew_HtmlEncode($deudas->id_ciudad->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_id_ciudad" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_id_ciudad" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_id_ciudad" value="<?php echo ew_HtmlEncode($deudas->id_ciudad->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->id_agente->Visible) { // id_agente ?>
		<td data-name="id_agente"<?php echo $deudas->id_agente->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($deudas->id_agente->getSessionValue() <> "") { ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_agente" class="form-group deudas_id_agente">
<span<?php echo $deudas->id_agente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id_agente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $deudas_grid->RowIndex ?>_id_agente" name="x<?php echo $deudas_grid->RowIndex ?>_id_agente" value="<?php echo ew_HtmlEncode($deudas->id_agente->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_agente" class="form-group deudas_id_agente">
<select data-table="deudas" data-field="x_id_agente" data-value-separator="<?php echo $deudas->id_agente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_id_agente" name="x<?php echo $deudas_grid->RowIndex ?>_id_agente"<?php echo $deudas->id_agente->EditAttributes() ?>>
<?php echo $deudas->id_agente->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_id_agente") ?>
</select>
</span>
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_id_agente" name="o<?php echo $deudas_grid->RowIndex ?>_id_agente" id="o<?php echo $deudas_grid->RowIndex ?>_id_agente" value="<?php echo ew_HtmlEncode($deudas->id_agente->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($deudas->id_agente->getSessionValue() <> "") { ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_agente" class="form-group deudas_id_agente">
<span<?php echo $deudas->id_agente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id_agente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $deudas_grid->RowIndex ?>_id_agente" name="x<?php echo $deudas_grid->RowIndex ?>_id_agente" value="<?php echo ew_HtmlEncode($deudas->id_agente->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_agente" class="form-group deudas_id_agente">
<select data-table="deudas" data-field="x_id_agente" data-value-separator="<?php echo $deudas->id_agente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_id_agente" name="x<?php echo $deudas_grid->RowIndex ?>_id_agente"<?php echo $deudas->id_agente->EditAttributes() ?>>
<?php echo $deudas->id_agente->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_id_agente") ?>
</select>
</span>
<?php } ?>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_agente" class="deudas_id_agente">
<span<?php echo $deudas->id_agente->ViewAttributes() ?>>
<?php echo $deudas->id_agente->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_id_agente" name="x<?php echo $deudas_grid->RowIndex ?>_id_agente" id="x<?php echo $deudas_grid->RowIndex ?>_id_agente" value="<?php echo ew_HtmlEncode($deudas->id_agente->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_id_agente" name="o<?php echo $deudas_grid->RowIndex ?>_id_agente" id="o<?php echo $deudas_grid->RowIndex ?>_id_agente" value="<?php echo ew_HtmlEncode($deudas->id_agente->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_id_agente" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_id_agente" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_id_agente" value="<?php echo ew_HtmlEncode($deudas->id_agente->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_id_agente" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_id_agente" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_id_agente" value="<?php echo ew_HtmlEncode($deudas->id_agente->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->id_estadodeuda->Visible) { // id_estadodeuda ?>
		<td data-name="id_estadodeuda"<?php echo $deudas->id_estadodeuda->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_estadodeuda" class="form-group deudas_id_estadodeuda">
<select data-table="deudas" data-field="x_id_estadodeuda" data-value-separator="<?php echo $deudas->id_estadodeuda->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" name="x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda"<?php echo $deudas->id_estadodeuda->EditAttributes() ?>>
<?php echo $deudas->id_estadodeuda->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda") ?>
</select>
</span>
<input type="hidden" data-table="deudas" data-field="x_id_estadodeuda" name="o<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" id="o<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" value="<?php echo ew_HtmlEncode($deudas->id_estadodeuda->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_estadodeuda" class="form-group deudas_id_estadodeuda">
<select data-table="deudas" data-field="x_id_estadodeuda" data-value-separator="<?php echo $deudas->id_estadodeuda->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" name="x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda"<?php echo $deudas->id_estadodeuda->EditAttributes() ?>>
<?php echo $deudas->id_estadodeuda->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda") ?>
</select>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_id_estadodeuda" class="deudas_id_estadodeuda">
<span<?php echo $deudas->id_estadodeuda->ViewAttributes() ?>>
<?php echo $deudas->id_estadodeuda->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_id_estadodeuda" name="x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" id="x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" value="<?php echo ew_HtmlEncode($deudas->id_estadodeuda->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_id_estadodeuda" name="o<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" id="o<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" value="<?php echo ew_HtmlEncode($deudas->id_estadodeuda->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_id_estadodeuda" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" value="<?php echo ew_HtmlEncode($deudas->id_estadodeuda->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_id_estadodeuda" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" value="<?php echo ew_HtmlEncode($deudas->id_estadodeuda->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->mig_codigo_deuda->Visible) { // mig_codigo_deuda ?>
		<td data-name="mig_codigo_deuda"<?php echo $deudas->mig_codigo_deuda->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_codigo_deuda" class="form-group deudas_mig_codigo_deuda">
<input type="text" data-table="deudas" data-field="x_mig_codigo_deuda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" id="x<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->mig_codigo_deuda->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_codigo_deuda->EditValue ?>"<?php echo $deudas->mig_codigo_deuda->EditAttributes() ?>>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_codigo_deuda" name="o<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" id="o<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_codigo_deuda->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_codigo_deuda" class="form-group deudas_mig_codigo_deuda">
<input type="text" data-table="deudas" data-field="x_mig_codigo_deuda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" id="x<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->mig_codigo_deuda->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_codigo_deuda->EditValue ?>"<?php echo $deudas->mig_codigo_deuda->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_codigo_deuda" class="deudas_mig_codigo_deuda">
<span<?php echo $deudas->mig_codigo_deuda->ViewAttributes() ?>>
<?php echo $deudas->mig_codigo_deuda->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_codigo_deuda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" id="x<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_codigo_deuda->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_codigo_deuda" name="o<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" id="o<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_codigo_deuda->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_codigo_deuda" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_codigo_deuda->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_codigo_deuda" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_codigo_deuda->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->mig_fecha_desembolso->Visible) { // mig_fecha_desembolso ?>
		<td data-name="mig_fecha_desembolso"<?php echo $deudas->mig_fecha_desembolso->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_fecha_desembolso" class="form-group deudas_mig_fecha_desembolso">
<input type="text" data-table="deudas" data-field="x_mig_fecha_desembolso" data-format="7" name="x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" id="x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" size="16" placeholder="<?php echo ew_HtmlEncode($deudas->mig_fecha_desembolso->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_fecha_desembolso->EditValue ?>"<?php echo $deudas->mig_fecha_desembolso->EditAttributes() ?>>
<?php if (!$deudas->mig_fecha_desembolso->ReadOnly && !$deudas->mig_fecha_desembolso->Disabled && !isset($deudas->mig_fecha_desembolso->EditAttrs["readonly"]) && !isset($deudas->mig_fecha_desembolso->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdeudasgrid", "x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_fecha_desembolso" name="o<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" id="o<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_fecha_desembolso->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_fecha_desembolso" class="form-group deudas_mig_fecha_desembolso">
<input type="text" data-table="deudas" data-field="x_mig_fecha_desembolso" data-format="7" name="x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" id="x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" size="16" placeholder="<?php echo ew_HtmlEncode($deudas->mig_fecha_desembolso->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_fecha_desembolso->EditValue ?>"<?php echo $deudas->mig_fecha_desembolso->EditAttributes() ?>>
<?php if (!$deudas->mig_fecha_desembolso->ReadOnly && !$deudas->mig_fecha_desembolso->Disabled && !isset($deudas->mig_fecha_desembolso->EditAttrs["readonly"]) && !isset($deudas->mig_fecha_desembolso->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdeudasgrid", "x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_fecha_desembolso" class="deudas_mig_fecha_desembolso">
<span<?php echo $deudas->mig_fecha_desembolso->ViewAttributes() ?>>
<?php echo $deudas->mig_fecha_desembolso->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_fecha_desembolso" name="x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" id="x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_fecha_desembolso->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_fecha_desembolso" name="o<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" id="o<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_fecha_desembolso->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_fecha_desembolso" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_fecha_desembolso->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_fecha_desembolso" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_fecha_desembolso->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->mig_moneda->Visible) { // mig_moneda ?>
		<td data-name="mig_moneda"<?php echo $deudas->mig_moneda->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_moneda" class="form-group deudas_mig_moneda">
<select data-table="deudas" data-field="x_mig_moneda" data-value-separator="<?php echo $deudas->mig_moneda->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_mig_moneda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_moneda"<?php echo $deudas->mig_moneda->EditAttributes() ?>>
<?php echo $deudas->mig_moneda->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_mig_moneda") ?>
</select>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_moneda" name="o<?php echo $deudas_grid->RowIndex ?>_mig_moneda" id="o<?php echo $deudas_grid->RowIndex ?>_mig_moneda" value="<?php echo ew_HtmlEncode($deudas->mig_moneda->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_moneda" class="form-group deudas_mig_moneda">
<select data-table="deudas" data-field="x_mig_moneda" data-value-separator="<?php echo $deudas->mig_moneda->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_mig_moneda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_moneda"<?php echo $deudas->mig_moneda->EditAttributes() ?>>
<?php echo $deudas->mig_moneda->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_mig_moneda") ?>
</select>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_moneda" class="deudas_mig_moneda">
<span<?php echo $deudas->mig_moneda->ViewAttributes() ?>>
<?php echo $deudas->mig_moneda->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_moneda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_moneda" id="x<?php echo $deudas_grid->RowIndex ?>_mig_moneda" value="<?php echo ew_HtmlEncode($deudas->mig_moneda->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_moneda" name="o<?php echo $deudas_grid->RowIndex ?>_mig_moneda" id="o<?php echo $deudas_grid->RowIndex ?>_mig_moneda" value="<?php echo ew_HtmlEncode($deudas->mig_moneda->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_moneda" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_moneda" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_moneda" value="<?php echo ew_HtmlEncode($deudas->mig_moneda->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_moneda" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_moneda" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_moneda" value="<?php echo ew_HtmlEncode($deudas->mig_moneda->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->mig_tasa->Visible) { // mig_tasa ?>
		<td data-name="mig_tasa"<?php echo $deudas->mig_tasa->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_tasa" class="form-group deudas_mig_tasa">
<input type="text" data-table="deudas" data-field="x_mig_tasa" name="x<?php echo $deudas_grid->RowIndex ?>_mig_tasa" id="x<?php echo $deudas_grid->RowIndex ?>_mig_tasa" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_tasa->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_tasa->EditValue ?>"<?php echo $deudas->mig_tasa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_tasa" name="o<?php echo $deudas_grid->RowIndex ?>_mig_tasa" id="o<?php echo $deudas_grid->RowIndex ?>_mig_tasa" value="<?php echo ew_HtmlEncode($deudas->mig_tasa->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_tasa" class="form-group deudas_mig_tasa">
<input type="text" data-table="deudas" data-field="x_mig_tasa" name="x<?php echo $deudas_grid->RowIndex ?>_mig_tasa" id="x<?php echo $deudas_grid->RowIndex ?>_mig_tasa" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_tasa->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_tasa->EditValue ?>"<?php echo $deudas->mig_tasa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_tasa" class="deudas_mig_tasa">
<span<?php echo $deudas->mig_tasa->ViewAttributes() ?>>
<?php echo $deudas->mig_tasa->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_tasa" name="x<?php echo $deudas_grid->RowIndex ?>_mig_tasa" id="x<?php echo $deudas_grid->RowIndex ?>_mig_tasa" value="<?php echo ew_HtmlEncode($deudas->mig_tasa->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_tasa" name="o<?php echo $deudas_grid->RowIndex ?>_mig_tasa" id="o<?php echo $deudas_grid->RowIndex ?>_mig_tasa" value="<?php echo ew_HtmlEncode($deudas->mig_tasa->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_tasa" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_tasa" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_tasa" value="<?php echo ew_HtmlEncode($deudas->mig_tasa->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_tasa" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_tasa" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_tasa" value="<?php echo ew_HtmlEncode($deudas->mig_tasa->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->mig_plazo->Visible) { // mig_plazo ?>
		<td data-name="mig_plazo"<?php echo $deudas->mig_plazo->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_plazo" class="form-group deudas_mig_plazo">
<input type="text" data-table="deudas" data-field="x_mig_plazo" name="x<?php echo $deudas_grid->RowIndex ?>_mig_plazo" id="x<?php echo $deudas_grid->RowIndex ?>_mig_plazo" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_plazo->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_plazo->EditValue ?>"<?php echo $deudas->mig_plazo->EditAttributes() ?>>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_plazo" name="o<?php echo $deudas_grid->RowIndex ?>_mig_plazo" id="o<?php echo $deudas_grid->RowIndex ?>_mig_plazo" value="<?php echo ew_HtmlEncode($deudas->mig_plazo->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_plazo" class="form-group deudas_mig_plazo">
<input type="text" data-table="deudas" data-field="x_mig_plazo" name="x<?php echo $deudas_grid->RowIndex ?>_mig_plazo" id="x<?php echo $deudas_grid->RowIndex ?>_mig_plazo" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_plazo->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_plazo->EditValue ?>"<?php echo $deudas->mig_plazo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_plazo" class="deudas_mig_plazo">
<span<?php echo $deudas->mig_plazo->ViewAttributes() ?>>
<?php echo $deudas->mig_plazo->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_plazo" name="x<?php echo $deudas_grid->RowIndex ?>_mig_plazo" id="x<?php echo $deudas_grid->RowIndex ?>_mig_plazo" value="<?php echo ew_HtmlEncode($deudas->mig_plazo->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_plazo" name="o<?php echo $deudas_grid->RowIndex ?>_mig_plazo" id="o<?php echo $deudas_grid->RowIndex ?>_mig_plazo" value="<?php echo ew_HtmlEncode($deudas->mig_plazo->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_plazo" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_plazo" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_plazo" value="<?php echo ew_HtmlEncode($deudas->mig_plazo->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_plazo" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_plazo" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_plazo" value="<?php echo ew_HtmlEncode($deudas->mig_plazo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->mig_dias_mora->Visible) { // mig_dias_mora ?>
		<td data-name="mig_dias_mora"<?php echo $deudas->mig_dias_mora->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_dias_mora" class="form-group deudas_mig_dias_mora">
<input type="text" data-table="deudas" data-field="x_mig_dias_mora" name="x<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" id="x<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_dias_mora->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_dias_mora->EditValue ?>"<?php echo $deudas->mig_dias_mora->EditAttributes() ?>>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_dias_mora" name="o<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" id="o<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" value="<?php echo ew_HtmlEncode($deudas->mig_dias_mora->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_dias_mora" class="form-group deudas_mig_dias_mora">
<input type="text" data-table="deudas" data-field="x_mig_dias_mora" name="x<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" id="x<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_dias_mora->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_dias_mora->EditValue ?>"<?php echo $deudas->mig_dias_mora->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_dias_mora" class="deudas_mig_dias_mora">
<span<?php echo $deudas->mig_dias_mora->ViewAttributes() ?>>
<?php echo $deudas->mig_dias_mora->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_dias_mora" name="x<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" id="x<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" value="<?php echo ew_HtmlEncode($deudas->mig_dias_mora->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_dias_mora" name="o<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" id="o<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" value="<?php echo ew_HtmlEncode($deudas->mig_dias_mora->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_dias_mora" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" value="<?php echo ew_HtmlEncode($deudas->mig_dias_mora->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_dias_mora" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" value="<?php echo ew_HtmlEncode($deudas->mig_dias_mora->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->mig_monto_desembolso->Visible) { // mig_monto_desembolso ?>
		<td data-name="mig_monto_desembolso"<?php echo $deudas->mig_monto_desembolso->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_monto_desembolso" class="form-group deudas_mig_monto_desembolso">
<input type="text" data-table="deudas" data-field="x_mig_monto_desembolso" name="x<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" id="x<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_monto_desembolso->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_monto_desembolso->EditValue ?>"<?php echo $deudas->mig_monto_desembolso->EditAttributes() ?>>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_monto_desembolso" name="o<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" id="o<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_monto_desembolso->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_monto_desembolso" class="form-group deudas_mig_monto_desembolso">
<input type="text" data-table="deudas" data-field="x_mig_monto_desembolso" name="x<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" id="x<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_monto_desembolso->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_monto_desembolso->EditValue ?>"<?php echo $deudas->mig_monto_desembolso->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_monto_desembolso" class="deudas_mig_monto_desembolso">
<span<?php echo $deudas->mig_monto_desembolso->ViewAttributes() ?>>
<?php echo $deudas->mig_monto_desembolso->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_monto_desembolso" name="x<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" id="x<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_monto_desembolso->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_monto_desembolso" name="o<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" id="o<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_monto_desembolso->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_monto_desembolso" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_monto_desembolso->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_monto_desembolso" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_monto_desembolso->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->mig_intereses->Visible) { // mig_intereses ?>
		<td data-name="mig_intereses"<?php echo $deudas->mig_intereses->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_intereses" class="form-group deudas_mig_intereses">
<input type="text" data-table="deudas" data-field="x_mig_intereses" name="x<?php echo $deudas_grid->RowIndex ?>_mig_intereses" id="x<?php echo $deudas_grid->RowIndex ?>_mig_intereses" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_intereses->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_intereses->EditValue ?>"<?php echo $deudas->mig_intereses->EditAttributes() ?>>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_intereses" name="o<?php echo $deudas_grid->RowIndex ?>_mig_intereses" id="o<?php echo $deudas_grid->RowIndex ?>_mig_intereses" value="<?php echo ew_HtmlEncode($deudas->mig_intereses->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_intereses" class="form-group deudas_mig_intereses">
<input type="text" data-table="deudas" data-field="x_mig_intereses" name="x<?php echo $deudas_grid->RowIndex ?>_mig_intereses" id="x<?php echo $deudas_grid->RowIndex ?>_mig_intereses" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_intereses->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_intereses->EditValue ?>"<?php echo $deudas->mig_intereses->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_intereses" class="deudas_mig_intereses">
<span<?php echo $deudas->mig_intereses->ViewAttributes() ?>>
<?php echo $deudas->mig_intereses->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_intereses" name="x<?php echo $deudas_grid->RowIndex ?>_mig_intereses" id="x<?php echo $deudas_grid->RowIndex ?>_mig_intereses" value="<?php echo ew_HtmlEncode($deudas->mig_intereses->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_intereses" name="o<?php echo $deudas_grid->RowIndex ?>_mig_intereses" id="o<?php echo $deudas_grid->RowIndex ?>_mig_intereses" value="<?php echo ew_HtmlEncode($deudas->mig_intereses->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_intereses" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_intereses" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_intereses" value="<?php echo ew_HtmlEncode($deudas->mig_intereses->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_intereses" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_intereses" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_intereses" value="<?php echo ew_HtmlEncode($deudas->mig_intereses->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->mig_cargos_gastos->Visible) { // mig_cargos_gastos ?>
		<td data-name="mig_cargos_gastos"<?php echo $deudas->mig_cargos_gastos->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_cargos_gastos" class="form-group deudas_mig_cargos_gastos">
<input type="text" data-table="deudas" data-field="x_mig_cargos_gastos" name="x<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" id="x<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_cargos_gastos->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_cargos_gastos->EditValue ?>"<?php echo $deudas->mig_cargos_gastos->EditAttributes() ?>>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_cargos_gastos" name="o<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" id="o<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" value="<?php echo ew_HtmlEncode($deudas->mig_cargos_gastos->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_cargos_gastos" class="form-group deudas_mig_cargos_gastos">
<input type="text" data-table="deudas" data-field="x_mig_cargos_gastos" name="x<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" id="x<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_cargos_gastos->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_cargos_gastos->EditValue ?>"<?php echo $deudas->mig_cargos_gastos->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_cargos_gastos" class="deudas_mig_cargos_gastos">
<span<?php echo $deudas->mig_cargos_gastos->ViewAttributes() ?>>
<?php echo $deudas->mig_cargos_gastos->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_cargos_gastos" name="x<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" id="x<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" value="<?php echo ew_HtmlEncode($deudas->mig_cargos_gastos->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_cargos_gastos" name="o<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" id="o<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" value="<?php echo ew_HtmlEncode($deudas->mig_cargos_gastos->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_cargos_gastos" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" value="<?php echo ew_HtmlEncode($deudas->mig_cargos_gastos->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_cargos_gastos" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" value="<?php echo ew_HtmlEncode($deudas->mig_cargos_gastos->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($deudas->mig_total_deuda->Visible) { // mig_total_deuda ?>
		<td data-name="mig_total_deuda"<?php echo $deudas->mig_total_deuda->CellAttributes() ?>>
<?php if ($deudas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_total_deuda" class="form-group deudas_mig_total_deuda">
<input type="text" data-table="deudas" data-field="x_mig_total_deuda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" id="x<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_total_deuda->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_total_deuda->EditValue ?>"<?php echo $deudas->mig_total_deuda->EditAttributes() ?>>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_total_deuda" name="o<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" id="o<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_total_deuda->OldValue) ?>">
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_total_deuda" class="form-group deudas_mig_total_deuda">
<input type="text" data-table="deudas" data-field="x_mig_total_deuda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" id="x<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_total_deuda->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_total_deuda->EditValue ?>"<?php echo $deudas->mig_total_deuda->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($deudas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $deudas_grid->RowCnt ?>_deudas_mig_total_deuda" class="deudas_mig_total_deuda">
<span<?php echo $deudas->mig_total_deuda->ViewAttributes() ?>>
<?php echo $deudas->mig_total_deuda->ListViewValue() ?></span>
</span>
<?php if ($deudas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_total_deuda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" id="x<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_total_deuda->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_total_deuda" name="o<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" id="o<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_total_deuda->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="deudas" data-field="x_mig_total_deuda" name="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" id="fdeudasgrid$x<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_total_deuda->FormValue) ?>">
<input type="hidden" data-table="deudas" data-field="x_mig_total_deuda" name="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" id="fdeudasgrid$o<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_total_deuda->OldValue) ?>">
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
	<?php if ($deudas->id_ciudad->Visible) { // id_ciudad ?>
		<td data-name="id_ciudad">
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_id_ciudad" class="form-group deudas_id_ciudad">
<select data-table="deudas" data-field="x_id_ciudad" data-value-separator="<?php echo $deudas->id_ciudad->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_id_ciudad" name="x<?php echo $deudas_grid->RowIndex ?>_id_ciudad"<?php echo $deudas->id_ciudad->EditAttributes() ?>>
<?php echo $deudas->id_ciudad->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_id_ciudad") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_id_ciudad" class="form-group deudas_id_ciudad">
<span<?php echo $deudas->id_ciudad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id_ciudad->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_id_ciudad" name="x<?php echo $deudas_grid->RowIndex ?>_id_ciudad" id="x<?php echo $deudas_grid->RowIndex ?>_id_ciudad" value="<?php echo ew_HtmlEncode($deudas->id_ciudad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_id_ciudad" name="o<?php echo $deudas_grid->RowIndex ?>_id_ciudad" id="o<?php echo $deudas_grid->RowIndex ?>_id_ciudad" value="<?php echo ew_HtmlEncode($deudas->id_ciudad->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->id_agente->Visible) { // id_agente ?>
		<td data-name="id_agente">
<?php if ($deudas->CurrentAction <> "F") { ?>
<?php if ($deudas->id_agente->getSessionValue() <> "") { ?>
<span id="el$rowindex$_deudas_id_agente" class="form-group deudas_id_agente">
<span<?php echo $deudas->id_agente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id_agente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $deudas_grid->RowIndex ?>_id_agente" name="x<?php echo $deudas_grid->RowIndex ?>_id_agente" value="<?php echo ew_HtmlEncode($deudas->id_agente->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_deudas_id_agente" class="form-group deudas_id_agente">
<select data-table="deudas" data-field="x_id_agente" data-value-separator="<?php echo $deudas->id_agente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_id_agente" name="x<?php echo $deudas_grid->RowIndex ?>_id_agente"<?php echo $deudas->id_agente->EditAttributes() ?>>
<?php echo $deudas->id_agente->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_id_agente") ?>
</select>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_deudas_id_agente" class="form-group deudas_id_agente">
<span<?php echo $deudas->id_agente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id_agente->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_id_agente" name="x<?php echo $deudas_grid->RowIndex ?>_id_agente" id="x<?php echo $deudas_grid->RowIndex ?>_id_agente" value="<?php echo ew_HtmlEncode($deudas->id_agente->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_id_agente" name="o<?php echo $deudas_grid->RowIndex ?>_id_agente" id="o<?php echo $deudas_grid->RowIndex ?>_id_agente" value="<?php echo ew_HtmlEncode($deudas->id_agente->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->id_estadodeuda->Visible) { // id_estadodeuda ?>
		<td data-name="id_estadodeuda">
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_id_estadodeuda" class="form-group deudas_id_estadodeuda">
<select data-table="deudas" data-field="x_id_estadodeuda" data-value-separator="<?php echo $deudas->id_estadodeuda->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" name="x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda"<?php echo $deudas->id_estadodeuda->EditAttributes() ?>>
<?php echo $deudas->id_estadodeuda->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_id_estadodeuda" class="form-group deudas_id_estadodeuda">
<span<?php echo $deudas->id_estadodeuda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id_estadodeuda->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_id_estadodeuda" name="x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" id="x<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" value="<?php echo ew_HtmlEncode($deudas->id_estadodeuda->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_id_estadodeuda" name="o<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" id="o<?php echo $deudas_grid->RowIndex ?>_id_estadodeuda" value="<?php echo ew_HtmlEncode($deudas->id_estadodeuda->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->mig_codigo_deuda->Visible) { // mig_codigo_deuda ?>
		<td data-name="mig_codigo_deuda">
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_mig_codigo_deuda" class="form-group deudas_mig_codigo_deuda">
<input type="text" data-table="deudas" data-field="x_mig_codigo_deuda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" id="x<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->mig_codigo_deuda->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_codigo_deuda->EditValue ?>"<?php echo $deudas->mig_codigo_deuda->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_mig_codigo_deuda" class="form-group deudas_mig_codigo_deuda">
<span<?php echo $deudas->mig_codigo_deuda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->mig_codigo_deuda->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_codigo_deuda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" id="x<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_codigo_deuda->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_mig_codigo_deuda" name="o<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" id="o<?php echo $deudas_grid->RowIndex ?>_mig_codigo_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_codigo_deuda->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->mig_fecha_desembolso->Visible) { // mig_fecha_desembolso ?>
		<td data-name="mig_fecha_desembolso">
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_mig_fecha_desembolso" class="form-group deudas_mig_fecha_desembolso">
<input type="text" data-table="deudas" data-field="x_mig_fecha_desembolso" data-format="7" name="x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" id="x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" size="16" placeholder="<?php echo ew_HtmlEncode($deudas->mig_fecha_desembolso->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_fecha_desembolso->EditValue ?>"<?php echo $deudas->mig_fecha_desembolso->EditAttributes() ?>>
<?php if (!$deudas->mig_fecha_desembolso->ReadOnly && !$deudas->mig_fecha_desembolso->Disabled && !isset($deudas->mig_fecha_desembolso->EditAttrs["readonly"]) && !isset($deudas->mig_fecha_desembolso->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdeudasgrid", "x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_mig_fecha_desembolso" class="form-group deudas_mig_fecha_desembolso">
<span<?php echo $deudas->mig_fecha_desembolso->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->mig_fecha_desembolso->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_fecha_desembolso" name="x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" id="x<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_fecha_desembolso->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_mig_fecha_desembolso" name="o<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" id="o<?php echo $deudas_grid->RowIndex ?>_mig_fecha_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_fecha_desembolso->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->mig_moneda->Visible) { // mig_moneda ?>
		<td data-name="mig_moneda">
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_mig_moneda" class="form-group deudas_mig_moneda">
<select data-table="deudas" data-field="x_mig_moneda" data-value-separator="<?php echo $deudas->mig_moneda->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $deudas_grid->RowIndex ?>_mig_moneda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_moneda"<?php echo $deudas->mig_moneda->EditAttributes() ?>>
<?php echo $deudas->mig_moneda->SelectOptionListHtml("x<?php echo $deudas_grid->RowIndex ?>_mig_moneda") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_mig_moneda" class="form-group deudas_mig_moneda">
<span<?php echo $deudas->mig_moneda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->mig_moneda->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_moneda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_moneda" id="x<?php echo $deudas_grid->RowIndex ?>_mig_moneda" value="<?php echo ew_HtmlEncode($deudas->mig_moneda->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_mig_moneda" name="o<?php echo $deudas_grid->RowIndex ?>_mig_moneda" id="o<?php echo $deudas_grid->RowIndex ?>_mig_moneda" value="<?php echo ew_HtmlEncode($deudas->mig_moneda->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->mig_tasa->Visible) { // mig_tasa ?>
		<td data-name="mig_tasa">
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_mig_tasa" class="form-group deudas_mig_tasa">
<input type="text" data-table="deudas" data-field="x_mig_tasa" name="x<?php echo $deudas_grid->RowIndex ?>_mig_tasa" id="x<?php echo $deudas_grid->RowIndex ?>_mig_tasa" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_tasa->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_tasa->EditValue ?>"<?php echo $deudas->mig_tasa->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_mig_tasa" class="form-group deudas_mig_tasa">
<span<?php echo $deudas->mig_tasa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->mig_tasa->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_tasa" name="x<?php echo $deudas_grid->RowIndex ?>_mig_tasa" id="x<?php echo $deudas_grid->RowIndex ?>_mig_tasa" value="<?php echo ew_HtmlEncode($deudas->mig_tasa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_mig_tasa" name="o<?php echo $deudas_grid->RowIndex ?>_mig_tasa" id="o<?php echo $deudas_grid->RowIndex ?>_mig_tasa" value="<?php echo ew_HtmlEncode($deudas->mig_tasa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->mig_plazo->Visible) { // mig_plazo ?>
		<td data-name="mig_plazo">
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_mig_plazo" class="form-group deudas_mig_plazo">
<input type="text" data-table="deudas" data-field="x_mig_plazo" name="x<?php echo $deudas_grid->RowIndex ?>_mig_plazo" id="x<?php echo $deudas_grid->RowIndex ?>_mig_plazo" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_plazo->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_plazo->EditValue ?>"<?php echo $deudas->mig_plazo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_mig_plazo" class="form-group deudas_mig_plazo">
<span<?php echo $deudas->mig_plazo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->mig_plazo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_plazo" name="x<?php echo $deudas_grid->RowIndex ?>_mig_plazo" id="x<?php echo $deudas_grid->RowIndex ?>_mig_plazo" value="<?php echo ew_HtmlEncode($deudas->mig_plazo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_mig_plazo" name="o<?php echo $deudas_grid->RowIndex ?>_mig_plazo" id="o<?php echo $deudas_grid->RowIndex ?>_mig_plazo" value="<?php echo ew_HtmlEncode($deudas->mig_plazo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->mig_dias_mora->Visible) { // mig_dias_mora ?>
		<td data-name="mig_dias_mora">
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_mig_dias_mora" class="form-group deudas_mig_dias_mora">
<input type="text" data-table="deudas" data-field="x_mig_dias_mora" name="x<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" id="x<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_dias_mora->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_dias_mora->EditValue ?>"<?php echo $deudas->mig_dias_mora->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_mig_dias_mora" class="form-group deudas_mig_dias_mora">
<span<?php echo $deudas->mig_dias_mora->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->mig_dias_mora->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_dias_mora" name="x<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" id="x<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" value="<?php echo ew_HtmlEncode($deudas->mig_dias_mora->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_mig_dias_mora" name="o<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" id="o<?php echo $deudas_grid->RowIndex ?>_mig_dias_mora" value="<?php echo ew_HtmlEncode($deudas->mig_dias_mora->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->mig_monto_desembolso->Visible) { // mig_monto_desembolso ?>
		<td data-name="mig_monto_desembolso">
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_mig_monto_desembolso" class="form-group deudas_mig_monto_desembolso">
<input type="text" data-table="deudas" data-field="x_mig_monto_desembolso" name="x<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" id="x<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_monto_desembolso->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_monto_desembolso->EditValue ?>"<?php echo $deudas->mig_monto_desembolso->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_mig_monto_desembolso" class="form-group deudas_mig_monto_desembolso">
<span<?php echo $deudas->mig_monto_desembolso->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->mig_monto_desembolso->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_monto_desembolso" name="x<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" id="x<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_monto_desembolso->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_mig_monto_desembolso" name="o<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" id="o<?php echo $deudas_grid->RowIndex ?>_mig_monto_desembolso" value="<?php echo ew_HtmlEncode($deudas->mig_monto_desembolso->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->mig_intereses->Visible) { // mig_intereses ?>
		<td data-name="mig_intereses">
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_mig_intereses" class="form-group deudas_mig_intereses">
<input type="text" data-table="deudas" data-field="x_mig_intereses" name="x<?php echo $deudas_grid->RowIndex ?>_mig_intereses" id="x<?php echo $deudas_grid->RowIndex ?>_mig_intereses" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_intereses->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_intereses->EditValue ?>"<?php echo $deudas->mig_intereses->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_mig_intereses" class="form-group deudas_mig_intereses">
<span<?php echo $deudas->mig_intereses->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->mig_intereses->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_intereses" name="x<?php echo $deudas_grid->RowIndex ?>_mig_intereses" id="x<?php echo $deudas_grid->RowIndex ?>_mig_intereses" value="<?php echo ew_HtmlEncode($deudas->mig_intereses->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_mig_intereses" name="o<?php echo $deudas_grid->RowIndex ?>_mig_intereses" id="o<?php echo $deudas_grid->RowIndex ?>_mig_intereses" value="<?php echo ew_HtmlEncode($deudas->mig_intereses->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->mig_cargos_gastos->Visible) { // mig_cargos_gastos ?>
		<td data-name="mig_cargos_gastos">
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_mig_cargos_gastos" class="form-group deudas_mig_cargos_gastos">
<input type="text" data-table="deudas" data-field="x_mig_cargos_gastos" name="x<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" id="x<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_cargos_gastos->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_cargos_gastos->EditValue ?>"<?php echo $deudas->mig_cargos_gastos->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_mig_cargos_gastos" class="form-group deudas_mig_cargos_gastos">
<span<?php echo $deudas->mig_cargos_gastos->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->mig_cargos_gastos->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_cargos_gastos" name="x<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" id="x<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" value="<?php echo ew_HtmlEncode($deudas->mig_cargos_gastos->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_mig_cargos_gastos" name="o<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" id="o<?php echo $deudas_grid->RowIndex ?>_mig_cargos_gastos" value="<?php echo ew_HtmlEncode($deudas->mig_cargos_gastos->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($deudas->mig_total_deuda->Visible) { // mig_total_deuda ?>
		<td data-name="mig_total_deuda">
<?php if ($deudas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_deudas_mig_total_deuda" class="form-group deudas_mig_total_deuda">
<input type="text" data-table="deudas" data-field="x_mig_total_deuda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" id="x<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_total_deuda->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_total_deuda->EditValue ?>"<?php echo $deudas->mig_total_deuda->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_deudas_mig_total_deuda" class="form-group deudas_mig_total_deuda">
<span<?php echo $deudas->mig_total_deuda->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->mig_total_deuda->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_mig_total_deuda" name="x<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" id="x<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_total_deuda->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="deudas" data-field="x_mig_total_deuda" name="o<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" id="o<?php echo $deudas_grid->RowIndex ?>_mig_total_deuda" value="<?php echo ew_HtmlEncode($deudas->mig_total_deuda->OldValue) ?>">
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
