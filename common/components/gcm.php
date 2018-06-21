<?php

namespace common\components;

use yii\base\Component;

class gcm extends Component {
    
    public function send_notification($registation_ids, $message) {
        // Set POST variables
        $api_url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => array($registation_ids),
            'data' => array( "message" => $message),
        );

        $headers = array(
            'Authorization: key=' . 'AIzaSyA9wFLHlRZbstBfets6VdH_wz9PgvCAafo',
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $api_url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
        echo $result;
    }
}

