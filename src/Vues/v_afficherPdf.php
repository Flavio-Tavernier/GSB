<?php

/**
 * Vue État de Frais
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
 * @link      https://getbootstrap.com/docs/3.3/ Documentation Bootstrap v3
 */




 $leMois = filter_input(INPUT_GET, 'leMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
 $idVisiteur = $_SESSION['idUtilisateur'];


 $pdfDeLaBase = $pdo->getPdf($idVisiteur, $leMois);

 $nomPdf = $pdfDeLaBase['nompdf'] . ".pdf";
 $donneesPdf = $pdfDeLaBase['donneespdf'];

 header('Content-Type: application/pdf');
 header('Content-Disposition: filename="' . $nomPdf . '"');
 echo $donneesPdf;
?>