<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\References\FolderProfiles\Widget;

use Ge;
use Ge\Config\Mimes;

/**
 * Виджет для формирования интерфейса окна редактирования записи.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\References\FolderProfiles\Widget
 * @since 1.0
 */
class EditWindow extends \Ge\Panel\Widget\EditWindow
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
    public array $passParams = ['mediaType'];

    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        // панель формы (Ge.view.form.Panel GeJS)
        $this->form->autoScroll = true;
        $this->form->router->route = $this->creator->route('/form');
        if ($this->mediaType) {
            $this->form->loadJSONFile('/' . $this->mediaType['type'] . '-form', 'items', [
                '@mimes'  => $this->getMimes()->toList(),
                '@typeId' => $this->mediaType['id'],
                '@type'   => $this->mediaType['type']
            ]);
        }

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $this->title = $this->creator->t('{form.title}', [$this->mediaType['name']]);
        $this->width = 600;
        $this->height = 800;
        /** @var int $rowId Идентификатор редактируемой записи */
        // если редактирование
        if ($rowId = $this->getRowID()) {
            $this->tools = [
                [
                    'type'            => 'pin',
                    'callback'        => 'windowModalRemove',
                    'tooltip'         => '#Remove window modality',
                    'msgNotification' => '#The window modality has already been removed'
                ]
            ];
            // для уникальности идентификаторов окна и формы
            $this->viewIDPrefix = $rowId;
            $this->form->viewIDPrefix = $rowId;
        }

        $this->responsiveConfig = [
            'height < 800' => ['height' => '99%'],
            'width < 700' => ['height' => '99%'],
        ];
        $this->layout = 'fit';
        $this
            ->addCss(GE_DEBUG ? '/form.css' : '/form.min.css');
    }

    /**
     * @see EditWindow::getMimes()
     * 
     * @var Mimes
     */
    protected Mimes $mimes;

    /**
     * Возвращает определитель MIME-тип содержимого файла.
     *
     * @return Mimes
     */
    public function getMimes(): Mimes
    {
        if (!isset($this->mimes)) {
            $this->mimes = Ge::$services->getAs('mimes');
        }
        return $this->mimes;
    }
}
