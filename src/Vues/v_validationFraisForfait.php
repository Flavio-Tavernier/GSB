<?php

/**
 * Vue Liste des frais au forfait
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */

?>
<div class="row">     
    <label for="lstVisiteur">Choisir le visiteur :</label> 
    <select name="lstVisiteur" id="lstVisiteur">
        <?php 
            $visiteurs = $pdo->getVisiteurs();
            
            $idVisiteur = $visiteurs[0]["idVisiteur"];
            $lesMoisDisponibles = $pdo->getLesMoisDisponibles($idVisiteur);

            foreach($visiteurs as $unVisiteur) {
                $nomVisiteur = $unVisiteur['nom'];
                $prenomVisiteur = $unVisiteur['prenom'];
        ?>
            <option value="<?php echo $nomVisiteur; echo " " . $prenomVisiteur; ?>"><?php echo $nomVisiteur; echo " " . $prenomVisiteur; ?></option>
        <?php
            }
        ?>
    </select> 
    
    <label for="lstDateFicheFrais">Mois :</label>
    <select name="lstDateFicheFrais" id="lstDateFicheFrais">
    <?php 
            
            $lesMoisDisponibles = $pdo->getLesMoisDisponibles($idVisiteur);

            foreach($lesMoisDisponibles as $unMoisDisponible) {
                var_dump($unMoisDisponible);
        ?>
            <option value="<?php echo $unMoisDisponible['mois'] ?>">
                <?php echo $unMoisDisponible['numMois']; echo "/" . $unMoisDisponible['numAnnee'] ?></option>
        <?php
            }
        ?>
    </select> 
    
    
    
    
    <!-- <h2>
        Valider la fiche de frais 
    </h2>
    <h3>Eléments forfaitisés</h3>
    <div class="col-md-4">
        <form method="post" 
              action="index.php?uc=gererFrais&action=validerMajFraisForfait" 
              role="form">
            <fieldset>       
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite']; ?>
                    <div class="form-group">
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="text" id="idFrais" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control">
                    </div>
                    <?php
                }
                ?>
                <button class="btn btn-success" type="submit">Ajouter</button>
                <button class="btn btn-danger" type="reset">Effacer</button>
            </fieldset>
        </form>
    </div> -->
</div>
