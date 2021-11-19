// get current patient data
let patient = document.getElementById("currentP");
let getPatientbutton = document.getElementById("getPatient");

getPatientbutton.onclick = function(event) {
    console.log(patient.value)
}


let modal = document.getElementsByClassName("modal");

// Get the tab that opens the modal
var tab = document.getElementsByClassName("grid-item-main");


for (let index = 0; index < 7; index++) {
    // When the user clicks the button, open the modal 
    tab[index].onclick = function() {
        modal[index].style.display = "block";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        // console.log(event.target)

        if (event.target == modal[0] || event.target == modal[1]||event.target == modal[2]||event.target == modal[3]||
            event.target == modal[4]||event.target == modal[5]||event.target == modal[6]) {
            modal[0].style.display = "none";
            modal[1].style.display = "none";
            modal[2].style.display = "none";
            modal[3].style.display = "none";
            modal[4].style.display = "none";
            modal[5].style.display = "none";
            modal[6].style.display = "none";

        }
    }
}
