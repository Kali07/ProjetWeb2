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
        
        if (isset($_POST['lieu']) OR isset($_POST['evenement'])) {
            # code...
            $_POST['date'] = htmlspecialchars($_POST['date']);
            $_POST['lieu'] = htmlspecialchars($_POST['lieu']);
            $_POST['evenement'] = htmlspecialchars($_POST['evenement']);
            $_GET['nomCol'] = htmlspecialchars($_GET['nomCol']);
            $_GET['nom_photo'] = htmlspecialchars($_GET['nom_photo']);

            $chemin = "membre".$membre['Id_membre'].'/originaux/'.$_GET['nomCol'];
            $dossier = 'img/membre/'.$chemin;
            $cheminiature = "membre".$membre['Id_membre'].'/miniatures/'.$_GET['nomCol'];
            $dossiermin = 'img/membre/'.$cheminiature;

            $req = $db->prepare('SELECT * FROM COLLECTION WHERE Nom = ? AND Membre = ?');
            $req->execute( array($_GET['nomCol'], $_SESSION['id'] ));
            $collection= $req->fetch();
            $req->closeCursor();

                if (!empty($_POST['lieu'])) {

                    $req = $db->prepare('UPDATE  PHOTO SET Lieu = ?, Date_photo = ? WHERE Collection = ? AND Nom_img = ?' );
                    $req->execute( array( $_POST['lieu'], $_POST['date'], $collection['Id_Collection'], $_GET['nom_photo']));

                }
                if (!empty($_POST['evenement'])) {

                    $req = $db->prepare('UPDATE  PHOTO SET Evenement = ?, Date_photo= ? WHERE Collection = ? AND Nom_img = ?' );
                    $req->execute( array( $_POST['evenement'], $_POST['date'], $collection['Id_Collection'], $_GET['nom_photo']));
                    echo "string".$_POST['evenement'];

                }
                $errors['mes'] = "Bien, les modifications apportées ont été éffectuées.";
            
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
                 <form method="post" action="modifInfo.php?nomCol=<?=$_GET['nomCol']?> &amp nom_photo=<?=$_GET['nom_photo']?>" enctype="multipart/form-data">
                     <fieldset>
                        <legend>Infos relatives à la Photo</legend>
                            <label for="lieu">Lieu (max. 50 caractères):</label><br/>
                            <input type="text" name="lieu" placeholder="Lieu" class="rep" maxlength="50" /><br/>
                            <label for="evenement">Evènement (max. 50 caractères):</label><br/>
                            <input type="text" name="evenement" placeholder="Evènement" class="rep" maxlength="50" /><br/>
                            <label for="date">Date de l'évènement (max. 50 caractères):</label><br/>
                            <input type="date" name="date" placeholder="Date de l'évènement" class="rep" maxlength="50" required="required" /><br/>       
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