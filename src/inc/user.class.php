<?php

	require_once("commoncontainer.class.php");

	class User extends CommonContainer {
		
		const TYPE = "user";
		const SUBSCOPE = "attributes";
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		
		function asUnorderedList() {
			
			$result = "<ul class='treeCSS'>";
			$result .= "<li>" . $this->type . " <b>" . $this->name . "</b>";
			if (!empty($this->children)) {
				$result .= "<ul><li>" . self::SUBSCOPE . "<ul>";
				foreach ($this->children as $child) {
					$result .= "<li>" . $child->type . " " . $child->name . "</li>";
				}
				$result .= "</ul></li></ul>";
			}
			$result .= "</li></ul>";
			
			return $result;
		}
		
	}

?>
