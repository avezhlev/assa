<?php

	require_once("commonobject.class.php");

	class NatRule extends CommonObject {
		
		const TYPE = "nat";
		const SOURCE_MARK = "source";
		const DESTINATION_MARK = "destination";
		const SERVICE_MARK = "service";
		const DESCRIPTION_MARK = "description";
		
		public $source_type;
		public $destination_type;
		public $source_objects = array();
		public $destination_objects = array();
		public $service_objects = array();
		public $options = array();
		public $decription;
		
		
		function __construct($name, $type = self::TYPE) {
			parent::__construct($name, $type);
		}
		
		
		function show($highlight_object = "") {
			
			$highlight_all = empty($highlight_object);
			
			$result = self::TYPE . " " . $this->name . " ";
			
			if (count($this->source_objects)) {
				$result .= self::SOURCE_MARK . " " . $this->source_type . " ";
				foreach ($this->source_objects as $obj) {
					if ($highlight_all || ($obj === $highlight_object)) {
						$result .= "<b>" . $obj . "</b>" . " ";
					} else {
						$result .= $obj . " ";
					}
				}
			}
			
			if (count($this->destination_objects)) {
				$result .= self::DESTINATION_MARK . " " . $this->destination_type . " ";
				foreach ($this->destination_objects as $obj) {
					if ($highlight_all || ($obj === $highlight_object)) {
						$result .= "<b>" . $obj . "</b>" . " ";
					} else {
						$result .= $obj . " ";
					}
				}
			}
			
			if (count($this->service_objects)) {
				$result .= self::SERVICE_MARK . " ";
				foreach ($this->service_objects as $obj) {
					if ($highlight_all || ($obj === $highlight_object)) {
						$result .= "<b>" . $obj . "</b>" . " ";
					} else {
						$result .= $obj . " ";
					}
				}
			}
			
			foreach ($this->options as $option) {
				$result .= $option . " ";
			}
			
			if ($this->description !== "") {
				$result .= "<i>" . self::DESCRIPTION_MARK . " " . $this->description . "</i>";
			}
			
			return $result;
		}
		
	}

?>
