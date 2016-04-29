<?php

	require_once("commoncontainer.class.php");
	require_once("utils.class.php");

	class NetworkObject extends CommonContainer {
		
		const TYPE = "network object";
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		function showAsUnorderedList($as_group = false, $objects = array(), $groups = array()) {
			
			echo "<ul class='treeCSS'>";
			echo "<li>" . $this->type . " <b>" . $this->name . "</b>";
			if (!empty($this->children)) {
				echo "<ul>";
				foreach ($this->children as $child) {
					if ($as_group) {
						$this->showGroupChild($child->type, $child->name, $objects, $groups);
					} else {
						echo "<li>" . $child->type . " " . $child->name . "</li>";
					}
				}
				echo "</ul>";
			}
			echo "</li></ul>";
		}
		
		
		
		function showGroupChild($type, $name, $objects, $groups) {
			
			if ($type == "group-object") {
				foreach ($groups as $group) {
					if ($group->name == $name) {
						echo "<li>" . $group->type . " " . $group->name;
						if (!empty($group->children)) {
							echo "<ul>";
							foreach ($group->children as $child) {
								$this->showGroupChild($child->type, $child->name, $objects, $groups);
							}
							echo "</ul>";
						}
						echo "</li>";
					}
				}
			} else if ($type == "network-object object") {
				foreach ($objects as $obj) {
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
		
	}

?>
