<?php

	require_once("commoncontainer.class.php");

	class AccessList extends CommonContainer {
		
		const TYPE = "access-list";
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		function showAsUnorderedList() {
			
			echo "<ul class='treeCSS'>";
			echo "<li>" . $this->name . "<ul>";
			echo "<li>" . $this->type;
			if (!empty($this->children)) {
				echo "<ul>";
				foreach ($this->children as $child) {
					echo "<li>" . $child->type . " " . $child->name . "</li>";
				}
				echo "</ul>";
			}
			echo "</li></ul></li></ul>";
		}
		
	}

?>
