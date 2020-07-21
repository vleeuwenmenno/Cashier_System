<?php
include_once("includes.php");
require 'classes/PHPMailer/PHPMailerAutoload.php';

Permissions::checkSession(basename($_SERVER['REQUEST_URI']));

if (isset($_GET['openReportPrint']))
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
            echo '<br />Kas-in: '.$_CFG['CURRENCY'].' ' . $row['cashIn'];
        echo '</div>';
    }
}
else if (isset($_GET['openReport']))
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

    while($row = $result->fetch_assoc())
    {
        echo '<br /><br />';
        echo '<div style="margin-left: 12px; padding-top: 12px; font-size: 12px;" id="printPart">';
            echo Misc::sqlGet("crName", "cash_registers", "id", Misc::sqlGet("id", "cash_registers", "crStaticIP", $_SERVER['REMOTE_ADDR'])['id'])['crName'] . ' geopend op ' .  $row['openDate'];
            echo '<br /><br />Medewerker: ' . $_SESSION['login_ok']['nickName'];
            echo '<br />Kas-in: '.$_CFG['CURRENCY'].' ' . $row['cashIn'];
        echo '</div>';

        $content = Misc::url_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/print.php?openReportPrint');
        file_put_contents(getcwd() . "/temp/" . $row['cashSessionId'] . "-open.html", $content);

        ?>
        <script>
            $(document).ready(function() {
                var w = (window.parent)?window.parent:window
                w.location.assign('printhelp://<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . '/temp/' . $_GET['receipt'] . $row['cashSessionId'] . '-open.html'); ?>')
            });
        </script>
        <?php
    }
}
else if (isset($_GET['closeReport']))
{
    $cashSessionId = $_GET['closeReport'];

    $content = Misc::url_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/print.php?closeReportPrint=' . $cashSessionId);
    file_put_contents(getcwd() . "/temp/" . $cashSessionId . "-close.html", $content);

    ?>
    <html>
        <head>
            <!-- Bootstrap and all it's dependencies -->
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap-switch.min.css" />
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/multiple-emails.css" />
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap.css" />
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/stylesheet.css">
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/select2.min.css" />
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap-combobox.css" />
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/font-awesome.css" />
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/multiple-emails.css" />

            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.min.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/multiple-emails.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap.min.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap-notify.min.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/select2.full.min.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.jeditable.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap-combobox.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.print.js"></script>
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
                    <div><b>Kasgeld:</b><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo Misc::sqlGet("cashOut", "cashsession", "cashSessionId", $cashSessionId)['cashOut'] ?></span></div>
                    <div><b>Kas in:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo Misc::sqlGet("cashIn", "cashsession", "cashSessionId", $cashSessionId)['cashIn'] ?></span></div>
                    <div><b>Afromen:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?> <?php echo Misc::sqlGet("cutOut", "cashsession", "cashSessionId", $cashSessionId)['cutOut'] ?></span></div>
                    <div><b>Kas uit:</b><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo round(Misc::sqlGet("cashOut", "cashsession", "cashSessionId", $cashSessionId)['cashOut'] - Misc::sqlGet("cutOut", "cashsession", "cashSessionId", $cashSessionId)['cutOut'], 2); ?> </span></div>
                    <br />
                    <div><span>Totaal Inkomsten:</span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?> </span></div>
                    <div><span>Totaal Omzet:</span><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Verschil:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?> 0,00</span></div>
                    <br />
                    <div><span>Pinbon: </span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Pin, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><span>Omzet pin: </span><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Pin, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Verschil: </b><span style="float: right;"> <?=$_CFG['CURRENCY']?> 0,00</span></div>
                    <br />
                    <div><span>Kontant kasgeld-in: </span><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><span>Omzet kontant: </span><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Verschil: </b><span style="float: right;"> <?=$_CFG['CURRENCY']?> 0,00</span></div>
                    <br />
                    <div><span>Op rekening: </span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::BankTransfer, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><span>Omzet rekening: </span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::BankTransfer, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Verschil: </b><span style="float: right;"><?=$_CFG['CURRENCY']?> 0,00</span></div>
                    <br />
                    <div><span>iDeal: </span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::iDeal, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><span>Omzet iDeal: </span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::iDeal, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Verschil: </b><span style="float: right;"><?=$_CFG['CURRENCY']?> 0,00</span></div>
                    <br />
                    <div><b>Totaal Omzet:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Totaal Omzet Excl. BTW:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId) / $_CFG['VAT'], 2), 2, ",", "."); ?></span></div>
                    <div><b>Totaal Inkoop:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId) / $_CFG['VAT'], 2) - round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2), 2), 2, ",", "."); ?></span></div>
                    <div><b>Netto Winst:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                </div>
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

                        var w = (window.parent)?window.parent:window
                        w.location.assign('printhelp://<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . '/temp/' . $_GET['receipt'] . $cashSessionId . '-close.html'); ?>')

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
else if (isset($_GET['closeReportPrint']))
{
    $cashSessionId = $_GET['closeReportPrint'];
    ?>
    <html>
        <head>
            <!-- Bootstrap and all it's dependencies -->
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap-switch.min.css" />
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/multiple-emails.css" />
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap.css" />
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/stylesheet.css">
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/select2.min.css" />
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap-combobox.css" />
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/font-awesome.css" />
            <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/multiple-emails.css" />

            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.min.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/multiple-emails.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap.min.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap-notify.min.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/select2.full.min.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.jeditable.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap-combobox.js"></script>
            <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.print.js"></script>
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
                    <div><b>Kasgeld:</b><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo Misc::sqlGet("cashOut", "cashsession", "cashSessionId", $cashSessionId)['cashOut'] ?></span></div>
                    <div><b>Kas in:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo Misc::sqlGet("cashIn", "cashsession", "cashSessionId", $cashSessionId)['cashIn'] ?></span></div>
                    <div><b>Afromen:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?> <?php echo Misc::sqlGet("cutOut", "cashsession", "cashSessionId", $cashSessionId)['cutOut'] ?></span></div>
                    <div><b>Kas uit:</b><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo round(Misc::sqlGet("cashOut", "cashsession", "cashSessionId", $cashSessionId)['cashOut'] - Misc::sqlGet("cutOut", "cashsession", "cashSessionId", $cashSessionId)['cutOut'], 2); ?> </span></div>
                    <br />
                    <div><span>Totaal Inkomsten:</span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?> </span></div>
                    <div><span>Totaal Omzet:</span><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Verschil:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?> 0,00</span></div>
                    <br />
                    <div><span>Pinbon: </span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Pin, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><span>Omzet pin: </span><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Pin, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Verschil: </b><span style="float: right;"> <?=$_CFG['CURRENCY']?> 0,00</span></div>
                    <br />
                    <div><span>Kontant kasgeld-in: </span><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><span>Omzet kontant: </span><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Verschil: </b><span style="float: right;"> <?=$_CFG['CURRENCY']?> 0,00</span></div>
                    <br />
                    <div><span>Op rekening: </span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::BankTransfer, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><span>Omzet rekening: </span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::BankTransfer, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Verschil: </b><span style="float: right;"><?=$_CFG['CURRENCY']?> 0,00</span></div>
                    <br />
                    <div><b>Totaal Omzet:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                    <div><b>Totaal Omzet Excl. BTW:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId) / $_CFG['VAT'], 2), 2, ",", "."); ?></span></div>
                    <div><b>Totaal Inkoop:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId) / $_CFG['VAT'], 2) - round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2), 2), 2, ",", "."); ?></span></div>
                    <div><b>Netto Winst:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                </div>
            </div>
        </body>
    </html>
