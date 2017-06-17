<?php
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $start = $time;

    session_start();

    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include_once('functions.php');

 ?>
