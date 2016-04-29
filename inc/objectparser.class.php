<?php

	require_once("commonparser.class.php");
	require_once("filetokenizer.class.php");
	require_once("commonobject.class.php");
	require_once("networkobject.class.php");

	class ObjectParser extends CommonParser {
		
		const TRIGGER = "object";
		
		static function parse() {
			
			$tokenizer = FileTokenizer::getInstance();
			
			switch ($tokenizer->nextToken()) {
				
				case "network":
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
					return $network_object;
			}
			
			return false;
			
		}
		
		static function isNetworkObjectChild($data) {
			
			$network_object_children = array("host", "subnet", "range", "description", "nat");
			
			foreach ($network_object_children as $child) {
				if ($data === $child) {
					return true;
				}
			}
			
			return false;
		}
	
	}
	
?>
