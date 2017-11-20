<?php

return call_user_func(function ($extensionKey) {
    $normalizedExtensionKey = str_replace('_', '', strtolower($extensionKey));
    $languageFile = 'Resources/Private/Language/locallang.xlf';
    $languageIdentifier = sprintf(
        'LLL:EXT:%s/%s:tx_%s_domain_model_%s',
        $extensionKey,
        $languageFile,
        $normalizedExtensionKey,
        'user'
    );

    return [
        'ctrl' => [
            'title'          => $languageIdentifier,
            'label'          => 'username',
            'iconfile'       => 'EXT:' . $extensionKey . '/ext_icon.svg',
            'tstamp'         => 'tstamp',
            'crdate'         => 'crdate',
            'cruser_id'      => 'cruser_id',
            'adminOnly'      => true,
            'rootLevel'      => 1,
            'default_sortby' => 'username',
            'searchFields'   => 'username, full_name'
        ],
        'interface' => [
            'showRecordFieldList' => implode(',', [
                'id',
                'username',
                'full_name',
                'profile_picture'
            ])
        ],
        'columns' => [
            'id' => [
                'label' => $languageIdentifier . '.id',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'username' => [
                'label' => $languageIdentifier . '.username',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'full_name' => [
                'label' => $languageIdentifier . '.full_name',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'profile_picture' => [
                'label' => $languageIdentifier . '.profile_picture',
                'config' => [
                    'type' => 'input'
                ]
            ]
        ],
        'types'    => [
            '1' => [
                'showitem' =>
                    implode(',', [
                        'id',
                        'username',
                        'full_name',
                        'profile_picture'
                    ])
            ]
        ],
        'palettes' => []
    ];
}, 'instagram_importer');
