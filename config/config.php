<?php
/**
 * 4PSA VoipNow Plug-in: Click2Fax
 *  
 * This file stores all the configuration parameters like: 
 * email address to send the fax to
 * credentials
 *
 * @version 2.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2012 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
*/

/**
 * The IP/Hostname of the VoipNow Professional server
 * @global string
*/
$config['VN_SERVER_IP'] = 'CHANGEME';

/**
 * Number of the extension that will initiate the fax
 * @global string
 */
$config['VN_EXTENSION'] =  'CHANGEME';


/**
 * APP ID for 3-legged OAuth
 * Must be fetched from VoipNow interface
 * @global string
 */
$config['OAUTH_APP_ID'] = 'CHANGEME';

/**
 * APP Secret for 3-legged OAuth
 * Must be fetched from VoipNow interface
 * @global string
 */
$config['OAUTH_APP_SECRET'] = 'CHANGEME';

?>