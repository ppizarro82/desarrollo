<?php

// Global variable for table object
$direcciones = NULL;

//
// Table class for direcciones
//
class cdirecciones extends cTable {
	var $Id;
	var $id_fuente;
	var $id_gestion;
	var $id_tipodireccion;
	var $tipo_documento;
	var $no_documento;
	var $nombres;
	var $paterno;
	var $materno;
	var $pais;
	var $departamento;
	var $provincia;
	var $municipio;
	var $localidad;
	var $distrito;
	var $zona;
	var $direccion1;
	var $direccion2;
	var $direccion3;
	var $direccion4;
	var $mapa;
	var $longitud;
	var $latitud;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'direcciones';
		$this->TableName = 'direcciones';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`direcciones`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// Id
		$this->Id = new cField('direcciones', 'direcciones', 'x_Id', 'Id', '`Id`', '`Id`', 3, -1, FALSE, '`Id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->Id->Sortable = TRUE; // Allow sort
		$this->Id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Id'] = &$this->Id;

		// id_fuente
		$this->id_fuente = new cField('direcciones', 'direcciones', 'x_id_fuente', 'id_fuente', '`id_fuente`', '`id_fuente`', 3, -1, FALSE, '`id_fuente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_fuente->Sortable = TRUE; // Allow sort
		$this->id_fuente->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_fuente->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_fuente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_fuente'] = &$this->id_fuente;

		// id_gestion
		$this->id_gestion = new cField('direcciones', 'direcciones', 'x_id_gestion', 'id_gestion', '`id_gestion`', '`id_gestion`', 3, -1, FALSE, '`id_gestion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_gestion->Sortable = TRUE; // Allow sort
		$this->id_gestion->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_gestion->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_gestion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_gestion'] = &$this->id_gestion;

		// id_tipodireccion
		$this->id_tipodireccion = new cField('direcciones', 'direcciones', 'x_id_tipodireccion', 'id_tipodireccion', '`id_tipodireccion`', '`id_tipodireccion`', 3, -1, FALSE, '`id_tipodireccion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_tipodireccion->Sortable = TRUE; // Allow sort
		$this->id_tipodireccion->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_tipodireccion->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['id_tipodireccion'] = &$this->id_tipodireccion;

		// tipo_documento
		$this->tipo_documento = new cField('direcciones', 'direcciones', 'x_tipo_documento', 'tipo_documento', '`tipo_documento`', '`tipo_documento`', 200, -1, FALSE, '`tipo_documento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tipo_documento->Sortable = TRUE; // Allow sort
		$this->fields['tipo_documento'] = &$this->tipo_documento;

		// no_documento
		$this->no_documento = new cField('direcciones', 'direcciones', 'x_no_documento', 'no_documento', '`no_documento`', '`no_documento`', 200, -1, FALSE, '`no_documento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_documento->Sortable = TRUE; // Allow sort
		$this->fields['no_documento'] = &$this->no_documento;

		// nombres
		$this->nombres = new cField('direcciones', 'direcciones', 'x_nombres', 'nombres', '`nombres`', '`nombres`', 200, -1, FALSE, '`nombres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombres->Sortable = TRUE; // Allow sort
		$this->fields['nombres'] = &$this->nombres;

		// paterno
		$this->paterno = new cField('direcciones', 'direcciones', 'x_paterno', 'paterno', '`paterno`', '`paterno`', 200, -1, FALSE, '`paterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->paterno->Sortable = TRUE; // Allow sort
		$this->fields['paterno'] = &$this->paterno;

		// materno
		$this->materno = new cField('direcciones', 'direcciones', 'x_materno', 'materno', '`materno`', '`materno`', 200, -1, FALSE, '`materno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->materno->Sortable = TRUE; // Allow sort
		$this->fields['materno'] = &$this->materno;

		// pais
		$this->pais = new cField('direcciones', 'direcciones', 'x_pais', 'pais', '`pais`', '`pais`', 200, -1, FALSE, '`pais`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pais->Sortable = TRUE; // Allow sort
		$this->fields['pais'] = &$this->pais;

		// departamento
		$this->departamento = new cField('direcciones', 'direcciones', 'x_departamento', 'departamento', '`departamento`', '`departamento`', 200, -1, FALSE, '`departamento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->departamento->Sortable = TRUE; // Allow sort
		$this->fields['departamento'] = &$this->departamento;

		// provincia
		$this->provincia = new cField('direcciones', 'direcciones', 'x_provincia', 'provincia', '`provincia`', '`provincia`', 200, -1, FALSE, '`provincia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->provincia->Sortable = TRUE; // Allow sort
		$this->fields['provincia'] = &$this->provincia;

		// municipio
		$this->municipio = new cField('direcciones', 'direcciones', 'x_municipio', 'municipio', '`municipio`', '`municipio`', 200, -1, FALSE, '`municipio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->municipio->Sortable = TRUE; // Allow sort
		$this->fields['municipio'] = &$this->municipio;

		// localidad
		$this->localidad = new cField('direcciones', 'direcciones', 'x_localidad', 'localidad', '`localidad`', '`localidad`', 200, -1, FALSE, '`localidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->localidad->Sortable = TRUE; // Allow sort
		$this->fields['localidad'] = &$this->localidad;

		// distrito
		$this->distrito = new cField('direcciones', 'direcciones', 'x_distrito', 'distrito', '`distrito`', '`distrito`', 200, -1, FALSE, '`distrito`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->distrito->Sortable = TRUE; // Allow sort
		$this->fields['distrito'] = &$this->distrito;

		// zona
		$this->zona = new cField('direcciones', 'direcciones', 'x_zona', 'zona', '`zona`', '`zona`', 200, -1, FALSE, '`zona`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->zona->Sortable = TRUE; // Allow sort
		$this->fields['zona'] = &$this->zona;

		// direccion1
		$this->direccion1 = new cField('direcciones', 'direcciones', 'x_direccion1', 'direccion1', '`direccion1`', '`direccion1`', 200, -1, FALSE, '`direccion1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->direccion1->Sortable = TRUE; // Allow sort
		$this->fields['direccion1'] = &$this->direccion1;

		// direccion2
		$this->direccion2 = new cField('direcciones', 'direcciones', 'x_direccion2', 'direccion2', '`direccion2`', '`direccion2`', 200, -1, FALSE, '`direccion2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->direccion2->Sortable = TRUE; // Allow sort
		$this->fields['direccion2'] = &$this->direccion2;

		// direccion3
		$this->direccion3 = new cField('direcciones', 'direcciones', 'x_direccion3', 'direccion3', '`direccion3`', '`direccion3`', 200, -1, FALSE, '`direccion3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->direccion3->Sortable = TRUE; // Allow sort
		$this->fields['direccion3'] = &$this->direccion3;

		// direccion4
		$this->direccion4 = new cField('direcciones', 'direcciones', 'x_direccion4', 'direccion4', '`direccion4`', '`direccion4`', 200, -1, FALSE, '`direccion4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->direccion4->Sortable = TRUE; // Allow sort
		$this->fields['direccion4'] = &$this->direccion4;

		// mapa
		$this->mapa = new cField('direcciones', 'direcciones', 'x_mapa', 'mapa', '`mapa`', '`mapa`', 200, -1, FALSE, '`mapa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mapa->Sortable = TRUE; // Allow sort
		$this->fields['mapa'] = &$this->mapa;

		// longitud
		$this->longitud = new cField('direcciones', 'direcciones', 'x_longitud', 'longitud', '`longitud`', '`longitud`', 200, -1, FALSE, '`longitud`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->longitud->Sortable = TRUE; // Allow sort
		$this->fields['longitud'] = &$this->longitud;

		// latitud
		$this->latitud = new cField('direcciones', 'direcciones', 'x_latitud', 'latitud', '`latitud`', '`latitud`', 200, -1, FALSE, '`latitud`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->latitud->Sortable = TRUE; // Allow sort
		$this->fields['latitud'] = &$this->latitud;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $this->LeftColumnClass);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`direcciones`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$sSql = $this->ListSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->Id->setDbValue($conn->Insert_ID());
			$rs['Id'] = $this->Id->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('Id', $rs))
				ew_AddFilter($where, ew_QuotedName('Id', $this->DBID) . '=' . ew_QuotedValue($rs['Id'], $this->Id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`Id` = @Id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->Id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->Id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@Id@", ew_AdjustSql($this->Id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "direccioneslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "direccionesview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "direccionesedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "direccionesadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "direccioneslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("direccionesview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("direccionesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "direccionesadd.php?" . $this->UrlParm($parm);
		else
			$url = "direccionesadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("direccionesedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("direccionesadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("direccionesdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "Id:" . ew_VarToJson($this->Id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->Id->CurrentValue)) {
			$sUrl .= "Id=" . urlencode($this->Id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["Id"]))
				$arKeys[] = $_POST["Id"];
			elseif (isset($_GET["Id"]))
				$arKeys[] = $_GET["Id"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->Id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->Id->setDbValue($rs->fields('Id'));
		$this->id_fuente->setDbValue($rs->fields('id_fuente'));
		$this->id_gestion->setDbValue($rs->fields('id_gestion'));
		$this->id_tipodireccion->setDbValue($rs->fields('id_tipodireccion'));
		$this->tipo_documento->setDbValue($rs->fields('tipo_documento'));
		$this->no_documento->setDbValue($rs->fields('no_documento'));
		$this->nombres->setDbValue($rs->fields('nombres'));
		$this->paterno->setDbValue($rs->fields('paterno'));
		$this->materno->setDbValue($rs->fields('materno'));
		$this->pais->setDbValue($rs->fields('pais'));
		$this->departamento->setDbValue($rs->fields('departamento'));
		$this->provincia->setDbValue($rs->fields('provincia'));
		$this->municipio->setDbValue($rs->fields('municipio'));
		$this->localidad->setDbValue($rs->fields('localidad'));
		$this->distrito->setDbValue($rs->fields('distrito'));
		$this->zona->setDbValue($rs->fields('zona'));
		$this->direccion1->setDbValue($rs->fields('direccion1'));
		$this->direccion2->setDbValue($rs->fields('direccion2'));
		$this->direccion3->setDbValue($rs->fields('direccion3'));
		$this->direccion4->setDbValue($rs->fields('direccion4'));
		$this->mapa->setDbValue($rs->fields('mapa'));
		$this->longitud->setDbValue($rs->fields('longitud'));
		$this->latitud->setDbValue($rs->fields('latitud'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// Id
		// id_fuente
		// id_gestion
		// id_tipodireccion
		// tipo_documento
		// no_documento
		// nombres
		// paterno
		// materno
		// pais
		// departamento
		// provincia
		// municipio
		// localidad
		// distrito
		// zona
		// direccion1
		// direccion2
		// direccion3
		// direccion4
		// mapa
		// longitud
		// latitud
		// Id

		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// id_fuente
		if (strval($this->id_fuente->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_fuente->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `fuentes`";
		$sWhereWrk = "";
		$this->id_fuente->LookupFilters = array();
		$lookuptblfilter = "`estado`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_fuente, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_fuente->ViewValue = $this->id_fuente->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_fuente->ViewValue = $this->id_fuente->CurrentValue;
			}
		} else {
			$this->id_fuente->ViewValue = NULL;
		}
		$this->id_fuente->ViewCustomAttributes = "";

		// id_gestion
		if (strval($this->id_gestion->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_gestion->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gestiones`";
		$sWhereWrk = "";
		$this->id_gestion->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_gestion, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_gestion->ViewValue = $this->id_gestion->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_gestion->ViewValue = $this->id_gestion->CurrentValue;
			}
		} else {
			$this->id_gestion->ViewValue = NULL;
		}
		$this->id_gestion->ViewCustomAttributes = "";

		// id_tipodireccion
		if (strval($this->id_tipodireccion->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_tipodireccion->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_direccion`";
		$sWhereWrk = "";
		$this->id_tipodireccion->LookupFilters = array();
		$lookuptblfilter = "`estado`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_tipodireccion, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_tipodireccion->ViewValue = $this->id_tipodireccion->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_tipodireccion->ViewValue = $this->id_tipodireccion->CurrentValue;
			}
		} else {
			$this->id_tipodireccion->ViewValue = NULL;
		}
		$this->id_tipodireccion->ViewCustomAttributes = "";

		// tipo_documento
		$this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
		$this->tipo_documento->ViewCustomAttributes = "";

		// no_documento
		$this->no_documento->ViewValue = $this->no_documento->CurrentValue;
		$this->no_documento->ViewCustomAttributes = "";

		// nombres
		$this->nombres->ViewValue = $this->nombres->CurrentValue;
		$this->nombres->ViewCustomAttributes = "";

		// paterno
		$this->paterno->ViewValue = $this->paterno->CurrentValue;
		$this->paterno->ViewCustomAttributes = "";

		// materno
		$this->materno->ViewValue = $this->materno->CurrentValue;
		$this->materno->ViewCustomAttributes = "";

		// pais
		$this->pais->ViewValue = $this->pais->CurrentValue;
		$this->pais->ViewCustomAttributes = "";

		// departamento
		$this->departamento->ViewValue = $this->departamento->CurrentValue;
		$this->departamento->ViewCustomAttributes = "";

		// provincia
		$this->provincia->ViewValue = $this->provincia->CurrentValue;
		$this->provincia->ViewCustomAttributes = "";

		// municipio
		$this->municipio->ViewValue = $this->municipio->CurrentValue;
		$this->municipio->ViewCustomAttributes = "";

		// localidad
		$this->localidad->ViewValue = $this->localidad->CurrentValue;
		$this->localidad->ViewCustomAttributes = "";

		// distrito
		$this->distrito->ViewValue = $this->distrito->CurrentValue;
		$this->distrito->ViewCustomAttributes = "";

		// zona
		$this->zona->ViewValue = $this->zona->CurrentValue;
		$this->zona->ViewCustomAttributes = "";

		// direccion1
		$this->direccion1->ViewValue = $this->direccion1->CurrentValue;
		$this->direccion1->ViewCustomAttributes = "";

		// direccion2
		$this->direccion2->ViewValue = $this->direccion2->CurrentValue;
		$this->direccion2->ViewCustomAttributes = "";

		// direccion3
		$this->direccion3->ViewValue = $this->direccion3->CurrentValue;
		$this->direccion3->ViewCustomAttributes = "";

		// direccion4
		$this->direccion4->ViewValue = $this->direccion4->CurrentValue;
		$this->direccion4->ViewCustomAttributes = "";

		// mapa
		$this->mapa->ViewValue = $this->mapa->CurrentValue;
		$this->mapa->ViewCustomAttributes = "";

		// longitud
		$this->longitud->ViewValue = $this->longitud->CurrentValue;
		$this->longitud->ViewCustomAttributes = "";

		// latitud
		$this->latitud->ViewValue = $this->latitud->CurrentValue;
		$this->latitud->ViewCustomAttributes = "";

		// Id
		$this->Id->LinkCustomAttributes = "";
		$this->Id->HrefValue = "";
		$this->Id->TooltipValue = "";

		// id_fuente
		$this->id_fuente->LinkCustomAttributes = "";
		$this->id_fuente->HrefValue = "";
		$this->id_fuente->TooltipValue = "";

		// id_gestion
		$this->id_gestion->LinkCustomAttributes = "";
		$this->id_gestion->HrefValue = "";
		$this->id_gestion->TooltipValue = "";

		// id_tipodireccion
		$this->id_tipodireccion->LinkCustomAttributes = "";
		$this->id_tipodireccion->HrefValue = "";
		$this->id_tipodireccion->TooltipValue = "";

		// tipo_documento
		$this->tipo_documento->LinkCustomAttributes = "";
		$this->tipo_documento->HrefValue = "";
		$this->tipo_documento->TooltipValue = "";

		// no_documento
		$this->no_documento->LinkCustomAttributes = "";
		$this->no_documento->HrefValue = "";
		$this->no_documento->TooltipValue = "";

		// nombres
		$this->nombres->LinkCustomAttributes = "";
		$this->nombres->HrefValue = "";
		$this->nombres->TooltipValue = "";

		// paterno
		$this->paterno->LinkCustomAttributes = "";
		$this->paterno->HrefValue = "";
		$this->paterno->TooltipValue = "";

		// materno
		$this->materno->LinkCustomAttributes = "";
		$this->materno->HrefValue = "";
		$this->materno->TooltipValue = "";

		// pais
		$this->pais->LinkCustomAttributes = "";
		$this->pais->HrefValue = "";
		$this->pais->TooltipValue = "";

		// departamento
		$this->departamento->LinkCustomAttributes = "";
		$this->departamento->HrefValue = "";
		$this->departamento->TooltipValue = "";

		// provincia
		$this->provincia->LinkCustomAttributes = "";
		$this->provincia->HrefValue = "";
		$this->provincia->TooltipValue = "";

		// municipio
		$this->municipio->LinkCustomAttributes = "";
		$this->municipio->HrefValue = "";
		$this->municipio->TooltipValue = "";

		// localidad
		$this->localidad->LinkCustomAttributes = "";
		$this->localidad->HrefValue = "";
		$this->localidad->TooltipValue = "";

		// distrito
		$this->distrito->LinkCustomAttributes = "";
		$this->distrito->HrefValue = "";
		$this->distrito->TooltipValue = "";

		// zona
		$this->zona->LinkCustomAttributes = "";
		$this->zona->HrefValue = "";
		$this->zona->TooltipValue = "";

		// direccion1
		$this->direccion1->LinkCustomAttributes = "";
		$this->direccion1->HrefValue = "";
		$this->direccion1->TooltipValue = "";

		// direccion2
		$this->direccion2->LinkCustomAttributes = "";
		$this->direccion2->HrefValue = "";
		$this->direccion2->TooltipValue = "";

		// direccion3
		$this->direccion3->LinkCustomAttributes = "";
		$this->direccion3->HrefValue = "";
		$this->direccion3->TooltipValue = "";

		// direccion4
		$this->direccion4->LinkCustomAttributes = "";
		$this->direccion4->HrefValue = "";
		$this->direccion4->TooltipValue = "";

		// mapa
		$this->mapa->LinkCustomAttributes = "";
		$this->mapa->HrefValue = "";
		$this->mapa->TooltipValue = "";

		// longitud
		$this->longitud->LinkCustomAttributes = "";
		$this->longitud->HrefValue = "";
		$this->longitud->TooltipValue = "";

		// latitud
		$this->latitud->LinkCustomAttributes = "";
		$this->latitud->HrefValue = "";
		$this->latitud->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// Id
		$this->Id->EditAttrs["class"] = "form-control";
		$this->Id->EditCustomAttributes = "";
		$this->Id->EditValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// id_fuente
		$this->id_fuente->EditAttrs["class"] = "form-control";
		$this->id_fuente->EditCustomAttributes = "";

		// id_gestion
		$this->id_gestion->EditAttrs["class"] = "form-control";
		$this->id_gestion->EditCustomAttributes = "";

		// id_tipodireccion
		$this->id_tipodireccion->EditAttrs["class"] = "form-control";
		$this->id_tipodireccion->EditCustomAttributes = "";

		// tipo_documento
		$this->tipo_documento->EditAttrs["class"] = "form-control";
		$this->tipo_documento->EditCustomAttributes = "";
		$this->tipo_documento->EditValue = $this->tipo_documento->CurrentValue;
		$this->tipo_documento->PlaceHolder = ew_RemoveHtml($this->tipo_documento->FldCaption());

		// no_documento
		$this->no_documento->EditAttrs["class"] = "form-control";
		$this->no_documento->EditCustomAttributes = "";
		$this->no_documento->EditValue = $this->no_documento->CurrentValue;
		$this->no_documento->PlaceHolder = ew_RemoveHtml($this->no_documento->FldCaption());

		// nombres
		$this->nombres->EditAttrs["class"] = "form-control";
		$this->nombres->EditCustomAttributes = "";
		$this->nombres->EditValue = $this->nombres->CurrentValue;
		$this->nombres->PlaceHolder = ew_RemoveHtml($this->nombres->FldCaption());

		// paterno
		$this->paterno->EditAttrs["class"] = "form-control";
		$this->paterno->EditCustomAttributes = "";
		$this->paterno->EditValue = $this->paterno->CurrentValue;
		$this->paterno->PlaceHolder = ew_RemoveHtml($this->paterno->FldCaption());

		// materno
		$this->materno->EditAttrs["class"] = "form-control";
		$this->materno->EditCustomAttributes = "";
		$this->materno->EditValue = $this->materno->CurrentValue;
		$this->materno->PlaceHolder = ew_RemoveHtml($this->materno->FldCaption());

		// pais
		$this->pais->EditAttrs["class"] = "form-control";
		$this->pais->EditCustomAttributes = "";
		$this->pais->EditValue = $this->pais->CurrentValue;
		$this->pais->PlaceHolder = ew_RemoveHtml($this->pais->FldCaption());

		// departamento
		$this->departamento->EditAttrs["class"] = "form-control";
		$this->departamento->EditCustomAttributes = "";
		$this->departamento->EditValue = $this->departamento->CurrentValue;
		$this->departamento->PlaceHolder = ew_RemoveHtml($this->departamento->FldCaption());

		// provincia
		$this->provincia->EditAttrs["class"] = "form-control";
		$this->provincia->EditCustomAttributes = "";
		$this->provincia->EditValue = $this->provincia->CurrentValue;
		$this->provincia->PlaceHolder = ew_RemoveHtml($this->provincia->FldCaption());

		// municipio
		$this->municipio->EditAttrs["class"] = "form-control";
		$this->municipio->EditCustomAttributes = "";
		$this->municipio->EditValue = $this->municipio->CurrentValue;
		$this->municipio->PlaceHolder = ew_RemoveHtml($this->municipio->FldCaption());

		// localidad
		$this->localidad->EditAttrs["class"] = "form-control";
		$this->localidad->EditCustomAttributes = "";
		$this->localidad->EditValue = $this->localidad->CurrentValue;
		$this->localidad->PlaceHolder = ew_RemoveHtml($this->localidad->FldCaption());

		// distrito
		$this->distrito->EditAttrs["class"] = "form-control";
		$this->distrito->EditCustomAttributes = "";
		$this->distrito->EditValue = $this->distrito->CurrentValue;
		$this->distrito->PlaceHolder = ew_RemoveHtml($this->distrito->FldCaption());

		// zona
		$this->zona->EditAttrs["class"] = "form-control";
		$this->zona->EditCustomAttributes = "";
		$this->zona->EditValue = $this->zona->CurrentValue;
		$this->zona->PlaceHolder = ew_RemoveHtml($this->zona->FldCaption());

		// direccion1
		$this->direccion1->EditAttrs["class"] = "form-control";
		$this->direccion1->EditCustomAttributes = "";
		$this->direccion1->EditValue = $this->direccion1->CurrentValue;
		$this->direccion1->PlaceHolder = ew_RemoveHtml($this->direccion1->FldCaption());

		// direccion2
		$this->direccion2->EditAttrs["class"] = "form-control";
		$this->direccion2->EditCustomAttributes = "";
		$this->direccion2->EditValue = $this->direccion2->CurrentValue;
		$this->direccion2->PlaceHolder = ew_RemoveHtml($this->direccion2->FldCaption());

		// direccion3
		$this->direccion3->EditAttrs["class"] = "form-control";
		$this->direccion3->EditCustomAttributes = "";
		$this->direccion3->EditValue = $this->direccion3->CurrentValue;
		$this->direccion3->PlaceHolder = ew_RemoveHtml($this->direccion3->FldCaption());

		// direccion4
		$this->direccion4->EditAttrs["class"] = "form-control";
		$this->direccion4->EditCustomAttributes = "";
		$this->direccion4->EditValue = $this->direccion4->CurrentValue;
		$this->direccion4->PlaceHolder = ew_RemoveHtml($this->direccion4->FldCaption());

		// mapa
		$this->mapa->EditAttrs["class"] = "form-control";
		$this->mapa->EditCustomAttributes = "";
		$this->mapa->EditValue = $this->mapa->CurrentValue;
		$this->mapa->PlaceHolder = ew_RemoveHtml($this->mapa->FldCaption());

		// longitud
		$this->longitud->EditAttrs["class"] = "form-control";
		$this->longitud->EditCustomAttributes = 'readonly=true';
		$this->longitud->EditValue = $this->longitud->CurrentValue;
		$this->longitud->PlaceHolder = ew_RemoveHtml($this->longitud->FldCaption());

		// latitud
		$this->latitud->EditAttrs["class"] = "form-control";
		$this->latitud->EditCustomAttributes = 'readonly=true';
		$this->latitud->EditValue = $this->latitud->CurrentValue;
		$this->latitud->PlaceHolder = ew_RemoveHtml($this->latitud->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->Id->Exportable) $Doc->ExportCaption($this->Id);
					if ($this->id_fuente->Exportable) $Doc->ExportCaption($this->id_fuente);
					if ($this->id_gestion->Exportable) $Doc->ExportCaption($this->id_gestion);
					if ($this->id_tipodireccion->Exportable) $Doc->ExportCaption($this->id_tipodireccion);
					if ($this->tipo_documento->Exportable) $Doc->ExportCaption($this->tipo_documento);
					if ($this->no_documento->Exportable) $Doc->ExportCaption($this->no_documento);
					if ($this->nombres->Exportable) $Doc->ExportCaption($this->nombres);
					if ($this->paterno->Exportable) $Doc->ExportCaption($this->paterno);
					if ($this->materno->Exportable) $Doc->ExportCaption($this->materno);
					if ($this->pais->Exportable) $Doc->ExportCaption($this->pais);
					if ($this->departamento->Exportable) $Doc->ExportCaption($this->departamento);
					if ($this->provincia->Exportable) $Doc->ExportCaption($this->provincia);
					if ($this->municipio->Exportable) $Doc->ExportCaption($this->municipio);
					if ($this->localidad->Exportable) $Doc->ExportCaption($this->localidad);
					if ($this->distrito->Exportable) $Doc->ExportCaption($this->distrito);
					if ($this->zona->Exportable) $Doc->ExportCaption($this->zona);
					if ($this->direccion1->Exportable) $Doc->ExportCaption($this->direccion1);
					if ($this->direccion2->Exportable) $Doc->ExportCaption($this->direccion2);
					if ($this->direccion3->Exportable) $Doc->ExportCaption($this->direccion3);
					if ($this->direccion4->Exportable) $Doc->ExportCaption($this->direccion4);
					if ($this->mapa->Exportable) $Doc->ExportCaption($this->mapa);
					if ($this->longitud->Exportable) $Doc->ExportCaption($this->longitud);
					if ($this->latitud->Exportable) $Doc->ExportCaption($this->latitud);
				} else {
					if ($this->Id->Exportable) $Doc->ExportCaption($this->Id);
					if ($this->id_fuente->Exportable) $Doc->ExportCaption($this->id_fuente);
					if ($this->id_gestion->Exportable) $Doc->ExportCaption($this->id_gestion);
					if ($this->id_tipodireccion->Exportable) $Doc->ExportCaption($this->id_tipodireccion);
					if ($this->tipo_documento->Exportable) $Doc->ExportCaption($this->tipo_documento);
					if ($this->no_documento->Exportable) $Doc->ExportCaption($this->no_documento);
					if ($this->nombres->Exportable) $Doc->ExportCaption($this->nombres);
					if ($this->paterno->Exportable) $Doc->ExportCaption($this->paterno);
					if ($this->materno->Exportable) $Doc->ExportCaption($this->materno);
					if ($this->pais->Exportable) $Doc->ExportCaption($this->pais);
					if ($this->departamento->Exportable) $Doc->ExportCaption($this->departamento);
					if ($this->provincia->Exportable) $Doc->ExportCaption($this->provincia);
					if ($this->municipio->Exportable) $Doc->ExportCaption($this->municipio);
					if ($this->localidad->Exportable) $Doc->ExportCaption($this->localidad);
					if ($this->distrito->Exportable) $Doc->ExportCaption($this->distrito);
					if ($this->zona->Exportable) $Doc->ExportCaption($this->zona);
					if ($this->direccion1->Exportable) $Doc->ExportCaption($this->direccion1);
					if ($this->direccion2->Exportable) $Doc->ExportCaption($this->direccion2);
					if ($this->direccion3->Exportable) $Doc->ExportCaption($this->direccion3);
					if ($this->direccion4->Exportable) $Doc->ExportCaption($this->direccion4);
					if ($this->mapa->Exportable) $Doc->ExportCaption($this->mapa);
					if ($this->longitud->Exportable) $Doc->ExportCaption($this->longitud);
					if ($this->latitud->Exportable) $Doc->ExportCaption($this->latitud);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->Id->Exportable) $Doc->ExportField($this->Id);
						if ($this->id_fuente->Exportable) $Doc->ExportField($this->id_fuente);
						if ($this->id_gestion->Exportable) $Doc->ExportField($this->id_gestion);
						if ($this->id_tipodireccion->Exportable) $Doc->ExportField($this->id_tipodireccion);
						if ($this->tipo_documento->Exportable) $Doc->ExportField($this->tipo_documento);
						if ($this->no_documento->Exportable) $Doc->ExportField($this->no_documento);
						if ($this->nombres->Exportable) $Doc->ExportField($this->nombres);
						if ($this->paterno->Exportable) $Doc->ExportField($this->paterno);
						if ($this->materno->Exportable) $Doc->ExportField($this->materno);
						if ($this->pais->Exportable) $Doc->ExportField($this->pais);
						if ($this->departamento->Exportable) $Doc->ExportField($this->departamento);
						if ($this->provincia->Exportable) $Doc->ExportField($this->provincia);
						if ($this->municipio->Exportable) $Doc->ExportField($this->municipio);
						if ($this->localidad->Exportable) $Doc->ExportField($this->localidad);
						if ($this->distrito->Exportable) $Doc->ExportField($this->distrito);
						if ($this->zona->Exportable) $Doc->ExportField($this->zona);
						if ($this->direccion1->Exportable) $Doc->ExportField($this->direccion1);
						if ($this->direccion2->Exportable) $Doc->ExportField($this->direccion2);
						if ($this->direccion3->Exportable) $Doc->ExportField($this->direccion3);
						if ($this->direccion4->Exportable) $Doc->ExportField($this->direccion4);
						if ($this->mapa->Exportable) $Doc->ExportField($this->mapa);
						if ($this->longitud->Exportable) $Doc->ExportField($this->longitud);
						if ($this->latitud->Exportable) $Doc->ExportField($this->latitud);
					} else {
						if ($this->Id->Exportable) $Doc->ExportField($this->Id);
						if ($this->id_fuente->Exportable) $Doc->ExportField($this->id_fuente);
						if ($this->id_gestion->Exportable) $Doc->ExportField($this->id_gestion);
						if ($this->id_tipodireccion->Exportable) $Doc->ExportField($this->id_tipodireccion);
						if ($this->tipo_documento->Exportable) $Doc->ExportField($this->tipo_documento);
						if ($this->no_documento->Exportable) $Doc->ExportField($this->no_documento);
						if ($this->nombres->Exportable) $Doc->ExportField($this->nombres);
						if ($this->paterno->Exportable) $Doc->ExportField($this->paterno);
						if ($this->materno->Exportable) $Doc->ExportField($this->materno);
						if ($this->pais->Exportable) $Doc->ExportField($this->pais);
						if ($this->departamento->Exportable) $Doc->ExportField($this->departamento);
						if ($this->provincia->Exportable) $Doc->ExportField($this->provincia);
						if ($this->municipio->Exportable) $Doc->ExportField($this->municipio);
						if ($this->localidad->Exportable) $Doc->ExportField($this->localidad);
						if ($this->distrito->Exportable) $Doc->ExportField($this->distrito);
						if ($this->zona->Exportable) $Doc->ExportField($this->zona);
						if ($this->direccion1->Exportable) $Doc->ExportField($this->direccion1);
						if ($this->direccion2->Exportable) $Doc->ExportField($this->direccion2);
						if ($this->direccion3->Exportable) $Doc->ExportField($this->direccion3);
						if ($this->direccion4->Exportable) $Doc->ExportField($this->direccion4);
						if ($this->mapa->Exportable) $Doc->ExportField($this->mapa);
						if ($this->longitud->Exportable) $Doc->ExportField($this->longitud);
						if ($this->latitud->Exportable) $Doc->ExportField($this->latitud);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
