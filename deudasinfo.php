<?php

// Global variable for table object
$deudas = NULL;

//
// Table class for deudas
//
class cdeudas extends cTable {
	var $Id;
	var $cuenta;
	var $id_cliente;
	var $id_ciudad;
	var $id_agente;
	var $id_estadodeuda;
	var $mig_codigo_deuda;
	var $mig_tipo_operacion;
	var $mig_fecha_desembolso;
	var $mig_fecha_estado;
	var $mig_anios_castigo;
	var $mig_tipo_garantia;
	var $mig_real;
	var $mig_actividad_economica;
	var $mig_agencia;
	var $mig_no_juicio;
	var $mig_nombre_abogado;
	var $mig_fase_procesal;
	var $mig_moneda;
	var $mig_tasa;
	var $mig_plazo;
	var $mig_dias_mora;
	var $mig_monto_desembolso;
	var $mig_total_cartera;
	var $mig_capital;
	var $mig_intereses;
	var $mig_cargos_gastos;
	var $mig_total_deuda;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'deudas';
		$this->TableName = 'deudas';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`deudas`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// Id
		$this->Id = new cField('deudas', 'deudas', 'x_Id', 'Id', '`Id`', '`Id`', 3, -1, FALSE, '`Id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->Id->Sortable = TRUE; // Allow sort
		$this->Id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Id'] = &$this->Id;

		// cuenta
		$this->cuenta = new cField('deudas', 'deudas', 'x_cuenta', 'cuenta', '(SELECT CONCAT(c.codigo,\' - \',deudas.mig_codigo_deuda) FROM cuentas c WHERE c.Id=id_cliente)', '(SELECT CONCAT(c.codigo,\' - \',deudas.mig_codigo_deuda) FROM cuentas c WHERE c.Id=id_cliente)', 201, -1, FALSE, '(SELECT CONCAT(c.codigo,\' - \',deudas.mig_codigo_deuda) FROM cuentas c WHERE c.Id=id_cliente)', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->cuenta->FldIsCustom = TRUE; // Custom field
		$this->cuenta->Sortable = FALSE; // Allow sort
		$this->cuenta->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->cuenta->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['cuenta'] = &$this->cuenta;

		// id_cliente
		$this->id_cliente = new cField('deudas', 'deudas', 'x_id_cliente', 'id_cliente', '`id_cliente`', '`id_cliente`', 3, -1, FALSE, '`id_cliente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_cliente->Sortable = TRUE; // Allow sort
		$this->id_cliente->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_cliente->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_cliente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_cliente'] = &$this->id_cliente;

		// id_ciudad
		$this->id_ciudad = new cField('deudas', 'deudas', 'x_id_ciudad', 'id_ciudad', '`id_ciudad`', '`id_ciudad`', 3, -1, FALSE, '`id_ciudad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_ciudad->Sortable = TRUE; // Allow sort
		$this->id_ciudad->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_ciudad->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_ciudad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_ciudad'] = &$this->id_ciudad;

		// id_agente
		$this->id_agente = new cField('deudas', 'deudas', 'x_id_agente', 'id_agente', '`id_agente`', '`id_agente`', 3, -1, FALSE, '`id_agente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_agente->Sortable = TRUE; // Allow sort
		$this->id_agente->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_agente->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_agente->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_agente'] = &$this->id_agente;

		// id_estadodeuda
		$this->id_estadodeuda = new cField('deudas', 'deudas', 'x_id_estadodeuda', 'id_estadodeuda', '`id_estadodeuda`', '`id_estadodeuda`', 3, -1, FALSE, '`id_estadodeuda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->id_estadodeuda->Sortable = TRUE; // Allow sort
		$this->id_estadodeuda->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_estadodeuda->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_estadodeuda->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_estadodeuda'] = &$this->id_estadodeuda;

		// mig_codigo_deuda
		$this->mig_codigo_deuda = new cField('deudas', 'deudas', 'x_mig_codigo_deuda', 'mig_codigo_deuda', '`mig_codigo_deuda`', '`mig_codigo_deuda`', 200, -1, FALSE, '`mig_codigo_deuda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_codigo_deuda->Sortable = TRUE; // Allow sort
		$this->fields['mig_codigo_deuda'] = &$this->mig_codigo_deuda;

		// mig_tipo_operacion
		$this->mig_tipo_operacion = new cField('deudas', 'deudas', 'x_mig_tipo_operacion', 'mig_tipo_operacion', '`mig_tipo_operacion`', '`mig_tipo_operacion`', 200, -1, FALSE, '`mig_tipo_operacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_tipo_operacion->Sortable = TRUE; // Allow sort
		$this->fields['mig_tipo_operacion'] = &$this->mig_tipo_operacion;

		// mig_fecha_desembolso
		$this->mig_fecha_desembolso = new cField('deudas', 'deudas', 'x_mig_fecha_desembolso', 'mig_fecha_desembolso', '`mig_fecha_desembolso`', ew_CastDateFieldForLike('`mig_fecha_desembolso`', 7, "DB"), 135, 7, FALSE, '`mig_fecha_desembolso`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_fecha_desembolso->Sortable = TRUE; // Allow sort
		$this->mig_fecha_desembolso->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['mig_fecha_desembolso'] = &$this->mig_fecha_desembolso;

		// mig_fecha_estado
		$this->mig_fecha_estado = new cField('deudas', 'deudas', 'x_mig_fecha_estado', 'mig_fecha_estado', '`mig_fecha_estado`', ew_CastDateFieldForLike('`mig_fecha_estado`', 7, "DB"), 135, 7, FALSE, '`mig_fecha_estado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_fecha_estado->Sortable = TRUE; // Allow sort
		$this->mig_fecha_estado->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['mig_fecha_estado'] = &$this->mig_fecha_estado;

		// mig_anios_castigo
		$this->mig_anios_castigo = new cField('deudas', 'deudas', 'x_mig_anios_castigo', 'mig_anios_castigo', '`mig_anios_castigo`', '`mig_anios_castigo`', 3, -1, FALSE, '`mig_anios_castigo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_anios_castigo->Sortable = TRUE; // Allow sort
		$this->mig_anios_castigo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['mig_anios_castigo'] = &$this->mig_anios_castigo;

		// mig_tipo_garantia
		$this->mig_tipo_garantia = new cField('deudas', 'deudas', 'x_mig_tipo_garantia', 'mig_tipo_garantia', '`mig_tipo_garantia`', '`mig_tipo_garantia`', 200, -1, FALSE, '`mig_tipo_garantia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_tipo_garantia->Sortable = TRUE; // Allow sort
		$this->fields['mig_tipo_garantia'] = &$this->mig_tipo_garantia;

		// mig_real
		$this->mig_real = new cField('deudas', 'deudas', 'x_mig_real', 'mig_real', '`mig_real`', '`mig_real`', 200, -1, FALSE, '`mig_real`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_real->Sortable = TRUE; // Allow sort
		$this->fields['mig_real'] = &$this->mig_real;

		// mig_actividad_economica
		$this->mig_actividad_economica = new cField('deudas', 'deudas', 'x_mig_actividad_economica', 'mig_actividad_economica', '`mig_actividad_economica`', '`mig_actividad_economica`', 200, -1, FALSE, '`mig_actividad_economica`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_actividad_economica->Sortable = TRUE; // Allow sort
		$this->fields['mig_actividad_economica'] = &$this->mig_actividad_economica;

		// mig_agencia
		$this->mig_agencia = new cField('deudas', 'deudas', 'x_mig_agencia', 'mig_agencia', '`mig_agencia`', '`mig_agencia`', 200, -1, FALSE, '`mig_agencia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_agencia->Sortable = TRUE; // Allow sort
		$this->fields['mig_agencia'] = &$this->mig_agencia;

		// mig_no_juicio
		$this->mig_no_juicio = new cField('deudas', 'deudas', 'x_mig_no_juicio', 'mig_no_juicio', '`mig_no_juicio`', '`mig_no_juicio`', 200, -1, FALSE, '`mig_no_juicio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_no_juicio->Sortable = TRUE; // Allow sort
		$this->fields['mig_no_juicio'] = &$this->mig_no_juicio;

		// mig_nombre_abogado
		$this->mig_nombre_abogado = new cField('deudas', 'deudas', 'x_mig_nombre_abogado', 'mig_nombre_abogado', '`mig_nombre_abogado`', '`mig_nombre_abogado`', 200, -1, FALSE, '`mig_nombre_abogado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_nombre_abogado->Sortable = TRUE; // Allow sort
		$this->fields['mig_nombre_abogado'] = &$this->mig_nombre_abogado;

		// mig_fase_procesal
		$this->mig_fase_procesal = new cField('deudas', 'deudas', 'x_mig_fase_procesal', 'mig_fase_procesal', '`mig_fase_procesal`', '`mig_fase_procesal`', 200, -1, FALSE, '`mig_fase_procesal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_fase_procesal->Sortable = TRUE; // Allow sort
		$this->fields['mig_fase_procesal'] = &$this->mig_fase_procesal;

		// mig_moneda
		$this->mig_moneda = new cField('deudas', 'deudas', 'x_mig_moneda', 'mig_moneda', '`mig_moneda`', '`mig_moneda`', 200, -1, FALSE, '`mig_moneda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->mig_moneda->Sortable = TRUE; // Allow sort
		$this->mig_moneda->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->mig_moneda->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->mig_moneda->OptionCount = 2;
		$this->fields['mig_moneda'] = &$this->mig_moneda;

		// mig_tasa
		$this->mig_tasa = new cField('deudas', 'deudas', 'x_mig_tasa', 'mig_tasa', '`mig_tasa`', '`mig_tasa`', 131, -1, FALSE, '`mig_tasa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_tasa->Sortable = TRUE; // Allow sort
		$this->mig_tasa->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['mig_tasa'] = &$this->mig_tasa;

		// mig_plazo
		$this->mig_plazo = new cField('deudas', 'deudas', 'x_mig_plazo', 'mig_plazo', '`mig_plazo`', '`mig_plazo`', 131, -1, FALSE, '`mig_plazo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_plazo->Sortable = TRUE; // Allow sort
		$this->mig_plazo->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['mig_plazo'] = &$this->mig_plazo;

		// mig_dias_mora
		$this->mig_dias_mora = new cField('deudas', 'deudas', 'x_mig_dias_mora', 'mig_dias_mora', '`mig_dias_mora`', '`mig_dias_mora`', 131, -1, FALSE, '`mig_dias_mora`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_dias_mora->Sortable = TRUE; // Allow sort
		$this->mig_dias_mora->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['mig_dias_mora'] = &$this->mig_dias_mora;

		// mig_monto_desembolso
		$this->mig_monto_desembolso = new cField('deudas', 'deudas', 'x_mig_monto_desembolso', 'mig_monto_desembolso', '`mig_monto_desembolso`', '`mig_monto_desembolso`', 131, -1, FALSE, '`mig_monto_desembolso`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_monto_desembolso->Sortable = TRUE; // Allow sort
		$this->mig_monto_desembolso->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['mig_monto_desembolso'] = &$this->mig_monto_desembolso;

		// mig_total_cartera
		$this->mig_total_cartera = new cField('deudas', 'deudas', 'x_mig_total_cartera', 'mig_total_cartera', '`mig_total_cartera`', '`mig_total_cartera`', 131, -1, FALSE, '`mig_total_cartera`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_total_cartera->Sortable = TRUE; // Allow sort
		$this->mig_total_cartera->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['mig_total_cartera'] = &$this->mig_total_cartera;

		// mig_capital
		$this->mig_capital = new cField('deudas', 'deudas', 'x_mig_capital', 'mig_capital', '`mig_capital`', '`mig_capital`', 131, -1, FALSE, '`mig_capital`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_capital->Sortable = TRUE; // Allow sort
		$this->mig_capital->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['mig_capital'] = &$this->mig_capital;

		// mig_intereses
		$this->mig_intereses = new cField('deudas', 'deudas', 'x_mig_intereses', 'mig_intereses', '`mig_intereses`', '`mig_intereses`', 131, -1, FALSE, '`mig_intereses`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_intereses->Sortable = TRUE; // Allow sort
		$this->mig_intereses->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['mig_intereses'] = &$this->mig_intereses;

		// mig_cargos_gastos
		$this->mig_cargos_gastos = new cField('deudas', 'deudas', 'x_mig_cargos_gastos', 'mig_cargos_gastos', '`mig_cargos_gastos`', '`mig_cargos_gastos`', 131, -1, FALSE, '`mig_cargos_gastos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_cargos_gastos->Sortable = TRUE; // Allow sort
		$this->mig_cargos_gastos->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['mig_cargos_gastos'] = &$this->mig_cargos_gastos;

		// mig_total_deuda
		$this->mig_total_deuda = new cField('deudas', 'deudas', 'x_mig_total_deuda', 'mig_total_deuda', '`mig_total_deuda`', '`mig_total_deuda`', 131, -1, FALSE, '`mig_total_deuda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mig_total_deuda->Sortable = TRUE; // Allow sort
		$this->mig_total_deuda->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['mig_total_deuda'] = &$this->mig_total_deuda;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $this->LeftColumnClass);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "cuentas") {
			if ($this->id_cliente->getSessionValue() <> "")
				$sMasterFilter .= "`Id`=" . ew_QuotedValue($this->id_cliente->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "users") {
			if ($this->id_agente->getSessionValue() <> "")
				$sMasterFilter .= "`id_user`=" . ew_QuotedValue($this->id_agente->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "cuentas") {
			if ($this->id_cliente->getSessionValue() <> "")
				$sDetailFilter .= "`id_cliente`=" . ew_QuotedValue($this->id_cliente->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		if ($this->getCurrentMasterTable() == "users") {
			if ($this->id_agente->getSessionValue() <> "")
				$sDetailFilter .= "`id_agente`=" . ew_QuotedValue($this->id_agente->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_cuentas() {
		return "`Id`=@Id@";
	}

	// Detail filter
	function SqlDetailFilter_cuentas() {
		return "`id_cliente`=@id_cliente@";
	}

	// Master filter
	function SqlMasterFilter_users() {
		return "`id_user`=@id_user@";
	}

	// Detail filter
	function SqlDetailFilter_users() {
		return "`id_agente`=@id_agente@";
	}

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "deuda_persona") {
			$sDetailUrl = $GLOBALS["deuda_persona"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "deudaslist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`deudas`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT *, (SELECT CONCAT(c.codigo,' - ',deudas.mig_codigo_deuda) FROM cuentas c WHERE c.Id=id_cliente) AS `cuenta` FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$sSql = $this->ListSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->Id->setDbValue($conn->Insert_ID());
			$rs['Id'] = $this->Id->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('Id', $rs))
				ew_AddFilter($where, ew_QuotedName('Id', $this->DBID) . '=' . ew_QuotedValue($rs['Id'], $this->Id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`Id` = @Id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->Id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->Id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@Id@", ew_AdjustSql($this->Id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "deudaslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "deudasview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "deudasedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "deudasadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "deudaslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("deudasview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("deudasview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "deudasadd.php?" . $this->UrlParm($parm);
		else
			$url = "deudasadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("deudasedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("deudasedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("deudasadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("deudasadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("deudasdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		if ($this->getCurrentMasterTable() == "cuentas" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_Id=" . urlencode($this->id_cliente->CurrentValue);
		}
		if ($this->getCurrentMasterTable() == "users" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_id_user=" . urlencode($this->id_agente->CurrentValue);
		}
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "Id:" . ew_VarToJson($this->Id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->Id->CurrentValue)) {
			$sUrl .= "Id=" . urlencode($this->Id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["Id"]))
				$arKeys[] = $_POST["Id"];
			elseif (isset($_GET["Id"]))
				$arKeys[] = $_GET["Id"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->Id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->Id->setDbValue($rs->fields('Id'));
		$this->cuenta->setDbValue($rs->fields('cuenta'));
		$this->id_cliente->setDbValue($rs->fields('id_cliente'));
		$this->id_ciudad->setDbValue($rs->fields('id_ciudad'));
		$this->id_agente->setDbValue($rs->fields('id_agente'));
		$this->id_estadodeuda->setDbValue($rs->fields('id_estadodeuda'));
		$this->mig_codigo_deuda->setDbValue($rs->fields('mig_codigo_deuda'));
		$this->mig_tipo_operacion->setDbValue($rs->fields('mig_tipo_operacion'));
		$this->mig_fecha_desembolso->setDbValue($rs->fields('mig_fecha_desembolso'));
		$this->mig_fecha_estado->setDbValue($rs->fields('mig_fecha_estado'));
		$this->mig_anios_castigo->setDbValue($rs->fields('mig_anios_castigo'));
		$this->mig_tipo_garantia->setDbValue($rs->fields('mig_tipo_garantia'));
		$this->mig_real->setDbValue($rs->fields('mig_real'));
		$this->mig_actividad_economica->setDbValue($rs->fields('mig_actividad_economica'));
		$this->mig_agencia->setDbValue($rs->fields('mig_agencia'));
		$this->mig_no_juicio->setDbValue($rs->fields('mig_no_juicio'));
		$this->mig_nombre_abogado->setDbValue($rs->fields('mig_nombre_abogado'));
		$this->mig_fase_procesal->setDbValue($rs->fields('mig_fase_procesal'));
		$this->mig_moneda->setDbValue($rs->fields('mig_moneda'));
		$this->mig_tasa->setDbValue($rs->fields('mig_tasa'));
		$this->mig_plazo->setDbValue($rs->fields('mig_plazo'));
		$this->mig_dias_mora->setDbValue($rs->fields('mig_dias_mora'));
		$this->mig_monto_desembolso->setDbValue($rs->fields('mig_monto_desembolso'));
		$this->mig_total_cartera->setDbValue($rs->fields('mig_total_cartera'));
		$this->mig_capital->setDbValue($rs->fields('mig_capital'));
		$this->mig_intereses->setDbValue($rs->fields('mig_intereses'));
		$this->mig_cargos_gastos->setDbValue($rs->fields('mig_cargos_gastos'));
		$this->mig_total_deuda->setDbValue($rs->fields('mig_total_deuda'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
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
		// Id

		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// cuenta
		$this->cuenta->ViewCustomAttributes = "";

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

		// cuenta
		$this->cuenta->LinkCustomAttributes = "";
		$this->cuenta->HrefValue = "";
		$this->cuenta->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// Id
		$this->Id->EditAttrs["class"] = "form-control";
		$this->Id->EditCustomAttributes = "";
		$this->Id->EditValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// cuenta
		$this->cuenta->EditAttrs["class"] = "form-control";
		$this->cuenta->EditCustomAttributes = "";

		// id_cliente
		$this->id_cliente->EditAttrs["class"] = "form-control";
		$this->id_cliente->EditCustomAttributes = "";
		if ($this->id_cliente->getSessionValue() <> "") {
			$this->id_cliente->CurrentValue = $this->id_cliente->getSessionValue();
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
		}

		// id_ciudad
		$this->id_ciudad->EditAttrs["class"] = "form-control";
		$this->id_ciudad->EditCustomAttributes = "";

		// id_agente
		$this->id_agente->EditAttrs["class"] = "form-control";
		$this->id_agente->EditCustomAttributes = "";
		if ($this->id_agente->getSessionValue() <> "") {
			$this->id_agente->CurrentValue = $this->id_agente->getSessionValue();
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
		}

		// id_estadodeuda
		$this->id_estadodeuda->EditAttrs["class"] = "form-control";
		$this->id_estadodeuda->EditCustomAttributes = "";

		// mig_codigo_deuda
		$this->mig_codigo_deuda->EditAttrs["class"] = "form-control";
		$this->mig_codigo_deuda->EditCustomAttributes = "";
		$this->mig_codigo_deuda->EditValue = $this->mig_codigo_deuda->CurrentValue;
		$this->mig_codigo_deuda->PlaceHolder = ew_RemoveHtml($this->mig_codigo_deuda->FldCaption());

		// mig_tipo_operacion
		$this->mig_tipo_operacion->EditAttrs["class"] = "form-control";
		$this->mig_tipo_operacion->EditCustomAttributes = "";
		$this->mig_tipo_operacion->EditValue = $this->mig_tipo_operacion->CurrentValue;
		$this->mig_tipo_operacion->PlaceHolder = ew_RemoveHtml($this->mig_tipo_operacion->FldCaption());

		// mig_fecha_desembolso
		$this->mig_fecha_desembolso->EditAttrs["class"] = "form-control";
		$this->mig_fecha_desembolso->EditCustomAttributes = "";
		$this->mig_fecha_desembolso->EditValue = ew_FormatDateTime($this->mig_fecha_desembolso->CurrentValue, 7);
		$this->mig_fecha_desembolso->PlaceHolder = ew_RemoveHtml($this->mig_fecha_desembolso->FldCaption());

		// mig_fecha_estado
		$this->mig_fecha_estado->EditAttrs["class"] = "form-control";
		$this->mig_fecha_estado->EditCustomAttributes = "";
		$this->mig_fecha_estado->EditValue = ew_FormatDateTime($this->mig_fecha_estado->CurrentValue, 7);
		$this->mig_fecha_estado->PlaceHolder = ew_RemoveHtml($this->mig_fecha_estado->FldCaption());

		// mig_anios_castigo
		$this->mig_anios_castigo->EditAttrs["class"] = "form-control";
		$this->mig_anios_castigo->EditCustomAttributes = "";
		$this->mig_anios_castigo->EditValue = $this->mig_anios_castigo->CurrentValue;
		$this->mig_anios_castigo->PlaceHolder = ew_RemoveHtml($this->mig_anios_castigo->FldCaption());

		// mig_tipo_garantia
		$this->mig_tipo_garantia->EditAttrs["class"] = "form-control";
		$this->mig_tipo_garantia->EditCustomAttributes = "";
		$this->mig_tipo_garantia->EditValue = $this->mig_tipo_garantia->CurrentValue;
		$this->mig_tipo_garantia->PlaceHolder = ew_RemoveHtml($this->mig_tipo_garantia->FldCaption());

		// mig_real
		$this->mig_real->EditAttrs["class"] = "form-control";
		$this->mig_real->EditCustomAttributes = "";
		$this->mig_real->EditValue = $this->mig_real->CurrentValue;
		$this->mig_real->PlaceHolder = ew_RemoveHtml($this->mig_real->FldCaption());

		// mig_actividad_economica
		$this->mig_actividad_economica->EditAttrs["class"] = "form-control";
		$this->mig_actividad_economica->EditCustomAttributes = "";
		$this->mig_actividad_economica->EditValue = $this->mig_actividad_economica->CurrentValue;
		$this->mig_actividad_economica->PlaceHolder = ew_RemoveHtml($this->mig_actividad_economica->FldCaption());

		// mig_agencia
		$this->mig_agencia->EditAttrs["class"] = "form-control";
		$this->mig_agencia->EditCustomAttributes = "";
		$this->mig_agencia->EditValue = $this->mig_agencia->CurrentValue;
		$this->mig_agencia->PlaceHolder = ew_RemoveHtml($this->mig_agencia->FldCaption());

		// mig_no_juicio
		$this->mig_no_juicio->EditAttrs["class"] = "form-control";
		$this->mig_no_juicio->EditCustomAttributes = "";
		$this->mig_no_juicio->EditValue = $this->mig_no_juicio->CurrentValue;
		$this->mig_no_juicio->PlaceHolder = ew_RemoveHtml($this->mig_no_juicio->FldCaption());

		// mig_nombre_abogado
		$this->mig_nombre_abogado->EditAttrs["class"] = "form-control";
		$this->mig_nombre_abogado->EditCustomAttributes = "";
		$this->mig_nombre_abogado->EditValue = $this->mig_nombre_abogado->CurrentValue;
		$this->mig_nombre_abogado->PlaceHolder = ew_RemoveHtml($this->mig_nombre_abogado->FldCaption());

		// mig_fase_procesal
		$this->mig_fase_procesal->EditAttrs["class"] = "form-control";
		$this->mig_fase_procesal->EditCustomAttributes = "";
		$this->mig_fase_procesal->EditValue = $this->mig_fase_procesal->CurrentValue;
		$this->mig_fase_procesal->PlaceHolder = ew_RemoveHtml($this->mig_fase_procesal->FldCaption());

		// mig_moneda
		$this->mig_moneda->EditAttrs["class"] = "form-control";
		$this->mig_moneda->EditCustomAttributes = "";
		$this->mig_moneda->EditValue = $this->mig_moneda->Options(TRUE);

		// mig_tasa
		$this->mig_tasa->EditAttrs["class"] = "form-control";
		$this->mig_tasa->EditCustomAttributes = "";
		$this->mig_tasa->EditValue = $this->mig_tasa->CurrentValue;
		$this->mig_tasa->PlaceHolder = ew_RemoveHtml($this->mig_tasa->FldCaption());
		if (strval($this->mig_tasa->EditValue) <> "" && is_numeric($this->mig_tasa->EditValue)) $this->mig_tasa->EditValue = ew_FormatNumber($this->mig_tasa->EditValue, -2, 0, -2, 0);

		// mig_plazo
		$this->mig_plazo->EditAttrs["class"] = "form-control";
		$this->mig_plazo->EditCustomAttributes = "";
		$this->mig_plazo->EditValue = $this->mig_plazo->CurrentValue;
		$this->mig_plazo->PlaceHolder = ew_RemoveHtml($this->mig_plazo->FldCaption());
		if (strval($this->mig_plazo->EditValue) <> "" && is_numeric($this->mig_plazo->EditValue)) $this->mig_plazo->EditValue = ew_FormatNumber($this->mig_plazo->EditValue, -2, 0, -2, 0);

		// mig_dias_mora
		$this->mig_dias_mora->EditAttrs["class"] = "form-control";
		$this->mig_dias_mora->EditCustomAttributes = "";
		$this->mig_dias_mora->EditValue = $this->mig_dias_mora->CurrentValue;
		$this->mig_dias_mora->PlaceHolder = ew_RemoveHtml($this->mig_dias_mora->FldCaption());
		if (strval($this->mig_dias_mora->EditValue) <> "" && is_numeric($this->mig_dias_mora->EditValue)) $this->mig_dias_mora->EditValue = ew_FormatNumber($this->mig_dias_mora->EditValue, -2, 0, -2, 0);

		// mig_monto_desembolso
		$this->mig_monto_desembolso->EditAttrs["class"] = "form-control";
		$this->mig_monto_desembolso->EditCustomAttributes = "";
		$this->mig_monto_desembolso->EditValue = $this->mig_monto_desembolso->CurrentValue;
		$this->mig_monto_desembolso->PlaceHolder = ew_RemoveHtml($this->mig_monto_desembolso->FldCaption());
		if (strval($this->mig_monto_desembolso->EditValue) <> "" && is_numeric($this->mig_monto_desembolso->EditValue)) $this->mig_monto_desembolso->EditValue = ew_FormatNumber($this->mig_monto_desembolso->EditValue, -2, 0, -2, 0);

		// mig_total_cartera
		$this->mig_total_cartera->EditAttrs["class"] = "form-control";
		$this->mig_total_cartera->EditCustomAttributes = "";
		$this->mig_total_cartera->EditValue = $this->mig_total_cartera->CurrentValue;
		$this->mig_total_cartera->PlaceHolder = ew_RemoveHtml($this->mig_total_cartera->FldCaption());
		if (strval($this->mig_total_cartera->EditValue) <> "" && is_numeric($this->mig_total_cartera->EditValue)) $this->mig_total_cartera->EditValue = ew_FormatNumber($this->mig_total_cartera->EditValue, -2, 0, -2, 0);

		// mig_capital
		$this->mig_capital->EditAttrs["class"] = "form-control";
		$this->mig_capital->EditCustomAttributes = "";
		$this->mig_capital->EditValue = $this->mig_capital->CurrentValue;
		$this->mig_capital->PlaceHolder = ew_RemoveHtml($this->mig_capital->FldCaption());
		if (strval($this->mig_capital->EditValue) <> "" && is_numeric($this->mig_capital->EditValue)) $this->mig_capital->EditValue = ew_FormatNumber($this->mig_capital->EditValue, -2, 0, -2, 0);

		// mig_intereses
		$this->mig_intereses->EditAttrs["class"] = "form-control";
		$this->mig_intereses->EditCustomAttributes = "";
		$this->mig_intereses->EditValue = $this->mig_intereses->CurrentValue;
		$this->mig_intereses->PlaceHolder = ew_RemoveHtml($this->mig_intereses->FldCaption());
		if (strval($this->mig_intereses->EditValue) <> "" && is_numeric($this->mig_intereses->EditValue)) $this->mig_intereses->EditValue = ew_FormatNumber($this->mig_intereses->EditValue, -2, 0, -2, 0);

		// mig_cargos_gastos
		$this->mig_cargos_gastos->EditAttrs["class"] = "form-control";
		$this->mig_cargos_gastos->EditCustomAttributes = "";
		$this->mig_cargos_gastos->EditValue = $this->mig_cargos_gastos->CurrentValue;
		$this->mig_cargos_gastos->PlaceHolder = ew_RemoveHtml($this->mig_cargos_gastos->FldCaption());
		if (strval($this->mig_cargos_gastos->EditValue) <> "" && is_numeric($this->mig_cargos_gastos->EditValue)) $this->mig_cargos_gastos->EditValue = ew_FormatNumber($this->mig_cargos_gastos->EditValue, -2, 0, -2, 0);

		// mig_total_deuda
		$this->mig_total_deuda->EditAttrs["class"] = "form-control";
		$this->mig_total_deuda->EditCustomAttributes = "";
		$this->mig_total_deuda->EditValue = $this->mig_total_deuda->CurrentValue;
		$this->mig_total_deuda->PlaceHolder = ew_RemoveHtml($this->mig_total_deuda->FldCaption());
		if (strval($this->mig_total_deuda->EditValue) <> "" && is_numeric($this->mig_total_deuda->EditValue)) $this->mig_total_deuda->EditValue = ew_FormatNumber($this->mig_total_deuda->EditValue, -2, 0, -2, 0);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->Id->Exportable) $Doc->ExportCaption($this->Id);
					if ($this->id_cliente->Exportable) $Doc->ExportCaption($this->id_cliente);
					if ($this->id_ciudad->Exportable) $Doc->ExportCaption($this->id_ciudad);
					if ($this->id_agente->Exportable) $Doc->ExportCaption($this->id_agente);
					if ($this->id_estadodeuda->Exportable) $Doc->ExportCaption($this->id_estadodeuda);
					if ($this->mig_codigo_deuda->Exportable) $Doc->ExportCaption($this->mig_codigo_deuda);
					if ($this->mig_tipo_operacion->Exportable) $Doc->ExportCaption($this->mig_tipo_operacion);
					if ($this->mig_fecha_desembolso->Exportable) $Doc->ExportCaption($this->mig_fecha_desembolso);
					if ($this->mig_fecha_estado->Exportable) $Doc->ExportCaption($this->mig_fecha_estado);
					if ($this->mig_anios_castigo->Exportable) $Doc->ExportCaption($this->mig_anios_castigo);
					if ($this->mig_tipo_garantia->Exportable) $Doc->ExportCaption($this->mig_tipo_garantia);
					if ($this->mig_real->Exportable) $Doc->ExportCaption($this->mig_real);
					if ($this->mig_actividad_economica->Exportable) $Doc->ExportCaption($this->mig_actividad_economica);
					if ($this->mig_agencia->Exportable) $Doc->ExportCaption($this->mig_agencia);
					if ($this->mig_no_juicio->Exportable) $Doc->ExportCaption($this->mig_no_juicio);
					if ($this->mig_nombre_abogado->Exportable) $Doc->ExportCaption($this->mig_nombre_abogado);
					if ($this->mig_fase_procesal->Exportable) $Doc->ExportCaption($this->mig_fase_procesal);
					if ($this->mig_moneda->Exportable) $Doc->ExportCaption($this->mig_moneda);
					if ($this->mig_tasa->Exportable) $Doc->ExportCaption($this->mig_tasa);
					if ($this->mig_plazo->Exportable) $Doc->ExportCaption($this->mig_plazo);
					if ($this->mig_dias_mora->Exportable) $Doc->ExportCaption($this->mig_dias_mora);
					if ($this->mig_monto_desembolso->Exportable) $Doc->ExportCaption($this->mig_monto_desembolso);
					if ($this->mig_total_cartera->Exportable) $Doc->ExportCaption($this->mig_total_cartera);
					if ($this->mig_capital->Exportable) $Doc->ExportCaption($this->mig_capital);
					if ($this->mig_intereses->Exportable) $Doc->ExportCaption($this->mig_intereses);
					if ($this->mig_cargos_gastos->Exportable) $Doc->ExportCaption($this->mig_cargos_gastos);
					if ($this->mig_total_deuda->Exportable) $Doc->ExportCaption($this->mig_total_deuda);
				} else {
					if ($this->Id->Exportable) $Doc->ExportCaption($this->Id);
					if ($this->id_cliente->Exportable) $Doc->ExportCaption($this->id_cliente);
					if ($this->id_ciudad->Exportable) $Doc->ExportCaption($this->id_ciudad);
					if ($this->id_agente->Exportable) $Doc->ExportCaption($this->id_agente);
					if ($this->id_estadodeuda->Exportable) $Doc->ExportCaption($this->id_estadodeuda);
					if ($this->mig_codigo_deuda->Exportable) $Doc->ExportCaption($this->mig_codigo_deuda);
					if ($this->mig_tipo_operacion->Exportable) $Doc->ExportCaption($this->mig_tipo_operacion);
					if ($this->mig_fecha_desembolso->Exportable) $Doc->ExportCaption($this->mig_fecha_desembolso);
					if ($this->mig_fecha_estado->Exportable) $Doc->ExportCaption($this->mig_fecha_estado);
					if ($this->mig_anios_castigo->Exportable) $Doc->ExportCaption($this->mig_anios_castigo);
					if ($this->mig_tipo_garantia->Exportable) $Doc->ExportCaption($this->mig_tipo_garantia);
					if ($this->mig_real->Exportable) $Doc->ExportCaption($this->mig_real);
					if ($this->mig_actividad_economica->Exportable) $Doc->ExportCaption($this->mig_actividad_economica);
					if ($this->mig_agencia->Exportable) $Doc->ExportCaption($this->mig_agencia);
					if ($this->mig_no_juicio->Exportable) $Doc->ExportCaption($this->mig_no_juicio);
					if ($this->mig_nombre_abogado->Exportable) $Doc->ExportCaption($this->mig_nombre_abogado);
					if ($this->mig_fase_procesal->Exportable) $Doc->ExportCaption($this->mig_fase_procesal);
					if ($this->mig_moneda->Exportable) $Doc->ExportCaption($this->mig_moneda);
					if ($this->mig_tasa->Exportable) $Doc->ExportCaption($this->mig_tasa);
					if ($this->mig_plazo->Exportable) $Doc->ExportCaption($this->mig_plazo);
					if ($this->mig_dias_mora->Exportable) $Doc->ExportCaption($this->mig_dias_mora);
					if ($this->mig_monto_desembolso->Exportable) $Doc->ExportCaption($this->mig_monto_desembolso);
					if ($this->mig_total_cartera->Exportable) $Doc->ExportCaption($this->mig_total_cartera);
					if ($this->mig_capital->Exportable) $Doc->ExportCaption($this->mig_capital);
					if ($this->mig_intereses->Exportable) $Doc->ExportCaption($this->mig_intereses);
					if ($this->mig_cargos_gastos->Exportable) $Doc->ExportCaption($this->mig_cargos_gastos);
					if ($this->mig_total_deuda->Exportable) $Doc->ExportCaption($this->mig_total_deuda);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->Id->Exportable) $Doc->ExportField($this->Id);
						if ($this->id_cliente->Exportable) $Doc->ExportField($this->id_cliente);
						if ($this->id_ciudad->Exportable) $Doc->ExportField($this->id_ciudad);
						if ($this->id_agente->Exportable) $Doc->ExportField($this->id_agente);
						if ($this->id_estadodeuda->Exportable) $Doc->ExportField($this->id_estadodeuda);
						if ($this->mig_codigo_deuda->Exportable) $Doc->ExportField($this->mig_codigo_deuda);
						if ($this->mig_tipo_operacion->Exportable) $Doc->ExportField($this->mig_tipo_operacion);
						if ($this->mig_fecha_desembolso->Exportable) $Doc->ExportField($this->mig_fecha_desembolso);
						if ($this->mig_fecha_estado->Exportable) $Doc->ExportField($this->mig_fecha_estado);
						if ($this->mig_anios_castigo->Exportable) $Doc->ExportField($this->mig_anios_castigo);
						if ($this->mig_tipo_garantia->Exportable) $Doc->ExportField($this->mig_tipo_garantia);
						if ($this->mig_real->Exportable) $Doc->ExportField($this->mig_real);
						if ($this->mig_actividad_economica->Exportable) $Doc->ExportField($this->mig_actividad_economica);
						if ($this->mig_agencia->Exportable) $Doc->ExportField($this->mig_agencia);
						if ($this->mig_no_juicio->Exportable) $Doc->ExportField($this->mig_no_juicio);
						if ($this->mig_nombre_abogado->Exportable) $Doc->ExportField($this->mig_nombre_abogado);
						if ($this->mig_fase_procesal->Exportable) $Doc->ExportField($this->mig_fase_procesal);
						if ($this->mig_moneda->Exportable) $Doc->ExportField($this->mig_moneda);
						if ($this->mig_tasa->Exportable) $Doc->ExportField($this->mig_tasa);
						if ($this->mig_plazo->Exportable) $Doc->ExportField($this->mig_plazo);
						if ($this->mig_dias_mora->Exportable) $Doc->ExportField($this->mig_dias_mora);
						if ($this->mig_monto_desembolso->Exportable) $Doc->ExportField($this->mig_monto_desembolso);
						if ($this->mig_total_cartera->Exportable) $Doc->ExportField($this->mig_total_cartera);
						if ($this->mig_capital->Exportable) $Doc->ExportField($this->mig_capital);
						if ($this->mig_intereses->Exportable) $Doc->ExportField($this->mig_intereses);
						if ($this->mig_cargos_gastos->Exportable) $Doc->ExportField($this->mig_cargos_gastos);
						if ($this->mig_total_deuda->Exportable) $Doc->ExportField($this->mig_total_deuda);
					} else {
						if ($this->Id->Exportable) $Doc->ExportField($this->Id);
						if ($this->id_cliente->Exportable) $Doc->ExportField($this->id_cliente);
						if ($this->id_ciudad->Exportable) $Doc->ExportField($this->id_ciudad);
						if ($this->id_agente->Exportable) $Doc->ExportField($this->id_agente);
						if ($this->id_estadodeuda->Exportable) $Doc->ExportField($this->id_estadodeuda);
						if ($this->mig_codigo_deuda->Exportable) $Doc->ExportField($this->mig_codigo_deuda);
						if ($this->mig_tipo_operacion->Exportable) $Doc->ExportField($this->mig_tipo_operacion);
						if ($this->mig_fecha_desembolso->Exportable) $Doc->ExportField($this->mig_fecha_desembolso);
						if ($this->mig_fecha_estado->Exportable) $Doc->ExportField($this->mig_fecha_estado);
						if ($this->mig_anios_castigo->Exportable) $Doc->ExportField($this->mig_anios_castigo);
						if ($this->mig_tipo_garantia->Exportable) $Doc->ExportField($this->mig_tipo_garantia);
						if ($this->mig_real->Exportable) $Doc->ExportField($this->mig_real);
						if ($this->mig_actividad_economica->Exportable) $Doc->ExportField($this->mig_actividad_economica);
						if ($this->mig_agencia->Exportable) $Doc->ExportField($this->mig_agencia);
						if ($this->mig_no_juicio->Exportable) $Doc->ExportField($this->mig_no_juicio);
						if ($this->mig_nombre_abogado->Exportable) $Doc->ExportField($this->mig_nombre_abogado);
						if ($this->mig_fase_procesal->Exportable) $Doc->ExportField($this->mig_fase_procesal);
						if ($this->mig_moneda->Exportable) $Doc->ExportField($this->mig_moneda);
						if ($this->mig_tasa->Exportable) $Doc->ExportField($this->mig_tasa);
						if ($this->mig_plazo->Exportable) $Doc->ExportField($this->mig_plazo);
						if ($this->mig_dias_mora->Exportable) $Doc->ExportField($this->mig_dias_mora);
						if ($this->mig_monto_desembolso->Exportable) $Doc->ExportField($this->mig_monto_desembolso);
						if ($this->mig_total_cartera->Exportable) $Doc->ExportField($this->mig_total_cartera);
						if ($this->mig_capital->Exportable) $Doc->ExportField($this->mig_capital);
						if ($this->mig_intereses->Exportable) $Doc->ExportField($this->mig_intereses);
						if ($this->mig_cargos_gastos->Exportable) $Doc->ExportField($this->mig_cargos_gastos);
						if ($this->mig_total_deuda->Exportable) $Doc->ExportField($this->mig_total_deuda);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
