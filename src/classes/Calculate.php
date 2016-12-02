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
                $final += Calculate::getReceiptTotal($row['receiptId'])['total'];
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
                die('Er was een fout tijdens het ophalen van bruto-omzet (PaymentMethod::Pin) (' . $db->error . ')');
            }

            $final = 0.00;
            while($row = $result->fetch_assoc())
            {
                $final += Calculate::getReceiptTotal($row['receiptId'])['total'];
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
                die('Er was een fout tijdens het ophalen van bruto-omzet (PaymentMethod::Cash) (' . $db->error . ')');
            }

            $final = 0.00;
            while($row = $result->fetch_assoc())
            {
                $final += Calculate::getReceiptTotal($row['receiptId'])['total'];
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
                die('Er was een fout tijdens het ophalen van bruto-omzet (PaymentMethod::BankTransfer) (' . $db->error . ')');
            }

            $final = 0.00;
            while($row = $result->fetch_assoc())
            {
                $final += Calculate::getReceiptTotal($row['receiptId'])['total'];
            }
            return $final;
        }
    }

    public static function getNetTurnover($identifier, $sessionID)
    {
        global $config;

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
                $final += Calculate::getReceiptTotal($row['receiptId'])['exclVat'];
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
                die('Er was een fout tijdens het ophalen van bruto-omzet (PaymentMethod::Pin) (' . $db->error . ')');
            }

            $final = 0.00;
            while($row = $result->fetch_assoc())
            {
                $final += Calculate::getReceiptTotal($row['receiptId'])['exclVat'];
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
                die('Er was een fout tijdens het ophalen van bruto-omzet (PaymentMethod::Cash) (' . $db->error . ')');
            }

            $final = 0.00;
            while($row = $result->fetch_assoc())
            {
                $final += Calculate::getReceiptTotal($row['receiptId'])['exclVat'];
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
                die('Er was een fout tijdens het ophalen van bruto-omzet (PaymentMethod::BankTransfer) (' . $db->error . ')');
            }

            $final = 0.00;
            while($row = $result->fetch_assoc())
            {
                $final += Calculate::getReceiptTotal($row['receiptId'])['exclVat'];
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
                $receipt = Calculate::getReceiptTotal($row['receiptId']);
                $final += ($receipt['total'] - $receipt['exclVat']) / $_CFG['VAT'];
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
                $receipt = Calculate::getReceiptTotal($row['receiptId']);
                $final += ($receipt['total'] - $receipt['exclVat']) / $_CFG['VAT'];
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
                $receipt = Calculate::getReceiptTotal($row['receiptId']);
                $final += ($receipt['total'] - $receipt['exclVat']) / $_CFG['VAT'];
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
                $receipt = Calculate::getReceiptTotal($row['receiptId']);
                $final += ($receipt['total'] - $receipt['exclVat']) / $_CFG['VAT'];
            }
            return $final;
        }
    }

    public static function getReceiptTotal($identifier, $session = false)
    {
        global $config;
        global $_CFG;

        $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

        if($db->connect_errno > 0)
        {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        $sql = "SELECT * FROM receipt WHERE receiptId='$identifier';";
        $json = array();

        if(!$result = $db->query($sql))
        {
            die('Er was een fout tijdens het ophalen van het totaal van bonNr:' . $identifier . ' (' . $db->error . ')');
        }

        while($row = $result->fetch_assoc())
        {
            $decoded = urldecode($row['items']);
            $json = json_decode($decoded, TRUE);
        }

        if ($session == true)
        {
            $json = $_SESSION['receipt']['items'];
        }

        $final = array();
        foreach ($json as $key => $val)
        {
            $final['exclVat'] += ($val['priceAPiece']['priceExclVat'] *  $val['count']);
            $final['total'] += (Misc::calculate(number_format($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']) * $val['count']);
        }
        return $final;
    }
}
