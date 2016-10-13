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

                if ($row['EAN'] != "")
                    echo '            <td><a href="#" id="item' . $EAN . 'Btn">' . $EAN . '</a></td>';
                else
                    echo '            <td><a href="#" id="item' . $row['itemId'] . 'Btn">' . $EAN . '</a></td>';

                echo '            <td>' . urldecode($row['itemName']) . '</td>';
                echo '            <td>' . $row['factoryId'] . '</td>';
                echo '            <td>' . $row['itemStock'] . '</td>';

                if ($row['EAN'] != "")
                    echo '            <td><span class="priceClickable" id="' . $row['EAN'] . '" data-toggle="popover" title="Prijs berekening" data-content="'. $row['priceExclVat'] . '&nbsp;excl. ' . $row['priceModifier'] . ' = ' . str_replace(".", ",", round(Misc::calculate($row['priceExclVat'] . ' ' . str_replace(",", ".", $row['priceModifier'])), 2)) . '&nbsp;&euro;">' . str_replace(".", ",", round(Misc::calculate($row['priceExclVat'] . ' ' . str_replace(",", ".", $row['priceModifier'])), 2)) . ' &euro; </span></td>';
                else
                    echo '            <td><span class="priceClickable" id="' . $row['itemId'] . '" data-toggle="popover" title="Prijs berekening" data-content="'. $row['priceExclVat'] . '&nbsp;excl. ' . $row['priceModifier'] . ' = ' . str_replace(".", ",", round(Misc::calculate($row['priceExclVat'] . ' ' . str_replace(",", ".", $row['priceModifier'])), 2)) . '&nbsp;&euro;">' . str_replace(".", ",", round(Misc::calculate($row['priceExclVat'] . ' ' . str_replace(",", ".", $row['priceModifier'])), 2)) . ' &euro; </span></td>';

                echo '            <td><button type="button" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span></button></td>';
                echo '    </tr>';
                echo '    <script>';
                echo '    	$(document).ready(function ()
					                    {
                                                    ';
                if ($row['EAN'] != "")
                    echo                           '$( "#' . $row['EAN'] . '" ).hover(function() {
                                                    $(\'#' . $row['EAN'] . '\').popover(\'show\');
                                                });

                                                $( "#' . $row['EAN'] . '" ).mouseout(function() {
                                                    $(\'#' . $row['EAN'] . '\').popover(\'hide\');
                                                });';
                else
                    echo                           '$( "#' . $row['itemId'] . '" ).hover(function() {
                                                    $(\'#' . $row['itemId'] . '\').popover(\'show\');
                                                });

                                                $( "#' . $row['itemId'] . '" ).mouseout(function() {
                                                    $(\'#' . $row['itemId'] . '\').popover(\'hide\');
                                                });';

                if ($row['EAN'] != "")
                    echo			                    '$("#item' . $row['EAN'] . 'Btn").on("click", function () {';
                else
                    echo			                    '$("#item' . $row['itemId'] . 'Btn").on("click", function () {';
                echo                                '$("#loaderAnimation").fadeIn();';
                if ($row['EAN'] != "")
                    echo                           '$("#PageContent").load("item/viewItem.php?id=' . $row['EAN'] . '");';
                else
                    echo                           '$("#PageContent").load("item/viewItem.php?id=' . $row['itemId'] . '");';

                echo                        '});
					                    });';
                echo '    </script>';
            }
        }

    }
}