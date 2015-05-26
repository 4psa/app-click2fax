<?php
/**
 * 4PSA VoipNow App: Click 2 Fax
 *
 * File contains all the functioned used when making a call
 *
 * @version 2.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2012 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
 *
 */

/**
 * Sets a handle for uncaught exceptions.
 * @param Exception $exception
 */
function exception_handler($exception) {
    echo "Uncaught exception: " , $exception->getMessage(), "\n";
}

set_exception_handler('exception_handler');


/**
 * Generate a new token based on App ID and App secret
 *
 * @return string token
 * @return boolean FALSE when token could not be generated
 */
function generateToken() {
    global $config;

    $reqUrl = 'https://'.$config['VN_SERVER_IP'].'/oauth/token.php';

    $request = new cURLRequest();
    $request->setMethod(cURLRequest::METHOD_POST);

    $fields = array(
        'grant_type' => 'client_credentials',
        'redirect_uri' => $_SERVER['PHP_SELF'],
        'client_id' =>  urlencode($config['OAUTH_APP_ID']),
        'client_secret' => urlencode($config['OAUTH_APP_SECRET']),
        'state' => '0',

    );
    $request->setBody($fields);
    $response = $request->sendRequest($reqUrl);

    $respBody = $response->getBody();
    if ($response->getStatus() == Response::STATUS_OK && isset($respBody['access_token'])) {
        $_SESSION['Click2Fax']['token'] = 'Bearer '.$respBody['access_token'];
        return 'Bearer '.$respBody['access_token'];
    }
    return false;
}

/**
 * Get the token used for previous requests, or generate a new one if none exists
 *
 * @return string token
 */
function getToken() {
    if (isset($_SESSION['Click2Fax']['token']) && $_SESSION['Click2Fax']['token']) {
        $token = $_SESSION['Click2Fax']['token'];
    } else {
        /* generate token */
        $token = generateToken();
    }
    return $token;
}

/**
 * Sent a request to submit a fax
 *
 * @return string message, whether success or failure of the operation has occured
 */
function sendFaxRequest() {

    global $config, $msgArr;

    $token = getToken();
    if (!$token) {
        return false;
    }

    $headers = array(
        'Authorization' => $token
    );

    $request = new cURLRequest();

    //Faxes Create uses POST
    $request->setMethod(cURLRequest::METHOD_POST);
    $request->setHeaders($headers);

    $request->setBody(array('recipients' => array($_POST['to'])), cURLRequest::ENCODER_JSON);

    $numberOfFiles = count($_FILES['attachments']['name']);
    $files = array();

    for ($i = 0; $i < $numberOfFiles; $i++) {
        if ($_FILES['attachments']['error'][$i] > 0) {
            continue;
        }

        $files[$_FILES['attachments']['name'][$i]] = '@'.$_FILES['attachments']['tmp_name'][$i];
    }

    $request->setFiles($files);
   // echo "<pre>";
  //  print_r($request);
    $response = $request->sendRequest('https://'.$config['VN_SERVER_IP'].'/unifiedapi/faxes/@me/'.$config['VN_EXTENSION']);

 //   print_r($response);
    $body = $response->getBody();

    //if there's an error, the array will contain the 'error' key
    if (isset($body['error'])) {
        return $body['error']['message'];
    }  else {
        return $msgArr['send_success'];
    }
}