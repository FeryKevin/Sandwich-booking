<section id="navbar">

    <!-- navbar -->

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="logo">
                    <img src="images/logo.png" class="navbar-brand">
                    <p class="navbar-brand">Lycée Saint-Vincent</p>
                </div>
            </div>

            <!-- contenu navbar -->

            <div class="collapse navbar-collapse">
                <div class="lien">
                    <ul class="nav navbar-nav pull-left"><br> 
                        <a href=""></a>
                        <a href="index.php">Accueil</a>
                    </ul>
                    <?php
                    
                    
                    session_start();
                    
                    if (!$_SESSION['ifco']){
                        echo '<ul class="nav navbar-nav pull-right"><br>
                        <a href="login.php">Connexion</a>
                        </ul>';
                    }
                    else{
                        echo '
                        <ul class="nav navbar-nav pull-right"><br>
                        <a href="bvEleve.php">'.$_SESSION['username'][0].' ' .$_SESSION['username'][1].'</a>
                        </ul>';
                    }
                                       
                    ?>
                    
                </div>
            </div>
        </div>
    </nav>

</section>