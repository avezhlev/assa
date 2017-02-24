<?php

require_once(__DIR__ . "/../tokenizer/FileTokenizer.class.php");
require_once("entity/ObjectParser.class.php");
require_once("entity/ObjectGroupParser.class.php");
require_once("entity/UserParser.class.php");
require_once("entity/AccessListParser.class.php");
require_once("entity/NatRuleParser.class.php");
require_once("entity/PublicServiceParser.class.php");
require_once("entity/RouteParser.class.php");
require_once("entity/IPv6Parser.class.php");

class AsaConfigParser {

    const NETWORK_OBJECTS = 0;
    const NETWORK_GROUPS = 1;
    const USERS = 2;
    const USER_GROUPS = 3;
    const ROUTES = 4;
    const NAT_RULES = 5;
    const PUBLIC_SERVICES = 6;
    const ACCESS_LISTS = 7;

    static function parse($uploaded_file) {

        $networkObjects = array();
        $networkGroups = array();
        $users = array();
        $userGroups = array();
        $routes = array();
        $natRules = array();
        $publicServices = array();
        $accessLists = array();

        $tokenizer = FileTokenizer::getInstance($uploaded_file);

        while (($token = $tokenizer->nextLineStarter()) !== FileTokenizer::EOF_MARK) {

            switch ($token) {

                case ObjectParser::SCOPE:
                    if ($data = ObjectParser::parse()) {
                        switch (true) {

                            case $data instanceof NetworkObject:
                                $networkObjects[] = $data;
                                break;

                            case $data instanceof PublicService:
                                $publicServices[] = $data;
                                break;
                        }
                    }
                    break;

                case ObjectGroupParser::SCOPE:
                    if ($data = ObjectGroupParser::parse()) {
                        switch (true) {

                            case $data instanceof NetworkGroup:
                                $networkGroups[] = $data;
                                break;

                            case $data instanceof UserGroup:
                                $userGroups[] = $data;
                                break;
                        }
                    }
                    break;

                case UserParser::SCOPE:
                    if ($data = UserParser::parse()) {
                        $users[] = $data;
                    }
                    break;

                case AccessListParser::SCOPE:
                    if ($data = AccessListParser::parse()) {
                        $accessLists[] = $data;
                    }
                    break;

                case NatRuleParser::SCOPE:
                    if ($data = NatRuleParser::parse()) {
                        $natRules[] = $data;
                    }
                    break;

                case RouteParser::SCOPE:
                    if ($data = RouteParser::parse()) {
                        $routes[] = $data;
                    }
                    break;

                case IPv6Parser::SCOPE:
                    if ($data = IPv6Parser::parse()) {
                        switch (true) {

                            case $data instanceof Route:
                                $routes[] = $data;
                                break;
                        }
                    }
                    break;
            }

        }

        return array(
            self::NETWORK_OBJECTS => $networkObjects,
            self::NETWORK_GROUPS => $networkGroups,
            self::USERS => $users,
            self::USER_GROUPS => $userGroups,
            self::ROUTES => $routes,
            self::NAT_RULES => $natRules,
            self::PUBLIC_SERVICES => $publicServices,
            self::ACCESS_LISTS => $accessLists
        );

    }
}

?>
