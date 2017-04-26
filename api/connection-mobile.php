<?php
require_once("../include/fct.inc.php");
require_once ("../include/class.pdogsb.inc.php");
$pdo = PdoGsb::getPdoGsb(); 
$action = $_POST['action'] ; 
$login = $_POST['login'];
$mdp = $_POST['mdp'] ;  
$mdp = sha1($mdp);

if($action == "connecter"){
   $visiteur = $pdo->getInfosVisiteur($login,$mdp);
if ( ! is_array($visiteur)){ /* si les infos de visiteur ou comptable ne sont pas des tableaux*/
		/*print_r ($comptable);*/ //debug
			die(_('Mot de passe incorrect'));
		}
		else if (is_array($visiteur)){
			$type = "visiteur";
			$id = $visiteur['id'];
			$nom =  $visiteur['nom'];
			$prenom = $visiteur['prenom'];
			connecter($id,$nom,$prenom,$type);
                        $temps = 365*86400;
                        setcookie ("id", $id, time() + $temps);
                        setcookie ("nom", $nom, time() + $temps);
                        setcookie ("prenom", $prenom, time() + $temps);
                        echo ("ok");		
		}
}
if ($action == "deconnection"){
    //suppresion des cookies
    setcookie('id', "", time() - 3600, "/");
    setcookie('prenom', "", time() - 3600, "/");
    setcookie('nom', "", time() - 3600, "/");          
    echo ("Vous êtes bien déconnecté");
}
?>
