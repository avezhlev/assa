<?php

require_once(__DIR__."/../../tokenizer/FileTokenizer.class.php");
require_once(__DIR__ . "/../../entity/service/NatRule.class.php");

class NatRuleParser {

    const SCOPE = "nat";

    static function parse() {

        $tokenizer = FileTokenizer::getInstance();

        $natRule = new NatRule($tokenizer->nextToken());

        if (NatRule::SOURCE_MARK === $tokenizer->nextToken()) {
            $natRule->sourceType = $tokenizer->nextToken();
            $natRule->sourceObjects[] = $tokenizer->nextToken();
            $natRule->sourceObjects[] = $tokenizer->nextToken();
        } else {
            $tokenizer->previousToken();
        }

        if (NatRule::DESTINATION_MARK === $tokenizer->nextToken()) {
            $natRule->destinationType = $tokenizer->nextToken();
            $natRule->destinationObjects[] = $tokenizer->nextToken();
            $natRule->destinationObjects[] = $tokenizer->nextToken();
        } else {
            $tokenizer->previousToken();
        }

        if (NatRule::SERVICE_MARK === $tokenizer->nextToken()) {
            $natRule->serviceObjects[] = $tokenizer->nextToken();
            $natRule->serviceObjects[] = $tokenizer->nextToken();
        } else {
            $tokenizer->previousToken();
        }

        $natRule->description = "";
        while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
            if ($token === NatRule::DESCRIPTION_MARK) {
                while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
                    $natRule->description .= $token . " ";
                }
                break;
            } else {
                $natRule->options[] = $token;
            }
        }

        return $natRule;

    }

}

?>
