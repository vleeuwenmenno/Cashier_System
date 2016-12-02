<?php
include_once('../includes.php');

if (isset($_GET["modifier"]) && isset($_GET['global']) && isset($_GET["nativeId"]) && isset($_GET["priceExclVat"]))
{
    if ($_GET['global'] == true)
    {
        $_SESSION['receipt']['items'][$_GET['nativeId']]['priceAPiece']['priceModifier'] = str_replace(',', '.', $_GET["modifier"]);
        $_SESSION['receipt']['items'][$_GET['nativeId']]['priceAPiece']['priceExclVat'] = number_format($_GET["priceExclVat"], 2, '.', '');
    }
    else
    {
        $_SESSION['receipt']['items'][$_GET['nativeId']]['priceAPiece']['priceModifier'] = str_replace(',', '.', $_GET["modifier"]);
        $_SESSION['receipt']['items'][$_GET['nativeId']]['priceAPiece']['priceExclVat'] = number_format($_GET["priceExclVat"], 2, '.', '');

        //TODO: Update the modifier and priceExclVat globally!
    }
}

 ?>
