<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "direccionesinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$direcciones_delete = NULL; // Initialize page object first

class cdirecciones_delete extends cdirecciones {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'direcciones';

	// Page object name
	var $PageObjName = 'direcciones_delete';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (direcciones)
		if (!isset($GLOBALS["direcciones"]) || get_class($GLOBALS["direcciones"]) == "cdirecciones") {
			$GLOBALS["direcciones"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["direcciones"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'direcciones', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

		// User table object (users)
		if (!isset($UserTable)) {
			$UserTable = new cusers();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("direccioneslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->Id->SetVisibility();
		$this->Id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->id_fuente->SetVisibility();
		$this->id_gestion->SetVisibility();
		$this->id_tipodireccion->SetVisibility();
		$this->tipo_documento->SetVisibility();
		$this->no_documento->SetVisibility();
		$this->nombres->SetVisibility();
		$this->paterno->SetVisibility();
		$this->materno->SetVisibility();
		$this->pais->SetVisibility();
		$this->departamento->SetVisibility();
		$this->provincia->SetVisibility();
		$this->municipio->SetVisibility();
		$this->localidad->SetVisibility();
		$this->distrito->SetVisibility();
		$this->zona->SetVisibility();
		$this->direccion1->SetVisibility();
		$this->direccion2->SetVisibility();
		$this->direccion3->SetVisibility();
		$this->direccion4->SetVisibility();
		$this->mapa->SetVisibility();
		$this->longitud->SetVisibility();
		$this->latitud->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $direcciones;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($direcciones);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("direccioneslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in direcciones class, direccionesinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("direccioneslist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->Id->setDbValue($row['Id']);
		$this->id_fuente->setDbValue($row['id_fuente']);
		$this->id_gestion->setDbValue($row['id_gestion']);
		$this->id_tipodireccion->setDbValue($row['id_tipodireccion']);
		$this->tipo_documento->setDbValue($row['tipo_documento']);
		$this->no_documento->setDbValue($row['no_documento']);
		$this->nombres->setDbValue($row['nombres']);
		$this->paterno->setDbValue($row['paterno']);
		$this->materno->setDbValue($row['materno']);
		$this->pais->setDbValue($row['pais']);
		$this->departamento->setDbValue($row['departamento']);
		$this->provincia->setDbValue($row['provincia']);
		$this->municipio->setDbValue($row['municipio']);
		$this->localidad->setDbValue($row['localidad']);
		$this->distrito->setDbValue($row['distrito']);
		$this->zona->setDbValue($row['zona']);
		$this->direccion1->setDbValue($row['direccion1']);
		$this->direccion2->setDbValue($row['direccion2']);
		$this->direccion3->setDbValue($row['direccion3']);
		$this->direccion4->setDbValue($row['direccion4']);
		$this->mapa->setDbValue($row['mapa']);
		$this->longitud->setDbValue($row['longitud']);
		$this->latitud->setDbValue($row['latitud']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['Id'] = NULL;
		$row['id_fuente'] = NULL;
		$row['id_gestion'] = NULL;
		$row['id_tipodireccion'] = NULL;
		$row['tipo_documento'] = NULL;
		$row['no_documento'] = NULL;
		$row['nombres'] = NULL;
		$row['paterno'] = NULL;
		$row['materno'] = NULL;
		$row['pais'] = NULL;
		$row['departamento'] = NULL;
		$row['provincia'] = NULL;
		$row['municipio'] = NULL;
		$row['localidad'] = NULL;
		$row['distrito'] = NULL;
		$row['zona'] = NULL;
		$row['direccion1'] = NULL;
		$row['direccion2'] = NULL;
		$row['direccion3'] = NULL;
		$row['direccion4'] = NULL;
		$row['mapa'] = NULL;
		$row['longitud'] = NULL;
		$row['latitud'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->id_fuente->DbValue = $row['id_fuente'];
		$this->id_gestion->DbValue = $row['id_gestion'];
		$this->id_tipodireccion->DbValue = $row['id_tipodireccion'];
		$this->tipo_documento->DbValue = $row['tipo_documento'];
		$this->no_documento->DbValue = $row['no_documento'];
		$this->nombres->DbValue = $row['nombres'];
		$this->paterno->DbValue = $row['paterno'];
		$this->materno->DbValue = $row['materno'];
		$this->pais->DbValue = $row['pais'];
		$this->departamento->DbValue = $row['departamento'];
		$this->provincia->DbValue = $row['provincia'];
		$this->municipio->DbValue = $row['municipio'];
		$this->localidad->DbValue = $row['localidad'];
		$this->distrito->DbValue = $row['distrito'];
		$this->zona->DbValue = $row['zona'];
		$this->direccion1->DbValue = $row['direccion1'];
		$this->direccion2->DbValue = $row['direccion2'];
		$this->direccion3->DbValue = $row['direccion3'];
		$this->direccion4->DbValue = $row['direccion4'];
		$this->mapa->DbValue = $row['mapa'];
		$this->longitud->DbValue = $row['longitud'];
		$this->latitud->DbValue = $row['latitud'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['Id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("direccioneslist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($direcciones_delete)) $direcciones_delete = new cdirecciones_delete();

// Page init
$direcciones_delete->Page_Init();

// Page main
$direcciones_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$direcciones_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fdireccionesdelete = new ew_Form("fdireccionesdelete", "delete");

// Form_CustomValidate event
fdireccionesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdireccionesdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdireccionesdelete.Lists["x_id_fuente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"fuentes"};
fdireccionesdelete.Lists["x_id_fuente"].Data = "<?php echo $direcciones_delete->id_fuente->LookupFilterQuery(FALSE, "delete") ?>";
fdireccionesdelete.Lists["x_id_gestion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gestiones"};
fdireccionesdelete.Lists["x_id_gestion"].Data = "<?php echo $direcciones_delete->id_gestion->LookupFilterQuery(FALSE, "delete") ?>";
fdireccionesdelete.Lists["x_id_tipodireccion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipo_direccion"};
fdireccionesdelete.Lists["x_id_tipodireccion"].Data = "<?php echo $direcciones_delete->id_tipodireccion->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $direcciones_delete->ShowPageHeader(); ?>
<?php
$direcciones_delete->ShowMessage();
?>
<form name="fdireccionesdelete" id="fdireccionesdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($direcciones_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $direcciones_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="direcciones">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($direcciones_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($direcciones->Id->Visible) { // Id ?>
		<th class="<?php echo $direcciones->Id->HeaderCellClass() ?>"><span id="elh_direcciones_Id" class="direcciones_Id"><?php echo $direcciones->Id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->id_fuente->Visible) { // id_fuente ?>
		<th class="<?php echo $direcciones->id_fuente->HeaderCellClass() ?>"><span id="elh_direcciones_id_fuente" class="direcciones_id_fuente"><?php echo $direcciones->id_fuente->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->id_gestion->Visible) { // id_gestion ?>
		<th class="<?php echo $direcciones->id_gestion->HeaderCellClass() ?>"><span id="elh_direcciones_id_gestion" class="direcciones_id_gestion"><?php echo $direcciones->id_gestion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->id_tipodireccion->Visible) { // id_tipodireccion ?>
		<th class="<?php echo $direcciones->id_tipodireccion->HeaderCellClass() ?>"><span id="elh_direcciones_id_tipodireccion" class="direcciones_id_tipodireccion"><?php echo $direcciones->id_tipodireccion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->tipo_documento->Visible) { // tipo_documento ?>
		<th class="<?php echo $direcciones->tipo_documento->HeaderCellClass() ?>"><span id="elh_direcciones_tipo_documento" class="direcciones_tipo_documento"><?php echo $direcciones->tipo_documento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->no_documento->Visible) { // no_documento ?>
		<th class="<?php echo $direcciones->no_documento->HeaderCellClass() ?>"><span id="elh_direcciones_no_documento" class="direcciones_no_documento"><?php echo $direcciones->no_documento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->nombres->Visible) { // nombres ?>
		<th class="<?php echo $direcciones->nombres->HeaderCellClass() ?>"><span id="elh_direcciones_nombres" class="direcciones_nombres"><?php echo $direcciones->nombres->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->paterno->Visible) { // paterno ?>
		<th class="<?php echo $direcciones->paterno->HeaderCellClass() ?>"><span id="elh_direcciones_paterno" class="direcciones_paterno"><?php echo $direcciones->paterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->materno->Visible) { // materno ?>
		<th class="<?php echo $direcciones->materno->HeaderCellClass() ?>"><span id="elh_direcciones_materno" class="direcciones_materno"><?php echo $direcciones->materno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->pais->Visible) { // pais ?>
		<th class="<?php echo $direcciones->pais->HeaderCellClass() ?>"><span id="elh_direcciones_pais" class="direcciones_pais"><?php echo $direcciones->pais->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->departamento->Visible) { // departamento ?>
		<th class="<?php echo $direcciones->departamento->HeaderCellClass() ?>"><span id="elh_direcciones_departamento" class="direcciones_departamento"><?php echo $direcciones->departamento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->provincia->Visible) { // provincia ?>
		<th class="<?php echo $direcciones->provincia->HeaderCellClass() ?>"><span id="elh_direcciones_provincia" class="direcciones_provincia"><?php echo $direcciones->provincia->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->municipio->Visible) { // municipio ?>
		<th class="<?php echo $direcciones->municipio->HeaderCellClass() ?>"><span id="elh_direcciones_municipio" class="direcciones_municipio"><?php echo $direcciones->municipio->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->localidad->Visible) { // localidad ?>
		<th class="<?php echo $direcciones->localidad->HeaderCellClass() ?>"><span id="elh_direcciones_localidad" class="direcciones_localidad"><?php echo $direcciones->localidad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->distrito->Visible) { // distrito ?>
		<th class="<?php echo $direcciones->distrito->HeaderCellClass() ?>"><span id="elh_direcciones_distrito" class="direcciones_distrito"><?php echo $direcciones->distrito->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->zona->Visible) { // zona ?>
		<th class="<?php echo $direcciones->zona->HeaderCellClass() ?>"><span id="elh_direcciones_zona" class="direcciones_zona"><?php echo $direcciones->zona->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->direccion1->Visible) { // direccion1 ?>
		<th class="<?php echo $direcciones->direccion1->HeaderCellClass() ?>"><span id="elh_direcciones_direccion1" class="direcciones_direccion1"><?php echo $direcciones->direccion1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->direccion2->Visible) { // direccion2 ?>
		<th class="<?php echo $direcciones->direccion2->HeaderCellClass() ?>"><span id="elh_direcciones_direccion2" class="direcciones_direccion2"><?php echo $direcciones->direccion2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->direccion3->Visible) { // direccion3 ?>
		<th class="<?php echo $direcciones->direccion3->HeaderCellClass() ?>"><span id="elh_direcciones_direccion3" class="direcciones_direccion3"><?php echo $direcciones->direccion3->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->direccion4->Visible) { // direccion4 ?>
		<th class="<?php echo $direcciones->direccion4->HeaderCellClass() ?>"><span id="elh_direcciones_direccion4" class="direcciones_direccion4"><?php echo $direcciones->direccion4->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->mapa->Visible) { // mapa ?>
		<th class="<?php echo $direcciones->mapa->HeaderCellClass() ?>"><span id="elh_direcciones_mapa" class="direcciones_mapa"><?php echo $direcciones->mapa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->longitud->Visible) { // longitud ?>
		<th class="<?php echo $direcciones->longitud->HeaderCellClass() ?>"><span id="elh_direcciones_longitud" class="direcciones_longitud"><?php echo $direcciones->longitud->FldCaption() ?></span></th>
<?php } ?>
<?php if ($direcciones->latitud->Visible) { // latitud ?>
		<th class="<?php echo $direcciones->latitud->HeaderCellClass() ?>"><span id="elh_direcciones_latitud" class="direcciones_latitud"><?php echo $direcciones->latitud->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$direcciones_delete->RecCnt = 0;
$i = 0;
while (!$direcciones_delete->Recordset->EOF) {
	$direcciones_delete->RecCnt++;
	$direcciones_delete->RowCnt++;

	// Set row properties
	$direcciones->ResetAttrs();
	$direcciones->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$direcciones_delete->LoadRowValues($direcciones_delete->Recordset);

	// Render row
	$direcciones_delete->RenderRow();
?>
	<tr<?php echo $direcciones->RowAttributes() ?>>
<?php if ($direcciones->Id->Visible) { // Id ?>
		<td<?php echo $direcciones->Id->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_Id" class="direcciones_Id">
<span<?php echo $direcciones->Id->ViewAttributes() ?>>
<?php echo $direcciones->Id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->id_fuente->Visible) { // id_fuente ?>
		<td<?php echo $direcciones->id_fuente->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_id_fuente" class="direcciones_id_fuente">
<span<?php echo $direcciones->id_fuente->ViewAttributes() ?>>
<?php echo $direcciones->id_fuente->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->id_gestion->Visible) { // id_gestion ?>
		<td<?php echo $direcciones->id_gestion->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_id_gestion" class="direcciones_id_gestion">
<span<?php echo $direcciones->id_gestion->ViewAttributes() ?>>
<?php echo $direcciones->id_gestion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->id_tipodireccion->Visible) { // id_tipodireccion ?>
		<td<?php echo $direcciones->id_tipodireccion->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_id_tipodireccion" class="direcciones_id_tipodireccion">
<span<?php echo $direcciones->id_tipodireccion->ViewAttributes() ?>>
<?php echo $direcciones->id_tipodireccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->tipo_documento->Visible) { // tipo_documento ?>
		<td<?php echo $direcciones->tipo_documento->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_tipo_documento" class="direcciones_tipo_documento">
<span<?php echo $direcciones->tipo_documento->ViewAttributes() ?>>
<?php echo $direcciones->tipo_documento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->no_documento->Visible) { // no_documento ?>
		<td<?php echo $direcciones->no_documento->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_no_documento" class="direcciones_no_documento">
<span<?php echo $direcciones->no_documento->ViewAttributes() ?>>
<?php echo $direcciones->no_documento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->nombres->Visible) { // nombres ?>
		<td<?php echo $direcciones->nombres->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_nombres" class="direcciones_nombres">
<span<?php echo $direcciones->nombres->ViewAttributes() ?>>
<?php echo $direcciones->nombres->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->paterno->Visible) { // paterno ?>
		<td<?php echo $direcciones->paterno->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_paterno" class="direcciones_paterno">
<span<?php echo $direcciones->paterno->ViewAttributes() ?>>
<?php echo $direcciones->paterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->materno->Visible) { // materno ?>
		<td<?php echo $direcciones->materno->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_materno" class="direcciones_materno">
<span<?php echo $direcciones->materno->ViewAttributes() ?>>
<?php echo $direcciones->materno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->pais->Visible) { // pais ?>
		<td<?php echo $direcciones->pais->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_pais" class="direcciones_pais">
<span<?php echo $direcciones->pais->ViewAttributes() ?>>
<?php echo $direcciones->pais->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->departamento->Visible) { // departamento ?>
		<td<?php echo $direcciones->departamento->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_departamento" class="direcciones_departamento">
<span<?php echo $direcciones->departamento->ViewAttributes() ?>>
<?php echo $direcciones->departamento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->provincia->Visible) { // provincia ?>
		<td<?php echo $direcciones->provincia->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_provincia" class="direcciones_provincia">
<span<?php echo $direcciones->provincia->ViewAttributes() ?>>
<?php echo $direcciones->provincia->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->municipio->Visible) { // municipio ?>
		<td<?php echo $direcciones->municipio->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_municipio" class="direcciones_municipio">
<span<?php echo $direcciones->municipio->ViewAttributes() ?>>
<?php echo $direcciones->municipio->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->localidad->Visible) { // localidad ?>
		<td<?php echo $direcciones->localidad->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_localidad" class="direcciones_localidad">
<span<?php echo $direcciones->localidad->ViewAttributes() ?>>
<?php echo $direcciones->localidad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->distrito->Visible) { // distrito ?>
		<td<?php echo $direcciones->distrito->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_distrito" class="direcciones_distrito">
<span<?php echo $direcciones->distrito->ViewAttributes() ?>>
<?php echo $direcciones->distrito->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->zona->Visible) { // zona ?>
		<td<?php echo $direcciones->zona->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_zona" class="direcciones_zona">
<span<?php echo $direcciones->zona->ViewAttributes() ?>>
<?php echo $direcciones->zona->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->direccion1->Visible) { // direccion1 ?>
		<td<?php echo $direcciones->direccion1->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_direccion1" class="direcciones_direccion1">
<span<?php echo $direcciones->direccion1->ViewAttributes() ?>>
<?php echo $direcciones->direccion1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->direccion2->Visible) { // direccion2 ?>
		<td<?php echo $direcciones->direccion2->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_direccion2" class="direcciones_direccion2">
<span<?php echo $direcciones->direccion2->ViewAttributes() ?>>
<?php echo $direcciones->direccion2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->direccion3->Visible) { // direccion3 ?>
		<td<?php echo $direcciones->direccion3->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_direccion3" class="direcciones_direccion3">
<span<?php echo $direcciones->direccion3->ViewAttributes() ?>>
<?php echo $direcciones->direccion3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->direccion4->Visible) { // direccion4 ?>
		<td<?php echo $direcciones->direccion4->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_direccion4" class="direcciones_direccion4">
<span<?php echo $direcciones->direccion4->ViewAttributes() ?>>
<?php echo $direcciones->direccion4->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->mapa->Visible) { // mapa ?>
		<td<?php echo $direcciones->mapa->CellAttributes() ?>>
<script id="orig<?php echo $direcciones_delete->RowCnt ?>_direcciones_mapa" type="text/html">
<?php echo $direcciones->mapa->ListViewValue() ?>
</script>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_mapa" class="direcciones_mapa">
<span<?php echo $direcciones->mapa->ViewAttributes() ?>><script type="text/javascript">
ewGoogleMaps[ewGoogleMaps.length] = jQuery.extend({"id":"gm_direcciones_x_mapa","name":"Google Maps","apikey":"AIzaSyDFibhqbazLZqySy6EuVE_BHRUvkhyIVLg","width":400,"width_field":null,"height":400,"height_field":null,"latitude":null,"latitude_field":"latitud","longitude":null,"longitude_field":"longitud","address":null,"address_field":null,"type":"HYBRID","type_field":null,"zoom":18,"zoom_field":null,"title":null,"title_field":"direccion","icon":null,"icon_field":null,"description":null,"description_field":null,"use_single_map":true,"single_map_width":400,"single_map_height":400,"show_map_on_top":true,"show_all_markers":true,"geocoding_delay":250,"use_marker_clusterer":false,"cluster_max_zoom":-1,"cluster_grid_size":-1,"cluster_styles":-1,"template_id":"orig<?php echo $direcciones_delete->RowCnt ?>_direcciones_mapa"}, {
	latitude: <?php echo ew_VarToJson($direcciones->latitud->CurrentValue, "undefined") ?>,
	longitude: <?php echo ew_VarToJson($direcciones->longitud->CurrentValue, "undefined") ?>
});
</script>
</span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->longitud->Visible) { // longitud ?>
		<td<?php echo $direcciones->longitud->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_longitud" class="direcciones_longitud">
<span<?php echo $direcciones->longitud->ViewAttributes() ?>>
<?php echo $direcciones->longitud->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($direcciones->latitud->Visible) { // latitud ?>
		<td<?php echo $direcciones->latitud->CellAttributes() ?>>
<span id="el<?php echo $direcciones_delete->RowCnt ?>_direcciones_latitud" class="direcciones_latitud">
<span<?php echo $direcciones->latitud->ViewAttributes() ?>>
<?php echo $direcciones->latitud->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$direcciones_delete->Recordset->MoveNext();
}
$direcciones_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $direcciones_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fdireccionesdelete.Init();
</script>
<?php
$direcciones_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$direcciones_delete->Page_Terminate();
?>
