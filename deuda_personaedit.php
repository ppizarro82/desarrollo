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

$deuda_persona_edit = NULL; // Initialize page object first

class cdeuda_persona_edit extends cdeuda_persona {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'deuda_persona';

	// Page object name
	var $PageObjName = 'deuda_persona_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

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
			if ($objForm->HasValue("x_id_persona")) {
				$this->id_persona->setFormValue($objForm->GetValue("x_id_persona"));
			}
			if ($objForm->HasValue("x_id_deuda")) {
				$this->id_deuda->setFormValue($objForm->GetValue("x_id_deuda"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["id_persona"])) {
				$this->id_persona->setQueryStringValue($_GET["id_persona"]);
				$loadByQuery = TRUE;
			} else {
				$this->id_persona->CurrentValue = NULL;
			}
			if (isset($_GET["id_deuda"])) {
				$this->id_deuda->setQueryStringValue($_GET["id_deuda"]);
				$loadByQuery = TRUE;
			} else {
				$this->id_deuda->CurrentValue = NULL;
			}
		}

		// Set up master detail parameters
		$this->SetupMasterParms();

		// Load current record
		$loaded = $this->LoadRow();

		// Process form if post back
		if ($postBack) {
			$this->LoadFormValues(); // Get form values
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
					$this->Page_Terminate("deuda_personalist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "deuda_personalist.php")
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
		$row = array();
		$row['id_persona'] = NULL;
		$row['id_deuda'] = NULL;
		$row['id_tipopersona'] = NULL;
		$row['mig_codigo_cliente'] = NULL;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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

			// Edit refer script
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

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// id_persona
			// id_deuda
			// id_tipopersona

			$this->id_tipopersona->SetDbValueDef($rsnew, $this->id_tipopersona->CurrentValue, 0, $this->id_tipopersona->ReadOnly);

			// mig_codigo_cliente
			$this->mig_codigo_cliente->SetDbValueDef($rsnew, $this->mig_codigo_cliente->CurrentValue, NULL, $this->mig_codigo_cliente->ReadOnly);

			// Check referential integrity for master table 'deudas'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_deudas();
			$KeyValue = isset($rsnew['id_deuda']) ? $rsnew['id_deuda'] : $rsold['id_deuda'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@Id@", ew_AdjustSql($KeyValue), $sMasterFilter);
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
			$this->setSessionWhere($this->GetDetailFilter());

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
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($deuda_persona_edit)) $deuda_persona_edit = new cdeuda_persona_edit();

// Page init
$deuda_persona_edit->Page_Init();

// Page main
$deuda_persona_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$deuda_persona_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fdeuda_personaedit = new ew_Form("fdeuda_personaedit", "edit");

// Validate form
fdeuda_personaedit.Validate = function() {
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
fdeuda_personaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdeuda_personaedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdeuda_personaedit.Lists["x_id_persona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_paterno","x_materno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
fdeuda_personaedit.Lists["x_id_persona"].Data = "<?php echo $deuda_persona_edit->id_persona->LookupFilterQuery(FALSE, "edit") ?>";
fdeuda_personaedit.Lists["x_id_deuda"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_cuenta","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"deudas"};
fdeuda_personaedit.Lists["x_id_deuda"].Data = "<?php echo $deuda_persona_edit->id_deuda->LookupFilterQuery(FALSE, "edit") ?>";
fdeuda_personaedit.Lists["x_id_tipopersona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipo_persona"};
fdeuda_personaedit.Lists["x_id_tipopersona"].Data = "<?php echo $deuda_persona_edit->id_tipopersona->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $deuda_persona_edit->ShowPageHeader(); ?>
<?php
$deuda_persona_edit->ShowMessage();
?>
<form name="fdeuda_personaedit" id="fdeuda_personaedit" class="<?php echo $deuda_persona_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($deuda_persona_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $deuda_persona_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="deuda_persona">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($deuda_persona_edit->IsModal) ?>">
<?php if ($deuda_persona->getCurrentMasterTable() == "deudas") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="deudas">
<input type="hidden" name="fk_Id" value="<?php echo $deuda_persona->id_deuda->getSessionValue() ?>">
<?php } ?>
<?php if ($deuda_persona->getCurrentMasterTable() == "personas") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="personas">
<input type="hidden" name="fk_Id" value="<?php echo $deuda_persona->id_persona->getSessionValue() ?>">
<?php } ?>
<div class="ewEditDiv"><!-- page* -->
<?php if ($deuda_persona->id_persona->Visible) { // id_persona ?>
	<div id="r_id_persona" class="form-group">
		<label id="elh_deuda_persona_id_persona" for="x_id_persona" class="<?php echo $deuda_persona_edit->LeftColumnClass ?>"><?php echo $deuda_persona->id_persona->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $deuda_persona_edit->RightColumnClass ?>"><div<?php echo $deuda_persona->id_persona->CellAttributes() ?>>
<span id="el_deuda_persona_id_persona">
<span<?php echo $deuda_persona->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_persona->EditValue)) && $deuda_persona->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $deuda_persona->id_persona->EditValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deuda_persona->id_persona->EditValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" data-table="deuda_persona" data-field="x_id_persona" name="x_id_persona" id="x_id_persona" value="<?php echo ew_HtmlEncode($deuda_persona->id_persona->CurrentValue) ?>">
<?php echo $deuda_persona->id_persona->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deuda_persona->id_deuda->Visible) { // id_deuda ?>
	<div id="r_id_deuda" class="form-group">
		<label id="elh_deuda_persona_id_deuda" for="x_id_deuda" class="<?php echo $deuda_persona_edit->LeftColumnClass ?>"><?php echo $deuda_persona->id_deuda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $deuda_persona_edit->RightColumnClass ?>"><div<?php echo $deuda_persona->id_deuda->CellAttributes() ?>>
<span id="el_deuda_persona_id_deuda">
<span<?php echo $deuda_persona->id_deuda->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deuda_persona->id_deuda->EditValue)) && $deuda_persona->id_deuda->LinkAttributes() <> "") { ?>
<a<?php echo $deuda_persona->id_deuda->LinkAttributes() ?>><p class="form-control-static"><?php echo $deuda_persona->id_deuda->EditValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $deuda_persona->id_deuda->EditValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" data-table="deuda_persona" data-field="x_id_deuda" name="x_id_deuda" id="x_id_deuda" value="<?php echo ew_HtmlEncode($deuda_persona->id_deuda->CurrentValue) ?>">
<?php echo $deuda_persona->id_deuda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($deuda_persona->id_tipopersona->Visible) { // id_tipopersona ?>
	<div id="r_id_tipopersona" class="form-group">
		<label id="elh_deuda_persona_id_tipopersona" for="x_id_tipopersona" class="<?php echo $deuda_persona_edit->LeftColumnClass ?>"><?php echo $deuda_persona->id_tipopersona->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $deuda_persona_edit->RightColumnClass ?>"><div<?php echo $deuda_persona->id_tipopersona->CellAttributes() ?>>
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
		<label id="elh_deuda_persona_mig_codigo_cliente" for="x_mig_codigo_cliente" class="<?php echo $deuda_persona_edit->LeftColumnClass ?>"><?php echo $deuda_persona->mig_codigo_cliente->FldCaption() ?></label>
		<div class="<?php echo $deuda_persona_edit->RightColumnClass ?>"><div<?php echo $deuda_persona->mig_codigo_cliente->CellAttributes() ?>>
<span id="el_deuda_persona_mig_codigo_cliente">
<input type="text" data-table="deuda_persona" data-field="x_mig_codigo_cliente" name="x_mig_codigo_cliente" id="x_mig_codigo_cliente" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deuda_persona->mig_codigo_cliente->getPlaceHolder()) ?>" value="<?php echo $deuda_persona->mig_codigo_cliente->EditValue ?>"<?php echo $deuda_persona->mig_codigo_cliente->EditAttributes() ?>>
</span>
<?php echo $deuda_persona->mig_codigo_cliente->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$deuda_persona_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $deuda_persona_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $deuda_persona_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fdeuda_personaedit.Init();
</script>
<?php
$deuda_persona_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$deuda_persona_edit->Page_Terminate();
?>
