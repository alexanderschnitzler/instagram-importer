<?php
defined('TYPO3_MODE') or die('Access denied');

/** @var string $_EXTKEY */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Schnitzler\InstagramImporter\Tasks\ImportTask::class] = [
    'extension'        => $_EXTKEY,
    'title'            => 'LLL:EXT:instagram_importer/Resources/Private/Language/locallang_db.xlf:task.instagram.title',
    'description'      => 'LLL:EXT:instagram_importer/Resources/Private/Language/locallang_db.xlf:task.instagram.description',
    'additionalFields' => null
];
