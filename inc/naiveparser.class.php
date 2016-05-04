<?php

	require_once("filetokenizer.class.php");
	require_once("objectparser.class.php");
	require_once("objectgroupparser.class.php");
	require_once("userparser.class.php");
	require_once("accesslistparser.class.php");
	require_once("natparser.class.php");

	class NaiveParser {
		
		static function parse($uploaded_file) {
			
			$network_objects = array();
			$network_groups = array();
			$users = array();
			$access_lists = array();
			$nat_rules = array();
		
			$tokenizer = FileTokenizer::getInstance($uploaded_file);
			
			while (($token = $tokenizer->nextLineStarter()) !== FileTokenizer::EOF_MARK) {
				
				switch ($token) {
					
					case ObjectParser::SCOPE:
						if ($data = ObjectParser::parse()) {
							$network_objects[] = $data;
						}
						break;
						
					case ObjectGroupParser::SCOPE:
						if ($data = ObjectGroupParser::parse()) {
							$network_groups[] = $data;
						}
						break;
						
					case UserParser::SCOPE:
						if ($data = UserParser::parse()) {
							$users[] = $data;
						}
						break;
						
					case AccessListParser::SCOPE:
						if ($data = AccessListParser::parse()) {
							$access_lists[] = $data;
						}
						break;
					
					case NATParser::SCOPE:
						if ($data = NATParser::parse()) {
							$nat_rules[] = $data;
						}
						break;
					
				}
				
			}
			
			return array('objects' => $network_objects,
						'groups' => $network_groups,
						'users' => $users,
						'acl' => $access_lists,
						'nat' => $nat_rules
						);
			
		}
	}
	
?>
