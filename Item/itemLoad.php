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

        $sql = "SELECT * FROM items WHERE 1;";

        if(!$result = $db->query($sql))
        {
            die('There was an error running the query [' . $db->error . ']');
        }

        $i = 0;
        while($row = $result->fetch_assoc())
        {
            $i++;

            $matchResult = false;

            if ($_GET['sTerm'] != "")
            {
                if (strpos($row['EAN'], $_GET['sTerm']) !== false)
                    $matchResult = true;
                if (strpos(urldecode($row['itemName']), $_GET['sTerm']) !== false)
                    $matchResult = true;
                if (strpos($row['factoryId'], $_GET['sTerm']) !== false)
                    $matchResult = true;
                if (strpos($row['itemCategory'], $_GET['sTerm']) !== false)
                    $matchResult = true;
                if (strpos($row['itemId'], $_GET['sTerm']) !== false)
                    $matchResult = true;
            }
            else
                $matchResult = true;

            if ($i >= $_GET['start'] && $i < ($_GET['start'] + $_GET['count']) || $matchResult)
            {
                if ($matchResult)
                {
                    if ($row['EAN'] == "")
                        $EAN = "Geen EAN gevonden";
                    else
                        $EAN = $row['EAN'];

                    echo '    <tr>';
                    echo '            <td><a href="#" id="item' . $row['itemId'] . 'Btn">' . $EAN . '</a></td>';
                    echo '            <td>' . urldecode($row['itemName']) . '</td>';
                    echo '            <td>' . $row['factoryId'] . '</td>';
                    echo '            <td>' . $row['itemStock'] . '</td>';
                    echo '            <td>' . str_replace(".", ",", round(Misc::calculate($row['priceExclVat'] . ' ' . str_replace(",", ".", $row['priceModifier'])), 2)) . ' &euro; </td>'; //TODO: Maybe make a tooltip here and show the calculation and a button to change the modifier
                    echo '            <td><button type="button" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span></button></td>';
                    echo '    </tr>';
                    echo '    <script>';
                    echo '    	$(document).ready(function ()
					                        {
						                        $("#item' . $row['itemId'] . 'Btn").on("click", function () {
                                                    $("#loaderAnimation").fadeIn();
                                                    $("#PageContent").load("item/viewItem.php?id=' . $row['itemId'] . '");
						                        });
					                        });';
                    echo '    </script>';
                }
            }
        }

    }
}