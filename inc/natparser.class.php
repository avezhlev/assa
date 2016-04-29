<?php

	require_once("commonparser.class.php");
	require_once("filetokenizer.class.php");

	class NATParser extends CommonParser {
		
		const TRIGGER = "nat";
		
		static function parse() {
			
			if (self::isPreviousBOFOrEOL()) {
				
				$tokenizer = FileTokenizer::getInstance();
				
				$nat_rule = self::TRIGGER;
				while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
					$nat_rule .= " " . $token;
				}
				return $nat_rule;
			}
			
			return false;
			
		}
	
	}
	
?>
