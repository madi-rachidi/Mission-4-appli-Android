<?php

include("vues/v_sommaire.php");
$idVisiteur = $_SESSION['idVisiteur'];
$mois = getMois(date("d/m/Y"));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
$action = $_REQUEST['action'];
switch ($action) {
    case 'saisirFrais': {
            if ($pdo->estPremierFraisMois($idVisiteur, $mois)) {
                $pdo->creeNouvellesLignesFrais($idVisiteur, $mois);
            }
            break;
        }
    case 'validerMajFraisForfait': {
            $lesFrais = $_REQUEST['lesFrais'];
            /* on vérifie qu'il a bien selectionné un bouton */
            if (!empty($_POST['choix'])) {
                $choix = $_REQUEST['choix'];
                $choix = $choix[0];
                $pdo->setVehicule($idVisiteur, $mois, $choix);
            } else {
                ajouterErreur("le type de véhicule n'est pas saisit");
                include("vues/v_erreurs.php");
            }
            /*             * **************** */
            if (lesQteFraisValides($lesFrais)) {
                $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
                $pdo->montantValide($idVisiteur, $mois);
            } else {
                ajouterErreur("Les valeurs des frais doivent être numériques");
                include("vues/v_erreurs.php");
            }
            break;
        }
    case 'validerCreationFrais': {
            $dateFrais = $_REQUEST['dateFrais'];
            $libelle = $_REQUEST['libelle'];
            $montant = $_REQUEST['montant'];
            valideInfosFrais($dateFrais, $libelle, $montant);
            if (nbErreurs() != 0) {
                include("vues/v_erreurs.php");
            } else {
                $pdo->creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $dateFrais, $montant);
            }
            break;
        }
    case 'supprimerFrais': {
            $idFrais = $_REQUEST['idFrais'];
            $pdo->supprimerFraisHorsForfait($idFrais);
            break;
        }
}
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
$vehicule = $pdo->getVehicule($idVisiteur, $mois);
include("vues/v_listeFraisForfait.php");
include("vues/v_listeFraisHorsForfait.php");
?>