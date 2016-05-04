<?php

	require_once("filetokenizer.class.php");

	class NATParser {
		
		const SCOPE = "nat";
		
		static function parse() {
				
			$tokenizer = FileTokenizer::getInstance();
				
			$nat_rule = self::SCOPE;
			while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
				$nat_rule .= " " . $token;
			}
			return $nat_rule;
			
		}
	
	}
	
?>
