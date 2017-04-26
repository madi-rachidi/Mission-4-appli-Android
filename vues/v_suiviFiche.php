<div id="contenu">
    <h1> Suivi de paiement </h1>
    <form action="index.php?uc=fraisAValider&action=valideChoixFiche" method="post">
        <div class="corpsForm">
            <h3>Choisir une fiche : </h3>
            <p>
                <span style='color:green'>Fiche (état validé et mise en paiement):</span>
                <select id="lstVisiteur" style="width:480" name="lstVisiteur"> 
                    <?php
                    foreach ($listeFichesFrais as $data) {
                        $v_id = $data['v_id'];
                        $v_nom = $data['v_nom'];
                        $v_prenom = $data['v_prenom'];
                        $v_mois = $data['v_mois'];
                        $v_montant = $data['v_montant'];

                        //l'id de l'option est : mois + id
                        //ce format sera ensuite découpé par la suite.
                        //afin d'obtenir le mois et l'id séparément
                        ?> 
                        <option value="<?php echo $v_mois . $v_id; ?>"> <?php echo "Fiche du visiteur: " . $v_nom . " " . $v_prenom . " | Mois: " . $v_mois . " | Montant " . $v_montant . "€"; ?> </option>
                        <?php
                    }
                    ?>
                </select>
            </p>
            <p>
                <input id="ok" type="submit" value="Valider" size="20" />
            </p> 

        </div>
    </form>