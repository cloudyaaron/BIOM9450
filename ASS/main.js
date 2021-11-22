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

function refreshCalendar(date) {
    console.log(date)
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    var startday = new Date(date)

    for (let index = 0; index < 7; index++) {

        // clear current calendar
        var dayTable = document.getElementById("table "+index);
        dayTable.innerHTML = ''

        // create new calendar
        var newDaytable = document.createElement('tbody')

        var headerRow = document.createElement('tr')
        var headerContent = document.createElement('th')
        day = new Date(date)
        day.setDate(day.getDate()+index)
        // console.log(new Date(day))
        headerContent.innerText = day
        headerRow.appendChild(headerContent)
        newDaytable.appendChild(headerRow)
        dayTable.appendChild(newDaytable)

        

    }
}


var PID = ""
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
          console.log(data)
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
    }
}


let modal = document.getElementsByClassName("modal");

// Get the tab that opens the modal
var tab = document.getElementsByClassName("trigger");


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