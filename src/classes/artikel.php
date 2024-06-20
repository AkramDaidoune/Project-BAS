<?php
// Auteur: Akram D
// Functie: definitie class Artikel
namespace Bas\classes;

use PDO;
use Bas\classes\Database;

include_once "functions.php";

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

    // Methods

    /**
     * Summary of crudArtikel
     * @param string $searchQuery
     * @return void
     */
     public function crudArtikel(string $searchQuery = '') : void {
        // Haal gefilterde artikelen op uit de database mbv de method getArtikelen()
        $lijst = $this->getArtikelen($searchQuery);

        // Print een HTML tabel van de lijst   
        $this->showTable($lijst);
    }

    /**
     * Summary of getArtikelen
     * @param string $searchQuery
     * @return array
     */
    public function getArtikelen(string $searchQuery = '') : array {
        $sql = "SELECT artId, artOmschrijving, artInkoop, artVerkoop, artVoorraad, artMinVoorraad, artMaxVoorraad, artLocatie FROM " . $this->table_name;
        if (!empty($searchQuery)) {
            $sql .= " WHERE artOmschrijving LIKE :searchQuery";
        }
        $stmt = self::$conn->prepare($sql);
        if (!empty($searchQuery)) {
            $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%', PDO::PARAM_STR);
        }
        $stmt->execute();
        $lijst = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $lijst;
    }

    /**
     * Summary of getArtikel
     * @param int $artId
     * @return array
     */
    public function getArtikel(int $artId) : array {
        $sql = "SELECT artId, artOmschrijving, artInkoop, artVerkoop, artVoorraad, artMinVoorraad, artMaxVoorraad, artLocatie FROM " . $this->table_name . " WHERE artId = :artId";
        $stmt = self::$conn->prepare($sql);
        $stmt->bindParam(':artId', $artId, PDO::PARAM_INT);
        $stmt->execute();
        $artikel = $stmt->fetch(PDO::FETCH_ASSOC);
        return $artikel ? $artikel : [];
    }

    public function dropDownArtikel($row_selected = -1){
        // Haal alle artikelen op uit de database mbv de method getArtikelen()
        $lijst = $this->getArtikelen();

        echo "<label for='Artikel'>Choose an artikel:</label>";
        echo "<select name='artId'>";
        foreach ($lijst as $row){
            if($row_selected == $row["artId"]){
                echo "<option value='{$row["artId"]}' selected='selected'> {$row["artOmschrijving"]}</option>\n";
            } else {
                echo "<option value='{$row["artId"]}'> {$row["artOmschrijving"]}</option>\n";
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
        if (empty($lijst)) {
            echo "<p>Geen artikelen gevonden.</p>";
            return;
        }
    
        $txt = "<table>";
    
        // Voeg de kolomnamen boven de tabel
        $header = array_keys($lijst[0]);
        unset($header[0]);
        $txt .= "<tr>";
        foreach ($header as $col) {
            $txt .= "<th>" . htmlspecialchars($col ?? '') . "</th>";
        }
    
        foreach ($lijst as $row) {
            $txt .= "<tr>";
            
            foreach (array_slice($row, 1) as $key => $value) {
                $txt .= "<td>" . htmlspecialchars($value ?? '') . "</td>";
            }
            
            // Update
            // Wijzig knopje
            $txt .= "<td>
            <form method='post' action='update.php?artId={$row["artId"]}'>
                <button name='update'>Wzg</button>
            </form></td>";
    
            // Delete
            $txt .= "<td>
            <form method='post' action='delete.php?artId={$row["artId"]}'>
                <button name='verwijderen'>Verwijderen</button>
            </form></td>";
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
        $sql = "DELETE FROM " . $this->table_name . " WHERE artId = :artId";
        $stmt = self::$conn->prepare($sql);
        $stmt->bindParam(':artId', $artId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Summary of updateArtikel
     * @param array $row
     * @return bool
     */
    public function updateArtikel(array $row) : bool {
        try {
            $sql = "UPDATE " . $this->table_name . " SET 
                artOmschrijving = :artOmschrijving, 
                artInkoop = :artInkoop, 
                artVerkoop = :artVerkoop, 
                artVoorraad = :artVoorraad, 
                artMinVoorraad = :artMinVoorraad, 
                artMaxVoorraad = :artMaxVoorraad, 
                artLocatie = :artLocatie 
                WHERE artId = :artId";
            $stmt = self::$conn->prepare($sql);
            $stmt->bindParam(':artId', $row['artId'], PDO::PARAM_INT);
            $stmt->bindParam(':artOmschrijving', $row['artOmschrijving'], PDO::PARAM_STR);
            $stmt->bindParam(':artInkoop', $row['artInkoop'], PDO::PARAM_STR);
            $stmt->bindParam(':artVerkoop', $row['artVerkoop'], PDO::PARAM_STR);
            $stmt->bindParam(':artVoorraad', $row['artVoorraad'], PDO::PARAM_INT);
            $stmt->bindParam(':artMinVoorraad', $row['artMinVoorraad'], PDO::PARAM_INT);
            $stmt->bindParam(':artMaxVoorraad', $row['artMaxVoorraad'], PDO::PARAM_INT);
            $stmt->bindParam(':artLocatie', $row['artLocatie'], PDO::PARAM_STR);
            return $stmt->execute();
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
        $sql = "SELECT MAX(artId)+1 FROM " . $this->table_name;
        return (int) self::$conn->query($sql)->fetchColumn();
    }

    /**
     * Summary of insertArtikel
     * @param array $row
     * @return bool
     */
    public function insertArtikel(array $row) : bool {
        // Bepaal een unieke artId
        $artId = $this->BepMaxArtId();
        
        // query
        $sql = "INSERT INTO " . $this->table_name . " (artId, artOmschrijving, artInkoop, artVerkoop, artVoorraad, artMinVoorraad, artMaxVoorraad, artLocatie) VALUES (:artId, :artOmschrijving, :artInkoop, :artVerkoop, :artVoorraad, :artMinVoorraad, :artMaxVoorraad, :artLocatie)";
        
        // Prepare
        $stmt = self::$conn->prepare($sql);
        $stmt->bindParam(':artId', $artId, PDO::PARAM_INT);
        $stmt->bindParam(':artOmschrijving', $row['artOmschrijving'], PDO::PARAM_STR);
        $stmt->bindParam(':artInkoop', $row['artInkoop'], PDO::PARAM_STR);
        $stmt->bindParam(':artVerkoop', $row['artVerkoop'], PDO::PARAM_STR);
        $stmt->bindParam(':artVoorraad', $row['artVoorraad'], PDO::PARAM_INT);
        $stmt->bindParam(':artMinVoorraad', $row['artMinVoorraad'], PDO::PARAM_INT);
        $stmt->bindParam(':artMaxVoorraad', $row['artMaxVoorraad'], PDO::PARAM_INT);
        $stmt->bindParam(':artLocatie', $row['artLocatie'], PDO::PARAM_STR);
        
        // Execute
        return $stmt->execute();
    }



    public function getBeschikbareArtikelen() : array {
        $sql = "SELECT DISTINCT artOmschrijving FROM " . $this->table_name;
        $stmt = self::$conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getBeschikbareLocaties() : array {
        $sql = "SELECT DISTINCT artLocatie FROM " . $this->table_name;
        $stmt = self::$conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

}



?>
