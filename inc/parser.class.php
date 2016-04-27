<?php

	define("_OBJ_", "object network");
	define("_GRP_", "object-group network");
	define("_ACL_", "access-list");
	
	require_once("networkobject.class.php");
	require_once("accesslist.class.php");
	require_once("utils.class.php");

	class Parser {
		
		private $filters = array();
		private $network_objects = array();
		private $network_groups = array();
		private $nat_rules = array();
		private $access_lists = array();
		private $old_access_lists = array();
		

		function isNetworkObjectChild($row) {
			
			$network_object_children = array("host", "subnet", "range", "description", "nat");
			
			foreach ($network_object_children as $needle) {
				if (Utils::startsWith(trim($row), $needle)) {
					return true;
				}
			}
			
			return false;
		}
		
		

		function isNetworkGroupChild($row) {
			
			$network_group_children = array("network-object", "group-object", "description");
			
			foreach ($network_group_children as $needle) {
				if (Utils::startsWith(trim($row), $needle)) {
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
		
		

		function checkForContextChange($row) {
			
			$context_changers = array(_OBJ_, _GRP_, _ACL_);
			
			foreach ($context_changers as $needle) {
				if (Utils::startsWith(trim($row), $needle)) {
					return $needle;
				}
			}
			
			return false;
		}
		
		

		function Parser($uploaded_file, $filters) {
		
			$this->filters = $filters;
			
			$config_file = file_get_contents($uploaded_file);
			$rows = explode("\n", $config_file);
			$context = "";
			$current_acl = " ";

			$network_object = NULL;
			$network_group = NULL;
			$acl = NULL;
	
			foreach ($rows as $row => $data) {
				
				switch ($context) {
					
					case _OBJ_:
						if ($this->isNetworkObjectChild($data)) {
							$network_object->children[] = new NetworkObject($data);
						} else {
							$this->network_objects[] = $network_object;
							$context = "";
						}
						break;
					
					case _GRP_:
						if ($this->isNetworkGroupChild($data)) {
							$network_group->children[] = new NetworkObject($data);
						} else {
							$this->network_groups[] = $network_group;
							$context = "";
						}
						break;
						
					case _ACL_:
						if ($this->isCurrentACLPart($data, $current_acl)) {
							$acl->addChild($data);
						} else {
							$this->access_lists[] = $acl;
							$context = "";
							$current_acl = " ";
						}
						break;
				}
				
				if ($new_context = $this->checkForContextChange($data)) {
					
					switch ($new_context) {
						
						case _OBJ_:
							$network_object = new NetworkObject($data);
							break;
							
						case _GRP_:
							$network_group = new NetworkObject($data);
							break;
							
						case _ACL_:
							$tmp_acl = new AccessList($data);
							if ($tmp_acl->name != $current_acl) {
								$acl = $tmp_acl;
								$current_acl = $acl->name;
							}
							break;
					}
					
					$context = $new_context;
				}
				
				if (Utils::startsWith($data, "nat")) {
					$this->nat_rules[] = $data;
				}
			
			}
			
		}
		
		

		function mentionedInNATRule($name) {
			
			$results = array();
			
			foreach ($this->nat_rules as $rule) {
				if (strpos($rule . " ", " " . $name . " ") !== false) {
					$results[] = Utils::addBoldTags($rule, " " . $name);
				}
			}
			
			return empty($results) ? false : $results;
			
		}
		
		

		function mentionedInACL($name) {
			
			$results = array();
			
			foreach ($this->access_lists as $acl) {
				foreach ($acl->children as $child) {
					if (strpos($child . " ", " " . $name . " ") !== false) {
						$results[] = $acl;
						break;
					}
				}
			}
			
			return empty($results) ? false : $results;
			
		}
		
		

		function showData() {
			
			echo "<head></head>";
			echo "<body><link href='css/styles.css' rel='stylesheet'>";
			
			echo "<div class='wrapper'><div class='table'>";
			
			echo "<div class='row header green'><div class='cell'>Object</div>";
			if ($this->filters['nat']) {
				echo "<div class='cell'>NAT rule</div>";
			}
			if ($this->filters['acl']) {
				echo "<div class='cell'>ACL</div>";
			}
			echo "</div>";
			
			foreach ($this->network_objects as $obj) {
				
				$rules = $this->mentionedInNATRule($obj->name);
				$acls = $this->mentionedInACL($obj->name);
				
				if ($this->filters['empty'] || $this->filters['nat'] && $rules || $this->filters['acl'] && $acls) {
					
					echo "<div class='row'><div class='cell nowrap'>";
					$obj->showAsUnorderedList();
					echo "</div>";
					
					if ($this->filters['nat']) {
						echo "<div class='cell'>";
						if ($rules) {
							foreach ($rules as $rule) {
								echo $rule . "<br /><br />";
							}
						}
						echo "</div>";
					}
					
					if ($this->filters['acl']) {
						echo "<div class='cell nowrap'>";
						if ($acls) {
							foreach ($acls as $acl) {
								$acl->showAsUnorderedList();
								echo "<br />";
							}
						}
						echo "</div>";
					}
					echo "</div>";
				}
			}
			
			echo "</div><br /><br />";
			
			
			echo "<div class='table'>";
			
			echo "<div class='row header blue'><div class='cell'>Group</div>";
			if ($this->filters['nat']) {
				echo "<div class='cell'>NAT rule</div>";
			}
			if ($this->filters['acl']) {
				echo "<div class='cell'>ACL</div>";
			}
			echo "</div>";
			
			foreach ($this->network_groups as $group) {
				
				$rules = $this->mentionedInNATRule($group->name);
				$acls = $this->mentionedInACL($group->name);
				
				if ($this->filters['empty'] || $this->filters['nat'] && $rules || $this->filters['acl'] && $acls) {
					
					echo "<div class='row'><div class='cell nowrap'>";
					$group->showAsUnorderedList(true, $this->network_objects, $this->network_groups);
					echo "</div>";
					
					if ($this->filters['nat']) {
						echo "<div class='cell'>";
						if ($rules) {
							foreach ($rules as $rule) {
								echo $rule . "<br /><br />";
							}
						}
						echo "</div>";
					}
					
					if ($this->filters['acl']) {
						echo "<div class='cell nowrap'>";
						if ($acls) {
							foreach ($acls as $acl) {
								$acl->showAsUnorderedList();
								echo "<br />";
							}
						}
						echo "</div>";
					}
					echo "</div>";
				}
			}
			
			echo "</div></div>";
			echo "<script src='js/tree.js'></script>";
			echo "</body>";
			
		}
		
		
		
	}

?>