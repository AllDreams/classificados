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

/**
 * Classificadoss Controller.
 *
 * @package  classificados
 * @since    1.0.0
 */
class ClasificadosControllerTipoProduto extends AdminController
{


	const TB_TIPO_PRODUTO = '`#__tipo_produto`';
	const TB_PRODUTO = '`#__produto`';
    const STATUS_ATIVO = 'A';




    public function lista(){
        $db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$input = $app->input;
		$user = JFactory::getUser();

        $nome = $app->get('nome', null, 'string', 'post');


        $query = $db->getQuery ( true );
        $query->select('`t`.`nome`, `t`.`id`')
            ->from(ClasificadosControllerTipoProduto::TB_TIPO_PRODUTO . ' AS t')
            ->where('`t`.`status`  = ' . $db->quote(ClasificadosControllerTipoProduto::STATUS_ATIVO))
			->where('AND', '`t`.`id_user_criador` = ' . $user->id);
        if($nome != null && ! empty($nome)){
            $query->where('AND','upper(`t`.`nome`)  like ' . $db->quote(strtoupper(trim($tipoProduto )). '%'));
        }
        $query->order('t.nome');
        $db->setQuery ($query);
        $input->set( 'itens',$db->loadObjectList());


        $input->set( 'view', 'tipoProduto' );
		$input->set('layout', 'default' );
		parent::display (true);
    }

	public function remover(){
		$db = JFactory::getDbo ();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$input = $app->input;
		if(!JSession::checkToken()){
			JLog::add('Token inválido ao tentar remover tipo produto', JLog::DEBUG, 'com-classificado-tipoProduto');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_ERRO_TOKEN'), 'error');
			$this->parceiros();
			return;// Se o token expriou não valida o resto.
		}




        $id = $app->get('id', null, 'string', 'get');


		$query = $db->getQuery ( true );
		$query->select("`nome`,`id`")
			->from(ClasificadosControllerTipoProduto::TB_PRODUTO)
			->where('`status`  = ' . $db->quote(ClassificadosControllerBusca::STATUS_ATIVO))
            ->where('AND', '`id_tipo_produto` = ' . $db->quote($id))
			->limit(1);
		$db->setQuery ($query);
		$item = $db->loadObject();

		if($item){
			JLog::add('Tentou remover um tipo de produto que já está em um uso por um produto.', JLog::DEBUG, 'com-classificado-tipoProduto');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_TIPO_PRODUTO_EM_USO'), 'error');
			$this->lista();
			return;// Se o token expriou não valida o resto.
		}

		// Prepare the insert query.
		$query
		->delete($db->quoteName(ClasificadosControllerTipoProduto::TB_TIPO_PRODUTO))
		->where('id = ')
		->values(implode(',', $values));
			
		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		$db->execute();

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
				->from(ClasificadosControllerTipoProduto::TB_TIPO_PRODUTO)
				->where('`status`  = ' . $db->quote(ClassificadosControllerBusca::STATUS_ATIVO))
				->where('AND', '`id` = ' . $db->quote($id))
				->where('AND', '`id_user_criador` = ' . $user->id);
			
			$db->setQuery ($query);
			$item = $db->loadObject();

			if($item){
				$input->set( 'nome', $item->nome);
				$input->set( 'id', $item->id);
				
			}

		}

        $input->set( 'view', 'tipoProduto' );
		$input->set('layout', 'form' );
		parent::display (true);
	}

    public function adicionar(){
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
		

		if($nome==null || trim($nome) == '' || strlen(trim($nome)) < 3){
			JLog::add('Não enviou o tipo produto ', JLog::DEBUG, 'com-classificados-tipo-produto');
			$app->enqueueMessage(JText::_('COM_CLASSIFICADOS_NOME_TIPO_PRODUTO_OBRIGATORIO'), 'error');
			$isErro = true;
		}


		$query = $db->getQuery ( true );
		$query->select("`nome`,`id`")
			->from(ClasificadosControllerTipoProduto::TB_TIPO_PRODUTO)
			->where('`status`  = ' . $db->quote(ClassificadosControllerBusca::STATUS_ATIVO))
            ->where('AND', '`nome` = ' . $db->quote($nome));
		if($id != null){
			$query->where('AND', '`id` <> ' . $db->quote($id));
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
							ClassificadosControllerBusca::STATUS_ATIVO,
							'NOW()');
			
			// Prepare the insert query.
			$query
				->insert($db->quoteName(ClasificadosControllerTipoProduto::TB_TIPO_PRODUTO))
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
            
			$query->update(ClasificadosControllerTipoProduto::TB_TIPO_PRODUTO)->set($fields)->where($conditions);

		
            	
			$db->setQuery($query);
            $db->execute();

			$app->enqueueMessage(JText::_('INFORMACOES_ALTERADOS'), 'info');
		}




		$this->lista();
    }
}