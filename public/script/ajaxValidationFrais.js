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
    function() {ajaxGetLesMoisDisponibles();});    

    document.getElementById('btn-corriger-frais-forfait').addEventListener('click', 
        function() {ajaxMajFraisForfait();});  
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
 *  Récupère la valeur actuelle de 'lstDatesFicheFrais'
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
 * @return json 
 * 
 */
function ajaxGetLesMoisDisponibles() 
{
    let nomPrenom = recupereNomPrenom();
    let prenom = nomPrenom[1];
    let nom = nomPrenom[0];

    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxGetLesMoisDisponibles&nom=" + nom + "&prenom=" + prenom, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById("lstDatesFicheFrais").innerHTML = "";
            ajoutElementLstDatesFicheFrais(JSON.parse(xhr.responseText));

            ajaxGetValuesInputsValidationFraisForfaits(recupereNomPrenom()[0], recupereNomPrenom()[1]);
            ajaxGetValuesInputsValidationFraisHorsForfait(recupereNomPrenom()[0], recupereNomPrenom()[1]);

            document.getElementById('lstDatesFicheFrais').addEventListener('change', 
            function() {ajaxGetValuesInputsValidationFraisForfaits(recupereNomPrenom()[0], recupereNomPrenom()[1]);});

            document.getElementById('lstDatesFicheFrais').addEventListener('change', 
            function() {ajaxGetValuesInputsValidationFraisHorsForfait(recupereNomPrenom()[0], recupereNomPrenom()[1]);});


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
 * @param array dateFicheFrais Tableau des différents mois/années
 *
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



/**
 * 
 * @param string nom nom d'un visiteur
 * @param string prenom prenom d'un visiteur
 *
 * @return json 
 * 
 */
function ajaxGetValuesInputsValidationFraisForfaits(nom, prenom) 
{
    let mois = recupereMois();
        
    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxGetValuesInputsValidationFraisForfaits&nom=" + nom + 
            "&prenom=" + prenom + 
            "&mois=" + mois, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            let valuesInputsValidationFraisForfaits = JSON.parse(xhr.responseText);
            injectValuesInputsValidationFraisForfaits(valuesInputsValidationFraisForfaits);
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send();
}


/**
 * Injecte les donner récupérées en BDD dans les 'iputs' de la vue
 * 'validationFrais' 
 * 
 * @param array valuesInputsValidationFraisForfaits Tableau des frais forfaits
 *
 * 
 */
function injectValuesInputsValidationFraisForfaits(valuesInputsValidationFraisForfaits)
{
    for (let i = 0;i < valuesInputsValidationFraisForfaits.length;i++) {
       document.getElementById("input" + valuesInputsValidationFraisForfaits[i][0]).value = valuesInputsValidationFraisForfaits[i][2];
    } 
}






/**
 * 
 * @param string nom nom d'un visiteur
 * @param string prenom prenom d'un visiteur
 *
 * @return json 
 * 
 */
function ajaxGetValuesInputsValidationFraisHorsForfait(nom, prenom) 
{
    let mois = recupereMois();
        
    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxGetValuesInputsValidationFraisHorsForfait&nom=" + nom + 
            "&prenom=" + prenom + 
            "&mois=" + mois, true);

    xhr.onload = function() {
        if (xhr.status === 200) {            
            let valuesInputsValidationFraisHorsForfait = JSON.parse(xhr.responseText);
            injectValuesInputsValidationFraisHorsForfait(valuesInputsValidationFraisHorsForfait);
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send();
}



/**
 * Injecte les donner récupérées en BDD dans les 'iputs' de la vue
 * 'validationFraisHorsForfait' 
 * 
 * @param array valuesInputsValidationFraisHorsForfait Tableau des frais hors forfait
 *
 */
function injectValuesInputsValidationFraisHorsForfait(valuesInputsValidationFraisHorsForfait)
{
    let nomPrenom = recupereNomPrenom();
    let prenom = nomPrenom[1];
    let nom = nomPrenom[0];


    document.getElementById("container-values-frais-hors-forfait").innerHTML = "";

    for (let i = 0;i < valuesInputsValidationFraisHorsForfait.length;i++) {
        document.getElementById("container-values-frais-hors-forfait").innerHTML += 
        "<tr><td><input value='" + valuesInputsValidationFraisHorsForfait[i]['date'] + "'/></td>" +
        "<td><input value='" + valuesInputsValidationFraisHorsForfait[i]['libelle'] + "'/></td>" +          
        "<td><input value='" + valuesInputsValidationFraisHorsForfait[i]['montant'] + "'/></td>" +                                         
        "<td><button class='btn btn-success btn-corriger' type='button'>Corriger</button>" + 
        "<button class='btn btn-danger btn-reinitialiser' type='button'>Réinitialiser</button></td></tr>";
    } 


    document.querySelectorAll('.btn-reinitialiser').forEach(unBtnReinitialser => {
        unBtnReinitialser.addEventListener('click', function() {
            ajaxGetValuesInputsValidationFraisForfaits(nom, prenom);
            ajaxGetValuesInputsValidationFraisHorsForfait(nom, prenom);
        });
    });
}



// TODO: A corriger/finir
function ajaxMajFraisForfait() {
    let mois = recupereMois();

    let nomPrenom = recupereNomPrenom();
    let prenom = nomPrenom[1];
    let nom = nomPrenom[0];

    let forfaitEtape = document.getElementById("inputETP").value;
    let fraisKilometrique = document.getElementById("inputKM").value;
    let nuiteeHotel = document.getElementById("inputNUI").value;
    let repasRestaurant = document.getElementById("inputREP").value;

    let objetDesFraisForfait = {"ETP" : forfaitEtape, "KM" : fraisKilometrique, "NUI" : nuiteeHotel, "REP" : repasRestaurant};

    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxMajFraisForfait&nom=" + nom + 
            "&prenom=" + prenom + 
            "&mois=" + mois +
            "&lesFrais=" + JSON.stringify(objetDesFraisForfait));

    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log(xhr.response);
            
            ajaxGetValuesInputsValidationFraisForfaits(nom, prenom)
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send();
}


