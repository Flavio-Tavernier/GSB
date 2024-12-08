<?php

/**
 * Vue suivi du paiement
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Julian Cotten <cottenjulianregis@gmail.com>
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */

?>
<link href="../../public/styles/suiviPaiement.css" rel="stylesheet">

<div class="row" id="container">     
    <label for="lstVisiteur">Choisir le visiteur :</label> 
    <select name="lstVisiteur" id="lstVisiteur">
        <?php 
            $visiteurs = $pdo->getVisiteurs();

            foreach($visiteurs as $unVisiteur) {
                $nomVisiteur = $unVisiteur['nom'];
                $prenomVisiteur = $unVisiteur['prenom'];
        ?>
            <option value="<?php echo $nomVisiteur; echo " " . $prenomVisiteur; ?>"><?php echo $nomVisiteur; echo " " . $prenomVisiteur; ?></option>
        <?php
            }
        ?>
    </select>
    <h2>
        Liste des fiches de frais
    </h2>
<!--    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Etat</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fichesfrais as $fichefrais): ?>
                <tr>
                    <td><?= htmlspecialchars($fichefrais->date) ?></td>
                    <td><?= htmlspecialchars($fichefrais->etat) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>-->
</div>





