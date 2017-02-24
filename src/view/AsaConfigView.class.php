<?php

class AsaConfigView {

    const LOCAL_PREFIX = "LOCAL\\";
    const OPTION_HIGHLIGHT = "highlight";

    private $options;

    private $networkObjects = array();
    private $networkGroups = array();
    private $users = array();
    private $userGroups = array();
    private $routes = array();
    private $natRules = array();
    private $publicServices = array();
    private $accessLists = array();

    public function __construct($options) {

        $this->options = $options;
    }

    function getHtml($data) {

        $this->networkObjects = $data[AsaConfigParser::NETWORK_OBJECTS];
        $this->networkGroups = $data[AsaConfigParser::NETWORK_GROUPS];
        $this->users = $data[AsaConfigParser::USERS];
        $this->userGroups = $data[AsaConfigParser::USER_GROUPS];
        $this->routes = $data[AsaConfigParser::ROUTES];
        $this->natRules = $data[AsaConfigParser::NAT_RULES];
        $this->publicServices = $data[AsaConfigParser::PUBLIC_SERVICES];
        $this->accessLists = $data[AsaConfigParser::ACCESS_LISTS];

        return
            $this->getHeader() .
            $this->getTabs() .
            $this->getNetworkObjects() .
            $this->getNetworkGroups() .
            $this->getUsers() .
            $this->getUserGroups() .
            $this->getRoutes() .
            $this->getNatRules() .
            $this->getPublicServices() .
            $this->getAccessLists() .
            $this->getFooter();
    }

    function getHeader() {

        $result = "<!DOCTYPE html><html lang='en'>";
        $result .= "<head><title>Cisco ASA config parser</title>";
        $result .= "<meta charset='UTF-8'>";
        $result .= "<link href='css/styles.css' rel='stylesheet'></head>";
        $result .= "<body>";

        return $result;
    }


    function getFooter() {

        $result = "</div>";
        $result .= "<script src='js/tree.js'></script>";
        $result .= "<script src='js/tabs.js'></script>";
        $result .= "</body>";

        return $result;
    }


    function getTabs() {

        $result = "<ul class='tab'>";
        $result .= "<li><a href='javascript:void(0)' class='tablinks active' onclick='showTab(event, \"objects\")'>Network objects</a></li>";
        $result .= "<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"groups\")'>Network groups</a></li>";
        $result .= "<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"users\")'>Users</a></li>";
        $result .= "<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"usergroups\")'>User groups</a></li>";
        $result .= "<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"routes\")'>Routes</a></li>";
        $result .= "<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"natrules\")'>NAT rules</a></li>";
        $result .= "<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"publicservices\")'>Public services</a></li>";
        $result .= "<li><a href='javascript:void(0)' class='tablinks' onclick='showTab(event, \"accesslists\")'>Access control lists</a></li>";
        $result .= "</ul><br />";
        $result .= "<div class='wrapper'>";

        return $result;
    }


    function getNetworkObjects() {

        $result = "<div id='objects' class='tabcontent' style='display: block;'>";
        $result .= "<div class='table'>";

        $result .= "<div class='row header blue'><div class='cell'>Object</div>";
        $result .= "<div class='cell'>NAT or PS rule</div>";
        $result .= "<div class='cell'>ACL</div>";
        $result .= "</div>";

        foreach ($this->networkObjects as $obj) {

            $rules = $this->mentionedInNatRules($obj->name);
            $services = $this->mentionedInPublicServices($obj->name);
            $lists = $this->mentionedInAccessLists($obj->name);

            if ($this->options[self::OPTION_HIGHLIGHT] && !($rules || $services || $lists)) {
                $result .= "<div class='row blue'>";
            } else {
                $result .= "<div class='row'>";
            }

            $result .= "<div class='cell nowrap'>";
            $result .= $obj->asUnorderedList();
            $result .= "</div>";

            $result .= "<div class='cell'>";
            if ($rules) {
                foreach ($rules as $rule) {
                    $result .= $rule->asString($obj->name) . "<br /><br />";
                }
            }
            if ($services) {
                foreach ($services as $service) {
                    $result .= $service->asString($obj->name) . "<br /><br />";
                }
            }
            $result .= "</div>";

            $result .= "<div class='cell nowrap'>";
            if ($lists) {
                foreach ($lists as $list) {
                    $result .= $list->asUnorderedList($obj->name);
                    $result .= "<br />";
                }
            }
            $result .= "</div>";

            $result .= "</div>";

        }
        $result .= "</div></div>";

        return $result;
    }


    function getNetworkGroups() {

        $result = "<div id='groups' class='tabcontent'>";
        $result .= "<div class='table'>";

        $result .= "<div class='row header blue'><div class='cell'>Group</div>";
        $result .= "<div class='cell'>NAT rule</div>";

        $result .= "<div class='cell'>ACL</div>";
        $result .= "</div>";

        foreach ($this->networkGroups as $group) {

            $rules = $this->mentionedInNatRules($group->name);
            $lists = $this->mentionedInAccessLists($group->name);

            if ($this->options[self::OPTION_HIGHLIGHT] && !($rules || $lists)) {
                $result .= "<div class='row blue'>";
            } else {
                $result .= "<div class='row'>";
            }

            $result .= "<div class='cell nowrap'>";
            $result .= $group->asUnorderedList($this->networkObjects, $this->networkGroups);
            $result .= "</div>";

            $result .= "<div class='cell'>";
            if ($rules) {
                foreach ($rules as $rule) {
                    $result .= $rule->asString($group->name) . "<br /><br />";
                }
            }
            $result .= "</div>";

            $result .= "<div class='cell nowrap'>";
            if ($lists) {
                foreach ($lists as $list) {
                    $result .= $list->asUnorderedList($group->name);
                    $result .= "<br />";
                }
            }
            $result .= "</div>";

            $result .= "</div>";

        }
        $result .= "</div></div>";

        return $result;
    }


