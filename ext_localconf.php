<?php
defined('TYPO3_MODE') or die('Access denied');

require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('instagram_importer', 'vendor/autoload.php');

/** @var string $_EXTKEY */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Schnitzler\InstagramImporter\Tasks\ImportTask::class] = [
    'extension'        => $_EXTKEY,
    'title'            => 'LLL:EXT:instagram_importer/Resources/Private/Language/locallang.xlf:task.title',
    'description'      => 'LLL:EXT:instagram_importer/Resources/Private/Language/locallang.xlf:task.description',
    'additionalFields' => null
];
