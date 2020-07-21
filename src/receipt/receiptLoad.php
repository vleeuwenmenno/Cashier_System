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
            if ($row['items'] != "")
            {
                ?>
                <tr id="<?=$row['receiptId']?>">
                    <td><?=$row['receiptId']?></td>
                    <td><?=Misc::sqlGet("createDt", "receipt", "receiptId", $row['receiptId'])['createDt']?></td>
                    <td><?=$_CFG['CURRENCY']?>&nbsp;<?=number_format(round(Calculate::getReceiptTotal(Misc::sqlGet("items", "receipt", "receiptId", $row['receiptId'])['items'])['total'], 2), 2, ",", ".")?></td>
                    
                    <?php if ($row['paymentMethod'] != "") { ?>
                        <td><?php if ($row['paymentMethod'] == "CASH") { echo "Kontant"; } else if ($row['paymentMethod'] == "PIN") { echo 'Pin'; } else if ($row['paymentMethod'] == "BANK") { echo 'Bankoverdracht'; } else if ($row['paymentMethod'] == "iDeal") { echo 'iDeal'; } else if ($row['paymentMethod'] == "PC") { echo 'Pin en Kontant'; } ?></td>
                    <?php } ?>
                    <?php if ($row['paymentMethod'] == "") { ?><td>N.V.T</td><?php } ?>
                    
                    <td>
                        <a id="viewReceipt<?=$i?>" href="#">PDF Openen</a>
                        <?php if ($row['paymentMethod'] == "") { ?>
                            &nbsp;<a id="loadReceipt<?=$i?>" href="#">/ Bon laden</a>
                        <?php } ?>
                    </td>
                </tr>
                <script>
                    $(document).ready(function ()
                    {
                        $("#viewReceipt<?=$i?>").click(function() {
                            $("#pageLoaderIndicator").fadeIn();
                            $("#PageContent").load("pdf/pdf.php?rid=<?=$row['receiptId']?>", function () {
                                $("#pageLoaderIndicator").fadeOut();
                            });
                        });
                        
                        <?php if ($row['paymentMethod'] == "") { ?>
                        $("#loadReceipt<?=$i?>").click(function() {
                            $("#pageLoaderIndicator").fadeIn();
                            $("#PageContent").load("receipt/loadReceipt.php?receipt=<?=$row['receiptId']?>", function () {
                                $("#pageLoaderIndicator").fadeOut();
                            });
                        });
                        <?php } ?>
                    });
                </script>
                <?php
            }
        }
    }
}
