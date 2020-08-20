
<?php
    include_once("../includes.php");

    $custId = Misc::sqlGet("customerId", "receipt", "receiptId", $_POST['rid'])['customerId'];

    $time = new DateTime(Misc::sqlGet("createDt", "receipt", "receiptId", $_POST['rid'])['createDt']);
    $pMethod = Misc::sqlGet("paymentMethod", "receipt", "receiptId", $_POST['rid'])['paymentMethod'];
    $notice = Misc::sqlGet("receiptDesc", "receipt", "receiptId", $_POST['rid'])['receiptDesc'];
    $creator = Misc::sqlGet("nickName", "users", "userId", Misc::sqlGet("creator", "receipt", "receiptId", $_POST['rid'])['creator'])['nickName'];
    $cashier = Misc::sqlGet("crName", "cash_registers", "id", Misc::sqlGet("cashRegisterId", "cashsession", "cashSessionId", Misc::sqlGet("parentSession", "receipt", "receiptId", $_POST['rid'])['parentSession'])['cashRegisterId'])['crName'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Factuur</title>
            <link rel="stylesheet" href="style.css" media="all" />
        </head>
        <body>
            <header class="clearfix">
                <div>
                    <div id="logo">
                        <img src="logo.png">
                    </div>
                    <h1><?=$_CFG['companyAddress']?> &bull; <?=$_CFG['companyPhone']?> &bull; <?=$_CFG['companyWebsite']?> &bull; <?=$_CFG['companyEmail']?></h1>
                    <h2><?=$_CFG['companyKvk']?> &bull; <?=$_CFG['companyVATNo']?> &bull; <?=$_CFG['companyIBAN']?></h2>
                </div>
                <div class="briefpapier" style="display: none;"></div>
                <?php if ($custId == 0) {?>
                <div id="invoice">
                    <div><span>#<?=str_pad($_POST['rid'], 8, '0', STR_PAD_LEFT)?></span> BONNUMMER</div>
                    <div><span><?=strftime("%d %B %Y %H:%M:%S", $time->getTimestamp()), PHP_EOL?></span> DATUM</div>
                </div>
                <div id="projectBig">
                    <div><span>KASSA</span> <?=$cashier?></div>
                    <div><span>MEDEWERKER</span> <?=$creator?></div>
                    <div><span>BETAALWIJZE</span> <?=$pMethod != "" ? $pMethod : "Nog niet betaald"?></div>
                </div>
                <?php } ?>
                <?php if ($custId != 0) {?>
                <div id="invoice">
                    <div><span>#<?=str_pad($_POST['rid'], 8, '0', STR_PAD_LEFT)?></span> BONNUMMER</div>
                    <div><span><?=strftime("%d %B %Y %H:%M:%S", $time->getTimestamp()), PHP_EOL?></span> DATUM</div>
                    <div><span><?=$cashier?></span> KASSA</div>
                    <div><span><?=$creator?></span> MEDEWERKER</div>
                    <div><span>
                        <?php 
                            if ($pMethod != "")
                                if ($pMethod == "CASH") { echo "Kontant"; } else if ($pMethod == "PIN") { echo 'Pin'; } else if ($pMethod == "BANK") { echo 'Bankoverdracht'; } else if ($pMethod == "iDeal") { echo 'iDeal'; } else if ($pMethod == "PC") { echo 'Pin en Kontant'; }
                            else 
                                echo "Nog niet betaald";
                        ?>
                    </span> BETAALWIJZE</div>
                </div>
                <div id="project">
                    <div><span>BEDRIJF</span> <?=Misc::sqlGet("companyName", "customers", "customerId", $custId)['companyName'] != ""? Misc::sqlGet("companyName", "customers", "customerId", $custId)['companyName']: "Particulier"?></div>
                    <div><span>KLANT</span> <?=Misc::sqlGet("initials", "customers", "customerId", $custId)['initials']?> <?=Misc::sqlGet("familyName", "customers", "customerId", $custId)['familyName']?></div>
                    <div><span>ADRES</span> <?=Misc::sqlGet("streetName", "customers", "customerId", $custId)['streetName']?>, <?=Misc::sqlGet("postalCode", "customers", "customerId", $custId)['postalCode']?> <?=Misc::sqlGet("city", "customers", "customerId", $custId)['city']?></div>
                    <div><span>EMAIL</span> <a href="mailto:<?=Misc::sqlGet("email", "customers", "customerId", $custId)['email']?>"><?=Misc::sqlGet("email", "customers", "customerId", $custId)['email']?></a></div>
                </div>
                <?php } ?>
            </header>
            <main>
                <table>
                    <thead>
                        <tr>
                            <th class="desc">OMSCRHIJVING</th>
                            <th></th>
                            <th>STUKPRIJS</th>
                            <th>AANTAL</th>
                            <th><?=$_POST['exvat'] ? "EXCL. ".$_CFG['VATText'] : "BEDRAG"?></th>
                            <?=$_POST['exvat'] ? "<th>INCL. ".$_CFG['VATText']."</th>" : ""?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalVat = 0;
                        $totalExVat = 0;
                        $totalIncVat = 0;

                        $json = json_decode(urldecode(Misc::sqlGet("items", "receipt", "receiptId", $_POST['rid'])['items']), true);
                        while ($val = current($json))
                        {
                            $total = Misc::calculate(round($json[key($json)]['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . " " . $json[key($json)]['priceAPiece']['priceModifier']);
                            $purchase = $json[key($json)]['priceAPiece']['priceExclVat'];
                            $vatOnly = (($json[key($json)]['priceAPiece']['priceExclVat'] * $_CFG['VAT']) - $json[key($json)]['priceAPiece']['priceExclVat']);
                            ?>
                            <tr>
                                <td class="service"><?php 
                                    if ($json[key($json)]['itemDesc'] == "")
                                        echo 'Tijdelijk Artikel (' . key($json) . ')';
                                    else
                                        echo $json[key($json)]['itemDesc'];
                                ?></td>
                                <td class="unit"></td>
                                <td class="unit"><?=$_CFG['CURRENCY']?>&nbsp;<?=$_POST['exvat'] ? number_format((round($total, 2))-(round($total, 2) * 0.21), 2, ",", "."): number_format((round($total, 2)), 2, ",", ".")?></td>
                                <td class="qty"><?=$json[key($json)]['count']?></td>
                                <td class="total"><?=$_CFG['CURRENCY']?>&nbsp;<?=$_POST['exvat'] ? number_format((round(round($total, 2) * $json[key($json)]['count'], 2))-(round(round($total, 2) * $json[key($json)]['count'], 2) * 0.21), 2, ",", ".") : number_format((round(round($total, 2) * $json[key($json)]['count'], 2)), 2, ",", ".")?></td>
                                <?php if ($_POST['exvat']) { ?><td class="total"><?=$_CFG['CURRENCY']?>&nbsp;<?=number_format((round(round($total, 2) * $json[key($json)]['count'], 2)), 2, ",", ".")?></td><?php } ?>
                            </tr>
                            <?php
                            $totalIncVat = $totalIncVat + round(round($total, 2) * $json[key($json)]['count'], 2);
                            next($json);
                        }
                        
                        $totalVat = $totalIncVat - ($totalIncVat / 1.21);
                        $totalExVat = $totalIncVat / 1.21;
                        ?>
                        <tr>
                            <?php if ($_POST['exvat']) { ?><td class="total"></td><?php } ?>
                            <td colspan="4" class="total">EXCL. <?=$_CFG['VATText']?></td>
                            <td class="total"><?=$_CFG['CURRENCY']?>&nbsp;<?=number_format(round($totalExVat, 2), 2, ",", ".")?></td>
                        </tr>
                        <tr>
                            <?php if ($_POST['exvat']) { ?><td></td><?php } ?>
                            <td colspan="4"><?=$_CFG['VATText']?> <?=$_CFG['VAT']*100-100?>%</td>
                            <td class="total"><?=$_CFG['CURRENCY']?>&nbsp;<?=number_format(round($totalVat, 2), 2, ",", ".")?></td>
                        </tr>
                        <tr>
                            <?php if ($_POST['exvat']) { ?><td class="grand total"></td><?php } ?>
                            <td colspan="4" class="grand total">EINDTOTAAL</td>
                            <td class="grand total"><?=$_CFG['CURRENCY']?>&nbsp;<?=number_format(round($totalIncVat, 2), 2, ",", ".")?></td>
                        </tr>
                    </tbody>
                </table>
                <?php if (isset($notice) && $notice != "") {?>
                <div id="notices">
                    <div>OPMERKING:</div>
                    <div class="notice">&emsp;<?=urldecode($notice)?></div>
                </div>
                <?php } ?>
            </main>
            <footer>
                <?=$_CFG['disclaimer']?>
            </footer>
        </body>
    </html>