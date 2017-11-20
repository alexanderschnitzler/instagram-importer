<?php

return call_user_func(function ($extensionKey) {
    $normalizedExtensionKey = str_replace('_', '', strtolower($extensionKey));
    $languageFile = 'Resources/Private/Language/locallang.xlf';
    $languageIdentifier = sprintf(
        'LLL:EXT:%s/%s:tx_%s_domain_model_%s',
        $extensionKey,
        $languageFile,
        $normalizedExtensionKey,
        'image'
    );

    return [
        'ctrl' => [
            'title'           => $languageIdentifier,
            'label'           => 'name',
            'label_alt'       => 'url',
            'label_alt_force' => true,
            'iconfile'        => 'EXT:' . $extensionKey . '/ext_icon.svg',
            'tstamp'          => 'tstamp',
            'crdate'          => 'crdate',
            'cruser_id'       => 'cruser_id',
            'adminOnly'       => true,
            'rootLevel'       => 1,
            'default_sortby'  => 'uid',
            'searchFields'    => 'name'
        ],
        'interface' => [
            'showRecordFieldList' => 'name, width, height, url, post'
        ],
        'columns' => [
            'name' => [
                'label' => $languageIdentifier . '.name',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'width' => [
                'label' => $languageIdentifier . '.width',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'height' => [
                'label' => $languageIdentifier . '.height',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'url' => [
                'label' => $languageIdentifier . '.url',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'post' => [
                'label' => $languageIdentifier . '.post',
                'config' => [
                    'type' => 'select',
                    'foreign_table' => 'tx_instagramimporter_domain_model_post',
                    'minitems' => 1,
                    'maxitems' => 1
                ]
            ]
        ],
        'types' => [
            '1' => ['showitem' => 'name, width, height, url, post']
        ],
        'palettes' => []
    ];
}, 'instagram_importer');
