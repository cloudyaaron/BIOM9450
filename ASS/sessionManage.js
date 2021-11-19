// Get the session modal
let sessionModal = document.getElementById("sessionModal");

// Get the session tab that opens the modal
var SessionTab = document.getElementById("Session");

// dynymic refresh the page
function refreshSes() {
  var sessionTable = document.getElementById('sessionTable');

  sessionTable.innerHTML = `                                                    <tr>
  <th>
      PID
  </th>
  <th>
      User Name
  </th>
  <th>
      Full Name
  </th>
  <th>
      Token
  </th>
  <th>
      Valid From
  </th>
  <th>
      Action
  </th>
</tr>`

  fetch('request.php',{
    method:'post',
    body: JSON.stringify({
      "Type":"Session",
      "Action":"ALL",
  })
    }).then(res=> res.json())
    .then(data =>{
      console.log(data)
      if (data != false) {
        data.forEach(element => {
          var newrow = document.createElement('tr')
          newrow.setAttribute("id",element['id'])
          
          var newpid = document.createElement('td')
          newpid.innerText = element['PractitionerID']
          newrow.appendChild(newpid)

          var newusername = document.createElement('td')
          newusername.innerText = element['UserName']
          newrow.appendChild(newusername)

          var newfullname = document.createElement('td')
          newfullname.innerText = element['FirstName']+' '+element['LastName']
          newrow.appendChild(newfullname)

          var newtoken = document.createElement('td')
          newtoken.innerText = element['Token']
          newrow.appendChild(newtoken)

          var newLastLoginTime = document.createElement('td')
          newLastLoginTime.innerText = element['LastLoginTime']
          newrow.appendChild(newLastLoginTime)

          var newaction = document.createElement('td')
          var newactionbutton = document.createElement('button')
          newactionbutton.innerText = 'Force Log out'
          newactionbutton.setAttribute("class",'deleteSession')

          newaction.appendChild(newactionbutton)
          newrow.appendChild(newaction)
                    
          sessionTable.appendChild(newrow)
        });

        // forceLogOut button for admin
        var logoutbuttons = document.getElementsByClassName('deleteSession')
        console.log(logoutbuttons)

        // binding each button a correct function
        for (let index = 0; index < logoutbuttons.length; index++) {
          const button = logoutbuttons[index];
          button.onclick = function(event) {

            console.log(event.target.parentNode.parentNode.id)
            fetch('request.php',{
              method:'post',
              body: JSON.stringify({
                "Type":"Session",
                "Action":"Delete",
                "StatusID":event.target.parentNode.parentNode.id,
            })
              }).then(res=> res.text())
              .then(data => {
                if (data != false) {
                  refreshSes();
                  alert("Success")
                  
                }else{
                  alert("connection failed")
                }
              });
          }
        }

      }else{
        alert('Database internal error')

      }
    }

    );
}

refreshSes();


// When the user clicks the button, open the modal 
SessionTab.onclick = function() {
    sessionModal.style.display = "block";

}