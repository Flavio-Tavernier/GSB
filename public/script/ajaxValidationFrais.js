/**
 * Gestion des fonctions ajax du controleur 'c_validationFrais.php'
 *
 * @category  PPE
 * @package   GSB
 * @author    Flavio TAVERNIER <flavio.tavernier2@gmail.com>
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */


/**
 * Vérifie si la page est chargée
 * 
 */
document.addEventListener("DOMContentLoaded", function() {
    let nomPrenom = recupereNomPrenom();
    let prenom = nomPrenom[1];
    let nom = nomPrenom[0];
    
     ajaxGetLesMoisDisponibles(nom, prenom);
    document.getElementById('lstVisiteur').addEventListener('change', 
    function() {ajaxGetLesMoisDisponibles(nom, prenom);});
    
    
    
//    document.getElementById('lstDatesFicheFrais').addEventListener('change', 
//    ajaxGetValuesInputsValidationFrais(nom, prenom));
    
});


/**
 * Récupère les paramètres de 'nom' et 'prenom' d'un visiteur 
 * dans la balise 'option' de la balise 'select'.
 * 
 */
function recupereNomPrenom()
{
    // FIXME:le système de recup nom prenom n'est pas bon pour tous les cas d'utilisation
    return document.getElementById('lstVisiteur').value.split(" ");
}


/**
 * 
 */
function recupereMois()
{
    return document.getElementById('lstDatesFicheFrais').value;
}





/**
 * Fonction ajax qui récupère les mois pour lesquels 
 * un visiteur possède une fiche de frais
 * en fonction du nom et prenom passés en paramètres
 * 
 * @param string $nom nom d'un visiteur
 * @param string $prenom prenom d'un visiteur
 *
 * @return json 
 * 
 */
function ajaxGetLesMoisDisponibles(nom, prenom) 
{
    console.log("ça fonctionne");
    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxGetLesMoisDisponibles&nom=" + nom + "&prenom=" + prenom, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById("lstDatesFicheFrais").innerHTML = "";
            ajoutElementLstDatesFicheFrais(JSON.parse(xhr.responseText));

            
            ajaxGetValuesInputsValidationFrais(recupereNomPrenom()[0], recupereNomPrenom()[1]);
            document.getElementById('lstDatesFicheFrais').addEventListener('change', 
            function() {ajaxGetValuesInputsValidationFrais(recupereNomPrenom()[0], recupereNomPrenom()[1]);});
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send();
}



/**
 * 
 * @param string $nom nom d'un visiteur
 * @param string $prenom prenom d'un visiteur
 *
 * @return json 
 * 
 */
function ajaxGetValuesInputsValidationFrais(nom, prenom) 
{
    console.log("dans get values input");
    let mois = recupereMois();
    
    console.log(nom, prenom, mois);
    
    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxGetValuesInputsValidationFrais&nom=" + nom + 
            "&prenom=" + prenom + 
            "&mois=" + mois, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            let valuesInputsValidationFrais = JSON.parse(xhr.responseText);
            injectValuesInputsValidationFrais(valuesInputsValidationFrais);
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send();
}


/**
 * Grâce à une boucle 'for', parcours le tableau passé en paramètre et 
 * ajoute une balise 'option' par ligne de la BDD représentant 1 mois dans 1 année 
 * dans la balise 'select'.
 * 
 * @param array $dateFicheFrais Tableau des différents mois/années
 *
 * @return json 
 * 
 */
function ajoutElementLstDatesFicheFrais(datesFichesFrais) 
{
    for (let i = 0;i < datesFichesFrais.length;i++) {
        let mois = datesFichesFrais[i]['mois'];
        let numAnnee = datesFichesFrais[i]['numAnnee'];
        let numMois = datesFichesFrais[i]['numMois'];

        document.getElementById("lstDatesFicheFrais").innerHTML += "<option value='" + mois + "'>" + numMois + "/" + numAnnee + "</option>";
    }   
}


function injectValuesInputsValidationFrais(valuesInputsValidationFrais)
{
    console.log(valuesInputsValidationFrais);
    for (let i = 0;i < valuesInputsValidationFrais.length;i++) {
       document.getElementById("input" + valuesInputsValidationFrais[i][0]).value = valuesInputsValidationFrais[i][2];
    } 
    
    
    
}


