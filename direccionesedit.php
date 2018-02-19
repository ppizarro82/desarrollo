<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "direccionesinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "personasinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$direcciones_edit = NULL; // Initialize page object first

class cdirecciones_edit extends cdirecciones {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'direcciones';

	// Page object name
	var $PageObjName = 'direcciones_edit';

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

		// Table object (personas)
		if (!isset($GLOBALS['personas'])) $GLOBALS['personas'] = new cpersonas();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
		$this->Id->SetVisibility();
		$this->Id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->id_persona->SetVisibility();
		$this->id_ciudad->SetVisibility();
		$this->tipo_direccion->SetVisibility();
		$this->direccion->SetVisibility();
		$this->ult_fecha_activo->SetVisibility();
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
					$this->Page_Terminate("direccioneslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "direccioneslist.php")
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
		if (!$this->Id->FldIsDetailKey)
			$this->Id->setFormValue($objForm->GetValue("x_Id"));
		if (!$this->id_persona->FldIsDetailKey) {
			$this->id_persona->setFormValue($objForm->GetValue("x_id_persona"));
		}
		if (!$this->id_ciudad->FldIsDetailKey) {
			$this->id_ciudad->setFormValue($objForm->GetValue("x_id_ciudad"));
		}
		if (!$this->tipo_direccion->FldIsDetailKey) {
			$this->tipo_direccion->setFormValue($objForm->GetValue("x_tipo_direccion"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->ult_fecha_activo->FldIsDetailKey) {
			$this->ult_fecha_activo->setFormValue($objForm->GetValue("x_ult_fecha_activo"));
			$this->ult_fecha_activo->CurrentValue = ew_UnFormatDateTime($this->ult_fecha_activo->CurrentValue, 7);
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
		$this->Id->CurrentValue = $this->Id->FormValue;
		$this->id_persona->CurrentValue = $this->id_persona->FormValue;
		$this->id_ciudad->CurrentValue = $this->id_ciudad->FormValue;
		$this->tipo_direccion->CurrentValue = $this->tipo_direccion->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->ult_fecha_activo->CurrentValue = $this->ult_fecha_activo->FormValue;
		$this->ult_fecha_activo->CurrentValue = ew_UnFormatDateTime($this->ult_fecha_activo->CurrentValue, 7);
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
		$this->id_persona->setDbValue($row['id_persona']);
		$this->id_ciudad->setDbValue($row['id_ciudad']);
		$this->tipo_direccion->setDbValue($row['tipo_direccion']);
		$this->direccion->setDbValue($row['direccion']);
		$this->ult_fecha_activo->setDbValue($row['ult_fecha_activo']);
		$this->mapa->setDbValue($row['mapa']);
		$this->longitud->setDbValue($row['longitud']);
		$this->latitud->setDbValue($row['latitud']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['Id'] = NULL;
		$row['id_persona'] = NULL;
		$row['id_ciudad'] = NULL;
		$row['tipo_direccion'] = NULL;
		$row['direccion'] = NULL;
		$row['ult_fecha_activo'] = NULL;
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
		$this->id_persona->DbValue = $row['id_persona'];
		$this->id_ciudad->DbValue = $row['id_ciudad'];
		$this->tipo_direccion->DbValue = $row['tipo_direccion'];
		$this->direccion->DbValue = $row['direccion'];
		$this->ult_fecha_activo->DbValue = $row['ult_fecha_activo'];
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
		// id_persona
		// id_ciudad
		// tipo_direccion
		// direccion
		// ult_fecha_activo
		// mapa
		// longitud
		// latitud

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// id_persona
		if (strval($this->id_persona->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_persona->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id`, `nombres` AS `DispFld`, `paterno` AS `Disp2Fld`, `materno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `personas`";
		$sWhereWrk = "";
		$this->id_persona->LookupFilters = array();
		$lookuptblfilter = "`estado`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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
		$this->id_persona->ViewCustomAttributes = "";

		// id_ciudad
		if (strval($this->id_ciudad->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_ciudad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `ciudades`";
		$sWhereWrk = "";
		$this->id_ciudad->LookupFilters = array();
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

		// tipo_direccion
		if (strval($this->tipo_direccion->CurrentValue) <> "") {
			$this->tipo_direccion->ViewValue = $this->tipo_direccion->OptionCaption($this->tipo_direccion->CurrentValue);
		} else {
			$this->tipo_direccion->ViewValue = NULL;
		}
		$this->tipo_direccion->ViewCustomAttributes = "";

		// direccion
		$this->direccion->ViewValue = $this->direccion->CurrentValue;
		$this->direccion->ViewCustomAttributes = "";

		// ult_fecha_activo
		$this->ult_fecha_activo->ViewValue = $this->ult_fecha_activo->CurrentValue;
		$this->ult_fecha_activo->ViewValue = ew_FormatDateTime($this->ult_fecha_activo->ViewValue, 7);
		$this->ult_fecha_activo->ViewCustomAttributes = "";

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

			// id_persona
			$this->id_persona->LinkCustomAttributes = "";
			if (!ew_Empty($this->id_persona->CurrentValue)) {
				$this->id_persona->HrefValue = "personasview.php?showdetail=telefonos,direcciones,emails&Id=" . $this->id_persona->CurrentValue; // Add prefix/suffix
				$this->id_persona->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->id_persona->HrefValue = ew_FullUrl($this->id_persona->HrefValue, "href");
			} else {
				$this->id_persona->HrefValue = "";
			}
			$this->id_persona->TooltipValue = "";

			// id_ciudad
			$this->id_ciudad->LinkCustomAttributes = "";
			$this->id_ciudad->HrefValue = "";
			$this->id_ciudad->TooltipValue = "";

			// tipo_direccion
			$this->tipo_direccion->LinkCustomAttributes = "";
			$this->tipo_direccion->HrefValue = "";
			$this->tipo_direccion->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// ult_fecha_activo
			$this->ult_fecha_activo->LinkCustomAttributes = "";
			$this->ult_fecha_activo->HrefValue = "";
			$this->ult_fecha_activo->TooltipValue = "";

			// longitud
			$this->longitud->LinkCustomAttributes = "";
			$this->longitud->HrefValue = "";
			$this->longitud->TooltipValue = "";

			// latitud
			$this->latitud->LinkCustomAttributes = "";
			$this->latitud->HrefValue = "";
			$this->latitud->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			$this->Id->EditValue = $this->Id->CurrentValue;
			$this->Id->ViewCustomAttributes = "";

			// id_persona
			$this->id_persona->EditAttrs["class"] = "form-control";
			$this->id_persona->EditCustomAttributes = "";
			if ($this->id_persona->getSessionValue() <> "") {
				$this->id_persona->CurrentValue = $this->id_persona->getSessionValue();
			if (strval($this->id_persona->CurrentValue) <> "") {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_persona->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `Id`, `nombres` AS `DispFld`, `paterno` AS `Disp2Fld`, `materno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `personas`";
			$sWhereWrk = "";
			$this->id_persona->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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
			$this->id_persona->ViewCustomAttributes = "";
			} else {
			if (trim(strval($this->id_persona->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_persona->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id`, `nombres` AS `DispFld`, `paterno` AS `Disp2Fld`, `materno` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `personas`";
			$sWhereWrk = "";
			$this->id_persona->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_persona->EditValue = $arwrk;
			}

			// id_ciudad
			$this->id_ciudad->EditAttrs["class"] = "form-control";
			$this->id_ciudad->EditCustomAttributes = 'onChange=initMap(this);';
			if (trim(strval($this->id_ciudad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_ciudad->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `ciudades`";
			$sWhereWrk = "";
			$this->id_ciudad->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_ciudad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_ciudad->EditValue = $arwrk;

			// tipo_direccion
			$this->tipo_direccion->EditAttrs["class"] = "form-control";
			$this->tipo_direccion->EditCustomAttributes = "";
			$this->tipo_direccion->EditValue = $this->tipo_direccion->Options(TRUE);

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// ult_fecha_activo
			$this->ult_fecha_activo->EditAttrs["class"] = "form-control";
			$this->ult_fecha_activo->EditCustomAttributes = "";
			$this->ult_fecha_activo->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->ult_fecha_activo->CurrentValue, 7));
			$this->ult_fecha_activo->PlaceHolder = ew_RemoveHtml($this->ult_fecha_activo->FldCaption());

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

			// Edit refer script
			// Id

			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";

			// id_persona
			$this->id_persona->LinkCustomAttributes = "";
			if (!ew_Empty($this->id_persona->CurrentValue)) {
				$this->id_persona->HrefValue = "personasview.php?showdetail=telefonos,direcciones,emails&Id=" . $this->id_persona->CurrentValue; // Add prefix/suffix
				$this->id_persona->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->id_persona->HrefValue = ew_FullUrl($this->id_persona->HrefValue, "href");
			} else {
				$this->id_persona->HrefValue = "";
			}

			// id_ciudad
			$this->id_ciudad->LinkCustomAttributes = "";
			$this->id_ciudad->HrefValue = "";

			// tipo_direccion
			$this->tipo_direccion->LinkCustomAttributes = "";
			$this->tipo_direccion->HrefValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";

			// ult_fecha_activo
			$this->ult_fecha_activo->LinkCustomAttributes = "";
			$this->ult_fecha_activo->HrefValue = "";

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
		if (!$this->id_persona->FldIsDetailKey && !is_null($this->id_persona->FormValue) && $this->id_persona->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_persona->FldCaption(), $this->id_persona->ReqErrMsg));
		}
		if (!$this->id_ciudad->FldIsDetailKey && !is_null($this->id_ciudad->FormValue) && $this->id_ciudad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_ciudad->FldCaption(), $this->id_ciudad->ReqErrMsg));
		}
		if (!$this->tipo_direccion->FldIsDetailKey && !is_null($this->tipo_direccion->FormValue) && $this->tipo_direccion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tipo_direccion->FldCaption(), $this->tipo_direccion->ReqErrMsg));
		}
		if (!$this->direccion->FldIsDetailKey && !is_null($this->direccion->FormValue) && $this->direccion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->direccion->FldCaption(), $this->direccion->ReqErrMsg));
		}
		if (!$this->ult_fecha_activo->FldIsDetailKey && !is_null($this->ult_fecha_activo->FormValue) && $this->ult_fecha_activo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ult_fecha_activo->FldCaption(), $this->ult_fecha_activo->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->ult_fecha_activo->FormValue)) {
			ew_AddMessage($gsFormError, $this->ult_fecha_activo->FldErrMsg());
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
			$this->id_persona->SetDbValueDef($rsnew, $this->id_persona->CurrentValue, 0, $this->id_persona->ReadOnly);

			// id_ciudad
			$this->id_ciudad->SetDbValueDef($rsnew, $this->id_ciudad->CurrentValue, 0, $this->id_ciudad->ReadOnly);

			// tipo_direccion
			$this->tipo_direccion->SetDbValueDef($rsnew, $this->tipo_direccion->CurrentValue, "", $this->tipo_direccion->ReadOnly);

			// direccion
			$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, "", $this->direccion->ReadOnly);

			// ult_fecha_activo
			$this->ult_fecha_activo->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->ult_fecha_activo->CurrentValue, 7), ew_CurrentDate(), $this->ult_fecha_activo->ReadOnly);

