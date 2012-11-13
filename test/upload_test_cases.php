<?php
/**
 * SIMPLETEST Unit
 * @author Kelly Sutton
 */

if (! defined('SIMPLE_TEST')) {
    define('SIMPLE_TEST', '../../../simpletest/');
}
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');
require_once('../blipPHP.php');

class TestOfBlipUpload extends UnitTestCase {
    function setUp() {

    }
    
    function TestOfBlipUpload() {
        $this->UnitTestCase();
    }
    
    function test_the_truth() {
        $this->assertTrue(true);
    }
    
    function test_blip_create() {
        $blip = new BlipPHP();
        $this->assertNotNull($blip);
    }
    
    function test_upload_standard () {
        $blip = new BlipPHP();
        $response = $blip->upload($username = "api_test_user", $password = "bliptastic", $file = '../videos/blip_test_footage.mp4', $title='Test Title', $description = 'Test Description');
        $this->assertNotNull($response);
        $this->assertTrue($response instanceof SimpleXMLElement);
        $this->assertEqual($response->status, "OK");
        
        $this->assertTrue($response->timestamp > 0);
        
        // Moving on to testing the payload
        $this->assertNotNull($response->payload);
        $this->assertNotNull($response->payload->asset);

        $this->assertTrue($response->payload->asset->id > 0);
        $this->assertEqual("file", $response->payload->asset->item_type[0]);

        $this->assertNotNull($response->payload->asset->links);
        $this->assertEqual(5, count($response->payload->asset->links->link));
    }
    
    function test_upload_wrong_password() {
        $blip = new BlipPHP();
        
        $this->expectException('Exception');
        $response = $blip->upload($username = "api_test_user", $password = "wrongpassword", $file = '../videos/blip_test_footage.mp4', $title='Test Title', $description = 'Test Description');
    }
    
    function test_upload_no_username() {
        $blip = new BlipPHP();
        
        $this->expectException('BlipAuthenticationException');
        $response = $blip->upload($password = "wrongpassword", $file = '../videos/blip_test_footage.mp4', $title='Test Title', $description = 'Test Description');
    }
    
    function test_upload_blank_username() {
        $blip = new BlipPHP();
        
        $this->expectException('BlipAuthenticationException');
        $response = $blip->upload($username = "", $password = "wrongpassword", $file = '../videos/blip_test_footage.mp4', $title='Test Title', $description = 'Test Description');
    }
    
    // function test_upload_no_title() {
    //         $blip = new BlipPHP();
    //         
    //         $this->expectException('BlipDataException');
    //         $response = $blip->upload($username = "api_test_user", $password = "bliptastic", $file = '../videos/blip_test_footage.mp4', $description = 'Test Description');
    //     }
    
    function test_upload_blanke_title() {
        $blip = new BlipPHP();
        
        $this->expectException('BlipDataException');
        $response = $blip->upload($username = "something", $password = "wrongpassword", $file = '../videos/blip_test_footage.mp4', $title='', $description = 'Test Description');
    }
    
}

$test = &new TestOfBlipUpload();
$test->run(new HtmlReporter());
?>