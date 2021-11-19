<?php

// This page has been setup as a RESTFUL API PAGE

    // stop any direct visit
    if(empty($_SERVER['HTTP_REFERER'])){
        header("refresh:0; url=main.php");
        http_response_code(403);

    // setup api
    }else{

        $_POST = json_decode(file_get_contents('php://input'), true);


        // Get medical database data
        $conn = odbc_connect("ass",'','',SQL_CUR_USE_ODBC);

        // report a error if connection failed
        if(!$conn){
            echo "<div class='wr'>Internal Unkown Error, Plz contact us about this issue</div>";
        }

        // Medication API
        if ($_POST['Type'] == 'Medications'){
            http_response_code(200);
            header('Content-type: application/json');
            $sql_query = "SELECT * FROM [Medications]";
            $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());

            // if add or update
            if ($_POST['Action']=="Save") {
                $mn = $_POST['MedicationName'];
                $mp = "No";
                if ($_POST['Prescription'] == 1) {
                    $mp = "yes";
                }

                $md = $_POST['Description'];
                $mID = intval( $_POST['MedicationID']);
                // indicates a brand new terms 
                if ($_POST['MedicationID'] == "") {
                    $sql_insert = "INSERT INTO [Medications]
                    (`MedicationName`,`Prescription`,`Description`) VALUES ('$mn', $mp,'$md')";
                    $result = odbc_exec($conn,$sql_insert) or die(odbc_errormsg());
                } elseif($_POST['MedicationID']){
                    $sql_update = "UPDATE [Medications]
                    SET `MedicationName` = '$mn',`Prescription` = $mp,`Description` = '$md' 
                    WHERE  `MedicationID` = $mID";
                    $result = odbc_exec($conn,$sql_update) or die(odbc_errormsg());
                }
                print(json_encode( $_POST ));

            // If deleted
            }
            if ($_POST['Action']=="Delete") {
                $mID = intval($_POST['MedicationID']);

                // delete by id
                $sql_delete = "DELETE FROM [Medications] WHERE `MedicationID` = $mID";
                $result = odbc_exec($conn,$sql_delete) or die(odbc_errormsg());
                
                print(json_encode( $_POST ));

            }

            // if ask for a entry detail
            if($_POST['Action']=="Ask"){
                $mID = $_POST['MedicationID'];
                $sql_query = "SELECT * FROM [Medications] WHERE `MedicationID` = $mID";
                
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                $r = odbc_fetch_array($result);
                print(json_encode( $r ));

            }

            // if ask all entry
            if($_POST['Action']=="ALL"){
                $sql_query = "SELECT * FROM [Medications]";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                $r = array();
                while (odbc_fetch_row($result)) {
                    $medid = odbc_result($result,"MedicationID");
                    $medname = odbc_result($result,"MedicationName");
                    $medprescription = odbc_result($result,"Prescription");
                    $meddescription = odbc_result($result,"Description");
                    $term = array(
                        "id" => $medid,
                        "MedicationName" => $medname,
                        "Prescription" => $medprescription,
                        "Description" => $meddescription,
                    );
                    $r[] = $term;

                }
                print(json_encode( $r ));

            }
            die();
        }
        if($_POST['Type'] == 'Regimes'){
            http_response_code(200);
            header('Content-type: application/json');

            // if ask all entry
            if($_POST['Action']=="ALL"){
                $sql_query = "SELECT * FROM [DietRegime]";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                $r1 = array();
                while (odbc_fetch_row($result)) {
                    $regid = odbc_result($result,"RegimeID");
                    $regname = odbc_result($result,"RegimeName");
                    $regdescription = odbc_result($result,"Description");
                    $Protein = odbc_result($result,"Protein");
                    $Fat = odbc_result($result,"Fat");
                    $Carbs = odbc_result($result,"Carbs");
                    $Sugar = odbc_result($result,"Sugar");
                    $Sodium = odbc_result($result,"Sodium");
                    $Fibre = odbc_result($result,"Fibre");

                    $regterm = array(
                        "id" => $regid,
                        "RegimeName" => $regname,
                        "Description" => $regdescription,
                        "Protein" => $Protein,
                        "Fat" => $Fat,
                        "Carbs" => $Carbs,
                        "Sugar" => $Sugar,
                        "Sodium" => $Sodium,
                        "Fibre" => $Fibre,

                    );
                    $r1[] = $regterm;
                }
                print(json_encode( $r1));
            }

            // if ask for a entry detail
            if($_POST['Action']=="Ask"){
                $rID = $_POST['RegimeID'];
                $sql_query = "SELECT * FROM [DietRegime] WHERE `RegimeID` = $rID";
                
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                $r = odbc_fetch_array($result);
                print(json_encode( $r ));

            }

            // if add or update
            if ($_POST['Action']=="Save") {
                $rn = $_POST['RegimeName'];

                $rID = intval( $_POST['RegimeID']);
                $rd = $_POST['Description'];

                $Protein_t = intval( $_POST['Protein']);
                $Fat_t = intval( $_POST['Fat']);
                $Carbs_t = intval( $_POST['Carbs']);
                $Sugar_t = intval( $_POST['Sugar']);
                $Sodium_t = intval( $_POST['Sodium']);
                $Fibre_t = intval( $_POST['Fibre']);

                // indicates a brand new terms 
                if ($_POST['RegimeID'] == "") {
                    $sql_insert = "INSERT INTO [DietRegime]
                    (`RegimeName`,`Description`,`Protein`,`Fat`,`Carbs`,`Sugar`,`Sodium`,`Fibre`)
                    VALUES ('$rn','$rd',$Protein_t,$Fat_t,$Carbs_t,$Sugar_t,$Sodium_t,$Fibre_t)";
                    $result = odbc_exec($conn,$sql_insert) or die(odbc_errormsg());
                } elseif($_POST['RegimeID']){
                    $sql_update = "UPDATE [DietRegime]
                    SET `RegimeName` = '$rn',`Description` = '$rd',`Protein` = $Protein_t,`Fat` = $Fat_t
                    ,`Carbs` = $Carbs_t,`Sugar` = $Sugar_t,`Sodium` =$Sodium_t,`Fibre` = $Fibre_t
                    WHERE  `RegimeID` = $rID";
                    $result = odbc_exec($conn,$sql_update) or die(odbc_errormsg());
                }
                print(json_encode( $_POST ));

            }

            // If deleted
            if ($_POST['Action']=="Delete") {
                $mID = intval($_POST['RegimeID']);

                // delete by id
                $sql_delete = "DELETE FROM [DietRegime] WHERE `RegimeID` = $mID";
                $result = odbc_exec($conn,$sql_delete) or die(odbc_errormsg());
                
                print(json_encode( $_POST ));

            }
            die();
        }
        if($_POST['Type'] == 'Patients'){
            http_response_code(200);
            header('Content-type: application/json');

            // if ask all entry
            if($_POST['Action']=="ALL"){
                $sql_query = "SELECT * FROM [Patients]";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                $r1 = array();
                while (odbc_fetch_row($result)) {
                    $PatientID = odbc_result($result,"PatientID");
                    $FirstName = odbc_result($result,"FirstName");
                    $LastName = odbc_result($result,"LastName");
                    $Age = odbc_result($result,"Age");
                    $Gender = odbc_result($result,"Gender");
                    $Description = odbc_result($result,"Description");
                    $Photo = odbc_result($result,"Photo");

                    $patterm = array(
                        "id" => $PatientID,
                        "FirstName" => $FirstName,
                        "LastName" => $LastName,
                        "Age" => $Age,
                        "Gender" => $Gender,
                        "Description" => $Description,
                        "Photo" => $Photo,

                    );
                    $r2[] = $patterm;
                }
                print(json_encode( $r2));
            }


            // if ask for a entry detail
            if($_POST['Action']=="Ask"){
                $pID = $_POST['PatientID'];
                $sql_query = "SELECT * FROM [Patients] WHERE `PatientID` = $pID";
                
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                $r = odbc_fetch_array($result);
                print(json_encode( $r ));

            }

            // if add or update
            if ($_POST['Action']=="Save") {
                $ln = $_POST['LastName'];
                $fn = $_POST['FirstName'];

                $pID = intval( $_POST['PatientID']);
                $pd = $_POST['Description'];

                $Age = intval($_POST['Age']);
                $Gender = $_POST['Gender'];
                print(json_encode( $_POST ));


                // indicates a brand new terms 
                if ($_POST['PatientID'] == "") {
                    $sql_insert = "INSERT INTO [Patients]
                    (`FirstName`,`LastName`,`Age`,`Gender`,`Description`)
                    VALUES ('$fn','$ln',$Age,'$Gender','$pd')";
                    $result = odbc_exec($conn,$sql_insert) or die(odbc_errormsg());
                } elseif($_POST['PatientID']){
                    $sql_update = "UPDATE [Patients]
                    SET `FirstName` = '$fn',`LastName` = '$ln',
                    `Age` = $Age,`Gender` = '$Gender',`Description` = '$pd'
                    WHERE  `PatientID` = $pID";
                    $result = odbc_exec($conn,$sql_update) or die(odbc_errormsg());
                }
                print(json_encode( $_POST ));

            }

            // If deleted
            if ($_POST['Action']=="Delete") {
                $pID = intval($_POST['PatientID']);

                // delete by id
                $sql_delete = "DELETE FROM [Patients] WHERE `PatientID` = $pID";
                $result = odbc_exec($conn,$sql_delete) or die(odbc_errormsg());
                
                print(json_encode( $_POST ));

            }

            die();
        }
        if ($_POST['Type'] == 'Session') {

            http_response_code(200);
            header('Content-type: application/json');

            // if ask all entry
            if($_POST['Action']=="ALL"){
                $sql_query = "SELECT StatusID, LoginStatus.PractitionerID, Token, LastLoginTime,UserName, FirstName,LastName 
                FROM [LoginStatus] INNER JOIN [Practitioner] ON LoginStatus.PractitionerID = Practitioner.PractitionerID";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                $r1 = array();
                while (odbc_fetch_row($result)) {
                    $StatusID = odbc_result($result,"StatusID");

                    $PractitionerID = odbc_result($result,"PractitionerID");
                    $FirstName = odbc_result($result,"FirstName");
                    $LastName = odbc_result($result,"LastName");
                    $Token = odbc_result($result,"Token");
                    $LastLoginTime = odbc_result($result,"LastLoginTime");
                    $UserName = odbc_result($result,"UserName");


                    $sesterm = array(
                        "id" => $StatusID,
                        "PractitionerID" => $PractitionerID,

                        "FirstName" => $FirstName,
                        "LastName" => $LastName,
                        "Token" => $Token,
                        "LastLoginTime" => $LastLoginTime,
                        "UserName" => $UserName,

                    );
                    $r2[] = $sesterm;
                }
                print(json_encode( $r2));
            }

            // If deleted
            if ($_POST['Action']=="Delete") {
                $sID = intval($_POST['StatusID']);

                // delete by id
                $sql_delete = "DELETE FROM [LoginStatus] WHERE `StatusID` = $sID";
                $result = odbc_exec($conn,$sql_delete) or die(odbc_errormsg());
                
                print(json_encode( $_POST ));

            }

            die();
        
        }else{
            http_response_code(400);

            print(json_encode( array("Type"=>"Sorry but we dont have time to finish other stuff") ));

        }
        
        // odbc_close($conn);
    }


?>