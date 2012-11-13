<?php
/**
 * @example Blip-PHP
 * @author Almog Baku - almog.baku@gmail.com
 */

//Include the blipPHP class
include_once("blipPHP.php");

/** Create blipPHP object. **/
$blipPHP = new blipPHP("username", "password");

/** Upload file **/
$respond = $blipPHP->upload("videos/blip_test_footage.mp4", "title", "description");
print_r($respond);

/** Delete **/
$respond = $blipPHP->delete(1234, "reason");
print_r($respond);

/** Getting information **/
$respond = $blipPHP->info(1234);
print_r($respond);
?>