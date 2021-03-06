<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "emailsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$emails_delete = NULL; // Initialize page object first

class cemails_delete extends cemails {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{A36EA07C-DB7F-422A-9088-B007545008C2}';

	// Table name
	var $TableName = 'emails';

	// Page object name
	var $PageObjName = 'emails_delete';

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

		// Table object (emails)
		if (!isset($GLOBALS["emails"]) || get_class($GLOBALS["emails"]) == "cemails") {
			$GLOBALS["emails"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["emails"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'emails', TRUE);

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

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("emailslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->Id->SetVisibility();
		$this->Id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->id_fuente->SetVisibility();
		$this->id_gestion->SetVisibility();
		$this->tipo_documento->SetVisibility();
		$this->no_documento->SetVisibility();
		$this->nombres->SetVisibility();
		$this->paterno->SetVisibility();
		$this->materno->SetVisibility();
		$this->email1->SetVisibility();
		$this->email2->SetVisibility();
		$this->email3->SetVisibility();
		$this->email4->SetVisibility();

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
		global $EW_EXPORT, $emails;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($emails);
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("emailslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in emails class, emailsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("emailslist.php"); // Return to list
			}
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
		$this->tipo_documento->setDbValue($row['tipo_documento']);
		$this->no_documento->setDbValue($row['no_documento']);
		$this->nombres->setDbValue($row['nombres']);
		$this->paterno->setDbValue($row['paterno']);
		$this->materno->setDbValue($row['materno']);
		$this->email1->setDbValue($row['email1']);
		$this->email2->setDbValue($row['email2']);
		$this->email3->setDbValue($row['email3']);
		$this->email4->setDbValue($row['email4']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['Id'] = NULL;
		$row['id_fuente'] = NULL;
		$row['id_gestion'] = NULL;
		$row['tipo_documento'] = NULL;
		$row['no_documento'] = NULL;
		$row['nombres'] = NULL;
		$row['paterno'] = NULL;
		$row['materno'] = NULL;
		$row['email1'] = NULL;
		$row['email2'] = NULL;
		$row['email3'] = NULL;
		$row['email4'] = NULL;
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
		$this->tipo_documento->DbValue = $row['tipo_documento'];
		$this->no_documento->DbValue = $row['no_documento'];
		$this->nombres->DbValue = $row['nombres'];
		$this->paterno->DbValue = $row['paterno'];
		$this->materno->DbValue = $row['materno'];
		$this->email1->DbValue = $row['email1'];
		$this->email2->DbValue = $row['email2'];
		$this->email3->DbValue = $row['email3'];
		$this->email4->DbValue = $row['email4'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Id
		// id_fuente
		// id_gestion
		// tipo_documento
		// no_documento
		// nombres
		// paterno
		// materno
		// email1
		// email2
		// email3
		// email4

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

		// email1
		$this->email1->ViewValue = $this->email1->CurrentValue;
		$this->email1->ViewCustomAttributes = "";

		// email2
		$this->email2->ViewValue = $this->email2->CurrentValue;
		$this->email2->ViewCustomAttributes = "";

		// email3
		$this->email3->ViewValue = $this->email3->CurrentValue;
		$this->email3->ViewCustomAttributes = "";

		// email4
		$this->email4->ViewValue = $this->email4->CurrentValue;
		$this->email4->ViewCustomAttributes = "";

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

			// email1
			$this->email1->LinkCustomAttributes = "";
			$this->email1->HrefValue = "";
			$this->email1->TooltipValue = "";

			// email2
			$this->email2->LinkCustomAttributes = "";
			$this->email2->HrefValue = "";
			$this->email2->TooltipValue = "";

			// email3
			$this->email3->LinkCustomAttributes = "";
			$this->email3->HrefValue = "";
			$this->email3->TooltipValue = "";

			// email4
			$this->email4->LinkCustomAttributes = "";
			$this->email4->HrefValue = "";
			$this->email4->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
		$conn->BeginTrans();

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
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("emailslist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($emails_delete)) $emails_delete = new cemails_delete();

// Page init
$emails_delete->Page_Init();

// Page main
$emails_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$emails_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = femailsdelete = new ew_Form("femailsdelete", "delete");

// Form_CustomValidate event
femailsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
femailsdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
femailsdelete.Lists["x_id_fuente"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"fuentes"};
femailsdelete.Lists["x_id_fuente"].Data = "<?php echo $emails_delete->id_fuente->LookupFilterQuery(FALSE, "delete") ?>";
femailsdelete.Lists["x_id_gestion"] = {"LinkField":"x_Id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"gestiones"};
femailsdelete.Lists["x_id_gestion"].Data = "<?php echo $emails_delete->id_gestion->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $emails_delete->ShowPageHeader(); ?>
<?php
$emails_delete->ShowMessage();
?>
<form name="femailsdelete" id="femailsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($emails_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $emails_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="emails">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($emails_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($emails->Id->Visible) { // Id ?>
		<th class="<?php echo $emails->Id->HeaderCellClass() ?>"><span id="elh_emails_Id" class="emails_Id"><?php echo $emails->Id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($emails->id_fuente->Visible) { // id_fuente ?>
		<th class="<?php echo $emails->id_fuente->HeaderCellClass() ?>"><span id="elh_emails_id_fuente" class="emails_id_fuente"><?php echo $emails->id_fuente->FldCaption() ?></span></th>
<?php } ?>
<?php if ($emails->id_gestion->Visible) { // id_gestion ?>
		<th class="<?php echo $emails->id_gestion->HeaderCellClass() ?>"><span id="elh_emails_id_gestion" class="emails_id_gestion"><?php echo $emails->id_gestion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($emails->tipo_documento->Visible) { // tipo_documento ?>
		<th class="<?php echo $emails->tipo_documento->HeaderCellClass() ?>"><span id="elh_emails_tipo_documento" class="emails_tipo_documento"><?php echo $emails->tipo_documento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($emails->no_documento->Visible) { // no_documento ?>
		<th class="<?php echo $emails->no_documento->HeaderCellClass() ?>"><span id="elh_emails_no_documento" class="emails_no_documento"><?php echo $emails->no_documento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($emails->nombres->Visible) { // nombres ?>
		<th class="<?php echo $emails->nombres->HeaderCellClass() ?>"><span id="elh_emails_nombres" class="emails_nombres"><?php echo $emails->nombres->FldCaption() ?></span></th>
<?php } ?>
<?php if ($emails->paterno->Visible) { // paterno ?>
		<th class="<?php echo $emails->paterno->HeaderCellClass() ?>"><span id="elh_emails_paterno" class="emails_paterno"><?php echo $emails->paterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($emails->materno->Visible) { // materno ?>
		<th class="<?php echo $emails->materno->HeaderCellClass() ?>"><span id="elh_emails_materno" class="emails_materno"><?php echo $emails->materno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($emails->email1->Visible) { // email1 ?>
		<th class="<?php echo $emails->email1->HeaderCellClass() ?>"><span id="elh_emails_email1" class="emails_email1"><?php echo $emails->email1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($emails->email2->Visible) { // email2 ?>
		<th class="<?php echo $emails->email2->HeaderCellClass() ?>"><span id="elh_emails_email2" class="emails_email2"><?php echo $emails->email2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($emails->email3->Visible) { // email3 ?>
		<th class="<?php echo $emails->email3->HeaderCellClass() ?>"><span id="elh_emails_email3" class="emails_email3"><?php echo $emails->email3->FldCaption() ?></span></th>
<?php } ?>
<?php if ($emails->email4->Visible) { // email4 ?>
		<th class="<?php echo $emails->email4->HeaderCellClass() ?>"><span id="elh_emails_email4" class="emails_email4"><?php echo $emails->email4->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$emails_delete->RecCnt = 0;
$i = 0;
while (!$emails_delete->Recordset->EOF) {
	$emails_delete->RecCnt++;
	$emails_delete->RowCnt++;

	// Set row properties
	$emails->ResetAttrs();
	$emails->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$emails_delete->LoadRowValues($emails_delete->Recordset);

	// Render row
	$emails_delete->RenderRow();
?>
	<tr<?php echo $emails->RowAttributes() ?>>
<?php if ($emails->Id->Visible) { // Id ?>
		<td<?php echo $emails->Id->CellAttributes() ?>>
<span id="el<?php echo $emails_delete->RowCnt ?>_emails_Id" class="emails_Id">
<span<?php echo $emails->Id->ViewAttributes() ?>>
<?php echo $emails->Id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($emails->id_fuente->Visible) { // id_fuente ?>
		<td<?php echo $emails->id_fuente->CellAttributes() ?>>
<span id="el<?php echo $emails_delete->RowCnt ?>_emails_id_fuente" class="emails_id_fuente">
<span<?php echo $emails->id_fuente->ViewAttributes() ?>>
<?php echo $emails->id_fuente->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($emails->id_gestion->Visible) { // id_gestion ?>
		<td<?php echo $emails->id_gestion->CellAttributes() ?>>
<span id="el<?php echo $emails_delete->RowCnt ?>_emails_id_gestion" class="emails_id_gestion">
<span<?php echo $emails->id_gestion->ViewAttributes() ?>>
<?php echo $emails->id_gestion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($emails->tipo_documento->Visible) { // tipo_documento ?>
		<td<?php echo $emails->tipo_documento->CellAttributes() ?>>
<span id="el<?php echo $emails_delete->RowCnt ?>_emails_tipo_documento" class="emails_tipo_documento">
<span<?php echo $emails->tipo_documento->ViewAttributes() ?>>
<?php echo $emails->tipo_documento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($emails->no_documento->Visible) { // no_documento ?>
		<td<?php echo $emails->no_documento->CellAttributes() ?>>
<span id="el<?php echo $emails_delete->RowCnt ?>_emails_no_documento" class="emails_no_documento">
<span<?php echo $emails->no_documento->ViewAttributes() ?>>
<?php echo $emails->no_documento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($emails->nombres->Visible) { // nombres ?>
		<td<?php echo $emails->nombres->CellAttributes() ?>>
<span id="el<?php echo $emails_delete->RowCnt ?>_emails_nombres" class="emails_nombres">
<span<?php echo $emails->nombres->ViewAttributes() ?>>
<?php echo $emails->nombres->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($emails->paterno->Visible) { // paterno ?>
		<td<?php echo $emails->paterno->CellAttributes() ?>>
<span id="el<?php echo $emails_delete->RowCnt ?>_emails_paterno" class="emails_paterno">
<span<?php echo $emails->paterno->ViewAttributes() ?>>
<?php echo $emails->paterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($emails->materno->Visible) { // materno ?>
		<td<?php echo $emails->materno->CellAttributes() ?>>
<span id="el<?php echo $emails_delete->RowCnt ?>_emails_materno" class="emails_materno">
<span<?php echo $emails->materno->ViewAttributes() ?>>
<?php echo $emails->materno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($emails->email1->Visible) { // email1 ?>
		<td<?php echo $emails->email1->CellAttributes() ?>>
<span id="el<?php echo $emails_delete->RowCnt ?>_emails_email1" class="emails_email1">
<span<?php echo $emails->email1->ViewAttributes() ?>>
<?php echo $emails->email1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($emails->email2->Visible) { // email2 ?>
		<td<?php echo $emails->email2->CellAttributes() ?>>
<span id="el<?php echo $emails_delete->RowCnt ?>_emails_email2" class="emails_email2">
<span<?php echo $emails->email2->ViewAttributes() ?>>
<?php echo $emails->email2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($emails->email3->Visible) { // email3 ?>
		<td<?php echo $emails->email3->CellAttributes() ?>>
<span id="el<?php echo $emails_delete->RowCnt ?>_emails_email3" class="emails_email3">
<span<?php echo $emails->email3->ViewAttributes() ?>>
<?php echo $emails->email3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($emails->email4->Visible) { // email4 ?>
		<td<?php echo $emails->email4->CellAttributes() ?>>
<span id="el<?php echo $emails_delete->RowCnt ?>_emails_email4" class="emails_email4">
<span<?php echo $emails->email4->ViewAttributes() ?>>
<?php echo $emails->email4->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$emails_delete->Recordset->MoveNext();
}
$emails_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $emails_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
femailsdelete.Init();
</script>
<?php
$emails_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$emails_delete->Page_Terminate();
?>
