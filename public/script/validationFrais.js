document.addEventListener("DOMContentLoaded", function(event) {
    // document.getElementById('lstVisiteur').addEventListener('change',function(){
        let nomPrenom = document.getElementById('lstVisiteur').value.split(" ");
        let nom = nomPrenom[0];
        let prenom = nomPrenom[1];
        ajaxGetLesMoisDisponibles(nom, prenom);       
    // });
});



function ajaxGetLesMoisDisponibles(nom, prenom) 
{
    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_ajax.php?uc=ajax&ajax=true&fonction=ajaxGetLesMoisDisponibles&nom=" + nom + "&prenom=" + prenom, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            ajoutElementLstDatesFicheFrais(JSON.parse(xhr.responseText));
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.send();
}


function ajoutElementLstDatesFicheFrais(datesFichesFrais) 
{
    for (let i = 0;i < datesFichesFrais.length;i++) {
        let mois = datesFichesFrais[i]['mois'];
        let numAnnee = datesFichesFrais[i]['numAnnee'];
        let numMois = datesFichesFrais[i]['numMois'];

        document.getElementById("lstDatesFicheFrais").innerHTML += "<option value='" + mois + "'>" + numMois + "/" + numAnnee + "</option>";
    }   
}



