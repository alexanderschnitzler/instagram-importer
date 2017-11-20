<?php

return call_user_func(function ($extensionKey) {
    $normalizedExtensionKey = str_replace('_', '', strtolower($extensionKey));
    $languageFile = 'Resources/Private/Language/locallang.xlf';
    $languageIdentifier = sprintf(
        'LLL:EXT:%s/%s:tx_%s_domain_model_%s',
        $extensionKey,
        $languageFile,
        $normalizedExtensionKey,
        'accesstoken'
    );

    return [
        'ctrl' => [
            'title'          => $languageIdentifier,
            'label'          => 'uid',
            'iconfile'       => 'EXT:' . $extensionKey . '/ext_icon.svg',
            'tstamp'         => 'tstamp',
            'crdate'         => 'crdate',
            'cruser_id'      => 'cruser_id',
            'adminOnly'      => true,
            'rootLevel'      => 1,
            'default_sortby' => 'uid',
            'searchFields'   => 'description'
        ],
        'interface' => [
            'showRecordFieldList' => 'token, description'
        ],
        'columns' => [
            'token' => [
                'label' => $languageIdentifier . '.token',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'description' => [
                'label' => $languageIdentifier . '.description',
                'config' => [
                    'type' => 'text'
                ]
            ]
        ],
        'types' => [
            '1' => ['showitem' => 'token, description']
        ],
        'palettes' => []
    ];
}, 'instagram_importer');
