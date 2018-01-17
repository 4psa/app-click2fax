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
require_once(__DIR__ . '/util.php');
require_once(__DIR__ . '/FaxRequest.php');

/** Sets a handle for uncaught exceptions */
set_exception_handler('exception_handler');


/**
 * Sent a request to submit a fax
 * @return string message, whether success or failure of the operation has occured
 */
function sendFaxRequest() {

    global $msgArr;

    $token = generateToken();
    if (!$token) {
        return false;
    }

    $faxRequest = new FaxRequest();
    $faxRequest->initHeaders(array(
        'Authorization: ' . $token
    ));

    $params = array(
        'recipients' => array($_POST['to'])
    );
    $files = extractFilesFromRequest();

    $faxRequest->initPayload($params, $files);
    $response = $faxRequest->execute();

    // If there's an error, the array will contain the 'error' key
    if (isset($response['error'])) {
        return $response['error']['message'];
    }

    return $msgArr['send_success'];
}