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

        $sql = "SELECT * FROM receipt WHERE receiptId LIKE '%" . addslashes($_GET['sTerm']) . "%' OR paymentMethod LIKE '%" . addslashes($_GET['sTerm']) . "%' OR createDt LIKE '%" . addslashes($_GET['sTerm']) . "%' OR paidDt LIKE '%" . addslashes($_GET['sTerm']) . "% ';";

        if(!$result = $db->query($sql))
        {
            die('There was an error running the query [' . $db->error . ']');
        }

        $items = array();
        $i = 0;
        while($row = $result->fetch_assoc())
        {
            $items[$row['receiptId']] = $row;
        }

        krsort ($items, SORT_NUMERIC);

        foreach ($items as $key => $row)
        {
            $i++;

            $obj = json_decode($row['items'], TRUE);
            if (true)
            {
                if ($row['paymentMethod'] != "")
                {
                    echo '    <tr id="' . $row['receiptId'] . '">';
                    echo '        <td>' . $row['receiptId'] . '</td>';
                    echo '        <td>' . Misc::sqlGet("paidDt", "receipt", "receiptId", $row['receiptId'])['paidDt'] . '</td>';
                    echo '        <td>' . $row['receiptId'] . '</td>';
                    echo Misc::sqlGet("items", "receipt", "receiptId", $row['receiptId']);
                    echo '        <td>&euro;&nbsp;' . number_format(round(Calculate::getReceiptTotal(Misc::sqlGet("items", "receipt", "receiptId", $row['receiptId'])['items'])['total'], 2), 2, ",", ".") . '</td>';
                    echo '<td>';
                    if ($row['paymentMethod'] == "CASH") { echo "Kontant"; } else if ($row['paymentMethod'] == "PIN") { echo 'Pin'; } else if ($row['paymentMethod'] == "BANK") { echo 'Bankoverdracht'; } else if ($row['paymentMethod'] == "PC") { echo 'Pin en Kontant'; }
                    echo '</td>';
                    echo '<td>
                            <button id="viewReceipt' . $i . '" type="button" class="btn btn-info"><i class="fa fa-folder-open-o" aria-hidden="true"></i></button>
                            &nbsp;
                            <button id="loadReceipt' . $i . '" type="button" class="btn btn-primary" disabled readonly><i class="fa fa-download" aria-hidden="true"></i></button>';
                    echo '</td>';
                    echo '    </tr>';
                    echo '
                      <script>
                        $(document).ready(function ()
    			        {
                            $("#viewReceipt' . $i . '").click(function() {
                                $("#pageLoaderIndicator").fadeIn();
                                $("#PageContent").load("receipt/viewReceipt.php?receipt=' . $row['receiptId'] . '", function () {
                                    $("#pageLoaderIndicator").fadeOut();
                                });
                            });
    			        });
                      </script>';
                }
                else
                {
                    if ($row['items'] != "")
                    {
                        echo '    <tr id="' . $row['receiptId'] . '">';
                        echo '        <td>' . $row['receiptId'] . '</td>';
                        echo '        <td>' . Misc::sqlGet("createDt", "receipt", "receiptId", $row['receiptId'])['createDt'] . '</td>';
                        echo '        <td>' . $row['receiptId'] . '</td>';
                        echo Misc::sqlGet("items", "receipt", "receiptId", $row['receiptId']);
                        echo '        <td>&euro;&nbsp;' . number_format(round(Calculate::getReceiptTotal(Misc::sqlGet("items", "receipt", "receiptId", $row['receiptId'])['items'])['total'], 2), 2, ",", ".") . '</td>';
                        echo '<td>N.V.T</td>';
                        echo '<td>
                                <button id="viewReceipt' . $i . '" type="button" class="btn btn-info"><i class="fa fa-folder-open-o" aria-hidden="true"></i></span></button>
                                &nbsp;
                                <button id="loadReceipt' . $i . '" type="button" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i></button>';
                        echo '</td>';
                        echo '    </tr>';
                        echo '
                          <script>
                            $(document).ready(function ()
                            {
                                $("#viewReceipt' . $i . '").click(function() {
                                    $("#pageLoaderIndicator").fadeIn();
                                    $("#PageContent").load("receipt/viewReceipt.php?receipt=' . $row['receiptId'] . '", function () {
                                        $("#pageLoaderIndicator").fadeOut();
                                    });
                                });
                            });
                          </script>';
                      }
                }
            }
        }
    }
}
