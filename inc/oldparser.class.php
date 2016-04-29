<?php

	define("_OBJ_", "object network");
	define("_GRP_", "object-group network");
	define("_ACL_", "access-list");
	
	require_once("networkobject.class.php");
	require_once("accesslist.class.php");
	require_once("utils.class.php");

	class OldParser {
		
		function parse($uploaded_file) {
			
			$config_file = file_get_contents($uploaded_file);
			$rows = explode("\n", $config_file);
			$scope = "";
			
			$network_objects = array();
			$network_groups = array();
			$access_lists = array();
			$nat_rules = array();

			$network_object = NULL;
			$network_group = NULL;
			$user_groups = NULL;
			$access_list = NULL;
			$current_acl = " ";
	
			foreach ($rows as $row => $data) {
				
				switch ($scope) {
					
					case _OBJ_:
						if ($this->isNetworkObjectChild($data)) {
							$network_object->children[] = new NetworkObject($data);
						} else {
							$network_objects[] = $network_object;
							$scope = "";
						}
						break;
					
					case _GRP_:
						if ($this->isNetworkGroupChild($data)) {
							$network_group->children[] = new NetworkObject($data);
						} else {
							$network_groups[] = $network_group;
							$scope = "";
						}
						break;
						
					case _ACL_:
						if ($this->isCurrentACLPart($data, $current_acl)) {
							$access_list->addChild($data);
						} else {
							$access_lists[] = $access_list;
							$scope = "";
							$current_acl = " ";
						}
						break;
				}
				
				if ($new_scope = $this->checkForScopeChange($data)) {
					
					switch ($new_scope) {
						
						case _OBJ_:
							$network_object = new NetworkObject($data);
							break;
							
						case _GRP_:
							$network_group = new NetworkObject($data);
							break;
							
						case _ACL_:
							$tmp_acl = new AccessList($data);
							if ($tmp_acl->name != $current_acl) {
								$access_list = $tmp_acl;
								$current_acl = $access_list->name;
							}
							break;
					}
					
					$scope = $new_scope;
				}
				
				if (Utils::startsWith($data, "nat")) {
					$nat_rules[] = $data;
				}
			
			}
			
			return array(	'objects'	=> $network_objects,
							'groups'	=> $network_groups,
							'acl'		=> $access_lists,
							'nat'		=> $nat_rules
						);
			
		}
		
		
		function isNetworkObjectChild($row) {
			
			$network_object_children = array("host", "subnet", "range", "description", "nat");
			
			foreach ($network_object_children as $child) {
				if (Utils::startsWith(trim($row), $child)) {
					return true;
				}
			}
			
			return false;
		}
		
		

		function isNetworkGroupChild($row) {
			
			$network_group_children = array("network-object", "group-object", "description");
			
			foreach ($network_group_children as $child) {
				if (Utils::startsWith(trim($row), $child)) {
					return true;
				}
			}
			
			return false;
		}
		
		
		function isCurrentACLPart($row, $current_acl) {
			
			if (Utils::startsWith(trim($row), _ACL_ . " " . $current_acl)) {
				return true;
			} else {
				return false;
			}
		}
		
		

		function checkForScopeChange($row) {
			
			$scope_changers = array(_OBJ_, _GRP_, _ACL_);
			
			foreach ($scope_changers as $changer) {
				if (Utils::startsWith(trim($row), $changer)) {
					return $changer;
				}
			}
			
			return false;
		}
		
	}

?>
