<?php
// Declare variables


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

    $mail->addAddress('ngross@ziib.de');

    $betreff = 'Erinnerung: Bearbeiten von Anträgen';

    $inhalt = 'Liebe Nicole,<br><br>

    ich möchte dich freundlich daran erinnern, die ausstehenden Anträge zu bearbeiten.<br>
    Es handelt sich hierbei um eine automatisierte Erinnerung.<br><br>
        
    Vielen Dank<br><br>
    
    Das Zeiterfassungssystem';

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $betreff; 
    $mail->Body    = $inhalt;

    $mail->send();
    // echo ' Eine E-Mail wurde versendet.';
} catch (Exception $e) {
    echo "E-Mail konnte nicht versendet werden. Error: {$mail->ErrorInfo}";
}
