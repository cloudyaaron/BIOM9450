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
        <a href="index.html" onclick="" class="plain">logout</a>\
    </div >\
 </div>';

document.getElementById("headnav").innerHTML = header;

function logout() {
    document.cookie = "token = ; expires=Thu, 01 Jan 1970 00:00:01; path=/;";
    window.location.href="index.html";
}
