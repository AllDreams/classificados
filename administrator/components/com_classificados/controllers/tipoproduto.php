<?php
/**
 * @package    classificados
 *
 * @author     jorge <your@email.com>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       http://your.url.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\SearchHelper;

/**
 * Classificadoss Controller.
 *
 * @package  classificados
 * @since    1.0.0
 */
class ClassificadosControllerTipoproduto extends AdminController
{


	const TB_TIPO_PRODUTO = '`#__tipo_produto`';
	const TB_PRODUTO = '`#__produto`';
    const STATUS_ATIVO = 'A';
    const STATUS_REMOVER = 'R';



    public function lista(){


        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$input = $app->input;
		$user = JFactory::getUser();

        $nome = $app->get('nome', null, 'string', 'post');


        $query = $db->getQuery ( true );
        $query->select('`t`.`nome`, `t`.`id`')
            ->from(ClassificadosControllerTipoproduto::TB_TIPO_PRODUTO . ' AS t')
            ->where('`t`.`status`  = ' . $db->quote(ClassificadosControllerTipoproduto::STATUS_ATIVO))
			->where( '`t`.`id_user_criador` = ' . $user->id);
        if($nome != null && ! empty($nome)){
            $query->where('upper(`t`.`nome`)  like ' . $db->quote(strtoupper(trim($tipoProduto )). '%'));
        }
        $query->order('t.nome');


        $db->setQuery ($query);
        $input->set( 'itens',$db->loadObjectList());


        $input->set( 'view', 'tipoproduto' );
		$input->set('layout', 'default' );
		parent::display (true);
    }

	public function remover(){
		$db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$input = $app->input;
		if(!JSession::checkToken()){
			JLog::add('Token inválido ao tentar remover tipo produto', JLog::DEBUG, 'com-classificado-tipoProduto');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_ERRO_TOKEN'), 'error');
			$this->parceiros();
			return;// Se o token expriou não valida o resto.
		}




        $ids = $input->get('cid', array(), 'array');

		print_r($ids);die();
		foreach($ids as $id){

			$query = $db->getQuery ( true );
			$query->select("`nome`,`id`")
				->from(ClassificadosControllerTipoproduto::TB_PRODUTO)
				->where('`status`  = ' . $db->quote(ClassificadosControllerTipoproduto::STATUS_ATIVO))
				->where('`id_tipo_produto` = ' . $db->quote($id))
				->setLimit(1);
			$db->setQuery ($query);
			$item = $db->loadObject();

			if($item){
				JLog::add('Tentou remover um tipo de produto que já está em um uso por um produto.', JLog::DEBUG, 'com-classificado-tipoProduto');
				$app->enqueueMessage(JText::printf('COM_CLASSIFICADOS_TIPO_PRODUTO_EM_USO', $db->nome), 'error');
			}
			else{
				// Prepare the insert query.
				/*$query = $db->getQuery ( true );
				$query
				->delete($db->quoteName(ClassificadosControllerTipoproduto::TB_TIPO_PRODUTO))
				->where('`id` = ' . $db->quote($id))
				->values(implode(',', $values));
					
				// Set the query using our newly populated query object and execute it.
				$db->setQuery($query);
				$db->execute();*/
				$fields = array(
					$db->quoteName('status') . ' = ' . ClassificadosControllerTipoproduto::STATUS_REMOVER,
	
					//Campos de controle de alteração
					$db->quoteName('id_user_alterador') . ' = ' . $db->quote($user->id),
					$db->quoteName('ip_alterador') . ' = ' . $db->quote($_SERVER['REMOTE_ADDR']),
					$db->quoteName('ip_alterador_proxiado') . ' = ' . $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']),
					$db->quoteName('data_alterado') . ' = NOW()' 
				);
				$conditions = array(
					'  `id_criador` = ' . $user->id , 
					'  `id` = ' .  $db->quote($id)          );
	
				
				$query = $db->getQuery(true);
				
				$query->update(ClassificadosControllerTipoproduto::TB_TIPO_PRODUTO)->set($fields)->where($conditions);
	
			

				
			}
		}
		$this->lista();
    }

