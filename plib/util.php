<?php
/**
 * 4PSA VoipNow App: Click2Fax
 *
 * @version 2.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2017 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
 *
 */

/**
 * Custom exception handler
 * @param $exception
 */
function exception_handler($exception) {
    echo "Uncaught exception: " , $exception->getMessage(), "\n";
}

/**
 * Generate a new token based on App ID and App secret
 * ATTENTION: App must pe trusted !
 *
 * @return string token
 * @return boolean FALSE when token could not be generated
 */
function generateToken() {

    global $config;

    $tokenUri = 'https://' . $config['VN_SERVER_IP'] . '/oauth/token.php';
    $params = array(
        'grant_type' => 'client_credentials',
        'redirect_uri' => $_SERVER['PHP_SELF'],
        'client_id' =>  urlencode($config['OAUTH_APP_ID']),
        'client_secret' => urlencode($config['OAUTH_APP_SECRET']),
        'state' => '0'
    );

    $httpRequest = curl_init($tokenUri);
    curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($httpRequest, CURLOPT_POST, true);
    curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $params);

    $httpResponse = curl_exec($httpRequest);
    $contentType = curl_getinfo($httpRequest, CURLINFO_CONTENT_TYPE);
    $statusCode = curl_getinfo($httpRequest, CURLINFO_HTTP_CODE);
    curl_close($httpRequest);

    if (strpos($contentType, 'application/json') !== false) {
        $httpResponse = json_decode($httpResponse, true);
    }

    if ($statusCode == 200 && !empty($httpResponse['access_token'])) {
        return 'Bearer ' . $httpResponse['access_token'];
    }

    return false;
}

/**
 * @return array
 */
function extractFilesFromRequest() {

    $files = array();

    $numberOfFiles = count($_FILES['attachments']['name']);
    for ($i = 0; $i < $numberOfFiles; $i++) {
        if ($_FILES['attachments']['error'][$i] > 0) {
            continue;
        }

        $files[$_FILES['attachments']['name'][$i]] = array(
            'location' => $_FILES['attachments']['tmp_name'][$i],
            'type' => $_FILES['attachments']['type'][$i],
            'name' => $_FILES['attachments']['name'][$i]
        );
    }

    return $files;
}