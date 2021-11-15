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
    <div class='main'>

    <?php
        $valid = false;

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
            if ($t == $result_array['Token']) {
                $L = $result_array['Level'];
                $valid = true;
                $user = $result_array['UserName'];
         
            }else{
                $valid = false;
            }  
        } else{
            $valid = false;
        }

        // if token exist
        if ($valid) {
            // <!-- create the title and logo -->
            echo "<div id='headnav'></div>"; 

            // manage page, for user to add and edit data
            echo "
                <h1>Database Management</h1>
                <div align='left' class='citetext'> Welcom back --<b>$user</b> Your Level is <b>$L</b></div>

                <hr>"
                ;

            // if user is manager or admin
            if($L >= 2){
                echo "<div class='grid-container'>";
                echo "
                    <div id='medications' class='grid-item'>
                        Medications
                        <!-- The Modal -->
                        <div id='medicationsModal' class='modal'>

                        <!-- Modal content -->
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h4>Medications Database</h4>
                                </div>
                                <div class='modal-body'>
                                    <input list='meds'>
                                    <datalist id='meds'>"
                                    ;
                                
                // Get medical database data
                $conn = odbc_connect("ass",'','',SQL_CUR_USE_ODBC);

                // report a error if connection failed
                if(!$conn){
                    echo "<div class='wr'>Internal Unkown Error, Plz contact us about this issue</div>";
                }

                // get medication data
                $sql_query = "SELECT * FROM [Medications]";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());

                while(odbc_fetch_row($result)){
                    $medname = odbc_result($result,"MedicationName");
                    $pres = odbc_result($result,"Prescription");
                    $des = odbc_result($result,"Description");
                    echo "<option value='$medname'></option>";
                }

                echo "</datalist>";
                echo "
                                </div>
                            </div>
                        </div>
                    </div>";
                odbc_close($conn); 

                echo "
                    <div class='grid-item'>
                        Diet Regieme
                    </div>
                    <div class='grid-item'>
                        Patients
                    </div>
                    <div class='grid-item'>
                        Food Types
                    </div>
                    <div class='grid-item'>
                        Record Status
                    </div>
                    <div class='grid-item'>
                        Round time
                    </div>";
                if($L >= 3){
                    echo "                    
                    <div class='grid-item'>
                        <div class='tooltip'>
                            User
                        <span class='tooltiptext'>level 3 Exclusive</span>
                        </div>
                    </div>
                        <div class='grid-item'>
                            <div class='tooltip'>
                                login session
                                <span class='tooltiptext'>level 3 Exclusive</span>
                            </div>
                        </div>
                    </div>";
                } else{
                    echo"</div>";
                }


            }else{
                echo "<div class='wr'>
                This page is for Level 2 or aboved ONLY
                </div>";
            }
            


        // if direct visit or token lost, then return user to login page
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
    </div>

    <!-- A footer that give some cool contact info for user -->
    <div class="bottomtag">
        <a>Only Qualified ServiceUNSW Employee are allowed to access this site  ||  </a>
        <a> <b>Customer Services:</b> </a>
        <a href="tel:12-34-56" class="plain">12-34-56</a>
    </div>

</body>
<script src="header.js"></script>
<script src="manage.js"></script>

</html>