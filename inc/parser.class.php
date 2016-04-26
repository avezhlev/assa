<?php

	require_once("networkobject.class.php");
	require_once("utils.class.php");
	
	define("_OBJ_", "object network");
	define("_GRP_", "object-group network");
	

	class Parser {
		
		private $filters = array();
		private $network_objects = array();
		private $network_groups = array();
		private $nat_rules = array();
		private $access_lists = array();
		
		/**************************************************
		**************************************************/
		function isNetworkObjectChild($row) {
			
			$network_object_children = array("host", "subnet", "range", "description", "nat");
			
			foreach ($network_object_children as $needle) {
				if (Utils::startsWith(trim($row), $needle)) {
					return true;
				}
			}
			
			return false;
		}
		
		
		/**************************************************
		**************************************************/
		function isNetworkGroupChild($row) {
			
			$network_group_children = array("network-object", "group-object", "description");
			
			foreach ($network_group_children as $needle) {
				if (Utils::startsWith(trim($row), $needle)) {
					return true;
				}
			}
			
			return false;
		}
		
		
		/**************************************************
		**************************************************/
		function checkForContextChange($row) {
			
			$context_changers = array(_OBJ_, _GRP_);
			
			foreach ($context_changers as $needle) {
				if (Utils::startsWith(trim($row), $needle)) {
					return $needle;
				}
			}
			
			return false;
		}
		
		
		/**************************************************
		**************************************************/
		function Parser($uploaded_file, $filters) {
		
			$this->filters = $filters;
			
			$config_file = file_get_contents($uploaded_file);
			$rows = explode("\n", $config_file);
			$context = '';

			$network_object = NULL;
			$network_group = NULL;
	
			foreach ($rows as $row => $data) {
				
				switch ($context) {
					
					case _OBJ_:
						if ($this->isNetworkObjectChild($data)) {
							$network_object->children[] = new NetworkObject($data);
						} else {
							$this->network_objects[] = $network_object;
							$context = '';
						}
						break;
					
					case _GRP_:
						if ($this->isNetworkGroupChild($data)) {
							$network_group->children[] = new NetworkObject($data);
						} else {
							$this->network_groups[] = $network_group;
							$context = '';
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
					}
					
					$context = $new_context;
				}
				
				if (Utils::startsWith($data, "nat")) {
					$this->nat_rules[] = $data;
				} else if (Utils::startsWith($data, "access-list")) {
					$this->access_lists[] = $data;
				}
			
			}
			
		}
		
		
		/**************************************************
		**************************************************/
		function mentionedInNATRule($name) {
			
			$results = array();
			
			foreach ($this->nat_rules as $rule) {
				if (strpos($rule . " ", " " . $name . " ") !== false) {
					$results[] = Utils::addBoldTags($rule, " " . $name);
				}
			}
			
			return empty($results) ? false : $results;
			
		}
		
		
		/**************************************************
		**************************************************/
		function mentionedInACL($name) {
			
			$results = array();
			
			foreach ($this->access_lists as $acl) {
				if (strpos($acl . " ", " " . $name . " ") !== false) {
					$results[] = Utils::addBoldTags($acl, $name);
				}
			}
			
			return empty($results) ? false : $results;
			
		}
		
		
		/**************************************************
		**************************************************/
		function showGroupChildWithChildren($name, $type) {
			
			if ($type == "group-object") {
				foreach ($this->network_groups as $group) {
					if ($group->name == $name) {
						echo "<li>" . $group->type . " " . $group->name;
						if (!empty($group->children)) {
							echo "<ul>";
							foreach ($group->children as $child) {
								$this->showGroupChildWithChildren($child->name, $child->type);
							}
							echo "</ul>";
						}
						echo "</li>";
					}
				}
			} else if ($type == "network-object object") {
				foreach ($this->network_objects as $obj) {
					if ($obj->name == $name) {
						echo "<li>" . $obj->type . " " . $obj->name;
						if (!empty($obj->children)) {
							echo "<ul>";
							foreach ($obj->children as $child) {
								echo "<li>" . $child->type . " " . $child->name . "</li>";
							}
							echo "</ul>";
						}
						echo "</li>";
					}
				}
			} else {
				echo "<li>" . $type . " " . $name . "</li>";
			}
		}
		
		/**************************************************
		**************************************************/
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
					echo "<div class='row'><div class='cell nowrap'><ul class='treeCSS'>";
					echo "<li>" . $obj->type . " <b>" . $obj->name . "</b>";
					if (!empty($obj->children)) {
						echo "<ul>";
						foreach ($obj->children as $child) {
							echo "<li>" . $child->type . " " . $child->name . "</li>";
						}
						echo "</ul>";
					}
					echo "</li></ul></div>";
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
						echo "<div class='cell'>";
						if ($acls) {
							foreach ($acls as $acl) {
								echo $acl . "<br /><br />";
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
					echo "<div class='row'><div class='cell nowrap'><ul class='treeCSS'>";
					echo "<li>" . $group->type . " <b>" . $group->name . "</b>";
					if (!empty($group->children)) {
						echo "<ul>";
						foreach ($group->children as $child) {
							$this->showGroupChildWithChildren($child->name, $child->type);
						}					
						echo "</ul>";
					}
					echo "</li></ul></div>";
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
						echo "<div class='cell'>";
						if ($acls) {
							foreach ($acls as $acl) {
								echo $acl . "<br /><br />";
							}
						}
						echo "</div>";
					}
					echo "</div>";
				}
			}
			
			echo "</div></div>";
			echo "<script src='js/tree.js'></script></body>";
			
		}
		
		
		/**************************************************
		**************************************************/
		
	}

?>