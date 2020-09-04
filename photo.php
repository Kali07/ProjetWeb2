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

     	$req = $db->prepare('SELECT * FROM COLLECTION WHERE Nom = ? AND Membre = ?');
        $req->execute( array($_GET['nomCol'], $_SESSION['id'] ));
        $collection= $req->fetch();
        $req->closeCursor();

        $req = $db->prepare('SELECT * FROM PHOTO WHERE Collection = ? AND Nom_img = ?' );
        $req->execute( array( $collection['Id_Collection'], $_GET['nom_photo']));
        $image = $req->fetch();
        $req->closeCursor();

        $chemin = "membre".$membre['Id_membre'].'/originaux/'.$_GET['nomCol'];
        $dossier = 'img/membre/'.$chemin;
        $cheminiature = "membre".$membre['Id_membre'].'/miniatures/'.$_GET['nomCol'];
        $dossiermin = 'img/membre/'.$cheminiature;

     	$_GET['nomCol'] = htmlspecialchars($_GET['nomCol']);
     	$_GET['nom_photo'] = htmlspecialchars($_GET['nom_photo']);
     	
     	if (isset($_GET['supprimer'])) {

            $reponse=$db->prepare('DELETE FROM COMMENTAIRE where Photo_C = ?');
            $reponse->execute(array(
            	$image['Id_photo']));

            $reponse=$db->prepare('DELETE FROM PHOTO where Id_photo = ?');
            $reponse->execute(array(
            	$image['Id_photo']));

           	@unlink($dossier.'/'.$_GET['nom_photo']);

            @unlink($dossier.'/'.$_GET['nom_photo']);
            
            //header('Location:collection.php?nomCol='.$_GET['nomCol']);
     	}
     	if (isset($_POST['comment']) AND !empty($_POST['comment'])) {
     		# code...
     		$commentaire = $db->prepare('INSERT INTO  COMMENTAIRE (Auteur, Date_publication, Contenu, Photo_C) VALUES (:name, :creation, :contenu, :photo)');
            $commentaire->execute(array(
                'name'=> $_SESSION['id'],
                'creation'=> date("Y-m-d H:i:s"),
                'contenu'=> $_POST['comment'],
                'photo'=> $image['Id_photo']));
     	}
     	if (isset($_GET['id_com'])) {
     		# code...
     		$reponse=$db->prepare('DELETE FROM COMMENTAIRE where Id_commentaire = ?');
            $reponse->execute(array($_GET['id_com']));
     	}
     	$req = $db->prepare('SELECT * FROM COMMENTAIRE, MEMBRE WHERE Photo_C = ? AND Id_membre = COMMENTAIRE.Auteur');
        $req->execute( array($image['Id_photo']));

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
                          <li><a href="partagePhoto.php?nomCol=<?=$collection['Nom']?> &amp nom_photo=<?=$_GET['nom_photo']?>"> Partager la Photo</a></li>
                          <li><a href="modifInfo.php?nomCol=<?=$collection['Nom']?> &amp nom_photo=<?=$_GET['nom_photo']?>"> Modifier les informations</a></li>
                          <li><a href="photo.php?nomCol=<?=$_GET['nomCol']?> &amp nom_photo=<?=$_GET['nom_photo']?> &amp supprimer=1"> Supprimer la photo</a></li>
                      </ul>
                  </nav>
              </section>
              <section class="sousBlocGl">
                 <?php
                 	if (isset($_GET['nomCol']) AND isset($_GET['nom_photo'])) {

            			if ($collection) {
            	
            				if ($image) { 

            					if ($it = opendir($dossier)) {
                             ?>
                             		<div class="membre1">
                             	<?php
                             			if (false !== ($fichier = readdir($it))) {
                                  ?>
                                    		<figure>
                                        		<img src="<?=$dossier.'/'.$_GET['nom_photo']?>" alt="icone de 				la photo"/>
                                        		<figcaption> <?=$_GET['nom_photo']?></figcaption>
                                    		</figure>
                                    		<strong>Lieu : </strong>
                                            <?php echo $image['Lieu'];?>
                                            <br>
                                            <strong>Date : </strong>
                                            <?php echo  $image['Date_photo'];?>
                                            <br>
                                            <strong>Evénement : </strong>
                                            <?php echo $image['Evenement'];?>
                                    		<div>
                                    			<h2>Commentaires</h2>
												<form action="photo.php?nomCol=<?=$_GET['nomCol']?>&amp;nom_photo=<?=$_GET['nom_photo']?>" method="post">
   													<div>
        												<label for="comment">Entrez votre commentaire :</label><br />
        												<textarea id="comment" name="comment"></textarea>
    												</div>
    												<div>
        												<input type="submit" name="val" value="envoyer" />
    												</div>
												</form>
                                 			<?php
                                 				while ($commentaire = $req->fetch()) {
                                 			?>
                                 					
                                 					<strong>Auteur : </strong>
                                 					<?php echo $commentaire['Nom']." ".$commentaire['Prenom']."     ".$commentaire['Date_publication'];?>
                                					<br>
                                					<strong>Commentaire : </strong>
                                					<?php echo $commentaire['Contenu'];?>
                                					<br>
                                					<a href="photo.php?nomCol=<?=$collection['Nom']?> &amp nom_photo=<?=$_GET['nom_photo']?> &amp id_com=<?=$commentaire['Id_commentaire']?>">supprimer</a>
                                					<br>
                                 				<?php } ?>
                                 			</div>
                                 	<?php
                             		}
                             		?>
                             		</div> 
                         		<?php
                         			closedir($it);
                             	}
            				}
            				else
            					echo "Erreur lors de la transmission des données2.";
            				$req->closeCursor();
           				}
            			else
            				echo "Erreur lors de la transmission des données1.";
     				}
     				else
     					echo "Erreur lors de la transmission des données.";
     				?>
              </section>
         </div>
         <?php $content = ob_get_clean();
          require('template.php'); 
	}
	else
		header('Location:index.php');
	?>