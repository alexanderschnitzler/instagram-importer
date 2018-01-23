<?php
namespace Schnitzler\InstagramImporter;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\DataHandling\DataHandler;
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
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $accessToken
     * @return bool
     */
    public function import(string $accessToken): bool
    {
        // @todo: Put this code into a Client class or so
        $uri = new Uri('https://api.instagram.com/v1/users/self/media/recent');
        $uri = $uri->withQuery('access_token=' . $accessToken . '&count=200');

        $client = new Client();
        $response = $client->get($uri);

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

        /** @var array|array[] $content */
        foreach ($content['data'] as $post) {
            $count = (int)$this->getDatabaseConnection()->exec_SELECTcountRows(
                '*',
                static::TABLE_POST,
                'id = ' . (int)$post['id']
            );

            if ($count === 1) {
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

            $backendUser = clone $GLOBALS['BE_USER'];
            $backendUser->user['admin'] = 1;

            /** @var DataHandler $dataHandler */
            $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
            $dataHandler->start($data, [], $backendUser);
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

        $row = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
            'uid',
            static::TABLE_USER,
            'id = ' . (int)$user['id']
        );

        if (is_array($row) && isset($row['uid'])) {
            $data[static::TABLE_POST][$postId]['user'] = (int)$row['uid'];
        } else {
            $userUid = uniqid('NEW', true);
            $data[static::TABLE_USER][$userUid] = [
                'pid'             => 0,
                'id'              => $user['id'],
                'full_name'       => $user['full_name'],
                'profile_picture' => $user['profile_picture'],
                'username'        => $user['username']
            ];
            $data[static::TABLE_POST][$postId]['user'] = $userUid;
        }

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

        $row = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
            'uid',
            static::TABLE_LOCATION,
            'id = ' . (int)$location['id']
        );

        if (is_array($row) && isset($row['uid'])) {
            $data[static::TABLE_POST][$postId]['location'] = (int)$row['uid'];
        } else {
            $locationUid = uniqid('NEW', true);
            $data[static::TABLE_LOCATION][$locationUid] = [
                'pid'       => 0,
                'id'        => $location['id'],
                'name'      => $location['name'],
                'latitude'  => $location['latitude'],
                'longitude' => $location['longitude']
            ];
            $data[static::TABLE_POST][$postId]['location'] = $locationUid;
        }

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
        $uids = [];
        foreach ($tags as $tag) {
            $row = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
                'uid',
                static::TABLE_TAG,
                'name = "' . $this->getDatabaseConnection()->quoteStr($tag, static::TABLE_TAG) . '"'
            );

            if (is_array($row) && isset($row['uid'])) {
                $uids[] = (int)$row['uid'];
            } else {
                $tagUid = uniqid('NEW', true);
                $data[static::TABLE_TAG][$tagUid] = [
                    'pid'  => 0,
                    'name' => $tag
                ];
                $uids[] = $tagUid;
            }
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

    /**
     * @return DatabaseConnection
     */
    public function getDatabaseConnection(): DatabaseConnection
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
