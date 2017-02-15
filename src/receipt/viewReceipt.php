<?php
    include_once("../includes.php");

    Permissions::checkSession(basename($_SERVER['REQUEST_URI']));
?>
<html>
    <head>
        <script src="js/sidebar.js"></script>

        <!-- Bootstrap and all it's dependencies -->
        <?php
        if ($_CFG['THEME'] == "")
            $_CFG['THEME'] = 'Default';
        ?>
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/bootstrap.css" />
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/stylesheet.css">
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/select2.min.css" />
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/bootstrap-combobox.css" />

        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-notify.min.js"></script>
        <script src="js/select2.full.min.js"></script>
        <script src="js/jqeury.jeditable.js"></script>
        <script src="js/bootstrap-combobox.js"></script>
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
	line-height: 20px;">
            <div style="margin-left: 48px;margin-top: 160px;">
                Bon Nr. <?php echo $_GET['receipt']; ?><br />
                Tijd/Datum: <?php echo $receipt['paidDt']; ?><br />
                Kassa: <?php echo Misc::sqlGet("crName", "cash_registers", "id", Misc::sqlGet("cashRegisterId", "cashsession", "cashSessionId", $receipt['parentSession'])['cashRegisterId'])['crName']; ?><br />
                Medewerker: <?php echo Misc::sqlGet("nickname", "users", "userId", $receipt['creator'])['nickname']; ?><br />
                Betaalwijze: <?php if ($receipt['paymentMethod'] == "CASH") { echo "Kontant"; } else if ($receipt['paymentMethod'] == "PIN") { echo 'Pin'; } else if ($receipt['paymentMethod'] == "BANK") { echo 'Bankoverdracht'; } else if ($receipt['paymentMethod'] == "PC") { echo 'Pin en Kontant'; } ?><br />
            </div>

            <table class="table" style="
    margin-left: 48px;
    margin-right: 48px;
    margin-top: 32px;">
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
                            <td><?php echo urldecode(Misc::sqlGet("itemName", "items", "nativeId", $key)['itemName']); ?></td>
                            <td>€ <?php echo str_replace(".", ",", Misc::calculate('(' . $val['priceAPiece']['priceExclVat'] . ' * ' . $_CFG['VAT'] . ')' . $val['priceAPiece']['priceModifier'])); ?></td>
                            <td><?php echo $val['count']; ?>x</td>
                            <td>€ <?php echo str_replace(".", ",", (Misc::calculate('(' . $val['priceAPiece']['priceExclVat'] . ' * ' . $_CFG['VAT'] . ')' . $val['priceAPiece']['priceModifier']) * $val['count'])); ?></td>
                        </tr>
                        <?php

                        $totalIncl += (Misc::calculate('(' . $val['priceAPiece']['priceExclVat'] . ' * ' . $_CFG['VAT'] . ')' . $val['priceAPiece']['priceModifier']) * $val['count']);
                    }
                ?>
            </table>
            <div style="float: right; font-size: 14px;">
                <table>
                    <tr style="font-size: larger;">
                        <td style=" padding-bottom: 8px;">Excl. Btw: <div style="margin-left: 12px; font-size: small; float: right;">€ <?php echo str_replace(".", ",", round($totalIncl - ($totalIncl / 100 * 21), 2)); ?></div></td>
                    </tr>
                    <tr style="font-size: larger;">
                        <td style=" padding-bottom: 8px;">Btw: <div style="margin-left: 12px; font-size: small; float: right;">€ <?php echo str_replace(".", ",", round($totalIncl / 100 * 21, 2)); ?></div></td>
                    </tr>
                    <tr style="font-size: larger;">
                        <td style=" padding-bottom: 8px;">Totaal: <div style="margin-left: 12px; font-size: small; float: right;">€ <?php echo str_replace(".", ",", $totalIncl); ?></div></td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>
