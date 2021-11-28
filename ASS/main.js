// get main page elements
let patient = document.getElementById("currentP");
let getPatientbutton = document.getElementById("getPatient");

let patientNameBox = document.getElementById("patientName");
let patientDesBox = document.getElementById("patientDes");
let patientGenderBox = document.getElementById("patientGender");
let patientAgeBox = document.getElementById("patientAge");
let patientPhoto = document.getElementById("pImage");
let patientIDBox = document.getElementById("patientID");

// get date box
let dateBox = document.getElementById("pickedDate");

// get summary button and modal
let mySummary = document.getElementById("GMWS");
let patientSummary = document.getElementById("GPWS");
let summaryBox = document.getElementById("summarymodal");
let summaryTitle = document.getElementById("summary modal title");
let summaryBody = document.getElementById("summary modal body");

// onchange of the date box refresh the page
dateBox.onchange = function(event) {
    refreshCalendar(event.target.value)
}

// refreshing the calendar
function refreshCalendar(date) {
    // console.log(date)

    for (let index = 0; index < 7; index++) {

        // clear current calendar
        var dayTable = document.getElementById("table "+index);
        dayTable.innerHTML = ''

        // create new calendar
        // var newDaytable = document.createElement('tbody')

        var headerRow = document.createElement('tr')
        var headerContent = document.createElement('th')
        day = new Date(date)
        day.setDate(day.getDate()+index)

        // console.log(new Date(day))
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        let display = day.getFullYear()+"/" + (day.getMonth()+1) + "/"+day.getDate()+" "+days[day.getDay()] 
        let shortdisplay = (day.getMonth()+1) + '/'+day.getDate()+'/'+day.getFullYear()

        headerContent.innerText = display
        headerRow.appendChild(headerContent)
        // newDaytable.appendChild(headerRow)
        dayTable.setAttribute('data-value',shortdisplay)


        // asking api the dispensing info

        fetch('request.php',{
          method:'post',
          body: JSON.stringify({
            "Type":"Arrangement",
            "Action":"ShortAsk",
            "PatientID":patID,
            "Date":shortdisplay,
        })
          }).then(res=> res.json())
          .then(data =>{
            // console.log(data)
            if (data != false) {
              for (let key in data) {
                var row = document.createElement('tr')
                var rowcontent = document.createElement('td')
                rowcontent.innerText = key
                row.appendChild(rowcontent)
                rowcontent.className = 'bcell'
                var dayTable = document.getElementById("table "+index);
                dayTable.appendChild(row)
                for (let k in data[key]) {
                  var termrow = document.createElement('tr')
                  var termrowcontent = document.createElement('td')
                  termrowcontent.innerText = data[key][k]['name']
                  termrow.appendChild(termrowcontent)
                  termrowcontent.className = data[key][k]['type']
                  termrow.className = data[key][k]['type']
                  dayTable.appendChild(termrow)
                }
              }

            }else{
              alert('Not exist')
      
            }
          }
      
        );

        dayTable.appendChild(headerRow)


    }
}


var patID = ""
getPatientbutton.onclick = function(event) {
    patID = patient.value
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
            patientNameBox.innerText = data['FirstName'] +' '+ data['LastName']
            patientDesBox.innerText = data['Description']
            patientGenderBox.innerText = data['Gender']
            patientAgeBox.innerText = data['Age']
            patientIDBox.value = data['PatientID']
            patientIDBox.innerText = data['PatientID']

            if (data['Photo'] == null) {
                patientPhoto.setAttribute('src',"./ServiceUNSW.png")
 
            }else{
                patientPhoto.setAttribute('src',data['Photo'])
            }
            dateBox.disabled = false
            let day = new Date()
            let shortdisplay = day.getFullYear()+"-" + (day.getMonth()+1) + "-"+day.getDate()
      
            dateBox.value = shortdisplay
            refreshCalendar(dateBox.value)
            mySummary.disabled = false
            patientSummary.disabled = false
          }else{
            alert('Not exist')
            patientNameBox.innerText = ""
            patientDesBox.innerText = ""
            patientGenderBox.innerText = ""
            patientAgeBox.innerText = ""
            patientIDBox.value = ""
            patientIDBox.innerText = ""
            patientPhoto.setAttribute('src',"./ServiceUNSW.png")
            dateBox.disabled = true
            mySummary.disabled = true
            patientSummary.disabled = true
            for (let index = 0; index < 7; index++) {

              // clear current calendar
              var dayTable = document.getElementById("table "+index);
              dayTable.innerHTML = ''
            }
          }
        }
    
      );




    }
}


