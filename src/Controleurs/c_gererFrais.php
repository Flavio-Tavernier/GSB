<?php

/**
 * Gestion des frais
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
 */

use Outils\Utilitaires;

$idVisiteur = $_SESSION['idUtilisateur'];
$mois = Utilitaires::getMois(date('d/m/Y'));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
switch ($action) {
    case 'saisirFrais':
        if ($pdo->estPremierFraisMois($idVisiteur, $mois)) {
            $pdo->creeNouvellesLignesFrais($idVisiteur, $mois);
        }
        $typeVehiculeUtilisateur = $pdo->getTypeVehiculeUtilisateur($idVisiteur);
        break;
    case 'validerMajFraisForfait':
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        $typeVehicule = filter_input(INPUT_POST, 'typeVehicule', FILTER_SANITIZE_NUMBER_INT);

        if (Utilitaires::lesQteFraisValides($lesFrais) && !empty($typeVehicule)) {
            $pdo->changerTypeVehicule($idVisiteur, $typeVehicule);
            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
        } else {
            Utilitaires::ajouterErreur('Les valeurs des frais doivent être numériques et un type de véhicule doit être sélectionné');
            include PATH_VIEWS . 'v_erreurs.php';
        }
        $typeVehiculeUtilisateur = $pdo->getTypeVehiculeUtilisateur($idVisiteur);
        break;
    case 'validerCreationFrais':
        $dateFrais = Utilitaires::dateAnglaisVersFrancais(
            filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        );
        $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
        Utilitaires::valideInfosFrais($dateFrais, $libelle, $montant);
        if (Utilitaires::nbErreurs() != 0) {
            include PATH_VIEWS . 'v_erreurs.php';
        } else {
            $date = Utilitaires::dateFrancaisVersAnglais($dateFrais);
            $pdo->creeNouveauFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant);
        }
        break;
    case 'supprimerFrais':
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pdo->supprimerFraisHorsForfait($idFrais);
        break;
}
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
require PATH_VIEWS . 'v_listeFraisForfait.php';
require PATH_VIEWS . 'v_listeFraisHorsForfait.php';
