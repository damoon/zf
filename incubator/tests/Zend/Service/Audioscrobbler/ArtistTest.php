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
class Zend_Service_Audioscrobbler_ArtistTest extends PHPUnit_Framework_TestCase
{
    var $header = "HTTP/1.1 200 OK\r\nContent-type: text/xml\r\n\r\n";
    
	public function testGetRelatedArtists()
    {
        try {
            $test_response = $this->header .
                            '<?xml version="1.0" encoding="UTF-8"?>
                            <similarartists artist="Metallica" streamable="1" picture="http://static.last.fm/proposedimages/sidebar/6/1000024/1285.jpg" mbid="65f4f0c5-ef9e-490c-aee3-909e7ae6b2ab">
                            <artist>
                                <name>Iron Maiden</name>
                                <mbid>ca891d65-d9b0-4258-89f7-e6ba29d83767</mbid>
                                <match>100</match>
                                <url>http://www.last.fm/music/Iron+Maiden</url>
                                <image_small>http://static.last.fm/proposedimages/thumbnail/6/1000107/459375.jpg</image_small>
                                <image>http://static.last.fm/proposedimages/sidebar/6/1000107/459375.jpg</image>
                                <streamable>1</streamable>
                            </artist>
                            <artist>
                                <name>System of a Down</name>
                                <mbid>cc0b7089-c08d-4c10-b6b0-873582c17fd6</mbid>
                                <match>96</match>
                                <url>http://www.last.fm/music/System+of+a+Down</url>
                                <image_small>http://static.last.fm/proposedimages/thumbnail/6/4468/52383.jpg</image_small>
                                <image>http://static.last.fm/proposedimages/sidebar/6/4468/52383.jpg</image>
                                <streamable>1</streamable>
                            </artist>
                            </similarartists>';
            $as = new Zend_Service_Audioscrobbler(TRUE, $test_response);
            $as->set('artist', 'Metallica');
		    $response = $as->artistGetRelatedArtists();
            $this->assertNotNull($response);
			$this->assertNotNull($response->similarartists);
			$this->assertNotNull($response->artist);
            return;
        } catch (Exception $e ) {
            $this->fail("Exception: [" . $e->getMessage() . "] thrown by test");
        }

    }

	public function testGetFans()
	{
		try {
            $test_response = $this->header .
                            '<?xml version="1.0" encoding="UTF-8"?>
                            <fans artist="Metallica">
                            <user username="Liquid_Fire">
                                <url>http://www.last.fm/user/Liquid_Fire/</url>
                                <image>http://static.last.fm/avatar/d8d9af8246e537078a57d5dc826cb34a.gif</image>
                                <weight>617010250</weight>
                            </user>
                            <user username="CeciltheDark">
                                <url>http://www.last.fm/user/CeciltheDark/</url>
                                <image>http://static.last.fm/avatar/30f0417393b696ac2ea06213bc5777d9.png</image>
                                <weight>382812500</weight>
                            </user>
                            </fans>
                            ';
			$as = new Zend_Service_Audioscrobbler(TRUE, $test_response);
			$as->set('artist', 'Metallica');
			$response = $as->artistGetTopFans();
			$this->assertNotNull($response->fans);
			$this->assertEquals($response['artist'], 'Metallica');
			$this->assertNotNull($response->user);
			return;
		} catch (Exception $e) {
            $this->fail("Exception: [" . $e->getMessage() . "] thrown by test");
		}
	}
	
