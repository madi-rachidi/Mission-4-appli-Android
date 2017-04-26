<?php

/**
 * Classe d'accès aux données. 

 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe

 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */
class PdoGsb {

    private static $serveur = 'mysql:host=localhost';
    private static $bdd = 'dbname= amadirachidi';
    private static $user = 'amadirachidi';
    private static $mdp = 'congo4Do';
    private static $monPdo;
    private static $monPdoGsb = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct() {
        PdoGsb::$monPdo = new PDO(PdoGsb::$serveur . ';' . PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
        PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
    }

    public function _destruct() {
        PdoGsb::$monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe

     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();

     * @return l'unique objet de la classe PdoGsb
     */
    public static function getPdoGsb() {
        if (PdoGsb::$monPdoGsb == null) {
            PdoGsb::$monPdoGsb = new PdoGsb();
        }
        return PdoGsb::$monPdoGsb;
    }

    /**
     * Retourne les informations d'un visiteur

     * @param $login 
     * @param $mdp
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
     */
    public function getInfosVisiteur($login, $mdp) {
        $req = "select visiteur.id as id, visiteur.nom as nom, visiteur.prenom as prenom from visiteur 
		where visiteur.login='$login' and visiteur.mdp='$mdp'";
        $rs = PdoGsb::$monPdo->query($req);
        $ligne = $rs->fetch();
        return $ligne;
    }

    /**
     * Retourne les informations d'uncomtabl
     * @param $login 
     * @param $mdp
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
     */
    public function getInfosComptable($login, $mdp) {
        $req = "select comptable.id as id, comptable.nom as nom, comptable.prenom as prenom from comptable 
		where comptable.login='$login' and comptable.mdp='$mdp'";
        $rs = PdoGsb::$monPdo->query($req);
        $ligne = $rs->fetch();
        return $ligne;
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
     * concernées par les deux arguments

     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois) {
        $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idvisiteur ='$idVisiteur' 
		and lignefraishorsforfait.mois = '$mois' ";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        $nbLignes = count($lesLignes);
        for ($i = 0; $i < $nbLignes; $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }
    
      /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
     * concernées par les deux arguments

     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
     */
    public function getLesFraisHorsForfaitNonREF($idVisiteur, $mois) {
        $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idvisiteur ='$idVisiteur' 
		and lignefraishorsforfait.mois = '$mois' and situation = 'VAL' ";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        $nbLignes = count($lesLignes);
        for ($i = 0; $i < $nbLignes; $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }
    
    public function getLesFraisHorsForfaitREF($idVisiteur, $mois) {
        $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idvisiteur ='$idVisiteur' 
		and lignefraishorsforfait.mois = '$mois' and situation = 'REF' ";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        $nbLignes = count($lesLignes);
        for ($i = 0; $i < $nbLignes; $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

    /**
     * Retourne le nombre de justificatif d'un visiteur pour un mois donné

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return le nombre entier de justificatifs 
     */
    public function getNbjustificatifs($idVisiteur, $mois) {
        $req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        return $laLigne['nb'];
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
     * concernées par les deux arguments

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
     */
    public function getLesFraisForfait($idVisiteur, $mois) {
        $req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle,
		lignefraisforfait.quantite as quantite,fraisforfait.montant as montant from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        return $lesLignes;
    }

    /**
     * Retourne tous les id de la table FraisForfait

     * @return un tableau associatif 
     */
    public function getLesIdFrais() {
        $req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
        $res = PdoGsb::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        return $lesLignes;
    }

    /**
     * 
     * @return un tableau avec les fiche frais a valider***********************************************************
     */
    public function getLesFicheFraisAValider($mois) {
        $req = "SELECT `idVisiteur`, visiteur.nom, visiteur.prenom FROM `fichefrais` INNER JOIN visiteur 
                        WHERE `idEtat`= 'CL' AND `mois`= '$mois' AND fichefrais.`idVisiteur` = visiteur.`id`";
        $res = PdoGsb::$monPdo->query($req);
        $ligne = $res->fetchAll();
        return $ligne;
    }

    /**
     * Met à jour la table ligneFraisForfait

     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
     * @return un tableau associatif 
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais) {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idvisiteur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
            PdoGsb::$monPdo->exec($req);
        }
    }

    /**
     * met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs) {
        $req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     * Teste si un visiteur possède une fiche de frais pour le mois passé en argument

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return vrai ou faux 
     */
    public function estPremierFraisMois($idVisiteur, $mois) {
        $ok = false;
        $req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        if ($laLigne['nblignesfrais'] == 0) {
            $ok = true;
        }
        return $ok;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur

     * @param $idVisiteur 
     * @return le mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur) {
        $req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés

     * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
     * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois) {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        $req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
        PdoGsb::$monPdo->exec($req);
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $uneLigneIdFrais) {
            $unIdFrais = $uneLigneIdFrais['idfrais'];
            $req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
            PdoGsb::$monPdo->exec($req);
        }
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @param $libelle : le libelle du frais
     * @param $date : la date du frais au format français jj//mm/aaaa
     * @param $montant : le montant
     */
    public function creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant) {
        $dateFr = dateFrancaisVersAnglais($date);
        $req = "insert into lignefraishorsforfait values('','$idVisiteur','$mois','$libelle','$dateFr','$montant','VAL')";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     * Supprime le frais hors forfait dont l'id est passé en argument

     * @param $idFrais 
     */
    public function supprimerFraisHorsForfait($idFrais) {
        $req = "delete from lignefraishorsforfait where lignefraishorsforfait.id =$idFrais ";
        PdoGsb::$monPdo->exec($req);
    }
    public function supprimerFraisHorsForfaitDuMois($mois) {
        $req = "delete from lignefraishorsforfait where lignefraishorsforfait.mois =$mois ";
        PdoGsb::$monPdo->exec($req);
    }

    public function refuserFraisHorsForfait($idFrais) {
        $req = "UPDATE lignefraishorsforfait set situation = 'REF' where lignefraishorsforfait.id =$idFrais ";
        PdoGsb::$monPdo->exec($req);
    }

    public function validerFraisHorsForfait($idFrais) {
        $req = "UPDATE lignefraishorsforfait set situation = 'VAL' where lignefraishorsforfait.id =$idFrais ";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     * change l'etat d'une fiche donné en RB
     * @param type $id
     * @param type $mois
     */
    public function rembourserFraisForfait($id, $mois) {
        $req = "UPDATE fichefrais set idEtat = 'RB' where idvisiteur ='$id' AND mois='$mois' ";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     * change l'etat d'une fiche en VA
     * @param type $id
     * @param type $leMois
     */
    public function mettreEnPaiement($id, $leMois) {
        $req = "UPDATE fichefrais set idEtat = 'VA' where idvisiteur ='$id' AND mois='$leMois' ";
        PdoGsb::$monPdo->exec($req);
    }

    /* voir les frais hors forfait qui sont refusés */

    public function voirFraisRefuse($idFrais) {
        $req = "SELECT situation FROM lignefraishorsforfait WHERE lignefraishorsforfait.id =$idFrais";
        $res = PdoGsb::$monPdo->query($req);
        $resu = $res->fetchAll();
        return $resu;
    }

    /**
     * retourne la puissance du véhicule choisit pour une fiche donnée
     * @param type $id
     * @param type $mois
     * @return type
     */
    public function getVehicule($id, $mois) {
        $req = "SELECT vehicule FROM fichefrais WHERE fichefrais.idVisiteur ='$id' AND mois ='$mois'";
        $res = PdoGsb::$monPdo->query($req);
        $vehicule = $res->fetchAll();
        $vehicule = $vehicule[0];
        return $vehicule;
    }

    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais

     * @param $idVisiteur 
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
     */
    public function getLesMoisDisponibles($idVisiteur) {
        $req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' 
		order by fichefrais.mois desc ";
        $res = PdoGsb::$monPdo->query($req);
        $lesMois = array();
        $laLigne = $res->fetch();
        while ($laLigne != null) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois["$mois"] = array(
                "mois" => "$mois",
                "numAnnee" => "$numAnnee",
                "numMois" => "$numMois"
            );
            $laLigne = $res->fetch();
        }
        return $lesMois;
    }

    /*     * ****************Methode pour voir les mois a valider (comptable)**************************** */

    public function getLesMoisAValider() {
        $req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idEtat ='CL'  
		order by fichefrais.mois desc ";
        $res = PdoGsb::$monPdo->query($req);
        $lesMois = array();
        $laLigne = $res->fetch();
        while ($laLigne != null) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois["$mois"] = array(
                "mois" => "$mois",
                "numAnnee" => "$numAnnee",
                "numMois" => "$numMois"
            );
            $laLigne = $res->fetch();
        }
        return $lesMois;
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné

     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
     */
    public function getLesInfosFicheFrais($idVisiteur, $mois) {
        $req = "select fichefrais.idEtat as idetat, fichefrais.dateModif as dateModif, fichefrais.nbJustificatifs as nbJustificatifs, 
			fichefrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join etat on fichefrais.idEtat = etat.id 
			where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        return $laLigne;
    }

    /**
     * Modifie l'état et la date de modification d'une fiche de frais

     * Modifie le champ idEtat et met la date de modif à aujourd'hui
     * @param $idVisiteur 
     * @param $mois sous la forme aaaamm
     */
    public function majEtatFicheFrais($idVisiteur, $mois, $etat) {
        $req = "update fichefrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
        PdoGsb::$monPdo->exec($req);
    }

    /*     * ********************partie comptable************************************ */

    public function majValeurFicheFrais($idVisiteur, $mois, $ETP, $KM, $NUI, $REP) {
        $req = "update lignefraisforfait set `quantite`  = '$ETP' 
                        where lignefraisforfait.idvisiteur ='$idVisiteur' 
                        and lignefraisforfait.mois = '$mois'
                        and `idFraisForfait`='ETP';
                        update lignefraisforfait set `quantite`  = '$KM' 
                        where lignefraisforfait.idvisiteur ='$idVisiteur' 
                        and lignefraisforfait.mois = '$mois'
                        and `idFraisForfait`='KM';
                        update lignefraisforfait set `quantite`  = '$NUI' 
                        where lignefraisforfait.idvisiteur ='$idVisiteur' 
                        and lignefraisforfait.mois = '$mois'
                        and `idFraisForfait`='NUI';
                        update lignefraisforfait set `quantite`  = '$REP' 
                        where lignefraisforfait.idvisiteur ='$idVisiteur' 
                        and lignefraisforfait.mois = '$mois'
                                    and `idFraisForfait`='REP';";
        PdoGsb::$monPdo->exec($req);
    }

    public function reporterFraisHorsForfait($idFrais) {
        $req = "select mois From lignefraishorsforfait where id =$idFrais";
        $res = PdoGsb::$monPdo->query($req);
        $laLigne = $res->fetch();
        $annee = $laLigne[0];
        $mois = substr("$annee", 4);
        $annee = substr("$annee", 0, -2);

        if ($mois < 12) {
            $mois = $mois + 1;
        } else {
            $mois = 01;
            $annee = $annee + 1;
        }
        $date = $annee . $mois;
        echo $date;

        // faire une requete update qui va modier la date de plusieurs element de la base par date
    }

    /**
     * retourne la liste des fiches qui sont vaidées
     * @return type
     */
    public function getFicheFraisSuivre() {
        $req = PdoGsb::$monPdo->prepare("select fichefrais.idVisiteur as v_id, fichefrais.mois as v_mois, fichefrais.montantValide as v_montant, fichefrais.idEtat, visiteur.nom as v_nom, visiteur.prenom as v_prenom
                   from fichefrais join visiteur on fichefrais.idVisiteur = visiteur.id
                   where idEtat = 'VA' order by mois desc");
        $req->execute();
        $fiche = $req->fetchAll();
        return $fiche;
    }

    /**
     * Retourne tout les nom,prenom et l'id dans la table visiteur
     * @return type
     */
    public function getNomPrenomIdVisiteur() {
        $req = "select visiteur.nom as nom, visiteur.prenom as prenom, visiteur.id as id from visiteur";
        $res = PdoGsb::$monPdo->query($req);
        //$nom = $rs->fetch();
        return $res;
    }

    /**
     * Retourne le nom le prenom du visiteur en fonction de sont identifiant
     * @param type $pid
     * @return type
     */
    public function getNomPrenomVisiteur($pid) {
        $req = "select visiteur.nom as nom, visiteur.prenom as prenom from visiteur where id ='$pid'";
        $res = PdoGsb::$monPdo->query($req);
        $res = $res->fetch();
        return $res;
    }

    /**
     * permet de mettre a jour montant reel des faire forfais
     * @param type $id
     * @param type $mois
     */
    public function montantValide($id, $mois) {
        $req = "select quantite  from lignefraisforfait WHERE idVisiteur ='$id' AND mois='$mois' AND idFraisForfait = 'ETP'";
        $etp = PdoGsb::$monPdo->query($req);
        $ETP = $etp->fetch();
        $etp = $ETP[0];
        /* kilometre */
        $req = "select quantite  from lignefraisforfait WHERE idVisiteur ='$id' AND mois='$mois' AND idFraisForfait = 'KM'";
        $km = PdoGsb::$monPdo->query($req);
        $KM = $km->fetch();
        $km = $KM[0];
        /* nuit */
        $req = "select quantite  from lignefraisforfait WHERE idVisiteur ='$id' AND mois='$mois' AND idFraisForfait = 'NUI'";
        $nui = PdoGsb::$monPdo->query($req);
        $NUI = $nui->fetch();
        $nui = $NUI[0];
        /* repass */
        $req = "select quantite  from lignefraisforfait WHERE idVisiteur ='$id' AND mois='$mois' AND idFraisForfait = 'REP'";
        $rep = PdoGsb::$monPdo->query($req);
        $REP = $rep->fetch();
        $rep = $REP[0];
        /* recuperation des montant des frais forfait */
        $req = "select montant  from fraisforfait WHERE id='ETP'";
        $m_etp = PdoGsb::$monPdo->query($req);
        $M_ETP = $m_etp->fetch();
        $m_etp = $M_ETP[0];
        /* montant kilometrique */
        $vehicule = $this->getVehicule($id, $mois);
        $req = "select montant  from fraisforfait WHERE id='$vehicule[0]'";
        $m_km = PdoGsb::$monPdo->query($req);
        $M_KM = $m_km->fetch();
        $m_km = $M_KM[0];
        /* montant nuit */
        $req = "select montant  from fraisforfait WHERE id='NUI'";
        $m_nui = PdoGsb::$monPdo->query($req);
        $M_NUI = $m_nui->fetch();
        $m_nui = $M_NUI[0];
        /* repas */
        $req = "select montant  from fraisforfait WHERE id='REP'";
        $m_rep = PdoGsb::$monPdo->query($req);
        $M_REP = $m_rep->fetch();
        $m_rep = $M_REP[0];

        $resul = ($km * $m_km) + ($rep * $m_rep) + ($nui * $m_nui) + ($etp * $m_etp);
        /* insersion de la vraie valeur dans fiche frais */
        $req = "update fichefrais set `montantValide`  = '$resul' 
                        where fichefrais.idvisiteur ='$id' 
                        and fichefrais.mois = '$mois'";
        PdoGsb::$monPdo->exec($req);
    }

    public function setVehicule($id, $mois, $choix) {
        $req = "update fichefrais set `vehicule`  = '$choix' where fichefrais.idvisiteur ='$id' and fichefrais.mois = '$mois'";
        PdoGsb::$monPdo->exec($req);
    }

    /**
     * Retourne le montant de l'indemnité en fonction de sa puissance
     * @param type $vehicule
     * @return type
     */
    public function getMontantVehicule($vehicule) {
        $req = "select montant  from fraisforfait WHERE id='$vehicule'";
        $montant = PdoGsb::$monPdo->query($req);
        $laLigne = $montant->fetchAll();
        return $laLigne;
    }
    	/*convert les coords en adress*/
function getAdresseFromCoordsGoole($lat, $lng){
	$content = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&sensor=false&');
	if(!$content){
		return false;
	}	
	$json = json_decode($content, true);
	if(!$json || $json['status'] != "OK"){
		return false;
		}	
	if(!isset($json['results'][0]['formatted_address']) && empty($json['results'][0]['formatted_address'])){
		return false; 
	}	
	return $json['results'][0]['formatted_address'];
}
function getAdresseFromCoordsCage($lat, $lng){
	$content = file_get_contents('https://api.opencagedata.com/geocode/v1/json?q='.$lat.','.$lng.'&pretty=1&key=f38499fd6cfae686e9eb7a179abc0a2a');
		
	$json = json_decode($content, true);	
        $ad = $json['results'][0]['formatted'];
	return $ad;
}

    /**
     * creer la page pdf
     * @param type $codePdf
     */
    public function creerPdf($codePdf) {
        require_once("html2pdf/vendor/autoload.php");
        $pdf = new HTML2PDF('P', 'A4', 'fr', 'true', 'UTF-8');
        $pdf->writeHTML($codePdf);
        ob_end_clean(); //supprime les espaces cachés
        $pdf->Output('pdf.pdf');
    }

}
?>