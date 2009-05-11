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

require_once 'AbstractTestCase.php';

class stubMailer extends Zend_Mail_Transport_Sendmail {
	public function _sendMail() {
		return true;
	}
}

class EmailTest extends AbstractTestCase {
	
	public function testEmailsAreRenderedAndSent () {

		$mailer = new stubMailer();
		Zend_Mail::setDefaultTransport($mailer);

		FaZend_Email::create('test.tmpl')
			->set('toEmail', 'manager@fazend.com')
			->set('subject', 'subject line')
			->set('bodyText', 'test for body')
			->send(true);

		$this->assertNotEquals(false, $mailer->body, "Empty email generated, why?");

	}

}
