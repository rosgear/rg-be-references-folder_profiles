<?php
/**
 * Расширение модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\References\FolderProfiles;

/**
 * Расширение "Профили медиапапок".
 * 
 * Профили настроек отображения медиапапок.
 * 
 * Расширение принадлежит модулю "Справочники".
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\References\FolderProfiles
 * @since 1.0
 */
class Extension extends \Ge\Panel\Extension\Extension
{
    /**
     * {@inheritdoc}
     */
    public string $id = 'rg.be.references.folder_profiles';

    /**
     * {@inheritdoc}
     */
    public string $defaultController = 'grid';
}