    function getUsers() {

        $result = "<div id='users' class='tabcontent'>";
        $result .= "<div class='table'>";

        $result .= "<div class='row header green'><div class='cell'>User</div>";
        $result .= "<div class='cell'>ACL</div>";
        $result .= "</div>";

        foreach ($this->users as $user) {

            $lists = $this->mentionedInAccessLists(self::LOCAL_PREFIX . $user->name);

            if ($this->options[self::OPTION_HIGHLIGHT] && !$lists) {
                $result .= "<div class='row green'>";
            } else {
                $result .= "<div class='row'>";
            }

            $result .= "<div class='cell nowrap'>";
            $result .= $user->asUnorderedList();
            $result .= "</div>";

            $result .= "<div class='cell nowrap'>";
            if ($lists) {
                foreach ($lists as $list) {
                    $result .= $list->asUnorderedList(self::LOCAL_PREFIX . $user->name);
                    $result .= "<br />";
                }
            }
            $result .= "</div>";

            $result .= "</div>";
        }
        $result .= "</div></div>";

        return $result;
    }


    function getUserGroups() {

        $result = "<div id='usergroups' class='tabcontent'>";
        $result .= "<div class='table'>";

        $result .= "<div class='row header green'><div class='cell'>Group</div>";
        $result .= "<div class='cell'>ACL</div>";
        $result .= "</div>";

        foreach ($this->userGroups as $group) {

            $lists = $this->mentionedInAccessLists($group->name);

            if ($this->options[self::OPTION_HIGHLIGHT] && !$lists) {
                $result .= "<div class='row green'>";
            } else {
                $result .= "<div class='row'>";
            }

            $result .= "<div class='cell nowrap'>";
            $result .= $group->asUnorderedList($this->users);
            $result .= "</div>";

            $result .= "<div class='cell nowrap'>";
            if ($lists) {
                foreach ($lists as $list) {
                    $result .= $list->asUnorderedList($group->name);
                    $result .= "<br />";
                }
            }
            $result .= "</div>";

            $result .= "</div>";

        }
        $result .= "</div></div>";

        return $result;
    }


    function getRoutes() {

        $result = "<div id='routes' class='tabcontent'>";
        $result .= "<div class='table'>";

        $result .= "<div class='row header red'><div class='cell'>Route</div></div>";

        foreach ($this->routes as $route) {
            $result .= "<div class='row'><div class='cell'>";
            $result .= $route->asString();
            $result .= "</div></div>";
        }
        $result .= "</div></div>";

        return $result;
    }


    function getNatRules() {

        $result = "<div id='natrules' class='tabcontent'>";
        $result .= "<div class='table'>";

        $result .= "<div class='row header red'><div class='cell'>Rule</div></div>";

        foreach ($this->natRules as $rule) {
            $result .= "<div class='row'><div class='cell'>";
            $result .= $rule->asString();
            $result .= "</div></div>";
        }
        $result .= "</div></div>";

        return $result;
    }


    function getPublicServices() {

        $result = "<div id='publicservices' class='tabcontent'>";
        $result .= "<div class='table'>";

        $result .= "<div class='row header red'><div class='cell'>Rule</div></div>";

        foreach ($this->publicServices as $service) {
            $result .= "<div class='row'><div class='cell'>";
            $result .= $service->asString();
            $result .= "</div></div>";
        }
        $result .= "</div></div>";

        return $result;
    }


    function getAccessLists() {

        $result = "<div id='accesslists' class='tabcontent'>";
        $result .= "<div class='table'>";

        $result .= "<div class='row header red'><div class='cell'>ACL</div></div>";

        foreach ($this->accessLists as $list) {
            $result .= "<div class='row'><div class='cell'>";
            $result .= $list->asUnorderedList();
            $result .= "</div></div>";
        }
        $result .= "</div></div>";

        return $result;
    }


    function mentionedInNatRules($name) {

        $results = array();

        foreach ($this->natRules as $rule) {
            foreach ($rule->sourceObjects as $object) {
                if ($object === $name) {
                    $results[] = $rule;
                    break 2;
                }
            }
            foreach ($rule->destinationObjects as $object) {
                if ($object === $name) {
                    $results[] = $rule;
                    break 2;
                }
            }
        }

        return empty($results) ? false : $results;

    }


    function mentionedInPublicServices($name) {

        $results = array();

        foreach ($this->publicServices as $service) {
            if ($service->innerObject === $name || $service->outerObject === $name) {
                $results[] = $service;
                break;
            }
        }

        return empty($results) ? false : $results;

    }


    function mentionedInAccessLists($name) {

        $results = array();

        foreach ($this->accessLists as $list) {
            foreach ($list->children as $child) {
                if (strpos($child->name . " ", " " . $name . " ") !== false) {
                    $results[] = $list;
                    break;
                }
            }
        }

        return empty($results) ? false : $results;

    }
}