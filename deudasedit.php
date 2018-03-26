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
<?php include_once "deuda_personagridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$deudas_edit = NULL; // Initialize page object first

class cdeudas_edit extends cdeudas {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'deudas';

	// Page object name
	var $PageObjName = 'deudas_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanEdit()) {
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
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->Id->SetVisibility();
		$this->Id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->id_cliente->SetVisibility();
		$this->id_ciudad->SetVisibility();
		$this->id_agente->SetVisibility();
		$this->id_estadodeuda->SetVisibility();
		$this->mig_codigo_deuda->SetVisibility();
		$this->mig_tipo_operacion->SetVisibility();
		$this->mig_fecha_desembolso->SetVisibility();
		$this->mig_fecha_estado->SetVisibility();
		$this->mig_anios_castigo->SetVisibility();
		$this->mig_tipo_garantia->SetVisibility();
		$this->mig_real->SetVisibility();
		$this->mig_actividad_economica->SetVisibility();
		$this->mig_agencia->SetVisibility();
		$this->mig_no_juicio->SetVisibility();
		$this->mig_nombre_abogado->SetVisibility();
		$this->mig_fase_procesal->SetVisibility();
		$this->mig_moneda->SetVisibility();
		$this->mig_tasa->SetVisibility();
		$this->mig_plazo->SetVisibility();
		$this->mig_dias_mora->SetVisibility();
		$this->mig_monto_desembolso->SetVisibility();
		$this->mig_total_cartera->SetVisibility();
		$this->mig_capital->SetVisibility();
		$this->mig_intereses->SetVisibility();
		$this->mig_cargos_gastos->SetVisibility();
		$this->mig_total_deuda->SetVisibility();

