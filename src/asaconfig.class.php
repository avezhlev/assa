<?php
	
	require_once("parsers/tokenparser.class.php");
	require_once("utils.class.php");

	class ASAConfig {
		
		private $filters = array();
		private $network_objects = array();
		private $network_groups = array();
		private $users = array();
		private $user_groups = array();
		private $access_lists = array();
		private $nat_rules = array();
		private $public_services = array();
			

		function __construct($uploaded_file, $filters) {
		
			$this->filters = $filters;
			
			$data = TokenParser::parse($uploaded_file);
			
			$this->network_objects = $data['objects'];
			$this->network_groups = $data['groups'];
			$this->users = $data['users'];
			$this->user_groups = $data['user-groups'];
			$this->access_lists = $data['acl'];
			$this->nat_rules = $data['nat'];
			$this->public_services = $data['ps'];
		}
		
		
		function showData() {
			
			echo $this->showHeader().
			
			$this->showTabs().
			
			$this->showNetworkObjects().
			$this->showNetworkGroups().
			$this->showUsers().
			$this->showUserGroups().
			$this->showNATRules().
			$this->showPublicServices().
			$this->showAccessLists().
			
			$this->showFooter();
		}
		
		
		function showHeader() {
			
			$result = "<head></head>";
			$result .= "<body><link href='css/styles.css' rel='stylesheet'>";
			$result .= "<div class='wrapper'>";
			
			return $result;
		}
		
		
		function showFooter() {
			
			$result = "</div>";
			$result .= "<script src='js/tree.js'></script>";
			$result .= "<script src='js/tabs.js'></script>";
			$result .= "</body>";
			
			return $result;
		}
		
		
		function showTabs() {
			
			$result = "<ul class='tab'>";
			$result .= "<li><a href='javascript:void(0)' class='tablinks active' onclick='showTab(event, \"objects\")'>Network objects</a></li>";
			$result .= "<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"groups\")'>Network groups</a></li>";
			$result .= "<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"users\")'>Users</a></li>";
			$result .= "<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"usergroups\")'>User groups</a></li>";
			$result .= "<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"natrules\")'>NAT rules</a></li>";
			$result .= "<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"publicservices\")'>Public services</a></li>";
			$result .= "<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"accesslists\")'>Access control lists</a></li>";
			$result .= "</ul><br />";
			
			return $result;
		}
		
		
		function showNetworkObjects() {
			
			$result = "<div id='objects' class='tabcontent' style='display: block;'>";
			$result .= "<div class='table'>";
			
			$result .= "<div class='row header blue'><div class='cell'>Object</div>";
			if ($this->filters['nat']) {
				$result .= "<div class='cell'>NAT or PS rule</div>";
			}
			if ($this->filters['acl']) {
				$result .= "<div class='cell'>ACL</div>";
			}
			$result .= "</div>";
			
			foreach ($this->network_objects as $obj) {
				
				$rules = $this->mentionedInNATRule($obj->name);
				$service_rules = $this->mentionedInPublicService($obj->name);
				$acls = $this->mentionedInACL($obj->name);
				
				if ($this->filters['empty'] || $this->filters['nat'] && $rules || $this->filters['nat'] && $service_rules || $this->filters['acl'] && $acls) {
					
					$result .= "<div class='row'><div class='cell nowrap'>";
					$result .= $obj->asUnorderedList();
					$result .= "</div>";
					
					if ($this->filters['nat']) {
						$result .= "<div class='cell'>";
						if ($rules) {
							foreach ($rules as $rule) {
								$result .= $rule->asString($obj->name) . "<br /><br />";
							}
						}
						if ($service_rules) {
							foreach ($service_rules as $rule) {
								$result .= $rule->asString($obj->name) . "<br /><br />";
							}
						}
						$result .= "</div>";
					}
					
					if ($this->filters['acl']) {
						$result .= "<div class='cell nowrap'>";
						if ($acls) {
							foreach ($acls as $acl) {
								$result .= $acl->asUnorderedList();
								$result .= "<br />";
							}
						}
						$result .= "</div>";
					}
					$result .= "</div>";
				}
			}
			$result .= "</div></div>";
			
			return $result;
		}
		
		
		function showNetworkGroups() {
			
			$result = "<div id='groups' class='tabcontent'>";
			$result .= "<div class='table'>";
			
			$result .= "<div class='row header blue'><div class='cell'>Group</div>";
			if ($this->filters['nat']) {
				$result .= "<div class='cell'>NAT rule</div>";
			}
			if ($this->filters['acl']) {
				$result .= "<div class='cell'>ACL</div>";
			}
			$result .= "</div>";
			
			foreach ($this->network_groups as $group) {
				
				$rules = $this->mentionedInNATRule($group->name);
				$acls = $this->mentionedInACL($group->name);
				
				if ($this->filters['empty'] || $this->filters['nat'] && $rules || $this->filters['acl'] && $acls) {
					
					$result .= "<div class='row'><div class='cell nowrap'>";
					$result .= $group->asUnorderedList($this->network_objects, $this->network_groups);
					$result .= "</div>";
					
					if ($this->filters['nat']) {
						$result .= "<div class='cell'>";
						if ($rules) {
							foreach ($rules as $rule) {
								$result .= $rule->asString($group->name) . "<br /><br />";
							}
						}
						$result .= "</div>";
					}
					
					if ($this->filters['acl']) {
						$result .= "<div class='cell nowrap'>";
						if ($acls) {
							foreach ($acls as $acl) {
								$result .= $acl->asUnorderedList();
								$result .= "<br />";
							}
						}
						$result .= "</div>";
					}
					$result .= "</div>";
				}
			}
			$result .= "</div></div>";
			
			return $result;
		}
		
		
		function showUsers() {
			
			$result = "<div id='users' class='tabcontent'>";
			$result .= "<div class='table'>";
			
			$result .= "<div class='row header green'><div class='cell'>User</div>";
			if ($this->filters['acl']) {
				$result .= "<div class='cell'>ACL</div>";
			}
			$result .= "</div>";
			
			foreach ($this->users as $user) {
				
				$acls = $this->mentionedInACL("LOCAL\\" . $user->name);
				
				if ($this->filters['empty'] || $this->filters['acl'] && $acls) {
					
					$result .= "<div class='row'><div class='cell nowrap'>";
					$result .= $user->asUnorderedList();
					$result .= "</div>";
					
					if ($this->filters['acl']) {
						$result .= "<div class='cell nowrap'>";
						if ($acls) {
							foreach ($acls as $acl) {
								$result .= $acl->asUnorderedList();
								$result .= "<br />";
							}
						}
						$result .= "</div>";
					}
					$result .= "</div>";
				}
			}
			$result .= "</div></div>";
			
			return $result;
		}
		
		
		function showUserGroups() {
			
			$result = "<div id='usergroups' class='tabcontent'>";
			$result .= "<div class='table'>";
			
			$result .= "<div class='row header green'><div class='cell'>Group</div>";
			if ($this->filters['acl']) {
				$result .= "<div class='cell'>ACL</div>";
			}
			$result .= "</div>";
			
			foreach ($this->user_groups as $group) {
				
				$acls = $this->mentionedInACL($group->name);
				
				if ($this->filters['empty'] || $this->filters['acl'] && $acls) {
					
					$result .= "<div class='row'><div class='cell nowrap'>";
					$result .= $group->asUnorderedList($this->users);
					$result .= "</div>";
					
					if ($this->filters['acl']) {
						$result .= "<div class='cell nowrap'>";
						if ($acls) {
							foreach ($acls as $acl) {
								$result .= $acl->asUnorderedList();
								$result .= "<br />";
							}
						}
						$result .= "</div>";
					}
					$result .= "</div>";
				}
			}
			$result .= "</div></div>";
			
			return $result;
		}
		
		
		function showNATRules() {
			
			$result = "<div id='natrules' class='tabcontent'>";
			$result .= "<div class='table'>";
			
			$result .= "<div class='row header red'><div class='cell'>Rule</div></div>";
			
			foreach ($this->nat_rules as $rule) {				
				$result .= "<div class='row'><div class='cell'>";
				$result .= $rule->asString();
				$result .= "</div></div>";
			}
			$result .= "</div></div>";
			
			return $result;
		}
		
		
		function showPublicServices() {
			
			$result = "<div id='publicservices' class='tabcontent'>";
			$result .= "<div class='table'>";
			
			$result .= "<div class='row header red'><div class='cell'>Rule</div></div>";
			
			foreach ($this->public_services as $service) {				
				$result .= "<div class='row'><div class='cell'>";
				$result .= $service->asString();
				$result .= "</div></div>";
			}
			$result .= "</div></div>";
			
			return $result;
		}
		
		
		function showAccessLists() {
			
			$result = "<div id='accesslists' class='tabcontent'>";
			$result .= "<div class='table'>";
			
			$result .= "<div class='row header red'><div class='cell'>ACL</div></div>";
			
			foreach ($this->access_lists as $acl) {				
				$result .= "<div class='row'><div class='cell'>";
				$result .= $acl->asUnorderedList();
				$result .= "</div></div>";
			}
			$result .= "</div></div>";
			
			return $result;
		}

		
		function mentionedInNATRule($name) {
			
			$results = array();
			
			foreach ($this->nat_rules as $rule) {
				foreach ($rule->source_objects as $obj) {
					if ($obj === $name) {
						$results[] = $rule;
						break 2;
					}
				}
				foreach ($rule->destination_objects as $obj) {
					if ($obj === $name) {
						$results[] = $rule;
						break 2;
					}
				}
			}
			
			return empty($results) ? false : $results;
			
		}
		
		
		function mentionedInPublicService($name) {
			
			$results = array();
			
			foreach ($this->public_services as $rule) {
				if ($rule->inner_object === $name || $rule->outer_object === $name) {
					$results[] = $rule;
					break;
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