<?php
    include_once("../includes.php");

    if (isset($_GET['itemId']) && isset($_GET['newDesc']))
    {
        $_SESSION['receipt']['items'][$_GET['itemId']]['itemDesc'] = urldecode($_GET['newDesc']);
        echo 'OK';
    }
    else
    {
        echo 'ERROR, Missing parameters!';
    }
?>
