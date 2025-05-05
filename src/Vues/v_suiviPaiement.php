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
                $idVisiteur = $unVisiteur['id'];
        ?>
            <option id="<?php echo $idVisiteur;?>" value="<?php echo $nomVisiteur; echo " " . $prenomVisiteur; ?>"><?php echo $nomVisiteur; echo " " . $prenomVisiteur; ?></option>
        <?php
            }
        ?>
    </select>
    <h2>
    Liste des fiches de frais
    </h2>
    <table id="tableFichesFrais">
        <thead>
            <tr>
                <th>Date</th>
                <th>Etat</th>
                <th>Action</th> 
            </tr>
        </thead>
        <tbody id="tableFichesFraisContent">
            <!-- Le contenu sera généré dynamiquement par JavaScript -->
        </tbody>
    </table>
</div>





