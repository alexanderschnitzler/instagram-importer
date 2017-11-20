<?php
namespace Schnitzler\InstagramImporter\Tasks;

use Schnitzler\InstagramImporter\Importer;
use TYPO3\CMS\Core\Database\ConnectionPool;
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
        $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);

        $tableName = 'tx_instagramimporter_domain_model_accesstoken';

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($tableName)
            ->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();

        $query = $queryBuilder->select('*')->from($tableName);
        $rows = $query->execute()->fetchAll();

        $importer = GeneralUtility::makeInstance(Importer::class, $logger);
        foreach ($rows as $row) {
            $logger->debug('Start import with access token #' . $row['uid']);
            $importer->import($row['token']);
        }

        return true;
    }
}
