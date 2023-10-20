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

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var ClassificadosViewClassificadoss $this */

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('formbehavior.chosen');
/*
$listOrder     = $this->escape($this->state->get('list.ordering'));
$listDirection = $this->escape($this->state->get('list.direction'));
$loggedInUser  = Factory::getUser();

*/

$app = JFactory::getApplication();
$document = JFactory::getDocument();
$input = $app->input;

$itens = $input->get('itens', array(), 'array') ;


?>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_classificados" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo HTMLHelper::_('form.token'); ?>



<table class="table table-striped table-bordered table-hover">

<caption><?= JText::_('COM_CLASSIFICADOS_TIPO_PRODUTO') ?></caption>
  <thead  class="thead-dark">
    <tr>

		<th  scope="col"  width="1%" class="nowrap center">
			<?php echo HTMLHelper::_('grid.checkall'); ?>
		</th>
		<th  scope="col"  width="1%" class="nowrap center">
			<?php echo HTMLHelper::_('searchtools.sort', 'TIPO_PRODUTO_NOME', 'items.nome', $listDirection, $listOrder); ?>
		</th>
    </tr>
  </thead>
  <tbody>
<?php 
	$canEdit   = $this->canDo->get('core.edit');
	$canChange = true;//$loggedInUser->authorise('core.edit.state',	'com_classificados');

	foreach($itens as $i => $item) : ?>
	<tr>
		<td scope="row" class="center">
		<?php if ($canEdit || $canChange) : 
			echo HTMLHelper::_('grid.id', $i, $item->id); 
			
		endif; ?>

		</td>
		<td><?= $item->nome ?></td>
	</tr>
<?php endforeach; ?>
  </tbody>
  <tfoot>
	<tr>
		<td colspan="15">
			<?php 
			//echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
	</tfoot>
</table>

</form>
