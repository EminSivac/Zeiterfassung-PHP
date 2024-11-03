<?php
require "dbconn.php";

function abfrage($sql)
{
    $result = $GLOBALS['conn']->query($sql);
    return $result;
}

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

$sqlSelectEmailName = "SELECT * FROM `Entwickler`";

$resultSelectEmailName = abfrage($sqlSelectEmailName);

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

    while ($row = $resultSelectEmailName->fetch_assoc()) {
        $empfaenger = $row['Email'];
        $mail->addAddress($empfaenger);
    }

    $betreff = 'Zeiterfassung Error';

    $inhalt = 'Liebe Devs,<br><br>

    Dieser Fehler ist heute erschienen.<br><br>'.
    
    $error.'<br><br>
    
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
