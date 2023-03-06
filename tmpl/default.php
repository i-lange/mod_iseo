<?php
/**
 * @package    mod_iseo
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/mod_iseo
 * @copyright  (C) 2023 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Ilange\Component\Iseo\Site\Helper\RouteHelper;

defined('_JEXEC') or die;

/**
 * Список доступных переменных
 * @var stdClass $module
 * @var Joomla\CMS\Application\CMSApplicationInterface $app
 * @var Joomla\Input\Input $input
 * @var Joomla\Registry\Registry $params
 * @var stdClass $template
 * @var Joomla\CMS\WebAsset\WebAssetManager $wa
 */

$formId = 'i-seo-' . $module->id;
    
$wa->getRegistry()->addRegistryFile('media/com_iseo/joomla.asset.json');
$wa->getRegistry()->addRegistryFile('media/mod_iseo/joomla.asset.json');

if ($params->get('use_js')) {
    $wa->useScript('com_iseo.front.min');
    //$wa->useScript('mod_iseo.front.min');
}

if ($params->get('use_css')) {
    //$wa->useStyle('com_iseo.front.min');
    $wa->useStyle('mod_iseo.front.min');
}
?>
<div class="mod_iseo">
<?php if ($params->get('show_button')) : ?>
    <h3><?php echo Text::_('MOD_ISEO_AUTO_TITLE'); ?></h3>
<?php endif; ?>
    <form class=""
          id="<?php echo $formId; ?>" 
          action="<?php echo Route::_(RouteHelper::getOnlineRoute()); ?>"
          method="post"
          data-iseo-form>
        <fieldset class="mb-3">
            <label class="form-label"
                   for="url-<?php echo $formId; ?>"><?php echo Text::_('MOD_ISEO_AUTO_URL_LABEL'); ?></label>
            <input class="form-control"
                   id="url-<?php echo $formId; ?>"
                   type="text"
                   name="url"
                   placeholder="<?php echo Text::_('MOD_ISEO_AUTO_URL_PLACEHOLDER'); ?>"
                   required
                   aria-describedby="url-help-<?php echo $formId; ?>">
            <div class="invalid-feedback"><?php echo Text::_('MOD_ISEO_AUTO_URL_INVALID'); ?></div>
            <div class="form-text"
                 id="url-help-<?php echo $formId; ?>"><?php echo Text::_('MOD_ISEO_AUTO_URL_HELP'); ?></div>
            <?php echo HTMLHelper::_('form.token'); ?>
        </fieldset>
        <?php if ($params->get('show_button')) : ?>
            <button class="btn btn-primary mb-3" 
                    type="submit"><?php echo Text::_('MOD_ISEO_AUTO_BTN'); ?></button>
        <?php endif; ?>
        <div class="mod_iseo_spinner">
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden"><?php echo Text::_('MOD_ISEO_AUTO_LOADING'); ?></span>
                </div>
            </div>
        </div>
    </form>
</div>
<?php if ($params->get('show_text')) : ?>
<div class="blockquote mt-3">
    <p><?php echo Text::_('MOD_ISEO_AUTO_TXT'); ?></p>
</div>
<?php endif; ?>    