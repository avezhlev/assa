<?php

require_once(__DIR__."/../../tokenizer/FileTokenizer.class.php");
require_once(__DIR__ . "/../../entity/CommonEntity.class.php");
require_once(__DIR__ . "/../../entity/user/User.class.php");

class UserParser {

    const SCOPE = "username";
    const ATTRIBUTE_TYPES = array("vpn-group-policy", "vpn-tunnel-protocol", "service-type");

    static function parse() {

        $tokenizer = FileTokenizer::getInstance();

        $user = new User($tokenizer->nextToken());

        while (($token = $tokenizer->nextToken()) !== User::SUBSCOPE) {
        }
        $tokenizer->nextToken(); //move to EOL

        while (self::isAttributeType($token = $tokenizer->nextToken())) {
            $childType = $token;
            $childName = "";

            while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
                $childName .= $token . " ";
            }
            $user->addChild(new CommonEntity(trim($childName), $childType));
        }

        $tokenizer->previousToken();

        return $user;
    }


    static function isAttributeType($data) {

        foreach (self::ATTRIBUTE_TYPES as $type) {
            if ($data === $type) {
                return true;
            }
        }
        return false;
    }

}

?>
