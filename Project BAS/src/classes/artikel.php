<?php
// Auteur: Akram D
// Functie: definitie class Artikel
namespace Bas\classes;

use PDO;
use PDOException;
use Bas\classes\Database;

include_once "functions.php";

class Artikel extends Database {
    public $artId;
    public $artOmschrijving;
    public $artInkoop;
    public $artVerkoop;
    public $artVoorraad;
    public $artMinVoorraad;
    public $artLocatie;
    private $table_name = "artikel";

    // Methods

    /**
     * Summary of crudArtikel
     * @return void
     */
    public function crudArtikel() : void {
        // Haal alle artikelen op uit de database mbv de method getArtikelen()
        $lijst = $this->getArtikelen();

        // Print een HTML tabel van de lijst   
        $this->showTable($lijst);
    }

    /**
     * Summary of getArtikelen
     * @return array
     */
    public function getArtikelen() : array {
        try {
            $sql = "SELECT * FROM $this->table_name";
            $stmt = self::$conn->query($sql);
            $lijst = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $lijst;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Summary of getArtikel
     * @param int $artId
     * @return array
     */
    public function getArtikel(int $artId) : array {
        try {
            $sql = "SELECT * FROM $this->table_name WHERE artId = :artId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':artId', $artId, PDO::PARAM_INT);
            $stmt->execute();
            $lijst = $stmt->fetch(PDO::FETCH_ASSOC);

            return $lijst ?: [];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function dropDownArtikel($row_selected = -1){
        // Haal alle artikelen op uit de database mbv de method getArtikelen()
        $lijst = $this->getArtikelen();

        echo "<label for='Artikel'>Choose an article:</label>";
        echo "<select name='artId'>";
        foreach ($lijst as $row){
            if($row_selected == $row["artId"]){
                echo "<option value='$row[artId]' selected='selected'> Artikel $row[artId]</option>\n";
            } else {
                echo "<option value='$row[artId]'> Artikel $row[artId]</option>\n";
            }
        }
        echo "</select>";
    }

    /**
     * Summary of showTable
     * @param array $lijst
     * @return void
     */
    public function showTable(array $lijst) : void {
        $txt = "<table>";

        // Voeg de kolomnamen boven de tabel
        $txt .= "<tr>";
        $txt .= "<th>artId</th>";
        $txt .= "<th>artOmschrijving</th>";
        $txt .= "<th>artInkoop</th>";
        $txt .= "<th>artVerkoop</th>";
        $txt .= "<th>artVoorraad</th>";
        $txt .= "<th>artMinVoorraad</th>";
        $txt .= "<th>artLocatie</th>";
        $txt .= "</tr>";

        foreach($lijst as $row){
            $txt .= "<tr>";
            $txt .= "<td>" . htmlspecialchars($row["artId"] ?? '') . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["artOmschrijving"] ?? '') . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["artInkoop"] ?? '') . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["artVerkoop"] ?? '') . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["artVoorraad"] ?? '') . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["artMinVoorraad"] ?? '') . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["artLocatie"] ?? '') . "</td>";

            // Update
            // Wijzig knopje
            $txt .= "<td>
                <form method='post' action='update.php?artId={$row["artId"]}'>
                    <button name='update'>Wzg</button>
                </form>
            </td>";

            // Delete
            $txt .= "<td>
                <form method='post' action='delete.php?artId={$row["artId"]}'>
                    <button name='verwijderen'>Verwijderen</button>
                </form>
            </td>";
            $txt .= "</tr>";
        }
        $txt .= "</table>";
        echo $txt;
    }

    // Delete artikel
    /**
     * Summary of deleteArtikel
     * @param int $artId
     * @return bool
     */
    public function deleteArtikel(int $artId) : bool {
        try {
            // Doe een delete-query op basis van $artId
            $sql = "DELETE FROM $this->table_name WHERE artId = :artId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':artId', $artId, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function updateArtikel($row) : bool {
        // Voer de update van het artikel uit
        try {
            $sql = "UPDATE $this->table_name SET artOmschrijving = :artOmschrijving, artInkoop = :artInkoop, artVerkoop = :artVerkoop, artVoorraad = :artVoorraad, artMinVoorraad = :artMinVoorraad, artLocatie = :artLocatie WHERE artId = :artId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':artId', $row['artId'], PDO::PARAM_INT);
            $stmt->bindParam(':artOmschrijving', $row['artOmschrijving'], PDO::PARAM_STR);
            $stmt->bindParam(':artInkoop', $row['artInkoop'], PDO::PARAM_STR);
            $stmt->bindParam(':artVerkoop', $row['artVerkoop'], PDO::PARAM_STR);
            $stmt->bindParam(':artVoorraad', $row['artVoorraad'], PDO::PARAM_INT);
            $stmt->bindParam(':artMinVoorraad', $row['artMinVoorraad'], PDO::PARAM_INT);
            $stmt->bindParam(':artLocatie', $row['artLocatie'], PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Summary of BepMaxArtId
     * @return int
     */
    private function BepMaxArtId() : int {
        // Bepaal uniek nummer
        $sql="SELECT MAX(artId)+1 FROM $this->table_name";
        return (int) self::$conn->query($sql)->fetchColumn();
    }

    /**
     * Summary of insertArtikel
     * Voeg een nieuw artikel toe aan de database
     * @param mixed $row Array met artikelgegevens
     * @return bool True als het invoegen succesvol is, anders False
     */
    public function insertArtikel($row) : bool {
        try {
            $sql = "INSERT INTO $this->table_name (artOmschrijving, artInkoop, artVerkoop, artVoorraad, artMinVoorraad, artLocatie) VALUES (:artOmschrijving, :artInkoop, :artVerkoop, :artVoorraad, :artMinVoorraad, :artLocatie)";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':artOmschrijving', $row['artOmschrijving'], PDO::PARAM_STR);
            $stmt->bindParam(':artInkoop', $row['artInkoop'], PDO::PARAM_STR);
            $stmt->bindParam(':artVerkoop', $row['artVerkoop'], PDO::PARAM_STR);
            $stmt->bindParam(':artVoorraad', $row['artVoorraad'], PDO::PARAM_INT);
            $stmt->bindParam(':artMinVoorraad', $row['artMinVoorraad'], PDO::PARAM_INT);
            $stmt->bindParam(':artLocatie', $row['artLocatie'], PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
