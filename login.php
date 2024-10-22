<?php
include "dbconn.php";

$sqlGetMitarbeiter = "SELECT * FROM `Mitarbeiter`";
$resultGetMitarbeiter = $conn->query($sqlGetMitarbeiter);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" action="webterminalLogin.php">
    <label for="auswahl">Wähle eine Option:</label>
    <select name="MitarbeiterID" id="MitarbeiterID">
        <?php
        if ($resultGetMitarbeiter->num_rows > 0) {
            // Daten der einzelnen Zeilen ausgeben
            while($row = $resultGetMitarbeiter->fetch_assoc()) {
                echo '<option value="' . $row["MitarbeiterID"] . '">'.$row["Vorname"]." ".$row["Nachname"].'</option>';
            }
        } else {
            echo '<option value="">Keine Ergebnisse</option>';
        }
        ?>
    </select>
    <label for="auswahl">Wähle eine Option:</label>
    <select name="PIN" id="PIN">
        <?php
        $resultGetMitarbeiter = $conn->query($sqlGetMitarbeiter);
        if ($resultGetMitarbeiter->num_rows > 0) {
            // Daten der einzelnen Zeilen ausgeben
            while($row = $resultGetMitarbeiter->fetch_assoc()) {
                echo '<option value="' . $row["PIN"] . '">'.$row["Vorname"]." ".$row["Nachname"].'</option>';
            }
        } else {
            echo '<option value="">Keine Ergebnisse</option>';
        }
        ?>
    </select>
    <input type="submit" value="Absenden">
    </form>
</body>
</html>