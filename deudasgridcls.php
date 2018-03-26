<?php include_once "deudasinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php

//
// Page class
//

$deudas_grid = NULL; // Initialize page object first

class cdeudas_grid extends cdeudas {

	// Page ID
	var $PageID = 'gridcls';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'deudas';

	// Page object name
	var $PageObjName = 'deudas_grid';

	// Grid form hidden field names
	var $FormName = 'fdeudasgrid';
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
		$this->FormActionName .= '_' . $this->FormName;
		$this->FormKeyName .= '_' . $this->FormName;
		$this->FormOldKeyName .= '_' . $this->FormName;
		$this->FormBlankRowName .= '_' . $this->FormName;
		$this->FormKeyCountName .= '_' . $this->FormName;
		$GLOBALS["Grid"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (deudas)
		if (!isset($GLOBALS["deudas"]) || get_class($GLOBALS["deudas"]) == "cdeudas") {
			$GLOBALS["deudas"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["deudas"];

		}
		$this->AddUrl = "deudasadd.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'gridcls', TRUE);

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

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
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
		// Get grid add count

		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

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

//		$GLOBALS["Table"] = &$GLOBALS["MasterTable"];
		unset($GLOBALS["Grid"]);
		if ($url == "")
			return;
		$this->Page_Redirecting($url);

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
	var $ShowOtherOptions = FALSE;
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

			// Handle reset command
			$this->ResetCmd();

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

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Set up sorting order
			$this->SetupSortOrder();
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
	}

	// Exit inline mode
	function ClearInlineMode() {
		$this->mig_tasa->FormValue = ""; // Clear form value
		$this->mig_plazo->FormValue = ""; // Clear form value
		$this->mig_dias_mora->FormValue = ""; // Clear form value
		$this->mig_monto_desembolso->FormValue = ""; // Clear form value
		$this->mig_intereses->FormValue = ""; // Clear form value
		$this->mig_cargos_gastos->FormValue = ""; // Clear form value
		$this->mig_total_deuda->FormValue = ""; // Clear form value
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Perform update to grid
	function GridUpdate() {
		global $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
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

	// Perform Grid Add
	function GridInsert() {
		global $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;
		$conn = &$this->Connection();

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			if ($rowaction == "insert") {
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
				$this->LoadOldRecord(); // Load old record
			}
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->Id->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->ClearInlineMode(); // Clear grid add mode and return
			return TRUE;
		}
		if ($bGridInsert) {

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_id_cliente") && $objForm->HasValue("o_id_cliente") && $this->id_cliente->CurrentValue <> $this->id_cliente->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_id_ciudad") && $objForm->HasValue("o_id_ciudad") && $this->id_ciudad->CurrentValue <> $this->id_ciudad->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_id_agente") && $objForm->HasValue("o_id_agente") && $this->id_agente->CurrentValue <> $this->id_agente->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_id_estadodeuda") && $objForm->HasValue("o_id_estadodeuda") && $this->id_estadodeuda->CurrentValue <> $this->id_estadodeuda->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_mig_codigo_deuda") && $objForm->HasValue("o_mig_codigo_deuda") && $this->mig_codigo_deuda->CurrentValue <> $this->mig_codigo_deuda->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_mig_fecha_desembolso") && $objForm->HasValue("o_mig_fecha_desembolso") && $this->mig_fecha_desembolso->CurrentValue <> $this->mig_fecha_desembolso->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_mig_moneda") && $objForm->HasValue("o_mig_moneda") && $this->mig_moneda->CurrentValue <> $this->mig_moneda->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_mig_tasa") && $objForm->HasValue("o_mig_tasa") && $this->mig_tasa->CurrentValue <> $this->mig_tasa->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_mig_plazo") && $objForm->HasValue("o_mig_plazo") && $this->mig_plazo->CurrentValue <> $this->mig_plazo->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_mig_dias_mora") && $objForm->HasValue("o_mig_dias_mora") && $this->mig_dias_mora->CurrentValue <> $this->mig_dias_mora->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_mig_monto_desembolso") && $objForm->HasValue("o_mig_monto_desembolso") && $this->mig_monto_desembolso->CurrentValue <> $this->mig_monto_desembolso->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_mig_intereses") && $objForm->HasValue("o_mig_intereses") && $this->mig_intereses->CurrentValue <> $this->mig_intereses->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_mig_cargos_gastos") && $objForm->HasValue("o_mig_cargos_gastos") && $this->mig_cargos_gastos->CurrentValue <> $this->mig_cargos_gastos->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_mig_total_deuda") && $objForm->HasValue("o_mig_total_deuda") && $this->mig_total_deuda->CurrentValue <> $this->mig_total_deuda->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
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
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssClass = "text-nowrap";
			$item->OnLeft = TRUE;
			$item->Visible = FALSE; // Default hidden
		}

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

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($objForm->HasValue($this->FormOldKeyName))
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
			if ($this->RowOldKey <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $OldKeyName . "\" id=\"" . $OldKeyName . "\" value=\"" . ew_HtmlEncode($this->RowOldKey) . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}
		if ($this->CurrentMode == "view") { // View mode

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
		} // End View mode
		if ($this->CurrentMode == "edit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->Id->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('Id');
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$option = &$this->OtherOptions["addedit"];
		$option->UseDropDownButton = FALSE;
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$option->UseButtonGroup = TRUE;
		$option->ButtonClass = "btn-sm"; // Class for button group
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Add
		if ($this->CurrentMode == "view") { // Check view mode
			$item = &$option->Add("add");
			$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
			if (ew_IsMobile())
				$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
			else
				$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-table=\"deudas\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("AddLink") . "</a>";
			$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		}
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if (($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") && $this->CurrentAction != "F") { // Check add/copy/edit mode
			if ($this->AllowAddDeleteRow) {
				$option = &$options["addedit"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
				$item = &$option->Add("addblankrow");
				$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
				$item->Visible = $Security->CanAdd();
				$this->ShowOtherOptions = $item->Visible;
			}
		}
		if ($this->CurrentMode == "view") { // Check view mode
			$option = &$options["addedit"];
			$item = &$option->GetItem("add");
			$this->ShowOtherOptions = $item && $item->Visible;
		}
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->Id->CurrentValue = NULL;
		$this->Id->OldValue = $this->Id->CurrentValue;
		$this->cuenta->CurrentValue = NULL;
		$this->cuenta->OldValue = $this->cuenta->CurrentValue;
		$this->id_cliente->CurrentValue = 0;
		$this->id_cliente->OldValue = $this->id_cliente->CurrentValue;
		$this->id_ciudad->CurrentValue = 0;
		$this->id_ciudad->OldValue = $this->id_ciudad->CurrentValue;
		$this->id_agente->CurrentValue = 0;
		$this->id_agente->OldValue = $this->id_agente->CurrentValue;
		$this->id_estadodeuda->CurrentValue = 0;
		$this->id_estadodeuda->OldValue = $this->id_estadodeuda->CurrentValue;
		$this->mig_codigo_deuda->CurrentValue = NULL;
		$this->mig_codigo_deuda->OldValue = $this->mig_codigo_deuda->CurrentValue;
		$this->mig_tipo_operacion->CurrentValue = NULL;
		$this->mig_tipo_operacion->OldValue = $this->mig_tipo_operacion->CurrentValue;
		$this->mig_fecha_desembolso->CurrentValue = NULL;
		$this->mig_fecha_desembolso->OldValue = $this->mig_fecha_desembolso->CurrentValue;
		$this->mig_fecha_estado->CurrentValue = NULL;
		$this->mig_fecha_estado->OldValue = $this->mig_fecha_estado->CurrentValue;
		$this->mig_anios_castigo->CurrentValue = NULL;
		$this->mig_anios_castigo->OldValue = $this->mig_anios_castigo->CurrentValue;
		$this->mig_tipo_garantia->CurrentValue = NULL;
		$this->mig_tipo_garantia->OldValue = $this->mig_tipo_garantia->CurrentValue;
		$this->mig_real->CurrentValue = NULL;
		$this->mig_real->OldValue = $this->mig_real->CurrentValue;
		$this->mig_actividad_economica->CurrentValue = NULL;
		$this->mig_actividad_economica->OldValue = $this->mig_actividad_economica->CurrentValue;
		$this->mig_agencia->CurrentValue = NULL;
		$this->mig_agencia->OldValue = $this->mig_agencia->CurrentValue;
		$this->mig_no_juicio->CurrentValue = NULL;
		$this->mig_no_juicio->OldValue = $this->mig_no_juicio->CurrentValue;
		$this->mig_nombre_abogado->CurrentValue = NULL;
		$this->mig_nombre_abogado->OldValue = $this->mig_nombre_abogado->CurrentValue;
		$this->mig_fase_procesal->CurrentValue = NULL;
		$this->mig_fase_procesal->OldValue = $this->mig_fase_procesal->CurrentValue;
		$this->mig_moneda->CurrentValue = NULL;
		$this->mig_moneda->OldValue = $this->mig_moneda->CurrentValue;
		$this->mig_tasa->CurrentValue = NULL;
		$this->mig_tasa->OldValue = $this->mig_tasa->CurrentValue;
		$this->mig_plazo->CurrentValue = NULL;
		$this->mig_plazo->OldValue = $this->mig_plazo->CurrentValue;
		$this->mig_dias_mora->CurrentValue = NULL;
		$this->mig_dias_mora->OldValue = $this->mig_dias_mora->CurrentValue;
		$this->mig_monto_desembolso->CurrentValue = NULL;
		$this->mig_monto_desembolso->OldValue = $this->mig_monto_desembolso->CurrentValue;
		$this->mig_total_cartera->CurrentValue = NULL;
		$this->mig_total_cartera->OldValue = $this->mig_total_cartera->CurrentValue;
		$this->mig_capital->CurrentValue = NULL;
		$this->mig_capital->OldValue = $this->mig_capital->CurrentValue;
		$this->mig_intereses->CurrentValue = NULL;
		$this->mig_intereses->OldValue = $this->mig_intereses->CurrentValue;
		$this->mig_cargos_gastos->CurrentValue = NULL;
		$this->mig_cargos_gastos->OldValue = $this->mig_cargos_gastos->CurrentValue;
		$this->mig_total_deuda->CurrentValue = NULL;
		$this->mig_total_deuda->OldValue = $this->mig_total_deuda->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$objForm->FormName = $this->FormName;
		if (!$this->Id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->Id->setFormValue($objForm->GetValue("x_Id"));
		if (!$this->id_cliente->FldIsDetailKey) {
			$this->id_cliente->setFormValue($objForm->GetValue("x_id_cliente"));
		}
		$this->id_cliente->setOldValue($objForm->GetValue("o_id_cliente"));
		if (!$this->id_ciudad->FldIsDetailKey) {
			$this->id_ciudad->setFormValue($objForm->GetValue("x_id_ciudad"));
		}
		$this->id_ciudad->setOldValue($objForm->GetValue("o_id_ciudad"));
		if (!$this->id_agente->FldIsDetailKey) {
			$this->id_agente->setFormValue($objForm->GetValue("x_id_agente"));
		}
		$this->id_agente->setOldValue($objForm->GetValue("o_id_agente"));
		if (!$this->id_estadodeuda->FldIsDetailKey) {
			$this->id_estadodeuda->setFormValue($objForm->GetValue("x_id_estadodeuda"));
		}
		$this->id_estadodeuda->setOldValue($objForm->GetValue("o_id_estadodeuda"));
		if (!$this->mig_codigo_deuda->FldIsDetailKey) {
			$this->mig_codigo_deuda->setFormValue($objForm->GetValue("x_mig_codigo_deuda"));
		}
		$this->mig_codigo_deuda->setOldValue($objForm->GetValue("o_mig_codigo_deuda"));
		if (!$this->mig_fecha_desembolso->FldIsDetailKey) {
			$this->mig_fecha_desembolso->setFormValue($objForm->GetValue("x_mig_fecha_desembolso"));
			$this->mig_fecha_desembolso->CurrentValue = ew_UnFormatDateTime($this->mig_fecha_desembolso->CurrentValue, 7);
		}
		$this->mig_fecha_desembolso->setOldValue($objForm->GetValue("o_mig_fecha_desembolso"));
		if (!$this->mig_moneda->FldIsDetailKey) {
			$this->mig_moneda->setFormValue($objForm->GetValue("x_mig_moneda"));
		}
		$this->mig_moneda->setOldValue($objForm->GetValue("o_mig_moneda"));
		if (!$this->mig_tasa->FldIsDetailKey) {
			$this->mig_tasa->setFormValue($objForm->GetValue("x_mig_tasa"));
		}
		$this->mig_tasa->setOldValue($objForm->GetValue("o_mig_tasa"));
		if (!$this->mig_plazo->FldIsDetailKey) {
			$this->mig_plazo->setFormValue($objForm->GetValue("x_mig_plazo"));
		}
		$this->mig_plazo->setOldValue($objForm->GetValue("o_mig_plazo"));
		if (!$this->mig_dias_mora->FldIsDetailKey) {
			$this->mig_dias_mora->setFormValue($objForm->GetValue("x_mig_dias_mora"));
		}
		$this->mig_dias_mora->setOldValue($objForm->GetValue("o_mig_dias_mora"));
		if (!$this->mig_monto_desembolso->FldIsDetailKey) {
			$this->mig_monto_desembolso->setFormValue($objForm->GetValue("x_mig_monto_desembolso"));
		}
		$this->mig_monto_desembolso->setOldValue($objForm->GetValue("o_mig_monto_desembolso"));
		if (!$this->mig_intereses->FldIsDetailKey) {
			$this->mig_intereses->setFormValue($objForm->GetValue("x_mig_intereses"));
		}
		$this->mig_intereses->setOldValue($objForm->GetValue("o_mig_intereses"));
		if (!$this->mig_cargos_gastos->FldIsDetailKey) {
			$this->mig_cargos_gastos->setFormValue($objForm->GetValue("x_mig_cargos_gastos"));
		}
		$this->mig_cargos_gastos->setOldValue($objForm->GetValue("o_mig_cargos_gastos"));
		if (!$this->mig_total_deuda->FldIsDetailKey) {
			$this->mig_total_deuda->setFormValue($objForm->GetValue("x_mig_total_deuda"));
		}
		$this->mig_total_deuda->setOldValue($objForm->GetValue("o_mig_total_deuda"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->Id->CurrentValue = $this->Id->FormValue;
		$this->id_cliente->CurrentValue = $this->id_cliente->FormValue;
		$this->id_ciudad->CurrentValue = $this->id_ciudad->FormValue;
		$this->id_agente->CurrentValue = $this->id_agente->FormValue;
		$this->id_estadodeuda->CurrentValue = $this->id_estadodeuda->FormValue;
		$this->mig_codigo_deuda->CurrentValue = $this->mig_codigo_deuda->FormValue;
		$this->mig_fecha_desembolso->CurrentValue = $this->mig_fecha_desembolso->FormValue;
		$this->mig_fecha_desembolso->CurrentValue = ew_UnFormatDateTime($this->mig_fecha_desembolso->CurrentValue, 7);
		$this->mig_moneda->CurrentValue = $this->mig_moneda->FormValue;
		$this->mig_tasa->CurrentValue = $this->mig_tasa->FormValue;
		$this->mig_plazo->CurrentValue = $this->mig_plazo->FormValue;
		$this->mig_dias_mora->CurrentValue = $this->mig_dias_mora->FormValue;
		$this->mig_monto_desembolso->CurrentValue = $this->mig_monto_desembolso->FormValue;
		$this->mig_intereses->CurrentValue = $this->mig_intereses->FormValue;
		$this->mig_cargos_gastos->CurrentValue = $this->mig_cargos_gastos->FormValue;
		$this->mig_total_deuda->CurrentValue = $this->mig_total_deuda->FormValue;
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
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['Id'] = $this->Id->CurrentValue;
		$row['cuenta'] = $this->cuenta->CurrentValue;
		$row['id_cliente'] = $this->id_cliente->CurrentValue;
		$row['id_ciudad'] = $this->id_ciudad->CurrentValue;
		$row['id_agente'] = $this->id_agente->CurrentValue;
		$row['id_estadodeuda'] = $this->id_estadodeuda->CurrentValue;
		$row['mig_codigo_deuda'] = $this->mig_codigo_deuda->CurrentValue;
		$row['mig_tipo_operacion'] = $this->mig_tipo_operacion->CurrentValue;
		$row['mig_fecha_desembolso'] = $this->mig_fecha_desembolso->CurrentValue;
		$row['mig_fecha_estado'] = $this->mig_fecha_estado->CurrentValue;
		$row['mig_anios_castigo'] = $this->mig_anios_castigo->CurrentValue;
		$row['mig_tipo_garantia'] = $this->mig_tipo_garantia->CurrentValue;
		$row['mig_real'] = $this->mig_real->CurrentValue;
		$row['mig_actividad_economica'] = $this->mig_actividad_economica->CurrentValue;
		$row['mig_agencia'] = $this->mig_agencia->CurrentValue;
		$row['mig_no_juicio'] = $this->mig_no_juicio->CurrentValue;
		$row['mig_nombre_abogado'] = $this->mig_nombre_abogado->CurrentValue;
		$row['mig_fase_procesal'] = $this->mig_fase_procesal->CurrentValue;
		$row['mig_moneda'] = $this->mig_moneda->CurrentValue;
		$row['mig_tasa'] = $this->mig_tasa->CurrentValue;
		$row['mig_plazo'] = $this->mig_plazo->CurrentValue;
		$row['mig_dias_mora'] = $this->mig_dias_mora->CurrentValue;
		$row['mig_monto_desembolso'] = $this->mig_monto_desembolso->CurrentValue;
		$row['mig_total_cartera'] = $this->mig_total_cartera->CurrentValue;
		$row['mig_capital'] = $this->mig_capital->CurrentValue;
		$row['mig_intereses'] = $this->mig_intereses->CurrentValue;
		$row['mig_cargos_gastos'] = $this->mig_cargos_gastos->CurrentValue;
		$row['mig_total_deuda'] = $this->mig_total_deuda->CurrentValue;
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
		$arKeys[] = $this->RowOldKey;
		$cnt = count($arKeys);
		if ($cnt >= 1) {
			if (strval($arKeys[0]) <> "")
				$this->Id->CurrentValue = strval($arKeys[0]); // Id
			else
				$bValidKey = FALSE;
		} else {
			$bValidKey = FALSE;
		}

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
		$this->CopyUrl = $this->GetCopyUrl();
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Id
			// id_cliente

			$this->id_cliente->EditAttrs["class"] = "form-control";
			$this->id_cliente->EditCustomAttributes = "";
			if ($this->id_cliente->getSessionValue() <> "") {
				$this->id_cliente->CurrentValue = $this->id_cliente->getSessionValue();
				$this->id_cliente->OldValue = $this->id_cliente->CurrentValue;
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
			} else {
			if (trim(strval($this->id_cliente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_cliente->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			}

			// id_ciudad
			$this->id_ciudad->EditAttrs["class"] = "form-control";
			$this->id_ciudad->EditCustomAttributes = "";
			if (trim(strval($this->id_ciudad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_ciudad->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			if ($this->id_agente->getSessionValue() <> "") {
				$this->id_agente->CurrentValue = $this->id_agente->getSessionValue();
				$this->id_agente->OldValue = $this->id_agente->CurrentValue;
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
			} else {
			if (trim(strval($this->id_agente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_user`" . ew_SearchString("=", $this->id_agente->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			}

			// id_estadodeuda
			$this->id_estadodeuda->EditAttrs["class"] = "form-control";
			$this->id_estadodeuda->EditCustomAttributes = "";
			if (trim(strval($this->id_estadodeuda->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_estadodeuda->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			$this->mig_codigo_deuda->EditValue = ew_HtmlEncode($this->mig_codigo_deuda->CurrentValue);
			$this->mig_codigo_deuda->PlaceHolder = ew_RemoveHtml($this->mig_codigo_deuda->FldCaption());

			// mig_fecha_desembolso
			$this->mig_fecha_desembolso->EditAttrs["class"] = "form-control";
			$this->mig_fecha_desembolso->EditCustomAttributes = "";
			$this->mig_fecha_desembolso->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->mig_fecha_desembolso->CurrentValue, 7));
			$this->mig_fecha_desembolso->PlaceHolder = ew_RemoveHtml($this->mig_fecha_desembolso->FldCaption());

			// mig_moneda
			$this->mig_moneda->EditAttrs["class"] = "form-control";
			$this->mig_moneda->EditCustomAttributes = "";
			$this->mig_moneda->EditValue = $this->mig_moneda->Options(TRUE);

			// mig_tasa
			$this->mig_tasa->EditAttrs["class"] = "form-control";
			$this->mig_tasa->EditCustomAttributes = "";
			$this->mig_tasa->EditValue = ew_HtmlEncode($this->mig_tasa->CurrentValue);
			$this->mig_tasa->PlaceHolder = ew_RemoveHtml($this->mig_tasa->FldCaption());
			if (strval($this->mig_tasa->EditValue) <> "" && is_numeric($this->mig_tasa->EditValue)) {
			$this->mig_tasa->EditValue = ew_FormatNumber($this->mig_tasa->EditValue, -2, 0, -2, 0);
			$this->mig_tasa->OldValue = $this->mig_tasa->EditValue;
			}

			// mig_plazo
			$this->mig_plazo->EditAttrs["class"] = "form-control";
			$this->mig_plazo->EditCustomAttributes = "";
			$this->mig_plazo->EditValue = ew_HtmlEncode($this->mig_plazo->CurrentValue);
			$this->mig_plazo->PlaceHolder = ew_RemoveHtml($this->mig_plazo->FldCaption());
			if (strval($this->mig_plazo->EditValue) <> "" && is_numeric($this->mig_plazo->EditValue)) {
			$this->mig_plazo->EditValue = ew_FormatNumber($this->mig_plazo->EditValue, -2, 0, -2, 0);
			$this->mig_plazo->OldValue = $this->mig_plazo->EditValue;
			}

			// mig_dias_mora
			$this->mig_dias_mora->EditAttrs["class"] = "form-control";
			$this->mig_dias_mora->EditCustomAttributes = "";
			$this->mig_dias_mora->EditValue = ew_HtmlEncode($this->mig_dias_mora->CurrentValue);
			$this->mig_dias_mora->PlaceHolder = ew_RemoveHtml($this->mig_dias_mora->FldCaption());
			if (strval($this->mig_dias_mora->EditValue) <> "" && is_numeric($this->mig_dias_mora->EditValue)) {
			$this->mig_dias_mora->EditValue = ew_FormatNumber($this->mig_dias_mora->EditValue, -2, 0, -2, 0);
			$this->mig_dias_mora->OldValue = $this->mig_dias_mora->EditValue;
			}

			// mig_monto_desembolso
			$this->mig_monto_desembolso->EditAttrs["class"] = "form-control";
			$this->mig_monto_desembolso->EditCustomAttributes = "";
			$this->mig_monto_desembolso->EditValue = ew_HtmlEncode($this->mig_monto_desembolso->CurrentValue);
			$this->mig_monto_desembolso->PlaceHolder = ew_RemoveHtml($this->mig_monto_desembolso->FldCaption());
			if (strval($this->mig_monto_desembolso->EditValue) <> "" && is_numeric($this->mig_monto_desembolso->EditValue)) {
			$this->mig_monto_desembolso->EditValue = ew_FormatNumber($this->mig_monto_desembolso->EditValue, -2, 0, -2, 0);
			$this->mig_monto_desembolso->OldValue = $this->mig_monto_desembolso->EditValue;
			}

			// mig_intereses
			$this->mig_intereses->EditAttrs["class"] = "form-control";
			$this->mig_intereses->EditCustomAttributes = "";
			$this->mig_intereses->EditValue = ew_HtmlEncode($this->mig_intereses->CurrentValue);
			$this->mig_intereses->PlaceHolder = ew_RemoveHtml($this->mig_intereses->FldCaption());
			if (strval($this->mig_intereses->EditValue) <> "" && is_numeric($this->mig_intereses->EditValue)) {
			$this->mig_intereses->EditValue = ew_FormatNumber($this->mig_intereses->EditValue, -2, 0, -2, 0);
			$this->mig_intereses->OldValue = $this->mig_intereses->EditValue;
			}

			// mig_cargos_gastos
			$this->mig_cargos_gastos->EditAttrs["class"] = "form-control";
			$this->mig_cargos_gastos->EditCustomAttributes = "";
			$this->mig_cargos_gastos->EditValue = ew_HtmlEncode($this->mig_cargos_gastos->CurrentValue);
			$this->mig_cargos_gastos->PlaceHolder = ew_RemoveHtml($this->mig_cargos_gastos->FldCaption());
			if (strval($this->mig_cargos_gastos->EditValue) <> "" && is_numeric($this->mig_cargos_gastos->EditValue)) {
			$this->mig_cargos_gastos->EditValue = ew_FormatNumber($this->mig_cargos_gastos->EditValue, -2, 0, -2, 0);
			$this->mig_cargos_gastos->OldValue = $this->mig_cargos_gastos->EditValue;
			}

			// mig_total_deuda
			$this->mig_total_deuda->EditAttrs["class"] = "form-control";
			$this->mig_total_deuda->EditCustomAttributes = "";
			$this->mig_total_deuda->EditValue = ew_HtmlEncode($this->mig_total_deuda->CurrentValue);
			$this->mig_total_deuda->PlaceHolder = ew_RemoveHtml($this->mig_total_deuda->FldCaption());
			if (strval($this->mig_total_deuda->EditValue) <> "" && is_numeric($this->mig_total_deuda->EditValue)) {
			$this->mig_total_deuda->EditValue = ew_FormatNumber($this->mig_total_deuda->EditValue, -2, 0, -2, 0);
			$this->mig_total_deuda->OldValue = $this->mig_total_deuda->EditValue;
			}

			// Add refer script
			// Id

			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";

			// id_cliente
			$this->id_cliente->LinkCustomAttributes = "";
			if (!ew_Empty($this->id_cliente->CurrentValue)) {
				$this->id_cliente->HrefValue = $this->id_cliente->CurrentValue; // Add prefix/suffix
				$this->id_cliente->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->id_cliente->HrefValue = ew_FullUrl($this->id_cliente->HrefValue, "href");
			} else {
				$this->id_cliente->HrefValue = "";
			}

			// id_ciudad
			$this->id_ciudad->LinkCustomAttributes = "";
			$this->id_ciudad->HrefValue = "";

			// id_agente
			$this->id_agente->LinkCustomAttributes = "";
			$this->id_agente->HrefValue = "";

			// id_estadodeuda
			$this->id_estadodeuda->LinkCustomAttributes = "";
			$this->id_estadodeuda->HrefValue = "";

			// mig_codigo_deuda
			$this->mig_codigo_deuda->LinkCustomAttributes = "";
			$this->mig_codigo_deuda->HrefValue = "";

			// mig_fecha_desembolso
			$this->mig_fecha_desembolso->LinkCustomAttributes = "";
			$this->mig_fecha_desembolso->HrefValue = "";

			// mig_moneda
			$this->mig_moneda->LinkCustomAttributes = "";
			$this->mig_moneda->HrefValue = "";

			// mig_tasa
			$this->mig_tasa->LinkCustomAttributes = "";
			$this->mig_tasa->HrefValue = "";

			// mig_plazo
			$this->mig_plazo->LinkCustomAttributes = "";
			$this->mig_plazo->HrefValue = "";

			// mig_dias_mora
			$this->mig_dias_mora->LinkCustomAttributes = "";
			$this->mig_dias_mora->HrefValue = "";

			// mig_monto_desembolso
			$this->mig_monto_desembolso->LinkCustomAttributes = "";
			$this->mig_monto_desembolso->HrefValue = "";

			// mig_intereses
			$this->mig_intereses->LinkCustomAttributes = "";
			$this->mig_intereses->HrefValue = "";

			// mig_cargos_gastos
			$this->mig_cargos_gastos->LinkCustomAttributes = "";
			$this->mig_cargos_gastos->HrefValue = "";

			// mig_total_deuda
			$this->mig_total_deuda->LinkCustomAttributes = "";
			$this->mig_total_deuda->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			$this->Id->EditValue = $this->Id->CurrentValue;
			$this->Id->ViewCustomAttributes = "";

			// id_cliente
			$this->id_cliente->EditAttrs["class"] = "form-control";
			$this->id_cliente->EditCustomAttributes = "";
			if ($this->id_cliente->getSessionValue() <> "") {
				$this->id_cliente->CurrentValue = $this->id_cliente->getSessionValue();
				$this->id_cliente->OldValue = $this->id_cliente->CurrentValue;
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
			} else {
			if (trim(strval($this->id_cliente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_cliente->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			}

			// id_ciudad
			$this->id_ciudad->EditAttrs["class"] = "form-control";
			$this->id_ciudad->EditCustomAttributes = "";
			if (trim(strval($this->id_ciudad->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_ciudad->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			if ($this->id_agente->getSessionValue() <> "") {
				$this->id_agente->CurrentValue = $this->id_agente->getSessionValue();
				$this->id_agente->OldValue = $this->id_agente->CurrentValue;
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
			} else {
			if (trim(strval($this->id_agente->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_user`" . ew_SearchString("=", $this->id_agente->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			}

			// id_estadodeuda
			$this->id_estadodeuda->EditAttrs["class"] = "form-control";
			$this->id_estadodeuda->EditCustomAttributes = "";
			if (trim(strval($this->id_estadodeuda->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Id`" . ew_SearchString("=", $this->id_estadodeuda->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			$this->mig_codigo_deuda->EditValue = ew_HtmlEncode($this->mig_codigo_deuda->CurrentValue);
			$this->mig_codigo_deuda->PlaceHolder = ew_RemoveHtml($this->mig_codigo_deuda->FldCaption());

			// mig_fecha_desembolso
			$this->mig_fecha_desembolso->EditAttrs["class"] = "form-control";
			$this->mig_fecha_desembolso->EditCustomAttributes = "";
			$this->mig_fecha_desembolso->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->mig_fecha_desembolso->CurrentValue, 7));
			$this->mig_fecha_desembolso->PlaceHolder = ew_RemoveHtml($this->mig_fecha_desembolso->FldCaption());

			// mig_moneda
			$this->mig_moneda->EditAttrs["class"] = "form-control";
			$this->mig_moneda->EditCustomAttributes = "";
			$this->mig_moneda->EditValue = $this->mig_moneda->Options(TRUE);

			// mig_tasa
			$this->mig_tasa->EditAttrs["class"] = "form-control";
			$this->mig_tasa->EditCustomAttributes = "";
			$this->mig_tasa->EditValue = ew_HtmlEncode($this->mig_tasa->CurrentValue);
			$this->mig_tasa->PlaceHolder = ew_RemoveHtml($this->mig_tasa->FldCaption());
			if (strval($this->mig_tasa->EditValue) <> "" && is_numeric($this->mig_tasa->EditValue)) {
			$this->mig_tasa->EditValue = ew_FormatNumber($this->mig_tasa->EditValue, -2, 0, -2, 0);
			$this->mig_tasa->OldValue = $this->mig_tasa->EditValue;
			}

			// mig_plazo
			$this->mig_plazo->EditAttrs["class"] = "form-control";
			$this->mig_plazo->EditCustomAttributes = "";
			$this->mig_plazo->EditValue = ew_HtmlEncode($this->mig_plazo->CurrentValue);
			$this->mig_plazo->PlaceHolder = ew_RemoveHtml($this->mig_plazo->FldCaption());
			if (strval($this->mig_plazo->EditValue) <> "" && is_numeric($this->mig_plazo->EditValue)) {
			$this->mig_plazo->EditValue = ew_FormatNumber($this->mig_plazo->EditValue, -2, 0, -2, 0);
			$this->mig_plazo->OldValue = $this->mig_plazo->EditValue;
			}

			// mig_dias_mora
			$this->mig_dias_mora->EditAttrs["class"] = "form-control";
			$this->mig_dias_mora->EditCustomAttributes = "";
			$this->mig_dias_mora->EditValue = ew_HtmlEncode($this->mig_dias_mora->CurrentValue);
			$this->mig_dias_mora->PlaceHolder = ew_RemoveHtml($this->mig_dias_mora->FldCaption());
			if (strval($this->mig_dias_mora->EditValue) <> "" && is_numeric($this->mig_dias_mora->EditValue)) {
			$this->mig_dias_mora->EditValue = ew_FormatNumber($this->mig_dias_mora->EditValue, -2, 0, -2, 0);
			$this->mig_dias_mora->OldValue = $this->mig_dias_mora->EditValue;
			}

			// mig_monto_desembolso
			$this->mig_monto_desembolso->EditAttrs["class"] = "form-control";
			$this->mig_monto_desembolso->EditCustomAttributes = "";
			$this->mig_monto_desembolso->EditValue = ew_HtmlEncode($this->mig_monto_desembolso->CurrentValue);
			$this->mig_monto_desembolso->PlaceHolder = ew_RemoveHtml($this->mig_monto_desembolso->FldCaption());
			if (strval($this->mig_monto_desembolso->EditValue) <> "" && is_numeric($this->mig_monto_desembolso->EditValue)) {
			$this->mig_monto_desembolso->EditValue = ew_FormatNumber($this->mig_monto_desembolso->EditValue, -2, 0, -2, 0);
			$this->mig_monto_desembolso->OldValue = $this->mig_monto_desembolso->EditValue;
			}

			// mig_intereses
			$this->mig_intereses->EditAttrs["class"] = "form-control";
			$this->mig_intereses->EditCustomAttributes = "";
			$this->mig_intereses->EditValue = ew_HtmlEncode($this->mig_intereses->CurrentValue);
			$this->mig_intereses->PlaceHolder = ew_RemoveHtml($this->mig_intereses->FldCaption());
			if (strval($this->mig_intereses->EditValue) <> "" && is_numeric($this->mig_intereses->EditValue)) {
			$this->mig_intereses->EditValue = ew_FormatNumber($this->mig_intereses->EditValue, -2, 0, -2, 0);
			$this->mig_intereses->OldValue = $this->mig_intereses->EditValue;
			}

			// mig_cargos_gastos
			$this->mig_cargos_gastos->EditAttrs["class"] = "form-control";
			$this->mig_cargos_gastos->EditCustomAttributes = "";
			$this->mig_cargos_gastos->EditValue = ew_HtmlEncode($this->mig_cargos_gastos->CurrentValue);
			$this->mig_cargos_gastos->PlaceHolder = ew_RemoveHtml($this->mig_cargos_gastos->FldCaption());
			if (strval($this->mig_cargos_gastos->EditValue) <> "" && is_numeric($this->mig_cargos_gastos->EditValue)) {
			$this->mig_cargos_gastos->EditValue = ew_FormatNumber($this->mig_cargos_gastos->EditValue, -2, 0, -2, 0);
			$this->mig_cargos_gastos->OldValue = $this->mig_cargos_gastos->EditValue;
			}

			// mig_total_deuda
			$this->mig_total_deuda->EditAttrs["class"] = "form-control";
			$this->mig_total_deuda->EditCustomAttributes = "";
			$this->mig_total_deuda->EditValue = ew_HtmlEncode($this->mig_total_deuda->CurrentValue);
			$this->mig_total_deuda->PlaceHolder = ew_RemoveHtml($this->mig_total_deuda->FldCaption());
			if (strval($this->mig_total_deuda->EditValue) <> "" && is_numeric($this->mig_total_deuda->EditValue)) {
			$this->mig_total_deuda->EditValue = ew_FormatNumber($this->mig_total_deuda->EditValue, -2, 0, -2, 0);
			$this->mig_total_deuda->OldValue = $this->mig_total_deuda->EditValue;
			}

			// Edit refer script
			// Id

			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";

			// id_cliente
			$this->id_cliente->LinkCustomAttributes = "";
			if (!ew_Empty($this->id_cliente->CurrentValue)) {
				$this->id_cliente->HrefValue = $this->id_cliente->CurrentValue; // Add prefix/suffix
				$this->id_cliente->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->id_cliente->HrefValue = ew_FullUrl($this->id_cliente->HrefValue, "href");
			} else {
				$this->id_cliente->HrefValue = "";
			}

			// id_ciudad
			$this->id_ciudad->LinkCustomAttributes = "";
			$this->id_ciudad->HrefValue = "";

			// id_agente
			$this->id_agente->LinkCustomAttributes = "";
			$this->id_agente->HrefValue = "";

			// id_estadodeuda
			$this->id_estadodeuda->LinkCustomAttributes = "";
			$this->id_estadodeuda->HrefValue = "";

			// mig_codigo_deuda
			$this->mig_codigo_deuda->LinkCustomAttributes = "";
			$this->mig_codigo_deuda->HrefValue = "";

			// mig_fecha_desembolso
			$this->mig_fecha_desembolso->LinkCustomAttributes = "";
			$this->mig_fecha_desembolso->HrefValue = "";

			// mig_moneda
			$this->mig_moneda->LinkCustomAttributes = "";
			$this->mig_moneda->HrefValue = "";

			// mig_tasa
			$this->mig_tasa->LinkCustomAttributes = "";
			$this->mig_tasa->HrefValue = "";

			// mig_plazo
			$this->mig_plazo->LinkCustomAttributes = "";
			$this->mig_plazo->HrefValue = "";

			// mig_dias_mora
			$this->mig_dias_mora->LinkCustomAttributes = "";
			$this->mig_dias_mora->HrefValue = "";

			// mig_monto_desembolso
			$this->mig_monto_desembolso->LinkCustomAttributes = "";
			$this->mig_monto_desembolso->HrefValue = "";

			// mig_intereses
			$this->mig_intereses->LinkCustomAttributes = "";
			$this->mig_intereses->HrefValue = "";

			// mig_cargos_gastos
			$this->mig_cargos_gastos->LinkCustomAttributes = "";
			$this->mig_cargos_gastos->HrefValue = "";

			// mig_total_deuda
			$this->mig_total_deuda->LinkCustomAttributes = "";
			$this->mig_total_deuda->HrefValue = "";
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

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->mig_codigo_deuda->FldIsDetailKey && !is_null($this->mig_codigo_deuda->FormValue) && $this->mig_codigo_deuda->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->mig_codigo_deuda->FldCaption(), $this->mig_codigo_deuda->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->mig_fecha_desembolso->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_fecha_desembolso->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_tasa->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_tasa->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_plazo->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_plazo->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_dias_mora->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_dias_mora->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_monto_desembolso->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_monto_desembolso->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_intereses->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_intereses->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_cargos_gastos->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_cargos_gastos->FldErrMsg());
		}
		if (!ew_CheckNumber($this->mig_total_deuda->FormValue)) {
			ew_AddMessage($gsFormError, $this->mig_total_deuda->FldErrMsg());
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();

		// Check if records exist for detail table 'deuda_persona'
		if (!isset($GLOBALS["deuda_persona"])) $GLOBALS["deuda_persona"] = new cdeuda_persona();
		foreach ($rows as $row) {
			$rsdetail = $GLOBALS["deuda_persona"]->LoadRs("`id_deuda` = " . ew_QuotedValue($row['Id'], EW_DATATYPE_NUMBER, 'DB'));
			if ($rsdetail && !$rsdetail->EOF) {
				$sRelatedRecordMsg = str_replace("%t", "deuda_persona", $Language->Phrase("RelatedRecordExists"));
				$this->setFailureMessage($sRelatedRecordMsg);
				return FALSE;
			}
		}

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['Id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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

			// id_cliente
			$this->id_cliente->SetDbValueDef($rsnew, $this->id_cliente->CurrentValue, 0, $this->id_cliente->ReadOnly);

			// id_ciudad
			$this->id_ciudad->SetDbValueDef($rsnew, $this->id_ciudad->CurrentValue, 0, $this->id_ciudad->ReadOnly);

			// id_agente
			$this->id_agente->SetDbValueDef($rsnew, $this->id_agente->CurrentValue, 0, $this->id_agente->ReadOnly);

			// id_estadodeuda
			$this->id_estadodeuda->SetDbValueDef($rsnew, $this->id_estadodeuda->CurrentValue, 0, $this->id_estadodeuda->ReadOnly);

			// mig_codigo_deuda
			$this->mig_codigo_deuda->SetDbValueDef($rsnew, $this->mig_codigo_deuda->CurrentValue, "", $this->mig_codigo_deuda->ReadOnly);

			// mig_fecha_desembolso
			$this->mig_fecha_desembolso->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->mig_fecha_desembolso->CurrentValue, 7), NULL, $this->mig_fecha_desembolso->ReadOnly);

			// mig_moneda
			$this->mig_moneda->SetDbValueDef($rsnew, $this->mig_moneda->CurrentValue, NULL, $this->mig_moneda->ReadOnly);

			// mig_tasa
			$this->mig_tasa->SetDbValueDef($rsnew, $this->mig_tasa->CurrentValue, NULL, $this->mig_tasa->ReadOnly);

			// mig_plazo
			$this->mig_plazo->SetDbValueDef($rsnew, $this->mig_plazo->CurrentValue, NULL, $this->mig_plazo->ReadOnly);

			// mig_dias_mora
			$this->mig_dias_mora->SetDbValueDef($rsnew, $this->mig_dias_mora->CurrentValue, NULL, $this->mig_dias_mora->ReadOnly);

			// mig_monto_desembolso
			$this->mig_monto_desembolso->SetDbValueDef($rsnew, $this->mig_monto_desembolso->CurrentValue, NULL, $this->mig_monto_desembolso->ReadOnly);

			// mig_intereses
			$this->mig_intereses->SetDbValueDef($rsnew, $this->mig_intereses->CurrentValue, NULL, $this->mig_intereses->ReadOnly);

			// mig_cargos_gastos
			$this->mig_cargos_gastos->SetDbValueDef($rsnew, $this->mig_cargos_gastos->CurrentValue, NULL, $this->mig_cargos_gastos->ReadOnly);

			// mig_total_deuda
			$this->mig_total_deuda->SetDbValueDef($rsnew, $this->mig_total_deuda->CurrentValue, NULL, $this->mig_total_deuda->ReadOnly);

			// Check referential integrity for master table 'cuentas'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_cuentas();
			$KeyValue = isset($rsnew['id_cliente']) ? $rsnew['id_cliente'] : $rsold['id_cliente'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@Id@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				if (!isset($GLOBALS["cuentas"])) $GLOBALS["cuentas"] = new ccuentas();
				$rsmaster = $GLOBALS["cuentas"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "cuentas", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

			// Check referential integrity for master table 'users'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_users();
			$KeyValue = isset($rsnew['id_agente']) ? $rsnew['id_agente'] : $rsold['id_agente'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@id_user@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				if (!isset($GLOBALS["users"])) $GLOBALS["users"] = new cusers();
				$rsmaster = $GLOBALS["users"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "users", $Language->Phrase("RelatedRecordRequired"));
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;

		// Set up foreign key field value from Session
			if ($this->getCurrentMasterTable() == "cuentas") {
				$this->id_cliente->CurrentValue = $this->id_cliente->getSessionValue();
			}
			if ($this->getCurrentMasterTable() == "users") {
				$this->id_agente->CurrentValue = $this->id_agente->getSessionValue();
			}

		// Check referential integrity for master table 'cuentas'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_cuentas();
		if (strval($this->id_cliente->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@Id@", ew_AdjustSql($this->id_cliente->CurrentValue, "DB"), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			if (!isset($GLOBALS["cuentas"])) $GLOBALS["cuentas"] = new ccuentas();
			$rsmaster = $GLOBALS["cuentas"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "cuentas", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}

		// Check referential integrity for master table 'users'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_users();
		if (strval($this->id_agente->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@id_user@", ew_AdjustSql($this->id_agente->CurrentValue, "DB"), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			if (!isset($GLOBALS["users"])) $GLOBALS["users"] = new cusers();
			$rsmaster = $GLOBALS["users"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "users", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// id_cliente
		$this->id_cliente->SetDbValueDef($rsnew, $this->id_cliente->CurrentValue, 0, strval($this->id_cliente->CurrentValue) == "");

		// id_ciudad
		$this->id_ciudad->SetDbValueDef($rsnew, $this->id_ciudad->CurrentValue, 0, strval($this->id_ciudad->CurrentValue) == "");

		// id_agente
		$this->id_agente->SetDbValueDef($rsnew, $this->id_agente->CurrentValue, 0, strval($this->id_agente->CurrentValue) == "");

		// id_estadodeuda
		$this->id_estadodeuda->SetDbValueDef($rsnew, $this->id_estadodeuda->CurrentValue, 0, strval($this->id_estadodeuda->CurrentValue) == "");

		// mig_codigo_deuda
		$this->mig_codigo_deuda->SetDbValueDef($rsnew, $this->mig_codigo_deuda->CurrentValue, "", FALSE);

		// mig_fecha_desembolso
		$this->mig_fecha_desembolso->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->mig_fecha_desembolso->CurrentValue, 7), NULL, FALSE);

		// mig_moneda
		$this->mig_moneda->SetDbValueDef($rsnew, $this->mig_moneda->CurrentValue, NULL, FALSE);

		// mig_tasa
		$this->mig_tasa->SetDbValueDef($rsnew, $this->mig_tasa->CurrentValue, NULL, FALSE);

		// mig_plazo
		$this->mig_plazo->SetDbValueDef($rsnew, $this->mig_plazo->CurrentValue, NULL, FALSE);

		// mig_dias_mora
		$this->mig_dias_mora->SetDbValueDef($rsnew, $this->mig_dias_mora->CurrentValue, NULL, FALSE);

		// mig_monto_desembolso
		$this->mig_monto_desembolso->SetDbValueDef($rsnew, $this->mig_monto_desembolso->CurrentValue, NULL, FALSE);

		// mig_intereses
		$this->mig_intereses->SetDbValueDef($rsnew, $this->mig_intereses->CurrentValue, NULL, FALSE);

		// mig_cargos_gastos
		$this->mig_cargos_gastos->SetDbValueDef($rsnew, $this->mig_cargos_gastos->CurrentValue, NULL, FALSE);

		// mig_total_deuda
		$this->mig_total_deuda->SetDbValueDef($rsnew, $this->mig_total_deuda->CurrentValue, NULL, FALSE);

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

	// Set up master/detail based on QueryString
	function SetupMasterParms() {

		// Hide foreign keys
		$sMasterTblVar = $this->getCurrentMasterTable();
		if ($sMasterTblVar == "cuentas") {
			$this->id_cliente->Visible = FALSE;
			if ($GLOBALS["cuentas"]->EventCancelled) $this->EventCancelled = TRUE;
		}
		if ($sMasterTblVar == "users") {
			$this->id_agente->Visible = FALSE;
			if ($GLOBALS["users"]->EventCancelled) $this->EventCancelled = TRUE;
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
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
