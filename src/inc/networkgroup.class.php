<?php

	require_once("commoncontainer.class.php");

	class NetworkGroup extends CommonContainer {
		
		const TYPE = "object-group network";
		
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		
		function asUnorderedList($objects, $groups) {
			
			$result = "<ul class='treeCSS'>";
			$result .= "<li>" . $this->type . " <b>" . $this->name . "</b>";
			if (!empty($this->children)) {
				$result .= "<ul>";
				foreach ($this->children as $child) {
					$result .= $this->asGroupChild($child->type, $child->name, $objects, $groups);
				}
				$result .= "</ul>";
			}
			$result .= "</li></ul>";
			
			return $result;
		}
		
		
		function asGroupChild($type, $name, $objects, $groups) {
			
			$result = "";
			if ($type === "group-object") {
				foreach ($groups as $group) {
					if ($group->name === $name) {
						$result .= "<li>" . $type . " " . $group->name;
						if (!empty($group->children)) {
							$result .= "<ul>";
							foreach ($group->children as $child) {
								$result .= $this->asGroupChild($child->type, $child->name, $objects, $groups);
							}
							$result .= "</ul>";
						}
						$result .= "</li>";
					}
				}
			} else if ($type === "network-object object") {
				foreach ($objects as $obj) {
					if ($obj->name === $name) {
						$result .= "<li>" . $type . " " . $obj->name;
						if (!empty($obj->children)) {
							$result .= "<ul>";
							foreach ($obj->children as $child) {
								$result .= "<li>" . $child->type . " " . $child->name . "</li>";
							}
							$result .= "</ul>";
						}
						$result .= "</li>";
					}
				}
			} else {
				$result .= "<li>" . $type . " " . $name . "</li>";
			}
			
			return $result;
		}
		
	}

?>
