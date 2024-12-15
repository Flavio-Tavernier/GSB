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
    ajaxGetLesMoisDisponibles();

    document.getElementById('lstVisiteur').addEventListener('change', 
    function() {ajaxGetLesMoisDisponibles();});    

    document.getElementById('btn-corriger-frais-forfait').addEventListener('click', 
        function() {ajaxMajFraisForfait();});  

    document.getElementById('btn-valider-fiche-frais').addEventListener('click', 
        function() {ajaxValiderFichefrais(); ajaxMajNbJustificaifs();});
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
 * Récupère l'id d'un visiteur 
 * dans la balise 'option' elle même dans la balise 'select'.
 * 
 */
function recupereIdvisiteur()
{
    // FIXME:le système de recup nom prenom n'est pas bon pour tous les cas d'utilisation
    let lstVisiteur = document.getElementById('lstVisiteur');
    let idVisiteur = lstVisiteur.options[lstVisiteur.selectedIndex].id; 

    return idVisiteur;
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
    let idVisiteur = recupereIdvisiteur();
    

    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxGetLesMoisDisponibles&idVisiteur=" + idVisiteur, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById("lstDatesFicheFrais").innerHTML = "";
            ajoutElementLstDatesFicheFrais(JSON.parse(xhr.responseText));

            ajaxGetFraisForfaits();
            ajaxGetFraisHorsForfait();
            ajaxGetNbjustificatifs();

            document.getElementById('lstDatesFicheFrais').addEventListener('change', 
            function() {ajaxGetFraisForfaits(); ajaxGetFraisHorsForfait(); ajaxGetNbjustificatifs();});

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
 * fonction ajax qui récupère nom/prénom d'un visiteur
 * en paramètre afin de récupérer les valeurs des frais forfaits
 * 
 * @param string nom nom d'un visiteur
 * @param string prenom prenom d'un visiteur
 *
 * @return json 
 * 
 */
function ajaxGetFraisForfaits() 
{
    let idVisiteur = recupereIdvisiteur();
    let mois = recupereMois();
        
    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxGetFraisForfaits&idVisiteur=" + idVisiteur + 
            "&mois=" + mois, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            let valuesInputsFraisForfaits = JSON.parse(xhr.responseText);
            injectValuesInputsFraisForfaits(valuesInputsFraisForfaits);
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
 * @param array valuesInputsFraisForfaits Tableau des frais forfaits
 *
 * 
 */
function injectValuesInputsFraisForfaits(valuesInputsFraisForfaits)
{
    for (let i = 0;i < valuesInputsFraisForfaits.length;i++) {
       document.getElementById("input" + valuesInputsFraisForfaits[i][0]).value = valuesInputsFraisForfaits[i][2];
    } 
}






/**
 * fonction ajax qui récupère nom/prénom d'un visiteur
 * en paramètre afin de récupérer les valeurs des frais hors forfait
 * @param string nom nom d'un visiteur
 * @param string prenom prenom d'un visiteur
 *
 * @return json 
 * 
 */
function ajaxGetFraisHorsForfait() 
{
    let idVisiteur = recupereIdvisiteur();
    let mois = recupereMois();
        
    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxGetFraisHorsForfait&idVisiteur=" + idVisiteur + 
            "&mois=" + mois, true);

    xhr.onload = function() {
        if (xhr.status === 200) {             
            let valuesInputsFraisHorsForfait = JSON.parse(xhr.responseText);            
            injectValuesInputsFraisHorsForfait(valuesInputsFraisHorsForfait);
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
 * @param array valuesInputsFraisHorsForfait Tableau des frais hors forfait
 *
 */
function injectValuesInputsFraisHorsForfait(valuesInputsFraisHorsForfait)
{
    document.getElementById("container-values-frais-hors-forfait").innerHTML = "";

    for (let i = 0;i < valuesInputsFraisHorsForfait.length;i++) {
        let id = valuesInputsFraisHorsForfait[i]['id'];
        let date = valuesInputsFraisHorsForfait[i]['date'];
        let libelle = valuesInputsFraisHorsForfait[i]['libelle'];
        let montant = valuesInputsFraisHorsForfait[i]['montant'];

        document.getElementById("container-values-frais-hors-forfait").innerHTML += 
        "<tr id='" + id  + "'>" +
        "<td><input value='" + date + "' type='date'/></td>" +
        "<td><input value='" + libelle + "'/></td>" +          
        "<td><input value='" + montant + "'/></td>" +                                         
        "<td>" +
        "<button class='btn btn-success btn-corriger btn-corriger-frais-hors-forfait' type='button'>Corriger</button>" + 
        "<button class='btn btn-warning btn-reinitialiser' type='button'>Réinitialiser</button>" +
        "<button class='btn btn-danger btn-refuser-frais-hors-forfait' type='button'>Refuser</button>" +
        "<button class='btn btn-info btn-reporter-frais-hors-forfait' type='button'>Reporter</button>" +
        "</td>" +
        "</tr>";
    } 


    document.querySelectorAll('.btn-reinitialiser').forEach(unBtnReinitialser => {
        unBtnReinitialser.addEventListener('click', function() {
            ajaxGetFraisForfaits();
            ajaxGetFraisHorsForfait();
            ajaxGetNbjustificatifs();
        });
    });

    document.querySelectorAll('.btn-corriger-frais-hors-forfait').forEach(unBtnReinitialser => {
        unBtnReinitialser.addEventListener('click', function(element) {
            ajaxMajFraisHorsForfait(element.target.parentNode.parentNode.id);
        });
    });

    document.querySelectorAll('.btn-refuser-frais-hors-forfait').forEach(unBtnReinitialser => {
        unBtnReinitialser.addEventListener('click', function(element) {
            ajaxRefuserFraisHorsForfait(element.target.parentNode.parentNode.id);
        });
    });

    document.querySelectorAll('.btn-reporter-frais-hors-forfait').forEach(unBtnReinitialser => {
        unBtnReinitialser.addEventListener('click', function(element) {
            ajaxReporterFraisHorsForfait(element.target.parentNode.parentNode.id);
        });
    });

}


/**
 * Fonction ajax qui récupère l'id 
 * d'un visiteur et le mois d'une fiche de frais 
 * pour récupérer le nombre de justificatifs
 * 
 */
function ajaxGetNbjustificatifs() {
    let idVisiteur = recupereIdvisiteur();
    let mois = recupereMois();
    
    
    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxGetNbjustificatifs&idVisiteur=" + idVisiteur + 
            "&mois=" + mois);

    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById("inputNbJustificatifs").value = JSON.parse(xhr.response);
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send();
}

/**
 * Fonction ajax qui récupère l'id d'un visiteur, 
 * le mois et le nombre de justificatifs
 * afin de mettre à jour le nombre de justificatifs
 *
 * 
 */
function ajaxMajNbJustificaifs() 
{
    let idVisiteur = recupereIdvisiteur();
    let mois = recupereMois();
    let nbJustificatifs = document.getElementById("inputNbJustificatifs").value;

    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxMajNbJustificaifs&idVisiteur=" + idVisiteur + 
        "&mois=" + mois +
        "&nbJustificatifs=" + nbJustificatifs);

    xhr.onload = function() {
        if (xhr.status === 200) {
            // console.log(xhr.response);
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send(); 
}





/**
 * Fonction ajax qui récupère le nom/prénom et les frais forfait 
 * d'un visiteur pour mettre à jour les informations en BDD
 *
 * 
 */
function ajaxMajFraisForfait() {
    let mois = recupereMois();

    let idVisiteur = recupereIdvisiteur();

    let forfaitEtape = document.getElementById("inputETP").value;
    let fraisKilometrique = document.getElementById("inputKM").value;
    let nuiteeHotel = document.getElementById("inputNUI").value;
    let repasRestaurant = document.getElementById("inputREP").value;

    let objetDesFraisForfait = {"ETP" : forfaitEtape, "KM" : fraisKilometrique, "NUI" : nuiteeHotel, "REP" : repasRestaurant};

    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxMajFraisForfait&idVisiteur=" + idVisiteur + 
            "&mois=" + mois +
            "&lesFrais=" + JSON.stringify(objetDesFraisForfait));

    xhr.onload = function() {
        if (xhr.status === 200) {
            // console.log(xhr.response);
            
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send();
}


/**
 * Fonction ajax qui récupère le nom/prénom et les frais hors forfait 
 * d'un visiteur pour mettre à jour les informations en BDD
 *
 * 
 */
function ajaxMajFraisHorsForfait(idFraisHorsForfait) {
    let ligneFraisHorsForfait = window.document.getElementById(idFraisHorsForfait).children;
    let objetFraisHorsForfait = {"date" : "", "libelle" : "", "montant" : ""};

    let i = 0;
    for (let [key] of Object.entries(objetFraisHorsForfait)) {
        objetFraisHorsForfait[key] = ligneFraisHorsForfait[i].children.item(0).value;
        i++;
    }
    
    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxMajFraisHorsForfait&idFraisHorsForfait=" + idFraisHorsForfait +
            "&lesFraisHorsForfait=" + JSON.stringify(objetFraisHorsForfait));

    xhr.onload = function() {
        if (xhr.status === 200) {
            // console.log(xhr.response);
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send();
}


/**
 * Fonction ajax qui récupère l'id d'un frais hors forfait d'un visiteur 
 * en paramètre pour mettre à jour les informations en BDD
 *
 * @param array idFraisHorsForfait Id d'un frais hors forfait
 * 
 */
function ajaxRefuserFraisHorsForfait(idFraisHorsForfait)
{
    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxRefuserFraisHorsForfait&idFraisHorsForfait=" + idFraisHorsForfait);

    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById(idFraisHorsForfait).remove();
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send();
}



/**
 * Fonction ajax qui récupère l'id d'un frais hors forfait d'un visiteur 
 * et le mois de fiche de frais pour la valider
 *
 * 
 */
function ajaxValiderFichefrais() 
{
    let idVisiteur = recupereIdvisiteur();
    let mois = recupereMois();

    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxValiderFicheFrais&idVisiteur=" + idVisiteur + "&mois=" + mois);

    xhr.onload = function() {
        if (xhr.status === 200) {
            // console.log(xhr.response);
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send(); 
}




function ajaxReporterFraisHorsForfait(idFraisHorsForfait)
{  
    let idVisiteur = recupereIdvisiteur();

    let ligneFraisHorsForfait = window.document.getElementById(idFraisHorsForfait).children;
    let objetFraisHorsForfait = {"date" : "", "libelle" : "", "montant" : ""};

    let i = 0;
    for (let [key] of Object.entries(objetFraisHorsForfait)) {
        objetFraisHorsForfait[key] = ligneFraisHorsForfait[i].children.item(0).value;
        i++;
    }

    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&fonction=ajaxReporterFraisHorsForfait&idVisiteur=" + idVisiteur + 
        "&idFraisHorsForfait=" + idFraisHorsForfait +
        "&lesFraisHorsForfait=" + JSON.stringify(objetFraisHorsForfait));

    xhr.onload = function() {
        if (xhr.status === 200) {
            // console.log(xhr.response)
            ajaxGetFraisHorsForfait();
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send();
}