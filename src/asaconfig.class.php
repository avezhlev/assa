<?php
	
	require_once("parsers/naiveparser.class.php");
	require_once("inc/networkobject.class.php");
	require_once("inc/networkgroup.class.php");
	require_once("inc/user.class.php");
	require_once("inc/accesslist.class.php");
	require_once("utils.class.php");

	class ASAConfig {
		
		private $filters = array();
		private $network_objects = array();
		private $network_groups = array();
		private $users = array();
		private $user_groups = array();
		private $access_lists = array();
		private $nat_rules = array();
			

		function __construct($uploaded_file, $filters) {
		
			$this->filters = $filters;
			
			$data = NaiveParser::parse($uploaded_file);
			$this->network_objects = $data['objects'];
			$this->network_groups = $data['groups'];
			$this->users = $data['users'];
			$this->user_groups = $data['user-groups'];
			$this->access_lists = $data['acl'];
			$this->nat_rules = $data['nat'];
		}
		
		
		function showData() {
			
			$this->showHeader();
			
			$this->showTabs();
			
			$this->showNetworkObjects();
			$this->showNetworkGroups();
			$this->showUsers();
			$this->showUserGroups();
			$this->showNATRules();
			$this->showAccessLists();
			
			$this->showFooter();
		}
		
		
		function showHeader() {
			echo "<head></head>";
			echo "<body><link href='css/styles.css' rel='stylesheet'>";
			echo "<div class='wrapper'>";
		}
		
		
		function showFooter() {
			echo "</div>";
			echo "<script src='js/tree.js'></script>";
			echo "<script src='js/tabs.js'></script>";
			echo "</body>";
		}
		
		
		function showTabs() {
			echo "<ul class='tab'>
				<li><a href='javascript:void(0)' class='tablinks active' onclick='showTab(event, \"objects\")'>Network objects</a></li>
				<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"groups\")'>Network groups</a></li>
				<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"users\")'>Users</a></li>
				<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"usergroups\")'>User groups</a></li>
				<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"natrules\")'>NAT rules</a></li>
				<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"accesslists\")'>Access control lists</a></li>
				</ul><br />";
		}
		
		
		function showNetworkObjects() {
			
			echo "<div id='objects' class='tabcontent' style='display: block;'>";
			echo "<div class='table'>";
			
			echo "<div class='row header blue'><div class='cell'>Object</div>";
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
			echo "</div></div>";
		}
		
		function showNetworkGroups() {
			
			echo "<div id='groups' class='tabcontent'>";
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
					$group->showAsUnorderedList($this->network_objects, $this->network_groups);
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
		}
		
		
		function showUsers() {
			
			echo "<div id='users' class='tabcontent'>";
			echo "<div class='table'>";
			
			echo "<div class='row header green'><div class='cell'>User</div>";
			if ($this->filters['acl']) {
				echo "<div class='cell'>ACL</div>";
			}
			echo "</div>";
			
			foreach ($this->users as $user) {
				
				$acls = $this->mentionedInACL("LOCAL\\" . $user->name);
				
				if ($this->filters['empty'] || $this->filters['acl'] && $acls) {
					
					echo "<div class='row'><div class='cell nowrap'>";
					$user->showAsUnorderedList();
					echo "</div>";
					
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
		}
		
		
		function showUserGroups() {
			
			echo "<div id='usergroups' class='tabcontent'>";
			echo "<div class='table'>";
			
			echo "<div class='row header green'><div class='cell'>Group</div>";
			if ($this->filters['acl']) {
				echo "<div class='cell'>ACL</div>";
			}
			echo "</div>";
			
			foreach ($this->user_groups as $group) {
				
				$acls = $this->mentionedInACL($group->name);
				
				if ($this->filters['empty'] || $this->filters['acl'] && $acls) {
					
					echo "<div class='row'><div class='cell nowrap'>";
					$group->showAsUnorderedList($this->users);
					echo "</div>";
					
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
		}
		
		
		function showNATRules() {
			
			echo "<div id='natrules' class='tabcontent'>";
			echo "<div class='table'>";
			
			echo "<div class='row header red'><div class='cell'>Rule</div></div>";
			
			foreach ($this->nat_rules as $rule) {				
				echo "<div class='row'><div class='cell'>";
				echo $rule->out();
				echo "</div></div>";
			}
			echo "</div></div>";
		}
		
		
		function showAccessLists() {
			
			echo "<div id='accesslists' class='tabcontent'>";
			echo "<div class='table'>";
			
			echo "<div class='row header red'><div class='cell'>ACL</div></div>";
			
			foreach ($this->access_lists as $acl) {				
				echo "<div class='row'><div class='cell'>";
				$acl->showAsUnorderedList();
				echo "</div></div>";
			}
			echo "</div></div>";
		}

		
		function mentionedInNATRule($name) {
			
			$results = array();
			/*
			foreach ($this->nat_rules as $rule) {
				if (strpos($rule . " ", " " . $name . " ") !== false) {
					$results[] = Utils::addBoldTags($rule, " " . $name);
				}
			}
			*/
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