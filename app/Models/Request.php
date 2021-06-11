<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    /**
     * @description getAccessToken
     * @param string $url
     * @param string $grantType
     * @param string $clientId
     * @param string $clientSecret
     * @param string $code
     * @param string $redirectUri
     * @return string
     */
    public static function getAccessToken($url, $grantType, $clientId, $clientSecret, $code, $redirectUri = '') {
        $result = '';

        $params = [
            'grant_type' => $grantType,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'code' => $code,
            'redirect_uri' => $redirectUri,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $r = curl_exec($ch);
        $res = json_decode($r, true);
        if (!empty($res) && isset($res['access_token'])) {
            $result = $res['access_token'];
        }

        return $result;
    }

    /**
     * @description Создание сделки
     * @param string $url
     * @param string $accessToken
     * @param string $dealName
     * @param string $dealStage
     * @param string $taskName
     * @return string
     */
    public static function createDeal($urlCreateDeal, $urlCreateTask, $accessToken, $dealName, $dealStage, $taskName) {
        $result = '';

        $curl_pointer = curl_init();

        $curl_options = array();
        $curl_options[CURLOPT_URL] = $urlCreateDeal;

        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = "POST";
        $requestBody = array();
        $recordArray = array();
        $recordObject = array();
        $recordObject["Deal_Name"]=$dealName;
        $recordObject["Stage"]=$dealStage;


        $recordArray[] = $recordObject;
        $requestBody["data"] =$recordArray;
        $curl_options[CURLOPT_POSTFIELDS]= json_encode($requestBody);

        $headersArray = array();

        $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken " . $accessToken;

        $curl_options[CURLOPT_HTTPHEADER]=$headersArray;
        curl_setopt_array($curl_pointer, $curl_options);

        $result = curl_exec($curl_pointer);
        $responseInfo = curl_getinfo($curl_pointer);
        curl_close($curl_pointer);
        list ($headers, $content) = explode("\r\n\r\n", $result, 2);
        if(strpos($headers," 100 Continue")!==false){
            list( $headers, $content) = explode( "\r\n\r\n", $content , 2);
        }
        $headerArray = (explode("\r\n", $headers, 50));
        $headerMap = array();
        foreach ($headerArray as $key) {
            if (strpos($key, ":") != false) {
                $firstHalf = substr($key, 0, strpos($key, ":"));
                $secondHalf = substr($key, strpos($key, ":") + 1);
                $headerMap[$firstHalf] = trim($secondHalf);
            }
        }
        $jsonResponse = json_decode($content, true);
        if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
            list ($headers, $content) = explode("\r\n\r\n", $content, 2);
            $jsonResponse = json_decode($content, true);

        }

        $dealId = 0;
        if (!empty($jsonResponse) && isset($jsonResponse['data'][0]) && ($jsonResponse['data'][0]['code'] == 'SUCCESS')) {
            $dealId = $jsonResponse['data'][0]['details']['id'];
        }
        if ($dealId > 0) {
            $result = self::createTask($urlCreateTask, $accessToken, $dealId, $dealName, $taskName);
        }

        return $result;
    }

    /**
     * @description Создание задачи
     * @param string $url
     * @param string $accessToken
     * @param string $dealId
     * @param string $dealName
     * @param string $taskName
     * @return string
     */
    public static function createTask($url, $accessToken, $dealId, $dealName, $taskName) {
        $result = '';

        $curl_pointer = curl_init();

        $curl_options = array();
        $curl_options[CURLOPT_URL] =$url;

        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = "POST";
        $requestBody = array();
        $recordArray = array();
        $recordObject = array();
        $recordObject["Subject"] = $taskName;
        $recordObject["\$se_module"] = "Deals";
        $recordObject["What_Id"] = [
            'name' => $dealName,
            'id' => $dealId
        ];


        $recordArray[] = $recordObject;
        $requestBody["data"] =$recordArray;
        $curl_options[CURLOPT_POSTFIELDS]= json_encode($requestBody);

        $headersArray = array();

        $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken " . $accessToken;

        $curl_options[CURLOPT_HTTPHEADER]=$headersArray;
        curl_setopt_array($curl_pointer, $curl_options);

        $result = curl_exec($curl_pointer);
        $responseInfo = curl_getinfo($curl_pointer);
        curl_close($curl_pointer);
        list ($headers, $content) = explode("\r\n\r\n", $result, 2);
        if(strpos($headers," 100 Continue")!==false){
            list( $headers, $content) = explode( "\r\n\r\n", $content , 2);
        }
        $headerArray = (explode("\r\n", $headers, 50));
        $headerMap = array();
        foreach ($headerArray as $key) {
            if (strpos($key, ":") != false) {
                $firstHalf = substr($key, 0, strpos($key, ":"));
                $secondHalf = substr($key, strpos($key, ":") + 1);
                $headerMap[$firstHalf] = trim($secondHalf);
            }
        }
        $jsonResponse = json_decode($content, true);
        if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
            list ($headers, $content) = explode("\r\n\r\n", $content, 2);
            $jsonResponse = json_decode($content, true);

        }
        if (!empty($jsonResponse) && isset($jsonResponse['data'][0]) && ($jsonResponse['data'][0]['code'] == 'SUCCESS')) {
            $result = 'success';
        }
        return $result;
    }
}
