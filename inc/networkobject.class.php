<?php

	require_once("commoncontainer.class.php");
	require_once("utils.class.php");

	class NetworkObject extends CommonContainer {
		
		const TYPE = "object network";
		
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		
		function showAsUnorderedList() {
			
			echo "<ul class='treeCSS'>";
			echo "<li>" . $this->type . " <b>" . $this->name . "</b>";
			if (!empty($this->children)) {
				echo "<ul>";
				foreach ($this->children as $child) {
					echo "<li>" . $child->type . " " . $child->name . "</li>";
				}
				echo "</ul>";
			}
			echo "</li></ul>";
		}
		
	}

?>
