const header = '\
<div class="title">\
    <img src="ServiceUNSW.png" alt="Service UNSW" width="40" height="40" align="left">\
    <h1 align="left"> Medication and Diet Regime Management System </h1>\
</div>\
\
<div class="navbar">\
    <div class=" tooltip" > \
        <a href="main.php" class="plain">Home</a>\
    </div >\
    <div class=" tooltip">\
        <a href="manager.php" class="plain">database management</a>\
        <span class=" tooltiptext">Only certein level stuff able to access</span>\
    </div>\
    <div class=" tooltip" > \
        <a onclick="logout()" class="plain">logout</a> \
        <span class=" tooltiptext"> Remember to logout every time</span> \
    </div >\
 </div>';

document.getElementById("headnav").innerHTML = header;

function logout() {
    // Event.preventDefault()
    var c = confirm("Are you sure to log out?");
    if(c == true){
        window.location.href="index.php";
        document.cookie = "token=; Expires=Thu, 01 Jan 1970 00:00:01 GMT;Path=/;";
    }else{
        console.log("all good")
    }
}
