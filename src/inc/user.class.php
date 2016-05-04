<?php

	require_once("commoncontainer.class.php");

	class User extends CommonContainer {
		
		const TYPE = "user";
		const SUBSCOPE = "attributes";
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		
		function showAsUnorderedList() {
			
			echo "<ul class='treeCSS'>";
			echo "<li>" . $this->type . " <b>" . $this->name . "</b>";
			if (!empty($this->children)) {
				echo "<ul><li>" . self::SUBSCOPE . "<ul>";
				foreach ($this->children as $child) {
					echo "<li>" . $child->type . " " . $child->name . "</li>";
				}
				echo "</ul></li></ul>";
			}
			echo "</li></ul>";
		}
		
	}

?>