			// longitud
			$this->longitud->SetDbValueDef($rsnew, $this->longitud->CurrentValue, NULL, $this->longitud->ReadOnly);

			// latitud
			$this->latitud->SetDbValueDef($rsnew, $this->latitud->CurrentValue, NULL, $this->latitud->ReadOnly);

			// Check referential integrity for master table 'personas'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_personas();
			$KeyValue = isset($rsnew['id_persona']) ? $rsnew['id_persona'] : $rsold['id_persona'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@Id@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				if (!isset($GLOBALS["personas"])) $GLOBALS["personas"] = new cpersonas();
				$rsmaster = $GLOBALS["personas"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "personas", $Language->Phrase("RelatedRecordRequired"));
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("direccioneslist.php"), "", $this->TableVar, TRUE);
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
		$this->MultiPages = $pages;
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_id_persona":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id` AS `LinkFld`, `nombres` AS `DispFld`, `paterno` AS `Disp2Fld`, `materno` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `personas`";
			$sWhereWrk = "";
			$this->id_persona->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_persona, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_ciudad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `ciudades`";
			$sWhereWrk = "";
			$this->id_ciudad->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_ciudad, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($direcciones_edit)) $direcciones_edit = new cdirecciones_edit();

// Page init
$direcciones_edit->Page_Init();

