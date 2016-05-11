<?php

	require_once("filetokenizer.class.php");
	require_once(__DIR__ . "/../inc/route.class.php");

	class RouteParser {
		
		const SCOPE = "route";
		
		static function parse() {
				
			$tokenizer = FileTokenizer::getInstance();
				
			$route = new Route($tokenizer->nextToken());
			$route->subnet = $tokenizer->nextToken();
			$route->mask = $tokenizer->nextToken();
			$route->next_hop = $tokenizer->nextToken();
			$route->metric = $tokenizer->nextToken();
			
			return $route;
			
		}
	
	}
	
?>
