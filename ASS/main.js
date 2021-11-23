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

// onchange of the date box refresh the page
dateBox.onchange = function(event) {
    refreshCalendar(event.target.value)
}

// refreshing the calendar
function refreshCalendar(date) {
    console.log(date)

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
  
          }else{
            alert('Not exist')
    
          }
        }
    
      );


      dateBox.disabled = false
      let day = new Date()
      let shortdisplay = day.getFullYear()+"-" + (day.getMonth()+1) + "-"+day.getDate()

      dateBox.value = shortdisplay
      refreshCalendar(dateBox.value)

    }
}


let modal = document.getElementsByClassName("modal")[0];

// Get the tab that opens the modal
var tab = document.getElementsByClassName("trigger");


for (let index = 0; index < 7; index++) {
  // When the user clicks the button, open the modal 
  tab[index].onclick = function(event) {
    modal.style.display = "block";
    let date = event.target.parentNode.parentNode
    // console.log(date)
    var mtitle = document.getElementById('modal title')
    mtitle.innerText = date.dataset.value
  }
}


// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  // console.log(event.target)

  if (event.target == modal) {
      modal.style.display = "none";
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