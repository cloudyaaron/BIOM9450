<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Display Validated Form</title>
</head>

<body>

	
    <?php 
	
		$givenName = $_POST['givenName'];
		$familyName = $_POST['familyName'];
		$dateOfBirth = $_POST['dob'];
		$email = $_POST['email'];
		
		$fullName = strtolower($givenName." ".$familyName);
		
		switch ($fullName){
			case "johnny noshow":
			
				echo "<H2>Registration unsuccessful - no show policy</H2>";
				echo "<H3>Details:</H3>";
			
				echo "Dear Mr. Noshow, last year you didn't turn up to present your talk. You cannot register this year!";
				break;
			default:		
		
				echo "<H2>Congratulations your registration was successful</H2>";
				echo "<H3>Details:</H3>";
		
				echo "Name: ".$givenName." ";
				echo $familyName."<br>";
				
				echo "Date of birth: ";
				$dateOfBirth[0]="x";
				$dateOfBirth[1]="x";
				$dateOfBirth[3]="x";
				$dateOfBirth[4]="x";
				
				echo $dateOfBirth."<br>";
				
				echo "Email address: ";
				echo $email."<br>";
				
				echo "You will receive a confirmation email shortly <br/>";
				
				$to = $email;
				$subject = "Successful registration";
				$message = "Hello! Your registration was successful.";
				$from = "someonelse@example.com";
				$headers = "From:" . $from;
				mail($to,$subject,$message,$headers);
				
				echo "Mail Sent.";
			
		}
	?>

</body>
</html>
