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
			elm = this.GetElements("x" + infix + "_nombres");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $direcciones->nombres->FldCaption(), $direcciones->nombres->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_direccion1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $direcciones->direccion1->FldCaption(), $direcciones->direccion1->ReqErrMsg)) ?>");

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
	if (ew_ValueChanged(fobj, infix, "id_fuente", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_gestion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_tipodireccion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tipo_documento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "no_documento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nombres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "paterno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "materno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pais", false)) return false;
	if (ew_ValueChanged(fobj, infix, "departamento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "provincia", false)) return false;
	if (ew_ValueChanged(fobj, infix, "municipio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "localidad", false)) return false;
	if (ew_ValueChanged(fobj, infix, "distrito", false)) return false;
	if (ew_ValueChanged(fobj, infix, "zona", false)) return false;
	if (ew_ValueChanged(fobj, infix, "direccion1", false)) return false;
	if (ew_ValueChanged(fobj, infix, "direccion2", false)) return false;
	if (ew_ValueChanged(fobj, infix, "direccion3", false)) return false;
	if (ew_ValueChanged(fobj, infix, "direccion4", false)) return false;
	if (ew_ValueChanged(fobj, infix, "mapa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "longitud", false)) return false;
	if (ew_ValueChanged(fobj, infix, "latitud", false)) return false;
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
fdireccionesgrid.Lists["x_id_persona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_paterno","x_materno","x_nombres",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
fdireccionesgrid.Lists["x_id_persona"].Data = "<?php echo $direcciones_grid->id_persona->LookupFilterQuery(FALSE, "grid") ?>";
fdireccionesgrid.Lists["x_id_fuente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"fuentes"};
fdireccionesgrid.Lists["x_id_fuente"].Data = "<?php echo $direcciones_grid->id_fuente->LookupFilterQuery(FALSE, "grid") ?>";
fdireccionesgrid.Lists["x_id_gestion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gestiones"};
fdireccionesgrid.Lists["x_id_gestion"].Data = "<?php echo $direcciones_grid->id_gestion->LookupFilterQuery(FALSE, "grid") ?>";
fdireccionesgrid.Lists["x_id_tipodireccion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipo_direccion"};
fdireccionesgrid.Lists["x_id_tipodireccion"].Data = "<?php echo $direcciones_grid->id_tipodireccion->LookupFilterQuery(FALSE, "grid") ?>";

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
<?php if ($direcciones->id_fuente->Visible) { // id_fuente ?>
	<?php if ($direcciones->SortUrl($direcciones->id_fuente) == "") { ?>
		<th data-name="id_fuente" class="<?php echo $direcciones->id_fuente->HeaderCellClass() ?>"><div id="elh_direcciones_id_fuente" class="direcciones_id_fuente"><div class="ewTableHeaderCaption"><?php echo $direcciones->id_fuente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_fuente" class="<?php echo $direcciones->id_fuente->HeaderCellClass() ?>"><div><div id="elh_direcciones_id_fuente" class="direcciones_id_fuente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->id_fuente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->id_fuente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->id_fuente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->id_gestion->Visible) { // id_gestion ?>
	<?php if ($direcciones->SortUrl($direcciones->id_gestion) == "") { ?>
		<th data-name="id_gestion" class="<?php echo $direcciones->id_gestion->HeaderCellClass() ?>"><div id="elh_direcciones_id_gestion" class="direcciones_id_gestion"><div class="ewTableHeaderCaption"><?php echo $direcciones->id_gestion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_gestion" class="<?php echo $direcciones->id_gestion->HeaderCellClass() ?>"><div><div id="elh_direcciones_id_gestion" class="direcciones_id_gestion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->id_gestion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->id_gestion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->id_gestion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->id_tipodireccion->Visible) { // id_tipodireccion ?>
	<?php if ($direcciones->SortUrl($direcciones->id_tipodireccion) == "") { ?>
		<th data-name="id_tipodireccion" class="<?php echo $direcciones->id_tipodireccion->HeaderCellClass() ?>"><div id="elh_direcciones_id_tipodireccion" class="direcciones_id_tipodireccion"><div class="ewTableHeaderCaption"><?php echo $direcciones->id_tipodireccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipodireccion" class="<?php echo $direcciones->id_tipodireccion->HeaderCellClass() ?>"><div><div id="elh_direcciones_id_tipodireccion" class="direcciones_id_tipodireccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->id_tipodireccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->id_tipodireccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->id_tipodireccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->tipo_documento->Visible) { // tipo_documento ?>
	<?php if ($direcciones->SortUrl($direcciones->tipo_documento) == "") { ?>
		<th data-name="tipo_documento" class="<?php echo $direcciones->tipo_documento->HeaderCellClass() ?>"><div id="elh_direcciones_tipo_documento" class="direcciones_tipo_documento"><div class="ewTableHeaderCaption"><?php echo $direcciones->tipo_documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo_documento" class="<?php echo $direcciones->tipo_documento->HeaderCellClass() ?>"><div><div id="elh_direcciones_tipo_documento" class="direcciones_tipo_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->tipo_documento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->tipo_documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->tipo_documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->no_documento->Visible) { // no_documento ?>
	<?php if ($direcciones->SortUrl($direcciones->no_documento) == "") { ?>
		<th data-name="no_documento" class="<?php echo $direcciones->no_documento->HeaderCellClass() ?>"><div id="elh_direcciones_no_documento" class="direcciones_no_documento"><div class="ewTableHeaderCaption"><?php echo $direcciones->no_documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="no_documento" class="<?php echo $direcciones->no_documento->HeaderCellClass() ?>"><div><div id="elh_direcciones_no_documento" class="direcciones_no_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->no_documento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->no_documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->no_documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->nombres->Visible) { // nombres ?>
	<?php if ($direcciones->SortUrl($direcciones->nombres) == "") { ?>
		<th data-name="nombres" class="<?php echo $direcciones->nombres->HeaderCellClass() ?>"><div id="elh_direcciones_nombres" class="direcciones_nombres"><div class="ewTableHeaderCaption"><?php echo $direcciones->nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombres" class="<?php echo $direcciones->nombres->HeaderCellClass() ?>"><div><div id="elh_direcciones_nombres" class="direcciones_nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->nombres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->paterno->Visible) { // paterno ?>
	<?php if ($direcciones->SortUrl($direcciones->paterno) == "") { ?>
		<th data-name="paterno" class="<?php echo $direcciones->paterno->HeaderCellClass() ?>"><div id="elh_direcciones_paterno" class="direcciones_paterno"><div class="ewTableHeaderCaption"><?php echo $direcciones->paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="paterno" class="<?php echo $direcciones->paterno->HeaderCellClass() ?>"><div><div id="elh_direcciones_paterno" class="direcciones_paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->paterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->materno->Visible) { // materno ?>
	<?php if ($direcciones->SortUrl($direcciones->materno) == "") { ?>
		<th data-name="materno" class="<?php echo $direcciones->materno->HeaderCellClass() ?>"><div id="elh_direcciones_materno" class="direcciones_materno"><div class="ewTableHeaderCaption"><?php echo $direcciones->materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="materno" class="<?php echo $direcciones->materno->HeaderCellClass() ?>"><div><div id="elh_direcciones_materno" class="direcciones_materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->materno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->pais->Visible) { // pais ?>
	<?php if ($direcciones->SortUrl($direcciones->pais) == "") { ?>
		<th data-name="pais" class="<?php echo $direcciones->pais->HeaderCellClass() ?>"><div id="elh_direcciones_pais" class="direcciones_pais"><div class="ewTableHeaderCaption"><?php echo $direcciones->pais->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pais" class="<?php echo $direcciones->pais->HeaderCellClass() ?>"><div><div id="elh_direcciones_pais" class="direcciones_pais">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->pais->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->pais->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->pais->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->departamento->Visible) { // departamento ?>
	<?php if ($direcciones->SortUrl($direcciones->departamento) == "") { ?>
		<th data-name="departamento" class="<?php echo $direcciones->departamento->HeaderCellClass() ?>"><div id="elh_direcciones_departamento" class="direcciones_departamento"><div class="ewTableHeaderCaption"><?php echo $direcciones->departamento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="departamento" class="<?php echo $direcciones->departamento->HeaderCellClass() ?>"><div><div id="elh_direcciones_departamento" class="direcciones_departamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->departamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->departamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->departamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->provincia->Visible) { // provincia ?>
	<?php if ($direcciones->SortUrl($direcciones->provincia) == "") { ?>
		<th data-name="provincia" class="<?php echo $direcciones->provincia->HeaderCellClass() ?>"><div id="elh_direcciones_provincia" class="direcciones_provincia"><div class="ewTableHeaderCaption"><?php echo $direcciones->provincia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="provincia" class="<?php echo $direcciones->provincia->HeaderCellClass() ?>"><div><div id="elh_direcciones_provincia" class="direcciones_provincia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->provincia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->provincia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->provincia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->municipio->Visible) { // municipio ?>
	<?php if ($direcciones->SortUrl($direcciones->municipio) == "") { ?>
		<th data-name="municipio" class="<?php echo $direcciones->municipio->HeaderCellClass() ?>"><div id="elh_direcciones_municipio" class="direcciones_municipio"><div class="ewTableHeaderCaption"><?php echo $direcciones->municipio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="municipio" class="<?php echo $direcciones->municipio->HeaderCellClass() ?>"><div><div id="elh_direcciones_municipio" class="direcciones_municipio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->municipio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->municipio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->municipio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->localidad->Visible) { // localidad ?>
	<?php if ($direcciones->SortUrl($direcciones->localidad) == "") { ?>
		<th data-name="localidad" class="<?php echo $direcciones->localidad->HeaderCellClass() ?>"><div id="elh_direcciones_localidad" class="direcciones_localidad"><div class="ewTableHeaderCaption"><?php echo $direcciones->localidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="localidad" class="<?php echo $direcciones->localidad->HeaderCellClass() ?>"><div><div id="elh_direcciones_localidad" class="direcciones_localidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->localidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->localidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->localidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->distrito->Visible) { // distrito ?>
	<?php if ($direcciones->SortUrl($direcciones->distrito) == "") { ?>
		<th data-name="distrito" class="<?php echo $direcciones->distrito->HeaderCellClass() ?>"><div id="elh_direcciones_distrito" class="direcciones_distrito"><div class="ewTableHeaderCaption"><?php echo $direcciones->distrito->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="distrito" class="<?php echo $direcciones->distrito->HeaderCellClass() ?>"><div><div id="elh_direcciones_distrito" class="direcciones_distrito">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->distrito->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->distrito->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->distrito->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->zona->Visible) { // zona ?>
	<?php if ($direcciones->SortUrl($direcciones->zona) == "") { ?>
		<th data-name="zona" class="<?php echo $direcciones->zona->HeaderCellClass() ?>"><div id="elh_direcciones_zona" class="direcciones_zona"><div class="ewTableHeaderCaption"><?php echo $direcciones->zona->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="zona" class="<?php echo $direcciones->zona->HeaderCellClass() ?>"><div><div id="elh_direcciones_zona" class="direcciones_zona">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->zona->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->zona->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->zona->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->direccion1->Visible) { // direccion1 ?>
	<?php if ($direcciones->SortUrl($direcciones->direccion1) == "") { ?>
		<th data-name="direccion1" class="<?php echo $direcciones->direccion1->HeaderCellClass() ?>"><div id="elh_direcciones_direccion1" class="direcciones_direccion1"><div class="ewTableHeaderCaption"><?php echo $direcciones->direccion1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion1" class="<?php echo $direcciones->direccion1->HeaderCellClass() ?>"><div><div id="elh_direcciones_direccion1" class="direcciones_direccion1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->direccion1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->direccion1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->direccion1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->direccion2->Visible) { // direccion2 ?>
	<?php if ($direcciones->SortUrl($direcciones->direccion2) == "") { ?>
		<th data-name="direccion2" class="<?php echo $direcciones->direccion2->HeaderCellClass() ?>"><div id="elh_direcciones_direccion2" class="direcciones_direccion2"><div class="ewTableHeaderCaption"><?php echo $direcciones->direccion2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion2" class="<?php echo $direcciones->direccion2->HeaderCellClass() ?>"><div><div id="elh_direcciones_direccion2" class="direcciones_direccion2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->direccion2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->direccion2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->direccion2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->direccion3->Visible) { // direccion3 ?>
	<?php if ($direcciones->SortUrl($direcciones->direccion3) == "") { ?>
		<th data-name="direccion3" class="<?php echo $direcciones->direccion3->HeaderCellClass() ?>"><div id="elh_direcciones_direccion3" class="direcciones_direccion3"><div class="ewTableHeaderCaption"><?php echo $direcciones->direccion3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion3" class="<?php echo $direcciones->direccion3->HeaderCellClass() ?>"><div><div id="elh_direcciones_direccion3" class="direcciones_direccion3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->direccion3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->direccion3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->direccion3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->direccion4->Visible) { // direccion4 ?>
	<?php if ($direcciones->SortUrl($direcciones->direccion4) == "") { ?>
		<th data-name="direccion4" class="<?php echo $direcciones->direccion4->HeaderCellClass() ?>"><div id="elh_direcciones_direccion4" class="direcciones_direccion4"><div class="ewTableHeaderCaption"><?php echo $direcciones->direccion4->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion4" class="<?php echo $direcciones->direccion4->HeaderCellClass() ?>"><div><div id="elh_direcciones_direccion4" class="direcciones_direccion4">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->direccion4->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->direccion4->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->direccion4->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->mapa->Visible) { // mapa ?>
	<?php if ($direcciones->SortUrl($direcciones->mapa) == "") { ?>
		<th data-name="mapa" class="<?php echo $direcciones->mapa->HeaderCellClass() ?>"><div id="elh_direcciones_mapa" class="direcciones_mapa"><div class="ewTableHeaderCaption"><?php echo $direcciones->mapa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mapa" class="<?php echo $direcciones->mapa->HeaderCellClass() ?>"><div><div id="elh_direcciones_mapa" class="direcciones_mapa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->mapa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->mapa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->mapa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->longitud->Visible) { // longitud ?>
	<?php if ($direcciones->SortUrl($direcciones->longitud) == "") { ?>
		<th data-name="longitud" class="<?php echo $direcciones->longitud->HeaderCellClass() ?>"><div id="elh_direcciones_longitud" class="direcciones_longitud"><div class="ewTableHeaderCaption"><?php echo $direcciones->longitud->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="longitud" class="<?php echo $direcciones->longitud->HeaderCellClass() ?>"><div><div id="elh_direcciones_longitud" class="direcciones_longitud">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->longitud->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->longitud->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->longitud->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->latitud->Visible) { // latitud ?>
	<?php if ($direcciones->SortUrl($direcciones->latitud) == "") { ?>
		<th data-name="latitud" class="<?php echo $direcciones->latitud->HeaderCellClass() ?>"><div id="elh_direcciones_latitud" class="direcciones_latitud"><div class="ewTableHeaderCaption"><?php echo $direcciones->latitud->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="latitud" class="<?php echo $direcciones->latitud->HeaderCellClass() ?>"><div><div id="elh_direcciones_latitud" class="direcciones_latitud">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->latitud->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->latitud->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->latitud->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
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
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $direcciones_grid->RowIndex ?>_id_persona"><?php echo (strval($direcciones->id_persona->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $direcciones->id_persona->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($direcciones->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $direcciones_grid->RowIndex ?>_id_persona',m:0,n:30});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="direcciones" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $direcciones->id_persona->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" id="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" value="<?php echo $direcciones->id_persona->CurrentValue ?>"<?php echo $direcciones->id_persona->EditAttributes() ?>>
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
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $direcciones_grid->RowIndex ?>_id_persona"><?php echo (strval($direcciones->id_persona->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $direcciones->id_persona->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($direcciones->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $direcciones_grid->RowIndex ?>_id_persona',m:0,n:30});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="direcciones" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $direcciones->id_persona->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" id="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" value="<?php echo $direcciones->id_persona->CurrentValue ?>"<?php echo $direcciones->id_persona->EditAttributes() ?>>
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
	<?php if ($direcciones->id_fuente->Visible) { // id_fuente ?>
		<td data-name="id_fuente"<?php echo $direcciones->id_fuente->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_fuente" class="form-group direcciones_id_fuente">
<select data-table="direcciones" data-field="x_id_fuente" data-value-separator="<?php echo $direcciones->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $direcciones_grid->RowIndex ?>_id_fuente" name="x<?php echo $direcciones_grid->RowIndex ?>_id_fuente"<?php echo $direcciones->id_fuente->EditAttributes() ?>>
<?php echo $direcciones->id_fuente->SelectOptionListHtml("x<?php echo $direcciones_grid->RowIndex ?>_id_fuente") ?>
</select>
</span>
<input type="hidden" data-table="direcciones" data-field="x_id_fuente" name="o<?php echo $direcciones_grid->RowIndex ?>_id_fuente" id="o<?php echo $direcciones_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($direcciones->id_fuente->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_fuente" class="form-group direcciones_id_fuente">
<select data-table="direcciones" data-field="x_id_fuente" data-value-separator="<?php echo $direcciones->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $direcciones_grid->RowIndex ?>_id_fuente" name="x<?php echo $direcciones_grid->RowIndex ?>_id_fuente"<?php echo $direcciones->id_fuente->EditAttributes() ?>>
<?php echo $direcciones->id_fuente->SelectOptionListHtml("x<?php echo $direcciones_grid->RowIndex ?>_id_fuente") ?>
</select>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_fuente" class="direcciones_id_fuente">
<span<?php echo $direcciones->id_fuente->ViewAttributes() ?>>
<?php echo $direcciones->id_fuente->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_id_fuente" name="x<?php echo $direcciones_grid->RowIndex ?>_id_fuente" id="x<?php echo $direcciones_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($direcciones->id_fuente->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_id_fuente" name="o<?php echo $direcciones_grid->RowIndex ?>_id_fuente" id="o<?php echo $direcciones_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($direcciones->id_fuente->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_id_fuente" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_id_fuente" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($direcciones->id_fuente->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_id_fuente" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_id_fuente" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($direcciones->id_fuente->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->id_gestion->Visible) { // id_gestion ?>
		<td data-name="id_gestion"<?php echo $direcciones->id_gestion->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_gestion" class="form-group direcciones_id_gestion">
<select data-table="direcciones" data-field="x_id_gestion" data-value-separator="<?php echo $direcciones->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $direcciones_grid->RowIndex ?>_id_gestion" name="x<?php echo $direcciones_grid->RowIndex ?>_id_gestion"<?php echo $direcciones->id_gestion->EditAttributes() ?>>
<?php echo $direcciones->id_gestion->SelectOptionListHtml("x<?php echo $direcciones_grid->RowIndex ?>_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$direcciones->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $direcciones->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $direcciones_grid->RowIndex ?>_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $direcciones_grid->RowIndex ?>_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $direcciones->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<input type="hidden" data-table="direcciones" data-field="x_id_gestion" name="o<?php echo $direcciones_grid->RowIndex ?>_id_gestion" id="o<?php echo $direcciones_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($direcciones->id_gestion->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_gestion" class="form-group direcciones_id_gestion">
<select data-table="direcciones" data-field="x_id_gestion" data-value-separator="<?php echo $direcciones->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $direcciones_grid->RowIndex ?>_id_gestion" name="x<?php echo $direcciones_grid->RowIndex ?>_id_gestion"<?php echo $direcciones->id_gestion->EditAttributes() ?>>
<?php echo $direcciones->id_gestion->SelectOptionListHtml("x<?php echo $direcciones_grid->RowIndex ?>_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$direcciones->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $direcciones->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $direcciones_grid->RowIndex ?>_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $direcciones_grid->RowIndex ?>_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $direcciones->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_gestion" class="direcciones_id_gestion">
<span<?php echo $direcciones->id_gestion->ViewAttributes() ?>>
<?php echo $direcciones->id_gestion->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_id_gestion" name="x<?php echo $direcciones_grid->RowIndex ?>_id_gestion" id="x<?php echo $direcciones_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($direcciones->id_gestion->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_id_gestion" name="o<?php echo $direcciones_grid->RowIndex ?>_id_gestion" id="o<?php echo $direcciones_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($direcciones->id_gestion->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_id_gestion" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_id_gestion" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($direcciones->id_gestion->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_id_gestion" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_id_gestion" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($direcciones->id_gestion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->id_tipodireccion->Visible) { // id_tipodireccion ?>
		<td data-name="id_tipodireccion"<?php echo $direcciones->id_tipodireccion->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_tipodireccion" class="form-group direcciones_id_tipodireccion">
<select data-table="direcciones" data-field="x_id_tipodireccion" data-value-separator="<?php echo $direcciones->id_tipodireccion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" name="x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion"<?php echo $direcciones->id_tipodireccion->EditAttributes() ?>>
<?php echo $direcciones->id_tipodireccion->SelectOptionListHtml("x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipo_direccion") && !$direcciones->id_tipodireccion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $direcciones->id_tipodireccion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion',url:'tipo_direccionaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $direcciones->id_tipodireccion->FldCaption() ?></span></button>
<?php } ?>
</span>
<input type="hidden" data-table="direcciones" data-field="x_id_tipodireccion" name="o<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" id="o<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" value="<?php echo ew_HtmlEncode($direcciones->id_tipodireccion->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_tipodireccion" class="form-group direcciones_id_tipodireccion">
<select data-table="direcciones" data-field="x_id_tipodireccion" data-value-separator="<?php echo $direcciones->id_tipodireccion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" name="x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion"<?php echo $direcciones->id_tipodireccion->EditAttributes() ?>>
<?php echo $direcciones->id_tipodireccion->SelectOptionListHtml("x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipo_direccion") && !$direcciones->id_tipodireccion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $direcciones->id_tipodireccion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion',url:'tipo_direccionaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $direcciones->id_tipodireccion->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_id_tipodireccion" class="direcciones_id_tipodireccion">
<span<?php echo $direcciones->id_tipodireccion->ViewAttributes() ?>>
<?php echo $direcciones->id_tipodireccion->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_id_tipodireccion" name="x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" id="x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" value="<?php echo ew_HtmlEncode($direcciones->id_tipodireccion->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_id_tipodireccion" name="o<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" id="o<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" value="<?php echo ew_HtmlEncode($direcciones->id_tipodireccion->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_id_tipodireccion" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" value="<?php echo ew_HtmlEncode($direcciones->id_tipodireccion->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_id_tipodireccion" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" value="<?php echo ew_HtmlEncode($direcciones->id_tipodireccion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->tipo_documento->Visible) { // tipo_documento ?>
		<td data-name="tipo_documento"<?php echo $direcciones->tipo_documento->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_tipo_documento" class="form-group direcciones_tipo_documento">
<input type="text" data-table="direcciones" data-field="x_tipo_documento" name="x<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" id="x<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($direcciones->tipo_documento->getPlaceHolder()) ?>" value="<?php echo $direcciones->tipo_documento->EditValue ?>"<?php echo $direcciones->tipo_documento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_tipo_documento" name="o<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" id="o<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($direcciones->tipo_documento->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_tipo_documento" class="form-group direcciones_tipo_documento">
<input type="text" data-table="direcciones" data-field="x_tipo_documento" name="x<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" id="x<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($direcciones->tipo_documento->getPlaceHolder()) ?>" value="<?php echo $direcciones->tipo_documento->EditValue ?>"<?php echo $direcciones->tipo_documento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_tipo_documento" class="direcciones_tipo_documento">
<span<?php echo $direcciones->tipo_documento->ViewAttributes() ?>>
<?php echo $direcciones->tipo_documento->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_tipo_documento" name="x<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" id="x<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($direcciones->tipo_documento->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_tipo_documento" name="o<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" id="o<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($direcciones->tipo_documento->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_tipo_documento" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($direcciones->tipo_documento->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_tipo_documento" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($direcciones->tipo_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->no_documento->Visible) { // no_documento ?>
		<td data-name="no_documento"<?php echo $direcciones->no_documento->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_no_documento" class="form-group direcciones_no_documento">
<input type="text" data-table="direcciones" data-field="x_no_documento" name="x<?php echo $direcciones_grid->RowIndex ?>_no_documento" id="x<?php echo $direcciones_grid->RowIndex ?>_no_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->no_documento->getPlaceHolder()) ?>" value="<?php echo $direcciones->no_documento->EditValue ?>"<?php echo $direcciones->no_documento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_no_documento" name="o<?php echo $direcciones_grid->RowIndex ?>_no_documento" id="o<?php echo $direcciones_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($direcciones->no_documento->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_no_documento" class="form-group direcciones_no_documento">
<input type="text" data-table="direcciones" data-field="x_no_documento" name="x<?php echo $direcciones_grid->RowIndex ?>_no_documento" id="x<?php echo $direcciones_grid->RowIndex ?>_no_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->no_documento->getPlaceHolder()) ?>" value="<?php echo $direcciones->no_documento->EditValue ?>"<?php echo $direcciones->no_documento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_no_documento" class="direcciones_no_documento">
<span<?php echo $direcciones->no_documento->ViewAttributes() ?>>
<?php echo $direcciones->no_documento->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_no_documento" name="x<?php echo $direcciones_grid->RowIndex ?>_no_documento" id="x<?php echo $direcciones_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($direcciones->no_documento->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_no_documento" name="o<?php echo $direcciones_grid->RowIndex ?>_no_documento" id="o<?php echo $direcciones_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($direcciones->no_documento->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_no_documento" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_no_documento" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($direcciones->no_documento->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_no_documento" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_no_documento" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($direcciones->no_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->nombres->Visible) { // nombres ?>
		<td data-name="nombres"<?php echo $direcciones->nombres->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_nombres" class="form-group direcciones_nombres">
<input type="text" data-table="direcciones" data-field="x_nombres" name="x<?php echo $direcciones_grid->RowIndex ?>_nombres" id="x<?php echo $direcciones_grid->RowIndex ?>_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->nombres->getPlaceHolder()) ?>" value="<?php echo $direcciones->nombres->EditValue ?>"<?php echo $direcciones->nombres->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_nombres" name="o<?php echo $direcciones_grid->RowIndex ?>_nombres" id="o<?php echo $direcciones_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($direcciones->nombres->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_nombres" class="form-group direcciones_nombres">
<input type="text" data-table="direcciones" data-field="x_nombres" name="x<?php echo $direcciones_grid->RowIndex ?>_nombres" id="x<?php echo $direcciones_grid->RowIndex ?>_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->nombres->getPlaceHolder()) ?>" value="<?php echo $direcciones->nombres->EditValue ?>"<?php echo $direcciones->nombres->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_nombres" class="direcciones_nombres">
<span<?php echo $direcciones->nombres->ViewAttributes() ?>>
<?php echo $direcciones->nombres->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_nombres" name="x<?php echo $direcciones_grid->RowIndex ?>_nombres" id="x<?php echo $direcciones_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($direcciones->nombres->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_nombres" name="o<?php echo $direcciones_grid->RowIndex ?>_nombres" id="o<?php echo $direcciones_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($direcciones->nombres->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_nombres" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_nombres" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($direcciones->nombres->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_nombres" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_nombres" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($direcciones->nombres->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->paterno->Visible) { // paterno ?>
		<td data-name="paterno"<?php echo $direcciones->paterno->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_paterno" class="form-group direcciones_paterno">
<input type="text" data-table="direcciones" data-field="x_paterno" name="x<?php echo $direcciones_grid->RowIndex ?>_paterno" id="x<?php echo $direcciones_grid->RowIndex ?>_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->paterno->getPlaceHolder()) ?>" value="<?php echo $direcciones->paterno->EditValue ?>"<?php echo $direcciones->paterno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_paterno" name="o<?php echo $direcciones_grid->RowIndex ?>_paterno" id="o<?php echo $direcciones_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($direcciones->paterno->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_paterno" class="form-group direcciones_paterno">
<input type="text" data-table="direcciones" data-field="x_paterno" name="x<?php echo $direcciones_grid->RowIndex ?>_paterno" id="x<?php echo $direcciones_grid->RowIndex ?>_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->paterno->getPlaceHolder()) ?>" value="<?php echo $direcciones->paterno->EditValue ?>"<?php echo $direcciones->paterno->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_paterno" class="direcciones_paterno">
<span<?php echo $direcciones->paterno->ViewAttributes() ?>>
<?php echo $direcciones->paterno->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_paterno" name="x<?php echo $direcciones_grid->RowIndex ?>_paterno" id="x<?php echo $direcciones_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($direcciones->paterno->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_paterno" name="o<?php echo $direcciones_grid->RowIndex ?>_paterno" id="o<?php echo $direcciones_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($direcciones->paterno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_paterno" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_paterno" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($direcciones->paterno->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_paterno" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_paterno" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($direcciones->paterno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->materno->Visible) { // materno ?>
		<td data-name="materno"<?php echo $direcciones->materno->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_materno" class="form-group direcciones_materno">
<input type="text" data-table="direcciones" data-field="x_materno" name="x<?php echo $direcciones_grid->RowIndex ?>_materno" id="x<?php echo $direcciones_grid->RowIndex ?>_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->materno->getPlaceHolder()) ?>" value="<?php echo $direcciones->materno->EditValue ?>"<?php echo $direcciones->materno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_materno" name="o<?php echo $direcciones_grid->RowIndex ?>_materno" id="o<?php echo $direcciones_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($direcciones->materno->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_materno" class="form-group direcciones_materno">
<input type="text" data-table="direcciones" data-field="x_materno" name="x<?php echo $direcciones_grid->RowIndex ?>_materno" id="x<?php echo $direcciones_grid->RowIndex ?>_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->materno->getPlaceHolder()) ?>" value="<?php echo $direcciones->materno->EditValue ?>"<?php echo $direcciones->materno->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_materno" class="direcciones_materno">
<span<?php echo $direcciones->materno->ViewAttributes() ?>>
<?php echo $direcciones->materno->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_materno" name="x<?php echo $direcciones_grid->RowIndex ?>_materno" id="x<?php echo $direcciones_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($direcciones->materno->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_materno" name="o<?php echo $direcciones_grid->RowIndex ?>_materno" id="o<?php echo $direcciones_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($direcciones->materno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_materno" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_materno" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($direcciones->materno->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_materno" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_materno" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($direcciones->materno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->pais->Visible) { // pais ?>
		<td data-name="pais"<?php echo $direcciones->pais->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_pais" class="form-group direcciones_pais">
<input type="text" data-table="direcciones" data-field="x_pais" name="x<?php echo $direcciones_grid->RowIndex ?>_pais" id="x<?php echo $direcciones_grid->RowIndex ?>_pais" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->pais->getPlaceHolder()) ?>" value="<?php echo $direcciones->pais->EditValue ?>"<?php echo $direcciones->pais->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_pais" name="o<?php echo $direcciones_grid->RowIndex ?>_pais" id="o<?php echo $direcciones_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($direcciones->pais->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_pais" class="form-group direcciones_pais">
<input type="text" data-table="direcciones" data-field="x_pais" name="x<?php echo $direcciones_grid->RowIndex ?>_pais" id="x<?php echo $direcciones_grid->RowIndex ?>_pais" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->pais->getPlaceHolder()) ?>" value="<?php echo $direcciones->pais->EditValue ?>"<?php echo $direcciones->pais->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_pais" class="direcciones_pais">
<span<?php echo $direcciones->pais->ViewAttributes() ?>>
<?php echo $direcciones->pais->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_pais" name="x<?php echo $direcciones_grid->RowIndex ?>_pais" id="x<?php echo $direcciones_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($direcciones->pais->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_pais" name="o<?php echo $direcciones_grid->RowIndex ?>_pais" id="o<?php echo $direcciones_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($direcciones->pais->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_pais" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_pais" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($direcciones->pais->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_pais" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_pais" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($direcciones->pais->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->departamento->Visible) { // departamento ?>
		<td data-name="departamento"<?php echo $direcciones->departamento->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_departamento" class="form-group direcciones_departamento">
<input type="text" data-table="direcciones" data-field="x_departamento" name="x<?php echo $direcciones_grid->RowIndex ?>_departamento" id="x<?php echo $direcciones_grid->RowIndex ?>_departamento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->departamento->getPlaceHolder()) ?>" value="<?php echo $direcciones->departamento->EditValue ?>"<?php echo $direcciones->departamento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_departamento" name="o<?php echo $direcciones_grid->RowIndex ?>_departamento" id="o<?php echo $direcciones_grid->RowIndex ?>_departamento" value="<?php echo ew_HtmlEncode($direcciones->departamento->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_departamento" class="form-group direcciones_departamento">
<input type="text" data-table="direcciones" data-field="x_departamento" name="x<?php echo $direcciones_grid->RowIndex ?>_departamento" id="x<?php echo $direcciones_grid->RowIndex ?>_departamento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->departamento->getPlaceHolder()) ?>" value="<?php echo $direcciones->departamento->EditValue ?>"<?php echo $direcciones->departamento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_departamento" class="direcciones_departamento">
<span<?php echo $direcciones->departamento->ViewAttributes() ?>>
<?php echo $direcciones->departamento->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_departamento" name="x<?php echo $direcciones_grid->RowIndex ?>_departamento" id="x<?php echo $direcciones_grid->RowIndex ?>_departamento" value="<?php echo ew_HtmlEncode($direcciones->departamento->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_departamento" name="o<?php echo $direcciones_grid->RowIndex ?>_departamento" id="o<?php echo $direcciones_grid->RowIndex ?>_departamento" value="<?php echo ew_HtmlEncode($direcciones->departamento->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_departamento" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_departamento" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_departamento" value="<?php echo ew_HtmlEncode($direcciones->departamento->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_departamento" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_departamento" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_departamento" value="<?php echo ew_HtmlEncode($direcciones->departamento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->provincia->Visible) { // provincia ?>
		<td data-name="provincia"<?php echo $direcciones->provincia->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_provincia" class="form-group direcciones_provincia">
<input type="text" data-table="direcciones" data-field="x_provincia" name="x<?php echo $direcciones_grid->RowIndex ?>_provincia" id="x<?php echo $direcciones_grid->RowIndex ?>_provincia" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->provincia->getPlaceHolder()) ?>" value="<?php echo $direcciones->provincia->EditValue ?>"<?php echo $direcciones->provincia->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_provincia" name="o<?php echo $direcciones_grid->RowIndex ?>_provincia" id="o<?php echo $direcciones_grid->RowIndex ?>_provincia" value="<?php echo ew_HtmlEncode($direcciones->provincia->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_provincia" class="form-group direcciones_provincia">
<input type="text" data-table="direcciones" data-field="x_provincia" name="x<?php echo $direcciones_grid->RowIndex ?>_provincia" id="x<?php echo $direcciones_grid->RowIndex ?>_provincia" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->provincia->getPlaceHolder()) ?>" value="<?php echo $direcciones->provincia->EditValue ?>"<?php echo $direcciones->provincia->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_provincia" class="direcciones_provincia">
<span<?php echo $direcciones->provincia->ViewAttributes() ?>>
<?php echo $direcciones->provincia->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_provincia" name="x<?php echo $direcciones_grid->RowIndex ?>_provincia" id="x<?php echo $direcciones_grid->RowIndex ?>_provincia" value="<?php echo ew_HtmlEncode($direcciones->provincia->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_provincia" name="o<?php echo $direcciones_grid->RowIndex ?>_provincia" id="o<?php echo $direcciones_grid->RowIndex ?>_provincia" value="<?php echo ew_HtmlEncode($direcciones->provincia->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_provincia" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_provincia" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_provincia" value="<?php echo ew_HtmlEncode($direcciones->provincia->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_provincia" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_provincia" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_provincia" value="<?php echo ew_HtmlEncode($direcciones->provincia->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->municipio->Visible) { // municipio ?>
		<td data-name="municipio"<?php echo $direcciones->municipio->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_municipio" class="form-group direcciones_municipio">
<input type="text" data-table="direcciones" data-field="x_municipio" name="x<?php echo $direcciones_grid->RowIndex ?>_municipio" id="x<?php echo $direcciones_grid->RowIndex ?>_municipio" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->municipio->getPlaceHolder()) ?>" value="<?php echo $direcciones->municipio->EditValue ?>"<?php echo $direcciones->municipio->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_municipio" name="o<?php echo $direcciones_grid->RowIndex ?>_municipio" id="o<?php echo $direcciones_grid->RowIndex ?>_municipio" value="<?php echo ew_HtmlEncode($direcciones->municipio->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_municipio" class="form-group direcciones_municipio">
<input type="text" data-table="direcciones" data-field="x_municipio" name="x<?php echo $direcciones_grid->RowIndex ?>_municipio" id="x<?php echo $direcciones_grid->RowIndex ?>_municipio" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->municipio->getPlaceHolder()) ?>" value="<?php echo $direcciones->municipio->EditValue ?>"<?php echo $direcciones->municipio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_municipio" class="direcciones_municipio">
<span<?php echo $direcciones->municipio->ViewAttributes() ?>>
<?php echo $direcciones->municipio->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_municipio" name="x<?php echo $direcciones_grid->RowIndex ?>_municipio" id="x<?php echo $direcciones_grid->RowIndex ?>_municipio" value="<?php echo ew_HtmlEncode($direcciones->municipio->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_municipio" name="o<?php echo $direcciones_grid->RowIndex ?>_municipio" id="o<?php echo $direcciones_grid->RowIndex ?>_municipio" value="<?php echo ew_HtmlEncode($direcciones->municipio->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_municipio" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_municipio" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_municipio" value="<?php echo ew_HtmlEncode($direcciones->municipio->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_municipio" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_municipio" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_municipio" value="<?php echo ew_HtmlEncode($direcciones->municipio->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->localidad->Visible) { // localidad ?>
		<td data-name="localidad"<?php echo $direcciones->localidad->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_localidad" class="form-group direcciones_localidad">
<input type="text" data-table="direcciones" data-field="x_localidad" name="x<?php echo $direcciones_grid->RowIndex ?>_localidad" id="x<?php echo $direcciones_grid->RowIndex ?>_localidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->localidad->getPlaceHolder()) ?>" value="<?php echo $direcciones->localidad->EditValue ?>"<?php echo $direcciones->localidad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_localidad" name="o<?php echo $direcciones_grid->RowIndex ?>_localidad" id="o<?php echo $direcciones_grid->RowIndex ?>_localidad" value="<?php echo ew_HtmlEncode($direcciones->localidad->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_localidad" class="form-group direcciones_localidad">
<input type="text" data-table="direcciones" data-field="x_localidad" name="x<?php echo $direcciones_grid->RowIndex ?>_localidad" id="x<?php echo $direcciones_grid->RowIndex ?>_localidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->localidad->getPlaceHolder()) ?>" value="<?php echo $direcciones->localidad->EditValue ?>"<?php echo $direcciones->localidad->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_localidad" class="direcciones_localidad">
<span<?php echo $direcciones->localidad->ViewAttributes() ?>>
<?php echo $direcciones->localidad->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_localidad" name="x<?php echo $direcciones_grid->RowIndex ?>_localidad" id="x<?php echo $direcciones_grid->RowIndex ?>_localidad" value="<?php echo ew_HtmlEncode($direcciones->localidad->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_localidad" name="o<?php echo $direcciones_grid->RowIndex ?>_localidad" id="o<?php echo $direcciones_grid->RowIndex ?>_localidad" value="<?php echo ew_HtmlEncode($direcciones->localidad->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_localidad" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_localidad" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_localidad" value="<?php echo ew_HtmlEncode($direcciones->localidad->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_localidad" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_localidad" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_localidad" value="<?php echo ew_HtmlEncode($direcciones->localidad->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->distrito->Visible) { // distrito ?>
		<td data-name="distrito"<?php echo $direcciones->distrito->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_distrito" class="form-group direcciones_distrito">
<input type="text" data-table="direcciones" data-field="x_distrito" name="x<?php echo $direcciones_grid->RowIndex ?>_distrito" id="x<?php echo $direcciones_grid->RowIndex ?>_distrito" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->distrito->getPlaceHolder()) ?>" value="<?php echo $direcciones->distrito->EditValue ?>"<?php echo $direcciones->distrito->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_distrito" name="o<?php echo $direcciones_grid->RowIndex ?>_distrito" id="o<?php echo $direcciones_grid->RowIndex ?>_distrito" value="<?php echo ew_HtmlEncode($direcciones->distrito->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_distrito" class="form-group direcciones_distrito">
<input type="text" data-table="direcciones" data-field="x_distrito" name="x<?php echo $direcciones_grid->RowIndex ?>_distrito" id="x<?php echo $direcciones_grid->RowIndex ?>_distrito" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->distrito->getPlaceHolder()) ?>" value="<?php echo $direcciones->distrito->EditValue ?>"<?php echo $direcciones->distrito->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_distrito" class="direcciones_distrito">
<span<?php echo $direcciones->distrito->ViewAttributes() ?>>
<?php echo $direcciones->distrito->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_distrito" name="x<?php echo $direcciones_grid->RowIndex ?>_distrito" id="x<?php echo $direcciones_grid->RowIndex ?>_distrito" value="<?php echo ew_HtmlEncode($direcciones->distrito->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_distrito" name="o<?php echo $direcciones_grid->RowIndex ?>_distrito" id="o<?php echo $direcciones_grid->RowIndex ?>_distrito" value="<?php echo ew_HtmlEncode($direcciones->distrito->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_distrito" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_distrito" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_distrito" value="<?php echo ew_HtmlEncode($direcciones->distrito->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_distrito" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_distrito" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_distrito" value="<?php echo ew_HtmlEncode($direcciones->distrito->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->zona->Visible) { // zona ?>
		<td data-name="zona"<?php echo $direcciones->zona->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_zona" class="form-group direcciones_zona">
<input type="text" data-table="direcciones" data-field="x_zona" name="x<?php echo $direcciones_grid->RowIndex ?>_zona" id="x<?php echo $direcciones_grid->RowIndex ?>_zona" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->zona->getPlaceHolder()) ?>" value="<?php echo $direcciones->zona->EditValue ?>"<?php echo $direcciones->zona->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_zona" name="o<?php echo $direcciones_grid->RowIndex ?>_zona" id="o<?php echo $direcciones_grid->RowIndex ?>_zona" value="<?php echo ew_HtmlEncode($direcciones->zona->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_zona" class="form-group direcciones_zona">
<input type="text" data-table="direcciones" data-field="x_zona" name="x<?php echo $direcciones_grid->RowIndex ?>_zona" id="x<?php echo $direcciones_grid->RowIndex ?>_zona" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->zona->getPlaceHolder()) ?>" value="<?php echo $direcciones->zona->EditValue ?>"<?php echo $direcciones->zona->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_zona" class="direcciones_zona">
<span<?php echo $direcciones->zona->ViewAttributes() ?>>
<?php echo $direcciones->zona->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_zona" name="x<?php echo $direcciones_grid->RowIndex ?>_zona" id="x<?php echo $direcciones_grid->RowIndex ?>_zona" value="<?php echo ew_HtmlEncode($direcciones->zona->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_zona" name="o<?php echo $direcciones_grid->RowIndex ?>_zona" id="o<?php echo $direcciones_grid->RowIndex ?>_zona" value="<?php echo ew_HtmlEncode($direcciones->zona->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_zona" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_zona" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_zona" value="<?php echo ew_HtmlEncode($direcciones->zona->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_zona" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_zona" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_zona" value="<?php echo ew_HtmlEncode($direcciones->zona->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->direccion1->Visible) { // direccion1 ?>
		<td data-name="direccion1"<?php echo $direcciones->direccion1->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion1" class="form-group direcciones_direccion1">
<input type="text" data-table="direcciones" data-field="x_direccion1" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion1" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion1" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion1->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion1->EditValue ?>"<?php echo $direcciones->direccion1->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_direccion1" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion1" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion1" value="<?php echo ew_HtmlEncode($direcciones->direccion1->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion1" class="form-group direcciones_direccion1">
<input type="text" data-table="direcciones" data-field="x_direccion1" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion1" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion1" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion1->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion1->EditValue ?>"<?php echo $direcciones->direccion1->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion1" class="direcciones_direccion1">
<span<?php echo $direcciones->direccion1->ViewAttributes() ?>>
<?php echo $direcciones->direccion1->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion1" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion1" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion1" value="<?php echo ew_HtmlEncode($direcciones->direccion1->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_direccion1" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion1" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion1" value="<?php echo ew_HtmlEncode($direcciones->direccion1->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion1" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_direccion1" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_direccion1" value="<?php echo ew_HtmlEncode($direcciones->direccion1->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_direccion1" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_direccion1" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_direccion1" value="<?php echo ew_HtmlEncode($direcciones->direccion1->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->direccion2->Visible) { // direccion2 ?>
		<td data-name="direccion2"<?php echo $direcciones->direccion2->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion2" class="form-group direcciones_direccion2">
<input type="text" data-table="direcciones" data-field="x_direccion2" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion2" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion2->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion2->EditValue ?>"<?php echo $direcciones->direccion2->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_direccion2" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion2" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion2" value="<?php echo ew_HtmlEncode($direcciones->direccion2->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion2" class="form-group direcciones_direccion2">
<input type="text" data-table="direcciones" data-field="x_direccion2" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion2" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion2->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion2->EditValue ?>"<?php echo $direcciones->direccion2->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion2" class="direcciones_direccion2">
<span<?php echo $direcciones->direccion2->ViewAttributes() ?>>
<?php echo $direcciones->direccion2->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion2" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion2" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion2" value="<?php echo ew_HtmlEncode($direcciones->direccion2->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_direccion2" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion2" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion2" value="<?php echo ew_HtmlEncode($direcciones->direccion2->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion2" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_direccion2" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_direccion2" value="<?php echo ew_HtmlEncode($direcciones->direccion2->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_direccion2" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_direccion2" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_direccion2" value="<?php echo ew_HtmlEncode($direcciones->direccion2->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->direccion3->Visible) { // direccion3 ?>
		<td data-name="direccion3"<?php echo $direcciones->direccion3->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion3" class="form-group direcciones_direccion3">
<input type="text" data-table="direcciones" data-field="x_direccion3" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion3" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion3" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion3->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion3->EditValue ?>"<?php echo $direcciones->direccion3->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_direccion3" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion3" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion3" value="<?php echo ew_HtmlEncode($direcciones->direccion3->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion3" class="form-group direcciones_direccion3">
<input type="text" data-table="direcciones" data-field="x_direccion3" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion3" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion3" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion3->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion3->EditValue ?>"<?php echo $direcciones->direccion3->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion3" class="direcciones_direccion3">
<span<?php echo $direcciones->direccion3->ViewAttributes() ?>>
<?php echo $direcciones->direccion3->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion3" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion3" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion3" value="<?php echo ew_HtmlEncode($direcciones->direccion3->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_direccion3" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion3" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion3" value="<?php echo ew_HtmlEncode($direcciones->direccion3->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion3" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_direccion3" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_direccion3" value="<?php echo ew_HtmlEncode($direcciones->direccion3->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_direccion3" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_direccion3" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_direccion3" value="<?php echo ew_HtmlEncode($direcciones->direccion3->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->direccion4->Visible) { // direccion4 ?>
		<td data-name="direccion4"<?php echo $direcciones->direccion4->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion4" class="form-group direcciones_direccion4">
<input type="text" data-table="direcciones" data-field="x_direccion4" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion4" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion4" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion4->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion4->EditValue ?>"<?php echo $direcciones->direccion4->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_direccion4" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion4" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion4" value="<?php echo ew_HtmlEncode($direcciones->direccion4->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion4" class="form-group direcciones_direccion4">
<input type="text" data-table="direcciones" data-field="x_direccion4" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion4" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion4" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion4->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion4->EditValue ?>"<?php echo $direcciones->direccion4->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_direccion4" class="direcciones_direccion4">
<span<?php echo $direcciones->direccion4->ViewAttributes() ?>>
<?php echo $direcciones->direccion4->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion4" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion4" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion4" value="<?php echo ew_HtmlEncode($direcciones->direccion4->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_direccion4" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion4" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion4" value="<?php echo ew_HtmlEncode($direcciones->direccion4->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion4" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_direccion4" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_direccion4" value="<?php echo ew_HtmlEncode($direcciones->direccion4->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_direccion4" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_direccion4" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_direccion4" value="<?php echo ew_HtmlEncode($direcciones->direccion4->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->mapa->Visible) { // mapa ?>
		<td data-name="mapa"<?php echo $direcciones->mapa->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_mapa" class="form-group direcciones_mapa">
<input type="text" data-table="direcciones" data-field="x_mapa" name="x<?php echo $direcciones_grid->RowIndex ?>_mapa" id="x<?php echo $direcciones_grid->RowIndex ?>_mapa" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->mapa->getPlaceHolder()) ?>" value="<?php echo $direcciones->mapa->EditValue ?>"<?php echo $direcciones->mapa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_mapa" name="o<?php echo $direcciones_grid->RowIndex ?>_mapa" id="o<?php echo $direcciones_grid->RowIndex ?>_mapa" value="<?php echo ew_HtmlEncode($direcciones->mapa->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_mapa" class="form-group direcciones_mapa">
<input type="text" data-table="direcciones" data-field="x_mapa" name="x<?php echo $direcciones_grid->RowIndex ?>_mapa" id="x<?php echo $direcciones_grid->RowIndex ?>_mapa" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->mapa->getPlaceHolder()) ?>" value="<?php echo $direcciones->mapa->EditValue ?>"<?php echo $direcciones->mapa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<script id="orig<?php echo $direcciones_grid->RowCnt ?>_direcciones_mapa" type="text/html">
<?php echo $direcciones->mapa->ListViewValue() ?>
</script>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_mapa" class="direcciones_mapa">
<span<?php echo $direcciones->mapa->ViewAttributes() ?>><script type="text/javascript">
ewGoogleMaps[ewGoogleMaps.length] = jQuery.extend({"id":"gm_direcciones_x_mapa","name":"Google Maps","apikey":"AIzaSyDFibhqbazLZqySy6EuVE_BHRUvkhyIVLg","width":400,"width_field":null,"height":400,"height_field":null,"latitude":null,"latitude_field":"latitud","longitude":null,"longitude_field":"longitud","address":null,"address_field":null,"type":"HYBRID","type_field":null,"zoom":18,"zoom_field":null,"title":null,"title_field":"direccion","icon":null,"icon_field":null,"description":null,"description_field":null,"use_single_map":true,"single_map_width":400,"single_map_height":400,"show_map_on_top":true,"show_all_markers":true,"geocoding_delay":250,"use_marker_clusterer":false,"cluster_max_zoom":-1,"cluster_grid_size":-1,"cluster_styles":-1,"template_id":"orig<?php echo $direcciones_grid->RowCnt ?>_direcciones_mapa"}, {
	latitude: <?php echo ew_VarToJson($direcciones->latitud->CurrentValue, "undefined") ?>,
	longitude: <?php echo ew_VarToJson($direcciones->longitud->CurrentValue, "undefined") ?>
});
</script>
</span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_mapa" name="x<?php echo $direcciones_grid->RowIndex ?>_mapa" id="x<?php echo $direcciones_grid->RowIndex ?>_mapa" value="<?php echo ew_HtmlEncode($direcciones->mapa->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_mapa" name="o<?php echo $direcciones_grid->RowIndex ?>_mapa" id="o<?php echo $direcciones_grid->RowIndex ?>_mapa" value="<?php echo ew_HtmlEncode($direcciones->mapa->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_mapa" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_mapa" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_mapa" value="<?php echo ew_HtmlEncode($direcciones->mapa->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_mapa" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_mapa" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_mapa" value="<?php echo ew_HtmlEncode($direcciones->mapa->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->longitud->Visible) { // longitud ?>
		<td data-name="longitud"<?php echo $direcciones->longitud->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_longitud" class="form-group direcciones_longitud">
<input type="text" data-table="direcciones" data-field="x_longitud" name="x<?php echo $direcciones_grid->RowIndex ?>_longitud" id="x<?php echo $direcciones_grid->RowIndex ?>_longitud" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->longitud->getPlaceHolder()) ?>" value="<?php echo $direcciones->longitud->EditValue ?>"<?php echo $direcciones->longitud->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_longitud" name="o<?php echo $direcciones_grid->RowIndex ?>_longitud" id="o<?php echo $direcciones_grid->RowIndex ?>_longitud" value="<?php echo ew_HtmlEncode($direcciones->longitud->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_longitud" class="form-group direcciones_longitud">
<input type="text" data-table="direcciones" data-field="x_longitud" name="x<?php echo $direcciones_grid->RowIndex ?>_longitud" id="x<?php echo $direcciones_grid->RowIndex ?>_longitud" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->longitud->getPlaceHolder()) ?>" value="<?php echo $direcciones->longitud->EditValue ?>"<?php echo $direcciones->longitud->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_longitud" class="direcciones_longitud">
<span<?php echo $direcciones->longitud->ViewAttributes() ?>>
<?php echo $direcciones->longitud->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_longitud" name="x<?php echo $direcciones_grid->RowIndex ?>_longitud" id="x<?php echo $direcciones_grid->RowIndex ?>_longitud" value="<?php echo ew_HtmlEncode($direcciones->longitud->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_longitud" name="o<?php echo $direcciones_grid->RowIndex ?>_longitud" id="o<?php echo $direcciones_grid->RowIndex ?>_longitud" value="<?php echo ew_HtmlEncode($direcciones->longitud->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_longitud" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_longitud" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_longitud" value="<?php echo ew_HtmlEncode($direcciones->longitud->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_longitud" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_longitud" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_longitud" value="<?php echo ew_HtmlEncode($direcciones->longitud->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($direcciones->latitud->Visible) { // latitud ?>
		<td data-name="latitud"<?php echo $direcciones->latitud->CellAttributes() ?>>
<?php if ($direcciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_latitud" class="form-group direcciones_latitud">
<input type="text" data-table="direcciones" data-field="x_latitud" name="x<?php echo $direcciones_grid->RowIndex ?>_latitud" id="x<?php echo $direcciones_grid->RowIndex ?>_latitud" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->latitud->getPlaceHolder()) ?>" value="<?php echo $direcciones->latitud->EditValue ?>"<?php echo $direcciones->latitud->EditAttributes() ?>>
</span>
<input type="hidden" data-table="direcciones" data-field="x_latitud" name="o<?php echo $direcciones_grid->RowIndex ?>_latitud" id="o<?php echo $direcciones_grid->RowIndex ?>_latitud" value="<?php echo ew_HtmlEncode($direcciones->latitud->OldValue) ?>">
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_latitud" class="form-group direcciones_latitud">
<input type="text" data-table="direcciones" data-field="x_latitud" name="x<?php echo $direcciones_grid->RowIndex ?>_latitud" id="x<?php echo $direcciones_grid->RowIndex ?>_latitud" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->latitud->getPlaceHolder()) ?>" value="<?php echo $direcciones->latitud->EditValue ?>"<?php echo $direcciones->latitud->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($direcciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $direcciones_grid->RowCnt ?>_direcciones_latitud" class="direcciones_latitud">
<span<?php echo $direcciones->latitud->ViewAttributes() ?>>
<?php echo $direcciones->latitud->ListViewValue() ?></span>
</span>
<?php if ($direcciones->CurrentAction <> "F") { ?>
<input type="hidden" data-table="direcciones" data-field="x_latitud" name="x<?php echo $direcciones_grid->RowIndex ?>_latitud" id="x<?php echo $direcciones_grid->RowIndex ?>_latitud" value="<?php echo ew_HtmlEncode($direcciones->latitud->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_latitud" name="o<?php echo $direcciones_grid->RowIndex ?>_latitud" id="o<?php echo $direcciones_grid->RowIndex ?>_latitud" value="<?php echo ew_HtmlEncode($direcciones->latitud->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="direcciones" data-field="x_latitud" name="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_latitud" id="fdireccionesgrid$x<?php echo $direcciones_grid->RowIndex ?>_latitud" value="<?php echo ew_HtmlEncode($direcciones->latitud->FormValue) ?>">
<input type="hidden" data-table="direcciones" data-field="x_latitud" name="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_latitud" id="fdireccionesgrid$o<?php echo $direcciones_grid->RowIndex ?>_latitud" value="<?php echo ew_HtmlEncode($direcciones->latitud->OldValue) ?>">
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
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $direcciones_grid->RowIndex ?>_id_persona"><?php echo (strval($direcciones->id_persona->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $direcciones->id_persona->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($direcciones->id_persona->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $direcciones_grid->RowIndex ?>_id_persona',m:0,n:30});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="direcciones" data-field="x_id_persona" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $direcciones->id_persona->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" id="x<?php echo $direcciones_grid->RowIndex ?>_id_persona" value="<?php echo $direcciones->id_persona->CurrentValue ?>"<?php echo $direcciones->id_persona->EditAttributes() ?>>
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
	<?php if ($direcciones->id_fuente->Visible) { // id_fuente ?>
		<td data-name="id_fuente">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_id_fuente" class="form-group direcciones_id_fuente">
<select data-table="direcciones" data-field="x_id_fuente" data-value-separator="<?php echo $direcciones->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $direcciones_grid->RowIndex ?>_id_fuente" name="x<?php echo $direcciones_grid->RowIndex ?>_id_fuente"<?php echo $direcciones->id_fuente->EditAttributes() ?>>
<?php echo $direcciones->id_fuente->SelectOptionListHtml("x<?php echo $direcciones_grid->RowIndex ?>_id_fuente") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_id_fuente" class="form-group direcciones_id_fuente">
<span<?php echo $direcciones->id_fuente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->id_fuente->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_id_fuente" name="x<?php echo $direcciones_grid->RowIndex ?>_id_fuente" id="x<?php echo $direcciones_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($direcciones->id_fuente->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_id_fuente" name="o<?php echo $direcciones_grid->RowIndex ?>_id_fuente" id="o<?php echo $direcciones_grid->RowIndex ?>_id_fuente" value="<?php echo ew_HtmlEncode($direcciones->id_fuente->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->id_gestion->Visible) { // id_gestion ?>
		<td data-name="id_gestion">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_id_gestion" class="form-group direcciones_id_gestion">
<select data-table="direcciones" data-field="x_id_gestion" data-value-separator="<?php echo $direcciones->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $direcciones_grid->RowIndex ?>_id_gestion" name="x<?php echo $direcciones_grid->RowIndex ?>_id_gestion"<?php echo $direcciones->id_gestion->EditAttributes() ?>>
<?php echo $direcciones->id_gestion->SelectOptionListHtml("x<?php echo $direcciones_grid->RowIndex ?>_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$direcciones->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $direcciones->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $direcciones_grid->RowIndex ?>_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $direcciones_grid->RowIndex ?>_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $direcciones->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_id_gestion" class="form-group direcciones_id_gestion">
<span<?php echo $direcciones->id_gestion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->id_gestion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_id_gestion" name="x<?php echo $direcciones_grid->RowIndex ?>_id_gestion" id="x<?php echo $direcciones_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($direcciones->id_gestion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_id_gestion" name="o<?php echo $direcciones_grid->RowIndex ?>_id_gestion" id="o<?php echo $direcciones_grid->RowIndex ?>_id_gestion" value="<?php echo ew_HtmlEncode($direcciones->id_gestion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->id_tipodireccion->Visible) { // id_tipodireccion ?>
		<td data-name="id_tipodireccion">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_id_tipodireccion" class="form-group direcciones_id_tipodireccion">
<select data-table="direcciones" data-field="x_id_tipodireccion" data-value-separator="<?php echo $direcciones->id_tipodireccion->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" name="x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion"<?php echo $direcciones->id_tipodireccion->EditAttributes() ?>>
<?php echo $direcciones->id_tipodireccion->SelectOptionListHtml("x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipo_direccion") && !$direcciones->id_tipodireccion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $direcciones->id_tipodireccion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion',url:'tipo_direccionaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $direcciones->id_tipodireccion->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_id_tipodireccion" class="form-group direcciones_id_tipodireccion">
<span<?php echo $direcciones->id_tipodireccion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->id_tipodireccion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_id_tipodireccion" name="x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" id="x<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" value="<?php echo ew_HtmlEncode($direcciones->id_tipodireccion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_id_tipodireccion" name="o<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" id="o<?php echo $direcciones_grid->RowIndex ?>_id_tipodireccion" value="<?php echo ew_HtmlEncode($direcciones->id_tipodireccion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->tipo_documento->Visible) { // tipo_documento ?>
		<td data-name="tipo_documento">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_tipo_documento" class="form-group direcciones_tipo_documento">
<input type="text" data-table="direcciones" data-field="x_tipo_documento" name="x<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" id="x<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($direcciones->tipo_documento->getPlaceHolder()) ?>" value="<?php echo $direcciones->tipo_documento->EditValue ?>"<?php echo $direcciones->tipo_documento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_tipo_documento" class="form-group direcciones_tipo_documento">
<span<?php echo $direcciones->tipo_documento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->tipo_documento->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_tipo_documento" name="x<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" id="x<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($direcciones->tipo_documento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_tipo_documento" name="o<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" id="o<?php echo $direcciones_grid->RowIndex ?>_tipo_documento" value="<?php echo ew_HtmlEncode($direcciones->tipo_documento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->no_documento->Visible) { // no_documento ?>
		<td data-name="no_documento">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_no_documento" class="form-group direcciones_no_documento">
<input type="text" data-table="direcciones" data-field="x_no_documento" name="x<?php echo $direcciones_grid->RowIndex ?>_no_documento" id="x<?php echo $direcciones_grid->RowIndex ?>_no_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->no_documento->getPlaceHolder()) ?>" value="<?php echo $direcciones->no_documento->EditValue ?>"<?php echo $direcciones->no_documento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_no_documento" class="form-group direcciones_no_documento">
<span<?php echo $direcciones->no_documento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->no_documento->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_no_documento" name="x<?php echo $direcciones_grid->RowIndex ?>_no_documento" id="x<?php echo $direcciones_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($direcciones->no_documento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_no_documento" name="o<?php echo $direcciones_grid->RowIndex ?>_no_documento" id="o<?php echo $direcciones_grid->RowIndex ?>_no_documento" value="<?php echo ew_HtmlEncode($direcciones->no_documento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->nombres->Visible) { // nombres ?>
		<td data-name="nombres">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_nombres" class="form-group direcciones_nombres">
<input type="text" data-table="direcciones" data-field="x_nombres" name="x<?php echo $direcciones_grid->RowIndex ?>_nombres" id="x<?php echo $direcciones_grid->RowIndex ?>_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->nombres->getPlaceHolder()) ?>" value="<?php echo $direcciones->nombres->EditValue ?>"<?php echo $direcciones->nombres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_nombres" class="form-group direcciones_nombres">
<span<?php echo $direcciones->nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->nombres->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_nombres" name="x<?php echo $direcciones_grid->RowIndex ?>_nombres" id="x<?php echo $direcciones_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($direcciones->nombres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_nombres" name="o<?php echo $direcciones_grid->RowIndex ?>_nombres" id="o<?php echo $direcciones_grid->RowIndex ?>_nombres" value="<?php echo ew_HtmlEncode($direcciones->nombres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->paterno->Visible) { // paterno ?>
		<td data-name="paterno">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_paterno" class="form-group direcciones_paterno">
<input type="text" data-table="direcciones" data-field="x_paterno" name="x<?php echo $direcciones_grid->RowIndex ?>_paterno" id="x<?php echo $direcciones_grid->RowIndex ?>_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->paterno->getPlaceHolder()) ?>" value="<?php echo $direcciones->paterno->EditValue ?>"<?php echo $direcciones->paterno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_paterno" class="form-group direcciones_paterno">
<span<?php echo $direcciones->paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->paterno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_paterno" name="x<?php echo $direcciones_grid->RowIndex ?>_paterno" id="x<?php echo $direcciones_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($direcciones->paterno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_paterno" name="o<?php echo $direcciones_grid->RowIndex ?>_paterno" id="o<?php echo $direcciones_grid->RowIndex ?>_paterno" value="<?php echo ew_HtmlEncode($direcciones->paterno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->materno->Visible) { // materno ?>
		<td data-name="materno">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_materno" class="form-group direcciones_materno">
<input type="text" data-table="direcciones" data-field="x_materno" name="x<?php echo $direcciones_grid->RowIndex ?>_materno" id="x<?php echo $direcciones_grid->RowIndex ?>_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->materno->getPlaceHolder()) ?>" value="<?php echo $direcciones->materno->EditValue ?>"<?php echo $direcciones->materno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_materno" class="form-group direcciones_materno">
<span<?php echo $direcciones->materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->materno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_materno" name="x<?php echo $direcciones_grid->RowIndex ?>_materno" id="x<?php echo $direcciones_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($direcciones->materno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_materno" name="o<?php echo $direcciones_grid->RowIndex ?>_materno" id="o<?php echo $direcciones_grid->RowIndex ?>_materno" value="<?php echo ew_HtmlEncode($direcciones->materno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->pais->Visible) { // pais ?>
		<td data-name="pais">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_pais" class="form-group direcciones_pais">
<input type="text" data-table="direcciones" data-field="x_pais" name="x<?php echo $direcciones_grid->RowIndex ?>_pais" id="x<?php echo $direcciones_grid->RowIndex ?>_pais" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->pais->getPlaceHolder()) ?>" value="<?php echo $direcciones->pais->EditValue ?>"<?php echo $direcciones->pais->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_pais" class="form-group direcciones_pais">
<span<?php echo $direcciones->pais->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->pais->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_pais" name="x<?php echo $direcciones_grid->RowIndex ?>_pais" id="x<?php echo $direcciones_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($direcciones->pais->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_pais" name="o<?php echo $direcciones_grid->RowIndex ?>_pais" id="o<?php echo $direcciones_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($direcciones->pais->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->departamento->Visible) { // departamento ?>
		<td data-name="departamento">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_departamento" class="form-group direcciones_departamento">
<input type="text" data-table="direcciones" data-field="x_departamento" name="x<?php echo $direcciones_grid->RowIndex ?>_departamento" id="x<?php echo $direcciones_grid->RowIndex ?>_departamento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->departamento->getPlaceHolder()) ?>" value="<?php echo $direcciones->departamento->EditValue ?>"<?php echo $direcciones->departamento->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_departamento" class="form-group direcciones_departamento">
<span<?php echo $direcciones->departamento->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->departamento->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_departamento" name="x<?php echo $direcciones_grid->RowIndex ?>_departamento" id="x<?php echo $direcciones_grid->RowIndex ?>_departamento" value="<?php echo ew_HtmlEncode($direcciones->departamento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_departamento" name="o<?php echo $direcciones_grid->RowIndex ?>_departamento" id="o<?php echo $direcciones_grid->RowIndex ?>_departamento" value="<?php echo ew_HtmlEncode($direcciones->departamento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->provincia->Visible) { // provincia ?>
		<td data-name="provincia">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_provincia" class="form-group direcciones_provincia">
<input type="text" data-table="direcciones" data-field="x_provincia" name="x<?php echo $direcciones_grid->RowIndex ?>_provincia" id="x<?php echo $direcciones_grid->RowIndex ?>_provincia" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->provincia->getPlaceHolder()) ?>" value="<?php echo $direcciones->provincia->EditValue ?>"<?php echo $direcciones->provincia->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_provincia" class="form-group direcciones_provincia">
<span<?php echo $direcciones->provincia->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->provincia->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_provincia" name="x<?php echo $direcciones_grid->RowIndex ?>_provincia" id="x<?php echo $direcciones_grid->RowIndex ?>_provincia" value="<?php echo ew_HtmlEncode($direcciones->provincia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_provincia" name="o<?php echo $direcciones_grid->RowIndex ?>_provincia" id="o<?php echo $direcciones_grid->RowIndex ?>_provincia" value="<?php echo ew_HtmlEncode($direcciones->provincia->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->municipio->Visible) { // municipio ?>
		<td data-name="municipio">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_municipio" class="form-group direcciones_municipio">
<input type="text" data-table="direcciones" data-field="x_municipio" name="x<?php echo $direcciones_grid->RowIndex ?>_municipio" id="x<?php echo $direcciones_grid->RowIndex ?>_municipio" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->municipio->getPlaceHolder()) ?>" value="<?php echo $direcciones->municipio->EditValue ?>"<?php echo $direcciones->municipio->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_municipio" class="form-group direcciones_municipio">
<span<?php echo $direcciones->municipio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->municipio->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_municipio" name="x<?php echo $direcciones_grid->RowIndex ?>_municipio" id="x<?php echo $direcciones_grid->RowIndex ?>_municipio" value="<?php echo ew_HtmlEncode($direcciones->municipio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_municipio" name="o<?php echo $direcciones_grid->RowIndex ?>_municipio" id="o<?php echo $direcciones_grid->RowIndex ?>_municipio" value="<?php echo ew_HtmlEncode($direcciones->municipio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->localidad->Visible) { // localidad ?>
		<td data-name="localidad">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_localidad" class="form-group direcciones_localidad">
<input type="text" data-table="direcciones" data-field="x_localidad" name="x<?php echo $direcciones_grid->RowIndex ?>_localidad" id="x<?php echo $direcciones_grid->RowIndex ?>_localidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->localidad->getPlaceHolder()) ?>" value="<?php echo $direcciones->localidad->EditValue ?>"<?php echo $direcciones->localidad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_localidad" class="form-group direcciones_localidad">
<span<?php echo $direcciones->localidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->localidad->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_localidad" name="x<?php echo $direcciones_grid->RowIndex ?>_localidad" id="x<?php echo $direcciones_grid->RowIndex ?>_localidad" value="<?php echo ew_HtmlEncode($direcciones->localidad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_localidad" name="o<?php echo $direcciones_grid->RowIndex ?>_localidad" id="o<?php echo $direcciones_grid->RowIndex ?>_localidad" value="<?php echo ew_HtmlEncode($direcciones->localidad->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->distrito->Visible) { // distrito ?>
		<td data-name="distrito">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_distrito" class="form-group direcciones_distrito">
<input type="text" data-table="direcciones" data-field="x_distrito" name="x<?php echo $direcciones_grid->RowIndex ?>_distrito" id="x<?php echo $direcciones_grid->RowIndex ?>_distrito" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->distrito->getPlaceHolder()) ?>" value="<?php echo $direcciones->distrito->EditValue ?>"<?php echo $direcciones->distrito->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_distrito" class="form-group direcciones_distrito">
<span<?php echo $direcciones->distrito->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->distrito->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_distrito" name="x<?php echo $direcciones_grid->RowIndex ?>_distrito" id="x<?php echo $direcciones_grid->RowIndex ?>_distrito" value="<?php echo ew_HtmlEncode($direcciones->distrito->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_distrito" name="o<?php echo $direcciones_grid->RowIndex ?>_distrito" id="o<?php echo $direcciones_grid->RowIndex ?>_distrito" value="<?php echo ew_HtmlEncode($direcciones->distrito->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->zona->Visible) { // zona ?>
		<td data-name="zona">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_zona" class="form-group direcciones_zona">
<input type="text" data-table="direcciones" data-field="x_zona" name="x<?php echo $direcciones_grid->RowIndex ?>_zona" id="x<?php echo $direcciones_grid->RowIndex ?>_zona" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->zona->getPlaceHolder()) ?>" value="<?php echo $direcciones->zona->EditValue ?>"<?php echo $direcciones->zona->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_zona" class="form-group direcciones_zona">
<span<?php echo $direcciones->zona->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->zona->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_zona" name="x<?php echo $direcciones_grid->RowIndex ?>_zona" id="x<?php echo $direcciones_grid->RowIndex ?>_zona" value="<?php echo ew_HtmlEncode($direcciones->zona->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_zona" name="o<?php echo $direcciones_grid->RowIndex ?>_zona" id="o<?php echo $direcciones_grid->RowIndex ?>_zona" value="<?php echo ew_HtmlEncode($direcciones->zona->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->direccion1->Visible) { // direccion1 ?>
		<td data-name="direccion1">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_direccion1" class="form-group direcciones_direccion1">
<input type="text" data-table="direcciones" data-field="x_direccion1" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion1" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion1" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion1->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion1->EditValue ?>"<?php echo $direcciones->direccion1->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_direccion1" class="form-group direcciones_direccion1">
<span<?php echo $direcciones->direccion1->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->direccion1->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_direccion1" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion1" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion1" value="<?php echo ew_HtmlEncode($direcciones->direccion1->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion1" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion1" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion1" value="<?php echo ew_HtmlEncode($direcciones->direccion1->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->direccion2->Visible) { // direccion2 ?>
		<td data-name="direccion2">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_direccion2" class="form-group direcciones_direccion2">
<input type="text" data-table="direcciones" data-field="x_direccion2" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion2" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion2->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion2->EditValue ?>"<?php echo $direcciones->direccion2->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_direccion2" class="form-group direcciones_direccion2">
<span<?php echo $direcciones->direccion2->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->direccion2->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_direccion2" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion2" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion2" value="<?php echo ew_HtmlEncode($direcciones->direccion2->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion2" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion2" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion2" value="<?php echo ew_HtmlEncode($direcciones->direccion2->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->direccion3->Visible) { // direccion3 ?>
		<td data-name="direccion3">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_direccion3" class="form-group direcciones_direccion3">
<input type="text" data-table="direcciones" data-field="x_direccion3" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion3" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion3" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion3->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion3->EditValue ?>"<?php echo $direcciones->direccion3->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_direccion3" class="form-group direcciones_direccion3">
<span<?php echo $direcciones->direccion3->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->direccion3->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_direccion3" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion3" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion3" value="<?php echo ew_HtmlEncode($direcciones->direccion3->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion3" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion3" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion3" value="<?php echo ew_HtmlEncode($direcciones->direccion3->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->direccion4->Visible) { // direccion4 ?>
		<td data-name="direccion4">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_direccion4" class="form-group direcciones_direccion4">
<input type="text" data-table="direcciones" data-field="x_direccion4" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion4" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion4" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion4->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion4->EditValue ?>"<?php echo $direcciones->direccion4->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_direccion4" class="form-group direcciones_direccion4">
<span<?php echo $direcciones->direccion4->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->direccion4->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_direccion4" name="x<?php echo $direcciones_grid->RowIndex ?>_direccion4" id="x<?php echo $direcciones_grid->RowIndex ?>_direccion4" value="<?php echo ew_HtmlEncode($direcciones->direccion4->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_direccion4" name="o<?php echo $direcciones_grid->RowIndex ?>_direccion4" id="o<?php echo $direcciones_grid->RowIndex ?>_direccion4" value="<?php echo ew_HtmlEncode($direcciones->direccion4->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->mapa->Visible) { // mapa ?>
		<td data-name="mapa">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_mapa" class="form-group direcciones_mapa">
<input type="text" data-table="direcciones" data-field="x_mapa" name="x<?php echo $direcciones_grid->RowIndex ?>_mapa" id="x<?php echo $direcciones_grid->RowIndex ?>_mapa" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->mapa->getPlaceHolder()) ?>" value="<?php echo $direcciones->mapa->EditValue ?>"<?php echo $direcciones->mapa->EditAttributes() ?>>
</span>
<?php } else { ?>
<script id="orig<?php echo $direcciones_grid->RowCnt ?>_direcciones_mapa" type="text/html">
<p class="form-control-static"><?php echo $direcciones->mapa->ViewValue ?></p>
</script>
<span id="el$rowindex$_direcciones_mapa" class="form-group direcciones_mapa">
<span<?php echo $direcciones->mapa->ViewAttributes() ?>><script type="text/javascript">
ewGoogleMaps[ewGoogleMaps.length] = jQuery.extend({"id":"gm_direcciones_x_mapa","name":"Google Maps","apikey":"AIzaSyDFibhqbazLZqySy6EuVE_BHRUvkhyIVLg","width":400,"width_field":null,"height":400,"height_field":null,"latitude":null,"latitude_field":"latitud","longitude":null,"longitude_field":"longitud","address":null,"address_field":null,"type":"HYBRID","type_field":null,"zoom":18,"zoom_field":null,"title":null,"title_field":"direccion","icon":null,"icon_field":null,"description":null,"description_field":null,"use_single_map":true,"single_map_width":400,"single_map_height":400,"show_map_on_top":true,"show_all_markers":true,"geocoding_delay":250,"use_marker_clusterer":false,"cluster_max_zoom":-1,"cluster_grid_size":-1,"cluster_styles":-1,"template_id":"orig<?php echo $direcciones_grid->RowCnt ?>_direcciones_mapa"}, {
	latitude: <?php echo ew_VarToJson($direcciones->latitud->CurrentValue, "undefined") ?>,
	longitude: <?php echo ew_VarToJson($direcciones->longitud->CurrentValue, "undefined") ?>
});
</script>
</span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_mapa" name="x<?php echo $direcciones_grid->RowIndex ?>_mapa" id="x<?php echo $direcciones_grid->RowIndex ?>_mapa" value="<?php echo ew_HtmlEncode($direcciones->mapa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_mapa" name="o<?php echo $direcciones_grid->RowIndex ?>_mapa" id="o<?php echo $direcciones_grid->RowIndex ?>_mapa" value="<?php echo ew_HtmlEncode($direcciones->mapa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->longitud->Visible) { // longitud ?>
		<td data-name="longitud">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_longitud" class="form-group direcciones_longitud">
<input type="text" data-table="direcciones" data-field="x_longitud" name="x<?php echo $direcciones_grid->RowIndex ?>_longitud" id="x<?php echo $direcciones_grid->RowIndex ?>_longitud" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->longitud->getPlaceHolder()) ?>" value="<?php echo $direcciones->longitud->EditValue ?>"<?php echo $direcciones->longitud->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_longitud" class="form-group direcciones_longitud">
<span<?php echo $direcciones->longitud->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->longitud->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_longitud" name="x<?php echo $direcciones_grid->RowIndex ?>_longitud" id="x<?php echo $direcciones_grid->RowIndex ?>_longitud" value="<?php echo ew_HtmlEncode($direcciones->longitud->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_longitud" name="o<?php echo $direcciones_grid->RowIndex ?>_longitud" id="o<?php echo $direcciones_grid->RowIndex ?>_longitud" value="<?php echo ew_HtmlEncode($direcciones->longitud->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($direcciones->latitud->Visible) { // latitud ?>
		<td data-name="latitud">
<?php if ($direcciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_direcciones_latitud" class="form-group direcciones_latitud">
<input type="text" data-table="direcciones" data-field="x_latitud" name="x<?php echo $direcciones_grid->RowIndex ?>_latitud" id="x<?php echo $direcciones_grid->RowIndex ?>_latitud" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->latitud->getPlaceHolder()) ?>" value="<?php echo $direcciones->latitud->EditValue ?>"<?php echo $direcciones->latitud->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_direcciones_latitud" class="form-group direcciones_latitud">
<span<?php echo $direcciones->latitud->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->latitud->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_latitud" name="x<?php echo $direcciones_grid->RowIndex ?>_latitud" id="x<?php echo $direcciones_grid->RowIndex ?>_latitud" value="<?php echo ew_HtmlEncode($direcciones->latitud->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="direcciones" data-field="x_latitud" name="o<?php echo $direcciones_grid->RowIndex ?>_latitud" id="o<?php echo $direcciones_grid->RowIndex ?>_latitud" value="<?php echo ew_HtmlEncode($direcciones->latitud->OldValue) ?>">
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
