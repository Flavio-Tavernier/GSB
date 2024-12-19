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
    $tabFraisHorsForfait .= '
    <tr>
        <td colspan="1">
            '.$unFraisHorsForfait[4].'
        </td>
        <td colspan="2">
            '.$unFraisHorsForfait[3].'
        </td>
        <td colspan="1">
            '.$unFraisHorsForfait[5].'
        </td>
    </tr>
    ';

    $prixTotal += $unFraisHorsForfait[5];
 }


 $date = new DateTime();
 $dateActuelle = $date->format('d-m-Y');

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
$pdf->SetTitle('fiche_frais_' . $idVisiteur . "_" . $leMois);
$pdf->SetSubject('Fiche de frais');
$pdf->SetKeywords('fiche, frais');


// set font
$pdf->SetFont('helvetica', 'B', 20);

// add a page
$pdf->AddPage();

$pdf->Image('..\resources\images\gsb.jpg', 0, 0, 40, 40, '', '', 'T', false, 300, 'C', false, false, 0, false, false, false);


$pdf->SetFont('helvetica', '', 8);

// -----------------------------------------------------------------------------
$pdf->SetXY(15, 50);
$tbl = <<<EOF
<style>
    .texte-bleu {
        color: blue;
    }

    .sans-bordure {
        border-width: 0;
    }
</style>

<table cellspacing="0" cellpadding="10" border="1">
    <tr>
        <td align="center" class="texte-bleu">
            REMBOURSEMENT DE FRAIS ENGAGES
        </td>
    </tr>
    <tr>
        <td align="center">
            Visiteur : $idVisiteur
            <br>
            Mois : $numMois/$numAnnee
            <br>

            
            <table cellspacing="0" cellpadding="1" border="1">
                <tr>
                    <td align="center" class="texte-bleu sans-bordure">
                        Frais Forfaitaires
                    </td>   
                    <td align="center" class="texte-bleu sans-bordure">
                        Quantité
                    </td>
                    <td align="center" class="texte-bleu sans-bordure">
                        Montant unitaire
                    </td>
                    <td align="center" class="texte-bleu sans-bordure">
                        Total
                    </td>
                </tr>

                
                <table border="1">
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
                </table>

                

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
                    <td colspan="4" align="center" height="50" class="sans-bordure">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="4" align="center"class="texte-bleu sans-bordure">
                        Autres Frais
                    </td>
                </tr>

                <tr>
                    <td colspan="1" class="texte-bleu sans-bordure">
                        Date
                    </td>
                    <td colspan="2" class="texte-bleu sans-bordure">
                        Libellé
                    </td>
                    <td colspan="1" class="texte-bleu sans-bordure">
                        Montant
                    </td>
                </tr>
                $tabFraisHorsForfait  

                
                <tr>
                    <td colspan="4" align="center" height="10" class="sans-bordure">
                        
                    </td>
                </tr>
                <tr align="center">
                    <td colspan="2" class="sans-bordure">

                    </td>
                    <td colspan="1">
                        Total $numMois/$numAnnee
                    </td>
                    <td colspan="1">
                        $prixTotal €
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
EOF;

$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf->SetXY(0, 210);
$pdf->Write(0, 'Fait à Paris, le ' . $dateActuelle, '', 0, 'R', true, 0, false, false, 0);

$pdf->SetXY(0, 215);
$pdf->Write(0, 'Vu l\'agent comptable', '', 0, 'R', true, 0, false, false, 0);

$pdf->Image('..\resources\images\signatureComptable.png', 0, 230, 40, 40, '', '', 'B', false, 300, 'R', false, false, 0, false, false, false);
// -----------------------------------------------------------------------------
ob_end_clean();
//Close and output PDF document
$pdfData = $pdf->Output('fiche_frais_' . $idVisiteur . "_" . $leMois . '.pdf', 'S');

$pdo->insertPdf($idVisiteur, $leMois, $pdfData);



?>