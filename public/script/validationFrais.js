document.addEventListener("DOMContentLoaded", function(event) {


    document.getElementById('lstVisiteur').addEventListener('change',function(){
        let nomPrenom = document.getElementById('lstVisiteur').value.split(" ");
        let nom = nomPrenom[0];
        let prenom = nomPrenom[1];

        ajaxGetLesMoisDisponibles(nom, prenom);
    });

});



function ajaxGetLesMoisDisponibles(nom, prenom) {
    var xhr=new XMLHttpRequest();
    xhr.open("POST","../../src/Controleurs/c_validationFrais.php?uc=validationFrais&ajax=true&fonction=ajaxGetLesMoisDisponibles&nom=" + nom + "&prenom=" + prenom, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log("../../src/Controleurs/c_validationFrais.php?uc=validationFrais&ajax=true&fonction=ajaxGetLesMoisDisponibles&nom=" + nom + "&prenom=" + prenom);
            
            console.log(xhr.responseText);
            document.getElementById("container").innerHTML=xhr.responseText;
        } else {
            console.error('Error:', xhr.statusText);
        }
    };

    
    xhr.send();
}





