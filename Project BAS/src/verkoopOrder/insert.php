<?php
// auteur: Àkram D
// functie: Verkooporder class definition and form handling

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

// Verkooporder class definition
class Verkooporder extends Database {
    public $verkOrdId;
    public $klantId;
    public $artId;
    public $verkOrdDatum;
    public $verkOrdBestAantal;
    public $verkOrdStatus;
    private $table_name = "verkooporder";

    public function __construct() {
        parent::__construct();
    }

    /**
     * Insert a new verkooporder
     * @param array $row
     * @return bool
     */
    public function insertVerkooporder($row) : bool {
        // Bepaal een unieke verkOrdId
        $verkOrdId = $this->BepMaxVerkOrdId();

        // SQL query
        $sql = "INSERT INTO $this->table_name (verkOrdId, klantId, artId, verkOrdDatum, verkOrdBestAantal, verkOrdStatus) 
                VALUES (:verkOrdId, :klantId, :artId, :verkOrdDatum, :verkOrdBestAantal, :verkOrdStatus)";

        // Prepare
        $stmt = self::$conn->prepare($sql);

        // Execute
        return $stmt->execute([
            ':verkOrdId' => $verkOrdId,
            ':klantId' => $row['klantId'],
            ':artId' => $row['artId'],
            ':verkOrdDatum' => $row['verkOrdDatum'],
            ':verkOrdBestAantal' => $row['verkOrdBestAantal'],
            ':verkOrdStatus' => $row['verkOrdStatus']
        ]);
    }

    /**
     * Bepaal het hoogste verkOrdId en verhoog dit met 1
     * @return int
     */
    private function BepMaxVerkOrdId() : int {
        // Bepaal uniek nummer
        $sql = "SELECT MAX(verkOrdId) + 1 FROM $this->table_name";
        return (int) self::$conn->query($sql)->fetchColumn();
    }
}

// Handling form submission
if (isset($_POST["insert"]) && $_POST["insert"] == "Toevoegen") {
    // Initialize the Verkooporder class
    $verkooporder = new Verkooporder();

    // Prepare the data array
    $data = [
        'klantId' => $_POST['klantId'],
        'artId' => $_POST['artId'],
        'verkOrdDatum' => $_POST['verkOrdDatum'],
        'verkOrdBestAantal' => $_POST['verkOrdBestAantal'],
        'verkOrdStatus' => $_POST['verkOrdStatus']
    ];

    // Insert the verkooporder into the database
    $inserted = $verkooporder->insertVerkooporder($data);

    // Check if the insertion was successful
    if ($inserted) {
        echo "Verkooporder succesvol toegevoegd!";
    } else {
        echo "Er is een fout opgetreden bij het toevoegen van de verkooporder.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Verkooporder</title>
    <link rel="stylesheet" href="../stylee.css">
</head>
<body>

    <h1>CRUD Verkooporder</h1>
    <h2>Toevoegen</h2>
    <form method="post">
        <label for="klantId">Klant ID:</label>
        <input type="number" id="klantId" name="klantId" placeholder="Klant ID" required/>
        <br>   
        <label for="artId">Artikel ID:</label>
        <input type="number" id="artId" name="artId" placeholder="Artikel ID" required/>
        <br>  
        <label for="verkOrdDatum">Besteldatum:</label>
        <input type="date" id="verkOrdDatum" name="verkOrdDatum" placeholder="Besteldatum" required/>
        <br>
        <label for="verkOrdBestAantal">Bestelaantal:</label>
        <input type="number" id="verkOrdBestAantal" name="verkOrdBestAantal" placeholder="Bestelaantal" required/>
        <br>
        <label for="verkOrdStatus">Status:</label>
        <input type="number" id="verkOrdStatus" name="verkOrdStatus" placeholder="Status" required/>
        <br><br>
        <input type='submit' name='insert' value='Toevoegen'>
    </form><br>

    <a href='read.php'>Terug</a>

</body>
</html>
