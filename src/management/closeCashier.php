<?php
    include_once("../includes.php");


    $thisIp = $_SERVER['REMOTE_ADDR'];
    $cashSessionId = Misc::sqlGet("currentSession", "cash_registers", "crStaticIP", $thisIp)['currentSession'];

    $margin = round(Calculate::getMargin(PaymentMethod::All, $cashSessionId), 2);
    $cashOut = round(Calculate::getNetTurnover(PaymentMethod::Cash, $cashSessionId) + Misc::sqlGet("cashIn", "cashsession", "cashSessionId", $cashSessionId)['cashIn'], 2);

    $sql = "UPDATE `cashsession` SET `closedBy`='" . $_SESSION['login_ok']['userId'] . "', `cutOut` = '" . $_GET['cutOut'] . "', `cashOut` = '" . $cashOut . "', `margin` = '" . $margin . "', `closeDate` = '" . date("d-m-Y H:i:s") . "' WHERE `cashSessionId` = " . $cashSessionId;
    $resultOne = Misc::sql($sql);

    $sql = "UPDATE `cash_registers` SET `status` = 'LoggedOff', `currentSession` = NULL WHERE `cash_registers`.`currentSession` = $cashSessionId";
    $resultTwo = Misc::sql($sql);

    if ($resultOne == 1 && $resultTwo == 1)
    {
        ?>
        <script>
            $( document ).ready(function() {
                window.location.replace("print.php?closeReport=<?php echo $cashSessionId;?>");
            });
        </script>
        <?php
    }
    else
    {
        echo 'Iets ging fout tijdens het afsluiten van de kassa.<br />'. $resultOne . "<br />" . $resultTwo;
    }
?>
