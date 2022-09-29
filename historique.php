<?php 

session_start();

if (!$_SESSION['ifco']){
  header('Location: login.php');
}

$user = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

$filtre = False;
$heure_apres = $heure_avant = $heure ='';

function checkInput($var) {
  $var = trim($var);
  $var = stripslashes($var);
  $var = htmlspecialchars($var);
  return $var;
}


require_once 'php/connexion.php';

$db = connect();
$statement = $db->prepare('SELECT count(*) FROM historique WHERE fk_user_id=?');
$statement -> execute(array($user_id));
$item = $statement->fetch();

//Si l'utilisateur a déjà fait une rechercher
if($item['0']==1){
  $filtre = true;
  $statement = $db->prepare('SELECT dateDebut_hist, dateFin_hist FROM historique WHERE fk_user_id=?');
  $statement -> execute(array($user_id));
  $item = $statement->fetch();
  $h1 = $item['dateDebut_hist'];
  $h2 = $item['dateFin_hist'];
  $statement = $db->prepare('SELECT * FROM `commande` WHERE date_heure_livraison_com BETWEEN ? and ? and fk_user_id = ? order by date_heure_livraison_com DESC');
  $statement-> execute (array($h1,$h2,$user_id));
}


//En cas de filtre
if (!empty($_POST['heure_avant'])){
  $filtre = True;
  $h1 = $_POST['heure_avant'];
  $h2 = $_POST['heure_apres'];

  if ($h2 < $h1 ){
    $h2 = $h1;
  }
  if ($h1 > $h2 ){
    $h1 = $h2;
  }

  //Suppression des anciennes infos
  $db = connect();
  $statement = $db->prepare('DELETE FROM historique where fk_user_id=?');
  $statement -> execute(array($user_id));

  //Historisation des dates
  $statement = $db->prepare('INSERT INTO historique (dateDebut_hist, dateFin_hist, fk_user_id) VALUES (?,?,?)');
  $statement-> execute (array($h1,$h2,$user_id));

  //Mise à jour de la table
  $statement = $db->prepare('SELECT * FROM `commande` WHERE date_heure_livraison_com BETWEEN ? and ? and fk_user_id = ? order by date_heure_livraison_com DESC');
  $statement-> execute (array($h1,$h2,$user_id));
} 

if (!empty($_POST['id_commande_supp'])){
  $id = checkInput($_POST['id_commande_supp']);
  $db= connect();
  $statement = $db->prepare('UPDATE commande SET annule_com = 1 WHERE id_com=?');
  $statement->execute(array($id));
}

if (!empty($_POST['reset-test'])){
  $statement = $db->query('SELECT date_heure_livraison_com FROM commande order by date_heure_livraison_com');
  $res = $statement->fetch();
  // On retire 1 jour
  $res1 = date('Y-m-d', strtotime($res[0]. ' - 1 days')); 

  $statement2 = $db->query('SELECT date_heure_livraison_com FROM commande order by date_heure_livraison_com DESC');
  $res = $statement2->fetch();
  //On ajoute un jour
  $res2 = date('Y-m-d', strtotime($res[0]. ' + 1 days')); 
  

  //Suppression des anciennes infos
  $db = connect();
  $statement = $db->prepare('DELETE FROM historique where fk_user_id=?');
  $statement -> execute(array($user_id));

  //Historisation des dates
  $statement = $db->prepare('INSERT INTO historique (dateDebut_hist, dateFin_hist, fk_user_id) VALUES (?,?,?)');
  $statement-> execute (array($res1,$res2,$user_id));

  //Mise à jour de la table
  $statement = $db->prepare('SELECT * FROM `commande` WHERE date_heure_livraison_com BETWEEN ? and ? and fk_user_id = ? order by date_heure_livraison_com DESC');
  $statement-> execute (array($h1,$h2,$user_id));
  header('refresh:0');
}

