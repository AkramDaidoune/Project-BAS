<?php
// auteur: Akram D
// functie: update class Klant

// Autoloader classes via composer
require '../../vendor/autoload.php';
use Bas\classes\Klant;

$klant = new Klant;

if (isset($_POST["update"]) && $_POST["update"] == "Wijzigen") {
    // Code voor een update
    $klantGegevens = [
        'klantId' => $_POST['klantId'],
        'klantNaam' => $_POST['klantNaam'],
        'klantEmail' => $_POST['klantEmail'],
        'klantAdres' => $_POST['klantAdres'],
        'klantPostcode' => $_POST['klantPostcode'],
        'klantWoonplaats' => $_POST['klantWoonplaats']
    ];

    if ($klant->updateKlant($klantGegevens)) {
        echo "Klant succesvol gewijzigd!";
    } else {
        echo "Er is een fout opgetreden bij het wijzigen van de klant.";
    }
}

if (isset($_GET['klantId'])) {
    $row = $klant->getKlant($_GET['klantId']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <link rel="stylesheet" href="../klant/styleKlant.css">
</head>
<body>
<h1>CRUD Klant</h1>
<h2>Wijzigen</h2>    
<form method="post">
<input type="hidden" name="klantId" value="<?php if (isset($row)) { echo $row['klantId']; } ?>">

<label for="klantNaam">Naam:</label>
<input type="text" id="klantNaam" name="klantNaam" required value="<?php if (isset($row)) { echo $row['klantNaam']; } ?>"><br>

<label for="klantEmail">Email:</label>
<input type="text" id="klantEmail" name="klantEmail" required value="<?php if (isset($row)) { echo $row['klantEmail']; } ?>"><br>

<label for="klantAdres">Adres:</label>
<input type="text" id="klantAdres" name="klantAdres" required value="<?php if (isset($row)) { echo $row['klantAdres']; } ?>"><br>

<label for="klantPostcode">Postcode:</label>
<input type="text" id="klantPostcode" name="klantPostcode" required value="<?php if (isset($row)) { echo $row['klantPostcode']; } ?>"><br>

<label for="klantWoonplaats">Woonplaats:</label>
<input type="text" id="klantWoonplaats" name="klantWoonplaats" required value="<?php if (isset($row)) { echo $row['klantWoonplaats']; } ?>"><br>

<input type="submit" name="update" value="Wijzigen">
</form><br>

<a href="read.php">Terug</a>

</body>
</html>

