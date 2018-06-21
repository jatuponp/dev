<?php

namespace common\components;

use yii\base\Component;

class APIKKU extends Component {

    const KEY = 'RL3eEB3LuCvNVaPAa1lJI6BKwCpVR0pITrUx1XhsxMdtU3231kLIqksWWPSMZhsQGnnEiC7PmjggkijkA321ltXhjN2g3u5GPrk63oSoH0D2pWkZWl1hMVx4GwjGYlmNLGQN3JbJSMJfG2cDmcS96ncJUtpSPLEApulpdu04hQgXRtAbDrjJOtqIVQzIzdiXFDmT6tUm';

    public static function getProfile($citizen_id) {
//        $curl = new curl\Curl();
//
//        $options = self::setOptions();
//        $curl->setOptions($options);
//        $response = $curl->get("https://api.kku.ac.th/person/profile/" . self::KEY . "/$citizen_id");
//
//        $d = json_decode($response);
//        return $d[0];

        $url = "https://api.kku.ac.th/person/profile/" . self::KEY . "/$citizen_id";
        return json_decode(self::get($url))[0];
    }

    public static function getInfo($citizen_id) {
        $url = "https://api.kku.ac.th/person/get_info/" . self::KEY . "/$citizen_id";
        return json_decode(self::get($url));
    }

    public static function getAcad($citizen_id) {

        $url = "https://api.kku.ac.th/person/get_academic_position/" . self::KEY . "/$citizen_id";
        return json_decode(self::get($url));
    }

    public static function getSalary($citizen_id) {
        $url = "https://api.kku.ac.th/person/getSalaryByCitizenId/" . self::KEY . "/$citizen_id";
        return json_decode(self::get($url));
    }

//    private function setOptions() {
//        $options = [
//            CURLOPT_RETURNTRANSFER => 1,
//            CURLOPT_FOLLOWLOCATION => 1,
//            CURLOPT_SSL_VERIFYPEER => 0,
//            CURLOPT_SSL_VERIFYHOST => 0,
////            CURLOPT_CUSTOMREQUEST => 'GET',
//        ];
//
//        return $options;
//    }

    private function get($url, $type = 'GET', $data = array()) {
        $jData = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jData))
            );
        }
        $data = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        return empty($error) ? $data : $error;
    }

}
