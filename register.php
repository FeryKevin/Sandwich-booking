<?php 

require('php/connexion.php');

/* déclaration des variables php */

$prenom_user = $nom_user = $email_user = $password_user = "";
$role_user = "e";
$active_user = "0";
$prenomError = $nomError = $emailError = $emailExiste = "";
$pass = $passError = "";
$isSuccess = false;

/* contrôle des champs du formulaire */

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email_user      = verifyInput($_POST["email"]);
    $password_user   = verifyInput($_POST["password"]);
    $nom_user        = verifyInput($_POST["nom"]);
    $prenom_user     = verifyInput($_POST["prenom"]);
    $isSuccess = true;

    /* vérification champs */

    if(empty($prenom_user))
    {
        $prenomError = "Veuillez saisir votre prénom";
        $isSuccess = false;
    }

    if(empty($nom_user))
    {
        $nomError = "Veuillez saisir votre nom";
        $isSuccess = false;
    }

    if(!isEmail($email_user))
    {
        $emailError = "Veuillez saisir un e-mail valide";
        $isSuccess = false;
    }

    /* mot de passe sous condition */
    
    if (preg_match('#^(?=.*[0-9])(?=.{8,50})(?=.*\W)#', $password_user))
    {
        $pass = "Mot de passe conforme";
    }

    else 
    {
        $passError = 'Mot de passe non conforme';
        $isSuccess = false;
    }

    /* insert et select dans la base de donnée */

    if($isSuccess) 
    {
        $password = password_hash($password_user, PASSWORD_ARGON2I);
        $db = connect();

        $req = $db->prepare("SELECT COUNT(*) FROM utilisateur WHERE email_user = :email_user");
        $req->execute(array('email_user' => $email_user));
        $results = $req->fetch();

        if($results[0] == 0)
        {
            $statement = $db->prepare("INSERT INTO utilisateur (role_user, email_user, password_user, nom_user, prenom_user, active_user) values (?, ?, ?, ?, ?, ?)");
            $statement->execute(array($role_user, $email_user, $password, $nom_user, $prenom_user, $active_user));
            $db = null;
            header('Location: login.php');
        }
        else
        {
            $emailExiste = "Erreur : l'email a déjà été utilisé.";
            $isSuccess = false;
        }

        $db = null;
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="images/logo.png">
        <title>Inscription | Lycée privé Saint-Vincent</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
    </head>
    
    <body>

        <!-- header -> navbar -->
        
        <?php 
        
        require('php/header.php');
        
        ?>
        
        
        <!-- section formulaire register mdp -->
        
        <section id="register">   
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        
                        <!-- titre du formulaire -->
                        
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h1 class="hConInscr">Inscription</h1>
                        </div>
                    
                        <!-- début du formulaire -->

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            
                            <form id="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" role="form">

                                
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                
                                    <!-- input nom -->

                                    <div class="form-group">
                                        <label for="nom">Nom <span class="red">*</span> :</label>
                                        <input type="text" id="nom" name="nom" placeholder="Veuillez entrer votre nom" class="form-control" value="<?php echo $nom_user; ?>">
                                        <p class="messageError"><?php echo $nomError; ?></p>
                                    </div>
                                        
                                    <!-- input email -->

                                    <div class="form-group">
                                        <label for="email">Email <span class="red">*</span> :</label>
                                        <input type="text" id="email" name="email" placeholder="Veuillez entrer votre email" class="form-control" value="<?php echo $email_user; ?>">
                                        <p class="messageError"><?php echo $emailError; ?></p>
                                        <p class="messageError"><?php echo $emailExiste; ?></p>
                                    </div>
                                    
                                </div>
                                
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    
                                    <!-- input prénom -->

                                    <div class="form-group">
                                        <label for="prenom">Prénom <span class="red">*</span> :</label>
                                        <input type="text" id="prenom" name="prenom" placeholder="Veuillez entrer votre prénom" class="form-control" value="<?php echo $prenom_user; ?>">
                                        <p class="messageError"><?php echo $prenomError; ?></p>
                                    </div>

                                    <!-- input password -->

                                    <div class="form-group">
                                        <label for="password">Mot de passe (8 caractères, dont 1 chiffre et 1 caractère spécial) <span class="red">*</span> :</label>
                                        <input type="password" id="password" name="password" placeholder="Veuillez entrer votre mot de passe" class="form-control" pattern="^(?=.*\d)(?=.{8,50})(?=.*[-+!*$@%_?,#'$:;/}{=][)(^])([-+!*$@%_?,#'$:;/}{=][)$" value="<?php echo $password_user; ?>">
                                        <p class="messageError"><?php echo $passError; ?></p>
                                        <span id="msg"></span>
                                    </div>
                                </div>
                                
                                <!-- bouton submit -->
                                
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <button type="submit" name="submit" class="btn btn-primary" >S'inscrire</button>
                                
                                <!-- bouton retour -->
                                    
                                    <button type="button" name="submit" class="btn btn-primary"><a href="login.php">Retour</a></button>
                                </div>
                                
                            </form>
                            
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