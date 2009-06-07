<?php
/**
 *
 * Copyright (c) FaZend.com
 * All rights reserved.
 *
 * You can use this product "as is" without any warranties from authors.
 * You can change the product only through Google Code repository
 * at http://code.google.com/p/fazend
 * If you have any questions about privacy, please email privacy@fazend.com
 *
 * @copyright Copyright (c) FaZend.com
 * @version $Id$
 * @category FaZend
 */

require_once 'phing/Task.php';

/**
* This is Phing Task for pinging production server
*
* @see http://phing.info/docs/guide/current/chapters/ExtendingPhing.html#WritingTasks
*/
class PingFaZend extends Task {

	private $url;

	/**
	* Initiator (when the build.xml sees the task)
	* 
	* @return void
	*/
	public function init () {
	}

	/**
	* Executes
	* 
	* @return void
	*/
	public function main () {

		$this->Log ("Pinging {$this->url}...");

		$curl = curl_init($this->url);
		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($curl);
		curl_close($curl);

		$this->Log("Response (" . strlen($response). "bytes): \n{$response}");

	}

	/**
	* Initalizer
	*
	* @param $fileName string
	*/
	public function seturl($url) {
		$this->url = $url;
	}
}
