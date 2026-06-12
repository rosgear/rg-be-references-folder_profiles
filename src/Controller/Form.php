<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\References\FolderProfiles\Controller;

use Ge;
use Ge\Panel\Controller\FormController;
use Rg\Backend\References\FolderProfiles\Widget\EditWindow;
use Rg\Backend\References\FolderProfiles\Model\FolderProfile;

/**
 * Контроллер формы профиля медиапапки.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\References\FolderProfiles\Controller
 * @since 1.0
 */
class Form extends FormController
{
    /**
     * Тип медиаданных.
     * 
     * @var array
     */
    protected array $mediaType = [];

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_BEFORE_ACTION, function ($controller, $action, &$result) {
                switch ($action) {
                    case 'view': 
                        /** @var int $typeId */
                        $typeId = Ge::$app->request->getQuery('type', 0, 'int');
                        if ($typeId) {
                            /** @var array|false mediaType */
                            $mediaType = $this->defineMediaType($typeId);
                            if ($mediaType === false)
                                $result = false;
                            else
                                $this->mediaType = $mediaType;
                            return;
                        } 

                        // попытка получить `type` (если он не указан) из профиля медиапапки
                        /** @var int $profileId Идентификатор редактируемой записи */
                        $profileId = (int) Ge::$app->router->get('id');
                        // если редактирование
                        if ($profileId) {
                            /** @var FolderProfile|null $profile*/
                            $profile = (new FolderProfile())->get($profileId);
                            if ($profile && $profile->typeId) {
                                /** @var array|false mediaType */
                                $mediaType = $this->defineMediaType($profile->typeId);
                                if ($mediaType === false)
                                    $result = false;
                                else
                                    $this->mediaType = $mediaType;
                                return;
                            }
                        }
                        $this->getResponse()
                            ->meta->error(Ge::t('app', 'Parameter "{0}" not specified', ['type']));
                        $result = false;
                        break;
                }
            });
    }

    /**
     * Определяет атрибуты типа медиаданных.
     * 
     * @param int $typeId Идентификатор типа медиаданных.
     * 
     * @return array|false
     */
    protected function defineMediaType(int $typeId): false|array
    {
        /** @var \Rg\Backend\References\MediaTypes\Model\MediaType|null $mediaType */
        $mediaType = Ge::$app->extensions->getModel('MediaType', 'rg.be.references.media_types');
        if ($mediaType === null) {
            $this->getResponse()
                ->meta->error(Ge::t('app', 'Could not defined data model "{0}"', ['MediaType']));
            return false;
        }

        /** @var \Rg\Backend\References\MediaTypes\Model\MediaType|null $mediaType */
        $mediaType = $mediaType->get($typeId);
        if ($mediaType === null) {
            $this->getResponse()
                ->meta->error(Ge::t('app', 'Parameter passed incorrectly "{0}"', ['type']));
            return false;
        }
        return $mediaType->getAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): EditWindow
    {
        return new EditWindow(['mediaType' => $this->mediaType]);
    }
}
