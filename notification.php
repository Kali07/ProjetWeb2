
<?php ob_start(); ?>
<div class="bloc">
     <section class="sousBloc">
         <form method="post" action="index.php">
             <p>
                 <span class="titre"> Anpari :</span>
                 <span class="text1">
                     <label for="email1">Email</label> :
                     <input type="email" name="email1" required="required" placeholder="Entrez votre email"
                     size="35" size="35" class="pass1"> 
                     <label for="mot_de_passe">Mot de passe</label> :
                     <input type="password" name="motDePasse" required="required" placeholder="Entrez votre mot de passe..." size="35" minlength="9" class="pass1">
                     <input type="submit" name="connexion" value="Connexion" class="con">
                 </span>
             </p>
         </form>
     </section>
     <div class="bloc1">
         <section class="sousBloc1">
             <p>
                 Avec Anpari,  stockez, partagez en toute sécurité et restez en contact avec votre entourage.
                 <figure>
                     <img src="image9.4" alt="photo de partage" align="left">
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
                     <input type="email" name="email" required="required" placeholder="Entrez votre email..."
                     size="35" class="pass"> 
                 </p>
                 <p>
                     <label for="mot_de_passe" class="text">Mot de passe</label> :</br>
                     <input type="password" name="mot_de_passe" required="required" placeholder="Entrez votre mot de passe..." size="35" minlength="9" class="pass">
                 </p>
                 <p>
                     <label for="confirm" class="text">Confirmez le mot de passe</label> :</br>
                     <input type="password" name="confirm" required="required" placeholder="Entrez votre mot de passe..." size="35" minlength="9" class="pass">
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