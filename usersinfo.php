<?php

// Global variable for table object
$users = NULL;

//
// Table class for users
//
class cusers extends cTable {
	var $id_user;
	var $User_Level;
	var $id_user_creator;
	var $Username;
	var $Password;
	var $No_documento;
	var $Tipo_documento;
	var $First_Name;
	var $Last_Name;
	var $_Email;
	var $Telefono_movil;
	var $Telefono_fijo;
	var $Fecha_nacimiento;
	var $Report_To;
	var $Activated;
	var $Locked;
	var $token;
	var $acceso_app;
	var $observaciones;
	var $fecha_ingreso;
	var $Profile;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'users';
		$this->TableName = 'users';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`users`";
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
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id_user
		$this->id_user = new cField('users', 'users', 'x_id_user', 'id_user', '`id_user`', '`id_user`', 3, -1, FALSE, '`id_user`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id_user->Sortable = TRUE; // Allow sort
		$this->id_user->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_user'] = &$this->id_user;

		// User_Level
		$this->User_Level = new cField('users', 'users', 'x_User_Level', 'User_Level', '`User_Level`', '`User_Level`', 3, -1, FALSE, '`User_Level`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->User_Level->Sortable = TRUE; // Allow sort
		$this->User_Level->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->User_Level->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->User_Level->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['User_Level'] = &$this->User_Level;

		// id_user_creator
		$this->id_user_creator = new cField('users', 'users', 'x_id_user_creator', 'id_user_creator', '`id_user_creator`', '`id_user_creator`', 3, -1, FALSE, '`id_user_creator`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_user_creator->Sortable = FALSE; // Allow sort
		$this->id_user_creator->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_user_creator'] = &$this->id_user_creator;

		// Username
		$this->Username = new cField('users', 'users', 'x_Username', 'Username', '`Username`', '`Username`', 200, -1, FALSE, '`Username`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Username->Sortable = TRUE; // Allow sort
		$this->fields['Username'] = &$this->Username;

		// Password
		$this->Password = new cField('users', 'users', 'x_Password', 'Password', '`Password`', '`Password`', 200, -1, FALSE, '`Password`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'PASSWORD');
		$this->Password->Sortable = TRUE; // Allow sort
		$this->fields['Password'] = &$this->Password;

		// No_documento
		$this->No_documento = new cField('users', 'users', 'x_No_documento', 'No_documento', '`No_documento`', '`No_documento`', 200, -1, FALSE, '`No_documento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->No_documento->Sortable = TRUE; // Allow sort
		$this->fields['No_documento'] = &$this->No_documento;

		// Tipo_documento
		$this->Tipo_documento = new cField('users', 'users', 'x_Tipo_documento', 'Tipo_documento', '`Tipo_documento`', '`Tipo_documento`', 200, -1, FALSE, '`Tipo_documento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Tipo_documento->Sortable = TRUE; // Allow sort
		$this->fields['Tipo_documento'] = &$this->Tipo_documento;

		// First_Name
		$this->First_Name = new cField('users', 'users', 'x_First_Name', 'First_Name', '`First_Name`', '`First_Name`', 200, -1, FALSE, '`First_Name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->First_Name->Sortable = TRUE; // Allow sort
		$this->fields['First_Name'] = &$this->First_Name;

		// Last_Name
		$this->Last_Name = new cField('users', 'users', 'x_Last_Name', 'Last_Name', '`Last_Name`', '`Last_Name`', 200, -1, FALSE, '`Last_Name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Last_Name->Sortable = TRUE; // Allow sort
		$this->fields['Last_Name'] = &$this->Last_Name;

		// Email
		$this->_Email = new cField('users', 'users', 'x__Email', 'Email', '`Email`', '`Email`', 200, -1, FALSE, '`Email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_Email->Sortable = TRUE; // Allow sort
		$this->fields['Email'] = &$this->_Email;

		// Telefono_movil
		$this->Telefono_movil = new cField('users', 'users', 'x_Telefono_movil', 'Telefono_movil', '`Telefono_movil`', '`Telefono_movil`', 200, -1, FALSE, '`Telefono_movil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Telefono_movil->Sortable = TRUE; // Allow sort
		$this->fields['Telefono_movil'] = &$this->Telefono_movil;

		// Telefono_fijo
		$this->Telefono_fijo = new cField('users', 'users', 'x_Telefono_fijo', 'Telefono_fijo', '`Telefono_fijo`', '`Telefono_fijo`', 200, -1, FALSE, '`Telefono_fijo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Telefono_fijo->Sortable = TRUE; // Allow sort
		$this->fields['Telefono_fijo'] = &$this->Telefono_fijo;

		// Fecha_nacimiento
		$this->Fecha_nacimiento = new cField('users', 'users', 'x_Fecha_nacimiento', 'Fecha_nacimiento', '`Fecha_nacimiento`', ew_CastDateFieldForLike('`Fecha_nacimiento`', 17, "DB"), 135, 17, FALSE, '`Fecha_nacimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Fecha_nacimiento->Sortable = TRUE; // Allow sort
		$this->Fecha_nacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateDMY"));
		$this->fields['Fecha_nacimiento'] = &$this->Fecha_nacimiento;

		// Report_To
		$this->Report_To = new cField('users', 'users', 'x_Report_To', 'Report_To', '`Report_To`', '`Report_To`', 3, -1, FALSE, '`Report_To`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Report_To->Sortable = FALSE; // Allow sort
		$this->Report_To->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Report_To'] = &$this->Report_To;

		// Activated
		$this->Activated = new cField('users', 'users', 'x_Activated', 'Activated', '`Activated`', '`Activated`', 16, -1, FALSE, '`Activated`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->Activated->Sortable = FALSE; // Allow sort
		$this->Activated->OptionCount = 2;
		$this->Activated->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Activated'] = &$this->Activated;

		// Locked
		$this->Locked = new cField('users', 'users', 'x_Locked', 'Locked', '`Locked`', '`Locked`', 16, -1, FALSE, '`Locked`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->Locked->Sortable = FALSE; // Allow sort
		$this->Locked->OptionCount = 2;
		$this->Locked->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Locked'] = &$this->Locked;

		// token
		$this->token = new cField('users', 'users', 'x_token', 'token', '`token`', '`token`', 200, -1, FALSE, '`token`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->token->Sortable = FALSE; // Allow sort
		$this->fields['token'] = &$this->token;

		// acceso_app
		$this->acceso_app = new cField('users', 'users', 'x_acceso_app', 'acceso_app', '`acceso_app`', '`acceso_app`', 16, -1, FALSE, '`acceso_app`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->acceso_app->Sortable = FALSE; // Allow sort
		$this->acceso_app->OptionCount = 2;
		$this->acceso_app->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['acceso_app'] = &$this->acceso_app;

		// observaciones
		$this->observaciones = new cField('users', 'users', 'x_observaciones', 'observaciones', '`observaciones`', '`observaciones`', 201, -1, FALSE, '`observaciones`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->observaciones->Sortable = FALSE; // Allow sort
		$this->fields['observaciones'] = &$this->observaciones;

		// fecha_ingreso
		$this->fecha_ingreso = new cField('users', 'users', 'x_fecha_ingreso', 'fecha_ingreso', '`fecha_ingreso`', ew_CastDateFieldForLike('`fecha_ingreso`', 0, "DB"), 135, 0, FALSE, '`fecha_ingreso`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha_ingreso->Sortable = FALSE; // Allow sort
		$this->fecha_ingreso->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['fecha_ingreso'] = &$this->fecha_ingreso;

		// Profile
		$this->Profile = new cField('users', 'users', 'x_Profile', 'Profile', '`Profile`', '`Profile`', 201, -1, FALSE, '`Profile`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->Profile->Sortable = FALSE; // Allow sort
		$this->fields['Profile'] = &$this->Profile;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`users`";
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
			if (EW_ENCRYPTED_PASSWORD && $name == 'Password')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
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
			$this->id_user->setDbValue($conn->Insert_ID());
			$rs['id_user'] = $this->id_user->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'Password') {
				if ($value == $this->fields[$name]->OldValue) // No need to update MD5 password if not changed
					continue;
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
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
			if (array_key_exists('id_user', $rs))
				ew_AddFilter($where, ew_QuotedName('id_user', $this->DBID) . '=' . ew_QuotedValue($rs['id_user'], $this->id_user->FldDataType, $this->DBID));
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
		return "`id_user` = @id_user@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_user->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->id_user->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@id_user@", ew_AdjustSql($this->id_user->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "userslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "usersview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "usersedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "usersadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "userslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("usersview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("usersview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "usersadd.php?" . $this->UrlParm($parm);
		else
			$url = "usersadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("usersedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("usersadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("usersdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id_user:" . ew_VarToJson($this->id_user->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_user->CurrentValue)) {
			$sUrl .= "id_user=" . urlencode($this->id_user->CurrentValue);
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
			if ($isPost && isset($_POST["id_user"]))
				$arKeys[] = $_POST["id_user"];
			elseif (isset($_GET["id_user"]))
				$arKeys[] = $_GET["id_user"];
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
			$this->id_user->CurrentValue = $key;
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
		$this->id_user->setDbValue($rs->fields('id_user'));
		$this->User_Level->setDbValue($rs->fields('User_Level'));
		$this->id_user_creator->setDbValue($rs->fields('id_user_creator'));
		$this->Username->setDbValue($rs->fields('Username'));
		$this->Password->setDbValue($rs->fields('Password'));
		$this->No_documento->setDbValue($rs->fields('No_documento'));
		$this->Tipo_documento->setDbValue($rs->fields('Tipo_documento'));
		$this->First_Name->setDbValue($rs->fields('First_Name'));
		$this->Last_Name->setDbValue($rs->fields('Last_Name'));
		$this->_Email->setDbValue($rs->fields('Email'));
		$this->Telefono_movil->setDbValue($rs->fields('Telefono_movil'));
		$this->Telefono_fijo->setDbValue($rs->fields('Telefono_fijo'));
		$this->Fecha_nacimiento->setDbValue($rs->fields('Fecha_nacimiento'));
		$this->Report_To->setDbValue($rs->fields('Report_To'));
		$this->Activated->setDbValue($rs->fields('Activated'));
		$this->Locked->setDbValue($rs->fields('Locked'));
		$this->token->setDbValue($rs->fields('token'));
		$this->acceso_app->setDbValue($rs->fields('acceso_app'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->fecha_ingreso->setDbValue($rs->fields('fecha_ingreso'));
		$this->Profile->setDbValue($rs->fields('Profile'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id_user
		// User_Level
		// id_user_creator
		// Username
		// Password
		// No_documento
		// Tipo_documento
		// First_Name
		// Last_Name
		// Email
		// Telefono_movil
		// Telefono_fijo
		// Fecha_nacimiento
		// Report_To
		// Activated
		// Locked
		// token
		// acceso_app
		// observaciones
		// fecha_ingreso
		// Profile
		// id_user

		$this->id_user->ViewValue = $this->id_user->CurrentValue;
		$this->id_user->ViewCustomAttributes = "";

		// User_Level
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->User_Level->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->User_Level->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
		$sWhereWrk = "";
		$this->User_Level->LookupFilters = array();
		$lookuptblfilter = "`userlevelid` > 0";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->User_Level, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->User_Level->ViewValue = $this->User_Level->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->User_Level->ViewValue = $this->User_Level->CurrentValue;
			}
		} else {
			$this->User_Level->ViewValue = NULL;
		}
		} else {
			$this->User_Level->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->User_Level->ViewCustomAttributes = "";

		// id_user_creator
		$this->id_user_creator->ViewValue = $this->id_user_creator->CurrentValue;
		$this->id_user_creator->ViewCustomAttributes = "";

		// Username
		$this->Username->ViewValue = $this->Username->CurrentValue;
		$this->Username->ViewCustomAttributes = "";

		// Password
		$this->Password->ViewValue = $Language->Phrase("PasswordMask");
		$this->Password->ViewCustomAttributes = "";

		// No_documento
		$this->No_documento->ViewValue = $this->No_documento->CurrentValue;
		$this->No_documento->ViewCustomAttributes = "";

		// Tipo_documento
		$this->Tipo_documento->ViewValue = $this->Tipo_documento->CurrentValue;
		$this->Tipo_documento->ViewCustomAttributes = "";

		// First_Name
		$this->First_Name->ViewValue = $this->First_Name->CurrentValue;
		$this->First_Name->ViewCustomAttributes = "";

		// Last_Name
		$this->Last_Name->ViewValue = $this->Last_Name->CurrentValue;
		$this->Last_Name->ViewCustomAttributes = "";

		// Email
		$this->_Email->ViewValue = $this->_Email->CurrentValue;
		$this->_Email->ViewCustomAttributes = "";

		// Telefono_movil
		$this->Telefono_movil->ViewValue = $this->Telefono_movil->CurrentValue;
		$this->Telefono_movil->ViewValue = ew_FormatNumber($this->Telefono_movil->ViewValue, 0, 0, 0, 0);
		$this->Telefono_movil->ViewCustomAttributes = "";

		// Telefono_fijo
		$this->Telefono_fijo->ViewValue = $this->Telefono_fijo->CurrentValue;
		$this->Telefono_fijo->ViewValue = ew_FormatNumber($this->Telefono_fijo->ViewValue, 0, -2, -2, -2);
		$this->Telefono_fijo->ViewCustomAttributes = "";

		// Fecha_nacimiento
		$this->Fecha_nacimiento->ViewValue = $this->Fecha_nacimiento->CurrentValue;
		$this->Fecha_nacimiento->ViewValue = ew_FormatDateTime($this->Fecha_nacimiento->ViewValue, 17);
		$this->Fecha_nacimiento->ViewCustomAttributes = "";

		// Report_To
		$this->Report_To->ViewValue = $this->Report_To->CurrentValue;
		$this->Report_To->ViewCustomAttributes = "";

		// Activated
		if (strval($this->Activated->CurrentValue) <> "") {
			$this->Activated->ViewValue = $this->Activated->OptionCaption($this->Activated->CurrentValue);
		} else {
			$this->Activated->ViewValue = NULL;
		}
		$this->Activated->ViewCustomAttributes = "";

		// Locked
		if (strval($this->Locked->CurrentValue) <> "") {
			$this->Locked->ViewValue = $this->Locked->OptionCaption($this->Locked->CurrentValue);
		} else {
			$this->Locked->ViewValue = NULL;
		}
		$this->Locked->ViewCustomAttributes = "";

		// token
		$this->token->ViewValue = $this->token->CurrentValue;
		$this->token->ViewCustomAttributes = "";

		// acceso_app
		if (strval($this->acceso_app->CurrentValue) <> "") {
			$this->acceso_app->ViewValue = $this->acceso_app->OptionCaption($this->acceso_app->CurrentValue);
		} else {
			$this->acceso_app->ViewValue = NULL;
		}
		$this->acceso_app->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// fecha_ingreso
		$this->fecha_ingreso->ViewValue = $this->fecha_ingreso->CurrentValue;
		$this->fecha_ingreso->ViewValue = ew_FormatDateTime($this->fecha_ingreso->ViewValue, 0);
		$this->fecha_ingreso->ViewCustomAttributes = "";

		// Profile
		$this->Profile->ViewValue = $this->Profile->CurrentValue;
		$this->Profile->ViewCustomAttributes = "";

		// id_user
		$this->id_user->LinkCustomAttributes = "";
		$this->id_user->HrefValue = "";
		$this->id_user->TooltipValue = "";

		// User_Level
		$this->User_Level->LinkCustomAttributes = "";
		$this->User_Level->HrefValue = "";
		$this->User_Level->TooltipValue = "";

		// id_user_creator
		$this->id_user_creator->LinkCustomAttributes = "";
		$this->id_user_creator->HrefValue = "";
		$this->id_user_creator->TooltipValue = "";

		// Username
		$this->Username->LinkCustomAttributes = "";
		$this->Username->HrefValue = "";
		$this->Username->TooltipValue = "";

		// Password
		$this->Password->LinkCustomAttributes = "";
		$this->Password->HrefValue = "";
		$this->Password->TooltipValue = "";

		// No_documento
		$this->No_documento->LinkCustomAttributes = "";
		$this->No_documento->HrefValue = "";
		$this->No_documento->TooltipValue = "";

		// Tipo_documento
		$this->Tipo_documento->LinkCustomAttributes = "";
		$this->Tipo_documento->HrefValue = "";
		$this->Tipo_documento->TooltipValue = "";

		// First_Name
		$this->First_Name->LinkCustomAttributes = "";
		$this->First_Name->HrefValue = "";
		$this->First_Name->TooltipValue = "";

		// Last_Name
		$this->Last_Name->LinkCustomAttributes = "";
		$this->Last_Name->HrefValue = "";
		$this->Last_Name->TooltipValue = "";

		// Email
		$this->_Email->LinkCustomAttributes = "";
		$this->_Email->HrefValue = "";
		$this->_Email->TooltipValue = "";

		// Telefono_movil
		$this->Telefono_movil->LinkCustomAttributes = "";
		$this->Telefono_movil->HrefValue = "";
		$this->Telefono_movil->TooltipValue = "";

		// Telefono_fijo
		$this->Telefono_fijo->LinkCustomAttributes = "";
		$this->Telefono_fijo->HrefValue = "";
		$this->Telefono_fijo->TooltipValue = "";

		// Fecha_nacimiento
		$this->Fecha_nacimiento->LinkCustomAttributes = "";
		if (!ew_Empty($this->No_documento->CurrentValue)) {
			$this->Fecha_nacimiento->HrefValue = ((!empty($this->No_documento->ViewValue)) ? ew_RemoveHtml($this->No_documento->ViewValue) : $this->No_documento->CurrentValue); // Add prefix/suffix
			$this->Fecha_nacimiento->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->Fecha_nacimiento->HrefValue = ew_FullUrl($this->Fecha_nacimiento->HrefValue, "href");
		} else {
			$this->Fecha_nacimiento->HrefValue = "";
		}
		$this->Fecha_nacimiento->TooltipValue = "";

		// Report_To
		$this->Report_To->LinkCustomAttributes = "";
		$this->Report_To->HrefValue = "";
		$this->Report_To->TooltipValue = "";

		// Activated
		$this->Activated->LinkCustomAttributes = "";
		$this->Activated->HrefValue = "";
		$this->Activated->TooltipValue = "";

		// Locked
		$this->Locked->LinkCustomAttributes = "";
		$this->Locked->HrefValue = "";
		$this->Locked->TooltipValue = "";

		// token
		$this->token->LinkCustomAttributes = "";
		$this->token->HrefValue = "";
		$this->token->TooltipValue = "";

		// acceso_app
		$this->acceso_app->LinkCustomAttributes = "";
		$this->acceso_app->HrefValue = "";
		$this->acceso_app->TooltipValue = "";

		// observaciones
		$this->observaciones->LinkCustomAttributes = "";
		$this->observaciones->HrefValue = "";
		$this->observaciones->TooltipValue = "";

		// fecha_ingreso
		$this->fecha_ingreso->LinkCustomAttributes = "";
		$this->fecha_ingreso->HrefValue = "";
		$this->fecha_ingreso->TooltipValue = "";

		// Profile
		$this->Profile->LinkCustomAttributes = "";
		$this->Profile->HrefValue = "";
		$this->Profile->TooltipValue = "";

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

		// id_user
		$this->id_user->EditAttrs["class"] = "form-control";
		$this->id_user->EditCustomAttributes = "";
		$this->id_user->EditValue = $this->id_user->CurrentValue;
		$this->id_user->ViewCustomAttributes = "";

		// User_Level
		$this->User_Level->EditAttrs["class"] = "form-control";
		$this->User_Level->EditCustomAttributes = "";
		if (!$Security->CanAdmin()) { // System admin
			$this->User_Level->EditValue = $Language->Phrase("PasswordMask");
		} else {
		}

		// id_user_creator
		$this->id_user_creator->EditAttrs["class"] = "form-control";
		$this->id_user_creator->EditCustomAttributes = "";
		$this->id_user_creator->EditValue = $this->id_user_creator->CurrentValue;
		$this->id_user_creator->PlaceHolder = ew_RemoveHtml($this->id_user_creator->FldCaption());

		// Username
		$this->Username->EditAttrs["class"] = "form-control";
		$this->Username->EditCustomAttributes = "";
		$this->Username->EditValue = $this->Username->CurrentValue;
		$this->Username->PlaceHolder = ew_RemoveHtml($this->Username->FldCaption());

		// Password
		$this->Password->EditAttrs["class"] = "form-control ewPasswordStrength";
		$this->Password->EditCustomAttributes = "";
		$this->Password->EditValue = $this->Password->CurrentValue;
		$this->Password->PlaceHolder = ew_RemoveHtml($this->Password->FldCaption());

		// No_documento
		$this->No_documento->EditAttrs["class"] = "form-control";
		$this->No_documento->EditCustomAttributes = "";
		$this->No_documento->EditValue = $this->No_documento->CurrentValue;
		$this->No_documento->PlaceHolder = ew_RemoveHtml($this->No_documento->FldCaption());

		// Tipo_documento
		$this->Tipo_documento->EditAttrs["class"] = "form-control";
		$this->Tipo_documento->EditCustomAttributes = "";
		$this->Tipo_documento->EditValue = $this->Tipo_documento->CurrentValue;
		$this->Tipo_documento->PlaceHolder = ew_RemoveHtml($this->Tipo_documento->FldCaption());

		// First_Name
		$this->First_Name->EditAttrs["class"] = "form-control";
		$this->First_Name->EditCustomAttributes = "";
		$this->First_Name->EditValue = $this->First_Name->CurrentValue;
		$this->First_Name->PlaceHolder = ew_RemoveHtml($this->First_Name->FldCaption());

		// Last_Name
		$this->Last_Name->EditAttrs["class"] = "form-control";
		$this->Last_Name->EditCustomAttributes = "";
		$this->Last_Name->EditValue = $this->Last_Name->CurrentValue;
		$this->Last_Name->PlaceHolder = ew_RemoveHtml($this->Last_Name->FldCaption());

		// Email
		$this->_Email->EditAttrs["class"] = "form-control";
		$this->_Email->EditCustomAttributes = "";
		$this->_Email->EditValue = $this->_Email->CurrentValue;
		$this->_Email->PlaceHolder = ew_RemoveHtml($this->_Email->FldCaption());

		// Telefono_movil
		$this->Telefono_movil->EditAttrs["class"] = "form-control";
		$this->Telefono_movil->EditCustomAttributes = "";
		$this->Telefono_movil->EditValue = $this->Telefono_movil->CurrentValue;
		$this->Telefono_movil->PlaceHolder = ew_RemoveHtml($this->Telefono_movil->FldCaption());

		// Telefono_fijo
		$this->Telefono_fijo->EditAttrs["class"] = "form-control";
		$this->Telefono_fijo->EditCustomAttributes = "";
		$this->Telefono_fijo->EditValue = $this->Telefono_fijo->CurrentValue;
		$this->Telefono_fijo->PlaceHolder = ew_RemoveHtml($this->Telefono_fijo->FldCaption());

		// Fecha_nacimiento
		$this->Fecha_nacimiento->EditAttrs["class"] = "form-control";
		$this->Fecha_nacimiento->EditCustomAttributes = "";
		$this->Fecha_nacimiento->EditValue = ew_FormatDateTime($this->Fecha_nacimiento->CurrentValue, 17);
		$this->Fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->Fecha_nacimiento->FldCaption());

		// Report_To
		$this->Report_To->EditAttrs["class"] = "form-control";
		$this->Report_To->EditCustomAttributes = "";
		$this->Report_To->EditValue = $this->Report_To->CurrentValue;
		$this->Report_To->PlaceHolder = ew_RemoveHtml($this->Report_To->FldCaption());

		// Activated
		$this->Activated->EditCustomAttributes = "";
		$this->Activated->EditValue = $this->Activated->Options(FALSE);

		// Locked
		$this->Locked->EditCustomAttributes = "";
		$this->Locked->EditValue = $this->Locked->Options(FALSE);

		// token
		$this->token->EditAttrs["class"] = "form-control";
		$this->token->EditCustomAttributes = "";
		$this->token->EditValue = $this->token->CurrentValue;
		$this->token->PlaceHolder = ew_RemoveHtml($this->token->FldCaption());

		// acceso_app
		$this->acceso_app->EditCustomAttributes = "";
		$this->acceso_app->EditValue = $this->acceso_app->Options(FALSE);

		// observaciones
		$this->observaciones->EditAttrs["class"] = "form-control";
		$this->observaciones->EditCustomAttributes = "";
		$this->observaciones->EditValue = $this->observaciones->CurrentValue;
		$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

		// fecha_ingreso
		$this->fecha_ingreso->EditAttrs["class"] = "form-control";
		$this->fecha_ingreso->EditCustomAttributes = "";
		$this->fecha_ingreso->EditValue = ew_FormatDateTime($this->fecha_ingreso->CurrentValue, 8);
		$this->fecha_ingreso->PlaceHolder = ew_RemoveHtml($this->fecha_ingreso->FldCaption());

		// Profile
		$this->Profile->EditAttrs["class"] = "form-control";
		$this->Profile->EditCustomAttributes = "";
		$this->Profile->EditValue = $this->Profile->CurrentValue;
		$this->Profile->PlaceHolder = ew_RemoveHtml($this->Profile->FldCaption());

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
					if ($this->id_user->Exportable) $Doc->ExportCaption($this->id_user);
					if ($this->User_Level->Exportable) $Doc->ExportCaption($this->User_Level);
					if ($this->Username->Exportable) $Doc->ExportCaption($this->Username);
					if ($this->No_documento->Exportable) $Doc->ExportCaption($this->No_documento);
					if ($this->Tipo_documento->Exportable) $Doc->ExportCaption($this->Tipo_documento);
					if ($this->First_Name->Exportable) $Doc->ExportCaption($this->First_Name);
					if ($this->Last_Name->Exportable) $Doc->ExportCaption($this->Last_Name);
					if ($this->_Email->Exportable) $Doc->ExportCaption($this->_Email);
					if ($this->Telefono_movil->Exportable) $Doc->ExportCaption($this->Telefono_movil);
					if ($this->Telefono_fijo->Exportable) $Doc->ExportCaption($this->Telefono_fijo);
					if ($this->Fecha_nacimiento->Exportable) $Doc->ExportCaption($this->Fecha_nacimiento);
					if ($this->Report_To->Exportable) $Doc->ExportCaption($this->Report_To);
					if ($this->Activated->Exportable) $Doc->ExportCaption($this->Activated);
					if ($this->Locked->Exportable) $Doc->ExportCaption($this->Locked);
					if ($this->acceso_app->Exportable) $Doc->ExportCaption($this->acceso_app);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
				} else {
					if ($this->id_user->Exportable) $Doc->ExportCaption($this->id_user);
					if ($this->User_Level->Exportable) $Doc->ExportCaption($this->User_Level);
					if ($this->Username->Exportable) $Doc->ExportCaption($this->Username);
					if ($this->No_documento->Exportable) $Doc->ExportCaption($this->No_documento);
					if ($this->First_Name->Exportable) $Doc->ExportCaption($this->First_Name);
					if ($this->Last_Name->Exportable) $Doc->ExportCaption($this->Last_Name);
					if ($this->_Email->Exportable) $Doc->ExportCaption($this->_Email);
					if ($this->Telefono_movil->Exportable) $Doc->ExportCaption($this->Telefono_movil);
					if ($this->Fecha_nacimiento->Exportable) $Doc->ExportCaption($this->Fecha_nacimiento);
					if ($this->Locked->Exportable) $Doc->ExportCaption($this->Locked);
					if ($this->acceso_app->Exportable) $Doc->ExportCaption($this->acceso_app);
					if ($this->fecha_ingreso->Exportable) $Doc->ExportCaption($this->fecha_ingreso);
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
						if ($this->id_user->Exportable) $Doc->ExportField($this->id_user);
						if ($this->User_Level->Exportable) $Doc->ExportField($this->User_Level);
						if ($this->Username->Exportable) $Doc->ExportField($this->Username);
						if ($this->No_documento->Exportable) $Doc->ExportField($this->No_documento);
						if ($this->Tipo_documento->Exportable) $Doc->ExportField($this->Tipo_documento);
						if ($this->First_Name->Exportable) $Doc->ExportField($this->First_Name);
						if ($this->Last_Name->Exportable) $Doc->ExportField($this->Last_Name);
						if ($this->_Email->Exportable) $Doc->ExportField($this->_Email);
						if ($this->Telefono_movil->Exportable) $Doc->ExportField($this->Telefono_movil);
						if ($this->Telefono_fijo->Exportable) $Doc->ExportField($this->Telefono_fijo);
						if ($this->Fecha_nacimiento->Exportable) $Doc->ExportField($this->Fecha_nacimiento);
						if ($this->Report_To->Exportable) $Doc->ExportField($this->Report_To);
						if ($this->Activated->Exportable) $Doc->ExportField($this->Activated);
						if ($this->Locked->Exportable) $Doc->ExportField($this->Locked);
						if ($this->acceso_app->Exportable) $Doc->ExportField($this->acceso_app);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
					} else {
						if ($this->id_user->Exportable) $Doc->ExportField($this->id_user);
						if ($this->User_Level->Exportable) $Doc->ExportField($this->User_Level);
						if ($this->Username->Exportable) $Doc->ExportField($this->Username);
						if ($this->No_documento->Exportable) $Doc->ExportField($this->No_documento);
						if ($this->First_Name->Exportable) $Doc->ExportField($this->First_Name);
						if ($this->Last_Name->Exportable) $Doc->ExportField($this->Last_Name);
						if ($this->_Email->Exportable) $Doc->ExportField($this->_Email);
						if ($this->Telefono_movil->Exportable) $Doc->ExportField($this->Telefono_movil);
						if ($this->Fecha_nacimiento->Exportable) $Doc->ExportField($this->Fecha_nacimiento);
						if ($this->Locked->Exportable) $Doc->ExportField($this->Locked);
						if ($this->acceso_app->Exportable) $Doc->ExportField($this->acceso_app);
						if ($this->fecha_ingreso->Exportable) $Doc->ExportField($this->fecha_ingreso);
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
