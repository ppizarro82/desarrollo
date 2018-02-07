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

$users_list = NULL; // Initialize page object first

class cusers_list extends cusers {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_list';

	// Grid form hidden field names
	var $FormName = 'fuserslist';
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

		// Table object (users)
		if (!isset($GLOBALS["users"]) || get_class($GLOBALS["users"]) == "cusers") {
			$GLOBALS["users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["users"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "usersadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "usersdelete.php";
		$this->MultiUpdateUrl = "usersupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fuserslistsrch";

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
		$this->id_user->SetVisibility();
		$this->id_user->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->User_Level->SetVisibility();
		$this->Username->SetVisibility();
		$this->No_documento->SetVisibility();
		$this->First_Name->SetVisibility();
		$this->Last_Name->SetVisibility();
		$this->_Email->SetVisibility();
		$this->Telefono_movil->SetVisibility();
		$this->Fecha_nacimiento->SetVisibility();
		$this->Locked->SetVisibility();
		$this->acceso_app->SetVisibility();
		$this->fecha_ingreso->SetVisibility();

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
			$this->id_user->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_user->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "fuserslistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id_user->AdvancedSearch->ToJson(), ","); // Field id_user
		$sFilterList = ew_Concat($sFilterList, $this->User_Level->AdvancedSearch->ToJson(), ","); // Field User_Level
		$sFilterList = ew_Concat($sFilterList, $this->id_user_creator->AdvancedSearch->ToJson(), ","); // Field id_user_creator
		$sFilterList = ew_Concat($sFilterList, $this->Username->AdvancedSearch->ToJson(), ","); // Field Username
		$sFilterList = ew_Concat($sFilterList, $this->Password->AdvancedSearch->ToJson(), ","); // Field Password
		$sFilterList = ew_Concat($sFilterList, $this->No_documento->AdvancedSearch->ToJson(), ","); // Field No_documento
		$sFilterList = ew_Concat($sFilterList, $this->Tipo_documento->AdvancedSearch->ToJson(), ","); // Field Tipo_documento
		$sFilterList = ew_Concat($sFilterList, $this->First_Name->AdvancedSearch->ToJson(), ","); // Field First_Name
		$sFilterList = ew_Concat($sFilterList, $this->Last_Name->AdvancedSearch->ToJson(), ","); // Field Last_Name
		$sFilterList = ew_Concat($sFilterList, $this->_Email->AdvancedSearch->ToJson(), ","); // Field Email
		$sFilterList = ew_Concat($sFilterList, $this->Telefono_movil->AdvancedSearch->ToJson(), ","); // Field Telefono_movil
		$sFilterList = ew_Concat($sFilterList, $this->Telefono_fijo->AdvancedSearch->ToJson(), ","); // Field Telefono_fijo
		$sFilterList = ew_Concat($sFilterList, $this->Fecha_nacimiento->AdvancedSearch->ToJson(), ","); // Field Fecha_nacimiento
		$sFilterList = ew_Concat($sFilterList, $this->Report_To->AdvancedSearch->ToJson(), ","); // Field Report_To
		$sFilterList = ew_Concat($sFilterList, $this->Activated->AdvancedSearch->ToJson(), ","); // Field Activated
		$sFilterList = ew_Concat($sFilterList, $this->Locked->AdvancedSearch->ToJson(), ","); // Field Locked
		$sFilterList = ew_Concat($sFilterList, $this->token->AdvancedSearch->ToJson(), ","); // Field token
		$sFilterList = ew_Concat($sFilterList, $this->acceso_app->AdvancedSearch->ToJson(), ","); // Field acceso_app
		$sFilterList = ew_Concat($sFilterList, $this->observaciones->AdvancedSearch->ToJson(), ","); // Field observaciones
		$sFilterList = ew_Concat($sFilterList, $this->fecha_ingreso->AdvancedSearch->ToJson(), ","); // Field fecha_ingreso
		$sFilterList = ew_Concat($sFilterList, $this->Profile->AdvancedSearch->ToJson(), ","); // Field Profile
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fuserslistsrch", $filters);

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

		// Field id_user
		$this->id_user->AdvancedSearch->SearchValue = @$filter["x_id_user"];
		$this->id_user->AdvancedSearch->SearchOperator = @$filter["z_id_user"];
		$this->id_user->AdvancedSearch->SearchCondition = @$filter["v_id_user"];
		$this->id_user->AdvancedSearch->SearchValue2 = @$filter["y_id_user"];
		$this->id_user->AdvancedSearch->SearchOperator2 = @$filter["w_id_user"];
		$this->id_user->AdvancedSearch->Save();

		// Field User_Level
		$this->User_Level->AdvancedSearch->SearchValue = @$filter["x_User_Level"];
		$this->User_Level->AdvancedSearch->SearchOperator = @$filter["z_User_Level"];
		$this->User_Level->AdvancedSearch->SearchCondition = @$filter["v_User_Level"];
		$this->User_Level->AdvancedSearch->SearchValue2 = @$filter["y_User_Level"];
		$this->User_Level->AdvancedSearch->SearchOperator2 = @$filter["w_User_Level"];
		$this->User_Level->AdvancedSearch->Save();

		// Field id_user_creator
		$this->id_user_creator->AdvancedSearch->SearchValue = @$filter["x_id_user_creator"];
		$this->id_user_creator->AdvancedSearch->SearchOperator = @$filter["z_id_user_creator"];
		$this->id_user_creator->AdvancedSearch->SearchCondition = @$filter["v_id_user_creator"];
		$this->id_user_creator->AdvancedSearch->SearchValue2 = @$filter["y_id_user_creator"];
		$this->id_user_creator->AdvancedSearch->SearchOperator2 = @$filter["w_id_user_creator"];
		$this->id_user_creator->AdvancedSearch->Save();

		// Field Username
		$this->Username->AdvancedSearch->SearchValue = @$filter["x_Username"];
		$this->Username->AdvancedSearch->SearchOperator = @$filter["z_Username"];
		$this->Username->AdvancedSearch->SearchCondition = @$filter["v_Username"];
		$this->Username->AdvancedSearch->SearchValue2 = @$filter["y_Username"];
		$this->Username->AdvancedSearch->SearchOperator2 = @$filter["w_Username"];
		$this->Username->AdvancedSearch->Save();

		// Field Password
		$this->Password->AdvancedSearch->SearchValue = @$filter["x_Password"];
		$this->Password->AdvancedSearch->SearchOperator = @$filter["z_Password"];
		$this->Password->AdvancedSearch->SearchCondition = @$filter["v_Password"];
		$this->Password->AdvancedSearch->SearchValue2 = @$filter["y_Password"];
		$this->Password->AdvancedSearch->SearchOperator2 = @$filter["w_Password"];
		$this->Password->AdvancedSearch->Save();

		// Field No_documento
		$this->No_documento->AdvancedSearch->SearchValue = @$filter["x_No_documento"];
		$this->No_documento->AdvancedSearch->SearchOperator = @$filter["z_No_documento"];
		$this->No_documento->AdvancedSearch->SearchCondition = @$filter["v_No_documento"];
		$this->No_documento->AdvancedSearch->SearchValue2 = @$filter["y_No_documento"];
		$this->No_documento->AdvancedSearch->SearchOperator2 = @$filter["w_No_documento"];
		$this->No_documento->AdvancedSearch->Save();

		// Field Tipo_documento
		$this->Tipo_documento->AdvancedSearch->SearchValue = @$filter["x_Tipo_documento"];
		$this->Tipo_documento->AdvancedSearch->SearchOperator = @$filter["z_Tipo_documento"];
		$this->Tipo_documento->AdvancedSearch->SearchCondition = @$filter["v_Tipo_documento"];
		$this->Tipo_documento->AdvancedSearch->SearchValue2 = @$filter["y_Tipo_documento"];
		$this->Tipo_documento->AdvancedSearch->SearchOperator2 = @$filter["w_Tipo_documento"];
		$this->Tipo_documento->AdvancedSearch->Save();

		// Field First_Name
		$this->First_Name->AdvancedSearch->SearchValue = @$filter["x_First_Name"];
		$this->First_Name->AdvancedSearch->SearchOperator = @$filter["z_First_Name"];
		$this->First_Name->AdvancedSearch->SearchCondition = @$filter["v_First_Name"];
		$this->First_Name->AdvancedSearch->SearchValue2 = @$filter["y_First_Name"];
		$this->First_Name->AdvancedSearch->SearchOperator2 = @$filter["w_First_Name"];
		$this->First_Name->AdvancedSearch->Save();

		// Field Last_Name
		$this->Last_Name->AdvancedSearch->SearchValue = @$filter["x_Last_Name"];
		$this->Last_Name->AdvancedSearch->SearchOperator = @$filter["z_Last_Name"];
		$this->Last_Name->AdvancedSearch->SearchCondition = @$filter["v_Last_Name"];
		$this->Last_Name->AdvancedSearch->SearchValue2 = @$filter["y_Last_Name"];
		$this->Last_Name->AdvancedSearch->SearchOperator2 = @$filter["w_Last_Name"];
		$this->Last_Name->AdvancedSearch->Save();

		// Field Email
		$this->_Email->AdvancedSearch->SearchValue = @$filter["x__Email"];
		$this->_Email->AdvancedSearch->SearchOperator = @$filter["z__Email"];
		$this->_Email->AdvancedSearch->SearchCondition = @$filter["v__Email"];
		$this->_Email->AdvancedSearch->SearchValue2 = @$filter["y__Email"];
		$this->_Email->AdvancedSearch->SearchOperator2 = @$filter["w__Email"];
		$this->_Email->AdvancedSearch->Save();

		// Field Telefono_movil
		$this->Telefono_movil->AdvancedSearch->SearchValue = @$filter["x_Telefono_movil"];
		$this->Telefono_movil->AdvancedSearch->SearchOperator = @$filter["z_Telefono_movil"];
		$this->Telefono_movil->AdvancedSearch->SearchCondition = @$filter["v_Telefono_movil"];
		$this->Telefono_movil->AdvancedSearch->SearchValue2 = @$filter["y_Telefono_movil"];
		$this->Telefono_movil->AdvancedSearch->SearchOperator2 = @$filter["w_Telefono_movil"];
		$this->Telefono_movil->AdvancedSearch->Save();

		// Field Telefono_fijo
		$this->Telefono_fijo->AdvancedSearch->SearchValue = @$filter["x_Telefono_fijo"];
		$this->Telefono_fijo->AdvancedSearch->SearchOperator = @$filter["z_Telefono_fijo"];
		$this->Telefono_fijo->AdvancedSearch->SearchCondition = @$filter["v_Telefono_fijo"];
		$this->Telefono_fijo->AdvancedSearch->SearchValue2 = @$filter["y_Telefono_fijo"];
		$this->Telefono_fijo->AdvancedSearch->SearchOperator2 = @$filter["w_Telefono_fijo"];
		$this->Telefono_fijo->AdvancedSearch->Save();

		// Field Fecha_nacimiento
		$this->Fecha_nacimiento->AdvancedSearch->SearchValue = @$filter["x_Fecha_nacimiento"];
		$this->Fecha_nacimiento->AdvancedSearch->SearchOperator = @$filter["z_Fecha_nacimiento"];
		$this->Fecha_nacimiento->AdvancedSearch->SearchCondition = @$filter["v_Fecha_nacimiento"];
		$this->Fecha_nacimiento->AdvancedSearch->SearchValue2 = @$filter["y_Fecha_nacimiento"];
		$this->Fecha_nacimiento->AdvancedSearch->SearchOperator2 = @$filter["w_Fecha_nacimiento"];
		$this->Fecha_nacimiento->AdvancedSearch->Save();

		// Field Report_To
		$this->Report_To->AdvancedSearch->SearchValue = @$filter["x_Report_To"];
		$this->Report_To->AdvancedSearch->SearchOperator = @$filter["z_Report_To"];
		$this->Report_To->AdvancedSearch->SearchCondition = @$filter["v_Report_To"];
		$this->Report_To->AdvancedSearch->SearchValue2 = @$filter["y_Report_To"];
		$this->Report_To->AdvancedSearch->SearchOperator2 = @$filter["w_Report_To"];
		$this->Report_To->AdvancedSearch->Save();

		// Field Activated
		$this->Activated->AdvancedSearch->SearchValue = @$filter["x_Activated"];
		$this->Activated->AdvancedSearch->SearchOperator = @$filter["z_Activated"];
		$this->Activated->AdvancedSearch->SearchCondition = @$filter["v_Activated"];
		$this->Activated->AdvancedSearch->SearchValue2 = @$filter["y_Activated"];
		$this->Activated->AdvancedSearch->SearchOperator2 = @$filter["w_Activated"];
		$this->Activated->AdvancedSearch->Save();

		// Field Locked
		$this->Locked->AdvancedSearch->SearchValue = @$filter["x_Locked"];
		$this->Locked->AdvancedSearch->SearchOperator = @$filter["z_Locked"];
		$this->Locked->AdvancedSearch->SearchCondition = @$filter["v_Locked"];
		$this->Locked->AdvancedSearch->SearchValue2 = @$filter["y_Locked"];
		$this->Locked->AdvancedSearch->SearchOperator2 = @$filter["w_Locked"];
		$this->Locked->AdvancedSearch->Save();

		// Field token
		$this->token->AdvancedSearch->SearchValue = @$filter["x_token"];
		$this->token->AdvancedSearch->SearchOperator = @$filter["z_token"];
		$this->token->AdvancedSearch->SearchCondition = @$filter["v_token"];
		$this->token->AdvancedSearch->SearchValue2 = @$filter["y_token"];
		$this->token->AdvancedSearch->SearchOperator2 = @$filter["w_token"];
		$this->token->AdvancedSearch->Save();

		// Field acceso_app
		$this->acceso_app->AdvancedSearch->SearchValue = @$filter["x_acceso_app"];
		$this->acceso_app->AdvancedSearch->SearchOperator = @$filter["z_acceso_app"];
		$this->acceso_app->AdvancedSearch->SearchCondition = @$filter["v_acceso_app"];
		$this->acceso_app->AdvancedSearch->SearchValue2 = @$filter["y_acceso_app"];
		$this->acceso_app->AdvancedSearch->SearchOperator2 = @$filter["w_acceso_app"];
		$this->acceso_app->AdvancedSearch->Save();

		// Field observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$filter["x_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator = @$filter["z_observaciones"];
		$this->observaciones->AdvancedSearch->SearchCondition = @$filter["v_observaciones"];
		$this->observaciones->AdvancedSearch->SearchValue2 = @$filter["y_observaciones"];
		$this->observaciones->AdvancedSearch->SearchOperator2 = @$filter["w_observaciones"];
		$this->observaciones->AdvancedSearch->Save();

		// Field fecha_ingreso
		$this->fecha_ingreso->AdvancedSearch->SearchValue = @$filter["x_fecha_ingreso"];
		$this->fecha_ingreso->AdvancedSearch->SearchOperator = @$filter["z_fecha_ingreso"];
		$this->fecha_ingreso->AdvancedSearch->SearchCondition = @$filter["v_fecha_ingreso"];
		$this->fecha_ingreso->AdvancedSearch->SearchValue2 = @$filter["y_fecha_ingreso"];
		$this->fecha_ingreso->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_ingreso"];
		$this->fecha_ingreso->AdvancedSearch->Save();

		// Field Profile
		$this->Profile->AdvancedSearch->SearchValue = @$filter["x_Profile"];
		$this->Profile->AdvancedSearch->SearchOperator = @$filter["z_Profile"];
		$this->Profile->AdvancedSearch->SearchCondition = @$filter["v_Profile"];
		$this->Profile->AdvancedSearch->SearchValue2 = @$filter["y_Profile"];
		$this->Profile->AdvancedSearch->SearchOperator2 = @$filter["w_Profile"];
		$this->Profile->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id_user, $Default, FALSE); // id_user
		$this->BuildSearchSql($sWhere, $this->User_Level, $Default, FALSE); // User_Level
		$this->BuildSearchSql($sWhere, $this->id_user_creator, $Default, FALSE); // id_user_creator
		$this->BuildSearchSql($sWhere, $this->Username, $Default, FALSE); // Username
		$this->BuildSearchSql($sWhere, $this->Password, $Default, FALSE); // Password
		$this->BuildSearchSql($sWhere, $this->No_documento, $Default, FALSE); // No_documento
		$this->BuildSearchSql($sWhere, $this->Tipo_documento, $Default, FALSE); // Tipo_documento
		$this->BuildSearchSql($sWhere, $this->First_Name, $Default, FALSE); // First_Name
		$this->BuildSearchSql($sWhere, $this->Last_Name, $Default, FALSE); // Last_Name
		$this->BuildSearchSql($sWhere, $this->_Email, $Default, FALSE); // Email
		$this->BuildSearchSql($sWhere, $this->Telefono_movil, $Default, FALSE); // Telefono_movil
		$this->BuildSearchSql($sWhere, $this->Telefono_fijo, $Default, FALSE); // Telefono_fijo
		$this->BuildSearchSql($sWhere, $this->Fecha_nacimiento, $Default, FALSE); // Fecha_nacimiento
		$this->BuildSearchSql($sWhere, $this->Report_To, $Default, FALSE); // Report_To
		$this->BuildSearchSql($sWhere, $this->Activated, $Default, FALSE); // Activated
		$this->BuildSearchSql($sWhere, $this->Locked, $Default, FALSE); // Locked
		$this->BuildSearchSql($sWhere, $this->token, $Default, FALSE); // token
		$this->BuildSearchSql($sWhere, $this->acceso_app, $Default, FALSE); // acceso_app
		$this->BuildSearchSql($sWhere, $this->observaciones, $Default, FALSE); // observaciones
		$this->BuildSearchSql($sWhere, $this->fecha_ingreso, $Default, FALSE); // fecha_ingreso
		$this->BuildSearchSql($sWhere, $this->Profile, $Default, FALSE); // Profile

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id_user->AdvancedSearch->Save(); // id_user
			$this->User_Level->AdvancedSearch->Save(); // User_Level
			$this->id_user_creator->AdvancedSearch->Save(); // id_user_creator
			$this->Username->AdvancedSearch->Save(); // Username
			$this->Password->AdvancedSearch->Save(); // Password
			$this->No_documento->AdvancedSearch->Save(); // No_documento
			$this->Tipo_documento->AdvancedSearch->Save(); // Tipo_documento
			$this->First_Name->AdvancedSearch->Save(); // First_Name
			$this->Last_Name->AdvancedSearch->Save(); // Last_Name
			$this->_Email->AdvancedSearch->Save(); // Email
			$this->Telefono_movil->AdvancedSearch->Save(); // Telefono_movil
			$this->Telefono_fijo->AdvancedSearch->Save(); // Telefono_fijo
			$this->Fecha_nacimiento->AdvancedSearch->Save(); // Fecha_nacimiento
			$this->Report_To->AdvancedSearch->Save(); // Report_To
			$this->Activated->AdvancedSearch->Save(); // Activated
			$this->Locked->AdvancedSearch->Save(); // Locked
			$this->token->AdvancedSearch->Save(); // token
			$this->acceso_app->AdvancedSearch->Save(); // acceso_app
			$this->observaciones->AdvancedSearch->Save(); // observaciones
			$this->fecha_ingreso->AdvancedSearch->Save(); // fecha_ingreso
			$this->Profile->AdvancedSearch->Save(); // Profile
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
		$this->BuildBasicSearchSQL($sWhere, $this->Username, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Password, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->No_documento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Tipo_documento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->First_Name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Last_Name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_Email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Telefono_movil, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Telefono_fijo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->token, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->observaciones, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Profile, $arKeywords, $type);
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
		if ($this->id_user->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->User_Level->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_user_creator->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Username->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Password->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->No_documento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Tipo_documento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->First_Name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Last_Name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_Email->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Telefono_movil->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Telefono_fijo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Fecha_nacimiento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Report_To->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Activated->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Locked->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->token->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->acceso_app->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->observaciones->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_ingreso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Profile->AdvancedSearch->IssetSession())
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
		$this->id_user->AdvancedSearch->UnsetSession();
		$this->User_Level->AdvancedSearch->UnsetSession();
		$this->id_user_creator->AdvancedSearch->UnsetSession();
		$this->Username->AdvancedSearch->UnsetSession();
		$this->Password->AdvancedSearch->UnsetSession();
		$this->No_documento->AdvancedSearch->UnsetSession();
		$this->Tipo_documento->AdvancedSearch->UnsetSession();
		$this->First_Name->AdvancedSearch->UnsetSession();
		$this->Last_Name->AdvancedSearch->UnsetSession();
		$this->_Email->AdvancedSearch->UnsetSession();
		$this->Telefono_movil->AdvancedSearch->UnsetSession();
		$this->Telefono_fijo->AdvancedSearch->UnsetSession();
		$this->Fecha_nacimiento->AdvancedSearch->UnsetSession();
		$this->Report_To->AdvancedSearch->UnsetSession();
		$this->Activated->AdvancedSearch->UnsetSession();
		$this->Locked->AdvancedSearch->UnsetSession();
		$this->token->AdvancedSearch->UnsetSession();
		$this->acceso_app->AdvancedSearch->UnsetSession();
		$this->observaciones->AdvancedSearch->UnsetSession();
		$this->fecha_ingreso->AdvancedSearch->UnsetSession();
		$this->Profile->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id_user->AdvancedSearch->Load();
		$this->User_Level->AdvancedSearch->Load();
		$this->id_user_creator->AdvancedSearch->Load();
		$this->Username->AdvancedSearch->Load();
		$this->Password->AdvancedSearch->Load();
		$this->No_documento->AdvancedSearch->Load();
		$this->Tipo_documento->AdvancedSearch->Load();
		$this->First_Name->AdvancedSearch->Load();
		$this->Last_Name->AdvancedSearch->Load();
		$this->_Email->AdvancedSearch->Load();
		$this->Telefono_movil->AdvancedSearch->Load();
		$this->Telefono_fijo->AdvancedSearch->Load();
		$this->Fecha_nacimiento->AdvancedSearch->Load();
		$this->Report_To->AdvancedSearch->Load();
		$this->Activated->AdvancedSearch->Load();
		$this->Locked->AdvancedSearch->Load();
		$this->token->AdvancedSearch->Load();
		$this->acceso_app->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->fecha_ingreso->AdvancedSearch->Load();
		$this->Profile->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_user); // id_user
			$this->UpdateSort($this->User_Level); // User_Level
			$this->UpdateSort($this->Username); // Username
			$this->UpdateSort($this->No_documento); // No_documento
			$this->UpdateSort($this->First_Name); // First_Name
			$this->UpdateSort($this->Last_Name); // Last_Name
			$this->UpdateSort($this->_Email); // Email
			$this->UpdateSort($this->Telefono_movil); // Telefono_movil
			$this->UpdateSort($this->Fecha_nacimiento); // Fecha_nacimiento
			$this->UpdateSort($this->Locked); // Locked
			$this->UpdateSort($this->acceso_app); // acceso_app
			$this->UpdateSort($this->fecha_ingreso); // fecha_ingreso
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
				$this->id_user->setSort("");
				$this->User_Level->setSort("");
				$this->Username->setSort("");
				$this->No_documento->setSort("");
				$this->First_Name->setSort("");
				$this->Last_Name->setSort("");
				$this->_Email->setSort("");
				$this->Telefono_movil->setSort("");
				$this->Fecha_nacimiento->setSort("");
				$this->Locked->setSort("");
				$this->acceso_app->setSort("");
				$this->fecha_ingreso->setSort("");
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
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"users\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
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
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"users\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'EditBtn',url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("EditLink") . "</a>";
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
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-table=\"users\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("CopyLink") . "</a>";
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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->id_user->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
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
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-table=\"users\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fuserslist,url:'" . $this->MultiDeleteUrl . "',msg:ewLanguage.Phrase('DeleteConfirmMsg')});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fuserslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fuserslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fuserslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		global $UserProfile;
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
					$user = $row['Username'];
					if ($userlist <> "") $userlist .= ",";
					$userlist .= $user;
					if ($UserAction == "resendregisteremail")
						$Processed = FALSE;
					elseif ($UserAction == "resetconcurrentuser")
						$Processed = FALSE;
					elseif ($UserAction == "resetloginretry")
						$Processed = FALSE;
					elseif ($UserAction == "setpasswordexpired")
						$Processed = FALSE;
					else
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fuserslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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
		// id_user

