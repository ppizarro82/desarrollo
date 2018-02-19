<?php

// Id
// id_persona
// id_deuda
// id_tipopersona

?>
<?php if ($deuda_persona->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_deuda_personamaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($deuda_persona->Id->Visible) { // Id ?>
		<tr id="r_Id">
			<td class="col-sm-2"><?php echo $deuda_persona->Id->FldCaption() ?></td>
			<td<?php echo $deuda_persona->Id->CellAttributes() ?>>
<span id="el_deuda_persona_Id">
<span<?php echo $deuda_persona->Id->ViewAttributes() ?>>
<?php echo $deuda_persona->Id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deuda_persona->id_persona->Visible) { // id_persona ?>
		<tr id="r_id_persona">
			<td class="col-sm-2"><?php echo $deuda_persona->id_persona->FldCaption() ?></td>
			<td<?php echo $deuda_persona->id_persona->CellAttributes() ?>>
<span id="el_deuda_persona_id_persona">
<span<?php echo $deuda_persona->id_persona->ViewAttributes() ?>>
<?php echo $deuda_persona->id_persona->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deuda_persona->id_deuda->Visible) { // id_deuda ?>
		<tr id="r_id_deuda">
			<td class="col-sm-2"><?php echo $deuda_persona->id_deuda->FldCaption() ?></td>
			<td<?php echo $deuda_persona->id_deuda->CellAttributes() ?>>
<span id="el_deuda_persona_id_deuda">
<span<?php echo $deuda_persona->id_deuda->ViewAttributes() ?>>
<?php echo $deuda_persona->id_deuda->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($deuda_persona->id_tipopersona->Visible) { // id_tipopersona ?>
		<tr id="r_id_tipopersona">
			<td class="col-sm-2"><?php echo $deuda_persona->id_tipopersona->FldCaption() ?></td>
			<td<?php echo $deuda_persona->id_tipopersona->CellAttributes() ?>>
<span id="el_deuda_persona_id_tipopersona">
<span<?php echo $deuda_persona->id_tipopersona->ViewAttributes() ?>>
<?php echo $deuda_persona->id_tipopersona->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
