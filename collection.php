<?php 
     session_start();
     require_once('fonctions.php');

     if (isset($_SESSION['id'])) {
        ob_start();
     $req = $db->prepare('SELECT * FROM MEMBRE WHERE Email = ? AND Id_membre = ?');
     $req->execute( array($_SESSION['email'], $_SESSION['id'] ));
     $membre = $req->fetch();
     $req->closeCursor();
     $_GET['nomCol'] = htmlspecialchars($_GET['nomCol']);
     if (empty($_GET['nomCol'])) {
         header('Location: index.php');
     }

     $req = $db->prepare('SELECT * FROM COLLECTION WHERE Membre = ? AND Nom = ?');
     $req->execute( array( $_SESSION['id'], $_GET['nomCol']));
     $collection = $req->fetch();
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
                          <li><a href="ajoutePhoto.php?nomCol=<?=$collection['Nom']?>"> Ajouter Photo</a></li>
                      </ul>
                  </nav>
              </section>
              <section class="sousBlocGl">
                 <?php

                     if ($collection) {

                         $req->closeCursor();

                         $chemin = "membre".$membre['Id_membre'].'/miniatures/'.$_GET['nomCol'];
                         $dossier = 'img/membre/'.$chemin;
                         if ($it = opendir($dossier)) {
                             ?>
                             <div class="flex">
                             <?php
                             while (false !== ($fichier = readdir($it))) {
                                 if ($fichier != '.' AND $fichier != '..') {
                                    ?>
                                    <figure>
                                        <a href="photo.php?nomCol=<?=$collection['Nom']?> &amp nom_photo=<?=$fichier?>"><img src="<?=$dossier.'/'.$fichier?>" alt="icone de la photo"/></a>
                                        <figcaption> <?= $fichier ?></figcaption>
                                    </figure>
                                    <?php
                                 }
                             }
                             ?>
                             </div> 
                         <?php
                         closedir($it);
                         }
                       }
                      else
                        { 
                        ?>
                            <p class="ac">
                                Vous n'avez pas encore publié de photo.
                            </p>
                        <?php
                        } 
                  ?> 
              </section>
         </div>
         <?php $content = ob_get_clean();
          require('template.php'); 
     } 
     else
     	 header("Location:index.php");?>