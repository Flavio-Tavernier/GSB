document.addEventListener("DOMContentLoaded", function(event) {


    document.getElementById('lstVisiteur').addEventListener('change',function(){
        console.log("ca change");

        ajaxGetLesMoisDisponibles();
    });

});



function ajaxGetLesMoisDisponibles() {
    var xmlhttp=new XMLHttpRequest();

    xmlhttp.open("GET","c_ajax.php?fonction=ajaxGetLesMoisDisponibles&nom=Ayot&nom=Percy",true);

    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            // document.getElementById("testMoisDispos").innerHTML=this.responseText;
            console.log(this.responseText);
            
        }
    }

    
    xmlhttp.send();
}





