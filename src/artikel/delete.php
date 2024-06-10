<?php 
// auteur: studentnaam
// functie: 

// Autoloader classes via composer
require '../../vendor/autoload.php';
use Bas\classes\Artikel;

if(isset($_POST["verwijderen"])){
	
	// Maak een object Artikel
	$artikel = new Artikel();
	
	// Delete Artikel op basis van NR
	
	echo '<script>alert("Artikel verwijderd")</script>';
	echo "<script> location.replace('read.php'); </script>";
}
?>
