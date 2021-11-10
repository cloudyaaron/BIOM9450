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
    <div id="headnav"></div> 

    <!-- Infomations about the event, location and abstract needed -->
    <div class="main">
        <?php
            $valid = false;

            // if the request is noraml log in to the page
            if (!empty($_POST['username']) && !empty($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                echo "username:\n";
                echo "$username \n";
                echo "password:\n";
                echo "$password \n";

                $valid = true;
                setcookie("token","aRandomToken");

            // else request can be either direct visit or contain a token
            } else{

                // check if user has a token
                if (!empty($_COOKIE["token"]) ) {
                    # code...
                    echo "valid from token";
                    $valid = true;

                // invalid visit show error
                }else{
                    $valid = false;
                }
            }

            // show valid page, main page
            if ($valid) {
                # code...

            // show reject info
            }else{
                echo "<div class='wr'>invalid</div>";
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