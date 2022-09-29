<?php

/*connection admin */

session_start();

if ($_SESSION['role']=='e' && $_SESSION['ifco'])
{
    header('Location: bvEleve.php');
}
else if (!$_SESSION['ifco'])
{
    header('Location: login.php');
}

require 'php/connexion.php';

/* déclation des variables */

$txtError = $pdfError = $txt = $pdf = "";

/* connexion */

$db = connect();

/* affichage */

$statement = $db -> query("SELECT texte_accueil, lien_pdf FROM accueil WHERE id_accueil = 1");         
$row = $statement -> fetch();

/* controle des champs */

if(!empty($_POST['texte_accueil']))
{
    $txt                = verifyInput($_POST['texte_accueil']);
    $pdf                = verifyInput($_FILES["lien_pdf"]["name"]);
    $imagePath          = 'pdf'. basename($pdf);
    $imageExtension     = pathinfo($imagePath,PATHINFO_EXTENSION);
    $isSuccess          = true;
    $isUploadSuccess    = true;


    if(empty($txt)) 
    {
        $txtError  = 'Ce champ ne peut pas être vide';
        $isSuccess = false;
    }

    if(empty($pdf)) 
    {
        $isImageUpdated = false;
    }
    else
    {
        $isImageUpdated = true;

        if($imageExtension != "pdf" && $imageExtension != "PDF") 
        {
            $pdfError = "Les fichiers autorisés sont: .pdf, .PDF";
            $isUploadSuccess = false;
        }

        if(file_exists($imagePath)) 
        {
            $pdfError = "Le fichier existe deja";
            $isUploadSuccess = false;
        }

        if($_FILES["lien_pdf"]["size"] > 1000000) 
        {
            $pdfError = "Le fichier ne doit pas dépasser les 1000KB";
            $isUploadSuccess = false;
        }

        if($isUploadSuccess)
        {
            if(!move_uploaded_file($_FILES["lien_pdf"]["tmp_name"], $pdf))
            {
                $pdfError = "Il y a eu une erreur lors de l'upload";
                $isUploadSuccess = false;
            } 
        } 
    }

    /* requete update si les champs sont correct */
    
    if(($isSuccess && $isImageUpdated && $isUploadSuccess) || ($isSuccess && !$isImageUpdated)) 
    { 
        $db = connect();

        if($isImageUpdated)
        {
            $statement = $db->prepare("UPDATE accueil set texte_accueil =?, lien_pdf =? WHERE id_accueil =1");
            $statement->execute(array($txt, $pdf));
        }
        else
        {
            $statement = $db->prepare("UPDATE accueil set texte_accueil =? WHERE id_accueil =1");
            $statement->execute(array($txt));
        }

        header("Location: index.php");
    }    
}

?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="images/logo.png">
        <title>Modification de l'accueil | Lycée privé Saint-Vincent</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
       
        <?php
        
        require 'php/header.php';    
        
        ?>
        
        <section id="updateAccueil">
             <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        
                        <!--- titre section-->

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h1 class="hModif">Modifier la page d'accueil</h1>
                        </div>

                        <!-- formulaire update -->

                        <div class="col-lg-6 col-md-12 col-sm-12">

                            <form class="form" role="form" method="post" enctype="multipart/form-data">

                                <!-- texte d'accueil -->

                                <div class="form-group">
                                    <label for="texte_accueil">Texte d'accueil :</label>
                                    <input type="text" class="form-control" id="texte_accueil" class="form-control" name="texte_accueil" placeholder="Veuillez entrer le texte d'accueil :" value="<?php echo $row['texte_accueil'];?>">
                                    <span class="help-inline"><?php echo $txtError;?></span>
                                </div>

                                <!-- pdf -->

                                <div class="form-group">
                                    <label>PDF :</label>
                                    <p class="lienPdf"><?php echo $row['lien_pdf'];?></p>
                                    <label for="lien_pdf">Sélectionner un PDF :</label>
                                    <input type="file" id="lien_pdf" name="lien_pdf" class="lien_pdf">
                                    <span class="help-inline"><?php echo $pdfError;?></span>
                                </div>
                                
                                <!-- bouton modifier et retourS -->
                                
                                <div class="form-actions"><br>
                                    <a href="bvAdmin.php" class="btn btn-primary">Retour</a>
                                    <button type="submit" class="btn btn-primary">Modifier</button>
                                </div>

                            </form>    
                        </div>

                        <!-- thumbnail update -->

                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <br><br>
                            <div class="thumbnail">
                                <h4 class="hThumbnail"><?php echo $row['texte_accueil'];?></h4>
                                <div class="pdfPlanning">
                                    <?php echo"<object data='".$row['lien_pdf']."' width=220px height=145px type='application/pdf'></object";?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </section>
        
        <!-- footer -->
        
        <?php
        
        require 'php/footer.php';    
        
        ?>
        
    </body>
</html>