<?php

return call_user_func(function ($extensionKey) {
    $normalizedExtensionKey = str_replace('_', '', strtolower($extensionKey));
    $languageFile = 'Resources/Private/Language/locallang.xlf';
    $languageIdentifier = sprintf(
        'LLL:EXT:%s/%s:tx_%s_domain_model_%s',
        $extensionKey,
        $languageFile,
        $normalizedExtensionKey,
        'tag'
    );

    return [
        'ctrl' => [
            'title'          => $languageIdentifier,
            'label'          => 'name',
            'iconfile'       => 'EXT:' . $extensionKey . '/ext_icon.svg',
            'tstamp'         => 'tstamp',
            'crdate'         => 'crdate',
            'cruser_id'      => 'cruser_id',
            'adminOnly'      => true,
            'rootLevel'      => 1,
            'default_sortby' => 'name',
            'searchFields'   => 'name'
        ],
        'interface' => [
            'showRecordFieldList' => 'name'
        ],
        'columns' => [
            'name' => [
                'label' => $languageIdentifier . '.name',
                'config' => [
                    'type' => 'input'
                ]
            ]
        ],
        'types' => [
            '1' => ['showitem' => 'name']
        ],
        'palettes' => []
    ];
}, 'instagram_importer');
