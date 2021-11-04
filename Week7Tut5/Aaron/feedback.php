<!DOCTYPE>
<html>
<head>
    <link rel="icon" type="image/png" href="Aaronico.png" />
    <link rel="stylesheet" href="MyOwnUglyCss.CSS">
    <title> Workshop</title>

</head>
<body>
<div id="headnav"></div>
    
    <!-- create the title and logo -->
    <!-- create a navigation bar to allow user navigates between main and programme page -->
    <!-- move every pages into same js code for better control -->
    <div id="headnav"></div>
    <div id="profileinfo"></div>

    <!-- check user info -->
    <?php

        // open connection to database
        // $db = "Y:/xampp/htdocs/project.mdb";
        $conn = odbc_connect('z5111547', '', '',SQL_CUR_USE_ODBC);
        // $conn = odbc_connect("Driver={Microsoft Access Driver (*.mdb, *.accdb)};dbq=$db",'','',SQL_CUR_USE_ODBC);
        
        // report a error if connection failed
        if(!$conn){
            echo "<div class='wr'>Internal Unkown Error, Plz contact us about this issue</div>";
        }

        // read the post info to var
        $password = $_POST["password"];
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $Email = $_POST["email"];
        $dob = $_POST["dob"];
        $gender = $_POST["gender"];
        $market = $_POST["market"];

        // different type will create different pages,0 is missing info from previous page
        //  1 is default,2 is repeated user, 3 is banned
        $type = "1";

        if(!isset($password)||!isset($firstname)||!isset($lastname)||!isset($Email)||!isset($dob)||!isset($gender)||!isset($market) ){
            $type = "0";
        }

        // user can not direct visit this page
        // redirect to index page if user direct visit this page
        // or if post info contains NULL value
        if($type == "0"){
            header("Location: /index.html");
            exit;
        }

        // check if user registed already
        $sql_query = "SELECT * FROM Regestration";
        $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());

        // check each entry if matched in banned list
        while(odbc_fetch_row($result)){
            $LN = odbc_result($result,"LastName");
            $EM = odbc_result($result,"Email");

            // if in list set type to 2
            if((strtoupper(trim($LN)) == strtoupper(trim($lastname)) && (strtoupper(trim($FN)) == strtoupper(trim($firstname)) ) || (strtoupper(trim($EM)) == strtoupper(trim($Email)))){
                $type = "2";
            }

        }

        // compare info from database, test if user has been banned
        $sql_query = "SELECT * FROM Regestration WHERE banned = true";
        $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());

        // check each entry if matched in banned list
        while(odbc_fetch_row($result)){
            $LN = odbc_result($result,"LastName");
            $EM = odbc_result($result,"Email");

            // if in banned list set type to 3
            if((strtoupper(trim($LN)) == strtoupper(trim($lastname)) && (strtoupper(trim($FN)) == strtoupper(trim($firstname)) ) || (strtoupper(trim($EM)) == strtoupper(trim($Email)))){
                $type = "3";
            }

        }




    ?>
    <div class="main">

        <div>
            <?php

            // if register normal process
            if($type == "1"){
                // update terms in database
                $today = date('Y/m/d');
                $sql_update = "INSERT INTO [Regestration] (`FirstName`,`LastName`,`Email`,`Password`,`DOB`,`Gender`,`Market`,`DateJoined`) VALUES('$firstname','$lastname','$Email','$password','$dob','$gender',$market,'$today' )";

                $result = odbc_exec($conn,$sql_update) or die(odbc_errormsg());

                // retrieve others info
                $sql_query = "SELECT * FROM Regestration";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());

                echo "        
                <h1>
                    Congrats, you have just successful create an account with Service UNSW
                </h1>
                <h2>
                    You can now enjoy the extra features
                </h2>
                    <div class='citetext'>which is nothing, because we will work on group assignments</div>
                <hr>
                <h3>
                    Your Details:
                </h3>
                ";
                echo "<Span>  <b>Name: </b>$firstname $lastname </Span><br>";
                echo "<Span> <b> Dob:</b> $dob</Span><br>";
                echo "<Span>  <b>Email: </b>$Email</Span>";
                echo "<h2> Check Other Service UNSW Registered Users: </h2>";
                echo " <table class='fulltable'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>";

                // show all record except banned account
                while(odbc_fetch_row($result)){
                    $FN = odbc_result($result,"FirstName");
                    $LN = odbc_result($result,"LastName");
                    $EM = odbc_result($result,"Email");
                    $status = odbc_result($result,"Banned");
                    if(!$status){
                        echo "                
                        <tr>
                            <td class='firstcol'>$FN $LN</td>
                            <td>$EM</td>
                        </tr>";
                    }

                }

                echo "</tbody> </table>";
                odbc_close($conn);

            // Display error msg when user is banned
            } elseif ($type == "3") {
                echo "        
                <h1 class = 'wr'>
                    Warning, Registration Failed!
                </h1>
                <h2>
                    $firstname $lastname ($Email), you have been banned from registering for this workshop.
                </h2>
                    <div class='citetext'>Plz contact us, if you feel unfair</div>
                <hr>
                ";
            // display warning msg if user have perviouls registered
            } elseif ($type == "2") {
                // retrieve others info
                $sql_query = "SELECT * FROM Regestration";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());

                echo "        
                <h1>
                    Warning, Duplicate Registration!
                </h1>
                <h2>
                    $firstname $lastname, you have already registered!
                </h2>
                    <div class='citetext'>If you forget your detail, plz contact us through email</div>
                <hr>
                ";
                echo "<h2> Check out every Service UNSW Registered Users: </h2>";
                echo " <table class='fulltable'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>";

                // show all record except banned account
                while(odbc_fetch_row($result)){
                    $FN = odbc_result($result,"FirstName");
                    $LN = odbc_result($result,"LastName");
                    $EM = odbc_result($result,"Email");
                    $status = odbc_result($result,"Banned");
                    if(!$status){
                        echo "                
                        <tr>
                            <td class='firstcol'>$FN $LN</td>
                            <td>$EM</td>
                        </tr>";
                    }

                }
                echo "</tbody> </table>";
                odbc_close($conn);
            }

            ?>

        </div>
    </div>
    <hr>

    <!-- A footer that give some cool contact info for user -->
    <div class="bottomtag">
        <a> Workshop host at UNSW Village green at <b>14/10/2021</b></a>
        <a> <b>Call Us now:</b> </a>
        <a href="tel:12-34-56" class="plain">12-34-56</a>
        <a><b>Or Email Us:</b></a>
        <a href="mailto: nosuchaddress@fakeEmail.com" class="plain">Workshop@ServiceUNSW.com</a>
    </div>

</body>

<script src="header.js"></script>

</html>