	public function testTopTracks()
	{
		try {
            $test_response = $this->header .
                            '<?xml version="1.0" encoding="UTF-8"?>
                            <mostknowntracks artist="Metallica">
                            <track>
                                <name>Nothing Else Matters</name>
                                <mbid></mbid>
                                <reach>7481</reach>
                                <url>http://www.last.fm/music/Metallica/_/Nothing+Else+Matters</url>
                            </track>
                            <track>
                                <name>Enter Sandman</name>
                                <mbid></mbid>
                                <reach>7139</reach>
                                <url>http://www.last.fm/music/Metallica/_/Enter+Sandman</url>
                            </track>
                            </mostknowntracks>';
			$as = new Zend_Service_Audioscrobbler(TRUE, $test_response);
			$as->set('artist', 'Metallica');
			$response = $as->artistGetTopTracks();
			$this->assertNotNull($response->mostknowntracks);
			$this->assertEquals($response['artist'], 'Metallica');
			$this->assertNotNull($response->track);
			return;
		} catch (Exception $e) {
			$this->fail("Exception: [" . $e->getMessage() . "] thrown by test");
		}
	}
	
	public function testTopAlbums()
	{
		try {
            $test_response = $this->header .
                            '<?xml version="1.0" encoding="UTF-8"?>
                            <topalbums artist="Metallica">
                            <album>
                                <name>Master of Puppets</name>
                                <mbid>fed37cfc-2a6d-4569-9ac0-501a7c7598eb</mbid>
                                <reach>28940</reach>
                                <url>http://www.last.fm/music/Metallica/Master+of+Puppets</url>
                                <releasedate>7 Oct 1986, 00:00</releasedate>
                                <coverart>
                                    <small>http://static.last.fm/coverart/50x50/1411810.jpg</small>
                                    <medium>http://static.last.fm/coverart/130x130/1411810.jpg</medium>
                                    <large>http://static.last.fm/coverart/300x300/1411810.jpg</large>
                                </coverart>
                            </album>
                            <album>
                                <name>Reload</name>
                                <mbid>b05cf8e3-67ad-4d40-9dc1-3a697e3a1bf8</mbid>
                                <reach>27226</reach>
                                <url>http://www.last.fm/music/Metallica/Reload</url>
                                <releasedate>18 Nov 1997, 00:00</releasedate>
                                <coverart>
                                    <small>http://images.amazon.com/images/P/B000002HRE.01._SCMZZZZZZZ_.jpg</small>
                                    <medium>http://images.amazon.com/images/P/B000002HRE.01._SCMZZZZZZZ_.jpg</medium>
                                    <large>http://images.amazon.com/images/P/B000002HRE.01._SCMZZZZZZZ_.jpg</large>
                                </coverart>
                            </album>
                            </topalbums>
                            ';
			$as = new Zend_Service_Audioscrobbler(TRUE, $test_response);
			$as->set('artist', 'Metallica');
			$response = $as->artistGetTopAlbums();
			$this->assertNotNull($response->topalbums);
			$this->assertEquals($response['artist'], 'Metallica');
			$this->assertNotNull($response->album);
			return;
		} catch (Exception $e) {
			$this->fail("Exception: [" . $e->getMessage() . "] thrown by test");
		}
	}
	
	public function testTopTags()
	{
		try {
            $test_response = $this->header .
                            '<?xml version="1.0" encoding="UTF-8"?>
                            <toptags artist="Metallica">
                            <tag>
                                <name>metal</name> 
                                <count>100</count>
                                <url>http://www.last.fm/tag/metal</url>
                            </tag>
                            <tag>
                                <name>heavy metal</name> 
                                <count>24</count>
                                <url>http://www.last.fm/tag/heavy%20metal</url>
                            </tag>
                            <tag>
                                <name>thrash metal</name> 
                                <count>18</count>
                                <url>http://www.last.fm/tag/thrash%20metal</url>
                            </tag>
                            </toptags>
                            ';
			$as = new Zend_Service_Audioscrobbler(TRUE, $test_response);
			$as->set('artist', 'Metallica');
			$response = $as->artistGetTopTags();
			$this->assertNotNull($response->toptags);
			$this->assertEquals($response['artist'], 'Metallica');
			$this->assertNotNull($response->tag);
		} catch (Exception $e) {
			$this->fail("Exception: [" . $e->getMessage() . "] thrown by test");
		}
	}
}

?>