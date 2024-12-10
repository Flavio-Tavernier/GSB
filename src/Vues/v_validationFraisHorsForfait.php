<?php

/**
 * Vue Liste de validation des frais hors forfait
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    Flavio TAVERNIER <flavio.tavernier2@gmail.com>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */

?>
<hr>
<div class="row">
    <div class="panel panel-info">
        <div class="panel-heading">Descriptif des éléments hors forfait</div>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th class="date">Date</th>
                    <th class="libelle">Libellé</th>  
                    <th class="montant">Montant</th>  
                    <th class="action">&nbsp;</th> 
                </tr>
            </thead>  
            <tbody id="container-values-frais-hors-forfait">     

            </tbody>  
        </table>
    </div>

    <label for="inputNbJustificatifs">Nombre de justificatifs : </label>
    <input type="text" id="inputNbJustificatifs">

    <button id="btn-valider-fiche-frais" class="btn btn-success btn-valider" type="button">Valider</button>
    <button class="btn btn-danger btn-reinitialiser" type="button">Réinitialiser</button>
</div>