function summaryShow(type){
  // console.log(type)
  summaryBox.style.display = "block";

  let displayDate = new Date(dateBox.value)
  let shortdisplay = (displayDate.getMonth()+1) + '/'+displayDate.getDate()+'/'+displayDate.getFullYear()
  displayDate.setDate(displayDate.getDate()+6)
  let endshortdisplay = (displayDate.getMonth()+1) + '/'+displayDate.getDate()+'/'+displayDate.getFullYear()

  if (type == "GMWS") {
    summaryTitle.innerText = "My Practitioner weekly summary From: " + shortdisplay + " to " + endshortdisplay
    fetch('request.php',{
      method:'post',
      body: JSON.stringify({
        "Type":"Summary",
        "Action":type,
        "StartDate":shortdisplay,
        "EndDate":endshortdisplay,
    })
      }).then(res=> res.json())
      .then(data =>{
        console.log(data)
        if (data != false) {
          summaryBody.innerHTML = ""
          let frist = document.createElement('p')
          frist.innerText = "You have assign: " + data['totalRegimes'] + " Meals to " +data['uniquePatientsRegime'] + " Patient(s), in the chosen week"
          let second = document.createElement('p')
          second.innerText = "And dispense: " + data['totalMedications'] + " Medications to " +data['uniquePatientsMedication'] + " Patient(s), in the chosen week"
          summaryBody.appendChild(frist)
          summaryBody.appendChild(second)
          let s = document.createElement('hr')
          summaryBody.appendChild(s)
          
          let t = document.createElement('table')
          let header = document.createElement('tr')
          let headerc1 = document.createElement('th')
          headerc1.innerText = 'Name'
          header.appendChild(headerc1)
          let headerc2 = document.createElement('th')
          headerc2.innerText = 'Patient'
          header.appendChild(headerc2)
          let headerc6 = document.createElement('th')
          headerc6.innerText = 'Date'
          header.appendChild(headerc6)
          let headerc3 = document.createElement('th')
          headerc3.innerText = 'Round'
          header.appendChild(headerc3)
          let headerc4 = document.createElement('th')
          headerc4.innerText = 'Status'
          header.appendChild(headerc4)
          let headerc5 = document.createElement('th')
          headerc5.innerText = 'Dosage'
          header.appendChild(headerc5)
          t.appendChild(header)
          t.className = 'fulltable'
          summaryBody.appendChild(t)

          for (let key in data['Regime']) {
            let row = document.createElement('tr')
            let headerc1 = document.createElement('td')
            headerc1.innerText = data['Regime'][key]['name']
            row.appendChild(headerc1)
            let headerc2 = document.createElement('td')
            headerc2.innerText = data['Regime'][key]['firstname']+" "+data['Regime'][key]['lastname']
            row.appendChild(headerc2)
            let headerc3 = document.createElement('td')
            let shortdate = data['Regime'][key]['date'].substring(0,10)
            headerc3.innerText = shortdate
            row.appendChild(headerc3)
            let headerc4 = document.createElement('td')
            headerc4.innerText = data['Regime'][key]['round']
            row.appendChild(headerc4)
            let headerc5 = document.createElement('td')
            headerc5.innerText = data['Regime'][key]['status']
            row.appendChild(headerc5)
            let headerc6 = document.createElement('td')
            headerc6.innerText = 'NA'
            row.appendChild(headerc6)
            t.appendChild(row)
          }
          for (let key in data['Medication']) {
            let row = document.createElement('tr')
            let headerc1 = document.createElement('td')
            headerc1.innerText = data['Medication'][key]['name']
            row.appendChild(headerc1)
            let headerc2 = document.createElement('td')
            headerc2.innerText = data['Medication'][key]['firstname']+" "+data['Medication'][key]['lastname']
            row.appendChild(headerc2)
            let headerc3 = document.createElement('td')
            headerc3.innerText = data['Medication'][key]['date'].substring(0,10)
            row.appendChild(headerc3)
            let headerc4 = document.createElement('td')
            headerc4.innerText = data['Medication'][key]['round']
            row.appendChild(headerc4)
            let headerc5 = document.createElement('td')
            headerc5.innerText = data['Medication'][key]['status']
            row.appendChild(headerc5)
            let headerc6 = document.createElement('td')
            headerc6.innerText = data['Medication'][key]['dosage']
            row.appendChild(headerc6)
            t.appendChild(row)
          }
        }else{
          alert('database internal error')
        }
      }
    );

  }else if(type == "GPWS"){
    summaryTitle.innerText = "Patient weekly summary From: " + shortdisplay + " to " + endshortdisplay
    summaryBody.innerHTML = ""
    fetch('request.php',{
      method:'post',
      body: JSON.stringify({
        "Type":"Summary",
        "Action":type,
        "PatientID":patID,
        "StartDate":shortdisplay,
        "EndDate":endshortdisplay,
    })
      }).then(res=> res.json())
      .then(data =>{
        console.log(data)
        if (data != false) {
          let tProtein = 0
          let tCarbs = 0
          let tFat = 0
          let tFibre = 0;
          let tSodium = 0;
          let tSugar = 0;
          let foodList = {}
          let ceasedfoodList = {}

          for (let key in data['Regime']) {
            tProtein = tProtein + parseFloat(data['Regime'][key]['Protein'])
            tCarbs = tCarbs + parseFloat(data['Regime'][key]['Carbs'])
            tFat = tFat + parseFloat(data['Regime'][key]['Fat'])
            tFibre = tFibre + parseFloat(data['Regime'][key]['Fibre'])
            tSodium = tSodium + parseFloat(data['Regime'][key]['Sodium'])
            tSugar = tSugar + parseFloat(data['Regime'][key]['Sugar'])
            if (data['Regime'][key]['Status'] == "Given") {
              if (foodList[data['Regime'][key]['RegimeName']]) {
                foodList[data['Regime'][key]['RegimeName']] = foodList[data['Regime'][key]['RegimeName']] + 1

              }else{
                foodList[data['Regime'][key]['RegimeName']] = 1
              }
            }else{
              if (ceasedfoodList[data['Regime'][key]['RegimeName']]) {
                ceasedfoodList[data['Regime'][key]['RegimeName']] = ceasedfoodList[data['Regime'][key]['RegimeName']] + 1
              }else{
                ceasedfoodList[data['Regime'][key]['RegimeName']] = 1
              }
            }
          }

          let ceased = 0
          for (let key in ceasedfoodList) {
            ceased = ceased + ceasedfoodList[key]
          }
          let foods = 0
          for (let key in foodList) {
            foods = foods + foodList[key]
          }
          summaryBody.innerHTML = ""

          // protein
          let aprotein = 50
          let d1 = document.createElement('div')
          let bar1 = document.createElement('meter')
          bar1.setAttribute('max',aprotein)
          bar1.setAttribute('value',tProtein/7)
          d1.innerText='Average Daily Protein taken:  '+ parseInt(tProtein / 7)+" / "+ aprotein+"  :    "
          d1.appendChild(bar1)
          summaryBody.appendChild(d1)

          let afat = 61
          let d2 = document.createElement('div')
          let bar2 = document.createElement('meter')
          bar2.setAttribute('max',afat)
          bar2.setAttribute('value',tFat/7)
          d2.innerText='Average Daily Fat taken:  '+ parseInt(tFat / 7)+" / "+ afat+"  :    "
          d2.appendChild(bar2)
          summaryBody.appendChild(d2)

          let asodium = 2.3
          let d3 = document.createElement('div')
          let bar3 = document.createElement('meter')
          bar3.setAttribute('max',asodium)
          bar3.setAttribute('value',tSodium/7)
          d3.innerText='Average Daily Sodium taken:  '+ parseInt(tSodium / 7)+" / "+ asodium+"  :    "
          d3.appendChild(bar3)
          summaryBody.appendChild(d3)

          let asugar = 30
          let d4 = document.createElement('div')
          let bar4 = document.createElement('meter')
          bar4.setAttribute('max',asugar)
          bar4.setAttribute('value',tSugar/7)
          d4.innerText='Average Daily Sugar taken:  '+ parseInt(tSugar / 7)+" / "+ asugar+"  :    "
          d4.appendChild(bar4)
          summaryBody.appendChild(d4)

          let afibre = 27
          let d5 = document.createElement('div')
          let bar5 = document.createElement('meter')
          bar5.setAttribute('max',afibre)
          bar5.setAttribute('value',tFibre/7)
          d5.innerText='Average Daily Fibre taken:  '+ parseInt(tFibre / 7)+" / "+ afibre+"  :    "
          d5.appendChild(bar5)
          summaryBody.appendChild(d5)

          let acarbs = 270
          let d6 = document.createElement('div')
          let bar6 = document.createElement('meter')
          bar6.setAttribute('max',acarbs)
          bar6.setAttribute('value',tCarbs/7)
          d6.innerText='Average Daily Carbs taken:  '+ parseInt(tCarbs / 7)+" / "+ acarbs+"  :    "
          d6.appendChild(bar6)
          summaryBody.appendChild(d6)

          let frist = document.createElement('p')
          frist.innerText = "This patient has been successful assigned: " + foods + " Meals in the chosen week. And " + ceased + " meals has been rejected"
          let second = document.createElement('p')
          summaryBody.appendChild(frist)
          summaryBody.appendChild(second)
          let s = document.createElement('hr')
          summaryBody.appendChild(s)

          let t = document.createElement('table')
          let header = document.createElement('tr')
          let headerc1 = document.createElement('th')
          headerc1.innerText = 'Meal/Medication Name'
          header.appendChild(headerc1)
          let headerc2 = document.createElement('th')
          headerc2.innerText = 'Total Consumed Number'
          header.appendChild(headerc2)
          t.appendChild(header)
          t.className = 'fulltable'
          summaryBody.appendChild(t)

          for (let key in foodList) {
            let row = document.createElement('tr')
            let headerc1 = document.createElement('td')
            headerc1.innerText = key
            row.appendChild(headerc1)
            let headerc2 = document.createElement('td')
            headerc2.setAttribute("align","middle")
            headerc2.innerText = foodList[key]
            row.appendChild(headerc2)
            t.appendChild(row)
          }
          let medlist = {}
          let ceasedmedlist = {}
          for (let key in data['Medication']) {
            if (data['Medication'][key]['Status'] == "Given") {
              if (medlist[data['Medication'][key]['MedicationName']]) {
                medlist[data['Medication'][key]['MedicationName']] =  medlist[data['Medication'][key]['MedicationName']] + parseFloat( data['Medication'][key]['Dosage'])

              }else{
                medlist[data['Medication'][key]['MedicationName']] = parseFloat( data['Medication'][key]['Dosage'])
              }
            }else{
              if (ceasedmedlist[data['Medication'][key]['MedicationName']]) {
                ceasedmedlist[data['Medication'][key]['MedicationName']] = ceasedmedlist[data['Medication'][key]['MedicationName']] + parseFloat( data['Medication'][key]['Dosage'])
              }else{
                ceasedmedlist[data['Medication'][key]['MedicationName']] = parseFloat(data['Medication'][key]['Dosage'])
              }
            }
          }
          let ceasedmed = 0
          for (let key in ceasedmedlist) {
            ceasedmed = ceasedmed + ceasedmedlist[key]
          }
          let meds = 0
          for (let key in medlist) {
            meds = meds + medlist[key]
          }

          console.log(medlist)
          console.log(ceasedmed)
          second.innerText = "This patient taken: " + meds + " Medications and reject " +ceasedmed + " medications in the chosen week"

          for (let key in medlist) {
            let row = document.createElement('tr')
            let headerc1 = document.createElement('td')
            headerc1.innerText = key
            row.appendChild(headerc1)
            let headerc2 = document.createElement('td')
            headerc2.setAttribute("align","middle")
            headerc2.innerText = medlist[key]
            row.appendChild(headerc2)
            t.appendChild(row)
          }

        }else{
          if (data != []) {
            alert('database internal error')

          }else{

          }
        }
      }
    );
  }
}

