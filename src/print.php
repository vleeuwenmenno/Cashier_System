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
        // This is 2/3 of a A4 paper size
        //height: 19.8cm;
        //width: 14cm;

        echo '<br /><br />
        <div class="printExample">';
        echo '<div style="margin-left: 12px; padding-top: 12px;" id="printPart">';
            echo Misc::sqlGet("crName", "cash_registers", "id", $row['cashRegisterId'])['crName'] . ' geopend op ' .  $row['openDate'];
            echo '<br /><br />Medewerker: ' . $_SESSION['login_ok']['nickName'];
            echo '<br />Kas-in: &euro; ' . $row['cashIn'];
        echo '</div>';
        echo '</div>';
        echo '<center><button id="printAgain" type="button" class="btn btn-default">Nogmaals Afdrukken</button></center>';
        echo '<script>';
        echo '
            function PrintElem(elem)
            {
                var mywindow = window.open(\'\', \'PRINT\', \'height=400,width=600\');

                mywindow.document.write(\'<html><head><title>\' + document.title  + \'</title>\');

                mywindow.document.write(\'</head><body >\');

                mywindow.document.write(document.getElementById(elem).innerHTML);
                mywindow.document.write(\'</body></html>\');

                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10*/

                mywindow.print();
                mywindow.close();

                return true;
            }

            $(document).ready(function() {
                PrintElem("printPart");

                $("#printAgain").on("click", function() {
                    PrintElem("printPart");
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
    // This is 2/3 of a A4 paper size
    //height: 19.8cm;
    //width: 14cm;

    echo '<br /><br />
    <div style="background: white;
                display: block;
                margin: 0 auto;
                margin-bottom: 0.5cm;
                box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
                width: 14cm;
                height: 19.8cm;">';
    echo '<div style="margin-left: 12px; padding-top: 12px;">';
        //GET RECEIPT FROM MYSQL AND SHOW IT HERE!
    echo 'Bon<span id="barcodeEan"></span>';
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
    </script>';
    echo '</div>';
    echo '</div>';
    echo '<center><button id="printAgain" type="button" class="btn btn-default">';
    if ($_GET['print'] > 0)
    {
        echo 'Nogmaals ';
    }
    echo 'Afdrukken</button></center>';
}
