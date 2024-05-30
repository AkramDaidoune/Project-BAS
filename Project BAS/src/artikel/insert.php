<?php
// auteur: Àkram D
// functie: insert class Artikel

// Autoloader classes via composer
require '../../vendor/autoload.php';

// Database class for managing the connection
class Database {
    protected static $conn;

    public function __construct() {
        if (!self::$conn) {
            try {
                self::$conn = new PDO('mysql:host=localhost;dbname=bas', 'root', '');
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    }
}

// Artikel class definition
class Artikel extends Database {
    public $artId;
    public $artOmschrijving;
    public $artInkoop;
    public $artVerkoop;
    public $artVoorraad;
    public $artMinVoorraad;
    public $artMaxVoorraad;
    public $artLocatie;
    private $table_name = "Artikel";

    public function __construct() {
        parent::__construct();
    }

    /**
     * Insert a new artikel
     * @param array $row
     * @return bool
     */
    public function insertArtikel($row) : bool {
        // Bepaal een unieke artId
        $artId = $this->BepMaxArtId();

        // SQL query
        $sql = "INSERT INTO $this->table_name (artId, artOmschrijving, artInkoop, artVerkoop, artVoorraad, artMinVoorraad, artMaxVoorraad, artLocatie) 
                VALUES (:artId, :artOmschrijving, :artInkoop, :artVerkoop, :artVoorraad, :artMinVoorraad, :artMaxVoorraad, :artLocatie)";

        // Prepare
        $stmt = self::$conn->prepare($sql);

        // Execute
        return $stmt->execute([
            ':artId' => $artId,
            ':artOmschrijving' => $row['artOmschrijving'],
            ':artInkoop' => $row['artInkoop'],
            ':artVerkoop' => $row['artVerkoop'],
            ':artVoorraad' => $row['artVoorraad'],
            ':artMinVoorraad' => $row['artMinVoorraad'],
            ':artMaxVoorraad' => $row['artMaxVoorraad'],
            ':artLocatie' => $row['artLocatie']
        ]);
    }

    /**
     * Bepaal het hoogste artId en verhoog dit met 1
     * @return int
     */
    private function BepMaxArtId() : int {
        // Bepaal uniek nummer
        $sql = "SELECT MAX(artId) + 1 FROM $this->table_name";
        return (int) self::$conn->query($sql)->fetchColumn();
    }
}

// Handling form submission
if (isset($_POST["insert"]) && $_POST["insert"] == "Toevoegen") {
    // Initialize the Artikel class
    $artikel = new Artikel();

    // Prepare the data array
    $data = [
        'artOmschrijving' => $_POST['artOmschrijving'],
        'artInkoop' => $_POST['artInkoop'],
        'artVerkoop' => $_POST['artVerkoop'],
        'artVoorraad' => $_POST['artVoorraad'],
        'artMinVoorraad' => $_POST['artMinVoorraad'],
        'artMaxVoorraad' => $_POST['artMaxVoorraad'],
        'artLocatie' => $_POST['artLocatie']
    ];

    // Insert the artikel into the database
    $inserted = $artikel->insertArtikel($data);

    // Check if the insertion was successful
    if ($inserted) {
        echo "Artikel succesvol toegevoegd!";
    } else {
        echo "Er is een fout opgetreden bij het toevoegen van het artikel.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <link rel="stylesheet" href="../stylee.css">
</head>
<body>

    <h1>CRUD Artikel</h1>
    <h2>Toevoegen</h2>
    <form method="post">
        <label for="omschrijving">Artikelomschrijving:</label>
        <input type="text" id="omschrijving" name="artOmschrijving" placeholder="Artikelomschrijving" required/>
        <br>
        <label for="inkoop">Inkoopprijs:</label>
        <input type="number" id="inkoop" name="artInkoop" placeholder="Inkoopprijs" step="0.01" required/>
        <br>
        <label for="verkoop">Verkoopprijs:</label>
        <input type="number" id="verkoop" name="artVerkoop" placeholder="Verkoopprijs" step="0.01" required/>
        <br>
        <label for="voorraad">Voorraad:</label>
        <input type="number" id="voorraad" name="artVoorraad" placeholder="Voorraad" required/>
        <br>
        <label for="minvoorraad">Minimale Voorraad:</label>
        <input type="number" id="minvoorraad" name="artMinVoorraad" placeholder="Minimale Voorraad" required/>
        <br>
        <label for="maxvoorraad">Maximale Voorraad:</label>
        <input type="number" id="maxvoorraad" name="artMaxVoorraad" placeholder="Maximale Voorraad" required/>
        <br>
        <label for="locatie">Locatie:</label>
        <input type="number" id="locatie" name="artLocatie" placeholder="Locatie" required/>
        <br><br>
        <input type='submit' name='insert' value='Toevoegen'>
    </form><br>

    <a href='read.php'>Terug</a>

</body>
</html>
