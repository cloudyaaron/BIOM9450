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
                $dbpath = "C:/Users/Aaron/Desktop/BIOM9450/BIOM9450/ASS/Ass.accdb";
                // $dbpath = "C:/Users/Aaron/Desktop/BIOM9450/BIOM9450/ASS/Database1.mdb";
                $conn = odbc_connect("Driver={Microsoft Access Driver (*.mdb, *.accdb)};dbq=$dbpath",'','',SQL_CUR_USE_ODBC);
                
                // report a error if connection failed
                if(!$conn){
                    echo "<div class='wr'>Internal Unkown Error, Plz contact us about this issue</div>";
                }
                
                $sql_query = "SELECT * FROM Practitioner";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                while(odbc_fetch_row($result)){
                    $FN = odbc_result($result,"FirstName");
                    $LN = odbc_result($result,"LastName");
                    echo "<div class='firstcol'>$FN $LN</div>";

                }
                odbc_close($conn);

                // generates a token
                $t = openssl_random_pseudo_bytes(16);
                $t = bin2hex($t);
                setcookie("token",$t);

            // else request can be either direct visit or contain a token
            } else{

                // check if user has a token
                if (!empty($_COOKIE["token"]) ) {
                    $valid = true;

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
                header("refresh:3; url=index.html");

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