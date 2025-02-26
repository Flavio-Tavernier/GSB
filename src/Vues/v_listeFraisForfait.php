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
    <h2>Renseigner ma fiche de frais du mois 
        <?php echo $numMois . '-' . $numAnnee ?>
    </h2>
    <h3>Eléments forfaitisés</h3>
    <div class="col-md-4">
        <form method="post" 
              action="index.php?uc=gererFrais&action=validerMajFraisForfait" 
              role="form">
            <fieldset>       
                <?php
                $typesVehicules = [
                    ['id' => 1, 'libelle' => '4CV Diesel'],
                    ['id' => 2, 'libelle' => '5/6CV Diesel'],
                    ['id' => 3, 'libelle' => '4CV Essence'],
                    ['id' => 4, 'libelle' => '5/6CV Essence']
                ];
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
                <div class="form-group">
                    <label for="typeVehicule">Type de véhicule</label>
                    <select id="typeVehicule" name="typeVehicule" class="form-control">
                        <?php foreach ($typesVehicules as $type) { ?>
                            <option value="<?php echo $type['id']; ?>"
                                <?php echo ($type['id'] == $typeVehiculeUtilisateur) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($type['libelle']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <button class="btn btn-success" type="submit">Ajouter</button>
                <button class="btn btn-danger" type="reset">Effacer</button>
            </fieldset>
        </form>
    </div>
</div>
