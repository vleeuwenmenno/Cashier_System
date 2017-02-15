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
                    echo '        <td>&euro;&nbsp;' . number_format(Calculate::getReceiptTotal(Misc::sqlGet("items", "receipt", "receiptId", $row['receiptId'])['items'])['total'], 2, ',', '') . '</td>';
                    echo '<td>
                            <button id="viewReceipt' . $i . '" type="button" class="btn btn-info"><span class="glyphicon glyphicon-list"></span></button>
                            &nbsp;
                            <button id="loadReceipt' . $i . '" type="button" class="btn btn-primary" disabled readonly><span class="glyphicon glyphicon-upload"></span></button>';
                    if (Permissions::isManager($_SESSION['login_ok']['userId']))
                    {
                        echo '&nbsp;&nbsp;<button id="removeReceipt' . $i . '" type="button" class="btn btn-warning"><span class="glyphicon glyphicon-trash"></span></button>';
                    }
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
                        echo '        <td>&euro;&nbsp;' . number_format(Calculate::getReceiptTotal(Misc::sqlGet("items", "receipt", "receiptId", $row['receiptId'])['items'])['total'], 2, ',', '') . '</td>';
                        echo '<td>
                                <button id="viewReceipt' . $i . '" type="button" class="btn btn-info"><span class="glyphicon glyphicon-list"></span></button>
                                &nbsp;
                                <button id="loadReceipt' . $i . '" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-upload"></span></button>';
                        if (Permissions::isManager($_SESSION['login_ok']['userId']))
                        {
                            echo '&nbsp;&nbsp;<button id="removeReceipt' . $i . '" type="button" class="btn btn-warning"><span class="glyphicon glyphicon-trash"></span></button>';
                        }
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
