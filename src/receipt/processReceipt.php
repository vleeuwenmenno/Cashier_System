<?php
include_once('../includes.php');

//Check if receipt contains any items at ALL
if (count($_SESSION['receipt']['items']) < 1)
{
    echo '
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

    //Create a document for the paper receipt

    //Print receipt (Amount based on GET para &print)

    //Register receipt as paid into the database

    //Move receipt data in session to OLD

    //Go to print page
    echo '
    <script>
        $(document).ready(function() {
            $("#pageLoaderIndicator").fadeIn();
            $("#PageContent").load("print.php?receipt=' . str_pad($_SESSION['receipt']['id'], 4, '0', STR_PAD_LEFT) . '", function () {
                $("#pageLoaderIndicator").fadeOut();
            });
        });
    </script>';
}
