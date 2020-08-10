<?php 
include_once("../includes.php");

if ($_GET['receipt'])
{
    echo 'Attempting to load receipt: ' . $_GET['receipt'];
    $json = json_decode(urldecode(Misc::sqlGet("items", "receipt", "receiptId", $_GET['receipt'])['items']), true);
    
    $_SESSION['receipt']['status'] = "open";
    $_SESSION['receipt']['saved'] = 1;
    $_SESSION['receipt']['id'] = $_GET['receipt'];
    $_SESSION['receipt']['items'] = $json;

    if (Misc::sqlGet("customerId", "receipt", "receiptId", $_GET['receipt'])['customerId'] != 0)
        $_SESSION['receipt']['customer'] = Misc::sqlGet("customerId", "receipt", "receiptId", $_GET['receipt'])['customerId'];

    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
    
    echo '  <script>
                $(document).ready(function() {
                    $("#pageLoaderIndicator").fadeIn();
                    $("#PageContent").load("receipt.php?new", function () {
                        $("#pageLoaderIndicator").fadeOut();
                    });
                });
            </script>';
}