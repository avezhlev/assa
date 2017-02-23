<?php

require_once(__DIR__ . "/../CommonEntity.class.php");

class Route extends CommonEntity {

    const TYPE = "route";
    const IPV6_TYPE = "ipv6 route";

    public $ip_version;
    public $subnet;
    public $mask;
    public $next_hop;
    public $metric;


    function __construct($name, $type = self::TYPE) {
        parent::__construct($name, $type);
    }


    function asString() {

        if ($this->ip_version === 6) {
            $result = self::IPV6_TYPE;
        } else {
            $result = self::TYPE;
        }

        $result .= " " . $this->name . " <b>" . $this->subnet . "/" . $this->mask . "</b> --> <b>" . $this->next_hop . "</b> " . $this->metric;

        return $result;
    }

}

?>
