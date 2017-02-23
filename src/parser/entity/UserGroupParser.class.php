<?php

require_once(__DIR__."/../../tokenizer/FileTokenizer.class.php");
require_once(__DIR__ . "/../../entity/CommonEntity.class.php");
require_once(__DIR__ . "/../../entity/user/UserGroup.class.php");

class UserGroupParser {

    const SUBSCOPE = "user";
    const CHILD_TYPE = "user";

    static function parse() {

        $tokenizer = FileTokenizer::getInstance();

        $user_group = new UserGroup($tokenizer->nextToken());
        $tokenizer->nextToken();//EOL
        while (($token = $tokenizer->nextToken()) === self::CHILD_TYPE) {
            $data = preg_split("~\\\\~", $tokenizer->nextToken());
            $child_type = $data[0];
            $child_name = $data[1];
            while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
                $child_name .= " " . $token;
            }
            $user_group->addChild(new CommonEntity($child_name, $child_type));
        }
        $tokenizer->previousToken();

        return $user_group;
    }

}

?>
