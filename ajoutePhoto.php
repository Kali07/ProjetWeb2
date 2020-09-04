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
    
        if (isset($_FILES['image'])) {
            $_POST['nom'] = htmlspecialchars($_POST['nom']);
            $_POST['lieu'] = htmlspecialchars($_POST['lieu']);
            $_POST['evenement'] = htmlspecialchars($_POST['evenement']);
            $_POST['description'] = htmlspecialchars($_POST['description']);
            $_GET['nomCol'] = htmlspecialchars($_GET['nomCol']);

            $req = $db->prepare('SELECT * FROM COLLECTION WHERE Nom = ? AND Membre = ?');
            $req->execute( array($_GET['nomCol'], $_SESSION['id'] ));
            $collection= $req->fetch();
            $req->closeCursor();
            $chemin = "membre".$membre['Id_membre'].'/originaux/'.$_GET['nomCol'];
            $dossier = 'img/membre/'.$chemin;
            $cheminiature = "membre".$membre['Id_membre'].'/miniatures/'.$_GET['nomCol'];
            $dossiermin = 'img/membre/'.$cheminiature;
           
            if ($_FILES['image']['error'] === 0){

                $extensions_valides = array( 'jpg' , 'jpeg' , 'png' );
                $extension_upload = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
                if (in_array($extension_upload,$extensions_valides)){
                    if (!empty($_POST['nom'])) {
                        $req = $db->prepare('SELECT (Id_photo) FROM PHOTO WHERE Collection = ? AND Nom_img = ?' );
                        $req->execute( array( $collection['Id_Collection'], $_POST['nom']));
                        $image = $req->fetch();
                        if (!$image) {
                            
                            move_uploaded_file($_FILES['image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/projet/'.$dossier.'/'.$_POST['nom'].strrchr($_FILES['image']['name'], '.'));

                            redimmension2($_SERVER['DOCUMENT_ROOT'].'/projet/'.$dossier.'/'.$_POST['nom'].strrchr($_FILES['image']['name'], '.'), $dossier, $_POST['nom'].strrchr($_FILES['image']['name'], '.'));

                            redimmension($_SERVER['DOCUMENT_ROOT'].'/projet/'.$dossier.'/'.$_POST['nom'].strrchr($_FILES['image']['name'], '.'), $dossiermin, $_POST['nom'].strrchr($_FILES['image']['name'], '.'));
                            $image = $db->prepare('INSERT INTO  PHOTO (Nom_img, Date_photo, Date_pub, Lieu, Evenement, Collection) VALUES (:name, :datec, :datep, :lieu, :evenement, :col)');
                            $image->execute(array(
                            'name'=> $_POST['nom'].strrchr($_FILES['image']['name'], '.'),
                            'datec'=> $_POST['date'],
                            'datep'=> date("Y-m-d H:i:s"),
                            'lieu'=>  $_POST['lieu'],
                            'evenement'=> $_POST['evenement'],
                            'col'=> $collection['Id_Collection']));

                            if (!empty($_POST['description'])) {
                                            # code...
                                $req = $db->prepare('SELECT (Id_photo) FROM PHOTO WHERE Collection = ? AND Nom_img = ?' );
                                $req->execute( array( $collection['Id_Collection'], $_POST['nom'].strrchr($_FILES['image']['name'], '.')));
                                $image = $req->fetch();

                                $commentaire = $db->prepare('INSERT INTO  COMMENTAIRE (Auteur, Date_publication, Contenu, Photo_C) VALUES (:name, :creation, :contenu, :photo)');
                                $commentaire->execute(array(
                                    'name'=> $_SESSION['id'],
                                    'creation'=> date("Y-m-d H:i:s"),
                                    'contenu'=> $_POST['description'],
                                    'photo'=> $image['Id_photo']));
                            }
                        }
                        $req->closeCursor();
                        header('Location:collection.php?nomCol='.$_GET['nomCol']);
                    }
                    else
                    {
                        $req = $db->prepare('SELECT (Id_photo) FROM PHOTO WHERE Collection = ? AND Nom_img = ?' );
                        $req->execute( array( $collection['Id_Collection'], $_FILES['image']['name']));
                        $image = $req->fetch();

                        if (!$image) {
                            move_uploaded_file($_FILES['image']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/projet/'.$dossier.'/'.$_FILES['image']['name']);
                            redimmension2($_SERVER['DOCUMENT_ROOT'].'/projet/'.$dossier.'/'.$_FILES['image']['name'], $dossier, $_FILES['image']['name']);
                            redimmension($_SERVER['DOCUMENT_ROOT'].'/projet/'.$dossier.'/'.$_FILES['image']['name'], $dossiermin, $_FILES['image']['name']);

                            $image = $db->prepare('INSERT INTO  PHOTO (Nom_img, Date_photo, Date_pub, Lieu, Evenement, Collection) VALUES (:name, :datec, :datep, :lieu, :evenement, :col)');
                            $image->execute(array(
                                'name'=> $_FILES['image']['name'],
                                'datec'=> $_POST['date'],
                                'datep'=> date("Y-m-d H:i:s"),
                                'lieu'=>  $_POST['lieu'],
                                'evenement'=> $_POST['evenement'],
                                'col'=> $collection['Id_Collection']));

                            if (!empty($_POST['description'])) {
                                            # code...
                                $req = $db->prepare('SELECT (Id_photo) FROM PHOTO WHERE Collection = ? AND Nom_img = ?' );
                                $req->execute( array( $collection['Id_Collection'], $_FILES['image']['name']));
                                            $image = $req->fetch();

                                $commentaire = $db->prepare('INSERT INTO  COMMENTAIRE (Auteur, Date_publication, Contenu, Photo_C) VALUES (:name, :creation, :contenu, :photo)');
                                $commentaire->execute(array(
                                    'name'=> $_SESSION['id'],
                                    'creation'=> date("Y-m-d H:i:s"),
                                    'contenu'=> $_POST['description'],
                                    'photo'=> $image['Id_photo']));
                            }
                        }
                        $req->closeCursor();
                        header('Location:collection.php?nomCol='.$_GET['nomCol']);
                    }
                }
                else
                   $errors['ext'] = "Erreur, cet extension n'est pas prise en compte, recommencez avec une autre.";
            }
            else
               $errors['char'] = "Erreur rencontrée lors du chargement du fichier, recommencez s'il vous plait."; 
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
         </section>
         <?php
           if (!empty($errors)) {?>
              <section class="error">
                <?php foreach($errors as $element)
                    {
                     echo $element . '<br />';
                    }?>
             </section>
         <?php  
            } 
          ?>
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
              <section class="col">
                 <form method="post" action="ajoutePhoto.php?nomCol=<?=$_GET['nomCol']?>" enctype="multipart/form-data">
                     <fieldset>
                        <legend>Infos relatives à la Photo</legend>
                            <label for="nom">Nom du Fichier (max. 50 caractères):</label><br/>
                            <input type="text" name="nom" placeholder="Nom du fichier" class="rep" maxlength="50" /><br/>
                            <label for="lieu">Lieu (max. 50 caractères):</label><br/>
                            <input type="text" name="lieu" placeholder="Lieu" class="rep" maxlength="50" /><br/>
                            <label for="evenement">Evènement (max. 50 caractères):</label><br/>
                            <input type="text" name="evenement" placeholder="Evènement" class="rep" maxlength="50" /><br/>
                            <label for="date">Date de l'évènement (max. 50 caractères):</label><br/>
                            <input type="date" name="date" placeholder="Date de l'évènement" class="rep" maxlength="50" required="required" /><br/>
                            <label for="image">Photo(JPG ou PNG) :</label><br/>
                            <input type="file" name="image" class="image" required="required" maxlength="50" /><br/>
                            <label for="description"> Commentaire (max. 255 caractères) :</label><br/>
                            <textarea name="description" class="description" maxlength="255"></textarea><br/>       
                     </fieldset>
                     <input type="submit" name="submit" value="Envoyer" class="valid" />
                 </form>
              </section>
         </div>

     <?php $content = ob_get_clean();
          require('template.php'); 
    }
    else
        header("Location:index.php");
  ?>