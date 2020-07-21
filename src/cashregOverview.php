<?php
include_once("includes.php");
Permissions::checkSession(basename($_SERVER['REQUEST_URI']));

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
        $cashOut = round($row['cashOut'] - $row['cutOut'], 2);
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
                            <input type="text" class="form-control" id="cashInTxt" placeholder="<?php echo $cashOut; ?>" value="<?php echo $cashOut; ?>">
                            <span class="input-group-addon"><?=$_CFG['CURRENCY']?></span>
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
            <div class="col-md-3"></div>
            <div class="text-center col-md-4" style="margin-top: 32px;">
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

                        $sql = "SELECT * FROM cash_registers WHERE crStaticIP='$thisIp' OR crStaticIP='*';";

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

                        /*if (!$ok)
                        {
                            echo 'U bevindt zich niet op een kassa systeem! (' . $thisIp . ')';
                            return;
                        }*/

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
                                <b>Omzet:</b><br />
                                <div><span style="margin-left: 2.5em; font-size: 12px;">Omzet pin:</span><i style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Pin, $cashSessionId), 2), 2, ",", "."); ?></i></div><br />
                                <div style="position: relative; top: -16px;"><span style="margin-left: 2.5em; font-size: 12px;">Omzet kontant:</span><i style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId), 2), 2, ",", "."); ?></i></div><br />
                                <div style="position: relative; top: -32px;"><span style="margin-left: 2.5em; font-size: 12px;">Omzet op rekening:</span><i style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::BankTransfer, $cashSessionId), 2), 2, ",", "."); ?></i></div><br />
                                <div style="position: relative; top: -47px;"><span style="margin-left: 2.5em; font-size: 12px;">Omzet iDeal:</span><i style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::iDeal, $cashSessionId), 2), 2, ",", "."); ?></i></div><br />
                                <div style="position: relative; top: -48px;"><b style="margin-left: 2.5em; font-size: 12px;">Totaal omzet:</b><i style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></i></div><br />
                                <div style="position: relative; top: -32px;"><b>Marge:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div><br />
                                <div style="position: relative; top: -48px;"><b>Kas-in:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?> <?php echo number_format(round($row['cashIn'], 2), 2, ",", "."); ?></span><br /></div>
                                <div style="position: relative; top: -32px; padding-bottom: 32px;">
                                    <b>Kassa geopend op:</b> <?php echo $row['openDate']; ?><br />
                                    <b>Geopend door:</b> <?php echo Misc::sqlGet("nickName", "users", "userId", Misc::sqlGet("openedBy", "cashsession", "cashSessionId", $cashSessionId)['openedBy'])['nickName']; ?>
                                </div>
                                <div style="position: relative; top: -32px;">
                                    <?php
                                        $cashOut = round($row['cashIn'], 2) + round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId), 2);
                                    ?>
                                    <div class="input-group">
                                        <span class="input-group-addon" style="max-width: 120px; width: 120px;">Totaal kasgeld</span>
                                        <input type="text" class="form-control" id="cashOut" placeholder="<?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round($cashOut, 2), 2, ",", "."); ?>">
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-addon" style="max-width: 120px; width: 120px;">Pinbon</span>
                                        <input type="text" class="form-control" id="pinOut" placeholder="<?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Pin, $cashSessionId), 2), 2, ",", "."); ?>">
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-addon" style="max-width: 120px; width: 120px;">Op rekening</span>
                                        <input type="text" class="form-control" id="bankOut" placeholder="<?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::BankTransfer, $cashSessionId), 2), 2, ",", "."); ?>">
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-addon" style="max-width: 120px; width: 120px;">iDeal</span>
                                        <input type="text" class="form-control" id="iDealOut" placeholder="<?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::iDeal, $cashSessionId), 2), 2, ",", "."); ?>">
                                    </div>

                                    <div class="checkbox">
                                        <label><input type="checkbox" id="agreeConfirm" value="">Hierbij verklaar ik alles naar waarheid te hebben ingevuld.</label>
                                    </div>

                                    <center style="position: relative; top: 32px;">
                                        <button type="button" id="closeCr" class="btn btn-primary" disabled>Kassa sluiten</button>
                                        <button type="button" id="cancelCr" class="btn btn-default">Annuleren</button>
                                    </center>
                                </div>
                            <?php
                            }
                        }
                        ?>
                    </div>
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

                            $("#iDealOut").on("input", function()
                            {
                                if ($("#iDealOut").val() == "€ " || $("#iDealOut").val() == "")
                                {
                                    $("#iDealOut").val("");
                                    return;
                                }

                                if ($("#iDealOut").val().substring(0, 2) != "€ ")
                                    $("#iDealOut").val("€ " + $("#iDealOut").val())
                            });

                            $("#closeCr").on("click", function () {
                                if ($("#cashOut").val() != "" && $("#bankOut").val() != "" && $("#pinOut").val() != "")
                                {
                                    $("#pageLoaderIndicator").fadeIn();
                                    $("#PageContent").load("cashregOverview.php?cashOut=" + encodeURI($("#cashOut").val()) + "&pinOut=" + encodeURI($("#pinOut").val()) + "&bankOut=" + encodeURI($("#bankOut").val()) + "&iDealOut=" + encodeURI($("#iDealOut").val()), function () {
                                        $("#pageLoaderIndicator").fadeOut();
                                    });
                                }
                                else
                                {
                                    $.notify({
                                        icon: 'fa fa-exclamation-triangle fa-2x',
                                        title: '<b>Vul alle velden</b><br />',
                                        message: 'Niet alle velden waren ingevuld, vul alle velden A.U.B'
                                    }, {
                                        // settings
                                        type: 'danger',
                                        delay: 2000,
                                        timer: 5,
                                        placement: {
                                            from: "bottom",
                                            align: "right"
                                        }
                                    });
                                }
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
else if (isset($_GET['cashOut']) && isset($_GET['pinOut']) && isset($_GET['bankOut']))
{
    ?>
    <div class="container container-table">
        <div class="row vertical-center-row">
            <div class="col-md-3"></div>
            <div class="text-center col-md-4" style="margin-top: 32px;">
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

                            $sql = "SELECT * FROM cash_registers WHERE crStaticIP='*' OR crStaticIP='$thisIp';";

                            if(!$result = $db->query($sql))
                            {
                                die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')');
                            }

                            while($row = $result->fetch_assoc())
                            { $ok = true; }

                            /*if (!$ok)
                            {
                                echo 'U bevindt zich niet op een kassa systeem! (' . $thisIp . ')';
                                return;
                            }*/

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
                                    $totalIn = str_replace("€ ", "", str_replace(",", ".", $_GET['pinOut']));
                                    $totalIn = round((str_replace("€ ", "", str_replace(",", ".", $_GET['cashOut'])) + $totalIn) - round($row['cashIn'], 2), 2);
                                    $totalIn = round((str_replace("€ ", "", str_replace(",", ".", $_GET['bankOut'])) + $totalIn), 2);
                                    $totalIn = round((str_replace("€ ", "", str_replace(",", ".", $_GET['iDealOut'])) + $totalIn), 2);

                                    $cashSessionId = Misc::sqlGet("currentSession", "cash_registers", "crStaticIP", $thisIp)['currentSession'];
                                    $cashOut = round($row['cashIn'], 2) + round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId), 2);
                                    ?>
                                    <div><b>Totaal Inkomsten:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?> <?php echo number_format($totalIn, 2, ",", "."); ?></span></div>
                                    <div><b>Totaal Omzet:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                                    <div><b>Verschil:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?> <?php echo number_format(round($totalIn - round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2), 2), 2, ",", ".");?></span></div>
                                    <br />
                                    <div><span>Pinbon: </span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(str_replace("€ ", "", str_replace(",", ".", $_GET['pinOut'])), 2, ",", "."); ?></span></div>
                                    <div><span>Omzet pin: </span><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Pin, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                                    <div><b>Verschil: </b><span style="float: right;"> <?=$_CFG['CURRENCY']?> <?php echo number_format(round(str_replace("€ ", "", str_replace(",", ".", $_GET['pinOut'])) - round(Calculate::getNetTurnover(PaymentMethod::Pin, $cashSessionId), 2), 2), 2, ",", ".");?></span></div>
                                    <br />
                                    <div><span>Kontant kasgeld-in: </span><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(str_replace("€ ", "", str_replace(",", ".", $_GET['cashOut'])) -  $row['cashIn'], 2), 2, ",", "."); ?></span></div>
                                    <div><span>Omzet kontant: </span><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                                    <div><b>Verschil: </b><span style="float: right;"> <?=$_CFG['CURRENCY']?> <?php echo number_format(round(round(str_replace("€ ", "", str_replace(",", ".", $_GET['cashOut'])) -  $row['cashIn'], 2) - round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId), 2), 2), 2, ",", ".");?></span></div>
                                    <br />
                                    <div><span>Op rekening: </span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(str_replace("€ ", "", str_replace(",", ".", $_GET['bankOut'])), 2, ",", "."); ?></span></div>
                                    <div><span>Omzet rekening: </span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::BankTransfer, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                                    <div><b>Verschil: </b><span style="float: right;"><?=$_CFG['CURRENCY']?> <?php echo number_format(round(str_replace("€ ", "", str_replace(",", ".", $_GET['bankOut'])) - round(Calculate::getNetTurnover(PaymentMethod::BankTransfer, $cashSessionId), 2), 2), 2, ",", "."); ?></span></div>
                                    <br />
                                    <div><span>iDeal: </span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(str_replace("€ ", "", str_replace(",", ".", $_GET['iDealOut'])), 2, ",", "."); ?></span></div>
                                    <div><span>Omzet iDeal: </span><span style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::iDeal, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                                    <div><b>Verschil: </b><span style="float: right;"><?=$_CFG['CURRENCY']?> <?php echo number_format(round(str_replace("€ ", "", str_replace(",", ".", $_GET['iDealOut'])) - round(Calculate::getNetTurnover(PaymentMethod::iDeal, $cashSessionId), 2), 2), 2, ",", "."); ?></span></div>
                                    <br />
                                    <div><b>Marge:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div>
                                    <div><b>Kasgeld-in: </b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;  <?php echo number_format(round($row['cashIn'], 2), 2, ",", "."); ?></span></div>
                                    <div><b>Kasgeld-uit: </b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp; <?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId) + Misc::sqlGet("cashIn", "cashsession", "cashSessionId", $cashSessionId)['cashIn'], 2), 2, ",", "."); ?></span></div>

                                    <div class="input-group" style="padding-top: 16px;">
                                        <span class="input-group-addon" style="max-width: 120px; width: 120px;">Afromen</span>
                                        <input type="text" class="form-control" id="cutOut" placeholder="€&nbsp;0,00">
                                    </div>
                                </div>
                                <br />
                                <button type="button" class="btn btn-primary" id="closeCashier">Bevestigen</button>
                                <button type="button" class="btn btn-default" id="cancelClose">Annuleren</button><br /><br />
                                <?php
                                }
                            }
                            else
                            {
                                echo 'Kan kassa niet sluiten als hij nog niet is geopent.';
                            }
                        ?>
                        <script>
                            $( document ).ready(function() {
                                $("#cancelClose").on("click", function () {
                                    $("#pageLoaderIndicator").fadeIn();
                                    $("#PageContent").load("cashregOverview.php", function () {
                                        $("#pageLoaderIndicator").fadeOut();
                                    });
                                });

                                $("#closeCashier").on("click", function() {
                                    $("#pageLoaderIndicator").fadeIn();
                                    $("#PageContent").load("management/closeCashier.php?cutOut=" + $("#cutOut").val(), function () {
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
            <div class="col-md-3"></div>
            <div class="text-center col-md-4" style="margin-top: 32px;">
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


                            /*if (!$ok)
                            {
                                echo 'U bevindt zich niet op een kassa systeem! (' . $thisIp . ')';
                                return;
                            }*/

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
                                    <b>Omzet:</b><br />
                                    <div><span style="margin-left: 2.5em; font-size: 12px;">Omzet pin:</span><i style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Pin, $cashSessionId), 2), 2, ",", "."); ?></i></div><br />
                                    <div style="position: relative; top: -16px;"><span style="margin-left: 2.5em; font-size: 12px;">Omzet kontant:</span><i style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId), 2), 2, ",", "."); ?></i></div><br />
                                    <div style="position: relative; top: -32px;"><span style="margin-left: 2.5em; font-size: 12px;">Omzet op rekening:</span><i style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::BankTransfer, $cashSessionId), 2), 2, ",", "."); ?></i></div><br />
                                    <div style="position: relative; top: -47px;"><span style="margin-left: 2.5em; font-size: 12px;">Omzet iDeal:</span><i style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::iDeal, $cashSessionId), 2), 2, ",", "."); ?></i></div><br />
                                    <div style="position: relative; top: -48px;"><b style="margin-left: 2.5em; font-size: 12px;">Totaal omzet:</b><i style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getNetTurnover(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></i></div><br />
                                    <div style="position: relative; top: -32px;"><b>Marge:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2), 2, ",", "."); ?></span></div><br />
                                    <div style="position: relative; top: -48px;"><b>Kas-in:</b><span style="float: right;"> <?=$_CFG['CURRENCY']?> <?php echo number_format(round($row['cashIn'], 2), 2, ",", "."); ?></span><br /></div>
                                    <div style="position: relative; top: -32px;">
                                        <b>Kassa geopend op:</b> <?php echo $row['openDate']; ?><br />
                                        <b>Geopend door:</b> <?php echo Misc::sqlGet("nickName", "users", "userId", Misc::sqlGet("openedBy", "cashsession", "cashSessionId", $cashSessionId)['openedBy'])['nickName']; ?>
                                    </div>
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
