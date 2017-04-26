<?php
require_once("../include/fct.inc.php");
require_once ("../include/class.pdogsb.inc.php");
$pdo = PdoGsb::getPdoGsb(); 
//récuperation des cookies
if (isset($_COOKIE['id']) && isset($_COOKIE['nom']) && isset($_COOKIE['prenom'])) {
	$id = $_COOKIE['id'];
        $nom = $_COOKIE['nom'];
        $prenom = $_COOKIE['prenom'];
        $type = "comptable";
}else{
    die(_('Erreur cookies, veuillez vous reconnecter'));
}
$list = $_POST['list']; // récupération de la list envoyé par le mobile

if ($list == null){
    die(_('Probleme de reception'));
}
$mois=$list["annee"].$list["mois"];
$dateFr = $list["mois"]."/".$list["annee"];
$listFraisHorsForfais =$list["FraisHF"];
if(!isset($listFraisHorsForfais)){
    array_filter($list);
}else{
    unset($list["FraisHF"]); 
}
 // retire les elements hors forfait du tab
 unset($list["annee"]);
 unset($list["mois"]);
 //création d'un nouveau frais au début du mois
if ($pdo->estPremierFraisMois($id, $mois)) {
                $pdo->creeNouvellesLignesFrais($id, $mois); 
            }      
            
  $pdo->majFraisForfait($id, $mois, $list);
  $pdo->supprimerFraisHorsForfaitDuMois($mois); // supprime tout les frais hors forfait du mois
            if(isset($listFraisHorsForfais)){
               
                foreach ($listFraisHorsForfais as $value) {
                     $pdo->creeNouveauFraisHorsForfait($id, $mois, $value["motif"], $value["jour"]."/".$dateFr, $value["montant"]);
                }               
            }
            $pdo->montantValide($id, $mois);
echo "OK";
?>
