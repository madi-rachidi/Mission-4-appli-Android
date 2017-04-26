<?php

include("vues/v_sommaire.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
switch ($action) {
    case 'selectionnerMoisAValider': {
        
            $lesMois = $pdo->getLesMoisAValider();
            // Afin de sélectionner par défaut le dernier mois dans la zone de liste
            // on demande toutes les clés, et on prend la première,
            // les mois étant triés décrfoissants
            array_filter($lesMois); //va clean le tableau et supprimer les lignes qui aurons des valeurs null, vides ou false.
            if (empty($lesMois)) {
                ajouterErreur("Pas de fiche frais a valider ce mois-ci !");
                include("vues/v_erreurs.php");
            } else {
                $lesCles = array_keys($lesMois);
                $moisASelectionner = $lesCles[0];
                include("vues/v_listMoisAValider.php");
            }
            break;
        }
    case 'visiteurFraisAValider': {
            $lesMois = $pdo->getLesMoisAValider();
            $leMois = $_REQUEST['lstMois'];
            $lesCles = array_keys($lesMois);
            $moisASelectionner = $leMois;
            /* var_dump($moisASelectionner); */ //debug
            include("vues/v_listMoisAValider.php");
            $numAnnee = substr($leMois, 0, 4);
            /* modifie le formatage de l'année */
            $numMois = substr($leMois, 4, 2);
            /* modifie le formatage du mois */
            $aValider = $pdo->getLesFicheFraisAValider($leMois);
            include("vues/v_afficheVisiteur.php");
            break;
        }
    case "voirFraisAValider": {
            $valeur = $_REQUEST["valeur"];
            $leMois = $_REQUEST["date"];
            /**/
            $numAnnee = substr($leMois, 0, 4); /* modifie le formatage de l'année */
            $numMois = substr($leMois, 4, 2); /* modifie le formatage du mois */
            /**/
            $lesMois = $pdo->getLesMoisAValider();
            $lesCles = array_keys($lesMois);
            $moisASelectionner = $leMois;
            /**/
            $aValider = $pdo->getLesFicheFraisAValider($leMois);
            /**/
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($valeur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($valeur, $leMois);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($valeur, $leMois);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = $lesInfosFicheFrais['dateModif'];
            $dateModif = dateAnglaisVersFrancais($dateModif);
            /**/
            include("vues/v_listMoisAValider.php");
            include("vues/v_afficheVisiteur.php");
            include("vues/v_validerFiche.php");
            break;
        }
    case 'enregistrement': {
            $repas = $_POST['repas'];
            $nuitee = $_POST['nuitee'];
            $etape = $_POST['etape'];
            $km = $_POST['km'];
            $id = $_REQUEST["idvisiteur"];
            $leMois = $_REQUEST["date"];
            // test afin de voir si toute les valeur son bien saisie
            if (($repas && $nuitee && $etape && $km && $id && $leMois) != null) {
                $pdo->majValeurFicheFrais($id, $leMois, $etape, $km, $nuitee, $repas);
                $pdo->montantValide($id, $leMois);
                echo "enregistrement prit en compte";
                /* affichage de la barre de selection */
                $lesMois = $pdo->getLesMoisAValider();
                // Afin de sélectionner par défaut le dernier mois dans la zone de liste
                // on demande toutes les clés, et on prend la première,
                // les mois étant triés décroissants

                /*                 * ********************************* */

                /* rafficher le visiteur */
                $valeur = $id;
                /**/
                $numAnnee = substr($leMois, 0, 4); /* modifie le formatage de l'année */
                $numMois = substr($leMois, 4, 2); /* modifie le formatage du mois */
                /**/
                $lesMois = $pdo->getLesMoisAValider();
                $lesCles = array_keys($lesMois);
                $moisASelectionner = $leMois;
                /**/
                $aValider = $pdo->getLesFicheFraisAValider($leMois);
                /**/
                $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($valeur, $leMois);
                $lesFraisForfait = $pdo->getLesFraisForfait($valeur, $leMois);
                $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($valeur, $leMois);
                $numAnnee = substr($leMois, 0, 4);
                $numMois = substr($leMois, 4, 2);
                $libEtat = $lesInfosFicheFrais['libEtat'];
                $montantValide = $lesInfosFicheFrais['montantValide'];
                $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
                $dateModif = $lesInfosFicheFrais['dateModif'];
                $dateModif = dateAnglaisVersFrancais($dateModif);
                /**/
                include("vues/v_listMoisAValider.php");
                include("vues/v_afficheVisiteur.php");
                include("vues/v_validerFiche.php");
                break;

                /**/

                /* include("vues/v_validerFiche.php"); */
            } else {
                ajouterErreur("Valeur non saisie");
                include("vues/v_erreurs.php");
            }
            break;
        }
    case 'reporterFrais': {
            $idFrais = $_REQUEST['idFrais'];
            $pdo->reporterFraisHorsForfait($idFrais);
            break;
        }
    case 'refuserFrais': {
            $idFrais = $_REQUEST['idFrais'];
            $pdo->refuserFraisHorsForfait($idFrais);
            break;
        }
    case 'validerFraisHorsForfait': {
            $idFrais = $_REQUEST['idFrais'];
            $pdo->validerFraisHorsForfait($idFrais);
            break;
        }
    case 'mettreEnPaiement': {
            $id = $_REQUEST["idvisiteur"];
            $leMois = $_REQUEST["date"];
            $pdo->mettreEnPaiement($id, $leMois);
            echo "Enregistrement prit en compte ";
            /* reafiche la bare de selection */
            $lesMois = $pdo->getLesMoisAValider();
            // Afin de sélectionner par défaut le dernier mois dans la zone de liste
            // on demande toutes les clés, et on prend la première,
            // les mois étant triés décroissants
            array_filter($lesMois); //va clean le tableau et supprimer les lignes qui aurons des valeurs null, vides ou false.
            if (empty($lesMois)) {
                ajouterErreur("Pas de fiche frais a valider ce mois-ci !");
                include("vues/v_erreurs.php");
            } else {
                $lesCles = array_keys($lesMois);
                $moisASelectionner = $lesCles[0];
                include("vues/v_listMoisAValider.php");
            }
            break;
        }
    case 'suiviPaiement': {
        // récuperation de toutes les fiches qui sont 'VA'
            $listeFichesFrais = $pdo->getFicheFraisSuivre();
            include("vues/v_suiviFiche.php");
            break;
        }
    case 'valideChoixFiche': {
            $listeFichesFrais = $pdo->getFicheFraisSuivre();
            // On récupère le visiteur et le mois
            $dateValide = substr($_REQUEST['lstVisiteur'], 0, 6);
            $visiteur = substr($_REQUEST['lstVisiteur'], 6, strlen($_REQUEST['lstVisiteur']));
            //le type de véhicule du visiteur
            $vehicule = $pdo->getVehicule($visiteur, $dateValide);
            $montant1 = $pdo->getMontantVehicule($vehicule[0]);
            // On récupère toutes les infos de la fiche du visiteur pour le mois
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteur, $dateValide);
            /*retourne les nom des visiteurs*/
            $listeVisiteur = $pdo->getNomPrenomIdVisiteur();
            $nomPrenomVisiteur = $pdo->getNomPrenomVisiteur($visiteur);
            /**/
            $nbJustificatifs = $pdo->getNbjustificatifs($visiteur, $dateValide);
            
            $lesFraisForfait = $pdo->getLesFraisForfait($visiteur, $dateValide);
            /**/
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfaitNonREF($visiteur, $dateValide);
            $lesFraisHorsForfaitREF = $pdo->getLesFraisHorsForfaitREF($visiteur, $dateValide);
            /**/
            $montantValide = $lesInfosFicheFrais['montantValide'];
            include ('vues/v_suiviFiche.php');
            // Vérification si aucune fiche n'est retournée
            if (empty($lesInfosFicheFrais)) {
                ajouterErreur("Fiche inexistante");
                include("vues/v_erreurs.php");
                ;
            } else {
                include ("vues/v_suivreFrais.php");
            }
            break;
        }
    case'rembourser': {
            // on recupere les variable id et mois afin de trouver la bonne fiche a modifier
            $mois = $_REQUEST['in_mois'];
            $id = $_REQUEST['in_user'];

            $pdo->rembourserFraisForfait($id, $mois);

            // on réafiche la selection des fiches validée
            $listeFichesFrais = $pdo->getFicheFraisSuivre();
            echo "Enregistrement prit en compte !";
            include("vues/v_suiviFiche.php");
            break;
        }
    case'montantValide': {
            $pdo->montantValide($id, $mois);
            break;
        }
    case'pdf': {
          //  $pdo->creerPdf();
        $dateValide = $_REQUEST['date'];
        $visiteur = $_REQUEST['id'];
            $vehicule = $pdo->getVehicule($visiteur, $dateValide);
            $montant1 = $pdo->getMontantVehicule($vehicule[0]);
            // On récupère toutes les infos de la fiche du visiteur pour le mois
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteur, $dateValide);
            /**/
            $listeVisiteur = $pdo->getNomPrenomIdVisiteur();
            $nomPrenomVisiteur = $pdo->getNomPrenomVisiteur($visiteur);
            /**/
            $nbJustificatifs = $pdo->getNbjustificatifs($visiteur, $dateValide);
            $lesFraisForfait = $pdo->getLesFraisForfait($visiteur, $dateValide);
            /**/
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfaitNonREF($visiteur, $dateValide);
            $lesFraisHorsForfaitREF = $pdo->getLesFraisHorsForfaitREF($visiteur, $dateValide);
            /**/
            $montantValide = $lesInfosFicheFrais['montantValide'];
        include("vues/v_pagePdf.php");
            break;
        }
}
?>