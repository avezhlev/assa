<?php

	require_once("filetokenizer.class.php");
	require_once("commonobject.class.php");
	require_once("networkobject.class.php");

	class ObjectParser {
		
		const SCOPE = "object";
		const NETWORK_SUBSCOPE = "network";
		const CHILD_TYPES = array("host", "subnet", "range", "description", "nat");
		
		static function parse() {
			
			$tokenizer = FileTokenizer::getInstance();
			
			switch ($tokenizer->nextToken()) {
				
				case self::NETWORK_SUBSCOPE:
					$network_object = new NetworkObject($tokenizer->nextToken());
					$tokenizer->nextToken();//EOL
					while (self::isNetworkObjectChild($token = $tokenizer->nextToken())) {
						$child_type = $token;
						$child_name = "";
						while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
							$child_name .= $token . " ";
						}
						$network_object->addChild(new CommonObject(trim($child_name), $child_type));
					}
					$tokenizer->previousToken();
					return $network_object;
				}
			
			return false;
			
		}
		
		static function isNetworkObjectChild($data) {
			
			foreach (self::CHILD_TYPES as $type) {
				if ($data === $type) {
					return true;
				}
			}
			
			return false;
		}
	
	}
	
?>
