<?php
    include_once("../includes.php");
    Permissions::checkSession(basename($_SERVER['REQUEST_URI']));

    $content = Misc::url_get_contents('http://127.0.0.1/CashRegister/src/print.php?receipt=' . $_GET['receipt']);
    file_put_contents(getcwd() . "/temp/" . $_GET['receipt'] . ".html", $content);
?>
<html>
    <head>
        <!-- Bootstrap and all it's dependencies -->
        <?php
        if (!isset($_SESSION['login_ok']['userTheme']))
        {
            $_SESSION['login_ok']['userTheme'] = "Yeti";
        }
        
        if ($_CFG['THEME'] == "")
            $_CFG['THEME'] = 'Default';
        ?>
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/bootstrap-switch.min.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/multiple-emails.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/bootstrap.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/stylesheet.css">
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/select2.min.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/bootstrap-combobox.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/font-awesome.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/multiple-emails.css" />


        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/jquery.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/multiple-emails.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/bootstrap.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/bootstrap-notify.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/select2.full.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/jquery.jeditable.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/bootstrap-combobox.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/jquery.print.js"></script>
    </head>
    <body>
        <?php
            $receipt = Misc::sqlGet("*", "receipt", "receiptId", $_GET['receipt']);
            $json = json_decode(urldecode($receipt['items']), TRUE);
        ?>

        <div style="font-family: Verdana, Geneva, sans-serif;
	font-size: 14px;
	font-style: normal;
	font-variant: normal;
	font-weight: 400;
	line-height: 20px;
    background: white;
    width: 21cm;
    display: block;
    margin: 0 auto;
    box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
    position: absolute;">
            <img src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/images/A4-Template.png" id="letterPaper" style="position: absolute; top: -32px; width: 21cm;" />
            <div style="position: absolute; top: 196px; width: 18cm;">
                <div style="position: relative; left: 48px; font-size: 12px;">
                    Bon Nr. <?php echo $_GET['receipt']; ?><br />
                    Tijd/Datum: <?php echo $receipt['paidDt']; ?><br />
                    Kassa: <?php echo Misc::sqlGet("crName", "cash_registers", "id", Misc::sqlGet("cashRegisterId", "cashsession", "cashSessionId", $receipt['parentSession'])['cashRegisterId'])['crName']; ?><br />
                    Medewerker: <?php echo Misc::sqlGet("nickname", "users", "userId", $receipt['creator'])['nickname']; ?><br />
                    Betaalwijze: <?php if ($receipt['paymentMethod'] == "CASH") { echo "Kontant"; } else if ($receipt['paymentMethod'] == "PIN") { echo 'Pin'; } else if ($receipt['paymentMethod'] == "BANK") { echo 'Bankoverdracht'; } else if ($receipt['paymentMethod'] == "PC") { echo 'Pin en Kontant'; } else echo $receipt['paymentMethod']; ?><br />
                </div>

                <?php if (Misc::sqlGet("customerId", "receipt", "receiptId", $_GET['receipt'])['customerId'] > 0) { ?>
                <div style="margin-left: 48px; margin-top: 32px; font-size: 12px;">
                    <?php
                        $cust = Misc::sqlGet("*", "customers", "customerId", Misc::sqlGet("customerId", "receipt", "receiptId", $_GET['receipt'])['customerId']);

                    ?>
                    <?php echo $cust['initials'] . ' ' . $cust['familyName']; ?><br />
                    <?php if ($cust['companyName'] != "") echo $cust['companyName'] . '<br />'; ?>
                    <?php echo $cust['streetName']; ?><br />
                    <?php echo $cust['postalCode'] . ' ' . $cust['city']; ?><br />
                </div>
                <?php } ?>

                <br /><center style="font-size: 12px;"><?php echo urldecode(Misc::sqlGet("receiptDesc", "receipt", "receiptId", $_GET['receipt'])['receiptDesc']); ?></center>

                <table class="table" style="
        position: relative;
        left: 48px;
        margin-top: 32px;
        font-size: 10px; 
        width: 100%;">
                    <tr>
                        <th style="width: 60%;">Omschrijving</th>
                        <th style="width: 15%;">Prijs per stuk</th>
                        <th style="width: 10%;">Aantal</th>
                        <th style="width: 15%;">Totaal prijs</th>
                    </tr>
                    <?php
                        $totalIncl = 0;

                        foreach ($json as $key => $val)
                        {
                            ?>
                            <tr>
                                <td><?php if (isset($val['itemDesc'])) { echo urldecode($val['itemDesc']); } else { echo urldecode(Misc::sqlGet("itemName", "items", "nativeId", $key)['itemName']); } ?></td>
                                <td>&euro; <?php echo number_format(Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']), 2, ",", "."); ?></td>
                                <td><?php echo $val['count']; ?>x</td>
                                <td>&euro; <?php echo number_format ((Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']) * $val['count']) ,2 ,"," ,"."); ?></td>
                            </tr>
                            <?php

                            $totalIncl += (Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']) * $val['count']);
                        }
                    ?>
                </table>
                <div style="float: right; font-size: 14px;">
                    <table style="float: right; font-size: 10px;">
                        <tr style="font-size: larger;">
                            <td style=" padding-bottom: 8px;">Excl. Btw: <div style="margin-left: 12px; font-size: 10px; float: right;">&euro; <?php echo number_format(round($totalIncl / $_CFG['VAT'], 2), 2, ",", "."); ?></div></td>
                        </tr>
                        <tr style="font-size: larger;">
                            <td style=" padding-bottom: 8px;">Btw: <div style="margin-left: 12px; font-size: 10px; float: right;">&euro; <?php echo number_format(round($totalIncl - round($totalIncl / $_CFG['VAT'], 2), 2), 2, ",", "."); ?></div></td>
                        </tr>

                        <?php if ($receipt['paymentMethod'] == "PC") { ?>
                            <tr style="font-size: larger;">
                                <td style=" padding-bottom: 8px;">Pin: <div style="margin-left: 12px; font-size: 10px; float: right;">&euro; <?php echo number_format(Misc::sqlGet("pinValue", "receipt", "receiptId", $_GET['receipt'])['pinValue'], 2, ",", "."); ?></div></td>
                            </tr>
                            <tr style="font-size: larger;">
                                <td style=" padding-bottom: 8px;">Kontant: <div style="margin-left: 12px; font-size: 10px; float: right;">&euro; <?php echo number_format(Misc::sqlGet("cashValue", "receipt", "receiptId", $_GET['receipt'])['cashValue'], 2, ",", "."); ?></div></td>
                            </tr>
                        <?php } ?>

                        <tr style="font-size: larger;">
                            <td style=" padding-bottom: 8px;"><b>Totaal:</b> <div style="margin-left: 12px; font-size: 10px; float: right;">&euro; <?php echo number_format($totalIncl, 2, ",", "."); ?></div></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <?php if (!isset($_GET['mail']) || $_GET['mail'] == "false") { ?>
        <center style="
            position: relative;
            top: 16px;
            left: 36%;
        ">
            <button id="printAgain" type="button" class="btn btn-default">Afdrukken</button>
        </center>
        <script>
            $(document).ready(function() {
                $("#printAgain").on("click", function() {
                    var w = (window.parent)?window.parent:window
                    w.location.assign('printhelp://<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . '/CashRegister/src/temp/' . $_GET['receipt'] . '.html'); ?>')
                });
            });
        </script>
        <?php } else {?>
        <?php } ?>
    </body>
</html>
