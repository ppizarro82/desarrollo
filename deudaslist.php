<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "deudasinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "cuentasinfo.php" ?>
<?php include_once "deuda_personagridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$deudas_list = NULL; // Initialize page object first

class cdeudas_list extends cdeudas {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'deudas';

	// Page object name
	var $PageObjName = 'deudas_list';

	// Grid form hidden field names
	var $FormName = 'fdeudaslist';
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

		// Table object (deudas)
		if (!isset($GLOBALS["deudas"]) || get_class($GLOBALS["deudas"]) == "cdeudas") {
			$GLOBALS["deudas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["deudas"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "deudasadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "deudasdelete.php";
		$this->MultiUpdateUrl = "deudasupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Table object (cuentas)
		if (!isset($GLOBALS['cuentas'])) $GLOBALS['cuentas'] = new ccuentas();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'deudas', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fdeudaslistsrch";

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
		$this->id_cliente->SetVisibility();
		$this->id_ciudad->SetVisibility();
		$this->id_agente->SetVisibility();
		$this->id_estadodeuda->SetVisibility();
		$this->mig_codigo_deuda->SetVisibility();
		$this->mig_fecha_desembolso->SetVisibility();
		$this->mig_moneda->SetVisibility();
		$this->mig_tasa->SetVisibility();
		$this->mig_plazo->SetVisibility();
		$this->mig_dias_mora->SetVisibility();
		$this->mig_monto_desembolso->SetVisibility();
		$this->mig_intereses->SetVisibility();
		$this->mig_cargos_gastos->SetVisibility();
		$this->mig_total_deuda->SetVisibility();

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
		global $EW_EXPORT, $deudas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($deudas);
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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "cuentas") {
			global $cuentas;
			$rsmaster = $cuentas->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("cuentaslist.php"); // Return to master page
			} else {
				$cuentas->LoadListRowValues($rsmaster);
				$cuentas->RowType = EW_ROWTYPE_MASTER; // Master row
				$cuentas->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "users") {
			global $users;
			$rsmaster = $users->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("userslist.php"); // Return to master page
			} else {
				$users->LoadListRowValues($rsmaster);
				$users->RowType = EW_ROWTYPE_MASTER; // Master row
				$users->RenderListRow();
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
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "fdeudaslistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->Id->AdvancedSearch->ToJson(), ","); // Field Id
		$sFilterList = ew_Concat($sFilterList, $this->id_cliente->AdvancedSearch->ToJson(), ","); // Field id_cliente
		$sFilterList = ew_Concat($sFilterList, $this->id_ciudad->AdvancedSearch->ToJson(), ","); // Field id_ciudad
		$sFilterList = ew_Concat($sFilterList, $this->id_agente->AdvancedSearch->ToJson(), ","); // Field id_agente
		$sFilterList = ew_Concat($sFilterList, $this->id_estadodeuda->AdvancedSearch->ToJson(), ","); // Field id_estadodeuda
		$sFilterList = ew_Concat($sFilterList, $this->mig_codigo_deuda->AdvancedSearch->ToJson(), ","); // Field mig_codigo_deuda
		$sFilterList = ew_Concat($sFilterList, $this->mig_tipo_operacion->AdvancedSearch->ToJson(), ","); // Field mig_tipo_operacion
		$sFilterList = ew_Concat($sFilterList, $this->mig_fecha_desembolso->AdvancedSearch->ToJson(), ","); // Field mig_fecha_desembolso
		$sFilterList = ew_Concat($sFilterList, $this->mig_fecha_estado->AdvancedSearch->ToJson(), ","); // Field mig_fecha_estado
		$sFilterList = ew_Concat($sFilterList, $this->mig_anios_castigo->AdvancedSearch->ToJson(), ","); // Field mig_anios_castigo
		$sFilterList = ew_Concat($sFilterList, $this->mig_tipo_garantia->AdvancedSearch->ToJson(), ","); // Field mig_tipo_garantia
		$sFilterList = ew_Concat($sFilterList, $this->mig_real->AdvancedSearch->ToJson(), ","); // Field mig_real
		$sFilterList = ew_Concat($sFilterList, $this->mig_actividad_economica->AdvancedSearch->ToJson(), ","); // Field mig_actividad_economica
		$sFilterList = ew_Concat($sFilterList, $this->mig_agencia->AdvancedSearch->ToJson(), ","); // Field mig_agencia
		$sFilterList = ew_Concat($sFilterList, $this->mig_no_juicio->AdvancedSearch->ToJson(), ","); // Field mig_no_juicio
		$sFilterList = ew_Concat($sFilterList, $this->mig_nombre_abogado->AdvancedSearch->ToJson(), ","); // Field mig_nombre_abogado
		$sFilterList = ew_Concat($sFilterList, $this->mig_fase_procesal->AdvancedSearch->ToJson(), ","); // Field mig_fase_procesal
		$sFilterList = ew_Concat($sFilterList, $this->mig_moneda->AdvancedSearch->ToJson(), ","); // Field mig_moneda
		$sFilterList = ew_Concat($sFilterList, $this->mig_tasa->AdvancedSearch->ToJson(), ","); // Field mig_tasa
		$sFilterList = ew_Concat($sFilterList, $this->mig_plazo->AdvancedSearch->ToJson(), ","); // Field mig_plazo
		$sFilterList = ew_Concat($sFilterList, $this->mig_dias_mora->AdvancedSearch->ToJson(), ","); // Field mig_dias_mora
		$sFilterList = ew_Concat($sFilterList, $this->mig_monto_desembolso->AdvancedSearch->ToJson(), ","); // Field mig_monto_desembolso
		$sFilterList = ew_Concat($sFilterList, $this->mig_total_cartera->AdvancedSearch->ToJson(), ","); // Field mig_total_cartera
		$sFilterList = ew_Concat($sFilterList, $this->mig_capital->AdvancedSearch->ToJson(), ","); // Field mig_capital
		$sFilterList = ew_Concat($sFilterList, $this->mig_intereses->AdvancedSearch->ToJson(), ","); // Field mig_intereses
		$sFilterList = ew_Concat($sFilterList, $this->mig_cargos_gastos->AdvancedSearch->ToJson(), ","); // Field mig_cargos_gastos
		$sFilterList = ew_Concat($sFilterList, $this->mig_total_deuda->AdvancedSearch->ToJson(), ","); // Field mig_total_deuda
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fdeudaslistsrch", $filters);

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

		// Field id_cliente
		$this->id_cliente->AdvancedSearch->SearchValue = @$filter["x_id_cliente"];
		$this->id_cliente->AdvancedSearch->SearchOperator = @$filter["z_id_cliente"];
		$this->id_cliente->AdvancedSearch->SearchCondition = @$filter["v_id_cliente"];
		$this->id_cliente->AdvancedSearch->SearchValue2 = @$filter["y_id_cliente"];
		$this->id_cliente->AdvancedSearch->SearchOperator2 = @$filter["w_id_cliente"];
		$this->id_cliente->AdvancedSearch->Save();

		// Field id_ciudad
		$this->id_ciudad->AdvancedSearch->SearchValue = @$filter["x_id_ciudad"];
		$this->id_ciudad->AdvancedSearch->SearchOperator = @$filter["z_id_ciudad"];
		$this->id_ciudad->AdvancedSearch->SearchCondition = @$filter["v_id_ciudad"];
		$this->id_ciudad->AdvancedSearch->SearchValue2 = @$filter["y_id_ciudad"];
		$this->id_ciudad->AdvancedSearch->SearchOperator2 = @$filter["w_id_ciudad"];
		$this->id_ciudad->AdvancedSearch->Save();

		// Field id_agente
		$this->id_agente->AdvancedSearch->SearchValue = @$filter["x_id_agente"];
		$this->id_agente->AdvancedSearch->SearchOperator = @$filter["z_id_agente"];
		$this->id_agente->AdvancedSearch->SearchCondition = @$filter["v_id_agente"];
		$this->id_agente->AdvancedSearch->SearchValue2 = @$filter["y_id_agente"];
		$this->id_agente->AdvancedSearch->SearchOperator2 = @$filter["w_id_agente"];
		$this->id_agente->AdvancedSearch->Save();

		// Field id_estadodeuda
		$this->id_estadodeuda->AdvancedSearch->SearchValue = @$filter["x_id_estadodeuda"];
		$this->id_estadodeuda->AdvancedSearch->SearchOperator = @$filter["z_id_estadodeuda"];
		$this->id_estadodeuda->AdvancedSearch->SearchCondition = @$filter["v_id_estadodeuda"];
		$this->id_estadodeuda->AdvancedSearch->SearchValue2 = @$filter["y_id_estadodeuda"];
		$this->id_estadodeuda->AdvancedSearch->SearchOperator2 = @$filter["w_id_estadodeuda"];
		$this->id_estadodeuda->AdvancedSearch->Save();

		// Field mig_codigo_deuda
		$this->mig_codigo_deuda->AdvancedSearch->SearchValue = @$filter["x_mig_codigo_deuda"];
		$this->mig_codigo_deuda->AdvancedSearch->SearchOperator = @$filter["z_mig_codigo_deuda"];
		$this->mig_codigo_deuda->AdvancedSearch->SearchCondition = @$filter["v_mig_codigo_deuda"];
		$this->mig_codigo_deuda->AdvancedSearch->SearchValue2 = @$filter["y_mig_codigo_deuda"];
		$this->mig_codigo_deuda->AdvancedSearch->SearchOperator2 = @$filter["w_mig_codigo_deuda"];
		$this->mig_codigo_deuda->AdvancedSearch->Save();

		// Field mig_tipo_operacion
		$this->mig_tipo_operacion->AdvancedSearch->SearchValue = @$filter["x_mig_tipo_operacion"];
		$this->mig_tipo_operacion->AdvancedSearch->SearchOperator = @$filter["z_mig_tipo_operacion"];
		$this->mig_tipo_operacion->AdvancedSearch->SearchCondition = @$filter["v_mig_tipo_operacion"];
		$this->mig_tipo_operacion->AdvancedSearch->SearchValue2 = @$filter["y_mig_tipo_operacion"];
		$this->mig_tipo_operacion->AdvancedSearch->SearchOperator2 = @$filter["w_mig_tipo_operacion"];
		$this->mig_tipo_operacion->AdvancedSearch->Save();

		// Field mig_fecha_desembolso
		$this->mig_fecha_desembolso->AdvancedSearch->SearchValue = @$filter["x_mig_fecha_desembolso"];
		$this->mig_fecha_desembolso->AdvancedSearch->SearchOperator = @$filter["z_mig_fecha_desembolso"];
		$this->mig_fecha_desembolso->AdvancedSearch->SearchCondition = @$filter["v_mig_fecha_desembolso"];
		$this->mig_fecha_desembolso->AdvancedSearch->SearchValue2 = @$filter["y_mig_fecha_desembolso"];
		$this->mig_fecha_desembolso->AdvancedSearch->SearchOperator2 = @$filter["w_mig_fecha_desembolso"];
		$this->mig_fecha_desembolso->AdvancedSearch->Save();

		// Field mig_fecha_estado
		$this->mig_fecha_estado->AdvancedSearch->SearchValue = @$filter["x_mig_fecha_estado"];
		$this->mig_fecha_estado->AdvancedSearch->SearchOperator = @$filter["z_mig_fecha_estado"];
		$this->mig_fecha_estado->AdvancedSearch->SearchCondition = @$filter["v_mig_fecha_estado"];
		$this->mig_fecha_estado->AdvancedSearch->SearchValue2 = @$filter["y_mig_fecha_estado"];
		$this->mig_fecha_estado->AdvancedSearch->SearchOperator2 = @$filter["w_mig_fecha_estado"];
		$this->mig_fecha_estado->AdvancedSearch->Save();

		// Field mig_anios_castigo
		$this->mig_anios_castigo->AdvancedSearch->SearchValue = @$filter["x_mig_anios_castigo"];
		$this->mig_anios_castigo->AdvancedSearch->SearchOperator = @$filter["z_mig_anios_castigo"];
		$this->mig_anios_castigo->AdvancedSearch->SearchCondition = @$filter["v_mig_anios_castigo"];
		$this->mig_anios_castigo->AdvancedSearch->SearchValue2 = @$filter["y_mig_anios_castigo"];
		$this->mig_anios_castigo->AdvancedSearch->SearchOperator2 = @$filter["w_mig_anios_castigo"];
		$this->mig_anios_castigo->AdvancedSearch->Save();

		// Field mig_tipo_garantia
		$this->mig_tipo_garantia->AdvancedSearch->SearchValue = @$filter["x_mig_tipo_garantia"];
		$this->mig_tipo_garantia->AdvancedSearch->SearchOperator = @$filter["z_mig_tipo_garantia"];
		$this->mig_tipo_garantia->AdvancedSearch->SearchCondition = @$filter["v_mig_tipo_garantia"];
		$this->mig_tipo_garantia->AdvancedSearch->SearchValue2 = @$filter["y_mig_tipo_garantia"];
		$this->mig_tipo_garantia->AdvancedSearch->SearchOperator2 = @$filter["w_mig_tipo_garantia"];
		$this->mig_tipo_garantia->AdvancedSearch->Save();

		// Field mig_real
		$this->mig_real->AdvancedSearch->SearchValue = @$filter["x_mig_real"];
		$this->mig_real->AdvancedSearch->SearchOperator = @$filter["z_mig_real"];
		$this->mig_real->AdvancedSearch->SearchCondition = @$filter["v_mig_real"];
		$this->mig_real->AdvancedSearch->SearchValue2 = @$filter["y_mig_real"];
		$this->mig_real->AdvancedSearch->SearchOperator2 = @$filter["w_mig_real"];
		$this->mig_real->AdvancedSearch->Save();

		// Field mig_actividad_economica
		$this->mig_actividad_economica->AdvancedSearch->SearchValue = @$filter["x_mig_actividad_economica"];
		$this->mig_actividad_economica->AdvancedSearch->SearchOperator = @$filter["z_mig_actividad_economica"];
		$this->mig_actividad_economica->AdvancedSearch->SearchCondition = @$filter["v_mig_actividad_economica"];
		$this->mig_actividad_economica->AdvancedSearch->SearchValue2 = @$filter["y_mig_actividad_economica"];
		$this->mig_actividad_economica->AdvancedSearch->SearchOperator2 = @$filter["w_mig_actividad_economica"];
		$this->mig_actividad_economica->AdvancedSearch->Save();

		// Field mig_agencia
		$this->mig_agencia->AdvancedSearch->SearchValue = @$filter["x_mig_agencia"];
		$this->mig_agencia->AdvancedSearch->SearchOperator = @$filter["z_mig_agencia"];
		$this->mig_agencia->AdvancedSearch->SearchCondition = @$filter["v_mig_agencia"];
		$this->mig_agencia->AdvancedSearch->SearchValue2 = @$filter["y_mig_agencia"];
		$this->mig_agencia->AdvancedSearch->SearchOperator2 = @$filter["w_mig_agencia"];
		$this->mig_agencia->AdvancedSearch->Save();

		// Field mig_no_juicio
		$this->mig_no_juicio->AdvancedSearch->SearchValue = @$filter["x_mig_no_juicio"];
		$this->mig_no_juicio->AdvancedSearch->SearchOperator = @$filter["z_mig_no_juicio"];
		$this->mig_no_juicio->AdvancedSearch->SearchCondition = @$filter["v_mig_no_juicio"];
		$this->mig_no_juicio->AdvancedSearch->SearchValue2 = @$filter["y_mig_no_juicio"];
		$this->mig_no_juicio->AdvancedSearch->SearchOperator2 = @$filter["w_mig_no_juicio"];
		$this->mig_no_juicio->AdvancedSearch->Save();

		// Field mig_nombre_abogado
		$this->mig_nombre_abogado->AdvancedSearch->SearchValue = @$filter["x_mig_nombre_abogado"];
		$this->mig_nombre_abogado->AdvancedSearch->SearchOperator = @$filter["z_mig_nombre_abogado"];
		$this->mig_nombre_abogado->AdvancedSearch->SearchCondition = @$filter["v_mig_nombre_abogado"];
		$this->mig_nombre_abogado->AdvancedSearch->SearchValue2 = @$filter["y_mig_nombre_abogado"];
		$this->mig_nombre_abogado->AdvancedSearch->SearchOperator2 = @$filter["w_mig_nombre_abogado"];
		$this->mig_nombre_abogado->AdvancedSearch->Save();

		// Field mig_fase_procesal
		$this->mig_fase_procesal->AdvancedSearch->SearchValue = @$filter["x_mig_fase_procesal"];
		$this->mig_fase_procesal->AdvancedSearch->SearchOperator = @$filter["z_mig_fase_procesal"];
		$this->mig_fase_procesal->AdvancedSearch->SearchCondition = @$filter["v_mig_fase_procesal"];
		$this->mig_fase_procesal->AdvancedSearch->SearchValue2 = @$filter["y_mig_fase_procesal"];
		$this->mig_fase_procesal->AdvancedSearch->SearchOperator2 = @$filter["w_mig_fase_procesal"];
		$this->mig_fase_procesal->AdvancedSearch->Save();

		// Field mig_moneda
		$this->mig_moneda->AdvancedSearch->SearchValue = @$filter["x_mig_moneda"];
		$this->mig_moneda->AdvancedSearch->SearchOperator = @$filter["z_mig_moneda"];
		$this->mig_moneda->AdvancedSearch->SearchCondition = @$filter["v_mig_moneda"];
		$this->mig_moneda->AdvancedSearch->SearchValue2 = @$filter["y_mig_moneda"];
		$this->mig_moneda->AdvancedSearch->SearchOperator2 = @$filter["w_mig_moneda"];
		$this->mig_moneda->AdvancedSearch->Save();

		// Field mig_tasa
		$this->mig_tasa->AdvancedSearch->SearchValue = @$filter["x_mig_tasa"];
		$this->mig_tasa->AdvancedSearch->SearchOperator = @$filter["z_mig_tasa"];
		$this->mig_tasa->AdvancedSearch->SearchCondition = @$filter["v_mig_tasa"];
		$this->mig_tasa->AdvancedSearch->SearchValue2 = @$filter["y_mig_tasa"];
		$this->mig_tasa->AdvancedSearch->SearchOperator2 = @$filter["w_mig_tasa"];
		$this->mig_tasa->AdvancedSearch->Save();

		// Field mig_plazo
		$this->mig_plazo->AdvancedSearch->SearchValue = @$filter["x_mig_plazo"];
		$this->mig_plazo->AdvancedSearch->SearchOperator = @$filter["z_mig_plazo"];
		$this->mig_plazo->AdvancedSearch->SearchCondition = @$filter["v_mig_plazo"];
		$this->mig_plazo->AdvancedSearch->SearchValue2 = @$filter["y_mig_plazo"];
		$this->mig_plazo->AdvancedSearch->SearchOperator2 = @$filter["w_mig_plazo"];
		$this->mig_plazo->AdvancedSearch->Save();

		// Field mig_dias_mora
		$this->mig_dias_mora->AdvancedSearch->SearchValue = @$filter["x_mig_dias_mora"];
		$this->mig_dias_mora->AdvancedSearch->SearchOperator = @$filter["z_mig_dias_mora"];
		$this->mig_dias_mora->AdvancedSearch->SearchCondition = @$filter["v_mig_dias_mora"];
		$this->mig_dias_mora->AdvancedSearch->SearchValue2 = @$filter["y_mig_dias_mora"];
		$this->mig_dias_mora->AdvancedSearch->SearchOperator2 = @$filter["w_mig_dias_mora"];
		$this->mig_dias_mora->AdvancedSearch->Save();

		// Field mig_monto_desembolso
		$this->mig_monto_desembolso->AdvancedSearch->SearchValue = @$filter["x_mig_monto_desembolso"];
		$this->mig_monto_desembolso->AdvancedSearch->SearchOperator = @$filter["z_mig_monto_desembolso"];
		$this->mig_monto_desembolso->AdvancedSearch->SearchCondition = @$filter["v_mig_monto_desembolso"];
		$this->mig_monto_desembolso->AdvancedSearch->SearchValue2 = @$filter["y_mig_monto_desembolso"];
		$this->mig_monto_desembolso->AdvancedSearch->SearchOperator2 = @$filter["w_mig_monto_desembolso"];
		$this->mig_monto_desembolso->AdvancedSearch->Save();

		// Field mig_total_cartera
		$this->mig_total_cartera->AdvancedSearch->SearchValue = @$filter["x_mig_total_cartera"];
		$this->mig_total_cartera->AdvancedSearch->SearchOperator = @$filter["z_mig_total_cartera"];
		$this->mig_total_cartera->AdvancedSearch->SearchCondition = @$filter["v_mig_total_cartera"];
		$this->mig_total_cartera->AdvancedSearch->SearchValue2 = @$filter["y_mig_total_cartera"];
		$this->mig_total_cartera->AdvancedSearch->SearchOperator2 = @$filter["w_mig_total_cartera"];
		$this->mig_total_cartera->AdvancedSearch->Save();

		// Field mig_capital
		$this->mig_capital->AdvancedSearch->SearchValue = @$filter["x_mig_capital"];
		$this->mig_capital->AdvancedSearch->SearchOperator = @$filter["z_mig_capital"];
		$this->mig_capital->AdvancedSearch->SearchCondition = @$filter["v_mig_capital"];
		$this->mig_capital->AdvancedSearch->SearchValue2 = @$filter["y_mig_capital"];
		$this->mig_capital->AdvancedSearch->SearchOperator2 = @$filter["w_mig_capital"];
		$this->mig_capital->AdvancedSearch->Save();

		// Field mig_intereses
		$this->mig_intereses->AdvancedSearch->SearchValue = @$filter["x_mig_intereses"];
		$this->mig_intereses->AdvancedSearch->SearchOperator = @$filter["z_mig_intereses"];
		$this->mig_intereses->AdvancedSearch->SearchCondition = @$filter["v_mig_intereses"];
		$this->mig_intereses->AdvancedSearch->SearchValue2 = @$filter["y_mig_intereses"];
		$this->mig_intereses->AdvancedSearch->SearchOperator2 = @$filter["w_mig_intereses"];
		$this->mig_intereses->AdvancedSearch->Save();

		// Field mig_cargos_gastos
		$this->mig_cargos_gastos->AdvancedSearch->SearchValue = @$filter["x_mig_cargos_gastos"];
		$this->mig_cargos_gastos->AdvancedSearch->SearchOperator = @$filter["z_mig_cargos_gastos"];
		$this->mig_cargos_gastos->AdvancedSearch->SearchCondition = @$filter["v_mig_cargos_gastos"];
		$this->mig_cargos_gastos->AdvancedSearch->SearchValue2 = @$filter["y_mig_cargos_gastos"];
		$this->mig_cargos_gastos->AdvancedSearch->SearchOperator2 = @$filter["w_mig_cargos_gastos"];
		$this->mig_cargos_gastos->AdvancedSearch->Save();

		// Field mig_total_deuda
		$this->mig_total_deuda->AdvancedSearch->SearchValue = @$filter["x_mig_total_deuda"];
		$this->mig_total_deuda->AdvancedSearch->SearchOperator = @$filter["z_mig_total_deuda"];
		$this->mig_total_deuda->AdvancedSearch->SearchCondition = @$filter["v_mig_total_deuda"];
		$this->mig_total_deuda->AdvancedSearch->SearchValue2 = @$filter["y_mig_total_deuda"];
		$this->mig_total_deuda->AdvancedSearch->SearchOperator2 = @$filter["w_mig_total_deuda"];
		$this->mig_total_deuda->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->Id, $Default, FALSE); // Id
		$this->BuildSearchSql($sWhere, $this->id_cliente, $Default, FALSE); // id_cliente
		$this->BuildSearchSql($sWhere, $this->id_ciudad, $Default, FALSE); // id_ciudad
		$this->BuildSearchSql($sWhere, $this->id_agente, $Default, FALSE); // id_agente
		$this->BuildSearchSql($sWhere, $this->id_estadodeuda, $Default, FALSE); // id_estadodeuda
		$this->BuildSearchSql($sWhere, $this->mig_codigo_deuda, $Default, FALSE); // mig_codigo_deuda
		$this->BuildSearchSql($sWhere, $this->mig_tipo_operacion, $Default, FALSE); // mig_tipo_operacion
		$this->BuildSearchSql($sWhere, $this->mig_fecha_desembolso, $Default, FALSE); // mig_fecha_desembolso
		$this->BuildSearchSql($sWhere, $this->mig_fecha_estado, $Default, FALSE); // mig_fecha_estado
		$this->BuildSearchSql($sWhere, $this->mig_anios_castigo, $Default, FALSE); // mig_anios_castigo
		$this->BuildSearchSql($sWhere, $this->mig_tipo_garantia, $Default, FALSE); // mig_tipo_garantia
		$this->BuildSearchSql($sWhere, $this->mig_real, $Default, FALSE); // mig_real
		$this->BuildSearchSql($sWhere, $this->mig_actividad_economica, $Default, FALSE); // mig_actividad_economica
		$this->BuildSearchSql($sWhere, $this->mig_agencia, $Default, FALSE); // mig_agencia
		$this->BuildSearchSql($sWhere, $this->mig_no_juicio, $Default, FALSE); // mig_no_juicio
		$this->BuildSearchSql($sWhere, $this->mig_nombre_abogado, $Default, FALSE); // mig_nombre_abogado
		$this->BuildSearchSql($sWhere, $this->mig_fase_procesal, $Default, FALSE); // mig_fase_procesal
		$this->BuildSearchSql($sWhere, $this->mig_moneda, $Default, FALSE); // mig_moneda
		$this->BuildSearchSql($sWhere, $this->mig_tasa, $Default, FALSE); // mig_tasa
		$this->BuildSearchSql($sWhere, $this->mig_plazo, $Default, FALSE); // mig_plazo
		$this->BuildSearchSql($sWhere, $this->mig_dias_mora, $Default, FALSE); // mig_dias_mora
		$this->BuildSearchSql($sWhere, $this->mig_monto_desembolso, $Default, FALSE); // mig_monto_desembolso
		$this->BuildSearchSql($sWhere, $this->mig_total_cartera, $Default, FALSE); // mig_total_cartera
		$this->BuildSearchSql($sWhere, $this->mig_capital, $Default, FALSE); // mig_capital
		$this->BuildSearchSql($sWhere, $this->mig_intereses, $Default, FALSE); // mig_intereses
		$this->BuildSearchSql($sWhere, $this->mig_cargos_gastos, $Default, FALSE); // mig_cargos_gastos
		$this->BuildSearchSql($sWhere, $this->mig_total_deuda, $Default, FALSE); // mig_total_deuda

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Id->AdvancedSearch->Save(); // Id
			$this->id_cliente->AdvancedSearch->Save(); // id_cliente
			$this->id_ciudad->AdvancedSearch->Save(); // id_ciudad
			$this->id_agente->AdvancedSearch->Save(); // id_agente
			$this->id_estadodeuda->AdvancedSearch->Save(); // id_estadodeuda
			$this->mig_codigo_deuda->AdvancedSearch->Save(); // mig_codigo_deuda
			$this->mig_tipo_operacion->AdvancedSearch->Save(); // mig_tipo_operacion
			$this->mig_fecha_desembolso->AdvancedSearch->Save(); // mig_fecha_desembolso
			$this->mig_fecha_estado->AdvancedSearch->Save(); // mig_fecha_estado
			$this->mig_anios_castigo->AdvancedSearch->Save(); // mig_anios_castigo
			$this->mig_tipo_garantia->AdvancedSearch->Save(); // mig_tipo_garantia
			$this->mig_real->AdvancedSearch->Save(); // mig_real
			$this->mig_actividad_economica->AdvancedSearch->Save(); // mig_actividad_economica
			$this->mig_agencia->AdvancedSearch->Save(); // mig_agencia
			$this->mig_no_juicio->AdvancedSearch->Save(); // mig_no_juicio
			$this->mig_nombre_abogado->AdvancedSearch->Save(); // mig_nombre_abogado
			$this->mig_fase_procesal->AdvancedSearch->Save(); // mig_fase_procesal
			$this->mig_moneda->AdvancedSearch->Save(); // mig_moneda
			$this->mig_tasa->AdvancedSearch->Save(); // mig_tasa
			$this->mig_plazo->AdvancedSearch->Save(); // mig_plazo
			$this->mig_dias_mora->AdvancedSearch->Save(); // mig_dias_mora
			$this->mig_monto_desembolso->AdvancedSearch->Save(); // mig_monto_desembolso
			$this->mig_total_cartera->AdvancedSearch->Save(); // mig_total_cartera
			$this->mig_capital->AdvancedSearch->Save(); // mig_capital
			$this->mig_intereses->AdvancedSearch->Save(); // mig_intereses
			$this->mig_cargos_gastos->AdvancedSearch->Save(); // mig_cargos_gastos
			$this->mig_total_deuda->AdvancedSearch->Save(); // mig_total_deuda
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
		$this->BuildBasicSearchSQL($sWhere, $this->mig_codigo_deuda, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mig_tipo_operacion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mig_tipo_garantia, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mig_real, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mig_actividad_economica, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mig_agencia, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mig_no_juicio, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mig_nombre_abogado, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mig_fase_procesal, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mig_moneda, $arKeywords, $type);
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
		if ($this->id_cliente->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_ciudad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_agente->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_estadodeuda->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_codigo_deuda->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_tipo_operacion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_fecha_desembolso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_fecha_estado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_anios_castigo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_tipo_garantia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_real->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_actividad_economica->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_agencia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_no_juicio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_nombre_abogado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_fase_procesal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_moneda->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_tasa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_plazo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_dias_mora->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_monto_desembolso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_total_cartera->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_capital->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_intereses->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_cargos_gastos->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mig_total_deuda->AdvancedSearch->IssetSession())
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
		$this->id_cliente->AdvancedSearch->UnsetSession();
		$this->id_ciudad->AdvancedSearch->UnsetSession();
		$this->id_agente->AdvancedSearch->UnsetSession();
		$this->id_estadodeuda->AdvancedSearch->UnsetSession();
		$this->mig_codigo_deuda->AdvancedSearch->UnsetSession();
		$this->mig_tipo_operacion->AdvancedSearch->UnsetSession();
		$this->mig_fecha_desembolso->AdvancedSearch->UnsetSession();
		$this->mig_fecha_estado->AdvancedSearch->UnsetSession();
		$this->mig_anios_castigo->AdvancedSearch->UnsetSession();
		$this->mig_tipo_garantia->AdvancedSearch->UnsetSession();
		$this->mig_real->AdvancedSearch->UnsetSession();
		$this->mig_actividad_economica->AdvancedSearch->UnsetSession();
		$this->mig_agencia->AdvancedSearch->UnsetSession();
		$this->mig_no_juicio->AdvancedSearch->UnsetSession();
		$this->mig_nombre_abogado->AdvancedSearch->UnsetSession();
		$this->mig_fase_procesal->AdvancedSearch->UnsetSession();
		$this->mig_moneda->AdvancedSearch->UnsetSession();
		$this->mig_tasa->AdvancedSearch->UnsetSession();
		$this->mig_plazo->AdvancedSearch->UnsetSession();
		$this->mig_dias_mora->AdvancedSearch->UnsetSession();
		$this->mig_monto_desembolso->AdvancedSearch->UnsetSession();
		$this->mig_total_cartera->AdvancedSearch->UnsetSession();
		$this->mig_capital->AdvancedSearch->UnsetSession();
		$this->mig_intereses->AdvancedSearch->UnsetSession();
		$this->mig_cargos_gastos->AdvancedSearch->UnsetSession();
		$this->mig_total_deuda->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->Id->AdvancedSearch->Load();
		$this->id_cliente->AdvancedSearch->Load();
		$this->id_ciudad->AdvancedSearch->Load();
		$this->id_agente->AdvancedSearch->Load();
		$this->id_estadodeuda->AdvancedSearch->Load();
		$this->mig_codigo_deuda->AdvancedSearch->Load();
		$this->mig_tipo_operacion->AdvancedSearch->Load();
		$this->mig_fecha_desembolso->AdvancedSearch->Load();
		$this->mig_fecha_estado->AdvancedSearch->Load();
		$this->mig_anios_castigo->AdvancedSearch->Load();
		$this->mig_tipo_garantia->AdvancedSearch->Load();
		$this->mig_real->AdvancedSearch->Load();
		$this->mig_actividad_economica->AdvancedSearch->Load();
		$this->mig_agencia->AdvancedSearch->Load();
		$this->mig_no_juicio->AdvancedSearch->Load();
		$this->mig_nombre_abogado->AdvancedSearch->Load();
		$this->mig_fase_procesal->AdvancedSearch->Load();
		$this->mig_moneda->AdvancedSearch->Load();
		$this->mig_tasa->AdvancedSearch->Load();
		$this->mig_plazo->AdvancedSearch->Load();
		$this->mig_dias_mora->AdvancedSearch->Load();
		$this->mig_monto_desembolso->AdvancedSearch->Load();
		$this->mig_total_cartera->AdvancedSearch->Load();
		$this->mig_capital->AdvancedSearch->Load();
		$this->mig_intereses->AdvancedSearch->Load();
		$this->mig_cargos_gastos->AdvancedSearch->Load();
		$this->mig_total_deuda->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Id); // Id
			$this->UpdateSort($this->id_cliente); // id_cliente
			$this->UpdateSort($this->id_ciudad); // id_ciudad
			$this->UpdateSort($this->id_agente); // id_agente
			$this->UpdateSort($this->id_estadodeuda); // id_estadodeuda
			$this->UpdateSort($this->mig_codigo_deuda); // mig_codigo_deuda
			$this->UpdateSort($this->mig_fecha_desembolso); // mig_fecha_desembolso
			$this->UpdateSort($this->mig_moneda); // mig_moneda
			$this->UpdateSort($this->mig_tasa); // mig_tasa
			$this->UpdateSort($this->mig_plazo); // mig_plazo
			$this->UpdateSort($this->mig_dias_mora); // mig_dias_mora
			$this->UpdateSort($this->mig_monto_desembolso); // mig_monto_desembolso
			$this->UpdateSort($this->mig_intereses); // mig_intereses
			$this->UpdateSort($this->mig_cargos_gastos); // mig_cargos_gastos
			$this->UpdateSort($this->mig_total_deuda); // mig_total_deuda
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->id_cliente->setSessionValue("");
				$this->id_agente->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->Id->setSort("");
				$this->id_cliente->setSort("");
				$this->id_ciudad->setSort("");
				$this->id_agente->setSort("");
				$this->id_estadodeuda->setSort("");
				$this->mig_codigo_deuda->setSort("");
				$this->mig_fecha_desembolso->setSort("");
				$this->mig_moneda->setSort("");
				$this->mig_tasa->setSort("");
				$this->mig_plazo->setSort("");
				$this->mig_dias_mora->setSort("");
				$this->mig_monto_desembolso->setSort("");
				$this->mig_intereses->setSort("");
				$this->mig_cargos_gastos->setSort("");
				$this->mig_total_deuda->setSort("");
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
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"deudas\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
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
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"deudas\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'EditBtn',url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("EditLink") . "</a>";
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
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-table=\"deudas\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("CopyLink") . "</a>";
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

