<?php

class CommonEntity {

    const TYPE = "common-entity";

    public $name;
    public $type;

    function __construct($name, $type = self::TYPE) {
        $this->name = $name;
        $this->type = $type;
    }

    function setType($type) {
        $this->type = $type;
    }

    function getType() {
        return $this->type;
    }

}

?>