if (!empty($_POST['id_commande_modif'])){
  $isSuccess = false;
  $id = $_POST['id_commande_modif'];
  $nouvelle_heure = $_POST['nouvelle_heure'];
  $auj = array(date('Y-m-d'),date('H:i'));
  if ($nouvelle_heure < $auj[0]){
    $isSuccess= false;
  }
  else if ($auj[1] > date("H:i",30600) and $auj[0] == date('Y-m-d',strtotime($nouvelle_heure))){
      $isSuccess= false;
  }
  else if (date("l",strtotime($nouvelle_heure)) != 'Saturday' and date("l",strtotime($nouvelle_heure)) != 'Sunday'){
    $statement = $db->prepare("UPDATE commande set date_heure_livraison_com=? WHERE id_com=?");
    $statement->execute(array($nouvelle_heure,$id));
    header('Refresh: 0');
  }
  else{
      $isSuccess= false;
  }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="images/logo.png">
        <title>Historique de réservation de Sandwichs | Lycée privé Saint-Vincent</title>
        <meta charset="utf-8">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">


        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
        <script src='script.js'></script>
    </head>

    <body>
      <?php require 'php/header.php' ?>
        <div class="container site">

            <h1 class="text-logo" id ='titre-table'>Historique de commande de <?php echo $user[0] ,' ', $user[1];?> : </h1>

            <div id='filtres'>
              <form class="form" action="" role="form" method="post" id="filtre-form">
                <h3>Filtres : </h3>
                <input type="date" id="heure_avant" name="heure_avant" value="<?php echo $h1;?>">
                <input type="date" id="heure_apres" name="heure_apres" value="<?php echo $h2;?>">
                <button type="submit" class="btn btn-primary" id='filtre-btn'>Filtrer</button>
              </form>
              <form class="form" action="" role="form" method="post">
                  <input type="hidden" name="reset-test" value="1">
                  <div class="form-actions">
                  <button type="submit" class="btn btn-warning" id='reset-btn'>Réinitialiser</button>
                  </div>
              </form> 
            </div>
            

                <table class="table table-striped table-bordered">
                <thead>
                <tr>
                  <th>Sandwich</th>
                  <th>Boisson</th>
                  <th>Dessert</th>
                  <th>Chips</th>
                  <th>Date de livraison</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                require_once 'php/connexion.php';

                $db = connect();

                if (!$filtre){
                    $statement = $db->prepare('SELECT * FROM `commande` WHERE  fk_user_id = ? order by date_heure_livraison_com DESC');
                    $statement-> execute (array($user_id));
                }
                while($item = $statement->fetch()){
                    echo '<tr>';

                    //Nom du sandwich
                    $statement2 = $db->prepare('SELECT nom_sandwich FROM sandwich where id_sandwich=?');
                    $statement2->execute(array($item['fk_sandwich_id']));
                    $temp = $statement2 ->fetch();

                    echo '<td>' .$temp['nom_sandwich'] . '</td>';

                    //Nom de la boisson
                    $statement2 = $db->prepare('SELECT nom_boisson FROM boisson where id_boisson=?');
                    $statement2->execute(array($item['fk_boisson_id']));
                    $temp = $statement2 ->fetch();

                    echo '<td>' .$temp['nom_boisson'] . '</td>';


                    //Nom du dessert
                    $statement2 = $db->prepare('SELECT nom_dessert FROM dessert where id_dessert=?');
                    $statement2->execute(array($item['fk_dessert_id']));
                    $temp = $statement2 ->fetch();

                    echo '<td>' .$temp['nom_dessert'] . '</td>';

                    //Check des chips
                    if ($item['chips_com']==1){
                      echo '<td><i class="fa fa-check" aria-hidden="true" ></i></td>';
                    }
                    else{
                      echo '<td>X</td>';
                    }

                    
                    echo '<td>' .$item['date_heure_livraison_com'] . '</td>';
                    echo '<td width=340>';
                    $check_date = ($item['date_heure_livraison_com'] > date('Y-m-d H:i:s')) ? True: False;
                    if ($item['annule_com']==1){
                      echo 'Actions impossible sur une commande annulée';
                    }
                    else if (!$check_date){
                        echo 'Actions impossible sur une commande déja passée';
                    } 
                    else if ($check_date){
                      echo "<a class='btn btn-primary' data-toggle='modal' data-target='#modal_update". $item['id_com'] ."'><span class='bi-at'></span> Modifier</a>";
                      echo' ';
                      echo "
                      <div class='modal fade' id='modal_update".$item['id_com'] ."'> 
                        <div class='modal-dialog'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <button type='button' class='close' data-dismiss='modal'>x</button>
                                    <h5 class='modal-title'>Modifier la commande</h5>        
                                </div>
                                <div class='modal-body'>          
                                    <p>Selectionez la nouvelle date :</p>
                                    <form class='form' action='' role='form' method='post'>
                                      <input type='hidden' name='id_commande_modif' value=" .$item['id_com'].">
                                      <input type='datetime-local' id='heure' name='nouvelle_heure' value='". str_replace(" ", "T", $item['date_heure_livraison_com']) ."'>
                                </div>
                                <div class='modal-footer'>
                                      <a type='button' href='#' class='btn btn-primary' data-dismiss='modal'>Non</a>
                                      <button type='submit' class='btn btn-danger' >Changer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                      </div>";

                      echo' ';

                      echo "<a class='btn btn-danger' data-toggle='modal' data-target='#modal". $item['id_com'] ."'><span class='bi-x'></span> Annuler</a>
                      <div class='modal fade' id='modal".$item['id_com'] ."'> 
                                <div class='modal-dialog'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <button type='button' class='close' data-dismiss='modal'>x</button>
                                            <h5 class='modal-title'>Annuler la commande</h5>        
                                            </div>
                                        <div class='modal-body'>          
                                            <p>Voulez-vous vraiment annuler la commande</p>
                                        </div>
                                        <div class='modal-footer'>
                                            <form class='form' action='' role='form' method='post'>
                                              <input type='hidden' name='id_commande_supp' value=" .$item['id_com']."/>
                                              <a type='button' href='#' class='btn btn-primary' data-dismiss='modal'>Non</a>
                                              <button type='submit' class='btn btn-danger' >Oui</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>";
                    }


                    echo'</td>';
                  echo'</tr> ';
                }
              echo "                  
                </tbody>
              </table>
              <a id='retour-btn2' href='bvEleve.php' class='btn btn-primary'>Retour</a>
        </div>";

        require 'php/footer.php';
        echo"
    </body>
</html>";
?>