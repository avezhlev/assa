<?php

	require_once("filetokenizer.class.php");
	require_once(__DIR__ . "/../inc/commonobject.class.php");
	require_once(__DIR__ . "/../inc/networkgroup.class.php");
	require_once(__DIR__ . "/../inc/usergroup.class.php");

	class ObjectGroupParser {
		
		const SCOPE = "object-group";
		const NETWORK_SUBSCOPE = "network";
		const NETWORK_CHILD_TYPES = array("network-object", "group-object", "description");
		const NETWORK_CHILD_SUBTYPE = "object";
		const USER_SUBSCOPE = "user";
		const USER_CHILD_TYPE = "user";
		
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
					
				
				case self::USER_SUBSCOPE:
					$user_group = new UserGroup($tokenizer->nextToken());
					$tokenizer->nextToken();//EOL
					while (($token = $tokenizer->nextToken()) === self::USER_CHILD_TYPE) {
						$data = preg_split("~\\\\~", $tokenizer->nextToken());
						$child_type = $data[0];
						$child_name = $data[1];
						while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
							$child_name .= " " . $token;
						}
						$user_group->addChild(new CommonObject($child_name, $child_type));
					}
					$tokenizer->previousToken();
					return $user_group;
					
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
