<?php

	require_once("commoncontainer.class.php");

	class Route extends CommonContainer {
		
		const TYPE = "route";
		
		public $subnet;
		public $mask;
		public $next_hop;
		public $metric;
		
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		
		function asString() {
						
			$result = self::TYPE . " " . $this->name . " <b>" . $this->subnet . " " . $this->mask . " " . $this->next_hop . "</b> " . $this->metric;
			
			return $result;
		}
		
	}

?>
