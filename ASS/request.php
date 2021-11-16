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

        if ($_POST['Type'] == 'Medications'){

            // get medication data
            $sql_query = "SELECT * FROM [Medications]";
            $result = odbc_exec($conn,$sql_query) or die(odbc_errormsg());

            print(json_encode(  array("Type"=>"Medications") ));

        }elseif($_POST['Type'] == 'Patients'){
            print(json_encode(  array("Type"=>"DietRegime") ));
        }else{
            http_response_code(400);

            print(json_encode( array("Type"=>"Not a thing") ));

        }
        
        odbc_close($conn);
    }


?>