		// Set up multi page object
		$this->SetupMultiPages();

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {

			// Process auto fill for detail table 'deuda_persona'
			if (@$_POST["grid"] == "fdeuda_personagrid") {
				if (!isset($GLOBALS["deuda_persona_grid"])) $GLOBALS["deuda_persona_grid"] = new cdeuda_persona_grid;
				$GLOBALS["deuda_persona_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "deudasview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
				}
				echo ew_ArrayToJson(array($row));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $MultiPages; // Multi pages object

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewEditForm form-horizontal";
		$sReturnUrl = "";
		$loaded = FALSE;
		$postBack = FALSE;

		// Set up current action and primary key
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			if ($this->CurrentAction <> "I") // Not reload record, handle as postback
				$postBack = TRUE;

			// Load key from Form
			if ($objForm->HasValue("x_Id")) {
				$this->Id->setFormValue($objForm->GetValue("x_Id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["Id"])) {
				$this->Id->setQueryStringValue($_GET["Id"]);
				$loadByQuery = TRUE;
			} else {
				$this->Id->CurrentValue = NULL;
			}
		}

		// Set up master detail parameters
		$this->SetupMasterParms();

		// Load current record
		$loaded = $this->LoadRow();

		// Process form if post back
		if ($postBack) {
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetupDetailParms();
		}

		// Validate form if post back
		if ($postBack) {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$loaded) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("deudaslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetupDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "deudaslist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetupDetailParms();
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetupStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Id->FldIsDetailKey)
			$this->Id->setFormValue($objForm->GetValue("x_Id"));
		if (!$this->id_cliente->FldIsDetailKey) {
			$this->id_cliente->setFormValue($objForm->GetValue("x_id_cliente"));
		}
		if (!$this->id_ciudad->FldIsDetailKey) {
			$this->id_ciudad->setFormValue($objForm->GetValue("x_id_ciudad"));
		}
		if (!$this->id_agente->FldIsDetailKey) {
			$this->id_agente->setFormValue($objForm->GetValue("x_id_agente"));
		}
		if (!$this->id_estadodeuda->FldIsDetailKey) {
			$this->id_estadodeuda->setFormValue($objForm->GetValue("x_id_estadodeuda"));
		}
		if (!$this->mig_codigo_deuda->FldIsDetailKey) {
			$this->mig_codigo_deuda->setFormValue($objForm->GetValue("x_mig_codigo_deuda"));
		}
		if (!$this->mig_tipo_operacion->FldIsDetailKey) {
			$this->mig_tipo_operacion->setFormValue($objForm->GetValue("x_mig_tipo_operacion"));
		}
		if (!$this->mig_fecha_desembolso->FldIsDetailKey) {
			$this->mig_fecha_desembolso->setFormValue($objForm->GetValue("x_mig_fecha_desembolso"));
			$this->mig_fecha_desembolso->CurrentValue = ew_UnFormatDateTime($this->mig_fecha_desembolso->CurrentValue, 7);
		}
		if (!$this->mig_fecha_estado->FldIsDetailKey) {
			$this->mig_fecha_estado->setFormValue($objForm->GetValue("x_mig_fecha_estado"));
			$this->mig_fecha_estado->CurrentValue = ew_UnFormatDateTime($this->mig_fecha_estado->CurrentValue, 7);
		}
		if (!$this->mig_anios_castigo->FldIsDetailKey) {
			$this->mig_anios_castigo->setFormValue($objForm->GetValue("x_mig_anios_castigo"));
		}
		if (!$this->mig_tipo_garantia->FldIsDetailKey) {
			$this->mig_tipo_garantia->setFormValue($objForm->GetValue("x_mig_tipo_garantia"));
		}
		if (!$this->mig_real->FldIsDetailKey) {
			$this->mig_real->setFormValue($objForm->GetValue("x_mig_real"));
		}
		if (!$this->mig_actividad_economica->FldIsDetailKey) {
			$this->mig_actividad_economica->setFormValue($objForm->GetValue("x_mig_actividad_economica"));
		}
		if (!$this->mig_agencia->FldIsDetailKey) {
			$this->mig_agencia->setFormValue($objForm->GetValue("x_mig_agencia"));
		}
		if (!$this->mig_no_juicio->FldIsDetailKey) {
			$this->mig_no_juicio->setFormValue($objForm->GetValue("x_mig_no_juicio"));
		}
		if (!$this->mig_nombre_abogado->FldIsDetailKey) {
			$this->mig_nombre_abogado->setFormValue($objForm->GetValue("x_mig_nombre_abogado"));
		}
		if (!$this->mig_fase_procesal->FldIsDetailKey) {
			$this->mig_fase_procesal->setFormValue($objForm->GetValue("x_mig_fase_procesal"));
		}
		if (!$this->mig_moneda->FldIsDetailKey) {
			$this->mig_moneda->setFormValue($objForm->GetValue("x_mig_moneda"));
		}
		if (!$this->mig_tasa->FldIsDetailKey) {
			$this->mig_tasa->setFormValue($objForm->GetValue("x_mig_tasa"));
		}
		if (!$this->mig_plazo->FldIsDetailKey) {
			$this->mig_plazo->setFormValue($objForm->GetValue("x_mig_plazo"));
		}
		if (!$this->mig_dias_mora->FldIsDetailKey) {
			$this->mig_dias_mora->setFormValue($objForm->GetValue("x_mig_dias_mora"));
		}
		if (!$this->mig_monto_desembolso->FldIsDetailKey) {
			$this->mig_monto_desembolso->setFormValue($objForm->GetValue("x_mig_monto_desembolso"));
		}
		if (!$this->mig_total_cartera->FldIsDetailKey) {
			$this->mig_total_cartera->setFormValue($objForm->GetValue("x_mig_total_cartera"));
		}
		if (!$this->mig_capital->FldIsDetailKey) {
			$this->mig_capital->setFormValue($objForm->GetValue("x_mig_capital"));
		}
		if (!$this->mig_intereses->FldIsDetailKey) {
			$this->mig_intereses->setFormValue($objForm->GetValue("x_mig_intereses"));
		}
		if (!$this->mig_cargos_gastos->FldIsDetailKey) {
			$this->mig_cargos_gastos->setFormValue($objForm->GetValue("x_mig_cargos_gastos"));
		}
		if (!$this->mig_total_deuda->FldIsDetailKey) {
			$this->mig_total_deuda->setFormValue($objForm->GetValue("x_mig_total_deuda"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->Id->CurrentValue = $this->Id->FormValue;
		$this->id_cliente->CurrentValue = $this->id_cliente->FormValue;
		$this->id_ciudad->CurrentValue = $this->id_ciudad->FormValue;
		$this->id_agente->CurrentValue = $this->id_agente->FormValue;
		$this->id_estadodeuda->CurrentValue = $this->id_estadodeuda->FormValue;
		$this->mig_codigo_deuda->CurrentValue = $this->mig_codigo_deuda->FormValue;
		$this->mig_tipo_operacion->CurrentValue = $this->mig_tipo_operacion->FormValue;
		$this->mig_fecha_desembolso->CurrentValue = $this->mig_fecha_desembolso->FormValue;
		$this->mig_fecha_desembolso->CurrentValue = ew_UnFormatDateTime($this->mig_fecha_desembolso->CurrentValue, 7);
		$this->mig_fecha_estado->CurrentValue = $this->mig_fecha_estado->FormValue;
		$this->mig_fecha_estado->CurrentValue = ew_UnFormatDateTime($this->mig_fecha_estado->CurrentValue, 7);
		$this->mig_anios_castigo->CurrentValue = $this->mig_anios_castigo->FormValue;
		$this->mig_tipo_garantia->CurrentValue = $this->mig_tipo_garantia->FormValue;
		$this->mig_real->CurrentValue = $this->mig_real->FormValue;
		$this->mig_actividad_economica->CurrentValue = $this->mig_actividad_economica->FormValue;
		$this->mig_agencia->CurrentValue = $this->mig_agencia->FormValue;
		$this->mig_no_juicio->CurrentValue = $this->mig_no_juicio->FormValue;
		$this->mig_nombre_abogado->CurrentValue = $this->mig_nombre_abogado->FormValue;
		$this->mig_fase_procesal->CurrentValue = $this->mig_fase_procesal->FormValue;
		$this->mig_moneda->CurrentValue = $this->mig_moneda->FormValue;
		$this->mig_tasa->CurrentValue = $this->mig_tasa->FormValue;
		$this->mig_plazo->CurrentValue = $this->mig_plazo->FormValue;
		$this->mig_dias_mora->CurrentValue = $this->mig_dias_mora->FormValue;
		$this->mig_monto_desembolso->CurrentValue = $this->mig_monto_desembolso->FormValue;
		$this->mig_total_cartera->CurrentValue = $this->mig_total_cartera->FormValue;
		$this->mig_capital->CurrentValue = $this->mig_capital->FormValue;
		$this->mig_intereses->CurrentValue = $this->mig_intereses->FormValue;
		$this->mig_cargos_gastos->CurrentValue = $this->mig_cargos_gastos->FormValue;
		$this->mig_total_deuda->CurrentValue = $this->mig_total_deuda->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Id")) <> "")
			$this->Id->CurrentValue = $this->getKey("Id"); // Id
		else
			$bValidKey = FALSE;

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
		return $bValidKey;
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
		if ($this->mig_total_cartera->FormValue == $this->mig_total_cartera->CurrentValue && is_numeric(ew_StrToFloat($this->mig_total_cartera->CurrentValue)))
			$this->mig_total_cartera->CurrentValue = ew_StrToFloat($this->mig_total_cartera->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_capital->FormValue == $this->mig_capital->CurrentValue && is_numeric(ew_StrToFloat($this->mig_capital->CurrentValue)))
			$this->mig_capital->CurrentValue = ew_StrToFloat($this->mig_capital->CurrentValue);

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

			// mig_tipo_operacion
			$this->mig_tipo_operacion->LinkCustomAttributes = "";
			$this->mig_tipo_operacion->HrefValue = "";
			$this->mig_tipo_operacion->TooltipValue = "";

			// mig_fecha_desembolso
			$this->mig_fecha_desembolso->LinkCustomAttributes = "";
			$this->mig_fecha_desembolso->HrefValue = "";
			$this->mig_fecha_desembolso->TooltipValue = "";

			// mig_fecha_estado
			$this->mig_fecha_estado->LinkCustomAttributes = "";
			$this->mig_fecha_estado->HrefValue = "";
			$this->mig_fecha_estado->TooltipValue = "";

			// mig_anios_castigo
			$this->mig_anios_castigo->LinkCustomAttributes = "";
			$this->mig_anios_castigo->HrefValue = "";
			$this->mig_anios_castigo->TooltipValue = "";

			// mig_tipo_garantia
			$this->mig_tipo_garantia->LinkCustomAttributes = "";
			$this->mig_tipo_garantia->HrefValue = "";
			$this->mig_tipo_garantia->TooltipValue = "";

			// mig_real
			$this->mig_real->LinkCustomAttributes = "";
			$this->mig_real->HrefValue = "";
			$this->mig_real->TooltipValue = "";

			// mig_actividad_economica
			$this->mig_actividad_economica->LinkCustomAttributes = "";
			$this->mig_actividad_economica->HrefValue = "";
			$this->mig_actividad_economica->TooltipValue = "";

			// mig_agencia
			$this->mig_agencia->LinkCustomAttributes = "";
			$this->mig_agencia->HrefValue = "";
			$this->mig_agencia->TooltipValue = "";

			// mig_no_juicio
			$this->mig_no_juicio->LinkCustomAttributes = "";
			$this->mig_no_juicio->HrefValue = "";
			$this->mig_no_juicio->TooltipValue = "";

			// mig_nombre_abogado
			$this->mig_nombre_abogado->LinkCustomAttributes = "";
			$this->mig_nombre_abogado->HrefValue = "";
			$this->mig_nombre_abogado->TooltipValue = "";

			// mig_fase_procesal
			$this->mig_fase_procesal->LinkCustomAttributes = "";
			$this->mig_fase_procesal->HrefValue = "";
			$this->mig_fase_procesal->TooltipValue = "";

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

			// mig_total_cartera
			$this->mig_total_cartera->LinkCustomAttributes = "";
			$this->mig_total_cartera->HrefValue = "";
			$this->mig_total_cartera->TooltipValue = "";

			// mig_capital
			$this->mig_capital->LinkCustomAttributes = "";
			$this->mig_capital->HrefValue = "";
			$this->mig_capital->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			$this->Id->EditValue = $this->Id->CurrentValue;
			$this->Id->ViewCustomAttributes = "";

			// id_cliente
			$this->id_cliente->EditAttrs["class"] = "form-control";
			$this->id_cliente->EditCustomAttributes = "";
			if ($this->id_cliente->getSessionValue() <> "") {
				$this->id_cliente->CurrentValue = $this->id_cliente->getSessionValue();
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
			} else {
			if (trim(strval($this->id_cliente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_cliente->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cuentas`";
			$sWhereWrk = "";
			$this->id_cliente->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_cliente, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_cliente->EditValue = $arwrk;
			}

			// id_ciudad
			$this->id_ciudad->EditAttrs["class"] = "form-control";
			$this->id_ciudad->EditCustomAttributes = "";
			if (trim(strval($this->id_ciudad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_ciudad->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `ciudades`";
			$sWhereWrk = "";
			$this->id_ciudad->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_ciudad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_ciudad->EditValue = $arwrk;

			// id_agente
			$this->id_agente->EditAttrs["class"] = "form-control";
			$this->id_agente->EditCustomAttributes = "";
			if ($this->id_agente->getSessionValue() <> "") {
				$this->id_agente->CurrentValue = $this->id_agente->getSessionValue();
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
			} else {
			if (trim(strval($this->id_agente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_user`" . ew_SearchString("=", $this->id_agente->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_user`, `First_Name` AS `DispFld`, `Last_Name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->id_agente->LookupFilters = array();
			$lookuptblfilter = "`User_Level`=2";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_agente, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `First_Name`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_agente->EditValue = $arwrk;
			}

			// id_estadodeuda
			$this->id_estadodeuda->EditAttrs["class"] = "form-control";
			$this->id_estadodeuda->EditCustomAttributes = "";
			if (trim(strval($this->id_estadodeuda->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_estadodeuda->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `estado_deuda`";
			$sWhereWrk = "";
			$this->id_estadodeuda->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_estadodeuda, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_estadodeuda->EditValue = $arwrk;

			// mig_codigo_deuda
			$this->mig_codigo_deuda->EditAttrs["class"] = "form-control";
			$this->mig_codigo_deuda->EditCustomAttributes = "";
			$this->mig_codigo_deuda->EditValue = ew_HtmlEncode($this->mig_codigo_deuda->CurrentValue);
			$this->mig_codigo_deuda->PlaceHolder = ew_RemoveHtml($this->mig_codigo_deuda->FldCaption());

			// mig_tipo_operacion
			$this->mig_tipo_operacion->EditAttrs["class"] = "form-control";
			$this->mig_tipo_operacion->EditCustomAttributes = "";
			$this->mig_tipo_operacion->EditValue = ew_HtmlEncode($this->mig_tipo_operacion->CurrentValue);
			$this->mig_tipo_operacion->PlaceHolder = ew_RemoveHtml($this->mig_tipo_operacion->FldCaption());

			// mig_fecha_desembolso
			$this->mig_fecha_desembolso->EditAttrs["class"] = "form-control";
			$this->mig_fecha_desembolso->EditCustomAttributes = "";
			$this->mig_fecha_desembolso->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->mig_fecha_desembolso->CurrentValue, 7));
			$this->mig_fecha_desembolso->PlaceHolder = ew_RemoveHtml($this->mig_fecha_desembolso->FldCaption());

			// mig_fecha_estado
			$this->mig_fecha_estado->EditAttrs["class"] = "form-control";
			$this->mig_fecha_estado->EditCustomAttributes = "";
			$this->mig_fecha_estado->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->mig_fecha_estado->CurrentValue, 7));
			$this->mig_fecha_estado->PlaceHolder = ew_RemoveHtml($this->mig_fecha_estado->FldCaption());

			// mig_anios_castigo
			$this->mig_anios_castigo->EditAttrs["class"] = "form-control";
			$this->mig_anios_castigo->EditCustomAttributes = "";
			$this->mig_anios_castigo->EditValue = ew_HtmlEncode($this->mig_anios_castigo->CurrentValue);
			$this->mig_anios_castigo->PlaceHolder = ew_RemoveHtml($this->mig_anios_castigo->FldCaption());

			// mig_tipo_garantia
			$this->mig_tipo_garantia->EditAttrs["class"] = "form-control";
			$this->mig_tipo_garantia->EditCustomAttributes = "";
			$this->mig_tipo_garantia->EditValue = ew_HtmlEncode($this->mig_tipo_garantia->CurrentValue);
			$this->mig_tipo_garantia->PlaceHolder = ew_RemoveHtml($this->mig_tipo_garantia->FldCaption());

			// mig_real
			$this->mig_real->EditAttrs["class"] = "form-control";
			$this->mig_real->EditCustomAttributes = "";
			$this->mig_real->EditValue = ew_HtmlEncode($this->mig_real->CurrentValue);
			$this->mig_real->PlaceHolder = ew_RemoveHtml($this->mig_real->FldCaption());

			// mig_actividad_economica
			$this->mig_actividad_economica->EditAttrs["class"] = "form-control";
			$this->mig_actividad_economica->EditCustomAttributes = "";
			$this->mig_actividad_economica->EditValue = ew_HtmlEncode($this->mig_actividad_economica->CurrentValue);
			$this->mig_actividad_economica->PlaceHolder = ew_RemoveHtml($this->mig_actividad_economica->FldCaption());

			// mig_agencia
			$this->mig_agencia->EditAttrs["class"] = "form-control";
			$this->mig_agencia->EditCustomAttributes = "";
			$this->mig_agencia->EditValue = ew_HtmlEncode($this->mig_agencia->CurrentValue);
			$this->mig_agencia->PlaceHolder = ew_RemoveHtml($this->mig_agencia->FldCaption());

			// mig_no_juicio
			$this->mig_no_juicio->EditAttrs["class"] = "form-control";
			$this->mig_no_juicio->EditCustomAttributes = "";
			$this->mig_no_juicio->EditValue = ew_HtmlEncode($this->mig_no_juicio->CurrentValue);
			$this->mig_no_juicio->PlaceHolder = ew_RemoveHtml($this->mig_no_juicio->FldCaption());

			// mig_nombre_abogado
			$this->mig_nombre_abogado->EditAttrs["class"] = "form-control";
			$this->mig_nombre_abogado->EditCustomAttributes = "";
			$this->mig_nombre_abogado->EditValue = ew_HtmlEncode($this->mig_nombre_abogado->CurrentValue);
			$this->mig_nombre_abogado->PlaceHolder = ew_RemoveHtml($this->mig_nombre_abogado->FldCaption());

			// mig_fase_procesal
			$this->mig_fase_procesal->EditAttrs["class"] = "form-control";
			$this->mig_fase_procesal->EditCustomAttributes = "";
			$this->mig_fase_procesal->EditValue = ew_HtmlEncode($this->mig_fase_procesal->CurrentValue);
			$this->mig_fase_procesal->PlaceHolder = ew_RemoveHtml($this->mig_fase_procesal->FldCaption());

			// mig_moneda
			$this->mig_moneda->EditAttrs["class"] = "form-control";
			$this->mig_moneda->EditCustomAttributes = "";
			$this->mig_moneda->EditValue = $this->mig_moneda->Options(TRUE);

			// mig_tasa
			$this->mig_tasa->EditAttrs["class"] = "form-control";
			$this->mig_tasa->EditCustomAttributes = "";
			$this->mig_tasa->EditValue = ew_HtmlEncode($this->mig_tasa->CurrentValue);
			$this->mig_tasa->PlaceHolder = ew_RemoveHtml($this->mig_tasa->FldCaption());
			if (strval($this->mig_tasa->EditValue) <> "" && is_numeric($this->mig_tasa->EditValue)) $this->mig_tasa->EditValue = ew_FormatNumber($this->mig_tasa->EditValue, -2, 0, -2, 0);

			// mig_plazo
			$this->mig_plazo->EditAttrs["class"] = "form-control";
			$this->mig_plazo->EditCustomAttributes = "";
			$this->mig_plazo->EditValue = ew_HtmlEncode($this->mig_plazo->CurrentValue);
			$this->mig_plazo->PlaceHolder = ew_RemoveHtml($this->mig_plazo->FldCaption());
			if (strval($this->mig_plazo->EditValue) <> "" && is_numeric($this->mig_plazo->EditValue)) $this->mig_plazo->EditValue = ew_FormatNumber($this->mig_plazo->EditValue, -2, 0, -2, 0);

			// mig_dias_mora
			$this->mig_dias_mora->EditAttrs["class"] = "form-control";
			$this->mig_dias_mora->EditCustomAttributes = "";
			$this->mig_dias_mora->EditValue = ew_HtmlEncode($this->mig_dias_mora->CurrentValue);
			$this->mig_dias_mora->PlaceHolder = ew_RemoveHtml($this->mig_dias_mora->FldCaption());
			if (strval($this->mig_dias_mora->EditValue) <> "" && is_numeric($this->mig_dias_mora->EditValue)) $this->mig_dias_mora->EditValue = ew_FormatNumber($this->mig_dias_mora->EditValue, -2, 0, -2, 0);

			// mig_monto_desembolso
			$this->mig_monto_desembolso->EditAttrs["class"] = "form-control";
			$this->mig_monto_desembolso->EditCustomAttributes = "";
			$this->mig_monto_desembolso->EditValue = ew_HtmlEncode($this->mig_monto_desembolso->CurrentValue);
			$this->mig_monto_desembolso->PlaceHolder = ew_RemoveHtml($this->mig_monto_desembolso->FldCaption());
			if (strval($this->mig_monto_desembolso->EditValue) <> "" && is_numeric($this->mig_monto_desembolso->EditValue)) $this->mig_monto_desembolso->EditValue = ew_FormatNumber($this->mig_monto_desembolso->EditValue, -2, 0, -2, 0);

			// mig_total_cartera
			$this->mig_total_cartera->EditAttrs["class"] = "form-control";
			$this->mig_total_cartera->EditCustomAttributes = "";
			$this->mig_total_cartera->EditValue = ew_HtmlEncode($this->mig_total_cartera->CurrentValue);
			$this->mig_total_cartera->PlaceHolder = ew_RemoveHtml($this->mig_total_cartera->FldCaption());
			if (strval($this->mig_total_cartera->EditValue) <> "" && is_numeric($this->mig_total_cartera->EditValue)) $this->mig_total_cartera->EditValue = ew_FormatNumber($this->mig_total_cartera->EditValue, -2, 0, -2, 0);

			// mig_capital
			$this->mig_capital->EditAttrs["class"] = "form-control";
			$this->mig_capital->EditCustomAttributes = "";
			$this->mig_capital->EditValue = ew_HtmlEncode($this->mig_capital->CurrentValue);
			$this->mig_capital->PlaceHolder = ew_RemoveHtml($this->mig_capital->FldCaption());
			if (strval($this->mig_capital->EditValue) <> "" && is_numeric($this->mig_capital->EditValue)) $this->mig_capital->EditValue = ew_FormatNumber($this->mig_capital->EditValue, -2, 0, -2, 0);

			// mig_intereses
			$this->mig_intereses->EditAttrs["class"] = "form-control";
			$this->mig_intereses->EditCustomAttributes = "";
			$this->mig_intereses->EditValue = ew_HtmlEncode($this->mig_intereses->CurrentValue);
			$this->mig_intereses->PlaceHolder = ew_RemoveHtml($this->mig_intereses->FldCaption());
			if (strval($this->mig_intereses->EditValue) <> "" && is_numeric($this->mig_intereses->EditValue)) $this->mig_intereses->EditValue = ew_FormatNumber($this->mig_intereses->EditValue, -2, 0, -2, 0);

			// mig_cargos_gastos
			$this->mig_cargos_gastos->EditAttrs["class"] = "form-control";
			$this->mig_cargos_gastos->EditCustomAttributes = "";
			$this->mig_cargos_gastos->EditValue = ew_HtmlEncode($this->mig_cargos_gastos->CurrentValue);
			$this->mig_cargos_gastos->PlaceHolder = ew_RemoveHtml($this->mig_cargos_gastos->FldCaption());
			if (strval($this->mig_cargos_gastos->EditValue) <> "" && is_numeric($this->mig_cargos_gastos->EditValue)) $this->mig_cargos_gastos->EditValue = ew_FormatNumber($this->mig_cargos_gastos->EditValue, -2, 0, -2, 0);

			// mig_total_deuda
			$this->mig_total_deuda->EditAttrs["class"] = "form-control";
			$this->mig_total_deuda->EditCustomAttributes = "";
			$this->mig_total_deuda->EditValue = ew_HtmlEncode($this->mig_total_deuda->CurrentValue);
			$this->mig_total_deuda->PlaceHolder = ew_RemoveHtml($this->mig_total_deuda->FldCaption());
			if (strval($this->mig_total_deuda->EditValue) <> "" && is_numeric($this->mig_total_deuda->EditValue)) $this->mig_total_deuda->EditValue = ew_FormatNumber($this->mig_total_deuda->EditValue, -2, 0, -2, 0);

			// Edit refer script
			// Id

			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";

			// id_cliente
			$this->id_cliente->LinkCustomAttributes = "";
			if (!ew_Empty($this->id_cliente->CurrentValue)) {
				$this->id_cliente->HrefValue = $this->id_cliente->CurrentValue; // Add prefix/suffix
				$this->id_cliente->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->id_cliente->HrefValue = ew_FullUrl($this->id_cliente->HrefValue, "href");
			} else {
				$this->id_cliente->HrefValue = "";
			}

			// id_ciudad
			$this->id_ciudad->LinkCustomAttributes = "";
			$this->id_ciudad->HrefValue = "";

			// id_agente
			$this->id_agente->LinkCustomAttributes = "";
			$this->id_agente->HrefValue = "";

			// id_estadodeuda
			$this->id_estadodeuda->LinkCustomAttributes = "";
			$this->id_estadodeuda->HrefValue = "";

			// mig_codigo_deuda
			$this->mig_codigo_deuda->LinkCustomAttributes = "";
			$this->mig_codigo_deuda->HrefValue = "";

			// mig_tipo_operacion
			$this->mig_tipo_operacion->LinkCustomAttributes = "";
			$this->mig_tipo_operacion->HrefValue = "";

			// mig_fecha_desembolso
			$this->mig_fecha_desembolso->LinkCustomAttributes = "";
			$this->mig_fecha_desembolso->HrefValue = "";

			// mig_fecha_estado
			$this->mig_fecha_estado->LinkCustomAttributes = "";
			$this->mig_fecha_estado->HrefValue = "";

			// mig_anios_castigo
			$this->mig_anios_castigo->LinkCustomAttributes = "";
			$this->mig_anios_castigo->HrefValue = "";

			// mig_tipo_garantia
			$this->mig_tipo_garantia->LinkCustomAttributes = "";
			$this->mig_tipo_garantia->HrefValue = "";

			// mig_real
			$this->mig_real->LinkCustomAttributes = "";
			$this->mig_real->HrefValue = "";

			// mig_actividad_economica
			$this->mig_actividad_economica->LinkCustomAttributes = "";
			$this->mig_actividad_economica->HrefValue = "";

			// mig_agencia
			$this->mig_agencia->LinkCustomAttributes = "";
			$this->mig_agencia->HrefValue = "";

			// mig_no_juicio
			$this->mig_no_juicio->LinkCustomAttributes = "";
			$this->mig_no_juicio->HrefValue = "";

			// mig_nombre_abogado
			$this->mig_nombre_abogado->LinkCustomAttributes = "";
			$this->mig_nombre_abogado->HrefValue = "";

			// mig_fase_procesal
			$this->mig_fase_procesal->LinkCustomAttributes = "";
			$this->mig_fase_procesal->HrefValue = "";

			// mig_moneda
			$this->mig_moneda->LinkCustomAttributes = "";
			$this->mig_moneda->HrefValue = "";

			// mig_tasa
			$this->mig_tasa->LinkCustomAttributes = "";
			$this->mig_tasa->HrefValue = "";

			// mig_plazo
			$this->mig_plazo->LinkCustomAttributes = "";
			$this->mig_plazo->HrefValue = "";

			// mig_dias_mora
			$this->mig_dias_mora->LinkCustomAttributes = "";
			$this->mig_dias_mora->HrefValue = "";

			// mig_monto_desembolso
			$this->mig_monto_desembolso->LinkCustomAttributes = "";
			$this->mig_monto_desembolso->HrefValue = "";

			// mig_total_cartera
			$this->mig_total_cartera->LinkCustomAttributes = "";
			$this->mig_total_cartera->HrefValue = "";

			// mig_capital
			$this->mig_capital->LinkCustomAttributes = "";
			$this->mig_capital->HrefValue = "";

			// mig_intereses
			$this->mig_intereses->LinkCustomAttributes = "";
			$this->mig_intereses->HrefValue = "";

			// mig_cargos_gastos
			$this->mig_cargos_gastos->LinkCustomAttributes = "";
			$this->mig_cargos_gastos->HrefValue = "";

			// mig_total_deuda
			$this->mig_total_deuda->LinkCustomAttributes = "";
			$this->mig_total_deuda->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->mig_codigo_deuda->FldIsDetailKey && !is_null($this->mig_codigo_deuda->FormValue) && $this->mig_codigo_deuda->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->mig_codigo_deuda->FldCaption(), $this->mig_codigo_deuda->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->mig_fecha_desembolso->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_fecha_desembolso->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->mig_fecha_estado->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_fecha_estado->FldErrMsg());
		}
		if (!ew_CheckInteger($this->mig_anios_castigo->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_anios_castigo->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_tasa->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_tasa->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_plazo->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_plazo->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_dias_mora->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_dias_mora->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_monto_desembolso->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_monto_desembolso->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_total_cartera->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_total_cartera->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_capital->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_capital->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_intereses->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_intereses->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_cargos_gastos->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_cargos_gastos->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_total_deuda->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_total_deuda->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("deuda_persona", $DetailTblVar) && $GLOBALS["deuda_persona"]->DetailEdit) {
			if (!isset($GLOBALS["deuda_persona_grid"])) $GLOBALS["deuda_persona_grid"] = new cdeuda_persona_grid(); // get detail page object
			$GLOBALS["deuda_persona_grid"]->ValidateGridForm();
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// id_cliente
			$this->id_cliente->SetDbValueDef($rsnew, $this->id_cliente->CurrentValue, 0, $this->id_cliente->ReadOnly);

			// id_ciudad
			$this->id_ciudad->SetDbValueDef($rsnew, $this->id_ciudad->CurrentValue, 0, $this->id_ciudad->ReadOnly);

			// id_agente
			$this->id_agente->SetDbValueDef($rsnew, $this->id_agente->CurrentValue, 0, $this->id_agente->ReadOnly);

			// id_estadodeuda
			$this->id_estadodeuda->SetDbValueDef($rsnew, $this->id_estadodeuda->CurrentValue, 0, $this->id_estadodeuda->ReadOnly);

			// mig_codigo_deuda
			$this->mig_codigo_deuda->SetDbValueDef($rsnew, $this->mig_codigo_deuda->CurrentValue, "", $this->mig_codigo_deuda->ReadOnly);

			// mig_tipo_operacion
			$this->mig_tipo_operacion->SetDbValueDef($rsnew, $this->mig_tipo_operacion->CurrentValue, NULL, $this->mig_tipo_operacion->ReadOnly);

			// mig_fecha_desembolso
			$this->mig_fecha_desembolso->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->mig_fecha_desembolso->CurrentValue, 7), NULL, $this->mig_fecha_desembolso->ReadOnly);

			// mig_fecha_estado
			$this->mig_fecha_estado->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->mig_fecha_estado->CurrentValue, 7), NULL, $this->mig_fecha_estado->ReadOnly);

			// mig_anios_castigo
			$this->mig_anios_castigo->SetDbValueDef($rsnew, $this->mig_anios_castigo->CurrentValue, NULL, $this->mig_anios_castigo->ReadOnly);

			// mig_tipo_garantia
			$this->mig_tipo_garantia->SetDbValueDef($rsnew, $this->mig_tipo_garantia->CurrentValue, NULL, $this->mig_tipo_garantia->ReadOnly);

			// mig_real
			$this->mig_real->SetDbValueDef($rsnew, $this->mig_real->CurrentValue, NULL, $this->mig_real->ReadOnly);

			// mig_actividad_economica
			$this->mig_actividad_economica->SetDbValueDef($rsnew, $this->mig_actividad_economica->CurrentValue, NULL, $this->mig_actividad_economica->ReadOnly);

			// mig_agencia
			$this->mig_agencia->SetDbValueDef($rsnew, $this->mig_agencia->CurrentValue, NULL, $this->mig_agencia->ReadOnly);

			// mig_no_juicio
			$this->mig_no_juicio->SetDbValueDef($rsnew, $this->mig_no_juicio->CurrentValue, NULL, $this->mig_no_juicio->ReadOnly);

			// mig_nombre_abogado
			$this->mig_nombre_abogado->SetDbValueDef($rsnew, $this->mig_nombre_abogado->CurrentValue, NULL, $this->mig_nombre_abogado->ReadOnly);

			// mig_fase_procesal
			$this->mig_fase_procesal->SetDbValueDef($rsnew, $this->mig_fase_procesal->CurrentValue, NULL, $this->mig_fase_procesal->ReadOnly);

			// mig_moneda
			$this->mig_moneda->SetDbValueDef($rsnew, $this->mig_moneda->CurrentValue, NULL, $this->mig_moneda->ReadOnly);

			// mig_tasa
			$this->mig_tasa->SetDbValueDef($rsnew, $this->mig_tasa->CurrentValue, NULL, $this->mig_tasa->ReadOnly);

			// mig_plazo
			$this->mig_plazo->SetDbValueDef($rsnew, $this->mig_plazo->CurrentValue, NULL, $this->mig_plazo->ReadOnly);

			// mig_dias_mora
			$this->mig_dias_mora->SetDbValueDef($rsnew, $this->mig_dias_mora->CurrentValue, NULL, $this->mig_dias_mora->ReadOnly);

			// mig_monto_desembolso
			$this->mig_monto_desembolso->SetDbValueDef($rsnew, $this->mig_monto_desembolso->CurrentValue, NULL, $this->mig_monto_desembolso->ReadOnly);

			// mig_total_cartera
			$this->mig_total_cartera->SetDbValueDef($rsnew, $this->mig_total_cartera->CurrentValue, NULL, $this->mig_total_cartera->ReadOnly);

			// mig_capital
			$this->mig_capital->SetDbValueDef($rsnew, $this->mig_capital->CurrentValue, NULL, $this->mig_capital->ReadOnly);

			// mig_intereses
			$this->mig_intereses->SetDbValueDef($rsnew, $this->mig_intereses->CurrentValue, NULL, $this->mig_intereses->ReadOnly);

			// mig_cargos_gastos
			$this->mig_cargos_gastos->SetDbValueDef($rsnew, $this->mig_cargos_gastos->CurrentValue, NULL, $this->mig_cargos_gastos->ReadOnly);

			// mig_total_deuda
			$this->mig_total_deuda->SetDbValueDef($rsnew, $this->mig_total_deuda->CurrentValue, NULL, $this->mig_total_deuda->ReadOnly);

			// Check referential integrity for master table 'cuentas'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_cuentas();
			$KeyValue = isset($rsnew['id_cliente']) ? $rsnew['id_cliente'] : $rsold['id_cliente'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@Id@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				if (!isset($GLOBALS["cuentas"])) $GLOBALS["cuentas"] = new ccuentas();
				$rsmaster = $GLOBALS["cuentas"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "cuentas", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

			// Check referential integrity for master table 'users'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_users();
			$KeyValue = isset($rsnew['id_agente']) ? $rsnew['id_agente'] : $rsold['id_agente'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@id_user@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				if (!isset($GLOBALS["users"])) $GLOBALS["users"] = new cusers();
				$rsmaster = $GLOBALS["users"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "users", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("deuda_persona", $DetailTblVar) && $GLOBALS["deuda_persona"]->DetailEdit) {
						if (!isset($GLOBALS["deuda_persona_grid"])) $GLOBALS["deuda_persona_grid"] = new cdeuda_persona_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "deuda_persona"); // Load user level of detail table
						$EditRow = $GLOBALS["deuda_persona_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
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
			$this->setSessionWhere($this->GetDetailFilter());

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

	// Set up detail parms based on QueryString
	function SetupDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("deuda_persona", $DetailTblVar)) {
				if (!isset($GLOBALS["deuda_persona_grid"]))
					$GLOBALS["deuda_persona_grid"] = new cdeuda_persona_grid;
				if ($GLOBALS["deuda_persona_grid"]->DetailEdit) {
					$GLOBALS["deuda_persona_grid"]->CurrentMode = "edit";
					$GLOBALS["deuda_persona_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["deuda_persona_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["deuda_persona_grid"]->setStartRecordNumber(1);
					$GLOBALS["deuda_persona_grid"]->id_deuda->FldIsDetailKey = TRUE;
					$GLOBALS["deuda_persona_grid"]->id_deuda->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["deuda_persona_grid"]->id_deuda->setSessionValue($GLOBALS["deuda_persona_grid"]->id_deuda->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("deudaslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Set up multi pages
	function SetupMultiPages() {
		$pages = new cSubPages();
		$pages->Style = "tabs";
		$pages->Add(0);
		$pages->Add(1);
		$pages->Add(2);
		$pages->Add(3);
		$pages->Add(4);
		$this->MultiPages = $pages;
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_cliente":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuentas`";
			$sWhereWrk = "";
			$this->id_cliente->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_cliente, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion`";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_ciudad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `ciudades`";
			$sWhereWrk = "";
			$this->id_ciudad->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_ciudad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_agente":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_user` AS `LinkFld`, `First_Name` AS `DispFld`, `Last_Name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->id_agente->LookupFilters = array();
			$lookuptblfilter = "`User_Level`=2";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_user` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_agente, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `First_Name`";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_estadodeuda":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estado_deuda`";
			$sWhereWrk = "";
			$this->id_estadodeuda->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_estadodeuda, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($deudas_edit)) $deudas_edit = new cdeudas_edit();

// Page init
$deudas_edit->Page_Init();

// Page main
$deudas_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$deudas_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fdeudasedit = new ew_Form("fdeudasedit", "edit");

// Validate form
fdeudasedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_mig_codigo_deuda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $deudas->mig_codigo_deuda->FldCaption(), $deudas->mig_codigo_deuda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_mig_fecha_desembolso");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_fecha_desembolso->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mig_fecha_estado");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_fecha_estado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mig_anios_castigo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_anios_castigo->FldErrMsg()) ?>");
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
			elm = this.GetElements("x" + infix + "_mig_total_cartera");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_total_cartera->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mig_capital");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_capital->FldErrMsg()) ?>");
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
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fdeudasedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdeudasedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
fdeudasedit.MultiPage = new ew_MultiPage("fdeudasedit");

// Dynamic selection lists
fdeudasedit.Lists["x_id_cliente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"cuentas"};
fdeudasedit.Lists["x_id_cliente"].Data = "<?php echo $deudas_edit->id_cliente->LookupFilterQuery(FALSE, "edit") ?>";
fdeudasedit.Lists["x_id_ciudad"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"ciudades"};
fdeudasedit.Lists["x_id_ciudad"].Data = "<?php echo $deudas_edit->id_ciudad->LookupFilterQuery(FALSE, "edit") ?>";
fdeudasedit.Lists["x_id_agente"] = {"LinkField":"x_id_user","Ajax":true,"AutoFill":false,"DisplayFields":["x_First_Name","x_Last_Name","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdeudasedit.Lists["x_id_agente"].Data = "<?php echo $deudas_edit->id_agente->LookupFilterQuery(FALSE, "edit") ?>";
fdeudasedit.Lists["x_id_estadodeuda"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"estado_deuda"};
fdeudasedit.Lists["x_id_estadodeuda"].Data = "<?php echo $deudas_edit->id_estadodeuda->LookupFilterQuery(FALSE, "edit") ?>";
fdeudasedit.Lists["x_mig_moneda"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdeudasedit.Lists["x_mig_moneda"].Options = <?php echo json_encode($deudas_edit->mig_moneda->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $deudas_edit->ShowPageHeader(); ?>
<?php
$deudas_edit->ShowMessage();
?>
<form name="fdeudasedit" id="fdeudasedit" class="<?php echo $deudas_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($deudas_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $deudas_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="deudas">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($deudas_edit->IsModal) ?>">
<?php if ($deudas->getCurrentMasterTable() == "cuentas") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="cuentas">
<input type="hidden" name="fk_Id" value="<?php echo $deudas->id_cliente->getSessionValue() ?>">
<?php } ?>
<?php if ($deudas->getCurrentMasterTable() == "users") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="users">
<input type="hidden" name="fk_id_user" value="<?php echo $deudas->id_agente->getSessionValue() ?>">
<?php } ?>
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="deudas_edit"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $deudas_edit->MultiPages->NavStyle() ?>">
		<li<?php echo $deudas_edit->MultiPages->TabStyle("1") ?>><a href="#tab_deudas1" data-toggle="tab"><?php echo $deudas->PageCaption(1) ?></a></li>
		<li<?php echo $deudas_edit->MultiPages->TabStyle("2") ?>><a href="#tab_deudas2" data-toggle="tab"><?php echo $deudas->PageCaption(2) ?></a></li>
		<li<?php echo $deudas_edit->MultiPages->TabStyle("3") ?>><a href="#tab_deudas3" data-toggle="tab"><?php echo $deudas->PageCaption(3) ?></a></li>
		<li<?php echo $deudas_edit->MultiPages->TabStyle("4") ?>><a href="#tab_deudas4" data-toggle="tab"><?php echo $deudas->PageCaption(4) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $deudas_edit->MultiPages->PageStyle("1") ?>" id="tab_deudas1"><!-- multi-page .tab-pane -->
<div class="ewEditDiv"><!-- page* -->
<?php if ($deudas->Id->Visible) { // Id ?>
	<div id="r_Id" class="form-group">
		<label id="elh_deudas_Id" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->Id->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->Id->CellAttributes() ?>>
<span id="el_deudas_Id">
<span<?php echo $deudas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="deudas" data-field="x_Id" data-page="1" name="x_Id" id="x_Id" value="<?php echo ew_HtmlEncode($deudas->Id->CurrentValue) ?>">
<?php echo $deudas->Id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->id_cliente->Visible) { // id_cliente ?>
	<div id="r_id_cliente" class="form-group">
		<label id="elh_deudas_id_cliente" for="x_id_cliente" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->id_cliente->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->id_cliente->CellAttributes() ?>>
<?php if ($deudas->id_cliente->getSessionValue() <> "") { ?>
<span id="el_deudas_id_cliente">
<span<?php echo $deudas->id_cliente->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deudas->id_cliente->ViewValue)) && $deudas->id_cliente->LinkAttributes() <> "") { ?>
<a<?php echo $deudas->id_cliente->LinkAttributes() ?>><p class="form-control-static"><?php echo $deudas->id_cliente->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deudas->id_cliente->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x_id_cliente" name="x_id_cliente" value="<?php echo ew_HtmlEncode($deudas->id_cliente->CurrentValue) ?>">
<?php } else { ?>
<span id="el_deudas_id_cliente">
<select data-table="deudas" data-field="x_id_cliente" data-page="1" data-value-separator="<?php echo $deudas->id_cliente->DisplayValueSeparatorAttribute() ?>" id="x_id_cliente" name="x_id_cliente"<?php echo $deudas->id_cliente->EditAttributes() ?>>
<?php echo $deudas->id_cliente->SelectOptionListHtml("x_id_cliente") ?>
</select>
</span>
<?php } ?>
<?php echo $deudas->id_cliente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->id_ciudad->Visible) { // id_ciudad ?>
	<div id="r_id_ciudad" class="form-group">
		<label id="elh_deudas_id_ciudad" for="x_id_ciudad" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->id_ciudad->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->id_ciudad->CellAttributes() ?>>
<span id="el_deudas_id_ciudad">
<select data-table="deudas" data-field="x_id_ciudad" data-page="1" data-value-separator="<?php echo $deudas->id_ciudad->DisplayValueSeparatorAttribute() ?>" id="x_id_ciudad" name="x_id_ciudad"<?php echo $deudas->id_ciudad->EditAttributes() ?>>
<?php echo $deudas->id_ciudad->SelectOptionListHtml("x_id_ciudad") ?>
</select>
</span>
<?php echo $deudas->id_ciudad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->id_agente->Visible) { // id_agente ?>
	<div id="r_id_agente" class="form-group">
		<label id="elh_deudas_id_agente" for="x_id_agente" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->id_agente->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->id_agente->CellAttributes() ?>>
<?php if ($deudas->id_agente->getSessionValue() <> "") { ?>
<span id="el_deudas_id_agente">
<span<?php echo $deudas->id_agente->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $deudas->id_agente->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_id_agente" name="x_id_agente" value="<?php echo ew_HtmlEncode($deudas->id_agente->CurrentValue) ?>">
<?php } else { ?>
<span id="el_deudas_id_agente">
<select data-table="deudas" data-field="x_id_agente" data-page="1" data-value-separator="<?php echo $deudas->id_agente->DisplayValueSeparatorAttribute() ?>" id="x_id_agente" name="x_id_agente"<?php echo $deudas->id_agente->EditAttributes() ?>>
<?php echo $deudas->id_agente->SelectOptionListHtml("x_id_agente") ?>
</select>
</span>
<?php } ?>
<?php echo $deudas->id_agente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->id_estadodeuda->Visible) { // id_estadodeuda ?>
	<div id="r_id_estadodeuda" class="form-group">
		<label id="elh_deudas_id_estadodeuda" for="x_id_estadodeuda" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->id_estadodeuda->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->id_estadodeuda->CellAttributes() ?>>
<span id="el_deudas_id_estadodeuda">
<select data-table="deudas" data-field="x_id_estadodeuda" data-page="1" data-value-separator="<?php echo $deudas->id_estadodeuda->DisplayValueSeparatorAttribute() ?>" id="x_id_estadodeuda" name="x_id_estadodeuda"<?php echo $deudas->id_estadodeuda->EditAttributes() ?>>
<?php echo $deudas->id_estadodeuda->SelectOptionListHtml("x_id_estadodeuda") ?>
</select>
</span>
<?php echo $deudas->id_estadodeuda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_codigo_deuda->Visible) { // mig_codigo_deuda ?>
	<div id="r_mig_codigo_deuda" class="form-group">
		<label id="elh_deudas_mig_codigo_deuda" for="x_mig_codigo_deuda" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_codigo_deuda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_codigo_deuda->CellAttributes() ?>>
<span id="el_deudas_mig_codigo_deuda">
<input type="text" data-table="deudas" data-field="x_mig_codigo_deuda" data-page="1" name="x_mig_codigo_deuda" id="x_mig_codigo_deuda" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->mig_codigo_deuda->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_codigo_deuda->EditValue ?>"<?php echo $deudas->mig_codigo_deuda->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_codigo_deuda->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $deudas_edit->MultiPages->PageStyle("2") ?>" id="tab_deudas2"><!-- multi-page .tab-pane -->
<div class="ewEditDiv"><!-- page* -->
<?php if ($deudas->mig_tipo_operacion->Visible) { // mig_tipo_operacion ?>
	<div id="r_mig_tipo_operacion" class="form-group">
		<label id="elh_deudas_mig_tipo_operacion" for="x_mig_tipo_operacion" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_tipo_operacion->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_tipo_operacion->CellAttributes() ?>>
<span id="el_deudas_mig_tipo_operacion">
<input type="text" data-table="deudas" data-field="x_mig_tipo_operacion" data-page="2" name="x_mig_tipo_operacion" id="x_mig_tipo_operacion" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->mig_tipo_operacion->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_tipo_operacion->EditValue ?>"<?php echo $deudas->mig_tipo_operacion->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_tipo_operacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_fecha_desembolso->Visible) { // mig_fecha_desembolso ?>
	<div id="r_mig_fecha_desembolso" class="form-group">
		<label id="elh_deudas_mig_fecha_desembolso" for="x_mig_fecha_desembolso" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_fecha_desembolso->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_fecha_desembolso->CellAttributes() ?>>
<span id="el_deudas_mig_fecha_desembolso">
<input type="text" data-table="deudas" data-field="x_mig_fecha_desembolso" data-page="2" data-format="7" name="x_mig_fecha_desembolso" id="x_mig_fecha_desembolso" size="16" placeholder="<?php echo ew_HtmlEncode($deudas->mig_fecha_desembolso->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_fecha_desembolso->EditValue ?>"<?php echo $deudas->mig_fecha_desembolso->EditAttributes() ?>>
<?php if (!$deudas->mig_fecha_desembolso->ReadOnly && !$deudas->mig_fecha_desembolso->Disabled && !isset($deudas->mig_fecha_desembolso->EditAttrs["readonly"]) && !isset($deudas->mig_fecha_desembolso->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdeudasedit", "x_mig_fecha_desembolso", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php echo $deudas->mig_fecha_desembolso->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_fecha_estado->Visible) { // mig_fecha_estado ?>
	<div id="r_mig_fecha_estado" class="form-group">
		<label id="elh_deudas_mig_fecha_estado" for="x_mig_fecha_estado" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_fecha_estado->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_fecha_estado->CellAttributes() ?>>
<span id="el_deudas_mig_fecha_estado">
<input type="text" data-table="deudas" data-field="x_mig_fecha_estado" data-page="2" data-format="7" name="x_mig_fecha_estado" id="x_mig_fecha_estado" size="16" placeholder="<?php echo ew_HtmlEncode($deudas->mig_fecha_estado->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_fecha_estado->EditValue ?>"<?php echo $deudas->mig_fecha_estado->EditAttributes() ?>>
<?php if (!$deudas->mig_fecha_estado->ReadOnly && !$deudas->mig_fecha_estado->Disabled && !isset($deudas->mig_fecha_estado->EditAttrs["readonly"]) && !isset($deudas->mig_fecha_estado->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdeudasedit", "x_mig_fecha_estado", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php echo $deudas->mig_fecha_estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_anios_castigo->Visible) { // mig_anios_castigo ?>
	<div id="r_mig_anios_castigo" class="form-group">
		<label id="elh_deudas_mig_anios_castigo" for="x_mig_anios_castigo" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_anios_castigo->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_anios_castigo->CellAttributes() ?>>
<span id="el_deudas_mig_anios_castigo">
<input type="text" data-table="deudas" data-field="x_mig_anios_castigo" data-page="2" name="x_mig_anios_castigo" id="x_mig_anios_castigo" size="10" placeholder="<?php echo ew_HtmlEncode($deudas->mig_anios_castigo->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_anios_castigo->EditValue ?>"<?php echo $deudas->mig_anios_castigo->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_anios_castigo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_tipo_garantia->Visible) { // mig_tipo_garantia ?>
	<div id="r_mig_tipo_garantia" class="form-group">
		<label id="elh_deudas_mig_tipo_garantia" for="x_mig_tipo_garantia" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_tipo_garantia->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_tipo_garantia->CellAttributes() ?>>
<span id="el_deudas_mig_tipo_garantia">
<input type="text" data-table="deudas" data-field="x_mig_tipo_garantia" data-page="2" name="x_mig_tipo_garantia" id="x_mig_tipo_garantia" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($deudas->mig_tipo_garantia->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_tipo_garantia->EditValue ?>"<?php echo $deudas->mig_tipo_garantia->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_tipo_garantia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_real->Visible) { // mig_real ?>
	<div id="r_mig_real" class="form-group">
		<label id="elh_deudas_mig_real" for="x_mig_real" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_real->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_real->CellAttributes() ?>>
<span id="el_deudas_mig_real">
<input type="text" data-table="deudas" data-field="x_mig_real" data-page="2" name="x_mig_real" id="x_mig_real" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($deudas->mig_real->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_real->EditValue ?>"<?php echo $deudas->mig_real->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_real->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $deudas_edit->MultiPages->PageStyle("3") ?>" id="tab_deudas3"><!-- multi-page .tab-pane -->
<div class="ewEditDiv"><!-- page* -->
<?php if ($deudas->mig_actividad_economica->Visible) { // mig_actividad_economica ?>
	<div id="r_mig_actividad_economica" class="form-group">
		<label id="elh_deudas_mig_actividad_economica" for="x_mig_actividad_economica" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_actividad_economica->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_actividad_economica->CellAttributes() ?>>
<span id="el_deudas_mig_actividad_economica">
<input type="text" data-table="deudas" data-field="x_mig_actividad_economica" data-page="3" name="x_mig_actividad_economica" id="x_mig_actividad_economica" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->mig_actividad_economica->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_actividad_economica->EditValue ?>"<?php echo $deudas->mig_actividad_economica->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_actividad_economica->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_agencia->Visible) { // mig_agencia ?>
	<div id="r_mig_agencia" class="form-group">
		<label id="elh_deudas_mig_agencia" for="x_mig_agencia" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_agencia->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_agencia->CellAttributes() ?>>
<span id="el_deudas_mig_agencia">
<input type="text" data-table="deudas" data-field="x_mig_agencia" data-page="3" name="x_mig_agencia" id="x_mig_agencia" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($deudas->mig_agencia->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_agencia->EditValue ?>"<?php echo $deudas->mig_agencia->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_agencia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_no_juicio->Visible) { // mig_no_juicio ?>
	<div id="r_mig_no_juicio" class="form-group">
		<label id="elh_deudas_mig_no_juicio" for="x_mig_no_juicio" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_no_juicio->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_no_juicio->CellAttributes() ?>>
<span id="el_deudas_mig_no_juicio">
<input type="text" data-table="deudas" data-field="x_mig_no_juicio" data-page="3" name="x_mig_no_juicio" id="x_mig_no_juicio" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($deudas->mig_no_juicio->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_no_juicio->EditValue ?>"<?php echo $deudas->mig_no_juicio->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_no_juicio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_nombre_abogado->Visible) { // mig_nombre_abogado ?>
	<div id="r_mig_nombre_abogado" class="form-group">
		<label id="elh_deudas_mig_nombre_abogado" for="x_mig_nombre_abogado" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_nombre_abogado->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_nombre_abogado->CellAttributes() ?>>
<span id="el_deudas_mig_nombre_abogado">
<input type="text" data-table="deudas" data-field="x_mig_nombre_abogado" data-page="3" name="x_mig_nombre_abogado" id="x_mig_nombre_abogado" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($deudas->mig_nombre_abogado->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_nombre_abogado->EditValue ?>"<?php echo $deudas->mig_nombre_abogado->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_nombre_abogado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_fase_procesal->Visible) { // mig_fase_procesal ?>
	<div id="r_mig_fase_procesal" class="form-group">
		<label id="elh_deudas_mig_fase_procesal" for="x_mig_fase_procesal" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_fase_procesal->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_fase_procesal->CellAttributes() ?>>
<span id="el_deudas_mig_fase_procesal">
<input type="text" data-table="deudas" data-field="x_mig_fase_procesal" data-page="3" name="x_mig_fase_procesal" id="x_mig_fase_procesal" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($deudas->mig_fase_procesal->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_fase_procesal->EditValue ?>"<?php echo $deudas->mig_fase_procesal->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_fase_procesal->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $deudas_edit->MultiPages->PageStyle("4") ?>" id="tab_deudas4"><!-- multi-page .tab-pane -->
<div class="ewEditDiv"><!-- page* -->
<?php if ($deudas->mig_moneda->Visible) { // mig_moneda ?>
	<div id="r_mig_moneda" class="form-group">
		<label id="elh_deudas_mig_moneda" for="x_mig_moneda" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_moneda->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_moneda->CellAttributes() ?>>
<span id="el_deudas_mig_moneda">
<select data-table="deudas" data-field="x_mig_moneda" data-page="4" data-value-separator="<?php echo $deudas->mig_moneda->DisplayValueSeparatorAttribute() ?>" id="x_mig_moneda" name="x_mig_moneda"<?php echo $deudas->mig_moneda->EditAttributes() ?>>
<?php echo $deudas->mig_moneda->SelectOptionListHtml("x_mig_moneda") ?>
</select>
</span>
<?php echo $deudas->mig_moneda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_tasa->Visible) { // mig_tasa ?>
	<div id="r_mig_tasa" class="form-group">
		<label id="elh_deudas_mig_tasa" for="x_mig_tasa" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_tasa->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_tasa->CellAttributes() ?>>
<span id="el_deudas_mig_tasa">
<input type="text" data-table="deudas" data-field="x_mig_tasa" data-page="4" name="x_mig_tasa" id="x_mig_tasa" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_tasa->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_tasa->EditValue ?>"<?php echo $deudas->mig_tasa->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_tasa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_plazo->Visible) { // mig_plazo ?>
	<div id="r_mig_plazo" class="form-group">
		<label id="elh_deudas_mig_plazo" for="x_mig_plazo" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_plazo->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_plazo->CellAttributes() ?>>
<span id="el_deudas_mig_plazo">
<input type="text" data-table="deudas" data-field="x_mig_plazo" data-page="4" name="x_mig_plazo" id="x_mig_plazo" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_plazo->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_plazo->EditValue ?>"<?php echo $deudas->mig_plazo->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_plazo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_dias_mora->Visible) { // mig_dias_mora ?>
	<div id="r_mig_dias_mora" class="form-group">
		<label id="elh_deudas_mig_dias_mora" for="x_mig_dias_mora" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_dias_mora->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_dias_mora->CellAttributes() ?>>
<span id="el_deudas_mig_dias_mora">
<input type="text" data-table="deudas" data-field="x_mig_dias_mora" data-page="4" name="x_mig_dias_mora" id="x_mig_dias_mora" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_dias_mora->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_dias_mora->EditValue ?>"<?php echo $deudas->mig_dias_mora->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_dias_mora->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_monto_desembolso->Visible) { // mig_monto_desembolso ?>
	<div id="r_mig_monto_desembolso" class="form-group">
		<label id="elh_deudas_mig_monto_desembolso" for="x_mig_monto_desembolso" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_monto_desembolso->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_monto_desembolso->CellAttributes() ?>>
<span id="el_deudas_mig_monto_desembolso">
<input type="text" data-table="deudas" data-field="x_mig_monto_desembolso" data-page="4" name="x_mig_monto_desembolso" id="x_mig_monto_desembolso" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_monto_desembolso->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_monto_desembolso->EditValue ?>"<?php echo $deudas->mig_monto_desembolso->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_monto_desembolso->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_total_cartera->Visible) { // mig_total_cartera ?>
	<div id="r_mig_total_cartera" class="form-group">
		<label id="elh_deudas_mig_total_cartera" for="x_mig_total_cartera" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_total_cartera->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_total_cartera->CellAttributes() ?>>
<span id="el_deudas_mig_total_cartera">
<input type="text" data-table="deudas" data-field="x_mig_total_cartera" data-page="4" name="x_mig_total_cartera" id="x_mig_total_cartera" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_total_cartera->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_total_cartera->EditValue ?>"<?php echo $deudas->mig_total_cartera->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_total_cartera->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_capital->Visible) { // mig_capital ?>
	<div id="r_mig_capital" class="form-group">
		<label id="elh_deudas_mig_capital" for="x_mig_capital" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_capital->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_capital->CellAttributes() ?>>
<span id="el_deudas_mig_capital">
<input type="text" data-table="deudas" data-field="x_mig_capital" data-page="4" name="x_mig_capital" id="x_mig_capital" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_capital->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_capital->EditValue ?>"<?php echo $deudas->mig_capital->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_capital->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_intereses->Visible) { // mig_intereses ?>
	<div id="r_mig_intereses" class="form-group">
		<label id="elh_deudas_mig_intereses" for="x_mig_intereses" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_intereses->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_intereses->CellAttributes() ?>>
<span id="el_deudas_mig_intereses">
<input type="text" data-table="deudas" data-field="x_mig_intereses" data-page="4" name="x_mig_intereses" id="x_mig_intereses" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_intereses->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_intereses->EditValue ?>"<?php echo $deudas->mig_intereses->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_intereses->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_cargos_gastos->Visible) { // mig_cargos_gastos ?>
	<div id="r_mig_cargos_gastos" class="form-group">
		<label id="elh_deudas_mig_cargos_gastos" for="x_mig_cargos_gastos" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_cargos_gastos->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_cargos_gastos->CellAttributes() ?>>
<span id="el_deudas_mig_cargos_gastos">
<input type="text" data-table="deudas" data-field="x_mig_cargos_gastos" data-page="4" name="x_mig_cargos_gastos" id="x_mig_cargos_gastos" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_cargos_gastos->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_cargos_gastos->EditValue ?>"<?php echo $deudas->mig_cargos_gastos->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_cargos_gastos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deudas->mig_total_deuda->Visible) { // mig_total_deuda ?>
	<div id="r_mig_total_deuda" class="form-group">
		<label id="elh_deudas_mig_total_deuda" for="x_mig_total_deuda" class="<?php echo $deudas_edit->LeftColumnClass ?>"><?php echo $deudas->mig_total_deuda->FldCaption() ?></label>
		<div class="<?php echo $deudas_edit->RightColumnClass ?>"><div<?php echo $deudas->mig_total_deuda->CellAttributes() ?>>
<span id="el_deudas_mig_total_deuda">
<input type="text" data-table="deudas" data-field="x_mig_total_deuda" data-page="4" name="x_mig_total_deuda" id="x_mig_total_deuda" size="30" placeholder="<?php echo ew_HtmlEncode($deudas->mig_total_deuda->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_total_deuda->EditValue ?>"<?php echo $deudas->mig_total_deuda->EditAttributes() ?>>
</span>
<?php echo $deudas->mig_total_deuda->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<?php
	if (in_array("deuda_persona", explode(",", $deudas->getCurrentDetailTable())) && $deuda_persona->DetailEdit) {
?>
<?php if ($deudas->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("deuda_persona", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "deuda_personagrid.php" ?>
<?php } ?>
<?php if (!$deudas_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $deudas_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $deudas_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fdeudasedit.Init();
</script>
<?php
$deudas_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$deudas_edit->Page_Terminate();
?>
