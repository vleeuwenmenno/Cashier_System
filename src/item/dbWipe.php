<?php
include_once("../includes.php");

if (isset($_GET['deleteAll']) && $_GET['deleteAll'] == 'true')
{
    echo Misc::sql("DELETE FROM `items` WHERE manuallyInserted=0");
}
else echo '0';

if (isset($_GET['deleteAllInclManual']) && $_GET['deleteAllInclManual'] == 'true')
{
    echo Misc::sql("DELETE FROM `items` WHERE manuallyInserted=1");
}
else echo '0';

if (isset($_GET['resetAll']) && $_GET['resetAll'] == 'true')
{
    echo Misc::sql("UPDATE items SET itemStock=0;");
}
else echo '0';

if (isset($_GET['deleteAllReceipts']) && $_GET['deleteAllReceipts'] == 'true')
{
    echo Misc::sql("DELETE FROM `receipt` WHERE 1");
}
else echo '0';

if (isset($_GET['deleteAllSessions']) && $_GET['deleteAllSessions'] == 'true')
{
    echo Misc::sql("DELETE FROM `cashsession` WHERE 1");
}
else echo '0';
?>
