<?php

	require_once("filetokenizer.class.php");
	require_once("objectparser.class.php");
	require_once("objectgroupparser.class.php");
	require_once("userparser.class.php");
	require_once("accesslistparser.class.php");
	require_once("natparser.class.php");
	require_once("publicserviceparser.class.php");
	require_once("routeparser.class.php");

	class TokenParser {
		
		static function parse($uploaded_file) {
			
			$network_objects = array();
			$network_groups = array();
			$users = array();
			$user_groups = array();
			$access_lists = array();
			$nat_rules = array();
			$public_services = array();
			$routes = array();
		
			$tokenizer = FileTokenizer::getInstance($uploaded_file);
			
			while (($token = $tokenizer->nextLineStarter()) !== FileTokenizer::EOF_MARK) {
				
				switch ($token) {
					
					case ObjectParser::SCOPE:
						if ($data = ObjectParser::parse()) {
							switch (true) {
								
								case $data instanceof NetworkObject:
									$network_objects[] = $data;
									break;
									
								case $data instanceof PublicService:
									$public_services[] = $data;
									break;	
							}
						}
						break;
						
					case ObjectGroupParser::SCOPE:
						if ($data = ObjectGroupParser::parse()) {
							switch (true) {
								
								case $data instanceof NetworkGroup:
									$network_groups[] = $data;
									break;
									
								case $data instanceof UserGroup:
									$user_groups[] = $data;
									break;
							}
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
					
					case RouteParser::SCOPE:
						if ($data = RouteParser::parse()) {
							$routes[] = $data;
						}
						break;
				}
				
			}
			
			return array('objects' => $network_objects,
						'groups' => $network_groups,
						'users' => $users,
						'user-groups' => $user_groups,
						'acl' => $access_lists,
						'nat' => $nat_rules,
						'ps' => $public_services,
						'routes' => $routes
						);
			
		}
	}
	
?>
