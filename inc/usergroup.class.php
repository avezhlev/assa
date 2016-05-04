<?php

	require_once("commoncontainer.class.php");
	require_once("user.class.php");
	require_once("utils.class.php");

	class UserGroup extends CommonContainer {
		
		const TYPE = "object-group user";
		
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		
		function showAsUnorderedList($users) {
			
			echo "<ul class='treeCSS'>";
			echo "<li>" . $this->type . " <b>" . $this->name . "</b>";
			if (!empty($this->children)) {
				echo "<ul>";
				foreach ($this->children as $child) {
					foreach ($users as $user) {
						if ($user->name == $child->name) {
							echo "<li>" . $child->type . "\\" . $child->name;
							if (!empty($user->children)) {
								echo "<ul>";
								foreach ($user->children as $child) {
									echo "<li>" . $child->type . " " . $child->name . "</li>";
								}
								echo "</ul>";
							}
							echo "</li>";
						}
					}
				}
				echo "</ul>";
			}
			echo "</li></ul>";
		}
		
	}

?>
