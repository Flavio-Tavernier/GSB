<?php

/**
 * Vue validation des frais forfaits
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Flavio TAVERNIER <flavio.tavernier2@gmail.com>
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */

?>
<link href="/styles/validationFrais.css" rel="stylesheet">

<div class="row" id="container">     
    <label for="lstVisiteur">Choisir le visiteur :</label> 
    <select name="lstVisiteur" id="lstVisiteur">
        <?php 
            $visiteurs = $pdo->getVisiteurs();

            foreach($visiteurs as $unVisiteur) {
                $nomVisiteur = $unVisiteur['nom'];
                $prenomVisiteur = $unVisiteur['prenom'];
                $idVisiteur = $unVisiteur['id'];
        ?>
            <option id="<?php echo $idVisiteur;?>" value="<?php echo $nomVisiteur; echo " " . $prenomVisiteur; ?>">
                <?php echo $nomVisiteur; echo " " . $prenomVisiteur; ?></option>
        <?php
            }
            $idVisiteur = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mois = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (!empty($idVisiteur) && !empty($mois)) {
                ?>
                <script>
                    const idVisiteur = "<?php echo htmlspecialchars($idVisiteur); ?>";
                    const mois = "<?php echo htmlspecialchars($mois); ?>";
                    ajaxUtilisateurEtMoisAuto(idVisiteur, mois);
                </script>
                <?php
            }
        ?>
    </select> 

    <div id="testMoisDispos"></div>
    
    <label for="lstDatesFicheFrais">Mois :</label>
    <select name="lstDatesFicheFrais" id="lstDatesFicheFrais">
        <!-- Éléments ajoutés via fonction ajax -->
    </select> 
    

    <h2>
        Valider la fiche de frais 
    </h2>
    <h3>Eléments forfaitisés</h3>
    <div class="col-md-4">
        <form method="post" 
              action="index.php?uc=gererFrais&action=validerMajFraisForfait" 
              role="form">
            <fieldset>       
                <div id="container-inputs-validation-frais" class="champsVerticaux">
                    <label for="inputETP">Forfait Étape</label>
                    <input type="text" id="inputETP">
                    
                    <label for="inputKM">Frais Kilométrique</label>
                    <input type="text" id="inputKM">
                    
                    <label for="inputNUI">Nuitée Hôtel</label>
                    <input type="text" id="inputNUI">
                    
                    <label for="inputREP">Repas Restaurant</label>
                    <input type="text" id="inputREP">
               
                </div>
                
                <button id="btn-corriger-frais-forfait" class="btn btn-success btn-corriger" type="button">Corriger</button>
                <button class="btn btn-warning btn-reinitialiser" type="button">Réinitialiser</button>
            </fieldset>
        </form>
    </div> 
</div>






