<?php

	require_once("commoncontainer.class.php");

	class AccessList extends CommonContainer {
		
		const TYPE = "access-list";
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		function asUnorderedList() {
			
			$result = "<ul class='treeCSS'>";
			$result .= "<li>" . $this->name . "<ul>";
			$result .= "<li>" . $this->type;
			if (!empty($this->children)) {
				$result .= "<ul>";
				foreach ($this->children as $child) {
					$result .= "<li>" . $child->type . " " . $child->name . "</li>";
				}
				$result .= "</ul>";
			}
			$result .= "</li></ul></li></ul>";
			
			return $result;
		}
		
	}

?>
