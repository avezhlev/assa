<?php

	class FileTokenizer {
		
		private static $instance = NULL;

		private static $tokens;
		private static $count;
		private static $index;
		
		const BOF_MARK = "BOF";
		const EOF_MARK = "EOF";
		const EOL_MARK = "EOL";

		public static function getInstance($file_path = "") {
			
			if (self::$instance === NULL) {
				self::$instance = new self($file_path);
			}
			return self::$instance;
		}
		
		
		private function __clone() {}
		
		
		private function __construct($file_path) {

			self::$tokens = preg_split("~\s+~", preg_replace("~[\n\r]+~", " " . self::EOL_MARK . " ", file_get_contents($file_path)));
			self::$count = count(self::$tokens);
			self::$index = -1;
		}
		
		public function nextToken() {
			if (self::$index < self::$count-1) {
				return (self::$tokens[++self::$index]);
			} else {
				return self::EOF_MARK;
			}
		}
		
		public function previousToken() {
			if (self::$index > 0) {
				return (self::$tokens[--self::$index]);
			} else {
				self::$index = -1;
				return self::BOF_MARK;
			}
		}
		
		public function getToken() {
			return (self::$tokens[self::$index]);
		}
		
		public function nextLineStarter() {
			if (self::$index === -1 || $this->getToken() === self::EOL_MARK) {
				return $this->nextToken();
			} else {
				while (($token = $this->nextToken()) !== self::EOF_MARK) {
					if ($token === self::EOL_MARK) {
						return $this->nextToken();
					}
				}
				return self::EOF_MARK;
			}
		}
		
	}
	
?>
