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
      "Action":"ALL"
  })
    }).then(res=> res.text())
    .then(data =>{
      console.log(data)
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

// get editor panel
var RegNameBox = document.getElementById('RegimesName');
var descriptionText = document.getElementById('RegimesDescription');

// unlock the panel
function unlockRegPanel(params) {
  RegNameBox.disabled = params
  descriptionText.disabled = params
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
          descriptionText.value = data['Description']
          unlockRegPanelButtons(false);
          saveRegButton.disabled = true
  
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
  descriptionText.value = ""

  deleteRegButton.disabled = true
  editRegButton.disabled = true
  currentReg.disabled = true

}

// edit give ability to edit current term
editRegButton.onclick = function(event) {

  unlockRegPanel(false);
  editRegButton.disabled = true
  saveRegButton.disabled = false
  currentReg.disabled = true

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
        "Prescription":prescriptionBox.checked,
        "Description":descriptionText.value,
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
  descriptionText.value = ""
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

}