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

 use Outils\Utilitaires;
 require_once('../resources/Outils/tcpdf/tcpdf.php');


 $leMois = filter_input(INPUT_GET, 'leMois', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
 $idVisiteur = $_SESSION['idUtilisateur'];

 $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
 $nbNuitee = $lesFraisForfait[2]['2'];
 $prixNuitee = $nbNuitee * 80;

 $nbRepasMidi = $lesFraisForfait[3]['2'];
 $prixRepasMidi = $nbRepasMidi * 25;

 $nbKm = $lesFraisForfait[1]['2'];
 $prixNbKm = $nbKm * 0.62;

 $prixTotal = $prixNuitee + $prixRepasMidi + $prixNbKm;


 $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
 $tabFraisHorsForfait = "";
 foreach ($lesFraisHorsForfait as $unFraisHorsForfait){
    $tabFraisHorsForfait .= "
    <tr>
        <td>
            $unFraisHorsForfait[4]
        </td>
        <td>
            $unFraisHorsForfait[3]
        </td>
        <td>
            $unFraisHorsForfait[5]
        </td>
    </tr>
    ";

    $prixTotal += $unFraisHorsForfait[5];
 }

 $date = new DateTime();
 $dateActuelle = $date->format('d M Y');

 $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
 $numAnnee = substr($leMois, 0, 4);
 $numMois = substr($leMois, 4, 2);
 $libEtat = $lesInfosFicheFrais['libEtat'];
 $montantValide = $lesInfosFicheFrais['montantValide'];
 $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
 $dateModif = Utilitaires::dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);



 $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GSB');
$pdf->SetTitle('fiche_frais_xx');
$pdf->SetSubject('Fiche de frais');
$pdf->SetKeywords('fiche, frais');

// set default header data
// $pdf->SetHeaderData('gsb.jpg', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);
$pdf->setJPEGQuality(75);
$pdf->SetXY(110, 200);
$pdf->Image('../../resources/images/gsb.jpg', '', '', 40, 40, '', '', 'T', false, 300, '', false, false, 1, false, false, false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 20);

// add a page
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 8);

// -----------------------------------------------------------------------------

$tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <td>
            REMBOURSEMENT DE FRAIS ENGAGES
        </td>
    </tr>
    <tr>
        <td>
            Visiteur : $idVisiteur
            <br>
            Mois : $numMois/$numAnnee
            <br>
            <table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <td>
                        Frais Forfaitaires
                    </td>
                    <td>
                        Quantité
                    </td>
                    <td>
                        Montant unitaire
                    </td>
                    <td>
                        Total
                    </td>
                </tr>

                <tr>
                    <td>
                        Nuitée
                    </td>

                    <td>
                        $nbNuitee
                    </td>
                    <td>
                        80.00
                    </td>
                    <td>
                        $prixNuitee
                    </td>
                </tr>

                <tr>
                    <td>
                        Repas Midi
                    </td>

                    <td>
                        $nbRepasMidi
                    </td>
                    <td>
                        25.00
                    </td>
                    <td>
                        $prixRepasMidi
                    </td>
                </tr>

                <tr>
                    <td>
                        Véhicule
                    </td>

                    <td>
                        $nbKm
                    </td>
                    <td>
                        0.62
                    </td>
                    <td>
                        $prixNbKm
                    </td>
                </tr>

                <tr>
                    <td >
                        Autres Frais
                    </td>
                </tr>

                <tr>
                    <td>
                        Date
                    </td>
                    <td>
                        Libellé
                    </td>
                    <td>
                        Montant
                    </td>
                </tr>
                $tabFraisHorsForfait  

                <tr>
                    <td>
                        Total $numMois/$numAnnee
                    </td>
                    <td>
                        $prixTotal €
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf->Write(0, 'Fait à Paris, le ' . $dateActuelle, '', 0, 'L', true, 0, false, false, 0);
$pdf->Write(0, 'Vu l\'agent comptable', '', 0, 'L', true, 0, false, false, 0);


// -----------------------------------------------------------------------------
ob_end_clean();
//Close and output PDF document
$pdf->Output('example_048.pdf', 'I');

?>