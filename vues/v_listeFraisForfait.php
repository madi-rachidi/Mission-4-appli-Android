<div id="contenu">
    <h2>Renseigner ma fiche de frais du mois <?php echo $numMois . "-" . $numAnnee ?></h2>
    <form method="POST"  action="index.php?uc=gererFrais&action=validerMajFraisForfait">
        <div class="corpsForm">

            <fieldset>
                <legend>Eléments forfaitisés </legend>
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = $unFrais['libelle'];
                    $quantite = $unFrais['quantite'];
                    ?>
                    <p>
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="text" id="idFrais" name="lesFrais[<?php echo $idFrais ?>]" size="10" maxlength="5" value="<?php echo $quantite ?>" >
                    </p>
                    <?php
                }
                ?>
                    <h2>Saisir la puissance du véhicule</h2><br />
                    • 4CV Diesel: <input type="radio" name="choix[]" value="4CVD"  <?php if($vehicule[0]=='4CVD') { echo 'checked="checked"';} ?>/><br />
                    • 5/6CV Diesel: <input type="radio" name="choix[]" value="5CVD" <?php if($vehicule[0]=='5CVD') { echo 'checked="checked"';} ?>/><br />
                    • 4CV Essence: <input type="radio" name="choix[]" value="4CVE" <?php if($vehicule[0]=='4CVE') { echo 'checked="checked"';} ?>/><br />
                    • 5/6CV Essence: <input type="radio" name="choix[]" value="5CVE" <?php if($vehicule[0]=='5CVE') { echo 'checked="checked"';} ?>/><br />
            </fieldset>
        </div>
        <div class="piedForm">
            <p>
                <input id="ok" type="submit" value="Valider" size="20" />
                <input id="annuler" type="reset" value="Effacer" size="20" />
            </p> 
        </div>
    </form>
