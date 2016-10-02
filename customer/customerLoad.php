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
            if ($i >= $_GET['start'] && $i < ($_GET['start'] + $_GET['count']))
            {
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

                if ($matchResult)
                {
                    echo '    <tr>';
                    echo '            <td><a href="#" id="customer' . $row['customerId'] . 'Btn">' . $row['customerId'] . '</a></td>';
                    echo '            <td>' . $row['initials'] . '</td>';
                    echo '            <td>' . $row['familyName'] . '</td>';
                    echo '            <td>' . $row['companyName'] . '</td>';
                    echo '            <td>' . $row['postalCode'] . '</td>';
                    echo '    </tr>';
                    echo '    <script>';
                    echo '    	$(document).ready(function ()
					                        {
						                        $("#customer' . $row['customerId'] . 'Btn").on("click", function () {
                                                    $("#loaderAnimation").fadeIn();
                                                    $("#PageContent").load("customer/viewCustomer.php?id=' . $row['customerId'] . '");
						                        });
					                        });';
                    echo '    </script>';
                }
            }
        }

    }
}