let modal = document.getElementsByClassName("modal")[0];

// Get the tab that opens the modal
var tab = document.getElementsByClassName("trigger");


// binding the function when modal is opened
for (let index = 0; index < 7; index++) {
  // When the user clicks the button, open the modal 
  tab[index].onclick = refreshModal
}

// refreshing the modal with brand new data from api
function refreshModal(event) {
  modal.style.display = "block";
  let date = event.target.parentNode.parentNode.dataset.value
  // console.log(date)
  var mtitle = document.getElementById('modal title')
  mtitle.innerText = date
  refreshModalPanel(date)
  
}


function refreshModalPanel(date) {
  // get data for that date 
  fetch('request.php',{
    method:'post',
    body: JSON.stringify({
      "Type":"Arrangement",
      "Action":"Ask",
      "PatientID":patID,
      "Date":date,

  })
    }).then(res=> res.json())
    .then(data =>{
      // console.log(data)
      if (data != false) {
        var regimetable = document.getElementById('regimeTable')
        regimetable.innerHTML = 
          `<tr>
            <th>
                Regieme Name
            </th>
            <th>
                Round Time
            </th>
            <th>
                Status
            </th>
            <th>
                Action
            </th>
          </tr>`
        for (let key in data['Regime']) {
          let newRow = document.createElement('tr')
          newRow.setAttribute('data-id',data['Regime'][key]['id'])
          newRow.setAttribute('data-statusid',data['Regime'][key]['statusid'])

          let newRowName = document.createElement('td')
          newRowName.innerText = data['Regime'][key]['name']
          newRow.appendChild(newRowName)

          let newRowRound = document.createElement('td')
          newRowRound.innerText = data['Regime'][key]['round']
          newRow.appendChild(newRowRound)


          let newRowStatus = document.createElement('td')
          let newSelectionList = document.createElement('select')
          let statuses = ['Given','Refused','Fasting','No Stock','Ceased']
          for (let i in statuses) {
            let newOption = document.createElement('option')
            newOption.value = i
            newOption.innerText = statuses[i]
            if (newOption.innerText == data['Regime'][key]['status']) {
              newOption.selected = true
            }
            newSelectionList.appendChild(newOption)
          }
          newSelectionList.onchange = statusChange
          newRowStatus.appendChild(newSelectionList)
          newRow.appendChild(newRowStatus)

          let newRowAction = document.createElement('td')
          let newSaveButton = document.createElement('button')
          newSaveButton.innerHTML = "&#10004;"
          newSaveButton.onclick = saveRegime

          let newDeleteButton = document.createElement('button')
          newDeleteButton.innerHTML = "&#x2716;"
          newDeleteButton.onclick = regimeDelete
          newRowAction.appendChild(newSaveButton)
          newRowAction.appendChild(newDeleteButton)
          newRow.appendChild(newRowAction)

          regimetable.appendChild(newRow)
        }

        var medicationTable = document.getElementById('medicationTable')
        medicationTable.innerHTML =`
          <tr>
            <th>
                medication Name
            </th>
            <th>
                Round Time
            </th>
            <th>
                Status
            </th>
            <th>
                Dosage
            </th>
            <th>
                Action
            </th>
          </tr> 
        `
        for (let key in data['Medication']) {
          let newRow = document.createElement('tr')
          newRow.setAttribute('data-id',data['Medication'][key]['id'])
          newRow.setAttribute('data-statusid',data['Medication'][key]['statusid'])

          let newRowName = document.createElement('td')
          newRowName.innerText = data['Medication'][key]['name']
          newRow.appendChild(newRowName)

          let newRowRound = document.createElement('td')
          newRowRound.innerText = data['Medication'][key]['round']
          newRow.appendChild(newRowRound)


          let newRowStatus = document.createElement('td')
          let newSelectionList = document.createElement('select')
          let statuses = ['Given','Refused','Fasting','No Stock','Ceased']
          for (let i in statuses) {
            let newOption = document.createElement('option')
            newOption.value = i
            newOption.innerText = statuses[i]
            if (newOption.innerText == data['Medication'][key]['status']) {
              newOption.selected = true
            }
            newSelectionList.appendChild(newOption)
          }
          newSelectionList.onchange = statusChange
          newRowStatus.appendChild(newSelectionList)
          newRow.appendChild(newRowStatus)

          let newdosage = document.createElement('td')
          newdosage.innerText = data['Medication'][key]['dosage']
          newRow.setAttribute('data-dosage',data['Medication'][key]['dosage'])
          newdosage.setAttribute('align','center')
          newRow.appendChild(newdosage)

          let newRowAction = document.createElement('td')
          let newSaveButton = document.createElement('button')
          newSaveButton.innerHTML = "&#10004;"
          newSaveButton.onclick = saveMedication

          let newDeleteButton = document.createElement('button')
          newDeleteButton.innerHTML = "&#x2716;"
          newDeleteButton.onclick = medicationDelete
          newRowAction.appendChild(newSaveButton)
          newRowAction.appendChild(newDeleteButton)
          newRow.appendChild(newRowAction)
          // console.log(newRow)
          medicationTable.appendChild(newRow)
        }
      }else{
        alert('Not exist')

      }
    }

  );
}
// onchange of status
function statusChange(event) {
  let row = event.target.parentNode.parentNode
  row.setAttribute('data-statusid',parseInt(event.target.value)+1)
  // console.log(row)

}

