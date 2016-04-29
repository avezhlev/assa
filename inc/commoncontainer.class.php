<?php

	require_once("commonobject.class.php");

	class CommonContainer extends CommonObject{
		
		const TYPE = "common-container";
		
		public $children = array();
		
		function addChild($child) {
			$this->children[] = $child;
		}
	
	}
	
?>
