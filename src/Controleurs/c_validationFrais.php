<script type="text/javascript" src="../../script/validationFrais.js"></script>

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

require PATH_VIEWS . 'v_validationFraisForfait.php';

var_dump("pas dans le if");
if (isset($_GET['nom'])) {
    var_dump("dans le if");
    function getLesMoisDisponibles($pdo) 
    {
        $nom = $_GET['nom'];
        $prenom = $_GET['prenom'];

        $idVisiteur = $pdo->getIdVisiteur($nom, $prenom);
        var_dump($idVisiteur);
    
        $moisDisponibles = $pdo->getLesMoisDisponibles($idVisiteur);
        var_dump($moisDisponibles);
    
    }
}




?>