<?php

	require_once("filetokenizer.class.php");
	require_once("routeparser.class.php");

	class IPv6Parser {
		
		const SCOPE = "ipv6";
		CONST IP_VERSION = 6;
		
		static function parse() {
			
			$tokenizer = FileTokenizer::getInstance();
			
			switch ($tokenizer->nextToken()) {
				
				case RouteParser::SCOPE:
					if ($data = RouteParser::parse(self::IP_VERSION)) {
						return $data;
					} else {
						return false;
					}
			}
			
			return false;
		}
	
	}
	
?>
