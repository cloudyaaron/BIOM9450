<!DOCTYPE html>
<html lang="en">
<head>
    <title>Service UNSW WWDC Program Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Styling the top navigation bar */
        .topnav {
            overflow: hidden;
            background-color: #333;
            color: white;
            text-align: right;
        }

            /* Styling the topnav links */
            .topnav a {
                float: left;
                display: block;
                color: #f2f2f2;
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
            }

                /* Changing color on hover */
                .topnav a:hover {
                    background-color: #ddd;
                    color: black;
                }

        /* Styling the content */
        .content {
            background-color: #ddd;
            padding: 10px;
        }

        /* Styling the footer */
        .footer {
            background-color: #f1f1f1;
            padding: 10px;
        }

        /* Styling the Table*/
        #topic {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

            #topic td, #topic th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            #topic tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            #topic tr:hover {
                background-color: #ddd;
            }

            #topic th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #04AA6D;
                color: white;
            }
    </style>
</head>

<body>
    <!--Adding content to the navigation bar-->
    <div class="topnav">
        <!--Adding the links to the required pages at the top of the page-->
        <a href="./TUT5_W6_Ian.html">About Us</a>
        <a href="./TUT5_W6_Ian_2.html">Workshop Program</a>
        <a href="./TUT5_W6_Ian.html">Contact Information</a>
        <b>Service UNSW WWDC 2022</b> <img src="logo.png" width="50" height="50" alt="Company Logo" align="right">
    </div>

    <!--PHP Code-->
    <?php
        //Connect to Database
        $conn = odbc_connect("z5161724", '', '',SQL_CUR_USE_ODBC);
        $sql = "SELECT * FROM Registration";
        $rs = odbc_exec($conn,$sql);
        if (!$conn) {
            exit("Connection Failed: ".$conn);
        }
        if (!$rs) {
            exit("Error in SQL");
        }

        //Get HTML
        $Firstname = $_GET["FirstName"];
        $Lastname = $_GET["LastName"];
        $Email = $_GET["Email"];
        $DOB = $_GET["DOB"];
        

        //Check if user is banned
        $sqlQuery = "SELECT * FROM Registration WHERE Banned=TRUE;";
		$banned = odbc_exec($conn,$sqlQuery);
        $userbanned = 0;
		if (!$banned) {exit("Error in SQL");}
        while (odbc_fetch_row($banned)) {
            $Fname = odbc_result($banned,"FirstName");
            $Lname = odbc_result($banned,"LastName");
            $Mail = odbc_result($banned,"Email");
            //echo "First Name is ".$Firstname." compared to ".$Fname;
            //echo "<br />";
            //echo "Last Name is ".$Lastname." compared to ".$Lname;
            //echo "<br />";
            //echo "Email is ".$Email." compared to ".$Mail;
            //echo "<br />";
            //echo "<br />";
            if ((strcasecmp($Firstname,$Fname)==0 && strcasecmp($Lastname,$Lname)==0) || (strcasecmp($Email,$Mail)==0)) {
                //echo "Banned match found!";
                //echo "<br />";
                //echo "<br />";
                $userbanned = 1;
            }
        }
        odbc_close($conn);

        //Check if user is repeated
        $sqlQuery = "SELECT * FROM Registration WHERE Banned=FALSE;";
		$registered = odbc_exec($conn,$sqlQuery);
        $userregistered = 0;
		if (!$registered) {exit("Error in SQL");}
        while (odbc_fetch_row($registered)) {
            $Fname = odbc_result($registered,"FirstName");
            $Lname = odbc_result($registered,"LastName");
            $Mail = odbc_result($registered,"Email");
            //echo "First Name is ".$Firstname." compared to ".$Fname;
            //echo "<br />";
            //echo "Last Name is ".$Lastname." compared to ".$Lname;
            //echo "<br />";
            //echo "Email is ".$Email." compared to ".$Mail;
            //echo "<br />";
            //echo "<br />";
            if ((strcasecmp($Firstname,$Fname)==0 && strcasecmp($Lastname,$Lname)==0) || (strcasecmp($Email,$Mail)==0)) {
                //echo "Repeating match found!";
                //echo "<br />";
                //echo "<br />";
                $userregistered = 1;
            }
        }
        odbc_close($conn);

        

        //Perform corresponding actions
        if ($userbanned == 1) {
            echo "Registration Failed!";
            echo "<br />";
            echo $Firstname." ".$Lastname."(".$Email."), you are banned from registering for this workshop!";
        } else if ($userregistered == 1) {
            echo "Duplicate Registration!";
            echo "<br />";
            echo $Firstname." ".$Lastname."(".$Email."), you have previously registered!";
        } else {
            echo "Congratulations! Your registration was successful!";
            echo "<br />";
            echo "Name: ".$Firstname." ".$Lastname;
            echo "<br />";
            echo "DOB: xx/xx/".date("Y",$DOB);
            echo "<br />";
            echo "Email: ".$Email;
            echo "<br />";
        }

        echo "<br />";
        //Put Registered Users in a table
        $sqlQuery = "SELECT * FROM Registration WHERE Banned=FALSE;";
		$registered = odbc_exec($conn,$sqlQuery);
		if (!$registered) {exit("Error in SQL");}
        echo "<br />";
	    echo "Registered Users";
        echo "<table><tr>";
        echo "<th>First Name</th>";
        echo "<th>Last Name</th>";
        echo "<th>Email</th></tr>";
        while (odbc_fetch_row($registered)) {
            $Fname = odbc_result($registered,"FirstName");
            $Lname = odbc_result($registered,"LastName");
            $Mail = odbc_result($registered,"Email");
            echo "<td>$Fname</td>";
            echo "<td>$Lname</td>";
            echo "<td>$Mail</td></tr>";
        }
        if ($userregistered==0 && $userbanned==0) {
            //Add new user
            echo "<td>$Firstname</td>";
            echo "<td>$Lastname</td>";
            echo "<td>$Email</td></tr>";
        }
        
        odbc_close($conn);
        echo "</table>";
        echo "<br />";



    ?>

    <!--Adding the citation for CSS format use and Logos-->
    <div class="footer">
        <p>Created with: https://www.w3schools.com/css/css_table.asp </p>
        <p>Logos are obtained from https://99designs.com.au/inspiration/logos/messaging </p>
    </div>

</body>
</html>


