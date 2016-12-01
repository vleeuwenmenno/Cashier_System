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
    //TODO: Generate document

    //Print receipt (Amount based on GET para &print)
    //TODO: Print with PHP functions

    //Register receipt as paid into the database
    $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

    if($db->connect_errno > 0)
    {
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    $sql = "UPDATE `receipt` SET `paidDt` = '" . date("d-m-Y H:i:s") . "', `paymentMethod` = '" . $paymentMethod . "' WHERE `receipt`.`receiptId`='" . $receiptId . "';";

    if(!$result = $db->query($sql))
    {
        die('There was an error running the query [' . $db->error . ']');
    }

    //Move receipt data in session to OLD
    //Go to print page
    echo '
    <script>
        $(document).ready(function() {
            $("#pageLoaderIndicator").fadeIn();
            $("#PageContent").load("print.php?receipt=' . str_pad($receiptId, 4, '0', STR_PAD_LEFT) . '&print=' . $printAmount . '", function () {
                $("#pageLoaderIndicator").fadeOut();
            });

            $("#newReceipt").html("<span class=\"glyphicon glyphicon-file\"></span> Nieuwe Bon");

            $.get(
                "receipt/empty.php",
                {
                    receiptId: \'' . $receiptId . '\',
                    save: 1
                },
                function (data)
                {
                    $.notify({
                        icon: \'glyphicon glyphicon-trash\',
                        title: \'\',
                        message: \'Bon is betaalt en verwerkt in het systeem.\'
                    }, {
                        // settings
                        type: \'success\',
                        delay: 2000,
                        timer: 10,
                        placement: {
                            from: "bottom",
                            align: "right"
                        }
                    });
                }
            );
        });
    </script>';
}
