<?php
   require_once('fonctions.php');
   $errors  = array();

 if (!isset($_POST['mot_de_passe']) AND isset($_POST['motDePasse']) AND !empty($_POST['motDePasse'])) {
            
     $_POST['email1'] = htmlspecialchars($_POST['email1']);
     $_POST['motDePasse'] = htmlspecialchars($_POST['motDePasse']);
     $_POST['motDePasse'] = hashPassword($_POST['motDePasse']);

     $req = $db->prepare('SELECT * FROM MEMBRE WHERE Email = ? AND mot_de_passe = ?');
     $req->execute( array($_POST['email1'], $_POST['motDePasse']));

     if ($membreByEmail = $req->fetch()) {
         session_start();
         $_SESSION['id'] = $membreByEmail['Id_membre'];
         $_SESSION['email'] = $membreByEmail['Email'];
         header("Location:acceuil.php"); 
     }
     else
     {
         $errors['mail'] = "Email ou mot de passe incorrect(s), réessayez avec les bonnes informations.";
     }
     $req->closeCursor();
 }
 else 
 {
    if (isset($_POST['mot_de_passe']) AND !empty($_POST['mot_de_passe']) AND !isset($_POST['motDePasse'])) {

         $_POST['mot_de_passe'] = htmlspecialchars($_POST['mot_de_passe']);
         $_POST['prenom'] = htmlspecialchars($_POST['prenom']);
         $_POST['email'] = htmlspecialchars($_POST['email']);
         $_POST['name'] = htmlspecialchars($_POST['name']);
         $_POST['valid'] = htmlspecialchars($_POST['valid']);

         if ($_POST['mot_de_passe'] == $_POST['confirm']) {

             $req = $db->prepare('SELECT * FROM MEMBRE WHERE Email = ?');
             $req->execute( array($_POST['email']));
             $membreByEmail = $req->fetchAll();      
                    
             if (!$membreByEmail) {
                if ($_POST['valid'] === "1ZaUbS") {
                     $req->closeCursor();

                     $_POST['mot_de_passe'] = hashPassword($_POST['mot_de_passe']);

                     $membre = $db->prepare('INSERT INTO  MEMBRE(Nom, Prenom, Email, Mot_De_Passe) VALUES (:name, :firstName, :login, :password)');
                     $membre->execute(array(
                        'name'=> $_POST['name'],
                        'firstName'=>  $_POST['prenom'],
                        'login'=>  $_POST['email'],
                        'password'=> $_POST['mot_de_passe']));

                     $req = $db->prepare('SELECT * FROM MEMBRE WHERE Email = ?');
                     $req->execute( array($_POST['email']));
                     $membre = $req->fetch();
                     if ($membre) {
                         $chemin = "membre".$membre['Id_membre'];
                         $dossier = 'img/membre/'.$chemin;
                         if(!is_dir($dossier)){
                             mkdir($dossier, 777, true);
                             if (is_dir($dossier)) {
                                 $originaux = "membre".$membre['Id_membre'].'/originaux';
                                 $dossier_or = 'img/membre/'.$originaux;
                                 if (!is_dir($dossier_or)) {
                                     mkdir($dossier_or, 777, true);
                                     if (is_dir($dossier_or)) {
                                         $miniatures = "membre".$membre['Id_membre'].'/miniatures';
                                         $dossier_min = 'img/membre/'.$miniatures;
                                         if (!is_dir($dossier_min)) {
                                             mkdir($dossier_min, 777, true);
                                        }
                                    }
                                }
                            }
                        } 
                    }
                 
                 $errors['bien'] = "Votre inscription a bien été effectuée, vous pouvez maintenant vous connecter.";
                 $req->closeCursor();
                }
                else
                {
                    $errors['cle'] = "La clé que vous avez entrée n'est pas valide, réessayez avec la bonne";
                }
             }
             else
             {
                 $errors['mail'] = "L'adresse email a déjà été utilisé pour un autre compte, essayez avec un autre SVP.";
                 $req->closeCursor();
             }
         }
         else
         {
             $errors['mot'] = "Vous devez rentrer le même mot de passe lors de la confirmation.";
         }    
     }
 }
?>

<?php ob_start(); ?>
     <div class="bloc">
         <section class="sousBloc">
             <form method="post" action="index.php">
                 <p>
                     <span class="titre"> Anpari </span>
                     <span class="text1">
                     <label for="email1">Email</label> :
                     <input type="email" name="email1" required="required" placeholder="Entrez votre email"
                     size="35" size="35" class="pass1"> 
                     <label for="mot_de_passe">Mot de passe</label> :
                     <input type="password" name="motDePasse" required="required" placeholder="Entrez votre mot de passe..." size="35" class="pass1">
                     <input type="submit" name="connexion" value="Connexion" class="con">
                     </span>
                 </p>
             </form>
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
         <div class="bloc1">
             <section class="sousBloc1">
                 <p>
                     Avec Anpari,  stockez, partagez en toute sécurité et restez en contact avec votre entourage.
                     <figure>
                         <img src="img/image9.4" alt="photo de partage" align="left">
                     </figure>
                 </p>
             </section>
             <section class="sousBloc2">
                 <h3> Créer un compte : </h3> </br>
                 <form method="post"  action="index.php">
                     <p>
                         <label for="name" class="text">Nom</label> :</br> 
                         <input type="text" name="name" required="required" placeholder="Entrez votre nom..." size="35" class="pass">
                     </p>
                     <p>
                         <label for="prenom" class="text">Prénom</label> :</br>
                         <input type="text" name="prenom" required="required" placeholder="Entrez votre prénom..." size="35" class="pass">
                     </p>
                     <p>
                         <label for="email" class="text">Email</label> :</br>
                         <input type="email" name="email" required="required" placeholder="Entrez votre email..." size="35" class="pass"> 
                     </p>
                     <p>
                         <label for="mot_de_passe" class="text">Mot de passe</label> :</br>
                         <input type="password" name="mot_de_passe" required="required" placeholder="Entrez votre mot de passe..." size="35" class="pass">
                     </p>
                     <p>
                         <label for="confirm" class="text">Confirmez le mot de passe</label> :</br>
                         <input type="password" name="confirm" required="required" placeholder="Entrez votre mot de passe..." size="35" class="pass">
                     </p>
                     <p>
                         <label for="valid" class="text">Entrez la clé de validation</label> :</br>
                         <input type="password" name="valid" required="required" placeholder="Entrez la clé de validation..." size="35" class="pass">
                     </p>
                     <p class="inscription">
                         <input type="submit" name="Inscription" value="Inscription" class="ins">
                     </p>
                 </form>
             </section>  
         </div>
     </div>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
