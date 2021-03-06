<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "deuda_personainfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "personasinfo.php" ?>
<?php include_once "deudasinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$deuda_persona_add = NULL; // Initialize page object first

class cdeuda_persona_add extends cdeuda_persona {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'deuda_persona';

	// Page object name
	var $PageObjName = 'deuda_persona_add';

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

		// Table object (deuda_persona)
		if (!isset($GLOBALS["deuda_persona"]) || get_class($GLOBALS["deuda_persona"]) == "cdeuda_persona") {
			$GLOBALS["deuda_persona"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["deuda_persona"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Table object (personas)
		if (!isset($GLOBALS['personas'])) $GLOBALS['personas'] = new cpersonas();

		// Table object (deudas)
		if (!isset($GLOBALS['deudas'])) $GLOBALS['deudas'] = new cdeudas();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'deuda_persona', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("deuda_personalist.php"));
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
		$this->id_persona->SetVisibility();
		$this->id_deuda->SetVisibility();
		$this->id_tipopersona->SetVisibility();
		$this->mig_codigo_cliente->SetVisibility();

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
		global $EW_EXPORT, $deuda_persona;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($deuda_persona);
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
					if ($pageName == "deuda_personaview.php")
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

		// Set up master/detail parameters
		$this->SetupMasterParms();

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id_persona"] != "") {
				$this->id_persona->setQueryStringValue($_GET["id_persona"]);
				$this->setKey("id_persona", $this->id_persona->CurrentValue); // Set up key
			} else {
				$this->setKey("id_persona", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["id_deuda"] != "") {
				$this->id_deuda->setQueryStringValue($_GET["id_deuda"]);
				$this->setKey("id_deuda", $this->id_deuda->CurrentValue); // Set up key
			} else {
				$this->setKey("id_deuda", ""); // Clear key
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
					$this->Page_Terminate("deuda_personalist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "deuda_personalist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "deuda_personaview.php")
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
		$this->id_persona->CurrentValue = 0;
		$this->id_deuda->CurrentValue = 0;
		$this->id_tipopersona->CurrentValue = NULL;
		$this->id_tipopersona->OldValue = $this->id_tipopersona->CurrentValue;
		$this->mig_codigo_cliente->CurrentValue = NULL;
		$this->mig_codigo_cliente->OldValue = $this->mig_codigo_cliente->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_persona->FldIsDetailKey) {
			$this->id_persona->setFormValue($objForm->GetValue("x_id_persona"));
		}
		if (!$this->id_deuda->FldIsDetailKey) {
			$this->id_deuda->setFormValue($objForm->GetValue("x_id_deuda"));
		}
		if (!$this->id_tipopersona->FldIsDetailKey) {
			$this->id_tipopersona->setFormValue($objForm->GetValue("x_id_tipopersona"));
		}
		if (!$this->mig_codigo_cliente->FldIsDetailKey) {
			$this->mig_codigo_cliente->setFormValue($objForm->GetValue("x_mig_codigo_cliente"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_persona->CurrentValue = $this->id_persona->FormValue;
		$this->id_deuda->CurrentValue = $this->id_deuda->FormValue;
		$this->id_tipopersona->CurrentValue = $this->id_tipopersona->FormValue;
		$this->mig_codigo_cliente->CurrentValue = $this->mig_codigo_cliente->FormValue;
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
		$this->id_persona->setDbValue($row['id_persona']);
		if (array_key_exists('EV__id_persona', $rs->fields)) {
			$this->id_persona->VirtualValue = $rs->fields('EV__id_persona'); // Set up virtual field value
		} else {
			$this->id_persona->VirtualValue = ""; // Clear value
		}
		$this->id_deuda->setDbValue($row['id_deuda']);
		if (array_key_exists('EV__id_deuda', $rs->fields)) {
			$this->id_deuda->VirtualValue = $rs->fields('EV__id_deuda'); // Set up virtual field value
		} else {
			$this->id_deuda->VirtualValue = ""; // Clear value
		}
		$this->id_tipopersona->setDbValue($row['id_tipopersona']);
		$this->mig_codigo_cliente->setDbValue($row['mig_codigo_cliente']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id_persona'] = $this->id_persona->CurrentValue;
		$row['id_deuda'] = $this->id_deuda->CurrentValue;
		$row['id_tipopersona'] = $this->id_tipopersona->CurrentValue;
		$row['mig_codigo_cliente'] = $this->mig_codigo_cliente->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_persona->DbValue = $row['id_persona'];
		$this->id_deuda->DbValue = $row['id_deuda'];
		$this->id_tipopersona->DbValue = $row['id_tipopersona'];
		$this->mig_codigo_cliente->DbValue = $row['mig_codigo_cliente'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_persona")) <> "")
			$this->id_persona->CurrentValue = $this->getKey("id_persona"); // id_persona
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("id_deuda")) <> "")
			$this->id_deuda->CurrentValue = $this->getKey("id_deuda"); // id_deuda
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
		// id_persona
		// id_deuda
		// id_tipopersona
		// mig_codigo_cliente

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_persona
			$this->id_persona->EditAttrs["class"] = "form-control";
			$this->id_persona->EditCustomAttributes = "";
			if ($this->id_persona->getSessionValue() <> "") {
				$this->id_persona->CurrentValue = $this->id_persona->getSessionValue();
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
			} else {
			if (trim(strval($this->id_persona->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_persona->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT DISTINCT `Id`, `nombres` AS `DispFld`, `paterno` AS `Disp2Fld`, `materno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `personas`";
			$sWhereWrk = "";
			$this->id_persona->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombres`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_persona->EditValue = $arwrk;
			}

			// id_deuda
			$this->id_deuda->EditCustomAttributes = "";
			if ($this->id_deuda->getSessionValue() <> "") {
				$this->id_deuda->CurrentValue = $this->id_deuda->getSessionValue();
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
			} else {
			if (trim(strval($this->id_deuda->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_deuda->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT DISTINCT `Id`, (SELECT CONCAT(c.codigo,' - ',deudas.mig_codigo_deuda) FROM cuentas c WHERE c.Id=id_cliente) AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `deudas`";
			$sWhereWrk = "";
			$this->id_deuda->LookupFilters = array("dx1" => '(SELECT CONCAT(c.codigo,\' - \',deudas.mig_codigo_deuda) FROM cuentas c WHERE c.Id=id_cliente)');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_deuda, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->id_deuda->ViewValue = $this->id_deuda->DisplayValue($arwrk);
			} else {
				$this->id_deuda->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_deuda->EditValue = $arwrk;
			}

			// id_tipopersona
			$this->id_tipopersona->EditAttrs["class"] = "form-control";
			$this->id_tipopersona->EditCustomAttributes = "";
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
			$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_tipopersona->EditValue = $arwrk;

			// mig_codigo_cliente
			$this->mig_codigo_cliente->EditAttrs["class"] = "form-control";
			$this->mig_codigo_cliente->EditCustomAttributes = "";
			$this->mig_codigo_cliente->EditValue = ew_HtmlEncode($this->mig_codigo_cliente->CurrentValue);
			$this->mig_codigo_cliente->PlaceHolder = ew_RemoveHtml($this->mig_codigo_cliente->FldCaption());

			// Add refer script
			// id_persona

			$this->id_persona->LinkCustomAttributes = "";
			if (!ew_Empty($this->id_persona->CurrentValue)) {
				$this->id_persona->HrefValue = "personasview.php?showdetail=direcciones,telefonos,emails,vehiculos,deuda_persona&Id=" . $this->id_persona->CurrentValue; // Add prefix/suffix
				$this->id_persona->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->id_persona->HrefValue = ew_FullUrl($this->id_persona->HrefValue, "href");
			} else {
				$this->id_persona->HrefValue = "";
			}

			// id_deuda
			$this->id_deuda->LinkCustomAttributes = "";
			if (!ew_Empty($this->id_deuda->CurrentValue)) {
				$this->id_deuda->HrefValue = "deudasview.php?showdetail=deuda_persona&Id=" . $this->id_deuda->CurrentValue; // Add prefix/suffix
				$this->id_deuda->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->id_deuda->HrefValue = ew_FullUrl($this->id_deuda->HrefValue, "href");
			} else {
				$this->id_deuda->HrefValue = "";
			}

			// id_tipopersona
			$this->id_tipopersona->LinkCustomAttributes = "";
			$this->id_tipopersona->HrefValue = "";

			// mig_codigo_cliente
			$this->mig_codigo_cliente->LinkCustomAttributes = "";
			$this->mig_codigo_cliente->HrefValue = "";
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
		if (!$this->id_persona->FldIsDetailKey && !is_null($this->id_persona->FormValue) && $this->id_persona->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_persona->FldCaption(), $this->id_persona->ReqErrMsg));
		}
		if (!$this->id_deuda->FldIsDetailKey && !is_null($this->id_deuda->FormValue) && $this->id_deuda->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_deuda->FldCaption(), $this->id_deuda->ReqErrMsg));
		}
		if (!$this->id_tipopersona->FldIsDetailKey && !is_null($this->id_tipopersona->FormValue) && $this->id_tipopersona->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_tipopersona->FldCaption(), $this->id_tipopersona->ReqErrMsg));
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

		// Check referential integrity for master table 'deudas'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_deudas();
		if (strval($this->id_deuda->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@Id@", ew_AdjustSql($this->id_deuda->CurrentValue, "DB"), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			if (!isset($GLOBALS["deudas"])) $GLOBALS["deudas"] = new cdeudas();
			$rsmaster = $GLOBALS["deudas"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "deudas", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// id_persona
		$this->id_persona->SetDbValueDef($rsnew, $this->id_persona->CurrentValue, 0, strval($this->id_persona->CurrentValue) == "");

		// id_deuda
		$this->id_deuda->SetDbValueDef($rsnew, $this->id_deuda->CurrentValue, 0, strval($this->id_deuda->CurrentValue) == "");

		// id_tipopersona
		$this->id_tipopersona->SetDbValueDef($rsnew, $this->id_tipopersona->CurrentValue, 0, strval($this->id_tipopersona->CurrentValue) == "");

		// mig_codigo_cliente
		$this->mig_codigo_cliente->SetDbValueDef($rsnew, $this->mig_codigo_cliente->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['id_persona']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['id_deuda']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
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
			if ($sMasterTblVar == "deudas") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_Id"] <> "") {
					$GLOBALS["deudas"]->Id->setQueryStringValue($_GET["fk_Id"]);
					$this->id_deuda->setQueryStringValue($GLOBALS["deudas"]->Id->QueryStringValue);
					$this->id_deuda->setSessionValue($this->id_deuda->QueryStringValue);
					if (!is_numeric($GLOBALS["deudas"]->Id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "personas") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_Id"] <> "") {
					$GLOBALS["personas"]->Id->setQueryStringValue($_GET["fk_Id"]);
					$this->id_persona->setQueryStringValue($GLOBALS["personas"]->Id->QueryStringValue);
					$this->id_persona->setSessionValue($this->id_persona->QueryStringValue);
					if (!is_numeric($GLOBALS["personas"]->Id->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar == "deudas") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_Id"] <> "") {
					$GLOBALS["deudas"]->Id->setFormValue($_POST["fk_Id"]);
					$this->id_deuda->setFormValue($GLOBALS["deudas"]->Id->FormValue);
					$this->id_deuda->setSessionValue($this->id_deuda->FormValue);
					if (!is_numeric($GLOBALS["deudas"]->Id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "personas") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_Id"] <> "") {
					$GLOBALS["personas"]->Id->setFormValue($_POST["fk_Id"]);
					$this->id_persona->setFormValue($GLOBALS["personas"]->Id->FormValue);
					$this->id_persona->setSessionValue($this->id_persona->FormValue);
					if (!is_numeric($GLOBALS["personas"]->Id->FormValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "deudas") {
				if ($this->id_deuda->CurrentValue == "") $this->id_deuda->setSessionValue("");
			}
			if ($sMasterTblVar <> "personas") {
				if ($this->id_persona->CurrentValue == "") $this->id_persona->setSessionValue("");
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("deuda_personalist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_persona":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT DISTINCT `Id` AS `LinkFld`, `nombres` AS `DispFld`, `paterno` AS `Disp2Fld`, `materno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `personas`";
			$sWhereWrk = "";
			$this->id_persona->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombres`";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_deuda":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT DISTINCT `Id` AS `LinkFld`, (SELECT CONCAT(c.codigo,' - ',deudas.mig_codigo_deuda) FROM cuentas c WHERE c.Id=id_cliente) AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `deudas`";
			$sWhereWrk = "{filter}";
			$this->id_deuda->LookupFilters = array("dx1" => '(SELECT CONCAT(c.codigo,\' - \',deudas.mig_codigo_deuda) FROM cuentas c WHERE c.Id=id_cliente)');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_deuda, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
if (!isset($deuda_persona_add)) $deuda_persona_add = new cdeuda_persona_add();

// Page init
$deuda_persona_add->Page_Init();

// Page main
$deuda_persona_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$deuda_persona_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fdeuda_personaadd = new ew_Form("fdeuda_personaadd", "add");

// Validate form
fdeuda_personaadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_persona");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $deuda_persona->id_persona->FldCaption(), $deuda_persona->id_persona->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_deuda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $deuda_persona->id_deuda->FldCaption(), $deuda_persona->id_deuda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_tipopersona");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $deuda_persona->id_tipopersona->FldCaption(), $deuda_persona->id_tipopersona->ReqErrMsg)) ?>");

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
fdeuda_personaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdeuda_personaadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdeuda_personaadd.Lists["x_id_persona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_paterno","x_materno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
fdeuda_personaadd.Lists["x_id_persona"].Data = "<?php echo $deuda_persona_add->id_persona->LookupFilterQuery(FALSE, "add") ?>";
fdeuda_personaadd.Lists["x_id_deuda"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_cuenta","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"deudas"};
fdeuda_personaadd.Lists["x_id_deuda"].Data = "<?php echo $deuda_persona_add->id_deuda->LookupFilterQuery(FALSE, "add") ?>";
fdeuda_personaadd.Lists["x_id_tipopersona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipo_persona"};
fdeuda_personaadd.Lists["x_id_tipopersona"].Data = "<?php echo $deuda_persona_add->id_tipopersona->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $deuda_persona_add->ShowPageHeader(); ?>
<?php
$deuda_persona_add->ShowMessage();
?>
<form name="fdeuda_personaadd" id="fdeuda_personaadd" class="<?php echo $deuda_persona_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($deuda_persona_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $deuda_persona_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="deuda_persona">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($deuda_persona_add->IsModal) ?>">
<?php if ($deuda_persona->getCurrentMasterTable() == "deudas") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="deudas">
<input type="hidden" name="fk_Id" value="<?php echo $deuda_persona->id_deuda->getSessionValue() ?>">
<?php } ?>
<?php if ($deuda_persona->getCurrentMasterTable() == "personas") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="personas">
<input type="hidden" name="fk_Id" value="<?php echo $deuda_persona->id_persona->getSessionValue() ?>">
<?php } ?>
<div class="ewAddDiv"><!-- page* -->
<?php if ($deuda_persona->id_persona->Visible) { // id_persona ?>
	<div id="r_id_persona" class="form-group">
		<label id="elh_deuda_persona_id_persona" for="x_id_persona" class="<?php echo $deuda_persona_add->LeftColumnClass ?>"><?php echo $deuda_persona->id_persona->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $deuda_persona_add->RightColumnClass ?>"><div<?php echo $deuda_persona->id_persona->CellAttributes() ?>>
<?php if ($deuda_persona->id_persona->getSessionValue() <> "") { ?>
<span id="el_deuda_persona_id_persona">
<span<?php echo $deuda_persona->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_persona->ViewValue)) && $deuda_persona->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $deuda_persona->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deuda_persona->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x_id_persona" name="x_id_persona" value="<?php echo ew_HtmlEncode($deuda_persona->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el_deuda_persona_id_persona">
<select data-table="deuda_persona" data-field="x_id_persona" data-value-separator="<?php echo $deuda_persona->id_persona->DisplayValueSeparatorAttribute() ?>" id="x_id_persona" name="x_id_persona"<?php echo $deuda_persona->id_persona->EditAttributes() ?>>
<?php echo $deuda_persona->id_persona->SelectOptionListHtml("x_id_persona") ?>
</select>
</span>
<?php } ?>
<?php echo $deuda_persona->id_persona->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deuda_persona->id_deuda->Visible) { // id_deuda ?>
	<div id="r_id_deuda" class="form-group">
		<label id="elh_deuda_persona_id_deuda" for="x_id_deuda" class="<?php echo $deuda_persona_add->LeftColumnClass ?>"><?php echo $deuda_persona->id_deuda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $deuda_persona_add->RightColumnClass ?>"><div<?php echo $deuda_persona->id_deuda->CellAttributes() ?>>
<?php if ($deuda_persona->id_deuda->getSessionValue() <> "") { ?>
<span id="el_deuda_persona_id_deuda">
<span<?php echo $deuda_persona->id_deuda->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_deuda->ViewValue)) && $deuda_persona->id_deuda->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_deuda->LinkAttributes() ?>><p class="form-control-static"><?php echo $deuda_persona->id_deuda->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deuda_persona->id_deuda->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x_id_deuda" name="x_id_deuda" value="<?php echo ew_HtmlEncode($deuda_persona->id_deuda->CurrentValue) ?>">
<?php } else { ?>
<span id="el_deuda_persona_id_deuda">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_deuda"><?php echo (strval($deuda_persona->id_deuda->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $deuda_persona->id_deuda->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($deuda_persona->id_deuda->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_deuda',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="deuda_persona" data-field="x_id_deuda" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $deuda_persona->id_deuda->DisplayValueSeparatorAttribute() ?>" name="x_id_deuda" id="x_id_deuda" value="<?php echo $deuda_persona->id_deuda->CurrentValue ?>"<?php echo $deuda_persona->id_deuda->EditAttributes() ?>>
</span>
<?php } ?>
<?php echo $deuda_persona->id_deuda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deuda_persona->id_tipopersona->Visible) { // id_tipopersona ?>
	<div id="r_id_tipopersona" class="form-group">
		<label id="elh_deuda_persona_id_tipopersona" for="x_id_tipopersona" class="<?php echo $deuda_persona_add->LeftColumnClass ?>"><?php echo $deuda_persona->id_tipopersona->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $deuda_persona_add->RightColumnClass ?>"><div<?php echo $deuda_persona->id_tipopersona->CellAttributes() ?>>
<span id="el_deuda_persona_id_tipopersona">
<select data-table="deuda_persona" data-field="x_id_tipopersona" data-value-separator="<?php echo $deuda_persona->id_tipopersona->DisplayValueSeparatorAttribute() ?>" id="x_id_tipopersona" name="x_id_tipopersona"<?php echo $deuda_persona->id_tipopersona->EditAttributes() ?>>
<?php echo $deuda_persona->id_tipopersona->SelectOptionListHtml("x_id_tipopersona") ?>
</select>
</span>
<?php echo $deuda_persona->id_tipopersona->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deuda_persona->mig_codigo_cliente->Visible) { // mig_codigo_cliente ?>
	<div id="r_mig_codigo_cliente" class="form-group">
		<label id="elh_deuda_persona_mig_codigo_cliente" for="x_mig_codigo_cliente" class="<?php echo $deuda_persona_add->LeftColumnClass ?>"><?php echo $deuda_persona->mig_codigo_cliente->FldCaption() ?></label>
		<div class="<?php echo $deuda_persona_add->RightColumnClass ?>"><div<?php echo $deuda_persona->mig_codigo_cliente->CellAttributes() ?>>
<span id="el_deuda_persona_mig_codigo_cliente">
<input type="text" data-table="deuda_persona" data-field="x_mig_codigo_cliente" name="x_mig_codigo_cliente" id="x_mig_codigo_cliente" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deuda_persona->mig_codigo_cliente->getPlaceHolder()) ?>" value="<?php echo $deuda_persona->mig_codigo_cliente->EditValue ?>"<?php echo $deuda_persona->mig_codigo_cliente->EditAttributes() ?>>
</span>
<?php echo $deuda_persona->mig_codigo_cliente->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$deuda_persona_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $deuda_persona_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $deuda_persona_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fdeuda_personaadd.Init();
</script>
<?php
$deuda_persona_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$deuda_persona_add->Page_Terminate();
?>
