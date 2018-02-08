<?php

// Id
// nombre
// estado

?>
<?php if ($tipo_persona->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_tipo_personamaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($tipo_persona->Id->Visible) { // Id ?>
		<tr id="r_Id">
			<td class="col-sm-2"><?php echo $tipo_persona->Id->FldCaption() ?></td>
			<td<?php echo $tipo_persona->Id->CellAttributes() ?>>
<span id="el_tipo_persona_Id">
<span<?php echo $tipo_persona->Id->ViewAttributes() ?>>
<?php echo $tipo_persona->Id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tipo_persona->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td class="col-sm-2"><?php echo $tipo_persona->nombre->FldCaption() ?></td>
			<td<?php echo $tipo_persona->nombre->CellAttributes() ?>>
<span id="el_tipo_persona_nombre">
<span<?php echo $tipo_persona->nombre->ViewAttributes() ?>>
<?php echo $tipo_persona->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($tipo_persona->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td class="col-sm-2"><?php echo $tipo_persona->estado->FldCaption() ?></td>
			<td<?php echo $tipo_persona->estado->CellAttributes() ?>>
<span id="el_tipo_persona_estado">
<span<?php echo $tipo_persona->estado->ViewAttributes() ?>>
<?php echo $tipo_persona->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
