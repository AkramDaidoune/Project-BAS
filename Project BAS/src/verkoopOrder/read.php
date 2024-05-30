<!--
	Auteur: Akram D
	Function: home page CRUD Verkooporder
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
<header>
        <h1>CRUD VerkoopOrder</h1>
        <nav>
            
                <a href="../index.html">Home</a><br><br>   
            
        </nav>
    </header>
	
<?php

// Autoloader classes via composer
require '../../vendor/autoload.php';

use Bas\classes\VerkoopOrder;

// Maak een object Klant
$verkooporder = new VerkoopOrder;

// Start CRUD
$verkooporder->crudVerkoopOrder();

?>
</body>
</html>