	public function carregarEditar(){

		$db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$input = $app->input;
		$user = JFactory::getUser();

		$id = $app->get('id', null, 'int');

		if($id != null){
			$query = $db->getQuery ( true );
			$query->select("`nome`,`id`")
				->from(ClassificadosControllerTipoproduto::TB_TIPO_PRODUTO)
				->where('`status`  = ' . $db->quote(ClassificadosControllerTipoproduto::STATUS_ATIVO))
				->where('`id` = ' . $db->quote($id))
				->where('`id_user_criador` = ' . $user->id);
			
			$db->setQuery ($query);
			$item = $db->loadObject();

			if($item){
				$input->set( 'nome', $item->nome);
				$input->set( 'id', $item->id);
				
			}

		}

        $input->set( 'view', 'tipoproduto' );
		$input->set('layout', 'form' );
		parent::display (true);
	}

    public function gravar(){
		$db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$input = $app->input;
		$user = JFactory::getUser();

		if(!JSession::checkToken()){
			JLog::add('Token inválido ao tentar salvar tipo produto', JLog::DEBUG, 'com-classificado-tipoProduto');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_ERRO_TOKEN'), 'error');
			$this->carregarEditar();
			return;// Se o token expriou não valida o resto.
		}





        $nome = $app->get('nome', null, 'string', 'post');
		$id = $app->get('id', null, 'int', 'post');
		

		die($nome);

		
		if($nome==null || trim($nome) == '' || strlen(trim($nome)) < 3){
			JLog::add('Não enviou o tipo produto ', JLog::DEBUG, 'com-classificados-tipo-produto');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_NOME_TIPO_PRODUTO_OBRIGATORIO'), 'error');
			$isErro = true;
		}


		$query = $db->getQuery ( true );
		$query->select("`nome`,`id`")
			->from(ClassificadosControllerTipoproduto::TB_TIPO_PRODUTO)
			->where('`status`  = ' . $db->quote(ClassificadosControllerTipoproduto::STATUS_ATIVO))
            ->where('`nome` = ' . $db->quote($nome));
		if($id != null){
			$query->where('`id` <> ' . $db->quote($id));
		}
		$db->setQuery ($query);
		$item = $db->loadObject();

		if($item != null || isset($item) ){
			JLog::add('Tipo de produto já cadastrado.', JLog::DEBUG, 'com-classificados-tipo-produto');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_NOME_TIPO_PRODUTO_OBRIGATORIO'), 'error');
			$isErro = true;
		}
		

		if($isErro){
			$this->carregarEditar();
			return;
		}
		







		$isCadastrado = !($id == null || empty($id) || $id == '' || $id == 0 );

		if($isCadastrado){
			// Create a new query object.
			$query = $db->getQuery(true);
			
			// Insert columns.
			$columns = array('nome', 'id_user_criador', 'ip_criador', 'ip_criador_proxiado', 'status', 'data_criado');
			
			// Insert values.
			$values = array($db->quote($nome),
							$user->id, 
							$db->quote($_SERVER['REQUEST_URI']), 
							$db->quote($_SERVER['REMOTE_ADDR']), 
							$db->quote($_SERVER['HTTP_X_FORWARDED_FOR']), 
							ClassificadosControllerTipoproduto::STATUS_ATIVO,
							'NOW()');
			
			// Prepare the insert query.
			$query
				->insert($db->quoteName(ClassificadosControllerTipoproduto::TB_TIPO_PRODUTO))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			
			// Set the query using our newly populated query object and execute it.
			$db->setQuery($query);
			$db->execute();

				
			$app->enqueueMessage(JText::_('INFORMACOES_CADASTRADAS'), 'info');
		}
		else{
            $fields = array(
                $db->quoteName('nome') . ' = ' . $db->quote(trim($nome)),

                //Campos de controle de alteração
                $db->quoteName('id_user_alterador') . ' = ' . $db->quote($user->id),
                $db->quoteName('ip_alterador') . ' = ' . $db->quote($_SERVER['REMOTE_ADDR']),
                $db->quoteName('ip_alterador_proxiado') . ' = ' . $db->quote($_SERVER['HTTP_X_FORWARDED_FOR']),
                $db->quoteName('data_alterado') . ' = NOW()' 
            );
            $conditions = array(
                '  `id` = ' . $user->id             );

            
			$query = $db->getQuery(true);
            
			$query->update(ClassificadosControllerTipoproduto::TB_TIPO_PRODUTO)->set($fields)->where($conditions);

		
            	
			$db->setQuery($query);
            $db->execute();

			$app->enqueueMessage(JText::_('INFORMACOES_ALTERADOS'), 'info');
		}




		$this->lista();
    }
}