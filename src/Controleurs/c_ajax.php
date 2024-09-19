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


if ($fonction == "ajaxGetLesMoisDisponibles") {
    $nom = $_GET["nom"];
    $prenom = $_GET["prenom"];

    $moisDispos = getLesMoisDisponibles($pdo, $nom, $prenom);
    var_dump($moisDispos);
 }



 function getLesMoisDisponibles($pdo, $nom, $prenom) 
{
    $idVisiteur = $pdo->getIdVisiteur($nom, $prenom);

    var_dump($idVisiteur);

    $moisDisponibles = $pdo->getLesMoisDisponibles($idVisiteur);

    var_dump($moisDisponibles);

}

?>