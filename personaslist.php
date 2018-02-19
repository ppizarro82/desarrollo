<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "personasinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "direccionesgridcls.php" ?>
<?php include_once "telefonosgridcls.php" ?>
<?php include_once "emailsgridcls.php" ?>
<?php include_once "vehiculosgridcls.php" ?>
<?php include_once "deuda_personagridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$personas_list = NULL; // Initialize page object first

class cpersonas_list extends cpersonas {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'personas';

	// Page object name
	var $PageObjName = 'personas_list';

	// Grid form hidden field names
	var $FormName = 'fpersonaslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "personasadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "personasdelete.php";
		$this->MultiUpdateUrl = "personasupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fpersonaslistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Get export parameters

		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} elseif (@$_GET["cmd"] == "json") {
			$this->Export = $_GET["cmd"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->Id->SetVisibility();
		$this->Id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->tipo_documento->SetVisibility();
		$this->no_documento->SetVisibility();
		$this->nombres->SetVisibility();
		$this->paterno->SetVisibility();
		$this->materno->SetVisibility();
		$this->fecha_nacimiento->SetVisibility();
		$this->fecha_registro->SetVisibility();
		$this->imagen->SetVisibility();
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

			// Process auto fill for detail table 'direcciones'
			if (@$_POST["grid"] == "fdireccionesgrid") {
				if (!isset($GLOBALS["direcciones_grid"])) $GLOBALS["direcciones_grid"] = new cdirecciones_grid;
				$GLOBALS["direcciones_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 'telefonos'
			if (@$_POST["grid"] == "ftelefonosgrid") {
				if (!isset($GLOBALS["telefonos_grid"])) $GLOBALS["telefonos_grid"] = new ctelefonos_grid;
				$GLOBALS["telefonos_grid"]->Page_Init();
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

			// Process auto fill for detail table 'vehiculos'
			if (@$_POST["grid"] == "fvehiculosgrid") {
				if (!isset($GLOBALS["vehiculos_grid"])) $GLOBALS["vehiculos_grid"] = new cvehiculos_grid;
				$GLOBALS["vehiculos_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 50;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $AutoHidePageSizeSelector = EW_AUTO_HIDE_PAGE_SIZE_SELECTOR;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $direcciones_Count;
	var $telefonos_Count;
	var $emails_Count;
	var $vehiculos_Count;
	var $deuda_persona_Count;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security, $EW_EXPORT;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Process filter list
			$this->ProcessFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->Command <> "json" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetupSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->Command <> "json" && $this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 50; // Load default
		}

		// Load Sorting Order
		if ($this->Command <> "json")
			$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif ($this->Command <> "json") {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter
		if ($this->Command == "json") {
			$this->UseSessionForListSQL = FALSE; // Do not use session for ListSQL
			$this->CurrentFilter = $sFilter;
		} else {
			$this->setSessionWhere($sFilter);
			$this->CurrentFilter = "";
		}

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->ListRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->Id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->Id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "fpersonaslistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->Id->AdvancedSearch->ToJson(), ","); // Field Id
		$sFilterList = ew_Concat($sFilterList, $this->tipo_documento->AdvancedSearch->ToJson(), ","); // Field tipo_documento
		$sFilterList = ew_Concat($sFilterList, $this->no_documento->AdvancedSearch->ToJson(), ","); // Field no_documento
		$sFilterList = ew_Concat($sFilterList, $this->nombres->AdvancedSearch->ToJson(), ","); // Field nombres
		$sFilterList = ew_Concat($sFilterList, $this->paterno->AdvancedSearch->ToJson(), ","); // Field paterno
		$sFilterList = ew_Concat($sFilterList, $this->materno->AdvancedSearch->ToJson(), ","); // Field materno
		$sFilterList = ew_Concat($sFilterList, $this->fecha_nacimiento->AdvancedSearch->ToJson(), ","); // Field fecha_nacimiento
		$sFilterList = ew_Concat($sFilterList, $this->fecha_registro->AdvancedSearch->ToJson(), ","); // Field fecha_registro
		$sFilterList = ew_Concat($sFilterList, $this->imagen->AdvancedSearch->ToJson(), ","); // Field imagen
		$sFilterList = ew_Concat($sFilterList, $this->observaciones->AdvancedSearch->ToJson(), ","); // Field observaciones
		$sFilterList = ew_Concat($sFilterList, $this->estado->AdvancedSearch->ToJson(), ","); // Field estado
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = @$_POST["filters"];
			$UserProfile->SetSearchFilters(CurrentUserName(), "fpersonaslistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		$this->Command = "search";

		// Field Id
		$this->Id->AdvancedSearch->SearchValue = @$filter["x_Id"];
		$this->Id->AdvancedSearch->SearchOperator = @$filter["z_Id"];
		$this->Id->AdvancedSearch->SearchCondition = @$filter["v_Id"];
		$this->Id->AdvancedSearch->SearchValue2 = @$filter["y_Id"];
		$this->Id->AdvancedSearch->SearchOperator2 = @$filter["w_Id"];
		$this->Id->AdvancedSearch->Save();

		// Field tipo_documento
		$this->tipo_documento->AdvancedSearch->SearchValue = @$filter["x_tipo_documento"];
		$this->tipo_documento->AdvancedSearch->SearchOperator = @$filter["z_tipo_documento"];
		$this->tipo_documento->AdvancedSearch->SearchCondition = @$filter["v_tipo_documento"];
		$this->tipo_documento->AdvancedSearch->SearchValue2 = @$filter["y_tipo_documento"];
		$this->tipo_documento->AdvancedSearch->SearchOperator2 = @$filter["w_tipo_documento"];
		$this->tipo_documento->AdvancedSearch->Save();

		// Field no_documento
		$this->no_documento->AdvancedSearch->SearchValue = @$filter["x_no_documento"];
		$this->no_documento->AdvancedSearch->SearchOperator = @$filter["z_no_documento"];
		$this->no_documento->AdvancedSearch->SearchCondition = @$filter["v_no_documento"];
		$this->no_documento->AdvancedSearch->SearchValue2 = @$filter["y_no_documento"];
		$this->no_documento->AdvancedSearch->SearchOperator2 = @$filter["w_no_documento"];
		$this->no_documento->AdvancedSearch->Save();

		// Field nombres
		$this->nombres->AdvancedSearch->SearchValue = @$filter["x_nombres"];
		$this->nombres->AdvancedSearch->SearchOperator = @$filter["z_nombres"];
		$this->nombres->AdvancedSearch->SearchCondition = @$filter["v_nombres"];
		$this->nombres->AdvancedSearch->SearchValue2 = @$filter["y_nombres"];
		$this->nombres->AdvancedSearch->SearchOperator2 = @$filter["w_nombres"];
		$this->nombres->AdvancedSearch->Save();

		// Field paterno
		$this->paterno->AdvancedSearch->SearchValue = @$filter["x_paterno"];
		$this->paterno->AdvancedSearch->SearchOperator = @$filter["z_paterno"];
		$this->paterno->AdvancedSearch->SearchCondition = @$filter["v_paterno"];
		$this->paterno->AdvancedSearch->SearchValue2 = @$filter["y_paterno"];
		$this->paterno->AdvancedSearch->SearchOperator2 = @$filter["w_paterno"];
		$this->paterno->AdvancedSearch->Save();

		// Field materno
		$this->materno->AdvancedSearch->SearchValue = @$filter["x_materno"];
		$this->materno->AdvancedSearch->SearchOperator = @$filter["z_materno"];
		$this->materno->AdvancedSearch->SearchCondition = @$filter["v_materno"];
		$this->materno->AdvancedSearch->SearchValue2 = @$filter["y_materno"];
		$this->materno->AdvancedSearch->SearchOperator2 = @$filter["w_materno"];
		$this->materno->AdvancedSearch->Save();

		// Field fecha_nacimiento
		$this->fecha_nacimiento->AdvancedSearch->SearchValue = @$filter["x_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->SearchOperator = @$filter["z_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->SearchCondition = @$filter["v_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->SearchValue2 = @$filter["y_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_nacimiento"];
		$this->fecha_nacimiento->AdvancedSearch->Save();

		// Field fecha_registro
		$this->fecha_registro->AdvancedSearch->SearchValue = @$filter["x_fecha_registro"];
		$this->fecha_registro->AdvancedSearch->SearchOperator = @$filter["z_fecha_registro"];
		$this->fecha_registro->AdvancedSearch->SearchCondition = @$filter["v_fecha_registro"];
		$this->fecha_registro->AdvancedSearch->SearchValue2 = @$filter["y_fecha_registro"];
		$this->fecha_registro->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_registro"];
		$this->fecha_registro->AdvancedSearch->Save();

		// Field imagen
		$this->imagen->AdvancedSearch->SearchValue = @$filter["x_imagen"];
		$this->imagen->AdvancedSearch->SearchOperator = @$filter["z_imagen"];
		$this->imagen->AdvancedSearch->SearchCondition = @$filter["v_imagen"];
		$this->imagen->AdvancedSearch->SearchValue2 = @$filter["y_imagen"];
		$this->imagen->AdvancedSearch->SearchOperator2 = @$filter["w_imagen"];
		$this->imagen->AdvancedSearch->Save();

		// Field observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$filter["x_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator = @$filter["z_observaciones"];
		$this->observaciones->AdvancedSearch->SearchCondition = @$filter["v_observaciones"];
		$this->observaciones->AdvancedSearch->SearchValue2 = @$filter["y_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator2 = @$filter["w_observaciones"];
		$this->observaciones->AdvancedSearch->Save();

		// Field estado
		$this->estado->AdvancedSearch->SearchValue = @$filter["x_estado"];
		$this->estado->AdvancedSearch->SearchOperator = @$filter["z_estado"];
		$this->estado->AdvancedSearch->SearchCondition = @$filter["v_estado"];
		$this->estado->AdvancedSearch->SearchValue2 = @$filter["y_estado"];
		$this->estado->AdvancedSearch->SearchOperator2 = @$filter["w_estado"];
		$this->estado->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->Id, $Default, FALSE); // Id
		$this->BuildSearchSql($sWhere, $this->tipo_documento, $Default, FALSE); // tipo_documento
		$this->BuildSearchSql($sWhere, $this->no_documento, $Default, FALSE); // no_documento
		$this->BuildSearchSql($sWhere, $this->nombres, $Default, FALSE); // nombres
		$this->BuildSearchSql($sWhere, $this->paterno, $Default, FALSE); // paterno
		$this->BuildSearchSql($sWhere, $this->materno, $Default, FALSE); // materno
		$this->BuildSearchSql($sWhere, $this->fecha_nacimiento, $Default, FALSE); // fecha_nacimiento
		$this->BuildSearchSql($sWhere, $this->fecha_registro, $Default, FALSE); // fecha_registro
		$this->BuildSearchSql($sWhere, $this->imagen, $Default, FALSE); // imagen
		$this->BuildSearchSql($sWhere, $this->observaciones, $Default, FALSE); // observaciones
		$this->BuildSearchSql($sWhere, $this->estado, $Default, FALSE); // estado

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Id->AdvancedSearch->Save(); // Id
			$this->tipo_documento->AdvancedSearch->Save(); // tipo_documento
			$this->no_documento->AdvancedSearch->Save(); // no_documento
			$this->nombres->AdvancedSearch->Save(); // nombres
			$this->paterno->AdvancedSearch->Save(); // paterno
			$this->materno->AdvancedSearch->Save(); // materno
			$this->fecha_nacimiento->AdvancedSearch->Save(); // fecha_nacimiento
			$this->fecha_registro->AdvancedSearch->Save(); // fecha_registro
			$this->imagen->AdvancedSearch->Save(); // imagen
			$this->observaciones->AdvancedSearch->Save(); // observaciones
			$this->estado->AdvancedSearch->Save(); // estado
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = $Fld->FldParm();
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1)
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE || $Fld->FldDataType == EW_DATATYPE_TIME) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->tipo_documento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->no_documento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nombres, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->paterno, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->materno, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->imagen, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->observaciones, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .= "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

		// Get search SQL
		if ($sSearchKeyword <> "") {
			$ar = $this->BasicSearch->KeywordList($Default);

			// Search keyword in any fields
			if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
				foreach ($ar as $sKeyword) {
					if ($sKeyword <> "") {
						if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
						$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
					}
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			}
			if (!$Default && in_array($this->Command, array("", "reset", "resetall"))) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->Id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipo_documento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_documento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombres->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->paterno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->materno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_nacimiento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_registro->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->imagen->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->observaciones->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->estado->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->Id->AdvancedSearch->UnsetSession();
		$this->tipo_documento->AdvancedSearch->UnsetSession();
		$this->no_documento->AdvancedSearch->UnsetSession();
		$this->nombres->AdvancedSearch->UnsetSession();
		$this->paterno->AdvancedSearch->UnsetSession();
		$this->materno->AdvancedSearch->UnsetSession();
		$this->fecha_nacimiento->AdvancedSearch->UnsetSession();
		$this->fecha_registro->AdvancedSearch->UnsetSession();
		$this->imagen->AdvancedSearch->UnsetSession();
		$this->observaciones->AdvancedSearch->UnsetSession();
		$this->estado->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->Id->AdvancedSearch->Load();
		$this->tipo_documento->AdvancedSearch->Load();
		$this->no_documento->AdvancedSearch->Load();
		$this->nombres->AdvancedSearch->Load();
		$this->paterno->AdvancedSearch->Load();
		$this->materno->AdvancedSearch->Load();
		$this->fecha_nacimiento->AdvancedSearch->Load();
		$this->fecha_registro->AdvancedSearch->Load();
		$this->imagen->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Id); // Id
			$this->UpdateSort($this->tipo_documento); // tipo_documento
			$this->UpdateSort($this->no_documento); // no_documento
			$this->UpdateSort($this->nombres); // nombres
			$this->UpdateSort($this->paterno); // paterno
			$this->UpdateSort($this->materno); // materno
			$this->UpdateSort($this->fecha_nacimiento); // fecha_nacimiento
			$this->UpdateSort($this->fecha_registro); // fecha_registro
			$this->UpdateSort($this->imagen); // imagen
			$this->UpdateSort($this->estado); // estado
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->Id->setSort("");
				$this->tipo_documento->setSort("");
				$this->no_documento->setSort("");
				$this->nombres->setSort("");
				$this->paterno->setSort("");
				$this->materno->setSort("");
				$this->fecha_nacimiento->setSort("");
				$this->fecha_registro->setSort("");
				$this->imagen->setSort("");
				$this->estado->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// "detail_direcciones"
		$item = &$this->ListOptions->Add("detail_direcciones");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'direcciones') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["direcciones_grid"])) $GLOBALS["direcciones_grid"] = new cdirecciones_grid;

		// "detail_telefonos"
		$item = &$this->ListOptions->Add("detail_telefonos");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'telefonos') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["telefonos_grid"])) $GLOBALS["telefonos_grid"] = new ctelefonos_grid;

		// "detail_emails"
		$item = &$this->ListOptions->Add("detail_emails");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'emails') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["emails_grid"])) $GLOBALS["emails_grid"] = new cemails_grid;

		// "detail_vehiculos"
		$item = &$this->ListOptions->Add("detail_vehiculos");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'vehiculos') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["vehiculos_grid"])) $GLOBALS["vehiculos_grid"] = new cvehiculos_grid;

		// "detail_deuda_persona"
		$item = &$this->ListOptions->Add("detail_deuda_persona");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'deuda_persona') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["deuda_persona_grid"])) $GLOBALS["deuda_persona_grid"] = new cdeuda_persona_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssClass = "text-nowrap";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = TRUE;
			$item->ShowInButtonGroup = FALSE;
		}

		// Set up detail pages
		$pages = new cSubPages();
		$pages->Add("direcciones");
		$pages->Add("telefonos");
		$pages->Add("emails");
		$pages->Add("vehiculos");
		$pages->Add("deuda_persona");
		$this->DetailPages = $pages;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Call ListOptions_Rendering event
		$this->ListOptions_Rendering();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"personas\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"personas\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'EditBtn',url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-table=\"personas\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_direcciones"
		$oListOpt = &$this->ListOptions->Items["detail_direcciones"];
		if ($Security->AllowList(CurrentProjectID() . 'direcciones')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("direcciones", "TblCaption");
			$body .= "&nbsp;" . str_replace("%c", $this->direcciones_Count, $Language->Phrase("DetailCount"));
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("direccioneslist.php?" . EW_TABLE_SHOW_MASTER . "=personas&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["direcciones_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'direcciones')) {
				$caption = $Language->Phrase("MasterDetailViewLink");
				$url = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=direcciones");
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "direcciones";
			}
			if ($GLOBALS["direcciones_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'direcciones')) {
				$caption = $Language->Phrase("MasterDetailEditLink");
				$url = $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=direcciones");
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "direcciones";
			}
			if ($GLOBALS["direcciones_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'direcciones')) {
				$caption = $Language->Phrase("MasterDetailCopyLink");
				$url = $this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=direcciones");
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "direcciones";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_telefonos"
		$oListOpt = &$this->ListOptions->Items["detail_telefonos"];
		if ($Security->AllowList(CurrentProjectID() . 'telefonos')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("telefonos", "TblCaption");
			$body .= "&nbsp;" . str_replace("%c", $this->telefonos_Count, $Language->Phrase("DetailCount"));
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("telefonoslist.php?" . EW_TABLE_SHOW_MASTER . "=personas&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["telefonos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'telefonos')) {
				$caption = $Language->Phrase("MasterDetailViewLink");
				$url = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=telefonos");
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "telefonos";
			}
			if ($GLOBALS["telefonos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'telefonos')) {
				$caption = $Language->Phrase("MasterDetailEditLink");
				$url = $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=telefonos");
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "telefonos";
			}
			if ($GLOBALS["telefonos_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'telefonos')) {
				$caption = $Language->Phrase("MasterDetailCopyLink");
				$url = $this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=telefonos");
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "telefonos";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_emails"
		$oListOpt = &$this->ListOptions->Items["detail_emails"];
		if ($Security->AllowList(CurrentProjectID() . 'emails')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("emails", "TblCaption");
			$body .= "&nbsp;" . str_replace("%c", $this->emails_Count, $Language->Phrase("DetailCount"));
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("emailslist.php?" . EW_TABLE_SHOW_MASTER . "=personas&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["emails_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'emails')) {
				$caption = $Language->Phrase("MasterDetailViewLink");
				$url = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=emails");
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "emails";
			}
			if ($GLOBALS["emails_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'emails')) {
				$caption = $Language->Phrase("MasterDetailEditLink");
				$url = $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=emails");
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "emails";
			}
			if ($GLOBALS["emails_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'emails')) {
				$caption = $Language->Phrase("MasterDetailCopyLink");
				$url = $this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=emails");
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "emails";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_vehiculos"
		$oListOpt = &$this->ListOptions->Items["detail_vehiculos"];
		if ($Security->AllowList(CurrentProjectID() . 'vehiculos')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("vehiculos", "TblCaption");
			$body .= "&nbsp;" . str_replace("%c", $this->vehiculos_Count, $Language->Phrase("DetailCount"));
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("vehiculoslist.php?" . EW_TABLE_SHOW_MASTER . "=personas&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["vehiculos_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'vehiculos')) {
				$caption = $Language->Phrase("MasterDetailViewLink");
				$url = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=vehiculos");
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "vehiculos";
			}
			if ($GLOBALS["vehiculos_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'vehiculos')) {
				$caption = $Language->Phrase("MasterDetailEditLink");
				$url = $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=vehiculos");
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "vehiculos";
			}
			if ($GLOBALS["vehiculos_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'vehiculos')) {
				$caption = $Language->Phrase("MasterDetailCopyLink");
				$url = $this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=vehiculos");
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "vehiculos";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_deuda_persona"
		$oListOpt = &$this->ListOptions->Items["detail_deuda_persona"];
		if ($Security->AllowList(CurrentProjectID() . 'deuda_persona')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("deuda_persona", "TblCaption");
			$body .= "&nbsp;" . str_replace("%c", $this->deuda_persona_Count, $Language->Phrase("DetailCount"));
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("deuda_personalist.php?" . EW_TABLE_SHOW_MASTER . "=personas&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["deuda_persona_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'deuda_persona')) {
				$caption = $Language->Phrase("MasterDetailViewLink");
				$url = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=deuda_persona");
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "deuda_persona";
			}
			if ($GLOBALS["deuda_persona_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'deuda_persona')) {
				$caption = $Language->Phrase("MasterDetailEditLink");
				$url = $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=deuda_persona");
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "deuda_persona";
			}
			if ($GLOBALS["deuda_persona_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'deuda_persona')) {
				$caption = $Language->Phrase("MasterDetailCopyLink");
				$url = $this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=deuda_persona");
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "deuda_persona";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->Id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		if (ew_IsMobile())
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-table=\"personas\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_direcciones");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=direcciones");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["direcciones"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["direcciones"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'direcciones') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "direcciones";
		}
		$item = &$option->Add("detailadd_telefonos");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=telefonos");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["telefonos"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["telefonos"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'telefonos') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "telefonos";
		}
		$item = &$option->Add("detailadd_emails");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=emails");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["emails"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["emails"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'emails') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "emails";
		}
		$item = &$option->Add("detailadd_vehiculos");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=vehiculos");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["vehiculos"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["vehiculos"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'vehiculos') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "vehiculos";
		}
		$item = &$option->Add("detailadd_deuda_persona");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=deuda_persona");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["deuda_persona"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["deuda_persona"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'deuda_persona') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "deuda_persona";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink);
			$caption = $Language->Phrase("AddMasterDetailLink");
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fpersonaslist,url:'" . $this->MultiDeleteUrl . "',msg:ewLanguage.Phrase('DeleteConfirmMsg')});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fpersonaslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fpersonaslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fpersonaslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : "";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fpersonaslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// Id

		$this->Id->AdvancedSearch->SearchValue = @$_GET["x_Id"];
		if ($this->Id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->Id->AdvancedSearch->SearchOperator = @$_GET["z_Id"];

		// tipo_documento
		$this->tipo_documento->AdvancedSearch->SearchValue = @$_GET["x_tipo_documento"];
		if ($this->tipo_documento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->tipo_documento->AdvancedSearch->SearchOperator = @$_GET["z_tipo_documento"];

		// no_documento
		$this->no_documento->AdvancedSearch->SearchValue = @$_GET["x_no_documento"];
		if ($this->no_documento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->no_documento->AdvancedSearch->SearchOperator = @$_GET["z_no_documento"];

		// nombres
		$this->nombres->AdvancedSearch->SearchValue = @$_GET["x_nombres"];
		if ($this->nombres->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nombres->AdvancedSearch->SearchOperator = @$_GET["z_nombres"];

		// paterno
		$this->paterno->AdvancedSearch->SearchValue = @$_GET["x_paterno"];
		if ($this->paterno->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->paterno->AdvancedSearch->SearchOperator = @$_GET["z_paterno"];

		// materno
		$this->materno->AdvancedSearch->SearchValue = @$_GET["x_materno"];
		if ($this->materno->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->materno->AdvancedSearch->SearchOperator = @$_GET["z_materno"];

		// fecha_nacimiento
		$this->fecha_nacimiento->AdvancedSearch->SearchValue = @$_GET["x_fecha_nacimiento"];
		if ($this->fecha_nacimiento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha_nacimiento->AdvancedSearch->SearchOperator = @$_GET["z_fecha_nacimiento"];

		// fecha_registro
		$this->fecha_registro->AdvancedSearch->SearchValue = @$_GET["x_fecha_registro"];
		if ($this->fecha_registro->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha_registro->AdvancedSearch->SearchOperator = @$_GET["z_fecha_registro"];
		$this->fecha_registro->AdvancedSearch->SearchCondition = @$_GET["v_fecha_registro"];
		$this->fecha_registro->AdvancedSearch->SearchValue2 = @$_GET["y_fecha_registro"];
		if ($this->fecha_registro->AdvancedSearch->SearchValue2 <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha_registro->AdvancedSearch->SearchOperator2 = @$_GET["w_fecha_registro"];

		// imagen
		$this->imagen->AdvancedSearch->SearchValue = @$_GET["x_imagen"];
		if ($this->imagen->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->imagen->AdvancedSearch->SearchOperator = @$_GET["z_imagen"];

		// observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$_GET["x_observaciones"];
		if ($this->observaciones->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->observaciones->AdvancedSearch->SearchOperator = @$_GET["z_observaciones"];

		// estado
		$this->estado->AdvancedSearch->SearchValue = @$_GET["x_estado"];
		if ($this->estado->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->estado->AdvancedSearch->SearchOperator = @$_GET["z_estado"];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->tipo_documento->setDbValue($row['tipo_documento']);
		$this->no_documento->setDbValue($row['no_documento']);
		$this->nombres->setDbValue($row['nombres']);
		$this->paterno->setDbValue($row['paterno']);
		$this->materno->setDbValue($row['materno']);
		$this->fecha_nacimiento->setDbValue($row['fecha_nacimiento']);
		$this->fecha_registro->setDbValue($row['fecha_registro']);
		$this->imagen->Upload->DbValue = $row['imagen'];
		$this->imagen->CurrentValue = $this->imagen->Upload->DbValue;
		$this->observaciones->setDbValue($row['observaciones']);
		$this->estado->setDbValue($row['estado']);
		if (!isset($GLOBALS["direcciones_grid"])) $GLOBALS["direcciones_grid"] = new cdirecciones_grid;
		$sDetailFilter = $GLOBALS["direcciones"]->SqlDetailFilter_personas();
		$sDetailFilter = str_replace("@id_persona@", ew_AdjustSql($this->Id->DbValue, "DB"), $sDetailFilter);
		$GLOBALS["direcciones"]->setCurrentMasterTable("personas");
		$sDetailFilter = $GLOBALS["direcciones"]->ApplyUserIDFilters($sDetailFilter);
		$this->direcciones_Count = $GLOBALS["direcciones"]->LoadRecordCount($sDetailFilter);
		if (!isset($GLOBALS["telefonos_grid"])) $GLOBALS["telefonos_grid"] = new ctelefonos_grid;
		$sDetailFilter = $GLOBALS["telefonos"]->SqlDetailFilter_personas();
		$sDetailFilter = str_replace("@id_persona@", ew_AdjustSql($this->Id->DbValue, "DB"), $sDetailFilter);
		$GLOBALS["telefonos"]->setCurrentMasterTable("personas");
		$sDetailFilter = $GLOBALS["telefonos"]->ApplyUserIDFilters($sDetailFilter);
		$this->telefonos_Count = $GLOBALS["telefonos"]->LoadRecordCount($sDetailFilter);
		if (!isset($GLOBALS["emails_grid"])) $GLOBALS["emails_grid"] = new cemails_grid;
		$sDetailFilter = $GLOBALS["emails"]->SqlDetailFilter_personas();
		$sDetailFilter = str_replace("@id_persona@", ew_AdjustSql($this->Id->DbValue, "DB"), $sDetailFilter);
		$GLOBALS["emails"]->setCurrentMasterTable("personas");
		$sDetailFilter = $GLOBALS["emails"]->ApplyUserIDFilters($sDetailFilter);
		$this->emails_Count = $GLOBALS["emails"]->LoadRecordCount($sDetailFilter);
		if (!isset($GLOBALS["vehiculos_grid"])) $GLOBALS["vehiculos_grid"] = new cvehiculos_grid;
		$sDetailFilter = $GLOBALS["vehiculos"]->SqlDetailFilter_personas();
		$sDetailFilter = str_replace("@id_persona@", ew_AdjustSql($this->Id->DbValue, "DB"), $sDetailFilter);
		$GLOBALS["vehiculos"]->setCurrentMasterTable("personas");
		$sDetailFilter = $GLOBALS["vehiculos"]->ApplyUserIDFilters($sDetailFilter);
		$this->vehiculos_Count = $GLOBALS["vehiculos"]->LoadRecordCount($sDetailFilter);
		if (!isset($GLOBALS["deuda_persona_grid"])) $GLOBALS["deuda_persona_grid"] = new cdeuda_persona_grid;
		$sDetailFilter = $GLOBALS["deuda_persona"]->SqlDetailFilter_personas();
		$sDetailFilter = str_replace("@id_persona@", ew_AdjustSql($this->Id->DbValue, "DB"), $sDetailFilter);
		$GLOBALS["deuda_persona"]->setCurrentMasterTable("personas");
		$sDetailFilter = $GLOBALS["deuda_persona"]->ApplyUserIDFilters($sDetailFilter);
		$this->deuda_persona_Count = $GLOBALS["deuda_persona"]->LoadRecordCount($sDetailFilter);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['Id'] = NULL;
		$row['tipo_documento'] = NULL;
		$row['no_documento'] = NULL;
		$row['nombres'] = NULL;
		$row['paterno'] = NULL;
		$row['materno'] = NULL;
		$row['fecha_nacimiento'] = NULL;
		$row['fecha_registro'] = NULL;
		$row['imagen'] = NULL;
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
		$this->tipo_documento->DbValue = $row['tipo_documento'];
		$this->no_documento->DbValue = $row['no_documento'];
		$this->nombres->DbValue = $row['nombres'];
		$this->paterno->DbValue = $row['paterno'];
		$this->materno->DbValue = $row['materno'];
		$this->fecha_nacimiento->DbValue = $row['fecha_nacimiento'];
		$this->fecha_registro->DbValue = $row['fecha_registro'];
		$this->imagen->Upload->DbValue = $row['imagen'];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Id
		// tipo_documento
		// no_documento
		// nombres
		// paterno
		// materno
		// fecha_nacimiento
		// fecha_registro
		// imagen
		// observaciones
		// estado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

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

		// imagen
		$this->imagen->UploadPath = 'uploads/';
		if (!ew_Empty($this->imagen->Upload->DbValue)) {
			$this->imagen->ImageWidth = 120;
			$this->imagen->ImageHeight = 120;
			$this->imagen->ImageAlt = $this->imagen->FldAlt();
			$this->imagen->ViewValue = $this->imagen->Upload->DbValue;
		} else {
			$this->imagen->ViewValue = "";
		}
		$this->imagen->ViewCustomAttributes = "";

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

			// fecha_registro
			$this->fecha_registro->LinkCustomAttributes = "";
			$this->fecha_registro->HrefValue = "";
			$this->fecha_registro->TooltipValue = "";

			// imagen
			$this->imagen->LinkCustomAttributes = "";
			$this->imagen->UploadPath = 'uploads/';
			if (!ew_Empty($this->imagen->Upload->DbValue)) {
				$this->imagen->HrefValue = ew_GetFileUploadUrl($this->imagen, $this->imagen->Upload->DbValue); // Add prefix/suffix
				$this->imagen->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->imagen->HrefValue = ew_FullUrl($this->imagen->HrefValue, "href");
			} else {
				$this->imagen->HrefValue = "";
			}
			$this->imagen->HrefValue2 = $this->imagen->UploadPath . $this->imagen->Upload->DbValue;
			$this->imagen->TooltipValue = "";
			if ($this->imagen->UseColorbox) {
				if (ew_Empty($this->imagen->TooltipValue))
					$this->imagen->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->imagen->LinkAttrs["data-rel"] = "personas_x" . $this->RowCnt . "_imagen";
				ew_AppendClass($this->imagen->LinkAttrs["class"], "ewLightbox");
			}

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			$this->Id->EditValue = ew_HtmlEncode($this->Id->AdvancedSearch->SearchValue);
			$this->Id->PlaceHolder = ew_RemoveHtml($this->Id->FldCaption());

			// tipo_documento
			$this->tipo_documento->EditAttrs["class"] = "form-control";
			$this->tipo_documento->EditCustomAttributes = "";
			$this->tipo_documento->EditValue = $this->tipo_documento->Options(TRUE);

			// no_documento
			$this->no_documento->EditAttrs["class"] = "form-control";
			$this->no_documento->EditCustomAttributes = "";
			$this->no_documento->EditValue = ew_HtmlEncode($this->no_documento->AdvancedSearch->SearchValue);
			$this->no_documento->PlaceHolder = ew_RemoveHtml($this->no_documento->FldCaption());

			// nombres
			$this->nombres->EditAttrs["class"] = "form-control";
			$this->nombres->EditCustomAttributes = "";
			$this->nombres->EditValue = ew_HtmlEncode($this->nombres->AdvancedSearch->SearchValue);
			$this->nombres->PlaceHolder = ew_RemoveHtml($this->nombres->FldCaption());

			// paterno
			$this->paterno->EditAttrs["class"] = "form-control";
			$this->paterno->EditCustomAttributes = "";
			$this->paterno->EditValue = ew_HtmlEncode($this->paterno->AdvancedSearch->SearchValue);
			$this->paterno->PlaceHolder = ew_RemoveHtml($this->paterno->FldCaption());

			// materno
			$this->materno->EditAttrs["class"] = "form-control";
			$this->materno->EditCustomAttributes = "";
			$this->materno->EditValue = ew_HtmlEncode($this->materno->AdvancedSearch->SearchValue);
			$this->materno->PlaceHolder = ew_RemoveHtml($this->materno->FldCaption());

			// fecha_nacimiento
			$this->fecha_nacimiento->EditAttrs["class"] = "form-control";
			$this->fecha_nacimiento->EditCustomAttributes = "";
			$this->fecha_nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_nacimiento->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->fecha_nacimiento->FldCaption());

			// fecha_registro
			$this->fecha_registro->EditAttrs["class"] = "form-control";
			$this->fecha_registro->EditCustomAttributes = "";
			$this->fecha_registro->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_registro->AdvancedSearch->SearchValue, 11), 11));
			$this->fecha_registro->PlaceHolder = ew_RemoveHtml($this->fecha_registro->FldCaption());
			$this->fecha_registro->EditAttrs["class"] = "form-control";
			$this->fecha_registro->EditCustomAttributes = "";
			$this->fecha_registro->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_registro->AdvancedSearch->SearchValue2, 11), 11));
			$this->fecha_registro->PlaceHolder = ew_RemoveHtml($this->fecha_registro->FldCaption());

			// imagen
			$this->imagen->EditAttrs["class"] = "form-control";
			$this->imagen->EditCustomAttributes = "";
			$this->imagen->EditValue = ew_HtmlEncode($this->imagen->AdvancedSearch->SearchValue);
			$this->imagen->PlaceHolder = ew_RemoveHtml($this->imagen->FldCaption());

			// estado
			$this->estado->EditCustomAttributes = "";
			$this->estado->EditValue = $this->estado->Options(FALSE);
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckEuroDate($this->fecha_nacimiento->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha_nacimiento->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha_registro->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha_registro->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha_registro->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->fecha_registro->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->Id->AdvancedSearch->Load();
		$this->tipo_documento->AdvancedSearch->Load();
		$this->no_documento->AdvancedSearch->Load();
		$this->nombres->AdvancedSearch->Load();
		$this->paterno->AdvancedSearch->Load();
		$this->materno->AdvancedSearch->Load();
		$this->fecha_nacimiento->AdvancedSearch->Load();
		$this->fecha_registro->AdvancedSearch->Load();
		$this->imagen->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->estado->AdvancedSearch->Load();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_personas\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_personas',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fpersonaslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->ListRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetupStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($Doc->Text);
		} else {
			$Doc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];

		// Subject
		$sSubject = @$_POST["subject"];
		$sEmailSubject = $sSubject;

		// Message
		$sContent = @$_POST["message"];
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-danger\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = "html";
		if ($sEmailMessage <> "")
			$sEmailMessage = ew_RemoveXSS($sEmailMessage) . "<br><br>";
		foreach ($gTmpImages as $tmpimage)
			$Email->AddEmbeddedImage($tmpimage);
		$Email->Content = $sEmailMessage . ew_CleanEmailContent($EmailContent); // Content
		$EventArgs = array();
		if ($this->Recordset) {
			$this->RecCnt = $this->StartRec - 1;
			$this->Recordset->MoveFirst();
			if ($this->StartRec > 1)
				$this->Recordset->Move($this->StartRec - 1);
			$EventArgs["rs"] = &$this->Recordset;
		}
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-danger\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Build QueryString for search
		if ($this->BasicSearch->getKeyword() <> "") {
			$sQry .= "&" . EW_TABLE_BASIC_SEARCH . "=" . urlencode($this->BasicSearch->getKeyword()) . "&" . EW_TABLE_BASIC_SEARCH_TYPE . "=" . urlencode($this->BasicSearch->getType());
		}
		$this->AddSearchQueryString($sQry, $this->Id); // Id
		$this->AddSearchQueryString($sQry, $this->tipo_documento); // tipo_documento
		$this->AddSearchQueryString($sQry, $this->no_documento); // no_documento
		$this->AddSearchQueryString($sQry, $this->nombres); // nombres
		$this->AddSearchQueryString($sQry, $this->paterno); // paterno
		$this->AddSearchQueryString($sQry, $this->materno); // materno
		$this->AddSearchQueryString($sQry, $this->fecha_nacimiento); // fecha_nacimiento
		$this->AddSearchQueryString($sQry, $this->fecha_registro); // fecha_registro
		$this->AddSearchQueryString($sQry, $this->observaciones); // observaciones
		$this->AddSearchQueryString($sQry, $this->estado); // estado

		// Build QueryString for pager
		$sQry .= "&" . EW_TABLE_REC_PER_PAGE . "=" . urlencode($this->getRecordsPerPage()) . "&" . EW_TABLE_START_REC . "=" . urlencode($this->getStartRecordNumber());
		return $sQry;
	}

	// Add search QueryString
	function AddSearchQueryString(&$Qry, &$Fld) {
		$FldSearchValue = $Fld->AdvancedSearch->getValue("x");
		$FldParm = substr($Fld->FldVar,2);
		if (strval($FldSearchValue) <> "") {
			$Qry .= "&x_" . $FldParm . "=" . urlencode($FldSearchValue) .
				"&z_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("z"));
		}
		$FldSearchValue2 = $Fld->AdvancedSearch->getValue("y");
		if (strval($FldSearchValue2) <> "") {
			$Qry .= "&v_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("v")) .
				"&y_" . $FldParm . "=" . urlencode($FldSearchValue2) .
				"&w_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("w"));
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendering event
	function ListOptions_Rendering() {

		//$GLOBALS["xxx_grid"]->DetailAdd = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailEdit = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailView = (...condition...); // Set to TRUE or FALSE conditionally

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example:
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($personas_list)) $personas_list = new cpersonas_list();

// Page init
$personas_list->Page_Init();

// Page main
$personas_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$personas_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($personas->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fpersonaslist = new ew_Form("fpersonaslist", "list");
fpersonaslist.FormKeyCountName = '<?php echo $personas_list->FormKeyCountName ?>';

// Form_CustomValidate event
fpersonaslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpersonaslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fpersonaslist.Lists["x_tipo_documento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpersonaslist.Lists["x_tipo_documento"].Options = <?php echo json_encode($personas_list->tipo_documento->Options()) ?>;
fpersonaslist.Lists["x_estado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpersonaslist.Lists["x_estado"].Options = <?php echo json_encode($personas_list->estado->Options()) ?>;

// Form object for search
var CurrentSearchForm = fpersonaslistsrch = new ew_Form("fpersonaslistsrch");

// Validate function for search
fpersonaslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_fecha_nacimiento");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($personas->fecha_nacimiento->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_fecha_registro");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($personas->fecha_registro->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fpersonaslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fpersonaslistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fpersonaslistsrch.Lists["x_tipo_documento"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpersonaslistsrch.Lists["x_tipo_documento"].Options = <?php echo json_encode($personas_list->tipo_documento->Options()) ?>;
fpersonaslistsrch.Lists["x_estado"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpersonaslistsrch.Lists["x_estado"].Options = <?php echo json_encode($personas_list->estado->Options()) ?>;

// Init search panel as collapsed
if (fpersonaslistsrch) fpersonaslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($personas->Export == "") { ?>
<div class="ewToolbar">
<?php if ($personas_list->TotalRecs > 0 && $personas_list->ExportOptions->Visible()) { ?>
<?php $personas_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($personas_list->SearchOptions->Visible()) { ?>
<?php $personas_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($personas_list->FilterOptions->Visible()) { ?>
<?php $personas_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $personas_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($personas_list->TotalRecs <= 0)
			$personas_list->TotalRecs = $personas->ListRecordCount();
	} else {
		if (!$personas_list->Recordset && ($personas_list->Recordset = $personas_list->LoadRecordset()))
			$personas_list->TotalRecs = $personas_list->Recordset->RecordCount();
	}
	$personas_list->StartRec = 1;
	if ($personas_list->DisplayRecs <= 0 || ($personas->Export <> "" && $personas->ExportAll)) // Display all records
		$personas_list->DisplayRecs = $personas_list->TotalRecs;
	if (!($personas->Export <> "" && $personas->ExportAll))
		$personas_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$personas_list->Recordset = $personas_list->LoadRecordset($personas_list->StartRec-1, $personas_list->DisplayRecs);

	// Set no record found message
	if ($personas->CurrentAction == "" && $personas_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$personas_list->setWarningMessage(ew_DeniedMsg());
		if ($personas_list->SearchWhere == "0=101")
			$personas_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$personas_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$personas_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($personas->Export == "" && $personas->CurrentAction == "") { ?>
<form name="fpersonaslistsrch" id="fpersonaslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($personas_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fpersonaslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="personas">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$personas_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$personas->RowType = EW_ROWTYPE_SEARCH;

// Render row
$personas->ResetAttrs();
$personas_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($personas->tipo_documento->Visible) { // tipo_documento ?>
	<div id="xsc_tipo_documento" class="ewCell form-group">
		<label for="x_tipo_documento" class="ewSearchCaption ewLabel"><?php echo $personas->tipo_documento->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_tipo_documento" id="z_tipo_documento" value="LIKE"></span>
		<span class="ewSearchField">
<select data-table="personas" data-field="x_tipo_documento" data-value-separator="<?php echo $personas->tipo_documento->DisplayValueSeparatorAttribute() ?>" id="x_tipo_documento" name="x_tipo_documento"<?php echo $personas->tipo_documento->EditAttributes() ?>>
<?php echo $personas->tipo_documento->SelectOptionListHtml("x_tipo_documento") ?>
</select>
</span>
	</div>
<?php } ?>
<?php if ($personas->no_documento->Visible) { // no_documento ?>
	<div id="xsc_no_documento" class="ewCell form-group">
		<label for="x_no_documento" class="ewSearchCaption ewLabel"><?php echo $personas->no_documento->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_documento" id="z_no_documento" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="personas" data-field="x_no_documento" name="x_no_documento" id="x_no_documento" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->no_documento->getPlaceHolder()) ?>" value="<?php echo $personas->no_documento->EditValue ?>"<?php echo $personas->no_documento->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($personas->nombres->Visible) { // nombres ?>
	<div id="xsc_nombres" class="ewCell form-group">
		<label for="x_nombres" class="ewSearchCaption ewLabel"><?php echo $personas->nombres->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombres" id="z_nombres" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="personas" data-field="x_nombres" name="x_nombres" id="x_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->nombres->getPlaceHolder()) ?>" value="<?php echo $personas->nombres->EditValue ?>"<?php echo $personas->nombres->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($personas->paterno->Visible) { // paterno ?>
	<div id="xsc_paterno" class="ewCell form-group">
		<label for="x_paterno" class="ewSearchCaption ewLabel"><?php echo $personas->paterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_paterno" id="z_paterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="personas" data-field="x_paterno" name="x_paterno" id="x_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->paterno->getPlaceHolder()) ?>" value="<?php echo $personas->paterno->EditValue ?>"<?php echo $personas->paterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($personas->materno->Visible) { // materno ?>
	<div id="xsc_materno" class="ewCell form-group">
		<label for="x_materno" class="ewSearchCaption ewLabel"><?php echo $personas->materno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_materno" id="z_materno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="personas" data-field="x_materno" name="x_materno" id="x_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($personas->materno->getPlaceHolder()) ?>" value="<?php echo $personas->materno->EditValue ?>"<?php echo $personas->materno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($personas->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<div id="xsc_fecha_nacimiento" class="ewCell form-group">
		<label for="x_fecha_nacimiento" class="ewSearchCaption ewLabel"><?php echo $personas->fecha_nacimiento->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fecha_nacimiento" id="z_fecha_nacimiento" value="="></span>
		<span class="ewSearchField">
<input type="text" data-table="personas" data-field="x_fecha_nacimiento" data-format="7" name="x_fecha_nacimiento" id="x_fecha_nacimiento" size="20" placeholder="<?php echo ew_HtmlEncode($personas->fecha_nacimiento->getPlaceHolder()) ?>" value="<?php echo $personas->fecha_nacimiento->EditValue ?>"<?php echo $personas->fecha_nacimiento->EditAttributes() ?>>
<?php if (!$personas->fecha_nacimiento->ReadOnly && !$personas->fecha_nacimiento->Disabled && !isset($personas->fecha_nacimiento->EditAttrs["readonly"]) && !isset($personas->fecha_nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpersonaslistsrch", "x_fecha_nacimiento", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($personas->fecha_registro->Visible) { // fecha_registro ?>
	<div id="xsc_fecha_registro" class="ewCell form-group">
		<label for="x_fecha_registro" class="ewSearchCaption ewLabel"><?php echo $personas->fecha_registro->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_fecha_registro" id="z_fecha_registro" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-table="personas" data-field="x_fecha_registro" data-format="11" name="x_fecha_registro" id="x_fecha_registro" size="20" placeholder="<?php echo ew_HtmlEncode($personas->fecha_registro->getPlaceHolder()) ?>" value="<?php echo $personas->fecha_registro->EditValue ?>"<?php echo $personas->fecha_registro->EditAttributes() ?>>
<?php if (!$personas->fecha_registro->ReadOnly && !$personas->fecha_registro->Disabled && !isset($personas->fecha_registro->EditAttrs["readonly"]) && !isset($personas->fecha_registro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpersonaslistsrch", "x_fecha_registro", {"ignoreReadonly":true,"useCurrent":false,"format":11});
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_fecha_registro">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_fecha_registro">
<input type="text" data-table="personas" data-field="x_fecha_registro" data-format="11" name="y_fecha_registro" id="y_fecha_registro" size="20" placeholder="<?php echo ew_HtmlEncode($personas->fecha_registro->getPlaceHolder()) ?>" value="<?php echo $personas->fecha_registro->EditValue2 ?>"<?php echo $personas->fecha_registro->EditAttributes() ?>>
<?php if (!$personas->fecha_registro->ReadOnly && !$personas->fecha_registro->Disabled && !isset($personas->fecha_registro->EditAttrs["readonly"]) && !isset($personas->fecha_registro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fpersonaslistsrch", "y_fecha_registro", {"ignoreReadonly":true,"useCurrent":false,"format":11});
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
<?php if ($personas->estado->Visible) { // estado ?>
	<div id="xsc_estado" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $personas->estado->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_estado" id="z_estado" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_estado" class="ewTemplate"><input type="radio" data-table="personas" data-field="x_estado" data-value-separator="<?php echo $personas->estado->DisplayValueSeparatorAttribute() ?>" name="x_estado" id="x_estado" value="{value}"<?php echo $personas->estado->EditAttributes() ?>></div>
<div id="dsl_x_estado" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $personas->estado->RadioButtonListHtml(FALSE, "x_estado") ?>
</div></div>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($personas_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($personas_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $personas_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($personas_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($personas_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($personas_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($personas_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $personas_list->ShowPageHeader(); ?>
<?php
$personas_list->ShowMessage();
?>
<?php if ($personas_list->TotalRecs > 0 || $personas->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($personas_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> personas">
<?php if ($personas->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($personas->CurrentAction <> "gridadd" && $personas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($personas_list->Pager)) $personas_list->Pager = new cPrevNextPager($personas_list->StartRec, $personas_list->DisplayRecs, $personas_list->TotalRecs, $personas_list->AutoHidePager) ?>
<?php if ($personas_list->Pager->RecordCount > 0 && $personas_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($personas_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $personas_list->PageUrl() ?>start=<?php echo $personas_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($personas_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $personas_list->PageUrl() ?>start=<?php echo $personas_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $personas_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($personas_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $personas_list->PageUrl() ?>start=<?php echo $personas_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($personas_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $personas_list->PageUrl() ?>start=<?php echo $personas_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $personas_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $personas_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $personas_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $personas_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($personas_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fpersonaslist" id="fpersonaslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($personas_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $personas_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="personas">
<div id="gmp_personas" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($personas_list->TotalRecs > 0 || $personas->CurrentAction == "gridedit") { ?>
<table id="tbl_personaslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$personas_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$personas_list->RenderListOptions();

// Render list options (header, left)
$personas_list->ListOptions->Render("header", "left");
?>
<?php if ($personas->Id->Visible) { // Id ?>
	<?php if ($personas->SortUrl($personas->Id) == "") { ?>
		<th data-name="Id" class="<?php echo $personas->Id->HeaderCellClass() ?>"><div id="elh_personas_Id" class="personas_Id"><div class="ewTableHeaderCaption"><?php echo $personas->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id" class="<?php echo $personas->Id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $personas->SortUrl($personas->Id) ?>',1);"><div id="elh_personas_Id" class="personas_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->tipo_documento->Visible) { // tipo_documento ?>
	<?php if ($personas->SortUrl($personas->tipo_documento) == "") { ?>
		<th data-name="tipo_documento" class="<?php echo $personas->tipo_documento->HeaderCellClass() ?>"><div id="elh_personas_tipo_documento" class="personas_tipo_documento"><div class="ewTableHeaderCaption"><?php echo $personas->tipo_documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo_documento" class="<?php echo $personas->tipo_documento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $personas->SortUrl($personas->tipo_documento) ?>',1);"><div id="elh_personas_tipo_documento" class="personas_tipo_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->tipo_documento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->tipo_documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->tipo_documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->no_documento->Visible) { // no_documento ?>
	<?php if ($personas->SortUrl($personas->no_documento) == "") { ?>
		<th data-name="no_documento" class="<?php echo $personas->no_documento->HeaderCellClass() ?>"><div id="elh_personas_no_documento" class="personas_no_documento"><div class="ewTableHeaderCaption"><?php echo $personas->no_documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="no_documento" class="<?php echo $personas->no_documento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $personas->SortUrl($personas->no_documento) ?>',1);"><div id="elh_personas_no_documento" class="personas_no_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->no_documento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($personas->no_documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->no_documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->nombres->Visible) { // nombres ?>
	<?php if ($personas->SortUrl($personas->nombres) == "") { ?>
		<th data-name="nombres" class="<?php echo $personas->nombres->HeaderCellClass() ?>"><div id="elh_personas_nombres" class="personas_nombres"><div class="ewTableHeaderCaption"><?php echo $personas->nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombres" class="<?php echo $personas->nombres->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $personas->SortUrl($personas->nombres) ?>',1);"><div id="elh_personas_nombres" class="personas_nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->nombres->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($personas->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->paterno->Visible) { // paterno ?>
	<?php if ($personas->SortUrl($personas->paterno) == "") { ?>
		<th data-name="paterno" class="<?php echo $personas->paterno->HeaderCellClass() ?>"><div id="elh_personas_paterno" class="personas_paterno"><div class="ewTableHeaderCaption"><?php echo $personas->paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="paterno" class="<?php echo $personas->paterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $personas->SortUrl($personas->paterno) ?>',1);"><div id="elh_personas_paterno" class="personas_paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->paterno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($personas->paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->materno->Visible) { // materno ?>
	<?php if ($personas->SortUrl($personas->materno) == "") { ?>
		<th data-name="materno" class="<?php echo $personas->materno->HeaderCellClass() ?>"><div id="elh_personas_materno" class="personas_materno"><div class="ewTableHeaderCaption"><?php echo $personas->materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="materno" class="<?php echo $personas->materno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $personas->SortUrl($personas->materno) ?>',1);"><div id="elh_personas_materno" class="personas_materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->materno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($personas->materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
	<?php if ($personas->SortUrl($personas->fecha_nacimiento) == "") { ?>
		<th data-name="fecha_nacimiento" class="<?php echo $personas->fecha_nacimiento->HeaderCellClass() ?>"><div id="elh_personas_fecha_nacimiento" class="personas_fecha_nacimiento"><div class="ewTableHeaderCaption"><?php echo $personas->fecha_nacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_nacimiento" class="<?php echo $personas->fecha_nacimiento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $personas->SortUrl($personas->fecha_nacimiento) ?>',1);"><div id="elh_personas_fecha_nacimiento" class="personas_fecha_nacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->fecha_nacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->fecha_nacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->fecha_nacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->fecha_registro->Visible) { // fecha_registro ?>
	<?php if ($personas->SortUrl($personas->fecha_registro) == "") { ?>
		<th data-name="fecha_registro" class="<?php echo $personas->fecha_registro->HeaderCellClass() ?>"><div id="elh_personas_fecha_registro" class="personas_fecha_registro"><div class="ewTableHeaderCaption"><?php echo $personas->fecha_registro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_registro" class="<?php echo $personas->fecha_registro->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $personas->SortUrl($personas->fecha_registro) ?>',1);"><div id="elh_personas_fecha_registro" class="personas_fecha_registro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->fecha_registro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->fecha_registro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->fecha_registro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->imagen->Visible) { // imagen ?>
	<?php if ($personas->SortUrl($personas->imagen) == "") { ?>
		<th data-name="imagen" class="<?php echo $personas->imagen->HeaderCellClass() ?>"><div id="elh_personas_imagen" class="personas_imagen"><div class="ewTableHeaderCaption"><?php echo $personas->imagen->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="imagen" class="<?php echo $personas->imagen->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $personas->SortUrl($personas->imagen) ?>',1);"><div id="elh_personas_imagen" class="personas_imagen">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->imagen->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($personas->imagen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->imagen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($personas->estado->Visible) { // estado ?>
	<?php if ($personas->SortUrl($personas->estado) == "") { ?>
		<th data-name="estado" class="<?php echo $personas->estado->HeaderCellClass() ?>"><div id="elh_personas_estado" class="personas_estado"><div class="ewTableHeaderCaption"><?php echo $personas->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado" class="<?php echo $personas->estado->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $personas->SortUrl($personas->estado) ?>',1);"><div id="elh_personas_estado" class="personas_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $personas->estado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($personas->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($personas->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$personas_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($personas->ExportAll && $personas->Export <> "") {
	$personas_list->StopRec = $personas_list->TotalRecs;
} else {

	// Set the last record to display
	if ($personas_list->TotalRecs > $personas_list->StartRec + $personas_list->DisplayRecs - 1)
		$personas_list->StopRec = $personas_list->StartRec + $personas_list->DisplayRecs - 1;
	else
		$personas_list->StopRec = $personas_list->TotalRecs;
}
$personas_list->RecCnt = $personas_list->StartRec - 1;
if ($personas_list->Recordset && !$personas_list->Recordset->EOF) {
	$personas_list->Recordset->MoveFirst();
	$bSelectLimit = $personas_list->UseSelectLimit;
	if (!$bSelectLimit && $personas_list->StartRec > 1)
		$personas_list->Recordset->Move($personas_list->StartRec - 1);
} elseif (!$personas->AllowAddDeleteRow && $personas_list->StopRec == 0) {
	$personas_list->StopRec = $personas->GridAddRowCount;
}

// Initialize aggregate
$personas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$personas->ResetAttrs();
$personas_list->RenderRow();
while ($personas_list->RecCnt < $personas_list->StopRec) {
	$personas_list->RecCnt++;
	if (intval($personas_list->RecCnt) >= intval($personas_list->StartRec)) {
		$personas_list->RowCnt++;

		// Set up key count
		$personas_list->KeyCount = $personas_list->RowIndex;

		// Init row class and style
		$personas->ResetAttrs();
		$personas->CssClass = "";
		if ($personas->CurrentAction == "gridadd") {
		} else {
			$personas_list->LoadRowValues($personas_list->Recordset); // Load row values
		}
		$personas->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$personas->RowAttrs = array_merge($personas->RowAttrs, array('data-rowindex'=>$personas_list->RowCnt, 'id'=>'r' . $personas_list->RowCnt . '_personas', 'data-rowtype'=>$personas->RowType));

		// Render row
		$personas_list->RenderRow();

		// Render list options
		$personas_list->RenderListOptions();
?>
	<tr<?php echo $personas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$personas_list->ListOptions->Render("body", "left", $personas_list->RowCnt);
?>
	<?php if ($personas->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $personas->Id->CellAttributes() ?>>
<span id="el<?php echo $personas_list->RowCnt ?>_personas_Id" class="personas_Id">
<span<?php echo $personas->Id->ViewAttributes() ?>>
<?php echo $personas->Id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($personas->tipo_documento->Visible) { // tipo_documento ?>
		<td data-name="tipo_documento"<?php echo $personas->tipo_documento->CellAttributes() ?>>
<span id="el<?php echo $personas_list->RowCnt ?>_personas_tipo_documento" class="personas_tipo_documento">
<span<?php echo $personas->tipo_documento->ViewAttributes() ?>>
<?php echo $personas->tipo_documento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($personas->no_documento->Visible) { // no_documento ?>
		<td data-name="no_documento"<?php echo $personas->no_documento->CellAttributes() ?>>
<span id="el<?php echo $personas_list->RowCnt ?>_personas_no_documento" class="personas_no_documento">
<span<?php echo $personas->no_documento->ViewAttributes() ?>>
<?php echo $personas->no_documento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($personas->nombres->Visible) { // nombres ?>
		<td data-name="nombres"<?php echo $personas->nombres->CellAttributes() ?>>
<span id="el<?php echo $personas_list->RowCnt ?>_personas_nombres" class="personas_nombres">
<span<?php echo $personas->nombres->ViewAttributes() ?>>
<?php echo $personas->nombres->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($personas->paterno->Visible) { // paterno ?>
		<td data-name="paterno"<?php echo $personas->paterno->CellAttributes() ?>>
<span id="el<?php echo $personas_list->RowCnt ?>_personas_paterno" class="personas_paterno">
<span<?php echo $personas->paterno->ViewAttributes() ?>>
<?php echo $personas->paterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($personas->materno->Visible) { // materno ?>
		<td data-name="materno"<?php echo $personas->materno->CellAttributes() ?>>
<span id="el<?php echo $personas_list->RowCnt ?>_personas_materno" class="personas_materno">
<span<?php echo $personas->materno->ViewAttributes() ?>>
<?php echo $personas->materno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($personas->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<td data-name="fecha_nacimiento"<?php echo $personas->fecha_nacimiento->CellAttributes() ?>>
<span id="el<?php echo $personas_list->RowCnt ?>_personas_fecha_nacimiento" class="personas_fecha_nacimiento">
<span<?php echo $personas->fecha_nacimiento->ViewAttributes() ?>>
<?php echo $personas->fecha_nacimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($personas->fecha_registro->Visible) { // fecha_registro ?>
		<td data-name="fecha_registro"<?php echo $personas->fecha_registro->CellAttributes() ?>>
<span id="el<?php echo $personas_list->RowCnt ?>_personas_fecha_registro" class="personas_fecha_registro">
<span<?php echo $personas->fecha_registro->ViewAttributes() ?>>
<?php echo $personas->fecha_registro->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($personas->imagen->Visible) { // imagen ?>
		<td data-name="imagen"<?php echo $personas->imagen->CellAttributes() ?>>
<span id="el<?php echo $personas_list->RowCnt ?>_personas_imagen" class="personas_imagen">
<span>
<?php echo ew_GetFileViewTag($personas->imagen, $personas->imagen->ListViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
	<?php if ($personas->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $personas->estado->CellAttributes() ?>>
<span id="el<?php echo $personas_list->RowCnt ?>_personas_estado" class="personas_estado">
<span<?php echo $personas->estado->ViewAttributes() ?>>
<?php echo $personas->estado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$personas_list->ListOptions->Render("body", "right", $personas_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($personas->CurrentAction <> "gridadd")
		$personas_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($personas->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($personas_list->Recordset)
	$personas_list->Recordset->Close();
?>
<?php if ($personas->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($personas->CurrentAction <> "gridadd" && $personas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($personas_list->Pager)) $personas_list->Pager = new cPrevNextPager($personas_list->StartRec, $personas_list->DisplayRecs, $personas_list->TotalRecs, $personas_list->AutoHidePager) ?>
<?php if ($personas_list->Pager->RecordCount > 0 && $personas_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($personas_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $personas_list->PageUrl() ?>start=<?php echo $personas_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($personas_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $personas_list->PageUrl() ?>start=<?php echo $personas_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $personas_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($personas_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $personas_list->PageUrl() ?>start=<?php echo $personas_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($personas_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $personas_list->PageUrl() ?>start=<?php echo $personas_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $personas_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $personas_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $personas_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $personas_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($personas_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($personas_list->TotalRecs == 0 && $personas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($personas_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($personas->Export == "") { ?>
<script type="text/javascript">
fpersonaslistsrch.FilterList = <?php echo $personas_list->GetFilterList() ?>;
fpersonaslistsrch.Init();
fpersonaslist.Init();
</script>
<?php } ?>
<?php
$personas_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($personas->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$personas_list->Page_Terminate();
?>
