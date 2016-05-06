<?php

	require_once("filetokenizer.class.php");
	require_once("networkgroupparser.class.php");
	require_once("usergroupparser.class.php");
	require_once(__DIR__ . "/../inc/commonobject.class.php");

	class ObjectGroupParser {
		
		const SCOPE = "object-group";
		
		static function parse() {
			
			$tokenizer = FileTokenizer::getInstance();
			
			switch ($tokenizer->nextToken()) {
				
				case NetworkGroupParser::SUBSCOPE:
					if ($data = NetworkGroupParser::parse()) {
						return $data;
					} else {
						return false;
					}
					
				
				case UserGroupParser::SUBSCOPE:
					if ($data = UserGroupParser::parse()) {
						return $data;
					} else {
						return false;
					}
			}
			
			return false;
			
		}
	
	}
	
?>
