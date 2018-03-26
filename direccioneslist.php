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

$direcciones_list = NULL; // Initialize page object first

class cdirecciones_list extends cdirecciones {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'direcciones';

	// Page object name
	var $PageObjName = 'direcciones_list';

	// Grid form hidden field names
	var $FormName = 'fdireccioneslist';
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

		// Table object (direcciones)
		if (!isset($GLOBALS["direcciones"]) || get_class($GLOBALS["direcciones"]) == "cdirecciones") {
			$GLOBALS["direcciones"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["direcciones"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "direccionesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "direccionesdelete.php";
		$this->MultiUpdateUrl = "direccionesupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fdireccioneslistsrch";

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
		$this->mapa->SetVisibility();
		$this->longitud->SetVisibility();
		$this->latitud->SetVisibility();

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
		if ($sFilter == "") {
			$sFilter = "0=101";
			$this->SearchWhere = $sFilter;
		}

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
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "fdireccioneslistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->Id->AdvancedSearch->ToJson(), ","); // Field Id
		$sFilterList = ew_Concat($sFilterList, $this->id_fuente->AdvancedSearch->ToJson(), ","); // Field id_fuente
		$sFilterList = ew_Concat($sFilterList, $this->id_gestion->AdvancedSearch->ToJson(), ","); // Field id_gestion
		$sFilterList = ew_Concat($sFilterList, $this->id_tipodireccion->AdvancedSearch->ToJson(), ","); // Field id_tipodireccion
		$sFilterList = ew_Concat($sFilterList, $this->tipo_documento->AdvancedSearch->ToJson(), ","); // Field tipo_documento
		$sFilterList = ew_Concat($sFilterList, $this->no_documento->AdvancedSearch->ToJson(), ","); // Field no_documento
		$sFilterList = ew_Concat($sFilterList, $this->nombres->AdvancedSearch->ToJson(), ","); // Field nombres
		$sFilterList = ew_Concat($sFilterList, $this->paterno->AdvancedSearch->ToJson(), ","); // Field paterno
		$sFilterList = ew_Concat($sFilterList, $this->materno->AdvancedSearch->ToJson(), ","); // Field materno
		$sFilterList = ew_Concat($sFilterList, $this->pais->AdvancedSearch->ToJson(), ","); // Field pais
		$sFilterList = ew_Concat($sFilterList, $this->departamento->AdvancedSearch->ToJson(), ","); // Field departamento
		$sFilterList = ew_Concat($sFilterList, $this->provincia->AdvancedSearch->ToJson(), ","); // Field provincia
		$sFilterList = ew_Concat($sFilterList, $this->municipio->AdvancedSearch->ToJson(), ","); // Field municipio
		$sFilterList = ew_Concat($sFilterList, $this->localidad->AdvancedSearch->ToJson(), ","); // Field localidad
		$sFilterList = ew_Concat($sFilterList, $this->distrito->AdvancedSearch->ToJson(), ","); // Field distrito
		$sFilterList = ew_Concat($sFilterList, $this->zona->AdvancedSearch->ToJson(), ","); // Field zona
		$sFilterList = ew_Concat($sFilterList, $this->direccion1->AdvancedSearch->ToJson(), ","); // Field direccion1
		$sFilterList = ew_Concat($sFilterList, $this->direccion2->AdvancedSearch->ToJson(), ","); // Field direccion2
		$sFilterList = ew_Concat($sFilterList, $this->direccion3->AdvancedSearch->ToJson(), ","); // Field direccion3
		$sFilterList = ew_Concat($sFilterList, $this->direccion4->AdvancedSearch->ToJson(), ","); // Field direccion4
		$sFilterList = ew_Concat($sFilterList, $this->mapa->AdvancedSearch->ToJson(), ","); // Field mapa
		$sFilterList = ew_Concat($sFilterList, $this->longitud->AdvancedSearch->ToJson(), ","); // Field longitud
		$sFilterList = ew_Concat($sFilterList, $this->latitud->AdvancedSearch->ToJson(), ","); // Field latitud
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fdireccioneslistsrch", $filters);

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

		// Field id_fuente
		$this->id_fuente->AdvancedSearch->SearchValue = @$filter["x_id_fuente"];
		$this->id_fuente->AdvancedSearch->SearchOperator = @$filter["z_id_fuente"];
		$this->id_fuente->AdvancedSearch->SearchCondition = @$filter["v_id_fuente"];
		$this->id_fuente->AdvancedSearch->SearchValue2 = @$filter["y_id_fuente"];
		$this->id_fuente->AdvancedSearch->SearchOperator2 = @$filter["w_id_fuente"];
		$this->id_fuente->AdvancedSearch->Save();

		// Field id_gestion
		$this->id_gestion->AdvancedSearch->SearchValue = @$filter["x_id_gestion"];
		$this->id_gestion->AdvancedSearch->SearchOperator = @$filter["z_id_gestion"];
		$this->id_gestion->AdvancedSearch->SearchCondition = @$filter["v_id_gestion"];
		$this->id_gestion->AdvancedSearch->SearchValue2 = @$filter["y_id_gestion"];
		$this->id_gestion->AdvancedSearch->SearchOperator2 = @$filter["w_id_gestion"];
		$this->id_gestion->AdvancedSearch->Save();

		// Field id_tipodireccion
		$this->id_tipodireccion->AdvancedSearch->SearchValue = @$filter["x_id_tipodireccion"];
		$this->id_tipodireccion->AdvancedSearch->SearchOperator = @$filter["z_id_tipodireccion"];
		$this->id_tipodireccion->AdvancedSearch->SearchCondition = @$filter["v_id_tipodireccion"];
		$this->id_tipodireccion->AdvancedSearch->SearchValue2 = @$filter["y_id_tipodireccion"];
		$this->id_tipodireccion->AdvancedSearch->SearchOperator2 = @$filter["w_id_tipodireccion"];
		$this->id_tipodireccion->AdvancedSearch->Save();

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

		// Field pais
		$this->pais->AdvancedSearch->SearchValue = @$filter["x_pais"];
		$this->pais->AdvancedSearch->SearchOperator = @$filter["z_pais"];
		$this->pais->AdvancedSearch->SearchCondition = @$filter["v_pais"];
		$this->pais->AdvancedSearch->SearchValue2 = @$filter["y_pais"];
		$this->pais->AdvancedSearch->SearchOperator2 = @$filter["w_pais"];
		$this->pais->AdvancedSearch->Save();

		// Field departamento
		$this->departamento->AdvancedSearch->SearchValue = @$filter["x_departamento"];
		$this->departamento->AdvancedSearch->SearchOperator = @$filter["z_departamento"];
		$this->departamento->AdvancedSearch->SearchCondition = @$filter["v_departamento"];
		$this->departamento->AdvancedSearch->SearchValue2 = @$filter["y_departamento"];
		$this->departamento->AdvancedSearch->SearchOperator2 = @$filter["w_departamento"];
		$this->departamento->AdvancedSearch->Save();

		// Field provincia
		$this->provincia->AdvancedSearch->SearchValue = @$filter["x_provincia"];
		$this->provincia->AdvancedSearch->SearchOperator = @$filter["z_provincia"];
		$this->provincia->AdvancedSearch->SearchCondition = @$filter["v_provincia"];
		$this->provincia->AdvancedSearch->SearchValue2 = @$filter["y_provincia"];
		$this->provincia->AdvancedSearch->SearchOperator2 = @$filter["w_provincia"];
		$this->provincia->AdvancedSearch->Save();

		// Field municipio
		$this->municipio->AdvancedSearch->SearchValue = @$filter["x_municipio"];
		$this->municipio->AdvancedSearch->SearchOperator = @$filter["z_municipio"];
		$this->municipio->AdvancedSearch->SearchCondition = @$filter["v_municipio"];
		$this->municipio->AdvancedSearch->SearchValue2 = @$filter["y_municipio"];
		$this->municipio->AdvancedSearch->SearchOperator2 = @$filter["w_municipio"];
		$this->municipio->AdvancedSearch->Save();

		// Field localidad
		$this->localidad->AdvancedSearch->SearchValue = @$filter["x_localidad"];
		$this->localidad->AdvancedSearch->SearchOperator = @$filter["z_localidad"];
		$this->localidad->AdvancedSearch->SearchCondition = @$filter["v_localidad"];
		$this->localidad->AdvancedSearch->SearchValue2 = @$filter["y_localidad"];
		$this->localidad->AdvancedSearch->SearchOperator2 = @$filter["w_localidad"];
		$this->localidad->AdvancedSearch->Save();

		// Field distrito
		$this->distrito->AdvancedSearch->SearchValue = @$filter["x_distrito"];
		$this->distrito->AdvancedSearch->SearchOperator = @$filter["z_distrito"];
		$this->distrito->AdvancedSearch->SearchCondition = @$filter["v_distrito"];
		$this->distrito->AdvancedSearch->SearchValue2 = @$filter["y_distrito"];
		$this->distrito->AdvancedSearch->SearchOperator2 = @$filter["w_distrito"];
		$this->distrito->AdvancedSearch->Save();

		// Field zona
		$this->zona->AdvancedSearch->SearchValue = @$filter["x_zona"];
		$this->zona->AdvancedSearch->SearchOperator = @$filter["z_zona"];
		$this->zona->AdvancedSearch->SearchCondition = @$filter["v_zona"];
		$this->zona->AdvancedSearch->SearchValue2 = @$filter["y_zona"];
		$this->zona->AdvancedSearch->SearchOperator2 = @$filter["w_zona"];
		$this->zona->AdvancedSearch->Save();

		// Field direccion1
		$this->direccion1->AdvancedSearch->SearchValue = @$filter["x_direccion1"];
		$this->direccion1->AdvancedSearch->SearchOperator = @$filter["z_direccion1"];
		$this->direccion1->AdvancedSearch->SearchCondition = @$filter["v_direccion1"];
		$this->direccion1->AdvancedSearch->SearchValue2 = @$filter["y_direccion1"];
		$this->direccion1->AdvancedSearch->SearchOperator2 = @$filter["w_direccion1"];
		$this->direccion1->AdvancedSearch->Save();

		// Field direccion2
		$this->direccion2->AdvancedSearch->SearchValue = @$filter["x_direccion2"];
		$this->direccion2->AdvancedSearch->SearchOperator = @$filter["z_direccion2"];
		$this->direccion2->AdvancedSearch->SearchCondition = @$filter["v_direccion2"];
		$this->direccion2->AdvancedSearch->SearchValue2 = @$filter["y_direccion2"];
		$this->direccion2->AdvancedSearch->SearchOperator2 = @$filter["w_direccion2"];
		$this->direccion2->AdvancedSearch->Save();

		// Field direccion3
		$this->direccion3->AdvancedSearch->SearchValue = @$filter["x_direccion3"];
		$this->direccion3->AdvancedSearch->SearchOperator = @$filter["z_direccion3"];
		$this->direccion3->AdvancedSearch->SearchCondition = @$filter["v_direccion3"];
		$this->direccion3->AdvancedSearch->SearchValue2 = @$filter["y_direccion3"];
		$this->direccion3->AdvancedSearch->SearchOperator2 = @$filter["w_direccion3"];
		$this->direccion3->AdvancedSearch->Save();

		// Field direccion4
		$this->direccion4->AdvancedSearch->SearchValue = @$filter["x_direccion4"];
		$this->direccion4->AdvancedSearch->SearchOperator = @$filter["z_direccion4"];
		$this->direccion4->AdvancedSearch->SearchCondition = @$filter["v_direccion4"];
		$this->direccion4->AdvancedSearch->SearchValue2 = @$filter["y_direccion4"];
		$this->direccion4->AdvancedSearch->SearchOperator2 = @$filter["w_direccion4"];
		$this->direccion4->AdvancedSearch->Save();

		// Field mapa
		$this->mapa->AdvancedSearch->SearchValue = @$filter["x_mapa"];
		$this->mapa->AdvancedSearch->SearchOperator = @$filter["z_mapa"];
		$this->mapa->AdvancedSearch->SearchCondition = @$filter["v_mapa"];
		$this->mapa->AdvancedSearch->SearchValue2 = @$filter["y_mapa"];
		$this->mapa->AdvancedSearch->SearchOperator2 = @$filter["w_mapa"];
		$this->mapa->AdvancedSearch->Save();

		// Field longitud
		$this->longitud->AdvancedSearch->SearchValue = @$filter["x_longitud"];
		$this->longitud->AdvancedSearch->SearchOperator = @$filter["z_longitud"];
		$this->longitud->AdvancedSearch->SearchCondition = @$filter["v_longitud"];
		$this->longitud->AdvancedSearch->SearchValue2 = @$filter["y_longitud"];
		$this->longitud->AdvancedSearch->SearchOperator2 = @$filter["w_longitud"];
		$this->longitud->AdvancedSearch->Save();

		// Field latitud
		$this->latitud->AdvancedSearch->SearchValue = @$filter["x_latitud"];
		$this->latitud->AdvancedSearch->SearchOperator = @$filter["z_latitud"];
		$this->latitud->AdvancedSearch->SearchCondition = @$filter["v_latitud"];
		$this->latitud->AdvancedSearch->SearchValue2 = @$filter["y_latitud"];
		$this->latitud->AdvancedSearch->SearchOperator2 = @$filter["w_latitud"];
		$this->latitud->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->Id, $Default, FALSE); // Id
		$this->BuildSearchSql($sWhere, $this->id_fuente, $Default, FALSE); // id_fuente
		$this->BuildSearchSql($sWhere, $this->id_gestion, $Default, FALSE); // id_gestion
		$this->BuildSearchSql($sWhere, $this->id_tipodireccion, $Default, FALSE); // id_tipodireccion
		$this->BuildSearchSql($sWhere, $this->tipo_documento, $Default, FALSE); // tipo_documento
		$this->BuildSearchSql($sWhere, $this->no_documento, $Default, FALSE); // no_documento
		$this->BuildSearchSql($sWhere, $this->nombres, $Default, FALSE); // nombres
		$this->BuildSearchSql($sWhere, $this->paterno, $Default, FALSE); // paterno
		$this->BuildSearchSql($sWhere, $this->materno, $Default, FALSE); // materno
		$this->BuildSearchSql($sWhere, $this->pais, $Default, FALSE); // pais
		$this->BuildSearchSql($sWhere, $this->departamento, $Default, FALSE); // departamento
		$this->BuildSearchSql($sWhere, $this->provincia, $Default, FALSE); // provincia
		$this->BuildSearchSql($sWhere, $this->municipio, $Default, FALSE); // municipio
		$this->BuildSearchSql($sWhere, $this->localidad, $Default, FALSE); // localidad
		$this->BuildSearchSql($sWhere, $this->distrito, $Default, FALSE); // distrito
		$this->BuildSearchSql($sWhere, $this->zona, $Default, FALSE); // zona
		$this->BuildSearchSql($sWhere, $this->direccion1, $Default, FALSE); // direccion1
		$this->BuildSearchSql($sWhere, $this->direccion2, $Default, FALSE); // direccion2
		$this->BuildSearchSql($sWhere, $this->direccion3, $Default, FALSE); // direccion3
		$this->BuildSearchSql($sWhere, $this->direccion4, $Default, FALSE); // direccion4
		$this->BuildSearchSql($sWhere, $this->mapa, $Default, FALSE); // mapa
		$this->BuildSearchSql($sWhere, $this->longitud, $Default, FALSE); // longitud
		$this->BuildSearchSql($sWhere, $this->latitud, $Default, FALSE); // latitud

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Id->AdvancedSearch->Save(); // Id
			$this->id_fuente->AdvancedSearch->Save(); // id_fuente
			$this->id_gestion->AdvancedSearch->Save(); // id_gestion
			$this->id_tipodireccion->AdvancedSearch->Save(); // id_tipodireccion
			$this->tipo_documento->AdvancedSearch->Save(); // tipo_documento
			$this->no_documento->AdvancedSearch->Save(); // no_documento
			$this->nombres->AdvancedSearch->Save(); // nombres
			$this->paterno->AdvancedSearch->Save(); // paterno
			$this->materno->AdvancedSearch->Save(); // materno
			$this->pais->AdvancedSearch->Save(); // pais
			$this->departamento->AdvancedSearch->Save(); // departamento
			$this->provincia->AdvancedSearch->Save(); // provincia
			$this->municipio->AdvancedSearch->Save(); // municipio
			$this->localidad->AdvancedSearch->Save(); // localidad
			$this->distrito->AdvancedSearch->Save(); // distrito
			$this->zona->AdvancedSearch->Save(); // zona
			$this->direccion1->AdvancedSearch->Save(); // direccion1
			$this->direccion2->AdvancedSearch->Save(); // direccion2
			$this->direccion3->AdvancedSearch->Save(); // direccion3
			$this->direccion4->AdvancedSearch->Save(); // direccion4
			$this->mapa->AdvancedSearch->Save(); // mapa
			$this->longitud->AdvancedSearch->Save(); // longitud
			$this->latitud->AdvancedSearch->Save(); // latitud
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
		$this->BuildBasicSearchSQL($sWhere, $this->pais, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->departamento, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->provincia, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->municipio, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->localidad, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->distrito, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->zona, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->direccion1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->direccion2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->direccion3, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->direccion4, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mapa, $arKeywords, $type);
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
		if ($this->id_fuente->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_gestion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_tipodireccion->AdvancedSearch->IssetSession())
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
		if ($this->pais->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->departamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->provincia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->municipio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->localidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->distrito->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->zona->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->direccion1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->direccion2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->direccion3->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->direccion4->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mapa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->longitud->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->latitud->AdvancedSearch->IssetSession())
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
		$this->id_fuente->AdvancedSearch->UnsetSession();
		$this->id_gestion->AdvancedSearch->UnsetSession();
		$this->id_tipodireccion->AdvancedSearch->UnsetSession();
		$this->tipo_documento->AdvancedSearch->UnsetSession();
		$this->no_documento->AdvancedSearch->UnsetSession();
		$this->nombres->AdvancedSearch->UnsetSession();
		$this->paterno->AdvancedSearch->UnsetSession();
		$this->materno->AdvancedSearch->UnsetSession();
		$this->pais->AdvancedSearch->UnsetSession();
		$this->departamento->AdvancedSearch->UnsetSession();
		$this->provincia->AdvancedSearch->UnsetSession();
		$this->municipio->AdvancedSearch->UnsetSession();
		$this->localidad->AdvancedSearch->UnsetSession();
		$this->distrito->AdvancedSearch->UnsetSession();
		$this->zona->AdvancedSearch->UnsetSession();
		$this->direccion1->AdvancedSearch->UnsetSession();
		$this->direccion2->AdvancedSearch->UnsetSession();
		$this->direccion3->AdvancedSearch->UnsetSession();
		$this->direccion4->AdvancedSearch->UnsetSession();
		$this->mapa->AdvancedSearch->UnsetSession();
		$this->longitud->AdvancedSearch->UnsetSession();
		$this->latitud->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->Id->AdvancedSearch->Load();
		$this->id_fuente->AdvancedSearch->Load();
		$this->id_gestion->AdvancedSearch->Load();
		$this->id_tipodireccion->AdvancedSearch->Load();
		$this->tipo_documento->AdvancedSearch->Load();
		$this->no_documento->AdvancedSearch->Load();
		$this->nombres->AdvancedSearch->Load();
		$this->paterno->AdvancedSearch->Load();
		$this->materno->AdvancedSearch->Load();
		$this->pais->AdvancedSearch->Load();
		$this->departamento->AdvancedSearch->Load();
		$this->provincia->AdvancedSearch->Load();
		$this->municipio->AdvancedSearch->Load();
		$this->localidad->AdvancedSearch->Load();
		$this->distrito->AdvancedSearch->Load();
		$this->zona->AdvancedSearch->Load();
		$this->direccion1->AdvancedSearch->Load();
		$this->direccion2->AdvancedSearch->Load();
		$this->direccion3->AdvancedSearch->Load();
		$this->direccion4->AdvancedSearch->Load();
		$this->mapa->AdvancedSearch->Load();
		$this->longitud->AdvancedSearch->Load();
		$this->latitud->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Id); // Id
			$this->UpdateSort($this->id_fuente); // id_fuente
			$this->UpdateSort($this->id_gestion); // id_gestion
			$this->UpdateSort($this->id_tipodireccion); // id_tipodireccion
			$this->UpdateSort($this->tipo_documento); // tipo_documento
			$this->UpdateSort($this->no_documento); // no_documento
			$this->UpdateSort($this->nombres); // nombres
			$this->UpdateSort($this->paterno); // paterno
			$this->UpdateSort($this->materno); // materno
			$this->UpdateSort($this->pais); // pais
			$this->UpdateSort($this->departamento); // departamento
			$this->UpdateSort($this->provincia); // provincia
			$this->UpdateSort($this->municipio); // municipio
			$this->UpdateSort($this->localidad); // localidad
			$this->UpdateSort($this->distrito); // distrito
			$this->UpdateSort($this->zona); // zona
			$this->UpdateSort($this->direccion1); // direccion1
			$this->UpdateSort($this->direccion2); // direccion2
			$this->UpdateSort($this->direccion3); // direccion3
			$this->UpdateSort($this->direccion4); // direccion4
			$this->UpdateSort($this->mapa); // mapa
			$this->UpdateSort($this->longitud); // longitud
			$this->UpdateSort($this->latitud); // latitud
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
				$this->id_fuente->setSort("");
				$this->id_gestion->setSort("");
				$this->id_tipodireccion->setSort("");
				$this->tipo_documento->setSort("");
				$this->no_documento->setSort("");
				$this->nombres->setSort("");
				$this->paterno->setSort("");
				$this->materno->setSort("");
				$this->pais->setSort("");
				$this->departamento->setSort("");
				$this->provincia->setSort("");
				$this->municipio->setSort("");
				$this->localidad->setSort("");
				$this->distrito->setSort("");
				$this->zona->setSort("");
				$this->direccion1->setSort("");
				$this->direccion2->setSort("");
				$this->direccion3->setSort("");
				$this->direccion4->setSort("");
				$this->mapa->setSort("");
				$this->longitud->setSort("");
				$this->latitud->setSort("");
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
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"direcciones\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
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
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"direcciones\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'EditBtn',url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("EditLink") . "</a>";
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
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-table=\"direcciones\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("CopyLink") . "</a>";
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
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-table=\"direcciones\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fdireccioneslist,url:'" . $this->MultiDeleteUrl . "',msg:ewLanguage.Phrase('DeleteConfirmMsg')});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fdireccioneslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fdireccioneslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fdireccioneslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fdireccioneslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ResetSearch") . "\" data-caption=\"" . $Language->Phrase("ResetSearch") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ResetSearchBtn") . "</a>";
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

