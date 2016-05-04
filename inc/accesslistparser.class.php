<?php

	require_once("filetokenizer.class.php");
	require_once("commonobject.class.php");
	require_once("accesslist.class.php");

	class AccessListParser {
		
		const SCOPE = "access-list";
		const TYPES = array("standard", "extended");
		
		static function parse() {
				
			$tokenizer = FileTokenizer::getInstance();
				
			$acl_name = $tokenizer->nextToken();
			$acl = new AccessList($acl_name);
				
			$next_acl_name = $acl_name;
			$step_back = false;
			
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
					
				if (self::SCOPE === $tokenizer->nextToken()) {
					$next_acl_name = $tokenizer->nextToken();
					$step_back = true;
				} else {
					$next_acl_name = "";
				}
					
			}
				
			$tokenizer->previousToken();
			if ($step_back) {
				$tokenizer->previousToken();
			}

			return $acl;
			
		}
		
		static function isACLType($data) {
			
			foreach (self::TYPES as $type) {
				if ($data === $type) {
					return true;
				}
			}
			return false;
		}
	
	}
	
?>
