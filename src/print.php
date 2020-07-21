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
