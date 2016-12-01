<?php
session_start();

abstract class PaymentMethod
{
    const Pin = 1;
    const Cash = 2;
    const BankTransfer = 3;
    const All = 4;
}

class Calculate
{
    public static function getGrossTurnover($identifier)
    {
        if ($identifier == PaymentMethod::All)
        {
            $sql = "SELECT * FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod IS NOT NULL";


        }
        else if ($identifier == PaymentMethod::Pin)
        {
            $sql = "SELECT * FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='PIN' AND paymentMethod IS NOT NULL";


        }
        else if ($identifier == PaymentMethod::Cash)
        {
            $sql = "SELECT * FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='CASH' AND paymentMethod IS NOT NULL";


        }
        else if ($identifier == PaymentMethod::BankTransfer)
        {
            $sql = "SELECT * FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='BANK' AND paymentMethod IS NOT NULL";


        }
    }

    public static function getNetTurnover($identifier)
    {
        if ($identifier == PaymentMethod::All)
        {

        }
        else if ($identifier == PaymentMethod::Pin)
        {

        }
        else if ($identifier == PaymentMethod::Cash)
        {

        }
        else if ($identifier == PaymentMethod::BankTransfer)
        {

        }
    }

    public static function getMargin($identifier)
    {
        if ($identifier == PaymentMethod::All)
        {

        }
        else if ($identifier == PaymentMethod::Pin)
        {

        }
        else if ($identifier == PaymentMethod::Cash)
        {

        }
        else if ($identifier == PaymentMethod::BankTransfer)
        {

        }
    }

    public static function getReceiptTotal($identifier)
    {
        global $config;

        $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

        if($db->connect_errno > 0)
        {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        $sql = "SELECT * FROM receipt WHERE receiptId='1165241646';";
        $json = array();

        if(!$result = $db->query($sql))
        {
            die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')');
        }

        while($row = $result->fetch_assoc())
        {
            $decoded = urldecode($row['items']);
            $json = json_decode($decoded, TRUE);
        }

        $final = array();
        foreach ($json as $key => $val)
        {

            $final['exclVat'] += $val['priceAPiece']['priceExclVat'];
            $final['inclVat'] += Misc::calculate($val['priceAPiece']['priceExclVat'] . ' ' . $val['priceAPiece']['priceModifier']);
        }
        $final['inclVat']= number_format($final['inclVat'], 2, '.', '');

        return $final;
    }
}
