<?php
/**
 * @package    mod_iseo
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/mod_iseo
 * @copyright  (C) 2023 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

namespace Ilange\Module\Iseo\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;

defined('JPATH_PLATFORM') or die;

/**
 * Класс распаковщик
 * @since 1.0.0
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    /**
     * Возвращает данные для отображения
     * Метод родителя возвращает 'module', 'app', 'input', 'params', 'template'
     * @return array|false
     * @since 1.0.0
     */
    protected function getLayoutData()
    {
        $data = parent::getLayoutData();

        $data['wa'] = $data['app']->getDocument()->getWebAssetManager();
        
        return $data;
    }
}
