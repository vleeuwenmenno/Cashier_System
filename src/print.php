<?php 
include_once("includes.php");

if (isset($_GET['openReport']))
{
    $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

    if($db->connect_errno > 0)
    {
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    $sql = "SELECT * FROM cashsession ORDER BY cashSessionId DESC LIMIT 1;";

    if(!$result = $db->query($sql))
    {
        die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')');
    }

    $cashOut = 0.0;
    while($row = $result->fetch_assoc())
    {
        // This is 2/3 of a A4 paper size
        //height: 19.8cm;
        //width: 14cm;
                    
        echo '<br /><br />
        <div style="background: white;
                    display: block;
                    margin: 0 auto;
                    margin-bottom: 0.5cm;
                    box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
                    width: 14cm;
                    height: 19.8cm;">';
        echo '<div style="margin-left: 12px; padding-top: 12px;">';
            echo Misc::sqlGet("crName", "cash_registers", "id", $row['cashRegisterId'])['crName'] . ' geopend door ' . $_SESSION['login_ok']['nickName'] . ' op ' .  $row['openDate'];
            echo '<br />Kas-in: &euro; ' . str_replace(".", ",", $row['cashIn']);
        echo '</div>';
        echo '</div>';
    }
}
else if (isset($_GET['closeReport']))
{

}
else if (isset($_GET['receipt']))
{

}