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
            echo Misc::sqlGet("crName", "cash_registers", "id", $row['cashRegisterId'])['crName'] . ' geopend op ' .  $row['openDate'];
            echo '<br /><br />Medewerker: ' . $_SESSION['login_ok']['nickName'];
            echo '<br />Kas-in: &euro; ' . $row['cashIn'];
        echo '</div>';
        echo '<center><button id="printAgain" type="button" class="btn btn-default">Nogmaals Afdrukken</button></center>';
        echo '<script>';
        echo '
            $(document).ready(function() {
                $("#printAgain").css("display", "none");
                window.print();
                $("#printAgain").css("display", "inline");

                $("#printAgain").on("click", function() {
                    $("#printAgain").css("display", "none");
                    window.print();
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
    //TODO: GET RECEIPT FROM MYSQL AND SHOW IT HERE!
    echo 'Bon<span id="barcodeEan"></span>';
    echo '</div>';
    echo '<center><button id="printAgain" type="button" class="btn btn-default">';
    if ($_GET['print'] > 0)
    {
        echo 'Nogmaals ';
    }
    echo 'Afdrukken</button></center>';
    echo '<script>
        $.get(
            "barcode/getBarcode.php",
            {
                EAN: "' . $_GET['receipt'] . '"
            },
            function (data)
            {
                $("#barcodeEan").html(data);
            }
        );

        $(document).ready(function() {
            $("#printAgain").css("display", "none");
            window.print();
            $("#printAgain").css("display", "inline");

            $("#printAgain").on("click", function() {
                $("#printAgain").css("display", "none");
                window.print();
                $("#printAgain").css("display", "inline");
            });
        });
    ';
    echo '</script>';
}
