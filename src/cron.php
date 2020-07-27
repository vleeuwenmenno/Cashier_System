<?php
    include_once("includes.php");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Load Composer's autoloader
    require 'vendor/autoload.php';

    function sendOrder($cid, $orderDate)
    {
        global $config;
        global $_CFG;

        /// Get customer info
        $custid = Misc::sqlGet("customerId", "contract", "contractId", $cid)['customerId'];
        $cust = Misc::sqlGet("*", "customers", "customerId", $custid);
        $email = $cust['email'];
        $items = Misc::sqlGet("items", "contract", "contractId", $cid)['items'];
        $total = Calculate::getContractTotal(json_decode(urldecode($items), true), true)['total'];

        /// Save new log entry
        $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

        if($db->connect_errno > 0)
        {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        //Create the recept (ALTER TABLE receipt AUTO_INCREMENT = 20170000)
        $sql = "INSERT INTO log (contractId, orderDate, customerId, receiverEmail, items, total) VALUES ('$cid', '$orderDate', '$custid', '$email', '$items', '$total')";
        
        if(!$result = $db->query($sql))
        {
            die('There was an error running the query [' . $db->error . ']');
        }

        $lid = mysqli_insert_id($db);
    
        /// Get the PDF
        $content = Misc::url_get_contents('http://cashier.local/pdf/?cid=' . $cid . '&lid=' . $lid . '&exvat');
        file_put_contents(getcwd() . "/temp/factuur-" . $cid . "-".$lid.".pdf", $content);
        
        /// Setup the email
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = $_CFG['smtpHost'];
        $mail->SMTPAuth = true;
        $mail->Username = $_CFG['smtpUser'];
        $mail->Password = $_CFG['smtpPass'];
        $mail->SMTPSecure = $_CFG['smtpSecure'];
        $mail->Port = $_CFG['smtpPort'];

        ///TODO: SET CORRECT EMAIL AND REANBLE BCC
        $mail->setFrom($_CFG['smtpUser'], 'Com Today');
        $mail->addAddress($email, $cust['initials'] . ' ' . $cust['familyName']);
        //$mail->addBCC($_CFG['smtpName']);

        $mail->addAttachment(getcwd() . "/temp/factuur-" . $cid . "-".$lid.".pdf");
        $mail->isHTML(true);

        $mail->Subject = 'Uw factuur';
        $mail->Body    = 'Geachte klant,<br /><br />

                            De bijlage bevat uw factuur.<br />
                            Zorg ervoor dat u het totaalbedrag van deze factuur voor de vervaldatum heeft overgemaakt naar '.$_CFG['companyIBAN'].' ten name van '.$_CFG['COMPANY_NAME'].', onder vermelding van het factuurnummer.<br /><br />

                            Met vriendelijke groeten,<br /><br />

                            <b>'.$_CFG['COMPANY_NAME'].'</b><br />
                            '.str_replace(",", "<br/>", $_CFG['companyAddress']).'<br />
                            '.$_CFG['companyPhone'].'<br />
                            '.$_CFG['companyEmail'].'<br />';

        if(!$mail->send())
        {
            Misc::sqlUpdate("log", "success", 0, "logId", $lid);
            Misc::sqlUpdate("log", "notes", "Mail fout: {$mail->ErrorInfo}", "logId", $lid);
            ?>
            FAILED - Contract ID: <?=$cid?> Log ID: <?=$lid?><br/>
            <?php echo "Mail fout: {$mail->ErrorInfo}";
        }
        else
        {
            Misc::sqlUpdate("log", "success", 1, "logId", $lid);
            ?>
            SUCCESS<br/>
            <?php
        }
        unlink(getcwd() . "/temp/factuur-" . $cid . "-".$lid.".pdf");
    }

    $dbs = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

    /// Check if we have past logs that failed
    $sqls = "SELECT * FROM log WHERE success=0;";                        

    if(!$results = $dbs->query($sqls))
    {
        die('Er was een fout tijdens het uitvoeren van deze query (' . $dbs->error . ') (' . $sqls . ')');
    }

    /// Find all logs that failed to send
    while($rows = $results->fetch_assoc())
    {
        $rows['sendOrderNow'] = Misc::sqlGet("sendOrderNow", "contract", "contractId", $rows['contractId'])['sendOrderNow'];
        $rows['startDate'] = Misc::sqlGet("startDate", "contract", "contractId", $rows['contractId'])['startDate'];
        $rows['planningPeriod'] = Misc::sqlGet("planningPeriod", "contract", "contractId", $rows['contractId'])['planningPeriod'];
        $rows['planningDay'] = Misc::sqlGet("planningDay", "contract", "contractId", $rows['contractId'])['planningDay'];

        $startDate = new DateTime($rows['startDate']);                                
        $time = Calculate::calculateNextOrder($rows['planningDay'], $rows['planningDay'], $startDate, 0, $rows['sendOrderNow']);
        $time->setTime( 0, 0, 0 );

        $today = new DateTime();
        $today->setTime( 0, 0, 0 );

        $diff = $today->diff($time);
        $diffDays = (integer)$diff->format("%R%a");

        /// If its not 0 it means we should check if we need to retry sending one out today!
        if ($diffDays != 0)
        {
            echo "Contract #".$rows['contractId']." was ingepland voor factuur datum ".$rows['orderDate'].' maar versturen was mislukt.<br/>';
            sendOrder($rows['contractId'], $rows['orderDate']);
        }
    }

    $sqls = "SELECT * FROM contract WHERE 1;";                 
    if($dbs->connect_errno > 0)
    {
        die('Unable to connect to database [' . $dbs->connect_error . ']');
    }

    if(!$results = $dbs->query($sqls))
    {
        die('Er was een fout tijdens het uitvoeren van deze query (' . $dbs->error . ') (' . $sqls . ')');
    }
    
    /// Check contracts that were supposed to be send last month, but havent been.
    while($rows = $results->fetch_assoc())
    {
        $today = new DateTime();
        $today->setTime( 0, 0, 0 );
        $cid = $rows['contractId'];
        $pd = str_pad($rows['planningDay'], 2, '0', STR_PAD_LEFT);

        for ($i = 0; $i < 6; $i++)
        {
            $date = $today->format("Y").'-'.(str_pad(($today->format("m")-$i), 2, '0', STR_PAD_LEFT)).'-'.$pd;

            if (($today->format("m")-1) <= 0)
                $date = ($today->format("Y")-1).'-01-'.$pd;

            /// Check if the start date is older than last months iteration
            if (new DateTime($rows['startDate']) < new DateTime($date) && new DateTime($date) < $today)
            {
                /// Check if there was an attempt to send it last month
                $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);
                $sql = "SELECT * FROM log WHERE contractId=$cid AND orderDate='$date';";
                
                if($db->connect_errno > 0)
                {
                    die('Unable to connect to database [' . $db->connect_error . ']');
                }

                if(!$result = $db->query($sql))
                {
                    die('Er was een fout tijdens het uitvoeren van deze query (' . $db->error . ') (' . $sql . ')');
                }

                /// Check if it was already attempted to send for this order date
                $found = false;
                while($row = $result->fetch_assoc())
                {
                    $found = true;
                }

                if (!$found)
                {
                    echo "Contract #$cid was ingepland voor ".($i+1)." maand(en) terug op factuur datum $date maar was nooit verstuurd, wordt nu verstuurd ... ";
                    sendOrder($rows['contractId'], $date);
                }
            }
        }
    }

    $sqls = "SELECT * FROM contract WHERE 1;";                 
    if($dbs->connect_errno > 0)
    {
        die('Unable to connect to database [' . $dbs->connect_error . ']');
    }

    if(!$results = $dbs->query($sqls))
    {
        die('Er was een fout tijdens het uitvoeren van deze query (' . $dbs->error . ') (' . $sqls . ')');
    }
    
    ///Find all contracts
    while($rows = $results->fetch_assoc())
    {        
        $startDate = new DateTime($rows['startDate']);                                
        $time = Calculate::calculateNextOrder($rows['planningPeriod'], $rows['planningDay'], $startDate, 0, $rows['sendOrderNow']);
        $time->setTime( 0, 0, 0 );

        $today = new DateTime();
        $today->setTime( 0, 0, 0 );

        $diff = $today->diff($time);
        $diffDays = (integer)$diff->format("%R%a");

        /// Check if their send date time is 0 days from now
        if ($diffDays == 0)
        {
            $cid = $rows['contractId'];
            $orderDate = $today->format("Y-m-d");
            $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);
            $sql = "SELECT * FROM log WHERE contractId=$cid AND orderDate='$orderDate';";

            if($db->connect_errno > 0)
            {
                die('Unable to connect to database [' . $db->connect_error . ']');
            }

            if(!$result = $db->query($sql))
            {
                die('Er was een fout tijdens het uitvoeren van deze query (' . $db->error . ') (' . $sql . ')');
            }

            Misc::sqlUpdate("contract", "sendOrderNow", 0, "contractId", $cid);
            echo "Contract #$cid is ingepland voor vandaag ";

            /// Check if it was already attempted to send for this order date
            $found = false;
            while($row = $result->fetch_assoc())
            {     
                $found = true;
                echo ' en is verzonden';

                /// If it failed to send try sending it again!
                if ($row['success'] == false)
                {
                    echo ' maar verzenden was mislukt, wordt nu opnieuw verstuurd ... ';
                    sendOrder($row['contractId'], $orderDate);
                }
                else
                    echo '.<br/>';
            }

            /// If we haven't found one it means we must send it as today is the day to do so.
            if (!$found)
            {
                echo "wordt nu verstuurd ... ";
                sendOrder($rows['contractId'], $orderDate);
            }
        }
    }
    echo 'Klaar!<br />';