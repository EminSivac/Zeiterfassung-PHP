<?php
require "dbconn.php";

function abfrage($sql)
{
    $result = $GLOBALS['conn']->query($sql);
    return $result;
}
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings

    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'ziibpost@gmail.com';                     //SMTP username
    $mail->Password   = 'qnzbjgvcjtnxwnqq';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;  
    $mail->CharSet = 'UTF-8';                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    $mail->setFrom('ziibpost@gmail.com', 'ZIIB');

    $mail->addAddress('team@ziib.de');

    $betreff = 'Erinnerung: Einreichung der Anträge bis zum '.$Abgabe.'.'.date('m').'. um 8:00 Uhr';

    $inhalt = 'Liebes Team,<br><br>

    ich möchte euch daran erinnern, eure Anträge bis spätestens zum '.$Abgabe.'.'.date('m').'. um 08:00 Uhr einzureichen.<br>
    
    Bei Fragen steht Nicole zur Verfügung.<br><br>
    
    Vielen Dank<br><br>
    
    Die Zeiterfassung';

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $betreff; 
    $mail->Body    = $inhalt;

    $mail->send();
    // echo ' Eine E-Mail wurde versendet.';
} catch (Exception $e) {
    echo "E-Mail konnte nicht versendet werden. Error: {$mail->ErrorInfo}";
}



$mailA = new PHPMailer(true);

try {
    //Server settings

    $mailA->isSMTP();                                            //Send using SMTP
    $mailA->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mailA->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mailA->Username   = 'ziibpost@gmail.com';                     //SMTP username
    $mailA->Password   = 'qnzbjgvcjtnxwnqq';                               //SMTP password
    $mailA->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mailA->Port       = 465;  
    $mailA->CharSet = 'UTF-8';                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    $mailA->setFrom('ziibpost@gmail.com', 'ZIIB');

    $mailA->addAddress('ngross@ziib.de');

    $betreff = 'Erinnerung: Bearbeiten von Anträgen';

    $inhalt = 'Liebe Nicole,<br><br>

    ich möchte dich freundlich daran erinnern, die ausstehenden Anträge zu bearbeiten.<br>
    Es handelt sich hierbei um eine automatisierte Erinnerung.<br><br>
        
    Vielen Dank<br><br>
    
    Das Zeiterfassungssystem';

    //Content
    $mailA->isHTML(true);                                  //Set email format to HTML
    $mailA->Subject = $betreff; 
    $mailA->Body    = $inhalt;

    $mailA->send();
    // echo ' Eine E-Mail wurde versendet.';
} catch (Exception $e) {
    echo "E-Mail konnte nicht versendet werden. Error: {$mailA->ErrorInfo}";
}

