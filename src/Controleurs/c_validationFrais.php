<<<<<<< HEAD
<?php


use Outils\Utilitaires;

require PATH_VIEWS . "v_validationFraisForfait.php";
=======
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

if (!isset($_GET["ajax"])) {
    require PATH_VIEWS . 'v_validationFraisForfait.php';
}




?>
>>>>>>> 6951e72aed782af45c949e180a865f468620e9bf
