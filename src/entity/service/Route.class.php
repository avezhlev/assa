<?php

require_once(__DIR__ . "/../CommonEntity.class.php");

class Route extends CommonEntity {

    const TYPE = "route";
    const IPV6_TYPE = "ipv6 route";

    public $ipVersion;
    public $subnet;
    public $mask;
    public $nextHop;
    public $metric;


    function __construct($name, $type = self::TYPE) {
        parent::__construct($name, $type);
    }


    function asString() {

        if ($this->ipVersion === 6) {
            $result = self::IPV6_TYPE;
        } else {
            $result = self::TYPE;
        }

        $result .= " " . $this->name . " <b>" . $this->subnet . "/" . $this->mask . "</b> --> <b>" . $this->nextHop . "</b> " . $this->metric;

        return $result;
    }

}

?>
