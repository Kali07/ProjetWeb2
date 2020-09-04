<?php 
     session_start();
     require_once('fonctions.php');

     if (isset($_SESSION['id'])) {
        ob_start();
		//Gestion barre de recherce, affiche les noms des collections en fonction des Identifiants
		if(isset($_Get['rech']) AND !empty($_GET['rech']))
		{
			$rech = htmlspecialchars($_Get['rech']);
			$req = $db->prepare('SELECT Nom FROM COLLECTION WHERE Nom LIKE "%'.$rech.'%" ORDER BY Id_Collection DESC');
		}
		//fin de la requette recherche(reste à verifier si c'est bien fait et si ça va fonctionner, néanmoins //vous pouvez essayer et regarder le lien au dessu ça stoque la recherche dans notre valeur 
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
                          <li><a href="partage.php"> Photos Partagées</a></li>
                          <li><a href="ajout.php"> Ajouter Collection</a></li>
                      </ul>
                  </nav>
              </section>
              <section class="sousBlocGl">
                 <?php

                     $req = $db->prepare('SELECT * FROM COLLECTION WHERE Membre = ?');
                     $req->execute( array( $_SESSION['id'] ));
                     $collection = $req->fetchALL();

                     if ($collection) {

                         $req->closeCursor();

                         $chemin = "membre".$membre['Id_membre'].'/originaux';
                         $dossier = 'img/membre/'.$chemin;
                         if ($it = opendir($dossier)) {
                             ?>
                             <div class="flex">
                             <?php
                             while (false !== ($fichier = readdir($it))) {
                                 if ($fichier != '.' AND $fichier != '..') {
                                    ?>
                                    <figure>
                                        <a href="collection.php?nomCol=<?=$fichier?>"><img src="img/icone.jpg" alt="icone de dossier"/></a>
                                        <figcaption><?=$fichier?></figcaption>
                                    </figure>
                                    <?php
                                 }
                             }
                             ?>
                             </div> 
                         <?php
                         }
                       }
                      else
                        { 
                        ?>
                            <p class="ac">
                                Vous n'avez pas encore créé de collection.
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
      