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
 * recupère le nom de la fonction et execute la voulue 
 * en passant par un 'switch case'. Celui-ci permet un ajout de futures
 * fonctions facilité
 */
if (isset($_GET['fonction'])) {
    $fonction = $_GET['fonction'];
    
    switch ($fonction) {
        case "ajaxGetLesMoisDisponibles" :
            getLesMoisDisponibles($pdo);
            break;
        case "ajaxGetValuesInputsValidationFrais" :
            getValuesInputsValidationFrais($pdo);
            break;
        default :
            throw new Exception($fonction . "Fonction Ajax Inconnue");
    }
}


/**
 * Récupère les paramètres de 'nom' et 'prenom' d'un visiteur dans l'url
 * et réalise un requête en BDD afin de récupérer l'id du visiteur 
 * et les mois pour lesquels le visiteur passé en paramètre possède des fiches de frais
 * 
 * Renvoi les données à travers un 'echo' car le return ne permet pas
 * de faire transiter les données vers le fichier javascript
 * 
 * @param PdoGSb $pdo Objet de la clase PdoGsb.php permettant la connexion à la BDD
 *
 * @return json 
 * 
 */
function getLesMoisDisponibles($pdo)
{
    // FIXME: filter input
    $nom = $_GET["nom"];
    $prenom = $_GET["prenom"];

    $idVisiteur = $pdo->getIdVisiteur($nom, $prenom);
    $lesMoisDisponibles = $pdo->getLesMoisDisponibles($idVisiteur);
    
    echo json_encode($lesMoisDisponibles);
}





function getValuesInputsValidationFrais($pdo)
{
    $nom = $_GET["nom"];
    $prenom = $_GET["prenom"];
    $mois = $_GET['mois'];

    $idVisiteur = $pdo->getIdVisiteur($nom, $prenom);
    $valuesInputsValidationFrais = $pdo->getLesFraisForfait($idVisiteur, $mois);
    
    echo json_encode($valuesInputsValidationFrais);
}