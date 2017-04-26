    <!-- Division pour le sommaire -->
<div id="menuGauche">
    <div id="infosUtil">

        <h2></h2>

    </div>  
    <ul id="menuList">
        <li >
            <?php echo $_SESSION['type'] . "  " . " :" ?><br> <!--reprend le type de la personne connecté-->
            <?php echo $_SESSION['prenom'] . "  " . $_SESSION['nom'] ?>
        </li>
        <?php if ($_SESSION['type'] == "visiteur"): ?>
            <li class="smenu">
                <a href="index.php?uc=gererFrais&action=saisirFrais" title="Saisie fiche de frais ">Saisie fiche de frais</a>
            </li>
        <?php endif; ?>
        <li class="smenu">
            <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
        </li>
        <!-----------------affiche le tout que si c'est un comptable qui est connecté--------------------------------->
        <?php if ($_SESSION['type'] == "comptable"): ?>
            <li class="smenu">
                <a href="index.php?uc=fraisAValider&action=selectionnerMoisAValider" title="voir fiche de frais a valider">voir fiche de frais a valider</a> 
            <li class="smenu">
                <a href="index.php?uc=fraisAValider&action=suiviPaiement" title="suiviPaiement">Suivi paiement</a>
            <li>
            <?php endif; ?>
        </li>
        <!-------------------------------------------------->
        <li class="smenu">
            <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
        </li>
    </ul>       
</div>   