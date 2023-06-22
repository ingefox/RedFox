<?php

namespace RFCore\Controllers;

use Exception;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\VAPID;
use Minishlink\WebPush\WebPush;
use RFCore\Entities\E_NotificationSubscription;
use RFCore\Models\M_Notification;

class C_Notification extends RF_Controller
{
    /**
     * Setting up the VAPID keys
     * @see https://tools.reactpwa.com/vapid?email=projet%40ingefox.com
     * @return void
     */
    public function setUpVAPID()
    {
        try
        {
            // Generating a new key pair
            $keySet = VAPID::createVapidKeys();

            // Saving the keys in the integration config file
            file_put_contents(M_Notification::VAPIDFile, json_encode($keySet));
        }
        catch (Exception $e)
        {
            log_message('error', __CLASS__.'::'.__FUNCTION__.'() > An exception occurred : '.$e);
        }
    }

    /**
     * Retrieve the VAPID public key
     * @return false|string
     */
    public function getPublicKey()
    {
        $ret = ['status' => SC_INTERNAL_SERVER_ERROR, 'data' => null];

        // If no VAPID.json file detected, a new one must be generated
        if (!is_file(M_Notification::VAPIDFile))
        {
            $this->setUpVAPID();
        }

        try
        {
            // Making sure that a valid vapid.json file has been found
            if (is_file(M_Notification::VAPIDFile))
            {
                // Retrieving the VAPID keys
                $data 			= file_get_contents(M_Notification::VAPIDFile);
                $data 			= json_decode($data, true);

                // Retrieving the public key from the decoded data
                $ret['data'] 	= $data['publicKey'];
                $ret['status'] 	= SC_SUCCESS;
            }
        }
        catch (Exception $e)
        {
            log_message('error', __CLASS__.'::'.__FUNCTION__.'() > An exception occurred : '.$e);
        }

        // Setting response content type and encoding
        $this->response->setHeader('Content-type', 'application/json;charset=utf-8');

        return json_encode($ret, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
    }

    /**
     * Subscribe a user's browser to push notifications
     * @return false|string
     */
    public function subscribeUser()
    {
        $ret = ['status' => SC_INTERNAL_SERVER_ERROR];

        if (session()->get(SESSION_KEY_LOGGED_IN))
        {
            // Retrieving the subscription payload
            $subscriptionData = $this->request->getPostGet('sub');

            try
            {
                $M_Notification = new M_Notification();

                // Formatting the subscription data
                $data = [
                    'user' 				=> session()->get('id'),
                    'subscriptionJSON' 	=> $subscriptionData
                ];

                // Subscribing the user
                $ret = $M_Notification->subscribeUser($data);
            }
            catch (Exception $e)
            {
                log_message('error', __CLASS__.'::'.__FUNCTION__.'() > An exception occurred : '.$e);
            }
        }
        else{
            $ret['status'] = SC_FORBIDDEN;
        }

        return json_encode($ret);
    }

    /**
     * Send a notification to a given user
     * @return false|string
     */
    public function sendNotification()
    {
        $ret = ['status' => SC_INTERNAL_SERVER_ERROR];

        try
        {
            $M_Notification = new M_Notification();
            $data = $this->request->getPostGet('notificationData');
            $ret = $M_Notification->sendNotification($data);
        }
        catch (Exception $e)
        {
            log_message('error', __CLASS__.'::'.__FUNCTION__.'() > An exception occurred : '.$e);
        }

        // Setting response content type and encoding
        $this->response->setHeader('Content-type', 'application/json;charset=utf-8');

        return json_encode($ret);
    }
}
