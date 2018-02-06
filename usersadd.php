<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$users_add = NULL; // Initialize page object first

class cusers_add extends cusers {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
			if (@$_GET["id_user"] != "") {
				$this->id_user->setQueryStringValue($_GET["id_user"]);
				$this->setKey("id_user", $this->id_user->CurrentValue); // Set up key
			} else {
				$this->setKey("id_user", ""); // Clear key
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
					$this->Page_Terminate("userslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "userslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "usersview.php")
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
		$this->id_user->CurrentValue = NULL;
		$this->id_user->OldValue = $this->id_user->CurrentValue;
		$this->User_Level->CurrentValue = NULL;
		$this->User_Level->OldValue = $this->User_Level->CurrentValue;
		$this->id_user_creator->CurrentValue = 0;
		$this->Username->CurrentValue = NULL;
		$this->Username->OldValue = $this->Username->CurrentValue;
		$this->Password->CurrentValue = NULL;
		$this->Password->OldValue = $this->Password->CurrentValue;
		$this->No_documento->CurrentValue = NULL;
		$this->No_documento->OldValue = $this->No_documento->CurrentValue;
		$this->Tipo_documento->CurrentValue = NULL;
		$this->Tipo_documento->OldValue = $this->Tipo_documento->CurrentValue;
		$this->First_Name->CurrentValue = NULL;
		$this->First_Name->OldValue = $this->First_Name->CurrentValue;
		$this->Last_Name->CurrentValue = NULL;
		$this->Last_Name->OldValue = $this->Last_Name->CurrentValue;
		$this->_Email->CurrentValue = NULL;
		$this->_Email->OldValue = $this->_Email->CurrentValue;
		$this->Telefono_movil->CurrentValue = NULL;
		$this->Telefono_movil->OldValue = $this->Telefono_movil->CurrentValue;
		$this->Telefono_fijo->CurrentValue = NULL;
		$this->Telefono_fijo->OldValue = $this->Telefono_fijo->CurrentValue;
		$this->Fecha_nacimiento->CurrentValue = NULL;
		$this->Fecha_nacimiento->OldValue = $this->Fecha_nacimiento->CurrentValue;
		$this->Report_To->CurrentValue = NULL;
		$this->Report_To->OldValue = $this->Report_To->CurrentValue;
		$this->Activated->CurrentValue = 1;
		$this->Locked->CurrentValue = 0;
		$this->token->CurrentValue = "0";
		$this->acceso_app->CurrentValue = 1;
		$this->observaciones->CurrentValue = NULL;
		$this->observaciones->OldValue = $this->observaciones->CurrentValue;
		$this->fecha_ingreso->CurrentValue = NULL;
		$this->fecha_ingreso->OldValue = $this->fecha_ingreso->CurrentValue;
		$this->Profile->CurrentValue = NULL;
		$this->Profile->OldValue = $this->Profile->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
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
		$this->LoadDefaultValues();
		$row = array();
		$row['id_user'] = $this->id_user->CurrentValue;
		$row['User_Level'] = $this->User_Level->CurrentValue;
		$row['id_user_creator'] = $this->id_user_creator->CurrentValue;
		$row['Username'] = $this->Username->CurrentValue;
		$row['Password'] = $this->Password->CurrentValue;
		$row['No_documento'] = $this->No_documento->CurrentValue;
		$row['Tipo_documento'] = $this->Tipo_documento->CurrentValue;
		$row['First_Name'] = $this->First_Name->CurrentValue;
		$row['Last_Name'] = $this->Last_Name->CurrentValue;
		$row['Email'] = $this->_Email->CurrentValue;
		$row['Telefono_movil'] = $this->Telefono_movil->CurrentValue;
		$row['Telefono_fijo'] = $this->Telefono_fijo->CurrentValue;
		$row['Fecha_nacimiento'] = $this->Fecha_nacimiento->CurrentValue;
		$row['Report_To'] = $this->Report_To->CurrentValue;
		$row['Activated'] = $this->Activated->CurrentValue;
		$row['Locked'] = $this->Locked->CurrentValue;
		$row['token'] = $this->token->CurrentValue;
		$row['acceso_app'] = $this->acceso_app->CurrentValue;
		$row['observaciones'] = $this->observaciones->CurrentValue;
		$row['fecha_ingreso'] = $this->fecha_ingreso->CurrentValue;
		$row['Profile'] = $this->Profile->CurrentValue;
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
		$this->Tipo_documento->ViewValue = $this->Tipo_documento->CurrentValue;
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

		// Locked
		if (strval($this->Locked->CurrentValue) <> "") {
			$this->Locked->ViewValue = $this->Locked->OptionCaption($this->Locked->CurrentValue);
		} else {
			$this->Locked->ViewValue = NULL;
		}
		$this->Locked->ViewCustomAttributes = "";

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
		$this->fecha_ingreso->ViewValue = ew_FormatDateTime($this->fecha_ingreso->ViewValue, 0);
		$this->fecha_ingreso->ViewCustomAttributes = "";

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

			// acceso_app
			$this->acceso_app->LinkCustomAttributes = "";
			$this->acceso_app->HrefValue = "";
			$this->acceso_app->TooltipValue = "";

			// observaciones
			$this->observaciones->LinkCustomAttributes = "";
			$this->observaciones->HrefValue = "";
			$this->observaciones->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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
			$this->Tipo_documento->EditValue = ew_HtmlEncode($this->Tipo_documento->CurrentValue);
			$this->Tipo_documento->PlaceHolder = ew_RemoveHtml($this->Tipo_documento->FldCaption());

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

			// acceso_app
			$this->acceso_app->EditCustomAttributes = "";
			$this->acceso_app->EditValue = $this->acceso_app->Options(FALSE);

			// observaciones
			$this->observaciones->EditAttrs["class"] = "form-control";
			$this->observaciones->EditCustomAttributes = "";
			$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->CurrentValue);
			$this->observaciones->PlaceHolder = ew_RemoveHtml($this->observaciones->FldCaption());

