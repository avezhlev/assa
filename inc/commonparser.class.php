<?php

	require_once("filetokenizer.class.php");

	class CommonParser {
		
		static function isPreviousBOFOrEOL() {
			
			$tokenizer = FileTokenizer::getInstance();
			$token = $tokenizer->previousToken();
			$tokenizer->nextToken();
			return ($token === FileTokenizer::BOF_MARK || $token === FileTokenizer::EOL_MARK);
		}
	
	}
	
?>
