<?php

	require_once("commonparent.class.php");
	require_once(__DIR__ . "/../utils.class.php");

	class AccessList extends CommonParent {
		
		const TYPE = "access-list";
		const REMARK_MARK = "remark";
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		function asUnorderedList($highlight_object = "") {
			
			$result = "<ul class='treeCSS'>";
			$result .= "<li>" . $this->name . "<ul>";
			$result .= "<li>" . $this->type;
			if (!empty($this->children)) {
				$result .= "<ul>";
				foreach ($this->children as $child) {
					$result .= "<li>" .
					($child->type === self::REMARK_MARK ? "<i>" : "") .
					$child->type . " " .
					(empty($highlight_object) ? $child->name : Utils::addBoldTags($child->name, $highlight_object)) .
					($child->type === self::REMARK_MARK ? "</i>" : "") .
					"</li>";
				}
				$result .= "</ul>";
			}
			$result .= "</li></ul></li></ul>";
			
			return $result;
		}
		
	}

?>
