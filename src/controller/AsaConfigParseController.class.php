<?php

require_once(__DIR__ . "/../parser/AsaConfigParser.class.php");
require_once(__DIR__ . "/../view/AsaConfigView.class.php");
require_once(__DIR__ . "/../util/Utils.class.php");

class AsaConfigParseController {

    const UPLOADS_DIR = __DIR__ . "/../../uploads/";
    const FILE_TAG = "userfile";

    const ERROR_NOT_VALID_FILE_FORMAT = "Not valid file format";
    const ERROR_CHECK_PERMISSIONS = "Check permissions for uploads directory";

    function __construct() {

        $uploadedFile = self::UPLOADS_DIR . basename($_FILES[self::FILE_TAG]['name']);

        if (move_uploaded_file($_FILES[self::FILE_TAG]["tmp_name"], $uploadedFile)) {

            $data = AsaConfigParser::parse($uploadedFile);

            if (!$this->isEmpty($data)) {
                $options = array();
                $options[AsaConfigView::OPTION_HIGHLIGHT] = isset($_POST[AsaConfigView::OPTION_HIGHLIGHT]);
                $view = new AsaConfigView($options);
                echo $view->getHtml($data);
                return;
            } else {
                $error = self::ERROR_NOT_VALID_FILE_FORMAT;
            }
        } else {
            $error = self::ERROR_CHECK_PERMISSIONS;
        }

        include(__DIR__ . "/../view/ErrorView.php");
    }

    function isEmpty($data) {
        foreach ($data as $entry) {
            if (!empty($entry)) {
                return false;
            }
        }
        return true;
    }

}
?>
