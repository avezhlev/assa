<?php

	require_once("commonparser.class.php");
	require_once("filetokenizer.class.php");
	require_once("commonobject.class.php");
	require_once("accesslist.class.php");

	class AccessListParser extends CommonParser {
		
		const TRIGGER = "access-list";
		
		static function parse() {
			
			if (self::isPreviousBOFOrEOL()) {
				
				$tokenizer = FileTokenizer::getInstance();
				
				$acl_name = $tokenizer->nextToken();
				$next_acl_name = $acl_name;
				$acl = new AccessList($acl_name);
			
				while ($acl_name === $next_acl_name) {
					
					$token = $tokenizer->nextToken();
					if (self::isACLType($token)) {
						if ($acl->getType() !== $token) {
							$acl->setType($token);
						}
						$token = $tokenizer->nextToken();
					}
					
					$child_type = $token;
					$child_name = "";
					
					while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
						$child_name .= $token . " ";
					}
					
					$acl->addChild(new CommonObject(trim($child_name), $child_type));
					
					if (self::TRIGGER === $tokenizer->nextToken()) {
						$next_acl_name = $tokenizer->nextToken();
					} else {
						$next_acl_name = "";
					}
					
				}
				
				$tokenizer->previousToken();
				$tokenizer->previousToken();

				return $acl;
				
			}
			
			return false;
			
		}
		
		static function isACLType($data) {
			
			$types = array("standard", "extended");
			
			foreach ($types as $type) {
				if ($data === $type) {
					return true;
				}
			}
			return false;
		}
	
	}
	
?>
