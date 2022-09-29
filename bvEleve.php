<?php

session_start();

if (!$_SESSION['ifco']){
    header('Location: login.php');
}

?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="images/logo.png">
        <title>Accès élève | Lycée privé Saint-Vincent</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        
        <!-- section accès administrateur -->
        <?php require 'php/header.php'?>    
        <section class="choix">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        
                        <!--- titre section-->

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h2 class="hChoix">Bienvenue en tant qu'élève</h2>
                            <h3 class="hChoix">Que souhaitez-vous faire ?</h3>
                        </div>
                        
                        <!-- accès vers les 2 sections -->
                        
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                
                                <!-- lien 1 -->
                                
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="thumbnailChoix">
                                        <a class="thumbnail" href="reserver.php">
                                            <img src="images/logo.png" alt="photosSectionAlexia">
                                            <h4 class="h4Choix">Réserver un sandwich</h4>
                                        </a>
                                    </div>
                                </div>
                            
                                <!-- lien 2 -->

                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="thumbnailChoix">
                                        <a class="thumbnail" href="historique.php">
                                            <img src="images/logo.png" alt="photosSectionPierre">
                                            <h4 class="h4Choix">Voir l'historique de vos commandes</h4>
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <!-- bouton retour -->
                            
                            <a id='retour-btn' href="logout.php" class="btn btn-primary">Retour</a>
                            
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php require 'php/footer.php' ?>
    </body>
</html>