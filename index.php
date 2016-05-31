<?php
require_once( '/class-phpass.php' );

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=wordpress;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}

$valide=false;

if(isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['mdp']) && !empty($_POST['mdp'])){
	$id=$_POST['id'];
	$mdp=$_POST['mdp'];

	$req = $bdd->query('SELECT * FROM vtn_users WHERE user_login="'.$id.'"');
	$results = $req->fetch();

	$cryptmdp=$results['user_pass'];

	$wp_hasher = new PasswordHash( 8, TRUE );

	if($wp_hasher->CheckPassword($mdp, $cryptmdp)){
		$req = $bdd->query('SELECT meta_value FROM wp_usermeta WHERE meta_key="role" AND user_id="'.$results['ID'].'"');
		$results = $req->fetch();

		$role=$results['meta_value'];

		echo 'Bienvenue <b>'.$id.'</b> !<br>Votre role : <b>'.$role.'</b>';
		$valide=true;
	}
	else{
		echo 'Mauvais id/mdp !';
	}
}

if(!$valide){
	?>
	<form action="#" method="POST">
		<label for="id">Identifiant : </label><input type="text" name="id" id="id"/>
		<label for="mdp">Mot de passe : </label><input type="password" name="mdp" id="mdp"/>
		<input type="submit" value="Valider"/>
	</form>
	<?php
} 
?>