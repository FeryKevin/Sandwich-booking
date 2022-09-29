<?php

require 'php/connexion.php';    

/* select planning cantine et adffichage à 100% de sa hauteur et largeur */

$db = connect();

$statement = $db -> query("SELECT lien_pdf FROM accueil WHERE id_accueil = 1");

$row = $statement -> fetch();

echo"<object data='".$row['lien_pdf']."' width=100% height=100% type='application/pdf'></object";

$db = null;

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="images/logo.png">
        <title>Planning cantine | Lycée privé Saint-Vincent</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
    
        <!-- section pdf -->
        
        <section id="pdfHTML">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="btnRetour">    
                            <a href="index.php" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-left"></span>Retour</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    </body>
</html>