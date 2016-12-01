<?php
include_once('../includes.php');

if (isset($_GET["modifier"]) && isset($_GET['global']) && isset($_GET["nativeId"]) && isset($_GET["priceExclVat"]))
{
    if ($_GET['global'] == true)
    {
        $_SESSION['receipt']['items'][$_GET['nativeId']]['priceAPiece']['priceModifier'] = $_GET["modifier"];
        $_SESSION['receipt']['items'][$_GET['nativeId']]['priceAPiece']['priceExclVat'] = $_GET["priceExclVat"];
    }
    else
    {
        $_SESSION['receipt']['items'][$_GET['nativeId']]['priceAPiece']['priceModifier'] = $_GET["modifier"];
        $_SESSION['receipt']['items'][$_GET['nativeId']]['priceAPiece']['priceExclVat'] = $_GET["priceExclVat"];

        //TODO: Update the modifier and priceExclVat globally!
    }
}

 ?>
