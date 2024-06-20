<?php
// auteur: Akram D
// functie: definitie class Klant
namespace Bas\classes;

use PDO;
use Bas\classes\Database;

include_once "functions.php";

class Klant extends Database {
    public $klantId;
    public $klantEmail = null;
    public $klantNaam;
    public $klantWoonplaats;
    public $klantAdres;
    public $klantPostcode;
    private $table_name = "Klant";    

    // Methods

    /**
     * Summary of crudKlant
     * @param string $searchQuery
     * @return void
     */
     public function crudKlant(string $searchQuery = '') : void {
        // Haal gefilterde artikelen op uit de database mbv de method getArtikelen()
        $lijst = $this->getKlanten($searchQuery);

        // Print een HTML tabel van de lijst   
        $this->showTable($lijst);
    }
    /**
 * Summary of getKlanten
 * @param string $searchQuery
 * @return array
 */
public function getKlanten(string $searchQuery = '') : array {
    try {
        $sql = "SELECT klantId, klantEmail, klantNaam, klantWoonplaats, klantAdres, klantPostcode FROM $this->table_name";
        if (!empty($searchQuery)) {
            $sql .= " WHERE klantNaam LIKE :searchQuery";
        }
        $stmt = self::$conn->prepare($sql);
        if (!empty($searchQuery)) {
            $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%', PDO::PARAM_STR);
        }
        $stmt->execute();
        $lijst = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $lijst;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}



    /**
     * Summary of getKlant
     * @param int $klantId
     * @return array
     */
    public function getKlant(int $klantId) : array {
        try {
            $sql = "SELECT * FROM $this->table_name WHERE klantId = :klantId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':klantId', $klantId, PDO::PARAM_INT);
            $stmt->execute();
            $lijst = $stmt->fetch(PDO::FETCH_ASSOC);

            return $lijst ?: [];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    
    public function dropDownKlant($row_selected = -1){
        // Haal alle klanten op uit de database mbv de method getKlanten()
        $lijst = $this->getKlanten();
        
        echo "<label for='Klant'>Choose a klant:</label>";
        echo "<select name='klantId'>";
        foreach ($lijst as $row){
            if($row_selected == $row["klantId"]){
                echo "<option value='$row[klantId]' selected='selected'> " . htmlspecialchars($row["klantNaam"] ?? '') . " " . htmlspecialchars($row["klantEmail"] ?? '') . "</option>\n";
            } else {
                echo "<option value='$row[klantId]'> " . htmlspecialchars($row["klantNaam"] ?? '') . " " . htmlspecialchars($row["klantEmail"] ?? '') . "</option>\n";
            }
        }
        echo "</select>";
    }

    /**
     * Summary of showTable
     * @param mixed $lijst
     * @return void
     */
    public function showTable($lijst) : void {
        $txt = "<table>";

        // Voeg de kolomnamen boven de tabel
        $txt .= "<tr>";
        $txt .= "<th>klantId</th>";
        $txt .= "<th>klantNaam</th>";
        $txt .= "<th>klantEmail</th>";
        $txt .= "<th>klantWoonplaats</th>";
        $txt .= "<th>klantAdres</th>";
        $txt .= "<th>klantPostcode</th>";
        $txt .= "</tr>";

        foreach($lijst as $row){
            $txt .= "<tr>";
            $txt .= "<td>" . htmlspecialchars($row["klantId"] ?? '') . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["klantNaam"] ?? '') . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["klantEmail"] ?? '') . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["klantWoonplaats"] ?? '') . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["klantAdres"] ?? '') . "</td>";
            $txt .= "<td>" . htmlspecialchars($row["klantPostcode"] ?? '') . "</td>";
            
            // Update
            // Wijzig knopje
            $txt .= "<td>
                <form method='post' action='update.php?klantId={$row["klantId"]}'>
                    <button name='update'>Wzg</button>
                </form>
            </td>";

            // Delete
            $txt .= "<td>
                <form method='post' action='delete.php?klantId={$row["klantId"]}'>
                    <button name='verwijderen'>Verwijderen</button>
                </form>
            </td>";
            $txt .= "</tr>";
        }
        $txt .= "</table>";
        echo $txt;
    }

    // Delete klant
    /**
     * Summary of deleteKlant
     * @param int $klantId
     * @return bool
     */
    public function deleteKlant(int $klantId) : bool {
        try {
            // Doe een delete-query op basis van $klantId
            $sql = "DELETE FROM $this->table_name WHERE klantId = :klantId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':klantId', $klantId, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function updateKlant(array $row) : bool {
        try {
            $sql = "UPDATE " . $this->table_name . " SET 
                klantNaam = :klantNaam, 
                klantEmail = :klantEmail, 
                klantAdres = :klantAdres, 
                klantPostcode = :klantPostcode, 
                klantWoonplaats = :klantWoonplaats 
                WHERE klantId = :klantId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':klantId', $row['klantId'], PDO::PARAM_INT);
            $stmt->bindParam(':klantNaam', $row['klantNaam'], PDO::PARAM_STR);
            $stmt->bindParam(':klantEmail', $row['klantEmail'], PDO::PARAM_STR);
            $stmt->bindParam(':klantAdres', $row['klantAdres'], PDO::PARAM_STR);
            $stmt->bindParam(':klantPostcode', $row['klantPostcode'], PDO::PARAM_STR);
            $stmt->bindParam(':klantWoonplaats', $row['klantWoonplaats'], PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Summary of BepMaxKlantId
     * @return int
     */
    private function BepMaxKlantId() : int {
        // Bepaal uniek nummer
        $sql="SELECT MAX(klantId)+1 FROM $this->table_name";
        return (int) self::$conn->query($sql)->fetchColumn();
    }

    /**
     * Summary of insertKlant
     * Voeg een nieuwe klant toe aan de database
     * @param mixed $row Array met klantgegevens
     * @return bool True als het invoegen succesvol is, anders False
     */
    public function insertKlant($row) : bool {
        try {
            $sql = "INSERT INTO $this->table_name (klantEmail, klantNaam, klantAdres, klantPostcode, klantWoonplaats) VALUES (:klantEmail, :klantNaam, :klantAdres, :klantPostcode, :klantWoonplaats)";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':klantEmail', $row['klantEmail'], PDO::PARAM_STR);
            $stmt->bindParam(':klantNaam', $row['klantNaam'], PDO::PARAM_STR);
            $stmt->bindParam(':klantAdres', $row['klantAdres'], PDO::PARAM_STR);
            $stmt->bindParam(':klantPostcode', $row['klantPostcode'], PDO::PARAM_STR);
            $stmt->bindParam(':klantWoonplaats', $row['klantWoonplaats'], PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
