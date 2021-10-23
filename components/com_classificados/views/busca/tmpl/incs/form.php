<form action="<?= $url_busca ?>" method="get" enctype="application/x-www-form-urlencoded" class="form-validate form-horizontal well">
	<input type="hidden" name="option" value="com_classificados"/>
	<input type="hidden" name="task" value="busca.busca"/>
	<input type="hidden" name="Itemid" value="<?= $itemid ?>"/>
<?php if($ste != null && !empty($ste)) : ?>	<input type="hidden" name="ste" value="<?= $ste?>"/><?php endif; ?>
<?php if($stp != null && !empty($stp)) : ?>	<input type="hidden" name="stp" value="<?= $stp?>"/><?php endif; ?>
	<div class="control-group">
		<label class="control-label" for="busca"><?= JText::_('COM_CLASSIFICADOS_BUSCA_POR') ?></label>
		<div class="controls">
			<input type="text" id="busca" name="q" required="required" 
				maxlength="100" minlength="3" max="100" min="3" 
				placeholder="<?= JText::_('COM_CLASSIFICADOS_CAMPO_BUSCA') ?>"/>
		</div>
	</div><!--
1 - Formulário com o campo e-mail e o botão convidar
2 - Lista com:
    Botão para cancelar convite 
    e-mails dos convidados
    data de aceite
    data de recusa
-->
<?php if($ste == null || empty($ste)) : ?>
	<div class="control-group">
		<label class="control-label" for="tipoEmpresa"><?= JText::_('COM_CLASSIFICADOS_CAMPO_TIPO_EMPRESA') ?></label>
		<div class="controls">
			<select name="te" id="tipoEmpresa" style="max-width: 400px">
				<option></option>
<?php foreach($tiposEmpresa as $item) : ?>
				<option value="<?= $item->id?>"><?=  $item->nome . ' [' . $item->cnae . '] ' ?></option>
<?php endforeach; ?>
			</select>
		</div>
	</div>
<?php endif; ?>
<?php if($stp == null || empty($stp)) : ?>
	<div class="control-group">
		<label class="control-label" for="tipoProduto"><?= JText::_('COM_CLASSIFICADOS_CAMPO_TIPO_PRODUTO') ?></label>
		<div class="controls">
			<select name="tp" id="tipoProduto" style="max-width: 400px">
				<option></option>
<?php foreach($tiposProduto as $item) : ?>
				<option value="<?= $item->id?>"><?= $item->nome?></option>
<?php endforeach; ?>
			</select>
		</div>
	</div>
<?php endif; ?>
	<div class="btn-toolbar text-right">
		<div class="btn-group">
			<button type="submit" id="btnBuscar" class="btn btn-primary"><?=
				 JText::sprintf('COM_CLASSIFICADOS_BTN_BUSCAR_TEXT') .' ' . JText::sprintf('COM_CLASSIFICADOS_BTN_BUSCAR'); ?></button>


		</div>
	</div>

<?php

//echo JHtml::_('select.genericlist', $tiposProduto, "tp", 'style="max-width: 400px"', "id", "nome", $input->get('tp'));
?>

</form>