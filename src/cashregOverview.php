<?php
include_once("includes.php");

if (isset($_GET['open']))
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
        $cashOut = $row['cashOut'];
    }
    ?>
    <div class="container container-table">
        <div class="row vertical-center-row">
            <div class="text-center col-md-4 col-md-offset-4" style="margin-top: 32px;">
                <div class="panel panel-info"> 
                    <div class="panel-heading"> 
                        <h3 class="panel-title">Kassa overzicht</h3> 
                    </div> 
                    <div class="panel-body" style="display: inline-block; text-align: left"> 
                        <b>Tijd en datum:</b> <div id="time" style="display: inline-block;"><?php echo date("d-m-Y H:i:s"); ?></div><br />
                        <b>Medewerker:</b> <?php echo $_SESSION['login_ok']['nickName'];?><br /><br />
                        <div class="input-group">
                            <span class="input-group-addon">Kas-in</span>
                            <input type="text" class="form-control" placeholder="<?php echo $cashOut; ?>">
                            <span class="input-group-addon">&euro;</span>
                        </div>
                    </div> 
                    <button type="button" id="openCr" class="btn btn-primary">Kassa openen</button>
                    <button type="button" id="cancelCr" class="btn btn-default">Annuleren</button><br /><br /> 
                    <script>
                        $( document ).ready(function() {
                            setInterval(function() {
                                $.get(
                                    "getTime.php",
                                    { },
                                    function (data)
                                    {
                                        $("#time").text(data);
                                    }
                                );
                            }, 1000);

                            $("#cancelCr").on("click", function() {
                                $("#pageLoaderIndicator").fadeIn();
                                $("#PageContent").load("cashregOverview.php", function () {
                                    $("#pageLoaderIndicator").fadeOut();
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
    <?php
}
else
{
    ?>
    <div class="container container-table">
        <div class="row vertical-center-row">
            <div class="text-center col-md-4 col-md-offset-4" style="margin-top: 32px;">
                <div class="panel panel-info"> 
                    <div class="panel-heading"> 
                        <h3 class="panel-title">Kassa overzicht</h3> 
                    </div> 
                    <div class="panel-body" style="display: inline-block; text-align: left"> 
                        <?php   
                            $ok = false;
                            $thisIp = $_SERVER['REMOTE_ADDR'];

                            $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

                            if($db->connect_errno > 0)
                            {
                                die('Unable to connect to database [' . $db->connect_error . ']');
                            }

                            $sql = "SELECT * FROM cash_registers WHERE crStaticIP='$thisIp';";

                            if(!$result = $db->query($sql))
                            {
                                die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')');
                            }

                            while($row = $result->fetch_assoc())
                            {
                                echo '<b>' . $row['crName'] . "</b> op <b>" . $thisIp . "</b><br />";

                                if ($row['status'] == "LoggedOff")
                                    echo '<b>Kassa Status:</b> Gesloten<br /><br />';
                                else
                                    echo '<b>Kassa Status:</b> Geopend<br /><br />';

                                $ok = true;
                            }
                            
                            if (!$ok) 
                            { 
                                echo 'U bevindt zich niet op een kassa systeem!';
                                return;
                            }

                            if (Misc::crIsActive())
                            {
                                    ?>
                                    <b>Kas-in:</b> NaN &euro;<br /> <!-- kas-in is het bedrag in cash wat er in de kassa zit op het moment van kassa/winkel opening -->
                                    <b>Bruto-omzet:</b> NaN &euro;<br /> <!-- Bruto omzet is de totale omzet. (Omzet is de optelsom van alle inkomsten) -->
                                    <b>Netto-omzet:</b> NaN &euro;<br /> <!-- De netto omzet wordt berekend aan de hand van de bruto omzet met aftrek van teruggenomen artikelen, schadevergoedingen en achteraf toegekende kortingen. -->
                                    <b>Marge:</b> NaN &euro;<br /><br /> <!-- De marge is het verschil tussen inkoop- en verkoopprijs. -->
                                    <b>Kassa geopend op:</b> 00-00-0000 00:00:00<br />
                                    <b>Geopend door:</b> NAME<br />
                                </div> 
                                <button type="button" class="btn btn-primary">Sluiten</button>
                                <button type="button" class="btn btn-default">Afdrukken</button><br /><br />
                                <?php
                            }
                            else
                            {
                                    ?>
                                    <b>Tijd en datum:</b> <div id="time" style="display: inline-block;"><?php echo date("d-m-Y H:i:s"); ?></div><br />
                                    <b>Medewerker:</b> <?php echo $_SESSION['login_ok']['nickName'];?><br />
                                </div> 
                                <button type="button" id="openCr" class="btn btn-primary">Kassa openen</button><br /><br />
                                <?php
                            }
                        ?>
                        <script>
                        $( document ).ready(function() {
                            setInterval(function() {
                                $.get(
                                    "getTime.php",
                                    { },
                                    function (data)
                                    {
                                        $("#time").text(data);
                                    }
                                );
                            }, 1000);

                            $("#openCr").on("click", function() {
                                $("#pageLoaderIndicator").fadeIn();
                                $("#PageContent").load("cashregOverview.php?open", function () {
                                    $("#pageLoaderIndicator").fadeOut();
                                });
                            });
                        });
                        </script>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
