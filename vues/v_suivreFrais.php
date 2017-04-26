<form action="index.php?uc=fraisAValider&action=rembourser" method="post">

    <input type='hidden' name='in_user' value='<?php echo $visiteur; ?>' />
    <input type='hidden' name='in_mois' value='<?php echo $dateValide; ?>' />
    <br/>
    <?php
    $infos = $pdo->getNomPrenomVisiteur($visiteur);
    ?> <h2><?php echo "Fiche de : " . $infos['nom'] . " " . $infos["prenom"]; ?></h2>

    <br/>

    <table class="listeLegere">
        <caption>Eléments forfaitisés</caption>
        <tr>
            <th class="libelle"> Frais Forfaitaires</th>
            <th class='montant'>Quantite</th>
            <th class='montant'>Montant unitaire</th>
            <th class='montant'>Total</th>

        </tr>
        <?php
        $total = 0;
        foreach ($lesFraisForfait as $unFraisForfait) {
            $quantite = $unFraisForfait['quantite'];
            $libelle = $unFraisForfait['libelle'];
            if ($libelle == "Frais Kilométrique") {
                $montant = ($montant1[0]['montant']);
            } else {
                $montant = $unFraisForfait['montant'];
            }
            $id_ff = $unFraisForfait['idfrais'];
            $totalUnitaire = ($quantite * $montant);
            $total = $total + ($quantite * $montant);
            ?>
            <tr>
                <td><?php echo $libelle; ?></td>
                <td><?php echo $quantite; ?> </td>
                <td> <?php echo $montant; ?></td>
                <td> <?php echo $totalUnitaire; ?></td>        
            </tr>
            <?php
        }
        ?>
    </table>
       <br/>
    <br/>
 <table class="listeLegere">
        <caption>elements hors forfait</caption>
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>
            <th class='montant'>Montant</th>
        </tr>
        <?php
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $id_hf = $unFraisHorsForfait['id'];
            $date = $unFraisHorsForfait['date'];
            $libelle = $unFraisHorsForfait['libelle'];
            $montant = $unFraisHorsForfait['montant'];
            $total = $total + $montant;
            ?>
            <tr>
                <td>
                    <?php echo $date; ?>
                </td>
                <td>
                    <?php echo $libelle; ?>
                </td>
                <td>
                    <?php echo $montant; ?>
                </td>
                <?php
            }
            ?>
        <tr>
            <td> montant total : <?php echo $total; ?> </td>
        </tr>

        </tr>
    </table>
    <table class="listeLegere">
        <caption>NON VALIDER</caption>
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>
            <th class='montant'>Montant</th>
        </tr>

        <?php
        foreach ($lesFraisHorsForfaitREF as $unFraisHorsForfait) {

            $id_hf = $unFraisHorsForfait['id'];
            $date = $unFraisHorsForfait['date'];
            $libelle = $unFraisHorsForfait['libelle'];
            $montant = $unFraisHorsForfait['montant'];
            $total = $total + $montant;
            ?>
            <tr>

                <td>
                    <?php echo $date; ?>
                </td>
                <td>
                    <?php echo $libelle; ?>

                </td>
                <td>
                    <?php echo $montant; ?>
                </td>

                <?php
            }
            ?>
        <tr>
            <td> montant total : <?php echo $total; ?> </td>
        </tr>

        </tr>
    </table>

    <br/>
    <br/>

    <!-- Etat de la fiche -->

    <table class="listeLegere">
        <caption>Etat de la fiche de frais</caption>
        <tr>
            <th>Etat actuel</th>
        </tr>
        <tr>
            <td>
                <?php
                $etat_actuel = $lesInfosFicheFrais['libEtat'];

                echo $etat_actuel;
                ?>
                <input type='hidden' name='etat_defaut' value='<?php echo $etat_actuel; ?>' />
            </td>
        </tr>
    </table>

    <br/>
    <br/>

    <div class="encadre">
        <p>
            <br> Montant validé : <?php echo $montantValide ?>
        </p>


        <p class="titre"></p>
        <span>Nb justificatif : </span> <?php echo $nbJustificatifs ?>

        <br />
        <br />

        <input value="Rembourser" class="zone"type="submit" />

        <br />
        <br />
        <a href="index.php?uc=fraisAValider&action=pdf&id=<?php echo $visiteur; ?>&date=<?php echo $dateValide; ?>"><img id=supp src="images/pdf.png" alt="pdf" /> </a>

        <br />
        <br /> 

    </div>
</form>