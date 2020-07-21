<?php
include_once('../includes.php');

//Check if receipt contains any items at ALL
if (count($_SESSION['receipt']['items']) < 1)
{
    echo '
    <script src="../js/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#pageLoaderIndicator").fadeIn();
            $("#PageContent").load("receipt.php?new", function () {
                $("#pageLoaderIndicator").fadeOut();
                $("#statusText").html("<p style=\"color: orange !important;\">Voeg eerst een artikel toe om te betalen. &nbsp;&nbsp;&nbsp;&nbsp;</p>");
            });
        });
    </script>';
    die("Voeg eerst een artikel toe om te betalen.");
}
else
{
    //Get ALL receipt data to put on the paper
    $printAmount = $_GET['printAmount'];
    $receiptId = $_GET['receiptId'];
    $paymentMethod = $_GET['paymentMethod'];

    if ($paymentMethod == "PC")
    {
        $cashValue = $_GET['cash'];
        $pinValue = $_GET['pin'];
    }

    //Register receipt as paid into the database
    $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

    if($db->connect_errno > 0)
    {
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    $json = json_encode($_SESSION['receipt']['items']);
    $sql = "UPDATE `receipt` SET `receiptDesc` = '" . $_GET['receiptDesc'] . "', `paidDt` = '" . date("H:i:s d-m-Y") . "', `pinValue` = '" . str_replace (",", ".", $pinValue) . "', `cashValue` = '" . str_replace (",", ".", $cashValue) . "' , `paymentMethod` = '" . $paymentMethod . "', `items` = '" . urlencode($json) . "' WHERE `receipt`.`receiptId`='" . $receiptId . "';";

    if(!$result = $db->query($sql))
    {
        die('There was an error running the query [' . $db->error . ']');
    }

    //Foreach item in the receipt remove amount from the items Register
    $json = json_decode(urldecode(Misc::sqlGet("items", "receipt", "receiptId", $receiptId)['items']), TRUE);
    foreach ($json as $key => $val)
    {
        if (intval(Misc::sqlGet("itemStock", "items", "nativeId", $key)['itemStock']) != 2147483647)
        {
            if (strlen(Misc::sqlGet("EAN", "items", "nativeId", $key)['EAN']) > 10)
                Misc::sqlUpdate("items", "itemStock", "itemStock - " . $val['count'], "EAN", "" . Misc::sqlGet("EAN", "items", "nativeId", $key)['EAN']);
            else
                Misc::sqlUpdate("items", "itemStock", "itemStock - " . $val['count'], "nativeId", "" . $key);
        }
    }

    $_SESSION['receipt']['old'] = null;
    $_SESSION['receipt']['old'] = $_SESSION['receipt'];

    $_SESSION['receipt']['id'] = 0;
    $_SESSION['receipt']['items'] = null;
    $_SESSION['receipt']['status'] = 'closed';
    $_SESSION['receipt']['customer'] = null;
    $_SESSION['receipt']['saved'] = false;
    unset($_SESSION['receipt']['order']);

    //Move receipt data in session to OLD
    //Go to print page
    echo '
    <script>
        $(document).ready(function() {
            ';

    if (Misc::sqlGet("paymentMethod", "receipt", "receiptId", $receiptId)['paymentMethod'] == "BANK")
    {
        echo '
            $("#pageLoaderIndicator").fadeIn();
            $("#PageContent").load("mail.php?receipt=' . str_pad($receiptId, 4, '0', STR_PAD_LEFT) . '&mail=' . $_GET['mail'] . '&mailList=' . urlencode($_GET['mailList']) . '", function () {
                $("#pageLoaderIndicator").fadeOut();
            });';
    }
    else
    {
        echo '
            console.log("mail.php?receipt=' . str_pad($receiptId, 4, '0', STR_PAD_LEFT) . '&mail=' . $_GET['mail'] . '&nobcc=1&mailList=' . urlencode($_GET['mailList']) . '");
            $("#pageLoaderIndicator").fadeIn();
            $("#PageContent").load("mail.php?receipt=' . str_pad($receiptId, 4, '0', STR_PAD_LEFT) . '&mail=' . $_GET['mail'] . '&nobcc=1&mailList=' . urlencode($_GET['mailList']) . '", function () {
                $("#pageLoaderIndicator").fadeOut();
            });';
    }

    echo '

            $("#newReceipt").html("<i class=\"fa fa-file-text\" aria-hidden=\"true\"></i>&nbsp;&nbsp; Nieuwe Bon");
            $("#newReceipt").hide();
        });
    </script>';
}
