<?php
namespace Schnitzler\InstagramImporter;

use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Schnitzler\InstagramImporter\Importer
 */
class Importer
{
    const TABLE_IMAGE = 'tx_instagramimporter_domain_model_image';
    const TABLE_LOCATION = 'tx_instagramimporter_domain_model_location';
    const TABLE_POST = 'tx_instagramimporter_domain_model_post';
    const TABLE_TAG = 'tx_instagramimporter_domain_model_tag';
    const TABLE_USER = 'tx_instagramimporter_domain_model_user';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ConnectionPool
     */
    private $connectionPool;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
    }

    /**
     * @return bool
     */
    public function import(string $accessToken)
    {
        // @todo: Put this code into a Client class or so
        $uri = new Uri('https://api.instagram.com/v1/users/self/media/recent');
        $uri = $uri->withQuery('access_token=' . $accessToken);

        /** @var RequestFactory $requestFactory */
        $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
        $response = $requestFactory->request($uri);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(
                'Not a 200 response',
                1511187307,
                new \HttpResponseException(
                    $response->getReasonPhrase(),
                    $response->getStatusCode()
                )
            );
        }

        $content = json_decode($response->getBody()->getContents(), true);

//        $jsonFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('instagram_importer', 'foo.json');
//        $content = json_decode(file_get_contents($jsonFile), true);

        $queryBuilder = $this->connectionPool
            ->getConnectionForTable(static::TABLE_POST)
            ->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();

        /** @var array|array[] $content */
        foreach ($content['data'] as $post) {
            $query = $queryBuilder->count('*')
                ->from(static::TABLE_POST)
                ->where(
                    $queryBuilder->expr()->eq(
                        'id',
                        $queryBuilder->quote($post['id'], \PDO::PARAM_STR)
                    )
                );

            if ((int)$query->execute()->fetchColumn() === 1) {
                $this->logger->debug('Skip creation of post record as it already exists');
                continue;
            }

            $uid = uniqid('NEW', true);
            $data = [];
            $data[static::TABLE_POST][$uid] = [
                'pid'          => 0,
                'id'           => $post['id'],
                'created_time' => $post['created_time'],
                'likes'        => $post['likes']['count'],
                'filter'       => $post['filter'],
                'link'         => $post['link'],
                'type'         => $post['type']
            ];

            if (isset($post['user']['id'])) {
                ArrayUtility::mergeRecursiveWithOverrule($data, $this->fetchUserData($uid, $post['user']));
            }

            if (isset($post['images']) && count($post['images']) > 0) {
                ArrayUtility::mergeRecursiveWithOverrule($data, $this->fetchImageData($uid, $post['images']));
            }

            if (isset($post['location']['id'])) {
                ArrayUtility::mergeRecursiveWithOverrule($data, $this->fetchLocationData($uid, $post['location']));
            }

            if (!empty($post['tags'])) {
                ArrayUtility::mergeRecursiveWithOverrule($data, $this->fetchTagData($uid, $post['tags']));
            }

            /** @var DataHandler $dataHandler */
            $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
            $dataHandler->start($data, []);
            $dataHandler->process_datamap();

            if (count($dataHandler->errorLog) > 0) {
                $this->logger->error('Post could not be created', ['id' => $post['id']]);

                foreach ($dataHandler->errorLog as $message) {
                    $this->logger->debug('DataHandler: ' . $message);
                }
            }
        }

        return true;
    }

    /**
     * @param string $postId
     * @param array $user
     * @return array
     */
    private function fetchUserData(string $postId, array $user)
    {
        $data = [];
        $queryBuilder = $this->connectionPool
            ->getConnectionForTable(static::TABLE_USER)
            ->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();

        $query = $queryBuilder->select('uid')
            ->from(static::TABLE_USER)
            ->where(
                $queryBuilder->expr()->eq(
                    'id',
                    $queryBuilder->quote($user['id'], \PDO::PARAM_STR)
                )
            );

        if (($userUid = (int)$query->execute()->fetchColumn()) === 0) {
            $userUid = uniqid('NEW', true);
            $data[static::TABLE_USER][$userUid] = [
                'pid'             => 0,
                'id'              => $user['id'],
                'full_name'       => $user['full_name'],
                'profile_picture' => $user['profile_picture'],
                'username'        => $user['username']
            ];
        }
        $data[static::TABLE_POST][$postId]['user'] = $userUid;

        return $data;
    }

    /**
     * @param string $postId
     * @param array $location
     * @return array
     */
    private function fetchLocationData(string $postId, array $location)
    {
        $data = [];
        $queryBuilder = $this->connectionPool
            ->getConnectionForTable(static::TABLE_USER)
            ->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();

        $query = $queryBuilder->select('uid')
            ->from(static::TABLE_LOCATION)
            ->where(
                $queryBuilder->expr()->eq(
                    'id',
                    $queryBuilder->quote($location['id'], \PDO::PARAM_STR)
                )
            );

        if (($locationUid = (int)$query->execute()->fetchColumn()) === 0) {
            $locationUid = uniqid('NEW', true);
            $data[static::TABLE_LOCATION][$locationUid] = [
                'pid'       => 0,
                'id'        => $location['id'],
                'name'      => $location['name'],
                'latitude'  => $location['latitude'],
                'longitude' => $location['longitude']
            ];
        }
        $data[static::TABLE_POST][$postId]['location'] = $locationUid;

        return $data;
    }

    /**
     * @param string $postId
     * @param array|string[] $tags
     * @return array
     */
    public function fetchTagData(string $postId, array $tags)
    {
        $data = [];
        $queryBuilder = $this->connectionPool
            ->getConnectionForTable(static::TABLE_TAG)
            ->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();

        $uids = [];
        foreach ($tags as $tag) {
            $query = $queryBuilder->select('uid')
                ->from(static::TABLE_TAG)
                ->where(
                    $queryBuilder->expr()->eq(
                        'name',
                        $queryBuilder->quote($tag, \PDO::PARAM_STR)
                    )
                );

            if (($tagUid = (int)$query->execute()->fetchColumn()) === 0) {
                $tagUid = uniqid('NEW', true);
                $data[static::TABLE_TAG][$tagUid] = [
                    'pid'  => 0,
                    'name' => $tag
                ];
            }

            $uids[] = $tagUid;
        }

        $data[static::TABLE_POST][$postId]['tags'] = implode(',', $uids);

        return $data;
    }

    /**
     * @param string $postId
     * @param array|array[] $images
     * @return array
     */
    public function fetchImageData(string $postId, array $images)
    {
        $data = [];
        $queryBuilder = $this->connectionPool
            ->getConnectionForTable(static::TABLE_IMAGE)
            ->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll();

        $uids = [];
        foreach ($images as $name => $image) {
            $uid = uniqid('NEW', true);
            $uids[] = $uid;

            $data[static::TABLE_IMAGE][$uid] = [
                'pid'    => 0,
                'name'   => $name,
                'width'  => $image['width'],
                'height' => $image['height'],
                'url'    => $image['url']
            ];
        }

        $data[static::TABLE_POST][$postId]['images'] = implode(',', $uids);

        return $data;
    }
}
