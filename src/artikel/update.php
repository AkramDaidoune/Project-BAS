<?php
// auteur: Akram D
// functie: update class Artikel

// Autoloader classes via composer
require '../../vendor/autoload.php';
use Bas\classes\Artikel;

$artikel = new Artikel;

if (isset($_POST["update"]) && $_POST["update"] == "Wijzigen") {
    $artikelGegevens = [
        'artId' => $_POST['artikelId'],
        'artOmschrijving' => $_POST['artOmschrijving'],
        'artInkoop' => $_POST['artInkoop'],
        'artVerkoop' => $_POST['artVerkoop'],
        'artVoorraad' => $_POST['artVoorraad'],
        'artMinVoorraad' => $_POST['artMinVoorraad'],
        'artMaxVoorraad' => $_POST['artMaxVoorraad'],
        'artLocatie' => $_POST['artLocatie']
    ];

    if ($artikel->updateArtikel($artikelGegevens)) {
        echo "Artikel succesvol gewijzigd!";
    } else {
        echo "Er is een fout opgetreden bij het wijzigen van het artikel.";
    }
}

if (isset($_GET['artikelId'])) {
    $row = $artikel->getArtikel($_GET['artikelId']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <link rel="stylesheet" href="../artikel/styleArtikel.css">
</head>
<body>
<h1>CRUD Artikel</h1>
<h2>Wijzigen</h2>    
<form method="post">
<input type="hidden" name="artikelId" value="<?php if (isset($row)) { echo $row['artId']; } ?>">

<label for="artOmschrijving">Omschrijving:</label>
<select id="artOmschrijving" name="artOmschrijving" required>
    <option value="">Selecteer Omschrijving</option>
    <?php 
    $beschikbareArtikelen = $artikel->getBeschikbareArtikelen();
    foreach ($beschikbareArtikelen as $beschikbaar) {
        $selected = (isset($row) && $row['artOmschrijving'] == $beschikbaar['artOmschrijving']) ? 'selected' : '';
        echo "<option value='{$beschikbaar['artOmschrijving']}' $selected>{$beschikbaar['artOmschrijving']}</option>";
    }
    ?>
</select><br>

<label for="artInkoop">Inkoop:</label>
<input type="text" id="artInkoop" name="artInkoop" required value="<?php if (isset($row)) { echo $row['artInkoop']; } ?>"><br>

<label for="artVerkoop">Verkoop:</label>
<input type="text" id="artVerkoop" name="artVerkoop" required value="<?php if (isset($row)) { echo $row['artVerkoop']; } ?>"><br>

<label for="artVoorraad">Voorraad:</label>
<input type="text" id="artVoorraad" name="artVoorraad" required value="<?php if (isset($row)) { echo $row['artVoorraad']; } ?>"><br>

<label for="artMinVoorraad">Minimale Voorraad:</label>
<input type="text" id="artMinVoorraad" name="artMinVoorraad" required value="<?php if (isset($row)) { echo $row['artMinVoorraad']; } ?>"><br>

<label for="artMaxVoorraad">Maximale Voorraad:</label>
<input type="text" id="artMaxVoorraad" name="artMaxVoorraad" required value="<?php if (isset($row)) { echo $row['artMaxVoorraad']; } ?>"><br>

<label for="artLocatie">Locatie:</label>
<select id="artLocatie" name="artLocatie" required>
    <option value="">Selecteer Locatie</option>
    <?php 
    $beschikbareLocaties = $artikel->getBeschikbareLocaties();
    foreach ($beschikbareLocaties as $beschikbaar) {
        $selected = (isset($row) && $row['artLocatie'] == $beschikbaar['artLocatie']) ? 'selected' : '';
        echo "<option value='{$beschikbaar['artLocatie']}' $selected>{$beschikbaar['artLocatie']}</option>";
    }
    ?>
</select><br>

<input type="submit" name="update" value="Wijzigen">
</form><br>

<a href="read.php">Terug</a>

</body>
</html>


