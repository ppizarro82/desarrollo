<?php

// Global variable for table object
$deuda_persona = NULL;

//
// Table class for deuda_persona
//
class cdeuda_persona extends cTable {
	var $id_persona;
	var $id_deuda;
	var $id_tipopersona;
	var $mig_codigo_cliente;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'deuda_persona';
		$this->TableName = 'deuda_persona';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`deuda_persona`";
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

		// id_persona
		$this->id_persona = new cField('deuda_persona', 'deuda_persona', 'x_id_persona', 'id_persona', '`id_persona`', '`id_persona`', 3, -1, FALSE, '`EV__id_persona`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->id_persona->Sortable = TRUE; // Allow sort
		$this->id_persona->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_persona->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_persona->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_persona'] = &$this->id_persona;

		// id_deuda
		$this->id_deuda = new cField('deuda_persona', 'deuda_persona', 'x_id_deuda', 'id_deuda', '`id_deuda`', '`id_deuda`', 3, -1, FALSE, '`EV__id_deuda`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->id_deuda->Sortable = TRUE; // Allow sort
		$this->id_deuda->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_deuda->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_deuda->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_deuda'] = &$this->id_deuda;

		// id_tipopersona
		$this->id_tipopersona = new cField('deuda_persona', 'deuda_persona', 'x_id_tipopersona', 'id_tipopersona', '`id_tipopersona`', '`id_tipopersona`', 3, -1, FALSE, '`id_tipopersona`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_tipopersona->Sortable = TRUE; // Allow sort
		$this->id_tipopersona->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_tipopersona->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['id_tipopersona'] = &$this->id_tipopersona;

		// mig_codigo_cliente
		$this->mig_codigo_cliente = new cField('deuda_persona', 'deuda_persona', 'x_mig_codigo_cliente', 'mig_codigo_cliente', '`mig_codigo_cliente`', '`mig_codigo_cliente`', 200, -1, FALSE, '`mig_codigo_cliente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_codigo_cliente->Sortable = TRUE; // Allow sort
		$this->fields['mig_codigo_cliente'] = &$this->mig_codigo_cliente;
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "deudas") {
			if ($this->id_deuda->getSessionValue() <> "")
				$sMasterFilter .= "`Id`=" . ew_QuotedValue($this->id_deuda->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "personas") {
			if ($this->id_persona->getSessionValue() <> "")
				$sMasterFilter .= "`Id`=" . ew_QuotedValue($this->id_persona->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "deudas") {
			if ($this->id_deuda->getSessionValue() <> "")
				$sDetailFilter .= "`id_deuda`=" . ew_QuotedValue($this->id_deuda->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "personas") {
			if ($this->id_persona->getSessionValue() <> "")
				$sDetailFilter .= "`id_persona`=" . ew_QuotedValue($this->id_persona->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_deudas() {
		return "`Id`=@Id@";
	}

	// Detail filter
	function SqlDetailFilter_deudas() {
		return "`id_deuda`=@id_deuda@";
	}

	// Master filter
	function SqlMasterFilter_personas() {
		return "`Id`=@Id@";
	}

	// Detail filter
	function SqlDetailFilter_personas() {
		return "`id_persona`=@id_persona@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`deuda_persona`";
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
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT DISTINCT CONCAT(`nombres`,'" . ew_ValueSeparator(1, $this->id_persona) . "',`paterno`,'" . ew_ValueSeparator(2, $this->id_persona) . "',`materno`) FROM `personas` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`Id` = `deuda_persona`.`id_persona` LIMIT 1) AS `EV__id_persona`, (SELECT DISTINCT `cuenta` FROM `deudas` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`Id` = `deuda_persona`.`id_deuda` LIMIT 1) AS `EV__id_deuda` FROM `deuda_persona`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
		return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
		$this->_SqlSelectList = $v;
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
		if ($this->UseVirtualFields()) {
			$sSelect = $this->getSqlSelectList();
			$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderByList() : "";
		} else {
			$sSelect = $this->getSqlSelect();
			$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		}
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->UseSessionForListSQL ? $this->getSessionWhere() : $this->CurrentFilter;
		$sOrderBy = $this->UseSessionForListSQL ? $this->getSessionOrderByList() : "";
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->id_persona->AdvancedSearch->SearchValue <> "" ||
			$this->id_persona->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_persona->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_persona->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->id_deuda->AdvancedSearch->SearchValue <> "" ||
			$this->id_deuda->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_deuda->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_deuda->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
			if (array_key_exists('id_persona', $rs))
				ew_AddFilter($where, ew_QuotedName('id_persona', $this->DBID) . '=' . ew_QuotedValue($rs['id_persona'], $this->id_persona->FldDataType, $this->DBID));
			if (array_key_exists('id_deuda', $rs))
				ew_AddFilter($where, ew_QuotedName('id_deuda', $this->DBID) . '=' . ew_QuotedValue($rs['id_deuda'], $this->id_deuda->FldDataType, $this->DBID));
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
		return "`id_persona` = @id_persona@ AND `id_deuda` = @id_deuda@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_persona->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->id_persona->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@id_persona@", ew_AdjustSql($this->id_persona->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->id_deuda->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->id_deuda->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@id_deuda@", ew_AdjustSql($this->id_deuda->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "deuda_personalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "deuda_personaview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "deuda_personaedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "deuda_personaadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "deuda_personalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("deuda_personaview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("deuda_personaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "deuda_personaadd.php?" . $this->UrlParm($parm);
		else
			$url = "deuda_personaadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("deuda_personaedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("deuda_personaadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("deuda_personadelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		if ($this->getCurrentMasterTable() == "deudas" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_Id=" . urlencode($this->id_deuda->CurrentValue);
		}
		if ($this->getCurrentMasterTable() == "personas" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_Id=" . urlencode($this->id_persona->CurrentValue);
		}
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id_persona:" . ew_VarToJson($this->id_persona->CurrentValue, "number", "'");
		$json .= ",id_deuda:" . ew_VarToJson($this->id_deuda->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_persona->CurrentValue)) {
			$sUrl .= "id_persona=" . urlencode($this->id_persona->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->id_deuda->CurrentValue)) {
			$sUrl .= "&id_deuda=" . urlencode($this->id_deuda->CurrentValue);
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
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["id_persona"]))
				$arKey[] = $_POST["id_persona"];
			elseif (isset($_GET["id_persona"]))
				$arKey[] = $_GET["id_persona"];
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["id_deuda"]))
				$arKey[] = $_POST["id_deuda"];
			elseif (isset($_GET["id_deuda"]))
				$arKey[] = $_GET["id_deuda"];
			else
				$arKeys = NULL; // Do not setup
			if (is_array($arKeys)) $arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_array($key) || count($key) <> 2)
					continue; // Just skip so other keys will still work
				if (!is_numeric($key[0])) // id_persona
					continue;
				if (!is_numeric($key[1])) // id_deuda
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
			$this->id_persona->CurrentValue = $key[0];
			$this->id_deuda->CurrentValue = $key[1];
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
		$this->id_persona->setDbValue($rs->fields('id_persona'));
		$this->id_deuda->setDbValue($rs->fields('id_deuda'));
		$this->id_tipopersona->setDbValue($rs->fields('id_tipopersona'));
		$this->mig_codigo_cliente->setDbValue($rs->fields('mig_codigo_cliente'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id_persona
		// id_deuda
		// id_tipopersona
		// mig_codigo_cliente
		// id_persona

		if ($this->id_persona->VirtualValue <> "") {
			$this->id_persona->ViewValue = $this->id_persona->VirtualValue;
		} else {
		if (strval($this->id_persona->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_persona->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT DISTINCT `Id`, `nombres` AS `DispFld`, `paterno` AS `Disp2Fld`, `materno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `personas`";
		$sWhereWrk = "";
		$this->id_persona->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombres`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_persona->ViewValue = $this->id_persona->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_persona->ViewValue = $this->id_persona->CurrentValue;
			}
		} else {
			$this->id_persona->ViewValue = NULL;
		}
		}
		$this->id_persona->ViewCustomAttributes = "";

		// id_deuda
		if ($this->id_deuda->VirtualValue <> "") {
			$this->id_deuda->ViewValue = $this->id_deuda->VirtualValue;
		} else {
		if (strval($this->id_deuda->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_deuda->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT DISTINCT `Id`, (SELECT CONCAT(c.codigo,' - ',deudas.mig_codigo_deuda) FROM cuentas c WHERE c.Id=id_cliente) AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `deudas`";
		$sWhereWrk = "";
		$this->id_deuda->LookupFilters = array("dx1" => '(SELECT CONCAT(c.codigo,\' - \',deudas.mig_codigo_deuda) FROM cuentas c WHERE c.Id=id_cliente)');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_deuda, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_deuda->ViewValue = $this->id_deuda->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_deuda->ViewValue = $this->id_deuda->CurrentValue;
			}
		} else {
			$this->id_deuda->ViewValue = NULL;
		}
		}
		$this->id_deuda->ViewCustomAttributes = "";

		// id_tipopersona
		if (strval($this->id_tipopersona->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_tipopersona->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_persona`";
		$sWhereWrk = "";
		$this->id_tipopersona->LookupFilters = array();
		$lookuptblfilter = "`estado`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_tipopersona, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_tipopersona->ViewValue = $this->id_tipopersona->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_tipopersona->ViewValue = $this->id_tipopersona->CurrentValue;
			}
		} else {
			$this->id_tipopersona->ViewValue = NULL;
		}
		$this->id_tipopersona->ViewCustomAttributes = "";

		// mig_codigo_cliente
		$this->mig_codigo_cliente->ViewValue = $this->mig_codigo_cliente->CurrentValue;
		$this->mig_codigo_cliente->ViewCustomAttributes = "";

		// id_persona
		$this->id_persona->LinkCustomAttributes = "";
		if (!ew_Empty($this->id_persona->CurrentValue)) {
			$this->id_persona->HrefValue = "personasview.php?showdetail=direcciones,telefonos,emails,vehiculos,deuda_persona&Id=" . $this->id_persona->CurrentValue; // Add prefix/suffix
			$this->id_persona->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->id_persona->HrefValue = ew_FullUrl($this->id_persona->HrefValue, "href");
		} else {
			$this->id_persona->HrefValue = "";
		}
		$this->id_persona->TooltipValue = "";

		// id_deuda
		$this->id_deuda->LinkCustomAttributes = "";
		if (!ew_Empty($this->id_deuda->CurrentValue)) {
			$this->id_deuda->HrefValue = "deudasview.php?showdetail=deuda_persona&Id=" . $this->id_deuda->CurrentValue; // Add prefix/suffix
			$this->id_deuda->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->id_deuda->HrefValue = ew_FullUrl($this->id_deuda->HrefValue, "href");
		} else {
			$this->id_deuda->HrefValue = "";
		}
		$this->id_deuda->TooltipValue = "";

		// id_tipopersona
		$this->id_tipopersona->LinkCustomAttributes = "";
		$this->id_tipopersona->HrefValue = "";
		$this->id_tipopersona->TooltipValue = "";

		// mig_codigo_cliente
		$this->mig_codigo_cliente->LinkCustomAttributes = "";
		$this->mig_codigo_cliente->HrefValue = "";
		$this->mig_codigo_cliente->TooltipValue = "";

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

		// id_persona
		$this->id_persona->EditAttrs["class"] = "form-control";
		$this->id_persona->EditCustomAttributes = "";
		if ($this->id_persona->VirtualValue <> "") {
			$this->id_persona->EditValue = $this->id_persona->VirtualValue;
		} else {
		if (strval($this->id_persona->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_persona->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT DISTINCT `Id`, `nombres` AS `DispFld`, `paterno` AS `Disp2Fld`, `materno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `personas`";
		$sWhereWrk = "";
		$this->id_persona->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombres`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->id_persona->EditValue = $this->id_persona->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_persona->EditValue = $this->id_persona->CurrentValue;
			}
		} else {
			$this->id_persona->EditValue = NULL;
		}
		}
		$this->id_persona->ViewCustomAttributes = "";

		// id_deuda
		$this->id_deuda->EditAttrs["class"] = "form-control";
		$this->id_deuda->EditCustomAttributes = "";
		if ($this->id_deuda->VirtualValue <> "") {
			$this->id_deuda->EditValue = $this->id_deuda->VirtualValue;
		} else {
		if (strval($this->id_deuda->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_deuda->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT DISTINCT `Id`, (SELECT CONCAT(c.codigo,' - ',deudas.mig_codigo_deuda) FROM cuentas c WHERE c.Id=id_cliente) AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `deudas`";
		$sWhereWrk = "";
		$this->id_deuda->LookupFilters = array("dx1" => '(SELECT CONCAT(c.codigo,\' - \',deudas.mig_codigo_deuda) FROM cuentas c WHERE c.Id=id_cliente)');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_deuda, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_deuda->EditValue = $this->id_deuda->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_deuda->EditValue = $this->id_deuda->CurrentValue;
			}
		} else {
			$this->id_deuda->EditValue = NULL;
		}
		}
		$this->id_deuda->ViewCustomAttributes = "";

		// id_tipopersona
		$this->id_tipopersona->EditAttrs["class"] = "form-control";
		$this->id_tipopersona->EditCustomAttributes = "";

		// mig_codigo_cliente
		$this->mig_codigo_cliente->EditAttrs["class"] = "form-control";
		$this->mig_codigo_cliente->EditCustomAttributes = "";
		$this->mig_codigo_cliente->EditValue = $this->mig_codigo_cliente->CurrentValue;
		$this->mig_codigo_cliente->PlaceHolder = ew_RemoveHtml($this->mig_codigo_cliente->FldCaption());

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
					if ($this->id_persona->Exportable) $Doc->ExportCaption($this->id_persona);
					if ($this->id_deuda->Exportable) $Doc->ExportCaption($this->id_deuda);
					if ($this->id_tipopersona->Exportable) $Doc->ExportCaption($this->id_tipopersona);
					if ($this->mig_codigo_cliente->Exportable) $Doc->ExportCaption($this->mig_codigo_cliente);
				} else {
					if ($this->id_persona->Exportable) $Doc->ExportCaption($this->id_persona);
					if ($this->id_deuda->Exportable) $Doc->ExportCaption($this->id_deuda);
					if ($this->id_tipopersona->Exportable) $Doc->ExportCaption($this->id_tipopersona);
					if ($this->mig_codigo_cliente->Exportable) $Doc->ExportCaption($this->mig_codigo_cliente);
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
						if ($this->id_persona->Exportable) $Doc->ExportField($this->id_persona);
						if ($this->id_deuda->Exportable) $Doc->ExportField($this->id_deuda);
						if ($this->id_tipopersona->Exportable) $Doc->ExportField($this->id_tipopersona);
						if ($this->mig_codigo_cliente->Exportable) $Doc->ExportField($this->mig_codigo_cliente);
					} else {
						if ($this->id_persona->Exportable) $Doc->ExportField($this->id_persona);
						if ($this->id_deuda->Exportable) $Doc->ExportField($this->id_deuda);
						if ($this->id_tipopersona->Exportable) $Doc->ExportField($this->id_tipopersona);
						if ($this->mig_codigo_cliente->Exportable) $Doc->ExportField($this->mig_codigo_cliente);
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
