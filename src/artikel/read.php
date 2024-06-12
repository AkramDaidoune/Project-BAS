<!--
    Auteur: Akram D
    Function: home page CRUD Artikel
-->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel Overzicht</title>
    <link rel="stylesheet" href="styleArtikel.css">
</head>

<body>
    <h1>Artikel Overzicht</h1>
    <nav>
        <a href='../index.html'>Home</a><br>
        <a href='insert.php'>Toevoegen nieuw artikel</a><br><br>
    </nav>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Zoek Artikel" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Zoeken</button>
    </form>

<?php

// Autoloader classes via composer
require '../../vendor/autoload.php';

use Bas\classes\Artikel;

// Maak een object Artikel
$artikel = new Artikel();

// Check if search query is set and is not null
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Start CRUD with search query if available
$artikel->crudArtikel($searchQuery);
?>
</body>
</html>

