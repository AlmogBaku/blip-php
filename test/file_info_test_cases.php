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

class TestOfBlipFileInfo extends UnitTestCase {
    function setUp() {
    }
   
    function TestOfBlipFileInfo() {
        $this->UnitTestCase();
    }
   
    function test_file_info() {
        $response->payload->asset->id;
       
        $blip = new BlipPHP();
       
        $this->expectException('BlipDataException');
        $response = $blip->upload($username = "something", $password = "wrongpassword", $file = '../videos/blip_test_footage.mp4', $title='', $description = 'Test Description');
    }
   
    function test_file_info_no_id() {
        $blip = new BlipPHP();
       
        $this->expectException('BlipDataException');
        $response = $blip->file_info($username = "something", $password = "wrongpassword", $file = '../videos/blip_test_footage.mp4', $title='', $description = 'Test Description');
    }
}

$test = &new TestOfBlipFileInfo();
$test->run(new HtmlReporter());
?>
