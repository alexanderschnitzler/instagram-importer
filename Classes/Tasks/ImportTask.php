<?php
namespace Schnitzler\InstagramImporter\Tasks;

use Schnitzler\InstagramImporter\Importer;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Class Schnitzler\InstagramImporter\Tasks\ImportTask
 */
class ImportTask extends AbstractTask
{
    /**
     * @return bool
     */
    public function execute()
    {
        /** @var LogManager $logManager */
        $logManager = GeneralUtility::makeInstance(LogManager::class);
        $logger = $logManager->getLogger(__CLASS__);

        $tableName = 'tx_instagramimporter_domain_model_accesstoken';

        $rows = (array)$this->getDatabaseConnection()->exec_SELECTgetRows(
            '*',
            $tableName,
            ''
        );

        $importer = GeneralUtility::makeInstance(Importer::class, $logger);
        foreach ($rows as $row) {
            $logger->debug('Start import with access token #' . $row['uid']);
            $importer->import($row['token']);
        }

        return true;
    }

    /**
     * @return DatabaseConnection
     */
    public function getDatabaseConnection(): DatabaseConnection
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
