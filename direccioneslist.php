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

		// Table object (personas)
		if (!isset($GLOBALS['personas'])) $GLOBALS['personas'] = new cpersonas();

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
		$this->id_persona->SetVisibility();
		$this->id_ciudad->SetVisibility();
		$this->tipo_direccion->SetVisibility();
		$this->direccion->SetVisibility();
		$this->ult_fecha_activo->SetVisibility();
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

		// Set up master detail parameters
		$this->SetupMasterParms();

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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "personas") {
			global $personas;
			$rsmaster = $personas->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("personaslist.php"); // Return to master page
			} else {
				$personas->LoadListRowValues($rsmaster);
				$personas->RowType = EW_ROWTYPE_MASTER; // Master row
				$personas->RenderListRow();
				$rsmaster->Close();
			}
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
		$sFilterList = ew_Concat($sFilterList, $this->id_persona->AdvancedSearch->ToJson(), ","); // Field id_persona
		$sFilterList = ew_Concat($sFilterList, $this->id_ciudad->AdvancedSearch->ToJson(), ","); // Field id_ciudad
		$sFilterList = ew_Concat($sFilterList, $this->tipo_direccion->AdvancedSearch->ToJson(), ","); // Field tipo_direccion
		$sFilterList = ew_Concat($sFilterList, $this->direccion->AdvancedSearch->ToJson(), ","); // Field direccion
		$sFilterList = ew_Concat($sFilterList, $this->ult_fecha_activo->AdvancedSearch->ToJson(), ","); // Field ult_fecha_activo
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

		// Field id_persona
		$this->id_persona->AdvancedSearch->SearchValue = @$filter["x_id_persona"];
		$this->id_persona->AdvancedSearch->SearchOperator = @$filter["z_id_persona"];
		$this->id_persona->AdvancedSearch->SearchCondition = @$filter["v_id_persona"];
		$this->id_persona->AdvancedSearch->SearchValue2 = @$filter["y_id_persona"];
		$this->id_persona->AdvancedSearch->SearchOperator2 = @$filter["w_id_persona"];
		$this->id_persona->AdvancedSearch->Save();

		// Field id_ciudad
		$this->id_ciudad->AdvancedSearch->SearchValue = @$filter["x_id_ciudad"];
		$this->id_ciudad->AdvancedSearch->SearchOperator = @$filter["z_id_ciudad"];
		$this->id_ciudad->AdvancedSearch->SearchCondition = @$filter["v_id_ciudad"];
		$this->id_ciudad->AdvancedSearch->SearchValue2 = @$filter["y_id_ciudad"];
		$this->id_ciudad->AdvancedSearch->SearchOperator2 = @$filter["w_id_ciudad"];
		$this->id_ciudad->AdvancedSearch->Save();

		// Field tipo_direccion
		$this->tipo_direccion->AdvancedSearch->SearchValue = @$filter["x_tipo_direccion"];
		$this->tipo_direccion->AdvancedSearch->SearchOperator = @$filter["z_tipo_direccion"];
		$this->tipo_direccion->AdvancedSearch->SearchCondition = @$filter["v_tipo_direccion"];
		$this->tipo_direccion->AdvancedSearch->SearchValue2 = @$filter["y_tipo_direccion"];
		$this->tipo_direccion->AdvancedSearch->SearchOperator2 = @$filter["w_tipo_direccion"];
		$this->tipo_direccion->AdvancedSearch->Save();

		// Field direccion
		$this->direccion->AdvancedSearch->SearchValue = @$filter["x_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator = @$filter["z_direccion"];
		$this->direccion->AdvancedSearch->SearchCondition = @$filter["v_direccion"];
		$this->direccion->AdvancedSearch->SearchValue2 = @$filter["y_direccion"];
		$this->direccion->AdvancedSearch->SearchOperator2 = @$filter["w_direccion"];
		$this->direccion->AdvancedSearch->Save();

		// Field ult_fecha_activo
		$this->ult_fecha_activo->AdvancedSearch->SearchValue = @$filter["x_ult_fecha_activo"];
		$this->ult_fecha_activo->AdvancedSearch->SearchOperator = @$filter["z_ult_fecha_activo"];
		$this->ult_fecha_activo->AdvancedSearch->SearchCondition = @$filter["v_ult_fecha_activo"];
		$this->ult_fecha_activo->AdvancedSearch->SearchValue2 = @$filter["y_ult_fecha_activo"];
		$this->ult_fecha_activo->AdvancedSearch->SearchOperator2 = @$filter["w_ult_fecha_activo"];
		$this->ult_fecha_activo->AdvancedSearch->Save();

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
		$this->BuildSearchSql($sWhere, $this->id_persona, $Default, FALSE); // id_persona
		$this->BuildSearchSql($sWhere, $this->id_ciudad, $Default, FALSE); // id_ciudad
		$this->BuildSearchSql($sWhere, $this->tipo_direccion, $Default, FALSE); // tipo_direccion
		$this->BuildSearchSql($sWhere, $this->direccion, $Default, FALSE); // direccion
		$this->BuildSearchSql($sWhere, $this->ult_fecha_activo, $Default, FALSE); // ult_fecha_activo
		$this->BuildSearchSql($sWhere, $this->mapa, $Default, FALSE); // mapa
		$this->BuildSearchSql($sWhere, $this->longitud, $Default, FALSE); // longitud
		$this->BuildSearchSql($sWhere, $this->latitud, $Default, FALSE); // latitud

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Id->AdvancedSearch->Save(); // Id
			$this->id_persona->AdvancedSearch->Save(); // id_persona
			$this->id_ciudad->AdvancedSearch->Save(); // id_ciudad
			$this->tipo_direccion->AdvancedSearch->Save(); // tipo_direccion
			$this->direccion->AdvancedSearch->Save(); // direccion
			$this->ult_fecha_activo->AdvancedSearch->Save(); // ult_fecha_activo
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
		$this->BuildBasicSearchSQL($sWhere, $this->tipo_direccion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mapa, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->longitud, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->latitud, $arKeywords, $type);
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
		if ($this->id_persona->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_ciudad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipo_direccion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->direccion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ult_fecha_activo->AdvancedSearch->IssetSession())
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
		$this->id_persona->AdvancedSearch->UnsetSession();
		$this->id_ciudad->AdvancedSearch->UnsetSession();
		$this->tipo_direccion->AdvancedSearch->UnsetSession();
		$this->direccion->AdvancedSearch->UnsetSession();
		$this->ult_fecha_activo->AdvancedSearch->UnsetSession();
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
		$this->id_persona->AdvancedSearch->Load();
		$this->id_ciudad->AdvancedSearch->Load();
		$this->tipo_direccion->AdvancedSearch->Load();
		$this->direccion->AdvancedSearch->Load();
		$this->ult_fecha_activo->AdvancedSearch->Load();
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
			$this->UpdateSort($this->id_persona); // id_persona
			$this->UpdateSort($this->id_ciudad); // id_ciudad
			$this->UpdateSort($this->tipo_direccion); // tipo_direccion
			$this->UpdateSort($this->direccion); // direccion
			$this->UpdateSort($this->ult_fecha_activo); // ult_fecha_activo
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
				$this->ult_fecha_activo->setSort("DESC");
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->id_persona->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->Id->setSort("");
				$this->id_persona->setSort("");
				$this->id_ciudad->setSort("");
				$this->tipo_direccion->setSort("");
				$this->direccion->setSort("");
				$this->ult_fecha_activo->setSort("");
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
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
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

		// id_persona
		$this->id_persona->AdvancedSearch->SearchValue = @$_GET["x_id_persona"];
		if ($this->id_persona->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_persona->AdvancedSearch->SearchOperator = @$_GET["z_id_persona"];

		// id_ciudad
		$this->id_ciudad->AdvancedSearch->SearchValue = @$_GET["x_id_ciudad"];
		if ($this->id_ciudad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_ciudad->AdvancedSearch->SearchOperator = @$_GET["z_id_ciudad"];

		// tipo_direccion
		$this->tipo_direccion->AdvancedSearch->SearchValue = @$_GET["x_tipo_direccion"];
		if ($this->tipo_direccion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->tipo_direccion->AdvancedSearch->SearchOperator = @$_GET["z_tipo_direccion"];

		// direccion
		$this->direccion->AdvancedSearch->SearchValue = @$_GET["x_direccion"];
		if ($this->direccion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->direccion->AdvancedSearch->SearchOperator = @$_GET["z_direccion"];

		// ult_fecha_activo
		$this->ult_fecha_activo->AdvancedSearch->SearchValue = @$_GET["x_ult_fecha_activo"];
		if ($this->ult_fecha_activo->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->ult_fecha_activo->AdvancedSearch->SearchOperator = @$_GET["z_ult_fecha_activo"];
		$this->ult_fecha_activo->AdvancedSearch->SearchCondition = @$_GET["v_ult_fecha_activo"];
		$this->ult_fecha_activo->AdvancedSearch->SearchValue2 = @$_GET["y_ult_fecha_activo"];
		if ($this->ult_fecha_activo->AdvancedSearch->SearchValue2 <> "" && $this->Command == "") $this->Command = "search";
		$this->ult_fecha_activo->AdvancedSearch->SearchOperator2 = @$_GET["w_ult_fecha_activo"];

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
				$this->id_persona->HrefValue = "personasview.php?showdetail=direcciones,telefonos,emails,vehiculos,deuda_persona&Id=" . $this->id_persona->CurrentValue; // Add prefix/suffix
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

			// id_persona
			$this->id_persona->EditAttrs["class"] = "form-control";
			$this->id_persona->EditCustomAttributes = "";
			if (trim(strval($this->id_persona->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_persona->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
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

			// id_ciudad
			$this->id_ciudad->EditAttrs["class"] = "form-control";
			$this->id_ciudad->EditCustomAttributes = 'onChange=initMap(this);';

			// tipo_direccion
			$this->tipo_direccion->EditAttrs["class"] = "form-control";
			$this->tipo_direccion->EditCustomAttributes = "";
			$this->tipo_direccion->EditValue = $this->tipo_direccion->Options(TRUE);

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->AdvancedSearch->SearchValue);
			$this->direccion->PlaceHolder = ew_RemoveHtml($this->direccion->FldCaption());

			// ult_fecha_activo
			$this->ult_fecha_activo->EditAttrs["class"] = "form-control";
			$this->ult_fecha_activo->EditCustomAttributes = "";
			$this->ult_fecha_activo->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->ult_fecha_activo->AdvancedSearch->SearchValue, 7), 7));
			$this->ult_fecha_activo->PlaceHolder = ew_RemoveHtml($this->ult_fecha_activo->FldCaption());
			$this->ult_fecha_activo->EditAttrs["class"] = "form-control";
			$this->ult_fecha_activo->EditCustomAttributes = "";
			$this->ult_fecha_activo->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->ult_fecha_activo->AdvancedSearch->SearchValue2, 7), 7));
			$this->ult_fecha_activo->PlaceHolder = ew_RemoveHtml($this->ult_fecha_activo->FldCaption());

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
		if (!ew_CheckEuroDate($this->ult_fecha_activo->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->ult_fecha_activo->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->ult_fecha_activo->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->ult_fecha_activo->FldErrMsg());
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
		$this->id_persona->AdvancedSearch->Load();
		$this->id_ciudad->AdvancedSearch->Load();
		$this->tipo_direccion->AdvancedSearch->Load();
		$this->direccion->AdvancedSearch->Load();
		$this->ult_fecha_activo->AdvancedSearch->Load();
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

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "personas") {
			global $personas;
			if (!isset($personas)) $personas = new cpersonas;
			$rsmaster = $personas->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$personas;
					$personas->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}
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
		$this->AddSearchQueryString($sQry, $this->id_persona); // id_persona
		$this->AddSearchQueryString($sQry, $this->id_ciudad); // id_ciudad
		$this->AddSearchQueryString($sQry, $this->tipo_direccion); // tipo_direccion
		$this->AddSearchQueryString($sQry, $this->direccion); // direccion
		$this->AddSearchQueryString($sQry, $this->ult_fecha_activo); // ult_fecha_activo
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

			// Update URL
			$this->AddUrl = $this->AddMasterUrl($this->AddUrl);
			$this->InlineAddUrl = $this->AddMasterUrl($this->InlineAddUrl);
			$this->GridAddUrl = $this->AddMasterUrl($this->GridAddUrl);
			$this->GridEditUrl = $this->AddMasterUrl($this->GridEditUrl);

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

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
fdireccioneslist.Lists["x_id_persona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_paterno","x_materno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
fdireccioneslist.Lists["x_id_persona"].Data = "<?php echo $direcciones_list->id_persona->LookupFilterQuery(FALSE, "list") ?>";
fdireccioneslist.Lists["x_id_ciudad"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"ciudades"};
fdireccioneslist.Lists["x_id_ciudad"].Data = "<?php echo $direcciones_list->id_ciudad->LookupFilterQuery(FALSE, "list") ?>";
fdireccioneslist.Lists["x_tipo_direccion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdireccioneslist.Lists["x_tipo_direccion"].Options = <?php echo json_encode($direcciones_list->tipo_direccion->Options()) ?>;

// Form object for search
var CurrentSearchForm = fdireccioneslistsrch = new ew_Form("fdireccioneslistsrch");

// Validate function for search
fdireccioneslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_ult_fecha_activo");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($direcciones->ult_fecha_activo->FldErrMsg()) ?>");

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
fdireccioneslistsrch.Lists["x_id_persona"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_paterno","x_materno",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"personas"};
fdireccioneslistsrch.Lists["x_id_persona"].Data = "<?php echo $direcciones_list->id_persona->LookupFilterQuery(FALSE, "extbs") ?>";

// Init search panel as collapsed
if (fdireccioneslistsrch) fdireccioneslistsrch.InitSearchPanel = true;
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
<?php if (($direcciones->Export == "") || (EW_EXPORT_MASTER_RECORD && $direcciones->Export == "print")) { ?>
<?php
if ($direcciones_list->DbMasterFilter <> "" && $direcciones->getCurrentMasterTable() == "personas") {
	if ($direcciones_list->MasterRecordExists) {
?>
<?php include_once "personasmaster.php" ?>
<?php
	}
}
?>
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
<?php if ($direcciones->id_persona->Visible) { // id_persona ?>
	<div id="xsc_id_persona" class="ewCell form-group">
		<label for="x_id_persona" class="ewSearchCaption ewLabel"><?php echo $direcciones->id_persona->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_persona" id="z_id_persona" value="="></span>
		<span class="ewSearchField">
<select data-table="direcciones" data-field="x_id_persona" data-value-separator="<?php echo $direcciones->id_persona->DisplayValueSeparatorAttribute() ?>" id="x_id_persona" name="x_id_persona"<?php echo $direcciones->id_persona->EditAttributes() ?>>
<?php echo $direcciones->id_persona->SelectOptionListHtml("x_id_persona") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($direcciones->direccion->Visible) { // direccion ?>
	<div id="xsc_direccion" class="ewCell form-group">
		<label for="x_direccion" class="ewSearchCaption ewLabel"><?php echo $direcciones->direccion->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_direccion" id="z_direccion" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_direccion" name="x_direccion" id="x_direccion" size="60" maxlength="255" placeholder="<?php echo ew_HtmlEncode($direcciones->direccion->getPlaceHolder()) ?>" value="<?php echo $direcciones->direccion->EditValue ?>"<?php echo $direcciones->direccion->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($direcciones->ult_fecha_activo->Visible) { // ult_fecha_activo ?>
	<div id="xsc_ult_fecha_activo" class="ewCell form-group">
		<label for="x_ult_fecha_activo" class="ewSearchCaption ewLabel"><?php echo $direcciones->ult_fecha_activo->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_ult_fecha_activo" id="z_ult_fecha_activo" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-table="direcciones" data-field="x_ult_fecha_activo" data-format="7" name="x_ult_fecha_activo" id="x_ult_fecha_activo" size="20" placeholder="<?php echo ew_HtmlEncode($direcciones->ult_fecha_activo->getPlaceHolder()) ?>" value="<?php echo $direcciones->ult_fecha_activo->EditValue ?>"<?php echo $direcciones->ult_fecha_activo->EditAttributes() ?>>
<?php if (!$direcciones->ult_fecha_activo->ReadOnly && !$direcciones->ult_fecha_activo->Disabled && !isset($direcciones->ult_fecha_activo->EditAttrs["readonly"]) && !isset($direcciones->ult_fecha_activo->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdireccioneslistsrch", "x_ult_fecha_activo", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_ult_fecha_activo">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_ult_fecha_activo">
<input type="text" data-table="direcciones" data-field="x_ult_fecha_activo" data-format="7" name="y_ult_fecha_activo" id="y_ult_fecha_activo" size="20" placeholder="<?php echo ew_HtmlEncode($direcciones->ult_fecha_activo->getPlaceHolder()) ?>" value="<?php echo $direcciones->ult_fecha_activo->EditValue2 ?>"<?php echo $direcciones->ult_fecha_activo->EditAttributes() ?>>
<?php if (!$direcciones->ult_fecha_activo->ReadOnly && !$direcciones->ult_fecha_activo->Disabled && !isset($direcciones->ult_fecha_activo->EditAttrs["readonly"]) && !isset($direcciones->ult_fecha_activo->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdireccioneslistsrch", "y_ult_fecha_activo", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
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
<?php if ($direcciones->getCurrentMasterTable() == "personas" && $direcciones->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="personas">
<input type="hidden" name="fk_Id" value="<?php echo $direcciones->id_persona->getSessionValue() ?>">
<?php } ?>
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
<?php if ($direcciones->id_persona->Visible) { // id_persona ?>
	<?php if ($direcciones->SortUrl($direcciones->id_persona) == "") { ?>
		<th data-name="id_persona" class="<?php echo $direcciones->id_persona->HeaderCellClass() ?>"><div id="elh_direcciones_id_persona" class="direcciones_id_persona"><div class="ewTableHeaderCaption"><?php echo $direcciones->id_persona->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_persona" class="<?php echo $direcciones->id_persona->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->id_persona) ?>',1);"><div id="elh_direcciones_id_persona" class="direcciones_id_persona">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->id_persona->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->id_persona->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->id_persona->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->id_ciudad->Visible) { // id_ciudad ?>
	<?php if ($direcciones->SortUrl($direcciones->id_ciudad) == "") { ?>
		<th data-name="id_ciudad" class="<?php echo $direcciones->id_ciudad->HeaderCellClass() ?>"><div id="elh_direcciones_id_ciudad" class="direcciones_id_ciudad"><div class="ewTableHeaderCaption"><?php echo $direcciones->id_ciudad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_ciudad" class="<?php echo $direcciones->id_ciudad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->id_ciudad) ?>',1);"><div id="elh_direcciones_id_ciudad" class="direcciones_id_ciudad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->id_ciudad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->id_ciudad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->id_ciudad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->tipo_direccion->Visible) { // tipo_direccion ?>
	<?php if ($direcciones->SortUrl($direcciones->tipo_direccion) == "") { ?>
		<th data-name="tipo_direccion" class="<?php echo $direcciones->tipo_direccion->HeaderCellClass() ?>"><div id="elh_direcciones_tipo_direccion" class="direcciones_tipo_direccion"><div class="ewTableHeaderCaption"><?php echo $direcciones->tipo_direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo_direccion" class="<?php echo $direcciones->tipo_direccion->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->tipo_direccion) ?>',1);"><div id="elh_direcciones_tipo_direccion" class="direcciones_tipo_direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->tipo_direccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->tipo_direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->tipo_direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->direccion->Visible) { // direccion ?>
	<?php if ($direcciones->SortUrl($direcciones->direccion) == "") { ?>
		<th data-name="direccion" class="<?php echo $direcciones->direccion->HeaderCellClass() ?>"><div id="elh_direcciones_direccion" class="direcciones_direccion"><div class="ewTableHeaderCaption"><?php echo $direcciones->direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="direccion" class="<?php echo $direcciones->direccion->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->direccion) ?>',1);"><div id="elh_direcciones_direccion" class="direcciones_direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->direccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->ult_fecha_activo->Visible) { // ult_fecha_activo ?>
	<?php if ($direcciones->SortUrl($direcciones->ult_fecha_activo) == "") { ?>
		<th data-name="ult_fecha_activo" class="<?php echo $direcciones->ult_fecha_activo->HeaderCellClass() ?>"><div id="elh_direcciones_ult_fecha_activo" class="direcciones_ult_fecha_activo"><div class="ewTableHeaderCaption"><?php echo $direcciones->ult_fecha_activo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ult_fecha_activo" class="<?php echo $direcciones->ult_fecha_activo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->ult_fecha_activo) ?>',1);"><div id="elh_direcciones_ult_fecha_activo" class="direcciones_ult_fecha_activo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->ult_fecha_activo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->ult_fecha_activo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->ult_fecha_activo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
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
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->longitud->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->longitud->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->longitud->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($direcciones->latitud->Visible) { // latitud ?>
	<?php if ($direcciones->SortUrl($direcciones->latitud) == "") { ?>
		<th data-name="latitud" class="<?php echo $direcciones->latitud->HeaderCellClass() ?>"><div id="elh_direcciones_latitud" class="direcciones_latitud"><div class="ewTableHeaderCaption"><?php echo $direcciones->latitud->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="latitud" class="<?php echo $direcciones->latitud->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $direcciones->SortUrl($direcciones->latitud) ?>',1);"><div id="elh_direcciones_latitud" class="direcciones_latitud">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $direcciones->latitud->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($direcciones->latitud->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($direcciones->latitud->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
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
	<?php if ($direcciones->id_persona->Visible) { // id_persona ?>
		<td data-name="id_persona"<?php echo $direcciones->id_persona->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_id_persona" class="direcciones_id_persona">
<span<?php echo $direcciones->id_persona->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($direcciones->id_persona->ListViewValue())) && $direcciones->id_persona->LinkAttributes() <> "") { ?>
<a<?php echo $direcciones->id_persona->LinkAttributes() ?>><?php echo $direcciones->id_persona->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $direcciones->id_persona->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->id_ciudad->Visible) { // id_ciudad ?>
		<td data-name="id_ciudad"<?php echo $direcciones->id_ciudad->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_id_ciudad" class="direcciones_id_ciudad">
<span<?php echo $direcciones->id_ciudad->ViewAttributes() ?>>
<?php echo $direcciones->id_ciudad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->tipo_direccion->Visible) { // tipo_direccion ?>
		<td data-name="tipo_direccion"<?php echo $direcciones->tipo_direccion->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_tipo_direccion" class="direcciones_tipo_direccion">
<span<?php echo $direcciones->tipo_direccion->ViewAttributes() ?>>
<?php echo $direcciones->tipo_direccion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->direccion->Visible) { // direccion ?>
		<td data-name="direccion"<?php echo $direcciones->direccion->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_direccion" class="direcciones_direccion">
<span<?php echo $direcciones->direccion->ViewAttributes() ?>>
<?php echo $direcciones->direccion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($direcciones->ult_fecha_activo->Visible) { // ult_fecha_activo ?>
		<td data-name="ult_fecha_activo"<?php echo $direcciones->ult_fecha_activo->CellAttributes() ?>>
<span id="el<?php echo $direcciones_list->RowCnt ?>_direcciones_ult_fecha_activo" class="direcciones_ult_fecha_activo">
<span<?php echo $direcciones->ult_fecha_activo->ViewAttributes() ?>>
<?php echo $direcciones->ult_fecha_activo->ListViewValue() ?></span>
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
	longitude: <?php echo ew_VarToJson($direcciones->longitud->CurrentValue, "undefined") ?>,
	title: <?php echo ew_VarToJson($direcciones->direccion->CurrentValue, "string") ?>
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
