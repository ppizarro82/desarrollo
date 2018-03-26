<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "deudasgridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$users_edit = NULL; // Initialize page object first

class cusers_edit extends cusers {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_edit';

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

		// Table object (users)
		if (!isset($GLOBALS["users"]) || get_class($GLOBALS["users"]) == "cusers") {
			$GLOBALS["users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["users"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'users', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("userslist.php"));
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
		$this->id_user->SetVisibility();
		$this->id_user->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->User_Level->SetVisibility();
		$this->Username->SetVisibility();
		$this->Password->SetVisibility();
		$this->No_documento->SetVisibility();
		$this->Tipo_documento->SetVisibility();
		$this->First_Name->SetVisibility();
		$this->Last_Name->SetVisibility();
		$this->_Email->SetVisibility();
		$this->Telefono_movil->SetVisibility();
		$this->Telefono_fijo->SetVisibility();
		$this->Fecha_nacimiento->SetVisibility();
		$this->Activated->SetVisibility();
		$this->Locked->SetVisibility();
		$this->token->SetVisibility();
		$this->acceso_app->SetVisibility();
		$this->observaciones->SetVisibility();

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
		global $EW_EXPORT, $users;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($users);
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
					if ($pageName == "usersview.php")
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
			if ($objForm->HasValue("x_id_user")) {
				$this->id_user->setFormValue($objForm->GetValue("x_id_user"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["id_user"])) {
				$this->id_user->setQueryStringValue($_GET["id_user"]);
				$loadByQuery = TRUE;
			} else {
				$this->id_user->CurrentValue = NULL;
			}
		}

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
					$this->Page_Terminate("userslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetupDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "userslist.php")
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
		if (!$this->id_user->FldIsDetailKey)
			$this->id_user->setFormValue($objForm->GetValue("x_id_user"));
		if (!$this->User_Level->FldIsDetailKey) {
			$this->User_Level->setFormValue($objForm->GetValue("x_User_Level"));
		}
		if (!$this->Username->FldIsDetailKey) {
			$this->Username->setFormValue($objForm->GetValue("x_Username"));
		}
		if (!$this->Password->FldIsDetailKey) {
			$this->Password->setFormValue($objForm->GetValue("x_Password"));
		}
		if (!$this->No_documento->FldIsDetailKey) {
			$this->No_documento->setFormValue($objForm->GetValue("x_No_documento"));
		}
		if (!$this->Tipo_documento->FldIsDetailKey) {
			$this->Tipo_documento->setFormValue($objForm->GetValue("x_Tipo_documento"));
		}
		if (!$this->First_Name->FldIsDetailKey) {
			$this->First_Name->setFormValue($objForm->GetValue("x_First_Name"));
		}
		if (!$this->Last_Name->FldIsDetailKey) {
			$this->Last_Name->setFormValue($objForm->GetValue("x_Last_Name"));
		}
		if (!$this->_Email->FldIsDetailKey) {
			$this->_Email->setFormValue($objForm->GetValue("x__Email"));
		}
		if (!$this->Telefono_movil->FldIsDetailKey) {
			$this->Telefono_movil->setFormValue($objForm->GetValue("x_Telefono_movil"));
		}
		if (!$this->Telefono_fijo->FldIsDetailKey) {
			$this->Telefono_fijo->setFormValue($objForm->GetValue("x_Telefono_fijo"));
		}
		if (!$this->Fecha_nacimiento->FldIsDetailKey) {
			$this->Fecha_nacimiento->setFormValue($objForm->GetValue("x_Fecha_nacimiento"));
			$this->Fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->Fecha_nacimiento->CurrentValue, 17);
		}
		if (!$this->Activated->FldIsDetailKey) {
			$this->Activated->setFormValue($objForm->GetValue("x_Activated"));
		}
		if (!$this->Locked->FldIsDetailKey) {
			$this->Locked->setFormValue($objForm->GetValue("x_Locked"));
		}
		if (!$this->token->FldIsDetailKey) {
			$this->token->setFormValue($objForm->GetValue("x_token"));
		}
		if (!$this->acceso_app->FldIsDetailKey) {
			$this->acceso_app->setFormValue($objForm->GetValue("x_acceso_app"));
		}
		if (!$this->observaciones->FldIsDetailKey) {
			$this->observaciones->setFormValue($objForm->GetValue("x_observaciones"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->id_user->CurrentValue = $this->id_user->FormValue;
		$this->User_Level->CurrentValue = $this->User_Level->FormValue;
		$this->Username->CurrentValue = $this->Username->FormValue;
		$this->Password->CurrentValue = $this->Password->FormValue;
		$this->No_documento->CurrentValue = $this->No_documento->FormValue;
		$this->Tipo_documento->CurrentValue = $this->Tipo_documento->FormValue;
		$this->First_Name->CurrentValue = $this->First_Name->FormValue;
		$this->Last_Name->CurrentValue = $this->Last_Name->FormValue;
		$this->_Email->CurrentValue = $this->_Email->FormValue;
		$this->Telefono_movil->CurrentValue = $this->Telefono_movil->FormValue;
		$this->Telefono_fijo->CurrentValue = $this->Telefono_fijo->FormValue;
		$this->Fecha_nacimiento->CurrentValue = $this->Fecha_nacimiento->FormValue;
		$this->Fecha_nacimiento->CurrentValue = ew_UnFormatDateTime($this->Fecha_nacimiento->CurrentValue, 17);
		$this->Activated->CurrentValue = $this->Activated->FormValue;
		$this->Locked->CurrentValue = $this->Locked->FormValue;
		$this->token->CurrentValue = $this->token->FormValue;
		$this->acceso_app->CurrentValue = $this->acceso_app->FormValue;
		$this->observaciones->CurrentValue = $this->observaciones->FormValue;
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
		$this->id_user->setDbValue($row['id_user']);
		$this->User_Level->setDbValue($row['User_Level']);
		$this->id_user_creator->setDbValue($row['id_user_creator']);
		$this->Username->setDbValue($row['Username']);
		$this->Password->setDbValue($row['Password']);
		$this->No_documento->setDbValue($row['No_documento']);
		$this->Tipo_documento->setDbValue($row['Tipo_documento']);
		$this->First_Name->setDbValue($row['First_Name']);
		$this->Last_Name->setDbValue($row['Last_Name']);
		$this->_Email->setDbValue($row['Email']);
		$this->Telefono_movil->setDbValue($row['Telefono_movil']);
		$this->Telefono_fijo->setDbValue($row['Telefono_fijo']);
		$this->Fecha_nacimiento->setDbValue($row['Fecha_nacimiento']);
		$this->Report_To->setDbValue($row['Report_To']);
		$this->Activated->setDbValue($row['Activated']);
		$this->Locked->setDbValue($row['Locked']);
		$this->token->setDbValue($row['token']);
		$this->acceso_app->setDbValue($row['acceso_app']);
		$this->observaciones->setDbValue($row['observaciones']);
		$this->fecha_ingreso->setDbValue($row['fecha_ingreso']);
		$this->Profile->setDbValue($row['Profile']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['id_user'] = NULL;
		$row['User_Level'] = NULL;
		$row['id_user_creator'] = NULL;
		$row['Username'] = NULL;
		$row['Password'] = NULL;
		$row['No_documento'] = NULL;
		$row['Tipo_documento'] = NULL;
		$row['First_Name'] = NULL;
		$row['Last_Name'] = NULL;
		$row['Email'] = NULL;
		$row['Telefono_movil'] = NULL;
		$row['Telefono_fijo'] = NULL;
		$row['Fecha_nacimiento'] = NULL;
		$row['Report_To'] = NULL;
		$row['Activated'] = NULL;
		$row['Locked'] = NULL;
		$row['token'] = NULL;
		$row['acceso_app'] = NULL;
		$row['observaciones'] = NULL;
		$row['fecha_ingreso'] = NULL;
		$row['Profile'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_user->DbValue = $row['id_user'];
		$this->User_Level->DbValue = $row['User_Level'];
		$this->id_user_creator->DbValue = $row['id_user_creator'];
		$this->Username->DbValue = $row['Username'];
		$this->Password->DbValue = $row['Password'];
		$this->No_documento->DbValue = $row['No_documento'];
		$this->Tipo_documento->DbValue = $row['Tipo_documento'];
		$this->First_Name->DbValue = $row['First_Name'];
		$this->Last_Name->DbValue = $row['Last_Name'];
		$this->_Email->DbValue = $row['Email'];
		$this->Telefono_movil->DbValue = $row['Telefono_movil'];
		$this->Telefono_fijo->DbValue = $row['Telefono_fijo'];
		$this->Fecha_nacimiento->DbValue = $row['Fecha_nacimiento'];
		$this->Report_To->DbValue = $row['Report_To'];
		$this->Activated->DbValue = $row['Activated'];
		$this->Locked->DbValue = $row['Locked'];
		$this->token->DbValue = $row['token'];
		$this->acceso_app->DbValue = $row['acceso_app'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->fecha_ingreso->DbValue = $row['fecha_ingreso'];
		$this->Profile->DbValue = $row['Profile'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_user")) <> "")
			$this->id_user->CurrentValue = $this->getKey("id_user"); // id_user
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
		// id_user
		// User_Level
		// id_user_creator
		// Username
		// Password
		// No_documento
		// Tipo_documento
		// First_Name
		// Last_Name
		// Email
		// Telefono_movil
		// Telefono_fijo
		// Fecha_nacimiento
		// Report_To
		// Activated
		// Locked
		// token
		// acceso_app
		// observaciones
		// fecha_ingreso
		// Profile

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_user
		$this->id_user->ViewValue = $this->id_user->CurrentValue;
		$this->id_user->ViewCustomAttributes = "";

		// User_Level
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->User_Level->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->User_Level->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
		$sWhereWrk = "";
		$this->User_Level->LookupFilters = array();
		$lookuptblfilter = "`userlevelid` > 0";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->User_Level, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->User_Level->ViewValue = $this->User_Level->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->User_Level->ViewValue = $this->User_Level->CurrentValue;
			}
		} else {
			$this->User_Level->ViewValue = NULL;
		}
		} else {
			$this->User_Level->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->User_Level->ViewCustomAttributes = "";

		// Username
		$this->Username->ViewValue = $this->Username->CurrentValue;
		$this->Username->ViewCustomAttributes = "";

		// Password
		$this->Password->ViewValue = $Language->Phrase("PasswordMask");
		$this->Password->ViewCustomAttributes = "";

		// No_documento
		$this->No_documento->ViewValue = $this->No_documento->CurrentValue;
		$this->No_documento->ViewCustomAttributes = "";

		// Tipo_documento
		if (strval($this->Tipo_documento->CurrentValue) <> "") {
			$this->Tipo_documento->ViewValue = $this->Tipo_documento->OptionCaption($this->Tipo_documento->CurrentValue);
		} else {
			$this->Tipo_documento->ViewValue = NULL;
		}
		$this->Tipo_documento->ViewCustomAttributes = "";

		// First_Name
		$this->First_Name->ViewValue = $this->First_Name->CurrentValue;
		$this->First_Name->ViewCustomAttributes = "";

		// Last_Name
		$this->Last_Name->ViewValue = $this->Last_Name->CurrentValue;
		$this->Last_Name->ViewCustomAttributes = "";

		// Email
		$this->_Email->ViewValue = $this->_Email->CurrentValue;
		$this->_Email->ViewCustomAttributes = "";

		// Telefono_movil
		$this->Telefono_movil->ViewValue = $this->Telefono_movil->CurrentValue;
		$this->Telefono_movil->ViewValue = ew_FormatNumber($this->Telefono_movil->ViewValue, 0, 0, 0, 0);
		$this->Telefono_movil->ViewCustomAttributes = "";

		// Telefono_fijo
		$this->Telefono_fijo->ViewValue = $this->Telefono_fijo->CurrentValue;
		$this->Telefono_fijo->ViewValue = ew_FormatNumber($this->Telefono_fijo->ViewValue, 0, -2, -2, -2);
		$this->Telefono_fijo->ViewCustomAttributes = "";

		// Fecha_nacimiento
		$this->Fecha_nacimiento->ViewValue = $this->Fecha_nacimiento->CurrentValue;
		$this->Fecha_nacimiento->ViewValue = ew_FormatDateTime($this->Fecha_nacimiento->ViewValue, 17);
		$this->Fecha_nacimiento->ViewCustomAttributes = "";

		// Activated
		if (strval($this->Activated->CurrentValue) <> "") {
			$this->Activated->ViewValue = $this->Activated->OptionCaption($this->Activated->CurrentValue);
		} else {
			$this->Activated->ViewValue = NULL;
		}
		$this->Activated->ViewCustomAttributes = "";

		// Locked
		if (strval($this->Locked->CurrentValue) <> "") {
			$this->Locked->ViewValue = $this->Locked->OptionCaption($this->Locked->CurrentValue);
		} else {
			$this->Locked->ViewValue = NULL;
		}
		$this->Locked->ViewCustomAttributes = "";

		// token
		$this->token->ViewValue = $this->token->CurrentValue;
		$this->token->ViewCustomAttributes = "";

		// acceso_app
		if (strval($this->acceso_app->CurrentValue) <> "") {
			$this->acceso_app->ViewValue = $this->acceso_app->OptionCaption($this->acceso_app->CurrentValue);
		} else {
			$this->acceso_app->ViewValue = NULL;
		}
		$this->acceso_app->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// fecha_ingreso
		$this->fecha_ingreso->ViewValue = $this->fecha_ingreso->CurrentValue;
		$this->fecha_ingreso->ViewValue = ew_FormatDateTime($this->fecha_ingreso->ViewValue, 11);
		$this->fecha_ingreso->ViewCustomAttributes = "";

			// id_user
			$this->id_user->LinkCustomAttributes = "";
			$this->id_user->HrefValue = "";
			$this->id_user->TooltipValue = "";

			// User_Level
			$this->User_Level->LinkCustomAttributes = "";
			$this->User_Level->HrefValue = "";
			$this->User_Level->TooltipValue = "";

			// Username
			$this->Username->LinkCustomAttributes = "";
			$this->Username->HrefValue = "";
			$this->Username->TooltipValue = "";

			// Password
			$this->Password->LinkCustomAttributes = "";
			$this->Password->HrefValue = "";
			$this->Password->TooltipValue = "";

			// No_documento
			$this->No_documento->LinkCustomAttributes = "";
			$this->No_documento->HrefValue = "";
			$this->No_documento->TooltipValue = "";

			// Tipo_documento
			$this->Tipo_documento->LinkCustomAttributes = "";
			$this->Tipo_documento->HrefValue = "";
			$this->Tipo_documento->TooltipValue = "";

			// First_Name
			$this->First_Name->LinkCustomAttributes = "";
			$this->First_Name->HrefValue = "";
			$this->First_Name->TooltipValue = "";

			// Last_Name
			$this->Last_Name->LinkCustomAttributes = "";
			$this->Last_Name->HrefValue = "";
			$this->Last_Name->TooltipValue = "";

			// Email
			$this->_Email->LinkCustomAttributes = "";
			$this->_Email->HrefValue = "";
			$this->_Email->TooltipValue = "";

			// Telefono_movil
			$this->Telefono_movil->LinkCustomAttributes = "";
			$this->Telefono_movil->HrefValue = "";
			$this->Telefono_movil->TooltipValue = "";

			// Telefono_fijo
			$this->Telefono_fijo->LinkCustomAttributes = "";
			$this->Telefono_fijo->HrefValue = "";
			$this->Telefono_fijo->TooltipValue = "";

			// Fecha_nacimiento
			$this->Fecha_nacimiento->LinkCustomAttributes = "";
			if (!ew_Empty($this->No_documento->CurrentValue)) {
				$this->Fecha_nacimiento->HrefValue = ((!empty($this->No_documento->ViewValue)) ? ew_RemoveHtml($this->No_documento->ViewValue) : $this->No_documento->CurrentValue); // Add prefix/suffix
				$this->Fecha_nacimiento->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->Fecha_nacimiento->HrefValue = ew_FullUrl($this->Fecha_nacimiento->HrefValue, "href");
			} else {
				$this->Fecha_nacimiento->HrefValue = "";
			}
			$this->Fecha_nacimiento->TooltipValue = "";

			// Activated
			$this->Activated->LinkCustomAttributes = "";
			$this->Activated->HrefValue = "";
			$this->Activated->TooltipValue = "";

			// Locked
			$this->Locked->LinkCustomAttributes = "";
			$this->Locked->HrefValue = "";
			$this->Locked->TooltipValue = "";

			// token
			$this->token->LinkCustomAttributes = "";
			$this->token->HrefValue = "";
			$this->token->TooltipValue = "";

			// acceso_app
			$this->acceso_app->LinkCustomAttributes = "";
			$this->acceso_app->HrefValue = "";
			$this->acceso_app->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_user
			$this->id_user->EditAttrs["class"] = "form-control";
			$this->id_user->EditCustomAttributes = "";
			$this->id_user->EditValue = $this->id_user->CurrentValue;
			$this->id_user->ViewCustomAttributes = "";

			// User_Level
			$this->User_Level->EditAttrs["class"] = "form-control";
			$this->User_Level->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->User_Level->EditValue = $Language->Phrase("PasswordMask");
			} else {
			if (trim(strval($this->User_Level->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->User_Level->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
			$sWhereWrk = "";
			$this->User_Level->LookupFilters = array();
			$lookuptblfilter = "`userlevelid` > 0";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->User_Level, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->User_Level->EditValue = $arwrk;
			}

			// Username
			$this->Username->EditAttrs["class"] = "form-control";
			$this->Username->EditCustomAttributes = "";
			$this->Username->EditValue = ew_HtmlEncode($this->Username->CurrentValue);
			$this->Username->PlaceHolder = ew_RemoveHtml($this->Username->FldCaption());

			// Password
			$this->Password->EditAttrs["class"] = "form-control ewPasswordStrength";
			$this->Password->EditCustomAttributes = "";
			$this->Password->EditValue = ew_HtmlEncode($this->Password->CurrentValue);
			$this->Password->PlaceHolder = ew_RemoveHtml($this->Password->FldCaption());

			// No_documento
			$this->No_documento->EditAttrs["class"] = "form-control";
			$this->No_documento->EditCustomAttributes = "";
			$this->No_documento->EditValue = ew_HtmlEncode($this->No_documento->CurrentValue);
			$this->No_documento->PlaceHolder = ew_RemoveHtml($this->No_documento->FldCaption());

			// Tipo_documento
			$this->Tipo_documento->EditAttrs["class"] = "form-control";
			$this->Tipo_documento->EditCustomAttributes = "";
			$this->Tipo_documento->EditValue = $this->Tipo_documento->Options(TRUE);

			// First_Name
			$this->First_Name->EditAttrs["class"] = "form-control";
			$this->First_Name->EditCustomAttributes = "";
			$this->First_Name->EditValue = ew_HtmlEncode($this->First_Name->CurrentValue);
			$this->First_Name->PlaceHolder = ew_RemoveHtml($this->First_Name->FldCaption());

			// Last_Name
			$this->Last_Name->EditAttrs["class"] = "form-control";
			$this->Last_Name->EditCustomAttributes = "";
			$this->Last_Name->EditValue = ew_HtmlEncode($this->Last_Name->CurrentValue);
			$this->Last_Name->PlaceHolder = ew_RemoveHtml($this->Last_Name->FldCaption());

			// Email
			$this->_Email->EditAttrs["class"] = "form-control";
			$this->_Email->EditCustomAttributes = "";
			$this->_Email->EditValue = ew_HtmlEncode($this->_Email->CurrentValue);
			$this->_Email->PlaceHolder = ew_RemoveHtml($this->_Email->FldCaption());

			// Telefono_movil
			$this->Telefono_movil->EditAttrs["class"] = "form-control";
			$this->Telefono_movil->EditCustomAttributes = "";
			$this->Telefono_movil->EditValue = ew_HtmlEncode($this->Telefono_movil->CurrentValue);
			$this->Telefono_movil->PlaceHolder = ew_RemoveHtml($this->Telefono_movil->FldCaption());

			// Telefono_fijo
			$this->Telefono_fijo->EditAttrs["class"] = "form-control";
			$this->Telefono_fijo->EditCustomAttributes = "";
			$this->Telefono_fijo->EditValue = ew_HtmlEncode($this->Telefono_fijo->CurrentValue);
			$this->Telefono_fijo->PlaceHolder = ew_RemoveHtml($this->Telefono_fijo->FldCaption());

			// Fecha_nacimiento
			$this->Fecha_nacimiento->EditAttrs["class"] = "form-control";
			$this->Fecha_nacimiento->EditCustomAttributes = "";
			$this->Fecha_nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Fecha_nacimiento->CurrentValue, 17));
			$this->Fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->Fecha_nacimiento->FldCaption());

			// Activated
			$this->Activated->EditCustomAttributes = "";
			$this->Activated->EditValue = $this->Activated->Options(FALSE);

			// Locked
			$this->Locked->EditCustomAttributes = "";
			$this->Locked->EditValue = $this->Locked->Options(FALSE);

			// token
			$this->token->EditAttrs["class"] = "form-control";
			$this->token->EditCustomAttributes = "";
			$this->token->EditValue = ew_HtmlEncode($this->token->CurrentValue);
			$this->token->PlaceHolder = ew_RemoveHtml($this->token->FldCaption());

			// acceso_app
			$this->acceso_app->EditCustomAttributes = "";
			$this->acceso_app->EditValue = $this->acceso_app->Options(FALSE);

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->CurrentValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// Edit refer script
			// id_user

			$this->id_user->LinkCustomAttributes = "";
			$this->id_user->HrefValue = "";

			// User_Level
			$this->User_Level->LinkCustomAttributes = "";
			$this->User_Level->HrefValue = "";

			// Username
			$this->Username->LinkCustomAttributes = "";
			$this->Username->HrefValue = "";

			// Password
			$this->Password->LinkCustomAttributes = "";
			$this->Password->HrefValue = "";

			// No_documento
			$this->No_documento->LinkCustomAttributes = "";
			$this->No_documento->HrefValue = "";

			// Tipo_documento
			$this->Tipo_documento->LinkCustomAttributes = "";
			$this->Tipo_documento->HrefValue = "";

			// First_Name
			$this->First_Name->LinkCustomAttributes = "";
			$this->First_Name->HrefValue = "";

			// Last_Name
			$this->Last_Name->LinkCustomAttributes = "";
			$this->Last_Name->HrefValue = "";

			// Email
			$this->_Email->LinkCustomAttributes = "";
			$this->_Email->HrefValue = "";

			// Telefono_movil
			$this->Telefono_movil->LinkCustomAttributes = "";
			$this->Telefono_movil->HrefValue = "";

			// Telefono_fijo
			$this->Telefono_fijo->LinkCustomAttributes = "";
			$this->Telefono_fijo->HrefValue = "";

			// Fecha_nacimiento
			$this->Fecha_nacimiento->LinkCustomAttributes = "";
			if (!ew_Empty($this->No_documento->CurrentValue)) {
				$this->Fecha_nacimiento->HrefValue = ((!empty($this->No_documento->EditValue)) ? ew_RemoveHtml($this->No_documento->EditValue) : $this->No_documento->CurrentValue); // Add prefix/suffix
				$this->Fecha_nacimiento->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->Fecha_nacimiento->HrefValue = ew_FullUrl($this->Fecha_nacimiento->HrefValue, "href");
			} else {
				$this->Fecha_nacimiento->HrefValue = "";
			}

			// Activated
			$this->Activated->LinkCustomAttributes = "";
			$this->Activated->HrefValue = "";

			// Locked
			$this->Locked->LinkCustomAttributes = "";
			$this->Locked->HrefValue = "";

			// token
			$this->token->LinkCustomAttributes = "";
			$this->token->HrefValue = "";

			// acceso_app
			$this->acceso_app->LinkCustomAttributes = "";
			$this->acceso_app->HrefValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
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
		if (!$this->Username->FldIsDetailKey && !is_null($this->Username->FormValue) && $this->Username->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Username->FldCaption(), $this->Username->ReqErrMsg));
		}
		if (!$this->Password->FldIsDetailKey && !is_null($this->Password->FormValue) && $this->Password->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Password->FldCaption(), $this->Password->ReqErrMsg));
		}
		if (!$this->No_documento->FldIsDetailKey && !is_null($this->No_documento->FormValue) && $this->No_documento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->No_documento->FldCaption(), $this->No_documento->ReqErrMsg));
		}
		if (!$this->Tipo_documento->FldIsDetailKey && !is_null($this->Tipo_documento->FormValue) && $this->Tipo_documento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Tipo_documento->FldCaption(), $this->Tipo_documento->ReqErrMsg));
		}
		if (!$this->First_Name->FldIsDetailKey && !is_null($this->First_Name->FormValue) && $this->First_Name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->First_Name->FldCaption(), $this->First_Name->ReqErrMsg));
		}
		if (!$this->Last_Name->FldIsDetailKey && !is_null($this->Last_Name->FormValue) && $this->Last_Name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Last_Name->FldCaption(), $this->Last_Name->ReqErrMsg));
		}
		if (!$this->Telefono_movil->FldIsDetailKey && !is_null($this->Telefono_movil->FormValue) && $this->Telefono_movil->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Telefono_movil->FldCaption(), $this->Telefono_movil->ReqErrMsg));
		}
		if (!ew_CheckShortEuroDate($this->Fecha_nacimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->Fecha_nacimiento->FldErrMsg());
		}
		if ($this->Activated->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Activated->FldCaption(), $this->Activated->ReqErrMsg));
		}
		if (!$this->token->FldIsDetailKey && !is_null($this->token->FormValue) && $this->token->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->token->FldCaption(), $this->token->ReqErrMsg));
		}
		if ($this->acceso_app->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->acceso_app->FldCaption(), $this->acceso_app->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("deudas", $DetailTblVar) && $GLOBALS["deudas"]->DetailEdit) {
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

			// User_Level
			if ($Security->CanAdmin()) { // System admin
			$this->User_Level->SetDbValueDef($rsnew, $this->User_Level->CurrentValue, NULL, $this->User_Level->ReadOnly);
			}

			// Username
			$this->Username->SetDbValueDef($rsnew, $this->Username->CurrentValue, "", $this->Username->ReadOnly);

			// Password
			$this->Password->SetDbValueDef($rsnew, $this->Password->CurrentValue, "", $this->Password->ReadOnly || (EW_ENCRYPTED_PASSWORD && $rs->fields('Password') == $this->Password->CurrentValue));

			// No_documento
			$this->No_documento->SetDbValueDef($rsnew, $this->No_documento->CurrentValue, NULL, $this->No_documento->ReadOnly);

			// Tipo_documento
			$this->Tipo_documento->SetDbValueDef($rsnew, $this->Tipo_documento->CurrentValue, NULL, $this->Tipo_documento->ReadOnly);

			// First_Name
			$this->First_Name->SetDbValueDef($rsnew, $this->First_Name->CurrentValue, NULL, $this->First_Name->ReadOnly);

			// Last_Name
			$this->Last_Name->SetDbValueDef($rsnew, $this->Last_Name->CurrentValue, NULL, $this->Last_Name->ReadOnly);

			// Email
			$this->_Email->SetDbValueDef($rsnew, $this->_Email->CurrentValue, NULL, $this->_Email->ReadOnly);

			// Telefono_movil
			$this->Telefono_movil->SetDbValueDef($rsnew, $this->Telefono_movil->CurrentValue, "", $this->Telefono_movil->ReadOnly);

			// Telefono_fijo
			$this->Telefono_fijo->SetDbValueDef($rsnew, $this->Telefono_fijo->CurrentValue, NULL, $this->Telefono_fijo->ReadOnly);

			// Fecha_nacimiento
			$this->Fecha_nacimiento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Fecha_nacimiento->CurrentValue, 17), NULL, $this->Fecha_nacimiento->ReadOnly);

			// Activated
			$this->Activated->SetDbValueDef($rsnew, $this->Activated->CurrentValue, 0, $this->Activated->ReadOnly);

			// Locked
			$this->Locked->SetDbValueDef($rsnew, $this->Locked->CurrentValue, NULL, $this->Locked->ReadOnly);

			// token
			$this->token->SetDbValueDef($rsnew, $this->token->CurrentValue, "", $this->token->ReadOnly);

			// acceso_app
			$this->acceso_app->SetDbValueDef($rsnew, $this->acceso_app->CurrentValue, 0, $this->acceso_app->ReadOnly);

			// observaciones
			$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, NULL, $this->observaciones->ReadOnly);

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
					if (in_array("deudas", $DetailTblVar) && $GLOBALS["deudas"]->DetailEdit) {
						if (!isset($GLOBALS["deudas_grid"])) $GLOBALS["deudas_grid"] = new cdeudas_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "deudas"); // Load user level of detail table
						$EditRow = $GLOBALS["deudas_grid"]->GridUpdate();
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
				if ($GLOBALS["deudas_grid"]->DetailEdit) {
					$GLOBALS["deudas_grid"]->CurrentMode = "edit";
					$GLOBALS["deudas_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["deudas_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["deudas_grid"]->setStartRecordNumber(1);
					$GLOBALS["deudas_grid"]->id_agente->FldIsDetailKey = TRUE;
					$GLOBALS["deudas_grid"]->id_agente->CurrentValue = $this->id_user->CurrentValue;
					$GLOBALS["deudas_grid"]->id_agente->setSessionValue($GLOBALS["deudas_grid"]->id_agente->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("userslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_User_Level":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `userlevelid` AS `LinkFld`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
			$sWhereWrk = "";
			$this->User_Level->LookupFilters = array();
			$lookuptblfilter = "`userlevelid` > 0";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`userlevelid` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->User_Level, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($users_edit)) $users_edit = new cusers_edit();

// Page init
$users_edit->Page_Init();

// Page main
$users_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$users_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fusersedit = new ew_Form("fusersedit", "edit");

// Validate form
fusersedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Username");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->Username->FldCaption(), $users->Username->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->Password->FldCaption(), $users->Password->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Password");
			if (elm && $(elm).hasClass("ewPasswordStrength") && !$(elm).data("validated"))
				return this.OnError(elm, ewLanguage.Phrase("PasswordTooSimple"));
			elm = this.GetElements("x" + infix + "_No_documento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->No_documento->FldCaption(), $users->No_documento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Tipo_documento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->Tipo_documento->FldCaption(), $users->Tipo_documento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_First_Name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->First_Name->FldCaption(), $users->First_Name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Last_Name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->Last_Name->FldCaption(), $users->Last_Name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Telefono_movil");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->Telefono_movil->FldCaption(), $users->Telefono_movil->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Fecha_nacimiento");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($users->Fecha_nacimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Activated");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->Activated->FldCaption(), $users->Activated->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_token");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->token->FldCaption(), $users->token->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_acceso_app");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->acceso_app->FldCaption(), $users->acceso_app->ReqErrMsg)) ?>");

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
fusersedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fusersedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fusersedit.Lists["x_User_Level"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};
fusersedit.Lists["x_User_Level"].Data = "<?php echo $users_edit->User_Level->LookupFilterQuery(FALSE, "edit") ?>";
fusersedit.Lists["x_Tipo_documento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersedit.Lists["x_Tipo_documento"].Options = <?php echo json_encode($users_edit->Tipo_documento->Options()) ?>;
fusersedit.Lists["x_Activated"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersedit.Lists["x_Activated"].Options = <?php echo json_encode($users_edit->Activated->Options()) ?>;
fusersedit.Lists["x_Locked"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersedit.Lists["x_Locked"].Options = <?php echo json_encode($users_edit->Locked->Options()) ?>;
fusersedit.Lists["x_acceso_app"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersedit.Lists["x_acceso_app"].Options = <?php echo json_encode($users_edit->acceso_app->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $users_edit->ShowPageHeader(); ?>
<?php
$users_edit->ShowMessage();
?>
<form name="fusersedit" id="fusersedit" class="<?php echo $users_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($users_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $users_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="users">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($users_edit->IsModal) ?>">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($users->id_user->Visible) { // id_user ?>
	<div id="r_id_user" class="form-group">
		<label id="elh_users_id_user" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->id_user->FldCaption() ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->id_user->CellAttributes() ?>>
<span id="el_users_id_user">
<span<?php echo $users->id_user->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $users->id_user->EditValue ?></p></span>
</span>
<input type="hidden" data-table="users" data-field="x_id_user" name="x_id_user" id="x_id_user" value="<?php echo ew_HtmlEncode($users->id_user->CurrentValue) ?>">
<?php echo $users->id_user->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->User_Level->Visible) { // User_Level ?>
	<div id="r_User_Level" class="form-group">
		<label id="elh_users_User_Level" for="x_User_Level" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->User_Level->FldCaption() ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->User_Level->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el_users_User_Level">
<p class="form-control-static"><?php echo $users->User_Level->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el_users_User_Level">
<select data-table="users" data-field="x_User_Level" data-value-separator="<?php echo $users->User_Level->DisplayValueSeparatorAttribute() ?>" id="x_User_Level" name="x_User_Level"<?php echo $users->User_Level->EditAttributes() ?>>
<?php echo $users->User_Level->SelectOptionListHtml("x_User_Level") ?>
</select>
</span>
<?php } ?>
<?php echo $users->User_Level->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Username->Visible) { // Username ?>
	<div id="r_Username" class="form-group">
		<label id="elh_users_Username" for="x_Username" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->Username->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->Username->CellAttributes() ?>>
<span id="el_users_Username">
<input type="text" data-table="users" data-field="x_Username" name="x_Username" id="x_Username" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->Username->getPlaceHolder()) ?>" value="<?php echo $users->Username->EditValue ?>"<?php echo $users->Username->EditAttributes() ?>>
</span>
<?php echo $users->Username->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Password->Visible) { // Password ?>
	<div id="r_Password" class="form-group">
		<label id="elh_users_Password" for="x_Password" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->Password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->Password->CellAttributes() ?>>
<span id="el_users_Password">
<input type="password" data-password-strength="pst_Password" data-table="users" data-field="x_Password" name="x_Password" id="x_Password" value="<?php echo $users->Password->EditValue ?>" size="30" maxlength="64" placeholder="<?php echo ew_HtmlEncode($users->Password->getPlaceHolder()) ?>"<?php echo $users->Password->EditAttributes() ?>>
<div class="progress ewPasswordStrengthBar" id="pst_Password" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</span>
<?php echo $users->Password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->No_documento->Visible) { // No_documento ?>
	<div id="r_No_documento" class="form-group">
		<label id="elh_users_No_documento" for="x_No_documento" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->No_documento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->No_documento->CellAttributes() ?>>
<span id="el_users_No_documento">
<input type="text" data-table="users" data-field="x_No_documento" name="x_No_documento" id="x_No_documento" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($users->No_documento->getPlaceHolder()) ?>" value="<?php echo $users->No_documento->EditValue ?>"<?php echo $users->No_documento->EditAttributes() ?>>
</span>
<?php echo $users->No_documento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Tipo_documento->Visible) { // Tipo_documento ?>
	<div id="r_Tipo_documento" class="form-group">
		<label id="elh_users_Tipo_documento" for="x_Tipo_documento" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->Tipo_documento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->Tipo_documento->CellAttributes() ?>>
<span id="el_users_Tipo_documento">
<select data-table="users" data-field="x_Tipo_documento" data-value-separator="<?php echo $users->Tipo_documento->DisplayValueSeparatorAttribute() ?>" id="x_Tipo_documento" name="x_Tipo_documento"<?php echo $users->Tipo_documento->EditAttributes() ?>>
<?php echo $users->Tipo_documento->SelectOptionListHtml("x_Tipo_documento") ?>
</select>
</span>
<?php echo $users->Tipo_documento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->First_Name->Visible) { // First_Name ?>
	<div id="r_First_Name" class="form-group">
		<label id="elh_users_First_Name" for="x_First_Name" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->First_Name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->First_Name->CellAttributes() ?>>
<span id="el_users_First_Name">
<input type="text" data-table="users" data-field="x_First_Name" name="x_First_Name" id="x_First_Name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->First_Name->getPlaceHolder()) ?>" value="<?php echo $users->First_Name->EditValue ?>"<?php echo $users->First_Name->EditAttributes() ?>>
</span>
<?php echo $users->First_Name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Last_Name->Visible) { // Last_Name ?>
	<div id="r_Last_Name" class="form-group">
		<label id="elh_users_Last_Name" for="x_Last_Name" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->Last_Name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->Last_Name->CellAttributes() ?>>
<span id="el_users_Last_Name">
<input type="text" data-table="users" data-field="x_Last_Name" name="x_Last_Name" id="x_Last_Name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->Last_Name->getPlaceHolder()) ?>" value="<?php echo $users->Last_Name->EditValue ?>"<?php echo $users->Last_Name->EditAttributes() ?>>
</span>
<?php echo $users->Last_Name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->_Email->Visible) { // Email ?>
	<div id="r__Email" class="form-group">
		<label id="elh_users__Email" for="x__Email" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->_Email->FldCaption() ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->_Email->CellAttributes() ?>>
<span id="el_users__Email">
<input type="text" data-table="users" data-field="x__Email" name="x__Email" id="x__Email" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($users->_Email->getPlaceHolder()) ?>" value="<?php echo $users->_Email->EditValue ?>"<?php echo $users->_Email->EditAttributes() ?>>
</span>
<?php echo $users->_Email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Telefono_movil->Visible) { // Telefono_movil ?>
	<div id="r_Telefono_movil" class="form-group">
		<label id="elh_users_Telefono_movil" for="x_Telefono_movil" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->Telefono_movil->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->Telefono_movil->CellAttributes() ?>>
<span id="el_users_Telefono_movil">
<input type="text" data-table="users" data-field="x_Telefono_movil" name="x_Telefono_movil" id="x_Telefono_movil" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($users->Telefono_movil->getPlaceHolder()) ?>" value="<?php echo $users->Telefono_movil->EditValue ?>"<?php echo $users->Telefono_movil->EditAttributes() ?>>
</span>
<?php echo $users->Telefono_movil->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Telefono_fijo->Visible) { // Telefono_fijo ?>
	<div id="r_Telefono_fijo" class="form-group">
		<label id="elh_users_Telefono_fijo" for="x_Telefono_fijo" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->Telefono_fijo->FldCaption() ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->Telefono_fijo->CellAttributes() ?>>
<span id="el_users_Telefono_fijo">
<input type="text" data-table="users" data-field="x_Telefono_fijo" name="x_Telefono_fijo" id="x_Telefono_fijo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($users->Telefono_fijo->getPlaceHolder()) ?>" value="<?php echo $users->Telefono_fijo->EditValue ?>"<?php echo $users->Telefono_fijo->EditAttributes() ?>>
</span>
<?php echo $users->Telefono_fijo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Fecha_nacimiento->Visible) { // Fecha_nacimiento ?>
	<div id="r_Fecha_nacimiento" class="form-group">
		<label id="elh_users_Fecha_nacimiento" for="x_Fecha_nacimiento" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->Fecha_nacimiento->FldCaption() ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->Fecha_nacimiento->CellAttributes() ?>>
<span id="el_users_Fecha_nacimiento">
<input type="text" data-table="users" data-field="x_Fecha_nacimiento" data-format="17" name="x_Fecha_nacimiento" id="x_Fecha_nacimiento" placeholder="<?php echo ew_HtmlEncode($users->Fecha_nacimiento->getPlaceHolder()) ?>" value="<?php echo $users->Fecha_nacimiento->EditValue ?>"<?php echo $users->Fecha_nacimiento->EditAttributes() ?>>
<?php if (!$users->Fecha_nacimiento->ReadOnly && !$users->Fecha_nacimiento->Disabled && !isset($users->Fecha_nacimiento->EditAttrs["readonly"]) && !isset($users->Fecha_nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fusersedit", "x_Fecha_nacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":17});
</script>
<?php } ?>
</span>
<?php echo $users->Fecha_nacimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Activated->Visible) { // Activated ?>
	<div id="r_Activated" class="form-group">
		<label id="elh_users_Activated" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->Activated->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->Activated->CellAttributes() ?>>
<span id="el_users_Activated">
<div id="tp_x_Activated" class="ewTemplate"><input type="radio" data-table="users" data-field="x_Activated" data-value-separator="<?php echo $users->Activated->DisplayValueSeparatorAttribute() ?>" name="x_Activated" id="x_Activated" value="{value}"<?php echo $users->Activated->EditAttributes() ?>></div>
<div id="dsl_x_Activated" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->Activated->RadioButtonListHtml(FALSE, "x_Activated") ?>
</div></div>
</span>
<?php echo $users->Activated->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Locked->Visible) { // Locked ?>
	<div id="r_Locked" class="form-group">
		<label id="elh_users_Locked" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->Locked->FldCaption() ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->Locked->CellAttributes() ?>>
<span id="el_users_Locked">
<div id="tp_x_Locked" class="ewTemplate"><input type="radio" data-table="users" data-field="x_Locked" data-value-separator="<?php echo $users->Locked->DisplayValueSeparatorAttribute() ?>" name="x_Locked" id="x_Locked" value="{value}"<?php echo $users->Locked->EditAttributes() ?>></div>
<div id="dsl_x_Locked" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->Locked->RadioButtonListHtml(FALSE, "x_Locked") ?>
</div></div>
</span>
<?php echo $users->Locked->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->token->Visible) { // token ?>
	<div id="r_token" class="form-group">
		<label id="elh_users_token" for="x_token" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->token->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->token->CellAttributes() ?>>
<span id="el_users_token">
<input type="text" data-table="users" data-field="x_token" name="x_token" id="x_token" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($users->token->getPlaceHolder()) ?>" value="<?php echo $users->token->EditValue ?>"<?php echo $users->token->EditAttributes() ?>>
</span>
<?php echo $users->token->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->acceso_app->Visible) { // acceso_app ?>
	<div id="r_acceso_app" class="form-group">
		<label id="elh_users_acceso_app" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->acceso_app->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->acceso_app->CellAttributes() ?>>
<span id="el_users_acceso_app">
<div id="tp_x_acceso_app" class="ewTemplate"><input type="radio" data-table="users" data-field="x_acceso_app" data-value-separator="<?php echo $users->acceso_app->DisplayValueSeparatorAttribute() ?>" name="x_acceso_app" id="x_acceso_app" value="{value}"<?php echo $users->acceso_app->EditAttributes() ?>></div>
<div id="dsl_x_acceso_app" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->acceso_app->RadioButtonListHtml(FALSE, "x_acceso_app") ?>
</div></div>
</span>
<?php echo $users->acceso_app->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label id="elh_users_observaciones" for="x_observaciones" class="<?php echo $users_edit->LeftColumnClass ?>"><?php echo $users->observaciones->FldCaption() ?></label>
		<div class="<?php echo $users_edit->RightColumnClass ?>"><div<?php echo $users->observaciones->CellAttributes() ?>>
<span id="el_users_observaciones">
<textarea data-table="users" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($users->observaciones->getPlaceHolder()) ?>"<?php echo $users->observaciones->EditAttributes() ?>><?php echo $users->observaciones->EditValue ?></textarea>
</span>
<?php echo $users->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php
	if (in_array("deudas", explode(",", $users->getCurrentDetailTable())) && $deudas->DetailEdit) {
?>
<?php if ($users->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("deudas", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "deudasgrid.php" ?>
<?php } ?>
<?php if (!$users_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $users_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $users_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fusersedit.Init();
</script>
<?php
$users_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$users_edit->Page_Terminate();
?>
