<?php
/** @var array $EM_CONF */
/** @var string $_EXTKEY */
$EM_CONF[$_EXTKEY] = [
    'title'            => 'Instagram data importer',
    'description'      => 'This plugin imports data from instagram.',
    'category'         => 'be',
    'author'           => 'Alexander Schnitzler',
    'author_email'     => 'git@alexanderschnitzler.de',
    'author_company'   => 'Schnitzler Softwarelösungen',
    'state'            => 'stable',
    'uploadfolder'     => false,
    'createDirs'       => '',
    'clearCacheOnLoad' => true,
    'version'          => '7.6.0',
    'constraints'      => [
        'depends'   => [
            'typo3'   => '7.6.0-7.6.99'
        ],
        'conflicts' => [],
        'suggests'  => []
    ]
];
