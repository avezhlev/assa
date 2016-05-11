<?php

	require_once("filetokenizer.class.php");
	require_once("publicserviceparser.class.php");
	require_once(__DIR__ . "/../inc/commoncontainer.class.php");
	require_once(__DIR__ . "/../inc/networkobject.class.php");

	class ObjectParser {
		
		const SCOPE = "object";
		const NETWORK_SUBSCOPE = "network";
		const NETWORK_CHILD_TYPES = array("host", "subnet", "range", "description");
		
		static function parse() {
			
			$tokenizer = FileTokenizer::getInstance();
			
			switch ($tokenizer->nextToken()) {
				
				case self::NETWORK_SUBSCOPE:
					$network_object = new NetworkObject($tokenizer->nextToken());
					$tokenizer->nextToken();//EOL
					if (PublicServiceParser::SUBSCOPE === $tokenizer->nextToken()) {
						return PublicServiceParser::parse($network_object->name);
					}
					$tokenizer->previousToken();
					while (self::isNetworkObjectChild($token = $tokenizer->nextToken())) {
						$child_type = $token;
						$child_name = "";
						while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
							$child_name .= $token . " ";
						}
						$network_object->addChild(new CommonContainer(trim($child_name), $child_type));
					}
					$tokenizer->previousToken();
					
					return $network_object;
			}
			
			return false;
		}
		
		static function isNetworkObjectChild($data) {
			
			foreach (self::NETWORK_CHILD_TYPES as $type) {
				if ($data === $type) {
					return true;
				}
			}
			
			return false;
		}
	
	}
	
?>
