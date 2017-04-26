<?php
if(!isset($_REQUEST['action'])){ /*action vient de v_connexion et action=valieconnexion*//* on vérifie simplement si la variable action est vide ou pas*/
	$_REQUEST['action'] = 'demandeConnexion'; /*si action n'existe pas nexist pas  */
}
$action = $_REQUEST['action'];/* on transforme action en variable afin de le tester*/
switch($action){
	case 'demandeConnexion':{
		include("vues/v_connexion.php");/*renvoi vers la page de connexion*/
		break;
	}
	case 'valideConnexion':{
		$login = $_REQUEST['login'];
		$mdp = $_REQUEST['mdp'];
                $mdp = sha1($mdp);
		$comptable = $pdo->getInfosComptable($login,$mdp); /*création d'un variable comptable*/
	/*var_dump($comptable);	*/ //debug	
		$visiteur = $pdo->getInfosVisiteur($login,$mdp);
		 /*comptable*/
		
		if ( ! is_array($comptable) && ! is_array($visiteur)){ /* si les infos de visiteur ou comptable ne sont pas des tableaux*/
		/*print_r ($comptable);*/ //debug
			ajouterErreur("Login ou mot de passe incorrect !");
			include("vues/v_erreurs.php");
			include("vues/v_connexion.php");
		}
		else if (is_array($visiteur)){
			$type = "visiteur";
			$id = $visiteur['id'];
			$nom =  $visiteur['nom'];
			$prenom = $visiteur['prenom'];
			connecter($id,$nom,$prenom,$type);
			include("vues/v_sommaire.php");
		}
			else{
			$type = "comptable";
			$id = $comptable['id'];
			$nom =  $comptable['nom'];
			$prenom = $comptable['prenom'];
			connecter($id,$nom,$prenom,$type);
			include("vues/v_sommaire.php");
			}
		
		break;
        }
        case'deconnexion':{
            deconnecter();
            include("vues/v_connexion.php");
		break;
	}
	default :{
		include("vues/v_connexion.php");
		break;
	}
}
?>