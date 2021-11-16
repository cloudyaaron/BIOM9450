// Get the medication modal
let medicationsmodal = document.getElementById("medicationsModal");

// Get the medication tab that opens the modal
var medicationsTab = document.getElementById("medications");

// When the user clicks the button, open the modal 
medicationsTab.onclick = function() {
    medicationsmodal.style.display = "block";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == medicationsmodal) {
    medicationsmodal.style.display = "none";
  }
}

// add evenet listener to every medications
var currentMed = document.getElementById('currentMed');
var getMedButton = document.getElementById('getMedication');
var addMedButton = document.getElementById('addMedication');
var editMedButton = document.getElementById('editMed');
var deleteMedButton = document.getElementById('deleteMed');
var saveMedButton = document.getElementById('saveMed');

// get editor panel
var medNameBox = document.getElementById('madicationsName');
var prescriptionBox = document.getElementById('medicationsPrescription');
var descriptionText = document.getElementById('madicationsDescription');

// unlock the panel
function unlockMedPanel(params) {
  medNameBox.disabled = params
  prescriptionBox.disabled = params
  descriptionText.disabled = params
}

// unlock the button
function unlockMedPanelButtons(params) {
  editMedButton.disabled = params
  deleteMedButton.disabled = params
  saveMedButton.disabled = params

}

var medID = ""


// get detail of meds to editor panel
getMedButton.onclick = function(event) {
  let exist = false
  let index = 0
  for(let element of meds){
    if (element['MedicationName'] === currentMed.value) {
      exist = true
      console.log(index)
      break;
    }
    index = index + 1
  };

  // change editor panel
  if (exist) {
    unlockMedPanelButtons(false);
    saveMedButton.disabled = true

    medNameBox.value = meds[index]['MedicationName']
    if (meds[index]['Prescription']=='0') {
      prescriptionBox.checked = false
    }else{
      prescriptionBox.checked = true
    }
    descriptionText.value = meds[index]['Description']
  }else{
    alert("Input term is not exist in current database")
    medNameBox.value = ""
    prescriptionBox.checked = false
    descriptionText.value = ""
    exist = false
  }
}

addMedButton.onclick = function(event){
  unlockMedPanel(false);
  unlockMedPanelButtons(false);
  medNameBox.value = ""
  prescriptionBox.checked = false
  descriptionText.value = ""

  deleteMedButton.disabled = true
  editMedButton.disabled = true

}

// edit give ability to edit current term
editMedButton.onclick = function(event) {
  console.log(meds)
  unlockMedPanel(false);
  editMedButton.disabled = true
  saveMedButton.disabled = false

}

// save current term
saveMedButton.onclick = function(event) {
  unlockMedPanel(true);

  if (medNameBox.value.trim()!='') {
    fetch('request.php',{
      method:'post',
      body: JSON.stringify({
        "Type":"Medications",
        "Action":"Save",
        "MedicationID":medID,
        "MedicationName":medNameBox.value,
        "Prescription":prescriptionBox.checked,
        "Description":descriptionText.value,
    })
      }).then(res=> res.text())
      .then(resp =>
      console.log(resp)
      );
  
  }else{
    alert('Medication name can not be empty')
  }
  saveMedButton.disabled = true

}

// delete current term
deleteMedButton.onclick = function(event) {
  medNameBox.value = ""
  prescriptionBox.checked = false
  descriptionText.value = ""

  fetch('request.php',{
    method:'post',
    body: JSON.stringify({
      "Type":"Medications",
      "Action":"Delete",
      "MedicationID":medID,
  })
    }).then(res=> res.text())
    .then(resp =>
    console.log(resp)
    );

  unlockMedPanel(true);
  unlockMedPanelButtons(true);

}