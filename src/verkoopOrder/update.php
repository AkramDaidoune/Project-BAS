<?php
// auteur: Akram D
// functie: update verkooporder

// Autoloader classes via composer
require '../../vendor/autoload.php';
use Bas\classes\VerkoopOrder;

$verkooporder = new VerkoopOrder();
$message = "";

if (isset($_POST["update"]) && $_POST["update"] == "Wijzigen") {
    $requiredFields = [
        'verkOrdId', 'klantId', 'artId', 'verkOrdDatum', 'verkOrdBestAantal', 'verkOrdStatus'
    ];
    $isFormValid = true;

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $isFormValid = false;
            break;
        }
    }

    if ($isFormValid) {
        $verkoopordergegevens = [
            'verkOrdId' => intval($_POST['verkOrdId']),
            'klantId' => intval($_POST['klantId']),
            'artId' => intval($_POST['artId']),
            'verkOrdDatum' => $_POST['verkOrdDatum'],
            'verkOrdBestAantal' => intval($_POST['verkOrdBestAantal']),
            'verkOrdStatus' => $_POST['verkOrdStatus']
        ];

        if ($verkooporder->updateVerkoopOrder($verkoopordergegevens)) {
            $message = "Verkooporder succesvol gewijzigd!";
        } else {
            $message = "Er is een fout opgetreden bij het wijzigen van de verkooporder.";
        }
    } else {
        $message = "Vul alstublieft alle vereiste velden in.";
    }
}

if (isset($_GET['verkOrdId'])) {
    $row = $verkooporder->getVerkoopOrder($_GET['verkOrdId']);
    $klanten = $verkooporder->getKlanten();
    $artikelen = $verkooporder->getArtikelen();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wijzigen Verkooporder</title>
    <link rel="stylesheet" href="../verkoopOrder/styleVerkoopOrder.css">
</head>
<body>
<h1>CRUD Verkooporder</h1>
<h2>Wijzigen</h2>
<h3>Water</h3>
<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>
<form method="post">
    <input type="hidden" name="verkOrdId" 
        value="<?php if (isset($row)) { echo $row['verkOrdId']; } ?>">
    <label for="klantId">Klant:</label>
    <select id="klantId" name="klantId" required>
        <option value="">Selecteer Klant</option>
        <?php foreach ($klanten as $klant): ?>
            <option value="<?php echo $klant['klantId']; ?>" <?php if (isset($row) && $row['klantId'] == $klant['klantId']) { echo 'selected'; } ?>><?php echo $klant['klantNaam']; ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <label for="artId">Artikel:</label>
    <select id="artId" name="artId" required>
        <option value="">Selecteer Artikel</option>
        <?php foreach ($artikelen as $artikel): ?>
            <option value="<?php echo $artikel['artId']; ?>" <?php if (isset($row) && $row['artId'] == $artikel['artId']) { echo 'selected'; } ?>><?php echo $artikel['artOmschrijving']; ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <h3>Verkoopordergegevens</h3>
    <label for="verkOrdDatum">Verkooporder Datum:</label>
    <input type="date" id="verkOrdDatum" name="verkOrdDatum" required value="<?php if (isset($row)) { echo $row['verkOrdDatum']; } ?>"/>
    <br>
    <label for="verkOrdBestAantal">Verkooporder Aantal:</label>
    <input type="number" id="verkOrdBestAantal" name="verkOrdBestAantal" required value="<?php if (isset($row)) { echo $row['verkOrdBestAantal']; } ?>"/>
    <br>
    <label for="verkOrdStatus">Verkooporder Status:</label>
    <select id="verkOrdStatus" name="verkOrdStatus" required>
        <option value="">Selecteer Status</option>
        <option value="Bezorgd" <?php if (isset($row) && $row['verkOrdStatus'] == 'Bezorgd') { echo 'selected'; } ?>>Bezorgd</option>
        <option value="Onderweg" <?php if (isset($row) && $row['verkOrdStatus'] == 'Onderweg') { echo 'selected'; } ?>>Onderweg</option>
    </select>
    <br><br>
    <input type="submit" name="update" value="Wijzigen">
</form></br>

<a href="read.php">Terug</a>

</body>
</html>

<?php
} else {
    echo "Geen verkOrdId opgegeven<br>";
}
?>
