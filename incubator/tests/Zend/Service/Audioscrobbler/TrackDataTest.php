<?php

/**
 * @package    Zend_Service_Audioscrobbler
 * @subpackage UnitTests
 */

/**
 * Zend_Service_Audioscrobbler
 */
require_once 'Zend/Service/Audioscrobbler.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * @package    Zend_Service_Audioscrobbler
 * @subpackage UnitTests
 */
class Zend_Service_Audioscrobbler_TrackDataTest extends PHPUnit_Framework_TestCase
{
    var $header = "HTTP/1.1 200 OK\r\nContent-type: text/xml\r\n\r\n";
    
    public function testGetTopFans()
    {
        try {
            $testing_response = $this->header .
                                '<?xml version="1.0" encoding="UTF-8"?>
                                <fans artist="Metallica" track="Enter Sandman">
                                <user username="suhis">
                                    <url>http://www.last.fm/user/suhis/</url>
                                    <image>http://static.last.fm/depth/catalogue/noimage/nouser_140px.jpg</image>
                                    <weight>2816666</weight>
                                </user>
                                <user username="M4lu5">
                                    <url>http://www.last.fm/user/M4lu5/</url>
                                    <image>http://static.last.fm/avatar/ea9c0ddf6b6cc236dfc4297e376e9901.jpg</image>
                                    <weight>2380500</weight>
                                </user>
                                <user username="Ceniza666">
                                    <url>http://www.last.fm/user/Ceniza666/</url>
                                    <image>http://static.last.fm/depth/catalogue/noimage/nouser_140px.jpg</image>
                                    <weight>1352000</weight>
                                </user>
                                </fans>
                                ';
            $as = new Zend_Service_Audioscrobbler(TRUE, $testing_response);
            $as->set('artist', 'Metallica');
            $as->set('track', 'Enter Sandman');
            $response = $as->trackGetTopFans();
            $this->assertEquals($response['artist'], 'Metallica');
            $this->assertEquals($response['track'], 'Enter Sandman');
            $this->assertNotNull($response->user);
        } catch (Exception $e ) {
                $this->fail("Exception: [" . $e->getMessage() . "] thrown by test");
        }
    }
    
    public function testGetTopTags()
    {
        try {
            $testing_response = $this->header .
                                '<?xml version="1.0" encoding="UTF-8"?>
                                <toptags artist="Metallica" track="Enter Sandman">
                                <tag>
                                    <name>metal</name> 
                                    <count>100</count>
                                    <url>http://www.last.fm/tag/metal</url>
                                </tag>
                                <tag>
                                    <name>heavy metal</name> 
                                    <count>55</count>
                                    <url>http://www.last.fm/tag/heavy%20metal</url>
                                </tag>
                                <tag>
                                    <name>rock</name> 
                                    <count>21</count>
                                    <url>http://www.last.fm/tag/rock</url>
                                </tag>
                                </toptags>
                                ';
            $as = new Zend_Service_Audioscrobbler(TRUE, $testing_response);
            $as->set('artist', 'Metallica');
            $as->set('track', 'Enter Sandman');
            $response = $as->trackGetTopTags();
            $this->assertNotNull($response->tag);
            $this->assertEquals($response['artist'], 'Metallica');
            $this->assertEquals($response['track'], 'Enter Sandman');
        } catch (Exception $e) {
            $this->fail("Exception: [" . $e->getMessage() . "] thrown by test");
        }
    }
}

?>