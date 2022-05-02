<?php
session_start();

// connexion à la base de donnée
$bdd = new PDO('mysql:host=localhost;dbname=memory', 'root', '');

if (isset($_POST['connexion'])) {
	$login = htmlspecialchars($_POST['login']);
	$password = sha1($_POST['password']);
	// Si login et password n'est pas vide
	if (!empty($login) && !empty($password)) {
		// preparation de la requete et execute
		$requser = $bdd->prepare("SELECT id, login FROM utilisateur WHERE login = :login AND password = :password");
		$requser->execute([
			'password' => $password,
			'login' => $login,
		]);
		// Retourne le nombre de lignes affectées par le dernier appel à la fonction 
		$userexist = $requser->rowCount();

		if ($userexist == 1) {
			// recupere le resultat et les infos
			$userinfo = $requser->fetch();
			$_SESSION['user'] = $userinfo['id'];

			header("Location: index.php");
		} else {
			$erreur = "Mauvais identifiant ou mot de passe !";
		}
	} else {
		$erreur = "Tous les champs doivent être complétés !";
	}
}
ob_start();
?>
<div align="center">
	<br />
	<h1>Connexion</h1>
	<br />
	<form method="POST" action="" class="form">
		<fieldset class="field">
			<input type="login" name="login" placeholder="Identifiant.." />
			<br /><br />
			<input type="password" name="password" placeholder="Mot de passe.." />
			<br /><br />
			<input class=" bouton " type="submit" name="connexion" value="Se connecter" />
		</fieldset>
	</form>
	<?php
	if (isset($erreur)) {
		echo '<br/><font color="red">' . $erreur . "</font><br/>";
	}
	?>
	</br>
</div>

<?php
$content = ob_get_clean();
require_once 'template.php';
?>