<!--
	Auteur: Studentnaam
	Function: home page CRUD Klant
-->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <link rel="stylesheet" href="styleKlant.css">
</head>

<body>
    <h1>CRUD Klant</h1>
    <nav>
        <a href='../index.html'>Home</a><br>
        <a href='insert.php'>Toevoegen nieuwe klant</a><br><br>
    </nav>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Zoek Klant" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Zoeken</button>
    </form>

<?php

// Autoloader classes via composer
require '../../vendor/autoload.php';

use Bas\classes\Klant;

// Maak een object Klant
$klant = new Klant();

// Check if search query is set
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Start CRUD with search query if available
$klant->crudKlant($searchQuery);
?>
</body>
</html>
