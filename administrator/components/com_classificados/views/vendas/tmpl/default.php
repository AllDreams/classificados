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

$listOrder     = $this->escape($this->state->get('list.ordering'));
$listDirection = $this->escape($this->state->get('list.direction'));
$loggedInUser  = Factory::getUser();

?>

1
<form action="index.php?option=com_classificados&view=classificadoss" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="itemsList">
				<thead>
				<tr>
					<th width="1%" class="nowrap center">
						<?php echo HTMLHelper::_('grid.checkall'); ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'items.published', $listDirection, $listOrder); ?>
					</th>
					<th class="left">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_CLASSIFICADOS_CLASSIFICADOS_TITLE', 'items.title', $listDirection, $listOrder); ?>
					</th>
				</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="15">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
				</tfoot>
				<tbody>
				<?php
				$canEdit   = $this->canDo->get('core.edit');
				$canChange = $loggedInUser->authorise('core.edit.state',	'com_classificados');

				foreach ($this->items as $i => $item) :
					?>
					<tr>
						<td class="center">
							<?php if ($canEdit || $canChange) : ?>
								<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
							<?php endif; ?>
						</td>
						<td class="center">
							<div class="btn-group">
								<?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'classificadoss.', $canChange); ?>
							</div>
						</td>
						<td>
							<div class="name break-word">
								<?php if ($canEdit) : ?>
									<a href="<?php echo Route::_('index.php?option=com_classificados&task=classificados.edit&id=' . (int) $item->id); ?>" title="<?php echo Text::sprintf('COM_CLASSIFICADOSS_EDIT_CLASSIFICADOS', $this->escape($item->title)); ?>">
										<?php echo $this->escape($item->title); ?></a>
								<?php else : ?>
									<?php echo $this->escape($item->title); ?>
								<?php endif; ?>
								<div><small><?php echo $this->escape($item->alias); ?></small></div>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
