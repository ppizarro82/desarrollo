<?php

// Id
// id_fuente
// id_gestion
// id_ref
// tipo_documento
// no_documento
// nombres
// paterno
// materno
// fecha_nacimiento
// fecha_registro
// imagen
// estado

?>
<?php if ($personas->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_personasmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($personas->Id->Visible) { // Id ?>
		<tr id="r_Id">
			<td class="col-sm-2"><?php echo $personas->Id->FldCaption() ?></td>
			<td<?php echo $personas->Id->CellAttributes() ?>>
<span id="el_personas_Id">
<span<?php echo $personas->Id->ViewAttributes() ?>>
<?php echo $personas->Id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->id_fuente->Visible) { // id_fuente ?>
		<tr id="r_id_fuente">
			<td class="col-sm-2"><?php echo $personas->id_fuente->FldCaption() ?></td>
			<td<?php echo $personas->id_fuente->CellAttributes() ?>>
<span id="el_personas_id_fuente">
<span<?php echo $personas->id_fuente->ViewAttributes() ?>>
<?php echo $personas->id_fuente->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->id_gestion->Visible) { // id_gestion ?>
		<tr id="r_id_gestion">
			<td class="col-sm-2"><?php echo $personas->id_gestion->FldCaption() ?></td>
			<td<?php echo $personas->id_gestion->CellAttributes() ?>>
<span id="el_personas_id_gestion">
<span<?php echo $personas->id_gestion->ViewAttributes() ?>>
<?php echo $personas->id_gestion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->id_ref->Visible) { // id_ref ?>
		<tr id="r_id_ref">
			<td class="col-sm-2"><?php echo $personas->id_ref->FldCaption() ?></td>
			<td<?php echo $personas->id_ref->CellAttributes() ?>>
<span id="el_personas_id_ref">
<span<?php echo $personas->id_ref->ViewAttributes() ?>>
<?php echo $personas->id_ref->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->tipo_documento->Visible) { // tipo_documento ?>
		<tr id="r_tipo_documento">
			<td class="col-sm-2"><?php echo $personas->tipo_documento->FldCaption() ?></td>
			<td<?php echo $personas->tipo_documento->CellAttributes() ?>>
<span id="el_personas_tipo_documento">
<span<?php echo $personas->tipo_documento->ViewAttributes() ?>>
<?php echo $personas->tipo_documento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->no_documento->Visible) { // no_documento ?>
		<tr id="r_no_documento">
			<td class="col-sm-2"><?php echo $personas->no_documento->FldCaption() ?></td>
			<td<?php echo $personas->no_documento->CellAttributes() ?>>
<span id="el_personas_no_documento">
<span<?php echo $personas->no_documento->ViewAttributes() ?>>
<?php echo $personas->no_documento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->nombres->Visible) { // nombres ?>
		<tr id="r_nombres">
			<td class="col-sm-2"><?php echo $personas->nombres->FldCaption() ?></td>
			<td<?php echo $personas->nombres->CellAttributes() ?>>
<span id="el_personas_nombres">
<span<?php echo $personas->nombres->ViewAttributes() ?>>
<?php echo $personas->nombres->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->paterno->Visible) { // paterno ?>
		<tr id="r_paterno">
			<td class="col-sm-2"><?php echo $personas->paterno->FldCaption() ?></td>
			<td<?php echo $personas->paterno->CellAttributes() ?>>
<span id="el_personas_paterno">
<span<?php echo $personas->paterno->ViewAttributes() ?>>
<?php echo $personas->paterno->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->materno->Visible) { // materno ?>
		<tr id="r_materno">
			<td class="col-sm-2"><?php echo $personas->materno->FldCaption() ?></td>
			<td<?php echo $personas->materno->CellAttributes() ?>>
<span id="el_personas_materno">
<span<?php echo $personas->materno->ViewAttributes() ?>>
<?php echo $personas->materno->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->fecha_nacimiento->Visible) { // fecha_nacimiento ?>
		<tr id="r_fecha_nacimiento">
			<td class="col-sm-2"><?php echo $personas->fecha_nacimiento->FldCaption() ?></td>
			<td<?php echo $personas->fecha_nacimiento->CellAttributes() ?>>
<span id="el_personas_fecha_nacimiento">
<span<?php echo $personas->fecha_nacimiento->ViewAttributes() ?>>
<?php echo $personas->fecha_nacimiento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->fecha_registro->Visible) { // fecha_registro ?>
		<tr id="r_fecha_registro">
			<td class="col-sm-2"><?php echo $personas->fecha_registro->FldCaption() ?></td>
			<td<?php echo $personas->fecha_registro->CellAttributes() ?>>
<span id="el_personas_fecha_registro">
<span<?php echo $personas->fecha_registro->ViewAttributes() ?>>
<?php echo $personas->fecha_registro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->imagen->Visible) { // imagen ?>
		<tr id="r_imagen">
			<td class="col-sm-2"><?php echo $personas->imagen->FldCaption() ?></td>
			<td<?php echo $personas->imagen->CellAttributes() ?>>
<span id="el_personas_imagen">
<span>
<?php echo ew_GetFileViewTag($personas->imagen, $personas->imagen->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->estado->Visible) { // estado ?>
		<tr id="r_estado">
			<td class="col-sm-2"><?php echo $personas->estado->FldCaption() ?></td>
			<td<?php echo $personas->estado->CellAttributes() ?>>
<span id="el_personas_estado">
<span<?php echo $personas->estado->ViewAttributes() ?>>
<?php echo $personas->estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
