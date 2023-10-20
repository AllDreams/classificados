<?php
/**
 * @package    classificados
 *
 * @author     jorge <your@endereco.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\SearchHelper;

//SearchHelper

/**
 * Classificados controller.
 *
 * @package  classificados
 * @since    1.0.0
 */
class ClassificadosControllerPessoaEndereco extends BaseController
{

	const TB_ENDERECOPESSOA = '`#__endereco_pessoa`';

	const TB_USERS = '`#__users`';
	const TB_PESSOA = '`#__pessoa`';
    const TB_CIDADE = '`#__cidade`';
    const TB_LOGRADOURO = '`#__logradouros`';
    const TB_UF = '`#__uf`';

	const STATUS_ATIVO = 'A';
    const STATUS_REMOVIDO = 'R';


    const REGEXP_CEP = "/^[0-9]{5}\\-[0-9]{3}$/";



    public function apagar(){
        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;
        $itemid = $input->get( 'Itemid', null, 'string' );
        $id = $input->get->get('id', null,'int');

        $fields = array(
            '`status`  = \'R\'',
            '`id_user_alterador`  = ' . $db->quote($user->id),
            '`ip_alterador`  = ' . $db->quote($_SERVER['REMOTE_ADDR']),
            '`ip_alterador_proxiado`  = ' . $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']),
            '`data_alterado` = NOW()' 
        );
        $conditions = array(
            '  `id` = ' . $id       ,
            '  `status` = \'A\''        ,
            '  `id_pessoa` = ' . $user->id
        );
        $query = $db->getQuery(true);
        $query->update(ClassificadosControllerPessoaEndereco::TB_ENDERECOPESSOA)->set($fields)->where($conditions);

        $db->setQuery($query);
        $db->execute();



        $app->redirect(JRoute::_( 'index.php?option=com_classificados&task=pessoa.meusdados&t=endereco&Itemid='.$itemid , false ), "");
    }


    public function cidade(){
        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
        $uf = JRequest::getVar('uf', null,'string');

        
        if($uf != null){
            $query = $db->getQuery ( true );
            $query->select(' `a`.`id`,`a`.`nome`, `a`.`capital` ')
                ->from (ClassificadosControllerPessoaEndereco::TB_CIDADE . ' AS `a`' )
                ->where( '`status` = ' . $db->quote(ClassificadosControllerPessoaEndereco::STATUS_ATIVO), 'AND')
                ->where( '`uf` = ' . $db->quote($uf))
                ->order( ' `a`.`capital` DESC, `a`.`nome` ' )
                ->setLimit(1000);
            $db->setQuery ( $query );
            $itens = $db->loadObjectList();

            header('Content-Type: application/json');
            die(json_encode($itens));
        }
    }


    public function endereco(){
        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;
        $itemid = $input->get( 'Itemid', null, 'string' );
        $id = $input->get('id', null,'int');
        $uf = $input->post->get('uf', null,'string');

        $query = $db->getQuery ( true );
		$query->select(' `a`.`id`,`a`.`id_pessoa`,`a`.`endereco`,`a`.`numero`, `a`.`complemento`, `a`.`bairro`, `a`.`cep`, 
            `a`.`id_cidade`, `a`.`id_logradouro`, `c`.`uf`, 
            `a`.`status`,`a`.`id_user_criador`,
			`a`.`ip_criador`,`a`.`ip_criador_proxiado`,`a`.`ip_alterador`,`a`.`ip_alterador_proxiado`,`a`.`id_user_alterador`,
			`a`.`data_criado`,`a`.`data_alterado`,`b`.`name` AS `nomeAlterador` ')
			->from (ClassificadosControllerPessoaEndereco::TB_ENDERECOPESSOA . ' AS `a`' )
			->join ('LEFT', ClassificadosControllerPessoaEndereco::TB_USERS . ' AS `b` ON `a`.`id_user_alterador` = `b`.`id`')
			->join ('LEFT', ClassificadosControllerPessoaEndereco::TB_CIDADE . ' AS `c` ON `a`.`id_cidade` = `c`.`id`')
			->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoaEndereco::STATUS_ATIVO), 'AND')
            ->where( '`a`.`id`  = ' . $db->quote($id))
            ->where( '`a`.`id_pessoa`  = ' . $db->quote($user->id))
            ->setLimit(1);
		$db->setQuery ( $query );
		$endereco = $db->loadObject();


