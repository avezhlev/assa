<?php

	class Utils {
		
		
		static function startsWith($haystack, $needle) {
			
			$length = strlen($needle);
			return (substr($haystack, 0, $length) === $needle);
		}
		
		static function addBoldTags($text, $toBold) {
			return preg_replace('@(' . preg_quote($toBold, '@') . ')@i', "<b>\\1</b>", $text);
		}
	}

?>