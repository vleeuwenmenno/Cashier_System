<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

abstract class PaymentMethod
{
    const Pin = 1;
    const Cash = 2;
    const BankTransfer = 3;
    const All = 4;
    const iDeal = 5;
}

class Quarters
{
    public static function getQuarter($d) 
    {
        $q = [1,2,3,4];
        return $q[floor($d->format('m') / 3)];
    }
    
    public static function monthDiff($date1, $date2)
    {
        $ts1 = strtotime($date1->format("Y-m-d"));
        $ts2 = strtotime($date2->format("Y-m-d"));

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        return (($year2 - $year1) * 12) + ($month2 - $month1);
    }

    public static function getQuarterByMonth($monthNumber) {
        return floor(($monthNumber - 1) / 3) + 1;
    }

    public static function getDaysLeftInQuarter($d)
    {
        $today = new DateTime($d->format("Y-m-d"));
        $quarter = Quarters::getQuarterByMonth($today->format("m"));
        $nextq;
        
        if ($quarter == 1)
            $nextq = new DateTime ("01-04-".($today->format("Y")));
        else if ($quarter == 2)
            $nextq = new DateTime ("01-07-".($today->format("Y")));
        else if ($quarter == 3)
            $nextq = new DateTime ("01-10-".($today->format("Y")));
        else if ($quarter == 4)
            $nextq = new DateTime ("01-01-".($today->format("Y")+1));
            
        $end = strtotime($nextq->format("Y-m-d"));
        $start = strtotime($today->format("Y-m-d"));
        
        $days_between = ceil(abs($end - $start) / 86400);
        return $days_between;
    }
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
                    $final += round($receipt['total'],2 );
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
                    $final += round($receipt['total'],2 );
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
                    $final += round($receipt['total'],2 );
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
                    $final += round($receipt['total'],2 );
            }

            return $final;
        }
        else if ($identifier == PaymentMethod::iDeal)
        {
            $sql = "SELECT receiptId FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='iDeal' AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
            $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

            if($db->connect_errno > 0)
            {
                die('Unable to connect to database [' . $db->connect_error . ']');
            }

            if(!$result = $db->query($sql))
            {
                die('Er was een fout tijdens het ophalen van bruto-omzet (PaymentMethod::iDeal) (' . $db->error . ')');
            }

            $final = 0.00;
            while($row = $result->fetch_assoc())
            {
                $receipt = Calculate::getReceiptTotal($row['items']);

                if ($receipt['total'] > 0)
                    $final += round($receipt['total'],2 );
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
                $final += round($receipt['total'],2 );
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
                $final += round($receipt['total'],2 );
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
                $final += round($receipt['total'],2 );
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
                $final += round($receipt['total'],2 );
            }
            return $final;
        }
        else if ($identifier == PaymentMethod::iDeal)
        {
            $sql = "SELECT receiptId,items FROM receipt WHERE paidDt IS NOT NULL AND paymentMethod='iDeal' AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
            $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

            if($db->connect_errno > 0)
            {
                die('Unable to connect to database [' . $db->connect_error . ']');
            }

            if(!$result = $db->query($sql))
            {
                die('Er was een fout tijdens het ophalen van bruto-omzet (PaymentMethod::iDeal) (' . $db->error . ')');
            }

            $final = 0.00;
            while($row = $result->fetch_assoc())
            {
                $receipt = Calculate::getReceiptTotal($row['items']);
                $final += round($receipt['total'],2 );
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
                    $itemPrice = Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . " " . $val['priceAPiece']['priceModifier']);
                    $itemMargin = $itemPrice - (round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) - $val['priceAPiece']['priceExclVat']) - $val['priceAPiece']['priceExclVat'];
                    $final += round($itemMargin * $val['count'], 2);
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
                    $itemPrice = Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . " " . $val['priceAPiece']['priceModifier']);
                    $itemMargin = $itemPrice - (round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) - $val['priceAPiece']['priceExclVat']) - $val['priceAPiece']['priceExclVat'];
                    $final += round($itemMargin * $val['count'], 2);
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
                    $itemPrice = Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . " " . $val['priceAPiece']['priceModifier']);
                    $itemMargin = $itemPrice - (round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) - $val['priceAPiece']['priceExclVat']) - $val['priceAPiece']['priceExclVat'];
                    $final += round($itemMargin * $val['count'], 2);
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
                    $itemPrice = Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . " " . $val['priceAPiece']['priceModifier']);
                    $itemMargin = $itemPrice - (round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) - $val['priceAPiece']['priceExclVat']) - $val['priceAPiece']['priceExclVat'];
                    $final += round($itemMargin * $val['count'], 2);
                }
            }
            return $final;
        }
        else if ($identifier == PaymentMethod::iDeal)
        {
            $sql = "SELECT receiptId FROM  receipt WHERE paidDt IS NOT NULL AND paymentMethod='iDeal' AND paymentMethod IS NOT NULL AND parentSession='$sessionID'";
            $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

            if($db->connect_errno > 0)
            {
                die('Unable to connect to database [' . $db->connect_error . ']');
            }

            if(!$result = $db->query($sql))
            {
                die('Er was een fout tijdens het ophalen van bruto-omzet (PaymentMethod::iDeal) (' . $db->error . ')');
            }

            $final = 0.00;
            while($row = $result->fetch_assoc())
            {
                //Marge is totale prijs min belasting min inkoop prijs
                $receipt = Misc::sqlGet("items", "receipt", "receiptId", $row["receiptId"]);
                $json = json_decode(urldecode($receipt['items']), TRUE);

                foreach ($json as $key => $val)
                {
                    $itemPrice = Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . " " . $val['priceAPiece']['priceModifier']);
                    $itemMargin = $itemPrice - (round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) - $val['priceAPiece']['priceExclVat']) - $val['priceAPiece']['priceExclVat'];
                    $final += round($itemMargin * $val['count'], 2);
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
            $final['total'] += (Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']) * $val['count']);
            $final['exclVat'] += round($val['priceAPiece']['priceExclVat'] *  $val['count'], 2);
        }
        return $final;
    }

    public static function getContractTotal($json, $session = false)
    {
        global $config;
        global $_CFG;

        $final = array();
        foreach ($json as $key => $val)
        {
            $final['total'] += (Misc::calculate(round($val['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . $val['priceAPiece']['priceModifier']) * $val['count']);
            $final['exclVat'] += round($val['priceAPiece']['priceExclVat'] *  $val['count'], 2);
        }
        return $final;
    }

    public static function calculateNextOrder($period, $day, $start, $nextTime = 0, $sendNow = false)
    {
        $now = new DateTime();
        $next = new DateTime();

        /// Strip off the time to prevent nasty issues
        $now =  new DateTime($now->format("Y-m-d"));
        $next =  new DateTime($next->format("Y-m-d"));

        if ($sendNow && $nextTime == 0)
            return new DateTime();

        if ($period == "year")
        {
            $next = new DateTime("+$nextTime year");
            $next = new DateTime($next->format('Y')."-".$next->format('m')."-".$day);

            if ($next < $now)
                $next = new DateTime();
        }
        else if ($period == "quarter")
        {
            $daysLeftQ = ceil(Quarters::getDaysLeftinQuarter(new DateTime()));
            $diff = ceil(Quarters::monthDiff($next, $start));
            
            $next = $next->modify("+ $daysLeftQ days");
            $next = $next->modify("+ $diff months");
            $next = $next->modify("- 1 days");
            $next = $next->modify('+ 1 month'); // Correct the diff month returning 1 too less (Always)

            $next = new DateTime($next->format('Y')."-".($next->format('m'))."-".$day);
            
            if ($nextTime > 0 && $sendNow)
            {
                $next = $next->modify('+ '.(92*($nextTime-1)).' days');
                $next = new DateTime($next->format('Y')."-".($next->format('m'))."-".$day);
            }
            else if ($nextTime > 0)
            {
                $next = $next->modify('+ '.(92*($nextTime)).' days');
                $next = new DateTime($next->format('Y')."-".($next->format('m'))."-".$day);
            }
            else if ($nextTime == 0 && $sendNow)
            {
                $next = new DateTime();
            }
        }
        else // Month
        {
            $next = new DateTime("+$nextTime month");
            $next = new DateTime($next->format('Y')."-".$next->format('m')."-".$day);

            $diff = $next->diff($now);
            $diffDays = (integer)$diff->format( "%R%a" );

            if ($diffDays == 0)
            {
                $next = $now;
            }
            
            if ($next < $now)
            {
                $next = new DateTime("+".($nextTime+1)." month");
                $next = new DateTime($next->format('Y')."-".$next->format('m')."-".$day);
            }
            
            if (new DateTime($now->format('Y')."-".$now->format('m')."-".$day) < $now && !$sendNow)
            {
                $next = new DateTime("+".($nextTime+1)." month");
                $next = new DateTime($next->format('Y')."-".$next->format('m')."-".$day);
            }
        }

        /// Check if the start date is later than today except for yearly 
        if ($start > $now && $period == "year")
        {
            $start = new DateTime(date("Y-m-d", strtotime("+ $nextTime year", strtotime($start->format("Y-m-d H:i:s")))));
            $start = new DateTime($start->format('Y')."-".$start->format('m')."-".$day);
            
            return $start; // The start date is later than today so we will set the next date to be the start date.
        }
        else if ($start > $now && $period == "month")
        {
            $start = new DateTime(date("Y-m-d", strtotime("+ $nextTime month", strtotime($start->format("Y-m-d H:i:s")))));
            $start = new DateTime($start->format('Y')."-".$start->format('m')."-".$day);
                
            return $start; // The start date is later than today so we will set the next date to be the start date.
        }
        else
            return $next; // The start date has already passed so the next date will be as planned.
    }
}
