<?php

	require_once('utils.class.php');

	class NetworkObject {
		
		public $name;
		public $type;
		public $children = array();
		
		function NetworkObject($data) {
			
			$types = array('object network', 'object-group network', 'host', 'subnet', 'range', 'description', 'nat', 'network-object object', 'network-object', 'group-object');
			
			$data = trim($data);
			
			foreach ($types as $type) {
				if (Utils::startsWith($data, $type)) {
					$this->type = $type;
					$this->name = trim(str_replace($type, '', $data));
					break;
				}
			}
		}
		
	}

?>