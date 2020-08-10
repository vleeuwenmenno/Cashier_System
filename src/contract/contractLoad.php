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

        $sql = "SELECT * FROM contract WHERE 1;";

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
                if (strpos($row['initials'], $_GET['sTerm']) !== false)
                    $matchResult = true;
                if (strpos($row['familyName'], $_GET['sTerm']) !== false)
                    $matchResult = true;
                if (strpos($row['companyName'], $_GET['sTerm']) !== false)
                    $matchResult = true;
                if (strpos($row['postalCode'], $_GET['sTerm']) !== false)
                    $matchResult = true;
            }
            else
                $matchResult = true;

            if ($i >= $_GET['start'] && $i < ($_GET['start'] + $_GET['count']) || $matchResult)
            {
                if ($matchResult)
                {
                    $name = Misc::sqlGet("initials", "customers", "customerId", $row['customerId'])['initials'] . ' ' . Misc::sqlGet("familyName", "customers", "customerId", $row['customerId'])['familyName'].' | ';
                    
                    if (Misc::sqlGet("companyName", "customers", "customerId", $row['customerId'])['companyName'] != "")
                        $name = $name.Misc::sqlGet("companyName", "customers", "customerId", $row['customerId'])['companyName'] . ' | ';
                    
                    if (Misc::sqlGet("email", "customers", "customerId", $row['customerId'])['email'] == "")
                        $name = $name."!! Geen email, voeg email adres toe !!";
                    else
                        $name = $name . Misc::sqlGet("email", "customers", "customerId", $row['customerId'])['email'];

                    $startDate = new DateTime($row['startDate']);
                    $time = Calculate::calculateNextOrder($row['planningPeriod'], $row['planningDay'], $startDate, 0, $row['sendOrderNow']);

                    echo '    <tr>';
                    echo '            <td><a href="#" id="contract' . $row['contractId'] . 'Btn">#' . str_pad($row['contractId'], 8, '0', STR_PAD_LEFT) . '</a></td>';
                    echo '            <td>' . $name . '</td>';
                    echo '            <td>' . ($row['planningPeriod'] == "month" ? "Maandelijks" : ($row['planningPeriod'] == "quarter" ? "Per kwartaal" : "Jaarlijks")) . ' op de '.$row['planningDay'].'ste</td>';
                    echo '            <td>'. strftime("%d-%m-%Y", $time->getTimestamp()), PHP_EOL.'</td>';

                    echo '    <script>';
                    echo '    	$(document).ready(function ()
					                        {
						                        $("#contract' . $row['contractId'] . 'Btn").on("click", function () {
                                                    $("#loaderAnimation").fadeIn();
                                                    $("#PageContent").load("contract/viewContract.php?id=' . $row['contractId'] . '");
						                        });
                                            });';

                    echo '      $("#add' . $row['customerId'] . '").on("click", function() {
                        $.get(
                            "contract/contractSelect.php",
                            {
                                contractId: \'' . $row['contractId'] . '\',
                            },
                            function (data)
                            {
                                $("#pageLoaderIndicator").fadeIn();
                                $("#PageContent").load("receipt.php?new", function () {
                                    $("#pageLoaderIndicator").fadeOut();
                                });
                            }
                        );
                        });';

                    echo '    </script>';

                    echo '    </tr>';
                }
            }
        }

    }
}