        if( $endereco != null ){
            JRequest::setVar('endereco', $endereco->endereco);
            JRequest::setVar('numero', $endereco->numero);
            JRequest::setVar('complemento', $endereco->complemento);  
            JRequest::setVar('bairro', $endereco->bairro);   
            JRequest::setVar('cep', $endereco->cep);   
            JRequest::setVar('cidade', $endereco->id_cidade);  
            JRequest::setVar('logradouro', $endereco->id_logradouro);  
            JRequest::setVar('uf', $endereco->uf);  



            $query = $db->getQuery ( true );
            $query->select(' `id`,`nome`, `capital` , `uf` ')
                ->from (ClassificadosControllerPessoaEndereco::TB_CIDADE  )
                ->where( '`status` = ' . $db->quote(ClassificadosControllerPessoaEndereco::STATUS_ATIVO), 'AND')
                ->where( '`uf` =' . $db->quote($endereco->uf))
                ->order( ' `capital` DESC, `nome` ' )
                ->setLimit(1000);
            $db->setQuery ( $query );
            $itens = $db->loadObjectList();
            $input->set( 'cidades', $itens);
        }

        if($uf != null){

            $query = $db->getQuery ( true );
            $query->select(' `id`,`nome`, `capital` , `uf` ')
                ->from (ClassificadosControllerPessoaEndereco::TB_CIDADE  )
                ->where( '`status` = ' . $db->quote(ClassificadosControllerPessoaEndereco::STATUS_ATIVO), 'AND')
                ->where( '`uf` =' . $db->quote($uf))
                ->order( ' `capital` DESC, `nome` ' )
                ->setLimit(1000);
            $db->setQuery ( $query );
            $itens = $db->loadObjectList();
            $input->set( 'cidades', $itens);
        }



        $query = $db->getQuery ( true );
        $query->select(' `a`.`id`,`a`.`nome` ')
            ->from (ClassificadosControllerPessoaEndereco::TB_LOGRADOURO . ' AS `a`' )
            ->where( '`a`.`status` = ' . $db->quote(ClassificadosControllerPessoaEndereco::STATUS_ATIVO))
            ->order( ' `a`.`nome` ' )
            ->setLimit(1000);
        $db->setQuery ( $query );
        $itens = $db->loadObjectList();
        $input->set( 'logradouros', $itens);



        $query = $db->getQuery ( true );
        $query->select(' `a`.`uf`,`a`.`nome` ')
            ->from (ClassificadosControllerPessoaEndereco::TB_UF . ' AS `a`' )
            ->where( '`status` = ' . $db->quote(ClassificadosControllerPessoaEndereco::STATUS_ATIVO))
            ->order( ' `a`.`nome` ' )
            ->setLimit(1000);
        $db->setQuery ( $query );
        $itens = $db->loadObjectList();
        $input->set( 'ufs', $itens);


    


		


