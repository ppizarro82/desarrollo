<?php

// Id
// id_cliente
// id_ciudad
// id_agente
// id_estadodeuda
// mig_codigo_deuda
// mig_fecha_desembolso
// mig_moneda
// mig_tasa
// mig_plazo
// mig_dias_mora
// mig_monto_desembolso
// mig_intereses
// mig_cargos_gastos
// mig_total_deuda

?>
<?php if ($deudas->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_deudasmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($deudas->Id->Visible) { // Id ?>
		<tr id="r_Id">
			<td class="col-sm-2"><?php echo $deudas->Id->FldCaption() ?></td>
			<td<?php echo $deudas->Id->CellAttributes() ?>>
<span id="el_deudas_Id">
<span<?php echo $deudas->Id->ViewAttributes() ?>>
<?php echo $deudas->Id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->id_cliente->Visible) { // id_cliente ?>
		<tr id="r_id_cliente">
			<td class="col-sm-2"><?php echo $deudas->id_cliente->FldCaption() ?></td>
			<td<?php echo $deudas->id_cliente->CellAttributes() ?>>
<span id="el_deudas_id_cliente">
<span<?php echo $deudas->id_cliente->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($deudas->id_cliente->ListViewValue())) && $deudas->id_cliente->LinkAttributes() <> "") { ?>
<a<?php echo $deudas->id_cliente->LinkAttributes() ?>><?php echo $deudas->id_cliente->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $deudas->id_cliente->ListViewValue() ?>
<?php } ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->id_ciudad->Visible) { // id_ciudad ?>
		<tr id="r_id_ciudad">
			<td class="col-sm-2"><?php echo $deudas->id_ciudad->FldCaption() ?></td>
			<td<?php echo $deudas->id_ciudad->CellAttributes() ?>>
<span id="el_deudas_id_ciudad">
<span<?php echo $deudas->id_ciudad->ViewAttributes() ?>>
<?php echo $deudas->id_ciudad->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->id_agente->Visible) { // id_agente ?>
		<tr id="r_id_agente">
			<td class="col-sm-2"><?php echo $deudas->id_agente->FldCaption() ?></td>
			<td<?php echo $deudas->id_agente->CellAttributes() ?>>
<span id="el_deudas_id_agente">
<span<?php echo $deudas->id_agente->ViewAttributes() ?>>
<?php echo $deudas->id_agente->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->id_estadodeuda->Visible) { // id_estadodeuda ?>
		<tr id="r_id_estadodeuda">
			<td class="col-sm-2"><?php echo $deudas->id_estadodeuda->FldCaption() ?></td>
			<td<?php echo $deudas->id_estadodeuda->CellAttributes() ?>>
<span id="el_deudas_id_estadodeuda">
<span<?php echo $deudas->id_estadodeuda->ViewAttributes() ?>>
<?php echo $deudas->id_estadodeuda->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->mig_codigo_deuda->Visible) { // mig_codigo_deuda ?>
		<tr id="r_mig_codigo_deuda">
			<td class="col-sm-2"><?php echo $deudas->mig_codigo_deuda->FldCaption() ?></td>
			<td<?php echo $deudas->mig_codigo_deuda->CellAttributes() ?>>
<span id="el_deudas_mig_codigo_deuda">
<span<?php echo $deudas->mig_codigo_deuda->ViewAttributes() ?>>
<?php echo $deudas->mig_codigo_deuda->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->mig_fecha_desembolso->Visible) { // mig_fecha_desembolso ?>
		<tr id="r_mig_fecha_desembolso">
			<td class="col-sm-2"><?php echo $deudas->mig_fecha_desembolso->FldCaption() ?></td>
			<td<?php echo $deudas->mig_fecha_desembolso->CellAttributes() ?>>
<span id="el_deudas_mig_fecha_desembolso">
<span<?php echo $deudas->mig_fecha_desembolso->ViewAttributes() ?>>
<?php echo $deudas->mig_fecha_desembolso->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->mig_moneda->Visible) { // mig_moneda ?>
		<tr id="r_mig_moneda">
			<td class="col-sm-2"><?php echo $deudas->mig_moneda->FldCaption() ?></td>
			<td<?php echo $deudas->mig_moneda->CellAttributes() ?>>
<span id="el_deudas_mig_moneda">
<span<?php echo $deudas->mig_moneda->ViewAttributes() ?>>
<?php echo $deudas->mig_moneda->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->mig_tasa->Visible) { // mig_tasa ?>
		<tr id="r_mig_tasa">
			<td class="col-sm-2"><?php echo $deudas->mig_tasa->FldCaption() ?></td>
			<td<?php echo $deudas->mig_tasa->CellAttributes() ?>>
<span id="el_deudas_mig_tasa">
<span<?php echo $deudas->mig_tasa->ViewAttributes() ?>>
<?php echo $deudas->mig_tasa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->mig_plazo->Visible) { // mig_plazo ?>
		<tr id="r_mig_plazo">
			<td class="col-sm-2"><?php echo $deudas->mig_plazo->FldCaption() ?></td>
			<td<?php echo $deudas->mig_plazo->CellAttributes() ?>>
<span id="el_deudas_mig_plazo">
<span<?php echo $deudas->mig_plazo->ViewAttributes() ?>>
<?php echo $deudas->mig_plazo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->mig_dias_mora->Visible) { // mig_dias_mora ?>
		<tr id="r_mig_dias_mora">
			<td class="col-sm-2"><?php echo $deudas->mig_dias_mora->FldCaption() ?></td>
			<td<?php echo $deudas->mig_dias_mora->CellAttributes() ?>>
<span id="el_deudas_mig_dias_mora">
<span<?php echo $deudas->mig_dias_mora->ViewAttributes() ?>>
<?php echo $deudas->mig_dias_mora->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->mig_monto_desembolso->Visible) { // mig_monto_desembolso ?>
		<tr id="r_mig_monto_desembolso">
			<td class="col-sm-2"><?php echo $deudas->mig_monto_desembolso->FldCaption() ?></td>
			<td<?php echo $deudas->mig_monto_desembolso->CellAttributes() ?>>
<span id="el_deudas_mig_monto_desembolso">
<span<?php echo $deudas->mig_monto_desembolso->ViewAttributes() ?>>
<?php echo $deudas->mig_monto_desembolso->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->mig_intereses->Visible) { // mig_intereses ?>
		<tr id="r_mig_intereses">
			<td class="col-sm-2"><?php echo $deudas->mig_intereses->FldCaption() ?></td>
			<td<?php echo $deudas->mig_intereses->CellAttributes() ?>>
<span id="el_deudas_mig_intereses">
<span<?php echo $deudas->mig_intereses->ViewAttributes() ?>>
<?php echo $deudas->mig_intereses->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->mig_cargos_gastos->Visible) { // mig_cargos_gastos ?>
		<tr id="r_mig_cargos_gastos">
			<td class="col-sm-2"><?php echo $deudas->mig_cargos_gastos->FldCaption() ?></td>
			<td<?php echo $deudas->mig_cargos_gastos->CellAttributes() ?>>
<span id="el_deudas_mig_cargos_gastos">
<span<?php echo $deudas->mig_cargos_gastos->ViewAttributes() ?>>
<?php echo $deudas->mig_cargos_gastos->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deudas->mig_total_deuda->Visible) { // mig_total_deuda ?>
		<tr id="r_mig_total_deuda">
			<td class="col-sm-2"><?php echo $deudas->mig_total_deuda->FldCaption() ?></td>
			<td<?php echo $deudas->mig_total_deuda->CellAttributes() ?>>
<span id="el_deudas_mig_total_deuda">
<span<?php echo $deudas->mig_total_deuda->ViewAttributes() ?>>
<?php echo $deudas->mig_total_deuda->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
