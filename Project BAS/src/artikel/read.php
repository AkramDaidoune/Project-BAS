<!--
    Auteur: Studentnaam
    Function: home page CRUD Artikel
-->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <link rel="stylesheet" href="styleArtikel.css">
</head>

<body>
    <h1>CRUD Artikel</h1>
    <nav>
        <a href='../index.html'>Home</a><br>
        <a href='insert.php'>Toevoegen nieuw artikel</a><br><br>
      
    </nav>
    
<?php

// Autoloader classes via composer
require '../../vendor/autoload.php';

use Bas\classes\Artikel;

// Maak een object Artikel
$artikel = new Artikel;

// Start CRUD
$artikel->crudArtikel();

?>
</body>
</html>
