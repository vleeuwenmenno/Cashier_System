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

        $sql = "SELECT * FROM customers WHERE 1;";

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
                    echo '    <tr>';
                    echo '            <td><a href="#" id="customer' . $row['customerId'] . 'Btn">' . $row['customerId'] . '</a></td>';
                    echo '            <td>' . $row['initials'] . '</td>';
                    echo '            <td>' . $row['familyName'] . '</td>';
                    echo '            <td>' . $row['companyName'] . '</td>';
                    echo '            <td>' . $row['postalCode'] . '</td>';

                    if (isset($_SESSION['receipt']['status']) && $_SESSION['receipt']['status'] == "open")
                        echo '<td><button id="add' .  $row['customerId'] . '" type="button" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span></button></td>';

                    echo '    <script>';
                    echo '    	$(document).ready(function ()
					                        {
						                        $("#customer' . $row['customerId'] . 'Btn").on("click", function () {
                                                    $("#loaderAnimation").fadeIn();
                                                    $("#PageContent").load("customer/viewCustomer.php?id=' . $row['customerId'] . '");
						                        });
					                        });';
                    echo '      $("#add' . $row['customerId'] . '").on("click", function() {
                                $.get(
                                    "customer/customerSelect.php",
                                    {
                                        customerId: \'' . $row['customerId'] . '\',
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