		$this->id_user->AdvancedSearch->SearchValue = @$_GET["x_id_user"];
		if ($this->id_user->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_user->AdvancedSearch->SearchOperator = @$_GET["z_id_user"];

		// User_Level
		$this->User_Level->AdvancedSearch->SearchValue = @$_GET["x_User_Level"];
		if ($this->User_Level->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->User_Level->AdvancedSearch->SearchOperator = @$_GET["z_User_Level"];

		// id_user_creator
		$this->id_user_creator->AdvancedSearch->SearchValue = @$_GET["x_id_user_creator"];
		if ($this->id_user_creator->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_user_creator->AdvancedSearch->SearchOperator = @$_GET["z_id_user_creator"];

		// Username
		$this->Username->AdvancedSearch->SearchValue = @$_GET["x_Username"];
		if ($this->Username->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->Username->AdvancedSearch->SearchOperator = @$_GET["z_Username"];

		// Password
		$this->Password->AdvancedSearch->SearchValue = @$_GET["x_Password"];
		if ($this->Password->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->Password->AdvancedSearch->SearchOperator = @$_GET["z_Password"];

		// No_documento
		$this->No_documento->AdvancedSearch->SearchValue = @$_GET["x_No_documento"];
		if ($this->No_documento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->No_documento->AdvancedSearch->SearchOperator = @$_GET["z_No_documento"];

		// Tipo_documento
		$this->Tipo_documento->AdvancedSearch->SearchValue = @$_GET["x_Tipo_documento"];
		if ($this->Tipo_documento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->Tipo_documento->AdvancedSearch->SearchOperator = @$_GET["z_Tipo_documento"];

		// First_Name
		$this->First_Name->AdvancedSearch->SearchValue = @$_GET["x_First_Name"];
		if ($this->First_Name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->First_Name->AdvancedSearch->SearchOperator = @$_GET["z_First_Name"];

		// Last_Name
		$this->Last_Name->AdvancedSearch->SearchValue = @$_GET["x_Last_Name"];
		if ($this->Last_Name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->Last_Name->AdvancedSearch->SearchOperator = @$_GET["z_Last_Name"];

		// Email
		$this->_Email->AdvancedSearch->SearchValue = @$_GET["x__Email"];
		if ($this->_Email->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->_Email->AdvancedSearch->SearchOperator = @$_GET["z__Email"];

		// Telefono_movil
		$this->Telefono_movil->AdvancedSearch->SearchValue = @$_GET["x_Telefono_movil"];
		if ($this->Telefono_movil->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->Telefono_movil->AdvancedSearch->SearchOperator = @$_GET["z_Telefono_movil"];

		// Telefono_fijo
		$this->Telefono_fijo->AdvancedSearch->SearchValue = @$_GET["x_Telefono_fijo"];
		if ($this->Telefono_fijo->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->Telefono_fijo->AdvancedSearch->SearchOperator = @$_GET["z_Telefono_fijo"];

		// Fecha_nacimiento
		$this->Fecha_nacimiento->AdvancedSearch->SearchValue = @$_GET["x_Fecha_nacimiento"];
		if ($this->Fecha_nacimiento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->Fecha_nacimiento->AdvancedSearch->SearchOperator = @$_GET["z_Fecha_nacimiento"];

		// Report_To
		$this->Report_To->AdvancedSearch->SearchValue = @$_GET["x_Report_To"];
		if ($this->Report_To->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->Report_To->AdvancedSearch->SearchOperator = @$_GET["z_Report_To"];

		// Activated
		$this->Activated->AdvancedSearch->SearchValue = @$_GET["x_Activated"];
		if ($this->Activated->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->Activated->AdvancedSearch->SearchOperator = @$_GET["z_Activated"];

		// Locked
		$this->Locked->AdvancedSearch->SearchValue = @$_GET["x_Locked"];
		if ($this->Locked->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->Locked->AdvancedSearch->SearchOperator = @$_GET["z_Locked"];

		// token
		$this->token->AdvancedSearch->SearchValue = @$_GET["x_token"];
		if ($this->token->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->token->AdvancedSearch->SearchOperator = @$_GET["z_token"];

		// acceso_app
		$this->acceso_app->AdvancedSearch->SearchValue = @$_GET["x_acceso_app"];
		if ($this->acceso_app->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->acceso_app->AdvancedSearch->SearchOperator = @$_GET["z_acceso_app"];

		// observaciones
		$this->observaciones->AdvancedSearch->SearchValue = @$_GET["x_observaciones"];
		if ($this->observaciones->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->observaciones->AdvancedSearch->SearchOperator = @$_GET["z_observaciones"];

		// fecha_ingreso
		$this->fecha_ingreso->AdvancedSearch->SearchValue = @$_GET["x_fecha_ingreso"];
		if ($this->fecha_ingreso->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fecha_ingreso->AdvancedSearch->SearchOperator = @$_GET["z_fecha_ingreso"];

		// Profile
		$this->Profile->AdvancedSearch->SearchValue = @$_GET["x_Profile"];
		if ($this->Profile->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->Profile->AdvancedSearch->SearchOperator = @$_GET["z_Profile"];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

		// No_documento
		$this->No_documento->ViewValue = $this->No_documento->CurrentValue;
		$this->No_documento->ViewCustomAttributes = "";

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

		// fecha_ingreso
		$this->fecha_ingreso->ViewValue = $this->fecha_ingreso->CurrentValue;
		$this->fecha_ingreso->ViewValue = ew_FormatDateTime($this->fecha_ingreso->ViewValue, 0);
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

			// No_documento
			$this->No_documento->LinkCustomAttributes = "";
			$this->No_documento->HrefValue = "";
			$this->No_documento->TooltipValue = "";

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

			// Locked
			$this->Locked->LinkCustomAttributes = "";
			$this->Locked->HrefValue = "";
			$this->Locked->TooltipValue = "";

			// acceso_app
			$this->acceso_app->LinkCustomAttributes = "";
			$this->acceso_app->HrefValue = "";
			$this->acceso_app->TooltipValue = "";

			// fecha_ingreso
			$this->fecha_ingreso->LinkCustomAttributes = "";
			$this->fecha_ingreso->HrefValue = "";
			$this->fecha_ingreso->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id_user
			$this->id_user->EditAttrs["class"] = "form-control";
			$this->id_user->EditCustomAttributes = "";
			$this->id_user->EditValue = ew_HtmlEncode($this->id_user->AdvancedSearch->SearchValue);
			$this->id_user->PlaceHolder = ew_RemoveHtml($this->id_user->FldCaption());

			// User_Level
			$this->User_Level->EditAttrs["class"] = "form-control";
			$this->User_Level->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->User_Level->EditValue = $Language->Phrase("PasswordMask");
			} else {
			if (trim(strval($this->User_Level->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->User_Level->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
			$this->Username->EditValue = ew_HtmlEncode($this->Username->AdvancedSearch->SearchValue);
			$this->Username->PlaceHolder = ew_RemoveHtml($this->Username->FldCaption());

			// No_documento
			$this->No_documento->EditAttrs["class"] = "form-control";
			$this->No_documento->EditCustomAttributes = "";
			$this->No_documento->EditValue = ew_HtmlEncode($this->No_documento->AdvancedSearch->SearchValue);
			$this->No_documento->PlaceHolder = ew_RemoveHtml($this->No_documento->FldCaption());

			// First_Name
			$this->First_Name->EditAttrs["class"] = "form-control";
			$this->First_Name->EditCustomAttributes = "";
			$this->First_Name->EditValue = ew_HtmlEncode($this->First_Name->AdvancedSearch->SearchValue);
			$this->First_Name->PlaceHolder = ew_RemoveHtml($this->First_Name->FldCaption());

			// Last_Name
			$this->Last_Name->EditAttrs["class"] = "form-control";
			$this->Last_Name->EditCustomAttributes = "";
			$this->Last_Name->EditValue = ew_HtmlEncode($this->Last_Name->AdvancedSearch->SearchValue);
			$this->Last_Name->PlaceHolder = ew_RemoveHtml($this->Last_Name->FldCaption());

			// Email
			$this->_Email->EditAttrs["class"] = "form-control";
			$this->_Email->EditCustomAttributes = "";
			$this->_Email->EditValue = ew_HtmlEncode($this->_Email->AdvancedSearch->SearchValue);
			$this->_Email->PlaceHolder = ew_RemoveHtml($this->_Email->FldCaption());

			// Telefono_movil
			$this->Telefono_movil->EditAttrs["class"] = "form-control";
			$this->Telefono_movil->EditCustomAttributes = "";
			$this->Telefono_movil->EditValue = ew_HtmlEncode($this->Telefono_movil->AdvancedSearch->SearchValue);
			$this->Telefono_movil->PlaceHolder = ew_RemoveHtml($this->Telefono_movil->FldCaption());

			// Fecha_nacimiento
			$this->Fecha_nacimiento->EditAttrs["class"] = "form-control";
			$this->Fecha_nacimiento->EditCustomAttributes = "";
			$this->Fecha_nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Fecha_nacimiento->AdvancedSearch->SearchValue, 17), 17));
			$this->Fecha_nacimiento->PlaceHolder = ew_RemoveHtml($this->Fecha_nacimiento->FldCaption());

			// Locked
			$this->Locked->EditCustomAttributes = "";
			$this->Locked->EditValue = $this->Locked->Options(FALSE);

			// acceso_app
			$this->acceso_app->EditCustomAttributes = "";
			$this->acceso_app->EditValue = $this->acceso_app->Options(FALSE);

			// fecha_ingreso
			$this->fecha_ingreso->EditAttrs["class"] = "form-control";
			$this->fecha_ingreso->EditCustomAttributes = "";
			$this->fecha_ingreso->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha_ingreso->AdvancedSearch->SearchValue, 0), 8));
			$this->fecha_ingreso->PlaceHolder = ew_RemoveHtml($this->fecha_ingreso->FldCaption());
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
		$this->id_user->AdvancedSearch->Load();
		$this->User_Level->AdvancedSearch->Load();
		$this->id_user_creator->AdvancedSearch->Load();
		$this->Username->AdvancedSearch->Load();
		$this->Password->AdvancedSearch->Load();
		$this->No_documento->AdvancedSearch->Load();
		$this->Tipo_documento->AdvancedSearch->Load();
		$this->First_Name->AdvancedSearch->Load();
		$this->Last_Name->AdvancedSearch->Load();
		$this->_Email->AdvancedSearch->Load();
		$this->Telefono_movil->AdvancedSearch->Load();
		$this->Telefono_fijo->AdvancedSearch->Load();
		$this->Fecha_nacimiento->AdvancedSearch->Load();
		$this->Report_To->AdvancedSearch->Load();
		$this->Activated->AdvancedSearch->Load();
		$this->Locked->AdvancedSearch->Load();
		$this->token->AdvancedSearch->Load();
		$this->acceso_app->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->fecha_ingreso->AdvancedSearch->Load();
		$this->Profile->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_users\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_users',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fuserslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$this->AddSearchQueryString($sQry, $this->id_user); // id_user
		$this->AddSearchQueryString($sQry, $this->User_Level); // User_Level
		$this->AddSearchQueryString($sQry, $this->id_user_creator); // id_user_creator
		$this->AddSearchQueryString($sQry, $this->Username); // Username
		$this->AddSearchQueryString($sQry, $this->Password); // Password
		$this->AddSearchQueryString($sQry, $this->No_documento); // No_documento
		$this->AddSearchQueryString($sQry, $this->Tipo_documento); // Tipo_documento
		$this->AddSearchQueryString($sQry, $this->First_Name); // First_Name
		$this->AddSearchQueryString($sQry, $this->Last_Name); // Last_Name
		$this->AddSearchQueryString($sQry, $this->_Email); // Email
		$this->AddSearchQueryString($sQry, $this->Telefono_movil); // Telefono_movil
		$this->AddSearchQueryString($sQry, $this->Telefono_fijo); // Telefono_fijo
		$this->AddSearchQueryString($sQry, $this->Fecha_nacimiento); // Fecha_nacimiento
		$this->AddSearchQueryString($sQry, $this->Report_To); // Report_To
		$this->AddSearchQueryString($sQry, $this->Activated); // Activated
		$this->AddSearchQueryString($sQry, $this->Locked); // Locked
		$this->AddSearchQueryString($sQry, $this->token); // token
		$this->AddSearchQueryString($sQry, $this->acceso_app); // acceso_app
		$this->AddSearchQueryString($sQry, $this->observaciones); // observaciones
		$this->AddSearchQueryString($sQry, $this->fecha_ingreso); // fecha_ingreso
		$this->AddSearchQueryString($sQry, $this->Profile); // Profile

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
if (!isset($users_list)) $users_list = new cusers_list();

// Page init
$users_list->Page_Init();

// Page main
$users_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$users_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($users->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fuserslist = new ew_Form("fuserslist", "list");
fuserslist.FormKeyCountName = '<?php echo $users_list->FormKeyCountName ?>';

// Form_CustomValidate event
fuserslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fuserslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fuserslist.Lists["x_User_Level"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};
fuserslist.Lists["x_User_Level"].Data = "<?php echo $users_list->User_Level->LookupFilterQuery(FALSE, "list") ?>";
fuserslist.Lists["x_Locked"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserslist.Lists["x_Locked"].Options = <?php echo json_encode($users_list->Locked->Options()) ?>;
fuserslist.Lists["x_acceso_app"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserslist.Lists["x_acceso_app"].Options = <?php echo json_encode($users_list->acceso_app->Options()) ?>;

// Form object for search
var CurrentSearchForm = fuserslistsrch = new ew_Form("fuserslistsrch");

// Validate function for search
fuserslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fuserslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fuserslistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fuserslistsrch.Lists["x_User_Level"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};
fuserslistsrch.Lists["x_User_Level"].Data = "<?php echo $users_list->User_Level->LookupFilterQuery(FALSE, "extbs") ?>";

// Init search panel as collapsed
if (fuserslistsrch) fuserslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($users->Export == "") { ?>
<div class="ewToolbar">
<?php if ($users_list->TotalRecs > 0 && $users_list->ExportOptions->Visible()) { ?>
<?php $users_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($users_list->SearchOptions->Visible()) { ?>
<?php $users_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($users_list->FilterOptions->Visible()) { ?>
<?php $users_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $users_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($users_list->TotalRecs <= 0)
			$users_list->TotalRecs = $users->ListRecordCount();
	} else {
		if (!$users_list->Recordset && ($users_list->Recordset = $users_list->LoadRecordset()))
			$users_list->TotalRecs = $users_list->Recordset->RecordCount();
	}
	$users_list->StartRec = 1;
	if ($users_list->DisplayRecs <= 0 || ($users->Export <> "" && $users->ExportAll)) // Display all records
		$users_list->DisplayRecs = $users_list->TotalRecs;
	if (!($users->Export <> "" && $users->ExportAll))
		$users_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$users_list->Recordset = $users_list->LoadRecordset($users_list->StartRec-1, $users_list->DisplayRecs);

	// Set no record found message
	if ($users->CurrentAction == "" && $users_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$users_list->setWarningMessage(ew_DeniedMsg());
		if ($users_list->SearchWhere == "0=101")
			$users_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$users_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$users_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($users->Export == "" && $users->CurrentAction == "") { ?>
<form name="fuserslistsrch" id="fuserslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($users_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fuserslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="users">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$users_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$users->RowType = EW_ROWTYPE_SEARCH;

// Render row
$users->ResetAttrs();
$users_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($users->User_Level->Visible) { // User_Level ?>
	<div id="xsc_User_Level" class="ewCell form-group">
		<label for="x_User_Level" class="ewSearchCaption ewLabel"><?php echo $users->User_Level->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_User_Level" id="z_User_Level" value="="></span>
		<span class="ewSearchField">
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<p class="form-control-static"><?php echo $users->User_Level->EditValue ?></p>
<?php } else { ?>
<select data-table="users" data-field="x_User_Level" data-value-separator="<?php echo $users->User_Level->DisplayValueSeparatorAttribute() ?>" id="x_User_Level" name="x_User_Level"<?php echo $users->User_Level->EditAttributes() ?>>
<?php echo $users->User_Level->SelectOptionListHtml("x_User_Level") ?>
</select>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($users->Username->Visible) { // Username ?>
	<div id="xsc_Username" class="ewCell form-group">
		<label for="x_Username" class="ewSearchCaption ewLabel"><?php echo $users->Username->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Username" id="z_Username" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="users" data-field="x_Username" name="x_Username" id="x_Username" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->Username->getPlaceHolder()) ?>" value="<?php echo $users->Username->EditValue ?>"<?php echo $users->Username->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($users->First_Name->Visible) { // First_Name ?>
	<div id="xsc_First_Name" class="ewCell form-group">
		<label for="x_First_Name" class="ewSearchCaption ewLabel"><?php echo $users->First_Name->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_First_Name" id="z_First_Name" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="users" data-field="x_First_Name" name="x_First_Name" id="x_First_Name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->First_Name->getPlaceHolder()) ?>" value="<?php echo $users->First_Name->EditValue ?>"<?php echo $users->First_Name->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($users->Last_Name->Visible) { // Last_Name ?>
	<div id="xsc_Last_Name" class="ewCell form-group">
		<label for="x_Last_Name" class="ewSearchCaption ewLabel"><?php echo $users->Last_Name->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Last_Name" id="z_Last_Name" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="users" data-field="x_Last_Name" name="x_Last_Name" id="x_Last_Name" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($users->Last_Name->getPlaceHolder()) ?>" value="<?php echo $users->Last_Name->EditValue ?>"<?php echo $users->Last_Name->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($users_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($users_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $users_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($users_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($users_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($users_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($users_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $users_list->ShowPageHeader(); ?>
<?php
$users_list->ShowMessage();
?>
<?php if ($users_list->TotalRecs > 0 || $users->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($users_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> users">
<?php if ($users->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($users->CurrentAction <> "gridadd" && $users->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($users_list->Pager)) $users_list->Pager = new cPrevNextPager($users_list->StartRec, $users_list->DisplayRecs, $users_list->TotalRecs, $users_list->AutoHidePager) ?>
<?php if ($users_list->Pager->RecordCount > 0 && $users_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($users_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($users_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $users_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($users_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($users_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $users_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $users_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $users_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $users_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($users_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fuserslist" id="fuserslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($users_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $users_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="users">
<div id="gmp_users" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($users_list->TotalRecs > 0 || $users->CurrentAction == "gridedit") { ?>
<table id="tbl_userslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$users_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$users_list->RenderListOptions();

// Render list options (header, left)
$users_list->ListOptions->Render("header", "left");
?>
<?php if ($users->id_user->Visible) { // id_user ?>
	<?php if ($users->SortUrl($users->id_user) == "") { ?>
		<th data-name="id_user" class="<?php echo $users->id_user->HeaderCellClass() ?>"><div id="elh_users_id_user" class="users_id_user"><div class="ewTableHeaderCaption"><?php echo $users->id_user->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_user" class="<?php echo $users->id_user->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->id_user) ?>',1);"><div id="elh_users_id_user" class="users_id_user">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->id_user->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($users->id_user->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->id_user->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->User_Level->Visible) { // User_Level ?>
	<?php if ($users->SortUrl($users->User_Level) == "") { ?>
		<th data-name="User_Level" class="<?php echo $users->User_Level->HeaderCellClass() ?>"><div id="elh_users_User_Level" class="users_User_Level"><div class="ewTableHeaderCaption"><?php echo $users->User_Level->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="User_Level" class="<?php echo $users->User_Level->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->User_Level) ?>',1);"><div id="elh_users_User_Level" class="users_User_Level">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->User_Level->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($users->User_Level->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->User_Level->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->Username->Visible) { // Username ?>
	<?php if ($users->SortUrl($users->Username) == "") { ?>
		<th data-name="Username" class="<?php echo $users->Username->HeaderCellClass() ?>"><div id="elh_users_Username" class="users_Username"><div class="ewTableHeaderCaption"><?php echo $users->Username->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Username" class="<?php echo $users->Username->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->Username) ?>',1);"><div id="elh_users_Username" class="users_Username">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->Username->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->Username->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->Username->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->No_documento->Visible) { // No_documento ?>
	<?php if ($users->SortUrl($users->No_documento) == "") { ?>
		<th data-name="No_documento" class="<?php echo $users->No_documento->HeaderCellClass() ?>"><div id="elh_users_No_documento" class="users_No_documento"><div class="ewTableHeaderCaption"><?php echo $users->No_documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="No_documento" class="<?php echo $users->No_documento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->No_documento) ?>',1);"><div id="elh_users_No_documento" class="users_No_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->No_documento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->No_documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->No_documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->First_Name->Visible) { // First_Name ?>
	<?php if ($users->SortUrl($users->First_Name) == "") { ?>
		<th data-name="First_Name" class="<?php echo $users->First_Name->HeaderCellClass() ?>"><div id="elh_users_First_Name" class="users_First_Name"><div class="ewTableHeaderCaption"><?php echo $users->First_Name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="First_Name" class="<?php echo $users->First_Name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->First_Name) ?>',1);"><div id="elh_users_First_Name" class="users_First_Name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->First_Name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->First_Name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->First_Name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->Last_Name->Visible) { // Last_Name ?>
	<?php if ($users->SortUrl($users->Last_Name) == "") { ?>
		<th data-name="Last_Name" class="<?php echo $users->Last_Name->HeaderCellClass() ?>"><div id="elh_users_Last_Name" class="users_Last_Name"><div class="ewTableHeaderCaption"><?php echo $users->Last_Name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Last_Name" class="<?php echo $users->Last_Name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->Last_Name) ?>',1);"><div id="elh_users_Last_Name" class="users_Last_Name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->Last_Name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->Last_Name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->Last_Name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->_Email->Visible) { // Email ?>
	<?php if ($users->SortUrl($users->_Email) == "") { ?>
		<th data-name="_Email" class="<?php echo $users->_Email->HeaderCellClass() ?>"><div id="elh_users__Email" class="users__Email"><div class="ewTableHeaderCaption"><?php echo $users->_Email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_Email" class="<?php echo $users->_Email->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->_Email) ?>',1);"><div id="elh_users__Email" class="users__Email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->_Email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->_Email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->_Email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->Telefono_movil->Visible) { // Telefono_movil ?>
	<?php if ($users->SortUrl($users->Telefono_movil) == "") { ?>
		<th data-name="Telefono_movil" class="<?php echo $users->Telefono_movil->HeaderCellClass() ?>"><div id="elh_users_Telefono_movil" class="users_Telefono_movil"><div class="ewTableHeaderCaption"><?php echo $users->Telefono_movil->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Telefono_movil" class="<?php echo $users->Telefono_movil->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->Telefono_movil) ?>',1);"><div id="elh_users_Telefono_movil" class="users_Telefono_movil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->Telefono_movil->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->Telefono_movil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->Telefono_movil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->Fecha_nacimiento->Visible) { // Fecha_nacimiento ?>
	<?php if ($users->SortUrl($users->Fecha_nacimiento) == "") { ?>
		<th data-name="Fecha_nacimiento" class="<?php echo $users->Fecha_nacimiento->HeaderCellClass() ?>"><div id="elh_users_Fecha_nacimiento" class="users_Fecha_nacimiento"><div class="ewTableHeaderCaption"><?php echo $users->Fecha_nacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Fecha_nacimiento" class="<?php echo $users->Fecha_nacimiento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->Fecha_nacimiento) ?>',1);"><div id="elh_users_Fecha_nacimiento" class="users_Fecha_nacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->Fecha_nacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($users->Fecha_nacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->Fecha_nacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->Locked->Visible) { // Locked ?>
	<?php if ($users->SortUrl($users->Locked) == "") { ?>
		<th data-name="Locked" class="<?php echo $users->Locked->HeaderCellClass() ?>"><div id="elh_users_Locked" class="users_Locked"><div class="ewTableHeaderCaption"><?php echo $users->Locked->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Locked" class="<?php echo $users->Locked->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->Locked) ?>',1);"><div id="elh_users_Locked" class="users_Locked">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->Locked->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($users->Locked->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->Locked->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->acceso_app->Visible) { // acceso_app ?>
	<?php if ($users->SortUrl($users->acceso_app) == "") { ?>
		<th data-name="acceso_app" class="<?php echo $users->acceso_app->HeaderCellClass() ?>"><div id="elh_users_acceso_app" class="users_acceso_app"><div class="ewTableHeaderCaption"><?php echo $users->acceso_app->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="acceso_app" class="<?php echo $users->acceso_app->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->acceso_app) ?>',1);"><div id="elh_users_acceso_app" class="users_acceso_app">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->acceso_app->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($users->acceso_app->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->acceso_app->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->fecha_ingreso->Visible) { // fecha_ingreso ?>
	<?php if ($users->SortUrl($users->fecha_ingreso) == "") { ?>
		<th data-name="fecha_ingreso" class="<?php echo $users->fecha_ingreso->HeaderCellClass() ?>"><div id="elh_users_fecha_ingreso" class="users_fecha_ingreso"><div class="ewTableHeaderCaption"><?php echo $users->fecha_ingreso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_ingreso" class="<?php echo $users->fecha_ingreso->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->fecha_ingreso) ?>',1);"><div id="elh_users_fecha_ingreso" class="users_fecha_ingreso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->fecha_ingreso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($users->fecha_ingreso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->fecha_ingreso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$users_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($users->ExportAll && $users->Export <> "") {
	$users_list->StopRec = $users_list->TotalRecs;
} else {

	// Set the last record to display
	if ($users_list->TotalRecs > $users_list->StartRec + $users_list->DisplayRecs - 1)
		$users_list->StopRec = $users_list->StartRec + $users_list->DisplayRecs - 1;
	else
		$users_list->StopRec = $users_list->TotalRecs;
}
$users_list->RecCnt = $users_list->StartRec - 1;
if ($users_list->Recordset && !$users_list->Recordset->EOF) {
	$users_list->Recordset->MoveFirst();
	$bSelectLimit = $users_list->UseSelectLimit;
	if (!$bSelectLimit && $users_list->StartRec > 1)
		$users_list->Recordset->Move($users_list->StartRec - 1);
} elseif (!$users->AllowAddDeleteRow && $users_list->StopRec == 0) {
	$users_list->StopRec = $users->GridAddRowCount;
}

// Initialize aggregate
$users->RowType = EW_ROWTYPE_AGGREGATEINIT;
$users->ResetAttrs();
$users_list->RenderRow();
while ($users_list->RecCnt < $users_list->StopRec) {
	$users_list->RecCnt++;
	if (intval($users_list->RecCnt) >= intval($users_list->StartRec)) {
		$users_list->RowCnt++;

		// Set up key count
		$users_list->KeyCount = $users_list->RowIndex;

		// Init row class and style
		$users->ResetAttrs();
		$users->CssClass = "";
		if ($users->CurrentAction == "gridadd") {
		} else {
			$users_list->LoadRowValues($users_list->Recordset); // Load row values
		}
		$users->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$users->RowAttrs = array_merge($users->RowAttrs, array('data-rowindex'=>$users_list->RowCnt, 'id'=>'r' . $users_list->RowCnt . '_users', 'data-rowtype'=>$users->RowType));

		// Render row
		$users_list->RenderRow();

		// Render list options
		$users_list->RenderListOptions();
?>
	<tr<?php echo $users->RowAttributes() ?>>
<?php

// Render list options (body, left)
$users_list->ListOptions->Render("body", "left", $users_list->RowCnt);
?>
	<?php if ($users->id_user->Visible) { // id_user ?>
		<td data-name="id_user"<?php echo $users->id_user->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_id_user" class="users_id_user">
<span<?php echo $users->id_user->ViewAttributes() ?>>
<?php echo $users->id_user->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->User_Level->Visible) { // User_Level ?>
		<td data-name="User_Level"<?php echo $users->User_Level->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_User_Level" class="users_User_Level">
<span<?php echo $users->User_Level->ViewAttributes() ?>>
<?php echo $users->User_Level->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->Username->Visible) { // Username ?>
		<td data-name="Username"<?php echo $users->Username->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_Username" class="users_Username">
<span<?php echo $users->Username->ViewAttributes() ?>>
<?php echo $users->Username->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->No_documento->Visible) { // No_documento ?>
		<td data-name="No_documento"<?php echo $users->No_documento->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_No_documento" class="users_No_documento">
<span<?php echo $users->No_documento->ViewAttributes() ?>>
<?php echo $users->No_documento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->First_Name->Visible) { // First_Name ?>
		<td data-name="First_Name"<?php echo $users->First_Name->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_First_Name" class="users_First_Name">
<span<?php echo $users->First_Name->ViewAttributes() ?>>
<?php echo $users->First_Name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->Last_Name->Visible) { // Last_Name ?>
		<td data-name="Last_Name"<?php echo $users->Last_Name->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_Last_Name" class="users_Last_Name">
<span<?php echo $users->Last_Name->ViewAttributes() ?>>
<?php echo $users->Last_Name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->_Email->Visible) { // Email ?>
		<td data-name="_Email"<?php echo $users->_Email->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users__Email" class="users__Email">
<span<?php echo $users->_Email->ViewAttributes() ?>>
<?php echo $users->_Email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->Telefono_movil->Visible) { // Telefono_movil ?>
		<td data-name="Telefono_movil"<?php echo $users->Telefono_movil->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_Telefono_movil" class="users_Telefono_movil">
<span<?php echo $users->Telefono_movil->ViewAttributes() ?>>
<?php echo $users->Telefono_movil->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->Fecha_nacimiento->Visible) { // Fecha_nacimiento ?>
		<td data-name="Fecha_nacimiento"<?php echo $users->Fecha_nacimiento->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_Fecha_nacimiento" class="users_Fecha_nacimiento">
<span<?php echo $users->Fecha_nacimiento->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($users->Fecha_nacimiento->ListViewValue())) && $users->Fecha_nacimiento->LinkAttributes() <> "") { ?>
<a<?php echo $users->Fecha_nacimiento->LinkAttributes() ?>><?php echo $users->Fecha_nacimiento->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $users->Fecha_nacimiento->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
	<?php } ?>
	<?php if ($users->Locked->Visible) { // Locked ?>
		<td data-name="Locked"<?php echo $users->Locked->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_Locked" class="users_Locked">
<span<?php echo $users->Locked->ViewAttributes() ?>>
<?php echo $users->Locked->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->acceso_app->Visible) { // acceso_app ?>
		<td data-name="acceso_app"<?php echo $users->acceso_app->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_acceso_app" class="users_acceso_app">
<span<?php echo $users->acceso_app->ViewAttributes() ?>>
<?php echo $users->acceso_app->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->fecha_ingreso->Visible) { // fecha_ingreso ?>
		<td data-name="fecha_ingreso"<?php echo $users->fecha_ingreso->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_fecha_ingreso" class="users_fecha_ingreso">
<span<?php echo $users->fecha_ingreso->ViewAttributes() ?>>
<?php echo $users->fecha_ingreso->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$users_list->ListOptions->Render("body", "right", $users_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($users->CurrentAction <> "gridadd")
		$users_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($users->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($users_list->Recordset)
	$users_list->Recordset->Close();
?>
<?php if ($users->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($users->CurrentAction <> "gridadd" && $users->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($users_list->Pager)) $users_list->Pager = new cPrevNextPager($users_list->StartRec, $users_list->DisplayRecs, $users_list->TotalRecs, $users_list->AutoHidePager) ?>
<?php if ($users_list->Pager->RecordCount > 0 && $users_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($users_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($users_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $users_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($users_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($users_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $users_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $users_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $users_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $users_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($users_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($users_list->TotalRecs == 0 && $users->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($users_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($users->Export == "") { ?>
<script type="text/javascript">
fuserslistsrch.FilterList = <?php echo $users_list->GetFilterList() ?>;
fuserslistsrch.Init();
fuserslist.Init();
</script>
<?php } ?>
<?php
$users_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($users->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$users_list->Page_Terminate();
?>
