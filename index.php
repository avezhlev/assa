<?php
require_once("src/controller/AsaConfigParseController.class.php");
require_once("src/view/AsaConfigView.class.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cisco ASA config parser</title>
</head>
<body>
<link href='css/styles.css' rel='stylesheet'>
<div class='box'>
    <br/>
    <h1>ASA config parser</h1>
    <br/>
    <form enctype='multipart/form-data' action='do.php' method='POST'>

        <input type='hidden' name='MAX_FILE_SIZE' value='200000'/>
        <input name='<?php echo AsaConfigParseController::FILE_TAG ?>' type='file'/><br/>

        <div class='wrapper small'>
            <div class='table'>
                <div class='row header small blue'>
                    <div class='cell small'>
                        <input type='checkbox' id='chk' name='<?php echo AsaConfigView::OPTION_HIGHLIGHT ?>'>
                        <label for='chk'>Highlight possibly unused</label>
                    </div>

                </div>
            </div>
        </div>

        <input type='submit' value='Parse'/>
    </form>
</div>
</body>
</html>
