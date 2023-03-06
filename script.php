<?php
/**
 * @package    mod_iseo
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/mod_iseo
 * @copyright  (C) 2023 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

class Mod_IseoInstallerScript extends InstallerScript
{
    /**
     * Минимальная версия PHP, необходимая для установки модуля
     * @var string
     * @since 1.0.0
     */
    protected $minimumPhp = '7.2';

    /**
     * Минимальная версия Joomla, необходимая для установки модуля
     * @var string
     * @since 1.0.0
     */
    protected $minimumJoomla = '4.2.0';

    /**
     * Список файлов, которые необходимо удалить
     * @var array
     * @since 1.0.0
     */
    protected $deleteFiles = [];

    /**
     * Список папок, которые необходимо удалить
     * @var array
     * @since 1.0.0
     */
    protected $deleteFolders = [];

    /**
     * Объект приложения
     * @var object
     * @since 1.0.0
     */
    protected $app = null;

    /**
     * Конструктор
     * @throws Exception
     * @since 1.0.0
     */
    public function __construct()
    {
        // Получаем объект приложения
        $this->app = Factory::getApplication();
    }


    /**
     * Метод запускается непосредственно перед установкой/обновлением/удалением модуля
     * @param string $type Тип действия, которое выполняется (install|uninstall|discover_install|update)
     * @param InstallerAdapter $parent Класс, вызывающий этот метод.
     * @return boolean Возвращает True для продолжения, False для отмены установки/обновления/удаления
     * @throws Exception
     * @since 1.0.0
     */
    public function preflight($type, $parent): bool
    {
        if (!parent::preflight($type, $parent)) {
            return false;
        }

        // Проверяем, установлен ли компонент com_audit, без него модуль бесполезен
        if ($type === 'install') {
            if (!file_exists(JPATH_ADMINISTRATOR . '/components/com_iseo/')) {
                $this->app->enqueueMessage(Text::_('MOD_ISEO_XML_NO_COM_AUDIT'), 'error');

                return false;
            }
        }

        return true;
    }

    /**
     * Метод запускается непосредственно после установки/обновления/удаления модуля
     * @param string $type Тип действия, которое выполняется (install|uninstall|discover_install|update)
     * @param InstallerAdapter $parent Класс, вызывающий этот метод.
     * @return boolean True при успешном выполнении
     * @throws Exception
     * @since 1.0.0
     */
    public function postflight(string $type, InstallerAdapter $parent): bool
    {
        // Удаляем файлы и папки, в которых больше нет необходимости
        $this->removeFiles();

        if ($type === 'update') {
            // Получаем данные из xml файла модуля
            $xml = $parent->getManifest();

            // Пишем сообщение со ссылками на сайт автора и на репозиторий
            $message[] = '<p class="fs-2 mb-2">' . Text::_('MOD_ISEO') . ' [' . $xml->name . ']</p>';
            $message[] = '<ul>';
            $message[] = '<li>' . Text::_('MOD_ISEO_VERSION') . ': ' . $xml->version . '</li>';
            $message[] = '<li>' . Text::_('MOD_ISEO_AUTHOR') . ': ' . $xml->author . '</li>';
            $message[] = "<li><a href='https://ilange.ru' target='_blank'>https://ilange.ru</a></li>";
            $message[] = "<li><a href='https://github.com/i-lange/" . $xml->name . "' target='_blank'>GitHub</a></li>";
            $message[] = '</ul>';
            $message[] = '<p class="mb-2">' . Text::_('MOD_ISEO_DONATE') . ': </p>';
            $message[] = "<a href='" . Text::_('MOD_ISEO_DONATE_URL') 
                . "' target='_blank' class='btn btn-primary'>" . Text::_('MOD_ISEO_DONATE_BTN') . "</a>";
            $msgStr = implode($message);

            // Показываем сообщение
            echo $msgStr;
        } elseif ($type === 'uninstall') {
            $this->app->enqueueMessage(Text::_('MOD_ISEO_XML_UNINSTALL_OK'), 'warning');
        }

        return true;
    }
}