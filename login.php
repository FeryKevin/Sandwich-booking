<?php

/* connexion en tant qu'élève ou administrateur */

session_start();

$_SESSION['ifco'] = false;
$_SESSION['role'] = '';

require('php/connexion.php');

/* déclaration des variables */

$role = $email = $password = "";

/* démarrage d'une session et connexion à la base de donnée */  

$co = connect();

/* connection et vérification */

if (isset($_POST['submit']))
{   
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $password_user = password_hash($password, PASSWORD_ARGON2I);

    $query = $co->prepare('SELECT * FROM utilisateur WHERE email_user=:email');
    $query->bindParam(':email', $email);
    $query->execute();    

    $temp = $query->fetch();

    $hash = $temp['password_user'];
    $role = $temp['role_user'];
    
    $nom = $temp['nom_user'];
    $prenom = $temp['prenom_user'];
    $user_id = $temp['id_user'];

    $result = $query->fetch();

    /* redirection */

    if (password_verify($password, $hash)) {
        
        $_SESSION['ifco'] = true;
        
        $_SESSION['role'] = $role;
        
        $_SESSION['username'] = array($nom, $prenom);
        
        $_SESSION['user_id'] = $user_id;

        if($role == "e")
        {
            header("Location: bvEleve.php");
        }
        else if($role == "a")
        {
            header("Location: bvAdmin.php");
        }  
    }
    else
    {
        /* message d'erreur */
        
        $message = "Informations incorrectes";
        $password = "";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="images/logo.png">
        <title>Connexion | Lycée privé Saint-Vincent</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>
    
    <body>
        
        <!-- header -->
        
        <?php 

        require('php/header.php');
        
        
        ?>
        
        
        <!-- section formulaire login mdp -->
        
        <section id="login">   
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        
                        <!-- titre du formulaire -->
                        
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h1 class="hConInscr">Connexion</h1>
                        </div>
                    
                        <!-- début du formulaire -->
                        
                        <div class="col-lg-12 col-md-12 col-sm-12">
                        
                            <form class="form" method="post" name="login" id="loginform">
                                
                                <!-- email -->

                                <div class="form-group">
                                    <label for="email">Email <span class="red">*</span> :</label>
                                    <input type="text" id="email" name="email" placeholder="Veuillez entrer votre email" class="form-control" value="<?php echo $email; ?>">
                                </div>

                                <!-- password -->

                                <div class="form-group">
                                    <label for="password">Mot de passe <span class="red">*</span> :</label>
                                    <input type="password" id="passsword" name="password" placeholder="Veuillez entrer votre mot de passe" class="form-control" value="<?php echo $password; ?>">
                                </div>


                                <!-- bouton connexion -->

                                <input type="submit" value="Connexion " name="submit" class="btn btn-primary">

                                <!-- message d'erreur -->

                                <?php 

                                if (!empty($message)) 
                                { 

                                ?>

                                <p class="messageError"><?php echo $message; ?></p>

                                <?php 

                                } 

                                ?>
                            
                            </form>
                        </div>
                    </div>
                        
                    
                    <!-- partie inscription -->

                    <div class="col-lg-6 col-md-12 col-sm-12">

                        <!-- texte -->
                        
                        <h1 class="hConInscr">Inscription</h1>
                        <h4 class="txtInscription">Vous n'avez pas encore de compte ?</h4>
                        <h4 class="txtInscription">Inscrivez-vous dès maintenant !</h4>

                        <!-- bouton s'inscrire -->
                        
                        <div class="btnInscription">
                            <a href="register.php">
                                <button type="button" class="btn btn-primary">S'inscrire</button>
                            </a>
                        </div> 
                        
                    </div>
                    
                </div>
            </div>
        </section>
        
        
        <!--  footer -->
        
        <?php 
        
        require('php/footer.php');
        
        ?>
        
    </body>
</html>