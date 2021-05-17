<h2><?= JText::_('COM_CLASSIFICADOS_PESSOA_TITULO_MEUSDADOS'); ?></h2>
            <div class="row">
                <div class="span6 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_NOME'); ?></strong></div>
                <div class="span6 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_SOBRENOME'); ?></strong></div>
            </div>
            <div class="row">
                <div class="span6 text-center"><?= $item->nome ?></div>
                <div class="span6 text-center"><?= $item->sobrenome ?></div>
            </div>
            <div class="row">
                <div class="span3 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_USUARIO'); ?></strong></div>
                <div class="span3 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_NASCIMENTO'); ?></strong></div>
                <div class="span3 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_GENERO'); ?></strong></div>
                <div class="span3 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_CPF'); ?></strong></div>
            </div>
            <div class="row">
                <div class="span3 text-center"><?= $item->username ?></div>
                <div class="span3 text-center"><?= $this->formatarData($item->nascimento) ?></div>
                <div class="span3 text-center">
                <?php
                    switch($item->genero){
                        case 'M':
                            echo JText::_('MASCULINO');
                            break;
                        case 'F':
                            echo JText::_('FEMININO');
                            break;
                        case 'O':
                            echo JText::_('OUTRO');
                            break;
                        default:
                    }?>
                </div>
                <div class="span3 text-center"><?= $item->cpf ?></div>
            </div>
            <div class="row">
            <?php
                $ip = $this->umOuOutro($item->ip_alterador,  $item->ip_alterador_proxiado );
                if( $ip != '') :
            ?>
                <div class="span3 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_IP_ULTIMALTERACAO'); ?></strong></div>
            <?php 
                endif; ?>
                <div class="span3 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_DATA_ULTIMOACESSO'); ?></strong></div>
                <div class="span3 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_DATA_CADASTRO'); ?></strong></div>
                <div class="span3 text-center"><strong><?= JText::_('COM_CLASSIFICADOS_PESSOA_DATA_ALTERACAO'); ?></strong></div>
            </div>
            <div class="row">
<?php         if($ip != '') : 
            ?>
                <div class="span3 text-center"><?= $ip ?></div><?php endif;?>
                <div class="span3 text-center"><?= $this->formatarDataHora($item->usuario->lastvisitDate) ?></div>
                <div class="span3 text-center"><?= $this->formatarDataHora($item->usuario->registerDate) ?></div>
                <div class="span3 text-center"><?= $this->umOuOutro( $this->umOuOutro( $this->formatarDataHora($item->data_criado) ,$this->formatarDataHora($item->data_alterado)), $this->formatarDataHora($item->usuario->registerDate)) ?></div>
            </div> 

            <form action="<?= $cadastro?>" method="post">
                <div class="btn-toolbar text-right">
                    <div class="btn-group">
                        <button type="submit" id="btnBuscar" class="btn"><?=
                        JText::_('COM_CLASSIFICADOS_BTN_EDITAR') . JText::_('COM_CLASSIFICADOS_PESSOA_BTN_EDITAR_TEXT') ?></button>
                    </div>
                </div>
            </form>