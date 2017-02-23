<?php

require_once(__DIR__."/../../tokenizer/FileTokenizer.class.php");
require_once(__DIR__ . "/../../entity/service/NatRule.class.php");

class NatRuleParser {

    const SCOPE = "nat";

    static function parse() {

        $tokenizer = FileTokenizer::getInstance();

        $nat_rule = new NatRule($tokenizer->nextToken());

        if (NatRule::SOURCE_MARK === $tokenizer->nextToken()) {
            $nat_rule->source_type = $tokenizer->nextToken();
            $nat_rule->source_objects[] = $tokenizer->nextToken();
            $nat_rule->source_objects[] = $tokenizer->nextToken();
        } else {
            $tokenizer->previousToken();
        }

        if (NatRule::DESTINATION_MARK === $tokenizer->nextToken()) {
            $nat_rule->destination_type = $tokenizer->nextToken();
            $nat_rule->destination_objects[] = $tokenizer->nextToken();
            $nat_rule->destination_objects[] = $tokenizer->nextToken();
        } else {
            $tokenizer->previousToken();
        }

        if (NatRule::SERVICE_MARK === $tokenizer->nextToken()) {
            $nat_rule->service_objects[] = $tokenizer->nextToken();
            $nat_rule->service_objects[] = $tokenizer->nextToken();
        } else {
            $tokenizer->previousToken();
        }

        $nat_rule->description = "";
        while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
            if ($token === NatRule::DESCRIPTION_MARK) {
                while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
                    $nat_rule->description .= $token . " ";
                }
                break;
            } else {
                $nat_rule->options[] = $token;
            }
        }

        return $nat_rule;

    }

}

?>