		// id_fuente
		$this->id_fuente->AdvancedSearch->SearchValue = @$_GET["x_id_fuente"];
		if ($this->id_fuente->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_fuente->AdvancedSearch->SearchOperator = @$_GET["z_id_fuente"];

		// id_gestion
		$this->id_gestion->AdvancedSearch->SearchValue = @$_GET["x_id_gestion"];
		if ($this->id_gestion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_gestion->AdvancedSearch->SearchOperator = @$_GET["z_id_gestion"];

		// id_tipodireccion
		$this->id_tipodireccion->AdvancedSearch->SearchValue = @$_GET["x_id_tipodireccion"];
		if ($this->id_tipodireccion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_tipodireccion->AdvancedSearch->SearchOperator = @$_GET["z_id_tipodireccion"];

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

		// pais
		$this->pais->AdvancedSearch->SearchValue = @$_GET["x_pais"];
		if ($this->pais->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->pais->AdvancedSearch->SearchOperator = @$_GET["z_pais"];

		// departamento
		$this->departamento->AdvancedSearch->SearchValue = @$_GET["x_departamento"];
		if ($this->departamento->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->departamento->AdvancedSearch->SearchOperator = @$_GET["z_departamento"];

		// provincia
		$this->provincia->AdvancedSearch->SearchValue = @$_GET["x_provincia"];
		if ($this->provincia->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->provincia->AdvancedSearch->SearchOperator = @$_GET["z_provincia"];

		// municipio
		$this->municipio->AdvancedSearch->SearchValue = @$_GET["x_municipio"];
		if ($this->municipio->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->municipio->AdvancedSearch->SearchOperator = @$_GET["z_municipio"];

		// localidad
		$this->localidad->AdvancedSearch->SearchValue = @$_GET["x_localidad"];
		if ($this->localidad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->localidad->AdvancedSearch->SearchOperator = @$_GET["z_localidad"];

		// distrito
		$this->distrito->AdvancedSearch->SearchValue = @$_GET["x_distrito"];
		if ($this->distrito->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->distrito->AdvancedSearch->SearchOperator = @$_GET["z_distrito"];

		// zona
		$this->zona->AdvancedSearch->SearchValue = @$_GET["x_zona"];
		if ($this->zona->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->zona->AdvancedSearch->SearchOperator = @$_GET["z_zona"];

		// direccion1
		$this->direccion1->AdvancedSearch->SearchValue = @$_GET["x_direccion1"];
		if ($this->direccion1->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->direccion1->AdvancedSearch->SearchOperator = @$_GET["z_direccion1"];

		// direccion2
		$this->direccion2->AdvancedSearch->SearchValue = @$_GET["x_direccion2"];
		if ($this->direccion2->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->direccion2->AdvancedSearch->SearchOperator = @$_GET["z_direccion2"];

		// direccion3
		$this->direccion3->AdvancedSearch->SearchValue = @$_GET["x_direccion3"];
		if ($this->direccion3->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->direccion3->AdvancedSearch->SearchOperator = @$_GET["z_direccion3"];

		// direccion4
		$this->direccion4->AdvancedSearch->SearchValue = @$_GET["x_direccion4"];
		if ($this->direccion4->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->direccion4->AdvancedSearch->SearchOperator = @$_GET["z_direccion4"];

		// mapa
		$this->mapa->AdvancedSearch->SearchValue = @$_GET["x_mapa"];
		if ($this->mapa->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mapa->AdvancedSearch->SearchOperator = @$_GET["z_mapa"];

		// longitud
		$this->longitud->AdvancedSearch->SearchValue = @$_GET["x_longitud"];
		if ($this->longitud->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->longitud->AdvancedSearch->SearchOperator = @$_GET["z_longitud"];

		// latitud
		$this->latitud->AdvancedSearch->SearchValue = @$_GET["x_latitud"];
		if ($this->latitud->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->latitud->AdvancedSearch->SearchOperator = @$_GET["z_latitud"];
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
		$row = array();
		$row['Id'] = NULL;
		$row['id_fuente'] = NULL;
		$row['id_gestion'] = NULL;
		$row['id_tipodireccion'] = NULL;
		$row['tipo_documento'] = NULL;
		$row['no_documento'] = NULL;
		$row['nombres'] = NULL;
		$row['paterno'] = NULL;
		$row['materno'] = NULL;
		$row['pais'] = NULL;
		$row['departamento'] = NULL;
		$row['provincia'] = NULL;
		$row['municipio'] = NULL;
		$row['localidad'] = NULL;
		$row['distrito'] = NULL;
		$row['zona'] = NULL;
		$row['direccion1'] = NULL;
		$row['direccion2'] = NULL;
		$row['direccion3'] = NULL;
		$row['direccion4'] = NULL;
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

			// Id
			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";
			$this->Id->TooltipValue = "";

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

			// mapa
			$this->mapa->LinkCustomAttributes = "";
			$this->mapa->HrefValue = "";
			$this->mapa->TooltipValue = "";

			// longitud
			$this->longitud->LinkCustomAttributes = "";
			$this->longitud->HrefValue = "";
			$this->longitud->TooltipValue = "";

			// latitud
			$this->latitud->LinkCustomAttributes = "";
			$this->latitud->HrefValue = "";
			$this->latitud->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			$this->Id->EditValue = ew_HtmlEncode($this->Id->AdvancedSearch->SearchValue);
			$this->Id->PlaceHolder = ew_RemoveHtml($this->Id->FldCaption());

			// id_fuente
			$this->id_fuente->EditAttrs["class"] = "form-control";
			$this->id_fuente->EditCustomAttributes = "";
			if (trim(strval($this->id_fuente->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_fuente->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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
			if (trim(strval($this->id_gestion->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_gestion->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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

			// tipo_documento
			$this->tipo_documento->EditAttrs["class"] = "form-control";
			$this->tipo_documento->EditCustomAttributes = "";
			$this->tipo_documento->EditValue = ew_HtmlEncode($this->tipo_documento->AdvancedSearch->SearchValue);
			$this->tipo_documento->PlaceHolder = ew_RemoveHtml($this->tipo_documento->FldCaption());

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

			// pais
			$this->pais->EditAttrs["class"] = "form-control";
			$this->pais->EditCustomAttributes = "";
			$this->pais->EditValue = ew_HtmlEncode($this->pais->AdvancedSearch->SearchValue);
			$this->pais->PlaceHolder = ew_RemoveHtml($this->pais->FldCaption());

			// departamento
			$this->departamento->EditAttrs["class"] = "form-control";
			$this->departamento->EditCustomAttributes = "";
			$this->departamento->EditValue = ew_HtmlEncode($this->departamento->AdvancedSearch->SearchValue);
			$this->departamento->PlaceHolder = ew_RemoveHtml($this->departamento->FldCaption());

			// provincia
			$this->provincia->EditAttrs["class"] = "form-control";
			$this->provincia->EditCustomAttributes = "";
			$this->provincia->EditValue = ew_HtmlEncode($this->provincia->AdvancedSearch->SearchValue);
			$this->provincia->PlaceHolder = ew_RemoveHtml($this->provincia->FldCaption());

			// municipio
			$this->municipio->EditAttrs["class"] = "form-control";
			$this->municipio->EditCustomAttributes = "";
			$this->municipio->EditValue = ew_HtmlEncode($this->municipio->AdvancedSearch->SearchValue);
			$this->municipio->PlaceHolder = ew_RemoveHtml($this->municipio->FldCaption());

			// localidad
			$this->localidad->EditAttrs["class"] = "form-control";
			$this->localidad->EditCustomAttributes = "";
			$this->localidad->EditValue = ew_HtmlEncode($this->localidad->AdvancedSearch->SearchValue);
			$this->localidad->PlaceHolder = ew_RemoveHtml($this->localidad->FldCaption());

			// distrito
			$this->distrito->EditAttrs["class"] = "form-control";
			$this->distrito->EditCustomAttributes = "";
			$this->distrito->EditValue = ew_HtmlEncode($this->distrito->AdvancedSearch->SearchValue);
			$this->distrito->PlaceHolder = ew_RemoveHtml($this->distrito->FldCaption());

			// zona
			$this->zona->EditAttrs["class"] = "form-control";
			$this->zona->EditCustomAttributes = "";
			$this->zona->EditValue = ew_HtmlEncode($this->zona->AdvancedSearch->SearchValue);
			$this->zona->PlaceHolder = ew_RemoveHtml($this->zona->FldCaption());

			// direccion1
			$this->direccion1->EditAttrs["class"] = "form-control";
			$this->direccion1->EditCustomAttributes = "";
			$this->direccion1->EditValue = ew_HtmlEncode($this->direccion1->AdvancedSearch->SearchValue);
			$this->direccion1->PlaceHolder = ew_RemoveHtml($this->direccion1->FldCaption());

			// direccion2
			$this->direccion2->EditAttrs["class"] = "form-control";
			$this->direccion2->EditCustomAttributes = "";
			$this->direccion2->EditValue = ew_HtmlEncode($this->direccion2->AdvancedSearch->SearchValue);
			$this->direccion2->PlaceHolder = ew_RemoveHtml($this->direccion2->FldCaption());

			// direccion3
			$this->direccion3->EditAttrs["class"] = "form-control";
			$this->direccion3->EditCustomAttributes = "";
			$this->direccion3->EditValue = ew_HtmlEncode($this->direccion3->AdvancedSearch->SearchValue);
			$this->direccion3->PlaceHolder = ew_RemoveHtml($this->direccion3->FldCaption());

			// direccion4
			$this->direccion4->EditAttrs["class"] = "form-control";
			$this->direccion4->EditCustomAttributes = "";
			$this->direccion4->EditValue = ew_HtmlEncode($this->direccion4->AdvancedSearch->SearchValue);
			$this->direccion4->PlaceHolder = ew_RemoveHtml($this->direccion4->FldCaption());

			// mapa
			$this->mapa->EditAttrs["class"] = "form-control";
			$this->mapa->EditCustomAttributes = "";
			$this->mapa->EditValue = ew_HtmlEncode($this->mapa->AdvancedSearch->SearchValue);
			$this->mapa->PlaceHolder = ew_RemoveHtml($this->mapa->FldCaption());

			// longitud
			$this->longitud->EditAttrs["class"] = "form-control";
			$this->longitud->EditCustomAttributes = 'readonly=true';
			$this->longitud->EditValue = ew_HtmlEncode($this->longitud->AdvancedSearch->SearchValue);
			$this->longitud->PlaceHolder = ew_RemoveHtml($this->longitud->FldCaption());

			// latitud
			$this->latitud->EditAttrs["class"] = "form-control";
			$this->latitud->EditCustomAttributes = 'readonly=true';
			$this->latitud->EditValue = ew_HtmlEncode($this->latitud->AdvancedSearch->SearchValue);
			$this->latitud->PlaceHolder = ew_RemoveHtml($this->latitud->FldCaption());
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
		$this->Id->AdvancedSearch->Load();
		$this->id_fuente->AdvancedSearch->Load();
		$this->id_gestion->AdvancedSearch->Load();
		$this->id_tipodireccion->AdvancedSearch->Load();
		$this->tipo_documento->AdvancedSearch->Load();
		$this->no_documento->AdvancedSearch->Load();
		$this->nombres->AdvancedSearch->Load();
		$this->paterno->AdvancedSearch->Load();
		$this->materno->AdvancedSearch->Load();
		$this->pais->AdvancedSearch->Load();
		$this->departamento->AdvancedSearch->Load();
		$this->provincia->AdvancedSearch->Load();
		$this->municipio->AdvancedSearch->Load();
		$this->localidad->AdvancedSearch->Load();
		$this->distrito->AdvancedSearch->Load();
		$this->zona->AdvancedSearch->Load();
		$this->direccion1->AdvancedSearch->Load();
		$this->direccion2->AdvancedSearch->Load();
		$this->direccion3->AdvancedSearch->Load();
		$this->direccion4->AdvancedSearch->Load();
		$this->mapa->AdvancedSearch->Load();
		$this->longitud->AdvancedSearch->Load();
		$this->latitud->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_direcciones\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_direcciones',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fdireccioneslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$this->AddSearchQueryString($sQry, $this->id_fuente); // id_fuente
		$this->AddSearchQueryString($sQry, $this->id_gestion); // id_gestion
		$this->AddSearchQueryString($sQry, $this->id_tipodireccion); // id_tipodireccion
		$this->AddSearchQueryString($sQry, $this->tipo_documento); // tipo_documento
		$this->AddSearchQueryString($sQry, $this->no_documento); // no_documento
		$this->AddSearchQueryString($sQry, $this->nombres); // nombres
		$this->AddSearchQueryString($sQry, $this->paterno); // paterno
		$this->AddSearchQueryString($sQry, $this->materno); // materno
		$this->AddSearchQueryString($sQry, $this->pais); // pais
		$this->AddSearchQueryString($sQry, $this->departamento); // departamento
		$this->AddSearchQueryString($sQry, $this->provincia); // provincia
		$this->AddSearchQueryString($sQry, $this->municipio); // municipio
		$this->AddSearchQueryString($sQry, $this->localidad); // localidad
		$this->AddSearchQueryString($sQry, $this->distrito); // distrito
		$this->AddSearchQueryString($sQry, $this->zona); // zona
		$this->AddSearchQueryString($sQry, $this->direccion1); // direccion1
		$this->AddSearchQueryString($sQry, $this->direccion2); // direccion2
		$this->AddSearchQueryString($sQry, $this->direccion3); // direccion3
		$this->AddSearchQueryString($sQry, $this->direccion4); // direccion4
		$this->AddSearchQueryString($sQry, $this->mapa); // mapa
		$this->AddSearchQueryString($sQry, $this->longitud); // longitud
		$this->AddSearchQueryString($sQry, $this->latitud); // latitud

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
	if (!isset($_GET["cmd"]) && !isset($_GET["export"]))
						$this->ResetSearchParms();
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
if (!isset($direcciones_list)) $direcciones_list = new cdirecciones_list();

// Page init
$direcciones_list->Page_Init();

// Page main
$direcciones_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$direcciones_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($direcciones->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fdireccioneslist = new ew_Form("fdireccioneslist", "list");
fdireccioneslist.FormKeyCountName = '<?php echo $direcciones_list->FormKeyCountName ?>';

// Form_CustomValidate event
fdireccioneslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdireccioneslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdireccioneslist.Lists["x_id_fuente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"fuentes"};
fdireccioneslist.Lists["x_id_fuente"].Data = "<?php echo $direcciones_list->id_fuente->LookupFilterQuery(FALSE, "list") ?>";
fdireccioneslist.Lists["x_id_gestion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gestiones"};
fdireccioneslist.Lists["x_id_gestion"].Data = "<?php echo $direcciones_list->id_gestion->LookupFilterQuery(FALSE, "list") ?>";
fdireccioneslist.Lists["x_id_tipodireccion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipo_direccion"};
fdireccioneslist.Lists["x_id_tipodireccion"].Data = "<?php echo $direcciones_list->id_tipodireccion->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fdireccioneslistsrch = new ew_Form("fdireccioneslistsrch");

// Validate function for search
fdireccioneslistsrch.Validate = function(fobj) {
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
fdireccioneslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdireccioneslistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdireccioneslistsrch.Lists["x_id_fuente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"fuentes"};
fdireccioneslistsrch.Lists["x_id_fuente"].Data = "<?php echo $direcciones_list->id_fuente->LookupFilterQuery(FALSE, "extbs") ?>";
fdireccioneslistsrch.Lists["x_id_gestion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gestiones"};
fdireccioneslistsrch.Lists["x_id_gestion"].Data = "<?php echo $direcciones_list->id_gestion->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($direcciones->Export == "") { ?>
<div class="ewToolbar">
<?php if ($direcciones_list->TotalRecs > 0 && $direcciones_list->ExportOptions->Visible()) { ?>
<?php $direcciones_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($direcciones_list->SearchOptions->Visible()) { ?>
<?php $direcciones_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($direcciones_list->FilterOptions->Visible()) { ?>
<?php $direcciones_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $direcciones_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($direcciones_list->TotalRecs <= 0)
			$direcciones_list->TotalRecs = $direcciones->ListRecordCount();
	} else {
		if (!$direcciones_list->Recordset && ($direcciones_list->Recordset = $direcciones_list->LoadRecordset()))
			$direcciones_list->TotalRecs = $direcciones_list->Recordset->RecordCount();
	}
	$direcciones_list->StartRec = 1;
	if ($direcciones_list->DisplayRecs <= 0 || ($direcciones->Export <> "" && $direcciones->ExportAll)) // Display all records
		$direcciones_list->DisplayRecs = $direcciones_list->TotalRecs;
	if (!($direcciones->Export <> "" && $direcciones->ExportAll))
		$direcciones_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$direcciones_list->Recordset = $direcciones_list->LoadRecordset($direcciones_list->StartRec-1, $direcciones_list->DisplayRecs);

	// Set no record found message
	if ($direcciones->CurrentAction == "" && $direcciones_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$direcciones_list->setWarningMessage(ew_DeniedMsg());
		if ($direcciones_list->SearchWhere == "0=101")
			$direcciones_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$direcciones_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$direcciones_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($direcciones->Export == "" && $direcciones->CurrentAction == "") { ?>
<form name="fdireccioneslistsrch" id="fdireccioneslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($direcciones_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fdireccioneslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="direcciones">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$direcciones_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$direcciones->RowType = EW_ROWTYPE_SEARCH;

// Render row
$direcciones->ResetAttrs();
$direcciones_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($direcciones->id_fuente->Visible) { // id_fuente ?>
	<div id="xsc_id_fuente" class="ewCell form-group">
		<label for="x_id_fuente" class="ewSearchCaption ewLabel"><?php echo $direcciones->id_fuente->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_fuente" id="z_id_fuente" value="="></span>
		<span class="ewSearchField">
<select data-table="direcciones" data-field="x_id_fuente" data-value-separator="<?php echo $direcciones->id_fuente->DisplayValueSeparatorAttribute() ?>" id="x_id_fuente" name="x_id_fuente"<?php echo $direcciones->id_fuente->EditAttributes() ?>>
<?php echo $direcciones->id_fuente->SelectOptionListHtml("x_id_fuente") ?>
</select>
</span>
	</div>
<?php } ?>
<?php if ($direcciones->id_gestion->Visible) { // id_gestion ?>
	<div id="xsc_id_gestion" class="ewCell form-group">
		<label for="x_id_gestion" class="ewSearchCaption ewLabel"><?php echo $direcciones->id_gestion->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_gestion" id="z_id_gestion" value="="></span>
		<span class="ewSearchField">
<select data-table="direcciones" data-field="x_id_gestion" data-value-separator="<?php echo $direcciones->id_gestion->DisplayValueSeparatorAttribute() ?>" id="x_id_gestion" name="x_id_gestion"<?php echo $direcciones->id_gestion->EditAttributes() ?>>
<?php echo $direcciones->id_gestion->SelectOptionListHtml("x_id_gestion") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($direcciones->no_documento->Visible) { // no_documento ?>
	<div id="xsc_no_documento" class="ewCell form-group">
		<label for="x_no_documento" class="ewSearchCaption ewLabel"><?php echo $direcciones->no_documento->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_documento" id="z_no_documento" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_no_documento" name="x_no_documento" id="x_no_documento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->no_documento->getPlaceHolder()) ?>" value="<?php echo $direcciones->no_documento->EditValue ?>"<?php echo $direcciones->no_documento->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($direcciones->nombres->Visible) { // nombres ?>
	<div id="xsc_nombres" class="ewCell form-group">
		<label for="x_nombres" class="ewSearchCaption ewLabel"><?php echo $direcciones->nombres->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombres" id="z_nombres" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_nombres" name="x_nombres" id="x_nombres" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->nombres->getPlaceHolder()) ?>" value="<?php echo $direcciones->nombres->EditValue ?>"<?php echo $direcciones->nombres->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($direcciones->paterno->Visible) { // paterno ?>
	<div id="xsc_paterno" class="ewCell form-group">
		<label for="x_paterno" class="ewSearchCaption ewLabel"><?php echo $direcciones->paterno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_paterno" id="z_paterno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_paterno" name="x_paterno" id="x_paterno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->paterno->getPlaceHolder()) ?>" value="<?php echo $direcciones->paterno->EditValue ?>"<?php echo $direcciones->paterno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($direcciones->materno->Visible) { // materno ?>
	<div id="xsc_materno" class="ewCell form-group">
		<label for="x_materno" class="ewSearchCaption ewLabel"><?php echo $direcciones->materno->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_materno" id="z_materno" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_materno" name="x_materno" id="x_materno" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->materno->getPlaceHolder()) ?>" value="<?php echo $direcciones->materno->EditValue ?>"<?php echo $direcciones->materno->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($direcciones->pais->Visible) { // pais ?>
	<div id="xsc_pais" class="ewCell form-group">
		<label for="x_pais" class="ewSearchCaption ewLabel"><?php echo $direcciones->pais->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_pais" id="z_pais" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_pais" name="x_pais" id="x_pais" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->pais->getPlaceHolder()) ?>" value="<?php echo $direcciones->pais->EditValue ?>"<?php echo $direcciones->pais->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($direcciones->departamento->Visible) { // departamento ?>
	<div id="xsc_departamento" class="ewCell form-group">
		<label for="x_departamento" class="ewSearchCaption ewLabel"><?php echo $direcciones->departamento->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_departamento" id="z_departamento" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_departamento" name="x_departamento" id="x_departamento" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->departamento->getPlaceHolder()) ?>" value="<?php echo $direcciones->departamento->EditValue ?>"<?php echo $direcciones->departamento->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($direcciones->provincia->Visible) { // provincia ?>
	<div id="xsc_provincia" class="ewCell form-group">
		<label for="x_provincia" class="ewSearchCaption ewLabel"><?php echo $direcciones->provincia->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_provincia" id="z_provincia" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_provincia" name="x_provincia" id="x_provincia" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->provincia->getPlaceHolder()) ?>" value="<?php echo $direcciones->provincia->EditValue ?>"<?php echo $direcciones->provincia->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($direcciones->municipio->Visible) { // municipio ?>
	<div id="xsc_municipio" class="ewCell form-group">
		<label for="x_municipio" class="ewSearchCaption ewLabel"><?php echo $direcciones->municipio->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_municipio" id="z_municipio" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_municipio" name="x_municipio" id="x_municipio" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->municipio->getPlaceHolder()) ?>" value="<?php echo $direcciones->municipio->EditValue ?>"<?php echo $direcciones->municipio->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($direcciones->localidad->Visible) { // localidad ?>
	<div id="xsc_localidad" class="ewCell form-group">
		<label for="x_localidad" class="ewSearchCaption ewLabel"><?php echo $direcciones->localidad->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_localidad" id="z_localidad" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_localidad" name="x_localidad" id="x_localidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($direcciones->localidad->getPlaceHolder()) ?>" value="<?php echo $direcciones->localidad->EditValue ?>"<?php echo $direcciones->localidad->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($direcciones->zona->Visible) { // zona ?>
	<div id="xsc_zona" class="ewCell form-group">
		<label for="x_zona" class="ewSearchCaption ewLabel"><?php echo $direcciones->zona->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_zona" id="z_zona" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_zona" name="x_zona" id="x_zona" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->zona->getPlaceHolder()) ?>" value="<?php echo $direcciones->zona->EditValue ?>"<?php echo $direcciones->zona->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
<?php if ($direcciones->direccion1->Visible) { // direccion1 ?>
	<div id="xsc_direccion1" class="ewCell form-group">
		<label for="x_direccion1" class="ewSearchCaption ewLabel"><?php echo $direcciones->direccion1->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_direccion1" id="z_direccion1" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_direccion1" name="x_direccion1" id="x_direccion1" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion1->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion1->EditValue ?>"<?php echo $direcciones->direccion1->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($direcciones->direccion2->Visible) { // direccion2 ?>
	<div id="xsc_direccion2" class="ewCell form-group">
		<label for="x_direccion2" class="ewSearchCaption ewLabel"><?php echo $direcciones->direccion2->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_direccion2" id="z_direccion2" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_direccion2" name="x_direccion2" id="x_direccion2" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion2->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion2->EditValue ?>"<?php echo $direcciones->direccion2->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_8" class="ewRow">
<?php if ($direcciones->direccion3->Visible) { // direccion3 ?>
	<div id="xsc_direccion3" class="ewCell form-group">
		<label for="x_direccion3" class="ewSearchCaption ewLabel"><?php echo $direcciones->direccion3->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_direccion3" id="z_direccion3" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_direccion3" name="x_direccion3" id="x_direccion3" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion3->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion3->EditValue ?>"<?php echo $direcciones->direccion3->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
<?php if ($direcciones->direccion4->Visible) { // direccion4 ?>
	<div id="xsc_direccion4" class="ewCell form-group">
		<label for="x_direccion4" class="ewSearchCaption ewLabel"><?php echo $direcciones->direccion4->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_direccion4" id="z_direccion4" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_direccion4" name="x_direccion4" id="x_direccion4" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion4->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion4->EditValue ?>"<?php echo $direcciones->direccion4->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_9" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($direcciones_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($direcciones_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $direcciones_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($direcciones_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($direcciones_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($direcciones_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($direcciones_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $direcciones_list->ShowPageHeader(); ?>
<?php
$direcciones_list->ShowMessage();
?>
<?php if ($direcciones_list->TotalRecs > 0 || $direcciones->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($direcciones_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> direcciones">
<?php if ($direcciones->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($direcciones->CurrentAction <> "gridadd" && $direcciones->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($direcciones_list->Pager)) $direcciones_list->Pager = new cPrevNextPager($direcciones_list->StartRec, $direcciones_list->DisplayRecs, $direcciones_list->TotalRecs, $direcciones_list->AutoHidePager) ?>
<?php if ($direcciones_list->Pager->RecordCount > 0 && $direcciones_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($direcciones_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $direcciones_list->PageUrl() ?>start=<?php echo $direcciones_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($direcciones_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $direcciones_list->PageUrl() ?>start=<?php echo $direcciones_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $direcciones_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($direcciones_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $direcciones_list->PageUrl() ?>start=<?php echo $direcciones_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($direcciones_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $direcciones_list->PageUrl() ?>start=<?php echo $direcciones_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $direcciones_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $direcciones_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $direcciones_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $direcciones_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($direcciones_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fdireccioneslist" id="fdireccioneslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($direcciones_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $direcciones_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="direcciones">
<div id="gmp_direcciones" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($direcciones_list->TotalRecs > 0 || $direcciones->CurrentAction == "gridedit") { ?>
<table id="tbl_direccioneslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$direcciones_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$direcciones_list->RenderListOptions();

// Render list options (header, left)
$direcciones_list->ListOptions->Render("header", "left");
?>
<?php if ($direcciones->Id->Visible) { // Id ?>
	<?php if ($direcciones->SortUrl($direcciones->Id) == "") { ?>
		<th data-name="Id" class="<?php echo $direcciones->Id->HeaderCellClass() ?>"><div id="elh_direcciones_Id" class="direcciones_Id"><div class="ewTableHeaderCaption"><?php echo $direcciones->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id" class="<?php echo $direcciones->Id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->Id) ?>',1);"><div id="elh_direcciones_Id" class="direcciones_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->id_fuente->Visible) { // id_fuente ?>
	<?php if ($direcciones->SortUrl($direcciones->id_fuente) == "") { ?>
		<th data-name="id_fuente" class="<?php echo $direcciones->id_fuente->HeaderCellClass() ?>"><div id="elh_direcciones_id_fuente" class="direcciones_id_fuente"><div class="ewTableHeaderCaption"><?php echo $direcciones->id_fuente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_fuente" class="<?php echo $direcciones->id_fuente->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->id_fuente) ?>',1);"><div id="elh_direcciones_id_fuente" class="direcciones_id_fuente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->id_fuente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->id_fuente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->id_fuente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->id_gestion->Visible) { // id_gestion ?>
	<?php if ($direcciones->SortUrl($direcciones->id_gestion) == "") { ?>
		<th data-name="id_gestion" class="<?php echo $direcciones->id_gestion->HeaderCellClass() ?>"><div id="elh_direcciones_id_gestion" class="direcciones_id_gestion"><div class="ewTableHeaderCaption"><?php echo $direcciones->id_gestion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_gestion" class="<?php echo $direcciones->id_gestion->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->id_gestion) ?>',1);"><div id="elh_direcciones_id_gestion" class="direcciones_id_gestion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->id_gestion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->id_gestion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->id_gestion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->id_tipodireccion->Visible) { // id_tipodireccion ?>
	<?php if ($direcciones->SortUrl($direcciones->id_tipodireccion) == "") { ?>
		<th data-name="id_tipodireccion" class="<?php echo $direcciones->id_tipodireccion->HeaderCellClass() ?>"><div id="elh_direcciones_id_tipodireccion" class="direcciones_id_tipodireccion"><div class="ewTableHeaderCaption"><?php echo $direcciones->id_tipodireccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_tipodireccion" class="<?php echo $direcciones->id_tipodireccion->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->id_tipodireccion) ?>',1);"><div id="elh_direcciones_id_tipodireccion" class="direcciones_id_tipodireccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->id_tipodireccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->id_tipodireccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->id_tipodireccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->tipo_documento->Visible) { // tipo_documento ?>
	<?php if ($direcciones->SortUrl($direcciones->tipo_documento) == "") { ?>
		<th data-name="tipo_documento" class="<?php echo $direcciones->tipo_documento->HeaderCellClass() ?>"><div id="elh_direcciones_tipo_documento" class="direcciones_tipo_documento"><div class="ewTableHeaderCaption"><?php echo $direcciones->tipo_documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo_documento" class="<?php echo $direcciones->tipo_documento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->tipo_documento) ?>',1);"><div id="elh_direcciones_tipo_documento" class="direcciones_tipo_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->tipo_documento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->tipo_documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->tipo_documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->no_documento->Visible) { // no_documento ?>
	<?php if ($direcciones->SortUrl($direcciones->no_documento) == "") { ?>
		<th data-name="no_documento" class="<?php echo $direcciones->no_documento->HeaderCellClass() ?>"><div id="elh_direcciones_no_documento" class="direcciones_no_documento"><div class="ewTableHeaderCaption"><?php echo $direcciones->no_documento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="no_documento" class="<?php echo $direcciones->no_documento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->no_documento) ?>',1);"><div id="elh_direcciones_no_documento" class="direcciones_no_documento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->no_documento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->no_documento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->no_documento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->nombres->Visible) { // nombres ?>
	<?php if ($direcciones->SortUrl($direcciones->nombres) == "") { ?>
		<th data-name="nombres" class="<?php echo $direcciones->nombres->HeaderCellClass() ?>"><div id="elh_direcciones_nombres" class="direcciones_nombres"><div class="ewTableHeaderCaption"><?php echo $direcciones->nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombres" class="<?php echo $direcciones->nombres->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->nombres) ?>',1);"><div id="elh_direcciones_nombres" class="direcciones_nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->nombres->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->paterno->Visible) { // paterno ?>
	<?php if ($direcciones->SortUrl($direcciones->paterno) == "") { ?>
		<th data-name="paterno" class="<?php echo $direcciones->paterno->HeaderCellClass() ?>"><div id="elh_direcciones_paterno" class="direcciones_paterno"><div class="ewTableHeaderCaption"><?php echo $direcciones->paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="paterno" class="<?php echo $direcciones->paterno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->paterno) ?>',1);"><div id="elh_direcciones_paterno" class="direcciones_paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->paterno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->materno->Visible) { // materno ?>
	<?php if ($direcciones->SortUrl($direcciones->materno) == "") { ?>
		<th data-name="materno" class="<?php echo $direcciones->materno->HeaderCellClass() ?>"><div id="elh_direcciones_materno" class="direcciones_materno"><div class="ewTableHeaderCaption"><?php echo $direcciones->materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="materno" class="<?php echo $direcciones->materno->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->materno) ?>',1);"><div id="elh_direcciones_materno" class="direcciones_materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->materno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->pais->Visible) { // pais ?>
	<?php if ($direcciones->SortUrl($direcciones->pais) == "") { ?>
		<th data-name="pais" class="<?php echo $direcciones->pais->HeaderCellClass() ?>"><div id="elh_direcciones_pais" class="direcciones_pais"><div class="ewTableHeaderCaption"><?php echo $direcciones->pais->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pais" class="<?php echo $direcciones->pais->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->pais) ?>',1);"><div id="elh_direcciones_pais" class="direcciones_pais">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->pais->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->pais->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->pais->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->departamento->Visible) { // departamento ?>
	<?php if ($direcciones->SortUrl($direcciones->departamento) == "") { ?>
		<th data-name="departamento" class="<?php echo $direcciones->departamento->HeaderCellClass() ?>"><div id="elh_direcciones_departamento" class="direcciones_departamento"><div class="ewTableHeaderCaption"><?php echo $direcciones->departamento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="departamento" class="<?php echo $direcciones->departamento->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->departamento) ?>',1);"><div id="elh_direcciones_departamento" class="direcciones_departamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->departamento->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->departamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->departamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->provincia->Visible) { // provincia ?>
	<?php if ($direcciones->SortUrl($direcciones->provincia) == "") { ?>
		<th data-name="provincia" class="<?php echo $direcciones->provincia->HeaderCellClass() ?>"><div id="elh_direcciones_provincia" class="direcciones_provincia"><div class="ewTableHeaderCaption"><?php echo $direcciones->provincia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="provincia" class="<?php echo $direcciones->provincia->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->provincia) ?>',1);"><div id="elh_direcciones_provincia" class="direcciones_provincia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->provincia->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->provincia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->provincia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->municipio->Visible) { // municipio ?>
	<?php if ($direcciones->SortUrl($direcciones->municipio) == "") { ?>
		<th data-name="municipio" class="<?php echo $direcciones->municipio->HeaderCellClass() ?>"><div id="elh_direcciones_municipio" class="direcciones_municipio"><div class="ewTableHeaderCaption"><?php echo $direcciones->municipio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="municipio" class="<?php echo $direcciones->municipio->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->municipio) ?>',1);"><div id="elh_direcciones_municipio" class="direcciones_municipio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->municipio->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->municipio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->municipio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->localidad->Visible) { // localidad ?>
	<?php if ($direcciones->SortUrl($direcciones->localidad) == "") { ?>
		<th data-name="localidad" class="<?php echo $direcciones->localidad->HeaderCellClass() ?>"><div id="elh_direcciones_localidad" class="direcciones_localidad"><div class="ewTableHeaderCaption"><?php echo $direcciones->localidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="localidad" class="<?php echo $direcciones->localidad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->localidad) ?>',1);"><div id="elh_direcciones_localidad" class="direcciones_localidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->localidad->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->localidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->localidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->distrito->Visible) { // distrito ?>
	<?php if ($direcciones->SortUrl($direcciones->distrito) == "") { ?>
		<th data-name="distrito" class="<?php echo $direcciones->distrito->HeaderCellClass() ?>"><div id="elh_direcciones_distrito" class="direcciones_distrito"><div class="ewTableHeaderCaption"><?php echo $direcciones->distrito->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="distrito" class="<?php echo $direcciones->distrito->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->distrito) ?>',1);"><div id="elh_direcciones_distrito" class="direcciones_distrito">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->distrito->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->distrito->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->distrito->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->zona->Visible) { // zona ?>
	<?php if ($direcciones->SortUrl($direcciones->zona) == "") { ?>
		<th data-name="zona" class="<?php echo $direcciones->zona->HeaderCellClass() ?>"><div id="elh_direcciones_zona" class="direcciones_zona"><div class="ewTableHeaderCaption"><?php echo $direcciones->zona->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="zona" class="<?php echo $direcciones->zona->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->zona) ?>',1);"><div id="elh_direcciones_zona" class="direcciones_zona">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->zona->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->zona->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->zona->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->direccion1->Visible) { // direccion1 ?>
	<?php if ($direcciones->SortUrl($direcciones->direccion1) == "") { ?>
		<th data-name="direccion1" class="<?php echo $direcciones->direccion1->HeaderCellClass() ?>"><div id="elh_direcciones_direccion1" class="direcciones_direccion1"><div class="ewTableHeaderCaption"><?php echo $direcciones->direccion1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion1" class="<?php echo $direcciones->direccion1->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->direccion1) ?>',1);"><div id="elh_direcciones_direccion1" class="direcciones_direccion1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->direccion1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->direccion1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->direccion1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->direccion2->Visible) { // direccion2 ?>
	<?php if ($direcciones->SortUrl($direcciones->direccion2) == "") { ?>
		<th data-name="direccion2" class="<?php echo $direcciones->direccion2->HeaderCellClass() ?>"><div id="elh_direcciones_direccion2" class="direcciones_direccion2"><div class="ewTableHeaderCaption"><?php echo $direcciones->direccion2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion2" class="<?php echo $direcciones->direccion2->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->direccion2) ?>',1);"><div id="elh_direcciones_direccion2" class="direcciones_direccion2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->direccion2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->direccion2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->direccion2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->direccion3->Visible) { // direccion3 ?>
	<?php if ($direcciones->SortUrl($direcciones->direccion3) == "") { ?>
		<th data-name="direccion3" class="<?php echo $direcciones->direccion3->HeaderCellClass() ?>"><div id="elh_direcciones_direccion3" class="direcciones_direccion3"><div class="ewTableHeaderCaption"><?php echo $direcciones->direccion3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion3" class="<?php echo $direcciones->direccion3->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->direccion3) ?>',1);"><div id="elh_direcciones_direccion3" class="direcciones_direccion3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->direccion3->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->direccion3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->direccion3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->direccion4->Visible) { // direccion4 ?>
	<?php if ($direcciones->SortUrl($direcciones->direccion4) == "") { ?>
		<th data-name="direccion4" class="<?php echo $direcciones->direccion4->HeaderCellClass() ?>"><div id="elh_direcciones_direccion4" class="direcciones_direccion4"><div class="ewTableHeaderCaption"><?php echo $direcciones->direccion4->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion4" class="<?php echo $direcciones->direccion4->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->direccion4) ?>',1);"><div id="elh_direcciones_direccion4" class="direcciones_direccion4">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->direccion4->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->direccion4->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->direccion4->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->mapa->Visible) { // mapa ?>
	<?php if ($direcciones->SortUrl($direcciones->mapa) == "") { ?>
		<th data-name="mapa" class="<?php echo $direcciones->mapa->HeaderCellClass() ?>"><div id="elh_direcciones_mapa" class="direcciones_mapa"><div class="ewTableHeaderCaption"><?php echo $direcciones->mapa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mapa" class="<?php echo $direcciones->mapa->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->mapa) ?>',1);"><div id="elh_direcciones_mapa" class="direcciones_mapa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->mapa->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->mapa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->mapa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->longitud->Visible) { // longitud ?>
	<?php if ($direcciones->SortUrl($direcciones->longitud) == "") { ?>
		<th data-name="longitud" class="<?php echo $direcciones->longitud->HeaderCellClass() ?>"><div id="elh_direcciones_longitud" class="direcciones_longitud"><div class="ewTableHeaderCaption"><?php echo $direcciones->longitud->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="longitud" class="<?php echo $direcciones->longitud->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->longitud) ?>',1);"><div id="elh_direcciones_longitud" class="direcciones_longitud">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->longitud->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->longitud->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->longitud->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->latitud->Visible) { // latitud ?>
	<?php if ($direcciones->SortUrl($direcciones->latitud) == "") { ?>
		<th data-name="latitud" class="<?php echo $direcciones->latitud->HeaderCellClass() ?>"><div id="elh_direcciones_latitud" class="direcciones_latitud"><div class="ewTableHeaderCaption"><?php echo $direcciones->latitud->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="latitud" class="<?php echo $direcciones->latitud->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->latitud) ?>',1);"><div id="elh_direcciones_latitud" class="direcciones_latitud">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->latitud->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->latitud->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->latitud->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$direcciones_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($direcciones->ExportAll && $direcciones->Export <> "") {
	$direcciones_list->StopRec = $direcciones_list->TotalRecs;
} else {

	// Set the last record to display
	if ($direcciones_list->TotalRecs > $direcciones_list->StartRec + $direcciones_list->DisplayRecs - 1)
		$direcciones_list->StopRec = $direcciones_list->StartRec + $direcciones_list->DisplayRecs - 1;
	else
		$direcciones_list->StopRec = $direcciones_list->TotalRecs;
}
$direcciones_list->RecCnt = $direcciones_list->StartRec - 1;
if ($direcciones_list->Recordset && !$direcciones_list->Recordset->EOF) {
	$direcciones_list->Recordset->MoveFirst();
	$bSelectLimit = $direcciones_list->UseSelectLimit;
	if (!$bSelectLimit && $direcciones_list->StartRec > 1)
		$direcciones_list->Recordset->Move($direcciones_list->StartRec - 1);
} elseif (!$direcciones->AllowAddDeleteRow && $direcciones_list->StopRec == 0) {
	$direcciones_list->StopRec = $direcciones->GridAddRowCount;
}

// Initialize aggregate
$direcciones->RowType = EW_ROWTYPE_AGGREGATEINIT;
$direcciones->ResetAttrs();
$direcciones_list->RenderRow();
while ($direcciones_list->RecCnt < $direcciones_list->StopRec) {
	$direcciones_list->RecCnt++;
	if (intval($direcciones_list->RecCnt) >= intval($direcciones_list->StartRec)) {
		$direcciones_list->RowCnt++;

		// Set up key count
		$direcciones_list->KeyCount = $direcciones_list->RowIndex;

		// Init row class and style
		$direcciones->ResetAttrs();
		$direcciones->CssClass = "";
		if ($direcciones->CurrentAction == "gridadd") {
		} else {
			$direcciones_list->LoadRowValues($direcciones_list->Recordset); // Load row values
		}
		$direcciones->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$direcciones->RowAttrs = array_merge($direcciones->RowAttrs, array('data-rowindex'=>$direcciones_list->RowCnt, 'id'=>'r' . $direcciones_list->RowCnt . '_direcciones', 'data-rowtype'=>$direcciones->RowType));

		// Render row
		$direcciones_list->RenderRow();

		// Render list options
		$direcciones_list->RenderListOptions();
?>
	<tr<?php echo $direcciones->RowAttributes() ?>>
<?php

// Render list options (body, left)
$direcciones_list->ListOptions->Render("body", "left", $direcciones_list->RowCnt);
?>
	<?php if ($direcciones->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $direcciones->Id->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_Id" class="direcciones_Id">
<span<?php echo $direcciones->Id->ViewAttributes() ?>>
<?php echo $direcciones->Id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->id_fuente->Visible) { // id_fuente ?>
		<td data-name="id_fuente"<?php echo $direcciones->id_fuente->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_id_fuente" class="direcciones_id_fuente">
<span<?php echo $direcciones->id_fuente->ViewAttributes() ?>>
<?php echo $direcciones->id_fuente->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->id_gestion->Visible) { // id_gestion ?>
		<td data-name="id_gestion"<?php echo $direcciones->id_gestion->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_id_gestion" class="direcciones_id_gestion">
<span<?php echo $direcciones->id_gestion->ViewAttributes() ?>>
<?php echo $direcciones->id_gestion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->id_tipodireccion->Visible) { // id_tipodireccion ?>
		<td data-name="id_tipodireccion"<?php echo $direcciones->id_tipodireccion->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_id_tipodireccion" class="direcciones_id_tipodireccion">
<span<?php echo $direcciones->id_tipodireccion->ViewAttributes() ?>>
<?php echo $direcciones->id_tipodireccion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->tipo_documento->Visible) { // tipo_documento ?>
		<td data-name="tipo_documento"<?php echo $direcciones->tipo_documento->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_tipo_documento" class="direcciones_tipo_documento">
<span<?php echo $direcciones->tipo_documento->ViewAttributes() ?>>
<?php echo $direcciones->tipo_documento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->no_documento->Visible) { // no_documento ?>
		<td data-name="no_documento"<?php echo $direcciones->no_documento->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_no_documento" class="direcciones_no_documento">
<span<?php echo $direcciones->no_documento->ViewAttributes() ?>>
<?php echo $direcciones->no_documento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->nombres->Visible) { // nombres ?>
		<td data-name="nombres"<?php echo $direcciones->nombres->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_nombres" class="direcciones_nombres">
<span<?php echo $direcciones->nombres->ViewAttributes() ?>>
<?php echo $direcciones->nombres->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->paterno->Visible) { // paterno ?>
		<td data-name="paterno"<?php echo $direcciones->paterno->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_paterno" class="direcciones_paterno">
<span<?php echo $direcciones->paterno->ViewAttributes() ?>>
<?php echo $direcciones->paterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->materno->Visible) { // materno ?>
		<td data-name="materno"<?php echo $direcciones->materno->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_materno" class="direcciones_materno">
<span<?php echo $direcciones->materno->ViewAttributes() ?>>
<?php echo $direcciones->materno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->pais->Visible) { // pais ?>
		<td data-name="pais"<?php echo $direcciones->pais->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_pais" class="direcciones_pais">
<span<?php echo $direcciones->pais->ViewAttributes() ?>>
<?php echo $direcciones->pais->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->departamento->Visible) { // departamento ?>
		<td data-name="departamento"<?php echo $direcciones->departamento->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_departamento" class="direcciones_departamento">
<span<?php echo $direcciones->departamento->ViewAttributes() ?>>
<?php echo $direcciones->departamento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->provincia->Visible) { // provincia ?>
		<td data-name="provincia"<?php echo $direcciones->provincia->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_provincia" class="direcciones_provincia">
<span<?php echo $direcciones->provincia->ViewAttributes() ?>>
<?php echo $direcciones->provincia->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->municipio->Visible) { // municipio ?>
		<td data-name="municipio"<?php echo $direcciones->municipio->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_municipio" class="direcciones_municipio">
<span<?php echo $direcciones->municipio->ViewAttributes() ?>>
<?php echo $direcciones->municipio->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->localidad->Visible) { // localidad ?>
		<td data-name="localidad"<?php echo $direcciones->localidad->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_localidad" class="direcciones_localidad">
<span<?php echo $direcciones->localidad->ViewAttributes() ?>>
<?php echo $direcciones->localidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->distrito->Visible) { // distrito ?>
		<td data-name="distrito"<?php echo $direcciones->distrito->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_distrito" class="direcciones_distrito">
<span<?php echo $direcciones->distrito->ViewAttributes() ?>>
<?php echo $direcciones->distrito->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->zona->Visible) { // zona ?>
		<td data-name="zona"<?php echo $direcciones->zona->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_zona" class="direcciones_zona">
<span<?php echo $direcciones->zona->ViewAttributes() ?>>
<?php echo $direcciones->zona->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->direccion1->Visible) { // direccion1 ?>
		<td data-name="direccion1"<?php echo $direcciones->direccion1->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_direccion1" class="direcciones_direccion1">
<span<?php echo $direcciones->direccion1->ViewAttributes() ?>>
<?php echo $direcciones->direccion1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->direccion2->Visible) { // direccion2 ?>
		<td data-name="direccion2"<?php echo $direcciones->direccion2->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_direccion2" class="direcciones_direccion2">
<span<?php echo $direcciones->direccion2->ViewAttributes() ?>>
<?php echo $direcciones->direccion2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->direccion3->Visible) { // direccion3 ?>
		<td data-name="direccion3"<?php echo $direcciones->direccion3->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_direccion3" class="direcciones_direccion3">
<span<?php echo $direcciones->direccion3->ViewAttributes() ?>>
<?php echo $direcciones->direccion3->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->direccion4->Visible) { // direccion4 ?>
		<td data-name="direccion4"<?php echo $direcciones->direccion4->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_direccion4" class="direcciones_direccion4">
<span<?php echo $direcciones->direccion4->ViewAttributes() ?>>
<?php echo $direcciones->direccion4->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->mapa->Visible) { // mapa ?>
		<td data-name="mapa"<?php echo $direcciones->mapa->CellAttributes() ?>>
<script id="orig<?php echo $direcciones_list->RowCnt ?>_direcciones_mapa" type="text/html">
<?php echo $direcciones->mapa->ListViewValue() ?>
</script>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_mapa" class="direcciones_mapa">
<span<?php echo $direcciones->mapa->ViewAttributes() ?>><script type="text/javascript">
ewGoogleMaps[ewGoogleMaps.length] = jQuery.extend({"id":"gm_direcciones_x_mapa","name":"Google Maps","apikey":"AIzaSyDFibhqbazLZqySy6EuVE_BHRUvkhyIVLg","width":400,"width_field":null,"height":400,"height_field":null,"latitude":null,"latitude_field":"latitud","longitude":null,"longitude_field":"longitud","address":null,"address_field":null,"type":"HYBRID","type_field":null,"zoom":18,"zoom_field":null,"title":null,"title_field":"direccion","icon":null,"icon_field":null,"description":null,"description_field":null,"use_single_map":true,"single_map_width":400,"single_map_height":400,"show_map_on_top":true,"show_all_markers":true,"geocoding_delay":250,"use_marker_clusterer":false,"cluster_max_zoom":-1,"cluster_grid_size":-1,"cluster_styles":-1,"template_id":"orig<?php echo $direcciones_list->RowCnt ?>_direcciones_mapa"}, {
	latitude: <?php echo ew_VarToJson($direcciones->latitud->CurrentValue, "undefined") ?>,
	longitude: <?php echo ew_VarToJson($direcciones->longitud->CurrentValue, "undefined") ?>
});
</script>
</span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->longitud->Visible) { // longitud ?>
		<td data-name="longitud"<?php echo $direcciones->longitud->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_longitud" class="direcciones_longitud">
<span<?php echo $direcciones->longitud->ViewAttributes() ?>>
<?php echo $direcciones->longitud->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->latitud->Visible) { // latitud ?>
		<td data-name="latitud"<?php echo $direcciones->latitud->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_latitud" class="direcciones_latitud">
<span<?php echo $direcciones->latitud->ViewAttributes() ?>>
<?php echo $direcciones->latitud->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$direcciones_list->ListOptions->Render("body", "right", $direcciones_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($direcciones->CurrentAction <> "gridadd")
		$direcciones_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($direcciones->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($direcciones_list->Recordset)
	$direcciones_list->Recordset->Close();
?>
<?php if ($direcciones->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($direcciones->CurrentAction <> "gridadd" && $direcciones->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($direcciones_list->Pager)) $direcciones_list->Pager = new cPrevNextPager($direcciones_list->StartRec, $direcciones_list->DisplayRecs, $direcciones_list->TotalRecs, $direcciones_list->AutoHidePager) ?>
<?php if ($direcciones_list->Pager->RecordCount > 0 && $direcciones_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($direcciones_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $direcciones_list->PageUrl() ?>start=<?php echo $direcciones_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($direcciones_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $direcciones_list->PageUrl() ?>start=<?php echo $direcciones_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $direcciones_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($direcciones_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $direcciones_list->PageUrl() ?>start=<?php echo $direcciones_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($direcciones_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $direcciones_list->PageUrl() ?>start=<?php echo $direcciones_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $direcciones_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $direcciones_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $direcciones_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $direcciones_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($direcciones_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($direcciones_list->TotalRecs == 0 && $direcciones->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($direcciones_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($direcciones->Export == "") { ?>
<script type="text/javascript">
fdireccioneslistsrch.FilterList = <?php echo $direcciones_list->GetFilterList() ?>;
fdireccioneslistsrch.Init();
fdireccioneslist.Init();
</script>
<?php } ?>
<?php
$direcciones_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($direcciones->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$direcciones_list->Page_Terminate();
?>