        $input->set('view', 'pessoa');
		$input->set('layout',  'endereco');
		parent::display (true);
    }


    public function salvar(){
        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;


        $itemid = $input->get( 'Itemid', null, 'string' );
        $id = $input->post->get('id', null,'int');
        
 



        $endereco= $input->post->get('endereco', null,'string');
        $numero= $input->post->get('numero', null,'string');
        $complemento= $input->post->get('complemento', null,'string');
        $bairro= $input->post->get('bairro', null,'string'); 
        $cep= $input->post->get('cep', null,'string');
        $cidade= $input->post->get('cidade', null,'int');
        $logradouro= $input->post->get('logradouro', null,'int');



        if($endereco != null && $endereco!= ''){
            $endereco = (trim($endereco));
        }
        if($numero != null && $numero!= ''){
            $numero = (trim($numero));
        }
        if($complemento != null && $complemento!= ''){
            $complemento = (trim($complemento));
        }
        if($bairro != null && $bairro!= ''){
            $bairro = (trim($bairro));
        }
        if($cep != null && $cep!= ''){
            $cep = (trim($cep));
        }




        if(!JSession::checkToken()){
			JLog::add('Token inválido ao tentar salvar parceiro', JLog::DEBUG, 'com-socialblade-parceiro');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_ERRO_TOKEN'), 'error');
			$this->parceiros();
			return;// Se o token expriou não valida o resto.
		}

		if($endereco==null || trim($endereco) == '' || strlen($endereco) < 3){
			JLog::add('Não enviou o endereco do pessoa', JLog::DEBUG, 'com-classificados-pessoaendereco');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_ENDERECO_OBRIGATORIO'), 'error');
			$isErro = true;
		}

        if($numero==null || trim($numero) == '' || strlen($numero) < 3 ){
            JLog::add('Não enviou o numero de endereco do pessoa', JLog::DEBUG, 'com-classificados-pessoaendereco');
            $app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_NUMERO_ENDERECO_OBRIGATORIO'), 'error');
            $isErro = true;
        }


        if($bairro==null || trim($bairro) == '' || strlen($bairro) < 3 ){
            JLog::add('Não enviou o bairro de endereco do pessoa', JLog::DEBUG, 'com-classificados-pessoaendereco');
            $app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_BAIRRO_ENDERECO_OBRIGATORIO'), 'error');
            $isErro = true;
        }

        if($cep==null || trim($cep) == '' || strlen($cep) < 3 ){
            JLog::add('Não enviou o numero de cep do pessoa', JLog::DEBUG, 'com-classificados-pessoaendereco');
            $app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_CEP_ENDERECO_OBRIGATORIO'), 'error');
            $isErro = true;
        }

        
        $exp = preg_match(ClassificadosControllerPessoaEndereco::REGEXP_CEP, $cep);

        if($exp===false || $exp<=0) {
			JLog::add('Enviou o cep inválido', JLog::DEBUG, 'com-classificados-pessoaemail');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_PESSOA_CEP_INVALIDO'), 'error');
			$isErro = true;

        }
 

		if($isErro){
			$this->endereco();
			return;
		}


		if($id != '' && $id != null && $id != 0 ){
            $fields = array(
                '`endereco` = ' . $db->quote($endereco),
                '`numero` = ' . $db->quote($numero),
                '`complemento` = ' . $db->quote($complemento),
                '`bairro` = ' . $db->quote($bairro),
                '`cep` = ' . $db->quote($cep),
                '`id_cidade` = ' . $db->quote($cidade),
                '`id_logradouro` = ' . $db->quote($logradouro),

                '`id_user_alterador`  = ' . $db->quote($user->id),
                '`ip_alterador`  = ' . $db->quote($_SERVER['REMOTE_ADDR']),
                '`ip_alterador_proxiado`  = ' . $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']),
                '`data_alterado` = NOW()' 
            );
            $conditions = array(
                '  `id` = ' . $id       ,
                '  `status` = \'A\''        ,
                '  `id_pessoa` = ' . $user->id
            );
			$query = $db->getQuery(true);
			$query->update(ClassificadosControllerPessoaEndereco::TB_ENDERECOPESSOA)->set($fields)->where($conditions);

			$db->setQuery($query);
            $db->execute();
        }
        else { //INCLUSÃO
            $query = $db->getQuery(true);
            


            $columns = array('endereco','numero', 'complemento', 'bairro','cep','id_cidade','id_logradouro', 'id_pessoa',
			'status', 'id_user_criador', 'ip_criador', 'ip_criador_proxiado', 'data_criado');
            $values = array(
                $db->quote($endereco),
                $db->quote($numero),
                $db->quote($complemento),
                $db->quote($bairro),
                $db->quote($cep),
                $db->quote($cidade),
                $db->quote($logradouro),

                $db->quote($user->id), 
                $db->quote(ClassificadosControllerPessoaEndereco::STATUS_ATIVO),
                $db->quote($user->id), 
                $db->quote($_SERVER['REMOTE_ADDR']), 
                $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']), 
                'NOW()');
            
            $query
                ->insert(ClassificadosControllerPessoaEndereco::TB_ENDERECOPESSOA)
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
            $db->setQuery($query);
            $db->execute();
        }





        $app->redirect(JRoute::_( 'index.php?option=com_classificados&task=pessoa.meusdados&t=endereco&Itemid='.$itemid , false ), "");
    }
}