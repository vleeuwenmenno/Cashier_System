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

        $sql = "SELECT * FROM items WHERE EAN LIKE '%" . addslashes($_GET['sTerm']) . "%' OR itemName LIKE '%" . addslashes($_GET['sTerm']) . "%' OR factoryId LIKE '%" . addslashes($_GET['sTerm']) . "%' OR itemId LIKE '%" . addslashes($_GET['sTerm']) . "%';";

        if(!$result = $db->query($sql))
        {
            die('There was an error running the query [' . $db->error . ']');
        }

        $i = 0;
        while($row = $result->fetch_assoc())
        {
            $i++;
            if ($i >= $_GET['start'] && $i < ($_GET['start'] + $_GET['count']))
            {
                if ($row['EAN'] == "")
                    $EAN = "Geen EAN gevonden";
                else
                    $EAN = $row['EAN'];

                echo '    <tr id="' . $row['nativeId'] . '">';

                echo '            <td><a href="#" id="item' . $row['nativeId'] . 'Btn">' . $EAN . '</a></td>';
                echo '            <td>' . $row['supplier'] . '</td>';

                echo '            <td>' . urldecode($row['itemName']) . '</td>';
                echo '            <td>' . $row['nativeId'] . '</td>';

                if ($row['itemStock'] == 2147483647)
                    echo '            <td><span style="font-size: 24px;">' . "&infin;" . '</span></td>';
                else
                    echo '            <td>' . $row['itemStock'] . '</td>';

                $total = Misc::calculate(number_format($row['priceExclVat'] * $_CFG['VAT'], 2, '.', '') . " " . str_replace(',', '.', $row['priceModifier']));
                $purchase = $row['priceExclVat'];
                $vatOnly = number_format($row['priceExclVat'] * $_CFG['VAT'], 2) - $row['priceExclVat'];

                echo '    <td><span class="priceClickable" id="popOver' . $row['nativeId'] . '" data-placement="bottom" data-trigger="hover">';
                echo '        <a>';
                echo '            &euro;&nbsp;' . number_format($total, 2, ',', '') . '</a>';
                echo '        </span>';
                echo '        <div id="popover-title' . $row['nativeId'] . '" class="hidden">';
                echo '            <b>Prijs berekening</b>';
                echo '        </div>';
                echo '        <div id="popover-content' . $row['nativeId'] . '" class="hidden">';
                echo '            <div>';
                echo '            Inkoop: &euro;&nbsp;' . number_format($purchase, 2, ',', '') . '<br/>
                                  Btw. : &nbsp;&nbsp;&nbsp;&euro;&nbsp;' . number_format($vatOnly, 2, ',', '') . '<br />
                                  Marge: &euro;&nbsp;' . number_format($total - (number_format($purchase, 2) + number_format($vatOnly, 2)), 2, ',', '') . '<br />
                                  P.S: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&euro;&nbsp; ' . number_format($total, 2, ',', '') . '<br />';
                echo '            </div>';
                echo '        </div>';
                echo '    </td>';

                if (isset($_SESSION['receipt']['status']) && $_SESSION['receipt']['status'] == "open")
                {
                    echo '            <td><button id="return' .  $row['nativeId'] . '" type="button" class="btn btn-warning"><span class="glyphicon glyphicon-hand-left"></span></button></td>';

                    if ($row['itemStock'] == "0")
                        echo '            <td><button id="add' .  $row['nativeId'] . 'Warn" type="button" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span></button></td>';
                    else
                        echo '            <td><button id="add' .  $row['nativeId'] . '" type="button" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span></button></td>';
                }
                echo '    </tr>';
                echo '    <script>';
                echo '    	$(document).ready(function ()
					    {';
                if (isset($_SESSION['receipt']['status']) && $_SESSION['receipt']['status'] == "open")
                {
                    if ($row['itemStock'] == "0")
                    {
                        echo '
                        $("#add' . $row['nativeId'] . 'Warn").on("click", function() {
                            $("#stockWarningFooter").html(\'<button type="button" class="btn btn-warning" id="add' .  $row['nativeId'] . '" data-dismiss="modal">Doorgaan</button><button type="button" class="btn btn-info" id="stockWarning.cancelBtn" data-dismiss="modal">Annuleren</button>\');
                            $("#stockWarning").modal("show");
                            $("#add' . $row['nativeId'] . '").on("click", function() {
                                        $.get(
                                            "receipt/addItem.php",
                                            {
                                                itemId: \'' . $row['nativeId'] . '\',
                                                itemCount: \'1\'
                                            },
                                            function (data)
                                            { }
                                        );

                                        $.notify({
                                            icon: \'glyphicon glyphicon-trash\',
                                            title: \'' . urldecode($row['itemName']) . '\',
                                            message: \'<br />Toegevoegt aan bon.\'
                                        }, {
                                            // settings
                                            type: \'success\',
                                            delay: 5000,
                                            timer: 10,
                                            placement: {
                                                from: "bottom",
                                                align: "right"
                                            }
                                        });
                                    });
                        });';
                    }
                    else
                    {
                        echo ' $("#add' . $row['nativeId'] . '").on("click", function() {
                                    $.get(
                                        "receipt/addItem.php",
                                        {
                                            itemId: \'' . $row['nativeId'] . '\',
                                            itemCount: \'1\'
                                        },
                                        function (data)
                                        { }
                                    );

                                    $.notify({
                                        icon: \'glyphicon glyphicon-trash\',
                                        title: \'' . urldecode($row['itemName']) . '\',
                                        message: \'<br />Toegevoegt aan bon.\'
                                    }, {
                                        // settings
                                        type: \'success\',
                                        delay: 5000,
                                        timer: 10,
                                        placement: {
                                            from: "bottom",
                                            align: "right"
                                        }
                                    });
                                });';
                    }
                }

                echo       '$("#popOver' . $row['nativeId'] . '").popover({
                                html : true,
                                content: function() {
                                  return $("#popover-content' . $row['nativeId'] . '").html();
                                },
                                title: function() {
                                  return $("#popover-title' . $row['nativeId'] . '").html();
                                }
                            });

                            $("#item' . $row['nativeId'] . 'Btn").on("click", function () {
                            $("#loaderAnimation").fadeIn();
                            $("#PageContent").load("item/viewItem.php?id=' . $row['nativeId'] . '");

					        });
                        });
                    </script>';
            }
        }
    }
}
