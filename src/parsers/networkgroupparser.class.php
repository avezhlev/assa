<?php

	require_once("filetokenizer.class.php");
	require_once(__DIR__ . "/../inc/commonobject.class.php");
	require_once(__DIR__ . "/../inc/networkgroup.class.php");

	class NetworkGroupParser {
		
		const SUBSCOPE = "network";
		const CHILD_TYPES = array("network-object", "group-object", "description");
		const CHILD_SUBTYPE = "object";

		
		static function parse() {
			
			$tokenizer = FileTokenizer::getInstance();
			
			$network_group = new NetworkGroup($tokenizer->nextToken());
			$tokenizer->nextToken();//EOL
			while (self::isNetworkGroupChild($token = $tokenizer->nextToken())) {
				$child_type = $token;
				if ($token === self::CHILD_TYPES[0]) {
					if (($next_token = $tokenizer->nextToken()) === self::CHILD_SUBTYPE) {
						$child_type .= " " . $next_token;
					} else {
						$tokenizer->previousToken();
					}
				}
				$child_name = "";
				while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
					$child_name .= $token . " ";
				}
				$network_group->addChild(new CommonObject(trim($child_name), $child_type));
			}
			$tokenizer->previousToken();
			
			return $network_group;
		}
		
		
		static function isNetworkGroupChild($data) {
			
			foreach (self::CHILD_TYPES as $type) {
				if ($data === $type) {
					return true;
				}
			}
			
			return false;
		}
	
	}
	
?>
