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
            // FIXME: filter input
            $nom = filter_input(INPUT_GET, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $prenom = $_GET["prenom"];

            $idVisiteur = $pdo->getIdVisiteur($nom, $prenom);
            $lesMoisDisponibles = $pdo->getLesMoisDisponibles($idVisiteur);
            
            echo json_encode($lesMoisDisponibles);
            break;
        case "ajaxGetValuesInputsValidationFraisForfaits" :
            // FIXME: filter input
            $nom = filter_input(INPUT_GET, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $prenom = $_GET['prenom'];
            $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $idVisiteur = $pdo->getIdVisiteur($nom, $prenom);
            $valuesInputsValidationFrais = $pdo->getLesFraisForfait($idVisiteur, $mois);
            
            echo json_encode($valuesInputsValidationFrais);
            break;
        case "ajaxGetValuesInputsValidationFraisHorsForfait" :
            // FIXME: filter input
            $nom = filter_input(INPUT_GET, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $prenom = $_GET["prenom"];
            $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $idVisiteur = $pdo->getIdVisiteur($nom, $prenom);
            $valuesInputsValidationFraisHorsForfait = $pdo -> getLesFraisHorsForfaitValidation($idVisiteur, $mois);

            echo json_encode($valuesInputsValidationFraisHorsForfait);
            break;
        case 'ajaxMajFraisForfait':
            // FIXME: filter input
            $idVisiteur = $pdo->getIdVisiteur(filter_input(INPUT_GET, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS), 
            $_GET["prenom"]);
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
        default :
            throw new Exception($fonction . " ---> Fonction Ajax Inconnue");
    }
}







