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


 require_once('../resources/Outils/tcpdf/tcpdf.php');

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
$pdf->Image('gsb.jpg', '', '', 40, 40, '', '', 'T', false, 300, '', false, false, 1, false, false, false);

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
            Visiteur
            <br>
            Mois
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
                        x
                    </td>
                    <td>
                        x
                    </td>
                    <td>
                        x
                    </td>
                </tr>

                <tr>
                    <td>
                        Repas Midi
                    </td>

                    <td>
                        x
                    </td>
                    <td>
                        x
                    </td>
                    <td>
                        x
                    </td>
                </tr>

                <tr>
                    <td>
                        Véhicule
                    </td>

                    <td>
                        x
                    </td>
                    <td>
                        x
                    </td>
                    <td>
                        x
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

                <tr>
                    <td>
                        Total 
                    </td>
                    <td>
                        x€
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf->Write(0, 'Fait à x, le x x xxxx', '', 0, 'L', true, 0, false, false, 0);
$pdf->Write(0, 'Vu l\'agent comptable', '', 0, 'L', true, 0, false, false, 0);


// -----------------------------------------------------------------------------
ob_end_clean();
//Close and output PDF document
$pdf->Output('example_048.pdf', 'I');

?>