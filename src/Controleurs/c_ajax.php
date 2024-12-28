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
    $fonction = filter_input(INPUT_GET, 'fonction', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    switch ($fonction) {
        case 'ajaxGetLesMoisDisponibles' :
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            echo json_encode($pdo->getLesMoisDisponibles($idVisiteur));
            break;
        case 'ajaxPostEnvoyerPaiement' :
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $pdo->postEnvoyerPaiement($idVisiteur, $mois);
            break;
        case 'ajaxGetFichesFrais' :
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            echo json_encode($pdo->getLesFichesFrais($idVisiteur));
            break;
        case 'ajaxGetFraisForfaits' :
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            echo json_encode($pdo->getLesFraisForfait($idVisiteur, $mois));
            break;
        case 'ajaxGetFraisHorsForfait' :
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            echo json_encode($pdo -> getLesFraisHorsForfaitValidation($idVisiteur, $mois));
            break;
        case 'ajaxGetNbjustificatifs':
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            echo json_encode($pdo->getNbjustificatifs($idVisiteur, $mois));
            break;
        case 'ajaxMajFraisForfait':
            // FIXME: filter input
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $lesFrais = json_decode($_GET["lesFrais"], true);

            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
            break;
        case 'ajaxMajFraisHorsForfait':
            // FIXME: filter input
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
        case 'ajaxMajNbJustificaifs':
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $nbJustificatifs = filter_input(INPUT_GET, 'nbJustificatifs', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $pdo->majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs);
            break;
        case 'ajaxReporterFraisHorsForfait':
            $idFraisHorsForfait = filter_input(INPUT_GET, 'idFraisHorsForfait', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $idVisiteur = filter_input(INPUT_GET, 'idVisiteur', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $dernierMoisSaisi = $pdo->dernierMoisSaisi($idVisiteur);
            $dateActuelle = new DateTime();
            $prochainMois = $dateActuelle->modify('+1 month')->format('Ym');

            if ($prochainMois > $dernierMoisSaisi) {
                
            }

            break;
        default :
            throw new Exception($fonction . " ---> Fonction Ajax Inconnue");
    }
}







