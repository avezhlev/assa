<?php

	class FileTokenizer {
		
		private static $instance = NULL;

		private static $tokens;
		private static $count;
		private static $index;

		public static function getInstance($file_path = "") {
			
			if (self::$instance === NULL) {
				self::$instance = new self($file_path);
			}
			return self::$instance;
		}
		
		
		private function __clone() {}
		
		
		private function __construct($file_path) {

			self::$tokens = preg_split("/[\s]+/", file_get_contents($file_path));
			self::$count = count(self::$tokens);
			self::$index = -1;
		}
		
		public function nextToken() {
			if (self::$index < self::$count-1) {
				return (self::$tokens[++self::$index]);
			} else {
				return "EOF";
			}
		}
		
		public function previousToken() {
			if (self::$index > 0) {
				return (self::$tokens[--self::$index]);
			} else {
				return "BOF";
			}
		}
		
	}
	
?>