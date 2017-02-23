<?php

require_once(__DIR__."/../../tokenizer/FileTokenizer.class.php");
require_once("RouteParser.class.php");

class IPv6Parser {

    const SCOPE = "ipv6";
    CONST IP_VERSION = 6;

    static function parse() {

        $tokenizer = FileTokenizer::getInstance();

        switch ($tokenizer->nextToken()) {

            case RouteParser::SCOPE:
                if ($data = RouteParser::parse(self::IP_VERSION)) {
                    return $data;
                } else {
                    return false;
                }
        }

        return false;
    }

}

?>
