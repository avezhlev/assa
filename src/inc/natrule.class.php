<?php

	require_once("commoncontainer.class.php");

	class NatRule extends CommonContainer {
		
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
		
		
		function asString($highlight_object = "") {
			
			$highlight_all = empty($highlight_object);
			
			$result = self::TYPE . " " . $this->name . " ";
			
			if (count($this->source_objects)) {
				$result .= self::SOURCE_MARK . " " . $this->source_type . " ";
				$is_first = true;
				foreach ($this->source_objects as $obj) {
					if ($highlight_all || ($obj === $highlight_object)) {
						$result .= "<b>" . $obj . "</b>" . " ";
					} else {
						$result .= $obj . " ";
					}
					if ($is_first) {
						$result .= " --> ";
						$is_first = false;
					}
				}
			}
			
			if (count($this->destination_objects)) {
				$result .= self::DESTINATION_MARK . " " . $this->destination_type . " ";
				$is_first = true;
				foreach ($this->destination_objects as $obj) {
					if ($highlight_all || ($obj === $highlight_object)) {
						$result .= "<b>" . $obj . "</b>" . " ";
					} else {
						$result .= $obj . " ";
					}
					if ($is_first) {
						$result .= " --> ";
						$is_first = false;
					}
				}
			}
			
			if (count($this->service_objects)) {
				$result .= self::SERVICE_MARK . " ";
				$is_first = true;
				foreach ($this->service_objects as $obj) {
					if ($highlight_all || ($obj === $highlight_object)) {
						$result .= "<b>" . $obj . "</b>" . " ";
					} else {
						$result .= $obj . " ";
					}
					if ($is_first) {
						$result .= " --> ";
						$is_first = false;
					}
				}
			}
			
			foreach ($this->options as $option) {
				$result .= $option . " ";
			}
			
			if (!empty($this->description)) {
				$result .= "<i>" . self::DESCRIPTION_MARK . " " . $this->description . "</i>";
			}
			
			return $result;
		}
		
	}

?>
