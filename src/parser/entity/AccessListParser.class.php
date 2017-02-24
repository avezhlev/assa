<?php

require_once(__DIR__."/../../tokenizer/FileTokenizer.class.php");
require_once(__DIR__ . "/../../entity/CommonEntity.class.php");
require_once(__DIR__ . "/../../entity/service/AccessList.class.php");

class AccessListParser {

    const SCOPE = "access-list";
    const TYPES = array("standard", "extended");

    static function parse() {

        $tokenizer = FileTokenizer::getInstance();

        $aclName = $tokenizer->nextToken();
        $acl = new AccessList($aclName);

        $nextAclName = $aclName;
        $stepBack = false;

        while ($aclName === $nextAclName) {

            $token = $tokenizer->nextToken();
            if (self::isACLType($token)) {
                if ($acl->getType() !== $token) {
                    $acl->setType($token);
                }
                $token = $tokenizer->nextToken();
            }

            $childType = $token;
            $childName = "";

            while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
                $childName .= $token . " ";
            }

            $acl->addChild(new CommonEntity(trim($childName), $childType));

            if (self::SCOPE === $tokenizer->nextToken()) {
                $nextAclName = $tokenizer->nextToken();
                $stepBack = true;
            } else {
                $nextAclName = "";
            }

        }

        $tokenizer->previousToken();
        if ($stepBack) {
            $tokenizer->previousToken();
        }

        return $acl;

    }

    static function isACLType($data) {

        foreach (self::TYPES as $type) {
            if ($data === $type) {
                return true;
            }
        }
        return false;
    }

}

?>
