<?php
// auteur: Àkram D
// functie: insert class Klant

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

// Klant class definition
class Klant extends Database {
    public $klantId;
    public $klantemail = null;
    public $klantnaam;
    public $klantwoonplaats;
    private $table_name = "Klant";    

    public function __construct() {
        parent::__construct();
    }

    /**
     * Insert a new klant
     * @param array $row
     * @return bool
     */
    public function insertKlant($row) : bool {
        // Bepaal een unieke klantId
        $klantId = $this->BepMaxKlantId();

        // SQL query
        $sql = "INSERT INTO $this->table_name (klantId, klantEmail, klantNaam, klantAdres, klantPostcode, klantWoonplaats) 
                VALUES (:klantId, :klantEmail, :klantNaam, :klantAdres, :klantPostcode, :klantWoonplaats)";

        // Prepare
        $stmt = self::$conn->prepare($sql);

        // Execute
        return $stmt->execute([
            ':klantId' => $klantId,
            ':klantEmail' => $row['klantEmail'],
            ':klantNaam' => $row['klantNaam'],
            ':klantAdres' => $row['klantAdres'],
            ':klantPostcode' => $row['klantPostcode'],
            ':klantWoonplaats' => $row['klantWoonplaats']
        ]);
    }

    /**
     * Bepaal het hoogste klantId en verhoog dit met 1
     * @return int
     */
    private function BepMaxKlantId() : int {
        // Bepaal uniek nummer
        $sql = "SELECT MAX(klantId) + 1 FROM $this->table_name";
        return (int) self::$conn->query($sql)->fetchColumn();
    }
}

// Handling form submission
if (isset($_POST["insert"]) && $_POST["insert"] == "Toevoegen") {
    // Initialize the Klant class
    $klant = new Klant();

    // Prepare the data array
    $data = [
        'klantNaam' => $_POST['klantnaam'],
        'klantEmail' => $_POST['klantemail'],
        'klantAdres' => $_POST['klantadres'],
        'klantPostcode' => $_POST['klantpostcode'],
        'klantWoonplaats' => $_POST['klantwoonplaats']
    ];

    // Insert the klant into the database
    $inserted = $klant->insertKlant($data);

    // Check if the insertion was successful
    if ($inserted) {
        echo "Klant succesvol toegevoegd!";
    } else {
        echo "Er is een fout opgetreden bij het toevoegen van de klant.";
    }
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
    <h2>Toevoegen</h2>
    <form method="post">
        <label for="nv">Klantnaam:</label>
        <input type="text" id="nv" name="klantnaam" placeholder="Klantnaam" required/>
        <br>   
        <label for="an">Klantemail:</label>
        <input type="email" id="an" name="klantemail" placeholder="Klantemail" required/>
        <br>  
        <label for="adres">Klantadres:</label>
        <input type="text" id="adres" name="klantadres" placeholder="Klantadres" required/>
        <br>
        <label for="postcode">Klantpostcode:</label>
        <input type="text" id="postcode" name="klantpostcode" placeholder="Klantpostcode" required/>
        <br>
        <label for="woonplaats">Klantwoonplaats:</label>
        <input type="text" id="woonplaats" name="klantwoonplaats" placeholder="Klantwoonplaats" required/>
        <br><br>
        <input type='submit' name='insert' value='Toevoegen'>
    </form><br>

    <a href='read.php'>Terug</a>

</body>
</html>
