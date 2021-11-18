// Get the medication modal
let medicationsmodal = document.getElementById("medicationsModal");

// Get the medication tab that opens the modal
var medicationsTab = document.getElementById("medications");

// dynymic refresh the page
function refreshMed() {
  var medlist = document.getElementById('meds');
  var totalMed = document.getElementById('totalMed');

  medlist.innerHTML = ""

  fetch('request.php',{
    method:'post',
    body: JSON.stringify({
      "Type":"Medications",
      "Action":"ALL",
      "MedicationID":medID,
  })
    }).then(res=> res.json())
    .then(data =>{
      if (data != false) {
        data.forEach(element => {
          var selectChild = document.createElement('option')
          selectChild.setAttribute("value",element['id'])
          selectChild.innerText = element['MedicationName']
          medlist.appendChild(selectChild)
          totalMed.innerText = data.length;
        });
      }else{
        alert('Database internal error')

      }
    }

    );
}

refreshMed();


// When the user clicks the button, open the modal 
medicationsTab.onclick = function() {
    medicationsmodal.style.display = "block";

}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == medicationsmodal) {
    medicationsmodal.style.display = "none";
  }
  if (event.target == regimesmodal) {
    regimesmodal.style.display = "none";
  }
}

// add evenet listener to every medications
var currentMed = document.getElementById('currentMed');
var getMedButton = document.getElementById('getMedication');
var addMedButton = document.getElementById('addMedication');
var editMedButton = document.getElementById('editMed');
var deleteMedButton = document.getElementById('deleteMed');
var saveMedButton = document.getElementById('saveMed');
var cancelMedButton = document.getElementById('cancelMed');


// get editor panel
var medNameBox = document.getElementById('madicationsName');
var prescriptionBox = document.getElementById('medicationsPrescription');
var descriptionMed = document.getElementById('madicationsDescription');

// unlock the panel
function unlockMedPanel(params) {
  medNameBox.disabled = params
  prescriptionBox.disabled = params
  descriptionMed.disabled = params
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
  medID = currentMed.value
  var re = /^\d+$/;

  // test input format
  if (!re.test(medID)) {
    alert('Search field only taken number, however text can be searched and auto transfer to id')
  }else{
    fetch('request.php',{
      method:'post',
      body: JSON.stringify({
        "Type":"Medications",
        "Action":"Ask",
        "MedicationID":medID,
    })
      }).then(res=> res.json())
      .then(data =>{
        console.log(data)
        if (data != false) {
          medNameBox.value = data['MedicationName']
          prescriptionBox.checked = data['Presctiption']
          descriptionMed.value = data['Description']
          unlockMedPanelButtons(false);
          saveMedButton.disabled = true
          unlockMedPanel(true);
          currentMed.disabled = false

        }else{
          alert('Not exist')
  
        }
      }
  
    );
  }

}

// if added new 
addMedButton.onclick = function(event){
  unlockMedPanel(false);
  unlockMedPanelButtons(false);
  currentMed.value= ""
  medNameBox.value = ""
  prescriptionBox.checked = false
  descriptionMed.value = ""

  deleteMedButton.disabled = true
  editMedButton.disabled = true
  currentMed.disabled = true
  cancelMedButton.disabled = false
}

// if not edit
cancelMedButton.onclick = function(params) {
  currentMed.value= ""
  medNameBox.value = ""
  prescriptionBox.checked = false
  descriptionMed.value = ""
  unlockMedPanel(true);
  unlockMedPanelButtons(true);
  cancelMedButton.disabled = true
  currentMed.disabled = false
}

// edit give ability to edit current term
editMedButton.onclick = function(event) {

  unlockMedPanel(false);
  editMedButton.disabled = true
  saveMedButton.disabled = false
  currentMed.disabled = true
  cancelMedButton.disabled = false

}

// save current term
saveMedButton.onclick = function(event) {
  unlockMedPanel(true);
  medID = currentMed.value

  if (medNameBox.value.trim()!='') {
    fetch('request.php',{
      method:'post',
      body: JSON.stringify({
        "Type":"Medications",
        "Action":"Save",
        "MedicationID":medID,
        "MedicationName":medNameBox.value,
        "Prescription":prescriptionBox.checked,
        "Description":descriptionMed.value,
    })
      }).then(res=> res.text())
      .then(data =>{
        if (data != false) {
          refreshMed();
          alert("Saved")
          console.log(data)
        }else{
          alert("connection failed")
        }

      });
  
  }else{
    alert('Medication name can not be empty')
  }
  saveMedButton.disabled = true
  currentMed.disabled = false
}

// delete current term
deleteMedButton.onclick = function(event) {
  medNameBox.value = ""
  prescriptionBox.checked = false
  descriptionMed.value = ""
  medID = currentMed.value

  fetch('request.php',{
    method:'post',
    body: JSON.stringify({
      "Type":"Medications",
      "Action":"Delete",
      "MedicationID":medID,
  })
    }).then(res=> res.text())
    .then(data => {
      if (data != false) {
        refreshMed();
        alert("Deleted")

      }else{
        alert("connection failed")
      }
    });

  
  unlockMedPanel(true);
  unlockMedPanelButtons(true);
  currentMed.disabled = false
  cancelMedButton.disabled = true
}