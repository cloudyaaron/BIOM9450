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

        // get pratitionerID from cookie,validate user
        $to = $_COOKIE['token'];
        $sql_query = "SELECT * FROM [LoginStatus] WHERE `Token` ='$to'";
        $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
        $currentPractitioner = -1;
        while (odbc_fetch_row($result)) {
            $currentPractitioner = odbc_result($result,"PractitionerID");
        }
        if ($currentPractitioner == -1) {
            setcookie('token', null, -1, '/'); 
            header("refresh:1; url=index.php");

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
                    $mp = "Yes";
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
        }

        // ask for any arrangement for regime or medications
        if ($_POST['Type'] == 'Arrangement') {

            http_response_code(200);
            header('Content-type: application/json');

            // return short results by date and patient
            if ($_POST['Action']=="ShortAsk") {
                $pID = intval($_POST['PatientID']);
                $date = str_replace('\\',"",$_POST['Date']);

                $answer = array(
                    "morning"=>array(),
                    "afternoon"=>array(),
                    "evening"=>array(),
                );
                if (!$pID) {
                    print(json_encode( $answer));
                }else{

                    $round = array("","morning","afternoon","evening");
                    for ($i=1; $i <= 3; $i++) { 

                        // first get regime
                        $sql_query = "SELECT * FROM [DietRegimeRecords]
                        INNER JOIN [DietRegime] ON
                        DietRegimeRecords.RegimeID = DietRegime.RegimeID
                        WHERE `PatientID` = $pID AND `Day` = #$date#
                        AND `RoundID`= $i";
                        $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                        // $answer = array();    
                        while (odbc_fetch_row($result)) {
                            $term = array();
                            $dr = odbc_result($result,'RegimeName');
                            $term['name']=$dr;
                            $term['type']='regime';
                            $answer[$round[$i]][] = $term;
                        }

                        // then get medications
                        $sql_query = "SELECT * FROM [MedicationRecords]
                        INNER JOIN [Medications] ON
                        MedicationRecords.MedicationID = Medications.MedicationID
                        WHERE `PatientID` = $pID AND `Day` = #$date#
                        AND `RoundID`= $i";
                        $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                        // $answer = array();    
                        while (odbc_fetch_row($result)) {
                            $term = array();
                            $dr = odbc_result($result,'MedicationName');
                            $term['name']=$dr;
                            $term['type']='medication';
                            $answer[$round[$i]][] = $term;
                        }
                    }

                    print(json_encode( $answer ));
                }
                //

            }

            // return all results by date and patient
            if ($_POST['Action']=="Ask") {
                $answer = array(
                    'Regime' =>array(),
                    'Medication'=>array()
                );

                $pID = intval($_POST['PatientID']);
                $date = str_replace('\\',"",$_POST['Date']);

                // first get regime
                $sql_query = "SELECT * FROM (([DietRegimeRecords]
                INNER JOIN [DietRegime] ON
                DietRegimeRecords.RegimeID = DietRegime.RegimeID)
                INNER JOIN [Status] ON
                DietRegimeRecords.StatusID = Status.StatusID)
                INNER JOIN [Round] ON
                DietRegimeRecords.RoundID = Round.RoundID
                WHERE `PatientID` = $pID AND `Day` = #$date#
                ORDER BY DietRegimeRecords.RoundID";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                // $answer = array();    
                while (odbc_fetch_row($result)) {
                    $term = array();
                    $drid = odbc_result($result,'DietRegimeRecordsID');
                    $dr = odbc_result($result,'RegimeName');
                    $drr = odbc_result($result,'RoundName');
                    $drs = odbc_result($result,'StatusName');
                    $drsid = odbc_result($result,'StatusID');

                    $term['id']=$drid;
                    $term['name']=$dr;
                    $term['status']=$drs;
                    $term['statusid']=$drsid;

                    $term['round']=$drr;

                    $answer['Regime'][] = $term;
                }

                // then get medications
                $sql_query = "SELECT * FROM (([MedicationRecords]
                INNER JOIN [Medications] ON
                MedicationRecords.MedicationID = Medications.MedicationID)
                INNER JOIN [Status] ON
                MedicationRecords.StatusID = Status.StatusID)
                INNER JOIN [Round] ON
                MedicationRecords.RoundID = Round.RoundID
                WHERE `PatientID` = $pID AND `Day` = #$date#
                ORDER BY MedicationRecords.RoundID";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                // $answer = array();    
                while (odbc_fetch_row($result)) {
                    $term = array();
                    $mrid = odbc_result($result,'MedicationRecordsID');
                    $mn = odbc_result($result,'MedicationName');
                    $drr = odbc_result($result,'RoundName');
                    $drs = odbc_result($result,'StatusName');
                    $drsid = odbc_result($result,'StatusID');
                    $dosage = odbc_result($result,'dosage');

                    $term['id']=$mrid;
                    $term['name']=$mn;
                    $term['status']=$drs;
                    $term['statusid']=$drsid;
                    $term['dosage']=$dosage;

                    $term['round']=$drr;

                    $answer['Medication'][] = $term;
                }
                print(json_encode( $answer ));

            }

            // handle regime add process
            if ($_POST['Action']=="AddRegime") {
                $pID = intval($_POST['PatientID']);
                $RecordID = intval($_POST['RecordID']);
                $StatusID = intval($_POST['StatusID']);
                $date = $_POST['Date'];
                $today = date("m/d/Y",time());
                // if update
                if ($_POST['RecordID']) {
                    $sql_update = "UPDATE [DietRegimeRecords]
                    SET `StatusID` = $StatusID, `LastModifiedDate` = #$today#,
                    `PractitionerID` = $currentPractitioner
                    WHERE  `DietRegimeRecordsID` = $RecordID";
                    $result = odbc_exec($conn,$sql_update) or die(odbc_errormsg());

                // if insert
                }else{
                    $RegimeID = intval($_POST['RegimeID']);
                    $RoundID = intval($_POST['RoundID']);

                    $sql_insert = "INSERT INTO [DietRegimeRecords]
                    (`Day`,`RoundID`,`RegimeID`,`StatusID`,`PatientID`,`PractitionerID`,`LastModifiedDate`)
                    VALUES (#$date#,$RoundID,$RegimeID,$StatusID,$pID,$currentPractitioner,#$today#)";
                    $result = odbc_exec($conn,$sql_insert) or die(odbc_errormsg());
                }

                print(json_encode( $_POST ));
            }

            // handle medication add process
            if ($_POST['Action']=="AddMedication") {
                $pID = intval($_POST['PatientID']);
                $RecordID = intval($_POST['RecordID']);
                $StatusID = intval($_POST['StatusID']);
                $dosage = intval($_POST['Dosage']);
                $date = $_POST['Date'];
                $today = date("m/d/Y",time());
                // if update
                // print(json_encode( $_POST ));

                if ($_POST['RecordID']) {
                    $sql_update = "UPDATE [MedicationRecords]
                    SET `StatusID` = $StatusID, `LastModifiedDate` = #$today#,
                    `PractitionerID` = $currentPractitioner
                    WHERE  `MedicationRecordsID` = $RecordID";
                    $result = odbc_exec($conn,$sql_update) or die(odbc_errormsg());

                // if insert
                }else{
                    $TermID = intval($_POST['MedicationID']);
                    $RoundID = intval($_POST['RoundID']);

                    $sql_insert = "INSERT INTO [MedicationRecords]
                    (`Day`,`RoundID`,`MedicationID`,`StatusID`,`PatientID`,`PractitionerID`,`Dosage`,`LastModifiedDate`)
                    VALUES (#$date#,$RoundID,$TermID,$StatusID,$pID,$currentPractitioner,$dosage,#$today#)";
                    $result = odbc_exec($conn,$sql_insert) or die(odbc_errormsg());
                }

                print(json_encode( $_POST ));
            }

            // handle medication deletion process
            if ($_POST['Action']=="DeleteMedication") {
                $RecordID = intval($_POST['RecordID']);

                // delete by id
                $sql_delete = "DELETE FROM [MedicationRecords] WHERE `MedicationRecordsID` = $RecordID";
                $result = odbc_exec($conn,$sql_delete) or die(odbc_errormsg());
                print(json_encode( $_POST ));
            }
            // handle regime delete process
            if ($_POST['Action']=="DeleteRegime") {
                $RecordID = intval($_POST['RecordID']);

                // delete by id
                $sql_delete = "DELETE FROM [DietRegimeRecords] WHERE `DietRegimeRecordsID` = $RecordID";
                $result = odbc_exec($conn,$sql_delete) or die(odbc_errormsg());
                print(json_encode( $_POST ));
            }
            die();
        }

        // return summary data
        if ($_POST['Type'] == 'Summary') {
            http_response_code(200);
            header('Content-type: application/json');

            $startDate = $_POST['StartDate'];
            $endDate = $_POST['EndDate'];
            
            if ($_POST['Action'] == "GMWS") {
                $answer = array();
                $sql_query = "SELECT COUNT(*) AS `totalPatients` FROM (SELECT DISTINCT `patientID` FROM [DietRegimeRecords] WHERE `PractitionerID` = $currentPractitioner AND
                `Day` >= #$startDate# AND `Day` <= #$endDate#)";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                while (odbc_fetch_row($result)) {
                    $t = odbc_result($result,"totalPatients");
                    $answer["uniquePatientsRegime"][]=$t;
                }
                $sql_query = "SELECT COUNT(*) AS `total` FROM [DietRegimeRecords] WHERE `PractitionerID` = $currentPractitioner  AND
                `Day` >= #$startDate# AND `Day` <= #$endDate#";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                while (odbc_fetch_row($result)) {
                    $t = odbc_result($result,"total");
                    $answer["totalRegimes"][]=$t;
                }
                $sql_query = "SELECT COUNT(*) AS `totalPatients` FROM (SELECT DISTINCT `patientID` FROM [MedicationRecords] WHERE `PractitionerID` = $currentPractitioner AND
                `Day` >= #$startDate# AND `Day` <= #$endDate#)";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                while (odbc_fetch_row($result)) {
                    $t = odbc_result($result,"totalPatients");
                    $answer["uniquePatientsMedication"][]=$t;
                }
                $sql_query = "SELECT COUNT(*) AS `total` FROM [MedicationRecords] WHERE `PractitionerID` = $currentPractitioner AND
                `Day` >= #$startDate# AND `Day` <= #$endDate#";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                while (odbc_fetch_row($result)) {
                    $t = odbc_result($result,"total");
                    $answer["totalMedications"][]=$t;
                }

                //  get Medications table
                $sql_query = "SELECT * FROM ((([MedicationRecords]
                INNER JOIN [Medications] ON
                MedicationRecords.MedicationID = Medications.MedicationID)
                INNER JOIN [Status] ON
                MedicationRecords.StatusID = Status.StatusID)
                INNER JOIN [Round] ON
                MedicationRecords.RoundID = Round.RoundID)
                INNER JOIN [Patients] ON
                MedicationRecords.PatientID = Patients.PatientID
                WHERE `PractitionerID` = $currentPractitioner AND 
                `Day` >= #$startDate# AND `Day` <= #$endDate#
                ORDER BY Day";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                // $answer = array();    
                while (odbc_fetch_row($result)) {
                    $term = array();
                    $dr = odbc_result($result,'MedicationName');
                    $drr = odbc_result($result,'RoundName');
                    $drs = odbc_result($result,'StatusName');
                    $fn = odbc_result($result,'FirstName');
                    $ln = odbc_result($result,'LastName');
                    $d = odbc_result($result,'Dosage');
                    $day = odbc_result($result,'Day');

                    $term['name']=$dr;
                    $term['status']=$drs;
                    $term['round']=$drr;
                    $term['firstname']=$fn;
                    $term['lastname']=$ln;
                    $term['dosage']=$d;
                    $term['date']=$day;

                    $answer['Medication'][] = $term;
                }

                //  get regime table
                $sql_query = "SELECT * FROM ((([DietRegimeRecords]
                INNER JOIN [DietRegime] ON
                DietRegimeRecords.RegimeID = DietRegime.RegimeID)
                INNER JOIN [Status] ON
                DietRegimeRecords.StatusID = Status.StatusID)
                INNER JOIN [Round] ON
                DietRegimeRecords.RoundID = Round.RoundID)
                INNER JOIN [Patients] ON
                DietRegimeRecords.PatientID = Patients.PatientID
                WHERE `PractitionerID` = $currentPractitioner AND 
                `Day` >= #$startDate# AND `Day` <= #$endDate#
                ORDER BY Day";
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());
                // $answer = array();    
                while (odbc_fetch_row($result)) {
                    $term = array();
                    $dr = odbc_result($result,'RegimeName');
                    $drr = odbc_result($result,'RoundName');
                    $drs = odbc_result($result,'StatusName');
                    $fn = odbc_result($result,'FirstName');
                    $ln = odbc_result($result,'LastName');
                    $day = odbc_result($result,'Day');

                    $term['name']=$dr;
                    $term['status']=$drs;
                    $term['round']=$drr;
                    $term['firstname']=$fn;
                    $term['lastname']=$ln;
                    $term['date']=$day;

                    $answer['Regime'][] = $term;
                }
                print(json_encode($answer));
            }
            if ($_POST['Action'] == "GPWS") {

                
                $pID = intval($_POST['PatientID']);
                $answer = array(
                    "Regime" => array(),
                    "Medication" => array(),
                );
                $sql_query = "SELECT * FROM ((([DietRegimeRecords]
                INNER JOIN [DietRegime] ON
                DietRegimeRecords.RegimeID = DietRegime.RegimeID)
                INNER JOIN [Status] ON
                DietRegimeRecords.StatusID = Status.StatusID)
                INNER JOIN [Round] ON
                DietRegimeRecords.RoundID = Round.RoundID)
                INNER JOIN [Patients] ON
                DietRegimeRecords.PatientID = Patients.PatientID
                WHERE DietRegimeRecords.PatientID = $pID  AND 
                `Day` >= #$startDate# AND `Day` <= #$endDate#
                ORDER BY Day";

                
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());


                while (odbc_fetch_row($result)) {
                    $regname = odbc_result($result,"RegimeName");
                    $Protein = odbc_result($result,"Protein");
                    $Fat = odbc_result($result,"Fat");
                    $Carbs = odbc_result($result,"Carbs");
                    $Sugar = odbc_result($result,"Sugar");
                    $Sodium = odbc_result($result,"Sodium");
                    $Fibre = odbc_result($result,"Fibre");
                    $SN = odbc_result($result,"StatusName");
                    $RN = odbc_result($result,"RoundName");
                    $day = odbc_result($result,"Day");
                    $regterm = array(
                        "RegimeName" => $regname,
                        "Protein" => $Protein,
                        "Fat" => $Fat,
                        "Carbs" => $Carbs,
                        "Sugar" => $Sugar,
                        "Sodium" => $Sodium,
                        "Fibre" => $Fibre,
                        "Status" => $SN,
                        "Round" => $RN,
                        "Date" => $day,
                    );
                    $answer['Regime'][] = $regterm;
                }

                $sql_query = "SELECT * FROM ((([MedicationRecords]
                INNER JOIN [Medications] ON
                MedicationRecords.MedicationID = Medications.MedicationID)
                INNER JOIN [Status] ON
                MedicationRecords.StatusID = Status.StatusID)
                INNER JOIN [Round] ON
                MedicationRecords.RoundID = Round.RoundID)
                INNER JOIN [Patients] ON
                MedicationRecords.PatientID = Patients.PatientID
                WHERE MedicationRecords.PatientID = $pID  AND 
                `Day` >= #$startDate# AND `Day` <= #$endDate#
                ORDER BY Day";
                
                $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());

                while (odbc_fetch_row($result)) {
                    $medname = odbc_result($result,"MedicationName");
                    $SN = odbc_result($result,"StatusName");
                    $RN = odbc_result($result,"RoundName");
                    $day = odbc_result($result,"Day");
                    $do  = odbc_result($result,"Dosage");
                    $medterm = array(
                        "MedicationName" => $medname,
                        "Status" => $SN,
                        "Round" => $RN,
                        "Date" => $day,
                        "Dosage" => $do,
                    );
                    $answer['Medication'][] = $medterm;
                }

                print(json_encode( $answer));

            }
        
        }else{
            http_response_code(400);

            print(json_encode( array("Type"=>"Sorry but we dont have time to finish other stuff") ));

        }
        
        // odbc_close($conn);
    }


?>