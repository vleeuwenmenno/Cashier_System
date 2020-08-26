<?php
include_once("includes.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

Permissions::checkSession(basename($_SERVER['REQUEST_URI']));

if (isset($_GET['receipt']))
{
    if ($_GET['mail'] == "true")
    {
        if (file_exists(getcwd() . "/temp/factuur-" . $_GET['receipt'] . ".pdf"))
            unlink(getcwd() . "/temp/factuur-" . $_GET['receipt'] . ".pdf");
        
        $content = Misc::url_get_contents('http://cashier.local/pdf/?rid=' . $_GET['receipt']);
        file_put_contents(getcwd() . "/temp/factuur-" . $_GET['receipt'] . ".pdf", $content);

        $cust = Misc::sqlGet("*", "customers", "customerId", Misc::sqlGet("customerId", "receipt", "receiptId", $_GET['receipt'])['customerId']);

        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = $_CFG['smtpHost'];
        $mail->SMTPAuth = true;
        $mail->Username = $_CFG['smtpUser'];
        $mail->Password = $_CFG['smtpPass'];
        $mail->SMTPSecure = $_CFG['smtpSecure'];
        $mail->Port = $_CFG['smtpPort'];

        $mail->setFrom('info@comtoday.nl', 'Com Today');

        $object = json_decode(urldecode($_GET['mailList']), TRUE);
        $mail->addAddress($object[0], $cust['initials'] . ' ' . $cust['familyName']);

        if (!isset($_GET['nobcc']))
            $mail->addBCC($_CFG['smtpName']);

        for($i = 0; $i < count($object); $i++)
        {
            if ($i > 0)
                $mail->addAddress($object[$i], "");
        }

        $mail->addAttachment(getcwd() . "/temp/factuur-" . $_GET['receipt'] . ".pdf");
        $mail->isHTML(true);

        $mail->Subject = 'Uw factuur';
        $mail->Body    = 'Geachte klant,<br /><br />

                            Bedankt voor uw aankoop bij '.$_CFG['COMPANY_NAME'].'.<br />
                            De bijlage bevat uw factuur.<br /><br />

                            Wij wensen u veel plezier met uw aankoop<br /><br />

                            Met vriendelijke groeten,<br /><br />

                            <b>'.$_CFG['COMPANY_NAME'].'</b><br />
                            '.str_replace(",", "<br/>", $_CFG['companyAddress']).'<br />
                            '.$_CFG['companyPhone'].'<br />
                            '.$_CFG['companyEmail'].'<br />';

        if(!$mail->send())
        {
            ?>
            <?php echo 'MAIL_ERROR: ' . $mail->ErrorInfo; ?>
            <script>
            $(document).ready(function() {
                $.notify({
                    icon: 'fa fa-envelope-o fa-2x',
                    title: '<b>Mail NIET verstuurd</b><br / >',
                    message: 'De email is niet verstuurd naar de klant wegens een fout!. <br /><?php echo $mail->ErrorInfo; ?>'
                }, {
                    // settings
                    type: 'warning',
                    delay: 5000,
                    timer: 10,
                    placement: {
                        from: "bottom",
                        align: "right"
                    }
                });

                $(document).ready(function() {
                    $("#pageLoaderIndicator").fadeOut();
                });
            });
            </script>
            <?php
        }
        else
        {
            ?>
            <script>
            $(document).ready(function() {
                $.notify({
                    icon: 'fa fa-envelope-o fa-2x',
                    title: '<b>Mail verstuurd</b><br / >',
                    message: 'De email is succesvol verstuurd naar de klant.'
                }, {
                    // settings
                    type: 'success',
                    delay: 5000,
                    timer: 10,
                    placement: {
                        from: "bottom",
                        align: "right"
                    }
                });

                $(document).ready(function() {
                    $("#pageLoaderIndicator").fadeOut();
                });
            });
            </script>
            <?php
        }

        unlink(getcwd() . "/temp/factuur-" . $_GET['receipt'] . ".pdf");
    }
    ?>
    <script>
        $(document).ready(function() {
            $("#pageLoaderIndicator").fadeIn();
            $("#PageContent").load("pdf/pdf.php?rid=<?=$_GET['receipt']?>", function () {
                $("#pageLoaderIndicator").fadeOut();
            });
        });
    </script>
    <?php
}