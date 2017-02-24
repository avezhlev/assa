<?php

require_once(__DIR__."/../../tokenizer/FileTokenizer.class.php");
require_once(__DIR__ . "/../../entity/service/Route.class.php");

class RouteParser {

    const SCOPE = "route";

    static function parse($ipVersion = 4) {

        $tokenizer = FileTokenizer::getInstance();

        $route = new Route($tokenizer->nextToken());

        $route->ipVersion = $ipVersion;

        if ($ipVersion === 6) {
            $data = preg_split("~/~", $tokenizer->nextToken());
            $route->subnet = $data[0];
            $route->mask = $data[1];
        } else {
            $route->subnet = $tokenizer->nextToken();
            $route->mask = $tokenizer->nextToken();
        }
        $route->nextHop = $tokenizer->nextToken();
        $route->metric = $tokenizer->nextToken();

        return $route;

    }

}

?>
