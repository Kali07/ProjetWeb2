<?php 
     session_start();
     require_once('fonctions.php');

     if (isset($_SESSION['id'])) { 

      ob_start();

      $errors = array();

      $req = $db->prepare('SELECT * FROM MEMBRE WHERE Email = ? AND Id_membre = ?');
      $req->execute( array($_SESSION['email'], $_SESSION['id'] ));
      $membre = $req->fetch();
      $req->closeCursor();
        
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
                      Prénom : <?= $membre['Prenom'] ?><br>
                      Nom : <?= $membre['Nom'] ?>
                  </p>
                  <nav class="lien2">
                      <ul>
                          <li><a href="acceuil.php"> Retour à l'acceuil</a></li>
                          <li><a href="partage.php"> Photos Partagées</a></li>
                      </ul>
                  </nav>
              </section>
              <section class="sousBlocGl">
                <div class="flex">
                 <?php

                  if (isset($_POST['rech']) AND !empty($_POST['rech'])) {
                        $req = $db->prepare('SELECT * FROM PHOTO, COLLECTION WHERE Lieu = ? OR Evenement = ? AND Membre = ? AND  COLLECTION.Id_collection = PHOTO.Collection');
                        $req->execute( array($_POST['rech'], $_POST['rech'], $_SESSION['id'] ));

                        while ($image = $req->fetch())
                        {

                         $chemin = "membre".$image['Membre'].'/miniatures/'.$image['Nom'];
                         $dossier = 'img/membre/'.$chemin;
                         
                         if ($it = opendir($dossier)) {
                             ?>
                             <div class="flex">
                             <?php
                             if (false !== ($fichier = readdir($it))) {
                                    ?>
                                    <figure>
                                        <a href="photo.php?nomCol=<?=$image['Nom']?> &amp nom_photo=<?=$image['Nom_img']?>"><img src="<?=$dossier.'/'.$image['Nom_img']?>" alt="icone de la photo"/></a>
                                        <figcaption><?=$image['Nom_img']?></figcaption>
                                    </figure>
                                    <?php
                             }
                             ?>
                             </div> 
                         <?php
                         closedir($it);
                         }
                       }
                       $req->closeCursor(); 
                  }
                     
                  ?> 
                </div>
              </section>
         </div>
         <?php $content = ob_get_clean();
          require('template.php'); 
     } 
     else
         header("Location:index.php");?>