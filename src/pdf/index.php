<?php
    include_once("../includes.php");
    require '../vendor/autoload.php';
    use Knp\Snappy\Pdf;

    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
        $snappy = new Pdf("wkhtmltopdf.exe");
    else 
        $snappy = new Pdf('/opt/lampp/htdocs/vendor/bin/wkhtmltopdf-amd64');

    if (isset($_GET['cid']) && isset($_GET['lid']))
    {
        header('Content-Type: application/pdf');    
        
        if (isset($_GET['download']))
            header('Content-Disposition: attachment; filename="file.pdf"');

        $snappy->resetOptions();
        $snappy->setOption('post', array('exvat' => isset($_GET['exvat']), 'lid' => $_GET['lid'], 'cid' => $_GET['cid'], 'notice' => isset($_GET['notice']) ? $_GET['notice'] : ""));

        echo $snappy->getOutput('http://cashier.local/pdf/invoice.php');
    }
    
    if (isset($_GET['rid']))
    {
        header('Content-Type: application/pdf');    
        
        if (isset($_GET['download']))
            header('Content-Disposition: attachment; filename="file.pdf"');

        $snappy->resetOptions();
        $snappy->setOption('post', array('exvat' => isset($_GET['exvat']), 'rid' => $_GET['rid']));

        echo $snappy->getOutput('http://cashier.local/pdf/receipt.php');
    }