// onchange of round
function roundChange(event) {
  // console.log(event.target.value)
  let row = event.target.parentNode.parentNode
  row.setAttribute('data-roundid',parseInt(event.target.value)+1)
}

// onchange of regime
function termChange(event) {
  // console.log(event.target.value)
  let row = event.target.parentNode.parentNode
  row.setAttribute('data-termid',parseInt(event.target.value))
}

function dosageChange(event) {
  // console.log(event.target.value)
  let row = event.target.parentNode.parentNode
  row.setAttribute('data-dosage',parseInt(event.target.value))
}

// onclick of delete button
function regimeDelete(event) {
    // send delete request
    var mtitle = document.getElementById('modal title')
    let date = mtitle.innerText

    let rid = event.target.parentNode.parentNode.dataset.id
    // console.log(rid)
    fetch('request.php',{
      method:'post',
      body: JSON.stringify({
        "Type":"Arrangement",
        "Action":"DeleteRegime",
        "RecordID": rid,
        "Date": date
    })
      }).then(res=> res.text())
      .then(data =>{
        // console.log(data)
        if (data = false) {
          alert('Database internal error')
        }else{
          refreshCalendar(dateBox.value)
          refreshModalPanel(date)
        }
      }
  
    );
}

// onclick of medicationDelete button
function medicationDelete(event) {
      // send delete request
      var mtitle = document.getElementById('modal title')
      let date = mtitle.innerText
  
      let rid = event.target.parentNode.parentNode.dataset.id
      // console.log(rid)
      fetch('request.php',{
        method:'post',
        body: JSON.stringify({
          "Type":"Arrangement",
          "Action":"DeleteMedication",
          "RecordID": rid,
          "Date": date
      })
        }).then(res=> res.text())
        .then(data =>{
          // console.log(data)
          if (data = false) {
            alert('Database internal error')
          }else{
            refreshCalendar(dateBox.value)
            refreshModalPanel(date)
          }
        }
    
      );
}

