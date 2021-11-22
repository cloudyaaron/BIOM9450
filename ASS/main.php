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
                        $user = $result_array['UserName'];
                        $L = $result_array['Level'];
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
                    
                    if (empty($result_array)) {
                        setcookie('token', null, -1, '/'); 
                        $valid = false;

                    }else{
                        if ($t == $result_array['Token']) {
                            $valid = true;
                            $user = $result_array['UserName'];
                            $L = $result_array['Level'];

                        }
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
                echo "<div>";
                echo "<hr>";
                echo "<div align='left' style='float:right;' class='citetext'> Welcom back --<b>$user</b> Your Level is <b>$L</b></div>";

                echo "<div align='left' style='float:left;'>Patients ID</div>";
                echo "<input id='currentP' list='plist' size=50 placeholder='Searching for Paient by ID or Name'>
                        <datalist id='plist'>"
                ;

                echo "</datalist>";
                echo "<button id='getPatient'> GET </button>";
                echo "</div>";
                
                echo "<hr>";

                echo "<div class='row'>
                <div class='column left' style='background-color:#aaa;'>
                
                    <h2 id='patientName'></h2>
                    <hr>
                    <div>
                    <h3> Details</h3>
                        <b>Patient ID: </b><b id='patientID'> </b>
                        <br>
                        <img id='pImage' src='Patients/Mrs Sally Smith.jpg' alt='Mrs Sally Smith' width='80' height='120' >
                        <br>
                        <b>Gender: </b><p id='patientGender'> Female </p>
                        <b>Age: </b><p id='patientAge'> 86 </p>
                        <b>Description: </b><p id='patientDes'> From Sydney </p>

                    </div>


                </div>
                <div class='column right' style='background-color:#bbb;'>
                    <div >
                        <div >
                            <h2>Arrangement</h2>
                        </div>
                        <div style='float:right;'>
                            <input id='pickedDate' type='date'>

                        </div>
                    </div>";
                echo"<br>";

                echo"<hr>";
                echo "<table id='calendar' class='arrangetable'>";
                $start = date('y/m/d');
                
                echo "<tr>";
                    
                for ($i=0; $i < 7; $i++) { 
                    $cdate = date('m/d l',mktime(0, 0, 0, date("m")  , date("d")+$i, date("Y")));
                    echo "<th>";
                    echo "<table id='table $i' class='trigger'>
                        <tr>
                            <th>
                                $cdate
                            </th>
                        </tr>
                    </table>

                    ";
                    echo"<!-- The Modal -->
                    <div id='modal $i' class='modal'>

                    <!-- Modal content -->
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h4 id='modal title $cdate'>$cdate</h4>
                            </div>
                            <div class='modal-body'>
                                <span>Patients ID<span>
                                <input id='currentPatient' list='patient' placeholder='Searching'>
                                <datalist id='patient'> </datalist>
                                <button align='right' id='getPatient'>GET</button>
                                <div align='right' class='citetext' style='float:right'>
                                    Current database has 
                                    <span id='totalPatient'>  </span> entries 
                                    <button id='addPatient' align='right'>Add NEW</button>
                                </div>

                                <hr>

                                <!-- the editor panel start here--------->
                                <div>
                                    <span>Patient Name</span>
                                    <br>
                                    <input type='text' id='patientName' size='50' disabled=true>
                                    <br>
                                    <br>

                                    <span>Medication</span>
                                    <br>
                                    <input type='text' id='medicationsPrescription' size='50' disabled=true>
                                    <br>
                                    <br>

                                    <span>Diet Regime</span>
                                    <br>
                                    <input type='text' id='dietRegime' size='50' disabled=true>
                                    <br>
                                    <br>

                                    <span>Details</span>
                                    <br>
                                    <textarea rows='5' cols='100' id='patientDetails' disabled=false></textarea>

                                    <hr>

                                    <div>
                                        <div align='left' style='float:left;'>
                                            <button id='editMed' disabled>EDIT</button>
                                        </div>
                                        <div align='right' style='float:left;'>
                                            <button id='saveMed' disabled>SAVE</button>
                                        </div>
                                        <div align='right' style='float:right;'>
                                            <button id='deleteMed' disabled>DELETE</button>
                                        </div>
                                        <div align='right' style='float:left;'>
                                            <button id='cancelMed' disabled>Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";
                    echo "</th>";
                }
                echo "
                    </tr>


                </table>";
              echo "</div>";

            // show reject info
            }else{
                echo "
                    <div class='title'>
                    <img src='ServiceUNSW.png' alt='Service UNSW' width='40' height='40' align='left'>
                    <h1 align='left' > Medication and Diet Regime Management System  </h1>
                    <div align='right' class='citetext'>powered by ServiceUNSW</div>
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
    

    <!-- A footer that give some cool contact info for user -->
    <div class="bottomtag">
        <a>Only Qualified ServiceUNSW Employee are allowed to access this site  ||  </a>
        <a> <b>Customer Services:</b> </a>
        <a href="tel:12-34-56" class="plain">12-34-56</a>
    </div>

</body>
<script src="header.js"></script>
<script src="main.js"></script>

</html>