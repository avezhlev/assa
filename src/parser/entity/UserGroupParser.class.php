<?php

require_once(__DIR__."/../../tokenizer/FileTokenizer.class.php");
require_once(__DIR__ . "/../../entity/CommonEntity.class.php");
require_once(__DIR__ . "/../../entity/user/UserGroup.class.php");

class UserGroupParser {

    const SUBSCOPE = "user";
    const CHILD_TYPE = "user";

    static function parse() {

        $tokenizer = FileTokenizer::getInstance();

        $userGroup = new UserGroup($tokenizer->nextToken());
        $tokenizer->nextToken();//EOL
        while (($token = $tokenizer->nextToken()) === self::CHILD_TYPE) {
            $data = preg_split("~\\\\~", $tokenizer->nextToken());
            $childType = $data[0];
            $childName = $data[1];
            while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
                $childName .= " " . $token;
            }
            $userGroup->addChild(new CommonEntity($childName, $childType));
        }
        $tokenizer->previousToken();

        return $userGroup;
    }

}

?>
