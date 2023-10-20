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

$app = JFactory::getApplication();
$input = $app->input; 

?>
<form action="index.php?option=com_classificados" method="post" name="adminForm" id="adminForm" class="form-validate">
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_classificados" />
	<input type="hidden" name="id" value="<?=$input->get('nome', '', 'int')?>" />
	<?php echo HTMLHelper::_('form.token'); ?>


    <div class="form-group">
        <label for="nome">Tipo produto</label>
        <input type="text" class="form-control required form-control-danger isValid" id="nome" name="nome" placeholder="Ex: Flores " required="required" minlength="3" value="<?=$input->get('nome', '', 'string')?>"/>
        <small id="emailHelp" class="form-text text-muted">Digite o tipo de produto que quer salvar, campo obrigat&oacute;rio e m&iacute;nimo 3 caracteres.</small>
    </div>

</form>