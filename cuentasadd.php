<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "cuentasinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "deudasgridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$cuentas_add = NULL; // Initialize page object first

class ccuentas_add extends ccuentas {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'cuentas';

	// Page object name
	var $PageObjName = 'cuentas_add';

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

		// Table object (cuentas)
		if (!isset($GLOBALS["cuentas"]) || get_class($GLOBALS["cuentas"]) == "ccuentas") {
			$GLOBALS["cuentas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cuentas"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cuentas', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cuentaslist.php"));
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
		$this->codigo->SetVisibility();
		$this->denominacion->SetVisibility();
		$this->inicio_contrato->SetVisibility();
		$this->fin_contrato->SetVisibility();
		$this->estado->SetVisibility();

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

			// Process auto fill for detail table 'deudas'
			if (@$_POST["grid"] == "fdeudasgrid") {
				if (!isset($GLOBALS["deudas_grid"])) $GLOBALS["deudas_grid"] = new cdeudas_grid;
				$GLOBALS["deudas_grid"]->Page_Init();
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
		global $EW_EXPORT, $cuentas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cuentas);
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
					if ($pageName == "cuentasview.php")
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

		// Set up detail parameters
		$this->SetupDetailParms();

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
					$this->Page_Terminate("cuentaslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetupDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "cuentaslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "cuentasview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetupDetailParms();
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
		$this->codigo->CurrentValue = NULL;
		$this->codigo->OldValue = $this->codigo->CurrentValue;
		$this->denominacion->CurrentValue = NULL;
		$this->denominacion->OldValue = $this->denominacion->CurrentValue;
		$this->inicio_contrato->CurrentValue = NULL;
		$this->inicio_contrato->OldValue = $this->inicio_contrato->CurrentValue;
		$this->fin_contrato->CurrentValue = NULL;
		$this->fin_contrato->OldValue = $this->fin_contrato->CurrentValue;
		$this->fecha_registro->CurrentValue = NULL;
		$this->fecha_registro->OldValue = $this->fecha_registro->CurrentValue;
		$this->estado->CurrentValue = 1;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->codigo->FldIsDetailKey) {
			$this->codigo->setFormValue($objForm->GetValue("x_codigo"));
		}
		if (!$this->denominacion->FldIsDetailKey) {
			$this->denominacion->setFormValue($objForm->GetValue("x_denominacion"));
		}
		if (!$this->inicio_contrato->FldIsDetailKey) {
			$this->inicio_contrato->setFormValue($objForm->GetValue("x_inicio_contrato"));
			$this->inicio_contrato->CurrentValue = ew_UnFormatDateTime($this->inicio_contrato->CurrentValue, 7);
		}
		if (!$this->fin_contrato->FldIsDetailKey) {
			$this->fin_contrato->setFormValue($objForm->GetValue("x_fin_contrato"));
			$this->fin_contrato->CurrentValue = ew_UnFormatDateTime($this->fin_contrato->CurrentValue, 7);
		}
		if (!$this->estado->FldIsDetailKey) {
			$this->estado->setFormValue($objForm->GetValue("x_estado"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->codigo->CurrentValue = $this->codigo->FormValue;
		$this->denominacion->CurrentValue = $this->denominacion->FormValue;
		$this->inicio_contrato->CurrentValue = $this->inicio_contrato->FormValue;
		$this->inicio_contrato->CurrentValue = ew_UnFormatDateTime($this->inicio_contrato->CurrentValue, 7);
		$this->fin_contrato->CurrentValue = $this->fin_contrato->FormValue;
		$this->fin_contrato->CurrentValue = ew_UnFormatDateTime($this->fin_contrato->CurrentValue, 7);
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
		$this->codigo->setDbValue($row['codigo']);
		$this->denominacion->setDbValue($row['denominacion']);
		$this->inicio_contrato->setDbValue($row['inicio_contrato']);
		$this->fin_contrato->setDbValue($row['fin_contrato']);
		$this->fecha_registro->setDbValue($row['fecha_registro']);
		$this->estado->setDbValue($row['estado']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['Id'] = $this->Id->CurrentValue;
		$row['codigo'] = $this->codigo->CurrentValue;
		$row['denominacion'] = $this->denominacion->CurrentValue;
		$row['inicio_contrato'] = $this->inicio_contrato->CurrentValue;
		$row['fin_contrato'] = $this->fin_contrato->CurrentValue;
		$row['fecha_registro'] = $this->fecha_registro->CurrentValue;
		$row['estado'] = $this->estado->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->codigo->DbValue = $row['codigo'];
		$this->denominacion->DbValue = $row['denominacion'];
		$this->inicio_contrato->DbValue = $row['inicio_contrato'];
		$this->fin_contrato->DbValue = $row['fin_contrato'];
		$this->fecha_registro->DbValue = $row['fecha_registro'];
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
		// codigo
		// denominacion
		// inicio_contrato
		// fin_contrato
		// fecha_registro
		// estado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// codigo
		$this->codigo->ViewValue = $this->codigo->CurrentValue;
		$this->codigo->ViewCustomAttributes = "";

		// denominacion
		$this->denominacion->ViewValue = $this->denominacion->CurrentValue;
		$this->denominacion->ViewCustomAttributes = "";

		// inicio_contrato
		$this->inicio_contrato->ViewValue = $this->inicio_contrato->CurrentValue;
		$this->inicio_contrato->ViewValue = ew_FormatDateTime($this->inicio_contrato->ViewValue, 7);
		$this->inicio_contrato->ViewCustomAttributes = "";

		// fin_contrato
		$this->fin_contrato->ViewValue = $this->fin_contrato->CurrentValue;
		$this->fin_contrato->ViewValue = ew_FormatDateTime($this->fin_contrato->ViewValue, 7);
		$this->fin_contrato->ViewCustomAttributes = "";

		// fecha_registro
		$this->fecha_registro->ViewValue = $this->fecha_registro->CurrentValue;
		$this->fecha_registro->ViewValue = ew_FormatDateTime($this->fecha_registro->ViewValue, 11);
		$this->fecha_registro->ViewCustomAttributes = "";

		// estado
		if (strval($this->estado->CurrentValue) <> "") {
			$this->estado->ViewValue = $this->estado->OptionCaption($this->estado->CurrentValue);
		} else {
			$this->estado->ViewValue = NULL;
		}
		$this->estado->ViewCustomAttributes = "";

			// codigo
			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";
			$this->codigo->TooltipValue = "";

			// denominacion
			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";
			$this->denominacion->TooltipValue = "";

			// inicio_contrato
			$this->inicio_contrato->LinkCustomAttributes = "";
			$this->inicio_contrato->HrefValue = "";
			$this->inicio_contrato->TooltipValue = "";

			// fin_contrato
			$this->fin_contrato->LinkCustomAttributes = "";
			$this->fin_contrato->HrefValue = "";
			$this->fin_contrato->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// codigo
			$this->codigo->EditAttrs["class"] = "form-control";
			$this->codigo->EditCustomAttributes = "";
			$this->codigo->EditValue = ew_HtmlEncode($this->codigo->CurrentValue);
			$this->codigo->PlaceHolder = ew_RemoveHtml($this->codigo->FldCaption());

			// denominacion
			$this->denominacion->EditAttrs["class"] = "form-control";
			$this->denominacion->EditCustomAttributes = "";
			$this->denominacion->EditValue = ew_HtmlEncode($this->denominacion->CurrentValue);
			$this->denominacion->PlaceHolder = ew_RemoveHtml($this->denominacion->FldCaption());

			// inicio_contrato
			$this->inicio_contrato->EditAttrs["class"] = "form-control";
			$this->inicio_contrato->EditCustomAttributes = "";
			$this->inicio_contrato->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->inicio_contrato->CurrentValue, 7));
			$this->inicio_contrato->PlaceHolder = ew_RemoveHtml($this->inicio_contrato->FldCaption());

			// fin_contrato
			$this->fin_contrato->EditAttrs["class"] = "form-control";
			$this->fin_contrato->EditCustomAttributes = "";
			$this->fin_contrato->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fin_contrato->CurrentValue, 7));
			$this->fin_contrato->PlaceHolder = ew_RemoveHtml($this->fin_contrato->FldCaption());

			// estado
			$this->estado->EditCustomAttributes = "";
			$this->estado->EditValue = $this->estado->Options(FALSE);

			// Add refer script
			// codigo

			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";

			// denominacion
			$this->denominacion->LinkCustomAttributes = "";
			$this->denominacion->HrefValue = "";

			// inicio_contrato
			$this->inicio_contrato->LinkCustomAttributes = "";
			$this->inicio_contrato->HrefValue = "";

			// fin_contrato
			$this->fin_contrato->LinkCustomAttributes = "";
			$this->fin_contrato->HrefValue = "";

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
		if (!$this->codigo->FldIsDetailKey && !is_null($this->codigo->FormValue) && $this->codigo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->codigo->FldCaption(), $this->codigo->ReqErrMsg));
		}
		if (!$this->denominacion->FldIsDetailKey && !is_null($this->denominacion->FormValue) && $this->denominacion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->denominacion->FldCaption(), $this->denominacion->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->inicio_contrato->FormValue)) {
			ew_AddMessage($gsFormError, $this->inicio_contrato->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fin_contrato->FormValue)) {
			ew_AddMessage($gsFormError, $this->fin_contrato->FldErrMsg());
		}
		if ($this->estado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->estado->FldCaption(), $this->estado->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("deudas", $DetailTblVar) && $GLOBALS["deudas"]->DetailAdd) {
			if (!isset($GLOBALS["deudas_grid"])) $GLOBALS["deudas_grid"] = new cdeudas_grid(); // get detail page object
			$GLOBALS["deudas_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// codigo
		$this->codigo->SetDbValueDef($rsnew, $this->codigo->CurrentValue, "", FALSE);

		// denominacion
		$this->denominacion->SetDbValueDef($rsnew, $this->denominacion->CurrentValue, "", FALSE);

		// inicio_contrato
		$this->inicio_contrato->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->inicio_contrato->CurrentValue, 7), NULL, FALSE);

		// fin_contrato
		$this->fin_contrato->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fin_contrato->CurrentValue, 7), NULL, FALSE);

		// estado
		$this->estado->SetDbValueDef($rsnew, $this->estado->CurrentValue, 0, strval($this->estado->CurrentValue) == "");

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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("deudas", $DetailTblVar) && $GLOBALS["deudas"]->DetailAdd) {
				$GLOBALS["deudas"]->id_cliente->setSessionValue($this->Id->CurrentValue); // Set master key
				if (!isset($GLOBALS["deudas_grid"])) $GLOBALS["deudas_grid"] = new cdeudas_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "deudas"); // Load user level of detail table
				$AddRow = $GLOBALS["deudas_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["deudas"]->id_cliente->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
			if (in_array("deudas", $DetailTblVar)) {
				if (!isset($GLOBALS["deudas_grid"]))
					$GLOBALS["deudas_grid"] = new cdeudas_grid;
				if ($GLOBALS["deudas_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["deudas_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["deudas_grid"]->CurrentMode = "add";
					$GLOBALS["deudas_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["deudas_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["deudas_grid"]->setStartRecordNumber(1);
					$GLOBALS["deudas_grid"]->id_cliente->FldIsDetailKey = TRUE;
					$GLOBALS["deudas_grid"]->id_cliente->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["deudas_grid"]->id_cliente->setSessionValue($GLOBALS["deudas_grid"]->id_cliente->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cuentaslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($cuentas_add)) $cuentas_add = new ccuentas_add();

// Page init
$cuentas_add->Page_Init();

// Page main
$cuentas_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cuentas_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fcuentasadd = new ew_Form("fcuentasadd", "add");

// Validate form
fcuentasadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_codigo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cuentas->codigo->FldCaption(), $cuentas->codigo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_denominacion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cuentas->denominacion->FldCaption(), $cuentas->denominacion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_inicio_contrato");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cuentas->inicio_contrato->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fin_contrato");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($cuentas->fin_contrato->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $cuentas->estado->FldCaption(), $cuentas->estado->ReqErrMsg)) ?>");

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
fcuentasadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fcuentasadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fcuentasadd.Lists["x_estado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcuentasadd.Lists["x_estado"].Options = <?php echo json_encode($cuentas_add->estado->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $cuentas_add->ShowPageHeader(); ?>
<?php
$cuentas_add->ShowMessage();
?>
<form name="fcuentasadd" id="fcuentasadd" class="<?php echo $cuentas_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cuentas_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cuentas_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cuentas">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($cuentas_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($cuentas->codigo->Visible) { // codigo ?>
	<div id="r_codigo" class="form-group">
		<label id="elh_cuentas_codigo" for="x_codigo" class="<?php echo $cuentas_add->LeftColumnClass ?>"><?php echo $cuentas->codigo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $cuentas_add->RightColumnClass ?>"><div<?php echo $cuentas->codigo->CellAttributes() ?>>
<span id="el_cuentas_codigo">
<input type="text" data-table="cuentas" data-field="x_codigo" name="x_codigo" id="x_codigo" size="15" maxlength="100" placeholder="<?php echo ew_HtmlEncode($cuentas->codigo->getPlaceHolder()) ?>" value="<?php echo $cuentas->codigo->EditValue ?>"<?php echo $cuentas->codigo->EditAttributes() ?>>
</span>
<?php echo $cuentas->codigo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cuentas->denominacion->Visible) { // denominacion ?>
	<div id="r_denominacion" class="form-group">
		<label id="elh_cuentas_denominacion" for="x_denominacion" class="<?php echo $cuentas_add->LeftColumnClass ?>"><?php echo $cuentas->denominacion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $cuentas_add->RightColumnClass ?>"><div<?php echo $cuentas->denominacion->CellAttributes() ?>>
<span id="el_cuentas_denominacion">
<input type="text" data-table="cuentas" data-field="x_denominacion" name="x_denominacion" id="x_denominacion" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($cuentas->denominacion->getPlaceHolder()) ?>" value="<?php echo $cuentas->denominacion->EditValue ?>"<?php echo $cuentas->denominacion->EditAttributes() ?>>
</span>
<?php echo $cuentas->denominacion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cuentas->inicio_contrato->Visible) { // inicio_contrato ?>
	<div id="r_inicio_contrato" class="form-group">
		<label id="elh_cuentas_inicio_contrato" for="x_inicio_contrato" class="<?php echo $cuentas_add->LeftColumnClass ?>"><?php echo $cuentas->inicio_contrato->FldCaption() ?></label>
		<div class="<?php echo $cuentas_add->RightColumnClass ?>"><div<?php echo $cuentas->inicio_contrato->CellAttributes() ?>>
<span id="el_cuentas_inicio_contrato">
<input type="text" data-table="cuentas" data-field="x_inicio_contrato" data-format="7" name="x_inicio_contrato" id="x_inicio_contrato" size="20" maxlength="10" placeholder="<?php echo ew_HtmlEncode($cuentas->inicio_contrato->getPlaceHolder()) ?>" value="<?php echo $cuentas->inicio_contrato->EditValue ?>"<?php echo $cuentas->inicio_contrato->EditAttributes() ?>>
<?php if (!$cuentas->inicio_contrato->ReadOnly && !$cuentas->inicio_contrato->Disabled && !isset($cuentas->inicio_contrato->EditAttrs["readonly"]) && !isset($cuentas->inicio_contrato->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fcuentasadd", "x_inicio_contrato", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php echo $cuentas->inicio_contrato->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cuentas->fin_contrato->Visible) { // fin_contrato ?>
	<div id="r_fin_contrato" class="form-group">
		<label id="elh_cuentas_fin_contrato" for="x_fin_contrato" class="<?php echo $cuentas_add->LeftColumnClass ?>"><?php echo $cuentas->fin_contrato->FldCaption() ?></label>
		<div class="<?php echo $cuentas_add->RightColumnClass ?>"><div<?php echo $cuentas->fin_contrato->CellAttributes() ?>>
<span id="el_cuentas_fin_contrato">
<input type="text" data-table="cuentas" data-field="x_fin_contrato" data-format="7" name="x_fin_contrato" id="x_fin_contrato" size="20" maxlength="10" placeholder="<?php echo ew_HtmlEncode($cuentas->fin_contrato->getPlaceHolder()) ?>" value="<?php echo $cuentas->fin_contrato->EditValue ?>"<?php echo $cuentas->fin_contrato->EditAttributes() ?>>
<?php if (!$cuentas->fin_contrato->ReadOnly && !$cuentas->fin_contrato->Disabled && !isset($cuentas->fin_contrato->EditAttrs["readonly"]) && !isset($cuentas->fin_contrato->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fcuentasadd", "x_fin_contrato", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php echo $cuentas->fin_contrato->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($cuentas->estado->Visible) { // estado ?>
	<div id="r_estado" class="form-group">
		<label id="elh_cuentas_estado" class="<?php echo $cuentas_add->LeftColumnClass ?>"><?php echo $cuentas->estado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $cuentas_add->RightColumnClass ?>"><div<?php echo $cuentas->estado->CellAttributes() ?>>
<span id="el_cuentas_estado">
<div id="tp_x_estado" class="ewTemplate"><input type="radio" data-table="cuentas" data-field="x_estado" data-value-separator="<?php echo $cuentas->estado->DisplayValueSeparatorAttribute() ?>" name="x_estado" id="x_estado" value="{value}"<?php echo $cuentas->estado->EditAttributes() ?>></div>
<div id="dsl_x_estado" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $cuentas->estado->RadioButtonListHtml(FALSE, "x_estado") ?>
</div></div>
</span>
<?php echo $cuentas->estado->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php
	if (in_array("deudas", explode(",", $cuentas->getCurrentDetailTable())) && $deudas->DetailAdd) {
?>
<?php if ($cuentas->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("deudas", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "deudasgrid.php" ?>
<?php } ?>
<?php if (!$cuentas_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $cuentas_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $cuentas_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fcuentasadd.Init();
</script>
<?php
$cuentas_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cuentas_add->Page_Terminate();
?>
