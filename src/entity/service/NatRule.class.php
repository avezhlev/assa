<?php

require_once(__DIR__ . "/../CommonEntity.class.php");

class NatRule extends CommonEntity {

    const TYPE = "nat";
    const SOURCE_MARK = "source";
    const DESTINATION_MARK = "destination";
    const SERVICE_MARK = "service";
    const DESCRIPTION_MARK = "description";

    public $sourceType;
    public $destinationType;
    public $sourceObjects = array();
    public $destinationObjects = array();
    public $serviceObjects = array();
    public $options = array();
    public $decription;


    function __construct($name, $type = self::TYPE) {
        parent::__construct($name, $type);
    }


    function asString($highlightObject = "") {

        $highlight_all = empty($highlightObject);

        $result = self::TYPE . " " . $this->name . " ";

        if (count($this->sourceObjects)) {
            $result .= self::SOURCE_MARK . " " . $this->sourceType . " ";
            $is_first = true;
            foreach ($this->sourceObjects as $obj) {
                if ($highlight_all || ($obj === $highlightObject)) {
                    $result .= "<b>" . $obj . "</b>" . " ";
                } else {
                    $result .= $obj . " ";
                }
                if ($is_first) {
                    $result .= " --> ";
                    $is_first = false;
                }
            }
        }

        if (count($this->destinationObjects)) {
            $result .= self::DESTINATION_MARK . " " . $this->destinationType . " ";
            $is_first = true;
            foreach ($this->destinationObjects as $obj) {
                if ($highlight_all || ($obj === $highlightObject)) {
                    $result .= "<b>" . $obj . "</b>" . " ";
                } else {
                    $result .= $obj . " ";
                }
                if ($is_first) {
                    $result .= " --> ";
                    $is_first = false;
                }
            }
        }

        if (count($this->serviceObjects)) {
            $result .= self::SERVICE_MARK . " ";
            $is_first = true;
            foreach ($this->serviceObjects as $obj) {
                if ($highlight_all || ($obj === $highlightObject)) {
                    $result .= "<b>" . $obj . "</b>" . " ";
                } else {
                    $result .= $obj . " ";
                }
                if ($is_first) {
                    $result .= " --> ";
                    $is_first = false;
                }
            }
        }

        foreach ($this->options as $option) {
            $result .= $option . " ";
        }

        if (!empty($this->description)) {
            $result .= "<i>" . self::DESCRIPTION_MARK . " " . $this->description . "</i>";
        }

        return $result;
    }

}

?>
