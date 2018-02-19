<?php

// Global variable for table object
$personas = NULL;

//
// Table class for personas
//
class cpersonas extends cTable {
	var $Id;
	var $tipo_documento;
	var $no_documento;
	var $nombres;
	var $paterno;
	var $materno;
	var $fecha_nacimiento;
	var $fecha_registro;
	var $imagen;
	var $observaciones;
	var $estado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'personas';
		$this->TableName = 'personas';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`personas`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = TRUE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// Id
		$this->Id = new cField('personas', 'personas', 'x_Id', 'Id', '`Id`', '`Id`', 3, -1, FALSE, '`Id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->Id->Sortable = TRUE; // Allow sort
		$this->Id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Id'] = &$this->Id;

		// tipo_documento
		$this->tipo_documento = new cField('personas', 'personas', 'x_tipo_documento', 'tipo_documento', '`tipo_documento`', '`tipo_documento`', 200, -1, FALSE, '`tipo_documento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->tipo_documento->Sortable = TRUE; // Allow sort
		$this->tipo_documento->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->tipo_documento->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->tipo_documento->OptionCount = 3;
		$this->fields['tipo_documento'] = &$this->tipo_documento;

		// no_documento
		$this->no_documento = new cField('personas', 'personas', 'x_no_documento', 'no_documento', '`no_documento`', '`no_documento`', 200, -1, FALSE, '`no_documento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_documento->Sortable = TRUE; // Allow sort
		$this->fields['no_documento'] = &$this->no_documento;

		// nombres
		$this->nombres = new cField('personas', 'personas', 'x_nombres', 'nombres', '`nombres`', '`nombres`', 200, -1, FALSE, '`nombres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nombres->Sortable = TRUE; // Allow sort
		$this->fields['nombres'] = &$this->nombres;

		// paterno
		$this->paterno = new cField('personas', 'personas', 'x_paterno', 'paterno', '`paterno`', '`paterno`', 200, -1, FALSE, '`paterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->paterno->Sortable = TRUE; // Allow sort
		$this->fields['paterno'] = &$this->paterno;

		// materno
		$this->materno = new cField('personas', 'personas', 'x_materno', 'materno', '`materno`', '`materno`', 200, -1, FALSE, '`materno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->materno->Sortable = TRUE; // Allow sort
		$this->fields['materno'] = &$this->materno;

		// fecha_nacimiento
		$this->fecha_nacimiento = new cField('personas', 'personas', 'x_fecha_nacimiento', 'fecha_nacimiento', '`fecha_nacimiento`', ew_CastDateFieldForLike('`fecha_nacimiento`', 7, "DB"), 135, 7, FALSE, '`fecha_nacimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha_nacimiento->Sortable = TRUE; // Allow sort
		$this->fecha_nacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_nacimiento'] = &$this->fecha_nacimiento;

		// fecha_registro
		$this->fecha_registro = new cField('personas', 'personas', 'x_fecha_registro', 'fecha_registro', '`fecha_registro`', ew_CastDateFieldForLike('`fecha_registro`', 11, "DB"), 135, 11, FALSE, '`fecha_registro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha_registro->Sortable = TRUE; // Allow sort
		$this->fecha_registro->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_registro'] = &$this->fecha_registro;

		// imagen
		$this->imagen = new cField('personas', 'personas', 'x_imagen', 'imagen', '`imagen`', '`imagen`', 200, -1, TRUE, '`imagen`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->imagen->Sortable = TRUE; // Allow sort
		$this->fields['imagen'] = &$this->imagen;

		// observaciones
		$this->observaciones = new cField('personas', 'personas', 'x_observaciones', 'observaciones', '`observaciones`', '`observaciones`', 201, -1, FALSE, '`observaciones`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->observaciones->Sortable = TRUE; // Allow sort
		$this->fields['observaciones'] = &$this->observaciones;

		// estado
		$this->estado = new cField('personas', 'personas', 'x_estado', 'estado', '`estado`', '`estado`', 16, -1, FALSE, '`estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->estado->Sortable = TRUE; // Allow sort
		$this->estado->OptionCount = 2;
		$this->estado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['estado'] = &$this->estado;
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

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "direcciones") {
			$sDetailUrl = $GLOBALS["direcciones"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "telefonos") {
			$sDetailUrl = $GLOBALS["telefonos"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "emails") {
			$sDetailUrl = $GLOBALS["emails"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "vehiculos") {
			$sDetailUrl = $GLOBALS["vehiculos"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "deuda_persona") {
			$sDetailUrl = $GLOBALS["deuda_persona"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "personaslist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`personas`";
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
			return "personaslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "personasview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "personasedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "personasadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "personaslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("personasview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("personasview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "personasadd.php?" . $this->UrlParm($parm);
		else
			$url = "personasadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("personasedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("personasedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("personasadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("personasadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("personasdelete.php", $this->UrlParm());
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
		$this->tipo_documento->setDbValue($rs->fields('tipo_documento'));
		$this->no_documento->setDbValue($rs->fields('no_documento'));
		$this->nombres->setDbValue($rs->fields('nombres'));
		$this->paterno->setDbValue($rs->fields('paterno'));
		$this->materno->setDbValue($rs->fields('materno'));
		$this->fecha_nacimiento->setDbValue($rs->fields('fecha_nacimiento'));
		$this->fecha_registro->setDbValue($rs->fields('fecha_registro'));
		$this->imagen->Upload->DbValue = $rs->fields('imagen');
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->estado->setDbValue($rs->fields('estado'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// Id
		// tipo_documento
		// no_documento
		// nombres
		// paterno
		// materno
		// fecha_nacimiento
		// fecha_registro
		// imagen
		// observaciones
		// estado
		// Id

		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// tipo_documento
		if (strval($this->tipo_documento->CurrentValue) <> "") {
			$this->tipo_documento->ViewValue = $this->tipo_documento->OptionCaption($this->tipo_documento->CurrentValue);
		} else {
			$this->tipo_documento->ViewValue = NULL;
		}
		$this->tipo_documento->ViewCustomAttributes = "";

		// no_documento
		$this->no_documento->ViewValue = $this->no_documento->CurrentValue;
		$this->no_documento->ViewValue = ew_FormatNumber($this->no_documento->ViewValue, 0, 0, 0, 0);
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

		// fecha_nacimiento
		$this->fecha_nacimiento->ViewValue = $this->fecha_nacimiento->CurrentValue;
		$this->fecha_nacimiento->ViewValue = ew_FormatDateTime($this->fecha_nacimiento->ViewValue, 7);
		$this->fecha_nacimiento->ViewCustomAttributes = "";

		// fecha_registro
		$this->fecha_registro->ViewValue = $this->fecha_registro->CurrentValue;
		$this->fecha_registro->ViewValue = ew_FormatDateTime($this->fecha_registro->ViewValue, 11);
		$this->fecha_registro->ViewCustomAttributes = "";

		// imagen
		$this->imagen->UploadPath = 'uploads/';
		if (!ew_Empty($this->imagen->Upload->DbValue)) {
			$this->imagen->ImageWidth = 120;
			$this->imagen->ImageHeight = 120;
			$this->imagen->ImageAlt = $this->imagen->FldAlt();
			$this->imagen->ViewValue = $this->imagen->Upload->DbValue;
		} else {
			$this->imagen->ViewValue = "";
		}
		$this->imagen->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// estado
		if (strval($this->estado->CurrentValue) <> "") {
			$this->estado->ViewValue = $this->estado->OptionCaption($this->estado->CurrentValue);
		} else {
			$this->estado->ViewValue = NULL;
		}
		$this->estado->ViewCustomAttributes = "";

		// Id
		$this->Id->LinkCustomAttributes = "";
		$this->Id->HrefValue = "";
		$this->Id->TooltipValue = "";

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

		// fecha_nacimiento
		$this->fecha_nacimiento->LinkCustomAttributes = "";
		$this->fecha_nacimiento->HrefValue = "";
		$this->fecha_nacimiento->TooltipValue = "";

		// fecha_registro
		$this->fecha_registro->LinkCustomAttributes = "";
		$this->fecha_registro->HrefValue = "";
		$this->fecha_registro->TooltipValue = "";

		// imagen
		$this->imagen->LinkCustomAttributes = "";
		$this->imagen->UploadPath = 'uploads/';
		if (!ew_Empty($this->imagen->Upload->DbValue)) {
			$this->imagen->HrefValue = ew_GetFileUploadUrl($this->imagen, $this->imagen->Upload->DbValue); // Add prefix/suffix
			$this->imagen->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->imagen->HrefValue = ew_FullUrl($this->imagen->HrefValue, "href");
		} else {
			$this->imagen->HrefValue = "";
		}
		$this->imagen->HrefValue2 = $this->imagen->UploadPath . $this->imagen->Upload->DbValue;
		$this->imagen->TooltipValue = "";
		if ($this->imagen->UseColorbox) {
			if (ew_Empty($this->imagen->TooltipValue))
				$this->imagen->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->imagen->LinkAttrs["data-rel"] = "personas_x_imagen";
			ew_AppendClass($this->imagen->LinkAttrs["class"], "ewLightbox");
		}

		// observaciones
		$this->observaciones->LinkCustomAttributes = "";
		$this->observaciones->HrefValue = "";
		$this->observaciones->TooltipValue = "";

		// estado
		$this->estado->LinkCustomAttributes = "";
		$this->estado->HrefValue = "";
		$this->estado->TooltipValue = "";

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

		// tipo_documento
		$this->tipo_documento->EditAttrs["class"] = "form-control";
		$this->tipo_documento->EditCustomAttributes = "";
		$this->tipo_documento->EditValue = $this->tipo_documento->Options(TRUE);

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

		// fecha_nacimiento
		$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
		$this->fecha_nacimiento->EditCustomAttributes = "";
		$this->fecha_nacimiento->EditValue = ew_FormatDateTime($this->fecha_nacimiento->CurrentValue, 7);
		$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

		// fecha_registro
		$this->fecha_registro->EditAttrs["class"] = "form-control";
		$this->fecha_registro->EditCustomAttributes = "";
		$this->fecha_registro->EditValue = ew_FormatDateTime($this->fecha_registro->CurrentValue, 11);
		$this->fecha_registro->PlaceHolder = ew_RemoveHtml($this->fecha_registro->FldCaption());

		// imagen
		$this->imagen->EditAttrs["class"] = "form-control";
		$this->imagen->EditCustomAttributes = "";
		$this->imagen->UploadPath = 'uploads/';
		if (!ew_Empty($this->imagen->Upload->DbValue)) {
			$this->imagen->ImageWidth = 120;
			$this->imagen->ImageHeight = 120;
			$this->imagen->ImageAlt = $this->imagen->FldAlt();
			$this->imagen->EditValue = $this->imagen->Upload->DbValue;
		} else {
			$this->imagen->EditValue = "";
		}
		if (!ew_Empty($this->imagen->CurrentValue))
			$this->imagen->Upload->FileName = $this->imagen->CurrentValue;

		// observaciones
		$this->observaciones->EditAttrs["class"] = "form-control";
		$this->observaciones->EditCustomAttributes = "";
		$this->observaciones->EditValue = $this->observaciones->CurrentValue;
		$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

		// estado
		$this->estado->EditCustomAttributes = "";
		$this->estado->EditValue = $this->estado->Options(FALSE);

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
					if ($this->tipo_documento->Exportable) $Doc->ExportCaption($this->tipo_documento);
					if ($this->no_documento->Exportable) $Doc->ExportCaption($this->no_documento);
					if ($this->nombres->Exportable) $Doc->ExportCaption($this->nombres);
					if ($this->paterno->Exportable) $Doc->ExportCaption($this->paterno);
					if ($this->materno->Exportable) $Doc->ExportCaption($this->materno);
					if ($this->fecha_nacimiento->Exportable) $Doc->ExportCaption($this->fecha_nacimiento);
					if ($this->fecha_registro->Exportable) $Doc->ExportCaption($this->fecha_registro);
					if ($this->imagen->Exportable) $Doc->ExportCaption($this->imagen);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
				} else {
					if ($this->Id->Exportable) $Doc->ExportCaption($this->Id);
					if ($this->tipo_documento->Exportable) $Doc->ExportCaption($this->tipo_documento);
					if ($this->no_documento->Exportable) $Doc->ExportCaption($this->no_documento);
					if ($this->nombres->Exportable) $Doc->ExportCaption($this->nombres);
					if ($this->paterno->Exportable) $Doc->ExportCaption($this->paterno);
					if ($this->materno->Exportable) $Doc->ExportCaption($this->materno);
					if ($this->fecha_nacimiento->Exportable) $Doc->ExportCaption($this->fecha_nacimiento);
					if ($this->fecha_registro->Exportable) $Doc->ExportCaption($this->fecha_registro);
					if ($this->imagen->Exportable) $Doc->ExportCaption($this->imagen);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
					if ($this->estado->Exportable) $Doc->ExportCaption($this->estado);
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
						if ($this->tipo_documento->Exportable) $Doc->ExportField($this->tipo_documento);
						if ($this->no_documento->Exportable) $Doc->ExportField($this->no_documento);
						if ($this->nombres->Exportable) $Doc->ExportField($this->nombres);
						if ($this->paterno->Exportable) $Doc->ExportField($this->paterno);
						if ($this->materno->Exportable) $Doc->ExportField($this->materno);
						if ($this->fecha_nacimiento->Exportable) $Doc->ExportField($this->fecha_nacimiento);
						if ($this->fecha_registro->Exportable) $Doc->ExportField($this->fecha_registro);
						if ($this->imagen->Exportable) $Doc->ExportField($this->imagen);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
					} else {
						if ($this->Id->Exportable) $Doc->ExportField($this->Id);
						if ($this->tipo_documento->Exportable) $Doc->ExportField($this->tipo_documento);
						if ($this->no_documento->Exportable) $Doc->ExportField($this->no_documento);
						if ($this->nombres->Exportable) $Doc->ExportField($this->nombres);
						if ($this->paterno->Exportable) $Doc->ExportField($this->paterno);
						if ($this->materno->Exportable) $Doc->ExportField($this->materno);
						if ($this->fecha_nacimiento->Exportable) $Doc->ExportField($this->fecha_nacimiento);
						if ($this->fecha_registro->Exportable) $Doc->ExportField($this->fecha_registro);
						if ($this->imagen->Exportable) $Doc->ExportField($this->imagen);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
						if ($this->estado->Exportable) $Doc->ExportField($this->estado);
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