// when new regime is added
function addNewRegime(params) {
  var regimetable = document.getElementById('regimeTable')
    let newRow = document.createElement('tr')
    newRow.setAttribute('data-id','')
    newRow.setAttribute('data-statusid','1')
    newRow.setAttribute('data-roundid','1')

    let newRowName = document.createElement('td')
    let newInput = document.createElement('input')
    let newList = document.createElement('datalist')
    newList.id = 'food list'


    // collect all foods
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
            newList.appendChild(selectChild)
          });
        }else{
          alert('Database internal error')
  
        }
      }
  
    );
    newInput.setAttribute('list','food list')
    newInput.onchange = termChange
    newRowName.appendChild(newInput)
    newRowName.appendChild(newList)
    newRow.appendChild(newRowName)

    let newRowRound = document.createElement('td')
    let newSelectionRound = document.createElement('select')
    let Rounds = ['morning','afternoon','evening']
    for (let i in Rounds) {
      let newOption = document.createElement('option')
      newOption.value = i
      newOption.innerText = Rounds[i]
      if (newOption.innerText == 'Given') {
        newOption.selected = true
      }
      newSelectionRound.appendChild(newOption)
    }
    newSelectionRound.onchange = roundChange
    newRowRound.appendChild(newSelectionRound)
    newRow.appendChild(newRowRound)


    let newRowStatus = document.createElement('td')
    let newSelectionList = document.createElement('select')
    let statuses = ['Given','Refused','Fasting','No Stock','Ceased']
    for (let i in statuses) {
      let newOption = document.createElement('option')
      newOption.value = i
      newOption.innerText = statuses[i]
      if (newOption.innerText == 'Given') {
        newOption.selected = true
      }
      newSelectionList.appendChild(newOption)
    }
    newSelectionList.onchange = statusChange
    newRowStatus.appendChild(newSelectionList)
    newRow.appendChild(newRowStatus)

    let newRowAction = document.createElement('td')
    let newSaveButton = document.createElement('button')
    newSaveButton.innerHTML = "&#10004;"
    newSaveButton.onclick = saveRegime
    newRowAction.appendChild(newSaveButton)
    newRow.appendChild(newRowAction)

    regimetable.appendChild(newRow)
}

