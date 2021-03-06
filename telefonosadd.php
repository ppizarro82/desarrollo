<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "telefonosinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$telefonos_add = NULL; // Initialize page object first

class ctelefonos_add extends ctelefonos {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'telefonos';

	// Page object name
	var $PageObjName = 'telefonos_add';

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

		// Table object (telefonos)
		if (!isset($GLOBALS["telefonos"]) || get_class($GLOBALS["telefonos"]) == "ctelefonos") {
			$GLOBALS["telefonos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["telefonos"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'telefonos', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("telefonoslist.php"));
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
		$this->tipo_documento->SetVisibility();
		$this->no_documento->SetVisibility();
		$this->nombres->SetVisibility();
		$this->paterno->SetVisibility();
		$this->materno->SetVisibility();
		$this->telefono1->SetVisibility();
		$this->telefono2->SetVisibility();
		$this->telefono3->SetVisibility();
		$this->telefono4->SetVisibility();

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
		global $EW_EXPORT, $telefonos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($telefonos);
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
					if ($pageName == "telefonosview.php")
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
					$this->Page_Terminate("telefonoslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "telefonoslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "telefonosview.php")
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
		$this->telefono1->CurrentValue = NULL;
		$this->telefono1->OldValue = $this->telefono1->CurrentValue;
		$this->telefono2->CurrentValue = NULL;
		$this->telefono2->OldValue = $this->telefono2->CurrentValue;
		$this->telefono3->CurrentValue = NULL;
		$this->telefono3->OldValue = $this->telefono3->CurrentValue;
		$this->telefono4->CurrentValue = NULL;
		$this->telefono4->OldValue = $this->telefono4->CurrentValue;
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
		if (!$this->telefono1->FldIsDetailKey) {
			$this->telefono1->setFormValue($objForm->GetValue("x_telefono1"));
		}
		if (!$this->telefono2->FldIsDetailKey) {
			$this->telefono2->setFormValue($objForm->GetValue("x_telefono2"));
		}
		if (!$this->telefono3->FldIsDetailKey) {
			$this->telefono3->setFormValue($objForm->GetValue("x_telefono3"));
		}
		if (!$this->telefono4->FldIsDetailKey) {
			$this->telefono4->setFormValue($objForm->GetValue("x_telefono4"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_fuente->CurrentValue = $this->id_fuente->FormValue;
		$this->id_gestion->CurrentValue = $this->id_gestion->FormValue;
		$this->tipo_documento->CurrentValue = $this->tipo_documento->FormValue;
		$this->no_documento->CurrentValue = $this->no_documento->FormValue;
		$this->nombres->CurrentValue = $this->nombres->FormValue;
		$this->paterno->CurrentValue = $this->paterno->FormValue;
		$this->materno->CurrentValue = $this->materno->FormValue;
		$this->telefono1->CurrentValue = $this->telefono1->FormValue;
		$this->telefono2->CurrentValue = $this->telefono2->FormValue;
		$this->telefono3->CurrentValue = $this->telefono3->FormValue;
		$this->telefono4->CurrentValue = $this->telefono4->FormValue;
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
		$this->tipo_documento->setDbValue($row['tipo_documento']);
		$this->no_documento->setDbValue($row['no_documento']);
		$this->nombres->setDbValue($row['nombres']);
		$this->paterno->setDbValue($row['paterno']);
		$this->materno->setDbValue($row['materno']);
		$this->telefono1->setDbValue($row['telefono1']);
		$this->telefono2->setDbValue($row['telefono2']);
		$this->telefono3->setDbValue($row['telefono3']);
		$this->telefono4->setDbValue($row['telefono4']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['Id'] = $this->Id->CurrentValue;
		$row['id_fuente'] = $this->id_fuente->CurrentValue;
		$row['id_gestion'] = $this->id_gestion->CurrentValue;
		$row['tipo_documento'] = $this->tipo_documento->CurrentValue;
		$row['no_documento'] = $this->no_documento->CurrentValue;
		$row['nombres'] = $this->nombres->CurrentValue;
		$row['paterno'] = $this->paterno->CurrentValue;
		$row['materno'] = $this->materno->CurrentValue;
		$row['telefono1'] = $this->telefono1->CurrentValue;
		$row['telefono2'] = $this->telefono2->CurrentValue;
		$row['telefono3'] = $this->telefono3->CurrentValue;
		$row['telefono4'] = $this->telefono4->CurrentValue;
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
		$this->tipo_documento->DbValue = $row['tipo_documento'];
		$this->no_documento->DbValue = $row['no_documento'];
		$this->nombres->DbValue = $row['nombres'];
		$this->paterno->DbValue = $row['paterno'];
		$this->materno->DbValue = $row['materno'];
		$this->telefono1->DbValue = $row['telefono1'];
		$this->telefono2->DbValue = $row['telefono2'];
		$this->telefono3->DbValue = $row['telefono3'];
		$this->telefono4->DbValue = $row['telefono4'];
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
		// tipo_documento
		// no_documento
		// nombres
		// paterno
		// materno
		// telefono1
		// telefono2
		// telefono3
		// telefono4

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

		// telefono1
		$this->telefono1->ViewValue = $this->telefono1->CurrentValue;
		$this->telefono1->ViewCustomAttributes = "";

		// telefono2
		$this->telefono2->ViewValue = $this->telefono2->CurrentValue;
		$this->telefono2->ViewCustomAttributes = "";

		// telefono3
		$this->telefono3->ViewValue = $this->telefono3->CurrentValue;
		$this->telefono3->ViewCustomAttributes = "";

		// telefono4
		$this->telefono4->ViewValue = $this->telefono4->CurrentValue;
		$this->telefono4->ViewCustomAttributes = "";

			// id_fuente
			$this->id_fuente->LinkCustomAttributes = "";
			$this->id_fuente->HrefValue = "";
			$this->id_fuente->TooltipValue = "";

			// id_gestion
			$this->id_gestion->LinkCustomAttributes = "";
			$this->id_gestion->HrefValue = "";
			$this->id_gestion->TooltipValue = "";

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

			// telefono1
			$this->telefono1->LinkCustomAttributes = "";
			$this->telefono1->HrefValue = "";
			$this->telefono1->TooltipValue = "";

			// telefono2
			$this->telefono2->LinkCustomAttributes = "";
			$this->telefono2->HrefValue = "";
			$this->telefono2->TooltipValue = "";

			// telefono3
			$this->telefono3->LinkCustomAttributes = "";
			$this->telefono3->HrefValue = "";
			$this->telefono3->TooltipValue = "";

			// telefono4
			$this->telefono4->LinkCustomAttributes = "";
			$this->telefono4->HrefValue = "";
			$this->telefono4->TooltipValue = "";
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

			// telefono1
			$this->telefono1->EditAttrs["class"] = "form-control";
			$this->telefono1->EditCustomAttributes = "";
			$this->telefono1->EditValue = ew_HtmlEncode($this->telefono1->CurrentValue);
			$this->telefono1->PlaceHolder = ew_RemoveHtml($this->telefono1->FldCaption());

			// telefono2
			$this->telefono2->EditAttrs["class"] = "form-control";
			$this->telefono2->EditCustomAttributes = "";
			$this->telefono2->EditValue = ew_HtmlEncode($this->telefono2->CurrentValue);
			$this->telefono2->PlaceHolder = ew_RemoveHtml($this->telefono2->FldCaption());

			// telefono3
			$this->telefono3->EditAttrs["class"] = "form-control";
			$this->telefono3->EditCustomAttributes = "";
			$this->telefono3->EditValue = ew_HtmlEncode($this->telefono3->CurrentValue);
			$this->telefono3->PlaceHolder = ew_RemoveHtml($this->telefono3->FldCaption());

			// telefono4
			$this->telefono4->EditAttrs["class"] = "form-control";
			$this->telefono4->EditCustomAttributes = "";
			$this->telefono4->EditValue = ew_HtmlEncode($this->telefono4->CurrentValue);
			$this->telefono4->PlaceHolder = ew_RemoveHtml($this->telefono4->FldCaption());

			// Add refer script
			// id_fuente

			$this->id_fuente->LinkCustomAttributes = "";
			$this->id_fuente->HrefValue = "";

			// id_gestion
			$this->id_gestion->LinkCustomAttributes = "";
			$this->id_gestion->HrefValue = "";

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

			// telefono1
			$this->telefono1->LinkCustomAttributes = "";
			$this->telefono1->HrefValue = "";

			// telefono2
			$this->telefono2->LinkCustomAttributes = "";
			$this->telefono2->HrefValue = "";

			// telefono3
			$this->telefono3->LinkCustomAttributes = "";
			$this->telefono3->HrefValue = "";

			// telefono4
			$this->telefono4->LinkCustomAttributes = "";
			$this->telefono4->HrefValue = "";
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
		if (!$this->telefono1->FldIsDetailKey && !is_null($this->telefono1->FormValue) && $this->telefono1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->telefono1->FldCaption(), $this->telefono1->ReqErrMsg));
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

		// tipo_documento
		$this->tipo_documento->SetDbValueDef($rsnew, $this->tipo_documento->CurrentValue, NULL, FALSE);

		// no_documento
		$this->no_documento->SetDbValueDef($rsnew, $this->no_documento->CurrentValue, NULL, FALSE);

		// nombres
		$this->nombres->SetDbValueDef($rsnew, $this->nombres->CurrentValue, NULL, FALSE);

		// paterno
		$this->paterno->SetDbValueDef($rsnew, $this->paterno->CurrentValue, NULL, FALSE);

		// materno
		$this->materno->SetDbValueDef($rsnew, $this->materno->CurrentValue, NULL, FALSE);

		// telefono1
		$this->telefono1->SetDbValueDef($rsnew, $this->telefono1->CurrentValue, NULL, FALSE);

		// telefono2
		$this->telefono2->SetDbValueDef($rsnew, $this->telefono2->CurrentValue, NULL, FALSE);

		// telefono3
		$this->telefono3->SetDbValueDef($rsnew, $this->telefono3->CurrentValue, NULL, FALSE);

		// telefono4
		$this->telefono4->SetDbValueDef($rsnew, $this->telefono4->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("telefonoslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($telefonos_add)) $telefonos_add = new ctelefonos_add();

// Page init
$telefonos_add->Page_Init();

// Page main
$telefonos_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$telefonos_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ftelefonosadd = new ew_Form("ftelefonosadd", "add");

// Validate form
ftelefonosadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_telefono1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $telefonos->telefono1->FldCaption(), $telefonos->telefono1->ReqErrMsg)) ?>");

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
ftelefonosadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
ftelefonosadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
ftelefonosadd.MultiPage = new ew_MultiPage("ftelefonosadd");

// Dynamic selection lists
ftelefonosadd.Lists["x_id_fuente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"fuentes"};
ftelefonosadd.Lists["x_id_fuente"].Data = "<?php echo $telefonos_add->id_fuente->LookupFilterQuery(FALSE, "add") ?>";
ftelefonosadd.Lists["x_id_gestion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gestiones"};
ftelefonosadd.Lists["x_id_gestion"].Data = "<?php echo $telefonos_add->id_gestion->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $telefonos_add->ShowPageHeader(); ?>
<?php
$telefonos_add->ShowMessage();
?>
<form name="ftelefonosadd" id="ftelefonosadd" class="<?php echo $telefonos_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($telefonos_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $telefonos_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="telefonos">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($telefonos_add->IsModal) ?>">
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="telefonos_add"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $telefonos_add->MultiPages->NavStyle() ?>">
		<li<?php echo $telefonos_add->MultiPages->TabStyle("1") ?>><a href="#tab_telefonos1" data-toggle="tab"><?php echo $telefonos->PageCaption(1) ?></a></li>
		<li<?php echo $telefonos_add->MultiPages->TabStyle("2") ?>><a href="#tab_telefonos2" data-toggle="tab"><?php echo $telefonos->PageCaption(2) ?></a></li>
		<li<?php echo $telefonos_add->MultiPages->TabStyle("3") ?>><a href="#tab_telefonos3" data-toggle="tab"><?php echo $telefonos->PageCaption(3) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $telefonos_add->MultiPages->PageStyle("1") ?>" id="tab_telefonos1"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($telefonos->id_fuente->Visible) { // id_fuente ?>
	<div id="r_id_fuente" class="form-group">
		<label id="elh_telefonos_id_fuente" for="x_id_fuente" class="<?php echo $telefonos_add->LeftColumnClass ?>"><?php echo $telefonos->id_fuente->FldCaption() ?></label>
		<div class="<?php echo $telefonos_add->RightColumnClass ?>"><div<?php echo $telefonos->id_fuente->CellAttributes() ?>>
<span id="el_telefonos_id_fuente">
<select data-table="telefonos" data-field="x_id_fuente" data-page="1" data-value-separator="<?php echo $telefonos->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x_id_fuente" name="x_id_fuente"<?php echo $telefonos->id_fuente->EditAttributes() ?>>
<?php echo $telefonos->id_fuente->SelectOptionListHtml("x_id_fuente") ?>
</select>
</span>
<?php echo $telefonos->id_fuente->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($telefonos->id_gestion->Visible) { // id_gestion ?>
	<div id="r_id_gestion" class="form-group">
		<label id="elh_telefonos_id_gestion" for="x_id_gestion" class="<?php echo $telefonos_add->LeftColumnClass ?>"><?php echo $telefonos->id_gestion->FldCaption() ?></label>
		<div class="<?php echo $telefonos_add->RightColumnClass ?>"><div<?php echo $telefonos->id_gestion->CellAttributes() ?>>
<span id="el_telefonos_id_gestion">
<select data-table="telefonos" data-field="x_id_gestion" data-page="1" data-value-separator="<?php echo $telefonos->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x_id_gestion" name="x_id_gestion"<?php echo $telefonos->id_gestion->EditAttributes() ?>>
<?php echo $telefonos->id_gestion->SelectOptionListHtml("x_id_gestion") ?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "gestiones") && !$telefonos->id_gestion->ReadOnly) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $telefonos->id_gestion->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_id_gestion',url:'gestionesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_id_gestion"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $telefonos->id_gestion->FldCaption() ?></span></button>
<?php } ?>
</span>
<?php echo $telefonos->id_gestion->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $telefonos_add->MultiPages->PageStyle("2") ?>" id="tab_telefonos2"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($telefonos->tipo_documento->Visible) { // tipo_documento ?>
	<div id="r_tipo_documento" class="form-group">
		<label id="elh_telefonos_tipo_documento" for="x_tipo_documento" class="<?php echo $telefonos_add->LeftColumnClass ?>"><?php echo $telefonos->tipo_documento->FldCaption() ?></label>
		<div class="<?php echo $telefonos_add->RightColumnClass ?>"><div<?php echo $telefonos->tipo_documento->CellAttributes() ?>>
<span id="el_telefonos_tipo_documento">
<input type="text" data-table="telefonos" data-field="x_tipo_documento" data-page="2" name="x_tipo_documento" id="x_tipo_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->tipo_documento->getPlaceHolder()) ?>" value="<?php echo $telefonos->tipo_documento->EditValue ?>"<?php echo $telefonos->tipo_documento->EditAttributes() ?>>
</span>
<?php echo $telefonos->tipo_documento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($telefonos->no_documento->Visible) { // no_documento ?>
	<div id="r_no_documento" class="form-group">
		<label id="elh_telefonos_no_documento" for="x_no_documento" class="<?php echo $telefonos_add->LeftColumnClass ?>"><?php echo $telefonos->no_documento->FldCaption() ?></label>
		<div class="<?php echo $telefonos_add->RightColumnClass ?>"><div<?php echo $telefonos->no_documento->CellAttributes() ?>>
<span id="el_telefonos_no_documento">
<input type="text" data-table="telefonos" data-field="x_no_documento" data-page="2" name="x_no_documento" id="x_no_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->no_documento->getPlaceHolder()) ?>" value="<?php echo $telefonos->no_documento->EditValue ?>"<?php echo $telefonos->no_documento->EditAttributes() ?>>
</span>
<?php echo $telefonos->no_documento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($telefonos->nombres->Visible) { // nombres ?>
	<div id="r_nombres" class="form-group">
		<label id="elh_telefonos_nombres" for="x_nombres" class="<?php echo $telefonos_add->LeftColumnClass ?>"><?php echo $telefonos->nombres->FldCaption() ?></label>
		<div class="<?php echo $telefonos_add->RightColumnClass ?>"><div<?php echo $telefonos->nombres->CellAttributes() ?>>
<span id="el_telefonos_nombres">
<input type="text" data-table="telefonos" data-field="x_nombres" data-page="2" name="x_nombres" id="x_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->nombres->getPlaceHolder()) ?>" value="<?php echo $telefonos->nombres->EditValue ?>"<?php echo $telefonos->nombres->EditAttributes() ?>>
</span>
<?php echo $telefonos->nombres->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($telefonos->paterno->Visible) { // paterno ?>
	<div id="r_paterno" class="form-group">
		<label id="elh_telefonos_paterno" for="x_paterno" class="<?php echo $telefonos_add->LeftColumnClass ?>"><?php echo $telefonos->paterno->FldCaption() ?></label>
		<div class="<?php echo $telefonos_add->RightColumnClass ?>"><div<?php echo $telefonos->paterno->CellAttributes() ?>>
<span id="el_telefonos_paterno">
<input type="text" data-table="telefonos" data-field="x_paterno" data-page="2" name="x_paterno" id="x_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->paterno->getPlaceHolder()) ?>" value="<?php echo $telefonos->paterno->EditValue ?>"<?php echo $telefonos->paterno->EditAttributes() ?>>
</span>
<?php echo $telefonos->paterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($telefonos->materno->Visible) { // materno ?>
	<div id="r_materno" class="form-group">
		<label id="elh_telefonos_materno" for="x_materno" class="<?php echo $telefonos_add->LeftColumnClass ?>"><?php echo $telefonos->materno->FldCaption() ?></label>
		<div class="<?php echo $telefonos_add->RightColumnClass ?>"><div<?php echo $telefonos->materno->CellAttributes() ?>>
<span id="el_telefonos_materno">
<input type="text" data-table="telefonos" data-field="x_materno" data-page="2" name="x_materno" id="x_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->materno->getPlaceHolder()) ?>" value="<?php echo $telefonos->materno->EditValue ?>"<?php echo $telefonos->materno->EditAttributes() ?>>
</span>
<?php echo $telefonos->materno->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $telefonos_add->MultiPages->PageStyle("3") ?>" id="tab_telefonos3"><!-- multi-page .tab-pane -->
<div class="ewAddDiv"><!-- page* -->
<?php if ($telefonos->telefono1->Visible) { // telefono1 ?>
	<div id="r_telefono1" class="form-group">
		<label id="elh_telefonos_telefono1" for="x_telefono1" class="<?php echo $telefonos_add->LeftColumnClass ?>"><?php echo $telefonos->telefono1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $telefonos_add->RightColumnClass ?>"><div<?php echo $telefonos->telefono1->CellAttributes() ?>>
<span id="el_telefonos_telefono1">
<input type="text" data-table="telefonos" data-field="x_telefono1" data-page="3" name="x_telefono1" id="x_telefono1" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono1->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono1->EditValue ?>"<?php echo $telefonos->telefono1->EditAttributes() ?>>
</span>
<?php echo $telefonos->telefono1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($telefonos->telefono2->Visible) { // telefono2 ?>
	<div id="r_telefono2" class="form-group">
		<label id="elh_telefonos_telefono2" for="x_telefono2" class="<?php echo $telefonos_add->LeftColumnClass ?>"><?php echo $telefonos->telefono2->FldCaption() ?></label>
		<div class="<?php echo $telefonos_add->RightColumnClass ?>"><div<?php echo $telefonos->telefono2->CellAttributes() ?>>
<span id="el_telefonos_telefono2">
<input type="text" data-table="telefonos" data-field="x_telefono2" data-page="3" name="x_telefono2" id="x_telefono2" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono2->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono2->EditValue ?>"<?php echo $telefonos->telefono2->EditAttributes() ?>>
</span>
<?php echo $telefonos->telefono2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($telefonos->telefono3->Visible) { // telefono3 ?>
	<div id="r_telefono3" class="form-group">
		<label id="elh_telefonos_telefono3" for="x_telefono3" class="<?php echo $telefonos_add->LeftColumnClass ?>"><?php echo $telefonos->telefono3->FldCaption() ?></label>
		<div class="<?php echo $telefonos_add->RightColumnClass ?>"><div<?php echo $telefonos->telefono3->CellAttributes() ?>>
<span id="el_telefonos_telefono3">
<input type="text" data-table="telefonos" data-field="x_telefono3" data-page="3" name="x_telefono3" id="x_telefono3" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono3->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono3->EditValue ?>"<?php echo $telefonos->telefono3->EditAttributes() ?>>
</span>
<?php echo $telefonos->telefono3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($telefonos->telefono4->Visible) { // telefono4 ?>
	<div id="r_telefono4" class="form-group">
		<label id="elh_telefonos_telefono4" for="x_telefono4" class="<?php echo $telefonos_add->LeftColumnClass ?>"><?php echo $telefonos->telefono4->FldCaption() ?></label>
		<div class="<?php echo $telefonos_add->RightColumnClass ?>"><div<?php echo $telefonos->telefono4->CellAttributes() ?>>
<span id="el_telefonos_telefono4">
<input type="text" data-table="telefonos" data-field="x_telefono4" data-page="3" name="x_telefono4" id="x_telefono4" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($telefonos->telefono4->getPlaceHolder()) ?>" value="<?php echo $telefonos->telefono4->EditValue ?>"<?php echo $telefonos->telefono4->EditAttributes() ?>>
</span>
<?php echo $telefonos->telefono4->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<?php if (!$telefonos_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $telefonos_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $telefonos_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
ftelefonosadd.Init();
</script>
<?php
$telefonos_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$telefonos_add->Page_Terminate();
?>
