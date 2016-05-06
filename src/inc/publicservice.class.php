<?php

	require_once("commonobject.class.php");
	require_once("networkobject.class.php");

	class PublicService extends CommonObject {
		
		const TYPE = "nat";
		
		public $service_type;
		public $outer_object;
		public $inner_object;
		public $options = array();
		
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		
		function asString($highlight_object = "") {
			
			$highlight_all = empty($highlight_object);
			
			$result = self::TYPE . " " . $this->name . " " . $this->service_type . " ";
			
			if ($highlight_all || ($this->inner_object === $highlight_object)) {
				$result .= "<b>" . $this->inner_object . "</b> ";
			} else {
				$result .= $this->inner_object . " ";
			}
			
			foreach ($this->options as $option) {
				$result .= $option . " ";
			}
			
			$result .= "<i>(" . NetworkObject::TYPE . " ";
			if ($highlight_all || ($this->outer_object === $highlight_object)) {
				$result .= "<b>" . $this->outer_object . "</b>";
			} else {
				$result .= $this->outer_object;
			}
			$result .= ")</i>";
			
			return $result;
		}
		
	}

?>
