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

$direcciones_add = NULL; // Initialize page object first

class cdirecciones_add extends cdirecciones {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'direcciones';

	// Page object name
	var $PageObjName = 'direcciones_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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
		$this->longitud->SetVisibility();
		$this->latitud->SetVisibility();

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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "direccionesview.php")
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;
	var $MultiPages; // Multi pages object

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["Id"] != "") {
				$this->Id->setQueryStringValue($_GET["Id"]);
				$this->setKey("Id", $this->Id->CurrentValue); // Set up key
			} else {
				$this->setKey("Id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Load old record / default values
		$loaded = $this->LoadOldRecord();

		// Load form values
		if (@$_POST["a_add"] <> "") {
			$this->LoadFormValues(); // Load form values
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Blank record
				break;
			case "C": // Copy an existing record
				if (!$loaded) { // Record not loaded
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("direccioneslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "direccioneslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "direccionesview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->Id->CurrentValue = NULL;
		$this->Id->OldValue = $this->Id->CurrentValue;
		$this->id_fuente->CurrentValue = 0;
		$this->id_gestion->CurrentValue = 0;
		$this->id_tipodireccion->CurrentValue = NULL;
		$this->id_tipodireccion->OldValue = $this->id_tipodireccion->CurrentValue;
		$this->tipo_documento->CurrentValue = NULL;
		$this->tipo_documento->OldValue = $this->tipo_documento->CurrentValue;
		$this->no_documento->CurrentValue = NULL;
		$this->no_documento->OldValue = $this->no_documento->CurrentValue;
		$this->nombres->CurrentValue = NULL;
		$this->nombres->OldValue = $this->nombres->CurrentValue;
		$this->paterno->CurrentValue = NULL;
		$this->paterno->OldValue = $this->paterno->CurrentValue;
		$this->materno->CurrentValue = NULL;
		$this->materno->OldValue = $this->materno->CurrentValue;
		$this->pais->CurrentValue = NULL;
		$this->pais->OldValue = $this->pais->CurrentValue;
		$this->departamento->CurrentValue = NULL;
		$this->departamento->OldValue = $this->departamento->CurrentValue;
		$this->provincia->CurrentValue = NULL;
		$this->provincia->OldValue = $this->provincia->CurrentValue;
		$this->municipio->CurrentValue = NULL;
		$this->municipio->OldValue = $this->municipio->CurrentValue;
		$this->localidad->CurrentValue = NULL;
		$this->localidad->OldValue = $this->localidad->CurrentValue;
		$this->distrito->CurrentValue = NULL;
		$this->distrito->OldValue = $this->distrito->CurrentValue;
		$this->zona->CurrentValue = NULL;
		$this->zona->OldValue = $this->zona->CurrentValue;
		$this->direccion1->CurrentValue = NULL;
		$this->direccion1->OldValue = $this->direccion1->CurrentValue;
		$this->direccion2->CurrentValue = NULL;
		$this->direccion2->OldValue = $this->direccion2->CurrentValue;
		$this->direccion3->CurrentValue = NULL;
		$this->direccion3->OldValue = $this->direccion3->CurrentValue;
		$this->direccion4->CurrentValue = NULL;
		$this->direccion4->OldValue = $this->direccion4->CurrentValue;
		$this->mapa->CurrentValue = NULL;
		$this->mapa->OldValue = $this->mapa->CurrentValue;
		$this->longitud->CurrentValue = NULL;
		$this->longitud->OldValue = $this->longitud->CurrentValue;
		$this->latitud->CurrentValue = NULL;
		$this->latitud->OldValue = $this->latitud->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_fuente->FldIsDetailKey) {
			$this->id_fuente->setFormValue($objForm->GetValue("x_id_fuente"));
		}
		if (!$this->id_gestion->FldIsDetailKey) {
			$this->id_gestion->setFormValue($objForm->GetValue("x_id_gestion"));
		}
		if (!$this->id_tipodireccion->FldIsDetailKey) {
			$this->id_tipodireccion->setFormValue($objForm->GetValue("x_id_tipodireccion"));
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
		if (!$this->pais->FldIsDetailKey) {
			$this->pais->setFormValue($objForm->GetValue("x_pais"));
		}
		if (!$this->departamento->FldIsDetailKey) {
			$this->departamento->setFormValue($objForm->GetValue("x_departamento"));
		}
		if (!$this->provincia->FldIsDetailKey) {
			$this->provincia->setFormValue($objForm->GetValue("x_provincia"));
		}
		if (!$this->municipio->FldIsDetailKey) {
			$this->municipio->setFormValue($objForm->GetValue("x_municipio"));
		}
		if (!$this->localidad->FldIsDetailKey) {
			$this->localidad->setFormValue($objForm->GetValue("x_localidad"));
		}
		if (!$this->distrito->FldIsDetailKey) {
			$this->distrito->setFormValue($objForm->GetValue("x_distrito"));
		}
		if (!$this->zona->FldIsDetailKey) {
			$this->zona->setFormValue($objForm->GetValue("x_zona"));
		}
		if (!$this->direccion1->FldIsDetailKey) {
			$this->direccion1->setFormValue($objForm->GetValue("x_direccion1"));
		}
		if (!$this->direccion2->FldIsDetailKey) {
			$this->direccion2->setFormValue($objForm->GetValue("x_direccion2"));
		}
		if (!$this->direccion3->FldIsDetailKey) {
			$this->direccion3->setFormValue($objForm->GetValue("x_direccion3"));
		}
		if (!$this->direccion4->FldIsDetailKey) {
			$this->direccion4->setFormValue($objForm->GetValue("x_direccion4"));
		}
		if (!$this->longitud->FldIsDetailKey) {
			$this->longitud->setFormValue($objForm->GetValue("x_longitud"));
		}
		if (!$this->latitud->FldIsDetailKey) {
			$this->latitud->setFormValue($objForm->GetValue("x_latitud"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_fuente->CurrentValue = $this->id_fuente->FormValue;
		$this->id_gestion->CurrentValue = $this->id_gestion->FormValue;
		$this->id_tipodireccion->CurrentValue = $this->id_tipodireccion->FormValue;
		$this->tipo_documento->CurrentValue = $this->tipo_documento->FormValue;
		$this->no_documento->CurrentValue = $this->no_documento->FormValue;
		$this->nombres->CurrentValue = $this->nombres->FormValue;
		$this->paterno->CurrentValue = $this->paterno->FormValue;
		$this->materno->CurrentValue = $this->materno->FormValue;
		$this->pais->CurrentValue = $this->pais->FormValue;
		$this->departamento->CurrentValue = $this->departamento->FormValue;
		$this->provincia->CurrentValue = $this->provincia->FormValue;
		$this->municipio->CurrentValue = $this->municipio->FormValue;
		$this->localidad->CurrentValue = $this->localidad->FormValue;
		$this->distrito->CurrentValue = $this->distrito->FormValue;
		$this->zona->CurrentValue = $this->zona->FormValue;
		$this->direccion1->CurrentValue = $this->direccion1->FormValue;
		$this->direccion2->CurrentValue = $this->direccion2->FormValue;
		$this->direccion3->CurrentValue = $this->direccion3->FormValue;
		$this->direccion4->CurrentValue = $this->direccion4->FormValue;
		$this->longitud->CurrentValue = $this->longitud->FormValue;
		$this->latitud->CurrentValue = $this->latitud->FormValue;
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
		$this->LoadDefaultValues();
		$row = array();
		$row['Id'] = $this->Id->CurrentValue;
		$row['id_fuente'] = $this->id_fuente->CurrentValue;
		$row['id_gestion'] = $this->id_gestion->CurrentValue;
		$row['id_tipodireccion'] = $this->id_tipodireccion->CurrentValue;
		$row['tipo_documento'] = $this->tipo_documento->CurrentValue;
		$row['no_documento'] = $this->no_documento->CurrentValue;
		$row['nombres'] = $this->nombres->CurrentValue;
		$row['paterno'] = $this->paterno->CurrentValue;
		$row['materno'] = $this->materno->CurrentValue;
		$row['pais'] = $this->pais->CurrentValue;
		$row['departamento'] = $this->departamento->CurrentValue;
		$row['provincia'] = $this->provincia->CurrentValue;
		$row['municipio'] = $this->municipio->CurrentValue;
		$row['localidad'] = $this->localidad->CurrentValue;
		$row['distrito'] = $this->distrito->CurrentValue;
		$row['zona'] = $this->zona->CurrentValue;
		$row['direccion1'] = $this->direccion1->CurrentValue;
		$row['direccion2'] = $this->direccion2->CurrentValue;
		$row['direccion3'] = $this->direccion3->CurrentValue;
		$row['direccion4'] = $this->direccion4->CurrentValue;
		$row['mapa'] = $this->mapa->CurrentValue;
		$row['longitud'] = $this->longitud->CurrentValue;
		$row['latitud'] = $this->latitud->CurrentValue;
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

			// longitud
			$this->longitud->LinkCustomAttributes = "";
			$this->longitud->HrefValue = "";
			$this->longitud->TooltipValue = "";

			// latitud
			$this->latitud->LinkCustomAttributes = "";
			$this->latitud->HrefValue = "";
			$this->latitud->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_fuente
			$this->id_fuente->EditAttrs["class"] = "form-control";
			$this->id_fuente->EditCustomAttributes = "";
			if (trim(strval($this->id_fuente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_fuente->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `fuentes`";
			$sWhereWrk = "";
			$this->id_fuente->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_fuente, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_fuente->EditValue = $arwrk;

			// id_gestion
			$this->id_gestion->EditAttrs["class"] = "form-control";
			$this->id_gestion->EditCustomAttributes = "";
			if (trim(strval($this->id_gestion->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_gestion->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `gestiones`";
			$sWhereWrk = "";
			$this->id_gestion->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_gestion, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_gestion->EditValue = $arwrk;

			// id_tipodireccion
			$this->id_tipodireccion->EditAttrs["class"] = "form-control";
			$this->id_tipodireccion->EditCustomAttributes = "";
			if (trim(strval($this->id_tipodireccion->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_tipodireccion->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipo_direccion`";
			$sWhereWrk = "";
			$this->id_tipodireccion->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_tipodireccion, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_tipodireccion->EditValue = $arwrk;

			// tipo_documento
			$this->tipo_documento->EditAttrs["class"] = "form-control";
			$this->tipo_documento->EditCustomAttributes = "";
			$this->tipo_documento->EditValue = ew_HtmlEncode($this->tipo_documento->CurrentValue);
			$this->tipo_documento->PlaceHolder = ew_RemoveHtml($this->tipo_documento->FldCaption());

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

			// pais
			$this->pais->EditAttrs["class"] = "form-control";
			$this->pais->EditCustomAttributes = "";
			$this->pais->EditValue = ew_HtmlEncode($this->pais->CurrentValue);
			$this->pais->PlaceHolder = ew_RemoveHtml($this->pais->FldCaption());

			// departamento
			$this->departamento->EditAttrs["class"] = "form-control";
			$this->departamento->EditCustomAttributes = "";
			$this->departamento->EditValue = ew_HtmlEncode($this->departamento->CurrentValue);
			$this->departamento->PlaceHolder = ew_RemoveHtml($this->departamento->FldCaption());

			// provincia
			$this->provincia->EditAttrs["class"] = "form-control";
			$this->provincia->EditCustomAttributes = "";
			$this->provincia->EditValue = ew_HtmlEncode($this->provincia->CurrentValue);
			$this->provincia->PlaceHolder = ew_RemoveHtml($this->provincia->FldCaption());

			// municipio
			$this->municipio->EditAttrs["class"] = "form-control";
			$this->municipio->EditCustomAttributes = "";
			$this->municipio->EditValue = ew_HtmlEncode($this->municipio->CurrentValue);
			$this->municipio->PlaceHolder = ew_RemoveHtml($this->municipio->FldCaption());

			// localidad
			$this->localidad->EditAttrs["class"] = "form-control";
			$this->localidad->EditCustomAttributes = "";
			$this->localidad->EditValue = ew_HtmlEncode($this->localidad->CurrentValue);
			$this->localidad->PlaceHolder = ew_RemoveHtml($this->localidad->FldCaption());

			// distrito
			$this->distrito->EditAttrs["class"] = "form-control";
			$this->distrito->EditCustomAttributes = "";
			$this->distrito->EditValue = ew_HtmlEncode($this->distrito->CurrentValue);
			$this->distrito->PlaceHolder = ew_RemoveHtml($this->distrito->FldCaption());

			// zona
			$this->zona->EditAttrs["class"] = "form-control";
			$this->zona->EditCustomAttributes = "";
			$this->zona->EditValue = ew_HtmlEncode($this->zona->CurrentValue);
			$this->zona->PlaceHolder = ew_RemoveHtml($this->zona->FldCaption());

			// direccion1
			$this->direccion1->EditAttrs["class"] = "form-control";
			$this->direccion1->EditCustomAttributes = "";
			$this->direccion1->EditValue = ew_HtmlEncode($this->direccion1->CurrentValue);
			$this->direccion1->PlaceHolder = ew_RemoveHtml($this->direccion1->FldCaption());

			// direccion2
			$this->direccion2->EditAttrs["class"] = "form-control";
			$this->direccion2->EditCustomAttributes = "";
			$this->direccion2->EditValue = ew_HtmlEncode($this->direccion2->CurrentValue);
			$this->direccion2->PlaceHolder = ew_RemoveHtml($this->direccion2->FldCaption());

			// direccion3
			$this->direccion3->EditAttrs["class"] = "form-control";
			$this->direccion3->EditCustomAttributes = "";
			$this->direccion3->EditValue = ew_HtmlEncode($this->direccion3->CurrentValue);
			$this->direccion3->PlaceHolder = ew_RemoveHtml($this->direccion3->FldCaption());

			// direccion4
			$this->direccion4->EditAttrs["class"] = "form-control";
			$this->direccion4->EditCustomAttributes = "";
			$this->direccion4->EditValue = ew_HtmlEncode($this->direccion4->CurrentValue);
			$this->direccion4->PlaceHolder = ew_RemoveHtml($this->direccion4->FldCaption());

			// longitud
			$this->longitud->EditAttrs["class"] = "form-control";
			$this->longitud->EditCustomAttributes = 'readonly=true';
			$this->longitud->EditValue = ew_HtmlEncode($this->longitud->CurrentValue);
			$this->longitud->PlaceHolder = ew_RemoveHtml($this->longitud->FldCaption());

			// latitud
			$this->latitud->EditAttrs["class"] = "form-control";
			$this->latitud->EditCustomAttributes = 'readonly=true';
			$this->latitud->EditValue = ew_HtmlEncode($this->latitud->CurrentValue);
			$this->latitud->PlaceHolder = ew_RemoveHtml($this->latitud->FldCaption());

			// Add refer script
			// id_fuente

			$this->id_fuente->LinkCustomAttributes = "";
			$this->id_fuente->HrefValue = "";

			// id_gestion
			$this->id_gestion->LinkCustomAttributes = "";
			$this->id_gestion->HrefValue = "";

			// id_tipodireccion
			$this->id_tipodireccion->LinkCustomAttributes = "";
			$this->id_tipodireccion->HrefValue = "";

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

			// pais
			$this->pais->LinkCustomAttributes = "";
			$this->pais->HrefValue = "";

			// departamento
			$this->departamento->LinkCustomAttributes = "";
			$this->departamento->HrefValue = "";

			// provincia
			$this->provincia->LinkCustomAttributes = "";
			$this->provincia->HrefValue = "";

			// municipio
			$this->municipio->LinkCustomAttributes = "";
			$this->municipio->HrefValue = "";

			// localidad
			$this->localidad->LinkCustomAttributes = "";
			$this->localidad->HrefValue = "";

			// distrito
			$this->distrito->LinkCustomAttributes = "";
			$this->distrito->HrefValue = "";

			// zona
			$this->zona->LinkCustomAttributes = "";
			$this->zona->HrefValue = "";

			// direccion1
			$this->direccion1->LinkCustomAttributes = "";
			$this->direccion1->HrefValue = "";

			// direccion2
			$this->direccion2->LinkCustomAttributes = "";
			$this->direccion2->HrefValue = "";

			// direccion3
			$this->direccion3->LinkCustomAttributes = "";
			$this->direccion3->HrefValue = "";

			// direccion4
			$this->direccion4->LinkCustomAttributes = "";
			$this->direccion4->HrefValue = "";

			// longitud
			$this->longitud->LinkCustomAttributes = "";
			$this->longitud->HrefValue = "";

			// latitud
			$this->latitud->LinkCustomAttributes = "";
			$this->latitud->HrefValue = "";
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
		if (!$this->nombres->FldIsDetailKey && !is_null($this->nombres->FormValue) && $this->nombres->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nombres->FldCaption(), $this->nombres->ReqErrMsg));
		}
		if (!$this->direccion1->FldIsDetailKey && !is_null($this->direccion1->FormValue) && $this->direccion1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->direccion1->FldCaption(), $this->direccion1->ReqErrMsg));
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// id_fuente
		$this->id_fuente->SetDbValueDef($rsnew, $this->id_fuente->CurrentValue, 0, strval($this->id_fuente->CurrentValue) == "");

		// id_gestion
		$this->id_gestion->SetDbValueDef($rsnew, $this->id_gestion->CurrentValue, 0, strval($this->id_gestion->CurrentValue) == "");

		// id_tipodireccion
		$this->id_tipodireccion->SetDbValueDef($rsnew, $this->id_tipodireccion->CurrentValue, 0, strval($this->id_tipodireccion->CurrentValue) == "");

		// tipo_documento
		$this->tipo_documento->SetDbValueDef($rsnew, $this->tipo_documento->CurrentValue, NULL, FALSE);

		// no_documento
		$this->no_documento->SetDbValueDef($rsnew, $this->no_documento->CurrentValue, NULL, FALSE);

		// nombres
		$this->nombres->SetDbValueDef($rsnew, $this->nombres->CurrentValue, "", FALSE);

		// paterno
		$this->paterno->SetDbValueDef($rsnew, $this->paterno->CurrentValue, NULL, FALSE);

		// materno
		$this->materno->SetDbValueDef($rsnew, $this->materno->CurrentValue, NULL, FALSE);

		// pais
		$this->pais->SetDbValueDef($rsnew, $this->pais->CurrentValue, NULL, FALSE);

		// departamento
		$this->departamento->SetDbValueDef($rsnew, $this->departamento->CurrentValue, NULL, FALSE);

		// provincia
		$this->provincia->SetDbValueDef($rsnew, $this->provincia->CurrentValue, NULL, FALSE);

		// municipio
		$this->municipio->SetDbValueDef($rsnew, $this->municipio->CurrentValue, NULL, FALSE);

		// localidad
		$this->localidad->SetDbValueDef($rsnew, $this->localidad->CurrentValue, NULL, FALSE);

		// distrito
		$this->distrito->SetDbValueDef($rsnew, $this->distrito->CurrentValue, NULL, FALSE);

		// zona
		$this->zona->SetDbValueDef($rsnew, $this->zona->CurrentValue, NULL, FALSE);

		// direccion1
		$this->direccion1->SetDbValueDef($rsnew, $this->direccion1->CurrentValue, "", FALSE);

		// direccion2
		$this->direccion2->SetDbValueDef($rsnew, $this->direccion2->CurrentValue, NULL, FALSE);

		// direccion3
		$this->direccion3->SetDbValueDef($rsnew, $this->direccion3->CurrentValue, NULL, FALSE);

		// direccion4
		$this->direccion4->SetDbValueDef($rsnew, $this->direccion4->CurrentValue, NULL, FALSE);

		// longitud
		$this->longitud->SetDbValueDef($rsnew, $this->longitud->CurrentValue, NULL, FALSE);

		// latitud
		$this->latitud->SetDbValueDef($rsnew, $this->latitud->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("direccioneslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
		case "x_id_fuente":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `fuentes`";
			$sWhereWrk = "";
			$this->id_fuente->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_fuente, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_gestion":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `gestiones`";
			$sWhereWrk = "";
			$this->id_gestion->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_gestion, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_tipodireccion":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipo_direccion`";
			$sWhereWrk = "";
			$this->id_tipodireccion->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_tipodireccion, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($direcciones_add)) $direcciones_add = new cdirecciones_add();

// Page init
$direcciones_add->Page_Init();

// Page main
$direcciones_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$direcciones_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fdireccionesadd = new ew_Form("fdireccionesadd", "add");

// Validate form
fdireccionesadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nombres");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $direcciones->nombres->FldCaption(), $direcciones->nombres->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_direccion1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $direcciones->direccion1->FldCaption(), $direcciones->direccion1->ReqErrMsg)) ?>");

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
fdireccionesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdireccionesadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
fdireccionesadd.MultiPage = new ew_MultiPage("fdireccionesadd");

// Dynamic selection lists
fdireccionesadd.Lists["x_id_fuente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"fuentes"};
fdireccionesadd.Lists["x_id_fuente"].Data = "<?php echo $direcciones_add->id_fuente->LookupFilterQuery(FALSE, "add") ?>";
fdireccionesadd.Lists["x_id_gestion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gestiones"};
fdireccionesadd.Lists["x_id_gestion"].Data = "<?php echo $direcciones_add->id_gestion->LookupFilterQuery(FALSE, "add") ?>";
fdireccionesadd.Lists["x_id_tipodireccion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipo_direccion"};
fdireccionesadd.Lists["x_id_tipodireccion"].Data = "<?php echo $direcciones_add->id_tipodireccion->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $direcciones_add->ShowPageHeader(); ?>
<?php
$direcciones_add->ShowMessage();
?>
<form name="fdireccionesadd" id="fdireccionesadd" class="<?php echo $direcciones_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($direcciones_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $direcciones_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="direcciones">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($direcciones_add->IsModal) ?>">
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="direcciones_add"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $direcciones_add->MultiPages->NavStyle() ?>">
		<li<?php echo $direcciones_add->MultiPages->TabStyle("1") ?>><a href="#tab_direcciones1" data-toggle="tab"><?php echo $direcciones->PageCaption(1) ?></a></li>
		<li<?php echo $direcciones_add->MultiPages->TabStyle("2") ?>><a href="#tab_direcciones2" data-toggle="tab"><?php echo $direcciones->PageCaption(2) ?></a></li>
		<li<?php echo $direcciones_add->MultiPages->TabStyle("3") ?>><a href="#tab_direcciones3" data-toggle="tab"><?php echo $direcciones->PageCaption(3) ?></a></li>
		<li<?php echo $direcciones_add->MultiPages->TabStyle("4") ?>><a href="#tab_direcciones4" data-toggle="tab"><?php echo $direcciones->PageCaption(4) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $direcciones_add->MultiPages->PageStyle("1") ?>" id="tab_direcciones1"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($direcciones->id_fuente->Visible) { // id_fuente ?>
	<div id="r_id_fuente" class="form-group">
		<label id="elh_direcciones_id_fuente" for="x_id_fuente" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->id_fuente->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->id_fuente->CellAttributes() ?>>
<span id="el_direcciones_id_fuente">
<select data-table="direcciones" data-field="x_id_fuente" data-page="1" data-value-separator="<?php echo $direcciones->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x_id_fuente" name="x_id_fuente"<?php echo $direcciones->id_fuente->EditAttributes() ?>>
<?php echo $direcciones->id_fuente->SelectOptionListHtml("x_id_fuente") ?>
</select>
</span>
<?php echo $direcciones->id_fuente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->id_gestion->Visible) { // id_gestion ?>
	<div id="r_id_gestion" class="form-group">
		<label id="elh_direcciones_id_gestion" for="x_id_gestion" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->id_gestion->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->id_gestion->CellAttributes() ?>>
<span id="el_direcciones_id_gestion">
<select data-table="direcciones" data-field="x_id_gestion" data-page="1" data-value-separator="<?php echo $direcciones->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x_id_gestion" name="x_id_gestion"<?php echo $direcciones->id_gestion->EditAttributes() ?>>
<?php echo $direcciones->id_gestion->SelectOptionListHtml("x_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$direcciones->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $direcciones->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $direcciones->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $direcciones->id_gestion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->id_tipodireccion->Visible) { // id_tipodireccion ?>
	<div id="r_id_tipodireccion" class="form-group">
		<label id="elh_direcciones_id_tipodireccion" for="x_id_tipodireccion" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->id_tipodireccion->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->id_tipodireccion->CellAttributes() ?>>
<span id="el_direcciones_id_tipodireccion">
<select data-table="direcciones" data-field="x_id_tipodireccion" data-page="1" data-value-separator="<?php echo $direcciones->id_tipodireccion->DisplayValueSeparatorAttribute() ?>" id="x_id_tipodireccion" name="x_id_tipodireccion"<?php echo $direcciones->id_tipodireccion->EditAttributes() ?>>
<?php echo $direcciones->id_tipodireccion->SelectOptionListHtml("x_id_tipodireccion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tipo_direccion") && !$direcciones->id_tipodireccion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $direcciones->id_tipodireccion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_tipodireccion',url:'tipo_direccionaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_tipodireccion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $direcciones->id_tipodireccion->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $direcciones->id_tipodireccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $direcciones_add->MultiPages->PageStyle("2") ?>" id="tab_direcciones2"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($direcciones->tipo_documento->Visible) { // tipo_documento ?>
	<div id="r_tipo_documento" class="form-group">
		<label id="elh_direcciones_tipo_documento" for="x_tipo_documento" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->tipo_documento->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->tipo_documento->CellAttributes() ?>>
<span id="el_direcciones_tipo_documento">
<input type="text" data-table="direcciones" data-field="x_tipo_documento" data-page="2" name="x_tipo_documento" id="x_tipo_documento" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($direcciones->tipo_documento->getPlaceHolder()) ?>" value="<?php echo $direcciones->tipo_documento->EditValue ?>"<?php echo $direcciones->tipo_documento->EditAttributes() ?>>
</span>
<?php echo $direcciones->tipo_documento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->no_documento->Visible) { // no_documento ?>
	<div id="r_no_documento" class="form-group">
		<label id="elh_direcciones_no_documento" for="x_no_documento" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->no_documento->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->no_documento->CellAttributes() ?>>
<span id="el_direcciones_no_documento">
<input type="text" data-table="direcciones" data-field="x_no_documento" data-page="2" name="x_no_documento" id="x_no_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->no_documento->getPlaceHolder()) ?>" value="<?php echo $direcciones->no_documento->EditValue ?>"<?php echo $direcciones->no_documento->EditAttributes() ?>>
</span>
<?php echo $direcciones->no_documento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->nombres->Visible) { // nombres ?>
	<div id="r_nombres" class="form-group">
		<label id="elh_direcciones_nombres" for="x_nombres" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->nombres->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->nombres->CellAttributes() ?>>
<span id="el_direcciones_nombres">
<input type="text" data-table="direcciones" data-field="x_nombres" data-page="2" name="x_nombres" id="x_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->nombres->getPlaceHolder()) ?>" value="<?php echo $direcciones->nombres->EditValue ?>"<?php echo $direcciones->nombres->EditAttributes() ?>>
</span>
<?php echo $direcciones->nombres->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->paterno->Visible) { // paterno ?>
	<div id="r_paterno" class="form-group">
		<label id="elh_direcciones_paterno" for="x_paterno" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->paterno->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->paterno->CellAttributes() ?>>
<span id="el_direcciones_paterno">
<input type="text" data-table="direcciones" data-field="x_paterno" data-page="2" name="x_paterno" id="x_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->paterno->getPlaceHolder()) ?>" value="<?php echo $direcciones->paterno->EditValue ?>"<?php echo $direcciones->paterno->EditAttributes() ?>>
</span>
<?php echo $direcciones->paterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->materno->Visible) { // materno ?>
	<div id="r_materno" class="form-group">
		<label id="elh_direcciones_materno" for="x_materno" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->materno->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->materno->CellAttributes() ?>>
<span id="el_direcciones_materno">
<input type="text" data-table="direcciones" data-field="x_materno" data-page="2" name="x_materno" id="x_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->materno->getPlaceHolder()) ?>" value="<?php echo $direcciones->materno->EditValue ?>"<?php echo $direcciones->materno->EditAttributes() ?>>
</span>
<?php echo $direcciones->materno->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $direcciones_add->MultiPages->PageStyle("3") ?>" id="tab_direcciones3"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($direcciones->pais->Visible) { // pais ?>
	<div id="r_pais" class="form-group">
		<label id="elh_direcciones_pais" for="x_pais" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->pais->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->pais->CellAttributes() ?>>
<span id="el_direcciones_pais">
<input type="text" data-table="direcciones" data-field="x_pais" data-page="3" name="x_pais" id="x_pais" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->pais->getPlaceHolder()) ?>" value="<?php echo $direcciones->pais->EditValue ?>"<?php echo $direcciones->pais->EditAttributes() ?>>
</span>
<?php echo $direcciones->pais->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->departamento->Visible) { // departamento ?>
	<div id="r_departamento" class="form-group">
		<label id="elh_direcciones_departamento" for="x_departamento" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->departamento->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->departamento->CellAttributes() ?>>
<span id="el_direcciones_departamento">
<input type="text" data-table="direcciones" data-field="x_departamento" data-page="3" name="x_departamento" id="x_departamento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->departamento->getPlaceHolder()) ?>" value="<?php echo $direcciones->departamento->EditValue ?>"<?php echo $direcciones->departamento->EditAttributes() ?>>
</span>
<?php echo $direcciones->departamento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->provincia->Visible) { // provincia ?>
	<div id="r_provincia" class="form-group">
		<label id="elh_direcciones_provincia" for="x_provincia" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->provincia->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->provincia->CellAttributes() ?>>
<span id="el_direcciones_provincia">
<input type="text" data-table="direcciones" data-field="x_provincia" data-page="3" name="x_provincia" id="x_provincia" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->provincia->getPlaceHolder()) ?>" value="<?php echo $direcciones->provincia->EditValue ?>"<?php echo $direcciones->provincia->EditAttributes() ?>>
</span>
<?php echo $direcciones->provincia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->municipio->Visible) { // municipio ?>
	<div id="r_municipio" class="form-group">
		<label id="elh_direcciones_municipio" for="x_municipio" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->municipio->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->municipio->CellAttributes() ?>>
<span id="el_direcciones_municipio">
<input type="text" data-table="direcciones" data-field="x_municipio" data-page="3" name="x_municipio" id="x_municipio" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->municipio->getPlaceHolder()) ?>" value="<?php echo $direcciones->municipio->EditValue ?>"<?php echo $direcciones->municipio->EditAttributes() ?>>
</span>
<?php echo $direcciones->municipio->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->localidad->Visible) { // localidad ?>
	<div id="r_localidad" class="form-group">
		<label id="elh_direcciones_localidad" for="x_localidad" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->localidad->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->localidad->CellAttributes() ?>>
<span id="el_direcciones_localidad">
<input type="text" data-table="direcciones" data-field="x_localidad" data-page="3" name="x_localidad" id="x_localidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->localidad->getPlaceHolder()) ?>" value="<?php echo $direcciones->localidad->EditValue ?>"<?php echo $direcciones->localidad->EditAttributes() ?>>
</span>
<?php echo $direcciones->localidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->distrito->Visible) { // distrito ?>
	<div id="r_distrito" class="form-group">
		<label id="elh_direcciones_distrito" for="x_distrito" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->distrito->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->distrito->CellAttributes() ?>>
<span id="el_direcciones_distrito">
<input type="text" data-table="direcciones" data-field="x_distrito" data-page="3" name="x_distrito" id="x_distrito" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->distrito->getPlaceHolder()) ?>" value="<?php echo $direcciones->distrito->EditValue ?>"<?php echo $direcciones->distrito->EditAttributes() ?>>
</span>
<?php echo $direcciones->distrito->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->zona->Visible) { // zona ?>
	<div id="r_zona" class="form-group">
		<label id="elh_direcciones_zona" for="x_zona" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->zona->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->zona->CellAttributes() ?>>
<span id="el_direcciones_zona">
<input type="text" data-table="direcciones" data-field="x_zona" data-page="3" name="x_zona" id="x_zona" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->zona->getPlaceHolder()) ?>" value="<?php echo $direcciones->zona->EditValue ?>"<?php echo $direcciones->zona->EditAttributes() ?>>
</span>
<?php echo $direcciones->zona->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->direccion1->Visible) { // direccion1 ?>
	<div id="r_direccion1" class="form-group">
		<label id="elh_direcciones_direccion1" for="x_direccion1" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->direccion1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->direccion1->CellAttributes() ?>>
<span id="el_direcciones_direccion1">
<input type="text" data-table="direcciones" data-field="x_direccion1" data-page="3" name="x_direccion1" id="x_direccion1" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion1->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion1->EditValue ?>"<?php echo $direcciones->direccion1->EditAttributes() ?>>
</span>
<?php echo $direcciones->direccion1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->direccion2->Visible) { // direccion2 ?>
	<div id="r_direccion2" class="form-group">
		<label id="elh_direcciones_direccion2" for="x_direccion2" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->direccion2->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->direccion2->CellAttributes() ?>>
<span id="el_direcciones_direccion2">
<input type="text" data-table="direcciones" data-field="x_direccion2" data-page="3" name="x_direccion2" id="x_direccion2" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion2->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion2->EditValue ?>"<?php echo $direcciones->direccion2->EditAttributes() ?>>
</span>
<?php echo $direcciones->direccion2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->direccion3->Visible) { // direccion3 ?>
	<div id="r_direccion3" class="form-group">
		<label id="elh_direcciones_direccion3" for="x_direccion3" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->direccion3->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->direccion3->CellAttributes() ?>>
<span id="el_direcciones_direccion3">
<input type="text" data-table="direcciones" data-field="x_direccion3" data-page="3" name="x_direccion3" id="x_direccion3" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion3->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion3->EditValue ?>"<?php echo $direcciones->direccion3->EditAttributes() ?>>
</span>
<?php echo $direcciones->direccion3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->direccion4->Visible) { // direccion4 ?>
	<div id="r_direccion4" class="form-group">
		<label id="elh_direcciones_direccion4" for="x_direccion4" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->direccion4->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->direccion4->CellAttributes() ?>>
<span id="el_direcciones_direccion4">
<input type="text" data-table="direcciones" data-field="x_direccion4" data-page="3" name="x_direccion4" id="x_direccion4" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion4->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion4->EditValue ?>"<?php echo $direcciones->direccion4->EditAttributes() ?>>
</span>
<?php echo $direcciones->direccion4->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $direcciones_add->MultiPages->PageStyle("4") ?>" id="tab_direcciones4"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($direcciones->longitud->Visible) { // longitud ?>
	<div id="r_longitud" class="form-group">
		<label id="elh_direcciones_longitud" for="x_longitud" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->longitud->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->longitud->CellAttributes() ?>>
<span id="el_direcciones_longitud">
<input type="text" data-table="direcciones" data-field="x_longitud" data-page="4" name="x_longitud" id="x_longitud" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->longitud->getPlaceHolder()) ?>" value="<?php echo $direcciones->longitud->EditValue ?>"<?php echo $direcciones->longitud->EditAttributes() ?>>
</span>
<?php echo $direcciones->longitud->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->latitud->Visible) { // latitud ?>
	<div id="r_latitud" class="form-group">
		<label id="elh_direcciones_latitud" for="x_latitud" class="<?php echo $direcciones_add->LeftColumnClass ?>"><?php echo $direcciones->latitud->FldCaption() ?></label>
		<div class="<?php echo $direcciones_add->RightColumnClass ?>"><div<?php echo $direcciones->latitud->CellAttributes() ?>>
<span id="el_direcciones_latitud">
<input type="text" data-table="direcciones" data-field="x_latitud" data-page="4" name="x_latitud" id="x_latitud" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->latitud->getPlaceHolder()) ?>" value="<?php echo $direcciones->latitud->EditValue ?>"<?php echo $direcciones->latitud->EditAttributes() ?>>
</span>
<?php echo $direcciones->latitud->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<?php if (!$direcciones_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $direcciones_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $direcciones_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fdireccionesadd.Init();
</script>
<?php
$direcciones_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");
function initMap(x = 0) {
	var uluru = {lat: -16.761532815764443, lng: -64.60053325000001};
	var zoom = 6;
	if(x != 0){	
		if (x.value == 1)
		{
			uluru = {lat: -16.466758409789318, lng: -68.11615825000001};
			zoom = 12;
		} 
		if (x.value == "2")
		{
			uluru = {lat: -17.810526498178312, lng: -63.15033793750001};
			zoom = 12;
		}
		if (x.value == "3")
		{
			uluru = {lat: -17.39164752212273, lng: -66.18256450000001};
			zoom = 12;
		}
		if (x.value == "4")
		{
			uluru = {lat: -17.977804381068, lng: -67.10541606250001};
			zoom = 12;
		}
		if (x.value == "8")
		{
			uluru = {lat: -14.827047472553783, lng: -64.89716410937501};
			zoom = 12;
		}
		if (x.value == "7")
		{
			uluru = {lat:-19.019654029686972, lng: -65.25971293750001};
			zoom = 12;
		}
		if (x.value == "9")
		{
			uluru = {lat: -10.983376644696966, lng: -67.28119731250001};
			zoom = 12;
		}
		if (x.value == "5")
		{
			uluru = {lat: -19.57957355157946, lng: -65.74311137500001};
			zoom = 12;
		}
		if (x.value == "6")
		{
			uluru = {lat: -21.513498358701952, lng: -64.73236918750001};
			zoom = 12;
		}
	}
	var map = new google.maps.Map(document.getElementById('map'), {
		  zoom: zoom,
		  center: uluru
		});
		var marker = new google.maps.Marker({
		  position: uluru,
		  map: map,
		  draggable: true
		});
		google.maps.event.addListener(marker, 'dragend', function(evt){
			document.getElementById('x_latitud').value = evt.latLng.lat();
			document.getElementById('x_longitud').value = evt.latLng.lng();
});
		 }
	  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	initMap();
});
	</script>
<script 
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFibhqbazLZqySy6EuVE_BHRUvkhyIVLg&callback=initMap" async defer>
</script>
</script>
<?php include_once "footer.php" ?>
<?php
$direcciones_add->Page_Terminate();
?>
