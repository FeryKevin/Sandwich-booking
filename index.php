<?php

/* garder la connexion */

session_start();
if (!isset($_SESSION['ifco']))
{
    $_SESSION['ifco']='';
}


?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="images/logo.png">
        <title>Réservation de Sandwichs | Lycée privé Saint-Vincent</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
       
        <!-- navbar -->
        
        <?php 
        
        require 'php/header.php';
        
        ?>

        <!-- section home -> php select -->
        
        <?php

        require 'php/connexion.php';    

        $db = connect();
        
        $statement = $db -> query("SELECT texte_accueil, lien_pdf FROM accueil WHERE id_accueil = 1");         
        $row = $statement -> fetch();
        
        ?>
        
        <!-- section home -> html et affichage php -->
        
        <section id="home">
            <div class="container">
                <div class="row">
                    
                    <!-- titre de la page -->
                    
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h2 class="hIndex">Bienvenue sur l'espace cantine</h2>
                    </div>
                    
                    <!-- affichage texte d'accueil -->
                    
                    <div class="col-sm-12 col-md-12 col-lg-6">

                        <?php 

                        echo"<h4 class='hTxt'>".$row['texte_accueil']."</h4>";

                        ?>
                        
                        <!-- lien pour voir le planning en grand -->
                        
                        <div class="txtPlanning">                          
                            <a href="planningCantine.php" target='_blank' type="button" class="btn btn-primary">Voir le planning de la cantine</a> 
                        </div>
                    </div>

                    <!-- affichage pdf -->
                    
                    <div class="col-sm-12 col-md-12 col-lg-6 col-lg-offset-0">
                        <div class="pdfPlanning">
                        
                            <?php 

                            echo"<object data='".$row['lien_pdf']."' width=300px height=185px type='application/pdf'></object";

                            $db = null;

                            ?>
                        </div> 
                    </div> 
                </div>                                 
            </div>
        </section>
        
        <!-- section présentation du site -->
        
        <section id="presentation">
            <div class="container">
                
                <!-- partie 1 -->
                
                <div class="row">
                    
                    <!-- texte partie 1 -->
                    
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="txtIndex">
                            <h4><a href="login.php" class="hIndex0" >Connectez-vous</a> pour pouvoir réserver votre repas</h4>
                        </div>
                    </div>
                    
                    <!-- image 1 -->
                    
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <img class='pres_img' src="images/pres_reserv.png" alt="img réservation">
                    </div>
                </div>
                
                <!-- partie 2 -->
                
                <div class="row">
                    
                    <!-- image 2 -->
                    
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <img class='pres_img' src="images/pres_histo.png" alt="img historique">
                    </div>
                    
                    <!-- texte partie 2 -->
                    
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="txtIndex">
                            <h4>... mais aussi pour gérer votre historique de commande !</h4>
                        </div>
                    </div>
                </div>
                
                <!-- fin section -> lien vers l'inscription -->
                
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h4 class="hIndex2">Vous êtes nouveau ? <a href="register.php">Inscrivez-vous</a> dès maintenant !</h4>
                    </div>
                </div>
            </div>
        </section>
        
            
        <!--  footer -->
        
        <?php 
        
        require 'php/footer.php';
        
        ?>   
        
    </body>
</html>