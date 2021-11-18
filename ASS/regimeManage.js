// Get the Regime modal
let regimesmodal = document.getElementById("regimeModal");

// Get the Regime tab that opens the modal
var RegimesTab = document.getElementById("Regime");

// dynymic refresh the page
function refreshReg() {
  var reglist = document.getElementById('regs');
  var totalReg = document.getElementById('totalRegime');
  reglist.innerHTML = ""

  fetch('request.php',{
    method:'post',
    body: JSON.stringify({
      "Type":"Regimes",
      "Action":"ALL",
  })
    }).then(res=> res.json())
    .then(data =>{
      if (data != false) {
        data.forEach(element => {
          var selectChild = document.createElement('option')
          selectChild.setAttribute("value",element['id'])
          selectChild.innerText = element['RegimeName']
          reglist.appendChild(selectChild)
          totalReg.innerText = data.length;
        });
      }else{
        alert('Database internal error')

      }
    }

    );
}

refreshReg();


// When the user clicks the button, open the modal 
RegimesTab.onclick = function() {
    regimesmodal.style.display = "block";

}

// add evenet listener to every Regimes
var currentReg = document.getElementById('currentReg');
var getRegButton = document.getElementById('getRegime');
var addRegButton = document.getElementById('addRegime');
var editRegButton = document.getElementById('editReg');
var deleteRegButton = document.getElementById('deleteReg');
var saveRegButton = document.getElementById('saveReg');
var cancelRegButton = document.getElementById('cancelReg');

// get editor panel
var RegNameBox = document.getElementById('RegimesName');
var descriptionReg = document.getElementById('RegimesDescription');
var Protein = document.getElementById('Protein');
var Fat = document.getElementById('Fat');
var Carbs = document.getElementById('Carbs');
var Sugar = document.getElementById('Sugar');
var Sodium = document.getElementById('Sodium');
var Fibre = document.getElementById('Fibre');

// unlock the panel
function unlockRegPanel(params) {
  RegNameBox.disabled = params
  descriptionReg.disabled = params
  Protein.disabled = params
  Fat.disabled = params
  Carbs.disabled = params
  Sugar.disabled = params
  Sodium.disabled = params
  Fibre.disabled = params

}

// unlock the button
function unlockRegPanelButtons(params) {
  editRegButton.disabled = params
  deleteRegButton.disabled = params
  saveRegButton.disabled = params

}

var regID = ""

// get detail of Regs to editor panel
getRegButton.onclick = function(event) {
  regID = currentReg.value
  var re = /^\d+$/;

  // test input format
  if (!re.test(regID)) {
    alert('Search field only taken number, however text can be searched and auto transfer to id')
  }else{
    fetch('request.php',{
      method:'post',
      body: JSON.stringify({
        "Type":"Regimes",
        "Action":"Ask",
        "RegimeID":regID,
    })
      }).then(res=> res.json())
      .then(data =>{
        if (data != false) {
          RegNameBox.value = data['RegimeName']
          prescriptionBox.checked = data['Presctiption']
          descriptionReg.value = data['Description']
          Protein.value = data['Protein']
          Fat.value = data['Fat']
          Carbs.value = data['Carbs']
          Sugar.value = data['Sugar']
          Sodium.value = data['Sodium']
          Fibre.value = data['Fibre']

          unlockRegPanel(true);
          unlockRegPanelButtons(false);
          saveRegButton.disabled = true
          currentReg.disabled = false
        }else{
          alert('Not exist')
  
        }
      }
  
    );
  }

}

addRegButton.onclick = function(event){
  unlockRegPanel(false);
  unlockRegPanelButtons(false);
  currentReg.value= ""
  RegNameBox.value = ""
  descriptionReg.value = ""
  Protein.value = ''
  Fat.value = ''
  Carbs.value = ''
  Sugar.value = ''
  Sodium.value = ''
  Fibre.value = ''

  deleteRegButton.disabled = true
  editRegButton.disabled = true
  currentReg.disabled = true
  cancelRegButton.disabled = false
}

// if not edit
cancelRegButton.onclick = function(params) {
  currentReg.value= ""
  RegNameBox.value = ""
  Protein.value = ''
  Fat.value = ''
  Carbs.value = ''
  Sugar.value = ''
  Sodium.value = ''
  Fibre.value = ''
  descriptionReg.value = ""
  unlockRegPanel(true);
  unlockRegPanelButtons(true);
  cancelRegButton.disabled = true
  currentReg.disabled = false
}


// edit give ability to edit current term
editRegButton.onclick = function(event) {

  unlockRegPanel(false);
  editRegButton.disabled = true
  saveRegButton.disabled = false
  currentReg.disabled = true
  cancelRegButton.disabled = false
}

// save current term
saveRegButton.onclick = function(event) {
  unlockRegPanel(true);
  regID = currentReg.value

  if (RegNameBox.value.trim()!='') {
    fetch('request.php',{
      method:'post',
      body: JSON.stringify({
        "Type":"Regimes",
        "Action":"Save",
        "RegimeID":regID,
        "RegimeName":RegNameBox.value,
        "Description":descriptionReg.value,
        "Protein":Protein.value,
        "Fat":Fat.value,
        "Carbs":Carbs.value,
        "Sugar":Sugar.value,
        "Sodium":Sodium.value,
        "Fibre":Fibre.value,

    })
      }).then(res=> res.text())
      .then(data =>{
        if (data != false) {
          refreshReg();
          alert("Saved")
          console.log(data)
        }else{
          alert("connection failed")
        }

      });
  
  }else{
    alert('Regime name can not be empty')
  }
  saveRegButton.disabled = true
  currentReg.disabled = false
}

// delete current term
deleteRegButton.onclick = function(event) {
  RegNameBox.value = ""
  prescriptionBox.checked = false
  descriptionReg.value = ""
  regID = currentReg.value

  fetch('request.php',{
    method:'post',
    body: JSON.stringify({
      "Type":"Regimes",
      "Action":"Delete",
      "RegimeID":regID,
  })
    }).then(res=> res.text())
    .then(data => {
      if (data != false) {
        refreshReg();
        alert("Deleted")

      }else{
        alert("connection failed")
      }
    });

  
  unlockRegPanel(true);
  unlockRegPanelButtons(true);
  currentReg.disabled = false
  cancelRegButton.disabled=true
}