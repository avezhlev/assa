<?php

	require_once("commoncontainer.class.php");

	class AccessList extends CommonContainer {
		
		function __construct($data) {
			
			$data = trim($data);
			$used_data = trim(str_replace(_ACL_, "", $data));
			$this->name = strstr($used_data, " ", true);
			$used_data = trim(str_replace($this->name, "", $used_data));
			
			$this->setACLType($used_data);
			$this->addChild($data);
		}
		
		function setACLType($data) {
			
			$types = array("standard", "extended");
			
			foreach ($types as $type) {
				if (Utils::startsWith($data, $type)) {
					$this->type = $type;
					return true;
				}
			}
			
			return false;
		}
		
		function addChild($data) {
			
			$used_data = trim(str_replace(_ACL_ . " " . $this->name, "", $data));
			
			if ($this->setACLType($used_data)) {
				$this->children[] = trim(str_replace($this->type, "", $used_data));
			} else {
				$this->children[] = $used_data;
			}
			
		}
		
		function showAsUnorderedList() {
			
			echo "<ul class='treeCSS'>";
			echo "<li>" . $this->name . "<ul>";
			echo "<li>" . $this->type;
			if (!empty($this->children)) {
				echo "<ul>";
				foreach ($this->children as $child) {
					echo "<li>" . $child . "</li>";
				}
				echo "</ul>";
			}
			echo "</li></ul></li></ul>";
		}
		
	}

?>