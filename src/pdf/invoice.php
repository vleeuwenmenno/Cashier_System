<?php
    include_once("../includes.php");

    $custId = Misc::sqlGet("customerId", "contract", "contractId", $_POST['cid'])['customerId'];
    $dd = Misc::sqlGet("directDebit", "contract", "contractId", $_POST['cid'])['directDebit'];
    $planningPeriod = Misc::sqlGet("planningPeriod", "contract", "contractId", $_POST['cid'])['planningPeriod'];

    $time = new DateTime(Misc::sqlGet("orderDate", "log", "logId", $_POST['lid'])['orderDate']);
    $expireTime = new DateTime($time->format("Y-m-d"));
    $expireTime = $expireTime->modify("+ ".$_CFG['invoiceExpireDays']." days");

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
                <div id="invoice">
                    <div><span>#<?=str_pad($_POST['lid'], 8, '0', STR_PAD_LEFT)?></span> FACTUURNUMMER</div>
                    <div><span><?=strftime("%d %B %Y", $time->getTimestamp()), PHP_EOL?></span> DATUM</div>
                    <?php if ($dd == 0) { ?><div><span><?=strftime("%d %B %Y", $expireTime->getTimestamp()), PHP_EOL?></span> VERVALDATUM</div><?php } ?>
                </div>
                <div id="project">
                    <?php if (!$_CFG['showCustomerFieldsChk']) { ?>
                    <div><span>BEDRIJF</span> <?=Misc::sqlGet("companyName", "customers", "customerId", $custId)['companyName'] != ""? Misc::sqlGet("companyName", "customers", "customerId", $custId)['companyName']: "Particulier"?></div>
                    <?php } ?>
                    
                    <div><span>KLANT</span> <?=Misc::sqlGet("initials", "customers", "customerId", $custId)['initials']?> <?=Misc::sqlGet("familyName", "customers", "customerId", $custId)['familyName']?></div>
                    <div><span>ADRES</span> <?=Misc::sqlGet("streetName", "customers", "customerId", $custId)['streetName']?>, <?=Misc::sqlGet("postalCode", "customers", "customerId", $custId)['postalCode']?> <?=Misc::sqlGet("city", "customers", "customerId", $custId)['city']?></div>
                    <div><span>EMAIL</span> <a href="mailto:<?=Misc::sqlGet("email", "customers", "customerId", $custId)['email']?>"><?=Misc::sqlGet("email", "customers", "customerId", $custId)['email']?></a></div>
                </div>
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

                        $json = json_decode(urldecode(Misc::sqlGet("items", "log", "logId", $_POST['lid'])['items']), true);
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
                                <?php if ($_CFG['multiplierOnItemsChk']) { ?> 
                                <td class="qty"><?=$json[key($json)]['multiplier']?></td>
                                <?php } ?>
                                <td class="qty"><?=$json[key($json)]['count']?></td>
                                <td class="total"><?=$_CFG['CURRENCY']?>&nbsp;<?=$_POST['exvat'] ? number_format((round(round($total, 2) * $json[key($json)]['count'], 2))-(round(round($total, 2) * $json[key($json)]['count'], 2) * 0.21), 2, ",", ".") : number_format((round(round($total, 2) * $json[key($json)]['count'], 2)), 2, ",", ".")?></td>
                                <?php if ($_POST['exvat']) { ?><td class="total"><?=$_CFG['CURRENCY']?>&nbsp;<?=number_format((round(round($total, 2) * $json[key($json)]['count'], 2)), 2, ",", ".")?></td><?php } ?>
                            </tr>
                            <?php
                            $totalIncVat = $totalIncVat + round(round($total, 2) * $json[key($json)]['count'], 2);
                            next($json);
                        }
                        
                        $totalVat = $totalIncVat - ($totalIncVat / $_CFG['VAT']);
                        $totalExVat = $totalIncVat / $_CFG['VAT'];
                        ?>
                        <tr>
                            <td colspan="4"></td>
                            <?php if ($_POST['exvat']) { ?><td class="total"></td><?php } ?>
                            <td colspan="4" class="total">EXCL. <?=$_CFG['VATText']?></td>
                            <td class="total"><?=$_CFG['CURRENCY']?>&nbsp;<?=number_format(round($totalExVat, 2), 2, ",", ".")?></td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <?php if ($_POST['exvat']) { ?><td></td><?php } ?>
                            <td colspan="4"><?=$_CFG['VATText']?> <?=$_CFG['VAT']*100-100?>%</td>
                            <td class="total"><?=$_CFG['CURRENCY']?>&nbsp;<?=number_format(round($totalVat, 2), 2, ",", ".")?></td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <?php if ($_POST['exvat']) { ?><td class="grand total"></td><?php } ?>
                            <td colspan="4" class="grand total">EINDTOTAAL</td>
                            <td class="grand total"><?=$_CFG['CURRENCY']?>&nbsp;<?=number_format(round($totalIncVat, 2), 2, ",", ".")?></td>
                        </tr>
                    </tbody>
                </table>
                <?php if ($_POST['notice'] != "" || $dd == 1) {?>
                <div id="notices">
                    <div>OPMERKING:</div>
                    <div class="notice">&emsp;<?=urldecode($_SESSION['pdf']['notice'])?></div>

                    <?php 
                        if ($planningPeriod == "month")
                            $when = "maandelijks";
                        else if ($planningPeriod == "quarter")
                            $when = "per kwartaal";
                        else if ($planningPeriod == "year")
                            $when = "jaarlijks";

                        if ($dd == 1) {
                    ?>
                    <div class="notice"><span style="color: red;">LET OP! Deze factuur wordt automatisch geïncasseerd, dit gebeurt <?=$when?>. U hoeft deze nota niet handmatig te betalen.</span></div>
                    <?php } ?>
                </div>
                <?php } ?>
            </main>
            <footer>
                <?=$_CFG['disclaimer']?>
            </footer>
        </body>
    </html>
    