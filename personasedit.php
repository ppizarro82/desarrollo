<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "personasinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "tipo_personainfo.php" ?>
<?php include_once "telefonosgridcls.php" ?>
<?php include_once "direccionesgridcls.php" ?>
<?php include_once "emailsgridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$personas_edit = NULL; // Initialize page object first

class cpersonas_edit extends cpersonas {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'personas';

	// Page object name
	var $PageObjName = 'personas_edit';

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

		// Table object (personas)
		if (!isset($GLOBALS["personas"]) || get_class($GLOBALS["personas"]) == "cpersonas") {
			$GLOBALS["personas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["personas"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Table object (tipo_persona)
		if (!isset($GLOBALS['tipo_persona'])) $GLOBALS['tipo_persona'] = new ctipo_persona();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'personas', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("personaslist.php"));
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
		$this->id_tipopersona->SetVisibility();
		$this->tipo_documento->SetVisibility();
		$this->no_documento->SetVisibility();
		$this->nombres->SetVisibility();
		$this->paterno->SetVisibility();
		$this->materno->SetVisibility();
		$this->fecha_nacimiento->SetVisibility();
		$this->observaciones->SetVisibility();
		$this->estado->SetVisibility();

		// Set up detail page object
		$this->SetupDetailPages();

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

			// Process auto fill for detail table 'telefonos'
			if (@$_POST["grid"] == "ftelefonosgrid") {
				if (!isset($GLOBALS["telefonos_grid"])) $GLOBALS["telefonos_grid"] = new ctelefonos_grid;
				$GLOBALS["telefonos_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'direcciones'
			if (@$_POST["grid"] == "fdireccionesgrid") {
				if (!isset($GLOBALS["direcciones_grid"])) $GLOBALS["direcciones_grid"] = new cdirecciones_grid;
				$GLOBALS["direcciones_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'emails'
			if (@$_POST["grid"] == "femailsgrid") {
				if (!isset($GLOBALS["emails_grid"])) $GLOBALS["emails_grid"] = new cemails_grid;
				$GLOBALS["emails_grid"]->Page_Init();
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
		global $EW_EXPORT, $personas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($personas);
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
					if ($pageName == "personasview.php")
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
	var $DetailPages; // Detail pages object

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
					$this->Page_Terminate("personaslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetupDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "personaslist.php")
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
		if (!$this->id_tipopersona->FldIsDetailKey) {
			$this->id_tipopersona->setFormValue($objForm->GetValue("x_id_tipopersona"));
		}
		if (!$this->tipo_documento->FldIsDetailKey) {
			$this->tipo_documento->setFormValue($objForm->GetValue("x_tipo_documento"));
		}
		if (!$this->no_documento->FldIsDetailKey) {
			$this->no_documento->setFormValue($objForm->GetValue("x_no_documento"));
		}
		if (!$this->nombres->FldIsDetailKey) {
			$this->nombres->setFormValue($objForm->GetValue("x_nombres"));
		}
		if (!$this->paterno->FldIsDetailKey) {
			$this->paterno->setFormValue($objForm->GetValue("x_paterno"));
		}
		if (!$this->materno->FldIsDetailKey) {
			$this->materno->setFormValue($objForm->GetValue("x_materno"));
		}
		if (!$this->fecha_nacimiento->FldIsDetailKey) {
			$this->fecha_nacimiento->setFormValue($objForm->GetValue("x_fecha_nacimiento"));
			$this->fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 7);
		}
		if (!$this->observaciones->FldIsDetailKey) {
			$this->observaciones->setFormValue($objForm->GetValue("x_observaciones"));
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->Id->CurrentValue = $this->Id->FormValue;
		$this->id_tipopersona->CurrentValue = $this->id_tipopersona->FormValue;
		$this->tipo_documento->CurrentValue = $this->tipo_documento->FormValue;
		$this->no_documento->CurrentValue = $this->no_documento->FormValue;
		$this->nombres->CurrentValue = $this->nombres->FormValue;
		$this->paterno->CurrentValue = $this->paterno->FormValue;
		$this->materno->CurrentValue = $this->materno->FormValue;
		$this->fecha_nacimiento->CurrentValue = $this->fecha_nacimiento->FormValue;
		$this->fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 7);
		$this->observaciones->CurrentValue = $this->observaciones->FormValue;
		$this->estado->CurrentValue = $this->estado->FormValue;
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
		$this->id_tipopersona->setDbValue($row['id_tipopersona']);
		$this->tipo_documento->setDbValue($row['tipo_documento']);
		$this->no_documento->setDbValue($row['no_documento']);
		$this->nombres->setDbValue($row['nombres']);
		$this->paterno->setDbValue($row['paterno']);
		$this->materno->setDbValue($row['materno']);
		$this->fecha_nacimiento->setDbValue($row['fecha_nacimiento']);
		$this->fecha_registro->setDbValue($row['fecha_registro']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->estado->setDbValue($row['estado']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['Id'] = NULL;
		$row['id_tipopersona'] = NULL;
		$row['tipo_documento'] = NULL;
		$row['no_documento'] = NULL;
		$row['nombres'] = NULL;
		$row['paterno'] = NULL;
		$row['materno'] = NULL;
		$row['fecha_nacimiento'] = NULL;
		$row['fecha_registro'] = NULL;
		$row['observaciones'] = NULL;
		$row['estado'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->id_tipopersona->DbValue = $row['id_tipopersona'];
		$this->tipo_documento->DbValue = $row['tipo_documento'];
		$this->no_documento->DbValue = $row['no_documento'];
		$this->nombres->DbValue = $row['nombres'];
		$this->paterno->DbValue = $row['paterno'];
		$this->materno->DbValue = $row['materno'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->fecha_registro->DbValue = $row['fecha_registro'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->estado->DbValue = $row['estado'];
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Id
		// id_tipopersona
		// tipo_documento
		// no_documento
		// nombres
		// paterno
		// materno
		// fecha_nacimiento
		// fecha_registro
		// observaciones
		// estado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

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

			// id_tipopersona
			$this->id_tipopersona->LinkCustomAttributes = "";
			$this->id_tipopersona->HrefValue = "";
			$this->id_tipopersona->TooltipValue = "";

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

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			$this->Id->EditValue = $this->Id->CurrentValue;
			$this->Id->ViewCustomAttributes = "";

			// id_tipopersona
			$this->id_tipopersona->EditAttrs["class"] = "form-control";
			$this->id_tipopersona->EditCustomAttributes = "";
			if ($this->id_tipopersona->getSessionValue() <> "") {
				$this->id_tipopersona->CurrentValue = $this->id_tipopersona->getSessionValue();
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
			} else {
			if (trim(strval($this->id_tipopersona->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_tipopersona->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_persona`";
			$sWhereWrk = "";
			$this->id_tipopersona->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_tipopersona, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_tipopersona->EditValue = $arwrk;
			}

			// tipo_documento
			$this->tipo_documento->EditAttrs["class"] = "form-control";
			$this->tipo_documento->EditCustomAttributes = "";
			$this->tipo_documento->EditValue = $this->tipo_documento->Options(TRUE);

			// no_documento
			$this->no_documento->EditAttrs["class"] = "form-control";
			$this->no_documento->EditCustomAttributes = "";
			$this->no_documento->EditValue = ew_HtmlEncode($this->no_documento->CurrentValue);
			$this->no_documento->PlaceHolder = ew_RemoveHtml($this->no_documento->FldCaption());

			// nombres
			$this->nombres->EditAttrs["class"] = "form-control";
			$this->nombres->EditCustomAttributes = "";
			$this->nombres->EditValue = ew_HtmlEncode($this->nombres->CurrentValue);
			$this->nombres->PlaceHolder = ew_RemoveHtml($this->nombres->FldCaption());

			// paterno
			$this->paterno->EditAttrs["class"] = "form-control";
			$this->paterno->EditCustomAttributes = "";
			$this->paterno->EditValue = ew_HtmlEncode($this->paterno->CurrentValue);
			$this->paterno->PlaceHolder = ew_RemoveHtml($this->paterno->FldCaption());

			// materno
			$this->materno->EditAttrs["class"] = "form-control";
			$this->materno->EditCustomAttributes = "";
			$this->materno->EditValue = ew_HtmlEncode($this->materno->CurrentValue);
			$this->materno->PlaceHolder = ew_RemoveHtml($this->materno->FldCaption());

			// fecha_nacimiento
			$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
			$this->fecha_nacimiento->EditCustomAttributes = "";
			$this->fecha_nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_nacimiento->CurrentValue, 7));
			$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->CurrentValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// estado
			$this->estado->EditCustomAttributes = "";
			$this->estado->EditValue = $this->estado->Options(FALSE);

			// Edit refer script
			// Id

			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";

			// id_tipopersona
			$this->id_tipopersona->LinkCustomAttributes = "";
			$this->id_tipopersona->HrefValue = "";

			// tipo_documento
			$this->tipo_documento->LinkCustomAttributes = "";
			$this->tipo_documento->HrefValue = "";

			// no_documento
			$this->no_documento->LinkCustomAttributes = "";
			$this->no_documento->HrefValue = "";

			// nombres
			$this->nombres->LinkCustomAttributes = "";
			$this->nombres->HrefValue = "";

			// paterno
			$this->paterno->LinkCustomAttributes = "";
			$this->paterno->HrefValue = "";

			// materno
			$this->materno->LinkCustomAttributes = "";
			$this->materno->HrefValue = "";

			// fecha_nacimiento
			$this->fecha_nacimiento->LinkCustomAttributes = "";
			$this->fecha_nacimiento->HrefValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
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
		if (!$this->id_tipopersona->FldIsDetailKey && !is_null($this->id_tipopersona->FormValue) && $this->id_tipopersona->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_tipopersona->FldCaption(), $this->id_tipopersona->ReqErrMsg));
		}
		if (!$this->nombres->FldIsDetailKey && !is_null($this->nombres->FormValue) && $this->nombres->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombres->FldCaption(), $this->nombres->ReqErrMsg));
		}
		if (!$this->paterno->FldIsDetailKey && !is_null($this->paterno->FormValue) && $this->paterno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->paterno->FldCaption(), $this->paterno->ReqErrMsg));
		}
		if (!$this->materno->FldIsDetailKey && !is_null($this->materno->FormValue) && $this->materno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->materno->FldCaption(), $this->materno->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->fecha_nacimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_nacimiento->FldErrMsg());
		}
		if ($this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("telefonos", $DetailTblVar) && $GLOBALS["telefonos"]->DetailEdit) {
			if (!isset($GLOBALS["telefonos_grid"])) $GLOBALS["telefonos_grid"] = new ctelefonos_grid(); // get detail page object
			$GLOBALS["telefonos_grid"]->ValidateGridForm();
		}
		if (in_array("direcciones", $DetailTblVar) && $GLOBALS["direcciones"]->DetailEdit) {
			if (!isset($GLOBALS["direcciones_grid"])) $GLOBALS["direcciones_grid"] = new cdirecciones_grid(); // get detail page object
			$GLOBALS["direcciones_grid"]->ValidateGridForm();
		}
		if (in_array("emails", $DetailTblVar) && $GLOBALS["emails"]->DetailEdit) {
			if (!isset($GLOBALS["emails_grid"])) $GLOBALS["emails_grid"] = new cemails_grid(); // get detail page object
			$GLOBALS["emails_grid"]->ValidateGridForm();
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

			// id_tipopersona
			$this->id_tipopersona->SetDbValueDef($rsnew, $this->id_tipopersona->CurrentValue, 0, $this->id_tipopersona->ReadOnly);

			// tipo_documento
			$this->tipo_documento->SetDbValueDef($rsnew, $this->tipo_documento->CurrentValue, NULL, $this->tipo_documento->ReadOnly);

			// no_documento
			$this->no_documento->SetDbValueDef($rsnew, $this->no_documento->CurrentValue, NULL, $this->no_documento->ReadOnly);

			// nombres
			$this->nombres->SetDbValueDef($rsnew, $this->nombres->CurrentValue, "", $this->nombres->ReadOnly);

			// paterno
			$this->paterno->SetDbValueDef($rsnew, $this->paterno->CurrentValue, "", $this->paterno->ReadOnly);

			// materno
			$this->materno->SetDbValueDef($rsnew, $this->materno->CurrentValue, "", $this->materno->ReadOnly);

			// fecha_nacimiento
			$this->fecha_nacimiento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_nacimiento->CurrentValue, 7), NULL, $this->fecha_nacimiento->ReadOnly);

			// observaciones
			$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, NULL, $this->observaciones->ReadOnly);

			// estado
			$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, 0, $this->estado->ReadOnly);

			// Check referential integrity for master table 'tipo_persona'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_tipo_persona();
			$KeyValue = isset($rsnew['id_tipopersona']) ? $rsnew['id_tipopersona'] : $rsold['id_tipopersona'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@Id@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				if (!isset($GLOBALS["tipo_persona"])) $GLOBALS["tipo_persona"] = new ctipo_persona();
				$rsmaster = $GLOBALS["tipo_persona"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "tipo_persona", $Language->Phrase("RelatedRecordRequired"));
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
					if (in_array("telefonos", $DetailTblVar) && $GLOBALS["telefonos"]->DetailEdit) {
						if (!isset($GLOBALS["telefonos_grid"])) $GLOBALS["telefonos_grid"] = new ctelefonos_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "telefonos"); // Load user level of detail table
						$EditRow = $GLOBALS["telefonos_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("direcciones", $DetailTblVar) && $GLOBALS["direcciones"]->DetailEdit) {
						if (!isset($GLOBALS["direcciones_grid"])) $GLOBALS["direcciones_grid"] = new cdirecciones_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "direcciones"); // Load user level of detail table
						$EditRow = $GLOBALS["direcciones_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("emails", $DetailTblVar) && $GLOBALS["emails"]->DetailEdit) {
						if (!isset($GLOBALS["emails_grid"])) $GLOBALS["emails_grid"] = new cemails_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "emails"); // Load user level of detail table
						$EditRow = $GLOBALS["emails_grid"]->GridUpdate();
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
			if ($sMasterTblVar == "tipo_persona") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_Id"] <> "") {
					$GLOBALS["tipo_persona"]->Id->setQueryStringValue($_GET["fk_Id"]);
					$this->id_tipopersona->setQueryStringValue($GLOBALS["tipo_persona"]->Id->QueryStringValue);
					$this->id_tipopersona->setSessionValue($this->id_tipopersona->QueryStringValue);
					if (!is_numeric($GLOBALS["tipo_persona"]->Id->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar == "tipo_persona") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_Id"] <> "") {
					$GLOBALS["tipo_persona"]->Id->setFormValue($_POST["fk_Id"]);
					$this->id_tipopersona->setFormValue($GLOBALS["tipo_persona"]->Id->FormValue);
					$this->id_tipopersona->setSessionValue($this->id_tipopersona->FormValue);
					if (!is_numeric($GLOBALS["tipo_persona"]->Id->FormValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "tipo_persona") {
				if ($this->id_tipopersona->CurrentValue == "") $this->id_tipopersona->setSessionValue("");
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
			if (in_array("telefonos", $DetailTblVar)) {
				if (!isset($GLOBALS["telefonos_grid"]))
					$GLOBALS["telefonos_grid"] = new ctelefonos_grid;
				if ($GLOBALS["telefonos_grid"]->DetailEdit) {
					$GLOBALS["telefonos_grid"]->CurrentMode = "edit";
					$GLOBALS["telefonos_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["telefonos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["telefonos_grid"]->setStartRecordNumber(1);
					$GLOBALS["telefonos_grid"]->id_persona->FldIsDetailKey = TRUE;
					$GLOBALS["telefonos_grid"]->id_persona->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["telefonos_grid"]->id_persona->setSessionValue($GLOBALS["telefonos_grid"]->id_persona->CurrentValue);
				}
			}
			if (in_array("direcciones", $DetailTblVar)) {
				if (!isset($GLOBALS["direcciones_grid"]))
					$GLOBALS["direcciones_grid"] = new cdirecciones_grid;
				if ($GLOBALS["direcciones_grid"]->DetailEdit) {
					$GLOBALS["direcciones_grid"]->CurrentMode = "edit";
					$GLOBALS["direcciones_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["direcciones_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["direcciones_grid"]->setStartRecordNumber(1);
					$GLOBALS["direcciones_grid"]->id_persona->FldIsDetailKey = TRUE;
					$GLOBALS["direcciones_grid"]->id_persona->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["direcciones_grid"]->id_persona->setSessionValue($GLOBALS["direcciones_grid"]->id_persona->CurrentValue);
				}
			}
			if (in_array("emails", $DetailTblVar)) {
				if (!isset($GLOBALS["emails_grid"]))
					$GLOBALS["emails_grid"] = new cemails_grid;
				if ($GLOBALS["emails_grid"]->DetailEdit) {
					$GLOBALS["emails_grid"]->CurrentMode = "edit";
					$GLOBALS["emails_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["emails_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["emails_grid"]->setStartRecordNumber(1);
					$GLOBALS["emails_grid"]->id_persona->FldIsDetailKey = TRUE;
					$GLOBALS["emails_grid"]->id_persona->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["emails_grid"]->id_persona->setSessionValue($GLOBALS["emails_grid"]->id_persona->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("personaslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Set up detail pages
	function SetupDetailPages() {
		$pages = new cSubPages();
		$pages->Style = "tabs";
		$pages->Add('telefonos');
		$pages->Add('direcciones');
		$pages->Add('emails');
		$this->DetailPages = $pages;
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_tipopersona":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_persona`";
			$sWhereWrk = "";
			$this->id_tipopersona->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_tipopersona, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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
if (!isset($personas_edit)) $personas_edit = new cpersonas_edit();

// Page init
$personas_edit->Page_Init();

// Page main
$personas_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$personas_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fpersonasedit = new ew_Form("fpersonasedit", "edit");

// Validate form
fpersonasedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_tipopersona");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $personas->id_tipopersona->FldCaption(), $personas->id_tipopersona->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombres");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $personas->nombres->FldCaption(), $personas->nombres->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_paterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $personas->paterno->FldCaption(), $personas->paterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_materno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $personas->materno->FldCaption(), $personas->materno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fecha_nacimiento");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($personas->fecha_nacimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $personas->estado->FldCaption(), $personas->estado->ReqErrMsg)) ?>");

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
fpersonasedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpersonasedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fpersonasedit.Lists["x_id_tipopersona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipo_persona"};
fpersonasedit.Lists["x_id_tipopersona"].Data = "<?php echo $personas_edit->id_tipopersona->LookupFilterQuery(FALSE, "edit") ?>";
fpersonasedit.Lists["x_tipo_documento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpersonasedit.Lists["x_tipo_documento"].Options = <?php echo json_encode($personas_edit->tipo_documento->Options()) ?>;
fpersonasedit.Lists["x_estado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpersonasedit.Lists["x_estado"].Options = <?php echo json_encode($personas_edit->estado->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $personas_edit->ShowPageHeader(); ?>
<?php
$personas_edit->ShowMessage();
?>
<form name="fpersonasedit" id="fpersonasedit" class="<?php echo $personas_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($personas_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $personas_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="personas">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($personas_edit->IsModal) ?>">
<?php if ($personas->getCurrentMasterTable() == "tipo_persona") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="tipo_persona">
<input type="hidden" name="fk_Id" value="<?php echo $personas->id_tipopersona->getSessionValue() ?>">
<?php } ?>
<div class="ewEditDiv"><!-- page* -->
<?php if ($personas->Id->Visible) { // Id ?>
	<div id="r_Id" class="form-group">
		<label id="elh_personas_Id" class="<?php echo $personas_edit->LeftColumnClass ?>"><?php echo $personas->Id->FldCaption() ?></label>
		<div class="<?php echo $personas_edit->RightColumnClass ?>"><div<?php echo $personas->Id->CellAttributes() ?>>
<span id="el_personas_Id">
<span<?php echo $personas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="personas" data-field="x_Id" name="x_Id" id="x_Id" value="<?php echo ew_HtmlEncode($personas->Id->CurrentValue) ?>">
<?php echo $personas->Id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($personas->id_tipopersona->Visible) { // id_tipopersona ?>
	<div id="r_id_tipopersona" class="form-group">
		<label id="elh_personas_id_tipopersona" for="x_id_tipopersona" class="<?php echo $personas_edit->LeftColumnClass ?>"><?php echo $personas->id_tipopersona->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $personas_edit->RightColumnClass ?>"><div<?php echo $personas->id_tipopersona->CellAttributes() ?>>
<?php if ($personas->id_tipopersona->getSessionValue() <> "") { ?>
<span id="el_personas_id_tipopersona">
<span<?php echo $personas->id_tipopersona->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $personas->id_tipopersona->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_id_tipopersona" name="x_id_tipopersona" value="<?php echo ew_HtmlEncode($personas->id_tipopersona->CurrentValue) ?>">
<?php } else { ?>
<span id="el_personas_id_tipopersona">
<select data-table="personas" data-field="x_id_tipopersona" data-value-separator="<?php echo $personas->id_tipopersona->DisplayValueSeparatorAttribute() ?>" id="x_id_tipopersona" name="x_id_tipopersona"<?php echo $personas->id_tipopersona->EditAttributes() ?>>
<?php echo $personas->id_tipopersona->SelectOptionListHtml("x_id_tipopersona") ?>
</select>
</span>
<?php } ?>
<?php echo $personas->id_tipopersona->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($personas->tipo_documento->Visible) { // tipo_documento ?>
	<div id="r_tipo_documento" class="form-group">
		<label id="elh_personas_tipo_documento" for="x_tipo_documento" class="<?php echo $personas_edit->LeftColumnClass ?>"><?php echo $personas->tipo_documento->FldCaption() ?></label>
		<div class="<?php echo $personas_edit->RightColumnClass ?>"><div<?php echo $personas->tipo_documento->CellAttributes() ?>>
<span id="el_personas_tipo_documento">
<select data-table="personas" data-field="x_tipo_documento" data-value-separator="<?php echo $personas->tipo_documento->DisplayValueSeparatorAttribute() ?>" id="x_tipo_documento" name="x_tipo_documento"<?php echo $personas->tipo_documento->EditAttributes() ?>>
<?php echo $personas->tipo_documento->SelectOptionListHtml("x_tipo_documento") ?>
</select>
</span>
<?php echo $personas->tipo_documento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($personas->no_documento->Visible) { // no_documento ?>
	<div id="r_no_documento" class="form-group">
		<label id="elh_personas_no_documento" for="x_no_documento" class="<?php echo $personas_edit->LeftColumnClass ?>"><?php echo $personas->no_documento->FldCaption() ?></label>
		<div class="<?php echo $personas_edit->RightColumnClass ?>"><div<?php echo $personas->no_documento->CellAttributes() ?>>
<span id="el_personas_no_documento">
<input type="text" data-table="personas" data-field="x_no_documento" name="x_no_documento" id="x_no_documento" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->no_documento->getPlaceHolder()) ?>" value="<?php echo $personas->no_documento->EditValue ?>"<?php echo $personas->no_documento->EditAttributes() ?>>
</span>
<?php echo $personas->no_documento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($personas->nombres->Visible) { // nombres ?>
	<div id="r_nombres" class="form-group">
		<label id="elh_personas_nombres" for="x_nombres" class="<?php echo $personas_edit->LeftColumnClass ?>"><?php echo $personas->nombres->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $personas_edit->RightColumnClass ?>"><div<?php echo $personas->nombres->CellAttributes() ?>>
<span id="el_personas_nombres">
<input type="text" data-table="personas" data-field="x_nombres" name="x_nombres" id="x_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->nombres->getPlaceHolder()) ?>" value="<?php echo $personas->nombres->EditValue ?>"<?php echo $personas->nombres->EditAttributes() ?>>
</span>
<?php echo $personas->nombres->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($personas->paterno->Visible) { // paterno ?>
	<div id="r_paterno" class="form-group">
		<label id="elh_personas_paterno" for="x_paterno" class="<?php echo $personas_edit->LeftColumnClass ?>"><?php echo $personas->paterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $personas_edit->RightColumnClass ?>"><div<?php echo $personas->paterno->CellAttributes() ?>>
<span id="el_personas_paterno">
<input type="text" data-table="personas" data-field="x_paterno" name="x_paterno" id="x_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->paterno->getPlaceHolder()) ?>" value="<?php echo $personas->paterno->EditValue ?>"<?php echo $personas->paterno->EditAttributes() ?>>
</span>
<?php echo $personas->paterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($personas->materno->Visible) { // materno ?>
	<div id="r_materno" class="form-group">
		<label id="elh_personas_materno" for="x_materno" class="<?php echo $personas_edit->LeftColumnClass ?>"><?php echo $personas->materno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $personas_edit->RightColumnClass ?>"><div<?php echo $personas->materno->CellAttributes() ?>>
<span id="el_personas_materno">
<input type="text" data-table="personas" data-field="x_materno" name="x_materno" id="x_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->materno->getPlaceHolder()) ?>" value="<?php echo $personas->materno->EditValue ?>"<?php echo $personas->materno->EditAttributes() ?>>
</span>
<?php echo $personas->materno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($personas->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<div id="r_fecha_nacimiento" class="form-group">
		<label id="elh_personas_fecha_nacimiento" for="x_fecha_nacimiento" class="<?php echo $personas_edit->LeftColumnClass ?>"><?php echo $personas->fecha_nacimiento->FldCaption() ?></label>
		<div class="<?php echo $personas_edit->RightColumnClass ?>"><div<?php echo $personas->fecha_nacimiento->CellAttributes() ?>>
<span id="el_personas_fecha_nacimiento">
<input type="text" data-table="personas" data-field="x_fecha_nacimiento" data-format="7" name="x_fecha_nacimiento" id="x_fecha_nacimiento" size="20" placeholder="<?php echo ew_HtmlEncode($personas->fecha_nacimiento->getPlaceHolder()) ?>" value="<?php echo $personas->fecha_nacimiento->EditValue ?>"<?php echo $personas->fecha_nacimiento->EditAttributes() ?>>
<?php if (!$personas->fecha_nacimiento->ReadOnly && !$personas->fecha_nacimiento->Disabled && !isset($personas->fecha_nacimiento->EditAttrs["readonly"]) && !isset($personas->fecha_nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpersonasedit", "x_fecha_nacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php echo $personas->fecha_nacimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($personas->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label id="elh_personas_observaciones" for="x_observaciones" class="<?php echo $personas_edit->LeftColumnClass ?>"><?php echo $personas->observaciones->FldCaption() ?></label>
		<div class="<?php echo $personas_edit->RightColumnClass ?>"><div<?php echo $personas->observaciones->CellAttributes() ?>>
<span id="el_personas_observaciones">
<textarea data-table="personas" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($personas->observaciones->getPlaceHolder()) ?>"<?php echo $personas->observaciones->EditAttributes() ?>><?php echo $personas->observaciones->EditValue ?></textarea>
</span>
<?php echo $personas->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($personas->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_personas_estado" class="<?php echo $personas_edit->LeftColumnClass ?>"><?php echo $personas->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $personas_edit->RightColumnClass ?>"><div<?php echo $personas->estado->CellAttributes() ?>>
<span id="el_personas_estado">
<div id="tp_x_estado" class="ewTemplate"><input type="radio" data-table="personas" data-field="x_estado" data-value-separator="<?php echo $personas->estado->DisplayValueSeparatorAttribute() ?>" name="x_estado" id="x_estado" value="{value}"<?php echo $personas->estado->EditAttributes() ?>></div>
<div id="dsl_x_estado" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $personas->estado->RadioButtonListHtml(FALSE, "x_estado") ?>
</div></div>
</span>
<?php echo $personas->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if ($personas->getCurrentDetailTable() <> "") { ?>
<?php
	$personas_edit->DetailPages->ValidKeys = explode(",", $personas->getCurrentDetailTable());
	$FirstActiveDetailTable = $personas_edit->DetailPages->ActivePageIndex();
?>
<div class="ewDetailPages"><!-- detail-pages -->
<div class="nav-tabs-custom" id="personas_edit_details"><!-- .nav-tabs-custom -->
	<ul class="nav<?php echo $personas_edit->DetailPages->NavStyle() ?>"><!-- .nav -->
<?php
	if (in_array("telefonos", explode(",", $personas->getCurrentDetailTable())) && $telefonos->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "telefonos") {
			$FirstActiveDetailTable = "telefonos";
		}
?>
		<li<?php echo $personas_edit->DetailPages->TabStyle("telefonos") ?>><a href="#tab_telefonos" data-toggle="tab"><?php echo $Language->TablePhrase("telefonos", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("direcciones", explode(",", $personas->getCurrentDetailTable())) && $direcciones->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "direcciones") {
			$FirstActiveDetailTable = "direcciones";
		}
?>
		<li<?php echo $personas_edit->DetailPages->TabStyle("direcciones") ?>><a href="#tab_direcciones" data-toggle="tab"><?php echo $Language->TablePhrase("direcciones", "TblCaption") ?></a></li>
<?php
	}
?>
<?php
	if (in_array("emails", explode(",", $personas->getCurrentDetailTable())) && $emails->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "emails") {
			$FirstActiveDetailTable = "emails";
		}
?>
		<li<?php echo $personas_edit->DetailPages->TabStyle("emails") ?>><a href="#tab_emails" data-toggle="tab"><?php echo $Language->TablePhrase("emails", "TblCaption") ?></a></li>
<?php
	}
?>
	</ul><!-- /.nav -->
	<div class="tab-content"><!-- .tab-content -->
<?php
	if (in_array("telefonos", explode(",", $personas->getCurrentDetailTable())) && $telefonos->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "telefonos") {
			$FirstActiveDetailTable = "telefonos";
		}
?>
		<div class="tab-pane<?php echo $personas_edit->DetailPages->PageStyle("telefonos") ?>" id="tab_telefonos"><!-- page* -->
<?php include_once "telefonosgrid.php" ?>
		</div><!-- /page* -->
<?php } ?>
<?php
	if (in_array("direcciones", explode(",", $personas->getCurrentDetailTable())) && $direcciones->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "direcciones") {
			$FirstActiveDetailTable = "direcciones";
		}
?>
		<div class="tab-pane<?php echo $personas_edit->DetailPages->PageStyle("direcciones") ?>" id="tab_direcciones"><!-- page* -->
<?php include_once "direccionesgrid.php" ?>
		</div><!-- /page* -->
<?php } ?>
<?php
	if (in_array("emails", explode(",", $personas->getCurrentDetailTable())) && $emails->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "emails") {
			$FirstActiveDetailTable = "emails";
		}
?>
		<div class="tab-pane<?php echo $personas_edit->DetailPages->PageStyle("emails") ?>" id="tab_emails"><!-- page* -->
<?php include_once "emailsgrid.php" ?>
		</div><!-- /page* -->
<?php } ?>
	</div><!-- /.tab-content -->
</div><!-- /.nav-tabs-custom -->
</div><!-- /detail-pages -->
<?php } ?>
<?php if (!$personas_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $personas_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $personas_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fpersonasedit.Init();
</script>
<?php
$personas_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$personas_edit->Page_Terminate();
?>
