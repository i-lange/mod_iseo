<?php
/**
 * @package    mod_iseo
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/mod_iseo
 * @copyright  (C) 2023 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

namespace Ilange\Module\Iseo\Site\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Input\Json;
use Joomla\CMS\Language\Text;
use Ilange\Component\Iseo\Administrator\Helper\IseoHelper;
use Ilange\Component\Iseo\Site\Helper\RouteHelper;

defined('_JEXEC') or die;

/**
 * Класс Helper для модуля mod_iseo
 * @since 1.0.0
 */
class IseoModuleHelper
{
    /**
     * Метод принимающий Ajax запрос
     * @throws
     * @since 1.0.0
     */
    public static function getAjax()
    {
        if (!Session::checkToken()) {
            IseoHelper::setResponse(
                'success',
                [],
                Text::_('JINVALID_TOKEN'));
        }

        $fields = [];
        $data = new Json();
        $input_data = $data->getArray();

        // Получаем необходимые поля формы
        if (isset($input_data['fields']['url'])) {
            $fields['url'] = filter_var($input_data['fields']['url'], FILTER_SANITIZE_URL);
        }
        //$fields['email'] = filter_var($input_data['fields']['email'], FILTER_SANITIZE_EMAIL);

        // Валидация полученных полей
        // $validations[0] - ошибки; $validations[1] - поля без ошибок
        $validations = IseoHelper::validationFields($fields);
        if (count($validations[0]) === 0) {
            $header = get_headers($fields['url'])[0];
            if (!strpos($header, ' 200 ')) {
                // Если URL не отдает 200 - возвращаем сообщение об ошибке
                $validations[0][] = IseoHelper::setError('header', $header, 'MOD_ISEO_ERROR_URL_NOT_OK');
                IseoHelper::setResponse(
                    'danger',
                    $validations,
                    Text::_('MOD_ISEO_ERROR_UNAVAILABLE'));
            } else {
                // Если ошибок нет и адрес доступен - вызываем метод модели
                // для проведения аудита и сохранения в базу
                $model = Factory::getApplication()
                    ->bootComponent('com_iseo')
                    ->getMVCFactory()
                    ->createModel('Online', 'Site', ['ignore_request' => true]);
                $redirect = $model->getNewItem($fields['url']);
                
                if ($redirect) {
                    $redirect = Route::_(RouteHelper::getOnlineRoute($redirect));
                }
                IseoHelper::setResponse(
                    'success',
                    $validations,
                    Text::_('MOD_ISEO_ERROR_NO'),
                    $redirect);
            }
        } else {
            // Если есть ошибки валидации - даем соответсвующий ответ
            IseoHelper::setResponse(
                'warning',
                $validations,
                Text::_('MOD_ISEO_ERROR_FIELDS'));
        }
    }
}