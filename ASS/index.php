<!DOCTYPE html>
<!-- declare it's a HTML5 file -->



<html>

<!-- declare head favicon,and css sheet used -->

<head>
    <link rel="icon" type="image/png" href="ServiceUNSW.png" />
    <link rel="stylesheet" href="MyOwnUglyCss.CSS">
    <title> ServiceUNSW</title>

</head>


<!-- the page start at here -->


<body>

    <!-- create the title and logo -->
    <div class="title">
        <img src="ServiceUNSW.png" alt="Service UNSW" width="40" height="40" align="left">
        <h1 align="left" > Medication and Diet Regime Management System  </h1>
        <div align="right" class="cite">powered by ServiceUNSW</div>
    </div>

    <?php

        if (!empty($_COOKIE['token'])) {

            $t = $_COOKIE['token'];
            // validate user detail with database
            $conn = odbc_connect("ass",'','',SQL_CUR_USE_ODBC);

            // report a error if connection failed
            if(!$conn){
                echo "<div class='wr'>Internal Unkown Error, Plz contact us about this issue</div>";
            }

            // Check if user exist
            $sql_query = "SELECT * FROM [LoginStatus] INNER JOIN [Practitioner] 
            ON LoginStatus.PractitionerID = Practitioner.PractitionerID 
            WHERE `Token` = '$t'";
            $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
            $result_array = odbc_fetch_array($result);
            odbc_close($conn); 
            if (empty($result_array)) {
                setcookie('token', null, -1, '/'); 

            }else{
                $u = $result_array['UserName'];
                if ($t == $result_array['Token']) {
                    header("refresh:0; url=main.php");
                }
            }
        }
    ?>

    
    <!-- login page -->
    <div class="main">
        <div>

            <hr>
            <div class="focus">
                <h1 align="center">
                    Medication and Diet Regime Management System (Employee Only)
               </h1>

               <!-- form but post by button -->
                <form id="loginform" >
                    <p>
                        <div class="r2c">
                            <div class="colright">
                                <span>Username</span>
                                <input type="text" name="username" id="username" placeholder="Enter your username">
                            </div>
                            <div class="colleft" id="usernameHL">
                                <span></span>
                            </div>
                        </div>
                        <br>
                        <div class="r2c">
                            <div class="colright">
                                <span>Password</span>
                                <input type="password" name="password" id="password" placeholder="Enter your password">
                            </div>
                            <div id="passwordHL">
                                <span></span>
                            </div>
                        </div>
                        <br>
                    </p>
                    <div>
                        <button onclick="login()" >Login</button>
                    </div>
                </form>
            </div>
            <hr>

        </div>
    </div>
    <hr>

    <!-- A footer that give some cool contact info for user -->
    <div class="bottomtag">
        <a>Only Qualified ServiceUNSW Employee are allowed to access this site  ||  </a>
        <a> <b>Customer Services:</b> </a>
        <a href="tel:12-34-56" class="plain">12-34-56</a>
    </div>

</body>

<script src="login.js"></script>

</html>