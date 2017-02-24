<?php

require_once(__DIR__."/../../tokenizer/FileTokenizer.class.php");
require_once(__DIR__ . "/../../entity/service/PublicService.class.php");

class PublicServiceParser {

    const SUBSCOPE = "nat";

    static function parse($outer_object) {

        $tokenizer = FileTokenizer::getInstance();

        $publicService = new PublicService($tokenizer->nextToken());
        $publicService->outerObject = $outer_object;
        $publicService->serviceType = $tokenizer->nextToken();
        $publicService->innerObject = $tokenizer->nextToken();

        while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
            $publicService->options[] = $token;
        }

        return $publicService;
    }

}

?>
