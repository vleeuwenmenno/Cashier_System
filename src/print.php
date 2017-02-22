<?php
include_once("includes.php");

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
        echo '<div style="margin-left: 12px; padding-top: 12px;" id="printPart">';
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
                    <td><?php echo urldecode(Misc::sqlGet("itemName", "items", "nativeId", $key)['itemName']); ?></td>
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
        <table style="float: right; font-size: 14px;">
            <tr style="font-size: larger;">
                <td style=" padding-bottom: 8px;">Excl. Btw: <div style="margin-left: 12px; font-size: 10px; float: right;">€ <?php echo str_replace(".", ",", round($totalIncl - ($totalIncl / 100 * 21), 2)); ?></div></td>
            </tr>
            <tr style="font-size: larger;">
                <td style=" padding-bottom: 8px;">Btw: <div style="margin-left: 12px; font-size: 10px; float: right;">€ <?php echo str_replace(".", ",", round($totalIncl / 100 * 21, 2)); ?></div></td>
            </tr>
            <tr style="font-size: larger;">
                <td style=" padding-bottom: 8px;">Totaal: <div style="margin-left: 12px; font-size: 10px; float: right;">€ <?php echo str_replace(".", ",", $totalIncl); ?></div></td>
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
