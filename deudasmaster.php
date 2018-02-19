<?php

// Id
// id_cliente
// codigo

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
<?php if ($deudas->codigo->Visible) { // codigo ?>
		<tr id="r_codigo">
			<td class="col-sm-2"><?php echo $deudas->codigo->FldCaption() ?></td>
			<td<?php echo $deudas->codigo->CellAttributes() ?>>
<span id="el_deudas_codigo">
<span<?php echo $deudas->codigo->ViewAttributes() ?>>
<?php echo $deudas->codigo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
