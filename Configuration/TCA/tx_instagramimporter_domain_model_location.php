<?php

return call_user_func(function ($extensionKey) {
    $normalizedExtensionKey = str_replace('_', '', strtolower($extensionKey));
    $languageFile = 'Resources/Private/Language/locallang.xlf';
    $languageIdentifier = sprintf(
        'LLL:EXT:%s/%s:tx_%s_domain_model_%s',
        $extensionKey,
        $languageFile,
        $normalizedExtensionKey,
        'location'
    );

    return [
        'ctrl' => [
            'title'           => $languageIdentifier,
            'label'           => 'name',
            'iconfile'        => 'EXT:' . $extensionKey . '/ext_icon.svg',
            'tstamp'          => 'tstamp',
            'crdate'          => 'crdate',
            'cruser_id'       => 'cruser_id',
            'adminOnly'       => true,
            'rootLevel'       => 1,
            'default_sortby'  => 'name',
            'searchFields'    => 'name'
        ],
        'interface' => [
            'showRecordFieldList' => 'id, name, latitude, longitude'
        ],
        'columns' => [
            'id' => [
                'label' => $languageIdentifier . '.id',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'name' => [
                'label' => $languageIdentifier . '.name',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'latitude' => [
                'label' => $languageIdentifier . '.latitude',
                'l10n_mode' => 'exclude',
                'l10n_display' => 'defaultAsReadonly',
                'config' => [
                    'type' => 'input',
                    'size' => 20,
                    'eval' => 'trim',
                    'max' => 30,
                    'default' => '0.00000000000000'
                ]
            ],
            'longitude' => [
                'label' => $languageIdentifier . '.longitude',
                'l10n_mode' => 'exclude',
                'l10n_display' => 'defaultAsReadonly',
                'config' => [
                    'type' => 'input',
                    'size' => 20,
                    'eval' => 'trim',
                    'max' => 30,
                    'default' => '0.00000000000000'
                ]
            ]
        ],
        'types' => [
            '1' => ['showitem' => 'id, name, latitude, longitude']
        ],
        'palettes' => []
    ];
}, 'instagram_importer');
