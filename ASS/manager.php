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

            if (empty($result_array)) {
                setcookie('token', null, -1, '/'); 
                $valid = false;

            }else{
                if ($t == $result_array['Token']) {
                    $L = $result_array['Level'];
                    $valid = true;
                    $user = $result_array['UserName'];
                }else{
                    $valid = false;
                }  
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

                // medications----------------------------------------------------
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
                                    <span>Medications ID<span>
                                    <input id='currentMed' list='meds' placeholder='Searching'>
                                    <datalist id='meds'> </datalist>
                                    <button align='right' id='getMedication'>GET</button>
                                    <div align='right' class='citetext' style='float:right'>
                                        Current database has 
                                        <span id='totalMed'>  </span> entries 
                                        <button id='addMedication' align='right'>Add NEW</button>
                                    </div>

                                    <hr>

                                    <!-- the editor panel start here--------->
                                    <div>
                                        <span>Medications Name</span>
                                        <br>
                                        <input type='text' id='madicationsName' size='50' disabled=true>
                                        <br>
                                        <br>

                                        <span>Prescription</span>
                                        <br>
                                        <input type='checkbox' id='medicationsPrescription' disabled=true>
                                        <br>
                                        <br>

                                        <span>Description</span>
                                        <br>
                                        <textarea rows='5' cols='100' id='madicationsDescription' disabled=false></textarea>

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
                        </div>
                    </div>";


                // Regime----------------------------------------------------
                echo "
                    <div id='Regime' class='grid-item'>
                        Diet regime
                        <!-- The Modal -->
                        <div id='regimeModal' class='modal'>

                        <!-- Modal content -->
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h4>Diet regime Database</h4>
                                </div>
                                <div class='modal-body'>
                                    <span>regime ID<span>
                                    <input id='currentReg' list='regs' placeholder='Searching'>
                                    <datalist id='regs'> </datalist>
                                    <button align='right' id='getRegime'>GET</button>
                                    <div align='right' class='citetext' style='float:right'>
                                        Current database has 
                                        <span id='totalRegime'>  </span> Reigiemes
                                        <button id='addRegime' align='right'>Add NEW</button>
                                    </div>

                                    <hr>

                                    <!-- the editor panel start here--------->
                                    <div>
                                        <span>Reigiemes Name</span>
                                        <br>
                                        <input type='text' id='RegimesName' size='50' disabled=true>
                                        <br>
                                        <br>
                                        <div class='grid-container'>
                                            <div class='grid-item-s'>
                                                <span>Protein</span>
                                                <br>
                                                <input type='number' id='Protein' disabled=true>
                                            </div>
                                            <div class='grid-item-s'>
                                                <span>Fat</span>
                                                <br>
                                                <input type='number' id='Fat' disabled=true>
                                            </div>
                                            <div class='grid-item-s'>
                                                <span>Carbs</span>
                                                <br>
                                                <input type='number' id='Carbs' disabled=true>
                                            </div>
                                            <div class='grid-item-s'>
                                                <span>Sugar</span>
                                                <br>
                                                <input type='number' id='Sugar' disabled=true>
                                            </div>
                                            <div class='grid-item-s'>
                                                <span>Sodium</span>
                                                <br>
                                                <input type='number' id='Sodium' disabled=true>
                                            </div>
                                            <div class='grid-item-s'>
                                                <span>Fibre</span>
                                                <br>
                                                <input type='number' id='Fibre' disabled=true>
                                            </div>
                                        </div>
                                        <span>Description</span>
                                        <br>
                                        <textarea rows='5' cols='100' id='RegimesDescription' disabled=false></textarea>

                                        <hr>

                                        <div>
                                            <div align='left' style='float:left;'>
                                                <button id='editReg' disabled>EDIT</button>
                                            </div>
                                            <div align='right' style='float:left;'>
                                                <button id='saveReg' disabled>SAVE</button>
                                            </div>
                                            <div align='right' style='float:right;'>
                                                <button id='deleteReg' disabled>DELETE</button>
                                            </div>
                                            <div align='right' style='float:left;'>
                                                <button id='cancelReg' disabled>Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>";


                    // Patient----------------------------------------------------
                    echo "
                        <div id='Patient' class='grid-item'>
                            Patients
                            <!-- The Modal -->
                            <div id='patientModal' class='modal'>
    
                            <!-- Modal content -->
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h4>Patients Database</h4>
                                    </div>
                                    <div class='modal-body'>
                                        <span>Patient ID<span>
                                        <input id='currentPat' list='pats' placeholder='Searching'>
                                        <datalist id='pats'> </datalist>
                                        <button align='right' id='getPatient'>GET</button>
                                        <div align='right' class='citetext' style='float:right'>
                                            Current database has 
                                            <span id='totalPat'>  </span> Patients
                                            <button id='addPatient' align='right'>Add NEW</button>
                                        </div>
    
                                        <hr>
    
                                        <!-- the editor panel start here--------->
                                        <div class='row'>
                                            <div class='column left-s'>
                                                <span>First Name</span>
                                                <br>
                                                <input type='text' id='firstName' size='20' disabled=true>
                                                <br>

                                                <span>Last Name</span>
                                                <br>
                                                <input type='text' id='lastName' size='20' disabled=true>
                                                <br>
                                            </div>

                                            <div class='column right-s'>
                                                <img id='pImage' src='ServiceUNSW.png' alt='Service UNSW' width='200' height='200' >
                                            </div>
                                        </div>
                                            <div class='grid-container'>
                                                <div class='grid-item-s'>
                                                    <span>Age</span>
                                                    <br>
                                                    <input type='number' id='Age' disabled=true>
                                                </div>
                                                <div class='grid-item-s'>
                                                    <span>Gender</span>
                                                    <br>
                                                    <select id=Gender disabled>
                                                        <option value='Male'>
                                                            Male
                                                        </option>
                                                        <option value='Female'>
                                                            Female
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <span>Description</span>
                                            <br>
                                            <textarea rows='5' cols='100' id='PatientDescription' disabled=false></textarea>
    
                                            <hr>
    
                                            <div>
                                                <div align='left' style='float:left;'>
                                                    <button id='editPat' disabled>EDIT</button>
                                                </div>
                                                <div align='right' style='float:left;'>
                                                    <button id='savePat' disabled>SAVE</button>
                                                </div>
                                                <div align='right' style='float:right;'>
                                                    <button id='deletePat' disabled>DELETE</button>
                                                </div>
                                                <div align='right' style='float:left;'>
                                                    <button id='cancelPat' disabled>Cancel</button>
                                                </div>
                                            </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>";
                if($L >= 3){
                    echo "                    
                        <div class='grid-item'>
                            <div class='tooltip'>
                                Practitioner
                            <span class='tooltiptext'>No need to implement as declared in documentation</span>
                            </div>
                        </div>
                        <div  id='Session' class='grid-item'>
                            <div class='tooltip'>
                                login session
                                <span class='tooltiptext'>level 3 Exclusive</span>

                                <!-- The Modal -->
                                <div id='sessionModal' class='modal'>
        
                                    <!-- Modal content -->
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h4>Current login session</h4>
                                            </div>
                                            <div class='modal-body'>
                                                <table id='sessionTable' class='fulltable'>
                                                    <tr>
                                                        <th>
                                                            PID
                                                        </th>
                                                        <th>
                                                            User Name
                                                        </th>
                                                        <th>
                                                            Full Name
                                                        </th>
                                                        <th>
                                                            Token
                                                        </th>
                                                        <th>
                                                            Valid From
                                                        </th>
                                                        <th>
                                                            Action
                                                        </th>
                                                    </tr>

                                                </table>
                                            </div>
                                        </div>
                                </div>
                                
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

<script src="https://code.jquery.com/jquery-3.5.0.js"></script>

<script src="header.js"></script>
<script src="medicationManage.js"></script>
<script src="RegimeManage.js"></script>

<script src="patientManage.js"></script>
<script src="sessionManage.js"></script>

</html>