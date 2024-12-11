<?php

/**
 * Gestion de la connexion
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

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if (!$uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
    case 'demandeConnexion':
        include PATH_VIEWS . 'v_connexion.php';
        break;
    case 'valideConnexion':
        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $role = $pdo->estComptable($login);
        $code = rand(1000, 9999);

        if ($role) {
            $utilisateur = $pdo->getInfosComptable($login);
            $id = $utilisateur['id'];
            
            $pdo->setCodeA2fComptable($id, $code);
        } else {
            $utilisateur = $pdo->getInfosVisiteur($login);
            $id = $utilisateur['id'];

            $pdo->setCodeA2fVisiteur($id,$code);
        }

        $email = $utilisateur['email'];
        $nom = $utilisateur['nom'];
        $prenom = $utilisateur['prenom'];

        mail($email, '[GSB-AppliFrais] Code de vérification', "Code : $code");
        Utilitaires::connecter($id, $nom, $prenom, $role);

        include PATH_VIEWS . 'v_code2facteurs.php';
        break;
    case 'valideA2fConnexion':
        $code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_NUMBER_INT);

        if ($_SESSION['role']) {
            $codeEnBdd = $pdo->getCodeComptable($_SESSION['idUtilisateur']);
        } else {
            $codeEnBdd = $pdo->getCodeVisiteur($_SESSION['idUtilisateur']);
        }
            
        if ($codeEnBdd !== $code) {
            Utilitaires::ajouterErreur('Code de vérification incorrect');
            include PATH_VIEWS . 'v_erreurs.php';
            include PATH_VIEWS . 'v_code2facteurs.php';
        } else {
            Utilitaires::connecterA2f($code);
            header('Location: index.php');
        }
        break;
    default:
        include PATH_VIEWS . 'v_connexion.php';
        break;
}
