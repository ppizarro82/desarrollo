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

$direcciones_view = NULL; // Initialize page object first

class cdirecciones_view extends cdirecciones {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'direcciones';

	// Page object name
	var $PageObjName = 'direcciones_view';

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
		$KeyUrl = "";
		if (@$_GET["Id"] <> "") {
			$this->RecKey["Id"] = $_GET["Id"];
			$KeyUrl .= "&amp;Id=" . urlencode($this->RecKey["Id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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
		if (@$_GET["Id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= $_GET["Id"];
		}

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;
	var $MultiPages; // Multi pages object

	//
	// Page main
	//
	function Page_Main() {
		global $Language, $gbSkipHeaderFooter, $EW_EXPORT;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["Id"] <> "") {
				$this->Id->setQueryStringValue($_GET["Id"]);
				$this->RecKey["Id"] = $this->Id->QueryStringValue;
			} elseif (@$_POST["Id"] <> "") {
				$this->Id->setFormValue($_POST["Id"]);
				$this->RecKey["Id"] = $this->Id->FormValue;
			} else {
				$sReturnUrl = "direccioneslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "direccioneslist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "direccioneslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = TRUE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
		$item->Body = "<button id=\"emf_direcciones\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_direcciones',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fdireccionesview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

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
		$this->SetupStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
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
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");
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

		// Add record key QueryString
		$sQry .= "&" . substr($this->KeyUrl("", ""), 1);
		return $sQry;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("direccioneslist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
	}

	// Set up multi pages
	function SetupMultiPages() {
		$pages = new cSubPages();
		$pages->Style = "tabs";
		$pages->Add(0);
		$pages->Add(1);
		$pages->Add(2);
		$pages->Add(3);
		$pages->Add(4);
		$this->MultiPages = $pages;
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
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
if (!isset($direcciones_view)) $direcciones_view = new cdirecciones_view();

// Page init
$direcciones_view->Page_Init();

// Page main
$direcciones_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$direcciones_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($direcciones->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fdireccionesview = new ew_Form("fdireccionesview", "view");

// Form_CustomValidate event
fdireccionesview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdireccionesview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
fdireccionesview.MultiPage = new ew_MultiPage("fdireccionesview");

// Dynamic selection lists
fdireccionesview.Lists["x_id_fuente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"fuentes"};
fdireccionesview.Lists["x_id_fuente"].Data = "<?php echo $direcciones_view->id_fuente->LookupFilterQuery(FALSE, "view") ?>";
fdireccionesview.Lists["x_id_gestion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gestiones"};
fdireccionesview.Lists["x_id_gestion"].Data = "<?php echo $direcciones_view->id_gestion->LookupFilterQuery(FALSE, "view") ?>";
fdireccionesview.Lists["x_id_tipodireccion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipo_direccion"};
fdireccionesview.Lists["x_id_tipodireccion"].Data = "<?php echo $direcciones_view->id_tipodireccion->LookupFilterQuery(FALSE, "view") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($direcciones->Export == "") { ?>
<div class="ewToolbar">
<?php $direcciones_view->ExportOptions->Render("body") ?>
<?php
	foreach ($direcciones_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $direcciones_view->ShowPageHeader(); ?>
<?php
$direcciones_view->ShowMessage();
?>
<form name="fdireccionesview" id="fdireccionesview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($direcciones_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $direcciones_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="direcciones">
<input type="hidden" name="modal" value="<?php echo intval($direcciones_view->IsModal) ?>">
<?php if ($direcciones->Export == "") { ?>
<div class="ewMultiPage">
<div class="nav-tabs-custom" id="direcciones_view">
	<ul class="nav<?php echo $direcciones_view->MultiPages->NavStyle() ?>">
		<li<?php echo $direcciones_view->MultiPages->TabStyle("1") ?>><a href="#tab_direcciones1" data-toggle="tab"><?php echo $direcciones->PageCaption(1) ?></a></li>
		<li<?php echo $direcciones_view->MultiPages->TabStyle("2") ?>><a href="#tab_direcciones2" data-toggle="tab"><?php echo $direcciones->PageCaption(2) ?></a></li>
		<li<?php echo $direcciones_view->MultiPages->TabStyle("3") ?>><a href="#tab_direcciones3" data-toggle="tab"><?php echo $direcciones->PageCaption(3) ?></a></li>
		<li<?php echo $direcciones_view->MultiPages->TabStyle("4") ?>><a href="#tab_direcciones4" data-toggle="tab"><?php echo $direcciones->PageCaption(4) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
<?php if ($direcciones->Export == "") { ?>
		<div class="tab-pane<?php echo $direcciones_view->MultiPages->PageStyle("1") ?>" id="tab_direcciones1">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($direcciones->Id->Visible) { // Id ?>
	<tr id="r_Id">
		<td class="col-sm-2"><span id="elh_direcciones_Id"><?php echo $direcciones->Id->FldCaption() ?></span></td>
		<td data-name="Id"<?php echo $direcciones->Id->CellAttributes() ?>>
<span id="el_direcciones_Id" data-page="1">
<span<?php echo $direcciones->Id->ViewAttributes() ?>>
<?php echo $direcciones->Id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->id_fuente->Visible) { // id_fuente ?>
	<tr id="r_id_fuente">
		<td class="col-sm-2"><span id="elh_direcciones_id_fuente"><?php echo $direcciones->id_fuente->FldCaption() ?></span></td>
		<td data-name="id_fuente"<?php echo $direcciones->id_fuente->CellAttributes() ?>>
<span id="el_direcciones_id_fuente" data-page="1">
<span<?php echo $direcciones->id_fuente->ViewAttributes() ?>>
<?php echo $direcciones->id_fuente->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->id_gestion->Visible) { // id_gestion ?>
	<tr id="r_id_gestion">
		<td class="col-sm-2"><span id="elh_direcciones_id_gestion"><?php echo $direcciones->id_gestion->FldCaption() ?></span></td>
		<td data-name="id_gestion"<?php echo $direcciones->id_gestion->CellAttributes() ?>>
<span id="el_direcciones_id_gestion" data-page="1">
<span<?php echo $direcciones->id_gestion->ViewAttributes() ?>>
<?php echo $direcciones->id_gestion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->id_tipodireccion->Visible) { // id_tipodireccion ?>
	<tr id="r_id_tipodireccion">
		<td class="col-sm-2"><span id="elh_direcciones_id_tipodireccion"><?php echo $direcciones->id_tipodireccion->FldCaption() ?></span></td>
		<td data-name="id_tipodireccion"<?php echo $direcciones->id_tipodireccion->CellAttributes() ?>>
<span id="el_direcciones_id_tipodireccion" data-page="1">
<span<?php echo $direcciones->id_tipodireccion->ViewAttributes() ?>>
<?php echo $direcciones->id_tipodireccion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($direcciones->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($direcciones->Export == "") { ?>
		<div class="tab-pane<?php echo $direcciones_view->MultiPages->PageStyle("2") ?>" id="tab_direcciones2">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($direcciones->tipo_documento->Visible) { // tipo_documento ?>
	<tr id="r_tipo_documento">
		<td class="col-sm-2"><span id="elh_direcciones_tipo_documento"><?php echo $direcciones->tipo_documento->FldCaption() ?></span></td>
		<td data-name="tipo_documento"<?php echo $direcciones->tipo_documento->CellAttributes() ?>>
<span id="el_direcciones_tipo_documento" data-page="2">
<span<?php echo $direcciones->tipo_documento->ViewAttributes() ?>>
<?php echo $direcciones->tipo_documento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->no_documento->Visible) { // no_documento ?>
	<tr id="r_no_documento">
		<td class="col-sm-2"><span id="elh_direcciones_no_documento"><?php echo $direcciones->no_documento->FldCaption() ?></span></td>
		<td data-name="no_documento"<?php echo $direcciones->no_documento->CellAttributes() ?>>
<span id="el_direcciones_no_documento" data-page="2">
<span<?php echo $direcciones->no_documento->ViewAttributes() ?>>
<?php echo $direcciones->no_documento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->nombres->Visible) { // nombres ?>
	<tr id="r_nombres">
		<td class="col-sm-2"><span id="elh_direcciones_nombres"><?php echo $direcciones->nombres->FldCaption() ?></span></td>
		<td data-name="nombres"<?php echo $direcciones->nombres->CellAttributes() ?>>
<span id="el_direcciones_nombres" data-page="2">
<span<?php echo $direcciones->nombres->ViewAttributes() ?>>
<?php echo $direcciones->nombres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->paterno->Visible) { // paterno ?>
	<tr id="r_paterno">
		<td class="col-sm-2"><span id="elh_direcciones_paterno"><?php echo $direcciones->paterno->FldCaption() ?></span></td>
		<td data-name="paterno"<?php echo $direcciones->paterno->CellAttributes() ?>>
<span id="el_direcciones_paterno" data-page="2">
<span<?php echo $direcciones->paterno->ViewAttributes() ?>>
<?php echo $direcciones->paterno->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->materno->Visible) { // materno ?>
	<tr id="r_materno">
		<td class="col-sm-2"><span id="elh_direcciones_materno"><?php echo $direcciones->materno->FldCaption() ?></span></td>
		<td data-name="materno"<?php echo $direcciones->materno->CellAttributes() ?>>
<span id="el_direcciones_materno" data-page="2">
<span<?php echo $direcciones->materno->ViewAttributes() ?>>
<?php echo $direcciones->materno->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($direcciones->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($direcciones->Export == "") { ?>
		<div class="tab-pane<?php echo $direcciones_view->MultiPages->PageStyle("3") ?>" id="tab_direcciones3">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($direcciones->pais->Visible) { // pais ?>
	<tr id="r_pais">
		<td class="col-sm-2"><span id="elh_direcciones_pais"><?php echo $direcciones->pais->FldCaption() ?></span></td>
		<td data-name="pais"<?php echo $direcciones->pais->CellAttributes() ?>>
<span id="el_direcciones_pais" data-page="3">
<span<?php echo $direcciones->pais->ViewAttributes() ?>>
<?php echo $direcciones->pais->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->departamento->Visible) { // departamento ?>
	<tr id="r_departamento">
		<td class="col-sm-2"><span id="elh_direcciones_departamento"><?php echo $direcciones->departamento->FldCaption() ?></span></td>
		<td data-name="departamento"<?php echo $direcciones->departamento->CellAttributes() ?>>
<span id="el_direcciones_departamento" data-page="3">
<span<?php echo $direcciones->departamento->ViewAttributes() ?>>
<?php echo $direcciones->departamento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->provincia->Visible) { // provincia ?>
	<tr id="r_provincia">
		<td class="col-sm-2"><span id="elh_direcciones_provincia"><?php echo $direcciones->provincia->FldCaption() ?></span></td>
		<td data-name="provincia"<?php echo $direcciones->provincia->CellAttributes() ?>>
<span id="el_direcciones_provincia" data-page="3">
<span<?php echo $direcciones->provincia->ViewAttributes() ?>>
<?php echo $direcciones->provincia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->municipio->Visible) { // municipio ?>
	<tr id="r_municipio">
		<td class="col-sm-2"><span id="elh_direcciones_municipio"><?php echo $direcciones->municipio->FldCaption() ?></span></td>
		<td data-name="municipio"<?php echo $direcciones->municipio->CellAttributes() ?>>
<span id="el_direcciones_municipio" data-page="3">
<span<?php echo $direcciones->municipio->ViewAttributes() ?>>
<?php echo $direcciones->municipio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->localidad->Visible) { // localidad ?>
	<tr id="r_localidad">
		<td class="col-sm-2"><span id="elh_direcciones_localidad"><?php echo $direcciones->localidad->FldCaption() ?></span></td>
		<td data-name="localidad"<?php echo $direcciones->localidad->CellAttributes() ?>>
<span id="el_direcciones_localidad" data-page="3">
<span<?php echo $direcciones->localidad->ViewAttributes() ?>>
<?php echo $direcciones->localidad->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->distrito->Visible) { // distrito ?>
	<tr id="r_distrito">
		<td class="col-sm-2"><span id="elh_direcciones_distrito"><?php echo $direcciones->distrito->FldCaption() ?></span></td>
		<td data-name="distrito"<?php echo $direcciones->distrito->CellAttributes() ?>>
<span id="el_direcciones_distrito" data-page="3">
<span<?php echo $direcciones->distrito->ViewAttributes() ?>>
<?php echo $direcciones->distrito->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->zona->Visible) { // zona ?>
	<tr id="r_zona">
		<td class="col-sm-2"><span id="elh_direcciones_zona"><?php echo $direcciones->zona->FldCaption() ?></span></td>
		<td data-name="zona"<?php echo $direcciones->zona->CellAttributes() ?>>
<span id="el_direcciones_zona" data-page="3">
<span<?php echo $direcciones->zona->ViewAttributes() ?>>
<?php echo $direcciones->zona->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->direccion1->Visible) { // direccion1 ?>
	<tr id="r_direccion1">
		<td class="col-sm-2"><span id="elh_direcciones_direccion1"><?php echo $direcciones->direccion1->FldCaption() ?></span></td>
		<td data-name="direccion1"<?php echo $direcciones->direccion1->CellAttributes() ?>>
<span id="el_direcciones_direccion1" data-page="3">
<span<?php echo $direcciones->direccion1->ViewAttributes() ?>>
<?php echo $direcciones->direccion1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->direccion2->Visible) { // direccion2 ?>
	<tr id="r_direccion2">
		<td class="col-sm-2"><span id="elh_direcciones_direccion2"><?php echo $direcciones->direccion2->FldCaption() ?></span></td>
		<td data-name="direccion2"<?php echo $direcciones->direccion2->CellAttributes() ?>>
<span id="el_direcciones_direccion2" data-page="3">
<span<?php echo $direcciones->direccion2->ViewAttributes() ?>>
<?php echo $direcciones->direccion2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->direccion3->Visible) { // direccion3 ?>
	<tr id="r_direccion3">
		<td class="col-sm-2"><span id="elh_direcciones_direccion3"><?php echo $direcciones->direccion3->FldCaption() ?></span></td>
		<td data-name="direccion3"<?php echo $direcciones->direccion3->CellAttributes() ?>>
<span id="el_direcciones_direccion3" data-page="3">
<span<?php echo $direcciones->direccion3->ViewAttributes() ?>>
<?php echo $direcciones->direccion3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->direccion4->Visible) { // direccion4 ?>
	<tr id="r_direccion4">
		<td class="col-sm-2"><span id="elh_direcciones_direccion4"><?php echo $direcciones->direccion4->FldCaption() ?></span></td>
		<td data-name="direccion4"<?php echo $direcciones->direccion4->CellAttributes() ?>>
<span id="el_direcciones_direccion4" data-page="3">
<span<?php echo $direcciones->direccion4->ViewAttributes() ?>>
<?php echo $direcciones->direccion4->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($direcciones->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($direcciones->Export == "") { ?>
		<div class="tab-pane<?php echo $direcciones_view->MultiPages->PageStyle("4") ?>" id="tab_direcciones4">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($direcciones->mapa->Visible) { // mapa ?>
	<tr id="r_mapa">
		<td class="col-sm-2"><span id="elh_direcciones_mapa"><?php echo $direcciones->mapa->FldCaption() ?></span></td>
		<td data-name="mapa"<?php echo $direcciones->mapa->CellAttributes() ?>>
<script id="orig_direcciones_mapa" type="text/html">
<?php echo $direcciones->mapa->ViewValue ?>
</script>
<span id="el_direcciones_mapa" data-page="4">
<span<?php echo $direcciones->mapa->ViewAttributes() ?>><div id="gm_direcciones_x_mapa" class="ewGoogleMap" style="width: 400px; height: 400px;"></div>
<script type="text/javascript">
ewGoogleMaps[ewGoogleMaps.length] = jQuery.extend({"id":"gm_direcciones_x_mapa","name":"Google Maps","apikey":"AIzaSyDFibhqbazLZqySy6EuVE_BHRUvkhyIVLg","width":400,"width_field":null,"height":400,"height_field":null,"latitude":null,"latitude_field":"latitud","longitude":null,"longitude_field":"longitud","address":null,"address_field":null,"type":"HYBRID","type_field":null,"zoom":18,"zoom_field":null,"title":null,"title_field":"direccion","icon":null,"icon_field":null,"description":null,"description_field":null,"use_single_map":false,"single_map_width":400,"single_map_height":400,"show_map_on_top":true,"show_all_markers":true,"geocoding_delay":250,"use_marker_clusterer":false,"cluster_max_zoom":-1,"cluster_grid_size":-1,"cluster_styles":-1,"template_id":""}, {
	latitude: <?php echo ew_VarToJson($direcciones->latitud->CurrentValue, "undefined") ?>,
	longitude: <?php echo ew_VarToJson($direcciones->longitud->CurrentValue, "undefined") ?>
});
</script>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->longitud->Visible) { // longitud ?>
	<tr id="r_longitud">
		<td class="col-sm-2"><span id="elh_direcciones_longitud"><?php echo $direcciones->longitud->FldCaption() ?></span></td>
		<td data-name="longitud"<?php echo $direcciones->longitud->CellAttributes() ?>>
<span id="el_direcciones_longitud" data-page="4">
<span<?php echo $direcciones->longitud->ViewAttributes() ?>>
<?php echo $direcciones->longitud->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($direcciones->latitud->Visible) { // latitud ?>
	<tr id="r_latitud">
		<td class="col-sm-2"><span id="elh_direcciones_latitud"><?php echo $direcciones->latitud->FldCaption() ?></span></td>
		<td data-name="latitud"<?php echo $direcciones->latitud->CellAttributes() ?>>
<span id="el_direcciones_latitud" data-page="4">
<span<?php echo $direcciones->latitud->ViewAttributes() ?>>
<?php echo $direcciones->latitud->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($direcciones->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($direcciones->Export == "") { ?>
	</div>
</div>
</div>
<?php } ?>
</form>
<?php if ($direcciones->Export == "") { ?>
<script type="text/javascript">
fdireccionesview.Init();
</script>
<?php } ?>
<?php
$direcciones_view->ShowPageFooter();
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
$direcciones_view->Page_Terminate();
?>
