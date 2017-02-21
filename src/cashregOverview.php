<?php
include_once("includes.php");

if (isset($_GET['cashIn']))
{
    $thisIp = $_SERVER['REMOTE_ADDR'];
    $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

    if($db->connect_errno > 0)
    {
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    $sql = "INSERT INTO `cashsession` (`openedBy`, `cashIn`, `openDate`) VALUES ('" . $_SESSION['login_ok']['userId'] . "', '" . $_GET['cashIn'] . "', '" . date("d-m-Y H:i:s") . "');";

    if (!$result = $db->query($sql))
    {
        die('Er was een fout tijdens het openen van de kassa. (' . $db->error . ')');
    }
    else
    {
        $id = mysqli_insert_id($db);
        $sqls = "UPDATE cash_registers SET cash_registers.status='LoggedOn', `currentSession` = '" . $id . "' WHERE crStaticIP='$thisIp'";

        if(!$results = $db->query($sqls))
        {
            die('Er was een fout tijdens het openen van de kassa. (' . $db->error . ')');
        }
        else
        {
            echo "LOG_OK";
        }
    }
}
else if (isset($_GET['open']))
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
    while ($row = $result->fetch_assoc())
    {
        $cashOut = round($row['cashOut'], 2);
    }
    ?>
    <div class="container container-table">
        <div class="row vertical-center-row">
            <div class="text-center col-md-4 col-md-offset-4" style="margin-top: 32px;">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Kassa openen</h3>
                    </div>
                    <div class="panel-body" style="display: inline-block; text-align: left">
                        <b>Tijd en datum:</b> <div id="time" style="display: inline-block;"><?php echo date("d-m-Y H:i:s"); ?></div><br />
                        <b>Medewerker:</b> <?php echo $_SESSION['login_ok']['nickName'];?><br /><br />
                        <div class="input-group">
                            <span class="input-group-addon">Kas-in</span>
                            <input type="text" class="form-control" id="cashInTxt" placeholder="<?php echo $cashOut; ?>">
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

                            $("#openCr").on("click", function() {
                                $.get(
                                    "cashregOverview.php",
                                    {
                                        cashIn: $('#cashInTxt').val().replace(",", ".")
                                    },
                                    function (data)
                                    {
                                        if (data.includes("LOG_OK"))
                                        {
                                            window.open("master.php","_self");
                                        }
                                        else
                                        {
                                            $.notify({
                                                icon: 'fa fa-exclamation-triangle fa-2x',
                                                title: 'Error<br />',
                                                message: data
                                            }, {
                                                // settings
                                                type: 'danger',
                                                delay: 5000,
                                                timer: 10,
                                                placement: {
                                                    from: "bottom",
                                                    align: "right"
                                                }
                                            });
                                        }
                                    }
                                );
                            });

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
else if (isset($_GET['close']))
{
    ?>
    <div class="container container-table">
        <div class="row vertical-center-row">
            <div class="text-center col-md-4 col-md-offset-4" style="margin-top: 32px;">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Kassa sluiten</h3>
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
                            echo 'U bevindt zich niet op een kassa systeem! (' . $thisIp . ')';
                            return;
                        }

                        if (Misc::crIsActive())
                        {
                            $sql = "SELECT * FROM cashsession ORDER BY cashSessionId DESC LIMIT 1;";

                            if(!$result = $db->query($sql))
                            {
                                die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')');
                            }

                            $cashOut = 0.0;
                            while($row = $result->fetch_assoc())
                            {
                                $cashSessionId = Misc::sqlGet("currentSession", "cash_registers", "crStaticIP", $thisIp)['currentSession'];
                                ?>
                                <b>Kas-in:</b> &euro; <?php echo '' . round($row['cashIn'], 2) ?><br /> <!-- kas-in is het bedrag in cash wat er in de kassa zit op het moment van kassa/winkel opening -->

                                <!-- <b>Bruto-omzet:</b> &euro;&nbsp;<?php //echo round(Calculate::getGrossTurnover(PaymentMethod::All, $cashSessionId), 2); ?><br />
                                Bruto omzet is de totale omzet. (Omzet is de optelsom van alle inkomsten) -->

                                <b>Omzet:</b> &euro;&nbsp;<?php echo round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2); ?><br />
                                <!-- De netto omzet wordt berekend aan de hand van de bruto omzet met aftrek van teruggenomen artikelen, schadevergoedingen en achteraf toegekende kortingen. -->

                                <b>Marge:</b> &euro;&nbsp;<?php echo round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2); ?><br /><br />
                                <!-- De marge is het verschil tussen inkoop- en verkoopprijs. -->

                                <b>Kassa geopend op:</b> <?php echo $row['openDate']; ?><br />
                                <b>Geopend door:</b> <?php echo $_SESSION['login_ok']['nickName']; ?><br /><br />
                                <?php
                                    $cashOut = round($row['cashIn'], 2) + round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId), 2);
                                ?>
                                <div class="input-group">
                                    <span class="input-group-addon" style="max-width: 120px; width: 120px;">Totaal kasgeld</span>
                                    <input type="text" class="form-control" id="cashOut" placeholder="&euro;&nbsp;<?php echo round($cashOut, 2); ?>">
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon" style="max-width: 120px; width: 120px;">Pinbon</span>
                                    <input type="text" class="form-control" id="pinOut" placeholder="&euro;&nbsp;<?php echo round(Calculate::getNetTurnover(PaymentMethod::Pin, $cashSessionId), 2); ?>">
                                </div>

                                <div class="input-group">
                                    <span class="input-group-addon" style="max-width: 120px; width: 120px;">Op rekening</span>
                                    <input type="text" class="form-control" id="bankOut" placeholder="&euro;&nbsp;<?php echo round(Calculate::getNetTurnover(PaymentMethod::BankTransfer, $cashSessionId), 2); ?>">
                                </div>

                                <div class="checkbox">
                                    <label><input type="checkbox" id="agreeConfirm" value="">Hierbij verklaar ik alles naar waarheid te hebben ingevuld.</label>
                                </div>
                            <?php
                            }
                        }
                        ?>
                    </div>
                    <button type="button" id="closeCr" class="btn btn-primary" disabled>Kassa sluiten</button>
                    <button type="button" id="cancelCr" class="btn btn-default">Annuleren</button><br /><br />
                    <script>
                        $( document ).ready(function() {
                            $('#agreeConfirm').change(function()
                            {
                                if($(this).is(":checked"))
                                    $("#closeCr").prop("disabled", false);
                                else
                                    $("#closeCr").prop("disabled", true);
                            });

                            $("#cashOut").on("input", function()
                            {
                                if ($("#cashOut").val() == "€ " || $("#cashOut").val() == "")
                                {
                                    $("#cashOut").val("");
                                    return;
                                }

                                if ($("#cashOut").val().substring(0, 2) != "€ ")
                                    $("#cashOut").val("€ " + $("#cashOut").val())
                            });

                            $("#pinOut").on("input", function()
                            {
                                if ($("#pinOut").val() == "€ " || $("#pinOut").val() == "")
                                {
                                    $("#pinOut").val("");
                                    return;
                                }

                                if ($("#pinOut").val().substring(0, 2) != "€ ")
                                    $("#pinOut").val("€ " + $("#pinOut").val())
                            });

                            $("#bankOut").on("input", function()
                            {
                                if ($("#bankOut").val() == "€ " || $("#bankOut").val() == "")
                                {
                                    $("#bankOut").val("");
                                    return;
                                }

                                if ($("#bankOut").val().substring(0, 2) != "€ ")
                                    $("#bankOut").val("€ " + $("#bankOut").val())
                            });

                            $("#closeCr").on("click", function () {
                                $("#pageLoaderIndicator").fadeIn();
                                $("#PageContent").load("cashregOverview.php?cashout", function () {
                                    $("#pageLoaderIndicator").fadeOut();
                                });
                            });

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
else if (isset($_GET['cashout']))
{
    ?>

    <div class="container container-table">
        <div class="row vertical-center-row">
            <div class="text-center col-md-4 col-md-offset-4" style="margin-top: 32px;">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Kassa Sluiten</h3>
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
                            { $ok = true; }

                            if (!$ok)
                            {
                                echo 'U bevindt zich niet op een kassa systeem! (' . $thisIp . ')';
                                return;
                            }

                            if (Misc::crIsActive())
                            {
                                $sql = "SELECT * FROM cashsession ORDER BY cashSessionId DESC LIMIT 1;";

                                if(!$result = $db->query($sql))
                                {
                                    die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')');
                                }

                                $cashOut = 0.0;
                                while($row = $result->fetch_assoc())
                                {
                                    $cashSessionId = Misc::sqlGet("currentSession", "cash_registers", "crStaticIP", $thisIp)['currentSession'];
                                    ?>
                                    <b>Kas-in:</b> &euro; <?php echo '' . round($row['cashIn'], 2) ?><br />
                                    <b>Kas-uit:</b> &euro; <?php echo '' . round($row['cashIn'], 2) ?><br />

                                    <b>Bruto-omzet:</b> &euro;&nbsp;<?php echo round(Calculate::getGrossTurnover(PaymentMethod::All, $cashSessionId), 2); ?><br />
                                    <b>Omzet:</b> &euro;&nbsp;<?php echo round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2); ?><br />
                                    <b>Marge:</b> &euro;&nbsp;<?php echo round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2); ?><br /><br />

                                    <b>Kassa geopend op:</b> <?php echo $row['openDate']; ?><br />
                                    <b>Geopend door:</b> <?php echo $_SESSION['login_ok']['nickName']; ?><br />
                                </div>
                                <button type="button" class="btn btn-primary" id="confirmClose">Bevestigen</button>
                                <button type="button" class="btn btn-default" id="cancelClose">Annuleren</button><br /><br />
                                <?php
                                }
                            }
                            else
                            {
                                echo 'Kan kassa niet sluiten als hij nog niet is geopent.';
                            }
                        ?>
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
                                echo 'U bevindt zich niet op een kassa systeem! (' . $thisIp . ')';
                                return;
                            }

                            if (Misc::crIsActive())
                            {
                                $sql = "SELECT * FROM cashsession ORDER BY cashSessionId DESC LIMIT 1;";

                                if(!$result = $db->query($sql))
                                {
                                    die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')');
                                }

                                $cashOut = 0.0;
                                while($row = $result->fetch_assoc())
                                {
                                    $cashSessionId = Misc::sqlGet("currentSession", "cash_registers", "crStaticIP", $thisIp)['currentSession'];
                                    ?>
                                    <b>Kas-in:</b> &euro; <?php echo '' . round($row['cashIn'], 2) ?><br /> <!-- kas-in is het bedrag in cash wat er in de kassa zit op het moment van kassa/winkel opening -->

                                    <!-- <b>Bruto-omzet:</b> &euro;&nbsp;<?php //echo round(Calculate::getGrossTurnover(PaymentMethod::All, $cashSessionId), 2); ?><br />
                                    Bruto omzet is de totale omzet. (Omzet is de optelsom van alle inkomsten) -->

                                    <b>Omzet:</b> &euro;&nbsp;<?php echo round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2); ?><br />
                                    <!-- De netto omzet wordt berekend aan de hand van de bruto omzet met aftrek van teruggenomen artikelen, schadevergoedingen en achteraf toegekende kortingen. -->

                                    <b>Marge:</b> &euro;&nbsp;<?php echo round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2); ?><br /><br />
                                    <!-- De marge is het verschil tussen inkoop- en verkoopprijs. -->

                                    <b>Kassa geopend op:</b> <?php echo $row['openDate']; ?><br />
                                    <b>Geopend door:</b> <?php echo $_SESSION['login_ok']['nickName']; ?><br />
                                </div>
                                <button type="button" class="btn btn-primary" id="closeCashr">Sluiten</button>
                                <button type="button" class="btn btn-default" id="printReport">Afdrukken</button><br /><br />
                                <?php
                                }
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
                            $("#closeCashr").on('click', function() {
                                $("#pageLoaderIndicator").fadeIn();
                                $("#PageContent").load("cashregOverview.php?close", function () {
                                    $("#pageLoaderIndicator").fadeOut();
                                });
                            });

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

                            $("#printReport").on("click", function() {
                                $("#pageLoaderIndicator").fadeIn();
                                $("#PageContent").load("print.php?openReport", function () {
                                    $("#pageLoaderIndicator").fadeOut();
                                });
                            });

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

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = str_replace("0.", "", number_format(($finish - $start), 4));
echo '<script> $(document).ready(function () { console.log("Page created in '.$total_time.'ms"); });';
?>
