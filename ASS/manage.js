// Get the modal
let medicationsmodal = document.getElementById("medicationsModal");

// Get the button that opens the modal
var btn = document.getElementById("medications");

// Get the <span> element that closes the modal
var medicationsClose = document.getElementById("medicationsClose");

// When the user clicks the button, open the modal 
btn.onclick = function() {
  
    medicationsmodal.style.display = "block";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == medicationsmodal) {
    medicationsmodal.style.display = "none";
  }
}