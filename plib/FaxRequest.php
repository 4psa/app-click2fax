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
 * Class FaxRequest
 */
class FaxRequest {

    /**
     * @param $serviceUri
     * @return bool|mixed
     */
    public function execute($serviceUri = null) {

        if (empty($serviceUri)) {
            $serviceUri = 'https://' . $this->_getServiceUri();
        }

        $httpRequest = curl_init($serviceUri);
        curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($httpRequest, CURLOPT_POST, true);
        curl_setopt($httpRequest, CURLOPT_POSTFIELDS, $this->_payload);
        curl_setopt($httpRequest, CURLOPT_HTTPHEADER, $this->_headers);

        $httpResponse = curl_exec($httpRequest);
        $contentType = curl_getinfo($httpRequest, CURLINFO_CONTENT_TYPE);
        curl_close($httpRequest);

        if (strpos($contentType, 'application/json') !== false) {
            $httpResponse = json_decode($httpResponse, true);
        }

        return $httpResponse;
    }

    /**
     * @param array $headers
     */
    public function initHeaders(array $headers = array()) {

        $this->_headers = $this->_defaultHeaders;
        if (!empty($headers)) {
            $this->_headers = array_unique(array_merge($this->_headers, $headers));
        }
    }

    /**
     * @param array $params
     * @param array $files
     */
    public function initPayload(array $params, array $files) {

        /**
         * Set fax information (ex. recipients list)
         * WARNING: This must be set in JSON format !
         */
        $this->_payload['request'] = json_encode($params);

        /**
         * Set uploaded files to send them by fax
         */
        foreach ($files as $fileAlias => $fileInfo) {
            $this->_payload[$fileAlias] = new CURLFile($fileInfo['location'], $fileInfo['type'], $fileInfo['name']);
        }
    }

    /**
     * @return string
     */
    private function _getServiceUri() {

        global $config;
        return $config['VN_SERVER_IP'] . '/unifiedapi/faxes/@me/' . $config['VN_EXTENSION'];
    }

    /**
     * @var array
     */
    private $_defaultHeaders = array(
        'Content-Disposition: multipart/form-data'
    );

    /**
     * @var array
     */
    private $_payload = array();

    /**
     * @var array
     */
    private $_headers = array();
}