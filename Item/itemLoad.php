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

                echo '    <tr>';

                echo '            <td><a href="#" id="item' . $row['nativeId'] . 'Btn">' . $EAN . '</a></td>';

                echo '            <td>' . urldecode($row['itemName']) . '</td>';
                echo '            <td>' . $row['factoryId'] . '</td>';
                echo '            <td>' . $row['itemStock'] . '</td>';

                echo '            <td><span class="priceClickable" id="' . $row['nativeId'] . '" data-toggle="popover" title="Prijs berekening" data-content="'. $row['priceExclVat'] . '&nbsp;excl. ' . $row['priceModifier'] . ' = ' . str_replace(".", ",", round(Misc::calculate($row['priceExclVat'] . ' ' . str_replace(",", ".", $row['priceModifier'])), 2)) . '&nbsp;&euro;">' . str_replace(".", ",", round(Misc::calculate($row['priceExclVat'] . ' ' . str_replace(",", ".", $row['priceModifier'])), 2)) . ' &euro; </span></td>';

                if (isset($_SESSION['receipt']['status']) && $_SESSION['receipt']['status'] == "open")
                {
                    echo '            <td><button id="add' .  $row['nativeId'] . '" type="button" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span></button></td>';
                }

                echo '    </tr>';
                echo '    <script>';
                echo '    	$(document).ready(function ()
					    {';
                if (isset($_SESSION['receipt']['status']) && $_SESSION['receipt']['status'] == "open")
                {
                    echo '      $("#add' . $row['nativeId'] . '").on("click", function() {
                                $.get(
                                    "receipt/addItem.php",
                                    {
                                        itemId: \'' . $row['itemId'] . '\',
                                        itemCount: \'1\'
                                    },
                                    function (data)
                                    { }
                                );

                                $.notify({
                                    icon: \'glyphicon glyphicon-trash\',
                                    title: \'' . urldecode($row['itemName']) . '\',
                                    message: \'<br />Toegevoegt aan bon (<a href="#">Ongedaan maken</a>, <a href="#">Open bon</a>)\'
                                }, {
                                    // settings
                                    type: \'success\',
                                    delay: 5000,
                                    timer: 10,
                                    template:
                                                \'<div data-notify="container" role="alert" class="col-xs-11 col-sm-2 alert alert-{0}" style="margin: 15px 0 15px 0; width: 150px;">\
                                                    <button type="button" class="close" data-notify="dismiss" style="top:7px;">\
                                                        <span aria-hidden="true">×</span>\
                                                        <span class="sr-only">Close</span>\
                                                    </button>\
                                                    <span data-notify="icon"></span>\
                                                    <span data-notify="title">{1}</span>\
                                                    <span data-notify="message" style="padding-right:15px">{2}</span>\
                                                    <a href="{3}" target="{4}" data-notify="url"></a>\
                                                </div>\',
                                    placement: {
                                        from: "bottom",
                                        align: "right"
                                    },
                                    onClosed: function () {
                                        //TODO: Send delete to SQL
                                    }
                                });
                            });';
                }

                echo           '$( "#' . $row['nativeId'] . '" ).hover(function() {
                                $(\'#' . $row['itemId'] . '\').popover(\'show\');
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