document.addEventListener("DOMContentLoaded", function(event) {


    document.getElementById('lstVisiteur').addEventListener('change',function(){
        console.log("ca change");

        ajaxGetLesMoisDisponibles();
    });

});



function ajaxGetLesMoisDisponibles() {
    var xhr=new XMLHttpRequest();

    xhr.open("GET","c_validationFrais.php?nom=Ayot&prenom=Percy",true);

    // xmlhttp.onreadystatechange=function() {
    //     if (this.readyState==4 && this.status==200) {
    //         // document.getElementById("testMoisDispos").innerHTML=this.responseText;
    //         console.log(this.responseText);
            
    //     }
    // }

    xhr.onload = function() {
        if (xhr.status === 200) {
            // Success! Handle the response here
            console.log('Response:', xhr.responseText);
        } else {
            console.error('Error:', xhr.statusText);
        }
    };

    
    xhr.send();
}





