<?php

require_once(__DIR__."/../../tokenizer/FileTokenizer.class.php");
require_once(__DIR__ . "/../../entity/service/PublicService.class.php");

class PublicServiceParser {

    const SUBSCOPE = "nat";

    static function parse($outer_object) {

        $tokenizer = FileTokenizer::getInstance();

        $public_service = new PublicService($tokenizer->nextToken());
        $public_service->outer_object = $outer_object;
        $public_service->service_type = $tokenizer->nextToken();
        $public_service->inner_object = $tokenizer->nextToken();

        while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
            $public_service->options[] = $token;
        }

        return $public_service;
    }

}

?>