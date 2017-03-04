<?php
include_once("includes.php");
Permissions::checkSession(basename($_SERVER['REQUEST_URI']));

if (isset($_GET['openReport']))
{
    $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

    if($db->connect_errno > 0)
    {
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    $sql = "SELECT * FROM cashsession ORDER BY cashSessionId DESC LIMIT 1;";

    if(!$result = $db->query($sql))
    {
        die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')');
    }

    $cashOut = 0.0;
    while($row = $result->fetch_assoc())
    {
        echo '<br /><br />';
        echo '<div style="margin-left: 12px; padding-top: 12px; font-size: 12px;" id="printPart">';
            echo Misc::sqlGet("crName", "cash_registers", "id", Misc::sqlGet("id", "cash_registers", "crStaticIP", $_SERVER['REMOTE_ADDR'])['id'])['crName'] . ' geopend op ' .  $row['openDate'];
            echo '<br /><br />Medewerker: ' . $_SESSION['login_ok']['nickName'];
            echo '<br />Kas-in: &euro; ' . $row['cashIn'];
        echo '</div>';
        echo '<center><button id="printAgain" type="button" class="btn btn-default">Nogmaals Afdrukken</button></center>';
        echo '<script>';
        echo '
            $(document).ready(function() {
                $("#printAgain").css("display", "none");
                $("#printPart").printElement();
                $("#printAgain").css("display", "inline");

                $("#printAgain").on("click", function() {
                    $("#printAgain").css("display", "none");
                    $("#printPart").printElement();
                    $("#printAgain").css("display", "inline");
                });
            });
        ';
        echo '</script>';
    }
}
else if (isset($_GET['closeReport']))
{
    $cashSessionId = $_GET['closeReport'];
    ?>
    <html>
        <head>
            <!-- Bootstrap and all it's dependencies -->
            <?php
            if ($_CFG['THEME'] == "")
                $_CFG['THEME'] = 'Default';
            ?>
            <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/bootstrap.css" />
            <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/stylesheet.css">
            <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/select2.min.css" />
            <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/bootstrap-combobox.css" />
            <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/font-awesome.css" />

            <script src="js/jquery.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/bootstrap-notify.min.js"></script>
            <script src="js/select2.full.min.js"></script>
            <script src="js/jquery.jeditable.js"></script>
            <script src="js/bootstrap-combobox.js"></script>
            <script src="js/jquery.printElement.js"></script>
        </head>
        <body>
            <div id="reportPrint" style="margin: 32px; font-size: 12px;">
                <div style="width: 256px;">
                    Dagoverzicht van <?php echo Misc::sqlGet("crName", "cash_registers", "id", Misc::sqlGet("cashRegisterId", "cashsession", "cashSessionId", $cashSessionId)['cashRegisterId'])['crName']; ?> op <?php echo substr(Misc::sqlGet("closeDate", "cashsession", "cashSessionId", $cashSessionId)['closeDate'], 0, 10); ?><br />
                    <br />
                    Geopend: <?php echo Misc::sqlGet("openDate", "cashsession", "cashSessionId", $cashSessionId)['openDate']; ?><br />
                    Medewerker: <?php echo Misc::sqlGet("nickName", "users", "userId", Misc::sqlGet("openedBy", "cashsession", "cashSessionId", $cashSessionId)['openedBy'])['nickName']; ?><br />
                    <br />
                    Gesloten: <?php echo Misc::sqlGet("closeDate", "cashsession", "cashSessionId", $cashSessionId)['closeDate']; ?><br />
                    Medewerker: <?php echo Misc::sqlGet("nickName", "users", "userId", Misc::sqlGet("closedBy", "cashsession", "cashSessionId", $cashSessionId)['closedBy'])['nickName']; ?><br />
                    <br />
                    <div><b>Kasgeld:</b><span style="float: right;">&euro;&nbsp;<?php echo Misc::sqlGet("cashOut", "cashsession", "cashSessionId", $cashSessionId)['cashOut'] ?></span></div>
                    <div><b>Kas in:</b><span style="float: right;"> &euro;&nbsp;<?php echo Misc::sqlGet("cashIn", "cashsession", "cashSessionId", $cashSessionId)['cashIn'] ?></span></div>
                    <div><b>Afromen:</b><span style="float: right;"> &euro; <?php echo Misc::sqlGet("cutOut", "cashsession", "cashSessionId", $cashSessionId)['cutOut'] ?></span></div>
                    <div><b>Kas uit:</b><span style="float: right;">&euro;&nbsp;<?php echo round(Misc::sqlGet("cashOut", "cashsession", "cashSessionId", $cashSessionId)['cashOut'] - Misc::sqlGet("cutOut", "cashsession", "cashSessionId", $cashSessionId)['cutOut'], 2); ?> </span></div>
                    <br />
                    <div><span>Totaal Inkomsten:</span><span style="float: right;">&euro;&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?> </span></div>
                    <div><span>Totaal Omzet:</span><span style="float: right;"> &euro;&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Verschil:</b><span style="float: right;"> &euro; 0,00</span></div>
                    <br />
                    <div><span>Pinbon: </span><span style="float: right;">&euro;&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Pin, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><span>Omzet pin: </span><span style="float: right;"> &euro;&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Pin, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Verschil: </b><span style="float: right;"> &euro; 0,00</span></div>
                    <br />
                    <div><span>Kontant kasgeld-in: </span><span style="float: right;"> &euro;&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><span>Omzet kontant: </span><span style="float: right;"> &euro;&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Verschil: </b><span style="float: right;"> &euro; 0,00</span></div>
                    <br />
                    <div><span>Op rekening: </span><span style="float: right;">&euro;&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::BankTransfer, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><span>Omzet rekening: </span><span style="float: right;">&euro;&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::BankTransfer, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Verschil: </b><span style="float: right;">&euro; 0,00</span></div>
                    <br />
                    <div><b>Totaal Omzet:</b><span style="float: right;"> &euro;&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Totaal Omzet Excl. BTW:</b><span style="float: right;"> &euro;&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId) / $_CFG['VAT'], 2), 2, ",", "."); ?></span></div>
                    <div><b>Totaal Inkoop:</b><span style="float: right;"> &euro;&nbsp;<?php echo number_format(round(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId) / $_CFG['VAT'], 2) - round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2), 2), 2, ",", "."); ?></span></div>
                    <div><b>Netto Winst:</b><span style="float: right;"> &euro;&nbsp;<?php echo number_format(round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                </div>
                <?php
                    //LOOP ALL ITEMS WE SOLD TODAY
                    echo '//TODO: Zorg dat de artikelen die verkocht zijn vandaag hier worden weergeven.';
                ?>
            </div>
            <center>
                <button type="button" id="printBtn" class="btn btn-primary">Afdrukken</button>
                <button type="button" id="backTo" class="btn btn-default">Terug naar Kassa</button>
            </center>
            <script>
                $(document).ready(function() {
                    $("#printBtn").on("click", function () {
                        $("#printBtn").css("display", "none");
                        $("#backTo").css("display", "none");

                        $("#reportPrint").printElement();

                        $("#printBtn").css("display", "");
                        $("#backTo").css("display", "");
                    });

                    $("#backTo").on("click", function () {
                        window.location.replace("master.php");
                    });
                });
            </script>
        </body>
    </html>
<?php
}
else if (isset($_GET['receipt']))
{
    echo '<br /><br />';
    echo '<div style="margin-left: 12px; padding-top: 12px;">';

    $receipt = Misc::sqlGet("*", "receipt", "receiptId", $_GET['receipt']);
    $json = json_decode(urldecode($receipt['items']), TRUE);
?>

<div style="font-family: Verdana, Geneva, sans-serif;
font-size: 14px;
font-style: normal;
font-variant: normal;
font-weight: 400;
line-height: 20px;">

    <div style="margin-left: 48px;margin-top: 128px; font-size: 12px;">
        Bon Nr. <?php echo $_GET['receipt']; ?><br />
        Tijd/Datum: <?php echo $receipt['paidDt']; ?><br />
        Kassa: <?php echo Misc::sqlGet("crName", "cash_registers", "id", Misc::sqlGet("cashRegisterId", "cashsession", "cashSessionId", $receipt['parentSession'])['cashRegisterId'])['crName']; ?><br />
        Medewerker: <?php echo Misc::sqlGet("nickname", "users", "userId", $receipt['creator'])['nickname']; ?><br />
        Betaalwijze: <?php if ($receipt['paymentMethod'] == "CASH") { echo "Kontant"; } else if ($receipt['paymentMethod'] == "PIN") { echo 'Pin'; } else if ($receipt['paymentMethod'] == "BANK") { echo 'Bankoverdracht'; } else if ($receipt['paymentMethod'] == "PC") { echo 'Pin en Kontant'; } ?><br />
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

    <table class="table" style="
margin-left: 48px;
margin-right: 48px;
margin-top: 32px;
font-size: 10px; ">
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
                    <td>€ <?php echo str_replace(".", ",", Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier'])); ?></td>
                    <td><?php echo $val['count']; ?>x</td>
                    <td>€ <?php echo str_replace(".", ",", (Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']) * $val['count'])); ?></td>
                </tr>
                <?php

                $totalIncl += (Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']) * $val['count']);
            }
        ?>
    </table>
    <div style="float: right; font-size: 14px;">
        <table style="float: right; font-size: 10px;">
            <tr style="font-size: larger;">
                <td style=" padding-bottom: 8px;">Excl. Btw: <div style="margin-left: 12px; font-size: 10px; float: right;">€ <?php echo number_format(round($totalIncl / $_CFG['VAT'], 2), 2, ",", "."); ?></div></td>
            </tr>
            <tr style="font-size: larger;">
                <td style=" padding-bottom: 8px;">Btw: <div style="margin-left: 12px; font-size: 10px; float: right;">€ <?php echo number_format(round($totalIncl - round($totalIncl / $_CFG['VAT'], 2), 2), 2, ",", "."); ?></div></td>
            </tr>

            <?php if ($receipt['paymentMethod'] == "PC") { ?>
                <tr style="font-size: larger;">
                    <td style=" padding-bottom: 8px;">Pin: <div style="margin-left: 12px; font-size: 10px; float: right;">€ <?php echo number_format(Misc::sqlGet("pinValue", "receipt", "receiptId", $_GET['receipt'])['pinValue'], 2, ",", "."); ?></div></td>
                </tr>
                <tr style="font-size: larger;">
                    <td style=" padding-bottom: 8px;">Kontant: <div style="margin-left: 12px; font-size: 10px; float: right;">€ <?php echo number_format(Misc::sqlGet("cashValue", "receipt", "receiptId", $_GET['receipt'])['cashValue'], 2, ",", "."); ?></div></td>
                </tr>
            <?php } ?>

            <tr style="font-size: larger;">
                <td style=" padding-bottom: 8px;"><b>Totaal:</b> <div style="margin-left: 12px; font-size: 10px; float: right;">€ <?php echo number_format($totalIncl, 2, ",", "."); ?></div></td>
            </tr>
        </table>
    </div>
</div>
<?php

    echo '</div>';
    echo '<center><button id="printAgain" type="button" class="btn btn-default">';
    if ($_GET['print'] > 0)
    {
        echo 'Nogmaals ';
    }
    echo 'Afdrukken</button></center>';
    echo '<script>
        $(document).ready(function() {';

    if ($_GET['print'] > 0)
    {
        echo '$("#printAgain").css("display", "none");
            $("#PageContent").printElement({ printMode:\'popup\' });
            $("#printAgain").css("display", "inline");';
    }

    echo '  $("#printAgain").on("click", function() {
                $("#printAgain").css("display", "none");
                $("#PageContent").printElement({ printMode:\'popup\' });
                $("#printAgain").css("display", "inline");
            });
        });
    ';
    echo '</script>';
}
