// adding listener for each input box which can give user feedback immedietly
// better than make user off focus the input
var email = document.getElementById("email")
email.addEventListener("keyup", EmailChecker)

var password = document.getElementById("password")
password.addEventListener("keyup", Passwordchecker)

var firstname = document.getElementById("firstname")
firstname.addEventListener("keyup", FirstnameChecker)

var lastname = document.getElementById("lastname")
lastname.addEventListener("keyup", LastnameChecker)

var dob = document.getElementById("dob")
dob.addEventListener("keyup", DobChecker)

// indication of each term status, all true means: ready to send this to backend.
var em = false;
var pas = false;
var repas = false;
var fn = false;
var ln = false;
var db = false;
var ma = true;



// email checker check email is valid and give feed back in real time
function EmailChecker() {

    // get highlight element
    const emailhighlight = document.getElementById("emailHL")

    // not ok if nothing in input
    if ((email.value.trim()).length == 0) {
        emailhighlight.innerText = "Email can not be empty"
        emailhighlight.className = "wr"
        em = false
    } else {

        // validate email in right regex
        if (ValidateEmail(email.value) == true) {

            // give feedback in correct style
            emailhighlight.innerText = "No errors"
            emailhighlight.className = "co"
            em = true;
        } else {
            emailhighlight.innerText = "Email Address is invalid"
            emailhighlight.className = "wr"
            em = false
        }
    }

}

//check the password field in real time
function Passwordchecker() {
    
    // get highlight element
    var passwordhighlight = document.getElementById("passwordHL")

    // password need to be longer than 8
    if (password.value.length < 8) {
        passwordhighlight.innerText = "Password should at least longer than 8 char"
        passwordhighlight.className = "wr"
        pas = false

    // validate password in correct regex
    } else if (!ValidatePassword(password.value)) {

        passwordhighlight.innerText = "Password have to include both uppercase, lowercase and numbers"
        passwordhighlight.className = "wr"
        pas = false
    } else {
        passwordhighlight.innerText = "No errors"
        passwordhighlight.className = "co"
        pas = true
    }
}

// check the confirm password field on off focus that input.
function RepasswordChecker(repassword) {

    // console.log(repassword)
    var repasswordhighlight = document.getElementById("repasswordHL")
    var password = document.getElementById("password")

    if (repassword.value.length == 0) {

        repasswordhighlight.innerText = "Confirm Passwords can not be empty"
        repasswordhighlight.className = "wr"
        repas = false


    } else if (password.value != repassword.value) {
        repasswordhighlight.innerText = "Passwords must match"
        repasswordhighlight.className = "wr"
        repas = false
    } else {

        repasswordhighlight.innerText = "No errors"
        repasswordhighlight.className = "co"
        repas = true
    }
}

// check firstname
function FirstnameChecker() {
    var firstnamehightlight = document.getElementById("firstnameHL")
    if (firstname.value.length == 0) {
        firstnamehightlight.innerText = "First name can not be empty"
        firstnamehightlight.className = "wr"
        fn = false
    } else if (!ValidateName(firstname.value)) {
        firstnamehightlight.innerText = "First name should contain only letters,apostrophes,spaces and hyphens"
        firstnamehightlight.className = "wr"
        fn = false
    } else {
        firstnamehightlight.innerText = "No errors"
        firstnamehightlight.className = "co"
        fn = true
    }
}

// check lastname
function LastnameChecker() {
    var lastnamehightlight = document.getElementById("lastnameHL")
    if (lastname.value.length == 0) {
        lastnamehightlight.innerText = "Last name can not be empty"
        lastnamehightlight.className = "wr"
        ln = false
    } else if (!ValidateName(lastname.value)) {
        lastnamehightlight.innerText = "Last name should contain only letters,apostrophes,spaces and hyphens"
        lastnamehightlight.className = "wr"
        ln = false

    } else {
        lastnamehightlight.innerText = "No errors"
        lastnamehightlight.className = "co"
        ln = true
    }
}


// check dob
function DobChecker() {
    var dobhightlight = document.getElementById("dobHL")
    if (dob.value.length == 0) {
        dobhightlight.innerText = "Date of birth can not be empty"
        dobhightlight.className = "wr"
        db = false

    } else if (!ValidateDob(dob.value)) {
        dobhightlight.innerText = "Invalid DOB"
        dobhightlight.className = "wr"
        db = false

    } else {
        dobhightlight.innerText = "No errors"
        dobhightlight.className = "co"
        db = true
    }
}

// check everything before submit
function validInfo() {
    DobChecker()
    FirstnameChecker()
    LastnameChecker()
    EmailChecker()
    Passwordchecker()
    RepasswordChecker()
    if (em == false || pas == false || repas == false || fn == false || ln == false || db == false) {
        alert("Somthing wrong with provided detail, Please check highlighted area")
        return false
    }
    return true
}


// Check of DOB not by regex,(date picker is probably better)
// return error number
function ValidateDob(d) {
    var dat = d.split("/")
    console.log(dat)
    console.log(dat[0])
    if (dat.length != 3) {
        return false
    } if (dat[0].trim().length == 0 || dat[0].trim().length > 2 || dat[1].trim().length == 0 || dat[1].trim().length > 2 || dat[2].trim().length != 4) {
        return false
    }
    if (parseInt(dat[0].trim()) <= 0 || parseInt(dat[0].trim()) > 31 || parseInt(dat[1].trim()) <= 0 || parseInt(dat[1].trim()) > 12 || parseInt(dat[2].trim()) > 2021) {
        return false

    }
    if (parseInt(dat[0].trim()) > 29 && parseInt(dat[1].trim()) == 2) {
        return false
    }

    return true;
}

// Check if name follow regex as required
function ValidateName(name) {
    var re = /(^[A-Za-z \-']+$)/g;
    return re.test(String(name))
}

// check if passord follow regex
function ValidatePassword(pass) {

    // checking if both number,lower, uppercase in the password
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/g
    return re.test(String(pass))
}

// valid the email by regex notation
function ValidateEmail(email) {

    // /means the regex start and end, 
    const re = /^([0-9a-zA-Z.-]+)@([0-9a-zA-Z.-]+)\.([a-zA-Z]{2,4})$/g
    return re.test(String(email).toLowerCase());
}

function canc() {
    window.location.href("index.html")
}