<?php

require_once("CommonEntity.class.php");

class CommonContainer extends CommonEntity {

    const TYPE = "common-container";

    public $children = array();

    function addChild($child) {
        $this->children[] = $child;
    }

}

?>
