<?php
include_once("../includes.php");

if (isset($_GET['sTerm']))
{
    if (isset($_GET['start']))
    {
        $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

        if($db->connect_errno > 0)
        {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        $sql = "SELECT * FROM receipt WHERE receiptId LIKE '%" . addslashes($_GET['sTerm']) . "%' OR paymentMethod LIKE '%" . addslashes($_GET['sTerm']) . "%' OR createDt LIKE '%" . addslashes($_GET['sTerm']) . "%' OR paidDt LIKE '%" . addslashes($_GET['sTerm']) . "%';";

        if(!$result = $db->query($sql))
        {
            die('There was an error running the query [' . $db->error . ']');
        }

        $i = 0;
        while($row = $result->fetch_assoc())
        {
                $i++;
                echo '    <tr id="' . $row['receiptId'] . '">';
                echo '        <td>' . $row['receiptId'] . '</td>';
                echo '        <td>' . Misc::sqlGet("paidDt", "receipt", "receiptId", $row['receiptId'])['paidDt'] . '</td>';
                echo '        <td>' . $row['receiptId'] . '</td>';
                echo Misc::sqlGet("items", "receipt", "receiptId", $row['receiptId']);
                echo '        <td>&euro;&nbsp;' . number_format(Calculate::getReceiptTotal(Misc::sqlGet("items", "receipt", "receiptId", $row['receiptId'])['items'])['total'], 2, ',', '') . '</td>';
                echo '    </tr>';
                echo '
                  <script>
                      $(document).ready(function ()
    					        {

    					        });
                  </script>';
        }

    }
}
