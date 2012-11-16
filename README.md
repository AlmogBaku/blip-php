Blip-PHP
================
Blip-PHP is a tiny PHP library meant to make accessing blip.tv's REST API easier.
It let you easily access to upload, modify, delete and getting videos information from Blip.tv via PHP.

It was conceived by [Almog Baku](http://www.almogbaku.com "Almog Baku") and is maintained in part by [Kelly Sutton](http://michaelkellysutton.com "Kelly Sutton") and [Jason Becht](http://rwsdev.net "Jason Becht").

Get It
------

[Download last version from here](https://github.com/downloads/AlmogBaku/blip-php/blip-php.v0.4b.zip "Download")

Example
-------
    <?php
    include_once("blipPHP.php");
    
    /** Create blipPHP object. **/
    $blipPHP = new blipPHP("username", "password");
    
    /** Upload file **/
    $respond = $blipPHP->upload("videos/blip_test_footage.mp4", "title", "description", "public");
    print_r($respond);
    
    /** Modify file **/
    $respond = $blipPHP->modify(1234, "title", "description", "public");
    print_r($respond);
    
    /** Change file privacy **/
    /** I believe this only works for pro accounts */
    $respond = $blipPHP->setPrivacy(1234, 'private');
    print_r($respond);
    
    /** Delete **/
    $respond = $blipPHP->delete(1234, "reason");
    print_r($respond);
    
    /** Getting information **/
    $respond = $blipPHP->info(1234);
    print_r($respond);