			// Add refer script
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
		if (!$this->Telefono_movil->FldIsDetailKey && !is_null($this->Telefono_movil->FormValue) && $this->Telefono_movil->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Telefono_movil->FldCaption(), $this->Telefono_movil->ReqErrMsg));
		}
		if (!ew_CheckShortEuroDate($this->Fecha_nacimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->Fecha_nacimiento->FldErrMsg());
		}
		if ($this->acceso_app->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->acceso_app->FldCaption(), $this->acceso_app->ReqErrMsg));
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

		// User_Level
		if ($Security->CanAdmin()) { // System admin
		$this->User_Level->SetDbValueDef($rsnew, $this->User_Level->CurrentValue, NULL, FALSE);
		}

		// Username
		$this->Username->SetDbValueDef($rsnew, $this->Username->CurrentValue, "", FALSE);

		// Password
		$this->Password->SetDbValueDef($rsnew, $this->Password->CurrentValue, "", FALSE);

		// No_documento
		$this->No_documento->SetDbValueDef($rsnew, $this->No_documento->CurrentValue, NULL, FALSE);

		// Tipo_documento
		$this->Tipo_documento->SetDbValueDef($rsnew, $this->Tipo_documento->CurrentValue, NULL, FALSE);

		// First_Name
		$this->First_Name->SetDbValueDef($rsnew, $this->First_Name->CurrentValue, NULL, FALSE);

		// Last_Name
		$this->Last_Name->SetDbValueDef($rsnew, $this->Last_Name->CurrentValue, NULL, FALSE);

		// Email
		$this->_Email->SetDbValueDef($rsnew, $this->_Email->CurrentValue, NULL, FALSE);

		// Telefono_movil
		$this->Telefono_movil->SetDbValueDef($rsnew, $this->Telefono_movil->CurrentValue, "", FALSE);

		// Telefono_fijo
		$this->Telefono_fijo->SetDbValueDef($rsnew, $this->Telefono_fijo->CurrentValue, NULL, FALSE);

		// Fecha_nacimiento
		$this->Fecha_nacimiento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Fecha_nacimiento->CurrentValue, 17), NULL, FALSE);

		// acceso_app
		$this->acceso_app->SetDbValueDef($rsnew, $this->acceso_app->CurrentValue, 0, strval($this->acceso_app->CurrentValue) == "");

		// observaciones
		$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("userslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($users_add)) $users_add = new cusers_add();

// Page init
$users_add->Page_Init();

// Page main
$users_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$users_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fusersadd = new ew_Form("fusersadd", "add");

// Validate form
fusersadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Telefono_movil");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $users->Telefono_movil->FldCaption(), $users->Telefono_movil->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Fecha_nacimiento");
			if (elm && !ew_CheckShortEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($users->Fecha_nacimiento->FldErrMsg()) ?>");
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
fusersadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fusersadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fusersadd.Lists["x_User_Level"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};
fusersadd.Lists["x_User_Level"].Data = "<?php echo $users_add->User_Level->LookupFilterQuery(FALSE, "add") ?>";
fusersadd.Lists["x_acceso_app"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersadd.Lists["x_acceso_app"].Options = <?php echo json_encode($users_add->acceso_app->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $users_add->ShowPageHeader(); ?>
<?php
$users_add->ShowMessage();
?>
<form name="fusersadd" id="fusersadd" class="<?php echo $users_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($users_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $users_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="users">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($users_add->IsModal) ?>">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($users->User_Level->Visible) { // User_Level ?>
	<div id="r_User_Level" class="form-group">
		<label id="elh_users_User_Level" for="x_User_Level" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->User_Level->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->User_Level->CellAttributes() ?>>
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
		<label id="elh_users_Username" for="x_Username" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->Username->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->Username->CellAttributes() ?>>
<span id="el_users_Username">
<input type="text" data-table="users" data-field="x_Username" name="x_Username" id="x_Username" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->Username->getPlaceHolder()) ?>" value="<?php echo $users->Username->EditValue ?>"<?php echo $users->Username->EditAttributes() ?>>
</span>
<?php echo $users->Username->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Password->Visible) { // Password ?>
	<div id="r_Password" class="form-group">
		<label id="elh_users_Password" for="x_Password" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->Password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->Password->CellAttributes() ?>>
<span id="el_users_Password">
<input type="password" data-password-strength="pst_Password" data-table="users" data-field="x_Password" name="x_Password" id="x_Password" size="30" maxlength="64" placeholder="<?php echo ew_HtmlEncode($users->Password->getPlaceHolder()) ?>"<?php echo $users->Password->EditAttributes() ?>>
<div class="progress ewPasswordStrengthBar" id="pst_Password" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</span>
<?php echo $users->Password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->No_documento->Visible) { // No_documento ?>
	<div id="r_No_documento" class="form-group">
		<label id="elh_users_No_documento" for="x_No_documento" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->No_documento->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->No_documento->CellAttributes() ?>>
<span id="el_users_No_documento">
<input type="text" data-table="users" data-field="x_No_documento" name="x_No_documento" id="x_No_documento" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($users->No_documento->getPlaceHolder()) ?>" value="<?php echo $users->No_documento->EditValue ?>"<?php echo $users->No_documento->EditAttributes() ?>>
</span>
<?php echo $users->No_documento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Tipo_documento->Visible) { // Tipo_documento ?>
	<div id="r_Tipo_documento" class="form-group">
		<label id="elh_users_Tipo_documento" for="x_Tipo_documento" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->Tipo_documento->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->Tipo_documento->CellAttributes() ?>>
<span id="el_users_Tipo_documento">
<input type="text" data-table="users" data-field="x_Tipo_documento" name="x_Tipo_documento" id="x_Tipo_documento" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($users->Tipo_documento->getPlaceHolder()) ?>" value="<?php echo $users->Tipo_documento->EditValue ?>"<?php echo $users->Tipo_documento->EditAttributes() ?>>
</span>
<?php echo $users->Tipo_documento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->First_Name->Visible) { // First_Name ?>
	<div id="r_First_Name" class="form-group">
		<label id="elh_users_First_Name" for="x_First_Name" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->First_Name->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->First_Name->CellAttributes() ?>>
<span id="el_users_First_Name">
<input type="text" data-table="users" data-field="x_First_Name" name="x_First_Name" id="x_First_Name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->First_Name->getPlaceHolder()) ?>" value="<?php echo $users->First_Name->EditValue ?>"<?php echo $users->First_Name->EditAttributes() ?>>
</span>
<?php echo $users->First_Name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Last_Name->Visible) { // Last_Name ?>
	<div id="r_Last_Name" class="form-group">
		<label id="elh_users_Last_Name" for="x_Last_Name" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->Last_Name->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->Last_Name->CellAttributes() ?>>
<span id="el_users_Last_Name">
<input type="text" data-table="users" data-field="x_Last_Name" name="x_Last_Name" id="x_Last_Name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->Last_Name->getPlaceHolder()) ?>" value="<?php echo $users->Last_Name->EditValue ?>"<?php echo $users->Last_Name->EditAttributes() ?>>
</span>
<?php echo $users->Last_Name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->_Email->Visible) { // Email ?>
	<div id="r__Email" class="form-group">
		<label id="elh_users__Email" for="x__Email" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->_Email->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->_Email->CellAttributes() ?>>
<span id="el_users__Email">
<input type="text" data-table="users" data-field="x__Email" name="x__Email" id="x__Email" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($users->_Email->getPlaceHolder()) ?>" value="<?php echo $users->_Email->EditValue ?>"<?php echo $users->_Email->EditAttributes() ?>>
</span>
<?php echo $users->_Email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Telefono_movil->Visible) { // Telefono_movil ?>
	<div id="r_Telefono_movil" class="form-group">
		<label id="elh_users_Telefono_movil" for="x_Telefono_movil" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->Telefono_movil->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->Telefono_movil->CellAttributes() ?>>
<span id="el_users_Telefono_movil">
<input type="text" data-table="users" data-field="x_Telefono_movil" name="x_Telefono_movil" id="x_Telefono_movil" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($users->Telefono_movil->getPlaceHolder()) ?>" value="<?php echo $users->Telefono_movil->EditValue ?>"<?php echo $users->Telefono_movil->EditAttributes() ?>>
</span>
<?php echo $users->Telefono_movil->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Telefono_fijo->Visible) { // Telefono_fijo ?>
	<div id="r_Telefono_fijo" class="form-group">
		<label id="elh_users_Telefono_fijo" for="x_Telefono_fijo" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->Telefono_fijo->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->Telefono_fijo->CellAttributes() ?>>
<span id="el_users_Telefono_fijo">
<input type="text" data-table="users" data-field="x_Telefono_fijo" name="x_Telefono_fijo" id="x_Telefono_fijo" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($users->Telefono_fijo->getPlaceHolder()) ?>" value="<?php echo $users->Telefono_fijo->EditValue ?>"<?php echo $users->Telefono_fijo->EditAttributes() ?>>
</span>
<?php echo $users->Telefono_fijo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->Fecha_nacimiento->Visible) { // Fecha_nacimiento ?>
	<div id="r_Fecha_nacimiento" class="form-group">
		<label id="elh_users_Fecha_nacimiento" for="x_Fecha_nacimiento" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->Fecha_nacimiento->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->Fecha_nacimiento->CellAttributes() ?>>
<span id="el_users_Fecha_nacimiento">
<input type="text" data-table="users" data-field="x_Fecha_nacimiento" data-format="17" name="x_Fecha_nacimiento" id="x_Fecha_nacimiento" placeholder="<?php echo ew_HtmlEncode($users->Fecha_nacimiento->getPlaceHolder()) ?>" value="<?php echo $users->Fecha_nacimiento->EditValue ?>"<?php echo $users->Fecha_nacimiento->EditAttributes() ?>>
<?php if (!$users->Fecha_nacimiento->ReadOnly && !$users->Fecha_nacimiento->Disabled && !isset($users->Fecha_nacimiento->EditAttrs["readonly"]) && !isset($users->Fecha_nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fusersadd", "x_Fecha_nacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":17});
</script>
<?php } ?>
</span>
<?php echo $users->Fecha_nacimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($users->acceso_app->Visible) { // acceso_app ?>
	<div id="r_acceso_app" class="form-group">
		<label id="elh_users_acceso_app" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->acceso_app->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->acceso_app->CellAttributes() ?>>
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
		<label id="elh_users_observaciones" for="x_observaciones" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->observaciones->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->observaciones->CellAttributes() ?>>
<span id="el_users_observaciones">
<textarea data-table="users" data-field="x_observaciones" name="x_observaciones" id="x_observaciones" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($users->observaciones->getPlaceHolder()) ?>"<?php echo $users->observaciones->EditAttributes() ?>><?php echo $users->observaciones->EditValue ?></textarea>
</span>
<?php echo $users->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$users_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $users_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $users_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fusersadd.Init();
</script>
<?php
$users_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$users_add->Page_Terminate();
?>
