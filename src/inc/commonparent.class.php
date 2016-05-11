<?php

	require_once("commoncontainer.class.php");

	class CommonParent extends CommonContainer{
		
		const TYPE = "common-parent";
		
		public $children = array();
		
		function addChild($child) {
			$this->children[] = $child;
		}
	
	}
	
?>
