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
    ajaxGetFichesFrais();

    document.getElementById('lstVisiteur').addEventListener('change', 
    function() {ajaxGetFichesFrais();});    
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
 * Fonction ajax qui récupère les mois pour lesquels 
 * un visiteur possède une fiche de frais
 * en fonction du nom et prenom passés en paramètres
 *
 * @return json 
 * 
 */
function ajaxGetFichesFrais() 
{
    let idVisiteur = recupereIdvisiteur();
    

    var xhr=new XMLHttpRequest();
    xhr.open("POST","../public/index.php?uc=ajax&fonction=ajaxGetFichesFrais&idVisiteur=" + idVisiteur, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            ajoutElementLstFicheFrais(JSON.parse(xhr.responseText), idVisiteur);
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send();
}


/**
 * Ajoute des lignes au tableau pour les fiches, avec des boutons pour les fiches "Validée".
 * @param {Array} datesFichesFrais - Tableau des fiches de frais.
 */
function ajoutElementLstFicheFrais(datesFichesFrais, idVisiteur) {
    const tableBody = document.getElementById("tableFichesFraisContent");

    tableBody.innerHTML = "";

    if (datesFichesFrais.length === 0) {
        const emptyRow = document.createElement("tr");
        const emptyCell = document.createElement("td");
        emptyCell.textContent = "Il n'y a aucune fiche à afficher";
        emptyCell.colSpan = 3;
        emptyCell.style.textAlign = "center";
        emptyRow.appendChild(emptyCell);
        tableBody.appendChild(emptyRow);
        return;
    }

    for (let fiche of datesFichesFrais) {
        if (fiche.etat === "Remboursée") {
            continue;
        }

        let row = document.createElement("tr");

        let dateCell = document.createElement("td");
        dateCell.textContent = `${fiche.numMois}/${fiche.numAnnee}`;
        row.appendChild(dateCell);

        let etatCell = document.createElement("td");
        etatCell.textContent = fiche.etat;
        row.appendChild(etatCell);

        let actionCell = document.createElement("td");

        if (fiche.etat === "Validée") {
            let buttonRemboursement = document.createElement("button");
            buttonRemboursement.textContent = "Envoyer au remboursement";
            buttonRemboursement.className = "btn-remboursement";
            buttonRemboursement.addEventListener("click", function () {
                envoyerAuRemboursement(fiche, idVisiteur);
            });
            actionCell.appendChild(buttonRemboursement);
        } else if (fiche.etat === "Saisie clôturée") {
            let buttonValidation = document.createElement("button");
            buttonValidation.textContent = "Envoyer à la validation";
            buttonValidation.className = "btn-validation";
            buttonValidation.addEventListener("click", function () {
                envoyerValidationFrais(fiche.mois, idVisiteur);
            });
            actionCell.appendChild(buttonValidation);
        }

        row.appendChild(actionCell);

        tableBody.appendChild(row);
    }
}


/**
 * Envoi d'une fiche au remboursement.
 * @param {Object} fiche - Objet représentant une fiche de frais.
 * @param {String} idVisiteur - L'id du visiteur concerné.
 */
function envoyerAuRemboursement(fiche, idVisiteur) {
    var xhr=new XMLHttpRequest();
    xhr.open("POST","../public/index.php?uc=ajax&fonction=ajaxPostEnvoyerPaiement&idVisiteur=" + idVisiteur + "&mois=" + fiche.mois, true);
    xhr.send();
    ajaxGetFichesFrais();
}

/**
 * Envoi une fiche en validation.
 * @param {int} mois - Mois de la fiche de frais.
 * @param {String} idVisiteur - L'id du visiteur concerné.
 */
function envoyerValidationFrais(mois, idVisiteur) {
    const url = "http://gsb/index.php?uc=validationFrais";

    const form = document.createElement("form");
    form.method = "post";
    form.action = url;

    // Champs POST
    const inputVisiteur = document.createElement("input");
    inputVisiteur.type = "hidden";
    inputVisiteur.name = "idVisiteur";
    inputVisiteur.value = idVisiteur;
    form.appendChild(inputVisiteur);

    const inputMois = document.createElement("input");
    inputMois.type = "hidden";
    inputMois.name = "mois";
    inputMois.value = mois;
    form.appendChild(inputMois);

    document.body.appendChild(form);
    form.submit();
}