<?php
}
else if (isset($_GET['receiptPrint']))
{
?>
<html>
    <head>
        <!-- Bootstrap and all it's dependencies -->
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap-switch.min.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/multiple-emails.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/stylesheet.css">
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/select2.min.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap-combobox.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/font-awesome.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/multiple-emails.css" />

        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/multiple-emails.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap-notify.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/select2.full.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.jeditable.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap-combobox.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.print.js"></script>
    </head>
    <body>
        <?php
            echo '<br /><br />';
            echo '<div style="margin-left: 12px; padding-top: 12px;">';

            $receipt = Misc::sqlGet("*", "receipt", "receiptId", $_GET['receiptPrint']);
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
            <div style="position: absolute; top: 196px; width: 18cm;">
                <div style="position: relative; left: 48px; font-size: 12px;">
                    Bon Nr. <?php echo $_GET['receiptPrint']; ?><br />
                    Tijd/Datum: <?php echo $receipt['paidDt']; ?><br />
                    Kassa: <?php echo Misc::sqlGet("crName", "cash_registers", "id", Misc::sqlGet("cashRegisterId", "cashsession", "cashSessionId", $receipt['parentSession'])['cashRegisterId'])['crName']; ?><br />
                    Medewerker: <?php echo Misc::sqlGet("nickname", "users", "userId", $receipt['creator'])['nickname']; ?><br />
                    Betaalwijze: <?php if ($receipt['paymentMethod'] == "CASH") { echo "Kontant"; } else if ($receipt['paymentMethod'] == "PIN") { echo 'Pin'; } else if ($receipt['paymentMethod'] == "BANK") { echo 'Bankoverdracht'; } else if ($receipt['paymentMethod'] == "PC") { echo 'Pin en Kontant'; } else echo $receipt['paymentMethod']; ?><br />
                </div>

                <?php if (Misc::sqlGet("customerId", "receipt", "receiptId", $_GET['receiptPrint'])['customerId'] > 0) { ?>
                <div style="margin-left: 48px; margin-top: 32px; font-size: 12px;">
                    <?php
                        $cust = Misc::sqlGet("*", "customers", "customerId", Misc::sqlGet("customerId", "receipt", "receiptId", $_GET['receiptPrint'])['customerId']);
                    ?>
                    <?php echo $cust['initials'] . ' ' . $cust['familyName']; ?><br />
                    <?php if ($cust['companyName'] != "") echo $cust['companyName'] . '<br />'; ?>
                    <?php echo $cust['streetName']; ?><br />
                    <?php echo $cust['postalCode'] . ' ' . $cust['city']; ?><br />
                </div>
                <?php } ?>

                <br /><center style="font-size: 12px;"><?php echo urldecode(Misc::sqlGet("receiptDesc", "receipt", "receiptId", $_GET['receiptPrint'])['receiptDesc']); ?></center>

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
                                <td><?=$_CFG['CURRENCY']?> <?php echo number_format(Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']), 2, ",", "."); ?></td>
                                <td><?php echo $val['count']; ?>x</td>
                                <td><?=$_CFG['CURRENCY']?> <?php echo number_format ((Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']) * $val['count']) ,2 ,"," ,"."); ?></td>
                            </tr>
                            <?php

                            $totalIncl += (Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']) * $val['count']);
                        }
                    ?>
                </table>
                <div style="float: right; font-size: 14px;">
                    <table style="float: right; font-size: 10px;">
                        <tr style="font-size: larger;">
                            <td style=" padding-bottom: 8px;">Excl. Btw: <div style="margin-left: 12px; font-size: 10px; float: right;"><?=$_CFG['CURRENCY']?> <?php echo number_format(round($totalIncl / $_CFG['VAT'], 2), 2, ",", "."); ?></div></td>
                        </tr>
                        <tr style="font-size: larger;">
                            <td style=" padding-bottom: 8px;">Btw: <div style="margin-left: 12px; font-size: 10px; float: right;"><?=$_CFG['CURRENCY']?> <?php echo number_format(round($totalIncl - round($totalIncl / $_CFG['VAT'], 2), 2), 2, ",", "."); ?></div></td>
                        </tr>

                        <?php if ($receipt['paymentMethod'] == "PC") { ?>
                            <tr style="font-size: larger;">
                                <td style=" padding-bottom: 8px;">Pin: <div style="margin-left: 12px; font-size: 10px; float: right;"><?=$_CFG['CURRENCY']?> <?php echo number_format(Misc::sqlGet("pinValue", "receipt", "receiptId", $_GET['receipt'])['pinValue'], 2, ",", "."); ?></div></td>
                            </tr>
                            <tr style="font-size: larger;">
                                <td style=" padding-bottom: 8px;">Kontant: <div style="margin-left: 12px; font-size: 10px; float: right;"><?=$_CFG['CURRENCY']?> <?php echo number_format(Misc::sqlGet("cashValue", "receipt", "receiptId", $_GET['receipt'])['cashValue'], 2, ",", "."); ?></div></td>
                            </tr>
                        <?php } ?>

                        <tr style="font-size: larger;">
                            <td style=" padding-bottom: 8px;"><b>Totaal:</b> <div style="margin-left: 12px; font-size: 10px; float: right;"><?=$_CFG['CURRENCY']?> <?php echo number_format($totalIncl, 2, ",", "."); ?></div></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
}
else if (isset($_GET['receipt']))
{
    $content = Misc::url_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/print.php?receiptPrint=' . $_GET['receipt']);
    file_put_contents(getcwd() . "/temp/" . $_GET['receipt'] . ".html", $content);
?>
<html>
    <head>
        <!-- Bootstrap and all it's dependencies -->
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap-switch.min.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/multiple-emails.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/stylesheet.css">
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/select2.min.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap-combobox.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/font-awesome.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/multiple-emails.css" />

        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/multiple-emails.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap-notify.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/select2.full.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.jeditable.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap-combobox.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.print.js"></script>
    </head>
    <body>
    <?php
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
        line-height: 20px;
        background: white;
        width: 21cm;
        display: block;
        margin: 0 auto;
        box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
        position: absolute;">
        <img src="http://<?php echo $_CFG['HOST_NAME']; ?>/images/A4-Template.png" id="letterPaper" style="position: absolute; top: -32px; width: 21cm;<?php if ($_GET['printLetterPaper'] == "true") { ?> display: block; <?php } else { ?> display: none; <?php } ?>" />
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
                            <td><?=$_CFG['CURRENCY']?> <?php echo number_format(Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']), 2, ",", "."); ?></td>
                            <td><?php echo $val['count']; ?>x</td>
                            <td><?=$_CFG['CURRENCY']?> <?php echo number_format ((Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']) * $val['count']) ,2 ,"," ,"."); ?></td>
                        </tr>
                        <?php

                        $totalIncl += (Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']) * $val['count']);
                    }
                ?>
            </table>
            <div style="float: right; font-size: 14px;">
                <table style="float: right; font-size: 10px;">
                    <tr style="font-size: larger;">
                        <td style=" padding-bottom: 8px;">Excl. Btw: <div style="margin-left: 12px; font-size: 10px; float: right;"><?=$_CFG['CURRENCY']?> <?php echo number_format(round($totalIncl / $_CFG['VAT'], 2), 2, ",", "."); ?></div></td>
                    </tr>
                    <tr style="font-size: larger;">
                        <td style=" padding-bottom: 8px;">Btw: <div style="margin-left: 12px; font-size: 10px; float: right;"><?=$_CFG['CURRENCY']?> <?php echo number_format(round($totalIncl - round($totalIncl / $_CFG['VAT'], 2), 2), 2, ",", "."); ?></div></td>
                    </tr>

                    <?php if ($receipt['paymentMethod'] == "PC") { ?>
                        <tr style="font-size: larger;">
                            <td style=" padding-bottom: 8px;">Pin: <div style="margin-left: 12px; font-size: 10px; float: right;"><?=$_CFG['CURRENCY']?> <?php echo number_format(Misc::sqlGet("pinValue", "receipt", "receiptId", $_GET['receipt'])['pinValue'], 2, ",", "."); ?></div></td>
                        </tr>
                        <tr style="font-size: larger;">
                            <td style=" padding-bottom: 8px;">Kontant: <div style="margin-left: 12px; font-size: 10px; float: right;"><?=$_CFG['CURRENCY']?> <?php echo number_format(Misc::sqlGet("cashValue", "receipt", "receiptId", $_GET['receipt'])['cashValue'], 2, ",", "."); ?></div></td>
                        </tr>
                    <?php } ?>

                    <tr style="font-size: larger;">
                        <td style=" padding-bottom: 8px;"><b>Totaal:</b> <div style="margin-left: 12px; font-size: 10px; float: right;"><?=$_CFG['CURRENCY']?> <?php echo number_format($totalIncl, 2, ",", "."); ?></div></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php

        echo '</div>';
        echo '<center style="
                position: relative;
                top: 16px;
                left: 36%;
            "><button id="printAgain" type="button" class="btn btn-default">';
        if ($_GET['print'] > 0)
        {
            echo 'Nogmaals ';
        }
        echo 'Afdrukken</button>';

        if ($_GET['mail'] == "true")
            echo '  <button id="emailAgain" type="button" class="btn btn-default">Nogmaals Emailen</button>';
        else
            echo '  <button id="emailAgain" type="button" class="btn btn-default">Emailen</button>';

        echo '<script>
            $(document).ready(function() {
                $("#emailAgain").css("display", "none");
                $("#letterPaperInput").css("display", "none");';

        if ($_GET['printLetterPaper'] == "false")
            echo '$("#letterPaper").css("display", "none");';

        if ($_GET['print'] > 0)
        {
                ?>
                $("#printAgain").css("display", "none");

                var w = (window.parent)?window.parent:window
                w.location.assign('printhelp://<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . '/temp/' . $_GET['receipt'] . '.html'); ?>')

                $("#emailAgain").css("display", "inline");
                $("#letterPaper").css("display", "inline");
                $("#printAgain").css("display", "inline");
                $("#letterPaperInput").css("display", "block");
                <?php
        }

        ?>
                    $("#printAgain").on("click", function() {
                    $("#printAgain").css("display", "none");
                    $("#letterPaper").css("display", "none");
                    $("#emailAgain").css("display", "none");
                    $("#letterPaperInput").css("display", "none");

                    var w = (window.parent)?window.parent:window
                    w.location.assign('printhelp://<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . '/temp/' . $_GET['receipt'] . '.html'); ?>')

                    $("#emailAgain").css("display", "inline");
                    $("#letterPaper").css("display", "inline");
                    $("#printAgain").css("display", "inline");
                    $("#letterPaperInput").css("display", "block");
                });
            });
        <?php
        echo '</script>';

        if ($_GET['mail'] == "true")
        {
            if (file_exists(getcwd() . "/temp/" . $_GET['receipt'] . ".html"))
            {
                unlink(getcwd() . "/temp/" . $_GET['receipt'] . ".html");
            }

            $content = Misc::url_get_contents('http://cashier.local/receipt/viewReceipt.php?receipt=' . $_GET['receipt'] . '&mail=true');
            file_put_contents(getcwd() . "/temp/" . $_GET['receipt'] . "-mail.html", $content);

            //Check which os we use to convert this to PDF
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            {
                //Do the magic https://wkhtmltopdf.org/
                echo "!!!!!!!!!!--Windows is not supported--!!!!!!!!!!";

                ?>
                    <script>
                    $(document).ready(function() {
                        $.notify({
                            icon: 'fa fa-envelope-o fa-2x',
                            title: '<b>Mail niet verstuurd</b><br / >',
                            message: 'Kassa op Windows host ondersteund geen PDF printen'
                        }, {
                            // settings
                            type: 'warning',
                            delay: 5000,
                            timer: 10,
                            placement: {
                                from: "bottom",
                                align: "right"
                            }
                        });
                    });
                    </script>
                <?php
            }
            else
            {
                //Do the magic https://wkhtmltopdf.org/
                //wkhtmltopdf -T 0 -R 0 -B 0 -L 0 --orientation Portrait --page-size A4 --disable-smart-shrinking 1182791971.html 1182791971.pdf
                exec(getcwd() . "/../deps/wkhtmltopdf -B 0 -L 0 -R 0 -T 0 --orientation Portrait --page-size A4 --disable-smart-shrinking " . "temp/" . $_GET['receipt'] . "-mail.html temp/" . $_GET['receipt'] . "-mail.pdf");

                //Wait for the exec to complete
                while (!file_exists("temp/" . $_GET['receipt'] . "-mail.pdf"))
                { }

                $cust = Misc::sqlGet("*", "customers", "customerId", Misc::sqlGet("customerId", "receipt", "receiptId", $_GET['receipt'])['customerId']);

                $mail = new PHPMailer;

                $mail->isSMTP();
                $mail->Host = 'smtp02.hostnet.nl';
                $mail->SMTPAuth = true;
                $mail->Username = 'smtp@comforttoday.nl';
                $mail->Password = 'Maerelaan26!';
                $mail->SMTPSecure = 'STARTTLS';
                $mail->Port = 587;

                $mail->setFrom('info@comtoday.nl', 'Com Today Castricum');

                $object = json_decode(urldecode($_GET['mailList']), TRUE);
                $mail->addAddress($object[0], $cust['initials'] . ' ' . $cust['familyName']);

                if (!isset($_GET['nobcc']))
                    $mail->addBCC('facturen@comforttoday.nl');

                for($i = 0; $i < count($object); $i++)
                {
                    if ($i > 0)
                        $mail->addAddress($object[$i], "");
                }

                $mail->addAttachment("temp/" . $_GET['receipt'] . "-mail.pdf");
                $mail->isHTML(true);

                $mail->Subject = 'Uw factuur';
                $mail->Body    = 'Geachte klant,<br /><br />

                                    Bedankt voor uw aankoop bij Com Today.<br />
                                    De bijlage bevat uw factuur.<br /><br />

                                    Wij wensen u veel plezier met uw aankoop<br /><br />

                                    Met vriendelijke groeten,<br /><br />

                                    <b>Com Today </b><br />
                                    Castricummer Werf 45 <br />
                                    1901 RV, Castricum <br />
                                    0251 200627 <br />
                                    info@comtoday.nl<br />';

                if(!$mail->send())
                {
                    ?>
					<?php echo 'MAIL_ERROR: ' . $mail->ErrorInfo; ?>
                    <script>
                    $(document).ready(function() {
                        $.notify({
                            icon: 'fa fa-envelope-o fa-2x',
                            title: '<b>Mail NIET verstuurd</b><br / >',
                            message: 'De email is niet verstuurd naar de klant wegens een fout!. <br /><?php echo $mail->ErrorInfo; ?>'
                        }, {
                            // settings
                            type: 'warning',
                            delay: 5000,
                            timer: 10,
                            placement: {
                                from: "bottom",
                                align: "right"
                            }
                        });
                        $(document).ready(function() {
                            $("#pageLoaderIndicator").fadeOut();
                            $("#sideBarMenu").removeClass("disabledbutton");
                        });
                    });
                    </script>
                    <?php
                }
                else
                {
                    ?>
                    <script>
                    $(document).ready(function() {
                        $.notify({
                            icon: 'fa fa-envelope-o fa-2x',
                            title: '<b>Mail verstuurd</b><br / >',
                            message: 'De email is succesvol verstuurd naar de klant.'
                        }, {
                            // settings
                            type: 'success',
                            delay: 5000,
                            timer: 10,
                            placement: {
                                from: "bottom",
                                align: "right"
                            }
                        });

                        $(document).ready(function() {
                            $("#pageLoaderIndicator").fadeOut();
                            $("#sideBarMenu").removeClass("disabledbutton");
                        });
                    });
                    </script>
                    <?php
                }

                unlink("temp/" . $_GET['receipt'] . "-mail.pdf");
            }
        }

        ?>
        <script>
            $(document).ready(function() {
                $("#pageLoaderIndicator").fadeOut();
                $("#sideBarMenu").removeClass("disabledbutton");
            });
        </script>
    </body>
</html>
        <?php
}
