<?php

	require_once("filetokenizer.class.php");
	require_once("commonobject.class.php");
	require_once("networkgroup.class.php");

	class ObjectGroupParser {
		
		const SCOPE = "object-group";
		const NETWORK_SUBSCOPE = "network";
		const NETWORK_CHILD_TYPES = array("network-object", "group-object", "description");
		const NETWORK_CHILD_SUBTYPE = "object";
		
		static function parse() {
			
			$tokenizer = FileTokenizer::getInstance();
			
			switch ($tokenizer->nextToken()) {
				
				case self::NETWORK_SUBSCOPE:
					$network_group = new NetworkGroup($tokenizer->nextToken());
					$tokenizer->nextToken();//EOL
					while (self::isNetworkGroupChild($token = $tokenizer->nextToken())) {
						$child_type = $token;
						if ($token == self::NETWORK_CHILD_TYPES[0]) {
							if (($next_token = $tokenizer->nextToken()) === self::NETWORK_CHILD_SUBTYPE) {
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
			
			return false;
			
		}
		
		static function isNetworkGroupChild($data) {
			
			foreach (self::NETWORK_CHILD_TYPES as $type) {
				if ($data === $type) {
					return true;
				}
			}
			
			return false;
		}
	
	}
	
?>
