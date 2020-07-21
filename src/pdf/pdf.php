<?php

if (isset($_GET['cid']) && isset($_GET['lid']))
{
    ?>
    <iframe src = "http://cashier.local/pdf/?cid=36&lid=7&exvat" style="width: 100%; height: 100%;"></iframe>
    <?php
    die();
}

if (isset($_GET['rid']))
{
    ?>
    <iframe src = "pdf/?rid=<?=$_GET['rid']?>" style="width: 100%; height: 100%;"></iframe>
    <?php
    die();
}