// Page main
$direcciones_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$direcciones_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fdireccionesedit = new ew_Form("fdireccionesedit", "edit");

// Validate form
fdireccionesedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $direcciones->id_persona->FldCaption(), $direcciones->id_persona->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_ciudad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $direcciones->id_ciudad->FldCaption(), $direcciones->id_ciudad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipo_direccion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $direcciones->tipo_direccion->FldCaption(), $direcciones->tipo_direccion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_direccion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $direcciones->direccion->FldCaption(), $direcciones->direccion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ult_fecha_activo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $direcciones->ult_fecha_activo->FldCaption(), $direcciones->ult_fecha_activo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ult_fecha_activo");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($direcciones->ult_fecha_activo->FldErrMsg()) ?>");

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
fdireccionesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdireccionesedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
fdireccionesedit.MultiPage = new ew_MultiPage("fdireccionesedit");

// Dynamic selection lists
fdireccionesedit.Lists["x_id_persona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_paterno","x_materno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
fdireccionesedit.Lists["x_id_persona"].Data = "<?php echo $direcciones_edit->id_persona->LookupFilterQuery(FALSE, "edit") ?>";
fdireccionesedit.Lists["x_id_ciudad"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"ciudades"};
fdireccionesedit.Lists["x_id_ciudad"].Data = "<?php echo $direcciones_edit->id_ciudad->LookupFilterQuery(FALSE, "edit") ?>";
fdireccionesedit.Lists["x_tipo_direccion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdireccionesedit.Lists["x_tipo_direccion"].Options = <?php echo json_encode($direcciones_edit->tipo_direccion->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $direcciones_edit->ShowPageHeader(); ?>
<?php
$direcciones_edit->ShowMessage();
?>
<form name="fdireccionesedit" id="fdireccionesedit" class="<?php echo $direcciones_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($direcciones_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $direcciones_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="direcciones">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($direcciones_edit->IsModal) ?>">
<?php if ($direcciones->getCurrentMasterTable() == "personas") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="personas">
<input type="hidden" name="fk_Id" value="<?php echo $direcciones->id_persona->getSessionValue() ?>">
<?php } ?>
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="direcciones_edit"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $direcciones_edit->MultiPages->NavStyle() ?>">
		<li<?php echo $direcciones_edit->MultiPages->TabStyle("1") ?>><a href="#tab_direcciones1" data-toggle="tab"><?php echo $direcciones->PageCaption(1) ?></a></li>
		<li<?php echo $direcciones_edit->MultiPages->TabStyle("2") ?>><a href="#tab_direcciones2" data-toggle="tab"><?php echo $direcciones->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $direcciones_edit->MultiPages->PageStyle("1") ?>" id="tab_direcciones1"><!-- multi-page .tab-pane -->
<div class="ewEditDiv"><!-- page* -->
<?php if ($direcciones->Id->Visible) { // Id ?>
	<div id="r_Id" class="form-group">
		<label id="elh_direcciones_Id" class="<?php echo $direcciones_edit->LeftColumnClass ?>"><?php echo $direcciones->Id->FldCaption() ?></label>
		<div class="<?php echo $direcciones_edit->RightColumnClass ?>"><div<?php echo $direcciones->Id->CellAttributes() ?>>
<span id="el_direcciones_Id">
<span<?php echo $direcciones->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $direcciones->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="direcciones" data-field="x_Id" data-page="1" name="x_Id" id="x_Id" value="<?php echo ew_HtmlEncode($direcciones->Id->CurrentValue) ?>">
<?php echo $direcciones->Id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->id_persona->Visible) { // id_persona ?>
	<div id="r_id_persona" class="form-group">
		<label id="elh_direcciones_id_persona" for="x_id_persona" class="<?php echo $direcciones_edit->LeftColumnClass ?>"><?php echo $direcciones->id_persona->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $direcciones_edit->RightColumnClass ?>"><div<?php echo $direcciones->id_persona->CellAttributes() ?>>
<?php if ($direcciones->id_persona->getSessionValue() <> "") { ?>
<span id="el_direcciones_id_persona">
<span<?php echo $direcciones->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($direcciones->id_persona->ViewValue)) && $direcciones->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $direcciones->id_persona->LinkAttributes() ?>><p class="form-control-static"><?php echo $direcciones->id_persona->ViewValue ?></p></a>
<?php } else { ?>
<p class="form-control-static"><?php echo $direcciones->id_persona->ViewValue ?></p>
<?php } ?>
</span>
</span>
<input type="hidden" id="x_id_persona" name="x_id_persona" value="<?php echo ew_HtmlEncode($direcciones->id_persona->CurrentValue) ?>">
<?php } else { ?>
<span id="el_direcciones_id_persona">
<select data-table="direcciones" data-field="x_id_persona" data-page="1" data-value-separator="<?php echo $direcciones->id_persona->DisplayValueSeparatorAttribute() ?>" id="x_id_persona" name="x_id_persona"<?php echo $direcciones->id_persona->EditAttributes() ?>>
<?php echo $direcciones->id_persona->SelectOptionListHtml("x_id_persona") ?>
</select>
</span>
<?php } ?>
<?php echo $direcciones->id_persona->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->tipo_direccion->Visible) { // tipo_direccion ?>
	<div id="r_tipo_direccion" class="form-group">
		<label id="elh_direcciones_tipo_direccion" for="x_tipo_direccion" class="<?php echo $direcciones_edit->LeftColumnClass ?>"><?php echo $direcciones->tipo_direccion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $direcciones_edit->RightColumnClass ?>"><div<?php echo $direcciones->tipo_direccion->CellAttributes() ?>>
<span id="el_direcciones_tipo_direccion">
<select data-table="direcciones" data-field="x_tipo_direccion" data-page="1" data-value-separator="<?php echo $direcciones->tipo_direccion->DisplayValueSeparatorAttribute() ?>" id="x_tipo_direccion" name="x_tipo_direccion"<?php echo $direcciones->tipo_direccion->EditAttributes() ?>>
<?php echo $direcciones->tipo_direccion->SelectOptionListHtml("x_tipo_direccion") ?>
</select>
</span>
<?php echo $direcciones->tipo_direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->ult_fecha_activo->Visible) { // ult_fecha_activo ?>
	<div id="r_ult_fecha_activo" class="form-group">
		<label id="elh_direcciones_ult_fecha_activo" for="x_ult_fecha_activo" class="<?php echo $direcciones_edit->LeftColumnClass ?>"><?php echo $direcciones->ult_fecha_activo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $direcciones_edit->RightColumnClass ?>"><div<?php echo $direcciones->ult_fecha_activo->CellAttributes() ?>>
<span id="el_direcciones_ult_fecha_activo">
<input type="text" data-table="direcciones" data-field="x_ult_fecha_activo" data-page="1" data-format="7" name="x_ult_fecha_activo" id="x_ult_fecha_activo" size="20" placeholder="<?php echo ew_HtmlEncode($direcciones->ult_fecha_activo->getPlaceHolder()) ?>" value="<?php echo $direcciones->ult_fecha_activo->EditValue ?>"<?php echo $direcciones->ult_fecha_activo->EditAttributes() ?>>
<?php if (!$direcciones->ult_fecha_activo->ReadOnly && !$direcciones->ult_fecha_activo->Disabled && !isset($direcciones->ult_fecha_activo->EditAttrs["readonly"]) && !isset($direcciones->ult_fecha_activo->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdireccionesedit", "x_ult_fecha_activo", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
<?php echo $direcciones->ult_fecha_activo->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $direcciones_edit->MultiPages->PageStyle("2") ?>" id="tab_direcciones2"><!-- multi-page .tab-pane -->
<div class="ewEditDiv"><!-- page* -->
<?php if ($direcciones->id_ciudad->Visible) { // id_ciudad ?>
	<div id="r_id_ciudad" class="form-group">
		<label id="elh_direcciones_id_ciudad" for="x_id_ciudad" class="<?php echo $direcciones_edit->LeftColumnClass ?>"><?php echo $direcciones->id_ciudad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $direcciones_edit->RightColumnClass ?>"><div<?php echo $direcciones->id_ciudad->CellAttributes() ?>>
<span id="el_direcciones_id_ciudad">
<select data-table="direcciones" data-field="x_id_ciudad" data-page="2" data-value-separator="<?php echo $direcciones->id_ciudad->DisplayValueSeparatorAttribute() ?>" id="x_id_ciudad" name="x_id_ciudad"<?php echo $direcciones->id_ciudad->EditAttributes() ?>>
<?php echo $direcciones->id_ciudad->SelectOptionListHtml("x_id_ciudad") ?>
</select>
</span>
<?php echo $direcciones->id_ciudad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_direcciones_direccion" for="x_direccion" class="<?php echo $direcciones_edit->LeftColumnClass ?>"><?php echo $direcciones->direccion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $direcciones_edit->RightColumnClass ?>"><div<?php echo $direcciones->direccion->CellAttributes() ?>>
<span id="el_direcciones_direccion">
<input type="text" data-table="direcciones" data-field="x_direccion" data-page="2" name="x_direccion" id="x_direccion" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion->EditValue ?>"<?php echo $direcciones->direccion->EditAttributes() ?>>
</span>
<?php echo $direcciones->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<div style="margin-left:100px"><b>Ubique exactamente la dirección en el mapa desplazando el marcador por favor:</b><br><br></div>
<?php if ($direcciones->longitud->Visible) { // longitud ?>
	<div id="r_longitud" class="form-group">
		<label id="elh_direcciones_longitud" for="x_longitud" class="<?php echo $direcciones_edit->LeftColumnClass ?>"><?php echo $direcciones->longitud->FldCaption() ?></label>
		<div class="<?php echo $direcciones_edit->RightColumnClass ?>"><div<?php echo $direcciones->longitud->CellAttributes() ?>>
<span id="el_direcciones_longitud">
<input type="text" data-table="direcciones" data-field="x_longitud" data-page="2" name="x_longitud" id="x_longitud" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->longitud->getPlaceHolder()) ?>" value="<?php echo $direcciones->longitud->EditValue ?>"<?php echo $direcciones->longitud->EditAttributes() ?>>
</span>
<?php echo $direcciones->longitud->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($direcciones->latitud->Visible) { // latitud ?>
	<div id="r_latitud" class="form-group">
		<label id="elh_direcciones_latitud" for="x_latitud" class="<?php echo $direcciones_edit->LeftColumnClass ?>"><?php echo $direcciones->latitud->FldCaption() ?></label>
		<div class="<?php echo $direcciones_edit->RightColumnClass ?>"><div<?php echo $direcciones->latitud->CellAttributes() ?>>
<span id="el_direcciones_latitud">
<input type="text" data-table="direcciones" data-field="x_latitud" data-page="2" name="x_latitud" id="x_latitud" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->latitud->getPlaceHolder()) ?>" value="<?php echo $direcciones->latitud->EditValue ?>"<?php echo $direcciones->latitud->EditAttributes() ?>>
</span>
<?php echo $direcciones->latitud->CustomMsg ?></div></div>
	</div>
<?php } ?>
<style>
 #map {
   width: 400px;
   height: 400px;
   background-color: grey;
   margin-left:150px;
 }
</style>
<div id="map" class="form-group"></div>
</div>
		</div>
		<div class="tab-pane<?php echo $direcciones_edit->MultiPages->PageStyle("2") ?>" id="tab_direcciones2">
<div>
</div><!-- /page* -->
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<?php if (!$direcciones_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $direcciones_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $direcciones_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fdireccionesedit.Init();
</script>
<?php
$direcciones_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");
function initMap(x = 0) {

	//alert(document.getElementById('x_pos_longitud').value);
	var uluru = "";
	var zoom = 6;
		if (x == 0)
		{
		uluru = {lat: parseFloat(document.getElementById('x_latitud').value), lng: parseFloat(document.getElementById('x_longitud').value)};			
		zoom = 19;
		} 
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
	var map = new google.maps.Map(document.getElementById('map'), {
		  zoom: zoom
		});
	var centerPoint = new google.maps.LatLng(uluru.lat, uluru.lng);
	map.setCenter(centerPoint);

	//alert(centerPoint.lat);	
	var marker = new google.maps.Marker({
		  position: centerPoint,
		  map: map,
		  draggable: true
		});
	google.maps.event.addListener(marker, 'dragend', function(evt){
			document.getElementById('x_latitud').value = evt.latLng.lat();
			document.getElementById('x_longitud').value = evt.latLng.lng();
	});
	google.maps.event.trigger(map, 'resize');
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
$direcciones_edit->Page_Terminate();
?>

