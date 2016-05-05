<?php

	require_once("commoncontainer.class.php");

	class NetworkObject extends CommonContainer {
		
		const TYPE = "object network";
		
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		
		function asUnorderedList() {
			
			$result = "<ul class='treeCSS'>";
			$result .= "<li>" . $this->type . " <b>" . $this->name . "</b>";
			if (!empty($this->children)) {
				$result .= "<ul>";
				foreach ($this->children as $child) {
					$result .= "<li>" . $child->type . " " . $child->name . "</li>";
				}
				$result .= "</ul>";
			}
			$result .= "</li></ul>";
			
			return $result;
		}
		
	}

?>