// when new medication is added
function addNewMedication(){
  var medicationTable = document.getElementById('medicationTable')
  let newRow = document.createElement('tr')
  newRow.setAttribute('data-id','')
  newRow.setAttribute('data-statusid','1')
  newRow.setAttribute('data-roundid','1')
  newRow.setAttribute('data-dosage','1')

  let newRowName = document.createElement('td')
  let newInput = document.createElement('input')
  let newList = document.createElement('datalist')
  newList.id = 'medication list'


  // collect all foods
  fetch('request.php',{
    method:'post',
    body: JSON.stringify({
      "Type":"Medications",
      "Action":"ALL",
  })
    }).then(res=> res.json())
    .then(data =>{
      if (data != false) {
        data.forEach(element => {
          var selectChild = document.createElement('option')
          selectChild.setAttribute("value",element['id'])
          selectChild.innerText = element['MedicationName']
          newList.appendChild(selectChild)
        });
      }else{
        alert('Database internal error')

      }
    }

  );
  newInput.setAttribute('list','medication list')
  newInput.onchange = termChange
  newRowName.appendChild(newInput)
  newRowName.appendChild(newList)
  newRow.appendChild(newRowName)

  let newRowRound = document.createElement('td')
  let newSelectionRound = document.createElement('select')
  let Rounds = ['morning','afternoon','evening']
  for (let i in Rounds) {
    let newOption = document.createElement('option')
    newOption.value = i
    newOption.innerText = Rounds[i]
    if (newOption.innerText == 'Given') {
      newOption.selected = true
    }
    newSelectionRound.appendChild(newOption)
  }
  newSelectionRound.onchange = roundChange
  newRowRound.appendChild(newSelectionRound)
  newRow.appendChild(newRowRound)


  let newRowStatus = document.createElement('td')
  let newSelectionList = document.createElement('select')
  let statuses = ['Given','Refused','Fasting','No Stock','Ceased']
  for (let i in statuses) {
    let newOption = document.createElement('option')
    newOption.value = i
    newOption.innerText = statuses[i]
    if (newOption.innerText == 'Given') {
      newOption.selected = true
    }
    newSelectionList.appendChild(newOption)
  }
  newSelectionList.onchange = statusChange
  newRowStatus.appendChild(newSelectionList)
  newRow.appendChild(newRowStatus)

  let newRowDosage = document.createElement('td')
  let newRowDosageInput = document.createElement('input')
  newRowDosageInput.setAttribute("type",'number')
  newRowDosageInput.setAttribute("style",'width:50px')
  newRowDosageInput.setAttribute("min",'1')
  newRowDosageInput.value = 1
  newRowDosageInput.onchange = dosageChange
  newRowDosage.appendChild(newRowDosageInput)
  newRow.appendChild(newRowDosage)

  let newRowAction = document.createElement('td')
  let newSaveButton = document.createElement('button')
  newSaveButton.innerHTML = "&#10004;"
  newSaveButton.onclick = saveMedication
  newRowAction.appendChild(newSaveButton)
  newRow.appendChild(newRowAction)

  medicationTable.appendChild(newRow)
}

