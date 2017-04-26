<h3>Fiche de frais a valider du mois <?php  echo $numMois."-".$numAnnee ?> : 
    </h3>
 <!-- pour l'affichage -->
    <?php  $title = ["Idvisiteur","Nom", "Prenom"]; ?>
    <div class="encadre">
  	<table class="listeLegere">
  	   <caption>FICHE</caption>
        <tr>
         <?php
            for ($i=0;$i<3; $i++) { ?>	
                <th> <?php  echo $title[$i] ?></th>
	 <?php
                } 
          ?>
	</tr>
        <tr>
        <?php
            foreach ($aValider as list($a, $b, $c)) { ?>
                <td class="qteForfait"><a href="index.php?uc=fraisAValider&action=voirFraisAValider&valeur=<?php  echo $a ?>&date=<?php  echo $leMois ?>"><?php  echo $a ?></a></td>
                <td class="qteForfait"><?php  echo $b ?></td>
                <td class="qteForfait"><?php  echo $c ?></td>
         </tr>
	<?php
        } 
        ?>
    </table>
  </div>