		// "detail_deuda_persona"
		$oListOpt = &$this->ListOptions->Items["detail_deuda_persona"];
		if ($Security->AllowList(CurrentProjectID() . 'deuda_persona')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("deuda_persona", "TblCaption");
			$body .= "&nbsp;" . str_replace("%c", $this->deuda_persona_Count, $Language->Phrase("DetailCount"));
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("deuda_personalist.php?" . EW_TABLE_SHOW_MASTER . "=deudas&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
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
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-table=\"deudas\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["detail"];
		$DetailTableLink = "";
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fdeudaslist,url:'" . $this->MultiDeleteUrl . "',msg:ewLanguage.Phrase('DeleteConfirmMsg')});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fdeudaslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fdeudaslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fdeudaslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fdeudaslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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

		// id_cliente
		$this->id_cliente->AdvancedSearch->SearchValue = @$_GET["x_id_cliente"];
		if ($this->id_cliente->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_cliente->AdvancedSearch->SearchOperator = @$_GET["z_id_cliente"];

		// id_ciudad
		$this->id_ciudad->AdvancedSearch->SearchValue = @$_GET["x_id_ciudad"];
		if ($this->id_ciudad->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_ciudad->AdvancedSearch->SearchOperator = @$_GET["z_id_ciudad"];

		// id_agente
		$this->id_agente->AdvancedSearch->SearchValue = @$_GET["x_id_agente"];
		if ($this->id_agente->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_agente->AdvancedSearch->SearchOperator = @$_GET["z_id_agente"];

		// id_estadodeuda
		$this->id_estadodeuda->AdvancedSearch->SearchValue = @$_GET["x_id_estadodeuda"];
		if ($this->id_estadodeuda->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id_estadodeuda->AdvancedSearch->SearchOperator = @$_GET["z_id_estadodeuda"];

		// mig_codigo_deuda
		$this->mig_codigo_deuda->AdvancedSearch->SearchValue = @$_GET["x_mig_codigo_deuda"];
		if ($this->mig_codigo_deuda->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_codigo_deuda->AdvancedSearch->SearchOperator = @$_GET["z_mig_codigo_deuda"];

		// mig_tipo_operacion
		$this->mig_tipo_operacion->AdvancedSearch->SearchValue = @$_GET["x_mig_tipo_operacion"];
		if ($this->mig_tipo_operacion->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_tipo_operacion->AdvancedSearch->SearchOperator = @$_GET["z_mig_tipo_operacion"];

		// mig_fecha_desembolso
		$this->mig_fecha_desembolso->AdvancedSearch->SearchValue = @$_GET["x_mig_fecha_desembolso"];
		if ($this->mig_fecha_desembolso->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_fecha_desembolso->AdvancedSearch->SearchOperator = @$_GET["z_mig_fecha_desembolso"];

		// mig_fecha_estado
		$this->mig_fecha_estado->AdvancedSearch->SearchValue = @$_GET["x_mig_fecha_estado"];
		if ($this->mig_fecha_estado->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_fecha_estado->AdvancedSearch->SearchOperator = @$_GET["z_mig_fecha_estado"];

		// mig_anios_castigo
		$this->mig_anios_castigo->AdvancedSearch->SearchValue = @$_GET["x_mig_anios_castigo"];
		if ($this->mig_anios_castigo->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_anios_castigo->AdvancedSearch->SearchOperator = @$_GET["z_mig_anios_castigo"];

		// mig_tipo_garantia
		$this->mig_tipo_garantia->AdvancedSearch->SearchValue = @$_GET["x_mig_tipo_garantia"];
		if ($this->mig_tipo_garantia->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_tipo_garantia->AdvancedSearch->SearchOperator = @$_GET["z_mig_tipo_garantia"];

		// mig_real
		$this->mig_real->AdvancedSearch->SearchValue = @$_GET["x_mig_real"];
		if ($this->mig_real->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_real->AdvancedSearch->SearchOperator = @$_GET["z_mig_real"];

		// mig_actividad_economica
		$this->mig_actividad_economica->AdvancedSearch->SearchValue = @$_GET["x_mig_actividad_economica"];
		if ($this->mig_actividad_economica->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_actividad_economica->AdvancedSearch->SearchOperator = @$_GET["z_mig_actividad_economica"];

		// mig_agencia
		$this->mig_agencia->AdvancedSearch->SearchValue = @$_GET["x_mig_agencia"];
		if ($this->mig_agencia->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_agencia->AdvancedSearch->SearchOperator = @$_GET["z_mig_agencia"];

		// mig_no_juicio
		$this->mig_no_juicio->AdvancedSearch->SearchValue = @$_GET["x_mig_no_juicio"];
		if ($this->mig_no_juicio->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_no_juicio->AdvancedSearch->SearchOperator = @$_GET["z_mig_no_juicio"];

		// mig_nombre_abogado
		$this->mig_nombre_abogado->AdvancedSearch->SearchValue = @$_GET["x_mig_nombre_abogado"];
		if ($this->mig_nombre_abogado->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_nombre_abogado->AdvancedSearch->SearchOperator = @$_GET["z_mig_nombre_abogado"];

		// mig_fase_procesal
		$this->mig_fase_procesal->AdvancedSearch->SearchValue = @$_GET["x_mig_fase_procesal"];
		if ($this->mig_fase_procesal->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_fase_procesal->AdvancedSearch->SearchOperator = @$_GET["z_mig_fase_procesal"];

		// mig_moneda
		$this->mig_moneda->AdvancedSearch->SearchValue = @$_GET["x_mig_moneda"];
		if ($this->mig_moneda->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_moneda->AdvancedSearch->SearchOperator = @$_GET["z_mig_moneda"];

		// mig_tasa
		$this->mig_tasa->AdvancedSearch->SearchValue = @$_GET["x_mig_tasa"];
		if ($this->mig_tasa->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_tasa->AdvancedSearch->SearchOperator = @$_GET["z_mig_tasa"];

		// mig_plazo
		$this->mig_plazo->AdvancedSearch->SearchValue = @$_GET["x_mig_plazo"];
		if ($this->mig_plazo->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_plazo->AdvancedSearch->SearchOperator = @$_GET["z_mig_plazo"];

		// mig_dias_mora
		$this->mig_dias_mora->AdvancedSearch->SearchValue = @$_GET["x_mig_dias_mora"];
		if ($this->mig_dias_mora->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_dias_mora->AdvancedSearch->SearchOperator = @$_GET["z_mig_dias_mora"];

		// mig_monto_desembolso
		$this->mig_monto_desembolso->AdvancedSearch->SearchValue = @$_GET["x_mig_monto_desembolso"];
		if ($this->mig_monto_desembolso->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_monto_desembolso->AdvancedSearch->SearchOperator = @$_GET["z_mig_monto_desembolso"];

		// mig_total_cartera
		$this->mig_total_cartera->AdvancedSearch->SearchValue = @$_GET["x_mig_total_cartera"];
		if ($this->mig_total_cartera->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_total_cartera->AdvancedSearch->SearchOperator = @$_GET["z_mig_total_cartera"];

		// mig_capital
		$this->mig_capital->AdvancedSearch->SearchValue = @$_GET["x_mig_capital"];
		if ($this->mig_capital->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_capital->AdvancedSearch->SearchOperator = @$_GET["z_mig_capital"];

		// mig_intereses
		$this->mig_intereses->AdvancedSearch->SearchValue = @$_GET["x_mig_intereses"];
		if ($this->mig_intereses->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_intereses->AdvancedSearch->SearchOperator = @$_GET["z_mig_intereses"];

		// mig_cargos_gastos
		$this->mig_cargos_gastos->AdvancedSearch->SearchValue = @$_GET["x_mig_cargos_gastos"];
		if ($this->mig_cargos_gastos->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_cargos_gastos->AdvancedSearch->SearchOperator = @$_GET["z_mig_cargos_gastos"];

		// mig_total_deuda
		$this->mig_total_deuda->AdvancedSearch->SearchValue = @$_GET["x_mig_total_deuda"];
		if ($this->mig_total_deuda->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mig_total_deuda->AdvancedSearch->SearchOperator = @$_GET["z_mig_total_deuda"];
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
		$this->cuenta->setDbValue($row['cuenta']);
		$this->id_cliente->setDbValue($row['id_cliente']);
		$this->id_ciudad->setDbValue($row['id_ciudad']);
		$this->id_agente->setDbValue($row['id_agente']);
		$this->id_estadodeuda->setDbValue($row['id_estadodeuda']);
		$this->mig_codigo_deuda->setDbValue($row['mig_codigo_deuda']);
		$this->mig_tipo_operacion->setDbValue($row['mig_tipo_operacion']);
		$this->mig_fecha_desembolso->setDbValue($row['mig_fecha_desembolso']);
		$this->mig_fecha_estado->setDbValue($row['mig_fecha_estado']);
		$this->mig_anios_castigo->setDbValue($row['mig_anios_castigo']);
		$this->mig_tipo_garantia->setDbValue($row['mig_tipo_garantia']);
		$this->mig_real->setDbValue($row['mig_real']);
		$this->mig_actividad_economica->setDbValue($row['mig_actividad_economica']);
		$this->mig_agencia->setDbValue($row['mig_agencia']);
		$this->mig_no_juicio->setDbValue($row['mig_no_juicio']);
		$this->mig_nombre_abogado->setDbValue($row['mig_nombre_abogado']);
		$this->mig_fase_procesal->setDbValue($row['mig_fase_procesal']);
		$this->mig_moneda->setDbValue($row['mig_moneda']);
		$this->mig_tasa->setDbValue($row['mig_tasa']);
		$this->mig_plazo->setDbValue($row['mig_plazo']);
		$this->mig_dias_mora->setDbValue($row['mig_dias_mora']);
		$this->mig_monto_desembolso->setDbValue($row['mig_monto_desembolso']);
		$this->mig_total_cartera->setDbValue($row['mig_total_cartera']);
		$this->mig_capital->setDbValue($row['mig_capital']);
		$this->mig_intereses->setDbValue($row['mig_intereses']);
		$this->mig_cargos_gastos->setDbValue($row['mig_cargos_gastos']);
		$this->mig_total_deuda->setDbValue($row['mig_total_deuda']);
		if (!isset($GLOBALS["deuda_persona_grid"])) $GLOBALS["deuda_persona_grid"] = new cdeuda_persona_grid;
		$sDetailFilter = $GLOBALS["deuda_persona"]->SqlDetailFilter_deudas();
		$sDetailFilter = str_replace("@id_deuda@", ew_AdjustSql($this->Id->DbValue, "DB"), $sDetailFilter);
		$GLOBALS["deuda_persona"]->setCurrentMasterTable("deudas");
		$sDetailFilter = $GLOBALS["deuda_persona"]->ApplyUserIDFilters($sDetailFilter);
		$this->deuda_persona_Count = $GLOBALS["deuda_persona"]->LoadRecordCount($sDetailFilter);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['Id'] = NULL;
		$row['cuenta'] = NULL;
		$row['id_cliente'] = NULL;
		$row['id_ciudad'] = NULL;
		$row['id_agente'] = NULL;
		$row['id_estadodeuda'] = NULL;
		$row['mig_codigo_deuda'] = NULL;
		$row['mig_tipo_operacion'] = NULL;
		$row['mig_fecha_desembolso'] = NULL;
		$row['mig_fecha_estado'] = NULL;
		$row['mig_anios_castigo'] = NULL;
		$row['mig_tipo_garantia'] = NULL;
		$row['mig_real'] = NULL;
		$row['mig_actividad_economica'] = NULL;
		$row['mig_agencia'] = NULL;
		$row['mig_no_juicio'] = NULL;
		$row['mig_nombre_abogado'] = NULL;
		$row['mig_fase_procesal'] = NULL;
		$row['mig_moneda'] = NULL;
		$row['mig_tasa'] = NULL;
		$row['mig_plazo'] = NULL;
		$row['mig_dias_mora'] = NULL;
		$row['mig_monto_desembolso'] = NULL;
		$row['mig_total_cartera'] = NULL;
		$row['mig_capital'] = NULL;
		$row['mig_intereses'] = NULL;
		$row['mig_cargos_gastos'] = NULL;
		$row['mig_total_deuda'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->cuenta->DbValue = $row['cuenta'];
		$this->id_cliente->DbValue = $row['id_cliente'];
		$this->id_ciudad->DbValue = $row['id_ciudad'];
		$this->id_agente->DbValue = $row['id_agente'];
		$this->id_estadodeuda->DbValue = $row['id_estadodeuda'];
		$this->mig_codigo_deuda->DbValue = $row['mig_codigo_deuda'];
		$this->mig_tipo_operacion->DbValue = $row['mig_tipo_operacion'];
		$this->mig_fecha_desembolso->DbValue = $row['mig_fecha_desembolso'];
		$this->mig_fecha_estado->DbValue = $row['mig_fecha_estado'];
		$this->mig_anios_castigo->DbValue = $row['mig_anios_castigo'];
		$this->mig_tipo_garantia->DbValue = $row['mig_tipo_garantia'];
		$this->mig_real->DbValue = $row['mig_real'];
		$this->mig_actividad_economica->DbValue = $row['mig_actividad_economica'];
		$this->mig_agencia->DbValue = $row['mig_agencia'];
		$this->mig_no_juicio->DbValue = $row['mig_no_juicio'];
		$this->mig_nombre_abogado->DbValue = $row['mig_nombre_abogado'];
		$this->mig_fase_procesal->DbValue = $row['mig_fase_procesal'];
		$this->mig_moneda->DbValue = $row['mig_moneda'];
		$this->mig_tasa->DbValue = $row['mig_tasa'];
		$this->mig_plazo->DbValue = $row['mig_plazo'];
		$this->mig_dias_mora->DbValue = $row['mig_dias_mora'];
		$this->mig_monto_desembolso->DbValue = $row['mig_monto_desembolso'];
		$this->mig_total_cartera->DbValue = $row['mig_total_cartera'];
		$this->mig_capital->DbValue = $row['mig_capital'];
		$this->mig_intereses->DbValue = $row['mig_intereses'];
		$this->mig_cargos_gastos->DbValue = $row['mig_cargos_gastos'];
		$this->mig_total_deuda->DbValue = $row['mig_total_deuda'];
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

		// Convert decimal values if posted back
		if ($this->mig_tasa->FormValue == $this->mig_tasa->CurrentValue && is_numeric(ew_StrToFloat($this->mig_tasa->CurrentValue)))
			$this->mig_tasa->CurrentValue = ew_StrToFloat($this->mig_tasa->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_plazo->FormValue == $this->mig_plazo->CurrentValue && is_numeric(ew_StrToFloat($this->mig_plazo->CurrentValue)))
			$this->mig_plazo->CurrentValue = ew_StrToFloat($this->mig_plazo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_dias_mora->FormValue == $this->mig_dias_mora->CurrentValue && is_numeric(ew_StrToFloat($this->mig_dias_mora->CurrentValue)))
			$this->mig_dias_mora->CurrentValue = ew_StrToFloat($this->mig_dias_mora->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_monto_desembolso->FormValue == $this->mig_monto_desembolso->CurrentValue && is_numeric(ew_StrToFloat($this->mig_monto_desembolso->CurrentValue)))
			$this->mig_monto_desembolso->CurrentValue = ew_StrToFloat($this->mig_monto_desembolso->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_intereses->FormValue == $this->mig_intereses->CurrentValue && is_numeric(ew_StrToFloat($this->mig_intereses->CurrentValue)))
			$this->mig_intereses->CurrentValue = ew_StrToFloat($this->mig_intereses->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_cargos_gastos->FormValue == $this->mig_cargos_gastos->CurrentValue && is_numeric(ew_StrToFloat($this->mig_cargos_gastos->CurrentValue)))
			$this->mig_cargos_gastos->CurrentValue = ew_StrToFloat($this->mig_cargos_gastos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_total_deuda->FormValue == $this->mig_total_deuda->CurrentValue && is_numeric(ew_StrToFloat($this->mig_total_deuda->CurrentValue)))
			$this->mig_total_deuda->CurrentValue = ew_StrToFloat($this->mig_total_deuda->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// Id
		// cuenta

		$this->cuenta->CellCssStyle = "white-space: nowrap;";

		// id_cliente
		// id_ciudad
		// id_agente
		// id_estadodeuda
		// mig_codigo_deuda
		// mig_tipo_operacion
		// mig_fecha_desembolso
		// mig_fecha_estado
		// mig_anios_castigo
		// mig_tipo_garantia
		// mig_real
		// mig_actividad_economica
		// mig_agencia
		// mig_no_juicio
		// mig_nombre_abogado
		// mig_fase_procesal
		// mig_moneda
		// mig_tasa
		// mig_plazo
		// mig_dias_mora
		// mig_monto_desembolso
		// mig_total_cartera
		// mig_capital
		// mig_intereses
		// mig_cargos_gastos
		// mig_total_deuda

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// id_cliente
		if (strval($this->id_cliente->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_cliente->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuentas`";
		$sWhereWrk = "";
		$this->id_cliente->LookupFilters = array();
		$lookuptblfilter = "`estado`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_cliente, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_cliente->ViewValue = $this->id_cliente->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_cliente->ViewValue = $this->id_cliente->CurrentValue;
			}
		} else {
			$this->id_cliente->ViewValue = NULL;
		}
		$this->id_cliente->ViewCustomAttributes = "";

		// id_ciudad
		if (strval($this->id_ciudad->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_ciudad->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `ciudades`";
		$sWhereWrk = "";
		$this->id_ciudad->LookupFilters = array();
		$lookuptblfilter = "`estado`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
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

		// id_agente
		if (strval($this->id_agente->CurrentValue) <> "") {
			$sFilterWrk = "`id_user`" . ew_SearchString("=", $this->id_agente->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_user`, `First_Name` AS `DispFld`, `Last_Name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->id_agente->LookupFilters = array();
		$lookuptblfilter = "`User_Level`=2";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_agente, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `First_Name`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->id_agente->ViewValue = $this->id_agente->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_agente->ViewValue = $this->id_agente->CurrentValue;
			}
		} else {
			$this->id_agente->ViewValue = NULL;
		}
		$this->id_agente->ViewCustomAttributes = "";

		// id_estadodeuda
		if (strval($this->id_estadodeuda->CurrentValue) <> "") {
			$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_estadodeuda->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estado_deuda`";
		$sWhereWrk = "";
		$this->id_estadodeuda->LookupFilters = array();
		$lookuptblfilter = "`estado`=1";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_estadodeuda, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_estadodeuda->ViewValue = $this->id_estadodeuda->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_estadodeuda->ViewValue = $this->id_estadodeuda->CurrentValue;
			}
		} else {
			$this->id_estadodeuda->ViewValue = NULL;
		}
		$this->id_estadodeuda->ViewCustomAttributes = "";

		// mig_codigo_deuda
		$this->mig_codigo_deuda->ViewValue = $this->mig_codigo_deuda->CurrentValue;
		$this->mig_codigo_deuda->ViewCustomAttributes = "";

		// mig_tipo_operacion
		$this->mig_tipo_operacion->ViewValue = $this->mig_tipo_operacion->CurrentValue;
		$this->mig_tipo_operacion->ViewCustomAttributes = "";

		// mig_fecha_desembolso
		$this->mig_fecha_desembolso->ViewValue = $this->mig_fecha_desembolso->CurrentValue;
		$this->mig_fecha_desembolso->ViewValue = ew_FormatDateTime($this->mig_fecha_desembolso->ViewValue, 7);
		$this->mig_fecha_desembolso->ViewCustomAttributes = "";

		// mig_fecha_estado
		$this->mig_fecha_estado->ViewValue = $this->mig_fecha_estado->CurrentValue;
		$this->mig_fecha_estado->ViewValue = ew_FormatDateTime($this->mig_fecha_estado->ViewValue, 7);
		$this->mig_fecha_estado->ViewCustomAttributes = "";

		// mig_anios_castigo
		$this->mig_anios_castigo->ViewValue = $this->mig_anios_castigo->CurrentValue;
		$this->mig_anios_castigo->ViewValue = ew_FormatNumber($this->mig_anios_castigo->ViewValue, 0, 0, 0, 0);
		$this->mig_anios_castigo->ViewCustomAttributes = "";

		// mig_tipo_garantia
		$this->mig_tipo_garantia->ViewValue = $this->mig_tipo_garantia->CurrentValue;
		$this->mig_tipo_garantia->ViewCustomAttributes = "";

		// mig_real
		$this->mig_real->ViewValue = $this->mig_real->CurrentValue;
		$this->mig_real->ViewCustomAttributes = "";

		// mig_actividad_economica
		$this->mig_actividad_economica->ViewValue = $this->mig_actividad_economica->CurrentValue;
		$this->mig_actividad_economica->ViewCustomAttributes = "";

		// mig_agencia
		$this->mig_agencia->ViewValue = $this->mig_agencia->CurrentValue;
		$this->mig_agencia->ViewCustomAttributes = "";

		// mig_no_juicio
		$this->mig_no_juicio->ViewValue = $this->mig_no_juicio->CurrentValue;
		$this->mig_no_juicio->ViewCustomAttributes = "";

		// mig_nombre_abogado
		$this->mig_nombre_abogado->ViewValue = $this->mig_nombre_abogado->CurrentValue;
		$this->mig_nombre_abogado->ViewCustomAttributes = "";

		// mig_fase_procesal
		$this->mig_fase_procesal->ViewValue = $this->mig_fase_procesal->CurrentValue;
		$this->mig_fase_procesal->ViewCustomAttributes = "";

		// mig_moneda
		if (strval($this->mig_moneda->CurrentValue) <> "") {
			$this->mig_moneda->ViewValue = $this->mig_moneda->OptionCaption($this->mig_moneda->CurrentValue);
		} else {
			$this->mig_moneda->ViewValue = NULL;
		}
		$this->mig_moneda->ViewCustomAttributes = "";

		// mig_tasa
		$this->mig_tasa->ViewValue = $this->mig_tasa->CurrentValue;
		$this->mig_tasa->ViewValue = ew_FormatNumber($this->mig_tasa->ViewValue, 2, 0, 0, 0);
		$this->mig_tasa->ViewCustomAttributes = "";

		// mig_plazo
		$this->mig_plazo->ViewValue = $this->mig_plazo->CurrentValue;
		$this->mig_plazo->ViewValue = ew_FormatNumber($this->mig_plazo->ViewValue, 2, 0, 0, 0);
		$this->mig_plazo->ViewCustomAttributes = "";

		// mig_dias_mora
		$this->mig_dias_mora->ViewValue = $this->mig_dias_mora->CurrentValue;
		$this->mig_dias_mora->ViewValue = ew_FormatNumber($this->mig_dias_mora->ViewValue, 2, 0, 0, 0);
		$this->mig_dias_mora->ViewCustomAttributes = "";

		// mig_monto_desembolso
		$this->mig_monto_desembolso->ViewValue = $this->mig_monto_desembolso->CurrentValue;
		$this->mig_monto_desembolso->ViewValue = ew_FormatNumber($this->mig_monto_desembolso->ViewValue, 2, 0, 0, 0);
		$this->mig_monto_desembolso->ViewCustomAttributes = "";

		// mig_total_cartera
		$this->mig_total_cartera->ViewValue = $this->mig_total_cartera->CurrentValue;
		$this->mig_total_cartera->ViewValue = ew_FormatNumber($this->mig_total_cartera->ViewValue, 2, 0, 0, 0);
		$this->mig_total_cartera->ViewCustomAttributes = "";

		// mig_capital
		$this->mig_capital->ViewValue = $this->mig_capital->CurrentValue;
		$this->mig_capital->ViewValue = ew_FormatNumber($this->mig_capital->ViewValue, 2, 0, 0, 0);
		$this->mig_capital->ViewCustomAttributes = "";

		// mig_intereses
		$this->mig_intereses->ViewValue = $this->mig_intereses->CurrentValue;
		$this->mig_intereses->ViewValue = ew_FormatNumber($this->mig_intereses->ViewValue, 2, 0, 0, 0);
		$this->mig_intereses->ViewCustomAttributes = "";

		// mig_cargos_gastos
		$this->mig_cargos_gastos->ViewValue = $this->mig_cargos_gastos->CurrentValue;
		$this->mig_cargos_gastos->ViewValue = ew_FormatNumber($this->mig_cargos_gastos->ViewValue, 2, 0, 0, 0);
		$this->mig_cargos_gastos->ViewCustomAttributes = "";

		// mig_total_deuda
		$this->mig_total_deuda->ViewValue = $this->mig_total_deuda->CurrentValue;
		$this->mig_total_deuda->ViewValue = ew_FormatNumber($this->mig_total_deuda->ViewValue, 2, 0, 0, 0);
		$this->mig_total_deuda->ViewCustomAttributes = "";

			// Id
			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";
			$this->Id->TooltipValue = "";

			// id_cliente
			$this->id_cliente->LinkCustomAttributes = "";
			if (!ew_Empty($this->id_cliente->CurrentValue)) {
				$this->id_cliente->HrefValue = $this->id_cliente->CurrentValue; // Add prefix/suffix
				$this->id_cliente->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->id_cliente->HrefValue = ew_FullUrl($this->id_cliente->HrefValue, "href");
			} else {
				$this->id_cliente->HrefValue = "";
			}
			$this->id_cliente->TooltipValue = "";

			// id_ciudad
			$this->id_ciudad->LinkCustomAttributes = "";
			$this->id_ciudad->HrefValue = "";
			$this->id_ciudad->TooltipValue = "";

			// id_agente
			$this->id_agente->LinkCustomAttributes = "";
			$this->id_agente->HrefValue = "";
			$this->id_agente->TooltipValue = "";

			// id_estadodeuda
			$this->id_estadodeuda->LinkCustomAttributes = "";
			$this->id_estadodeuda->HrefValue = "";
			$this->id_estadodeuda->TooltipValue = "";

			// mig_codigo_deuda
			$this->mig_codigo_deuda->LinkCustomAttributes = "";
			$this->mig_codigo_deuda->HrefValue = "";
			$this->mig_codigo_deuda->TooltipValue = "";

			// mig_fecha_desembolso
			$this->mig_fecha_desembolso->LinkCustomAttributes = "";
			$this->mig_fecha_desembolso->HrefValue = "";
			$this->mig_fecha_desembolso->TooltipValue = "";

			// mig_moneda
			$this->mig_moneda->LinkCustomAttributes = "";
			$this->mig_moneda->HrefValue = "";
			$this->mig_moneda->TooltipValue = "";

			// mig_tasa
			$this->mig_tasa->LinkCustomAttributes = "";
			$this->mig_tasa->HrefValue = "";
			$this->mig_tasa->TooltipValue = "";

			// mig_plazo
			$this->mig_plazo->LinkCustomAttributes = "";
			$this->mig_plazo->HrefValue = "";
			$this->mig_plazo->TooltipValue = "";

			// mig_dias_mora
			$this->mig_dias_mora->LinkCustomAttributes = "";
			$this->mig_dias_mora->HrefValue = "";
			$this->mig_dias_mora->TooltipValue = "";

			// mig_monto_desembolso
			$this->mig_monto_desembolso->LinkCustomAttributes = "";
			$this->mig_monto_desembolso->HrefValue = "";
			$this->mig_monto_desembolso->TooltipValue = "";

			// mig_intereses
			$this->mig_intereses->LinkCustomAttributes = "";
			$this->mig_intereses->HrefValue = "";
			$this->mig_intereses->TooltipValue = "";

			// mig_cargos_gastos
			$this->mig_cargos_gastos->LinkCustomAttributes = "";
			$this->mig_cargos_gastos->HrefValue = "";
			$this->mig_cargos_gastos->TooltipValue = "";

			// mig_total_deuda
			$this->mig_total_deuda->LinkCustomAttributes = "";
			$this->mig_total_deuda->HrefValue = "";
			$this->mig_total_deuda->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			$this->Id->EditValue = ew_HtmlEncode($this->Id->AdvancedSearch->SearchValue);
			$this->Id->PlaceHolder = ew_RemoveHtml($this->Id->FldCaption());

			// id_cliente
			$this->id_cliente->EditAttrs["class"] = "form-control";
			$this->id_cliente->EditCustomAttributes = "";
			if (trim(strval($this->id_cliente->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_cliente->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `cuentas`";
			$sWhereWrk = "";
			$this->id_cliente->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_cliente, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_cliente->EditValue = $arwrk;

			// id_ciudad
			$this->id_ciudad->EditAttrs["class"] = "form-control";
			$this->id_ciudad->EditCustomAttributes = "";
			if (trim(strval($this->id_ciudad->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_ciudad->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `ciudades`";
			$sWhereWrk = "";
			$this->id_ciudad->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_ciudad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_ciudad->EditValue = $arwrk;

			// id_agente
			$this->id_agente->EditAttrs["class"] = "form-control";
			$this->id_agente->EditCustomAttributes = "";
			if (trim(strval($this->id_agente->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_user`" . ew_SearchString("=", $this->id_agente->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_user`, `First_Name` AS `DispFld`, `Last_Name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->id_agente->LookupFilters = array();
			$lookuptblfilter = "`User_Level`=2";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_agente, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `First_Name`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_agente->EditValue = $arwrk;

			// id_estadodeuda
			$this->id_estadodeuda->EditAttrs["class"] = "form-control";
			$this->id_estadodeuda->EditCustomAttributes = "";
			if (trim(strval($this->id_estadodeuda->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_estadodeuda->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `Id`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `estado_deuda`";
			$sWhereWrk = "";
			$this->id_estadodeuda->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_estadodeuda, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_estadodeuda->EditValue = $arwrk;

			// mig_codigo_deuda
			$this->mig_codigo_deuda->EditAttrs["class"] = "form-control";
			$this->mig_codigo_deuda->EditCustomAttributes = "";
			$this->mig_codigo_deuda->EditValue = ew_HtmlEncode($this->mig_codigo_deuda->AdvancedSearch->SearchValue);
			$this->mig_codigo_deuda->PlaceHolder = ew_RemoveHtml($this->mig_codigo_deuda->FldCaption());

			// mig_fecha_desembolso
			$this->mig_fecha_desembolso->EditAttrs["class"] = "form-control";
			$this->mig_fecha_desembolso->EditCustomAttributes = "";
			$this->mig_fecha_desembolso->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->mig_fecha_desembolso->AdvancedSearch->SearchValue, 7), 7));
			$this->mig_fecha_desembolso->PlaceHolder = ew_RemoveHtml($this->mig_fecha_desembolso->FldCaption());

			// mig_moneda
			$this->mig_moneda->EditAttrs["class"] = "form-control";
			$this->mig_moneda->EditCustomAttributes = "";
			$this->mig_moneda->EditValue = $this->mig_moneda->Options(TRUE);

			// mig_tasa
			$this->mig_tasa->EditAttrs["class"] = "form-control";
			$this->mig_tasa->EditCustomAttributes = "";
			$this->mig_tasa->EditValue = ew_HtmlEncode($this->mig_tasa->AdvancedSearch->SearchValue);
			$this->mig_tasa->PlaceHolder = ew_RemoveHtml($this->mig_tasa->FldCaption());

			// mig_plazo
			$this->mig_plazo->EditAttrs["class"] = "form-control";
			$this->mig_plazo->EditCustomAttributes = "";
			$this->mig_plazo->EditValue = ew_HtmlEncode($this->mig_plazo->AdvancedSearch->SearchValue);
			$this->mig_plazo->PlaceHolder = ew_RemoveHtml($this->mig_plazo->FldCaption());

			// mig_dias_mora
			$this->mig_dias_mora->EditAttrs["class"] = "form-control";
			$this->mig_dias_mora->EditCustomAttributes = "";
			$this->mig_dias_mora->EditValue = ew_HtmlEncode($this->mig_dias_mora->AdvancedSearch->SearchValue);
			$this->mig_dias_mora->PlaceHolder = ew_RemoveHtml($this->mig_dias_mora->FldCaption());

			// mig_monto_desembolso
			$this->mig_monto_desembolso->EditAttrs["class"] = "form-control";
			$this->mig_monto_desembolso->EditCustomAttributes = "";
			$this->mig_monto_desembolso->EditValue = ew_HtmlEncode($this->mig_monto_desembolso->AdvancedSearch->SearchValue);
			$this->mig_monto_desembolso->PlaceHolder = ew_RemoveHtml($this->mig_monto_desembolso->FldCaption());

			// mig_intereses
			$this->mig_intereses->EditAttrs["class"] = "form-control";
			$this->mig_intereses->EditCustomAttributes = "";
			$this->mig_intereses->EditValue = ew_HtmlEncode($this->mig_intereses->AdvancedSearch->SearchValue);
			$this->mig_intereses->PlaceHolder = ew_RemoveHtml($this->mig_intereses->FldCaption());

			// mig_cargos_gastos
			$this->mig_cargos_gastos->EditAttrs["class"] = "form-control";
			$this->mig_cargos_gastos->EditCustomAttributes = "";
			$this->mig_cargos_gastos->EditValue = ew_HtmlEncode($this->mig_cargos_gastos->AdvancedSearch->SearchValue);
			$this->mig_cargos_gastos->PlaceHolder = ew_RemoveHtml($this->mig_cargos_gastos->FldCaption());

			// mig_total_deuda
			$this->mig_total_deuda->EditAttrs["class"] = "form-control";
			$this->mig_total_deuda->EditCustomAttributes = "";
			$this->mig_total_deuda->EditValue = ew_HtmlEncode($this->mig_total_deuda->AdvancedSearch->SearchValue);
			$this->mig_total_deuda->PlaceHolder = ew_RemoveHtml($this->mig_total_deuda->FldCaption());
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
		if (!ew_CheckEuroDate($this->mig_fecha_desembolso->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->mig_fecha_desembolso->FldErrMsg());
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
		$this->id_cliente->AdvancedSearch->Load();
		$this->id_ciudad->AdvancedSearch->Load();
		$this->id_agente->AdvancedSearch->Load();
		$this->id_estadodeuda->AdvancedSearch->Load();
		$this->mig_codigo_deuda->AdvancedSearch->Load();
		$this->mig_tipo_operacion->AdvancedSearch->Load();
		$this->mig_fecha_desembolso->AdvancedSearch->Load();
		$this->mig_fecha_estado->AdvancedSearch->Load();
		$this->mig_anios_castigo->AdvancedSearch->Load();
		$this->mig_tipo_garantia->AdvancedSearch->Load();
		$this->mig_real->AdvancedSearch->Load();
		$this->mig_actividad_economica->AdvancedSearch->Load();
		$this->mig_agencia->AdvancedSearch->Load();
		$this->mig_no_juicio->AdvancedSearch->Load();
		$this->mig_nombre_abogado->AdvancedSearch->Load();
		$this->mig_fase_procesal->AdvancedSearch->Load();
		$this->mig_moneda->AdvancedSearch->Load();
		$this->mig_tasa->AdvancedSearch->Load();
		$this->mig_plazo->AdvancedSearch->Load();
		$this->mig_dias_mora->AdvancedSearch->Load();
		$this->mig_monto_desembolso->AdvancedSearch->Load();
		$this->mig_total_cartera->AdvancedSearch->Load();
		$this->mig_capital->AdvancedSearch->Load();
		$this->mig_intereses->AdvancedSearch->Load();
		$this->mig_cargos_gastos->AdvancedSearch->Load();
		$this->mig_total_deuda->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_deudas\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_deudas',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fdeudaslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "cuentas") {
			global $cuentas;
			if (!isset($cuentas)) $cuentas = new ccuentas;
			$rsmaster = $cuentas->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$cuentas;
					$cuentas->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "users") {
			global $users;
			if (!isset($users)) $users = new cusers;
			$rsmaster = $users->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$users;
					$users->ExportDocument($Doc, $rsmaster, 1, 1);
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
		$this->AddSearchQueryString($sQry, $this->id_cliente); // id_cliente
		$this->AddSearchQueryString($sQry, $this->id_ciudad); // id_ciudad
		$this->AddSearchQueryString($sQry, $this->id_agente); // id_agente
		$this->AddSearchQueryString($sQry, $this->id_estadodeuda); // id_estadodeuda
		$this->AddSearchQueryString($sQry, $this->mig_codigo_deuda); // mig_codigo_deuda
		$this->AddSearchQueryString($sQry, $this->mig_tipo_operacion); // mig_tipo_operacion
		$this->AddSearchQueryString($sQry, $this->mig_fecha_desembolso); // mig_fecha_desembolso
		$this->AddSearchQueryString($sQry, $this->mig_fecha_estado); // mig_fecha_estado
		$this->AddSearchQueryString($sQry, $this->mig_anios_castigo); // mig_anios_castigo
		$this->AddSearchQueryString($sQry, $this->mig_tipo_garantia); // mig_tipo_garantia
		$this->AddSearchQueryString($sQry, $this->mig_real); // mig_real
		$this->AddSearchQueryString($sQry, $this->mig_actividad_economica); // mig_actividad_economica
		$this->AddSearchQueryString($sQry, $this->mig_agencia); // mig_agencia
		$this->AddSearchQueryString($sQry, $this->mig_no_juicio); // mig_no_juicio
		$this->AddSearchQueryString($sQry, $this->mig_nombre_abogado); // mig_nombre_abogado
		$this->AddSearchQueryString($sQry, $this->mig_fase_procesal); // mig_fase_procesal
		$this->AddSearchQueryString($sQry, $this->mig_moneda); // mig_moneda
		$this->AddSearchQueryString($sQry, $this->mig_tasa); // mig_tasa
		$this->AddSearchQueryString($sQry, $this->mig_plazo); // mig_plazo
		$this->AddSearchQueryString($sQry, $this->mig_dias_mora); // mig_dias_mora
		$this->AddSearchQueryString($sQry, $this->mig_monto_desembolso); // mig_monto_desembolso
		$this->AddSearchQueryString($sQry, $this->mig_total_cartera); // mig_total_cartera
		$this->AddSearchQueryString($sQry, $this->mig_capital); // mig_capital
		$this->AddSearchQueryString($sQry, $this->mig_intereses); // mig_intereses
		$this->AddSearchQueryString($sQry, $this->mig_cargos_gastos); // mig_cargos_gastos
		$this->AddSearchQueryString($sQry, $this->mig_total_deuda); // mig_total_deuda

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
			if ($sMasterTblVar == "cuentas") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_Id"] <> "") {
					$GLOBALS["cuentas"]->Id->setQueryStringValue($_GET["fk_Id"]);
					$this->id_cliente->setQueryStringValue($GLOBALS["cuentas"]->Id->QueryStringValue);
					$this->id_cliente->setSessionValue($this->id_cliente->QueryStringValue);
					if (!is_numeric($GLOBALS["cuentas"]->Id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "users") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_id_user"] <> "") {
					$GLOBALS["users"]->id_user->setQueryStringValue($_GET["fk_id_user"]);
					$this->id_agente->setQueryStringValue($GLOBALS["users"]->id_user->QueryStringValue);
					$this->id_agente->setSessionValue($this->id_agente->QueryStringValue);
					if (!is_numeric($GLOBALS["users"]->id_user->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar == "cuentas") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_Id"] <> "") {
					$GLOBALS["cuentas"]->Id->setFormValue($_POST["fk_Id"]);
					$this->id_cliente->setFormValue($GLOBALS["cuentas"]->Id->FormValue);
					$this->id_cliente->setSessionValue($this->id_cliente->FormValue);
					if (!is_numeric($GLOBALS["cuentas"]->Id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
			if ($sMasterTblVar == "users") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_id_user"] <> "") {
					$GLOBALS["users"]->id_user->setFormValue($_POST["fk_id_user"]);
					$this->id_agente->setFormValue($GLOBALS["users"]->id_user->FormValue);
					$this->id_agente->setSessionValue($this->id_agente->FormValue);
					if (!is_numeric($GLOBALS["users"]->id_user->FormValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "cuentas") {
				if ($this->id_cliente->CurrentValue == "") $this->id_cliente->setSessionValue("");
			}
			if ($sMasterTblVar <> "users") {
				if ($this->id_agente->CurrentValue == "") $this->id_agente->setSessionValue("");
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
		case "x_id_cliente":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `cuentas`";
			$sWhereWrk = "";
			$this->id_cliente->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_cliente, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `denominacion`";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_ciudad":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `ciudades`";
			$sWhereWrk = "";
			$this->id_ciudad->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_ciudad, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_agente":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_user` AS `LinkFld`, `First_Name` AS `DispFld`, `Last_Name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->id_agente->LookupFilters = array();
			$lookuptblfilter = "`User_Level`=2";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_user` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_agente, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `First_Name`";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_estadodeuda":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Id` AS `LinkFld`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `estado_deuda`";
			$sWhereWrk = "";
			$this->id_estadodeuda->LookupFilters = array();
			$lookuptblfilter = "`estado`=1";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_estadodeuda, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($deudas_list)) $deudas_list = new cdeudas_list();

// Page init
$deudas_list->Page_Init();

// Page main
$deudas_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$deudas_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($deudas->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fdeudaslist = new ew_Form("fdeudaslist", "list");
fdeudaslist.FormKeyCountName = '<?php echo $deudas_list->FormKeyCountName ?>';

// Form_CustomValidate event
fdeudaslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdeudaslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdeudaslist.Lists["x_id_cliente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"cuentas"};
fdeudaslist.Lists["x_id_cliente"].Data = "<?php echo $deudas_list->id_cliente->LookupFilterQuery(FALSE, "list") ?>";
fdeudaslist.Lists["x_id_ciudad"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"ciudades"};
fdeudaslist.Lists["x_id_ciudad"].Data = "<?php echo $deudas_list->id_ciudad->LookupFilterQuery(FALSE, "list") ?>";
fdeudaslist.Lists["x_id_agente"] = {"LinkField":"x_id_user","Ajax":true,"AutoFill":false,"DisplayFields":["x_First_Name","x_Last_Name","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdeudaslist.Lists["x_id_agente"].Data = "<?php echo $deudas_list->id_agente->LookupFilterQuery(FALSE, "list") ?>";
fdeudaslist.Lists["x_id_estadodeuda"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"estado_deuda"};
fdeudaslist.Lists["x_id_estadodeuda"].Data = "<?php echo $deudas_list->id_estadodeuda->LookupFilterQuery(FALSE, "list") ?>";
fdeudaslist.Lists["x_mig_moneda"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdeudaslist.Lists["x_mig_moneda"].Options = <?php echo json_encode($deudas_list->mig_moneda->Options()) ?>;

// Form object for search
var CurrentSearchForm = fdeudaslistsrch = new ew_Form("fdeudaslistsrch");

// Validate function for search
fdeudaslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_mig_fecha_desembolso");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($deudas->mig_fecha_desembolso->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fdeudaslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdeudaslistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fdeudaslistsrch.Lists["x_id_cliente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"cuentas"};
fdeudaslistsrch.Lists["x_id_cliente"].Data = "<?php echo $deudas_list->id_cliente->LookupFilterQuery(FALSE, "extbs") ?>";
fdeudaslistsrch.Lists["x_id_ciudad"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"ciudades"};
fdeudaslistsrch.Lists["x_id_ciudad"].Data = "<?php echo $deudas_list->id_ciudad->LookupFilterQuery(FALSE, "extbs") ?>";
fdeudaslistsrch.Lists["x_id_agente"] = {"LinkField":"x_id_user","Ajax":true,"AutoFill":false,"DisplayFields":["x_First_Name","x_Last_Name","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdeudaslistsrch.Lists["x_id_agente"].Data = "<?php echo $deudas_list->id_agente->LookupFilterQuery(FALSE, "extbs") ?>";
fdeudaslistsrch.Lists["x_id_estadodeuda"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"estado_deuda"};
fdeudaslistsrch.Lists["x_id_estadodeuda"].Data = "<?php echo $deudas_list->id_estadodeuda->LookupFilterQuery(FALSE, "extbs") ?>";

// Init search panel as collapsed
if (fdeudaslistsrch) fdeudaslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($deudas->Export == "") { ?>
<div class="ewToolbar">
<?php if ($deudas_list->TotalRecs > 0 && $deudas_list->ExportOptions->Visible()) { ?>
<?php $deudas_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($deudas_list->SearchOptions->Visible()) { ?>
<?php $deudas_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($deudas_list->FilterOptions->Visible()) { ?>
<?php $deudas_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($deudas->Export == "") || (EW_EXPORT_MASTER_RECORD && $deudas->Export == "print")) { ?>
<?php
if ($deudas_list->DbMasterFilter <> "" && $deudas->getCurrentMasterTable() == "cuentas") {
	if ($deudas_list->MasterRecordExists) {
?>
<?php include_once "cuentasmaster.php" ?>
<?php
	}
}
?>
<?php
if ($deudas_list->DbMasterFilter <> "" && $deudas->getCurrentMasterTable() == "users") {
	if ($deudas_list->MasterRecordExists) {
?>
<?php include_once "usersmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = $deudas_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($deudas_list->TotalRecs <= 0)
			$deudas_list->TotalRecs = $deudas->ListRecordCount();
	} else {
		if (!$deudas_list->Recordset && ($deudas_list->Recordset = $deudas_list->LoadRecordset()))
			$deudas_list->TotalRecs = $deudas_list->Recordset->RecordCount();
	}
	$deudas_list->StartRec = 1;
	if ($deudas_list->DisplayRecs <= 0 || ($deudas->Export <> "" && $deudas->ExportAll)) // Display all records
		$deudas_list->DisplayRecs = $deudas_list->TotalRecs;
	if (!($deudas->Export <> "" && $deudas->ExportAll))
		$deudas_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$deudas_list->Recordset = $deudas_list->LoadRecordset($deudas_list->StartRec-1, $deudas_list->DisplayRecs);

	// Set no record found message
	if ($deudas->CurrentAction == "" && $deudas_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$deudas_list->setWarningMessage(ew_DeniedMsg());
		if ($deudas_list->SearchWhere == "0=101")
			$deudas_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$deudas_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$deudas_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($deudas->Export == "" && $deudas->CurrentAction == "") { ?>
<form name="fdeudaslistsrch" id="fdeudaslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($deudas_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fdeudaslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="deudas">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$deudas_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$deudas->RowType = EW_ROWTYPE_SEARCH;

// Render row
$deudas->ResetAttrs();
$deudas_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($deudas->id_cliente->Visible) { // id_cliente ?>
	<div id="xsc_id_cliente" class="ewCell form-group">
		<label for="x_id_cliente" class="ewSearchCaption ewLabel"><?php echo $deudas->id_cliente->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_cliente" id="z_id_cliente" value="="></span>
		<span class="ewSearchField">
<select data-table="deudas" data-field="x_id_cliente" data-value-separator="<?php echo $deudas->id_cliente->DisplayValueSeparatorAttribute() ?>" id="x_id_cliente" name="x_id_cliente"<?php echo $deudas->id_cliente->EditAttributes() ?>>
<?php echo $deudas->id_cliente->SelectOptionListHtml("x_id_cliente") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($deudas->id_ciudad->Visible) { // id_ciudad ?>
	<div id="xsc_id_ciudad" class="ewCell form-group">
		<label for="x_id_ciudad" class="ewSearchCaption ewLabel"><?php echo $deudas->id_ciudad->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_ciudad" id="z_id_ciudad" value="="></span>
		<span class="ewSearchField">
<select data-table="deudas" data-field="x_id_ciudad" data-value-separator="<?php echo $deudas->id_ciudad->DisplayValueSeparatorAttribute() ?>" id="x_id_ciudad" name="x_id_ciudad"<?php echo $deudas->id_ciudad->EditAttributes() ?>>
<?php echo $deudas->id_ciudad->SelectOptionListHtml("x_id_ciudad") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($deudas->id_agente->Visible) { // id_agente ?>
	<div id="xsc_id_agente" class="ewCell form-group">
		<label for="x_id_agente" class="ewSearchCaption ewLabel"><?php echo $deudas->id_agente->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_agente" id="z_id_agente" value="="></span>
		<span class="ewSearchField">
<select data-table="deudas" data-field="x_id_agente" data-value-separator="<?php echo $deudas->id_agente->DisplayValueSeparatorAttribute() ?>" id="x_id_agente" name="x_id_agente"<?php echo $deudas->id_agente->EditAttributes() ?>>
<?php echo $deudas->id_agente->SelectOptionListHtml("x_id_agente") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($deudas->id_estadodeuda->Visible) { // id_estadodeuda ?>
	<div id="xsc_id_estadodeuda" class="ewCell form-group">
		<label for="x_id_estadodeuda" class="ewSearchCaption ewLabel"><?php echo $deudas->id_estadodeuda->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_estadodeuda" id="z_id_estadodeuda" value="="></span>
		<span class="ewSearchField">
<select data-table="deudas" data-field="x_id_estadodeuda" data-value-separator="<?php echo $deudas->id_estadodeuda->DisplayValueSeparatorAttribute() ?>" id="x_id_estadodeuda" name="x_id_estadodeuda"<?php echo $deudas->id_estadodeuda->EditAttributes() ?>>
<?php echo $deudas->id_estadodeuda->SelectOptionListHtml("x_id_estadodeuda") ?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($deudas->mig_codigo_deuda->Visible) { // mig_codigo_deuda ?>
	<div id="xsc_mig_codigo_deuda" class="ewCell form-group">
		<label for="x_mig_codigo_deuda" class="ewSearchCaption ewLabel"><?php echo $deudas->mig_codigo_deuda->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mig_codigo_deuda" id="z_mig_codigo_deuda" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-table="deudas" data-field="x_mig_codigo_deuda" name="x_mig_codigo_deuda" id="x_mig_codigo_deuda" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($deudas->mig_codigo_deuda->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_codigo_deuda->EditValue ?>"<?php echo $deudas->mig_codigo_deuda->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($deudas->mig_fecha_desembolso->Visible) { // mig_fecha_desembolso ?>
	<div id="xsc_mig_fecha_desembolso" class="ewCell form-group">
		<label for="x_mig_fecha_desembolso" class="ewSearchCaption ewLabel"><?php echo $deudas->mig_fecha_desembolso->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_mig_fecha_desembolso" id="z_mig_fecha_desembolso" value="="></span>
		<span class="ewSearchField">
<input type="text" data-table="deudas" data-field="x_mig_fecha_desembolso" data-format="7" name="x_mig_fecha_desembolso" id="x_mig_fecha_desembolso" size="16" placeholder="<?php echo ew_HtmlEncode($deudas->mig_fecha_desembolso->getPlaceHolder()) ?>" value="<?php echo $deudas->mig_fecha_desembolso->EditValue ?>"<?php echo $deudas->mig_fecha_desembolso->EditAttributes() ?>>
<?php if (!$deudas->mig_fecha_desembolso->ReadOnly && !$deudas->mig_fecha_desembolso->Disabled && !isset($deudas->mig_fecha_desembolso->EditAttrs["readonly"]) && !isset($deudas->mig_fecha_desembolso->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fdeudaslistsrch", "x_mig_fecha_desembolso", {"ignoreReadonly":true,"useCurrent":false,"format":7});
</script>
<?php } ?>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($deudas_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($deudas_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $deudas_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($deudas_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($deudas_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($deudas_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($deudas_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $deudas_list->ShowPageHeader(); ?>
<?php
$deudas_list->ShowMessage();
?>
<?php if ($deudas_list->TotalRecs > 0 || $deudas->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($deudas_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> deudas">
<?php if ($deudas->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($deudas->CurrentAction <> "gridadd" && $deudas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($deudas_list->Pager)) $deudas_list->Pager = new cPrevNextPager($deudas_list->StartRec, $deudas_list->DisplayRecs, $deudas_list->TotalRecs, $deudas_list->AutoHidePager) ?>
<?php if ($deudas_list->Pager->RecordCount > 0 && $deudas_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($deudas_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $deudas_list->PageUrl() ?>start=<?php echo $deudas_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($deudas_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $deudas_list->PageUrl() ?>start=<?php echo $deudas_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $deudas_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($deudas_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $deudas_list->PageUrl() ?>start=<?php echo $deudas_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($deudas_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $deudas_list->PageUrl() ?>start=<?php echo $deudas_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $deudas_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $deudas_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $deudas_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $deudas_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($deudas_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fdeudaslist" id="fdeudaslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($deudas_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $deudas_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="deudas">
<?php if ($deudas->getCurrentMasterTable() == "cuentas" && $deudas->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="cuentas">
<input type="hidden" name="fk_Id" value="<?php echo $deudas->id_cliente->getSessionValue() ?>">
<?php } ?>
<?php if ($deudas->getCurrentMasterTable() == "users" && $deudas->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="users">
<input type="hidden" name="fk_id_user" value="<?php echo $deudas->id_agente->getSessionValue() ?>">
<?php } ?>
<div id="gmp_deudas" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($deudas_list->TotalRecs > 0 || $deudas->CurrentAction == "gridedit") { ?>
<table id="tbl_deudaslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$deudas_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$deudas_list->RenderListOptions();

// Render list options (header, left)
$deudas_list->ListOptions->Render("header", "left");
?>
<?php if ($deudas->Id->Visible) { // Id ?>
	<?php if ($deudas->SortUrl($deudas->Id) == "") { ?>
		<th data-name="Id" class="<?php echo $deudas->Id->HeaderCellClass() ?>"><div id="elh_deudas_Id" class="deudas_Id"><div class="ewTableHeaderCaption"><?php echo $deudas->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id" class="<?php echo $deudas->Id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->Id) ?>',1);"><div id="elh_deudas_Id" class="deudas_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->id_cliente->Visible) { // id_cliente ?>
	<?php if ($deudas->SortUrl($deudas->id_cliente) == "") { ?>
		<th data-name="id_cliente" class="<?php echo $deudas->id_cliente->HeaderCellClass() ?>"><div id="elh_deudas_id_cliente" class="deudas_id_cliente"><div class="ewTableHeaderCaption"><?php echo $deudas->id_cliente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_cliente" class="<?php echo $deudas->id_cliente->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->id_cliente) ?>',1);"><div id="elh_deudas_id_cliente" class="deudas_id_cliente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->id_cliente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->id_cliente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->id_cliente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->id_ciudad->Visible) { // id_ciudad ?>
	<?php if ($deudas->SortUrl($deudas->id_ciudad) == "") { ?>
		<th data-name="id_ciudad" class="<?php echo $deudas->id_ciudad->HeaderCellClass() ?>"><div id="elh_deudas_id_ciudad" class="deudas_id_ciudad"><div class="ewTableHeaderCaption"><?php echo $deudas->id_ciudad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_ciudad" class="<?php echo $deudas->id_ciudad->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->id_ciudad) ?>',1);"><div id="elh_deudas_id_ciudad" class="deudas_id_ciudad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->id_ciudad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->id_ciudad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->id_ciudad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->id_agente->Visible) { // id_agente ?>
	<?php if ($deudas->SortUrl($deudas->id_agente) == "") { ?>
		<th data-name="id_agente" class="<?php echo $deudas->id_agente->HeaderCellClass() ?>"><div id="elh_deudas_id_agente" class="deudas_id_agente"><div class="ewTableHeaderCaption"><?php echo $deudas->id_agente->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_agente" class="<?php echo $deudas->id_agente->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->id_agente) ?>',1);"><div id="elh_deudas_id_agente" class="deudas_id_agente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->id_agente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->id_agente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->id_agente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->id_estadodeuda->Visible) { // id_estadodeuda ?>
	<?php if ($deudas->SortUrl($deudas->id_estadodeuda) == "") { ?>
		<th data-name="id_estadodeuda" class="<?php echo $deudas->id_estadodeuda->HeaderCellClass() ?>"><div id="elh_deudas_id_estadodeuda" class="deudas_id_estadodeuda"><div class="ewTableHeaderCaption"><?php echo $deudas->id_estadodeuda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_estadodeuda" class="<?php echo $deudas->id_estadodeuda->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->id_estadodeuda) ?>',1);"><div id="elh_deudas_id_estadodeuda" class="deudas_id_estadodeuda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->id_estadodeuda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->id_estadodeuda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->id_estadodeuda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_codigo_deuda->Visible) { // mig_codigo_deuda ?>
	<?php if ($deudas->SortUrl($deudas->mig_codigo_deuda) == "") { ?>
		<th data-name="mig_codigo_deuda" class="<?php echo $deudas->mig_codigo_deuda->HeaderCellClass() ?>"><div id="elh_deudas_mig_codigo_deuda" class="deudas_mig_codigo_deuda"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_codigo_deuda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_codigo_deuda" class="<?php echo $deudas->mig_codigo_deuda->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->mig_codigo_deuda) ?>',1);"><div id="elh_deudas_mig_codigo_deuda" class="deudas_mig_codigo_deuda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_codigo_deuda->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_codigo_deuda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_codigo_deuda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_fecha_desembolso->Visible) { // mig_fecha_desembolso ?>
	<?php if ($deudas->SortUrl($deudas->mig_fecha_desembolso) == "") { ?>
		<th data-name="mig_fecha_desembolso" class="<?php echo $deudas->mig_fecha_desembolso->HeaderCellClass() ?>"><div id="elh_deudas_mig_fecha_desembolso" class="deudas_mig_fecha_desembolso"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_fecha_desembolso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_fecha_desembolso" class="<?php echo $deudas->mig_fecha_desembolso->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->mig_fecha_desembolso) ?>',1);"><div id="elh_deudas_mig_fecha_desembolso" class="deudas_mig_fecha_desembolso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_fecha_desembolso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_fecha_desembolso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_fecha_desembolso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_moneda->Visible) { // mig_moneda ?>
	<?php if ($deudas->SortUrl($deudas->mig_moneda) == "") { ?>
		<th data-name="mig_moneda" class="<?php echo $deudas->mig_moneda->HeaderCellClass() ?>"><div id="elh_deudas_mig_moneda" class="deudas_mig_moneda"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_moneda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_moneda" class="<?php echo $deudas->mig_moneda->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->mig_moneda) ?>',1);"><div id="elh_deudas_mig_moneda" class="deudas_mig_moneda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_moneda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_moneda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_moneda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_tasa->Visible) { // mig_tasa ?>
	<?php if ($deudas->SortUrl($deudas->mig_tasa) == "") { ?>
		<th data-name="mig_tasa" class="<?php echo $deudas->mig_tasa->HeaderCellClass() ?>"><div id="elh_deudas_mig_tasa" class="deudas_mig_tasa"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_tasa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_tasa" class="<?php echo $deudas->mig_tasa->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->mig_tasa) ?>',1);"><div id="elh_deudas_mig_tasa" class="deudas_mig_tasa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_tasa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_tasa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_tasa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_plazo->Visible) { // mig_plazo ?>
	<?php if ($deudas->SortUrl($deudas->mig_plazo) == "") { ?>
		<th data-name="mig_plazo" class="<?php echo $deudas->mig_plazo->HeaderCellClass() ?>"><div id="elh_deudas_mig_plazo" class="deudas_mig_plazo"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_plazo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_plazo" class="<?php echo $deudas->mig_plazo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->mig_plazo) ?>',1);"><div id="elh_deudas_mig_plazo" class="deudas_mig_plazo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_plazo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_plazo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_plazo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_dias_mora->Visible) { // mig_dias_mora ?>
	<?php if ($deudas->SortUrl($deudas->mig_dias_mora) == "") { ?>
		<th data-name="mig_dias_mora" class="<?php echo $deudas->mig_dias_mora->HeaderCellClass() ?>"><div id="elh_deudas_mig_dias_mora" class="deudas_mig_dias_mora"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_dias_mora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_dias_mora" class="<?php echo $deudas->mig_dias_mora->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->mig_dias_mora) ?>',1);"><div id="elh_deudas_mig_dias_mora" class="deudas_mig_dias_mora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_dias_mora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_dias_mora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_dias_mora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_monto_desembolso->Visible) { // mig_monto_desembolso ?>
	<?php if ($deudas->SortUrl($deudas->mig_monto_desembolso) == "") { ?>
		<th data-name="mig_monto_desembolso" class="<?php echo $deudas->mig_monto_desembolso->HeaderCellClass() ?>"><div id="elh_deudas_mig_monto_desembolso" class="deudas_mig_monto_desembolso"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_monto_desembolso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_monto_desembolso" class="<?php echo $deudas->mig_monto_desembolso->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->mig_monto_desembolso) ?>',1);"><div id="elh_deudas_mig_monto_desembolso" class="deudas_mig_monto_desembolso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_monto_desembolso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_monto_desembolso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_monto_desembolso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_intereses->Visible) { // mig_intereses ?>
	<?php if ($deudas->SortUrl($deudas->mig_intereses) == "") { ?>
		<th data-name="mig_intereses" class="<?php echo $deudas->mig_intereses->HeaderCellClass() ?>"><div id="elh_deudas_mig_intereses" class="deudas_mig_intereses"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_intereses->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_intereses" class="<?php echo $deudas->mig_intereses->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->mig_intereses) ?>',1);"><div id="elh_deudas_mig_intereses" class="deudas_mig_intereses">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_intereses->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_intereses->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_intereses->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_cargos_gastos->Visible) { // mig_cargos_gastos ?>
	<?php if ($deudas->SortUrl($deudas->mig_cargos_gastos) == "") { ?>
		<th data-name="mig_cargos_gastos" class="<?php echo $deudas->mig_cargos_gastos->HeaderCellClass() ?>"><div id="elh_deudas_mig_cargos_gastos" class="deudas_mig_cargos_gastos"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_cargos_gastos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_cargos_gastos" class="<?php echo $deudas->mig_cargos_gastos->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->mig_cargos_gastos) ?>',1);"><div id="elh_deudas_mig_cargos_gastos" class="deudas_mig_cargos_gastos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_cargos_gastos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_cargos_gastos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_cargos_gastos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($deudas->mig_total_deuda->Visible) { // mig_total_deuda ?>
	<?php if ($deudas->SortUrl($deudas->mig_total_deuda) == "") { ?>
		<th data-name="mig_total_deuda" class="<?php echo $deudas->mig_total_deuda->HeaderCellClass() ?>"><div id="elh_deudas_mig_total_deuda" class="deudas_mig_total_deuda"><div class="ewTableHeaderCaption"><?php echo $deudas->mig_total_deuda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="mig_total_deuda" class="<?php echo $deudas->mig_total_deuda->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $deudas->SortUrl($deudas->mig_total_deuda) ?>',1);"><div id="elh_deudas_mig_total_deuda" class="deudas_mig_total_deuda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $deudas->mig_total_deuda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($deudas->mig_total_deuda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($deudas->mig_total_deuda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$deudas_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($deudas->ExportAll && $deudas->Export <> "") {
	$deudas_list->StopRec = $deudas_list->TotalRecs;
} else {

	// Set the last record to display
	if ($deudas_list->TotalRecs > $deudas_list->StartRec + $deudas_list->DisplayRecs - 1)
		$deudas_list->StopRec = $deudas_list->StartRec + $deudas_list->DisplayRecs - 1;
	else
		$deudas_list->StopRec = $deudas_list->TotalRecs;
}
$deudas_list->RecCnt = $deudas_list->StartRec - 1;
if ($deudas_list->Recordset && !$deudas_list->Recordset->EOF) {
	$deudas_list->Recordset->MoveFirst();
	$bSelectLimit = $deudas_list->UseSelectLimit;
	if (!$bSelectLimit && $deudas_list->StartRec > 1)
		$deudas_list->Recordset->Move($deudas_list->StartRec - 1);
} elseif (!$deudas->AllowAddDeleteRow && $deudas_list->StopRec == 0) {
	$deudas_list->StopRec = $deudas->GridAddRowCount;
}

// Initialize aggregate
$deudas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$deudas->ResetAttrs();
$deudas_list->RenderRow();
while ($deudas_list->RecCnt < $deudas_list->StopRec) {
	$deudas_list->RecCnt++;
	if (intval($deudas_list->RecCnt) >= intval($deudas_list->StartRec)) {
		$deudas_list->RowCnt++;

		// Set up key count
		$deudas_list->KeyCount = $deudas_list->RowIndex;

		// Init row class and style
		$deudas->ResetAttrs();
		$deudas->CssClass = "";
		if ($deudas->CurrentAction == "gridadd") {
		} else {
			$deudas_list->LoadRowValues($deudas_list->Recordset); // Load row values
		}
		$deudas->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$deudas->RowAttrs = array_merge($deudas->RowAttrs, array('data-rowindex'=>$deudas_list->RowCnt, 'id'=>'r' . $deudas_list->RowCnt . '_deudas', 'data-rowtype'=>$deudas->RowType));

		// Render row
		$deudas_list->RenderRow();

		// Render list options
		$deudas_list->RenderListOptions();
?>
	<tr<?php echo $deudas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$deudas_list->ListOptions->Render("body", "left", $deudas_list->RowCnt);
?>
	<?php if ($deudas->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $deudas->Id->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_Id" class="deudas_Id">
<span<?php echo $deudas->Id->ViewAttributes() ?>>
<?php echo $deudas->Id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->id_cliente->Visible) { // id_cliente ?>
		<td data-name="id_cliente"<?php echo $deudas->id_cliente->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_id_cliente" class="deudas_id_cliente">
<span<?php echo $deudas->id_cliente->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deudas->id_cliente->ListViewValue())) && $deudas->id_cliente->LinkAttributes() <> "") { ?>
<a<?php echo $deudas->id_cliente->LinkAttributes() ?>><?php echo $deudas->id_cliente->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $deudas->id_cliente->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->id_ciudad->Visible) { // id_ciudad ?>
		<td data-name="id_ciudad"<?php echo $deudas->id_ciudad->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_id_ciudad" class="deudas_id_ciudad">
<span<?php echo $deudas->id_ciudad->ViewAttributes() ?>>
<?php echo $deudas->id_ciudad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->id_agente->Visible) { // id_agente ?>
		<td data-name="id_agente"<?php echo $deudas->id_agente->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_id_agente" class="deudas_id_agente">
<span<?php echo $deudas->id_agente->ViewAttributes() ?>>
<?php echo $deudas->id_agente->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->id_estadodeuda->Visible) { // id_estadodeuda ?>
		<td data-name="id_estadodeuda"<?php echo $deudas->id_estadodeuda->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_id_estadodeuda" class="deudas_id_estadodeuda">
<span<?php echo $deudas->id_estadodeuda->ViewAttributes() ?>>
<?php echo $deudas->id_estadodeuda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->mig_codigo_deuda->Visible) { // mig_codigo_deuda ?>
		<td data-name="mig_codigo_deuda"<?php echo $deudas->mig_codigo_deuda->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_mig_codigo_deuda" class="deudas_mig_codigo_deuda">
<span<?php echo $deudas->mig_codigo_deuda->ViewAttributes() ?>>
<?php echo $deudas->mig_codigo_deuda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->mig_fecha_desembolso->Visible) { // mig_fecha_desembolso ?>
		<td data-name="mig_fecha_desembolso"<?php echo $deudas->mig_fecha_desembolso->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_mig_fecha_desembolso" class="deudas_mig_fecha_desembolso">
<span<?php echo $deudas->mig_fecha_desembolso->ViewAttributes() ?>>
<?php echo $deudas->mig_fecha_desembolso->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->mig_moneda->Visible) { // mig_moneda ?>
		<td data-name="mig_moneda"<?php echo $deudas->mig_moneda->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_mig_moneda" class="deudas_mig_moneda">
<span<?php echo $deudas->mig_moneda->ViewAttributes() ?>>
<?php echo $deudas->mig_moneda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->mig_tasa->Visible) { // mig_tasa ?>
		<td data-name="mig_tasa"<?php echo $deudas->mig_tasa->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_mig_tasa" class="deudas_mig_tasa">
<span<?php echo $deudas->mig_tasa->ViewAttributes() ?>>
<?php echo $deudas->mig_tasa->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->mig_plazo->Visible) { // mig_plazo ?>
		<td data-name="mig_plazo"<?php echo $deudas->mig_plazo->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_mig_plazo" class="deudas_mig_plazo">
<span<?php echo $deudas->mig_plazo->ViewAttributes() ?>>
<?php echo $deudas->mig_plazo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->mig_dias_mora->Visible) { // mig_dias_mora ?>
		<td data-name="mig_dias_mora"<?php echo $deudas->mig_dias_mora->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_mig_dias_mora" class="deudas_mig_dias_mora">
<span<?php echo $deudas->mig_dias_mora->ViewAttributes() ?>>
<?php echo $deudas->mig_dias_mora->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->mig_monto_desembolso->Visible) { // mig_monto_desembolso ?>
		<td data-name="mig_monto_desembolso"<?php echo $deudas->mig_monto_desembolso->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_mig_monto_desembolso" class="deudas_mig_monto_desembolso">
<span<?php echo $deudas->mig_monto_desembolso->ViewAttributes() ?>>
<?php echo $deudas->mig_monto_desembolso->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->mig_intereses->Visible) { // mig_intereses ?>
		<td data-name="mig_intereses"<?php echo $deudas->mig_intereses->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_mig_intereses" class="deudas_mig_intereses">
<span<?php echo $deudas->mig_intereses->ViewAttributes() ?>>
<?php echo $deudas->mig_intereses->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->mig_cargos_gastos->Visible) { // mig_cargos_gastos ?>
		<td data-name="mig_cargos_gastos"<?php echo $deudas->mig_cargos_gastos->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_mig_cargos_gastos" class="deudas_mig_cargos_gastos">
<span<?php echo $deudas->mig_cargos_gastos->ViewAttributes() ?>>
<?php echo $deudas->mig_cargos_gastos->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($deudas->mig_total_deuda->Visible) { // mig_total_deuda ?>
		<td data-name="mig_total_deuda"<?php echo $deudas->mig_total_deuda->CellAttributes() ?>>
<span id="el<?php echo $deudas_list->RowCnt ?>_deudas_mig_total_deuda" class="deudas_mig_total_deuda">
<span<?php echo $deudas->mig_total_deuda->ViewAttributes() ?>>
<?php echo $deudas->mig_total_deuda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$deudas_list->ListOptions->Render("body", "right", $deudas_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($deudas->CurrentAction <> "gridadd")
		$deudas_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($deudas->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($deudas_list->Recordset)
	$deudas_list->Recordset->Close();
?>
<?php if ($deudas->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($deudas->CurrentAction <> "gridadd" && $deudas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($deudas_list->Pager)) $deudas_list->Pager = new cPrevNextPager($deudas_list->StartRec, $deudas_list->DisplayRecs, $deudas_list->TotalRecs, $deudas_list->AutoHidePager) ?>
<?php if ($deudas_list->Pager->RecordCount > 0 && $deudas_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($deudas_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $deudas_list->PageUrl() ?>start=<?php echo $deudas_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($deudas_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $deudas_list->PageUrl() ?>start=<?php echo $deudas_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $deudas_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($deudas_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $deudas_list->PageUrl() ?>start=<?php echo $deudas_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($deudas_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $deudas_list->PageUrl() ?>start=<?php echo $deudas_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $deudas_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $deudas_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $deudas_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $deudas_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($deudas_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($deudas_list->TotalRecs == 0 && $deudas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($deudas_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($deudas->Export == "") { ?>
<script type="text/javascript">
fdeudaslistsrch.FilterList = <?php echo $deudas_list->GetFilterList() ?>;
fdeudaslistsrch.Init();
fdeudaslist.Init();
</script>
<?php } ?>
<?php
$deudas_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($deudas->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$('.ewDetailAdd').hide();
$('.ewDetailEdit').hide();
$('.ewDetailCopy').hide();
</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$deudas_list->Page_Terminate();
?>