// add and save for regime
function saveRegime(event) {
  let row = event.target.parentNode.parentNode
  // console.log(row)
  let recordId = row.dataset.id
  let statusId = row.dataset.statusid
  let roundId = row.dataset.roundid
  let regimeId = row.dataset.termid

  // console.log(recordId)
  // console.log(statusId)
  // console.log(roundId)
  // console.log(regimeId)
  let valid = true
  if (!recordId) {
    // console.log('new')
    var re = /^\d+$/;
    if (!re.test(regimeId)) {
      valid = false
    }
  }

  // send to api if qualified
  if (valid) {
    var mtitle = document.getElementById('modal title')
    let date = mtitle.innerText
    // console.log('send')
    fetch('request.php',{
      method:'post',
      body: JSON.stringify({
        "Type":"Arrangement",
        "Action":"AddRegime",
        "RecordID":recordId,
        "StatusID":statusId,
        "RoundID":roundId,
        "RegimeID":regimeId,
        "PatientID":patID,
        "Date": date

    })
      }).then(res=> res.json())
      .then(data =>{
        // console.log(data)
        if (data != false) {
          refreshCalendar(dateBox.value)
          refreshModalPanel(data['Date'].replace("\\",""))
          alert('saved')

        }else{
          alert('Unkown Regime ID')
        }
      }
    );
  }else{
    alert('Search field only taken number, however text can be searched and auto transfer to id')
  }
}

