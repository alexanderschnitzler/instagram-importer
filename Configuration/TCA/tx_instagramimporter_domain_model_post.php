<?php

return call_user_func(function ($extensionKey) {
    $normalizedExtensionKey = str_replace('_', '', strtolower($extensionKey));
    $languageFile = 'Resources/Private/Language/locallang.xlf';
    $languageIdentifier = sprintf(
        'LLL:EXT:%s/%s:tx_%s_domain_model_%s',
        $extensionKey,
        $languageFile,
        $normalizedExtensionKey,
        'post'
    );

    return [
        'ctrl' => [
            'title'           => $languageIdentifier,
            'label'           => 'uid',
            'label_alt'       => 'id',
            'label_alt_force' => true,
            'iconfile'        => 'EXT:' . $extensionKey . '/ext_icon.svg',
            'tstamp'          => 'tstamp',
            'crdate'          => 'crdate',
            'cruser_id'       => 'cruser_id',
            'enablecolumns'   => [
                'disabled' => 'disable'
            ],
            'adminOnly'       => true,
            'rootLevel'       => 1,
            'default_sortby'  => 'uid',
            'searchFields'    => 'uid'
        ],
        'interface' => [
            'showRecordFieldList' => 'id, user, images, created_time, likes, tags, filter, comments, type, link, location'
        ],
        'columns' => [
            'id' => [
                'label' => $languageIdentifier . '.id',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'user' => [
                'label' => $languageIdentifier . '.user',
                'config' => [
                    'type' => 'select',
                    'foreign_table' => 'tx_instagramimporter_domain_model_user',
                    'minitems' => 0,
                    'maxitems' => 1
                ]
            ],
            'images' => [
                'label' => $languageIdentifier . '.images',
                'config' => [
                    'type' => 'inline',
                    'foreign_table' => 'tx_instagramimporter_domain_model_image',
                    'foreign_field' => 'post'
                ]
            ],
            'created_time' => [
                'label' => $languageIdentifier . '.created_time',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'likes' => [
                'label' => $languageIdentifier . '.likes',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'tags' => [
                'label' => $languageIdentifier . '.tags',
                'config' => [
                    'type' => 'select',
                    'foreign_table' => 'tx_instagramimporter_domain_model_tag',
                    'MM' => 'tx_instagramimporter_domain_model_post_tag',
                    'multiple' => true,
                    'minitems' => 0,
                    'maxitems' => 99
                ]
            ],
            'filter' => [
                'label' => $languageIdentifier . '.filter',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'comments' => [
                'label' => $languageIdentifier . '.comments',
                'config' => [
                    'type' => 'inline',
                    'foreign_table' => 'tx_instagramimporter_domain_model_comment'
                ]
            ],
            'type' => [
                'label' => $languageIdentifier . '.type',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'link' => [
                'label' => $languageIdentifier . '.link',
                'config' => [
                    'type' => 'input'
                ]
            ],
            'location' => [
                'label' => $languageIdentifier . '.location',
                'config' => [
                    'type' => 'select',
                    'foreign_table' => 'tx_instagramimporter_domain_model_location',
                    'items' => [
                        ['', 0]
                    ],
                    'minitems' => 0,
                    'maxitems' => 1
                ]
            ]
        ],
        'types' => [
            '1' => ['showitem' => 'id, user, images, created_time, likes, tags, filter, comments, type, link, location']
        ],
        'palettes' => []
    ];
}, 'instagram_importer');
