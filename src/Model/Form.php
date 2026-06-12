<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\References\FolderProfiles\Model;

use Ge\Helper\Json;
use Ge\Panel\Data\Model\FormModel;

/**
 * Модель данных профиля медиапапки.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\References\FolderProfiles\Model
 * @since 1.0
 */
class Form extends FormModel
{
    use FolderOptionsTrait;

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'useAudit'   => true,
            'tableName'  => '{{reference_folder_profiles}}',
            'primaryKey' => 'id',
            'fields'     => [
                ['id'],
                ['name'], // название
                ['type_id', 'alias' => 'typeId'], // идент. типа медиаданных
                ['type'], // тип медиаданных: image, file, document...
                ['options'], // параметры
                ['enabled'] // доступен
            ],
            // правила форматирования полей
            'formatterRules' => [
                [['enabled'], 'logic']
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                /** @var \Ge\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                // обновить список
                $controller->cmdReloadGrid();
            })
            ->on(self::EVENT_AFTER_DELETE, function ($result, $message) {
                /** @var \Ge\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * Выполняет добавление параметров формата для указанного ключа.
     * 
     * @param array $format Формат параметров.
     * @param array $options Параметры в виде пар "ключ - значение".
     * 
     * @return void
     */
    protected function addOptionsFromFormat(array $format, array $options): void
    {
        foreach ($format as $option) {
            $name = $option[0];
            $value = $options[$name] ?? $option['default'];
            $this->attributes['options[' . $name . ']'] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeLoad(array &$data): void
    {
        /** @var array $format Формат параметров */
        $format = $this->getOptionsFormat();
        // параметры
        $data['options'] = $this->formatOptions($format, $data['options'] ?? []);
    }

    /**
     * Возращает значение для сохранения в поле "options".
     * 
     * @return string
     */
    public function unOptions(): string
    {
        // allowedExtensions
        $option = (array) $this->options;
        if (!empty($option['allowedExtensions'])) {
            $value = json_decode($option['allowedExtensions']);
            $option['allowedExtensions'] = implode(',', $value);
        }
        return  Json::encode($option);
    }

    /**
     * {@inheritdoc}
     */
    public function processing(): void
    {
        parent::processing();

        // параметры
        if ($this->options) {
            $options = json_decode($this->options, true);
            $this->addOptionsFromFormat($this->getOptionsFormat(), $options);
        }
    }
}
