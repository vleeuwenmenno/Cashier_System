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
    public static function getGrossTurnover($identifier, $sessionID)
    {
        global $config;

        //<!-- Bruto omzet is de totale omzet. (Omzet is de optelsom van alle inkomsten) -->
        if ($identifier == PaymentMethod::All)
        {
            $sql = "SELECT receiptId,items FROM receipt WHERE paidDt IS NOT NULL AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
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

                if ($receipt['total'] > 0)
                    $final += number_format($receipt['total'],2 , '.', '');
            }

            return $final;
        }
        else if ($identifier == PaymentMethod::Pin)
        {
            $sql = "SELECT receiptId FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='PIN' AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
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

                if ($receipt['total'] > 0)
                    $final += number_format($receipt['total'],2 , '.', '');
            }

            return $final;
        }
        else if ($identifier == PaymentMethod::Cash)
        {
            $sql = "SELECT receiptId FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='CASH' AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
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

                if ($receipt['total'] > 0)
                    $final += number_format($receipt['total'],2 , '.', '');
            }

            return $final;
        }
        else if ($identifier == PaymentMethod::BankTransfer)
        {
            $sql = "SELECT receiptId FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='BANK' AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
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

                if ($receipt['total'] > 0)
                    $final += number_format($receipt['total'],2 , '.', '');
            }

            return $final;
        }
    }

    public static function getNetTurnover($identifier, $sessionID)
    {
        global $config;

        //<!-- De netto omzet wordt berekend aan de hand van de bruto omzet met aftrek van teruggenomen artikelen, schadevergoedingen en achteraf toegekende kortingen. -->
        if ($identifier == PaymentMethod::All)
        {
            $sql = "SELECT receiptId,items FROM receipt WHERE paidDt IS NOT NULL AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
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
                $final += number_format($receipt['total'],2 , '.', '');
            }
            return $final;
        }
        else if ($identifier == PaymentMethod::Pin)
        {
            $sql = "SELECT receiptId,items FROM receipt WHERE paidDt IS NOT NULL AND paymentMethod='PIN' AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
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
                $final += number_format($receipt['total'],2 , '.', '');
            }
            return $final;
        }
        else if ($identifier == PaymentMethod::Cash)
        {
            $sql = "SELECT receiptId,items FROM receipt WHERE paidDt IS NOT NULL AND paymentMethod='CASH' AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
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
                $final += number_format($receipt['total'],2 , '.', '');
            }
            return $final;
        }
        else if ($identifier == PaymentMethod::BankTransfer)
        {
            $sql = "SELECT receiptId,items FROM receipt WHERE paidDt IS NOT NULL AND paymentMethod='BANK' AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
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
                $final += number_format($receipt['total'],2 , '.', '');
            }
            return $final;
        }
    }

    public static function getMargin($identifier, $sessionID)
    {
        global $config;
        global $_CFG;

        if ($identifier == PaymentMethod::All)
        {
            $sql = "SELECT receiptId FROM receipt WHERE paidDt IS NOT NULL AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
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
                //Marge is totale prijs min belasting min inkoop prijs
                $receipt = Misc::sqlGet("items", "receipt", "receiptId", $row["receiptId"]);
                $json = json_decode(urldecode($receipt['items']), TRUE);

                foreach ($json as $key => $val)
                {
                    $itemPrice = Misc::calculate(($val['priceAPiece']['priceExclVat'] * $_CFG['VAT']) . " " . $val['priceAPiece']['priceModifier']);
                    $itemMargin = $itemPrice - (($val['priceAPiece']['priceExclVat'] * $_CFG['VAT']) - $val['priceAPiece']['priceExclVat']) - $val['priceAPiece']['priceExclVat'];
                    $final += $itemMargin;
                }
            }
            return $final;
        }
        else if ($identifier == PaymentMethod::Pin)
        {
            $sql = "SELECT receiptId FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='PIN' AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
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
                //Marge is totale prijs min belasting min inkoop prijs
                $receipt = Misc::sqlGet("items", "receipt", "receiptId", $row["receiptId"]);
                $json = json_decode(urldecode($receipt['items']), TRUE);

                foreach ($json as $key => $val)
                {
                    $itemPrice = Misc::calculate(($val['priceAPiece']['priceExclVat'] * $_CFG['VAT']) . " " . $val['priceAPiece']['priceModifier']);
                    $itemMargin = $itemPrice - (($val['priceAPiece']['priceExclVat'] * $_CFG['VAT']) - $val['priceAPiece']['priceExclVat']) - $val['priceAPiece']['priceExclVat'];
                    $final += $itemMargin;
                }
            }
            return $final;
        }
        else if ($identifier == PaymentMethod::Cash)
        {
            $sql = "SELECT receiptId FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='CASH' AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
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
                //Marge is totale prijs min belasting min inkoop prijs
                $receipt = Misc::sqlGet("items", "receipt", "receiptId", $row["receiptId"]);
                $json = json_decode(urldecode($receipt['items']), TRUE);

                foreach ($json as $key => $val)
                {
                    $itemPrice = Misc::calculate(($val['priceAPiece']['priceExclVat'] * $_CFG['VAT']) . " " . $val['priceAPiece']['priceModifier']);
                    $itemMargin = $itemPrice - (($val['priceAPiece']['priceExclVat'] * $_CFG['VAT']) - $val['priceAPiece']['priceExclVat']) - $val['priceAPiece']['priceExclVat'];
                    $final += $itemMargin;
                }
            }
            return $final;
        }
        else if ($identifier == PaymentMethod::BankTransfer)
        {
            $sql = "SELECT receiptId FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='BANK' AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
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
                //Marge is totale prijs min belasting min inkoop prijs
                $receipt = Misc::sqlGet("items", "receipt", "receiptId", $row["receiptId"]);
                $json = json_decode(urldecode($receipt['items']), TRUE);

                foreach ($json as $key => $val)
                {
                    $itemPrice = Misc::calculate(($val['priceAPiece']['priceExclVat'] * $_CFG['VAT']) . " " . $val['priceAPiece']['priceModifier']);
                    $itemMargin = $itemPrice - (($val['priceAPiece']['priceExclVat'] * $_CFG['VAT']) - $val['priceAPiece']['priceExclVat']) - $val['priceAPiece']['priceExclVat'];
                    $final += $itemMargin;
                }
            }
            return $final;
        }
    }

    public static function getReceiptTotal($items, $session = false)
    {
        global $config;
        global $_CFG;

        $decoded = urldecode($items);
        $json = json_decode($decoded, TRUE);

        if ($session == true)
        {
            $json = $_SESSION['receipt']['items'];
        }

        $final = array();
        foreach ($json as $key => $val)
        {
            $final['total'] += (Misc::calculate(number_format($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2, '.', '') . $val['priceAPiece']['priceModifier']) * $val['count']);
            $final['exclVat'] += ($val['priceAPiece']['priceExclVat'] *  $val['count']);
        }
        return $final;
    }
}
