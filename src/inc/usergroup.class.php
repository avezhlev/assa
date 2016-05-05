<?php

	require_once("commoncontainer.class.php");
	require_once("user.class.php");

	class UserGroup extends CommonContainer {
		
		const TYPE = "object-group user";
		
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		
		function asUnorderedList($users) {
			
			$result = "<ul class='treeCSS'>";
			$result .= "<li>" . $this->type . " <b>" . $this->name . "</b>";
			if (!empty($this->children)) {
				$result .= "<ul>";
				foreach ($this->children as $child) {
					foreach ($users as $user) {
						if ($user->name === $child->name) {
							$result .= "<li>" . User::TYPE . " " . $child->type . "\\" . $child->name;
							if (!empty($user->children)) {
								$result .= "<ul>";
								foreach ($user->children as $child) {
									$result .= "<li>" . $child->type . " " . $child->name . "</li>";
								}
								$result .= "</ul>";
							}
							$result .= "</li>";
						}
					}
				}
				$result .= "</ul>";
			}
			$result .= "</li></ul>";
			
			return $result;
		}
		
	}

?>
