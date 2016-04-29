<?php
	
	require_once("naiveparser.class.php");
	require_once("networkobject.class.php");
	require_once("accesslist.class.php");
	require_once("utils.class.php");

	class ASAConfig {
		
		private $filters = array();
		private $network_objects = array();
		private $network_groups = array();
		private $access_lists = array();
		private $nat_rules = array();
			

		function __construct($uploaded_file, $filters) {
		
			$this->filters = $filters;
			
			$data = NaiveParser::parse($uploaded_file);
			$this->network_objects = $data['objects'];
			$this->network_groups = $data['groups'];
			$this->access_lists = $data['acl'];
			$this->nat_rules = $data['nat'];
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
					if (strpos($child->name . " ", " " . $name . " ") !== false) {
						$results[] = $acl;
						break;
					}
				}
			}
			
			return empty($results) ? false : $results;
			
		}
		
		
	}

?>