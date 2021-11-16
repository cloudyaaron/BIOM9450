<?php

// This page has been setup as a RESTFUL API PAGE

    // stop any direct visit
    if(empty($_SERVER['HTTP_REFERER'])){
        header("refresh:0; url=main.php");
        http_response_code(403);

    // setup api
    }else{

        header('Content-type: application/json');
        $_POST = json_decode(file_get_contents('php://input'), true);
        http_response_code(200);

        // Get medical database data
        $conn = odbc_connect("ass",'','',SQL_CUR_USE_ODBC);

        // report a error if connection failed
        if(!$conn){
            echo "<div class='wr'>Internal Unkown Error, Plz contact us about this issue</div>";
        }

        // Medication API
        if ($_POST['Type'] == 'Medications'){

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
                $mID = $_POST['MedicationID'];
                // indicates a brand new terms 
                if ($_POST['MedicationID'] == "") {
                    $sql_insert = "INSERT INTO [Medications]
                    (`MedicationName`,`Prescription`,`Description`) VALUES ('$mn', $mp,'$md')";
                    $result = odbc_exec($conn,$sql_insert) or die(odbc_errormsg());
                } elseif($_POST['MedicationID']){
                    $sql_update = "UPDATE [Medications]
                    (`MedicationName`,`Prescription`,`Description`) VALUES ('$mn', $mp,'$md') 
                    WHERE  `MedicationID` = $mID";
                    $result = odbc_exec($conn,$sql_update) or die(odbc_errormsg());
                }
                print(json_encode( $_POST ));

            // If deleted
            }elseif ($_POST['Action']=="Delete") {
                $mID = intval($_POST['MedicationID']);

                // delete by id
                if ($_POST['MedicationID'] == "") {

                    $sql_delete = "DELETE FROM [Medications] WHERE `MedicationID` = $mID";
                    $result = odbc_exec($conn,$sql_delete) or die(odbc_errormsg());
                }
                print(json_encode( $_POST ));

            }

        }elseif($_POST['Type'] == 'Patients'){
            print(json_encode(  array("Type"=>"DietRegime") ));
        }else{
            http_response_code(400);

            print(json_encode( array("Type"=>"Sorry but we dont have time to finish other stuff") ));

        }
        
        odbc_close($conn);
    }


?>