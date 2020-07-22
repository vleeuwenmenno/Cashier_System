<?php
include_once("includes.php");
require 'vendor/autoload.php';
use Knp\Snappy\Pdf;

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
    $snappy = new Pdf("wkhtmltopdf.exe");
else 
    $snappy = new Pdf($myProjectDirectory . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');

if (isset($_GET['openReport']))
{
    header('Content-Type: application/pdf');    
        
    if (isset($_GET['download']))
        header('Content-Disposition: attachment; filename="kassa-open-'.$today->format("d-m-Y").'.pdf"');

    $snappy->resetOptions();
    $snappy->setOption('post', array('cashSessionId' => $_GET['openReport']));

    echo $snappy->getOutput('http://cashier.local/openReport.php');
}
else if (isset($_GET['closeReport']))
{
    $today = new DateTime();
    $today->setTime( 0, 0, 0 );

    $cashSessionId = $_GET['closeReportNow'];
    header('Content-Type: application/pdf');    
        
    if (isset($_GET['download']))
        header('Content-Disposition: attachment; filename="kassa-sluiten-'.$today->format("d-m-Y").'.pdf"');

    $snappy->resetOptions();
    $snappy->setOption('post', array('cashSessionId' => $_GET['closeReport']));

    echo $snappy->getOutput("http://cashier.local/closeReport.php");
}