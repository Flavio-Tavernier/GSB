<?php
/**
 * Gestion des appels de fonctions ajax
 *
 * PHP Version 8
 *
 * @category  PPE
 * @package   GSB
 * @author    Flavio TAVERNIER <flavio.tavernier2@gmail.com>
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

use Outils\Utilitaires;


/**
 * Si dans l'url, le paramètre 'fonction' est présent alors, 
 * recupère le nom de la fonction et execute le traitement voulu 
 * en passant par un 'switch case'. Celui-ci permet un ajout de futures
 * fonctions facilité
 */
if (isset($_GET['fonction'])) {
    $fonction = $_GET['fonction'];
    
    switch ($fonction) {
        case "ajaxGetLesMoisDisponibles" :
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $lesMoisDisponibles = $pdo->getLesMoisDisponibles($idVisiteur);
            echo json_encode($lesMoisDisponibles);
            break;
        case "ajaxGetFraisForfaits" :
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $valuesInputsValidationFrais = $pdo->getLesFraisForfait($idVisiteur, $mois);
            echo json_encode($valuesInputsValidationFrais);
            break;
        case "ajaxGetFraisHorsForfait" :
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $valuesInputsValidationFraisHorsForfait = $pdo -> getLesFraisHorsForfaitValidation($idVisiteur, $mois);
            echo json_encode($valuesInputsValidationFraisHorsForfait);
            break;
        case 'ajaxMajFraisForfait':
            // FIXME: filter input
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $lesFrais = json_decode($_GET["lesFrais"], true);

            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
            break;
        case 'ajaxMajFraisHorsForfait':
            $idFraisHorsForfait = filter_input(INPUT_GET, 'idFraisHorsForfait', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fraisHorsForfait = json_decode($_GET['lesFraisHorsForfait'], true);

            $pdo->majFraisHorsForfait($idFraisHorsForfait, $fraisHorsForfait);
            break;
        case 'ajaxRefuserFraisHorsForfait':
            $idFraisHorsForfait = filter_input(INPUT_GET, 'idFraisHorsForfait', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $pdo->refuserFraisHorsForfait($idFraisHorsForfait);
            break;
        case 'ajaxValiderFicheFrais':
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $pdo->majEtatFicheFrais($idVisiteur, $mois, "VA");
            break;
        default :
            throw new Exception($fonction . " ---> Fonction Ajax Inconnue");
    }
}







