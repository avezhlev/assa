<?php

require_once(__DIR__."/../../tokenizer/FileTokenizer.class.php");
require_once(__DIR__ . "/../../entity/CommonEntity.class.php");
require_once(__DIR__ . "/../../entity/network/NetworkGroup.class.php");

class NetworkGroupParser {

    const SUBSCOPE = "network";
    const CHILD_TYPES = array("network-object", "group-object", "description");
    const CHILD_SUBTYPE = "object";


    static function parse() {

        $tokenizer = FileTokenizer::getInstance();

        $networkGroup = new NetworkGroup($tokenizer->nextToken());
        $tokenizer->nextToken();//EOL
        while (self::isNetworkGroupChild($token = $tokenizer->nextToken())) {
            $childType = $token;
            if ($token === self::CHILD_TYPES[0]) {
                if (($next_token = $tokenizer->nextToken()) === self::CHILD_SUBTYPE) {
                    $childType .= " " . $next_token;
                } else {
                    $tokenizer->previousToken();
                }
            }
            $childName = "";
            while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
                $childName .= $token . " ";
            }
            $networkGroup->addChild(new CommonEntity(trim($childName), $childType));
        }
        $tokenizer->previousToken();

        return $networkGroup;
    }


    static function isNetworkGroupChild($data) {

        foreach (self::CHILD_TYPES as $type) {
            if ($data === $type) {
                return true;
            }
        }

        return false;
    }

}

?>
