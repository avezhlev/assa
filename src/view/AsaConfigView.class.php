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
            $this->getNATRules() .
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

            $rules = $this->mentionedInNATRule($obj->name);
            $service_rules = $this->mentionedInPublicService($obj->name);
            $acls = $this->mentionedInACL($obj->name);

            if ($this->options[self::OPTION_HIGHLIGHT] && !($rules || $service_rules || $acls)) {
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
            if ($service_rules) {
                foreach ($service_rules as $rule) {
                    $result .= $rule->asString($obj->name) . "<br /><br />";
                }
            }
            $result .= "</div>";

            $result .= "<div class='cell nowrap'>";
            if ($acls) {
                foreach ($acls as $acl) {
                    $result .= $acl->asUnorderedList($obj->name);
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

            $rules = $this->mentionedInNATRule($group->name);
            $acls = $this->mentionedInACL($group->name);

            if ($this->options[self::OPTION_HIGHLIGHT] && !($rules || $acls)) {
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
            if ($acls) {
                foreach ($acls as $acl) {
                    $result .= $acl->asUnorderedList($group->name);
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

            $acls = $this->mentionedInACL(self::LOCAL_PREFIX . $user->name);

            if ($this->options[self::OPTION_HIGHLIGHT] && !$acls) {
                $result .= "<div class='row green'>";
            } else {
                $result .= "<div class='row'>";
            }

            $result .= "<div class='cell nowrap'>";
            $result .= $user->asUnorderedList();
            $result .= "</div>";

            $result .= "<div class='cell nowrap'>";
            if ($acls) {
                foreach ($acls as $acl) {
                    $result .= $acl->asUnorderedList(self::LOCAL_PREFIX . $user->name);
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

            $acls = $this->mentionedInACL($group->name);

            if ($this->options[self::OPTION_HIGHLIGHT] && !$acls) {
                $result .= "<div class='row green'>";
            } else {
                $result .= "<div class='row'>";
            }

            $result .= "<div class='cell nowrap'>";
            $result .= $group->asUnorderedList($this->users);
            $result .= "</div>";

            $result .= "<div class='cell nowrap'>";
            if ($acls) {
                foreach ($acls as $acl) {
                    $result .= $acl->asUnorderedList($group->name);
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


    function getNATRules() {

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

        foreach ($this->accessLists as $acl) {
            $result .= "<div class='row'><div class='cell'>";
            $result .= $acl->asUnorderedList();
            $result .= "</div></div>";
        }
        $result .= "</div></div>";

        return $result;
    }


    function mentionedInNATRule($name) {

        $results = array();

        foreach ($this->natRules as $rule) {
            foreach ($rule->sourceObjects as $obj) {
                if ($obj === $name) {
                    $results[] = $rule;
                    break 2;
                }
            }
            foreach ($rule->destinationObjects as $obj) {
                if ($obj === $name) {
                    $results[] = $rule;
                    break 2;
                }
            }
        }

        return empty($results) ? false : $results;

    }


    function mentionedInPublicService($name) {

        $results = array();

        foreach ($this->publicServices as $rule) {
            if ($rule->innerObject === $name || $rule->outerObject === $name) {
                $results[] = $rule;
                break;
            }
        }

        return empty($results) ? false : $results;

    }


    function mentionedInACL($name) {

        $results = array();

        foreach ($this->accessLists as $acl) {
            foreach ($acl->children as $child) {
                if (strpos($child->name . " ", " " . $name . " ") !== false) {
                    $results[] = $acl;
                    break;
                }
            }
        }

        return empty($results) ? false : $results;

    }
}