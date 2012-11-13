<?php
/**
 * SIMPLETEST Unit
 * @author Kelly Sutton
 */

$xmlstring = <<<XML
<?xml version="1.0" encoding="ISO-8859-1"?>
<note>
<to>Tove</to>
<from>Jani</from>
<heading>Reminder</heading>
<body>Don't forget me this weekend!</body>
</note>
XML;

        $hello = 321;
        var_dump($hello);
        echo "<br />";
        echo ($hello == 321);
        echo "<br />";
        $whoops = simplexml_load_string("$xmlstring");
        var_dump($whoops);
        echo $whoops->to;

?>