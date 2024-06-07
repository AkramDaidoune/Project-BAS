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
 
     <form method="GET" action="read.php">
        <input type="text" name="search" placeholder="Zoek artikel" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Zoeken</button>
    </form>
    <?php
    // Autoloader classes via composer
    require '../../vendor/autoload.php';

    use Bas\classes\Artikel;

    // Maak een object Artikel
    $artikel = new Artikel;

    // Check if search query is set
    $searchQuery = isset($_GET['search']) ? $_GET['search'] : null;

    // Start CRUD with search query if available
    $artikel->crudArtikel($searchQuery);
    ?>
</body>
</html>
