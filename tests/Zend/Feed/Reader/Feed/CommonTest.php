<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Zend/Feed/Reader.php';

class Zend_Feed_Reader_Feed_CommonTest extends PHPUnit_Framework_TestCase
{

    protected $_feedSamplePath = null;

    public function setup()
    {
        if (Zend_Registry::isRegistered('Zend_Locale')) {
            $registry = Zend_Registry::getInstance();
            unset($registry['Zend_Locale']);
        }
        $this->_feedSamplePath = dirname(__FILE__) . '/_files/Common';
    }

    /**
     * Check DOM Retrieval and Information Methods
     */
    public function testGetsDomDocumentObject()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/atom.xml')
        );
        $this->assertTrue($feed->getDomDocument() instanceof DOMDocument);
    }

    public function testGetsDomXpathObject()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/atom.xml')
        );
        $this->assertTrue($feed->getXpath() instanceof DOMXPath);
    }

    public function testGetsXpathPrefixString()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/atom.xml')
        );
        $this->assertTrue($feed->getXpathPrefix() == '/atom:feed');
    }

    public function testGetsDomElementObject()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/atom.xml')
        );
        $this->assertTrue($feed->getElement() instanceof DOMElement);
    }

    public function testSaveXmlOutputsXmlStringForFeed()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/atom.xml')
        );
        $this->assertEquals($feed->saveXml(), file_get_contents($this->_feedSamplePath.'/atom_rewrittenbydom.xml'));
    }


}
