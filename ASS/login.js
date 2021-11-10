// the function that control login and submitting the form
function login(params) {
    alert("yo")
    // document.cookie = "token=yoyoyo;Expires=Thu, 01 Dec 2021 00:00:01 GMT;Path=/;";
    let form = document.getElementById("loginform");
    form.action = "main.php";
    form.method = "POST";
    form.onsubmit();
}