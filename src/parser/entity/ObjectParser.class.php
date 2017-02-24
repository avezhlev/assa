<?php

require_once(__DIR__."/../../tokenizer/FileTokenizer.class.php");
require_once(__DIR__ . "/../../entity/CommonEntity.class.php");
require_once(__DIR__ . "/../../entity/network/NetworkObject.class.php");
require_once("PublicServiceParser.class.php");

class ObjectParser {

    const SCOPE = "object";
    const NETWORK_SUBSCOPE = "network";
    const NETWORK_CHILD_TYPES = array("host", "subnet", "range", "description");

    static function parse() {

        $tokenizer = FileTokenizer::getInstance();

        switch ($tokenizer->nextToken()) {

            case self::NETWORK_SUBSCOPE:
                $networkObject = new NetworkObject($tokenizer->nextToken());
                $tokenizer->nextToken();//EOL
                if (PublicServiceParser::SUBSCOPE === $tokenizer->nextToken()) {
                    return PublicServiceParser::parse($networkObject->name);
                }
                $tokenizer->previousToken();
                while (self::isNetworkObjectChild($token = $tokenizer->nextToken())) {
                    $childType = $token;
                    $childName = "";
                    while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
                        $childName .= $token . " ";
                    }
                    $networkObject->addChild(new CommonEntity(trim($childName), $childType));
                }
                $tokenizer->previousToken();

                return $networkObject;
        }

        return false;
    }

    static function isNetworkObjectChild($data) {

        foreach (self::NETWORK_CHILD_TYPES as $type) {
            if ($data === $type) {
                return true;
            }
        }

        return false;
    }

}

?>
