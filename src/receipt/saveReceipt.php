<?php
include_once("../includes.php");

$json = json_encode($_SESSION['receipt']['items']);

if (Misc::sqlExists("receiptId", $_SESSION['receipt']['id'], "receipt"))
{
    $result = Misc::sql("UPDATE receipt SET customerId='" . $_SESSION['receipt']['customer'] . "', items='" . urlencode($json) . "' WHERE receiptId='" . $_SESSION['receipt']['id'] . "'");

    if ($result == 1)
    {
        $_SESSION['receipt']['saved'] = true;
        die("OK");
    }
    else
        die($result);
}
else
{
    $result = Misc::sql("INSERT INTO `receipt` (`receiptId`, `creator`, `parentSession`, `items`, `createDt`, `paidDt`, `customerId`, `paymentMethod`) VALUES
                                            ('" . $_SESSION['receipt']['id'] . "', '" . $_SESSION['login_ok']['userId'] . "', '" .
                                            Misc::sqlGet("currentSession", "cash_registers", "crStaticIP", $_SERVER['REMOTE_ADDR'])['currentSession'] . "', '" .
                                            urlencode($json) . "', '" . date('H:i:s d-m-Y') . "', NULL, '" . $_SESSION['receipt']['customer'] . "', NULL);");
    if ($result == 1)
    {
        $_SESSION['receipt']['saved'] = true;
        die("OK");
    }
    else
        die($result);
}
