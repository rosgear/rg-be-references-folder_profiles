<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * Файл конфигурации установки расширения.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

return [
    'id'          => 'rg.be.references.folder_profiles',
    'moduleId'    => 'rg.be.references',
    'name'        => 'Media folder profiles',
    'description' => 'Media folder display settings profiles',
    'namespace'   => 'Rg\Backend\References\FolderProfiles',
    'path'        => '/rg/rg.be.references.folder_profiles',
    'route'       => 'folder-profiles',
    'locales'     => ['ru_RU', 'en_GB'],
    'permissions' => ['any', 'view', 'read', 'viewAudit', 'writeAudit', 'info'],
    'events'      => [],
    'required'    => [
        ['php', 'version' => '8.2'],
        ['app', 'code' => 'RG Workspace'],
        ['app', 'code' => 'RG CMS'],
        ['app', 'code' => 'RG CRM'],
        ['module', 'id' => 'rg.be.references'],
        ['extension', 'id' => 'rg.be.references.media_types']
    ]
];
