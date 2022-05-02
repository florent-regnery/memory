<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <link rel="stylesheet" href="./assets/style.css">
   <title>Memory</title>
</head>
<header>
   <div>
      <p>Memory</p>
   </div>

   <?php
   if (session_status() === PHP_SESSION_NONE) {
      session_start();
   }
   ?>
   <!-- on verif' si l'utilisateur est connecté dans ce cas on lui affiche des options différentes -->
   <p class="menu-link">
      <a href="index.php" class="href">Accueil</a></li>
      <a href="wall.php" class="href">Wall</a></li>
      <?php if (isset($_SESSION['user'])) : ?>
         <a href="deconnexion.php" class="connexion">Déconnexion</a></li>
      <?php else : ?>
         <a href="formulaire.php" class="inscription">Inscription</a>
         <a href="connexion.php" class="connexion">Connexion</a>
      <?php endif; ?>
   </p>

</header>

<body>
   <?= $content ?>
</body>

</html>