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

        $sql = "SELECT * FROM items WHERE EAN LIKE '%" . $_GET['sTerm'] . "%' OR itemName LIKE '%" . $_GET['sTerm'] . "%' OR factoryId LIKE '%" . $_GET['sTerm'] . "%' OR itemId LIKE '%" . $_GET['sTerm'] . "%';";

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

                echo '            <td>' . urldecode($row['itemName']) . '</td>';
                echo '            <td>' . $row['nativeId'] . '</td>';
                echo '            <td>' . $row['itemStock'] . '</td>';

                echo '            <td><span class="priceClickable" id="' . $row['nativeId'] . '" data-toggle="popover" title="Prijs berekening" data-content="'. $row['priceExclVat'] . '&nbsp;excl. ' . $row['priceModifier'] . ' = ' . round(Misc::calculate($row['priceExclVat'] . ' ' . $row['priceModifier']), 2) . '&nbsp;&euro;">'
                . '&euro;&nbsp;' . round(Misc::calculate($row['priceExclVat'] . ' ' . $row['priceModifier']), 2) . '</span></td>';

                if (isset($_SESSION['receipt']['status']) && $_SESSION['receipt']['status'] == "open")
                {
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

                echo       '$( "#' . $row['nativeId'] . '" ).hover(function() {
                                $(\'#' . $row['nativeId'] . '\').popover(\'show\');
                            });

                            $( "#' . $row['nativeId'] . '" ).mouseout(function() {
                                $(\'#' . $row['nativeId'] . '\').popover(\'hide\');
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
