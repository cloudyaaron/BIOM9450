<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Client-side Form Valdiation using JavaScript</title>

<script type="text/JavaScript">

	function validInfo(){
			
		// Copy HTML form element objects to javascript objects	
		var givenName = document.getElementById('givenName');
		var familyName = document.getElementById('familyName');
		var dob = document.getElementById('dob');
		var email = document.getElementById('email');
				
		//If all this is true then the form is valid...
		if(validName(givenName,'given name')){
			if(validName(familyName,'family name')){
				if(validDateOfBirth(dob,'Date of birth')){
					if(validEmail(email,'Email address')){
						return true;
					}
				}
			}
		}
		
		//Otherwise it is not valid	
		alert('Please correct the fields highlighted in red before submitting form.')
		return false;
	}
	
	//Check that name is only alphabetic or '
	function validName(elem, helperMsg) {
		
		var str = elem.value.replace(/\s/g, '') ;

		alphaExp = /^[a-zA-Z']+$/;
		if (str.match(alphaExp)){
			//Save trimmed string for posting to php
			document.getElementById(elem.id).value = str;
			//Change error text to empty
			document.getElementById(elem.id+'Echo').innerHTML = '';
			return true;
		}else{
			//Tricky stuff! Get id from elem.id and use it to refernce the text field in HTML which ends in Echo
			document.getElementById(elem.id+'Echo').innerHTML = helperMsg + ' can only contain a-z or single quotes';
			document.getElementById(elem.id+'Echo').style.color = 'red';
			elem.focus();
			elem.select();
						
			return false;
		}
	}	
	
	function validDateOfBirth(elem,helperMsg){
	
		//Remove all spaces
		var str = elem.value.replace(/\s/g, '') ;
	
		alphaExp = /^[0-3][0-9]\/[0-1][0-9]\/[12][0-9]{3}$/;
		
		if(str.match(alphaExp)){
			//Save trimmed string for posting to php
			document.getElementById(elem.id).value = str;
			//Remove error text from page
			document.getElementById(elem.id+'Echo').innerHTML = '';
			return true;
		}else{
			//Tricky stuff! Get id from elem.id and use it to refernce the text field in HTML which ends in Echo
			document.getElementById(elem.id+'Echo').innerHTML = helperMsg + ' must be in the format dd/mm/yyyy';
			document.getElementById(elem.id+'Echo').style.color = 'red';
			elem.focus();
			elem.select();
						
			return false;	
		}
	
	}
		
	function validEmail(elem,helperMsg){
	
		//Remove all spaces at start and end only!!
		var str = elem.value.replace(/^\s|\s$/g, '') ;
	
		//Got this from the web. RegExp for email addresses
		alphaExp = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
		if(str.match(alphaExp)){
			document.getElementById(elem.id).value = str;
			document.getElementById(elem.id+'Echo').innerHTML = '';
			return true;
		}else{
			//Tricky stuff! Get id from elem.id and use it to refernce the text field in HTML which ends in Echo
			document.getElementById(elem.id+'Echo').innerHTML = helperMsg + ' is invalid';
			document.getElementById(elem.id+'Echo').style.color = 'red';
			elem.focus();
			elem.select();
						
			return false;	
		}
	
	}
	

</script>


</head>

<body>

	<form id="registrantInfo" onSubmit="return validInfo()" method="POST" action="regSuccess.php">
    <table>
       
        <tr>
            <td>Given name:&nbsp;</td>
            <td><input type="text" id="givenName" name="givenName" value="" onChange="validName(this, 'Given name')"></td>
            <td id="givenNameEcho"></td>
        </tr>
       
        <tr>
            <td>Family name:&nbsp;</td>
            <td><input type="text" id="familyName" name="familyName" value="" onChange="validName(this, 'Family name')"></td>
            <td id="familyNameEcho"></td>
        </tr>
       
        <tr>
        	<td>Registrant type:&nbsp;</td>
            <td>
            	<select id="registrantType" name="registrantType">
                	<option>Student</option>
                    <option>Member</option>
                    <option>Non-member</option>
                </select>
            </td>
            <td id="registrantTypeEcho"></td>
        </tr>
       
        <tr>
            <td>Date of birth (dd/mm/yyyy):&nbsp;</td>
			<td><input type="text" id="dob" name="dob" maxlength="10" size="19" value="" onChange="validDateOfBirth(this, 'Date of birth')"></td>
            <td id="dobEcho"></td>
        </tr>
       
        <tr>
            <td>Email address:&nbsp;</td>
			<td><input type="text" id="email" name="email" value="" size="30" onChange="validEmail(this, 'Email address')"></td>
            <td id="emailEcho"></td>
        </tr>
        
        <tr>
			<td><input type="submit" id="submit" value="Register now!"></td>
        </tr>
        
    </table>
    </form>

</body>
</html>
