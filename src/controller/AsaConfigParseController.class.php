<?php

require_once(__DIR__ . "/../parser/AsaConfigParser.class.php");
require_once(__DIR__ . "/../view/AsaConfigView.class.php");
require_once(__DIR__ . "/../util/Utils.class.php");

class AsaConfigParseController {


    function __construct($file, $options) {

        $data = AsaConfigParser::parse($file);

        if (!$this->isEmpty($data)) {
            $view = new AsaConfigView($options);
            echo $view->getHtml($data);
        } else {
            include(__DIR__ . "/../view/NotValidFileFormat.html");
        }

    }

    function isEmpty($data) {
        foreach ($data as $entry) {
            if (!empty($entry)) {
                return false;
            }
        }
        return true;
    }

}
?>
