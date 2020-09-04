<?php 
     session_start();
     require_once('fonctions.php');

     if (isset($_SESSION['id'])) {
        ob_start();
     $req = $db->prepare('SELECT * FROM MEMBRE WHERE Email = ? AND Id_membre = ?');
     $req->execute( array($_SESSION['email'], $_SESSION['id'] ));
     $membre1 = $req->fetch();
     $req->closeCursor();
     $req = $db->prepare('SELECT * FROM COLLECTION WHERE Membre = ? AND Nom = ?');
     $req->execute( array( $_SESSION['id'], $_GET['nomCol']));
     $collection = $req->fetch();
     $req->closeCursor();
     $req = $db->prepare('SELECT * FROM PHOTO WHERE Collection = ? AND Nom_img = ?');
     $req->execute( array( $collection['Id_Collection'], $_GET['nom_photo']));
     $image = $req->fetch();
     $req->closeCursor();

     if (isset($_GET['nomCol']) AND isset($_GET['nom_photo'])) {
         # code...
        $_GET['nomCol'] = htmlspecialchars($_GET['nomCol']);
        $_GET['nom_photo'] = htmlspecialchars($_GET['nom_photo']);
        if (isset($_GET['id_pers'])) {
            # code...
            $_GET['id_pers'] = htmlspecialchars($_GET['id_pers']);

            $partage = $db->prepare('SELECT * FROM PARTAGE  WHERE Personne = :name AND Photo = :photo');
            $partage->execute(array(
                'name'=> $_GET['id_pers'],
                'photo'=> $image['Id_photo']));
            $test = $partage->fetchALL();
            if (!$test) {
                $partage = $db->prepare('INSERT INTO  PARTAGE (Photo, Personne) VALUES (:hoto, :name)');
                $partage->execute(array(
                    'hoto'=> $image['Id_photo'],
                    'name'=> $_GET['id_pers']));
                header('Location:collection.php?nomCol='.$_GET['nomCol']);
            }
           
        }
     }
     

        ?>
        <div class="bloc">
             <section class="sousBloc">
                     <p>
                        <nav> 
                            <span class="titre"> Anpari : </span>
                            <span class="recherche">
                                 <form method="post" action="recherche.php">
                                     <input type="search" name="rech" id="search" placeholder="Recherche ..." class="rec"/>
                                     <input type="submit" name="" id="resh" value="recherche" class="bout"/>
                                 </form>
                            </span>
                            <span class="liens1">
                                <a href="notification.php">  Notifications</a>
                            </span>
                            <span class="liens3">
                                <a href="deconnexion.php"> Déconnexion</a>
                            </span>    
                        </nav>
                     </p>
             </section>
         </div>
         <div class="bloc2">
              <section class="sousBlocKl">
                  <p class="infos">
                      Prénom : <?= $membre1['Prenom'] ?><br>
                      Nom : <?= $membre1['Nom'] ?>
                  </p>
                  <nav class="lien2">
                      <ul>
                          <li><a href="acceuil.php"> Retour à l'acceuil</a></li>
                          <li><a href="partage.php"> Photos Partagées</a></li>
                          <li><a href="ajoutePhoto.php?nomCol=<?=$collection['Nom']?>"> Ajouter Photo</a></li>
                      </ul>
                  </nav>
              </section>
              <section class="sousBlocGl">
                 <?php
                        $req = $db->prepare('SELECT * FROM MEMBRE WHERE Email != ? AND Id_membre != ?');
                        $req->execute( array($_SESSION['email'], $_SESSION['id'] ));
                    ?>
                    
                        <?php
                        while ($membre = $req->fetch()) {
                          ?>        
                            <div class="membre">      
                                <strong>Nom : </strong><?php 
                                    echo $membre['Nom'];
                                ?>
                                <br>
                                <strong>Prenom : </strong><?php 
                                    echo $membre['Prenom'];
                                ?>
                                <br>
                                <a href="partagePhoto.php?nomCol=<?=$collection['Nom']?> &amp nom_photo=<?=$_GET['nom_photo']?> &amp id_pers=<?=$membre['Id_membre']?>">Partager avec lui</a>
                                <br>
                            </div>
                        <?php
                        }
                        $req->closeCursor();
                    ?> 
                        
              </section>
         </div>
         <?php $content = ob_get_clean();
          require('template.php'); 
     } 
     else
         header("Location:index.php");?>