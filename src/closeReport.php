<?php
    include_once("includes.php");

    $cashSessionId = $_POST['cashSessionId'];
    $content = Misc::url_get_contents('http://' . $_SERVER['HTTP_HOST'] . '/print.php?closeReportPrint=' . $cashSessionId);
    file_put_contents(getcwd() . "/temp/" . $cashSessionId . "-close.html", $content);

    ?>
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
            <div><b>Totaal Omzet Excl. <?=$_CFG['VATText']?>:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId) / $_CFG['VAT'], 2), 2, ",", "."); ?></span></div>
            <div><b>Totaal Inkoop:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId) / $_CFG['VAT'], 2) - round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2), 2), 2, ",", "."); ?></span></div>
            <div><b>Netto Winst:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div>
        </div>
    </div>