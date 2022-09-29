<?php

session_start();

if (!$_SESSION['ifco']){
    header('Location: login.php');
}

$user_id = $_SESSION['user_id'];

require_once 'php/connexion.php';


    if(!empty($_POST['sandwich']))
    {   
        $fk_sandwich_id = $_POST['sandwich'];
        $fk_boisson_id = $_POST['boisson'];
        $fk_dessert_id = $_POST['dessert'];

        if (empty($fk_sandwich_id)){
            $isSuccess = false;
        }

        if (empty($fk_boisson_id)){
            $isSuccess = false;
        }

        if (empty($fk_dessert_id)){
            $isSuccess = false;
        }

        $isSuccess = true;

        if (!isset($_POST['chips'])){
            $chips=0;
        }
        else{
            $chips = 1;
        }


        if (empty($_POST['heure'])){
            $isSuccess = false;
        }
        else{
            $heure = $_POST['heure'];
            $auj = array(date('Y-m-d'),date('H:i'));
            if ($heure < $auj[0]){
            $erreur =  'Jour deja passé';
            $isSuccess= false;
            }
            else if ($auj[1] > date("H:i",30600) and $auj[0] == date('Y-m-d',strtotime($heure))){
                $erreur = 'Impossible de commander apres 9:30';
                $isSuccess= false;
            }
            else if (date("l",strtotime($heure)) != 'Saturday' and date("l",strtotime($heure)) != 'Sunday'){
                $db = connect();
                $statement = $db->prepare("INSERT INTO commande (fk_user_id,fk_sandwich_id, fk_boisson_id, fk_dessert_id, chips_com, date_heure_livraison_com,annule_com) VALUES(?,?,?,?,?,?,0)");
                $statement->execute(array($user_id,$fk_sandwich_id,$fk_boisson_id,$fk_dessert_id,$chips,$heure));
                $db = null;
                header("Location: bvEleve.php");
            }
            else{
                $erreur =  'Impossible de commande pour un week-end';
                $isSuccess= false;
            }
        }
        
        

        if($isSuccess)
        {
            
        }
        else{
            echo'<h1><strong>Erreur de commande, veuillez réessayer. </strong></h1>'; 
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="images/logo.png">
        <title>Réservation</title>
        <meta charset='utf-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">  <!-- Importation de bootstrap-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>  <!-- Importation de jquery-->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  <!-- Importation de bootstrap-->
        <link rel="stylesheet" href="css/styles.css">
    </head>

    <body>

      <?php require 'php/header.php' ?>
        <section id='reservation'>
            <div id='bloc'>
                <div class="container">
                
                    <div class='row'>
                        <div class='col-lg-12 col-md-12 col-sm-12'>
                            <form method="post" action="" class='form'>

                                <label for="sandwich"><img class="icon_res" src="images/sandwich.png"> Choisir le sandwich :</label>
                                <select class='form-control' id='sandwich' name='sandwich'>

                                    <?php 

                                    $db = connect();
                                    foreach ($db->query('SELECT * FROM sandwich') as $row) 
                                    {
                                            echo '<option value="'. $row['id_sandwich'] .'">'. $row['nom_sandwich'] . '</option>';

                                    }
                                    ?>
                                </select>
                                <label for="boisson"><img class="icon_res" src="images/boisson.png"> Choisir la boisson :</label>
                                <select class='form-control' id='boisson' name='boisson'>

                                    <?php 
                                    foreach ($db->query('SELECT * FROM boisson') as $row) 
                                    {
                                            echo '<option value="'. $row['id_boisson'] .'">'. $row['nom_boisson'] . '</option>';

                                    }
                                    ?>
                                </select>
                                <label for="dessert"><img class="icon_res" src="images/dessert.png"> Choisir le dessert :</label>
                                <select class='form-control' id='dessert' name='dessert'>

                                    <?php 
                                    foreach ($db->query('SELECT * FROM dessert') as $row) 
                                    {
                                            echo '<option value="'. $row['id_dessert'] .'">'. $row['nom_dessert'] . '</option>';

                                    }
                                    ?>
                                </select>
                                    <div>
                                        <label for="chips"><img class="icon_res" src="images/chips.png"> Chips :</label>
                                        <input type="checkbox" id="chips" name="chips">
                                        <br>
                                        <label for="date"> Date de commande :</label>
                                        <input type="datetime-local" id="heure" name="heure" value="<?php echo $heure;?>">

                                    </div>
                                <button name="submit" type='submit' class='btn-submit btn_res'>Commander</button>
                                <h3><?php echo $erreur ?></h3>    
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php require 'php/footer.php' ?>
</body>
</html>