// add and save for medication
function saveMedication(event) {
  let row = event.target.parentNode.parentNode
  // console.log(row)
  let recordId = row.dataset.id
  let statusId = row.dataset.statusid
  let roundId = row.dataset.roundid
  let termid = row.dataset.termid
  let dosage = row.dataset.dosage

  // console.log(recordId)
  // console.log(statusId)
  // console.log(roundId)
  // console.log(termid)
  // console.log(dosage)

  let valid = true
  if (!recordId) {
    // console.log('new')
    var re = /^\d+$/;
    if (!re.test(termid)) {
      valid = false
    }
    if (dosage<=0) {
      valid = false
    }
  }

  // send to api if qualified
  if (valid) {
    var mtitle = document.getElementById('modal title')
    let date = mtitle.innerText
    // console.log('send')
    fetch('request.php',{
      method:'post',
      body: JSON.stringify({
        "Type":"Arrangement",
        "Action":"AddMedication",
        "RecordID":recordId,
        "StatusID":statusId,
        "RoundID":roundId,
        "MedicationID":termid,
        "Dosage":dosage,
        "PatientID":patID,
        "Date": date

    })
      }).then(res=> res.json())
      .then(data =>{
        // console.log(data)
        if (data != false) {
          refreshCalendar(dateBox.value)
          refreshModalPanel(data['Date'].replace("\\",""))
          alert('saved')

        }else{
          alert('Unkown Regime ID')
        }
      }
    );
  }else{
    alert('Search and dosage field only taken number, however text can be searched and auto transfer to id\n Or Dosage can not zero or Negative value')
  }
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  // console.log(event.target)

  if (event.target == modal) {
      modal.style.display = "none";
  }
  if (event.target == summaryBox) {
    summaryBox.style.display = "none";
}
}


var patientSearchList = document.getElementById('plist')

// refreshing search bar patients
function refreshPatients(){
    patientSearchList.innerHTML = ''

    fetch('request.php',{
        method:'post',
        body: JSON.stringify({
          "Type":"Patients",
          "Action":"ALL",
      })
        }).then(res=> res.json())
        .then(data =>{
          // console.log(data)
          if (data != false) {
            data.forEach(element => {
              var selectChild = document.createElement('option')
              selectChild.setAttribute("value",element['id'])
              selectChild.innerText = element['FirstName']+" "+element['LastName']
              patientSearchList.appendChild(selectChild)
            });
          }else{
            alert('Database internal error')
    
          }
        }
    
        );
}





// main
refreshPatients()