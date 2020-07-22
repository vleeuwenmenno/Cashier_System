<?php
    include_once("includes.php");

    $cashSessionId = $_POST['cashSessionId'];
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

    while($row = $result->fetch_assoc())
    {
        ?>
        <br /><br />
        <div style="margin-left: 12px; padding-top: 12px; font-size: 12px;" id="printPart">
            <?=Misc::sqlGet("crName", "cash_registers", "id", Misc::sqlGet("id", "cash_registers", "crStaticIP", $_SERVER['REMOTE_ADDR'])['id'])['crName']?> geopend op <?=$row['openDate']?>
            <br /><br />Medewerker: <?=Misc::sqlGet("nickName", "users", "userId", Misc::sqlGet("openedBy", "cashsession", "cashSessionId", $cashSessionId)['openedBy'])['nickName']; ?>
            <br />Kas-in: <?=$_CFG['CURRENCY'].' ' . $row['cashIn']?>
        </div>
        <?php
    }