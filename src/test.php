<?php
include_once("includes.php");

$sql = "SELECT receiptId,items FROM receipt WHERE paidDt IS NOT NULL AND paymentMethod IS NOT NULL AND parentSession='35'";
$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

if($db->connect_errno > 0)
{
    die('Unable to connect to database [' . $db->connect_error . ']');
}

if(!$result = $db->query($sql))
{
    die('Er was een fout tijdens het ophalen van bruto-omzet (PaymentMethod::All) (' . $db->error . ')');
}

$final = 0.00;
while($row = $result->fetch_assoc())
{
    $receipt = Calculate::getReceiptTotal($row['items']);
    echo "<br />" . $row['receiptId'] . " total: " . $receipt['total'] . ' exclVat: ' . $receipt['exclVat'];

    $pre = number_format(($receipt['total'] - $receipt['exclVat']), 2, '.', '');

    if ($receipt['exclVat'] > 0)
        $pre = number_format($pre / $_CFG['VAT'], 2, '.', '');;

    $final += $pre;
    echo '<br />Sub-total: ' . $final;
}

echo '<br />Total: ' . $final;

 ?>
