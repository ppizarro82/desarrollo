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

$deudas_view = NULL; // Initialize page object first

class cdeudas_view extends cdeudas {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'deudas';

	// Page object name
	var $PageObjName = 'deudas_view';

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

		// Table object (cuentas)
		if (!isset($GLOBALS['cuentas'])) $GLOBALS['cuentas'] = new ccuentas();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("deudaslist.php"));
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
		$this->id_cliente->SetVisibility();
		$this->id_ciudad->SetVisibility();
		$this->id_agente->SetVisibility();
		$this->id_estadodeuda->SetVisibility();
		$this->mig_codigo_deuda->SetVisibility();
		$this->mig_tipo_operacion->SetVisibility();
		$this->mig_fecha_desembolso->SetVisibility();
		$this->mig_fecha_estado->SetVisibility();
		$this->mig_anios_castigo->SetVisibility();
		$this->mig_tipo_garantia->SetVisibility();
		$this->mig_real->SetVisibility();
		$this->mig_actividad_economica->SetVisibility();
		$this->mig_agencia->SetVisibility();
		$this->mig_no_juicio->SetVisibility();
		$this->mig_nombre_abogado->SetVisibility();
		$this->mig_fase_procesal->SetVisibility();
		$this->mig_moneda->SetVisibility();
		$this->mig_tasa->SetVisibility();
		$this->mig_plazo->SetVisibility();
		$this->mig_dias_mora->SetVisibility();
		$this->mig_monto_desembolso->SetVisibility();
		$this->mig_total_cartera->SetVisibility();
		$this->mig_capital->SetVisibility();
		$this->mig_intereses->SetVisibility();
		$this->mig_cargos_gastos->SetVisibility();
		$this->mig_total_deuda->SetVisibility();

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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "deudasview.php")
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
	var $deuda_persona_Count;
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

		// Set up master/detail parameters
		$this->SetupMasterParms();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["Id"] <> "") {
				$this->Id->setQueryStringValue($_GET["Id"]);
				$this->RecKey["Id"] = $this->Id->QueryStringValue;
			} elseif (@$_POST["Id"] <> "") {
				$this->Id->setFormValue($_POST["Id"]);
				$this->RecKey["Id"] = $this->Id->FormValue;
			} else {
				$sReturnUrl = "deudaslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "deudaslist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "deudaslist.php"; // Not page request, return to list
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

		// Set up detail parameters
		$this->SetupDetailParms();
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
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_deuda_persona"
		$item = &$option->Add("detail_deuda_persona");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("deuda_persona", "TblCaption");
		$body .= str_replace("%c", $this->deuda_persona_Count, $Language->Phrase("DetailCount"));
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("deuda_personalist.php?" . EW_TABLE_SHOW_MASTER . "=deudas&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["deuda_persona_grid"] && $GLOBALS["deuda_persona_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'deuda_persona')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=deuda_persona")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "deuda_persona";
		}
		if ($GLOBALS["deuda_persona_grid"] && $GLOBALS["deuda_persona_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'deuda_persona')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=deuda_persona")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "deuda_persona";
		}
		if ($GLOBALS["deuda_persona_grid"] && $GLOBALS["deuda_persona_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'deuda_persona')) {
			$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=deuda_persona")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
			$DetailCopyTblVar .= "deuda_persona";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'deuda_persona');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "deuda_persona";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
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
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

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
		if ($this->mig_total_cartera->FormValue == $this->mig_total_cartera->CurrentValue && is_numeric(ew_StrToFloat($this->mig_total_cartera->CurrentValue)))
			$this->mig_total_cartera->CurrentValue = ew_StrToFloat($this->mig_total_cartera->CurrentValue);

		// Convert decimal values if posted back
		if ($this->mig_capital->FormValue == $this->mig_capital->CurrentValue && is_numeric(ew_StrToFloat($this->mig_capital->CurrentValue)))
			$this->mig_capital->CurrentValue = ew_StrToFloat($this->mig_capital->CurrentValue);

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

			// mig_tipo_operacion
			$this->mig_tipo_operacion->LinkCustomAttributes = "";
			$this->mig_tipo_operacion->HrefValue = "";
			$this->mig_tipo_operacion->TooltipValue = "";

			// mig_fecha_desembolso
			$this->mig_fecha_desembolso->LinkCustomAttributes = "";
			$this->mig_fecha_desembolso->HrefValue = "";
			$this->mig_fecha_desembolso->TooltipValue = "";

			// mig_fecha_estado
			$this->mig_fecha_estado->LinkCustomAttributes = "";
			$this->mig_fecha_estado->HrefValue = "";
			$this->mig_fecha_estado->TooltipValue = "";

			// mig_anios_castigo
			$this->mig_anios_castigo->LinkCustomAttributes = "";
			$this->mig_anios_castigo->HrefValue = "";
			$this->mig_anios_castigo->TooltipValue = "";

			// mig_tipo_garantia
			$this->mig_tipo_garantia->LinkCustomAttributes = "";
			$this->mig_tipo_garantia->HrefValue = "";
			$this->mig_tipo_garantia->TooltipValue = "";

			// mig_real
			$this->mig_real->LinkCustomAttributes = "";
			$this->mig_real->HrefValue = "";
			$this->mig_real->TooltipValue = "";

			// mig_actividad_economica
			$this->mig_actividad_economica->LinkCustomAttributes = "";
			$this->mig_actividad_economica->HrefValue = "";
			$this->mig_actividad_economica->TooltipValue = "";

			// mig_agencia
			$this->mig_agencia->LinkCustomAttributes = "";
			$this->mig_agencia->HrefValue = "";
			$this->mig_agencia->TooltipValue = "";

			// mig_no_juicio
			$this->mig_no_juicio->LinkCustomAttributes = "";
			$this->mig_no_juicio->HrefValue = "";
			$this->mig_no_juicio->TooltipValue = "";

			// mig_nombre_abogado
			$this->mig_nombre_abogado->LinkCustomAttributes = "";
			$this->mig_nombre_abogado->HrefValue = "";
			$this->mig_nombre_abogado->TooltipValue = "";

			// mig_fase_procesal
			$this->mig_fase_procesal->LinkCustomAttributes = "";
			$this->mig_fase_procesal->HrefValue = "";
			$this->mig_fase_procesal->TooltipValue = "";

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

			// mig_total_cartera
			$this->mig_total_cartera->LinkCustomAttributes = "";
			$this->mig_total_cartera->HrefValue = "";
			$this->mig_total_cartera->TooltipValue = "";

			// mig_capital
			$this->mig_capital->LinkCustomAttributes = "";
			$this->mig_capital->HrefValue = "";
			$this->mig_capital->TooltipValue = "";

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
		$item->Body = "<button id=\"emf_deudas\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_deudas',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fdeudasview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export detail records (deuda_persona)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("deuda_persona", explode(",", $this->getCurrentDetailTable()))) {
			global $deuda_persona;
			if (!isset($deuda_persona)) $deuda_persona = new cdeuda_persona;
			$rsdetail = $deuda_persona->LoadRs($deuda_persona->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$oldtbl = $Doc->Table;
					$Doc->Table = $deuda_persona;
					$deuda_persona->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
					$Doc->Table = $oldtbl;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}
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

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

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
			if (in_array("deuda_persona", $DetailTblVar)) {
				if (!isset($GLOBALS["deuda_persona_grid"]))
					$GLOBALS["deuda_persona_grid"] = new cdeuda_persona_grid;
				if ($GLOBALS["deuda_persona_grid"]->DetailView) {
					$GLOBALS["deuda_persona_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["deuda_persona_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["deuda_persona_grid"]->setStartRecordNumber(1);
					$GLOBALS["deuda_persona_grid"]->id_deuda->FldIsDetailKey = TRUE;
					$GLOBALS["deuda_persona_grid"]->id_deuda->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["deuda_persona_grid"]->id_deuda->setSessionValue($GLOBALS["deuda_persona_grid"]->id_deuda->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("deudaslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($deudas_view)) $deudas_view = new cdeudas_view();

// Page init
$deudas_view->Page_Init();

// Page main
$deudas_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$deudas_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($deudas->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fdeudasview = new ew_Form("fdeudasview", "view");

// Form_CustomValidate event
fdeudasview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fdeudasview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
fdeudasview.MultiPage = new ew_MultiPage("fdeudasview");

// Dynamic selection lists
fdeudasview.Lists["x_id_cliente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"cuentas"};
fdeudasview.Lists["x_id_cliente"].Data = "<?php echo $deudas_view->id_cliente->LookupFilterQuery(FALSE, "view") ?>";
fdeudasview.Lists["x_id_ciudad"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"ciudades"};
fdeudasview.Lists["x_id_ciudad"].Data = "<?php echo $deudas_view->id_ciudad->LookupFilterQuery(FALSE, "view") ?>";
fdeudasview.Lists["x_id_agente"] = {"LinkField":"x_id_user","Ajax":true,"AutoFill":false,"DisplayFields":["x_First_Name","x_Last_Name","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
fdeudasview.Lists["x_id_agente"].Data = "<?php echo $deudas_view->id_agente->LookupFilterQuery(FALSE, "view") ?>";
fdeudasview.Lists["x_id_estadodeuda"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"estado_deuda"};
fdeudasview.Lists["x_id_estadodeuda"].Data = "<?php echo $deudas_view->id_estadodeuda->LookupFilterQuery(FALSE, "view") ?>";
fdeudasview.Lists["x_mig_moneda"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdeudasview.Lists["x_mig_moneda"].Options = <?php echo json_encode($deudas_view->mig_moneda->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($deudas->Export == "") { ?>
<div class="ewToolbar">
<?php $deudas_view->ExportOptions->Render("body") ?>
<?php
	foreach ($deudas_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $deudas_view->ShowPageHeader(); ?>
<?php
$deudas_view->ShowMessage();
?>
<form name="fdeudasview" id="fdeudasview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($deudas_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $deudas_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="deudas">
<input type="hidden" name="modal" value="<?php echo intval($deudas_view->IsModal) ?>">
<?php if ($deudas->Export == "") { ?>
<div class="ewMultiPage">
<div class="nav-tabs-custom" id="deudas_view">
	<ul class="nav<?php echo $deudas_view->MultiPages->NavStyle() ?>">
		<li<?php echo $deudas_view->MultiPages->TabStyle("1") ?>><a href="#tab_deudas1" data-toggle="tab"><?php echo $deudas->PageCaption(1) ?></a></li>
		<li<?php echo $deudas_view->MultiPages->TabStyle("2") ?>><a href="#tab_deudas2" data-toggle="tab"><?php echo $deudas->PageCaption(2) ?></a></li>
		<li<?php echo $deudas_view->MultiPages->TabStyle("3") ?>><a href="#tab_deudas3" data-toggle="tab"><?php echo $deudas->PageCaption(3) ?></a></li>
		<li<?php echo $deudas_view->MultiPages->TabStyle("4") ?>><a href="#tab_deudas4" data-toggle="tab"><?php echo $deudas->PageCaption(4) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
<?php if ($deudas->Export == "") { ?>
		<div class="tab-pane<?php echo $deudas_view->MultiPages->PageStyle("1") ?>" id="tab_deudas1">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($deudas->Id->Visible) { // Id ?>
	<tr id="r_Id">
		<td class="col-sm-2"><span id="elh_deudas_Id"><?php echo $deudas->Id->FldCaption() ?></span></td>
		<td data-name="Id"<?php echo $deudas->Id->CellAttributes() ?>>
<span id="el_deudas_Id" data-page="1">
<span<?php echo $deudas->Id->ViewAttributes() ?>>
<?php echo $deudas->Id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->id_cliente->Visible) { // id_cliente ?>
	<tr id="r_id_cliente">
		<td class="col-sm-2"><span id="elh_deudas_id_cliente"><?php echo $deudas->id_cliente->FldCaption() ?></span></td>
		<td data-name="id_cliente"<?php echo $deudas->id_cliente->CellAttributes() ?>>
<span id="el_deudas_id_cliente" data-page="1">
<span<?php echo $deudas->id_cliente->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deudas->id_cliente->ViewValue)) && $deudas->id_cliente->LinkAttributes() <> "") { ?>
<a<?php echo $deudas->id_cliente->LinkAttributes() ?>><?php echo $deudas->id_cliente->ViewValue ?></a>
<?php } else { ?>
<?php echo $deudas->id_cliente->ViewValue ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->id_ciudad->Visible) { // id_ciudad ?>
	<tr id="r_id_ciudad">
		<td class="col-sm-2"><span id="elh_deudas_id_ciudad"><?php echo $deudas->id_ciudad->FldCaption() ?></span></td>
		<td data-name="id_ciudad"<?php echo $deudas->id_ciudad->CellAttributes() ?>>
<span id="el_deudas_id_ciudad" data-page="1">
<span<?php echo $deudas->id_ciudad->ViewAttributes() ?>>
<?php echo $deudas->id_ciudad->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->id_agente->Visible) { // id_agente ?>
	<tr id="r_id_agente">
		<td class="col-sm-2"><span id="elh_deudas_id_agente"><?php echo $deudas->id_agente->FldCaption() ?></span></td>
		<td data-name="id_agente"<?php echo $deudas->id_agente->CellAttributes() ?>>
<span id="el_deudas_id_agente" data-page="1">
<span<?php echo $deudas->id_agente->ViewAttributes() ?>>
<?php echo $deudas->id_agente->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->id_estadodeuda->Visible) { // id_estadodeuda ?>
	<tr id="r_id_estadodeuda">
		<td class="col-sm-2"><span id="elh_deudas_id_estadodeuda"><?php echo $deudas->id_estadodeuda->FldCaption() ?></span></td>
		<td data-name="id_estadodeuda"<?php echo $deudas->id_estadodeuda->CellAttributes() ?>>
<span id="el_deudas_id_estadodeuda" data-page="1">
<span<?php echo $deudas->id_estadodeuda->ViewAttributes() ?>>
<?php echo $deudas->id_estadodeuda->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_codigo_deuda->Visible) { // mig_codigo_deuda ?>
	<tr id="r_mig_codigo_deuda">
		<td class="col-sm-2"><span id="elh_deudas_mig_codigo_deuda"><?php echo $deudas->mig_codigo_deuda->FldCaption() ?></span></td>
		<td data-name="mig_codigo_deuda"<?php echo $deudas->mig_codigo_deuda->CellAttributes() ?>>
<span id="el_deudas_mig_codigo_deuda" data-page="1">
<span<?php echo $deudas->mig_codigo_deuda->ViewAttributes() ?>>
<?php echo $deudas->mig_codigo_deuda->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($deudas->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($deudas->Export == "") { ?>
		<div class="tab-pane<?php echo $deudas_view->MultiPages->PageStyle("2") ?>" id="tab_deudas2">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($deudas->mig_tipo_operacion->Visible) { // mig_tipo_operacion ?>
	<tr id="r_mig_tipo_operacion">
		<td class="col-sm-2"><span id="elh_deudas_mig_tipo_operacion"><?php echo $deudas->mig_tipo_operacion->FldCaption() ?></span></td>
		<td data-name="mig_tipo_operacion"<?php echo $deudas->mig_tipo_operacion->CellAttributes() ?>>
<span id="el_deudas_mig_tipo_operacion" data-page="2">
<span<?php echo $deudas->mig_tipo_operacion->ViewAttributes() ?>>
<?php echo $deudas->mig_tipo_operacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_fecha_desembolso->Visible) { // mig_fecha_desembolso ?>
	<tr id="r_mig_fecha_desembolso">
		<td class="col-sm-2"><span id="elh_deudas_mig_fecha_desembolso"><?php echo $deudas->mig_fecha_desembolso->FldCaption() ?></span></td>
		<td data-name="mig_fecha_desembolso"<?php echo $deudas->mig_fecha_desembolso->CellAttributes() ?>>
<span id="el_deudas_mig_fecha_desembolso" data-page="2">
<span<?php echo $deudas->mig_fecha_desembolso->ViewAttributes() ?>>
<?php echo $deudas->mig_fecha_desembolso->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_fecha_estado->Visible) { // mig_fecha_estado ?>
	<tr id="r_mig_fecha_estado">
		<td class="col-sm-2"><span id="elh_deudas_mig_fecha_estado"><?php echo $deudas->mig_fecha_estado->FldCaption() ?></span></td>
		<td data-name="mig_fecha_estado"<?php echo $deudas->mig_fecha_estado->CellAttributes() ?>>
<span id="el_deudas_mig_fecha_estado" data-page="2">
<span<?php echo $deudas->mig_fecha_estado->ViewAttributes() ?>>
<?php echo $deudas->mig_fecha_estado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_anios_castigo->Visible) { // mig_anios_castigo ?>
	<tr id="r_mig_anios_castigo">
		<td class="col-sm-2"><span id="elh_deudas_mig_anios_castigo"><?php echo $deudas->mig_anios_castigo->FldCaption() ?></span></td>
		<td data-name="mig_anios_castigo"<?php echo $deudas->mig_anios_castigo->CellAttributes() ?>>
<span id="el_deudas_mig_anios_castigo" data-page="2">
<span<?php echo $deudas->mig_anios_castigo->ViewAttributes() ?>>
<?php echo $deudas->mig_anios_castigo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_tipo_garantia->Visible) { // mig_tipo_garantia ?>
	<tr id="r_mig_tipo_garantia">
		<td class="col-sm-2"><span id="elh_deudas_mig_tipo_garantia"><?php echo $deudas->mig_tipo_garantia->FldCaption() ?></span></td>
		<td data-name="mig_tipo_garantia"<?php echo $deudas->mig_tipo_garantia->CellAttributes() ?>>
<span id="el_deudas_mig_tipo_garantia" data-page="2">
<span<?php echo $deudas->mig_tipo_garantia->ViewAttributes() ?>>
<?php echo $deudas->mig_tipo_garantia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_real->Visible) { // mig_real ?>
	<tr id="r_mig_real">
		<td class="col-sm-2"><span id="elh_deudas_mig_real"><?php echo $deudas->mig_real->FldCaption() ?></span></td>
		<td data-name="mig_real"<?php echo $deudas->mig_real->CellAttributes() ?>>
<span id="el_deudas_mig_real" data-page="2">
<span<?php echo $deudas->mig_real->ViewAttributes() ?>>
<?php echo $deudas->mig_real->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($deudas->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($deudas->Export == "") { ?>
		<div class="tab-pane<?php echo $deudas_view->MultiPages->PageStyle("3") ?>" id="tab_deudas3">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($deudas->mig_actividad_economica->Visible) { // mig_actividad_economica ?>
	<tr id="r_mig_actividad_economica">
		<td class="col-sm-2"><span id="elh_deudas_mig_actividad_economica"><?php echo $deudas->mig_actividad_economica->FldCaption() ?></span></td>
		<td data-name="mig_actividad_economica"<?php echo $deudas->mig_actividad_economica->CellAttributes() ?>>
<span id="el_deudas_mig_actividad_economica" data-page="3">
<span<?php echo $deudas->mig_actividad_economica->ViewAttributes() ?>>
<?php echo $deudas->mig_actividad_economica->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_agencia->Visible) { // mig_agencia ?>
	<tr id="r_mig_agencia">
		<td class="col-sm-2"><span id="elh_deudas_mig_agencia"><?php echo $deudas->mig_agencia->FldCaption() ?></span></td>
		<td data-name="mig_agencia"<?php echo $deudas->mig_agencia->CellAttributes() ?>>
<span id="el_deudas_mig_agencia" data-page="3">
<span<?php echo $deudas->mig_agencia->ViewAttributes() ?>>
<?php echo $deudas->mig_agencia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_no_juicio->Visible) { // mig_no_juicio ?>
	<tr id="r_mig_no_juicio">
		<td class="col-sm-2"><span id="elh_deudas_mig_no_juicio"><?php echo $deudas->mig_no_juicio->FldCaption() ?></span></td>
		<td data-name="mig_no_juicio"<?php echo $deudas->mig_no_juicio->CellAttributes() ?>>
<span id="el_deudas_mig_no_juicio" data-page="3">
<span<?php echo $deudas->mig_no_juicio->ViewAttributes() ?>>
<?php echo $deudas->mig_no_juicio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_nombre_abogado->Visible) { // mig_nombre_abogado ?>
	<tr id="r_mig_nombre_abogado">
		<td class="col-sm-2"><span id="elh_deudas_mig_nombre_abogado"><?php echo $deudas->mig_nombre_abogado->FldCaption() ?></span></td>
		<td data-name="mig_nombre_abogado"<?php echo $deudas->mig_nombre_abogado->CellAttributes() ?>>
<span id="el_deudas_mig_nombre_abogado" data-page="3">
<span<?php echo $deudas->mig_nombre_abogado->ViewAttributes() ?>>
<?php echo $deudas->mig_nombre_abogado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_fase_procesal->Visible) { // mig_fase_procesal ?>
	<tr id="r_mig_fase_procesal">
		<td class="col-sm-2"><span id="elh_deudas_mig_fase_procesal"><?php echo $deudas->mig_fase_procesal->FldCaption() ?></span></td>
		<td data-name="mig_fase_procesal"<?php echo $deudas->mig_fase_procesal->CellAttributes() ?>>
<span id="el_deudas_mig_fase_procesal" data-page="3">
<span<?php echo $deudas->mig_fase_procesal->ViewAttributes() ?>>
<?php echo $deudas->mig_fase_procesal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($deudas->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($deudas->Export == "") { ?>
		<div class="tab-pane<?php echo $deudas_view->MultiPages->PageStyle("4") ?>" id="tab_deudas4">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($deudas->mig_moneda->Visible) { // mig_moneda ?>
	<tr id="r_mig_moneda">
		<td class="col-sm-2"><span id="elh_deudas_mig_moneda"><?php echo $deudas->mig_moneda->FldCaption() ?></span></td>
		<td data-name="mig_moneda"<?php echo $deudas->mig_moneda->CellAttributes() ?>>
<span id="el_deudas_mig_moneda" data-page="4">
<span<?php echo $deudas->mig_moneda->ViewAttributes() ?>>
<?php echo $deudas->mig_moneda->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_tasa->Visible) { // mig_tasa ?>
	<tr id="r_mig_tasa">
		<td class="col-sm-2"><span id="elh_deudas_mig_tasa"><?php echo $deudas->mig_tasa->FldCaption() ?></span></td>
		<td data-name="mig_tasa"<?php echo $deudas->mig_tasa->CellAttributes() ?>>
<span id="el_deudas_mig_tasa" data-page="4">
<span<?php echo $deudas->mig_tasa->ViewAttributes() ?>>
<?php echo $deudas->mig_tasa->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_plazo->Visible) { // mig_plazo ?>
	<tr id="r_mig_plazo">
		<td class="col-sm-2"><span id="elh_deudas_mig_plazo"><?php echo $deudas->mig_plazo->FldCaption() ?></span></td>
		<td data-name="mig_plazo"<?php echo $deudas->mig_plazo->CellAttributes() ?>>
<span id="el_deudas_mig_plazo" data-page="4">
<span<?php echo $deudas->mig_plazo->ViewAttributes() ?>>
<?php echo $deudas->mig_plazo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_dias_mora->Visible) { // mig_dias_mora ?>
	<tr id="r_mig_dias_mora">
		<td class="col-sm-2"><span id="elh_deudas_mig_dias_mora"><?php echo $deudas->mig_dias_mora->FldCaption() ?></span></td>
		<td data-name="mig_dias_mora"<?php echo $deudas->mig_dias_mora->CellAttributes() ?>>
<span id="el_deudas_mig_dias_mora" data-page="4">
<span<?php echo $deudas->mig_dias_mora->ViewAttributes() ?>>
<?php echo $deudas->mig_dias_mora->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_monto_desembolso->Visible) { // mig_monto_desembolso ?>
	<tr id="r_mig_monto_desembolso">
		<td class="col-sm-2"><span id="elh_deudas_mig_monto_desembolso"><?php echo $deudas->mig_monto_desembolso->FldCaption() ?></span></td>
		<td data-name="mig_monto_desembolso"<?php echo $deudas->mig_monto_desembolso->CellAttributes() ?>>
<span id="el_deudas_mig_monto_desembolso" data-page="4">
<span<?php echo $deudas->mig_monto_desembolso->ViewAttributes() ?>>
<?php echo $deudas->mig_monto_desembolso->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_total_cartera->Visible) { // mig_total_cartera ?>
	<tr id="r_mig_total_cartera">
		<td class="col-sm-2"><span id="elh_deudas_mig_total_cartera"><?php echo $deudas->mig_total_cartera->FldCaption() ?></span></td>
		<td data-name="mig_total_cartera"<?php echo $deudas->mig_total_cartera->CellAttributes() ?>>
<span id="el_deudas_mig_total_cartera" data-page="4">
<span<?php echo $deudas->mig_total_cartera->ViewAttributes() ?>>
<?php echo $deudas->mig_total_cartera->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_capital->Visible) { // mig_capital ?>
	<tr id="r_mig_capital">
		<td class="col-sm-2"><span id="elh_deudas_mig_capital"><?php echo $deudas->mig_capital->FldCaption() ?></span></td>
		<td data-name="mig_capital"<?php echo $deudas->mig_capital->CellAttributes() ?>>
<span id="el_deudas_mig_capital" data-page="4">
<span<?php echo $deudas->mig_capital->ViewAttributes() ?>>
<?php echo $deudas->mig_capital->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_intereses->Visible) { // mig_intereses ?>
	<tr id="r_mig_intereses">
		<td class="col-sm-2"><span id="elh_deudas_mig_intereses"><?php echo $deudas->mig_intereses->FldCaption() ?></span></td>
		<td data-name="mig_intereses"<?php echo $deudas->mig_intereses->CellAttributes() ?>>
<span id="el_deudas_mig_intereses" data-page="4">
<span<?php echo $deudas->mig_intereses->ViewAttributes() ?>>
<?php echo $deudas->mig_intereses->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_cargos_gastos->Visible) { // mig_cargos_gastos ?>
	<tr id="r_mig_cargos_gastos">
		<td class="col-sm-2"><span id="elh_deudas_mig_cargos_gastos"><?php echo $deudas->mig_cargos_gastos->FldCaption() ?></span></td>
		<td data-name="mig_cargos_gastos"<?php echo $deudas->mig_cargos_gastos->CellAttributes() ?>>
<span id="el_deudas_mig_cargos_gastos" data-page="4">
<span<?php echo $deudas->mig_cargos_gastos->ViewAttributes() ?>>
<?php echo $deudas->mig_cargos_gastos->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($deudas->mig_total_deuda->Visible) { // mig_total_deuda ?>
	<tr id="r_mig_total_deuda">
		<td class="col-sm-2"><span id="elh_deudas_mig_total_deuda"><?php echo $deudas->mig_total_deuda->FldCaption() ?></span></td>
		<td data-name="mig_total_deuda"<?php echo $deudas->mig_total_deuda->CellAttributes() ?>>
<span id="el_deudas_mig_total_deuda" data-page="4">
<span<?php echo $deudas->mig_total_deuda->ViewAttributes() ?>>
<?php echo $deudas->mig_total_deuda->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($deudas->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($deudas->Export == "") { ?>
	</div>
</div>
</div>
<?php } ?>
<?php
	if (in_array("deuda_persona", explode(",", $deudas->getCurrentDetailTable())) && $deuda_persona->DetailView) {
?>
<?php if ($deudas->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("deuda_persona", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "deuda_personagrid.php" ?>
<?php } ?>
</form>
<?php if ($deudas->Export == "") { ?>
<script type="text/javascript">
fdeudasview.Init();
</script>
<?php } ?>
<?php
$deudas_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($deudas->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

$('.ewDetailOption').hide();
$('.ewAddEditOption').hide();
</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$deudas_view->Page_Terminate();
?>
