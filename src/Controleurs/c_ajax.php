<?php

/**
 * Gestion des frais
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


if (isset($_GET['fonction'])) {
    $fonction = $_GET['fonction'];
    
    if ($fonction == "ajaxGetLesMoisDisponibles") {
        getLesMoisDisponibles($pdo);
    }
}



function getLesMoisDisponibles($pdo) 
{
    $nom = $_GET["nom"];
    $prenom = $_GET["prenom"];

    $idVisiteur = $pdo->getIdVisiteur($nom, $prenom);
    $lesMoisDisponibles = $pdo->getLesMoisDisponibles($idVisiteur);
    
    var_dump($lesMoisDisponibles);

    return $lesMoisDisponibles;
}




