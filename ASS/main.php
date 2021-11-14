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

    <!-- Infomations about the event, location and abstract needed -->
    <div class="main">
        <?php

            function validToken($t){
                echo "Text from a function";
            }

            $valid = false;

            // if the request is noraml log in to the page
            if (!empty($_POST['username']) && !empty($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $valid = true;


                // validate user detail with database
                $conn = odbc_connect("ass",'','',SQL_CUR_USE_ODBC);

                // report a error if connection failed
                if(!$conn){
                    echo "<div class='wr'>Internal Unkown Error, Plz contact us about this issue</div>";
                }
                
                // Check if user exist
                $sql_query = "SELECT * FROM Practitioner WHERE `UserName` = '$username'";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                $result_array = odbc_fetch_array($result);

                // User not exist, this should not give user info for safty consideration
                if (!$result_array) {
                    $valid = false;
                    
                // user Exist
                }else{

                    // Generate a token if everthing is valid
                    if ($result_array['UserName'] == $username && $result_array['Password'] == $password) {
                        // generates a token
                        $t = openssl_random_pseudo_bytes(16);
                        $t = bin2hex($t);
                        setcookie("token",$t);

                        // Registrate token in database
                        $practionerID = $result_array['PractitionerID'];
                        $d = strtotime("now .GMT-9");
                        $now = date("Y-m-d h:i:sa",$d);

                        // check if current database has token
                        // replace with the new token
                        $sql_query = "SELECT * FROM [LoginStatus] WHERE `PractitionerID` = $practionerID";
                        $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                        $result_array = odbc_fetch_array($result);

                        // if no token
                        if (!$result_array) {
                            $sql_insert = "INSERT INTO [LoginStatus] (`PractitionerID`,`Token`,`LastLoginTime`) VALUES('$practionerID','$t','$now')";
                            $result = odbc_exec($conn,$sql_insert) or die(odbc_errormsg());

                        // if token exist then update
                        }else{
                            $sql_delete = "DELETE FROM [LoginStatus] WHERE `PractitionerID` = $practionerID";
                            $result = odbc_exec($conn,$sql_delete) or die(odbc_errormsg());

                            $sql_insert = "INSERT INTO [LoginStatus] (`PractitionerID`,`Token`,`LastLoginTime`) VALUES('$practionerID','$t','$now')";
                            $result = odbc_exec($conn,$sql_insert) or die(odbc_errormsg());
                        }
 
                    // incorrect password
                    }else{
                        $valid = false;
                    }
                }

                odbc_close($conn);


            // else request can be either direct visit or contain a token
            } else{

                // check if user has a token
                if (!empty($_COOKIE["token"]) ) {
                    $t = $_COOKIE['token'];
                    // validate user detail with database
                    $conn = odbc_connect("ass",'','',SQL_CUR_USE_ODBC);
        
                    // report a error if connection failed
                    if(!$conn){
                        echo "<div class='wr'>Internal Unkown Error, Plz contact us about this issue</div>";
                    }
        
                    // Check if user login sesssion exist
                    $sql_query = "SELECT * FROM [LoginStatus] INNER JOIN [Practitioner] 
                    ON LoginStatus.PractitionerID = Practitioner.PractitionerID 
                    WHERE `Token` = '$t'";
                    $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                    $result_array = odbc_fetch_array($result);
                    odbc_close($conn); 
                    
                    if ($t == $result_array['Token']) {
                        $valid = true;
                    }
                // invalid visit show error
                }else{
                    $valid = false;
                }
            }

            // show valid page, main page
            if ($valid) {
                # code...
                echo "<div id='headnav'></div>"; 



            // show reject info
            }else{
                echo "
                    <div class='title'>
                    <img src='ServiceUNSW.png' alt='Service UNSW' width='40' height='40' align='left'>
                    <h1 align='left' > Medication and Diet Regime Management System  </h1>
                    <div align='right' class='cite'>powered by ServiceUNSW</div>
                    </div>"
                    ;
                echo "<div class='wr'>User token invalid</div>";
                echo "<div class='wr'>Check login session status detail</div>";
                echo "<div class='focus'><b>You will back at login page in a 3 seconds<b></div>";
                header("refresh:3; url=index.php");

            }




        ?>
        <div>
            

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
<script src="header.js"></script>

</html>