
<?php
    include_once("../includes.php");

    $custId = Misc::sqlGet("customerId", "receipt", "receiptId", $_POST['rid'])['customerId'];

    $time = new DateTime(Misc::sqlGet("createDt", "receipt", "receiptId", $_POST['rid'])['createDt']);
    $departure = new DateTime(Misc::sqlGet("departure", "receipt", "receiptId", $_POST['rid'])['departure']);
    $arrival = new DateTime(Misc::sqlGet("arrival", "receipt", "receiptId", $_POST['rid'])['arrival']);
    $roomNo = Misc::sqlGet("roomNo", "receipt", "receiptId", $_POST['rid'])['roomNo'];

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
            
            <link rel="stylesheet" href="../assets/style.css" media="all" />
            <link rel="stylesheet" href="style.css" media="all" />
        </head>
        <body>
            <header class="clearfix">
                <div>
                    <div id="logo">
                        <img src="../assets/logo.png">
                    </div>
                    <h1><?=$_CFG['companyAddress']?> &bull; <?=$_CFG['companyPhone']?> &bull; <?=$_CFG['companyWebsite']?> &bull; <?=$_CFG['companyEmail']?></h1>
                    <h2><?=$_CFG['companyKvk']?> &bull; <?=$_CFG['companyVATNo']?> &bull; <?=$_CFG['companyIBAN']?></h2>
                </div>
                <div class="briefpapier" style="display: none;"></div>
                <?php if ($custId == 0) {?>
                <div id="invoice">
                    <div><span><?=str_pad($_POST['rid'], 8, '0', STR_PAD_LEFT)?></span> FACTUUR NR.&nbsp;</div>
                    <div><span><?=strftime("%d %B %Y %H:%M:%S", $time->getTimestamp()), PHP_EOL?></span> DATUM</div>
                </div>
                <div id="projectBig">
                
                    <?php if (!$_CFG['showCustomerFieldsChk']) { ?>
                    <div><span>KASSA</span> <?=$cashier?></div>
                    <div><span>MEDEWERKER</span> <?=$creator?></div>
                    <?php } ?>

                    <div><span>BETAALWIJZE</span> <?=$pMethod != "" ? $pMethod : "Nog niet betaald"?></div>
                </div>
                <?php } ?>
                <?php if ($custId != 0) {?>
                <div id="invoice">
                    <div>FACTUURNUMMER:&nbsp;<span><?=str_pad($_POST['rid'], 8, '0', STR_PAD_LEFT)?></span></div>
                    <?php if (!$_CFG['showCustomerFieldsChk']) { ?>
                    <div>DATUM:&nbsp;<span><?=strftime("%d %B %Y %H:%M:%S", $time->getTimestamp()), PHP_EOL?></span> </div>
                    <?php } ?>
                    
                    <?php if (!$_CFG['showCustomerFieldsChk']) { ?>
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
                    <?php } ?>

                    <?php if ($_CFG['showCustomerFieldsChk']) { ?>
                    <div><span><?=strftime("%d %B %Y", $arrival->getTimestamp()), PHP_EOL?></span> AANKOMST</div>
                    <div><span><?=strftime("%d %B %Y", $departure->getTimestamp()), PHP_EOL?></span> VERTREK</div>
                    <div><span><?=$roomNo?></span> PLAATS NR.</div>
                    <?php } ?>
                </div>
                <div id="project">
                    <?php if (!$_CFG['showCustomerFieldsChk']) { ?>
                    <div><span>BEDRIJF</span> <?=Misc::sqlGet("companyName", "customers", "customerId", $custId)['companyName'] != ""? Misc::sqlGet("companyName", "customers", "customerId", $custId)['companyName']: "Particulier"?></div>
                    <?php } ?>

                    <div><span>KLANT</span> <?=Misc::sqlGet("initials", "customers", "customerId", $custId)['initials']?> <?=Misc::sqlGet("familyName", "customers", "customerId", $custId)['familyName']?></div>
                    <div><span>ADRES</span> <?=Misc::sqlGet("streetName", "customers", "customerId", $custId)['streetName']?>, <?=Misc::sqlGet("postalCode", "customers", "customerId", $custId)['postalCode']?> <?=Misc::sqlGet("city", "customers", "customerId", $custId)['city']?></div>
                    <div><span>EMAIL</span> <a href="mailto:<?=Misc::sqlGet("email", "customers", "customerId", $custId)['email']?>"><?=Misc::sqlGet("email", "customers", "customerId", $custId)['email']?></a></div>
                
                    <?php if ($_CFG['showCustomerFieldsChk']) { 
                        $no = Misc::sqlGet("mobileNumber", "customers", "customerId", $custId)['mobileNumber'];
                        
                        if ($no == "")
                            $no = Misc::sqlGet("phoneNumber", "customers", "customerId", $custId)['phoneNumber'];
                    ?>
                    <div><span>&nbsp;TEL.</span> <?=$no?></div>
                    <?php } ?>
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
                            <?php if ($_CFG['multiplierOnItemsChk']) { ?> 
                            <th>PERSONEN</th>
                            <?php } ?>
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
                            
                            if ($json[key($json)]['multiplier'] == 0)
                                $json[key($json)]['multiplier'] = 1;

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
                                <?php if ($_CFG['multiplierOnItemsChk']) { ?> 
                                <td class="qty"><?=$json[key($json)]['multiplier']?></td>
                                <?php } ?>
                                <td class="qty"><?=$json[key($json)]['count']?></td>
                                <td class="total"><?=$_CFG['CURRENCY']?>&nbsp;<?=$_POST['exvat'] ? number_format((round(round($total, 2) * $json[key($json)]['count'] * $json[key($json)]['multiplier'], 2))-(round(round($total, 2) * $json[key($json)]['count'] * $json[key($json)]['multiplier'], 2) * 0.21), 2, ",", ".") : number_format((round(round($total, 2) * $json[key($json)]['count'] * $json[key($json)]['multiplier'], 2)), 2, ",", ".")?></td>
                                <?php if ($_POST['exvat']) { ?><td class="total"><?=$_CFG['CURRENCY']?>&nbsp;<?=number_format((round(round($total, 2) * $json[key($json)]['count'], 2) * $json[key($json)]['multiplier']), 2, ",", ".")?></td><?php } ?>
                            </tr>
                            <?php
                            $totalIncVat = $totalIncVat + round(round($total, 2) * $json[key($json)]['count']  * $json[key($json)]['multiplier'], 2);
                            next($json);
                        }
                        
                        $totalVat = $totalIncVat - ($totalIncVat / $_CFG['VAT']);
                        $totalExVat = $totalIncVat / $_CFG['VAT'];
                        ?>
                        <tr>
                            <?php if ($_CFG['multiplierOnItemsChk']) { ?><td></td><?php } ?>
                            <?php if ($_POST['exvat']) { ?><td class="total"></td><?php } ?>
                            <td colspan="4" class="total">EXCL. <?=$_CFG['VATText']?></td>
                            <td class="total"><?=$_CFG['CURRENCY']?>&nbsp;<?=number_format(round($totalExVat, 2), 2, ",", ".")?></td>
                        </tr>
                        <tr>
                            <?php if ($_CFG['multiplierOnItemsChk']) { ?><td></td><?php } ?>
                            <?php if ($_POST['exvat']) { ?><td></td><?php } ?>
                            <td colspan="4"><?=$_CFG['VATText']?> <?=$_CFG['VAT']*100-100?>%</td>
                            <td class="total"><?=$_CFG['CURRENCY']?>&nbsp;<?=number_format(round($totalVat, 2), 2, ",", ".")?></td>
                        </tr>
                        <tr>
                            <?php if ($_CFG['multiplierOnItemsChk']) { ?><td class="grand total"></td><?php } ?>
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