// Get the patication modal
let patientsmodal = document.getElementById("patientModal");

// Get the patication tab that opens the modal
var patientsTab = document.getElementById("Patient");

// dynymic refresh the page
function refreshPat() {
  var patlist = document.getElementById('pats');
  var totalPat = document.getElementById('totalPat');

  patlist.innerHTML = ""

  fetch('request.php',{
    method:'post',
    body: JSON.stringify({
      "Type":"Patients",
      "Action":"ALL",
  })
    }).then(res=> res.json())
    .then(data =>{
      if (data != false) {
        data.forEach(element => {
          var selectChild = document.createElement('option')
          selectChild.setAttribute("value",element['id'])
          selectChild.innerText = element['FirstName']+" "+element['LastName']
          patlist.appendChild(selectChild)
          totalPat.innerText = data.length;
        });
      }else{
        alert('Database internal error')

      }
    }

    );
}

refreshPat();


// When the user clicks the button, open the modal 
patientsTab.onclick = function() {
    patientsmodal.style.display = "block";

}

// add evenet listener to every patients
var currentPat = document.getElementById('currentPat');
var getPatButton = document.getElementById('getPatient');
var addPatButton = document.getElementById('addPatient');
var editPatButton = document.getElementById('editPat');
var deletePatButton = document.getElementById('deletePat');
var savePatButton = document.getElementById('savePat');
var cancelPatButton = document.getElementById('cancelPat');


// get editor panel
var firstNameBox = document.getElementById('firstName');
var lastNameBox = document.getElementById('lastName');
var descriptionPat = document.getElementById('PatientDescription');
var AgeBox = document.getElementById('Age');
var GenderBox = document.getElementById('Gender');
var patientPhoto = document.getElementById('pImage');

// unlock the panel
function unlockPatPanel(params) {
  firstNameBox.disabled = params
  lastNameBox.disabled = params
  descriptionPat.disabled = params
  AgeBox.disabled = params
  GenderBox.disabled = params
  if (params == true) {
    patientPhoto.setAttribute('src','ServiceUNSW.png')
  }

}

// unlock the button
function unlockPatPanelButtons(params) {
  editPatButton.disabled = params
  deletePatButton.disabled = params
  savePatButton.disabled = params

}

var patID = ""




// get detail of pats to editor panel
getPatButton.onclick = function(event) {
  patID = currentPat.value
  var re = /^\d+$/;

  // test input format
  if (!re.test(patID)) {
    alert('Search field only taken number, however text can be searched and auto transfer to id')
  }else{
    fetch('request.php',{
      method:'post',
      body: JSON.stringify({
        "Type":"Patients",
        "Action":"Ask",
        "PatientID":patID,
    })
      }).then(res=> res.json())
      .then(data =>{
        // console.log(data)
        if (data != false) {
          firstNameBox.value = data['FirstName']
          lastNameBox.value = data['LastName']
          descriptionPat.value = data['Description']
          GenderBox.value = data['Gender']
          AgeBox.value = data['Age']
          unlockPatPanelButtons(false);
          savePatButton.disabled = true
          unlockPatPanel(true);
          currentPat.disabled = false
          patientPhoto.setAttribute('src',data['Photo'])

        }else{
          alert('Not exist')
  
        }
      }
  
    );
  }

}

// if added new 
addPatButton.onclick = function(event){
  unlockPatPanel(false);
  unlockPatPanelButtons(false);
  currentPat.value= ""
  firstNameBox.value = ""
  lastNameBox.value = ""
  descriptionPat.value = ""
  GenderBox.value = ""
  AgeBox.value = ""
  patientPhoto.setAttribute('src','ServiceUNSW.png')

  deletePatButton.disabled = true
  editPatButton.disabled = true
  currentPat.disabled = true
  cancelPatButton.disabled = false
}

// if not edit
cancelPatButton.onclick = function(params) {
  currentPat.value= ""
  firstNameBox.value = ""
  lastNameBox.value = ""
  descriptionPat.value = ""
  GenderBox.value = ""
  AgeBox.value = ""

  unlockPatPanel(true);
  unlockPatPanelButtons(true);
  cancelPatButton.disabled = true
  currentPat.disabled = false
}

// edit give ability to edit current term
editPatButton.onclick = function(event) {

  unlockPatPanel(false);
  editPatButton.disabled = true
  savePatButton.disabled = false
  currentPat.disabled = true
  cancelPatButton.disabled = false

}

// save current term
savePatButton.onclick = function(event) {
  unlockPatPanel(true);
  patID = currentPat.value

  if (firstNameBox.value.trim()!='' && lastNameBox.value.trim()!='') {
    fetch('request.php',{
      method:'post',
      body: JSON.stringify({
        "Type":"Patients",
        "Action":"Save",
        "PatientID":patID,
        "FirstName":firstNameBox.value,
        "LastName":lastNameBox.value,
        "Gender":GenderBox.value,
        "Age":AgeBox.value,
        "Description":descriptionPat.value,
    })
      }).then(res=> res.text())
      .then(data =>{
        // console.log(data)
        if (data != false) {
          refreshPat();
          alert("Saved")
          // console.log(data)
        }else{
          alert("connection failed")
        }

      });
  
  }else{
    alert('patient name can not be empty')
  }
  savePatButton.disabled = true
  currentPat.disabled = false
  cancelPatButton.disabled = true
}

// delete current term
deletePatButton.onclick = function(event) {
  lastNameBox.value = ""
  firstNameBox.value = ""
  GenderBox.value = ""
  AgeBox.value = ""

  descriptionPat.value = ""
  patID = currentPat.value

  fetch('request.php',{
    method:'post',
    body: JSON.stringify({
      "Type":"Patients",
      "Action":"Delete",
      "PatientID":patID,
  })
    }).then(res=> res.text())
    .then(data => {
      if (data != false) {
        refreshPat();
        alert("Deleted")

      }else{
        alert("connection failed")
      }
    });

  
  unlockPatPanel(true);
  unlockPatPanelButtons(true);
  currentPat.disabled = false
  cancelPatButton.disabled = true
}