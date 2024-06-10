<?php 
// auteur: AkramD
// functie: 

// Autoloader classes via composer
require '../../vendor/autoload.php';
use Bas\classes\VerkoopOrder;

if(isset($_POST["verwijderen"])){
	
	// Maak een object Verkooporder
	
	
	// Delete VerkoopOrder op basis van NR
	

	echo '<script>alert("VerkoopOrder verwijderd")</script>';
	echo "<script> location.replace('read.php'); </script>";
}
?>



