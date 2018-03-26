<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "deudasinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "cuentasinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$deudas_delete = NULL; // Initialize page object first

class cdeudas_delete extends cdeudas {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'deudas';

	// Page object name
	var $PageObjName = 'deudas_delete';

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

		// Table object (deudas)
		if (!isset($GLOBALS["deudas"]) || get_class($GLOBALS["deudas"]) == "cdeudas") {
			$GLOBALS["deudas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["deudas"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Table object (cuentas)
		if (!isset($GLOBALS['cuentas'])) $GLOBALS['cuentas'] = new ccuentas();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'deudas', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("deudaslist.php"));
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
		$this->id_cliente->SetVisibility();
		$this->id_ciudad->SetVisibility();
		$this->id_agente->SetVisibility();
		$this->id_estadodeuda->SetVisibility();
		$this->mig_codigo_deuda->SetVisibility();
		$this->mig_fecha_desembolso->SetVisibility();
		$this->mig_moneda->SetVisibility();
		$this->mig_tasa->SetVisibility();
		$this->mig_plazo->SetVisibility();
		$this->mig_dias_mora->SetVisibility();
		$this->mig_monto_desembolso->SetVisibility();
		$this->mig_intereses->SetVisibility();
		$this->mig_cargos_gastos->SetVisibility();
		$this->mig_total_deuda->SetVisibility();

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
		global $EW_EXPORT, $deudas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($deudas);
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

		// Set up master/detail parameters
		$this->SetupMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("deudaslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in deudas class, deudasinfo.php

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
				$this->Page_Terminate("deudaslist.php"); // Return to list
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
		$this->cuenta->setDbValue($row['cuenta']);
		$this->id_cliente->setDbValue($row['id_cliente']);
		$this->id_ciudad->setDbValue($row['id_ciudad']);
		$this->id_agente->setDbValue($row['id_agente']);
		$this->id_estadodeuda->setDbValue($row['id_estadodeuda']);
		$this->mig_codigo_deuda->setDbValue($row['mig_codigo_deuda']);
		$this->mig_tipo_operacion->setDbValue($row['mig_tipo_operacion']);
		$this->mig_fecha_desembolso->setDbValue($row['mig_fecha_desembolso']);
		$this->mig_fecha_estado->setDbValue($row['mig_fecha_estado']);
		$this->mig_anios_castigo->setDbValue($row['mig_anios_castigo']);
		$this->mig_tipo_garantia->setDbValue($row['mig_tipo_garantia']);
		$this->mig_real->setDbValue($row['mig_real']);
		$this->mig_actividad_economica->setDbValue($row['mig_actividad_economica']);
		$this->mig_agencia->setDbValue($row['mig_agencia']);
		$this->mig_no_juicio->setDbValue($row['mig_no_juicio']);
		$this->mig_nombre_abogado->setDbValue($row['mig_nombre_abogado']);
		$this->mig_fase_procesal->setDbValue($row['mig_fase_procesal']);
		$this->mig_moneda->setDbValue($row['mig_moneda']);
		$this->mig_tasa->setDbValue($row['mig_tasa']);
		$this->mig_plazo->setDbValue($row['mig_plazo']);
		$this->mig_dias_mora->setDbValue($row['mig_dias_mora']);
		$this->mig_monto_desembolso->setDbValue($row['mig_monto_desembolso']);
		$this->mig_total_cartera->setDbValue($row['mig_total_cartera']);
		$this->mig_capital->setDbValue($row['mig_capital']);
		$this->mig_intereses->setDbValue($row['mig_intereses']);
		$this->mig_cargos_gastos->setDbValue($row['mig_cargos_gastos']);
		$this->mig_total_deuda->setDbValue($row['mig_total_deuda']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['Id'] = NULL;
		$row['cuenta'] = NULL;
		$row['id_cliente'] = NULL;
		$row['id_ciudad'] = NULL;
		$row['id_agente'] = NULL;
		$row['id_estadodeuda'] = NULL;
		$row['mig_codigo_deuda'] = NULL;
		$row['mig_tipo_operacion'] = NULL;
		$row['mig_fecha_desembolso'] = NULL;
		$row['mig_fecha_estado'] = NULL;
		$row['mig_anios_castigo'] = NULL;
		$row['mig_tipo_garantia'] = NULL;
		$row['mig_real'] = NULL;
		$row['mig_actividad_economica'] = NULL;
		$row['mig_agencia'] = NULL;
		$row['mig_no_juicio'] = NULL;
		$row['mig_nombre_abogado'] = NULL;
		$row['mig_fase_procesal'] = NULL;
		$row['mig_moneda'] = NULL;
		$row['mig_tasa'] = NULL;
		$row['mig_plazo'] = NULL;
		$row['mig_dias_mora'] = NULL;
		$row['mig_monto_desembolso'] = NULL;
		$row['mig_total_cartera'] = NULL;
		$row['mig_capital'] = NULL;
		$row['mig_intereses'] = NULL;
		$row['mig_cargos_gastos'] = NULL;
		$row['mig_total_deuda'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->cuenta->DbValue = $row['cuenta'];
		$this->id_cliente->DbValue = $row['id_cliente'];
		$this->id_ciudad->DbValue = $row['id_ciudad'];
		$this->id_agente->DbValue = $row['id_agente'];
		$this->id_estadodeuda->DbValue = $row['id_estadodeuda'];
		$this->mig_codigo_deuda->DbValue = $row['mig_codigo_deuda'];
		$this->mig_tipo_operacion->DbValue = $row['mig_tipo_operacion'];
		$this->mig_fecha_desembolso->DbValue = $row['mig_fecha_desembolso'];
		$this->mig_fecha_estado->DbValue = $row['mig_fecha_estado'];
		$this->mig_anios_castigo->DbValue = $row['mig_anios_castigo'];
		$this->mig_tipo_garantia->DbValue = $row['mig_tipo_garantia'];
		$this->mig_real->DbValue = $row['mig_real'];
		$this->mig_actividad_economica->DbValue = $row['mig_actividad_economica'];
		$this->mig_agencia->DbValue = $row['mig_agencia'];
		$this->mig_no_juicio->DbValue = $row['mig_no_juicio'];
		$this->mig_nombre_abogado->DbValue = $row['mig_nombre_abogado'];
		$this->mig_fase_procesal->DbValue = $row['mig_fase_procesal'];
		$this->mig_moneda->DbValue = $row['mig_moneda'];
		$this->mig_tasa->DbValue = $row['mig_tasa'];
		$this->mig_plazo->DbValue = $row['mig_plazo'];
		$this->mig_dias_mora->DbValue = $row['mig_dias_mora'];
		$this->mig_monto_desembolso->DbValue = $row['mig_monto_desembolso'];
		$this->mig_total_cartera->DbValue = $row['mig_total_cartera'];
		$this->mig_capital->DbValue = $row['mig_capital'];
		$this->mig_intereses->DbValue = $row['mig_intereses'];
		$this->mig_cargos_gastos->DbValue = $row['mig_cargos_gastos'];
		$this->mig_total_deuda->DbValue = $row['mig_total_deuda'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->mig_tasa->FormValue == $this->mig_tasa->CurrentValue && is_numeric(ew_StrToFloat($this->mig_tasa->CurrentValue)))
			$this->mig_tasa->CurrentValue = ew_StrToFloat($this->mig_tasa->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_plazo->FormValue == $this->mig_plazo->CurrentValue && is_numeric(ew_StrToFloat($this->mig_plazo->CurrentValue)))
			$this->mig_plazo->CurrentValue = ew_StrToFloat($this->mig_plazo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_dias_mora->FormValue == $this->mig_dias_mora->CurrentValue && is_numeric(ew_StrToFloat($this->mig_dias_mora->CurrentValue)))
			$this->mig_dias_mora->CurrentValue = ew_StrToFloat($this->mig_dias_mora->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_monto_desembolso->FormValue == $this->mig_monto_desembolso->CurrentValue && is_numeric(ew_StrToFloat($this->mig_monto_desembolso->CurrentValue)))
			$this->mig_monto_desembolso->CurrentValue = ew_StrToFloat($this->mig_monto_desembolso->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_intereses->FormValue == $this->mig_intereses->CurrentValue && is_numeric(ew_StrToFloat($this->mig_intereses->CurrentValue)))
			$this->mig_intereses->CurrentValue = ew_StrToFloat($this->mig_intereses->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_cargos_gastos->FormValue == $this->mig_cargos_gastos->CurrentValue && is_numeric(ew_StrToFloat($this->mig_cargos_gastos->CurrentValue)))
			$this->mig_cargos_gastos->CurrentValue = ew_StrToFloat($this->mig_cargos_gastos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_total_deuda->FormValue == $this->mig_total_deuda->CurrentValue && is_numeric(ew_StrToFloat($this->mig_total_deuda->CurrentValue)))
			$this->mig_total_deuda->CurrentValue = ew_StrToFloat($this->mig_total_deuda->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Id
		// cuenta

		$this->cuenta->CellCssStyle = "white-space: nowrap;";

		// id_cliente
		// id_ciudad
		// id_agente
		// id_estadodeuda
		// mig_codigo_deuda
		// mig_tipo_operacion
		// mig_fecha_desembolso
		// mig_fecha_estado
		// mig_anios_castigo
		// mig_tipo_garantia
		// mig_real
		// mig_actividad_economica
		// mig_agencia
		// mig_no_juicio
		// mig_nombre_abogado
		// mig_fase_procesal
		// mig_moneda
		// mig_tasa
		// mig_plazo
		// mig_dias_mora
		// mig_monto_desembolso
		// mig_total_cartera
		// mig_capital
		// mig_intereses
		// mig_cargos_gastos
		// mig_total_deuda

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// id_cliente
		if (strval($this->id_cliente->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_cliente->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuentas`";
		$sWhereWrk = "";
		$this->id_cliente->LookupFilters = array();
		$lookuptblfilter = "`estado`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_cliente, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_cliente->ViewValue = $this->id_cliente->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_cliente->ViewValue = $this->id_cliente->CurrentValue;
			}
		} else {
			$this->id_cliente->ViewValue = NULL;
		}
		$this->id_cliente->ViewCustomAttributes = "";

		// id_ciudad
		if (strval($this->id_ciudad->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_ciudad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `ciudades`";
		$sWhereWrk = "";
		$this->id_ciudad->LookupFilters = array();
		$lookuptblfilter = "`estado`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_ciudad, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_ciudad->ViewValue = $this->id_ciudad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_ciudad->ViewValue = $this->id_ciudad->CurrentValue;
			}
		} else {
			$this->id_ciudad->ViewValue = NULL;
		}
		$this->id_ciudad->ViewCustomAttributes = "";

		// id_agente
		if (strval($this->id_agente->CurrentValue) <> "") {
			$sFilterWrk = "`id_user`" . ew_SearchString("=", $this->id_agente->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_user`, `First_Name` AS `DispFld`, `Last_Name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->id_agente->LookupFilters = array();
		$lookuptblfilter = "`User_Level`=2";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_agente, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `First_Name`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->id_agente->ViewValue = $this->id_agente->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_agente->ViewValue = $this->id_agente->CurrentValue;
			}
		} else {
			$this->id_agente->ViewValue = NULL;
		}
		$this->id_agente->ViewCustomAttributes = "";

		// id_estadodeuda
		if (strval($this->id_estadodeuda->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_estadodeuda->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estado_deuda`";
		$sWhereWrk = "";
		$this->id_estadodeuda->LookupFilters = array();
		$lookuptblfilter = "`estado`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_estadodeuda, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_estadodeuda->ViewValue = $this->id_estadodeuda->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_estadodeuda->ViewValue = $this->id_estadodeuda->CurrentValue;
			}
		} else {
			$this->id_estadodeuda->ViewValue = NULL;
		}
		$this->id_estadodeuda->ViewCustomAttributes = "";

		// mig_codigo_deuda
		$this->mig_codigo_deuda->ViewValue = $this->mig_codigo_deuda->CurrentValue;
		$this->mig_codigo_deuda->ViewCustomAttributes = "";

		// mig_tipo_operacion
		$this->mig_tipo_operacion->ViewValue = $this->mig_tipo_operacion->CurrentValue;
		$this->mig_tipo_operacion->ViewCustomAttributes = "";

		// mig_fecha_desembolso
		$this->mig_fecha_desembolso->ViewValue = $this->mig_fecha_desembolso->CurrentValue;
		$this->mig_fecha_desembolso->ViewValue = ew_FormatDateTime($this->mig_fecha_desembolso->ViewValue, 7);
		$this->mig_fecha_desembolso->ViewCustomAttributes = "";

		// mig_fecha_estado
		$this->mig_fecha_estado->ViewValue = $this->mig_fecha_estado->CurrentValue;
		$this->mig_fecha_estado->ViewValue = ew_FormatDateTime($this->mig_fecha_estado->ViewValue, 7);
		$this->mig_fecha_estado->ViewCustomAttributes = "";

		// mig_anios_castigo
		$this->mig_anios_castigo->ViewValue = $this->mig_anios_castigo->CurrentValue;
		$this->mig_anios_castigo->ViewValue = ew_FormatNumber($this->mig_anios_castigo->ViewValue, 0, 0, 0, 0);
		$this->mig_anios_castigo->ViewCustomAttributes = "";

		// mig_tipo_garantia
		$this->mig_tipo_garantia->ViewValue = $this->mig_tipo_garantia->CurrentValue;
		$this->mig_tipo_garantia->ViewCustomAttributes = "";

		// mig_real
		$this->mig_real->ViewValue = $this->mig_real->CurrentValue;
		$this->mig_real->ViewCustomAttributes = "";

		// mig_actividad_economica
		$this->mig_actividad_economica->ViewValue = $this->mig_actividad_economica->CurrentValue;
		$this->mig_actividad_economica->ViewCustomAttributes = "";

		// mig_agencia
		$this->mig_agencia->ViewValue = $this->mig_agencia->CurrentValue;
		$this->mig_agencia->ViewCustomAttributes = "";

		// mig_no_juicio
		$this->mig_no_juicio->ViewValue = $this->mig_no_juicio->CurrentValue;
		$this->mig_no_juicio->ViewCustomAttributes = "";

		// mig_nombre_abogado
		$this->mig_nombre_abogado->ViewValue = $this->mig_nombre_abogado->CurrentValue;
		$this->mig_nombre_abogado->ViewCustomAttributes = "";

		// mig_fase_procesal
		$this->mig_fase_procesal->ViewValue = $this->mig_fase_procesal->CurrentValue;
		$this->mig_fase_procesal->ViewCustomAttributes = "";

		// mig_moneda
		if (strval($this->mig_moneda->CurrentValue) <> "") {
			$this->mig_moneda->ViewValue = $this->mig_moneda->OptionCaption($this->mig_moneda->CurrentValue);
		} else {
			$this->mig_moneda->ViewValue = NULL;
		}
		$this->mig_moneda->ViewCustomAttributes = "";

		// mig_tasa
		$this->mig_tasa->ViewValue = $this->mig_tasa->CurrentValue;
		$this->mig_tasa->ViewValue = ew_FormatNumber($this->mig_tasa->ViewValue, 2, 0, 0, 0);
		$this->mig_tasa->ViewCustomAttributes = "";

		// mig_plazo
		$this->mig_plazo->ViewValue = $this->mig_plazo->CurrentValue;
		$this->mig_plazo->ViewValue = ew_FormatNumber($this->mig_plazo->ViewValue, 2, 0, 0, 0);
		$this->mig_plazo->ViewCustomAttributes = "";

		// mig_dias_mora
		$this->mig_dias_mora->ViewValue = $this->mig_dias_mora->CurrentValue;
		$this->mig_dias_mora->ViewValue = ew_FormatNumber($this->mig_dias_mora->ViewValue, 2, 0, 0, 0);
		$this->mig_dias_mora->ViewCustomAttributes = "";

		// mig_monto_desembolso
		$this->mig_monto_desembolso->ViewValue = $this->mig_monto_desembolso->CurrentValue;
		$this->mig_monto_desembolso->ViewValue = ew_FormatNumber($this->mig_monto_desembolso->ViewValue, 2, 0, 0, 0);
		$this->mig_monto_desembolso->ViewCustomAttributes = "";

		// mig_total_cartera
		$this->mig_total_cartera->ViewValue = $this->mig_total_cartera->CurrentValue;
		$this->mig_total_cartera->ViewValue = ew_FormatNumber($this->mig_total_cartera->ViewValue, 2, 0, 0, 0);
		$this->mig_total_cartera->ViewCustomAttributes = "";

		// mig_capital
		$this->mig_capital->ViewValue = $this->mig_capital->CurrentValue;
		$this->mig_capital->ViewValue = ew_FormatNumber($this->mig_capital->ViewValue, 2, 0, 0, 0);
		$this->mig_capital->ViewCustomAttributes = "";

		// mig_intereses
		$this->mig_intereses->ViewValue = $this->mig_intereses->CurrentValue;
		$this->mig_intereses->ViewValue = ew_FormatNumber($this->mig_intereses->ViewValue, 2, 0, 0, 0);
		$this->mig_intereses->ViewCustomAttributes = "";

		// mig_cargos_gastos
		$this->mig_cargos_gastos->ViewValue = $this->mig_cargos_gastos->CurrentValue;
		$this->mig_cargos_gastos->ViewValue = ew_FormatNumber($this->mig_cargos_gastos->ViewValue, 2, 0, 0, 0);
		$this->mig_cargos_gastos->ViewCustomAttributes = "";

		// mig_total_deuda
		$this->mig_total_deuda->ViewValue = $this->mig_total_deuda->CurrentValue;
		$this->mig_total_deuda->ViewValue = ew_FormatNumber($this->mig_total_deuda->ViewValue, 2, 0, 0, 0);
		$this->mig_total_deuda->ViewCustomAttributes = "";

			// Id
			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";
			$this->Id->TooltipValue = "";

			// id_cliente
			$this->id_cliente->LinkCustomAttributes = "";
			if (!ew_Empty($this->id_cliente->CurrentValue)) {
				$this->id_cliente->HrefValue = $this->id_cliente->CurrentValue; // Add prefix/suffix
				$this->id_cliente->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->id_cliente->HrefValue = ew_FullUrl($this->id_cliente->HrefValue, "href");
			} else {
				$this->id_cliente->HrefValue = "";
			}
			$this->id_cliente->TooltipValue = "";

			// id_ciudad
			$this->id_ciudad->LinkCustomAttributes = "";
			$this->id_ciudad->HrefValue = "";
			$this->id_ciudad->TooltipValue = "";

			// id_agente
			$this->id_agente->LinkCustomAttributes = "";
			$this->id_agente->HrefValue = "";
			$this->id_agente->TooltipValue = "";

			// id_estadodeuda
			$this->id_estadodeuda->LinkCustomAttributes = "";
			$this->id_estadodeuda->HrefValue = "";
			$this->id_estadodeuda->TooltipValue = "";

			// mig_codigo_deuda
			$this->mig_codigo_deuda->LinkCustomAttributes = "";
			$this->mig_codigo_deuda->HrefValue = "";
			$this->mig_codigo_deuda->TooltipValue = "";

			// mig_fecha_desembolso
			$this->mig_fecha_desembolso->LinkCustomAttributes = "";
			$this->mig_fecha_desembolso->HrefValue = "";
			$this->mig_fecha_desembolso->TooltipValue = "";

			// mig_moneda
			$this->mig_moneda->LinkCustomAttributes = "";
			$this->mig_moneda->HrefValue = "";
			$this->mig_moneda->TooltipValue = "";

			// mig_tasa
			$this->mig_tasa->LinkCustomAttributes = "";
			$this->mig_tasa->HrefValue = "";
			$this->mig_tasa->TooltipValue = "";

			// mig_plazo
			$this->mig_plazo->LinkCustomAttributes = "";
			$this->mig_plazo->HrefValue = "";
			$this->mig_plazo->TooltipValue = "";

			// mig_dias_mora
			$this->mig_dias_mora->LinkCustomAttributes = "";
			$this->mig_dias_mora->HrefValue = "";
			$this->mig_dias_mora->TooltipValue = "";

			// mig_monto_desembolso
			$this->mig_monto_desembolso->LinkCustomAttributes = "";
			$this->mig_monto_desembolso->HrefValue = "";
			$this->mig_monto_desembolso->TooltipValue = "";

			// mig_intereses
			$this->mig_intereses->LinkCustomAttributes = "";
			$this->mig_intereses->HrefValue = "";
			$this->mig_intereses->TooltipValue = "";

			// mig_cargos_gastos
			$this->mig_cargos_gastos->LinkCustomAttributes = "";
			$this->mig_cargos_gastos->HrefValue = "";
			$this->mig_cargos_gastos->TooltipValue = "";

			// mig_total_deuda
			$this->mig_total_deuda->LinkCustomAttributes = "";
			$this->mig_total_deuda->HrefValue = "";
			$this->mig_total_deuda->TooltipValue = "";
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

		// Check if records exist for detail table 'deuda_persona'
		if (!isset($GLOBALS["deuda_persona"])) $GLOBALS["deuda_persona"] = new cdeuda_persona();
		foreach ($rows as $row) {
			$rsdetail = $GLOBALS["deuda_persona"]->LoadRs("`id_deuda` = " . ew_QuotedValue($row['Id'], EW_DATATYPE_NUMBER, 'DB'));
			if ($rsdetail && !$rsdetail->EOF) {
				$sRelatedRecordMsg = str_replace("%t", "deuda_persona", $Language->Phrase("RelatedRecordExists"));
				$this->setFailureMessage($sRelatedRecordMsg);
				return FALSE;
			}
		}
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

	// Set up master/detail based on QueryString
	function SetupMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "cuentas") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_Id"] <> "") {
					$GLOBALS["cuentas"]->Id->setQueryStringValue($_GET["fk_Id"]);
					$this->id_cliente->setQueryStringValue($GLOBALS["cuentas"]->Id->QueryStringValue);
					$this->id_cliente->setSessionValue($this->id_cliente->QueryStringValue);
					if (!is_numeric($GLOBALS["cuentas"]->Id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "users") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_id_user"] <> "") {
					$GLOBALS["users"]->id_user->setQueryStringValue($_GET["fk_id_user"]);
					$this->id_agente->setQueryStringValue($GLOBALS["users"]->id_user->QueryStringValue);
					$this->id_agente->setSessionValue($this->id_agente->QueryStringValue);
					if (!is_numeric($GLOBALS["users"]->id_user->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "cuentas") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_Id"] <> "") {
					$GLOBALS["cuentas"]->Id->setFormValue($_POST["fk_Id"]);
					$this->id_cliente->setFormValue($GLOBALS["cuentas"]->Id->FormValue);
					$this->id_cliente->setSessionValue($this->id_cliente->FormValue);
					if (!is_numeric($GLOBALS["cuentas"]->Id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "users") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_id_user"] <> "") {
					$GLOBALS["users"]->id_user->setFormValue($_POST["fk_id_user"]);
					$this->id_agente->setFormValue($GLOBALS["users"]->id_user->FormValue);
					$this->id_agente->setSessionValue($this->id_agente->FormValue);
					if (!is_numeric($GLOBALS["users"]->id_user->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "cuentas") {
				if ($this->id_cliente->CurrentValue == "") $this->id_cliente->setSessionValue("");
			}
			if ($sMasterTblVar <> "users") {
				if ($this->id_agente->CurrentValue == "") $this->id_agente->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("deudaslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($deudas_delete)) $deudas_delete = new cdeudas_delete();

// Page init
$deudas_delete->Page_Init();

// Page main
$deudas_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$deudas_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fdeudasdelete = new ew_Form("fdeudasdelete", "delete");

// Form_CustomValidate event
fdeudasdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdeudasdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdeudasdelete.Lists["x_id_cliente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"cuentas"};
fdeudasdelete.Lists["x_id_cliente"].Data = "<?php echo $deudas_delete->id_cliente->LookupFilterQuery(FALSE, "delete") ?>";
fdeudasdelete.Lists["x_id_ciudad"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"ciudades"};
fdeudasdelete.Lists["x_id_ciudad"].Data = "<?php echo $deudas_delete->id_ciudad->LookupFilterQuery(FALSE, "delete") ?>";
fdeudasdelete.Lists["x_id_agente"] = {"LinkField":"x_id_user","Ajax":true,"AutoFill":false,"DisplayFields":["x_First_Name","x_Last_Name","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdeudasdelete.Lists["x_id_agente"].Data = "<?php echo $deudas_delete->id_agente->LookupFilterQuery(FALSE, "delete") ?>";
fdeudasdelete.Lists["x_id_estadodeuda"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"estado_deuda"};
fdeudasdelete.Lists["x_id_estadodeuda"].Data = "<?php echo $deudas_delete->id_estadodeuda->LookupFilterQuery(FALSE, "delete") ?>";
fdeudasdelete.Lists["x_mig_moneda"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdeudasdelete.Lists["x_mig_moneda"].Options = <?php echo json_encode($deudas_delete->mig_moneda->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $deudas_delete->ShowPageHeader(); ?>
<?php
$deudas_delete->ShowMessage();
?>
<form name="fdeudasdelete" id="fdeudasdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($deudas_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $deudas_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="deudas">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($deudas_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($deudas->Id->Visible) { // Id ?>
		<th class="<?php echo $deudas->Id->HeaderCellClass() ?>"><span id="elh_deudas_Id" class="deudas_Id"><?php echo $deudas->Id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->id_cliente->Visible) { // id_cliente ?>
		<th class="<?php echo $deudas->id_cliente->HeaderCellClass() ?>"><span id="elh_deudas_id_cliente" class="deudas_id_cliente"><?php echo $deudas->id_cliente->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->id_ciudad->Visible) { // id_ciudad ?>
		<th class="<?php echo $deudas->id_ciudad->HeaderCellClass() ?>"><span id="elh_deudas_id_ciudad" class="deudas_id_ciudad"><?php echo $deudas->id_ciudad->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->id_agente->Visible) { // id_agente ?>
		<th class="<?php echo $deudas->id_agente->HeaderCellClass() ?>"><span id="elh_deudas_id_agente" class="deudas_id_agente"><?php echo $deudas->id_agente->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->id_estadodeuda->Visible) { // id_estadodeuda ?>
		<th class="<?php echo $deudas->id_estadodeuda->HeaderCellClass() ?>"><span id="elh_deudas_id_estadodeuda" class="deudas_id_estadodeuda"><?php echo $deudas->id_estadodeuda->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->mig_codigo_deuda->Visible) { // mig_codigo_deuda ?>
		<th class="<?php echo $deudas->mig_codigo_deuda->HeaderCellClass() ?>"><span id="elh_deudas_mig_codigo_deuda" class="deudas_mig_codigo_deuda"><?php echo $deudas->mig_codigo_deuda->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->mig_fecha_desembolso->Visible) { // mig_fecha_desembolso ?>
		<th class="<?php echo $deudas->mig_fecha_desembolso->HeaderCellClass() ?>"><span id="elh_deudas_mig_fecha_desembolso" class="deudas_mig_fecha_desembolso"><?php echo $deudas->mig_fecha_desembolso->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->mig_moneda->Visible) { // mig_moneda ?>
		<th class="<?php echo $deudas->mig_moneda->HeaderCellClass() ?>"><span id="elh_deudas_mig_moneda" class="deudas_mig_moneda"><?php echo $deudas->mig_moneda->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->mig_tasa->Visible) { // mig_tasa ?>
		<th class="<?php echo $deudas->mig_tasa->HeaderCellClass() ?>"><span id="elh_deudas_mig_tasa" class="deudas_mig_tasa"><?php echo $deudas->mig_tasa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->mig_plazo->Visible) { // mig_plazo ?>
		<th class="<?php echo $deudas->mig_plazo->HeaderCellClass() ?>"><span id="elh_deudas_mig_plazo" class="deudas_mig_plazo"><?php echo $deudas->mig_plazo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->mig_dias_mora->Visible) { // mig_dias_mora ?>
		<th class="<?php echo $deudas->mig_dias_mora->HeaderCellClass() ?>"><span id="elh_deudas_mig_dias_mora" class="deudas_mig_dias_mora"><?php echo $deudas->mig_dias_mora->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->mig_monto_desembolso->Visible) { // mig_monto_desembolso ?>
		<th class="<?php echo $deudas->mig_monto_desembolso->HeaderCellClass() ?>"><span id="elh_deudas_mig_monto_desembolso" class="deudas_mig_monto_desembolso"><?php echo $deudas->mig_monto_desembolso->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->mig_intereses->Visible) { // mig_intereses ?>
		<th class="<?php echo $deudas->mig_intereses->HeaderCellClass() ?>"><span id="elh_deudas_mig_intereses" class="deudas_mig_intereses"><?php echo $deudas->mig_intereses->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->mig_cargos_gastos->Visible) { // mig_cargos_gastos ?>
		<th class="<?php echo $deudas->mig_cargos_gastos->HeaderCellClass() ?>"><span id="elh_deudas_mig_cargos_gastos" class="deudas_mig_cargos_gastos"><?php echo $deudas->mig_cargos_gastos->FldCaption() ?></span></th>
<?php } ?>
<?php if ($deudas->mig_total_deuda->Visible) { // mig_total_deuda ?>
		<th class="<?php echo $deudas->mig_total_deuda->HeaderCellClass() ?>"><span id="elh_deudas_mig_total_deuda" class="deudas_mig_total_deuda"><?php echo $deudas->mig_total_deuda->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$deudas_delete->RecCnt = 0;
$i = 0;
while (!$deudas_delete->Recordset->EOF) {
	$deudas_delete->RecCnt++;
	$deudas_delete->RowCnt++;

	// Set row properties
	$deudas->ResetAttrs();
	$deudas->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$deudas_delete->LoadRowValues($deudas_delete->Recordset);

	// Render row
	$deudas_delete->RenderRow();
?>
	<tr<?php echo $deudas->RowAttributes() ?>>
<?php if ($deudas->Id->Visible) { // Id ?>
		<td<?php echo $deudas->Id->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_Id" class="deudas_Id">
<span<?php echo $deudas->Id->ViewAttributes() ?>>
<?php echo $deudas->Id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($deudas->id_cliente->Visible) { // id_cliente ?>
		<td<?php echo $deudas->id_cliente->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_id_cliente" class="deudas_id_cliente">
<span<?php echo $deudas->id_cliente->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deudas->id_cliente->ListViewValue())) && $deudas->id_cliente->LinkAttributes() <> "") { ?>
<a<?php echo $deudas->id_cliente->LinkAttributes() ?>><?php echo $deudas->id_cliente->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $deudas->id_cliente->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($deudas->id_ciudad->Visible) { // id_ciudad ?>
		<td<?php echo $deudas->id_ciudad->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_id_ciudad" class="deudas_id_ciudad">
<span<?php echo $deudas->id_ciudad->ViewAttributes() ?>>
<?php echo $deudas->id_ciudad->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($deudas->id_agente->Visible) { // id_agente ?>
		<td<?php echo $deudas->id_agente->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_id_agente" class="deudas_id_agente">
<span<?php echo $deudas->id_agente->ViewAttributes() ?>>
<?php echo $deudas->id_agente->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($deudas->id_estadodeuda->Visible) { // id_estadodeuda ?>
		<td<?php echo $deudas->id_estadodeuda->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_id_estadodeuda" class="deudas_id_estadodeuda">
<span<?php echo $deudas->id_estadodeuda->ViewAttributes() ?>>
<?php echo $deudas->id_estadodeuda->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($deudas->mig_codigo_deuda->Visible) { // mig_codigo_deuda ?>
		<td<?php echo $deudas->mig_codigo_deuda->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_mig_codigo_deuda" class="deudas_mig_codigo_deuda">
<span<?php echo $deudas->mig_codigo_deuda->ViewAttributes() ?>>
<?php echo $deudas->mig_codigo_deuda->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($deudas->mig_fecha_desembolso->Visible) { // mig_fecha_desembolso ?>
		<td<?php echo $deudas->mig_fecha_desembolso->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_mig_fecha_desembolso" class="deudas_mig_fecha_desembolso">
<span<?php echo $deudas->mig_fecha_desembolso->ViewAttributes() ?>>
<?php echo $deudas->mig_fecha_desembolso->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($deudas->mig_moneda->Visible) { // mig_moneda ?>
		<td<?php echo $deudas->mig_moneda->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_mig_moneda" class="deudas_mig_moneda">
<span<?php echo $deudas->mig_moneda->ViewAttributes() ?>>
<?php echo $deudas->mig_moneda->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($deudas->mig_tasa->Visible) { // mig_tasa ?>
		<td<?php echo $deudas->mig_tasa->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_mig_tasa" class="deudas_mig_tasa">
<span<?php echo $deudas->mig_tasa->ViewAttributes() ?>>
<?php echo $deudas->mig_tasa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($deudas->mig_plazo->Visible) { // mig_plazo ?>
		<td<?php echo $deudas->mig_plazo->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_mig_plazo" class="deudas_mig_plazo">
<span<?php echo $deudas->mig_plazo->ViewAttributes() ?>>
<?php echo $deudas->mig_plazo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($deudas->mig_dias_mora->Visible) { // mig_dias_mora ?>
		<td<?php echo $deudas->mig_dias_mora->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_mig_dias_mora" class="deudas_mig_dias_mora">
<span<?php echo $deudas->mig_dias_mora->ViewAttributes() ?>>
<?php echo $deudas->mig_dias_mora->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($deudas->mig_monto_desembolso->Visible) { // mig_monto_desembolso ?>
		<td<?php echo $deudas->mig_monto_desembolso->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_mig_monto_desembolso" class="deudas_mig_monto_desembolso">
<span<?php echo $deudas->mig_monto_desembolso->ViewAttributes() ?>>
<?php echo $deudas->mig_monto_desembolso->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($deudas->mig_intereses->Visible) { // mig_intereses ?>
		<td<?php echo $deudas->mig_intereses->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_mig_intereses" class="deudas_mig_intereses">
<span<?php echo $deudas->mig_intereses->ViewAttributes() ?>>
<?php echo $deudas->mig_intereses->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($deudas->mig_cargos_gastos->Visible) { // mig_cargos_gastos ?>
		<td<?php echo $deudas->mig_cargos_gastos->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_mig_cargos_gastos" class="deudas_mig_cargos_gastos">
<span<?php echo $deudas->mig_cargos_gastos->ViewAttributes() ?>>
<?php echo $deudas->mig_cargos_gastos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($deudas->mig_total_deuda->Visible) { // mig_total_deuda ?>
		<td<?php echo $deudas->mig_total_deuda->CellAttributes() ?>>
<span id="el<?php echo $deudas_delete->RowCnt ?>_deudas_mig_total_deuda" class="deudas_mig_total_deuda">
<span<?php echo $deudas->mig_total_deuda->ViewAttributes() ?>>
<?php echo $deudas->mig_total_deuda->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$deudas_delete->Recordset->MoveNext();
}
$deudas_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $deudas_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fdeudasdelete.Init();
</script>
<?php
$deudas_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$deudas_delete->Page_Terminate();
?>
