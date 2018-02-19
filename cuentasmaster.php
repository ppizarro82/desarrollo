<?php

// Id
// codigo
// denominacion
// inicio_contrato
// fin_contrato
// fecha_registro
// estado

?>
<?php if ($cuentas->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_cuentasmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($cuentas->Id->Visible) { // Id ?>
		<tr id="r_Id">
			<td class="col-sm-2"><?php echo $cuentas->Id->FldCaption() ?></td>
			<td<?php echo $cuentas->Id->CellAttributes() ?>>
<span id="el_cuentas_Id">
<span<?php echo $cuentas->Id->ViewAttributes() ?>>
<?php echo $cuentas->Id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cuentas->codigo->Visible) { // codigo ?>
		<tr id="r_codigo">
			<td class="col-sm-2"><?php echo $cuentas->codigo->FldCaption() ?></td>
			<td<?php echo $cuentas->codigo->CellAttributes() ?>>
<span id="el_cuentas_codigo">
<span<?php echo $cuentas->codigo->ViewAttributes() ?>>
<?php echo $cuentas->codigo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cuentas->denominacion->Visible) { // denominacion ?>
		<tr id="r_denominacion">
			<td class="col-sm-2"><?php echo $cuentas->denominacion->FldCaption() ?></td>
			<td<?php echo $cuentas->denominacion->CellAttributes() ?>>
<span id="el_cuentas_denominacion">
<span<?php echo $cuentas->denominacion->ViewAttributes() ?>>
<?php echo $cuentas->denominacion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cuentas->inicio_contrato->Visible) { // inicio_contrato ?>
		<tr id="r_inicio_contrato">
			<td class="col-sm-2"><?php echo $cuentas->inicio_contrato->FldCaption() ?></td>
			<td<?php echo $cuentas->inicio_contrato->CellAttributes() ?>>
<span id="el_cuentas_inicio_contrato">
<span<?php echo $cuentas->inicio_contrato->ViewAttributes() ?>>
<?php echo $cuentas->inicio_contrato->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cuentas->fin_contrato->Visible) { // fin_contrato ?>
		<tr id="r_fin_contrato">
			<td class="col-sm-2"><?php echo $cuentas->fin_contrato->FldCaption() ?></td>
			<td<?php echo $cuentas->fin_contrato->CellAttributes() ?>>
<span id="el_cuentas_fin_contrato">
<span<?php echo $cuentas->fin_contrato->ViewAttributes() ?>>
<?php echo $cuentas->fin_contrato->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cuentas->fecha_registro->Visible) { // fecha_registro ?>
		<tr id="r_fecha_registro">
			<td class="col-sm-2"><?php echo $cuentas->fecha_registro->FldCaption() ?></td>
			<td<?php echo $cuentas->fecha_registro->CellAttributes() ?>>
<span id="el_cuentas_fecha_registro">
<span<?php echo $cuentas->fecha_registro->ViewAttributes() ?>>
<?php echo $cuentas->fecha_registro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($cuentas->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td class="col-sm-2"><?php echo $cuentas->estado->FldCaption() ?></td>
			<td<?php echo $cuentas->estado->CellAttributes() ?>>
<span id="el_cuentas_estado">
<span<?php echo $cuentas->estado->ViewAttributes() ?>>
<?php echo $cuentas->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
