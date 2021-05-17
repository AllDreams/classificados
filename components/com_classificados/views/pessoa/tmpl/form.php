<?php






defined ( '_JEXEC' ) || die ( 'Restricted access' );


$app = JFactory::getApplication();
$input = $app->input;
$itemid = $input->get( 'Itemid', null, 'string' );
$task =  $input->get( 'task', null, 'string' );

//$token = JSession::getFormToken();




$url =  JRoute::_( 'index.php?option=com_classificados&task=pessoa.editar&Itemid='.$itemid , false );
$urlGravar =  JRoute::_( 'index.php?option=com_classificados&task=pessoa.salvar&Itemid='.$itemid , false );

if ($task == null || $task == '') {
	$app->redirect ($url, "" );
	exit();
}

$nome = $input->get('nome',null,'string');
$sobrenome =$input->get('sobrenome',null,'string');
$genero = $input->get('genero',null,'string');
$nascimento=$input->get('nascimento',null,'string');
$cpf = $input->get('cpf',null,'string');
$item = JRequest::getVar('item');


$nome = $this->umOuOutro($nome, $item->nome);
$sobrenome = $this->umOuOutro($sobrenome, $item->sobrenome );
$genero = $this->umOuOutro($genero, $item->genero );   
$nascimento = $this->umOuOutro($nascimento, $item->nascimento );
$cpf = $this->umOuOutro($cpf, $item->cpf );

$nome = $nome == null || trim($nome) == '' && $item != null ? $item->usuario->name : $nome;


$doc = JFactory::getDocument();
$doc->addScript(JURI::base() . 'media/com_classificados/js/jquery.mask.js?v='.VERSAO);
$doc->addScriptDeclaration('
jQuery(document).ready(function(){
    jQuery("#cpf").mask("###.###.###-##");
});');
?>
<form action="<?= $urlGravar ?>" method="post" enctype="application/x-www-form-urlencoded" class="form-validate form-horizontal">
	<input type="hidden" name="option" value="com_classificados"/>
	<input type="hidden" name="task" value="pessoa.salvar"/>
	<input type="hidden" name="Itemid" value="<?= $itemid ?>"/>
    <?php echo JHtml::_('form.token'); ?>


    <fieldset>
        <legend><?= JText::_('COM_CLASSIFICADOS_PESSOA_DADOS_PESSOAIS') ?></legend>

        <div class="control-group">
            <label class="control-label" for="nome"><?= JText::_('COM_CLASSIFICADOS_PESSOA_NOME') ?></label>
            <div class="controls">
                <input type="text" id="nome" name="nome" value="<?= $nome ?>"
                    required="required" maxlength="200" minlength="3"
                    placeholder="<?= JText::_('COM_CLASSIFICADOS_PESSOA_NOME_PLACEHOLDER') ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="sobrenome"><?= JText::_('COM_CLASSIFICADOS_PESSOA_SOBRENOME') ?></label>
            <div class="controls">
                <input type="text" id="sobrenome" name="sobrenome" value="<?= $sobrenome  ?>"
                    required="required" maxlength="199" minlength="3"
                    placeholder="<?= JText::_('COM_CLASSIFICADOS_PESSOA_SOBRENOME_PLACEHOLDER') ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="nascimento"><?= JText::_('COM_CLASSIFICADOS_PESSOA_NASCIMENTO') ?></label>
            <div class="controls">
                <input type="date" id="nascimento" name="nascimento" value="<?= $nascimento ?>"
                    required="required" maxlength="10" minlength="10" max="<?= date('Y-m-d', strtotime('-18 years', mktime(0,0,0))) ?>" 
                    min="<?= date('Y-m-d', strtotime('-123 years', mktime(0,0,0))) ?>"  pattern="(\d{2})/(\d{3})/(\d{4})"
                    placeholder="<?= JText::_('COM_CLASSIFICADOS_PESSOA_NASCIMENTO_PLACEHOLDER') ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="genero"><?= JText::_('COM_CLASSIFICADOS_PESSOA_GENERO') ?></label>
            <div class="controls">
                <select name="genero" id="genero" required="required">
                    <option></option>
                    <option value="M"<?= $this->selecionado($genero,'M' )?>><?= JText::_('MASCULINO') ?></option>
                    <option value="F"<?= $this->selecionado($genero,'F' )?>><?= JText::_('FEMININO') ?></option>
                    <option value="O"<?= $this->selecionado($genero,'O' )?>><?= JText::_('OUTRO') ?></option>
                    <option value="Ñ"<?= $this->selecionado($genero,'Ñ' )?>><?= JText::_('PREFERE_NAO_INFORMAR') ?></option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="cpf"><?= JText::_('COM_CLASSIFICADOS_PESSOA_CPF') ?></label>
            <div class="controls">
                <input type="text" pattern="(\d{3})[.]?(\d{3})[.]?(\d{3})[-]?(\d{2})" id="cpf" name="cpf" 
                    value="<?= $cpf ?>" required="required" maxlength="250" minlength="3"
                    placeholder="<?= JText::_('COM_CLASSIFICADOS_PESSOA_CPF_PLACEHOLDER') ?>"/>
            </div>
        </div>




        <div class="btn-toolbar text-right">
            <div class="btn-group">
                <button type="submit" id="btnBuscar" class="btn"><?=
                JText::_('COM_CLASSIFICADOS_BTN_EDITAR') . JText::_('COM_CLASSIFICADOS_PESSOA_BTN_EDITAR_TEXT') ?></button>
            </div>
        </div>
    </fieldset>


    <h3><?= JText::_('COM_CLASSIFICADOS_ACESSO') ?></h3>
    <div class="tab-pane active" id="usuario">      
        <div class="row">
            <div class="span3 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_IP_ULTIMALTERACAO'); ?></strong></div>
            <div class="span3 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_DATA_ULTIMOACESSO'); ?></strong></div>
            <div class="span3 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_DATA_CADASTRO'); ?></strong></div>
            <div class="span3 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_DATA_ALTERACAO'); ?></strong></div>
        </div>
        <div class="row">
            <div class="span3 text-center"><?= $this->umOuOutro( $this->umOuOutro($item->ip_criador,  $item->ip_criador_proxiado ),$this->umOuOutro($item->ip_alterador,  $item->ip_alterador_proxiado )) ?></div>            
            <div class="span3 text-center"><?= $this->formatarDataHora($item->usuario->lastvisitDate) ?></div>
            <div class="span3 text-center"><?= $this->formatarDataHora($item->usuario->registerDate) ?></div>
            <div class="span3 text-center"><?= $this->umOuOutro( $this->formatarDataHora($item->data_criado) ,$this->formatarDataHora($item->data_alterado))?></div>
        </div> 
    </div>

</form>