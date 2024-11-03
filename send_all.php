<?php
require "dbconn.php";

$betreff = $_POST['Betreff'];
$inhalt = $_POST['Inhalt'];

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

$sqlSelectEmailName = "SELECT * FROM `Mitarbeiter` WHERE Angestellt = 1";

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

    while ($row = $resultSelectEmailName->fetch_assoc())
    {
        $empfaenger = $row['Email'];
        $mail->addAddress($empfaenger);
    }
    // $mail->addAddress('emin@paywithcharlie.com');

    // $betreff = 'Erinnerung: Einreichung der Anträge bis zum '.$Abgabe.'.'.date('m').'. um 8:00 Uhr';

    // $inhalt = 'Liebes Team,<br><br>

    // ich möchte euch daran erinnern, eure Anträge bis spätestens zum '.$Abgabe.'.'.date('m').'. um 08:00 Uhr einzureichen.<br>
    
    // Bei Fragen steht Nicole zur Verfügung.<br><br>
    
    // Vielen Dank<br><br>
    
    // Die Zeiterfassung';

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $betreff; 
    $mail->Body    = $inhalt;

    $mail->send();
    echo 'Die E-Mail wurde versendet.    <p>
    <a href="webterminal.php"><button>zurück</button></a>
</p>';
} catch (Exception $e) {
    echo "E-Mail konnte nicht versendet werden. Error: {$mail->ErrorInfo}";
}
