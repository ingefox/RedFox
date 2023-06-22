<?php

namespace RFCore\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
//use ComFox\Models\M_Email;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use RFCore\Entities\E_NotificationSubscription;
use RFCore\Entities\E_User;
use RFCore\Models\RF_Model;
use RFCore\Entities\E_Notification;
use RFCore\Entities\E_Channel;
use RFCore\Entities\E_UserChannel;
use RFCore\Entities\E_NotificationChannel;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Class M_Notification
 * @package RFCore\Models
 */
class M_Notification extends RF_Model
{
	const VAPIDFile = APPPATH . 'Modules' . DIRECTORY_SEPARATOR . INTEGRATION_BASE_MODULE . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'vapid.json';

    protected $entityName;
    protected $userEntity;
    protected $userChannelEntity;
    protected $notificationChannelEntity;
    protected $channelEntity;

    protected $repository;

    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
        $this->entityName = 'RFCore\Entities\E_Notification';
        $this->userEntity = 'RFCore\Entities\E_User';
        $this->userChannelEntity = 'RFCore\Entities\E_UserChannel';
        $this->notificationChannelEntity = 'RFCore\Entities\E_NotificationChannel';
        $this->channelEntity = 'RFCore\Entities\E_Channel';
        $this->repository = parent::$em->getRepository('RFCore\Entities\E_Notification');
    }

    /**
     * Persist a Channel in the DB
     * @param $data array Data for the new Channel Entity
     * @return int Status Code
     */
    public function addChannel(array $data): int
    {
        $ret = SC_INTERNAL_SERVER_ERROR;

        try {
            // Checking that a channel with the same key doesn't already exists
            $channel = $this->findOneBy('key', $data['key']);
            if ($channel == null)
            {
                // No Channel found, processing with entity persistence ...
                $channel = new E_Channel($data);
                $this->persist($channel);
                $this->flush();
                $ret = SC_SUCCESS;
            } else
            {
                // A Channel with the same key has been found
                $ret = SC_DOCTRINE_DUPLICATE_ENTITY;
            }
        } catch (Exception|ORMException $e) {
            log_message('error', 'Error while persisting a Channel instance: '.$e);
        }

        return $ret;
    }


    /**
     * Subscribes a user to a given channel
     * @param $data array Data for joint Entity creation
     * @return int Status Code
     */
    public function subscribe(array $data): int
    {
        $ret = SC_INTERNAL_SERVER_ERROR;

        // Retrieving the corresponding user
        $data['user'] = $this->findOneBy('id', $data['user'], $this->userEntity);
        if ($data['user'] != null) {

            // Retrieving the corresponding channel by its key reference
            $data['channel'] = $this->findOneBy('key', $data['channel'], $this->channelEntity);
            if ($data['channel'] != null) {
                try {
                    // Entities found, processing with entity persistence...
                    $userChannel = new E_UserChannel($data);

                    $this->persist($userChannel);
                    $this->flush();

                    $ret = SC_SUCCESS;
                } catch (Exception|ORMException $e) {
                    log_message('error', 'Error while persisting a UserChannel instance: '.$e);
                }
            }else {
                // Channel not found in DB
                $ret = SC_DOCTRINE_ENTITY_NOT_FOUND;
                log_message('error', 'Error while persisting a UserChannel instance : Channel not found');
            }
        }else {
            // User not found in DB
            $ret = SC_INTEGRATION_USER_UNKNOWN;
            log_message('error', 'Error while persisting a UserChannel instance : User not found');
        }

        return $ret;
    }

    /**
     * Unsubscribes a user to a given channel
     * @param $data array UserChannel data array
     * @return int Status code
     */
    public function unsubscribe(array $data): int
    {
        $ret = SC_INTERNAL_SERVER_ERROR;

        // Retrieving the Channel by its key reference
        /** @var E_Channel $channel */
        $channel = $this->findOneBy('key', $data['channel'], $this->channelEntity);

        if ($channel != null)
        {
            /** @var E_UserChannel $entity */
            $entity = $this->findBy(['user' => $data['user'], 'channel' => $channel->getProperty('id')], $this->userChannelEntity);

            // Checking if the UserChannel entity exists in the DB
            if ($entity != null)
            {
                try
                {
                    // Entity found in DB, processing with deletion...
                    $this->remove($entity);
                    $this->flush();

                    $ret = SC_SUCCESS;
                } catch (Exception | ORMException $e)
                {
                    log_message('error', 'Error while removing a UserChannel instance: ' . $e);
                }
            } else
            {
                // UserChannel entity not found in DB
                log_message('error', 'Error while removing a UserChannel instance : UserChannel not found');
                $ret = SC_DOCTRINE_ENTITY_NOT_FOUND;
            }
        } else
        {
            // Channel entity not found in DB
            log_message('error', 'Error while removing a UserChannel instance : Channel not found');
            $ret = SC_DOCTRINE_ENTITY_NOT_FOUND;
        }

        return $ret;
    }

    /**
     * Persists a Notification instance in DB
     * @param $data array Notification data array
     * @param $channels array Array of channels keys
     * @return int Status code
     */
    public function addNotification(array $data, array $channels): int
    {
        $ret = SC_INTERNAL_SERVER_ERROR;

        // Checking that channels keys array is not empty
        if (count($channels > 0)) {
            try {
                // Instantiating a new Notification entity
                $notification = new E_Notification($data);

                $this->persist($notification);
                $this->flush();

                if ($notification) {
                    $param = ['notification' => $notification];
                    foreach ($channels as $channelId) {
                        $channel = $this->findOneBy('id', $channelId, $this->channelEntity);

                        $param['channel'] = $channel;
                        $param['index'] = $channel->getProperty('lastIndex')+1;

                        $notificationChannel = new E_NotificationChannel($param);

                        $this->persist($notificationChannel);
                        $channel->update(['lastIndex' => $channel->getProperty('lastIndex')+1]);
                        $this->flush();
                    }
                }
                $ret = SC_SUCCESS;
            } catch (Exception|ORMException $e) {
                log_message('error', 'Error while persisting a notification instance: '.$e);
            }
        }
        return $ret;
    }

    /**
     * Check in the DB for unread notification for the given user based on its subscribed channels
     * @param $userId
     */
    public function checkForUnreadNotifications($userId)
    {
        /** @var E_User $user */
        $user = $this->findOneBy('id', $userId, $this->userEntity);

        /** @var Array<E_UserChannel> $userChannels */
        $userChannels = $this->findBy(['user' => $user], $this->userChannelEntity);

        $sessionData = ['notifications' => []];

        foreach ($userChannels as $userChannel) {

            $userChannelIndex = $userChannel->getCurrentIndex();
            $channelLastIndex = $userChannel->getChannel()->getLastIndex();

            if ($userChannelIndex != $channelLastIndex) {
                $notificationNumber = $channelLastIndex - $userChannelIndex;

                $sessionData['notifications'][$userChannel->getChannel()->getKey()] = [
                    'label' => $userChannel->getChannel()->getLabel(),
                    'value' => $notificationNumber
                ];

                session()->set($sessionData);
            }
        }
    }

    public function getNewNotifications($userId, $channelId)
    {

        $ret = null;
        $user = $this->findOneBy('id', $userId, $this->userEntity);
        $channel = $this->findOneBy('id', $channelId, $this->channelEntity);
        $userChannel= $this->findBy(['user' => $user, 'channel' => $channel], $this->userChannelEntity, ['id' => 'desc'])[0] ;

        $userChannelIndex = $userChannel->getProperty('currentIndex');
        $channelLastIndex = $userChannel->getProperty('channel')->getProperty('lastIndex');

        if ($userChannelIndex != $channelLastIndex) {

            $limit = $channelLastIndex - $userChannelIndex;

            $notifications = $this->findBy(['channel' => $channel], $this->notificationChannelEntity,['id' => 'desc'], $limit);

            $param['currentIndex'] = $channelLastIndex;

            $userChannel->update($param);
            $this->flush();

            $ret =  $notifications;
        }

        return $ret;
    }

    public function archiveNotifications()
    {
        $currDate = date("Y-m-d");

        $limitDate = date("Y-m-d",strtotime($currDate."-1 months"));

        $notifs = $this->repository->findNotifsToArchive($limitDate);

        $file = fopen(ROOTPATH.'writable/archivedNotification/'.$limitDate.'.txt', 'w');

        foreach ($notifs as $notif) {
            $string = $notif->getProperty('date')->format('d-m-Y').' : '.$notif->getProperty('informations').CHR(13).CHR(10);
            fputs($file, $string);

            $this->remove($notif);
            $this->flush();
        }
        fclose($file);

        $this->updateFirstIndex();
    }

    public function updateFirstIndex()
    {
        $channels = $this->findAllEntities($this->channelEntity);

        foreach ($channels as $channel) {
            $notificationChannel = $this->findBy(['channel' => $channel], $this->notificationChannelEntity, ['index' => 'asc']);

            $firstIndex = $notificationChannel[0]->getProperty('index');

            $data['firstIndex'] = $firstIndex;

            $channel->update($data);
            $this->flush();
        }
    }

	/**
	 * Subscribe a user to push notifications
	 * @param $data array
	 * @return array
	 */
	public function subscribeUser(array $data): array
	{
		$ret = ['status' => SC_INTERNAL_SERVER_ERROR];

		try
		{
			$user = $this->findOneBy('id',$data['user'],'RFCore\Entities\E_User');

			if (!empty($user))
			{
				$notificationSubscription = $this->findOneBy('user',$user->getId(),'RFCore\Entities\E_NotificationSubscription');
				$data['user'] = $user;

				if (empty($notificationSubscription))
				{
					$notificationSubscription = new E_NotificationSubscription($data);
					$this->persist($notificationSubscription);
				}
				else{
					$notificationSubscription->update($data);
				}

				$this->flush();

				$ret['status'] = SC_SUCCESS;
			}
			else{
				$ret['status'] = SC_NOT_FOUND;
			}
		}
		catch (Exception $e)
		{
			log_message('error', __CLASS__.'::'.__FUNCTION__.'() > An exception occurred : '.$e);
		}

		return $ret;
	}

	/**
	 * Send one or multiple notification to the specified user
	 * @param $data array Notification data (title, body and evtType)
	 * @param $UID string|int|null User ID (null for all users)
	 * @return array|int
	 */
	public function sendNotification(array $data, $UID = null)
	{
		$result = ['status' => SC_INTERNAL_SERVER_ERROR, 'reason' => ''];

		try
		{
			// Retrieving the VAPID data
			$VAPIDData = file_get_contents($this::VAPIDFile);
			$VAPIDData = json_decode($VAPIDData, true);

			// Instantiating a new Web Push object with the VAPID keys and associated subject
			$webPush = new WebPush(['VAPID' => $VAPIDData]);

			if (!empty($UID))
			{
				// Retrieving the subscription matching the given user ID
				/** @var E_NotificationSubscription $subscription */
				$subscription = $this->findOneBy('user',$UID,'RFCore\Entities\E_NotificationSubscription');

				// Instantiating a Subscription for the current user
				$sub = Subscription::create($subscription->getSubscriptionJSONDecoded());

				// Sending the notification
				$res = $webPush->sendOneNotification($sub, json_encode([
					'title' 	=> $data['title'],
					'body' 		=> $data['body'],
					'icon' 		=> base_url('public/img/tab.png'),
					'evtType' 	=> $data['evtType']
				]));

				if ($res->isSuccess())
				{
					$result = SC_SUCCESS;
				}
				else{
					// In case of error, the reason must be returned
					$result['reason'] = $res->getReason();
				}
			}
			else{
				// Retrieving all the notification subscriptions from the DB
				$subscriptions = $this->findAllEntities('RFCore\Entities\E_NotificationSubscription');

				$errorEncountered = false;

				foreach ($subscriptions as $subscription)
				{
					// Instantiating a Subscription for the current user
					$sub = Subscription::create($subscription->getSubscriptionJSONDecoded());

					// Sending the notification
					$res = $webPush->sendOneNotification($sub, json_encode([
						'title' 	=> $data['title'],
						'body' 		=> $data['body'],
						'icon' 		=> base_url('public/img/tab.png'),
						'evtType' 	=> $data['evtType']
					]));

					// In case of error, the process must be stopped and the reason returned
					if (!$res->isSuccess())
					{
						$result['reason'] = $res->getReason();
						$errorEncountered = true;
						break;
					}
				}

				// Making sure that no errors were encountered
				if (!$errorEncountered)
				{
					$result['status'] = SC_SUCCESS;
				}
			}
		}
		catch (Exception $e)
		{
			log_message('error', __CLASS__.'::'.__FUNCTION__.'() > An exception occurred : '.$e);
		}

		return $result;
	}
}
