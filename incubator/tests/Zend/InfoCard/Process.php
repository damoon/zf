<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_InfoCard
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id:$
 */

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework.php';


require_once 'Zend/InfoCard.php';


class _Zend_InfoCard_Test_Adapter  extends PHPUnit_Framework_TestCase 
						    implements Zend_InfoCard_Adapter_Interface {
	
						    	
	
	public function storeAssertion($assertionURI, $assertionID, $conditions) {

		$this->assertTrue(!empty($assertionURI));
		$this->assertTrue(!empty($assertionID));
		$this->assertTrue(!empty($conditions));
		return true;
		
	}
	
	public function retrieveAssertion($assertionURI, $assertionID) {
		
		$this->assertTrue(!empty($assertionURI));
		$this->assertTrue(!empty($assertionID));
		return false;
	}
	
	public function removeAssertion($asserionURI, $assertionID) {
		
		$this->assertTrue(!empty($assertionURI));
		$this->asserTrue(!empty($assertionID));
	}

}

class Zend_InfoCard_Process extends PHPUnit_Framework_TestCase
{
	const TOKEN_DOCUMENT = './_files/encryptedtoken.xml';
	
	const SSL_PUB_KEY = "./_files/ssl_pub.cert";
	const SSL_PRV_KEY = "./_files/ssl_private.cert";
	
	private $_xmlDocument;
	
	protected function setUp() {
		$this->loadXmlDocument();
	}
	
	private function loadXmlDocument() {
		$this->_xmlDocument = file_get_contents(self::TOKEN_DOCUMENT);
	}
	
	public function testCertificatePairs() {
		
		$infoCard = new Zend_InfoCard();
		
		$key_id = $infoCard->addCertificatePair(self::SSL_PRV_KEY, self::SSL_PUB_KEY);

		$this->assertTrue((bool)$key_id);
		
		$key_pair = $infoCard->getCertificatePair($key_id);
		
		$this->assertTrue(!empty($key_pair['public']));
		$this->assertTrue(!empty($key_pair['private']));
		$this->assertTrue(!empty($key_pair['type_uri']));
		
		$infoCard->removeCertificatePair($key_id);
		
		$failed = false;
		
		try {
			$key_pair = $infoCard->getCertificatePair($key_id);
		} catch(Zend_InfoCard_Exception $e) {
			$failed = true;
		}
		
		$this->assertTrue($failed);
		
	}
	
	public function testStandAloneProcess() {

		$_SERVER['SERVER_NAME'] = "192.168.1.105";
		$_SERVER['SERVER_PORT'] = 80;
		
		$infoCard = new Zend_InfoCard();
		
		$infoCard->addCertificatePair(self::SSL_PRV_KEY, self::SSL_PUB_KEY);

		$claims = $infoCard->process($this->_xmlDocument);
		
		$this->assertTrue($claims instanceof Zend_InfoCard_Claims);
		
			
	}
	
	public function testPlugins() {

		$adapter = new _Zend_InfoCard_Test_Adapter();
		
		$infoCard = new Zend_InfoCard();
		
		$infoCard->setAdapter($adapter);

		$result = $infoCard->getAdapter() instanceof Zend_InfoCard_Adapter_Interface;
		
		$this->assertTrue($result);
		$this->assertTrue($infoCard->getAdapter() instanceof _Zend_InfoCard_Test_Adapter);
				
		$infoCard->addCertificatePair(self::SSL_PRV_KEY, self::SSL_PUB_KEY);
		
		$claims = $infoCard->process($this->_xmlDocument);

		$pki_object = new Zend_InfoCard_Cipher_PKI_Adapter_RSA(Zend_InfoCard_Cipher_PKI_Adapter_Abstract::NO_PADDING);
		
		$infoCard->setPKICipherObject($pki_object);
		
		$this->assertTrue($pki_object === $infoCard->getPKICipherObject());
		
		$sym_object = new Zend_InfoCard_Cipher_Symmetric_Adapter_AES256CBC();
		
		$infoCard->setSymCipherObject($sym_object);
		
		$this->assertTrue($sym_object === $infoCard->getSymCipherObject());
		
		
	}
	
	public function testClaims() {
		
		$infoCard = new Zend_InfoCard();
		
		$infoCard->addCertificatePair(self::SSL_PRV_KEY, self::SSL_PUB_KEY);

		$claims = $infoCard->process($this->_xmlDocument);
		
		$this->assertTrue($claims instanceof Zend_InfoCard_Claims);

		$this->assertFalse($claims->isValid());
		
		$this->assertSame($claims->getCode(), Zend_InfoCard_Claims::RESULT_VALIDATION_FAILURE);
		
		@$claims->forceValid();
		
		$this->assertTrue($claims->isValid());
		
		$this->assertSame($claims->emailaddress, "john@zend.com");
		$this->assertSame($claims->givenname, "John");
		$this->assertSame($claims->surname, "Coggeshall");
		$this->assertSame($claims->getCardID(), "rW1/y9BuncoBK4WSipF2hHYParxxgMHk6ANBrhz1Zr4=");
		$this->assertSame($claims->getClaim("http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress"), "john@zend.com");
		$this->assertSame($claims->getDefaultNamespace(), "http://schemas.xmlsoap.org/ws/2005/05/identity/claims");
		
	}
	
	public function testDefaultAdapter() {

		$adapter = new Zend_InfoCard_Adapter_Default();
		
		$this->assertTrue($adapter->storeAssertion(1, 2, array(3)));
		$this->assertFalse($adapter->retrieveAssertion(1, 2));
		$this->assertTrue(is_null($adapter->removeAssertion(1, 2)));
	}

	public function testConditionValidation() {
		
		
	}
}

