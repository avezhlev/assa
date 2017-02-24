<?php

require_once(__DIR__ . "/../CommonEntity.class.php");
require_once(__DIR__ . "/../network/NetworkObject.class.php");

class PublicService extends CommonEntity {

    const TYPE = "nat";

    public $serviceType;
    public $outerObject;
    public $innerObject;
    public $options = array();


    function __construct($name, $type = self::TYPE) {
        parent::__construct($name, $type);
    }


    function asString($highlightObject = "") {

        $highlightAll = empty($highlightObject);

        $result = self::TYPE . " " . $this->name . " " . $this->serviceType . " ";

        if ($highlightAll || ($this->innerObject === $highlightObject)) {
            $result .= "<b>" . $this->innerObject . "</b> ";
        } else {
            $result .= $this->innerObject . " ";
        }

        foreach ($this->options as $option) {
            $result .= $option . " ";
        }

        $result .= "<i>(" . NetworkObject::TYPE . " ";
        if ($highlightAll || ($this->outerObject === $highlightObject)) {
            $result .= "<b>" . $this->outerObject . "</b>";
        } else {
            $result .= $this->outerObject;
        }
        $result .= ")</i>";

        return $result;
    }

}

?>
